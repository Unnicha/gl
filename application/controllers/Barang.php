<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Barang extends CI_Controller 
	{
		public function __construct() {
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Barang_model','barang');
			$this->load->library('form_validation');
		}

		public function index() {
			$data['title'] = 'Barang';
			$this->libtemplate->main('barang/index', $data);
		}
		
		public function table() {
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
			// $order_id	= $_POST['order'][0]['column'];
			// $order_by	= $_POST['columns'][$order_id]['name'];
			// $order_dir	= $_POST['order'][0]['dir'];
			
			$barang		= $this->barang->getAll($cari, $offset, $limit, $order);
			$countData	= $this->barang->countAll($cari);
			
			$data = [];
			foreach($barang as $k) {
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= $k['kode_barang'];
				$row[]	= $k['nama_barang'];
				$row[]	= $k['satuan'];
				$row[]	= $k['stok_awal'];
				$row[]	= $k['nilai_awal'];
				$row[]	= $k['stok_awal'] * $k['nilai_awal'];
				$row[]	= $k['proses'];
				$row[]	= '
					<a href="'.base_url('barang/edit/'.$k['kode_barang']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
						<i class="bi bi-pencil-square icon-medium"></i>
					</a>
					<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['kode_barang'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
					<i class="bi bi-trash icon-medium"></i>
					</a>';
				
				$data[] = $row;
			}
			
			$callback = [
				'draw'				=> $_POST['draw'], // Ini dari datatablenya
				'recordsTotal'		=> $countData,
				'recordsFiltered'	=> $countData,
				'data'				=> $data,
			];
			echo json_encode($callback);
		}
		
		public function tambah() {
			$data['title']	= 'Tambah Barang';
			
			$this->form_validation->set_rules('kode_barang','Kode Barang','required');
			$this->form_validation->set_rules('nama_barang','Nama Barang','required');
			$this->form_validation->set_rules('satuan','Satuan','required');
			$this->form_validation->set_rules('stock_awal','Stock Awal','required');
			$this->form_validation->set_rules('nilai_awal','Nilai Awal','required');
			$this->form_validation->set_rules('proses','Proses','required');

			if($this->form_validation->run() == false) {
				$this->libtemplate->main('barang/tambah', $data);
			} else {
				if ($this->barang->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal ditambahkan!');
				}
				redirect( base_url('barang') );
			}
		}
		
		public function edit($id) {
			$data['title']	= 'Edit Barang';
			$data['barang']	= $this->barang->getById($id);
			
			$this->form_validation->set_rules('kode_barang','Kode Barang','required');
			$this->form_validation->set_rules('nama_barang','Nama Barang','required');
			$this->form_validation->set_rules('satuan','Satuan','required');
			$this->form_validation->set_rules('stock_awal','Stock Awal','required');
			$this->form_validation->set_rules('nilai_awal','Nilai Awal','required');
			$this->form_validation->set_rules('proses','Proses','required');
			
			if($this->form_validation->run()== false) {
				$this->libtemplate->main('barang/edit', $data);
			} else {
				if ($this->barang->edit() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal ditambahkan!');
				}
				redirect( base_url('barang') );
			}
		}
		
		public function delete() {
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus barang?';
			$data['button']	= '
				<a href="barang/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id) {
			if( $this->barang->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect( base_url('barang') );
		}
	}
