<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Retur_penjualan_model extends CI_Model 
	{
		protected $db;
		
		public function __construct() 
		{
			$db_name	= $this->session->userdata('db_perusahaan');
			if ($db_name) {
				$db_config	= Globals::perusahaan($db_name);
				$this->db	= $this->load->database($db_config, true);
			}
		}
		
		public function getTransaksi($cari='', $offset=0, $limit='', $order='', $bulan='') 
		{
			if ($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari);
			}
			if ($limit) $this->db->limit($limit, $offset);
			if ($order) $this->db->order_by($order);
			if ($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM retur_penjualan_produk WHERE retur_penjualan_produk.kode_transaksi = retur_penjualan.kode_transaksi';
			return $this->db->select('retur_penjualan.*, pelanggan.*, kode_asal.nama_akun AS nama_asal, kode_lawan.nama_akun AS nama_lawan, ('.$total.') AS total_produk')
							->join('pelanggan', 'pelanggan.kode_pelanggan = retur_penjualan.pelanggan', 'left')
							->join('akun_perkiraan AS kode_asal', 'retur_penjualan.akun_asal = kode_asal.kode_akun', 'left')
							->join('akun_perkiraan AS kode_lawan', 'retur_penjualan.akun_lawan = kode_lawan.kode_akun', 'left')
							->get('retur_penjualan')->result_array();
		}
		
		public function countTransaksi($cari='', $bulan='') 
		{
			if ($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari);
			}
			if ($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			return $this->db->select('retur_penjualan.*')
							->join('akun_perkiraan AS kode_asal', 'retur_penjualan.akun_asal = kode_asal.kode_akun')
							->join('akun_perkiraan AS kode_lawan', 'retur_penjualan.akun_lawan = kode_lawan.kode_akun')
							->from('retur_penjualan')->count_all_results();
		}
		
		public function getJurnal($cari='', $offset=0, $limit='', $order='', $bulan='') 
		{
			if ($cari) {
				$this->db->like('retur_penjualan.kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('kode_akun', $cari)->or_like('nama_akun', $cari);
			}
			if ($limit) $this->db->limit($limit, $offset);
			if ($order) $this->db->order_by($order);
			if ($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM retur_penjualan_produk WHERE retur_penjualan_produk.kode_transaksi = retur_penjualan.kode_transaksi';
			$produk	= 'SELECT kode_produk FROM retur_penjualan_produk WHERE retur_penjualan_produk.kode_transaksi = retur_penjualan.kode_transaksi';
			$urut	= 'CASE WHEN kode_akun = akun_asal THEN 1 WHEN kode_akun = akun_ppn THEN 3 WHEN kode_akun = akun_lawan THEN 4 ELSE 2 END';
			return $this->db->distinct()
							->select('kode_akun, nama_akun, retur_penjualan.*, ('.$total.') AS total, ('.$urut.') AS urut')
							->join('akun_perkiraan', 'kode_akun = akun_asal OR kode_akun IN ('.$produk.') OR kode_akun = akun_ppn OR kode_akun = akun_lawan', 'left')
							->order_by('urut', 'asc')
							->get('retur_penjualan')->result_array();
		}
		
		public function countJurnal($cari='', $bulan='') 
		{
			if ($cari) {
				$this->db->like('tanggal_transaksi', $cari)->or_like('retur_penjualan.kode_transaksi', $cari)
						->or_like('kode_akun', $cari)->or_like('nama_akun', $cari);
			}
			if ($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			$produk	= 'SELECT kode_produk FROM retur_penjualan_produk WHERE retur_penjualan_produk.kode_transaksi = retur_penjualan.kode_transaksi';
			return $this->db->distinct()
							->select('kode_akun, retur_penjualan.*')
							->join('akun_perkiraan', 'kode_akun = akun_asal OR kode_akun IN ('.$produk.') OR kode_akun = akun_ppn OR kode_akun = akun_lawan', 'left')
							->from('retur_penjualan')->count_all_results();
		}
		
		public function getById($id) 
		{
			return $this->db->select('*, kode_asal.nama_akun AS nama_asal, kode_lawan.nama_akun AS nama_lawan, kode_ppn.nama_akun AS nama_ppn')
							->join('akun_perkiraan AS kode_asal', 'retur_penjualan.akun_asal = kode_asal.kode_akun', 'left')
							->join('akun_perkiraan AS kode_lawan', 'retur_penjualan.akun_lawan = kode_lawan.kode_akun', 'left')
							->join('akun_perkiraan AS kode_ppn', 'retur_penjualan.akun_ppn = kode_ppn.kode_akun', 'left')
							->join('pelanggan', 'retur_penjualan.pelanggan = pelanggan.kode_pelanggan', 'left')
							->where('kode_transaksi', $id)
							->get('retur_penjualan')->row_array();
		}
		
		public function getProduk($id_transaksi) 
		{
			return $this->db->where('kode_transaksi', $id_transaksi)
							->get('retur_penjualan_produk')->result_array();
		}
		
		public function getByInvoice($faktur_retur_jual)
		{
			return $this->db->where('faktur_retur_jual', $faktur_retur_jual)
							->get('retur_penjualan')->row_array();
		}
		
		public function getNewKode() 
		{
			$pre = substr($_SESSION['tahun_aktif'], -2).$_SESSION['bulan_aktif'];
			$max = $this->db->select_max('kode_transaksi')
							->like('kode_transaksi', $pre, 'after')
							->get('retur_penjualan')->row_array();
			
			$add	= $max['kode_transaksi'] ? substr($max['kode_transaksi'], -3) : 0;
			$baru	= sprintf('%03s', ++$add);
			return $pre.'21'.$baru;
		}
		
		public function add() 
		{
			$faktur_jual	= $this->input->post('faktur_jual', true);
			$kode_transaksi	= $this->getNewKode();
			
			$retur = [
				'kode_transaksi'	=> $kode_transaksi,
				'status_jurnal'		=> 'Mutasi',
				'jenis_saldo'		=> 'Debit',
				'tanggal_transaksi'	=> Globals::dateFormat($this->input->post('tanggal', true)),
				'faktur_jual'		=> $faktur_jual,
				'faktur_retur_jual'	=> $this->input->post('faktur_retur', true),
				'surat_jalan'		=> $this->input->post('surat_jalan', true),
				'pelanggan'			=> $this->input->post('pelanggan', true),
				'mata_uang'			=> $this->input->post('mata_uang', true),
				'konversi'			=> Globals::moneyFormat($this->input->post('konversi', true)),
				'akun_asal'			=> $this->input->post('akun_asal', true),
				'akun_lawan'		=> $this->input->post('akun_lawan', true),
				'ket_transaksi'		=> $this->input->post('keterangan', true),
				'no_giro'			=> $this->input->post('no_giro', true),
				'jatuh_tempo_giro'	=> Globals::dateFormat($this->input->post('jatuh_tempo_giro', true)),
				'jenis_ppn'			=> $this->input->post('jenis_ppn', true),
				'besar_ppn'			=> $this->input->post('besar_ppn', true),
				'akun_ppn'			=> $this->input->post('akun_ppn', true),
			];
			// // insert transaksi
			$this->db->insert('retur_penjualan', $retur);
			
			// insert produk
			$produk = $this->setProduk($kode_transaksi, $faktur_jual);
			$this->db->insert_batch('retur_penjualan_produk', $produk);
			return $this->db->affected_rows();
		}
		
		public function edit() 
		{
			$kode_transaksi	= $this->input->post('kode_transaksi', true);
			$faktur_jual	= $this->input->post('faktur_jual', true);
			
			$transaksi = [
				'tanggal_transaksi'	=> Globals::dateFormat($this->input->post('tanggal', true)),
				'faktur_jual'		=> $faktur_jual,
				'faktur_retur_jual'	=> $this->input->post('faktur_retur', true),
				'surat_jalan'		=> $this->input->post('surat_jalan', true),
				'pelanggan'			=> $this->input->post('pelanggan', true),
				'akun_asal'			=> $this->input->post('akun_asal', true),
				'akun_lawan'		=> $this->input->post('akun_lawan', true),
				'ket_transaksi'		=> $this->input->post('keterangan', true),
				'no_giro'			=> $this->input->post('no_giro', true),
				'jatuh_tempo_giro'	=> Globals::dateFormat($this->input->post('jatuh_tempo_giro', true)),
			];
			// insert transaksi
			$this->db->update('retur_penjualan', $transaksi, ['kode_transaksi' => $kode_transaksi]);
			
			// hapus produk yang sudah disimpan
			$this->db->delete('retur_penjualan_produk', ['kode_transaksi' => $kode_transaksi]);
			
			// insert produk
			$produk = $this->setProduk($kode_transaksi, $faktur_jual);
			$this->db->insert_batch('retur_penjualan_produk', $produk);
			return $this->db->affected_rows();
		}
		
		public function setProduk($kode_transaksi, $faktur_jual) 
		{
			$produk	= [];
			$kode_produk = $this->input->post('kode_produk', true);
			foreach ($kode_produk as $key => $kode) {
				$id_produk	= $kode_transaksi.sprintf('%02s', $key);
				$diskon		= $this->input->post('diskon_produk', true)[$key];
				$produk[]	= [
					'id_produk'			=> $id_produk,
					'kode_produk'		=> $kode,
					'nama_produk'		=> $this->input->post('nama_produk', true)[$key],
					'qty_produk'		=> $this->input->post('qty_produk', true)[$key],
					'harga_produk'		=> $this->input->post('harga_produk', true)[$key],
					'diskon_produk'		=> $diskon ? $diskon : 0.00,
					'kode_transaksi'	=> $kode_transaksi,
					'faktur_jual'		=> $faktur_jual,
				];
			}
			
			return $produk;
		}

		public function delete($kode_transaksi) 
		{
			$table	= ['retur_penjualan', 'retur_penjualan_produk'];
			$this->db->delete($table, ['kode_transaksi' => $kode_transaksi]);
			return $this->db->affected_rows();
		}
	}
?>