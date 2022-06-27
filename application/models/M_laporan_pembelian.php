<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class M_laporan_pembelian extends CI_Model 
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
		
		public function getDetail($cari='', $offset=0, $limit='', $order='', $filter)
		{
			// defenisikan table berdasarkan jenis transaksi
			$table1	= $filter['jenis_transaksi'] == 'pembelian' ? 'pembelian' : 'retur_pembelian';
			$table2	= $table1 . '_produk';
			
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			
			// filter pencarian
			if($filter['jenis_pajak'])	$this->db->where_in('jenis_ppn', $filter['jenis_pajak']);
			if($filter['supplier'])		$this->db->where('supplier', $filter['supplier']);
			if($filter['barang'])		$this->db->where('kode_barang', $filter['barang']);
			if($filter['mata_uang'])	$this->db->where('mata_uang', $filter['mata_uang']);
			
			// operasi untuk kolom tertentu
			$jumlah	= '(harga_produk - diskon_produk) * qty_produk';
			$diskon	= ($filter['jenis_transaksi'] == 'pembelian') ? 'diskon_luar' : '0';
			$total	= 'SELECT SUM('.$jumlah.') - '.$diskon.' FROM '.$table2.' WHERE '.$table2.'.kode_transaksi = '.$table1.'.kode_transaksi';
			$dpp	= 'IF(jenis_ppn = "Include", (('.$total.') * 100 / (100 + besar_ppn)), ('.$total.'))';
			$ppn	= 'IF(jenis_ppn = "Include", (('.$total.') * besar_ppn / (100 + besar_ppn)), (('.$total.') * besar_ppn / 100))';
			$total	= 'IF(jenis_ppn = "Include", ('.$total.'), (('.$total.') * (100 + besar_ppn) / 100))';
			
			$harga	= $filter['mata_uang'] ? 'harga_produk' : 'harga_produk * konversi';
			if (!$filter['mata_uang']) {
				$jumlah	= 'ROUND('.$jumlah.', 2) * konversi';
				$dpp	= 'ROUND('.$dpp.', 2) * konversi';
				$ppn	= 'ROUND('.$ppn.', 2) * konversi';
				$total	= 'ROUND('.$total.', 2) * konversi';
				$diskon	= 'ROUND('.$diskon.', 2) * konversi';
			}
			
			return $this->db->select('*, 
								('.$jumlah.') AS jumlah_produk, 
								('.$dpp.') AS dpp, 
								('.$ppn.') AS nilai_ppn, 
								('.$total.') AS total, 
								('.$harga.') AS harga_produk, 
								('.$diskon.') AS diskon_luar 
								')
							->join('supplier', 'supplier.kode_supplier = '.$table1.'.supplier', 'left')
							->join($table2, $table2.'.kode_transaksi = '.$table1.'.kode_transaksi', 'left')
							->join('barang', $table2.'.kode_produk = barang.kode_barang', 'left')
							->where([
								'tanggal_transaksi >= ' => $filter['tanggal_awal'], 
								'tanggal_transaksi <= ' => $filter['tanggal_akhir']
								])
							->get($table1)->result_array();
		}
		
		public function countDetail($cari='', $filter)
		{
			// defenisikan table berdasarkan jenis transaksi
			$table1	= $filter['jenis_transaksi'] == 'pembelian' ? 'pembelian' : 'retur_pembelian';
			$table2	= $table1 . '_produk';
			
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari);
			}
			
			if($filter['jenis_pajak'])	$this->db->where_in('jenis_ppn', $filter['jenis_pajak']);
			if($filter['supplier'])	$this->db->where('supplier', $filter['supplier']);
			if($filter['barang'])		$this->db->where('kode_barang', $filter['barang']);
			if($filter['mata_uang'])	$this->db->where('mata_uang', $filter['mata_uang']);
			
			return $this->db->join('supplier', 'supplier.kode_supplier = '.$table1.'.supplier', 'left')
							->join($table2, $table2.'.kode_transaksi = '.$table1.'.kode_transaksi', 'left')
							->join('barang', $table2.'.kode_produk = barang.kode_barang', 'left')
							->where([
								'tanggal_transaksi >= ' => $filter['tanggal_awal'], 
								'tanggal_transaksi <= ' => $filter['tanggal_akhir']
								])
							->from($table1)->count_all_results();
		}
		
		public function getBulanan($filter, $order='')
		{
			// defenisikan table berdasarkan jenis transaksi
			$table1	= $filter['jenis_transaksi'] == 'pembelian' ? 'pembelian' : 'retur_pembelian';
			$table2	= $table1 . '_produk';
			
			if($order) $this->db->order_by($order);
			
			// filter pencarian
			if($filter['jenis_pajak'])	$this->db->where_in('jenis_ppn', $filter['jenis_pajak']);
			if($filter['supplier'])	$this->db->where('supplier', $filter['supplier']);
			if($filter['barang'])		$this->db->where('kode_barang', $filter['barang']);
			if($filter['mata_uang'])	$this->db->where('mata_uang', $filter['mata_uang']);
			
			// operasi untuk kolom tertentu
			$qty	= 'SELECT IFNULL(SUM(qty_produk), 0)';
			$jumlah	= 'SELECT IFNULL(SUM(qty_produk * (harga_produk - diskon_produk)), 0)';
			$diskon	= 'SELECT SUM(IF(kode_produk LIKE "%001", diskon_luar, 0))';
			$total	= '('.$jumlah.') - ('.$diskon.')';
			
			// data yang akan ditampilkan
			if($filter['jenis_transaksi'] == 'pembelian') {
				$this->db->select('bulan.*, 
								('.$qty.') AS qty, 
								('.$jumlah.') AS jumlah, 
								('.$diskon.') AS diskon, 
								('.$total.') AS total
							');
			} else {
				$this->db->select('bulan.*, 
								('.$qty.') AS qty, 
								('.$jumlah.') AS jumlah, 
							');
			}
			
			// main query
			return $this->db->join($table1, 'MONTH(tanggal_transaksi) = id_bulan', 'left')
							->join('supplier', 'supplier.kode_supplier = '.$table1.'.supplier', 'left')
							->join($table2, $table2 . '.kode_transaksi = '.$table1.'.kode_transaksi', 'left')
							->join('barang', $table2 . '.kode_produk = barang.kode_barang', 'left')
							->where([
								'id_bulan >= ' => date('m', strtotime($filter['tanggal_awal'])),
								'id_bulan <= ' => date('m', strtotime($filter['tanggal_akhir'])),
								])
							->group_by('id_bulan')
							->get('bulan')->result_array();
		}
		
		public function countBulanan($filter)
		{
			$table1	= $filter['jenis_transaksi'] == 'pembelian' ? 'pembelian' : 'retur_pembelian';
			$table2	= $table1 . '_produk';
			
			// filter pencarian
			if($filter['jenis_pajak'])	$this->db->where_in('jenis_ppn', $filter['jenis_pajak']);
			if($filter['supplier'])	$this->db->where('supplier', $filter['supplier']);
			if($filter['barang'])		$this->db->where('kode_barang', $filter['barang']);
			if($filter['mata_uang'])	$this->db->where('mata_uang', $filter['mata_uang']);
			
			return $this->db->select('bulan.*')
							->join($table1, 'MONTH(tanggal_transaksi) = id_bulan', 'left')
							->join('supplier', 'supplier.kode_supplier = '.$table1.'.supplier', 'left')
							->join($table2, $table2 . '.kode_transaksi = '.$table1.'.kode_transaksi', 'left')
							->join('barang', $table2 . '.kode_produk = barang.kode_barang', 'left')
							->where([
								'id_bulan >= ' => date('m', strtotime($filter['tanggal_awal'])),
								'id_bulan <= ' => date('m', strtotime($filter['tanggal_akhir'])),
								])
							->group_by('id_bulan')
							->from('bulan')->count_all_results();
		}
	}
?>
