<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Libtemplate 
	{
		protected $ci;

		function __construct() 
		{
			$this->ci = & get_instance();
		}
		
		function main($content, $data = null) 
		{
			$this->ci->load->library('session');
			$tipe	= $this->ci->session->userdata('tipe_user');
			$username	= $this->ci->session->userdata('username');
			
			if($username) {
				$data['head']		= $this->ci->load->view('/template/header', $data, TRUE);
				$data['sidebar']	= $this->ci->load->view('/template/sidebar', $data, TRUE);
				$data['content']	= $this->ci->load->view($content, $data, TRUE);
				$data['foot']		= $this->ci->load->view('/template/footer', $data, TRUE);
				
				$this->ci->load->view('template/main', $data);
			} else {
				redirect('login');
			}
		}
		
		function user_check() 
		{
			$this->ci->load->library('session');
			$username	= $this->ci->session->userdata('username');
			$perusahaan	= $this->ci->session->userdata('kode_perusahaan');
			
			if(!$perusahaan) {
				if(!$username) {
					redirect('login');
				} else {
					redirect('pilih_perusahaan');
				}
			}
		}
		
		function session_check() 
		{
			$this->ci->load->library('session');
			$username	= $this->ci->session->userdata('username');
			
			if(!$username) {
				redirect('login');
			}
		}
		
		// function main($content, $data = null) {
			
		// 	$this->ci->load->library('session');
		// 	$level	= $this->ci->session->userdata('level');
		// 	$cek	= $this->ci->uri->segment('1');
		
		// 	if($level == null) {
		// 		redirect('login');
		// 	} else {
		// 		if($level == $cek) {
		// 			$data['head']		= $this->ci->load->view($level.'/template/header', $data, TRUE);
		// 			$data['sidebar']	= $this->ci->load->view($level.'/template/sidebar', $data, TRUE);
		// 			$data['content']	= $this->ci->load->view($content, $data, TRUE);
		// 			$data['foot']		= $this->ci->load->view($level.'/template/footer', $data, TRUE);
					
		// 			$this->ci->load->view('template', $data);
		// 		} else {
		// 			$redirect = base_url($level);
		// 			redirect($redirect);
		// 		}
		// 	}
		// }
	}
?>