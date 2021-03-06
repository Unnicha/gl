<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Piutang_model extends CI_Model 
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
				$this->db->like('kode_bayar', $cari)->or_like('tanggal_bayar', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari);
			}
			if($limit) $this->db->limit($limit, $offset);
			if($order) $this->db->order_by($order);
			if($bulan) $this->db->like('tanggal_bayar', $bulan, 'after');
			
			return $this->db->join('piutang_bayar', 'piutang_bayar.kode_bayar = piutang.kode_bayar', 'left')
							->join('pelanggan', 'pelanggan.kode_pelanggan = piutang.pelanggan', 'left')
							->get('piutang')->result_array();
		}
		
		public function countAll($cari='', $bulan='') 
		{
			if($cari) {
				$this->db->like('kode_bayar', $cari)->or_like('tanggal_bayar', $cari)
						->or_like('akun_asal', $cari)->or_like('kode_asal.nama_akun', $cari)
						->or_like('akun_lawan', $cari)->or_like('kode_lawan.nama_akun', $cari);
			}
			if($bulan) $this->db->like('tanggal_bayar', $bulan, 'after');
			return $this->db->join('piutang_bayar', 'piutang_bayar.kode_bayar = piutang.kode_bayar', 'left')
							->join('pelanggan', 'pelanggan.kode_pelanggan = piutang.pelanggan', 'left')
							->from('piutang')->count_all_results();
		}
		
		public function getById($id) 
		{
			return $this->db->select('*, 
								kode_asal.nama_akun AS nama_asal, 
								kode_lawan.nama_akun AS nama_lawan, 
								kode_ppn.nama_akun AS nama_ppn
								')
							->join('akun_perkiraan AS kode_asal', 'piutang.akun_asal = kode_asal.kode_akun', 'left')
							->join('akun_perkiraan AS kode_lawan', 'piutang.akun_lawan = kode_lawan.kode_akun', 'left')
							->join('akun_perkiraan AS kode_ppn', 'piutang.akun_ppn = kode_ppn.kode_akun', 'left')
							->join('pelanggan', 'piutang.pelanggan = pelanggan.kode_pelanggan', 'left')
							->where('kode_bayar', $id)
							->get('piutang')->row_array();
		}
		
		public function getByInvoice($faktur_jual) 
		{
			return $this->db->join('piutang_bayar', 'piutang_bayar.kode_bayar = piutang.kode_bayar', 'left')
							->join('pelanggan', 'pelanggan.kode_pelanggan = piutang.pelanggan', 'left')
							->where('faktur_jual', $faktur_jual)
							->get('piutang')->result_array();
		}
		
		/**
		 * mengambil detail transaksi penjualan yang dibayar berdasarkan kode pembayaran
		 */
		public function getDetail($kode_bayar) 
		{
			return $this->db->join('penjualan', 'penjualan.faktur_jual = piutang_bayar.faktur_jual')
							->where('piutang_bayar.kode_bayar', $kode_bayar)
							->order_by('kode_sub_bayar')
							->get('piutang_bayar')->result_array();
		}
		
		/**
		 * mengambil detail transaksi yang dibayar berdasarkan kode pembayaran
		 */
		public function getDetailById($kode_bayar, $invoice) 
		{
			return $this->db->get_where('piutang_bayar', ['kode_bayar'=>$kode_bayar, 'faktur_jual'=>$invoice])->row_array();
		}
		
		public function getPenjualan($faktur_jual)
		{
			$total	= 'SELECT SUM((harga_produk - diskon_produk) * qty_produk) FROM penjualan_produk WHERE penjualan_produk.kode_transaksi = penjualan.kode_transaksi';
			return $this->db->select('*, ('.$total.') AS total_produk')
							->where('faktur_jual', $faktur_jual)->get('penjualan')->row_array();
		}
		
		public function getNewKode() 
		{
			$pre = substr($this->session->userdata('tahun_aktif'), -2).$this->session->userdata('bulan_aktif');
			$max = $this->db->select_max('kode_bayar')
							->like('kode_bayar', $pre, 'after')
							->get('piutang')->row_array();
			
			$add	= $max['kode_bayar'] ? substr($max['kode_bayar'], -3) : 0;
			$baru	= sprintf('%03s', ++$add);
			return $pre.'22'.$baru;
		}
		
		public function add() 
		{
			$kode_bayar	= $this->getNewKode();
			
			// input detail penjualan yang dibayar
			$count	= 0;
			$faktur_jual = $this->addBayar($kode_bayar);
			$count		 = $count + $faktur_jual;
			
			$transaksi = [
				'kode_bayar'	=> $kode_bayar,
				'status_jurnal'		=> 'Mutasi',
				'jenis_saldo'		=> 'Debit',
				'tanggal_bayar'	=> Globals::dateFormat($this->input->post('tanggal', true)),
				'pelanggan'			=> $this->input->post('pelanggan'),
				'mata_uang'			=> $this->input->post('mata_uang', true),
				'konversi'			=> Globals::moneyFormat($this->input->post('konversi', true)),
				'akun_asal'			=> $this->input->post('akun_asal', true),
				'akun_lawan'		=> $this->input->post('akun_lawan'),
				'akun_ppn'			=> $this->input->post('akun_ppn'),
				'ket_bayar'		=> $this->input->post('keterangan', true),
			];
			// // insert transaksi
			$this->db->insert('piutang', $transaksi);
			$transaksi = $this->db->affected_rows();
			$count	= $count + $transaksi;
			return $count;
		}
		
		public function edit() 
		{
			$kode_bayar	= $this->input->post('kode_bayar');
			$this->db->delete('piutang_bayar', ['kode_bayar' => $kode_bayar]);
			
			$count	= 0;
			$faktur_jual = $this->addBayar($kode_bayar);
			$count		 = $count + $faktur_jual;
			
			$transaksi = [
				'tanggal_bayar'	=> Globals::dateFormat($this->input->post('tanggal', true)),
				'mata_uang'			=> $this->input->post('mata_uang', true),
				'konversi'			=> Globals::moneyFormat($this->input->post('konversi', true)),
				'akun_asal'			=> $this->input->post('akun_asal', true),
				'ket_bayar'		=> $this->input->post('keterangan', true),
			];
			// insert transaksi
			$this->db->update('piutang', $transaksi, ['kode_bayar' => $kode_bayar]);
			$transaksi = $this->db->affected_rows();
			$count	= $count + $transaksi;
			return $count;
		}
		
		public function addBayar($kode_bayar)
		{
			$faktur_jual	= $this->input->post('faktur_jual', true);
			$jumlah_tagihan	= $this->input->post('jumlah_dibayar', true);
			
			$bayar	= [];
			$num	= 0;
			foreach ($faktur_jual as $key => $faktur) {
				$penjualan	= $this->getPenjualan($faktur);
				$bayar[]	= [
					'kode_sub_bayar'		=> $kode_bayar . sprintf('%02s', ++$num),
					'kode_bayar'	=> $kode_bayar,
					'faktur_jual'		=> $faktur,
					'jenis_ppn'			=> $penjualan['jenis_ppn'],
					'besar_ppn'			=> $penjualan['besar_ppn'],
					'jumlah_bayar'		=> $jumlah_tagihan[$key],
				];
			}
			$this->db->insert_batch('piutang_bayar', $bayar);
			return $this->db->affected_rows();
		}

		public function delete($kode_bayar) 
		{
			$this->db->delete(['piutang', 'piutang_bayar'], ['kode_bayar' => $kode_bayar]);
			return $this->db->affected_rows();
		}
	}
?>
