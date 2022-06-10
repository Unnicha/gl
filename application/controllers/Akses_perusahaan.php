<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Akses_perusahaan extends CI_Controller 
	{
		public function __construct() {
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Akses_perusahaan_model','akses_perusahaan');
			$this->load->model('Data_perusahaan_model','data_perusahaan');
			$this->load->model('Data_anggota_model','data_anggota');
			$this->load->library('form_validation');
			
		}
		
		public function index($id_user) {
			$data['title'] = 'Akses ke Perusahaan';
			$data['user']	= $this->data_anggota->getById($id_user);
			
			$this->libtemplate->main('akses_perusahaan/index', $data);
		}

		public function table() {
			$offset		= $_POST['start'];
			$limit		= $_POST['length'];
			$cari		= $_POST['search']['value'];
			$id_user	= $_POST['id_user'];
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
			
			$akses	= $this->akses_perusahaan->aksesPerusahaan($id_user);
			$id_pt	= ($akses) ? explode(',', $akses['kode_perusahaan']) : '';
			
			$perusahaan	= $this->akses_perusahaan->getPerusahaan($id_pt, $cari, $offset, $limit, $order);
			$countData	= $this->akses_perusahaan->countPerusahaan($id_pt, $cari);
			
			$data = [];
			foreach($perusahaan as $k) {
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= $k['nama_perusahaan'];
				$row[]	= '
					<a class="btn-tampil badge badge-primary badge-action" data-id="'.$k['id_perusahaan'].'" data-toggle="tooltip" data-placement="left" title="Tampil">
					<i class="bi bi-search"></i>
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

		public function ubah($id_user){

			$data['title'] = 'Akses ke Perusahaan';
			$data['data_perusahaan'] = $this->data_perusahaan->getAll();
			$data['user']	= $this->data_anggota->getById($id_user);
			$akses = $this->akses_perusahaan->getById($id_user);
			$data['akses'] = $akses ? explode(",",$akses['kode_perusahaan']) : [];

			$this->form_validation->set_rules('user', 'Kode Perusahaan', 'required');

				
			if($this->form_validation->run() == false) {
				$this->libtemplate->main('akses_perusahaan/ubah', $data);
			} else {
				if ($this->akses_perusahaan->ubah() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Berhasil ditambahkan!');
				}
				redirect(base_url().'user/akses_perusahaan/index/'.$id_user);
			}
		}
	}