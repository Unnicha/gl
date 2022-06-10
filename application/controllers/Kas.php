<?php defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Kas extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Kas_model', 'kas');
			$this->load->model('Akun_model', 'akun');
			$this->load->model('Uang_model', 'mata_uang');
			$this->load->model('Kurs_model', 'kurs');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
			
			// untuk membatasi tanggal transaksi sesuai bulan & tahun aktif
			$active_date	= $_SESSION['bulan_aktif'].'/01/'.$_SESSION['tahun_aktif'];
			$this->min_date	= isset($_SESSION['bulan_aktif']) ? date('d/m/Y', strtotime($active_date)) : '';
			$this->max_date	= isset($_SESSION['bulan_aktif']) ? date('t/m/Y', strtotime($active_date)) : '';
		}

		public function index()
		{
			$data['title'] = 'Transaksi Kas';
			$this->libtemplate->main('kas/index', $data);
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
			$order	= implode(',', $order);
			$bulan	= $this->session->userdata('bulan');
			$tahun	= $this->session->userdata('tahun');
			
			$transaksi	= $this->kas->getAll($cari, $offset, $limit, $order, $bulan, $tahun);
			$countData	= $this->kas->countAll($cari, $bulan, $tahun);
			
			$data	= [];
			$saldo	= 0;
			foreach($transaksi as $k) {
				$jumlah		= $k['jumlah'] * $k['konversi'];
				$jumlah		= ($k['jenis_saldo'] == 'Debit') ? $jumlah : -$jumlah;
				$saldo		= $saldo + $jumlah;
				$show_jum	= number_format(abs($jumlah),2,',','.');
				$show_saldo	= number_format(abs($saldo),2,',','.');
				
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= Globals::dateView($k['tanggal_transaksi']);
				$row[]	= $k['kode_transaksi'];
				$row[]	= $k['ket_transaksi'];
				$row[]	= ($k['jenis_saldo'] == 'Debit') ? $show_jum : '-';
				$row[]	= ($k['jenis_saldo'] == 'Kredit') ? $show_jum : '-';
				$row[]	= ($saldo < 0) ? '( '.$show_saldo.' )' : $show_saldo;
				$row[]	= '
					<a class="btn-detail badge badge-primary badge-action" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="left" title="Detail">
						<i class="bi bi-info-circle icon-medium"></i>
					</a>
					<a href="'.base_url('kas/edit/'.$k['kode_transaksi']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
						<i class="bi bi-pencil-square icon-medium"></i>
					</a>
					<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
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
		
		public function getKonversi()
		{
			$mataUang	= $this->input->post('mata_uang');
			$tanggal	= $this->input->post('tanggal');
			$tanggal	= ($tanggal) ? Globals::dateView($tanggal) : date('Y/m/d');
			$kurs		= $this->kurs->getKurs($mataUang, $tanggal);
			echo ($kurs) ? $kurs['nilai_kurs'] : '';
		}
		
		public function getTotal()
		{
			$kurs	= str_replace(['.', ','], ['', '.'], $_REQUEST['konversi'] ? $_REQUEST['konversi'] : 0);
			$jumlah	= str_replace(['.', ','], ['', '.'], $_REQUEST['jumlah'] ? $_REQUEST['jumlah'] : 0);
			$total	= $kurs * $jumlah;
			echo number_format($total, 2);
		}
		
		public function tambah()
		{
			$data['title']		= 'Tambah Transaksi Kas';
			$data['akun_asal']	= $this->akun->getByJenis('21');
			$data['akun_lawan']	= $this->akun->getByTipe('Anak');
			$data['mata_uang']	= $this->mata_uang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->form_validation->set_rules('status_jurnal', 'Status Jurnal', 'required');
			$this->form_validation->set_rules('tanggal', 'Tanggal Transaksi', 'required');
			$this->form_validation->set_rules('jenis_saldo', 'Jenis Saldo', 'required');
			$this->form_validation->set_rules('akun_asal', 'Rekening Asal', 'required');
			$this->form_validation->set_rules('akun_lawan', 'Rekening Lawan', 'required');
			$this->form_validation->set_rules('jumlah', 'Jumlah Transaksi', 'required');
			$this->form_validation->set_rules('konversi', 'Nilai Konversi', 'required');
			$this->form_validation->set_rules('mata_uang', 'Mata Uang', 'required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('kas/tambah', $data);
			} else {
				if ($this->kas->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal ditambahkan!');
				}
				redirect(base_url().'kas');
			}
		}
		
		public function edit($id)
		{
			$transaksi	= $this->kas->getById($id);
			$transaksi['tanggal_transaksi']	= Globals::dateView($transaksi['tanggal_transaksi']);
			
			$data['title']		= 'Ubah Transaksi Kas';
			$data['transaksi']	= $transaksi;
			$data['akun_asal']	= $this->akun->getByJenis('21');
			$data['akun_lawan']	= $this->akun->getByTipe('Anak');
			$data['mata_uang']	= $this->mata_uang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->form_validation->set_rules('kode_transaksi', 'Kode Transaksi', 'required');
			$this->form_validation->set_rules('status_jurnal', 'Status Jurnal', 'required');
			$this->form_validation->set_rules('tanggal', 'Tanggal Transaksi', 'required');
			$this->form_validation->set_rules('jenis_saldo', 'Jenis Saldo', 'required');
			$this->form_validation->set_rules('akun_asal', 'Rekening Asal', 'required');
			$this->form_validation->set_rules('akun_lawan', 'Rekening Lawan', 'required');
			$this->form_validation->set_rules('jumlah', 'Jumlah Transaksi', 'required');
			$this->form_validation->set_rules('konversi', 'Nilai Konversi', 'required');
			$this->form_validation->set_rules('mata_uang', 'Mata Uang', 'required');
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('kas/edit', $data);
			} else {
				if ($this->kas->edit() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				} else {
					$this->session->set_flashdata('warning', 'Gagal diubah!');
				}
				redirect(base_url().'kas');
			}
		}

		public function detail()
		{
			$kode	= $_REQUEST['id'];
			$kas	= $this->kas->getById($kode);
			$total	= $kas['jumlah'] * $kas['konversi'];
			
			$kas['tanggal_transaksi']	= Globals::dateView($kas['tanggal_transaksi']);
			$kas['show_jumlah']			= number_format($kas['jumlah'],2,',','.'); 
			$kas['show_konv']			= number_format($kas['konversi'],2,',','.');
			$kas['show_total']			= number_format($total,2,',','.'); 
			
			$data['title']		= 'Detail Transaksi Kas';
			$data['transaksi']	= $kas;

			$this->load->view('kas/detail', $data);
		}
		
		public function delete()
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus transaksi?';
			$data['button']	= '
				<a href="kas/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id)
		{
			if( $this->kas->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect(base_url().'kas');
		}
	}
?>