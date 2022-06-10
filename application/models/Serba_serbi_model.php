<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Serba_serbi_model extends CI_Model 
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
		
		public function getAll($cari='', $offset=0, $limit='', $order='', $bulan='', $tahun='') 
		{
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari)
						->or_like('ket_transaksi', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			return $this->db->select('serba_serbi.*, kode_asal.nama_akun AS nama_asal, kode_lawan.nama_akun AS nama_lawan')
							->join('akun_perkiraan AS kode_asal', 'serba_serbi.akun_asal = kode_asal.kode_akun', 'left')
							->join('akun_perkiraan AS kode_lawan', 'serba_serbi.akun_lawan = kode_lawan.kode_akun', 'left')
							->get('serba_serbi')->result_array();
		}

		public function countAll($cari='', $bulan='', $tahun='') 
		{
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('kode_asal.kode_akun', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('kode_lawan.kode_akun', $cari)->or_like('kode_lawan.nama_akun', $cari)
						->or_like('ket_transaksi', $cari);
			}
			return $this->db->select('serba_serbi.*, kode_asal.nama_akun AS nama_asal, kode_lawan.nama_akun AS nama_lawan')
							->join('akun_perkiraan AS kode_asal', 'serba_serbi.akun_asal = kode_asal.kode_akun', 'left')
							->join('akun_perkiraan AS kode_lawan', 'serba_serbi.akun_lawan = kode_lawan.kode_akun', 'left')
							->from('serba_serbi')->count_all_results();
		}

		public function getById($id) 
		{
			return $this->db->select('*, kode_asal.nama_akun AS nama_asal, kode_lawan.nama_akun AS nama_lawan')
							->join('akun_perkiraan AS kode_asal', 'serba_serbi.akun_asal = kode_asal.kode_akun', 'left')
							->join('akun_perkiraan AS kode_lawan', 'serba_serbi.akun_lawan = kode_lawan.kode_akun', 'left')
							->where('kode_transaksi', $id)
							->get('serba_serbi')->row_array();
		}
		
		public function getNewKode() 
		{
			$pre = substr($_SESSION['tahun'], -2).$_SESSION['bulan'];
			$max = $this->db->select_max('kode_transaksi')
							->like('kode_transaksi', $pre, 'after')
							->get('serba_serbi')->row_array();
			
			$add	= $max['kode_transaksi'] ? substr($max['kode_transaksi'], -3) : 0;
			$baru	= sprintf('%03s', ++$add);
			return $pre.'40'.$baru;
		}
	
		public function moneyFormat($money)
		{
			return str_replace(['.', ','], ['', '.'], $money);
		}
		
		public function dateFormat($date)
		{
			return ($date) ? date('Y-m-d', strtotime($date)) : '';
		}
		
		public function add() 
		{
			$data = [
				'kode_transaksi'	=> $this->getNewKode(),
				'status_jurnal'		=> $this->input->post('status_jurnal', true),
				'tanggal_transaksi'	=> $this->dateFormat( $this->input->post('tanggal', true) ),
				'jenis_saldo'		=> $this->input->post('jenis_saldo', true),
				'akun_asal'			=> $this->input->post('akun_asal', true),
				'akun_lawan'		=> $this->input->post('akun_lawan', true),
				'jumlah'			=> $this->moneyFormat( $this->input->post('jumlah', true) ),
				'mata_uang'			=> $this->input->post('mata_uang', true),
				'konversi'			=> $this->moneyFormat( $this->input->post('konversi', true) ),
				'ket_transaksi'		=> $this->input->post('keterangan', true),
			];
			$this->db->insert('serba_serbi', $data);
			return $this->db->affected_rows();
		}
		
		public function edit() 
		{
			$kode = $this->input->post('kode_transaksi', true);
			
			$data = [
				'status_jurnal'		=> $this->input->post('status_jurnal', true),
				'tanggal_transaksi'	=> $this->dateFormat( $this->input->post('tanggal', true) ),
				'jenis_saldo'		=> $this->input->post('jenis_saldo', true),
				'akun_asal'			=> $this->input->post('akun_asal', true),
				'akun_lawan'		=> $this->input->post('akun_lawan', true),
				'jumlah'			=> $this->moneyFormat( $this->input->post('jumlah', true) ),
				'mata_uang'			=> $this->input->post('mata_uang', true),
				'konversi'			=> $this->moneyFormat( $this->input->post('konversi', true) ),
				'ket_transaksi'		=> $this->input->post('keterangan', true),
			];
			$this->db->update('serba_serbi', $data, ['kode_transaksi' => $kode]);
			return $this->db->affected_rows();
		}

		public function delete($id) 
		{
			$this->db->delete('serba_serbi', ['kode_transaksi' => $id]);
			return $this->db->affected_rows();
		}
	}
?>