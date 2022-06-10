<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Setup_perusahaan extends CI_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Perusahaan_model', 'perusahaan');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
		}
		
		public function index() 
		{
			$data['title'] = 'Setup Perusahaan';
			$this->libtemplate->main('setup_perusahaan/index', $data);
		}
		
		public function table() 
		{
			$tab		= $_POST['tab'];
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
			
			$perusahaan	= $this->perusahaan->getSetup($cari, $offset, $limit, $order);
			$countData	= $this->perusahaan->countSetup($cari);
			
			$data = [];
			foreach($perusahaan as $k) {
				$btn_edit = '
				<a href="'.base_url('setup_perusahaan/edit/'.$k['id_setup']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
					<i class="bi bi-pencil-square icon-medium"></i>
				</a>';
				
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= $k['nama_perusahaan'];
				$row[]	= $k['tahun'];
				$row[]	= Globals::dateView($k['tgl_mulai']);
				$row[]	= $k['database'];
				$row[]	= '
					<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['id_setup'].'" data-toggle="tooltip" data-placement="left" title="Hapus">
					<i class="bi bi-trash icon-medium"></i>
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
			$data['title']		= 'Tambah Perusahaan';
			$data['perusahaan']	= $this->perusahaan->getAll();
			
			$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
			$this->form_validation->set_rules('tgl_mulai', 'Tanggal Mulai', 'required');
			$this->form_validation->set_rules('tahun', 'Tahun', 'required');
			
			if($this->form_validation->run() == false) {
				$this->libtemplate->main('setup_perusahaan/tambah', $data);
			} else {
				if ($this->perusahaan->addSetup() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal ditambahkan!');
				}
				redirect('setup_perusahaan');
			}
		}
		
		public function edit($id) 
		{
			$perusahaan	= $this->perusahaan->getSetupById($id);
			$perusahaan['tgl_mulai']	= Globals::dateView($perusahaan['tgl_mulai']);
			
			$data['title']		= 'Edit Perusahaan';
			$data['perusahaan']	= $perusahaan;
			
			$this->form_validation->set_rules('id_setup', 'ID Setup', 'required');
			$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
			$this->form_validation->set_rules('tgl_mulai', 'Tanggal Mulai', 'required');
			$this->form_validation->set_rules('tahun', 'Tahun', 'required');
			
			if($this->form_validation->run()== false) {
				$this->libtemplate->main('setup_perusahaan/edit', $data);
			} else {
				if ($this->perusahaan->updateSetup() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				}
				redirect('setup_perusahaan');
			}

		}
		
		public function delete() 
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus Setup Perusahaan?';
			$data['button']	= '
				<a href="setup_perusahaan/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id) 
		{
			if( $this->perusahaan->deleteSetup($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect('setup_perusahaan');
		}
	}
?>