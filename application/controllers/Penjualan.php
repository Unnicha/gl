<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Penjualan extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Penjualan_model', 'penjualan');
			$this->load->model('Pelanggan_model', 'pelanggan');
			$this->load->model('Akun_model', 'akun');
			$this->load->model('Uang_model', 'mata_uang');
			$this->load->model('Kurs_model', 'kurs');
			$this->load->model('Barang_model', 'barang');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
			unset($_SESSION['produk']);
			
			$this->redirect = 'penjualan';
			$this->pph = [
				'PPh pasal 4(2)',
				'PPh pasal 15',
				'PPh pasal 21',
				'PPh pasal 22',
				'PPh pasal 23',
				'PPh pasal 26',
			];
			$this->ppn = ['Include', 'Exclude', 'Non PPN'];
			$this->jenis_pembayaran = ['Tunai', 'Transfer Bank', 'Kredit'];
			// untuk membatasi tanggal transaksi sesuai bulan & tahun aktif
			$active_date	= $_SESSION['bulan_aktif'].'/01/'.$_SESSION['tahun_aktif'];
			$this->min_date	= isset($_SESSION['bulan_aktif']) ? date('d/m/Y', strtotime($active_date)) : '';
			$this->max_date	= isset($_SESSION['bulan_aktif']) ? date('t/m/Y', strtotime($active_date)) : '';
		}

		public function index()
		{
			$data['title'] = 'Penjualan';
			$this->libtemplate->main('penjualan/index', $data);
		}
		
		public function jurnal()
		{
			$data['title'] = 'Penjualan';
			$this->libtemplate->main('penjualan/jurnal', $data);
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
			
			$data = [];
			// table transaksi
			if($tab == 'transaksi') {
				$transaksi	= $this->penjualan->getTransaksi($cari, $offset, $limit, $order, $bulan);
				$countData	= $this->penjualan->countTransaksi($cari, $bulan);
				
				foreach($transaksi as $i) {
					$invoice	= $this->penjualan->getByInvoice($i['faktur_jual']);
					
					$ppn		= 0;
					$diskon		= ($i['diskon_luar']) ? $i['diskon_luar'] : 0;
					$ppn		= ($i['jenis_ppn'] == 'Exclude') ? (100 + $i['besar_ppn']) : 100;
					$totalJual	= round((($i['total_produk'] - $diskon) * ($ppn / 100)), 2);
					$totalRetur	= round(($invoice['total_retur'] * ($ppn / 100)), 2);
					$sisa		= $totalJual - $totalRetur - $invoice['total_bayar'];
					
					$status	= '<small class="font-weight-bold text-danger">UNPAID</small>';
					if ($invoice['total_bayar']) {
						$status = '<small class="font-weight-bold text-danger">PARTIAL</small>';
						if ($sisa == 0) {
							$status = '<small class="font-weight-bold text-success">PAID</small>';
						}
					}
					
					$btn = '
						<a href="'.base_url('penjualan/edit/'.$i['kode_transaksi']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
							<i class="bi bi-pencil-square icon-medium"></i>
						</a>';
					$btn_1	= '
						<a class="btn-detail badge badge-primary badge-action" data-id="'.$i['kode_transaksi'].'" data-toggle="tooltip" data-placement="left" title="Detail">
							<i class="bi bi-info-circle icon-medium"></i>
						</a>';
					$btn_2	= '
						<a href="'.base_url('retur_penjualan/tambah/'.$i['kode_transaksi']).'" class="badge badge-warning badge-action" data-toggle="tooltip" data-placement="left" title="Retur">
							<i class="bi bi-arrow-left-right icon-medium"></i>
						</a>';
					$btn_3	= '
						<a href="'.base_url('piutang/tambah/'.$i['kode_transaksi']).'" class="badge badge-success badge-action" data-toggle="tooltip" data-placement="left" title="Pembayaran">
							<i class="bi bi-cash icon-medium"></i>
						</a>';
					$btn_3	= ($i['jenis_pembayaran'] != 'Kredit' || $sisa == 0) ? '' : $btn_3;
					$btn_4	= '
						<a class="btn-hapus badge badge-danger badge-action" data-id="'.$i['kode_transaksi'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
							<i class="bi bi-trash icon-medium"></i>
						</a>';
					
					$row	= [];
					$row[]	= ++$offset.'.';
					$row[]	= Globals::dateView($i['tanggal_transaksi']);
					$row[]	= '<a class="btn-detail transaksi" data-id="'.$i['kode_transaksi'].'" data-toggle="tooltip" data-placement="right" title="Detail">'.$i['kode_transaksi'].'</a>';
					$row[]	= $i['nama_pelanggan'];
					$row[]	= Globals::dateView($i['jatuh_tempo']);
					$row[]	= '<p class="mb-0 text-right">'.Globals::moneyView($totalJual).'</p>';
					$row[]	= $status;
					$row[]	= $btn_1 . $btn_2 . $btn_3 . $btn_4;
					
					$data[] = $row;
				}
			}
			// end table transaksi
			
			// table jurnal
			else if($tab == 'jurnal') {
				$transaksi	= $this->penjualan->getJurnal($cari, $offset, $limit, $order, $bulan);
				$countData	= $this->penjualan->countJurnal($cari, $bulan);
				
				$saldo	= 0;
				foreach($transaksi as $k) {
					$jenis_saldo	= $k['jenis_saldo'];
					
					switch ($k['kode_akun']) {
						case $k['akun_asal'] : 
							$ppn		= ($k['jenis_ppn'] == 'Include') ? 100 / (100 + $k['besar_ppn']) : 1;
							$jumlah		= ($k['total'] - $k['diskon_luar']) * $ppn;
							$jumlah		= round($jumlah, 2) * $k['konversi'];
							break;
						case $k['akun_ppn'] : 
							$ppn		= ($k['jenis_ppn'] == 'Include') ? $k['besar_ppn'] / (100 + $k['besar_ppn']) : $k['besar_ppn'] / 100;
							$jumlah		= ($k['total'] - $k['diskon_luar']) * $ppn;
							$jumlah		= round($jumlah, 2) * $k['konversi'];
							break;
						case $k['akun_lawan'] : 
							$jenis_saldo	= ($k['jenis_saldo'] == 'Debit') ? 'Kredit' : 'Debit';
							$ppn			= ($k['jenis_ppn'] == 'Exclude') ? (100 + $k['besar_ppn']) : 100;
							$jumlah			= ($k['total'] - $k['diskon_luar']) * $ppn / 100;
							$jumlah			= round($jumlah, 2) * $k['konversi'];
							break;
					}
					$saldo		= ($jenis_saldo == 'Debit') ? ($saldo + $jumlah) : ($saldo - $jumlah);
					$show_saldo	= Globals::moneyView( abs($saldo) );
					
					$row	= [];
					$row[]	= ++$offset.'.';
					$row[]	= Globals::dateView($k['tanggal_transaksi']);
					$row[]	= '<a class="btn-detail transaksi" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="right" title="Detail">'.$k['kode_transaksi'].'</a>';
					$row[]	= $k['kode_akun'];
					$row[]	= $k['nama_akun'];
					$row[]	= ($jenis_saldo == 'Debit') ? Globals::moneyView($jumlah) : Globals::moneyView(0);
					$row[]	= ($jenis_saldo == 'Kredit') ? Globals::moneyView($jumlah) : Globals::moneyView(0);
					$row[]	= ($saldo < 0) ? '( '.$show_saldo.' )' : $show_saldo;
					
					$data[] = $row;
				}
			}
			// end table jurnal
			
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
			$akun_ppn	= $this->akun->getByJenis('34');
			
			$data['title']			= 'Tambah Penjualan';
			$data['pelanggan']		= $this->pelanggan->getAll();
			$data['akun_asal']		= $this->akun->getByJenis(['31']);
			$data['akun_ppn']		= $akun_ppn[0]['kode_akun'];
			$data['mata_uang']		= $this->mata_uang->getAll();
			$data['jenis_ppn']		= $this->ppn;
			$data['jenis_trx']		= $this->jenis_pembayaran;
			$data['min_date']		= $this->min_date;
			$data['max_date']		= $this->max_date;
			
			$this->form_validation->set_rules('tanggal', 'Tanggal Transaksi', 'required');
			$this->form_validation->set_rules('jatuh_tempo', 'Tanggal Jatuh Tempo', 'required');
			$this->form_validation->set_rules('faktur_jual', 'Faktur Jual', 'required');//|is_unique
			$this->form_validation->set_rules('pelanggan', 'Pelanggan', 'required');
			$this->form_validation->set_rules('mata_uang', 'Mata Uang', 'required');
			$this->form_validation->set_rules('konversi', 'Nilai Konversi', 'required');
			$this->form_validation->set_rules('akun_asal', 'Rekening Asal', 'required');
			$this->form_validation->set_rules('akun_lawan', 'Rekening Lawan', 'required');
			$this->form_validation->set_rules('kode_produk[]', 'Produk', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('penjualan/tambah', $data);
			} else {
				if ($this->penjualan->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				}
				redirect($this->redirect);
			}
		}
		
		public function edit($id)
		{
			$akun_ppn	= $this->akun->getByJenis('34');
			$transaksi	= $this->penjualan->getById($id);
			
			$transaksi['tanggal_transaksi']	= Globals::dateView($transaksi['tanggal_transaksi']);
			$transaksi['jatuh_tempo']		= Globals::dateView($transaksi['jatuh_tempo']);
			$transaksi['jatuh_tempo_giro']	= Globals::dateView($transaksi['jatuh_tempo_giro']);
			
			$data['title']			= 'Ubah Penjualan';
			$data['transaksi']		= $transaksi;
			$data['pelanggan']		= $this->pelanggan->getAll();
			$data['akun_asal']		= $this->akun->getByJenis(['31']);
			$data['akun_ppn']		= $akun_ppn[0]['kode_akun'];
			$data['mata_uang']		= $this->mata_uang->getAll();
			$data['jenis_ppn']		= $this->ppn;
			$data['jenis_trx']		= $this->jenis_pembayaran;
			$data['min_date']		= $this->min_date;
			$data['max_date']		= $this->max_date;
			
			$this->form_validation->set_rules('kode_transaksi', 'Kode Transaksi', 'required');
			$this->form_validation->set_rules('tanggal', 'Tanggal Transaksi', 'required');
			$this->form_validation->set_rules('jatuh_tempo', 'Tanggal Jatuh Tempo', 'required');
			$this->form_validation->set_rules('faktur_jual', 'Faktur Jual', 'required');
			$this->form_validation->set_rules('pelanggan', 'Pelanggan', 'required');
			$this->form_validation->set_rules('mata_uang', 'Mata Uang', 'required');
			$this->form_validation->set_rules('konversi', 'Nilai Konversi', 'required');
			$this->form_validation->set_rules('akun_asal', 'Rekening Asal', 'required');
			$this->form_validation->set_rules('akun_lawan', 'Rekening Lawan', 'required');
			$this->form_validation->set_rules('kode_produk[]', 'Produk', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('penjualan/edit', $data);
			} else {
				if ($this->penjualan->edit() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil diubah!');
				}
				redirect($this->redirect);
			}
		}

		public function detail()
		{
			$kode_transaksi	= $_REQUEST['id'];
			$produk			= $this->penjualan->getProduk($kode_transaksi);
			$transaksi		= $this->penjualan->getById($kode_transaksi);
			
			$total_prod = 0;
			foreach($produk as $key => $p) {
				$jumlah		= $p['qty_produk'] * ($p['harga_produk'] - $p['diskon_produk']);
				$total_prod	= $total_prod + $jumlah;
				
				$p['harga_produk']	= Globals::moneyView($p['harga_produk']);
				$p['diskon_produk']	= Globals::moneyView($p['diskon_produk']);
				$p['jumlah']		= Globals::moneyView($jumlah);
				
				$produk[$key] = $p;
			}
			
			$netto		= $total_prod - $transaksi['diskon_luar'];
			$netto		= ($transaksi['jenis_ppn'] == 'Include') ? $netto * 100 / (100 + $transaksi['besar_ppn']) : $netto;
			$ppn		= $netto * $transaksi['besar_ppn'] / 100;
			$total_fin	= $netto + $ppn;
			
			$transaksi['tanggal_transaksi']	= Globals::dateView($transaksi['tanggal_transaksi']);
			$transaksi['jatuh_tempo']		= Globals::dateView($transaksi['jatuh_tempo']);
			$transaksi['jatuh_tempo_giro']	= Globals::dateView($transaksi['jatuh_tempo_giro']);
			
			$transaksi['konversi']		= Globals::moneyView($transaksi['konversi']);
			$transaksi['total_produk']	= Globals::moneyView($total_prod);
			$transaksi['diskon_luar']	= Globals::moneyView($transaksi['diskon_luar']);
			$transaksi['netto']			= Globals::moneyView($netto);
			$transaksi['total_ppn']		= Globals::moneyView($ppn);
			$transaksi['total_fin']		= Globals::moneyView($total_fin);
			
			$data['title']		= 'Detail Penjualan';
			$data['produk']		= $produk;
			$data['transaksi']	= $transaksi;
			
			$this->load->view('penjualan/detail', $data);
		}
		
		public function delete()
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus transaksi?';
			$data['button']	= '
				<a href="penjualan/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function fixDelete($id)
		{
			if( $this->penjualan->delete($id) > 0 ) {
				$this->session->set_flashdata('notification', 'Berhasil dihapus!');
			} else {
				$this->session->set_flashdata('warning', 'Gagal dihapus!');
			}
			redirect($this->redirect);
		}
		
		public function checkInvoice()
		{
			$result = 'true';
			$invoice = $this->input->post('invoice');
			$invoice = $this->penjualan->getByInvoice($invoice);
			if ($invoice) {
				$result = 'false';
			}
			echo $result;
		}
		
		public function getKonversi()
		{
			$mataUang	= $_REQUEST['mata_uang'];
			$tanggal	= $_REQUEST['tanggal'];
			$tanggal	= ($tanggal) ? Globals::dateFormat($tanggal) : date('Y/m/d');
			$kurs		= $this->kurs->getKurs($mataUang, $tanggal);
			echo ($kurs) ? $kurs['nilai_kurs'] : '';
		}
		
		public function getLawan()
		{
			$pelanggan	= $this->input->post('pelanggan', true);
			$jenis_trx	= $this->input->post('jenis_trx', true);
			$pelanggan	= $this->pelanggan->getById($pelanggan);
			
			if ($jenis_trx == 'Kredit')		$akun = $pelanggan['akun_piutang'];
			else if ($jenis_trx == 'Tunai')	$akun = $pelanggan['akun_kas'];
			else							$akun = $pelanggan['akun_bank'];
			
			$akun	= $this->akun->getById($akun);
			$option	= '<option value="'.$akun['kode_akun'].'" data-subtext="'.$akun['nama_jenis'].'" selected>'.$akun['kode_akun'].' - '.$akun['nama_akun'].'</option>';
			
			echo json_encode([
				'option'	=> $option,
				'selected'	=> $akun['kode_akun'],
			]);
		}
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function showProduk()
		{
			$row	= '';
			$produk	= $this->penjualan->getProduk($_REQUEST['kode_transaksi']);
			foreach ($produk as $key => $p) {
				$total_produk		= ($p['harga_produk'] - $p['diskon_produk']) * $p['qty_produk'];
				$row .= '<tr class="produk-row" id="produkRow'.$key.'" data-id="'.$key.'">
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
			$selected		= $this->input->post('selected');
			$produk			= $this->barang->getById($_REQUEST['kode']);
			$produk['stock'] = $this->barang->getStock($_REQUEST['kode']);
			$produk['stock'] = $produk['stock'] - $selected;
			echo json_encode($produk);
		}
		
		/**
		 * menampilkan form add produk
		 */
		public function addProduk()
		{
			$selected	= ($this->input->post('kode')) ? $this->input->post('kode') : [];
			$produk		= $this->barang->getAll();
			foreach ($produk as $key => $value) {
				if ( in_array($value['kode_barang'], $selected) )
				unset($produk[$key]);
			}
			
			$data['title']		= 'Input Produk';
			$data['produk']		= $produk;
			$data['mata_uang']	= $this->input->post('mata_uang');
			$data['id_row']		= $this->input->post('id_last');
			
			$this->load->view('penjualan/produk_tambah', $data);
		}
		
		/**
		 * menampilkan form edit produk
		 */
		public function editProduk()
		{
			$_REQUEST['diskon']	= $_REQUEST['diskon'] < 1 ? '' : $_REQUEST['diskon'];
			$data['title']		= 'Edit Barang';
			$data['produk']		= $_REQUEST;
			
			$this->load->view('penjualan/produk_edit', $data);
		}
		
		/**
		 * simpan produk
		 * temporary, hanya untuk form penjualan, bukan database
		 */
		public function saveProduk()
		{
			$produk = $this->input->post();
			$_SESSION['produk'][$produk['id_row']] = $produk;
			$row = '<tr class="produk-row" id="produkRow'.$produk['id_row'].'" data-id="'.$produk['id_row'].'">
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
		
		/**
		 * update produk
		 * temporary, hanya untuk form penjualan, bukan database
		 */
		public function updateProduk()
		{
			$row = '
				<td>'.$_REQUEST['kode'].' <input type="hidden" name="kode_produk[]" id="kode_produk'.$_REQUEST['id_row'].'" value="'.$_REQUEST['kode'].'"> </td>
				<td>'.$_REQUEST['nama'].' <input type="hidden" name="nama_produk[]" id="nama_produk'.$_REQUEST['id_row'].'" value="'.$_REQUEST['nama'].'"> </td>
				<td>'.$_REQUEST['qty'].' <input type="hidden" name="qty_produk[]" id="qty_produk'.$_REQUEST['id_row'].'" value="'.$_REQUEST['qty'].'"> </td>
				<td class="text-right">'.$_REQUEST['harga'].' <input type="hidden" name="harga_produk[]" id="harga_produk'.$_REQUEST['id_row'].'" value="'.Globals::moneyFormat($_REQUEST['harga']).'"> </td>
				<td class="text-right">'.$_REQUEST['diskon'].' <input type="hidden" name="diskon_produk[]" id="diskon_produk'.$_REQUEST['id_row'].'" value="'.Globals::moneyFormat($_REQUEST['diskon']).'"> </td>
				<td class="text-right">'.$_REQUEST['total'].' <input type="hidden" name="jumlah_produk[]" id="jumlah_produk'.$_REQUEST['id_row'].'" value="'.Globals::moneyFormat($_REQUEST['total']).'"> </td>
				<td>
					<a class="badge badge-info badge-action btn-edit" data-id="'.$_REQUEST['id_row'].'">
						<i class="bi bi-pencil-square icon-medium"></i>
					</a>
					<a class="badge badge-danger badge-action btn-delete" data-id="'.$_REQUEST['id_row'].'">
						<i class="bi bi-trash-fill icon-medium"></i>
					</a>
				</td>';
			echo $row;
		}
		
		/**
		 * hapus produk
		 * temporary, hanya untuk form penjualan, bukan database
		 */
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
		
		public function getTotal()
		{
			$diskon		= str_replace(['.', ','], ['', '.'], $_REQUEST['diskon']);
			$total_prod	= $_REQUEST['total_prod'];
			$jenis_ppn	= $_REQUEST['jenis_ppn'] ? $_REQUEST['jenis_ppn'] : 'Non PPN';
			$besar_ppn	= $_REQUEST['besar_ppn'] ? $_REQUEST['besar_ppn'] : 0;
			
			$besar_ppn	= ($jenis_ppn == 'Non PPN') ? 0 : $besar_ppn;
			$netto		= $total_prod - $diskon;
			$netto		= ($jenis_ppn == 'Include') ? $netto * 100 / (100 + $besar_ppn) : $netto;
			$ppn		= $netto * $besar_ppn / 100;
			$total_fin	= $netto + $ppn;
			
			$callback = [
				'diskon'		=> Globals::moneyView($diskon),
				'total_produk'	=> Globals::moneyView($netto),
				'ppn'			=> Globals::moneyView($ppn),
				'total_fin'		=> Globals::moneyView($total_fin),
			];
			echo json_encode($callback);
		}
	}