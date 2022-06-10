<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Kurs extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Kurs_model','kurs');
			$this->load->model('Uang_model', 'mata_uang');
			$this->load->library('form_validation');
		}

		public function index()
		{
			$data['title'] = 'Kurs';
			$this->libtemplate->main('kurs/index', $data);
		}
		
		public function table()
		{
			$offset		= $_POST['start'];
			$limit		= $_POST['length'];
			$cari		= $_POST['search']['value'];
			$order		= [];
			for($i=0; $i<5; $i++) {
				if( $order_id = isset($_POST['order'][$i]) ? $_POST['order'][$i]['column'] : '' ) {
					$order_by	= $_POST['columns'][$order_id]['name'];
					$order_dir	= $_POST['order'][$i]['dir'];
					$order[]	= $order_by.' '.$order_dir;
				} else {
					$order[]	= 'tanggal desc';
					break;
				}
			}
			$order = implode(',', $order);
			
			$kurs		= $this->kurs->getAll($cari, $offset, $limit, $order);
			$countData	= $this->kurs->countAll($cari);
			
			$data = [];
			foreach($kurs as $k) {
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= Globals::dateView($k['tanggal']);
				$row[]	= $k['kode_mu'];
				$row[]	= $k['nama_mu'];
				$row[]	= number_format($k['nilai_kurs'],2,',','.');
				$row[]	= '';
				$row[]	= '
					<a href="'.base_url('kurs/edit/'.$k['kode_kurs']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
						<i class="bi bi-pencil-square icon-medium"></i>
					</a>
					<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['kode_kurs'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
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
			$data['title']		= 'Tambah Kurs';
			$data['mata_uang']	= $this->mata_uang->getAll();
			
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
			$this->form_validation->set_rules('mata_uang', 'Mata Uang', 'required');
			$this->form_validation->set_rules('nilai_kurs', 'Nilai Kurs', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('kurs/tambah', $data);
			} else {
				if ($this->kurs->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal ditambahkan!');
				}
				redirect( base_url('kurs') );
			}
		}
		
		//update product to database
		public function edit($id)
		{
			$kurs = $this->kurs->getById($id);
			$kurs['tanggal'] = Globals::dateView($kurs['tanggal']);
			
			$data['title']		= 'Edit Kurs';
			$data['kurs']		= $kurs;
			$data['mata_uang']	= $this->mata_uang->getAll();
			
			$this->form_validation->set_rules('kode_kurs', 'Kode Kurs', 'required');
			$this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
			$this->form_validation->set_rules('mata_uang', 'Mata Uang', 'required');
			$this->form_validation->set_rules('nilai_kurs', 'Nilai Kurs', 'required');
			
			if($this->form_validation->run()== false) {
				$this->libtemplate->main('kurs/edit', $data);
			} else {
				if ($this->kurs->edit() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				// } else {
				// 	$this->session->set_flashdata('warning', 'Gagal diubah!');
				}
				redirect( base_url('kurs') );
			}
		}
		
		public function delete()
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus kode perkiraan?';
			$data['button']	= '
				<a href="kurs/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id)
		{
			if( $this->kurs->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect( base_url('kurs') );
		}
	}