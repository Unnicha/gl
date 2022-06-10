<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Menu extends CI_Controller {

		public function __construct() 
		{
			parent::__construct();
			$this->libtemplate->user_check();
			// $this->load->model('Admin_model');
		}
		
		public function master() 
		{
			$data['title'] = 'Master';
			$this->libtemplate->main('master', $data);
		}
	}
