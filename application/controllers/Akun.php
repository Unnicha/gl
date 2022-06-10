<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Akun extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Akun_model', 'akun');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
			$this->tipe_akun = ['Induk', 'Anak'];
			$this->golongan = ['NERACA', 'LABARUGI'];
		}

		public function index()
		{
			$data['title'] = 'Daftar Akun';
			$this->libtemplate->main('akun/index', $data);
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
			$order = implode(',', $order);
			
			$akun		= $this->akun->getAll($cari, $offset, $limit, $order);
			$countData	= $this->akun->countAll($cari);
			
			$data = [];
			foreach($akun as $k) {
				$saldo = $k['saldo_awal'] ? $k['saldo_awal'] : '-';
				if ($k['saldo_awal'])
				$saldo = ($saldo < 0) ? '( '.Globals::moneyView(abs($saldo)).' )' : Globals::moneyView($saldo);
				
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= $k['kode_akun'];
				$row[]	= $k['nama_akun'];
				$row[]	= $k['golongan'];
				$row[]	= $k['nama_jenis'];
				$row[]	= $k['saldo_normal'] ? $k['saldo_normal'] : '-';
				$row[]	= $saldo;
				$row[]	= '
					<a class="btn-detail badge badge-primary badge-action" data-id="'.$k['kode_akun'].'" data-toggle="tooltip" data-placement="left" title="Detail">
						<i class="bi bi-info-circle icon-medium"></i>
					</a>
					<a href="'.base_url('akun/edit/'.$k['kode_akun']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
						<i class="bi bi-pencil-square icon-medium"></i>
					</a>
					<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['kode_akun'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
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
		
		public function tambah() 
		{
			$data['title']		= 'Tambah Akun';
			$data['golongan']	= $this->golongan;
			$data['tipe']		= $this->tipe_akun;
			
			$tipe		= (isset($_REQUEST['submit'])) ? $_REQUEST['tipe'] : '';
			$golongan	= (isset($_REQUEST['submit'])) ? $_REQUEST['golongan'] : '';
			$rule_jenis	= ($tipe == 'Induk' && $golongan == 'LABARUGI') ? '' : 'required';
			$rule_saldo	= ($tipe == 'Anak') ? 'required' : '';
			
			$this->form_validation->set_rules('kode_akun', 'Kode Akun', 'required|callback_cek_kode');
			$this->form_validation->set_rules('nama_akun', 'Nama Akun', 'required');
			$this->form_validation->set_rules('golongan', 'Golongan', 'required');
			$this->form_validation->set_rules('tipe', 'Tipe', 'required');
			$this->form_validation->set_rules('tingkat', 'Tingkat', 'required');
			$this->form_validation->set_rules('jenis', 'Jenis', $rule_jenis);
			$this->form_validation->set_rules('induk', 'Induk', '');
			$this->form_validation->set_rules('saldo_normal', 'Saldo Normal', $rule_saldo);
			$this->form_validation->set_rules('saldo_awal', 'Saldo Awal', $rule_saldo);
			$this->form_validation->set_rules('nilai_saldo_awal', 'Saldo Awal', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('akun/tambah', $data);
			} else {
				$result = $this->akun->save();
				if ($result > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal ditambahkan!');
				}
				redirect( base_url('akun') );
			}
		}

		//update product to database
		public function edit($id) 
		{
			$akun = $this->akun->getById($id);
			$akun['nilai_saldo_awal']	= ($akun['saldo_awal'] < 0) ? 'Kredit' : 'Debit';
			$akun['saldo_awal']			= abs($akun['saldo_awal']);
			
			$data['title']		= 'Edit Akun';
			$data['tipe']		= $this->tipe_akun;
			$data['golongan']	= $this->golongan;
			$data['jenis']		= $this->akun->getJenis($akun['tipe_akun']);
			$data['induk']		= $this->akun->getInduk($akun['tingkat'] - 1);
			$data['perkiraan']	= $akun;
			
			$tipe		= (isset($_REQUEST['submit'])) ? $_REQUEST['tipe'] : '';
			$golongan	= (isset($_REQUEST['submit'])) ? $_REQUEST['golongan'] : '';
			$rule_jenis	= ($tipe == 'Induk' && $golongan == 'LABARUGI') ? '' : 'required';
			$rule_saldo	= ($tipe == 'Anak') ? 'required' : '';
			
			$this->form_validation->set_rules('kode_akun', 'Kode Akun', 'required');
			$this->form_validation->set_rules('nama_akun', 'Nama Akun', 'required');
			$this->form_validation->set_rules('golongan', 'Golongan', 'required');
			$this->form_validation->set_rules('tipe', 'Tipe', 'required');
			$this->form_validation->set_rules('tingkat', 'Tingkat', 'required');
			$this->form_validation->set_rules('jenis', 'Jenis', $rule_jenis);
			$this->form_validation->set_rules('induk', 'Induk', '');
			$this->form_validation->set_rules('saldo_normal', 'Saldo Normal', $rule_saldo);
			$this->form_validation->set_rules('saldo_awal', 'Saldo Awal', $rule_saldo);
			$this->form_validation->set_rules('nilai_saldo_awal', 'Saldo Awal', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('akun/edit', $data);
			} else {
				$result = $this->akun->update();
				if ($result > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				// } else {
				// 	$this->session->set_flashdata('warning', 'Gagal diubah!');
				}
				redirect( base_url('akun') );
			}
		}
		
		public function detail() 
		{
			$data['judul']		= 'Detail Akun';
			$data['perkiraan']	= $this->akun->getById($_REQUEST['id']);
			
			$this->load->view('akun/detail', $data);
		}
		
		public function cetak() 
		{
			$data['title'] = 'Cetak Akun';
			$data['akun'] = $this->akun->get_perkiraan();
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('users/akun/cetak');
		}
		
		public function delete() 
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus kode perkiraan?';
			$data['button']	= '
				<a href="akun/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id) 
		{
			if( $this->akun->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect( base_url('akun') );
		}
		
		public function cek_kode($kode)
		{
			if (!preg_match("/^[a-zA-Z0-9.]*$/", $kode) && substr($kode, -1) == ".") {
				$this->form_validation->set_message('cek_kode', 'Format {field} belum benar.');
				return FALSE;
			} else {
				$akun = $this->akun->getAll();
				foreach ($akun as $a) {
					if ($kode == $a['kode_akun']) {
						$this->form_validation->set_message('cek_kode', '{field} sudah digunakan.');
						return FALSE;
					}
				}
				return TRUE;
			}
		}

		// get Jenis by Tipe
		public function getJenis()
		{
			$flag	= 0;
			$value	= isset($_REQUEST['value']) ? $_REQUEST['value'] : '';
			$list	= '<option value="">---</option>';
			$jenis	= $this->akun->getJenis($_REQUEST['tipe']);
			
			if($_REQUEST['gol'] == 'LABARUGI' && $_REQUEST['tipe'] == 'Induk') {
				$flag = 1;
			}
			if($flag == 0) {
				$list = '<option value="">Pilih Jenis</option>';
				foreach($jenis as $i) {
					$pilih = ($value == $i['id_jenis']) ? 'selected' : '';
					$list .= '<option value="'.$i['id_jenis'].'" '.$pilih.'>'.$i['nama_jenis'].'</option>';
				}
			}
			echo $list;
		}
		
		public function getInduk() 
		{
			$list		= '<option value="">Pilih Induk</option>';
			$value		= isset($_REQUEST['value']) ? $_REQUEST['value'] : '';
			$tingkat	= isset($_REQUEST['tingkat']) ? $_REQUEST['tingkat'] : 0;
			$induk		= $this->akun->getInduk( $tingkat-1 );
			
			foreach($induk as $i) {
				$pilih = ($value == $i['kode_akun']) ? 'selected' : '';
				$list .= '<option value="'.$i['kode_akun'].'" '.$pilih.'>'.$i['kode_akun'].' - '.$i['nama_akun'].'</option>';
			}
			
			echo $list;
		}
		
		public function cekKode() 
		{
			$msg = '<small class="text-success">Kode Akun tersedia</small>';
			if ($this->akun->getById($_REQUEST['kode_akun'])) 
				$msg = '<small class="text-danger">Kode Akun sudah digunakan</small>';
			
			echo $msg;
		}
	}