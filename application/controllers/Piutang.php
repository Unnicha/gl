<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Piutang extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Piutang_model', 'piutang');
			$this->load->model('Penjualan_model', 'penjualan');
			$this->load->model('Retur_penjualan_model', 'retur_penjualan');
			$this->load->model('Pelanggan_model', 'pelanggan');
			$this->load->model('Akun_model', 'akun');
			$this->load->model('Uang_model', 'mata_uang');
			$this->load->model('Kurs_model', 'kurs');
			$this->load->model('Barang_model', 'barang');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
			unset($_SESSION['penjualan']);
			
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
			$data['title'] = 'Pembayaran Piutang';
			$this->libtemplate->main('piutang/index', $data);
		}
		
		public function jurnal()
		{
			$data['title'] = 'Pembayaran Piutang';
			$this->libtemplate->main('piutang/jurnal', $data);
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
			$transaksi	= $this->piutang->getAll($cari, $offset, $limit, $order, $bulan);
			$countData	= $this->piutang->countAll($cari, $bulan);
			
			foreach($transaksi as $k) 
			{
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= Globals::dateView($k['tanggal_bayar']);
				$row[]	= '<a class="btn-detail transaksi" data-id="'.$k['kode_bayar'].'" data-toggle="tooltip" data-placement="right" title="Detail">'.$k['kode_bayar'].'</a>';
				$row[]	= $k['nama_pelanggan'];
				$row[]	= $k['faktur_jual'];
				$row[]	= Globals::moneyDisplay($k['jumlah_bayar']);
				$row[]	= '
					<a class="btn-detail badge badge-primary badge-action" data-id="'.$k['kode_bayar'].'" data-toggle="tooltip" data-placement="left" title="Detail">
						<i class="bi bi-info-circle icon-medium"></i>
					</a>
					<a href="'.base_url('piutang/edit/'.$k['kode_bayar']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
						<i class="bi bi-pencil-square icon-medium"></i>
					</a>
					<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['kode_bayar'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
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
		
		public function tambah($penjualan='')
		{
			$data['title']		= 'Terima Pembayaran';
			$data['penjualan']	= $penjualan;
			$data['pelanggan']	= $this->pelanggan->getAll();
			$data['akun_asal']	= $this->akun->getByJenis(['21', '22']);
			$data['akun_ppn']	= $this->akun->getByJenis('34')[0]['kode_akun'];
			$data['mata_uang']	= $this->mata_uang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->form_validation->set_rules('pelanggan', 'Pelanggan', 'required');
			$this->form_validation->set_rules('akun_asal', 'Rekening Asal', 'required');
			$this->form_validation->set_rules('tanggal', 'Tanggal Pembayaran', 'required');
			$this->form_validation->set_rules('mata_uang', 'Mata Uang', 'required');
			$this->form_validation->set_rules('konversi', 'Nilai Konversi', 'required');
			$this->form_validation->set_rules('faktur_jual[]', 'Faktur Jual', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('piutang/tambah', $data);
			} else {
				$pelanggan = $this->pelanggan->getById($this->input->post('pelanggan', true));
				$_POST['akun_lawan']	= $pelanggan['akun_piutang'];
				$_POST['akun_ppn']		= $this->akun->getByJenis('34')[0]['kode_akun'];
				
				if ($this->piutang->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				}
				redirect( base_url('piutang') );
			}
		}
		
		public function edit($id)
		{
			$pembayaran =  $this->piutang->getById($id);
			$pembayaran['tanggal_bayar'] = Globals::dateView($pembayaran['tanggal_bayar']);
			
			$data['title']		= 'Ubah Pembayaran';
			$data['pembayaran']	= $pembayaran;
			$data['pelanggan']	= $this->pelanggan->getById($pembayaran['pelanggan']);
			$data['akun_asal']	= $this->akun->getByJenis(['21', '22']);
			$data['mata_uang']	= $this->mata_uang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->form_validation->set_rules('kode_bayar', 'Kode Transaksi', 'required');
			$this->form_validation->set_rules('pelanggan', 'Pelanggan', 'required');
			$this->form_validation->set_rules('akun_asal', 'Rekening Asal', 'required');
			$this->form_validation->set_rules('tanggal', 'Tanggal Pembayaran', 'required');
			$this->form_validation->set_rules('mata_uang', 'Mata Uang', 'required');
			$this->form_validation->set_rules('konversi', 'Nilai Konversi', 'required');
			$this->form_validation->set_rules('faktur_jual[]', 'Faktur Jual', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('piutang/edit', $data);
			} else {
				if ($this->piutang->edit() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				}
				redirect( base_url('piutang') );
			}
		}

		public function detail()
		{
			$kode_bayar	= $_REQUEST['id'];
			
			$piutang	= $this->piutang->getById($kode_bayar);
			$pembayaran	= $this->piutang->getDetail($kode_bayar);
			$total		= 0;
			foreach ($pembayaran as $p) {
				$total = $total + $p['jumlah_bayar'];
			}
			$piutang['total_bayar'] = Globals::moneyView($total);
			
			$data['title']		= 'Detail Pembayaran';
			$data['transaksi']	= $piutang;
			$data['pembayaran']	= $pembayaran;

			$this->load->view('piutang/detail', $data);
		}
		
		public function delete()
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus transaksi?';
			$data['button']	= '
				<a href="piutang/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id)
		{
			if( $this->piutang->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect( base_url('piutang') );
		}
		
		public function getKonversi()
		{
			$mataUang	= $_REQUEST['mata_uang'];
			$tanggal	= ($_REQUEST['tanggal']) ? Globals::dateFormat($_REQUEST['tanggal']) : date('Y/m/d');
			$kurs		= $this->kurs->getKurs($mataUang, $tanggal);
			echo ($kurs) ? $kurs['nilai_kurs'] : '';
		}
		
		public function getByPenjualan()
		{
			if ($this->input->post('penjualan', true))
			$transaksi	= $this->penjualan->getById($this->input->post('penjualan', true));
			else
			$transaksi	= $this->piutang->getById($this->input->post('kode_bayar', true));
			
			if ($transaksi) {
				$pelanggan	= $this->pelanggan->getById($transaksi['pelanggan']);
				$akun		= $this->akun->getById([$pelanggan['akun_kas'], $pelanggan['akun_bank']]);
				$mataUang	= $this->mata_uang->getById($transaksi['mata_uang']);
				
				$list_akun	= "<option value=''>Pilih Akun</option>";
				foreach ($akun as $value) {
					$list_akun .= "<option value='".$value['kode_akun']."' data-subtext='".$value['nama_jenis']."'>".$value['kode_akun']." - ".$value['nama_akun']."</option>";
				}
				$transaksi['akun_list']			= $list_akun;
				$transaksi['pelanggan_list']	= "<option value='".$pelanggan['kode_pelanggan']."'>".$pelanggan['kode_pelanggan']." - ".$pelanggan['nama_pelanggan']."</option>";
				$transaksi['uang_list']			= "<option value='".$mataUang['kode_mu']."'>".$mataUang['kode_mu']."</option>";
			}
			echo json_encode($transaksi);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function showPiutang()
		{
			// untuk edit pembayaran piutang
			if ($this->input->post('kode_bayar', true))
			$piutang = $this->piutang->getDetail($this->input->post('kode_bayar', true));
			// untuk tambah pembayaran dengan kode penjualan tertentu
			else
			$piutang = [$this->penjualan->getById($this->input->post('penjualan', true))];
			
			$row = '';
			foreach ($piutang as $key => $p) {
				$penjualan	= $this->countInvoice($p['faktur_jual']);
				$penjualan['id_row'] = $key;
				$penjualan['jumlah_bayar'] = isset($p['jumlah_bayar']) ? $p['jumlah_bayar'] : '0.00';
				$penjualan['sisa_tagihan'] = isset($p['jumlah_bayar']) ? $p['jumlah_bayar'] + $penjualan['sisa_tagihan'] : $penjualan['sisa_tagihan'];
				$row .= $this->piutangRow($penjualan);
			}
			echo $row;
		}
		
		/**
		 * menampilkan form add sub-bayar
		 */
		public function addPiutang()
		{
			$kode_bayar	= $this->input->post('kode_bayar', true);
			$pelanggan	= $this->input->post('pelanggan', true);
			$mata_uang	= $this->input->post('mata_uang', true);
			$selected	= ($this->input->post('selected', true)) ? $this->input->post('selected', true) : [];
			
			$saved	= $kode_bayar ? [] : '';
			if ($kode_bayar) {
				$kode_bayar	= $this->piutang->getDetail($kode_bayar);
				foreach ($kode_bayar as $b) {
					$saved[] = $b['faktur_jual'];
				}
			}
			
			$penjualan	= $this->penjualan->getPiutang($pelanggan, $mata_uang, $saved);
			foreach ($penjualan as $key => $value) {
				// unset jika penjualan sudah ditambahkan
				if (in_array($value['faktur_jual'], $selected)) {
					unset($penjualan[$key]);
				} else {
					$value['tanggal_bayar'] = Globals::dateView($value['tanggal_bayar']);
					$penjualan[$key] = $value;
				}
			}
			
			$data['title']		= 'Pilih Invoice';
			$data['id_row']		= $this->input->post('id_last', true);
			$data['penjualan']	= $penjualan;
			$data['mata_uang']	= $mata_uang;
			
			$this->load->view('piutang/invoice_tambah', $data);
		}
		
		public function editPiutang()
		{
			$invoice	= $this->input->post('invoice', true);
			$bayar		= $this->input->post('bayar', true);
			$kode_bayar	= $this->input->post('kode_bayar', true);
			$kode_bayar	= ($kode_bayar) ? $this->piutang->getDetailById($kode_bayar, $invoice) : [];
			
			$penjualan	= $this->countInvoice($invoice);
			$penjualan['jumlah_dibayar'] = ($bayar == 0) ? '' : $bayar;
			$penjualan['sisa_tagihan'] = isset($kode_bayar['jumlah_bayar']) ? Globals::moneyFormat($penjualan['sisa_tagihan'] + $kode_bayar['jumlah_bayar']) : $penjualan['sisa_tagihan'];
			
			$data['title']		= 'Ubah Pembayaran';
			$data['id_row']		= $this->input->post('id_row', true);
			$data['penjualan']	= $penjualan;
			$data['mata_uang']	= $this->input->post('mata_uang', true);
			
			$this->load->view('piutang/invoice_edit', $data);
		}
		
		public function detailPiutang() 
		{
			$produk	= $this->penjualan->getProdukById($_REQUEST['id_produk']);
			echo json_encode($produk);
		}
		
		public function deletePiutang() 
		{
			$data['text']	= 'Yakin ingin menghapus piutang?';
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
			$penjualan	= $this->countInvoice($invoice);
			
			echo json_encode($penjualan);
		}
		
		/**
		 * mendapatkan detail penjualan dari invoice/faktur
		 */
		public function countInvoice($invoice)
		{
			$penjualan	= $this->penjualan->getByInvoice($invoice);
			$ppn		= ($penjualan['jenis_ppn'] == 'Include') ? 100 : (100 + $penjualan['besar_ppn']);
			$jual		= ($penjualan['total_produk'] - $penjualan['diskon_luar']) * ($ppn / 100);
			$retur		= $penjualan['total_retur'] * ($ppn / 100);
			$sisa		= round($jual, 2) - round($retur, 2) - $penjualan['total_bayar'];
			
			$penjualan['total_tagihan']	= number_format(round($jual, 2),2,'.','');
			$penjualan['sisa_tagihan']	= number_format($sisa,2,'.','');
			$penjualan['tanggal_transaksi']	= Globals::dateView($penjualan['tanggal_transaksi']);
			$penjualan['jatuh_tempo']	= Globals::dateView($penjualan['jatuh_tempo']);
			
			return $penjualan;
		}
		
		public function savePiutang() 
		{
			// $penjualan	= $this->countInvoice($this->input->post('invoice', true));
			$penjualan['id_row']		= $this->input->post('id_row', true);
			$penjualan['faktur_jual']	= $this->input->post('invoice', true);
			$penjualan['tanggal_transaksi']	= $this->input->post('tanggal_jual', true);
			$penjualan['jatuh_tempo']	= $this->input->post('jatuh_tempo', true);
			$penjualan['total_tagihan']	= Globals::moneyFormat($this->input->post('total_tagihan', true));
			$penjualan['sisa_tagihan']	= Globals::moneyFormat($this->input->post('sisa_tagihan', true));
			$penjualan['jumlah_bayar']	= Globals::moneyFormat($this->input->post('jumlah_bayar', true));
			$penjualan['action']		= $this->input->post('action', true);
			
			$row = $this->piutangRow($penjualan);
			echo $row;
		}
		
		public function piutangRow($penjualan)
		{
			// $bayar	= isset($penjualan['jumlah_bayar']) ? $penjualan['jumlah_bayar'] : '0.00';
			$action	= isset($penjualan['action']) ? $penjualan['action'] : '';
			
			$callback =	'';
			if ($action != 'updatePiutang') {
				$callback .= '<tr class="piutang-row" id="piutangRow'.$penjualan['id_row'].'" data-id="'.$penjualan['id_row'].'">';
			}
			$callback .= '
					<td>'.$penjualan['faktur_jual'].' <input type="hidden" name="faktur_jual[]" id="faktur_jual'.$penjualan['id_row'].'" value="'.$penjualan['faktur_jual'].'"> </td>
					<td>'.Globals::dateView($penjualan['tanggal_transaksi']).'</td>
					<td>'.Globals::dateView($penjualan['jatuh_tempo']).'</td>
					<td class="text-right">'.Globals::moneyView($penjualan['total_tagihan']).' <input type="hidden" name="jumlah_tagihan[]" id="jumlah_tagihan'.$penjualan['id_row'].'" value="'.$penjualan['total_tagihan'].'"> </td>
					<td class="text-right">'.Globals::moneyView($penjualan['sisa_tagihan']).' <input type="hidden" name="jumlah_sisa[]" id="jumlah_sisa'.$penjualan['id_row'].'" value="'.$penjualan['sisa_tagihan'].'"> </td>
					<td class="text-right">'.Globals::moneyView($penjualan['jumlah_bayar']).' <input type="hidden" name="jumlah_dibayar[]" id="jumlah_dibayar'.$penjualan['id_row'].'" value="'.$penjualan['jumlah_bayar'].'"> </td>
					<td style="width:fit-content">
						<a class="badge badge-info badge-action btn-edit" data-id="'.$penjualan['id_row'].'">
							<i class="bi bi-pencil-square icon-medium"></i>
						</a>
						<a class="badge badge-danger badge-action btn-delete" data-id="'.$penjualan['id_row'].'">
							<i class="bi bi-trash-fill icon-medium"></i>
						</a>
					</td>';
			if ($action != 'updatePiutang') {
				$callback .= '</tr>';
			}
			return $callback;
		}
	}




