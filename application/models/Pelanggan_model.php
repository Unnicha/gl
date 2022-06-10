<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Pelanggan_model extends CI_Model 
	{
		protected $db;
		
		public function __construct() 
		{
			$db_name	= $this->session->userdata('db_perusahaan');
			if($db_name) {
				$db_config	= Globals::perusahaan($db_name);
				$this->db	= $this->load->database($db_config, true);
			}
		}
		
		public function getAll($cari='', $offset=0, $limit='', $order='') 
		{
			if($cari) {
				$this->db->like('kode_pelanggan', $cari)->or_like('nama_pelanggan', $cari)
						->or_like('kode_akun', $cari)->or_like('nama', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			return $this->db->get('pelanggan')->result_array();
		}

		public function countAll($cari='') 
		{
			if($cari) {
				$this->db->like('kode_pelanggan', $cari)->or_like('nama_pelanggan', $cari)
						->or_like('kode_akun', $cari)->or_like('nama', $cari);
			}
			return $this->db->from('pelanggan')->count_all_results();
		}
		
		public function getById($id) 
		{
			return $this->db->select('pelanggan.*, akun_kas.nama_akun AS nama_kas, akun_bank.nama_akun AS nama_bank, akun_piutang.nama_akun AS nama_piutang')
							->join('akun_perkiraan AS akun_kas', 'akun_kas.kode_akun = pelanggan.akun_kas', 'left')
							->join('akun_perkiraan AS akun_bank', 'akun_bank.kode_akun = pelanggan.akun_bank', 'left')
							->join('akun_perkiraan AS akun_piutang', 'akun_piutang.kode_akun = pelanggan.akun_piutang', 'left')
							->where('kode_pelanggan', $id)
							->get('pelanggan')->row_array();
		}
		
		public function getNewKode() 
		{
			$max = $this->db->select_max('kode_pelanggan')->get('pelanggan')->row_array();
			$add = $max['kode_pelanggan'] ? substr($max['kode_pelanggan'], -3) : 0;
			return 'C' . sprintf('%03s', ++$add);
		}

		public function add() 
		{
			$kode_pelanggan	= $this->getNewKode();
			$akun_piutang	= '11.04.' . $kode_pelanggan;
			
			$data = [
				'kode_pelanggan'	=> $kode_pelanggan,
				'nama_pelanggan'	=> $this->input->post('nama_pelanggan',  true),
				'npwp'				=> $this->input->post('npwp_pelanggan', true),
				'alamat'			=> $this->input->post('alamat_pelanggan', true),
				'email'				=> $this->input->post('email_pelanggan', true),
				'telp'				=> $this->input->post('tlp_pelanggan', true),
				'fax'				=> $this->input->post('fax_pelanggan', true),
				'akun_kas'			=> $this->input->post('akun_kas', true),
				'akun_bank'			=> $this->input->post('akun_bank', true),
				'akun_piutang'		=> $akun_piutang,
			];
			$this->db->insert('pelanggan', $data);
			
			$akun = [
				'kode_akun'		=> $akun_piutang,
				'nama_akun'		=> 'Piutang ' . $this->input->post('nama_pelanggan', true),
				'golongan'		=> 'NERACA',
				'tingkat'		=> '3',
				'tipe_akun'		=> 'Anak',
				'jenis_akun'	=> '29',
				'induk'			=> '11.04',
				'saldo_normal'	=> 'Debit',
				'saldo_awal'	=> '0.00',
			];
			$this->db->insert('akun_perkiraan', $akun);
			return $this->db->affected_rows();
		}
		
		public function edit() 
		{
			$kode_pelanggan = $this->input->post('kode_pelanggan', true);
			$data = [
				'nama_pelanggan'	=> $this->input->post('nama_pelanggan',  true),
				'npwp'				=> $this->input->post('npwp_pelanggan', true),
				'alamat'			=> $this->input->post('alamat_pelanggan', true),
				'email'				=> $this->input->post('email_pelanggan', true),
				'telp'				=> $this->input->post('tlp_pelanggan', true),
				'fax'				=> $this->input->post('fax_pelanggan', true),
				'akun_kas'			=> $this->input->post('akun_kas', true),
				'akun_bank'			=> $this->input->post('akun_bank', true),
				'akun_piutang'		=> substr($this->input->post('akun_piutang', true), 0, 10),
			];
			$this->db->update('pelanggan', $data, ['kode_pelanggan' => $kode_pelanggan]);
			return $this->db->affected_rows();
		}
		
		public function delete($kode_pelanggan) 
		{
			$this->db->delete('pelanggan', ['kode_pelanggan' => $kode_pelanggan]);
			$this->db->like('kode_akun', $kode_pelanggan, 'before')->delete('akun_perkiraan');
			return $this->db->affected_rows();
		}
	}
