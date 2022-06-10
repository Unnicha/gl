<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Admin extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Admin_model','admin');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
		}

		public function index()
		{
			$data['title'] = 'Data Admin';
			$this->libtemplate->main('admin/index', $data);
		}
		
		public function table()
		{
			$offset		= $_POST['start'];
			$limit		= $_POST['length'];
			$cari		= $_POST['search']['value'];
			
			$order		= [];
			foreach($_POST['order'] as $key => $sort) {
				$order_id	= $_POST['order'][$key]['column'];
				$order_dir	= $_POST['order'][$key]['dir'];
				$order_by	= $_POST['columns'][$order_id]['name'];
				$order[]	= $order_by.' '.$order_dir;
			}
			$order	= implode(',', $order);
			
			$admin		= $this->admin->getAll($cari, $offset, $limit, $order);
			$countData	= $this->admin->countAll($cari);
			
			$data = [];
			foreach($admin as $k) {
				$btn_detail = '
					<a class="btn-detail badge badge-primary badge-action" data-id="'.$k['id_user'].'" data-toggle="tooltip" data-placement="left" title="Detail">
						<i class="bi bi-info-circle"></i>
					</a>';
				
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= $k['id_user'];
				$row[]	= $k['username'];
				$row[]	= $k['nama'];
				$row[]	= '
					<a href="'.base_url('admin/ubah_password/'.$k['id_user']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah Password">
						<i class="bi bi-key"></i>
					</a>
					<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['id_user'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
						<i class="bi bi-trash"></i>
					</a>';
				
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
		
		
		public function tambah()
		{
			$data['title']	= 'Tambah Admin';

			$this->form_validation->set_rules('username', 'Username', 'required|min_length[8]|alpha_dash|is_unique[user.username]');
			$this->form_validation->set_rules('nama', 'Nama', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|alpha_dash');
			$this->form_validation->set_rules('confirm', 'Konfirmasi Password', 'required|matches[password]');

			
			if($this->form_validation->run() == false) {
				$this->libtemplate->main('admin/tambah', $data);
			} else {
				if ($this->admin->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal ditambahkan!');
				}
				redirect(base_url().'admin');
			}
		}
		
		public function edit($id)
		{
			$admin	= $this->admin->getById($id);
			$unique	= (isset($_POST['username']) && $_POST['username'] == $admin['username']) ? '' : '|is_unique[user.username]';
			
			$data['title']	= 'Edit Admin';
			$data['admin']	= $admin;
			
			$this->form_validation->set_rules('id_user', 'ID User', 'required');
			$this->form_validation->set_rules('nama', 'Nama', 'required');
			$this->form_validation->set_rules('username', 'Username', 'required|min_length[8]|alpha_dash'.$unique);
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|alpha_dash');
			$this->form_validation->set_rules('confirm', 'Konfirmasi Password', 'required|matches[password]');

			
			if($this->form_validation->run()== false) {
				$this->libtemplate->main('admin/edit', $data);
			} else {
				if ($this->admin->update() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				}
				redirect(base_url().'admin');
			}
		}
		
		public function ubah_password($id)
		{
			$data['title']	= 'Ubah Password';
			$data['admin']	= $this->admin->getById($id);
			
			$this->form_validation->set_rules('id_user', 'ID User', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|alpha_dash');
			$this->form_validation->set_rules('confirm', 'Konfirmasi Password', 'required|matches[password]');

			
			if($this->form_validation->run()== false) {
				$this->libtemplate->main('admin/ubah_pass', $data);
			} else {
				if ($this->admin->change_password() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				}
				redirect(base_url().'admin');
			}
		}
		
		public function detail()
		{
			$admin	= $this->admin->getById($_REQUEST['id']);
			
			$data['title']	= 'Detail Data Admin';
			$data['user']	= $admin;

			$this->load->view('admin/detail', $data);
		}
		
		public function delete()
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus data admin?';
			$data['button']	= '
				<a href="admin/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id)
		{
			$delete	= $this->admin->delete($id);
			if( $delete > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else if( $delete == -1 ) {
				$this->session->set_flashdata('warning', 'Harus ada minimal 1 admin!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect(base_url().'admin');
		}
	}
?>