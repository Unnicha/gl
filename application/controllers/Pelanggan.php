<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Pelanggan extends CI_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Pelanggan_model','pelanggan');
			$this->load->model('Akun_model', 'akun');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
		}

		public function index() 
		{
			$data['title'] = 'Pelanggan';
			$this->libtemplate->main('pelanggan/index', $data);
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
			$order = implode(',', $order);
			
			$pelanggan	= $this->pelanggan->getAll($cari, $offset, $limit, $order);
			$countData	= $this->pelanggan->countAll($cari);
			
			$data = [];
			foreach($pelanggan as $k) {
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= $k['kode_pelanggan'];
				$row[]	= $k['nama_pelanggan'];
				$row[]	= $k['telp'];
				$row[]	= $k['email'];
				$row[]	= '
					<a class="btn-detail badge badge-primary badge-action" data-id="'.$k['kode_pelanggan'].'" data-toggle="tooltip" data-placement="left" title="Detail">
						<i class="bi bi-info-circle icon-medium"></i>
					</a>
					<a href="'.base_url('pelanggan/edit/'.$k['kode_pelanggan']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
						<i class="bi bi-pencil-square icon-medium"></i>
					</a>
					<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['kode_pelanggan'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
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
		
		public function cekKode() 
		{
			if($_REQUEST['kode']) {
				$pelanggan = $this->pelanggan->getById($_REQUEST['kode']);
				if($pelanggan)
				echo '<small class="text-danger">Kode Pelanggan sudah digunakan.</small>';
				else
				echo '<small class="text-success">Kode Pelanggan tersedia.</small>';
			} else 
			echo '<small class="text-danger">Kode Pelanggan belum diisi.</small>';
		}
		
		public function tambah() 
		{
			$data['title']	= 'Tambah Pelanggan';
			$data['kas']	= $this->akun->getByJenis('21');
			$data['bank']	= $this->akun->getByJenis('22');
			
			$this->form_validation->set_rules('nama_pelanggan','Nama Pelanggan','required');
			$this->form_validation->set_rules('npwp_pelanggan','NPWP','required');
			$this->form_validation->set_rules('alamat_pelanggan','Alamat','required');
			$this->form_validation->set_rules('email_pelanggan','Email','required|valid_email');
			$this->form_validation->set_rules('tlp_pelanggan','Telepon','required');
			$this->form_validation->set_rules('fax_pelanggan','Fax','required');
			$this->form_validation->set_rules('akun_kas','Akun Kas','required');
			$this->form_validation->set_rules('akun_bank','Akun Bank','required');
			
			if($this->form_validation->run() == false) {
				$this->libtemplate->main('pelanggan/tambah', $data);
			} else {
				$result = $this->pelanggan->add();
				if ($result > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal ditambahkan!');
				}
				redirect( base_url('pelanggan') );
			}
		}
		
		public function edit($id) 
		{
			$data['title']		= 'Edit Pelanggan';
			$data['kas']		= $this->akun->getByJenis('21');
			$data['bank']		= $this->akun->getByJenis('22');
			$data['pelanggan']	= $this->pelanggan->getById($id);
			
			$this->form_validation->set_rules('kode_pelanggan','Kode Pelanggan','required');
			$this->form_validation->set_rules('nama_pelanggan','Nama Pelanggan','required');
			$this->form_validation->set_rules('npwp_pelanggan','NPWP','required');
			$this->form_validation->set_rules('alamat_pelanggan','Alamat','required');
			$this->form_validation->set_rules('email_pelanggan','Email','required|valid_email');
			$this->form_validation->set_rules('tlp_pelanggan','Telepon','required');
			$this->form_validation->set_rules('fax_pelanggan','Fax','required');
			$this->form_validation->set_rules('akun_kas','Akun Kas','required');
			$this->form_validation->set_rules('akun_bank','Akun Bank','required');
			
			if($this->form_validation->run()== false) {
				$this->libtemplate->main('pelanggan/edit', $data);
			} else {
				$result = $this->pelanggan->edit();
				if ($result > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				}
				redirect( base_url('pelanggan') );
			}
		}
		
		public function detail() 
		{
			$data['judul']		= 'Detail Pelanggan';
			$data['pelanggan']	= $this->pelanggan->getById($_REQUEST['id']);
			
			$this->load->view('pelanggan/detail', $data);
		}
		
		public function delete() 
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus pelanggan?';
			$data['button']	= '
				<a href="pelanggan/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id) 
		{
			if( $this->pelanggan->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect( base_url('pelanggan') );
		}
	}
