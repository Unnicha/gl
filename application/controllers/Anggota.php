<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Anggota extends CI_Controller 
	{

		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Anggota_model', 'anggota');
			$this->load->model('Perusahaan_model', 'perusahaan');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
		}

		public function index()
		{
			$data['title'] = 'Data Anggota';
			$this->libtemplate->main('anggota/index', $data);
		}
		
		public function table()
		{
			$offset		= $_POST['start'];
			$limit		= $_POST['length'];
			$cari		= $_POST['search']['value'];
			
			$order	= [];
			foreach($_POST['order'] as $key => $sort) {
				$order_id	= $_POST['order'][$key]['column'];
				$order_dir	= $_POST['order'][$key]['dir'];
				$order_by	= $_POST['columns'][$order_id]['name'];
				$order[]	= $order_by.' '.$order_dir;
			}
			$order	= implode(',', $order);
			
			$anggota	= $this->anggota->getAll($cari, $offset, $limit, $order);
			$countData	= $this->anggota->countAll($cari);
			
			$data = [];
			foreach($anggota as $k) {
				$btn_detail = '
					<a class="btn-detail badge badge-primary badge-action" data-id="'.$k['id_user'].'" data-toggle="tooltip" data-placement="left" title="Detail">
						<i class="bi bi-info-circle"></i>
					</a>
					<a href="'.base_url('akses_perusahaan/index/'.$k['id_user']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Edit">
						<i class="bi bi-pencil-square"></i>
					</a>';
				
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= $k['id_user'];
				$row[]	= $k['username'];
				$row[]	= $k['nama'];
				$row[]	= '
					<a class="btn-akses badge badge-primary badge-action" data-id="'.$k['id_user'].'" data-toggle="tooltip" data-placement="left" title="Akses Perusahaan">
						<i class="bi bi-briefcase-fill"></i>
					</a>
					<a href="'.base_url('anggota/ubah_password/'.$k['id_user']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah Password">
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
			$data['title']	= 'Tambah Anggota';

			$this->form_validation->set_rules('username', 'Username', 'required|min_length[8]|is_unique[user.username]');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
			$this->form_validation->set_rules('nama', 'Nama', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('confir', 'Konfirmasi Password', 'required|matches[password]');
			
			if($this->form_validation->run() == false) {
				$this->libtemplate->main('anggota/tambah', $data);
			} else {
				if ($this->anggota->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal ditambahkan!');
				}
				redirect(base_url().'anggota');
			}
		}
		
		public function edit($id)
		{
			$data['title']		= 'Edit Anggota';
			$data['anggota']	= $this->anggota->getById($id);

			$this->form_validation->set_rules('username', 'Username', 'required|min_length[8]');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
			$this->form_validation->set_rules('nama', 'Nama', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('confir', 'Konfirmasi Password', 'required|matches[password]');
			
			if($this->form_validation->run()== false) {
				$this->libtemplate->main('anggota/edit', $data);
			} else {
				if ($this->anggota->update() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				}
				redirect(base_url().'anggota');
			}
		}
			
		public function ubah_password($id)
		{
			$data['title']		= 'Ubah Password';
			$data['anggota']	= $this->anggota->getById($id);
			
			$this->form_validation->set_rules('id_user', 'ID User', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|alpha_dash');
			$this->form_validation->set_rules('confirm', 'Konfirmasi Password', 'required|matches[password]');

			
			if($this->form_validation->run()== false) {
				$this->libtemplate->main('anggota/ubah_pass', $data);
			} else {
				if ($this->anggota->change_password() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				}
				redirect(base_url().'anggota');
			}
		}
		
		public function detail_akses()
		{
			$anggota	= $this->anggota->getById($_REQUEST['id']);
			$akses		= explode(',', $anggota['akses_perusahaan']);
			$akses		= $this->perusahaan->getSetupById($akses);
			
			$data['title']		= 'Akses Perusahaan';
			$data['akses']		= $akses;
			$data['id_user']	= $_REQUEST['id'];
			
			$this->load->view('anggota/detail_akses', $data);
		}
			
		public function ubah_akses($id)
		{
			$anggota	= $this->anggota->getById($id);
			$akses		= explode(',', $anggota['akses_perusahaan']);
			// $akses		= $this->perusahaan->getById($akses);
			$perusahaan	= $this->perusahaan->getSetup();
			
			foreach($perusahaan as $key => $p) {
				$p['pilih']	= '';
				foreach ($akses as $a) {
					if ($p['id_setup'] == $a) {
						$p['pilih']	= 'selected';
					}
				}
				$perusahaan[$key] = $p;
			}
			
			$data['title']		= 'Ubah Akses';
			$data['anggota']	= $anggota;
			$data['perusahaan']	= $perusahaan;
			// $data['akses']		= $akses;
			
			$this->form_validation->set_rules('id_user', 'ID User', 'required');
			$this->form_validation->set_rules('perusahaan[]', 'Perusahaan', 'required');

			
			if($this->form_validation->run()== false) {
				$this->libtemplate->main('anggota/edit_akses', $data);
			} else {
				if ($this->anggota->change_akses() > 0) {
					$this->session->set_flashdata('notification', 'Akses berhasil diperbarui');
				}
				redirect(base_url().'anggota');
			}
		}
		
		public function detail()
		{
			$anggota	= $this->anggota->getById($_REQUEST['id']);
			
			$data['title']	= 'Detail Data Anggota';
			$data['user']	= $anggota;

			$this->load->view('anggota/detail', $data);
		}
		
		public function delete()
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus data anggota?';
			$data['button']	= '
				<a href="anggota/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id)
		{
			if( $this->anggota->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect(base_url().'anggota');
		}
	}
