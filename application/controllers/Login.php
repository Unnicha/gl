<?php defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Login extends CI_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
			// $this->libtemplate->user_check();
			$this->load->library('form_validation');
			$this->load->model('Admin_model', 'admin');
			$this->load->model('Perusahaan_model', 'perusahaan');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
		}
	
		public function index() 
		{
			$data['redirect_to'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
			
			if ($this->input->post() == NULL) {
				$this->load->view('login', $data);
			} else {
				$username	= $this->input->post('username', true);
				$password	= $this->input->post('password', true);
				$cek		= $this->admin->getByUsername($username);
				$verify		= password_verify($password, $cek['password']);
				
				if($cek == null || $verify == false) {
					$this->session->set_flashdata('pesan','<div class="alert alert-danger" role="alert">Username atau password salah!</div>');
					redirect('login');
				} else {
					$sess = [
						'id_user'		=> $cek['id_user'],
						'username'		=> $username,
						'nama_user'		=> $cek['nama'],
						'tipe_user'		=> $cek['tipe'],
						// 'key'		=> $cek['apikey'],
					];
					$this->session->set_userdata($sess);
					$this->session->set_flashdata('redirect_to', $this->input->post('redirect_to', true));
					
					// re-hash password
					$this->admin->reHashPassword($cek['id_user'], $password);
					
					redirect('pilih_perusahaan');
				}
			}
		}
		
		public function authLogin($username, $password) 
		{
			# code...
		}
	
		public function forget_password() 
		{
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			
			if($this->form_validation->run() == FALSE) {
				$this->load->view('reset_password/forget_pass');
			} else {
				// cek apakah email terdaftar
				$cek = $this->admin->getByEmail($this->input->post('email', true));
				if($cek == null) {
					$this->session->set_flashdata('flash', 'terkait');
					redirect('login/forget_password');
				} else {
					$token  = $this->admin->insertToken($cek['id_user']);
					$code   = $this->base64url_encode($token);
					$url    = base_url().'login/reset_password/token/'.$code;
					$link   = '<a href="' . $url . '">' . $url . '</a>'; 
					$send   = $this->sendmail->resetPassword($cek, $link);
					if($send == true) { 
						$this->load->view('reset_password/email_sent');
					} else { 
						echo $send; 
					}
				}
			}
		}
	
		public function reset_password() 
		{
			$token		= $this->base64url_decode($this->uri->segment(4));
			$cleanToken	= $this->security->xss_clean($token);
			$user_info	= $this->admin->validToken($cleanToken); 
	
			if (!$user_info) {
				$this->session->set_flashdata('sukses', 'Token tidak valid atau kadaluarsa');
			} else {
				$this->form_validation->set_rules('password', 'Password', 'required');
				$this->form_validation->set_rules('passconf', 'Password', 'required|matches[password]');
				
				if($this->form_validation->run() == FALSE) {
					$this->load->view('reset_password/reset_password', $user_info);
				} else {
					$this->admin->updatePassword($_REQUEST['password'], $user_info['id_user']);
					$this->load->view('reset_password/reset_success');
				}
			}
		}
	
		public function base64url_encode($data) 
		{
			return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
		}
	
		public function base64url_decode($data) 
		{
			return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
		}
		
		public function logout() 
		{
			session_destroy();
			redirect('login');
		}
	}
?>
