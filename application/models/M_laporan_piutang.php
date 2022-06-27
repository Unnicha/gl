<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class M_laporan_piutang extends CI_Model 
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
		
		public function getPiutang($cari='', $offset=0, $limit='', $order='', $filter)
		{
			if($cari) {
				$this->db->like('kode_bayar', $cari)->or_like('tanggal_bayar', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			
			// filter
			if ($filter['akun_asal']) $this->db->where('akun_lawan', $filter['akun_asal']);
			if ($filter['mata_uang']) $this->db->where('mata_uang', $filter['mata_uang']);
			
			$produk	= 'SELECT SUM(qty_produk * (harga_produk - diskon_produk))';
			// hitung jumlah penjualan
			$jumlah	= $produk.' - diskon_luar FROM penjualan_produk WHERE penjualan_produk.kode_transaksi = penjualan.kode_transaksi';
			$jumlah	= 'IF(penjualan.jenis_ppn = "Include", ('.$jumlah.'), (('.$jumlah.') * (100 + penjualan.besar_ppn) / 100))';
			// hitung jumlah retur penjualan
			$retur	= $produk.' FROM retur_penjualan_produk WHERE retur_penjualan_produk.faktur_jual = penjualan.faktur_jual';
			$retur	= 'IF(penjualan.jenis_ppn = "Include", ('.$retur.'), (('.$retur.') * (100 + penjualan.besar_ppn) / 100))';
			// hitung jumlah pembayaran
			$bayar	= 'SELECT SUM(jumlah_bayar) FROM piutang_bayar WHERE piutang_bayar.faktur_jual = penjualan.faktur_jual';
			
			if ($filter['jenis_laporan'] == 'piutang') {
				$sisa	= '('.$jumlah.') - (IFNULL(('.$retur.'), 0)) - (IFNULL(('.$bayar.'), 0))';
				
				return $this->db->select('penjualan.*, akun_perkiraan.*, ('.$jumlah.') AS jumlah, ('.$sisa.') AS sisa')
								->join('akun_perkiraan', 'akun_perkiraan.kode_akun = penjualan.akun_lawan', 'left')
								->where([
									'jenis_pembayaran' => 'Kredit', 
									'tanggal_transaksi >= ' => $filter['tanggal_awal'], 
									'tanggal_transaksi <= ' => $filter['tanggal_akhir']
									])
								->get('penjualan')->result_array();
			} 
			else if ($filter['jenis_laporan'] == 'bayar') {
				$sisa	= '('.$jumlah.') - (IFNULL(('.$retur.'), 0))';
				
				return $this->db->select('penjualan.*, piutang.*, jumlah_bayar,  
									('.$jumlah.') AS jumlah, ('.$sisa.') AS sisa_bayar, 
									akun_asal.kode_akun AS kode_asal, akun_asal.nama_akun AS nama_asal,
									akun_lawan.kode_akun AS kode_lawan, akun_lawan.nama_akun AS nama_lawan
									')
								->join('akun_perkiraan AS akun_asal', 'akun_asal.kode_akun = penjualan.akun_asal', 'left')
								->join('piutang_bayar', 'piutang_bayar.faktur_jual = penjualan.faktur_jual', 'left')
								->join('piutang', 'piutang.kode_bayar = piutang_bayar.kode_bayar', 'left')
								->join('akun_perkiraan AS akun_lawan', 'akun_lawan.kode_akun = piutang.akun_asal', 'left')
								->where([
									'jenis_pembayaran' => 'Kredit', 
									'piutang.kode_bayar != ' => NULL,
									'tanggal_transaksi >= ' => $filter['tanggal_awal'], 
									'tanggal_transaksi <= ' => $filter['tanggal_akhir']
									])
								// ->group_by('penjualan.kode_transaksi')
								->get('penjualan')->result_array();
			}
		}
		
		public function countPiutang($cari='', $filter)
		{
			if($cari) {
				$this->db->like('kode_bayar', $cari)->or_like('tanggal_bayar', $cari);
			}
			
			// filter
			if ($filter['akun_asal']) $this->db->where('akun_lawan', $filter['akun_asal']);
			if ($filter['mata_uang']) $this->db->where('mata_uang', $filter['mata_uang']);
			
			if ($filter['jenis_laporan'] == 'piutang') {
				return $this->db->select('penjualan.*, akun_perkiraan.*')
								->join('akun_perkiraan', 'akun_perkiraan.kode_akun = penjualan.akun_lawan', 'left')
								->where([
									'jenis_pembayaran' => 'Kredit', 
									'tanggal_transaksi >= ' => $filter['tanggal_awal'], 
									'tanggal_transaksi <= ' => $filter['tanggal_akhir']
									])
								->from('penjualan')->count_all_results();
			}
			else if ($filter['jenis_laporan'] == 'bayar') {
				return $this->db->select('penjualan.*, piutang.*')
								->join('akun_perkiraan AS akun_asal', 'akun_asal.kode_akun = penjualan.akun_asal', 'left')
								->join('piutang_bayar', 'piutang_bayar.faktur_jual = penjualan.faktur_jual', 'left')
								->join('piutang', 'piutang.kode_bayar = piutang_bayar.kode_bayar', 'left')
								->join('akun_perkiraan AS akun_bayar', 'akun_bayar.kode_akun = piutang.akun_asal', 'left')
								->where([
									'jenis_pembayaran' => 'Kredit', 
									'piutang.kode_bayar != ' => NULL,
									'tanggal_transaksi >= ' => $filter['tanggal_awal'], 
									'tanggal_transaksi <= ' => $filter['tanggal_akhir']
									])
								->from('penjualan')->count_all_results();
			}
		}
	}

?>
