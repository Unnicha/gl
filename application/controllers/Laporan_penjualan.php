<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Laporan_penjualan extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Laporan_penjualan_model', 'laporan');
			$this->load->model('Pelanggan_model', 'pelanggan');
			$this->load->model('Barang_model', 'barang');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
			
			// untuk membatasi tanggal transaksi sesuai tahun aktif
			$this->min_date	= isset($_SESSION['tahun_aktif']) ? date('d/m/Y', strtotime('01/01/'.$_SESSION['tahun_aktif'])) : '';
			$this->max_date	= isset($_SESSION['tahun_aktif']) ? date('t/m/Y', strtotime('12/01/'.$_SESSION['tahun_aktif'])) : '';
		}
		
		public function index()
		{
			$sess	= $this->session->userdata('laporan_penjualan');
			if ($sess) {
				$view	= $sess['jenis_transaksi'].'_'.$sess['jenis_laporan'];
				
				$transaksi	= ($sess['jenis_transaksi'] == 'penjualan') ? ' Penjualan' : ' Retur Penjualan';
				$pelanggan	= $this->pelanggan->getById($sess['pelanggan']);
				$barang		= $this->barang->getById($sess['barang']);
				$setting['header']		= 'Laporan ' . ucwords($sess['jenis_laporan']) . $transaksi;
				$setting['tanggal']		= $sess['tanggal_awal'].' - '.$sess['tanggal_akhir'];
				$setting['pelanggan']	= ($pelanggan) ? $pelanggan['nama_pelanggan'] : 'Semua';
				$setting['barang']		= ($barang) ? $barang['nama_barang'] : 'Semua';
				$setting['pajak']		= ($sess['jenis_pajak']) ? $sess['jenis_pajak'] : "Semua";
				
				$data['title']		= 'Laporan Penjualan & Retur';
				$data['setting']	= $setting;
				$this->libtemplate->main('laporan_penjualan/'.$view, $data);
			} else {
				redirect('laporan_penjualan/tampilan');
			}
		}

		public function tampilan()
		{
			$data['title']		= 'Laporan Penjualan & Retur';
			$data['pelanggan']	= $this->pelanggan->getAll();
			$data['barang']		= $this->barang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->form_validation->set_rules('jenis_transaksi', 'Jenis Transaksi', 'required');
			$this->form_validation->set_rules('jenis_laporan', 'Jenis Laporan', 'required');
			$this->form_validation->set_rules('tanggal_awal', 'Tanggal Awal', 'required');
			$this->form_validation->set_rules('tanggal_akhir', 'Tanggal Akhir', 'required');
			$this->form_validation->set_rules('jenis_pajak', 'Jenis Pajak', '');
			$this->form_validation->set_rules('pelanggan', 'Pelanggan', '');
			$this->form_validation->set_rules('barang', 'Barang', '');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('laporan_penjualan/index', $data);
			} else {
				$this->session->set_userdata(['laporan_penjualan' => $this->input->post()]);
				redirect('laporan_penjualan');
			}
		}
		
		public function table()
		{
			$offset	= $_POST['start'];
			$limit	= $_POST['length'];
			$cari	= $_POST['search']['value'];
			
			$order	= [];
			foreach($_POST['order'] as $key => $sort) {
				$order_id	= $_POST['order'][$key]['column'];
				$order_dir	= $_POST['order'][$key]['dir'];
				$order_by	= $_POST['columns'][$order_id]['name'];
				$order[]	= $order_by.' '.$order_dir;
			}
			$order	= implode(',', $order);
			$filter	= $this->session->userdata('laporan_penjualan');
			$filter['tanggal_awal']		= Globals::dateFormat($filter['tanggal_awal']);
			$filter['tanggal_akhir']	= Globals::dateFormat($filter['tanggal_akhir']);
			$filter['jenis_pajak']		= ($filter['jenis_pajak'] == 'PPN') ? ['Include', 'Exclude'] : $filter['jenis_pajak'];
			
			$data = [];
			// table transaksi
			if ($filter['jenis_laporan'] == 'detail') {
				if ($filter['jenis_transaksi'] == 'penjualan') {
					$transaksi	= $this->laporan->getDetailPenjualan($cari, $offset, $limit, $order, $filter);
					$countData	= $this->laporan->countDetailPenjualan($cari, $filter);
				} else {
					$transaksi	= $this->laporan->getDetailRetur($cari, $offset, $limit, $order, $filter);
					$countData	= $this->laporan->countDetailRetur($cari, $filter);
				}
				
				$before	= '';
				foreach($transaksi as $i) {
					$row	= [];
					$row[]	= ++$offset.'.';
					$row[]	= ($i['kode_transaksi'] != $before) ? Globals::dateView($i['tanggal_transaksi']) : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? $i['faktur_jual'] : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? $i['surat_jalan'] : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? $i['pelanggan'] : '';
					$row[]	= $i['kode_barang'];
					$row[]	= $i['nama_barang'];
					$row[]	= $i['qty_produk'];
					$row[]	= $i['satuan'];
					$row[]	= Globals::moneyView($i['harga_produk']);
					$row[]	= Globals::moneyView($i['diskon_produk']);
					$row[]	= Globals::moneyView($i['jumlah_produk']);
					$row[]	= ($i['kode_transaksi'] != $before) ? Globals::moneyView($i['diskon_luar']) : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? Globals::moneyView($i['dpp']) : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? $i['besar_ppn'] : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? Globals::moneyView($i['nilai_ppn']) : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? Globals::moneyView($i['total']) : '';
					
					$data[] = $row;
					$before	= $i['kode_transaksi'];
				}
			}
			// end table transaksi
			
			// table laporan bulanan
			else if($filter['jenis_laporan'] == 'bulanan') {
				$transaksi	= $this->laporan_penjualan->getJurnal($cari, $offset, $limit, $order, $filter);
				$countData	= $this->laporan_penjualan->countJurnal($cari, $filter);
				
				foreach($transaksi as $k) {
					$row	= [];
					$row[]	= ++$offset.'.';
					$row[]	= $k['bulan'];
					$row[]	= $k['qty'];
					$row[]	= $k['jumlah'];
					$row[]	= $k['diskon'];
					$row[]	= $k['total'];
					
					$data[] = $row;
				}
			}
			// end table laporan bulanan
			
			$callback	= [
				'draw'				=> $_POST['draw'], // Ini dari datatablenya
				'recordsTotal'		=> $countData,
				'recordsFiltered'	=> $countData,
				'data'				=> $data,
			];
			echo json_encode($callback);
		}
	}
?>