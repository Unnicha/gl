<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class M_laporan_serba_serbi extends CI_Model 
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
		
		public function getTransaksi($cari='', $offset=0, $limit='', $order='', $filter)
		{
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			
			// filter
			if ($filter['akun_asal']) $this->db->where('akun_lawan', $filter['akun_asal']);
			if ($filter['mata_uang']) $this->db->where('mata_uang', $filter['mata_uang']);
			
			return $this->db->select('*')
							->join('akun_perkiraan', 'akun_perkiraan.kode_akun = serba_serbi.akun_lawan', 'left')
							->where([
								'tanggal_transaksi >= ' => $filter['tanggal_awal'], 
								'tanggal_transaksi <= ' => $filter['tanggal_akhir']
								])
							->get('serba_serbi')->result_array();
		}
		
		public function countTransaksi($cari='', $filter)
		{
			if($cari) {
				$this->db->like('kode_bayar', $cari)->or_like('tanggal_bayar', $cari);
			}
			
			// filter
			if ($filter['akun_asal']) $this->db->where('akun_lawan', $filter['akun_asal']);
			if ($filter['mata_uang']) $this->db->where('mata_uang', $filter['mata_uang']);
			
			return $this->db->select('*')
							->join('akun_perkiraan', 'akun_perkiraan.kode_akun = serba_serbi.akun_lawan', 'left')
							->where([
								'tanggal_transaksi >= ' => $filter['tanggal_awal'], 
								'tanggal_transaksi <= ' => $filter['tanggal_akhir']
								])
							->from('serba_serbi')->count_all_results();
		}
	}

?>
