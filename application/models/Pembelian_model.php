<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Pembelian_model extends CI_Model 
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
		
		public function getTransaksi($cari='', $offset=0, $limit='', $order='', $bulan='')
		{
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			if($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM pembelian_produk WHERE pembelian_produk.kode_transaksi = pembelian.kode_transaksi';
			return $this->db->select('pembelian.*, supplier.*, kode_asal.nama_akun AS nama_asal, kode_lawan.nama_akun AS nama_lawan, ('.$total.') AS total_produk')
							->join('supplier', 'supplier.kode_supplier = pembelian.supplier', 'left')
							->join('akun_perkiraan AS kode_asal', 'pembelian.akun_asal = kode_asal.kode_akun', 'left')
							->join('akun_perkiraan AS kode_lawan', 'pembelian.akun_lawan = kode_lawan.kode_akun', 'left')
							->get('pembelian')->result_array();
		}
		
		public function countTransaksi($cari='', $bulan='')
		{
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari);
			}
			if($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			return $this->db->select('pembelian.*')
							->join('supplier', 'supplier.kode_supplier = pembelian.supplier', 'left')
							->join('akun_perkiraan AS kode_asal', 'pembelian.akun_asal = kode_asal.kode_akun')
							->join('akun_perkiraan AS kode_lawan', 'pembelian.akun_lawan = kode_lawan.kode_akun')
							->from('pembelian')->count_all_results();
		}
		
		public function getJurnal($cari='', $offset=0, $limit='', $order='', $bulan='')
		{
			if($cari) {
				$this->db->like('pembelian.kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('kode_akun', $cari)->or_like('nama_akun', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			if($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM pembelian_produk WHERE pembelian_produk.kode_transaksi = pembelian.kode_transaksi';
			$produk	= 'SELECT kode_produk FROM pembelian_produk WHERE pembelian_produk.kode_transaksi = pembelian.kode_transaksi';
			$urut	= 'CASE WHEN kode_akun = akun_asal THEN 1 WHEN kode_akun = akun_ppn THEN 2 ELSE 3 END';
			return $this->db->distinct()
							->select('kode_akun, nama_akun, pembelian.*, ('.$total.') AS total, ('.$urut.') AS urut')
							->join('akun_perkiraan', 'kode_akun = akun_asal OR kode_akun = akun_ppn OR kode_akun = akun_lawan', 'left')
							->order_by('urut asc')
							->get('pembelian')->result_array();
		}
		
		public function countJurnal($cari='', $bulan='')
		{
			if($cari) {
				$this->db->like('tanggal_transaksi', $cari)->or_like('pembelian.kode_transaksi', $cari)
						->or_like('kode_akun', $cari)->or_like('nama_akun', $cari);
			}
			if($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			$produk	= 'SELECT kode_produk FROM pembelian_produk WHERE pembelian_produk.kode_transaksi = pembelian.kode_transaksi';
			return $this->db->distinct()
							->select('kode_akun, pembelian.*')
							->join('akun_perkiraan', 'kode_akun = akun_asal OR kode_akun IN ('.$produk.') OR kode_akun = akun_ppn OR kode_akun = akun_lawan', 'left')
							->from('pembelian')->count_all_results();
		}
		
		/**
		 * untuk menampilkan data pembelian berdasarkan Id pembelian
		 */
		public function getById($id)
		{
			return $this->db->select('*, kode_asal.nama_akun AS nama_asal, kode_lawan.nama_akun AS nama_lawan, kode_ppn.nama_akun AS nama_ppn')
							->join('akun_perkiraan AS kode_asal', 'pembelian.akun_asal = kode_asal.kode_akun', 'left')
							->join('akun_perkiraan AS kode_lawan', 'pembelian.akun_lawan = kode_lawan.kode_akun', 'left')
							->join('akun_perkiraan AS kode_ppn', 'pembelian.akun_ppn = kode_ppn.kode_akun', 'left')
							->join('supplier', 'pembelian.supplier = supplier.kode_supplier', 'left')
							->where('kode_transaksi', $id)
							->get('pembelian')->row_array();
		}
		
		public function getByPelanggan($supplier)
		{
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM pembelian_produk WHERE pembelian_produk.kode_transaksi = pembelian.kode_transaksi';
			return $this->db->select('*, ('.$total.') AS total_produk')
							->join('akun_perkiraan', 'pembelian.akun_lawan = akun_perkiraan.kode_akun', 'left')
							->where('supplier', $supplier)
							->get('pembelian')->result_array();
		}
		
		/**
		 * untuk menampilkan data pembelian berdasarkan faktur beli
		 */
		public function getByInvoice($faktur_beli)
		{
			$beli	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM pembelian_produk WHERE pembelian_produk.kode_transaksi = pembelian.kode_transaksi';
			$retur	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM retur_pembelian_produk WHERE retur_pembelian_produk.faktur_beli = pembelian.faktur_beli';
			$bayar	= 'SELECT SUM(jumlah_bayar) AS total_bayar FROM utang_bayar WHERE utang_bayar.faktur_beli = pembelian.faktur_beli';
			return $this->db->select('pembelian.*, supplier.*, ('.$beli.') AS total_produk, ('.$retur.') AS total_retur, ('.$bayar.') AS total_bayar')
							->join('supplier', 'pembelian.supplier = supplier.kode_supplier', 'left')
							->where('faktur_beli', $faktur_beli)
							->get('pembelian')->row_array();
		}
		
		public function getProduk($keyword, $field='')
		{
			if ($field == 'faktur_beli') {
				$transaksi	= $this->getByInvoice($keyword);
				$keyword	= $transaksi['kode_transaksi'];
			}
			return $this->db->get_where('pembelian_produk', ['kode_transaksi'=>$keyword])->result_array();
		}
		
		public function getProdukById($id_produk)
		{
			return $this->db->get_where('pembelian_produk', ['id_produk'=>$id_produk])->row_array();
		}
		
		/**
		 * untuk menampilkan faktur pembelian, semuanya atau berdasarkan supplier
		 */
		public function getFaktur($supplier='', $bulan='')
		{
			if($supplier) $this->db->where('supplier', $supplier);
			if($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			return $this->db->select('faktur_beli')
							->like('tanggal_transaksi', $bulan, 'after')
							->get('pembelian')->result_array();
		}
		
		public function getUtang($kode_supplier='', $mata_uang='', $saved='')
		{
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM pembelian_produk WHERE pembelian_produk.kode_transaksi = pembelian.kode_transaksi';
			return $this->db->select('pembelian.*, ('.$total.') AS total_produk')
							->join('supplier', 'supplier.kode_supplier = pembelian.supplier', 'left')
							->join('utang_bayar', 'utang_bayar.faktur_beli = pembelian.faktur_beli', 'left')
							->where(['kode_supplier'=>$kode_supplier, 'mata_uang'=>$mata_uang, 'jenis_pembayaran'=>'Kredit'])
							->group_start()
								->where('kode_sub_bayar', NULL)
								->or_where_in('pembelian.faktur_beli', $saved)
							->group_end()
							->get('pembelian')->result_array();
		}
		
		public function getNewKode()
		{
			$pre = substr($_SESSION['tahun_aktif'], -2).$_SESSION['bulan_aktif'];
			$max = $this->db->select_max('kode_transaksi')
							->like('kode_transaksi', $pre, 'after')
							->get('pembelian')->row_array();
			
			$add	= $max['kode_transaksi'] ? substr($max['kode_transaksi'], -3) : 0;
			$baru	= sprintf('%03s', ++$add);
			return $pre.'30'.$baru;
		}
		
		public function add()
		{
			$count = 0;
			$kode_transaksi	= $this->getNewKode();
			$diskon_luar	= $this->input->post('diskon_luar', true);
			$besar_ppn		= $this->input->post('besar_ppn', true);
			
			// insert produk
			$produk = $this->addItems($kode_transaksi);
			$this->db->insert_batch('pembelian_produk', $produk);
			$produk	= $this->db->affected_rows();
			$count	= $count + $produk;
			
			$transaksi = [
				'kode_transaksi'	=> $kode_transaksi,
				'status_jurnal'		=> 'Mutasi',
				'jenis_saldo'		=> 'Debit',
				'supplier'			=> $this->input->post('supplier', true),
				'jenis_pembayaran'	=> $this->input->post('jenis_pembayaran', true),
				'tanggal_transaksi'	=> Globals::dateFormat( $this->input->post('tanggal', true) ),
				'jatuh_tempo'		=> Globals::dateFormat( $this->input->post('jatuh_tempo', true) ),
				'faktur_beli'		=> $this->input->post('faktur_beli', true),
				'surat_jalan'		=> $this->input->post('surat_jalan', true),
				'no_giro'			=> $this->input->post('no_giro', true),
				'jatuh_tempo_giro'	=> Globals::dateFormat( $this->input->post('jatuh_tempo_giro', true) ),
				'mata_uang'			=> $this->input->post('mata_uang', true),
				'konversi'			=> Globals::moneyFormat( $this->input->post('konversi', true) ),
				'akun_asal'			=> $this->input->post('akun_asal', true),
				'akun_lawan'		=> $this->input->post('akun_lawan', true),
				'diskon_luar'		=> ($diskon_luar) ? Globals::moneyFormat($diskon_luar) : 0.00,
				'akun_ppn'			=> $this->input->post('akun_ppn', true),
				'jenis_ppn'			=> $this->input->post('jenis_ppn', true),
				'besar_ppn'			=> $besar_ppn ? $besar_ppn : 0,
				'ket_transaksi'		=> $this->input->post('keterangan', true),
			];
			// // insert transaksi
			$this->db->insert('pembelian', $transaksi);
			$transaksi = $this->db->affected_rows();
			$count = $count + $transaksi;
			return $count;
		}
		
		public function edit()
		{
			$count = 0;
			$kode_transaksi	= $this->input->post('kode_transaksi', true);
			$diskon_luar	= $this->input->post('diskon_luar', true);
			$besar_ppn		= $this->input->post('besar_ppn', true);
			
			// hapus produk dan adjustment(jurnal PPH) yang sudah disimpan
			$this->db->delete('pembelian_produk', ['kode_transaksi' => $kode_transaksi]);
			$this->db->like('kode_asal', $kode_transaksi, 'after')->delete('serba_serbi');
			
			// insert 
			$produk = $this->addItems($kode_transaksi);
			$this->db->insert_batch('pembelian_produk', $produk);
			$produk	= $this->db->affected_rows();
			$count	= $count + $produk;
			
			$transaksi = [
				'supplier'			=> $this->input->post('supplier', true),
				'jenis_pembayaran'	=> $this->input->post('jenis_pembayaran', true),
				'tanggal_transaksi'	=> Globals::dateFormat($this->input->post('tanggal', true)),
				'jatuh_tempo'		=> Globals::dateFormat($this->input->post('jatuh_tempo', true)),
				'faktur_beli'		=> $this->input->post('faktur_beli', true),
				'surat_jalan'		=> $this->input->post('surat_jalan', true),
				'no_giro'			=> $this->input->post('no_giro', true),
				'jatuh_tempo_giro'	=> Globals::dateFormat($this->input->post('jatuh_tempo_giro', true)),
				'mata_uang'			=> $this->input->post('mata_uang', true),
				'konversi'			=> Globals::moneyFormat($this->input->post('konversi', true)),
				'akun_asal'			=> $this->input->post('akun_asal', true),
				'akun_lawan'		=> $this->input->post('akun_lawan', true),
				'diskon_luar'		=> ($diskon_luar) ? Globals::moneyFormat($diskon_luar) : 0.00,
				'jenis_ppn'			=> $this->input->post('jenis_ppn', true),
				'besar_ppn'			=> $besar_ppn ? $besar_ppn : 0,
				'ket_transaksi'		=> $this->input->post('keterangan', true),
			];
			// insert transaksi
			$this->db->update('pembelian', $transaksi, ['kode_transaksi' => $kode_transaksi]);
			$transaksi = $this->db->affected_rows();
			$count = $count + $transaksi;
			return $count;
		}

		public function delete($kode_transaksi)
		{
			$this->db->like('kode_asal', $kode_transaksi, 'after')->delete('serba_serbi');
			$this->db->delete(['pembelian', 'pembelian_produk'], ['kode_transaksi' => $kode_transaksi]);
			return $this->db->affected_rows();
		}
		
		public function addItems($kode_transaksi)
		{
			$produk	= [];
			$kode_produk = $this->input->post('kode_produk', true);
			foreach ($kode_produk as $key => $value) {
				$id_produk	= $kode_transaksi . sprintf('%02s', $key);
				$diskon		= $this->input->post('diskon_produk', true)[$key];
				$produk[]	= [
					'id_produk'			=> $id_produk,
					'kode_produk'		=> $value,
					'nama_produk'		=> $this->input->post('nama_produk', true)[$key],
					'qty_produk'		=> $this->input->post('qty_produk', true)[$key],
					'harga_produk'		=> $this->input->post('harga_produk', true)[$key],
					'diskon_produk'		=> $diskon ? $diskon : 0.00,
					'kode_transaksi'	=> $kode_transaksi,
				];
			}
			
			return $produk;
		}
	}
?>
