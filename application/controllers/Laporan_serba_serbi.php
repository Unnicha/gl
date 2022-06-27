<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Laporan_serba_serbi extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('M_laporan_serba_serbi', 'laporan');
			$this->load->model('Akun_model', 'akun');
			$this->load->model('Uang_model', 'mata_uang');
			$this->load->library('form_validation');
			
			// untuk membatasi tanggal transaksi sesuai tahun aktif
			$this->min_date	= isset($_SESSION['tahun_aktif']) ? date('d/m/Y', strtotime('01/01/'.$_SESSION['tahun_aktif'])) : '';
			$this->max_date	= isset($_SESSION['tahun_aktif']) ? date('t/m/Y', strtotime('12/01/'.$_SESSION['tahun_aktif'])) : '';
		}
		
		public function index() {
			// $this->session->unset_userdata('laporan_serba_serbi');
			$data['title']	= 'Laporan Serba Serbi';
			$this->libtemplate->main('laporan_serba_serbi/index', $data);
		}

		public function ganti()
		{
			$data['akun_asal']	= $this->akun->getByJenis('20');
			$data['mata_uang']	= $this->mata_uang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->load->view('laporan_serba_serbi/filter', $data);
		}
		
		public function display()
		{
			$ids	= $this->input->post('id', true);
			$values	= $this->input->post('value', true);
			$filter	= array_combine($ids, $values);
			$this->session->set_userdata('laporan_serba_serbi', $filter);
			
			$akun		= $this->akun->getById($filter['akun_asal']);
			$mata_uang	= $this->mata_uang->getById($filter['mata_uang']);
			
			$setting['header']		= 'Laporan Serba Serbi';
			$setting['tanggal']		= $filter['tanggal_awal'].' - '.$filter['tanggal_akhir'];
			$setting['akun_asal']	= $akun ? $akun['kode_akun'].' - '.$akun['nama_akun'] : 'Semua';
			$setting['mata_uang']	= $mata_uang ? $mata_uang['nama_mu'] : 'Semua';
			
			$data['title']		= 'Laporan Serba Serbi';
			$data['setting']	= $setting;
			$this->load->view('laporan_serba_serbi/serba_serbi', $data);
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
			$filter	= $this->session->userdata('laporan_serba_serbi');
			$filter['tanggal_awal']		= Globals::dateFormat($filter['tanggal_awal']);
			$filter['tanggal_akhir']	= Globals::dateFormat($filter['tanggal_akhir']);
			
			$transaksi	= $this->laporan->getTransaksi($cari, $offset, $limit, $order, $filter);
			$countData	= $this->laporan->countTransaksi($cari, $filter);
			
			$saldo	= 0;
			$data	= [];
			foreach($transaksi as $i) 
			{
				$debit	= $i['jenis_transaksi'] == 'Debit' ? $i['jumlah'] : 0;
				$kredit	= $i['jenis_transaksi'] == 'Kredit' ? $i['jumlah'] : 0;
				$saldo	= $i['jenis_transaksi'] == 'Debit' ? $saldo + $i['jumlah'] : $saldo - $i['jumlah'];
				
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= Globals::dateView($i['tanggal_transaksi']);
				$row[]	= $i['kode_transaksi'];
				$row[]	= $i['kode_akun'];
				$row[]	= $i['nama_akun'];
				$row[]	= $i['ket_transaksi'];
				$row[]	= Globals::moneyDisplay($debit);
				$row[]	= Globals::moneyDisplay($kredit);
				$row[]	= Globals::moneyDisplay($saldo);
				
				$data[] = $row;
			}
			
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
