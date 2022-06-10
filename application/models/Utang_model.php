<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Utang_model extends CI_Model 
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
		
		public function getAll($cari='', $offset=0, $limit='', $order='', $bulan='') 
		{
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			if($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			
			return $this->db->join('utang_bayar', 'utang_bayar.kode_transaksi = utang.kode_transaksi', 'left')
							->join('supplier', 'supplier.kode_supplier = utang.supplier', 'left')
							->get('utang')->result_array();
		}
		
		public function countAll($cari='', $bulan='') 
		{
			if($cari) {
				$this->db->like('kode_transaksi', $cari)->or_like('tanggal_transaksi', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari);
			}
			if($bulan) $this->db->like('tanggal_transaksi', $bulan, 'after');
			return $this->db->join('utang_bayar', 'utang_bayar.kode_transaksi = utang.kode_transaksi', 'left')
							->join('supplier', 'supplier.kode_supplier = utang.supplier', 'left')
							->from('utang')->count_all_results();
		}
		
		public function getById($id) 
		{
			return $this->db->select('*, kode_asal.nama_akun AS nama_asal, kode_lawan.nama_akun AS nama_lawan, kode_ppn.nama_akun AS nama_ppn')
							->join('akun_perkiraan AS kode_asal', 'utang.akun_asal = kode_asal.kode_akun', 'left')
							->join('akun_perkiraan AS kode_lawan', 'utang.akun_lawan = kode_lawan.kode_akun', 'left')
							->join('akun_perkiraan AS kode_ppn', 'utang.akun_ppn = kode_ppn.kode_akun', 'left')
							->join('supplier', 'utang.supplier = supplier.kode_supplier', 'left')
							->where('kode_transaksi', $id)
							->get('utang')->row_array();
		}
		
		public function getByInvoice($faktur_beli) 
		{
			return $this->db->join('utang_bayar', 'utang_bayar.kode_transaksi = utang.kode_transaksi', 'left')
							->join('supplier', 'supplier.kode_supplier = utang.supplier', 'left')
							->where('faktur_beli', $faktur_beli)
							->get('utang')->result_array();
		}
		
		/**
		 * mengambil detail transaksi yang dibayar berdasarkan kode pembayaran
		 */
		public function getDetail($kode_transaksi) 
		{
			return $this->db->join('pembelian', 'pembelian.faktur_beli = utang_bayar.faktur_beli')
							->where('utang_bayar.kode_transaksi', $kode_transaksi)
							->order_by('kode_bayar')
							->get('utang_bayar')->result_array();
		}
		
		/**
		 * mengambil detail transaksi yang dibayar berdasarkan kode pembayaran
		 */
		public function getDetailById($kode_transaksi, $invoice) 
		{
			return $this->db->get_where('utang_bayar', ['kode_transaksi'=>$kode_transaksi, 'faktur_beli'=>$invoice])->row_array();
		}
		
		public function getPembelian($faktur_beli)
		{
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM pembelian_produk WHERE pembelian_produk.kode_transaksi = pembelian.kode_transaksi';
			return $this->db->select('*, ('.$total.') AS total_produk')
							->where('faktur_beli', $faktur_beli)->get('pembelian')->row_array();
		}
		
		public function getNewKode() 
		{
			$pre = substr($this->session->userdata('tahun_aktif'), -2).$this->session->userdata('bulan_aktif');
			$max = $this->db->select_max('kode_transaksi')
							->like('kode_transaksi', $pre, 'after')
							->get('utang')->row_array();
			
			$add	= $max['kode_transaksi'] ? substr($max['kode_transaksi'], -3) : 0;
			$baru	= sprintf('%03s', ++$add);
			return $pre.'32'.$baru;
		}
		
		public function add() 
		{
			$kode_transaksi	= $this->getNewKode();
			
			// input detail pembelian yang dibayar
			$count	= 0;
			$faktur_beli = $this->addBayar($kode_transaksi);
			$count		 = $count + $faktur_beli;
			
			$transaksi = [
				'kode_transaksi'	=> $kode_transaksi,
				'status_jurnal'		=> 'Mutasi',
				'jenis_saldo'		=> 'Debit',
				'tanggal_transaksi'	=> Globals::dateFormat($this->input->post('tanggal', true)),
				'supplier'			=> $this->input->post('supplier'),
				'mata_uang'			=> $this->input->post('mata_uang', true),
				'konversi'			=> Globals::moneyFormat($this->input->post('konversi', true)),
				'akun_asal'			=> $this->input->post('akun_asal', true),
				'akun_lawan'		=> $this->input->post('akun_lawan'),
				'akun_ppn'			=> $this->input->post('akun_ppn'),
				'ket_transaksi'		=> $this->input->post('keterangan', true),
			];
			// // insert transaksi
			$this->db->insert('utang', $transaksi);
			$transaksi = $this->db->affected_rows();
			$count	= $count + $transaksi;
			return $count;
		}
		
		public function edit() 
		{
			$kode_transaksi	= $this->input->post('kode_transaksi');
			$this->db->delete('utang_bayar', ['kode_transaksi' => $kode_transaksi]);
			
			$count	= 0;
			$faktur_beli = $this->addBayar($kode_transaksi);
			$count		 = $count + $faktur_beli;
			
			$transaksi = [
				'tanggal_transaksi'	=> Globals::dateFormat($this->input->post('tanggal', true)),
				'mata_uang'			=> $this->input->post('mata_uang', true),
				'konversi'			=> Globals::moneyFormat($this->input->post('konversi', true)),
				'akun_asal'			=> $this->input->post('akun_asal', true),
				'ket_transaksi'		=> $this->input->post('keterangan', true),
			];
			// insert transaksi
			$this->db->update('utang', $transaksi, ['kode_transaksi' => $kode_transaksi]);
			$transaksi = $this->db->affected_rows();
			$count	= $count + $transaksi;
			return $count;
		}
		
		public function addBayar($kode_transaksi)
		{
			$faktur_beli	= $this->input->post('faktur_beli', true);
			$jumlah_tagihan	= $this->input->post('jumlah_dibayar', true);
			
			$bayar	= [];
			$num	= 0;
			foreach ($faktur_beli as $key => $faktur) {
				$pembelian	= $this->getPembelian($faktur);
				$bayar[]	= [
					'kode_bayar'		=> $kode_transaksi . sprintf('%02s', ++$num),
					'kode_transaksi'	=> $kode_transaksi,
					'faktur_beli'		=> $faktur,
					'jenis_ppn'			=> $pembelian['jenis_ppn'],
					'besar_ppn'			=> $pembelian['besar_ppn'],
					'jumlah_bayar'		=> $jumlah_tagihan[$key],
				];
			}
			$this->db->insert_batch('utang_bayar', $bayar);
			return $this->db->affected_rows();
		}

		public function delete($kode_transaksi) 
		{
			$this->db->delete(['utang', 'utang_bayar'], ['kode_transaksi' => $kode_transaksi]);
			return $this->db->affected_rows();
		}
	}
?>