<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Mata_uang extends CI_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Uang_model','mata_uang');
			$this->load->library('form_validation');
		}
		
		public function index() 
		{
			$data['title'] = 'Mata Uang';
			$this->libtemplate->main('mata_uang/index', $data);
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
					break;
				}
			}
			$order = implode(',', $order);
			
			$mata_uang	= $this->mata_uang->getAll($cari, $offset, $limit, $order);
			$countData	= $this->mata_uang->countAll($cari);
			
			$data = [];
			foreach($mata_uang as $k) {
				$delete	= '<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['kode_mu'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
					<i class="bi bi-trash icon-medium"></i>
				</a>';
				
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= $k['kode_mu'];
				$row[]	= $k['nama_mu'];
				$row[]	= ($k['kode_mu'] == 'IDR') ? '' : $delete ;
				
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
			$data['title'] = 'Tambah Mata Uang';
			$data['list']	= $this->mata_uang->daftarMataUang();
			
			$this->form_validation->set_rules('kode_mu','Kode Mata Uang','required|callback_kode_unique');
			$this->form_validation->set_rules('nama_mu','Nama Mata Uang','required');

			if($this->form_validation->run() == false) {
				$this->libtemplate->main('mata_uang/tambah', $data);
			} else {
				if ($this->mata_uang->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal ditambahkan!');
				}
				redirect( base_url('mata_uang') );
			}
		}
		
		public function delete() 
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus mata uang?';
			$data['button']	= '
				<a href="mata_uang/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id) 
		{
			if( $this->mata_uang->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect( base_url('mata_uang') );
		}
		
		public function getKode() 
		{
			$kode = '';
			$list = $this->mata_uang->daftarMataUang();
			foreach($list as $l) {
				if($l['nama'] == $_REQUEST['name']) 
				$kode = $l['kode'];
			}
			echo $kode;
		}
		
		public function kode_unique($kode)
		{
			$mata_uang = $this->mata_uang->getAll();
			foreach ($mata_uang as $uang) {
				if ($kode == $uang['kode_mu']) {
					$this->form_validation->set_message('kode_unique', 'Mata uang sudah ada.');
					return FALSE;
				}
			}
			return TRUE;
		}
	}
