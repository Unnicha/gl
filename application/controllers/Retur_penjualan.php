<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Retur_penjualan extends CI_Controller 
	{
		public function __construct() 
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Retur_penjualan_model', 'retur_penjualan');
			$this->load->model('Penjualan_model', 'penjualan');
			$this->load->model('Pelanggan_model', 'pelanggan');
			$this->load->model('Akun_model', 'akun');
			$this->load->model('Uang_model', 'mata_uang');
			$this->load->model('Kurs_model', 'kurs');
			$this->load->model('Barang_model', 'barang');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
			$this->pph = [
				'PPh pasal 4(2)',
				'PPh pasal 15',
				'PPh pasal 21',
				'PPh pasal 22',
				'PPh pasal 23',
				'PPh pasal 26',
			];
			$this->ppn = [ 'Include', 'Exclude', 'Non PPN' ];
			
			$this->redirect	= 'retur_penjualan';
			// untuk membatasi tanggal transaksi sesuai bulan & tahun aktif
			$active_date	= $_SESSION['bulan_aktif'].'/01/'.$_SESSION['tahun_aktif'];
			$this->min_date	= isset($_SESSION['bulan_aktif']) ? date('d/m/Y', strtotime($active_date)) : '';
			$this->max_date	= isset($_SESSION['bulan_aktif']) ? date('t/m/Y', strtotime($active_date)) : '';
		}

		public function index() 
		{
			$data['title'] = 'Retur Penjualan';
			$this->libtemplate->main('retur_penjualan/index', $data);
		}
		
		public function jurnal() 
		{
			$data['title'] = 'Retur Penjualan';
			$this->libtemplate->main('retur_penjualan/jurnal', $data);
		}
		
		public function table() 
		{
			$tab		= $_POST['tab'];
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
			$bulan	= '';
			
			$data = [];
			// table untuk tab transaksi
			if($tab == 'transaksi') {
				$transaksi	= $this->retur_penjualan->getTransaksi($cari, $offset, $limit, $order, $bulan);
				$countData	= $this->retur_penjualan->countTransaksi($cari, $bulan);
				
				foreach($transaksi as $k) {
					$ppn	= 0;
					$ppn	= ($k['jenis_ppn'] == 'Exclude') ? $k['besar_ppn'] : $ppn;
					$total	= $k['total_produk'] * ((100 + $ppn) / 100);
					
					$row	= [];
					$row[]	= ++$offset.'.';
					$row[]	= Globals::dateView($k['tanggal_transaksi']);
					$row[]	= '<a class="btn-detail transaksi" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="right" title="Detail">'.$k['kode_transaksi'].'</a>';
					$row[]	= $k['nama_pelanggan'];
					// $row[]	= $k['ket_transaksi'];
					$row[]	= Globals::moneyView($total);
					$row[]	= '
						<a class="btn-detail badge badge-primary badge-action" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="left" title="Detail">
							<i class="bi bi-info-circle icon-medium"></i>
						</a>
						<a href="'.base_url('retur_penjualan/edit/'.$k['kode_transaksi']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
							<i class="bi bi-pencil-square icon-medium"></i>
						</a>
						<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
						<i class="bi bi-trash icon-medium"></i>
						</a>';
					
					$data[] = $row;
				}
			} // table untuk tab jurnal
			else if($tab == 'jurnal') {
				$transaksi	= $this->retur_penjualan->getJurnal($cari, $offset, $limit, $order);
				$countData	= $this->retur_penjualan->countJurnal($cari);
				
				$saldo	= 0;
				foreach($transaksi as $k) {
					$jenis = $k['jenis_saldo'];
					$saldo_lawan = ($k['jenis_saldo'] == 'Debit') ? 'Kredit' : 'Debit';
					
					switch ($k['kode_akun']) {
						case $k['akun_asal'] : 
							$jumlah		= $k['total'];
							$jumlah		= ($k['jenis_ppn'] == 'Include') ? ($jumlah * 100 / (100 + $k['besar_ppn'])) : $jumlah;
							$jumlah		= round($jumlah, 2) * $k['konversi'];
							break;
						case $k['akun_ppn'] : 
							$jumlah		= $k['total'];
							$jumlah		= ($k['jenis_ppn'] == 'Include') ? ($jumlah * $k['besar_ppn'] / (100 + $k['besar_ppn'])) : $jumlah * $k['besar_ppn'] / 100;
							$jumlah		= round($jumlah, 2) * $k['konversi'];
							break;
						case $k['akun_lawan'] : 
							$jenis		= $saldo_lawan;
							$jumlah		= $k['total'];
							$ppn		= ($k['jenis_ppn'] == 'Exclude') ? (100 + $k['besar_ppn']) : 100;
							$jumlah		= round(($jumlah * $ppn / 100), 2) * $k['konversi'];
							break;
					}
					$saldo		= ($jenis == 'Debit') ? ($saldo + $jumlah) : ($saldo - $jumlah);
					$show_saldo	= Globals::moneyView( abs($saldo) );
					
					$row	= [];
					$row[]	= ++$offset.'.';
					$row[]	= Globals::dateView($k['tanggal_transaksi']);
					$row[]	= '<a class="btn-detail transaksi" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="right" title="Detail">'.$k['kode_transaksi'].'</a>';
					$row[]	= $k['kode_akun'];
					$row[]	= $k['nama_akun'];
					$row[]	= ($jenis == 'Debit') ? Globals::moneyView($jumlah) : Globals::moneyView(0);
					$row[]	= ($jenis == 'Kredit') ? Globals::moneyView($jumlah) : Globals::moneyView(0);
					$row[]	= ($saldo < 0) ? '( '.$show_saldo.' )' : $show_saldo;
					
					$data[] = $row;
				}
			}
			
			$callback	= [
				'draw'				=> $_POST['draw'], // Ini dari datatablenya
				'recordsTotal'		=> $countData,
				'recordsFiltered'	=> $countData,
				'data'				=> $data,
			];
			echo json_encode($callback);
		}
		
		public function tambah($id_penjualan='') 
		{
			$data['title']		= 'Tambah Retur Penjualan';
			$data['penjualan']	= $id_penjualan;
			$data['pelanggan']	= $this->pelanggan->getAll();
			$data['akun_asal']	= $this->akun->getByJenis(['32']);
			$data['akun_lawan']	= $this->akun->getByJenis(['21', '22', '33']);
			$data['mata_uang']	= $this->mata_uang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->form_validation->set_rules('tanggal', 'Tanggal Transaksi', 'required');
			$this->form_validation->set_rules('pelanggan', 'Pelanggan', 'required');
			$this->form_validation->set_rules('faktur_jual', 'Faktur Retur Jual', 'required');
			$this->form_validation->set_rules('faktur_retur', 'Faktur Retur Jual', 'required');
			$this->form_validation->set_rules('akun_lawan', 'Rekening Lawan', 'required');
			$this->form_validation->set_rules('kode_produk[]', 'Produk', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('retur_penjualan/tambah', $data);
			} else {
				if ($this->retur_penjualan->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				}
				redirect( $this->redirect );
			}
		}
		
		public function edit($id) 
		{
			$transaksi	= $this->retur_penjualan->getById($id);
			$transaksi['tanggal_transaksi']	= Globals::dateView($transaksi['tanggal_transaksi']);
			$transaksi['jatuh_tempo_giro']	= Globals::dateView($transaksi['jatuh_tempo_giro']);
			
			$pelanggan	= $this->pelanggan->getById($transaksi['pelanggan']);
			$akun_plg	= [$pelanggan['akun_kas'], $pelanggan['akun_bank'], $pelanggan['akun_piutang']];
			
			$data['title']		= 'Ubah Retur Penjualan';
			$data['transaksi']	= $transaksi;
			$data['akun_asal']	= $this->akun->getByJenis(['32']);
			$data['akun_lawan']	= $this->akun->getById($akun_plg);
			$data['mata_uang']	= $this->mata_uang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->form_validation->set_rules('kode_transaksi', 'Kode Transaksi', 'required');
			$this->form_validation->set_rules('tanggal', 'Tanggal Transaksi', 'required');
			$this->form_validation->set_rules('pelanggan', 'Pelanggan', 'required');
			$this->form_validation->set_rules('faktur_jual', 'Faktur Retur Jual', 'required');
			$this->form_validation->set_rules('faktur_retur', 'Faktur Retur Jual', 'required');
			$this->form_validation->set_rules('akun_lawan', 'Rekening Lawan', 'required');
			$this->form_validation->set_rules('kode_produk[]', 'Produk', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('retur_penjualan/edit', $data);
			} else {
				if ($this->retur_penjualan->edit() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				}
				redirect( $this->redirect );
			}
		}

		public function detail() 
		{
			$kode_transaksi	= $_REQUEST['id'];
			$produk			= $this->retur_penjualan->getProduk($kode_transaksi);
			$transaksi		= $this->retur_penjualan->getById($kode_transaksi);
			
			$total_prod = 0;
			foreach($produk as $key => $p) {
				$jumlah		= $p['qty_produk'] * ($p['harga_produk'] - $p['diskon_produk']);
				$total_prod	= $total_prod + $jumlah;
				
				$p['harga_produk']	= Globals::moneyView($p['harga_produk']);
				$p['diskon_produk']	= Globals::moneyView($p['diskon_produk']);
				$p['jumlah']		= Globals::moneyView($jumlah);
				
				$produk[$key] = $p;
			}
			
			$netto		= ($transaksi['jenis_ppn'] == 'Include') ? $total_prod * 100 / (100 + $transaksi['besar_ppn']) : $total_prod;
			$ppn		= $netto * $transaksi['besar_ppn'] / 100;
			$total_fin	= $netto + $ppn;
			
			$transaksi['tanggal_transaksi']	= Globals::dateView($transaksi['tanggal_transaksi']);
			$transaksi['jatuh_tempo_giro']	= Globals::dateView($transaksi['jatuh_tempo_giro']);
			
			$transaksi['konversi']		= Globals::moneyView($transaksi['konversi']);
			$transaksi['total_produk']	= Globals::moneyView($total_prod);
			$transaksi['netto']			= Globals::moneyView($netto);
			$transaksi['total_ppn']		= Globals::moneyView($ppn);
			$transaksi['total_fin']		= Globals::moneyView($total_fin);
			
			$data['title']		= 'Detail Penjualan';
			$data['produk']		= $produk;
			$data['transaksi']	= $transaksi;
			
			$this->load->view('retur_penjualan/detail', $data);
		}
		
		public function delete() 
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus transaksi?';
			$data['button']	= '
				<a href="retur_penjualan/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id) 
		{
			if( $this->retur_penjualan->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect( $this->redirect );
		}
		
		public function getByPelanggan()
		{
			$pelanggan		= $this->input->post('pelanggan', true);
			$list_faktur	= '<option value="">Pilih Faktur Penjualan</option>';
			
			if ($pelanggan) {
				$transaksi	= $this->penjualan->getFaktur($pelanggan);
				if ($transaksi) {
					foreach($transaksi as $p) {
						$list_faktur .= '<option value="'.$p['faktur_jual'].'">'.$p['faktur_jual'].'</option>';
					}
				}
				$list_lawan	= $this->getLawan($pelanggan);
			}
			
			echo json_encode([
				'list_faktur'	=> $list_faktur,
				'list_lawan'	=> $list_lawan,
			]);
		}
		
		public function getByPenjualan()
		{
			if ($this->input->post('penjualan', true))
			$penjualan	= $this->penjualan->getById($this->input->post('penjualan', true));
			else
			$penjualan	= $this->penjualan->getByInvoice($this->input->post('faktur_jual'));
			
			$lawan = $this->getLawan($penjualan['pelanggan']);
			if ($penjualan) {
				$pelanggan	= $this->pelanggan->getById($penjualan['pelanggan']);
				$penjualan['pelanggan_list']	= "<option value=".$pelanggan['kode_pelanggan'].">".$pelanggan['kode_pelanggan']." - ".$pelanggan['nama_pelanggan']."</option>";
				$penjualan['faktur_list']		= "<option value=".$penjualan['faktur_jual'].">".$penjualan['faktur_jual']."</option>";
				$penjualan['uang_list']			= "<option value=".$penjualan['mata_uang'].">".$penjualan['mata_uang']."</option>";
				$penjualan['lawan_list']		= $lawan;
				$penjualan['tanggal_transaksi']	= Globals::dateView($penjualan['tanggal_transaksi']);
			}
			echo json_encode($penjualan);
		}
		
		public function getPenjualan() 
		{
			$transaksi	= $this->penjualan->getByInvoice($_REQUEST['faktur_jual']);
			$mata_uang	= "<option value=IDR>IDR</option>";
			if($transaksi)
			$mata_uang	= "<option value=".$transaksi['mata_uang'].">".$transaksi['mata_uang']."</option>";
			
			$transaksi['uang_list']	= $mata_uang;
			$transaksi['min_date']	= Globals::dateView($transaksi['tanggal_transaksi']);
			echo json_encode($transaksi);
		}
		
		public function getLawan($pelanggan='')
		{
			$option	= '<option value="">Pilih Akun Lawan</option>';
			if ($pelanggan) {
				$pelanggan	= $this->pelanggan->getById($pelanggan);
				$akun		= [$pelanggan['akun_kas'], $pelanggan['akun_bank'], $pelanggan['akun_piutang']];
				$akun		= $this->akun->getById($akun);
				foreach ($akun as $key => $value) {
					$option	.= "<option value=".$value['kode_akun']." data-subtext=".$value['nama_jenis'].">".$value['kode_akun']." - ".$value['nama_akun']."</option>";
				}
			}
			return $option;
		}
		
		public function checkInvoice()
		{
			$result = 'true';
			$invoice = $this->input->post('invoice');
			$invoice = $this->retur_penjualan->getByInvoice($invoice);
			if ($invoice) {
				$result = 'false';
			}
			echo $result;
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function showProduk() 
		{
			$row	= '';
			$produk	= $this->retur_penjualan->getProduk($_REQUEST['kode_transaksi']);
			foreach ($produk as $key => $p) {
				$total_produk	= ($p['harga_produk'] - $p['diskon_produk']) * $p['qty_produk'];
				$row .= '<tr class="produk-row" id="produkRow'.$key.'" data-id="'.$key.'">
					<input type="hidden" name="id_produk[]" id="id_produk'.$key.'" value="'.$p['id_produk'].'">
					<td>'.$p['kode_produk'].' <input type="hidden" name="kode_produk[]" id="kode_produk'.$key.'" value="'.$p['kode_produk'].'"> </td>
					<td>'.$p['nama_produk'].' <input type="hidden" name="nama_produk[]" id="nama_produk'.$key.'" value="'.$p['nama_produk'].'"> </td>
					<td>'.$p['qty_produk'].' <input type="hidden" name="qty_produk[]" id="qty_produk'.$key.'" value="'.$p['qty_produk'].'"> </td>
					<td class="text-right">'.Globals::moneyView($p['harga_produk']).' <input type="hidden" name="harga_produk[]" id="harga_produk'.$key.'" value="'.$p['harga_produk'].'"> </td>
					<td class="text-right">'.Globals::moneyView($p['diskon_produk']).' <input type="hidden" name="diskon_produk[]" id="diskon_produk'.$key.'" value="'.$p['diskon_produk'].'"> </td>
					<td class="text-right">'.Globals::moneyView($total_produk).' <input type="hidden" name="jumlah_produk[]" id="jumlah_produk'.$key.'" value="'.$total_produk.'"> </td>
					<td>
						<a class="badge badge-info badge-action btn-edit" data-id="'.$key.'">
							<i class="bi bi-pencil-square icon-medium"></i>
						</a>
						<a class="badge badge-danger badge-action btn-delete" data-id="'.$key.'">
							<i class="bi bi-trash-fill icon-medium"></i>
						</a>
					</td>
				</tr>';
			}
			echo $row;
		}
		
		public function detailProduk() 
		{
			$produk	= $this->penjualan->getProdukById($_REQUEST['id_produk']);
			echo json_encode($produk);
		}
		
		/**
		 * menampilkan form add produk
		 */
		public function addProduk()
		{
			$id_selected = isset($_REQUEST['id_selected']) ? $_REQUEST['id_selected'] : '';
			$produk		 = $this->penjualan->getProduk($_REQUEST['faktur_jual'], 'faktur_jual');
			if ($id_selected) {
				foreach ($produk as $key => $p) {
					if ( in_array($p['id_produk'], $id_selected) )
					unset($produk[$key]);
				}
			}
			
			$data['title']		= 'Input Produk';
			$data['produk']		= $produk;
			$data['mata_uang']	= $_REQUEST['mata_uang'];
			$data['id_row']		= $_REQUEST['id_last'];
			
			$this->load->view('retur_penjualan/produk_tambah', $data);
		}
		
		/**
		 * menampilkan form edit produk
		 */
		public function editProduk()
		{
			$edit	= $_REQUEST;
			$produk	= $this->penjualan->getProduk($_REQUEST['faktur_jual'], 'faktur_jual');
			foreach ($produk as $p) {
				if ($p['kode_produk'] == $edit['kode'])
				$edit['max_produk'] = $p['qty_produk'];
			}
			
			$data['title']		= 'Ubah Detail Produk';
			$data['produk']		= $edit;
			$data['mata_uang']	= $_REQUEST['mata_uang'];
			
			$this->load->view('retur_penjualan/produk_edit', $data);
		}
		
		public function deleteProduk() 
		{
			$data['text']	= 'Yakin ingin menghapus produk?';
			$data['button']	= '
				<a class="btn btn-danger" onClick="fixDelete('.$_REQUEST['id'].')">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function saveProduk() 
		{
			$produk = $this->input->post();
			$_SESSION['produk'][$produk['id_row']] = $produk;
			$row = '<tr class="produk-row" id="produkRow'.$produk['id_row'].'" data-id="'.$produk['id_row'].'">
				<input type="hidden" name="id_produk[]" id="id_produk'.$produk['id_row'].'" value="'.$produk['id_produk'].'">
				<td>'.$produk['kode'].' <input type="hidden" name="kode_produk[]" id="kode_produk'.$produk['id_row'].'" value="'.$produk['kode'].'"> </td>
				<td>'.$produk['nama'].' <input type="hidden" name="nama_produk[]" id="nama_produk'.$produk['id_row'].'" value="'.$produk['nama'].'"> </td>
				<td>'.$produk['qty'].' <input type="hidden" name="qty_produk[]" id="qty_produk'.$produk['id_row'].'" value="'.$produk['qty'].'"> </td>
				<td class="text-right">'.$produk['harga'].' <input type="hidden" name="harga_produk[]" id="harga_produk'.$produk['id_row'].'" value="'.Globals::moneyFormat($produk['harga']).'"> </td>
				<td class="text-right">'.$produk['diskon'].' <input type="hidden" name="diskon_produk[]" id="diskon_produk'.$produk['id_row'].'" value="'.Globals::moneyFormat($produk['diskon']).'"> </td>
				<td class="text-right">'.$produk['total'].' <input type="hidden" name="jumlah_produk[]" id="jumlah_produk'.$produk['id_row'].'" value="'.Globals::moneyFormat($produk['total']).'"> </td>
				<td>
					<a class="badge badge-info badge-action btn-edit" data-id="'.$produk['id_row'].'">
						<i class="bi bi-pencil-square icon-medium"></i>
					</a>
					<a class="badge badge-danger badge-action btn-delete" data-id="'.$produk['id_row'].'">
						<i class="bi bi-trash-fill icon-medium"></i>
					</a>
				</td>
			</tr>';
			echo $row;
		}
		
		public function updateProduk() 
		{
			$produk = $this->input->post();
			$_SESSION['produk'][$produk['id_row']] = $produk;
			$row = '
				<input type="hidden" name="id_produk[]" id="id_produk'.$produk['id_row'].'" value="'.$produk['id_produk'].'">
				<td>'.$produk['kode'].' <input type="hidden" name="kode_produk[]" id="kode_produk'.$produk['id_row'].'" value="'.$produk['kode'].'"> </td>
				<td>'.$produk['nama'].' <input type="hidden" name="nama_produk[]" id="nama_produk'.$produk['id_row'].'" value="'.$produk['nama'].'"> </td>
				<td>'.$produk['qty'].' <input type="hidden" name="qty_produk[]" id="qty_produk'.$produk['id_row'].'" value="'.$produk['qty'].'"> </td>
				<td class="text-right">'.$produk['harga'].' <input type="hidden" name="harga_produk[]" id="harga_produk'.$produk['id_row'].'" value="'.Globals::moneyFormat($produk['harga']).'"> </td>
				<td class="text-right">'.$produk['diskon'].' <input type="hidden" name="diskon_produk[]" id="diskon_produk'.$produk['id_row'].'" value="'.Globals::moneyFormat($produk['diskon']).'"> </td>
				<td class="text-right">'.$produk['total'].' <input type="hidden" name="jumlah_produk[]" id="jumlah_produk'.$produk['id_row'].'" value="'.Globals::moneyFormat($produk['total']).'"> </td>
				<td>
					<a class="badge badge-info badge-action btn-edit" data-id="'.$produk['id_row'].'">
						<i class="bi bi-pencil-square icon-medium"></i>
					</a>
					<a class="badge badge-danger badge-action btn-delete" data-id="'.$produk['id_row'].'">
						<i class="bi bi-trash-fill icon-medium"></i>
					</a>
				</td>';
			echo $row;
		}
		
		public function getTotal()
		{
			$total_prod	= $_REQUEST['total_prod'];
			$jenis_ppn	= $_REQUEST['jenis_ppn'] ? $_REQUEST['jenis_ppn'] : 'Non PPN';
			$besar_ppn	= $_REQUEST['besar_ppn'] ? $_REQUEST['besar_ppn'] : 0;
			
			$besar_ppn	= ($jenis_ppn == 'Non PPN') ? 0 : $besar_ppn;
			$netto		= ($jenis_ppn == 'Include') ? $total_prod * 100 / (100 + $besar_ppn) : $total_prod;
			$ppn		= $netto * $besar_ppn / 100;
			$total_fin	= $netto + $ppn;
			
			$callback = [
				'total_produk'	=> Globals::moneyView($netto),
				'ppn'			=> Globals::moneyView($ppn),
				'total_fin'		=> Globals::moneyView($total_fin),
			];
			echo json_encode($callback);
		}
	}