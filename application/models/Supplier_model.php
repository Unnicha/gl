<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Supplier_model extends CI_Model 
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
				$this->db->like('kode_supplier', $cari)->or_like('nama_supplier', $cari)
						->or_like('telp', $cari)->or_like('email', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			
			return $this->db->get('supplier')->result_array();
		}

		public function countAll($cari='') 
		{
			if($cari) {
				$this->db->like('kode_supplier', $cari)->or_like('nama_supplier', $cari)
						->or_like('kode_akun', $cari)->or_like('nama_akun', $cari);
			}
			
			return $this->db->from('supplier')->count_all_results();
		}
		
		public function getById($id) 
		{
			return $this->db->select('supplier.*, akun_kas.nama_akun AS nama_kas, akun_bank.nama_akun AS nama_bank, akun_utang.nama_akun AS nama_utang')
							->join('akun_perkiraan AS akun_kas', 'akun_kas.kode_akun = supplier.akun_kas', 'left')
							->join('akun_perkiraan AS akun_bank', 'akun_bank.kode_akun = supplier.akun_bank', 'left')
							->join('akun_perkiraan AS akun_utang', 'akun_utang.kode_akun = supplier.akun_utang', 'left')
							->where('kode_supplier', $id)
							->get('supplier')->row_array();
		}
		
		public function getNewKode() 
		{
			$max = $this->db->select_max('kode_supplier')->get('supplier')->row_array();
			$add = $max['kode_supplier'] ? substr($max['kode_supplier'], -3) : 0;
			return 'S' . sprintf('%03s', ++$add);
		}

		public function add() 
		{
			$data = [
				'kode_supplier'	=> $this->getNewKode(),
				'nama_supplier'	=> $this->input->post('nama_supplier',  true),
				'npwp'			=> $this->input->post('npwp_supplier', true),
				'alamat'		=> $this->input->post('alamat_supplier', true),
				'email'			=> $this->input->post('email_supplier', true),
				'telp'			=> $this->input->post('tlp_supplier', true),
				'fax'			=> $this->input->post('fax_supplier', true),
				'akun_kas'		=> $this->input->post('akun_kas', true),
				'akun_bank'		=> $this->input->post('akun_bank', true),
				'akun_utang'	=> $this->input->post('akun_utang', true),
			];
			$this->db->insert('supplier', $data);
			return $this->db->affected_rows();
		}
		
		public function edit() 
		{
			$kode = $this->input->post('kode_supplier', true);
			$data = [
				'nama_supplier'	=> $this->input->post('nama_supplier',  true),
				'npwp'			=> $this->input->post('npwp_supplier', true),
				'alamat'		=> $this->input->post('alamat_supplier', true),
				'email'			=> $this->input->post('email_supplier', true),
				'telp'			=> $this->input->post('tlp_supplier', true),
				'fax'			=> $this->input->post('fax_supplier', true),
				'akun_kas'		=> $this->input->post('akun_kas', true),
				'akun_bank'		=> $this->input->post('akun_bank', true),
				'akun_utang'	=> $this->input->post('akun_utang', true),
			];
			$this->db->update('supplier', $data, ['kode_supplier' => $kode]);
			return $this->db->affected_rows();
		}
		
		public function delete($id) 
		{
			$this->db->delete('supplier', ['kode_supplier' => $id]);
			return $this->db->affected_rows();
		}
	}
