<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Perusahaan extends CI_Controller 
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
			$data['title'] = 'Data Perusahaan';
			$this->libtemplate->main('perusahaan/index', $data);
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
			
			$perusahaan	= $this->perusahaan->getAll($cari, $offset, $limit, $order);
			$countData	= $this->perusahaan->countAll($cari);
			
			$data = [];
			foreach($perusahaan as $k) {
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= $k['kode_perusahaan'];
				$row[]	= $k['nama_perusahaan'];
				$row[]	= $k['tlp'];
				// $row[]	= $k['fax'];
				$row[]	= $k['npwp'];
				$row[]	= $k['kode_pajak'];
				// $row[]	= $k['tgl_pkp'];
				$row[]	= '
					<a class="btn-detail badge badge-primary badge-action" data-id="'.$k['kode_perusahaan'].'" data-toggle="tooltip" data-placement="left" title="Detail">
						<i class="bi bi-info-circle icon-medium"></i>
					</a>
					<a href="'.base_url('perusahaan/edit/'.$k['kode_perusahaan']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
						<i class="bi bi-pencil-square icon-medium"></i>
					</a>
					<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['kode_perusahaan'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
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
			$data['title']	= 'Tambah Perusahaan';

			$this->form_validation->set_rules('kode_perusahaan', 'Kode Perusahaan', 'required|is_unique[perusahaan.kode_perusahaan]');
			$this->form_validation->set_rules('nama_perusahaan', 'Nama Perusahaan', 'required');
			$this->form_validation->set_rules('alamat', 'Alamat', 'required');
			$this->form_validation->set_rules('tlp', 'Telepon', 'required');
			$this->form_validation->set_rules('fax', 'Fax', 'required');
			$this->form_validation->set_rules('npwp', 'NPWP', 'required');
			$this->form_validation->set_rules('kode_pajak', 'Kode Pajak', 'required');
			$this->form_validation->set_rules('tgl_pkp', 'Tanggal PKP', 'required');
			
			if($this->form_validation->run() == false) {
				$this->libtemplate->main('perusahaan/tambah', $data);
			} else {
				if ($this->perusahaan->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal ditambahkan!');
				}
				redirect('perusahaan');
			}
		}
		
		public function edit($id) 
		{
			$perusahaan	= $this->perusahaan->getById($id);
			$perusahaan['tgl_pkp']	= Globals::dateView($perusahaan['tgl_pkp']);
			
			$data['title']		= 'Edit Perusahaan';
			$data['perusahaan']	= $perusahaan;

			$this->form_validation->set_rules('nama_perusahaan', 'Nama Perusahaan', 'required');
			$this->form_validation->set_rules('alamat', 'Alamat', 'required');
			$this->form_validation->set_rules('tlp', 'Telepon', 'required');
			$this->form_validation->set_rules('fax', 'Fax', 'required');
			$this->form_validation->set_rules('npwp', 'NPWP', 'required');
			$this->form_validation->set_rules('kode_pajak', 'Kode Pajak', 'required');
			$this->form_validation->set_rules('tgl_pkp', 'Tanggal PKP', 'required');
			
			if($this->form_validation->run()== false) {
				$this->libtemplate->main('perusahaan/edit', $data);
			} else {
				if ($this->perusahaan->update() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				}
				redirect('perusahaan');
			}

		}
		
		public function detail() 
		{
			$perusahaan	= $this->perusahaan->getById($_REQUEST['id']);
			$perusahaan['tgl_pkp']	= Globals::dateView($perusahaan['tgl_pkp']);
			
			$data['title']		= 'Detail Data Perusahaan';
			$data['perusahaan']	= $perusahaan;
			
			$this->load->view('perusahaan/detail', $data);
		}
		
		public function delete() 
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus Data Perusahaan?';
			$data['button']	= '
				<a href="perusahaan/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id) 
		{
			if( $this->perusahaan->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect('perusahaan');
		}
	}
?>