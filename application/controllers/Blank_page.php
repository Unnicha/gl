<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Blank_page extends CI_Controller 
	{

		public function __construct() 
		{
			parent::__construct();
			// $this->load->model('Admin_model');
		}
		
		public function index() 
		{
			$data['title'] = 'Blank Page';
			
			$this->libtemplate->main('blank_page', $data);
		}
	}
