<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Laporan_penjualan_model extends CI_Model 
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
		
		public function getDetailPenjualan($cari='', $offset=0, $limit='', $order='', $filter)
		{
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			if($filter['jenis_pajak']) $this->db->where_in('jenis_ppn', $filter['jenis_pajak']);
			if($filter['pelanggan']) $this->db->where('pelanggan', $filter['pelanggan']);
			if($filter['barang']) $this->db->where('kode_barang', $filter['barang']);
			
			$jumlah	= '(harga_produk - diskon_produk) * qty_produk';
			$total	= 'SELECT (SUM((harga_produk - diskon_produk) * qty_produk) - diskon_luar) FROM penjualan_produk WHERE penjualan_produk.kode_transaksi = penjualan.kode_transaksi';
			$dpp	= 'CASE WHEN jenis_ppn = "Include" THEN (('.$total.') * 100 / (100 + besar_ppn)) ELSE ('.$total.') END';
			$ppn	= 'CASE WHEN jenis_ppn = "Include" THEN (('.$total.') * besar_ppn / (100 + besar_ppn)) ELSE (('.$total.') * besar_ppn / 100) END';
			$total	= 'CASE WHEN jenis_ppn = "Include" THEN ('.$total.') ELSE (('.$total.') * (100 + besar_ppn) / 100) END';
			
			return $this->db->select('*, ('.$jumlah.') AS jumlah_produk, ('.$dpp.') AS dpp, ('.$ppn.') AS nilai_ppn, ('.$total.') AS total')
							->join('pelanggan', 'pelanggan.kode_pelanggan = penjualan.pelanggan', 'left')
							->join('penjualan_produk', 'penjualan_produk.kode_transaksi = penjualan.kode_transaksi', 'left')
							->join('barang', 'penjualan_produk.kode_produk = barang.kode_barang', 'left')
							->get('penjualan')->result_array();
		}
		
		public function countDetailPenjualan($cari='', $filter)
		{
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari);
			}
			if($filter['jenis_pajak']) $this->db->where_in('jenis_ppn', $filter['jenis_pajak']);
			if($filter['pelanggan']) $this->db->where('pelanggan', $filter['pelanggan']);
			if($filter['barang']) $this->db->where('kode_barang', $filter['barang']);
			
			return $this->db->join('pelanggan', 'pelanggan.kode_pelanggan = penjualan.pelanggan', 'left')
							->join('penjualan_produk', 'penjualan_produk.kode_transaksi = penjualan.kode_transaksi', 'left')
							->join('barang', 'penjualan_produk.kode_produk = barang.kode_barang', 'left')
							->from('penjualan')->count_all_results();
		}
		
		public function getDetailRetur($cari='', $offset=0, $limit='', $order='', $filter)
		{
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			if($filter['jenis_pajak']) $this->db->where_in('jenis_ppn', $filter['jenis_pajak']);
			if($filter['pelanggan']) $this->db->where('pelanggan', $filter['pelanggan']);
			if($filter['barang']) $this->db->where('kode_barang', $filter['barang']);
			
			$jumlah	= '(harga_produk - diskon_produk) * qty_produk';
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM retur_penjualan_produk WHERE retur_penjualan_produk.kode_transaksi = retur_penjualan.kode_transaksi';
			$dpp	= 'CASE WHEN jenis_ppn = "Include" THEN (('.$total.') * 100 / (100 + besar_ppn)) ELSE ('.$total.') END';
			$ppn	= 'CASE WHEN jenis_ppn = "Include" THEN (('.$total.') * besar_ppn / (100 + besar_ppn)) ELSE (('.$total.') * besar_ppn / 100) END';
			$total	= 'CASE WHEN jenis_ppn = "Include" THEN ('.$total.') ELSE (('.$total.') * (100 + besar_ppn) / 100) END';
			
			return $this->db->select('*, ('.$jumlah.') AS jumlah_produk, ('.$dpp.') AS dpp, ('.$ppn.') AS nilai_ppn, ('.$total.') AS total, "0" AS diskon_luar')
							->join('pelanggan', 'pelanggan.kode_pelanggan = retur_penjualan.pelanggan', 'left')
							->join('retur_penjualan_produk', 'retur_penjualan_produk.kode_transaksi = retur_penjualan.kode_transaksi', 'left')
							->join('barang', 'retur_penjualan_produk.kode_produk = barang.kode_barang', 'left')
							->get('retur_penjualan')->result_array();
		}
		
		public function countDetailRetur($cari='', $filter)
		{
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari);
			}
			if($filter['jenis_pajak']) $this->db->where_in('jenis_ppn', $filter['jenis_pajak']);
			if($filter['pelanggan']) $this->db->where('pelanggan', $filter['pelanggan']);
			if($filter['barang']) $this->db->where('kode_barang', $filter['barang']);
			
			return $this->db->join('pelanggan', 'pelanggan.kode_pelanggan = retur_penjualan.pelanggan', 'left')
							->join('retur_penjualan_produk', 'retur_penjualan_produk.kode_transaksi = retur_penjualan.kode_transaksi', 'left')
							->join('barang', 'retur_penjualan_produk.kode_produk = barang.kode_barang', 'left')
							->from('retur_penjualan')->count_all_results();
		}
	}
?>