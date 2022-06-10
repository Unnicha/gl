<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Home extends CI_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
			$this->load->model('Admin_model', 'admin');
			$this->load->model('Perusahaan_model', 'perusahaan');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
		}
		
		public function index() 
		{
			$this->libtemplate->user_check();
			$data['title'] = 'Home';
			$this->libtemplate->main('home', $data);
		}
		
		public function pilih_perusahaan()
		{
			$this->libtemplate->session_check();
			
			$user	= $this->admin->getById( $this->session->userdata('id_user') );
			if($user['tipe'] == 'admin') {
				$akses	= $this->perusahaan->getAll();
			} else {
				$akses	= explode(',', $user['akses_perusahaan']);
				$akses	= $this->perusahaan->getById($akses);
			}
			
			$data['title']		= 'Pilih Perusahaan';
			$data['perusahaan']	= $akses;
			$data['bulan']		= Globals::bulan();
			
			$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
			$this->form_validation->set_rules('tahun', 'Tahun', 'required');
			$this->form_validation->set_rules('bulan', 'Bulan', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->load->view('pilih_perusahaan', $data);
			} else {
				$id_pt		= $this->input->post('perusahaan', true);
				$tahun		= $this->input->post('tahun', true);
				$bulan		= Globals::bulan($this->input->post('bulan', true));
				$id_setup	= $id_pt.$tahun;
				$perusahaan	= $this->perusahaan->getSetupById($id_setup);
				$sess = [
					'kode_perusahaan'	=> $perusahaan['kode_perusahaan'],
					'nama_perusahaan'	=> $perusahaan['nama_perusahaan'],
					'db_perusahaan'		=> $perusahaan['database'],
					'id_setup'			=> $perusahaan['id_setup'],
					'tahun_aktif'		=> $tahun,
					'bulan_aktif'		=> $bulan['id'],
					'nama_bulan'		=> $bulan['nama'],
				];
				$this->session->set_userdata($sess);
				
				redirect('home');
			}
		}
		
		public function pindah_bulan()
		{
			$this->libtemplate->session_check();
			
			$data['title']		= 'Pindah Bulan';
			$data['perusahaan']	= $this->perusahaan->getSetupByPerusahaan($this->session->userdata('kode_perusahaan'));
			$data['bulan']		= Globals::bulan();
			
			$this->form_validation->set_rules('tahun', 'Tahun', 'required');
			$this->form_validation->set_rules('bulan', 'Bulan', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->load->view('pindah_bulan', $data);
			} else {
				$bulan	= Globals::bulan($this->input->post('bulan', true));
				$sess	= [
					'tahun_aktif'		=> $this->input->post('tahun', true),
					'bulan_aktif'		=> $bulan['id'],
					'nama_bulan'		=> $bulan['nama'],
				];
				$this->session->set_userdata($sess);
				
				redirect('home');
			}
		}
		
		/**
		 * digunakan di view pilih_perusahaan dan pindah bulan
		 * untuk menampilkan list tahun berdasarkan perusahaan yang dipilih
		 */
		public function getTahun()
		{
			$tahun		= [];
			$lists		= '<option value="">Pilih Tahun</option>';
			$perusahaan	= $this->perusahaan->getSetupByPerusahaan($_REQUEST['perusahaan']);
			foreach($perusahaan as $p) {
				$tahun[] = $p['tahun'];
			}
			rsort($tahun);
			foreach($tahun as $thn) {
				$lists	.= '<option value="'.$thn.'">'.$thn.'</option>';
			}
			echo $lists;
		}
	}
