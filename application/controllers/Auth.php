<?php defined('BASEPATH') or exit('No direct script access allowed');

	class Auth extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->library('form_validation');
		}

		public function index()
		{
			if ($this->session->userdata('email')) {
				redirect('user');
			}
			$this->form_validation->set_rules('username', 'Email or Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'required|trim');

			if ($this->form_validation->run() == false) {
				$data['title'] = 'Login Page';
				$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('auth/login');
			$this->load->view('templates/footer');
			} else {
				$this->_login();
			}
		}
		private function _login()
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			// model
			$this->load->model('User_model', 'user');
			$user = $this->user->userCheckLogin($username);

			if ($user != null) {
				if ($user['is_active'] == 1) {
					if (password_verify($password, $user['password'])) {
						$data = [
							'email' => $user['email'],
							'username' => $user['username'],
							'role_id' => $user['role_id']
						];
						$this->session->set_userdata($data);
						if ($user['role_id'] == 1) {
							redirect('admin');
						} else {
							redirect('user');
						}
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!</div>');
						redirect('auth');
					}
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">This email has not been activated!</div>');
					redirect('auth');
				}
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not registered!</div>');
				redirect('auth');
			}
		}
	}