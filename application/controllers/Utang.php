<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Utang extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Utang_model', 'utang');
			$this->load->model('Pembelian_model', 'pembelian');
			$this->load->model('Retur_pembelian_model', 'retur_pembelian');
			$this->load->model('Supplier_model', 'supplier');
			$this->load->model('Akun_model', 'akun');
			$this->load->model('Uang_model', 'mata_uang');
			$this->load->model('Kurs_model', 'kurs');
			$this->load->model('Barang_model', 'barang');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
			unset($_SESSION['pembelian']);
			
			$this->pph = [
				'PPh pasal 4(2)',
				'PPh pasal 15',
				'PPh pasal 21',
				'PPh pasal 22',
				'PPh pasal 23',
				'PPh pasal 26',
			];
			$this->ppn = [ 'Include', 'Exclude', 'Non PPN' ];
			
			// untuk membatasi tanggal transaksi sesuai bulan & tahun aktif
			$active_date	= $_SESSION['bulan_aktif'].'/01/'.$_SESSION['tahun_aktif'];
			$this->min_date	= isset($_SESSION['bulan_aktif']) ? date('d/m/Y', strtotime($active_date)) : '';
			$this->max_date	= isset($_SESSION['bulan_aktif']) ? date('t/m/Y', strtotime($active_date)) : '';
		}

		public function index()
		{
			$data['title'] = 'Pembayaran Hutang';
			$this->libtemplate->main('utang/index', $data);
		}
		
		public function jurnal()
		{
			$data['title'] = 'Pembayaran Hutang';
			$this->libtemplate->main('utang/jurnal', $data);
		}
		
		/**
		 * Memproses data yang akan ditampilkan pada dataTable
		 */
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
			$order	= implode(',', $order);
			$bulan	= $this->session->userdata('tahun_aktif').'/'.$this->session->userdata('bulan_aktif');
			
			$data = [];
			$transaksi	= $this->utang->getAll($cari, $offset, $limit, $order, $bulan);
			$countData	= $this->utang->countAll($cari, $bulan);
			
			foreach($transaksi as $k) {
				
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= Globals::dateView($k['tanggal_transaksi']);
				$row[]	= '<a class="btn-detail transaksi" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="right" title="Detail">'.$k['kode_transaksi'].'</a>';
				$row[]	= $k['nama_supplier'];
				$row[]	= $k['faktur_beli'];
				$row[]	= Globals::moneyView($k['jumlah_bayar']);
				$row[]	= '
					<a class="btn-detail badge badge-primary badge-action" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="left" title="Detail">
						<i class="bi bi-info-circle icon-medium"></i>
					</a>
					<a href="'.base_url('utang/edit/'.$k['kode_transaksi']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
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
		
		public function tambah($pembelian='')
		{
			$data['title']		= 'Terima Pembayaran';
			$data['pembelian']	= $pembelian;
			$data['supplier']	= $this->supplier->getAll();
			$data['akun_asal']	= $this->akun->getByJenis(['21', '22']);
			$data['akun_ppn']	= $this->akun->getByJenis('34')[0]['kode_akun'];
			$data['mata_uang']	= $this->mata_uang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->form_validation->set_rules('supplier', 'Supplier', 'required');
			$this->form_validation->set_rules('akun_asal', 'Rekening Asal', 'required');
			$this->form_validation->set_rules('tanggal', 'Tanggal Pembayaran', 'required');
			$this->form_validation->set_rules('mata_uang', 'Mata Uang', 'required');
			$this->form_validation->set_rules('konversi', 'Nilai Konversi', 'required');
			$this->form_validation->set_rules('faktur_beli[]', 'Faktur Beli', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('utang/tambah', $data);
			} else {
				$supplier = $this->supplier->getById($this->input->post('supplier', true));
				$_POST['akun_lawan']	= $supplier['akun_utang'];
				$_POST['akun_ppn']		= $this->akun->getByJenis('34')[0]['kode_akun'];
				
				if ($this->utang->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				}
				redirect( base_url('utang') );
			}
		}
		
		public function edit($id)
		{
			$pembayaran =  $this->utang->getById($id);
			$pembayaran['tanggal_transaksi'] = Globals::dateView($pembayaran['tanggal_transaksi']);
			
			$data['title']		= 'Ubah Pembayaran';
			$data['pembayaran']	= $pembayaran;
			$data['supplier']	= $this->supplier->getById($pembayaran['supplier']);
			$data['akun_asal']	= $this->akun->getByJenis(['21', '22']);
			$data['mata_uang']	= $this->mata_uang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->form_validation->set_rules('kode_transaksi', 'Kode Transaksi', 'required');
			$this->form_validation->set_rules('supplier', 'Supplier', 'required');
			$this->form_validation->set_rules('akun_asal', 'Rekening Asal', 'required');
			$this->form_validation->set_rules('tanggal', 'Tanggal Pembayaran', 'required');
			$this->form_validation->set_rules('mata_uang', 'Mata Uang', 'required');
			$this->form_validation->set_rules('konversi', 'Nilai Konversi', 'required');
			$this->form_validation->set_rules('faktur_beli[]', 'Faktur Beli', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('utang/edit', $data);
			} else {
				if ($this->utang->edit() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				}
				redirect( base_url('utang') );
			}
		}

		public function detail()
		{
			$kode_transaksi	= $_REQUEST['id'];
			
			$utang	= $this->utang->getById($kode_transaksi);
			$pembayaran	= $this->utang->getDetail($kode_transaksi);
			$total		= 0;
			foreach ($pembayaran as $p) {
				$total = $total + $p['jumlah_bayar'];
			}
			$utang['total_bayar'] = Globals::moneyView($total);
			
			$data['title']		= 'Detail Pembayaran';
			$data['transaksi']	= $utang;
			$data['pembayaran']	= $pembayaran;

			$this->load->view('utang/detail', $data);
		}
		
		public function delete()
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus transaksi?';
			$data['button']	= '
				<a href="utang/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id)
		{
			if( $this->utang->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect( base_url('utang') );
		}
		
		public function getKonversi()
		{
			$mataUang	= $_REQUEST['mata_uang'];
			$tanggal	= ($_REQUEST['tanggal']) ? Globals::dateFormat($_REQUEST['tanggal']) : date('Y/m/d');
			$kurs		= $this->kurs->getKurs($mataUang, $tanggal);
			echo ($kurs) ? $kurs['nilai_kurs'] : '';
		}
		
		public function getByPembelian()
		{
			if ($this->input->post('pembelian', true))
			$transaksi	= $this->pembelian->getById($this->input->post('pembelian', true));
			else
			$transaksi	= $this->utang->getById($this->input->post('kode_transaksi', true));
			
			if ($transaksi) {
				$supplier	= $this->supplier->getById($transaksi['supplier']);
				$akun		= $this->akun->getById([$supplier['akun_kas'], $supplier['akun_bank']]);
				$mataUang	= $this->mata_uang->getById($transaksi['mata_uang']);
				
				$list_akun	= "<option value=''>Pilih Akun</option>";
				foreach ($akun as $value) {
					$list_akun .= "<option value='".$value['kode_akun']."' data-subtext='".$value['nama_jenis']."'>".$value['kode_akun']." - ".$value['nama_akun']."</option>";
				}
				$transaksi['akun_list']			= $list_akun;
				$transaksi['supplier_list']	= "<option value='".$supplier['kode_supplier']."'>".$supplier['kode_supplier']." - ".$supplier['nama_supplier']."</option>";
				$transaksi['uang_list']			= "<option value='".$mataUang['kode_mu']."'>".$mataUang['kode_mu']."</option>";
			}
			echo json_encode($transaksi);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function showUtang()
		{
			// untuk edit pembayaran utang
			if ($this->input->post('kode_pembayaran', true))
			$utang = $this->utang->getDetail($this->input->post('kode_pembayaran', true));
			// untuk tambah pembayaran dengan kode pembelian tertentu
			else
			$utang = [$this->pembelian->getById($this->input->post('pembelian', true))];
			
			$row = '';
			foreach ($utang as $key => $p) {
				$pembelian	= $this->countInvoice($p['faktur_beli']);
				$pembelian['id_row'] = $key;
				$pembelian['jumlah_bayar'] = isset($p['jumlah_bayar']) ? $p['jumlah_bayar'] : '0.00';
				$pembelian['sisa_tagihan'] = isset($p['jumlah_bayar']) ? $p['jumlah_bayar'] + $pembelian['sisa_tagihan'] : $pembelian['sisa_tagihan'];
				$row .= $this->utangRow($pembelian);
			}
			echo $row;
		}
		
		/**
		 * menampilkan form add produk
		 */
		public function addUtang()
		{
			$kode_bayar	= $this->input->post('kode_bayar', true);
			$supplier	= $this->input->post('supplier', true);
			$mata_uang	= $this->input->post('mata_uang', true);
			$selected	= ($this->input->post('selected', true)) ? $this->input->post('selected', true) : [];
			
			$saved	= $kode_bayar ? [] : '';
			if ($kode_bayar) {
				$kode_bayar	= $this->utang->getDetail($kode_bayar);
				foreach ($kode_bayar as $b) {
					$saved[] = $b['faktur_beli'];
				}
			}
			
			$pembelian	= $this->pembelian->getUtang($supplier, $mata_uang, $saved);
			foreach ($pembelian as $key => $value) {
				// unset jika pembelian sudah ditambahkan
				if (in_array($value['faktur_beli'], $selected)) {
					unset($pembelian[$key]);
				} else {
					$value['tanggal_transaksi'] = Globals::dateView($value['tanggal_transaksi']);
					$pembelian[$key] = $value;
				}
			}
			
			$data['title']		= 'Pilih Invoice';
			$data['id_row']		= $this->input->post('id_last', true);
			$data['pembelian']	= $pembelian;
			$data['mata_uang']	= $mata_uang;
			
			$this->load->view('utang/invoice_tambah', $data);
		}
		
		public function editUtang()
		{
			$invoice	= $this->input->post('invoice', true);
			$bayar		= $this->input->post('bayar', true);
			$kode_bayar	= $this->input->post('kode_bayar', true);
			$kode_bayar	= ($kode_bayar) ? $this->utang->getDetailById($kode_bayar, $invoice) : [];
			
			$pembelian	= $this->countInvoice($invoice);
			$pembelian['jumlah_dibayar'] = ($bayar == 0) ? '' : $bayar;
			$pembelian['sisa_tagihan'] = isset($kode_bayar['jumlah_bayar']) ? Globals::moneyFormat($pembelian['sisa_tagihan'] + $kode_bayar['jumlah_bayar']) : $pembelian['sisa_tagihan'];
			
			$data['title']		= 'Ubah Pembayaran';
			$data['id_row']		= $this->input->post('id_row', true);
			$data['pembelian']	= $pembelian;
			$data['mata_uang']	= $this->input->post('mata_uang', true);
			
			$this->load->view('utang/invoice_edit', $data);
		}
		
		public function detailUtang() 
		{
			$produk	= $this->pembelian->getProdukById($_REQUEST['id_produk']);
			echo json_encode($produk);
		}
		
		public function deleteUtang() 
		{
			$data['text']	= 'Yakin ingin menghapus produk?';
			$data['button']	= '
				<a class="btn btn-danger" onClick="fixDelete('.$_REQUEST['id'].')">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function getInvoice()
		{
			$invoice	= $this->input->post('invoice', true);
			$pembelian	= $this->countInvoice($invoice);
			
			echo json_encode($pembelian);
		}
		
		public function countInvoice($invoice)
		{
			$pembelian	= $this->pembelian->getByInvoice($invoice);
			$ppn		= ($pembelian['jenis_ppn'] == 'Include') ? 100 : (100 + $pembelian['besar_ppn']);
			$beli		= ($pembelian['total_produk'] - $pembelian['diskon_luar']) * ($ppn / 100);
			$retur		= $pembelian['total_retur'] * ($ppn / 100);
			$sisa		= round($beli, 2) - round($retur, 2) - $pembelian['total_bayar'];
			
			$pembelian['total_tagihan']		= number_format(round($beli, 2),2,'.','');
			$pembelian['sisa_tagihan']		= number_format($sisa,2,'.','');
			$pembelian['tanggal_transaksi']	= Globals::dateView($pembelian['tanggal_transaksi']);
			$pembelian['jatuh_tempo']		= Globals::dateView($pembelian['jatuh_tempo']);
			
			return $pembelian;
		}
		
		public function saveUtang() 
		{
			// $pembelian	= $this->countInvoice($this->input->post('invoice', true));
			$pembelian['id_row']			= $this->input->post('id_row', true);
			$pembelian['faktur_beli']		= $this->input->post('invoice', true);
			$pembelian['tanggal_transaksi']	= $this->input->post('tanggal_beli', true);
			$pembelian['jatuh_tempo']		= $this->input->post('jatuh_tempo', true);
			$pembelian['total_tagihan']		= Globals::moneyFormat($this->input->post('total_tagihan', true));
			$pembelian['sisa_tagihan']		= Globals::moneyFormat($this->input->post('sisa_tagihan', true));
			$pembelian['jumlah_bayar']		= Globals::moneyFormat($this->input->post('jumlah_bayar', true));
			$pembelian['action']			= $this->input->post('action', true);
			
			$row = $this->utangRow($pembelian);
			echo $row;
		}
		
		public function utangRow($pembelian)
		{
			// $bayar	= isset($pembelian['jumlah_bayar']) ? $pembelian['jumlah_bayar'] : '0.00';
			$action	= isset($pembelian['action']) ? $pembelian['action'] : '';
			
			$callback =	'';
			if ($action != 'updateUtang') {
				$callback .= '<tr class="utang-row" id="utangRow'.$pembelian['id_row'].'" data-id="'.$pembelian['id_row'].'">';
			}
			$callback .= '
					<td>'.$pembelian['faktur_beli'].' <input type="hidden" name="faktur_beli[]" id="faktur_beli'.$pembelian['id_row'].'" value="'.$pembelian['faktur_beli'].'"> </td>
					<td>'.Globals::dateView($pembelian['tanggal_transaksi']).'</td>
					<td>'.Globals::dateView($pembelian['jatuh_tempo']).'</td>
					<td class="text-right">'.Globals::moneyView($pembelian['total_tagihan']).' <input type="hidden" name="jumlah_tagihan[]" id="jumlah_tagihan'.$pembelian['id_row'].'" value="'.$pembelian['total_tagihan'].'"> </td>
					<td class="text-right">'.Globals::moneyView($pembelian['sisa_tagihan']).' <input type="hidden" name="jumlah_sisa[]" id="jumlah_sisa'.$pembelian['id_row'].'" value="'.$pembelian['sisa_tagihan'].'"> </td>
					<td class="text-right">'.Globals::moneyView($pembelian['jumlah_bayar']).' <input type="hidden" name="jumlah_dibayar[]" id="jumlah_dibayar'.$pembelian['id_row'].'" value="'.$pembelian['jumlah_bayar'].'"> </td>
					<td style="width:fit-content">
						<a class="badge badge-info badge-action btn-edit" data-id="'.$pembelian['id_row'].'">
							<i class="bi bi-pencil-square icon-medium"></i>
						</a>
						<a class="badge badge-danger badge-action btn-delete" data-id="'.$pembelian['id_row'].'">
							<i class="bi bi-trash-fill icon-medium"></i>
						</a>
					</td>';
			if ($action != 'updateUtang') {
				$callback .= '</tr>';
			}
			return $callback;
		}
	}