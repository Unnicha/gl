<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Supplier extends CI_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Supplier_model','supplier');
			$this->load->model('Akun_model', 'akun');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
		}

		public function index() 
		{
			$data['title'] = 'Supplier';
			$this->libtemplate->main('supplier/index', $data);
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
			
			$supplier	= $this->supplier->getAll($cari, $offset, $limit, $order);
			$countData	= $this->supplier->countAll($cari);
			
			$data = [];
			foreach($supplier as $k) {
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= $k['kode_supplier'];
				$row[]	= $k['nama_supplier'];
				$row[]	= $k['telp'];
				$row[]	= $k['email'];
				$row[]	= '
					<a class="btn-detail badge badge-primary badge-action" data-id="'.$k['kode_supplier'].'" data-toggle="tooltip" data-placement="left" title="Detail">
						<i class="bi bi-info-circle icon-medium"></i>
					</a>
					<a href="'.base_url('supplier/edit/'.$k['kode_supplier']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
						<i class="bi bi-pencil-square icon-medium"></i>
					</a>
					<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['kode_supplier'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
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
			if ($_REQUEST['kode']) {
				$supplier = $this->supplier->getById($_REQUEST['kode']);
				if($supplier)
				echo '<small class="text-danger">Kode Supplier sudah digunakan.</small>';
				else
				echo '<small class="text-success">Kode Supplier tersedia.</small>';
			} else 
			echo '<small class="text-danger">Kode Supplier belum diisi.</small>';
		}
		
		public function tambah() 
		{
			$data['title']	= 'Tambah Supplier';
			$data['kas']	= $this->akun->getByJenis('21');
			$data['bank']	= $this->akun->getByJenis('22');
			$data['utang']	= $this->akun->getByJenis('43');
			
			$this->form_validation->set_rules('nama_supplier','Nama Supplier','required');
			$this->form_validation->set_rules('npwp_supplier','NPWP','required|min_length[20]',
				['min_length'=>'Format {field} tidak sesuai.']
			);
			$this->form_validation->set_rules('alamat_supplier','Alamat','required');
			$this->form_validation->set_rules('email_supplier','Email','required|valid_email');
			$this->form_validation->set_rules('tlp_supplier','Telepon','required|min_length[15]',
				['min_length'=>'Format {field} tidak sesuai.']
			);
			$this->form_validation->set_rules('fax_supplier','Fax','required|min_length[15]',
				['min_length'=>'Format {field} tidak sesuai.']
			);
			$this->form_validation->set_rules('akun_kas','Akun Kas','required');
			$this->form_validation->set_rules('akun_bank','Akun Bank','required');
			$this->form_validation->set_rules('akun_utang','Akun Hutang','required');
			
			if ($this->form_validation->run() == false) {
				$this->libtemplate->main('supplier/tambah', $data);
			} else {
				if ($this->supplier->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal ditambahkan!');
				}
				redirect(base_url().'supplier');
			}
		}
		
		public function edit($id) 
		{
			$data['title']		= 'Edit Supplier';
			$data['kas']		= $this->akun->getByJenis('21');
			$data['bank']		= $this->akun->getByJenis('22');
			$data['utang']		= $this->akun->getByJenis('43');
			$data['supplier']	= $this->supplier->getById($id);
			
			$this->form_validation->set_rules('kode_supplier','Kode Supplier','required');
			$this->form_validation->set_rules('nama_supplier','Nama Supplier','required');
			$this->form_validation->set_rules('npwp_supplier','NPWP','required|min_length[20]',
				['min_length'=>'Format {field} tidak sesuai.']
			);
			$this->form_validation->set_rules('alamat_supplier','Alamat','required');
			$this->form_validation->set_rules('email_supplier','Email','required|valid_email');
			$this->form_validation->set_rules('tlp_supplier','Telepon','required|min_length[15]',
				['min_length'=>'Format {field} tidak sesuai.']
			);
			$this->form_validation->set_rules('fax_supplier','Fax','required|min_length[15]',
				['min_length'=>'Format {field} tidak sesuai.']
			);
			$this->form_validation->set_rules('akun_kas','Akun Kas','required');
			$this->form_validation->set_rules('akun_bank','Akun Bank','required');
			$this->form_validation->set_rules('akun_utang','Akun Hutang','required');
			
			if ($this->form_validation->run()== false) {
				$this->libtemplate->main('supplier/edit', $data);
			} else {
				if ($this->supplier->edit() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal diubah!');
				}
				redirect(base_url().'supplier');
			}

		}
		
		public function detail() 
		{
			$id	= $_REQUEST['id'];
			
			$data['judul']		= 'Detail Supplier';
			$data['supplier']	= $this->supplier->getById($id);
			
			$this->load->view('supplier/detail', $data);
		}
		
		public function delete() 
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus supplier?';
			$data['button']	= '
				<a href="supplier/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id) 
		{
			if( $this->supplier->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect(base_url().'supplier');
		}
		
		public function phone($value)
		{
			if (empty($value)) {
				$this->form_validation->set_message('username_check', '{field} belum diisi');
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}
