<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Penjualan_model extends CI_Model 
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
			
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM penjualan_produk WHERE penjualan_produk.kode_transaksi = penjualan.kode_transaksi';
			return $this->db->select('penjualan.*, pelanggan.*, kode_asal.nama_akun AS nama_asal, kode_lawan.nama_akun AS nama_lawan, ('.$total.') AS total_produk')
							->join('pelanggan', 'pelanggan.kode_pelanggan = penjualan.pelanggan', 'left')
							->join('akun_perkiraan AS kode_asal', 'penjualan.akun_asal = kode_asal.kode_akun', 'left')
							->join('akun_perkiraan AS kode_lawan', 'penjualan.akun_lawan = kode_lawan.kode_akun', 'left')
							->get('penjualan')->result_array();
		}
		
		public function countTransaksi($cari='', $bulan='')
		{
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari);
			}
			if($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			return $this->db->select('penjualan.*')
							->join('pelanggan', 'pelanggan.kode_pelanggan = penjualan.pelanggan', 'left')
							->join('akun_perkiraan AS kode_asal', 'penjualan.akun_asal = kode_asal.kode_akun')
							->join('akun_perkiraan AS kode_lawan', 'penjualan.akun_lawan = kode_lawan.kode_akun')
							->from('penjualan')->count_all_results();
		}
		
		public function getJurnal($cari='', $offset=0, $limit='', $order='', $bulan='')
		{
			if($cari) {
				$this->db->like('penjualan.kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('kode_akun', $cari)->or_like('nama_akun', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			if($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM penjualan_produk WHERE penjualan_produk.kode_transaksi = penjualan.kode_transaksi';
			$produk	= 'SELECT kode_produk FROM penjualan_produk WHERE penjualan_produk.kode_transaksi = penjualan.kode_transaksi';
			$urut	= 'CASE WHEN kode_akun = akun_asal THEN 1 WHEN kode_akun = akun_ppn THEN 2 ELSE 3 END';
			return $this->db->distinct()
							->select('kode_akun, nama_akun, penjualan.*, ('.$total.') AS total, ('.$urut.') AS urut')
							->join('akun_perkiraan', 'kode_akun = akun_asal OR kode_akun = akun_ppn OR kode_akun = akun_lawan', 'left')
							->order_by('urut asc')
							->get('penjualan')->result_array();
		}
		
		public function countJurnal($cari='', $bulan='')
		{
			if($cari) {
				$this->db->like('tanggal_transaksi', $cari)->or_like('penjualan.kode_transaksi', $cari)
						->or_like('kode_akun', $cari)->or_like('nama_akun', $cari);
			}
			if($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			$produk	= 'SELECT kode_produk FROM penjualan_produk WHERE penjualan_produk.kode_transaksi = penjualan.kode_transaksi';
			return $this->db->distinct()
							->select('kode_akun, penjualan.*')
							->join('akun_perkiraan', 'kode_akun = akun_asal OR kode_akun IN ('.$produk.') OR kode_akun = akun_ppn OR kode_akun = akun_lawan', 'left')
							->from('penjualan')->count_all_results();
		}
		
		/**
		 * untuk menampilkan data penjualan berdasarkan Id penjualan
		 */
		public function getById($id)
		{
			return $this->db->select('*, kode_asal.nama_akun AS nama_asal, kode_lawan.nama_akun AS nama_lawan, kode_ppn.nama_akun AS nama_ppn')
							->join('akun_perkiraan AS kode_asal', 'penjualan.akun_asal = kode_asal.kode_akun', 'left')
							->join('akun_perkiraan AS kode_lawan', 'penjualan.akun_lawan = kode_lawan.kode_akun', 'left')
							->join('akun_perkiraan AS kode_ppn', 'penjualan.akun_ppn = kode_ppn.kode_akun', 'left')
							->join('pelanggan', 'penjualan.pelanggan = pelanggan.kode_pelanggan', 'left')
							->where('kode_transaksi', $id)
							->get('penjualan')->row_array();
		}
		
		public function getByPelanggan($pelanggan)
		{
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM penjualan_produk WHERE penjualan_produk.kode_transaksi = penjualan.kode_transaksi';
			return $this->db->select('*, ('.$total.') AS total_produk')
							->join('akun_perkiraan', 'penjualan.akun_lawan = akun_perkiraan.kode_akun', 'left')
							->where('pelanggan', $pelanggan)
							->get('penjualan')->result_array();
		}
		
		/**
		 * untuk menampilkan data penjualan berdasarkan faktur jual
		 */
		public function getByInvoice($faktur_jual)
		{
			$jual	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM penjualan_produk WHERE penjualan_produk.kode_transaksi = penjualan.kode_transaksi';
			$retur	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM retur_penjualan_produk WHERE retur_penjualan_produk.faktur_jual = penjualan.faktur_jual';
			$bayar	= 'SELECT SUM(jumlah_bayar) AS total_bayar FROM piutang_bayar WHERE piutang_bayar.faktur_jual = penjualan.faktur_jual';
			return $this->db->select('penjualan.*, pelanggan.*, ('.$jual.') AS total_produk, ('.$retur.') AS total_retur, ('.$bayar.') AS total_bayar')
							->join('pelanggan', 'penjualan.pelanggan = pelanggan.kode_pelanggan', 'left')
							->where('faktur_jual', $faktur_jual)
							->get('penjualan')->row_array();
		}
		
		public function getProduk($keyword, $field='')
		{
			if ($field == 'faktur_jual') {
				$transaksi	= $this->getByInvoice($keyword);
				$keyword	= $transaksi['kode_transaksi'];
			}
			return $this->db->get_where('penjualan_produk', ['kode_transaksi'=>$keyword])->result_array();
		}
		
		public function getProdukById($id_produk)
		{
			return $this->db->get_where('penjualan_produk', ['id_produk'=>$id_produk])->row_array();
		}
		
		/**
		 * untuk menampilkan faktur penjualan, semuanya atau berdasarkan pelanggan
		 */
		public function getFaktur($pelanggan='', $bulan='')
		{
			if($pelanggan) $this->db->where('pelanggan', $pelanggan);
			if($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			return $this->db->select('faktur_jual')
							->like('tanggal_transaksi', $bulan, 'after')
							->get('penjualan')->result_array();
		}
		
		public function getPiutang($kode_pelanggan='', $mata_uang='', $saved='')
		{
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM penjualan_produk WHERE penjualan_produk.kode_transaksi = penjualan.kode_transaksi';
			return $this->db->select('penjualan.*, ('.$total.') AS total_produk')
							->join('pelanggan', 'pelanggan.kode_pelanggan = penjualan.pelanggan', 'left')
							->join('piutang_bayar', 'piutang_bayar.faktur_jual = penjualan.faktur_jual', 'left')
							->where(['kode_pelanggan'=>$kode_pelanggan, 'mata_uang'=>$mata_uang, 'jenis_pembayaran'=>'Kredit'])
							->group_start()
								->where('kode_sub_bayar', NULL)
								->or_where_in('penjualan.faktur_jual', $saved)
							->group_end()
							->get('penjualan')->result_array();
		}
		
		public function getNewKode()
		{
			$pre = substr($_SESSION['tahun_aktif'], -2).$_SESSION['bulan_aktif'];
			$max = $this->db->select_max('kode_transaksi')
							->like('kode_transaksi', $pre, 'after')
							->get('penjualan')->row_array();
			
			$add	= $max['kode_transaksi'] ? substr($max['kode_transaksi'], -3) : 0;
			$baru	= sprintf('%03s', ++$add);
			return $pre.'20'.$baru;
		}
		
		public function add()
		{
			$count = 0;
			$kode_transaksi	= $this->getNewKode();
			$diskon_luar	= $this->input->post('diskon_luar', true);
			$besar_ppn		= $this->input->post('besar_ppn', true);
			
			// insert produk
			$produk = $this->addItems($kode_transaksi);
			$this->db->insert_batch('penjualan_produk', $produk);
			$produk	= $this->db->affected_rows();
			$count	= $count + $produk;
			
			$transaksi = [
				'kode_transaksi'	=> $kode_transaksi,
				'status_jurnal'		=> 'Mutasi',
				'jenis_saldo'		=> 'Kredit',
				'pelanggan'			=> $this->input->post('pelanggan', true),
				'jenis_pembayaran'	=> $this->input->post('jenis_pembayaran', true),
				'tanggal_transaksi'	=> Globals::dateFormat( $this->input->post('tanggal', true) ),
				'jatuh_tempo'		=> Globals::dateFormat( $this->input->post('jatuh_tempo', true) ),
				'faktur_jual'		=> $this->input->post('faktur_jual', true),
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
			$this->db->insert('penjualan', $transaksi);
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
			$this->db->delete('penjualan_produk', ['kode_transaksi' => $kode_transaksi]);
			$this->db->like('kode_asal', $kode_transaksi, 'after')->delete('serba_serbi');
			
			// insert 
			$produk = $this->addItems($kode_transaksi);
			$this->db->insert_batch('penjualan_produk', $produk);
			$produk	= $this->db->affected_rows();
			$count	= $count + $produk;
			
			$transaksi = [
				'pelanggan'			=> $this->input->post('pelanggan', true),
				'jenis_pembayaran'	=> $this->input->post('jenis_pembayaran', true),
				'tanggal_transaksi'	=> Globals::dateFormat($this->input->post('tanggal', true)),
				'jatuh_tempo'		=> Globals::dateFormat($this->input->post('jatuh_tempo', true)),
				'faktur_jual'		=> $this->input->post('faktur_jual', true),
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
			$this->db->update('penjualan', $transaksi, ['kode_transaksi' => $kode_transaksi]);
			$transaksi = $this->db->affected_rows();
			$count = $count + $transaksi;
			return $count;
		}

		public function delete($kode_transaksi)
		{
			$this->db->like('kode_asal', $kode_transaksi, 'after')->delete('serba_serbi');
			$this->db->delete(['penjualan', 'penjualan_produk'], ['kode_transaksi' => $kode_transaksi]);
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

