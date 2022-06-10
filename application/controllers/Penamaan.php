<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Penamaan extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Penamaan_model','penamaan');
			$this->load->library('form_validation');
		
		}

		public function index()
		{
			$data['title'] = 'Penamaan';
			$data['penamaan'] = $this->penamaan->getAllPenamaan();
			
			$this->libtemplate->main('penamaan/index', $data);
		}
	}