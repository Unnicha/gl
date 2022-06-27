<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Laporan_pembelian extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('M_laporan_pembelian', 'laporan');
			$this->load->model('Supplier_model', 'supplier');
			$this->load->model('Barang_model', 'barang');
			$this->load->model('Uang_model', 'mata_uang');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
			
			// untuk membatasi tanggal transaksi sesuai tahun aktif
			$this->min_date	= isset($_SESSION['tahun_aktif']) ? date('d/m/Y', strtotime('01/01/'.$_SESSION['tahun_aktif'])) : '';
			$this->max_date	= isset($_SESSION['tahun_aktif']) ? date('t/m/Y', strtotime('12/01/'.$_SESSION['tahun_aktif'])) : '';
		}
		
		public function index()
		{
			$data['title']	= 'Laporan Pembelian & Retur';
			$this->libtemplate->main('laporan_pembelian/index', $data);
		}

		public function ganti()
		{
			$data['supplier']	= $this->supplier->getAll();
			$data['barang']		= $this->barang->getAll();
			$data['mata_uang']	= $this->mata_uang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->load->view('laporan_pembelian/filter', $data);
		}
		
		public function display()
		{
			$ids	= $this->input->post('id', true);
			$values	= $this->input->post('value', true);
			$filter	= array_combine($ids, $values);
			$this->session->set_userdata('laporan_pembelian', $filter);
			
			$view	= $filter['jenis_transaksi'].'_'.$filter['jenis_laporan'];
			
			$transaksi	= ($filter['jenis_transaksi'] == 'pembelian') ? 'Pembelian ' : 'Retur Pembelian ';
			$supplier	= $this->supplier->getById($filter['supplier']);
			$barang		= $this->barang->getById($filter['barang']);
			$mata_uang	= $this->mata_uang->getById($filter['mata_uang']);
			
			$setting['header']		= 'Laporan '.$transaksi . ucwords($filter['jenis_laporan']);
			$setting['tanggal']		= $filter['tanggal_awal'].' - '.$filter['tanggal_akhir'];
			$setting['pajak']		= $filter['jenis_pajak'] ? $filter['jenis_pajak'] : "Semua";
			$setting['supplier']	= $supplier ? $supplier['nama_supplier'] : 'Semua';
			$setting['barang']		= $barang ? $barang['nama_barang'] : 'Semua';
			$setting['mata_uang']	= $mata_uang ? $mata_uang['nama_mu'] : 'Semua';
			
			$data['title']		= 'Laporan Pembelian & Retur';
			$data['setting']	= $setting;
			$this->load->view('laporan_pembelian/'.$view, $data);
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
			
			// filter yang akan dikirim ke model
			$filter	= $this->session->userdata('laporan_pembelian');
			$filter['tanggal_awal']		= Globals::dateFormat($filter['tanggal_awal']);
			$filter['tanggal_akhir']	= Globals::dateFormat($filter['tanggal_akhir']);
			$filter['jenis_pajak']		= ($filter['jenis_pajak'] == 'PPN') ? ['Include', 'Exclude'] : $filter['jenis_pajak'];
			
			$data = [];
			// table laporan detail
			if ($filter['jenis_laporan'] == 'detail') {
				$transaksi	= $this->laporan->getDetail($cari, $offset, $limit, $order, $filter);
				$countData	= $this->laporan->countDetail($cari, $filter);
				
				$before	= '';
				foreach($transaksi as $i) {
					$row	= [];
					$row[]	= ++$offset.'.';
					$row[]	= ($i['kode_transaksi'] != $before) ? Globals::dateView($i['tanggal_transaksi']) : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? $i['faktur_beli'] : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? $i['surat_jalan'] : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? $i['nama_supplier'] : '';
					$row[]	= $i['kode_barang'];
					$row[]	= $i['nama_barang'];
					$row[]	= $i['qty_produk'];
					$row[]	= $i['satuan'];
					$row[]	= Globals::moneyDisplay($i['harga_produk']);
					$row[]	= Globals::moneyDisplay($i['diskon_produk']);
					$row[]	= Globals::moneyDisplay($i['jumlah_produk']);
					if ($filter['jenis_transaksi'] == 'pembelian')
					$row[]	= ($i['kode_transaksi'] != $before) ? Globals::moneyDisplay($i['diskon_luar']) : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? Globals::moneyDisplay($i['dpp']) : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? $i['besar_ppn'] : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? Globals::moneyDisplay($i['nilai_ppn']) : '';
					$row[]	= ($i['kode_transaksi'] != $before) ? Globals::moneyDisplay($i['total']) : '';
					
					$data[] = $row;
					$before	= $i['kode_transaksi'];
				}
			}
			// end table laporan detail
			
			// table laporan bulanan
			else if($filter['jenis_laporan'] == 'bulanan') {
				$transaksi	= $this->laporan->getBulanan($filter, $order);
				$countData	= $this->laporan->countBulanan($filter);
				
				foreach($transaksi as $k) {
					$row	= [];
					// $row[]	= ++$offset.'.';
					$row[]	= $k['nama_bulan'];
					$row[]	= $k['qty'];
					$row[]	= Globals::moneyDisplay($k['jumlah']);
					if ($filter['jenis_transaksi'] == 'pembelian') {
						$row[]	= Globals::moneyDisplay($k['diskon']);
						$row[]	= Globals::moneyDisplay($k['total']);
					}
					
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
