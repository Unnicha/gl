<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Laporan_piutang extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('M_laporan_piutang', 'laporan');
			$this->load->model('Akun_model', 'akun');
			$this->load->model('Uang_model', 'mata_uang');
			$this->load->library('form_validation');
			
			// untuk membatasi tanggal transaksi sesuai tahun aktif
			$this->min_date	= isset($_SESSION['tahun_aktif']) ? date('d/m/Y', strtotime('01/01/'.$_SESSION['tahun_aktif'])) : '';
			$this->max_date	= isset($_SESSION['tahun_aktif']) ? date('t/m/Y', strtotime('12/01/'.$_SESSION['tahun_aktif'])) : '';
		}
		
		public function index() {
			// $this->session->unset_userdata('laporan_piutang');
			$data['title']	= 'Laporan Piutang & Pembayaran';
			$this->libtemplate->main('laporan_piutang/index', $data);
		}

		public function ganti()
		{
			$data['akun_asal']	= $this->akun->getByJenis('33');
			$data['mata_uang']	= $this->mata_uang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->load->view('laporan_piutang/filter', $data);
		}
		
		public function display()
		{
			$ids	= $this->input->post('id', true);
			$values	= $this->input->post('value', true);
			$filter	= array_combine($ids, $values);
			$this->session->set_userdata('laporan_piutang', $filter);
			
			$transaksi	= ($filter['jenis_laporan'] == 'piutang') ? 'Piutang' : 'Pembayaran Piutang';
			$akun		= $this->akun->getById($filter['akun_asal']);
			$mata_uang	= $this->mata_uang->getById($filter['mata_uang']);
			
			$setting['header']		= 'Laporan '.$transaksi;
			$setting['tanggal']		= $filter['tanggal_awal'].' - '.$filter['tanggal_akhir'];
			$setting['akun_asal']	= $akun ? $akun['nama_akun'] : 'Semua';
			$setting['mata_uang']	= $mata_uang ? $mata_uang['nama_mu'] : 'Semua';
			
			$data['title']		= 'Laporan Piutang & Pembayaran';
			$data['setting']	= $setting;
			$this->load->view('laporan_piutang/'.$filter['jenis_laporan'], $data);
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
			$filter	= $this->session->userdata('laporan_piutang');
			$filter['tanggal_awal']		= Globals::dateFormat($filter['tanggal_awal']);
			$filter['tanggal_akhir']	= Globals::dateFormat($filter['tanggal_akhir']);
			
			$transaksi	= $this->laporan->getPiutang($cari, $offset, $limit, $order, $filter);
			$countData	= $this->laporan->countPiutang($cari, $filter);
			
			$data = [];
			// table laporan piutang
			if ($filter['jenis_laporan'] == 'piutang') {
				foreach($transaksi as $i) 
				{
					$row	= [];
					$row[]	= ++$offset.'.';
					$row[]	= Globals::dateView($i['tanggal_transaksi']);
					$row[]	= $i['faktur_jual'];
					$row[]	= $i['kode_akun'];
					$row[]	= $i['nama_akun'];
					$row[]	= Globals::moneyDisplay($i['jumlah']);
					$row[]	= Globals::moneyDisplay($i['sisa']);
					
					$data[] = $row;
				}
			}
			// end table laporan detail
			
			// table laporan bayar piutang
			else if($filter['jenis_laporan'] == 'bayar') {
				$before	= '';
				$sisa	= 0;
				foreach($transaksi as $k) 
				{
					// sisa_bayar = jumlah - retur
					$sisa = ($k['faktur_jual'] == $before) ? $sisa : $k['sisa_bayar'];
					$sisa = $sisa - $k['jumlah_bayar'];
					
					$row	= [];
					$row[]	= ++$offset.'.';
					$row[]	= ($k['faktur_jual'] == $before) ? '' : Globals::dateView($k['tanggal_transaksi']);
					$row[]	= ($k['faktur_jual'] == $before) ? '' : $k['faktur_jual'];
					$row[]	= ($k['faktur_jual'] == $before) ? '' : $k['kode_asal'];
					$row[]	= ($k['faktur_jual'] == $before) ? '' : $k['nama_asal'];
					$row[]	= ($k['faktur_jual'] == $before) ? '' : Globals::moneyDisplay($k['jumlah']);
					$row[]	= $k['kode_bayar'];
					$row[]	= Globals::dateView($k['tanggal_bayar']);
					$row[]	= $k['kode_lawan'];
					$row[]	= $k['nama_lawan'];
					$row[]	= Globals::moneyDisplay($k['jumlah_bayar']);
					$row[]	= Globals::moneyDisplay($sisa);
					
					$data[] = $row;
					$before = $k['faktur_jual'];
				}
			}
			// end table laporan bayar piutang
			
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
