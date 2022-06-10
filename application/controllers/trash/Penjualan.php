<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
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
		$this->ppn = ['Include', 'Exclude', 'Non PPN'];
		
		// untuk membatasi tanggal transaksi sesuai bulan & tahun aktif
		$bulan			= $_SESSION['bulan_aktif'];
		$tahun			= $_SESSION['tahun_aktif'];
		$active_date	= $bulan.'/01/'.$tahun;
		$this->min_date	= isset($bulan) ? date('d/m/Y', strtotime($active_date)) : '';
		$this->max_date	= isset($bulan) ? date('t/m/Y', strtotime($active_date)) : '';
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
		$bulan	= $this->session->userdata('tahun_aktif').'-'.$this->session->userdata('bulan_aktif');
		
		$data = [];
		// table transaksi
		if($tab == 'transaksi') {
			$transaksi	= $this->penjualan->getTransaksi($cari, $offset, $limit, $order, $bulan);
			$countData	= $this->penjualan->countTransaksi($cari, $bulan);
			
			foreach($transaksi as $k) {
				$ppn	= 0;
				$ppn	= ($k['jenis_ppn'] == 'Exclude') ? $k['besar_ppn'] : $ppn;
				$diskon	= ($k['diskon_luar']) ? $k['diskon_luar'] : 0;
				$total	= ($k['total_transaksi'] - $diskon) * ((100 + $ppn) / 100) * $k['konversi'];
				
				$status	= '<small class="font-weight-bold text-danger">UNPAID</small>';
				// $payment = $this->piutang->getBySales($k['tanggal_transaksi']);
				// if($payment) {
				// 	$status = '<small class="font-weight-bold text-danger">PARTIAL</small>';
				// 	if ($payment['jumlah_bayar'] == $k['total_transaksi']) {
				// 		$status = '<small class="font-weight-bold text-success">PAID</small>';
				// 	}
				// }
				
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= $this->dateView($k['tanggal_transaksi']);
				$row[]	= $k['kode_transaksi'];
				$row[]	= $k['ket_transaksi'];
				$row[]	= $this->dateView($k['jatuh_tempo']);
				$row[]	= $this->moneyView($total);
				$row[]	= $status;
				$row[]	= '
					<a class="btn-detail badge badge-primary badge-action" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="left" title="Detail">
						<i class="bi bi-info-circle icon-medium"></i>
					</a>
					<a href="'.base_url('penjualan/edit/'.$k['kode_transaksi']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
						<i class="bi bi-pencil-square icon-medium"></i>
					</a>
					<a class="btn-hapus badge badge-danger badge-action" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
					<i class="bi bi-trash icon-medium"></i>
					</a>';
				
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
				$saldo_lawan = ($k['jenis_saldo'] == 'Debit') ? 'Kredit' : 'Debit';
				
				switch ($k['kode_akun']) {
					case $k['akun_asal'] : 
						$jenis	= $k['jenis_saldo'];
						$ppn	= ($k['jenis_ppn'] == 'Include') ? $k['besar_ppn'] : 0;
						$jumlah	= ($k['jumlah'] * $k['konversi']) * ((100 - $ppn) / 100);
						break;
					case $k['akun_ppn'] : 
						$jenis	= $k['jenis_saldo'];
						$jumlah	= abs($k['total'] * $k['konversi'] * ($k['besar_ppn'] / 100));
						break;
					case $k['akun_lawan'] : 
						$jenis	= $saldo_lawan;
						$ppn	= ($k['jenis_ppn'] == 'Exclude') ? $k['besar_ppn'] : 0;
						$jumlah	= ($k['total'] * $k['konversi']) * ((100 + $ppn) / 100);
						break;
					default : 
						$jenis	= $k['jenis_saldo'];
						$ppn	= ($k['jenis_ppn'] == 'Include') ? $k['besar_ppn'] : 0;
						$jumlah	= ($k['jumlah'] * $k['konversi']) * ((100 - $ppn) / 100);
						break;
				}
				$saldo		= ($jenis == 'Debit') ? ($saldo + $jumlah) : ($saldo - $jumlah);
				$show_saldo	= $this->moneyView( abs($saldo) );
				
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= $this->dateView($k['tanggal_transaksi']);
				$row[]	= $k['kode_transaksi'];
				$row[]	= $k['kode_akun'];
				$row[]	= $k['nama_akun'];
				$row[]	= ($jenis == 'Debit') ? $this->moneyView($jumlah) : $this->moneyView(0);
				$row[]	= ($jenis == 'Kredit') ? $this->moneyView($jumlah) : $this->moneyView(0);
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
	
	/**
	 * mengubah data ke format tampilan uang
	 */
	public function moneyView($money)
	{
		return number_format($money,2,',','.');
	}
	
	/**
	 * mengubah data ke format tampilan tanggal
	 */
	public function dateView($date)
	{
		return ($date) ? date('d-m-Y', strtotime($date)) : '';
	}
	
	/**
	 * mengubah inputan ke format angka untuk disimpan
	 */
	public function moneyFormat($money)
	{
		return str_replace(['.', ','], ['', '.'], $money);
	}
	
	public function getKonversi()
	{
		$mataUang	= $_REQUEST['mata_uang'];
		$tanggal	= $_REQUEST['tanggal'];
		$tanggal	= ($tanggal == '') ? date('Y-m-d') : date('Y-m-d', strtotime($tanggal));
		$kurs		= $this->kurs->getKurs($mataUang, $tanggal);
		echo ($kurs) ? $kurs['nilai_kurs'] : '';
	}
	
	public function getTotal()
	{
		$konversi	= str_replace(['.', ','], ['', '.'], $_REQUEST['konversi']);
		$diskon		= str_replace(['.', ','], ['', '.'], $_REQUEST['diskon']);
		$total_prod	= $_REQUEST['total_prod'];
		$total_pph	= $_REQUEST['total_pph'];
		$jenis_ppn	= $_REQUEST['jenis_ppn'];
		$besar_ppn	= $_REQUEST['besar_ppn'];
		
		$total_fin	= $total_prod - $diskon;
		if($jenis_ppn == 'Non PPN' || $jenis_ppn == '') {
			$besar_ppn = 0;
		}
		$ppn = $total_fin * ($besar_ppn / 100);
		if($jenis_ppn == 'Exclude') {
			$total_fin = $total_fin + $ppn;
		}
		
		$callback = [
			'total_produk'	=> $this->moneyView($total_prod * $konversi),
			'diskon'		=> $this->moneyView($diskon * $konversi),
			'ppn'			=> $this->moneyView($ppn * $konversi),
			'pph'			=> $this->moneyView($total_pph * $konversi),
			'total_fin'		=> $this->moneyView($total_fin * $konversi),
		];
		echo json_encode($callback);
	}
	
	public function maxProduk()
	{
		$kode	= $_REQUEST['kode'];
		$produk	= $this->barang->getById($kode);
		$stock	= $this->barang->getStock($kode);
		echo json_encode([
			'nama'	=> $produk['nama_barang'],
			'stock'	=> $stock,
		]);
	}
	
	public function tambah()
	{
		$data['title']		= 'Tambah Penjualan';
		$data['pelanggan']	= $this->pelanggan->getAll();
		$data['akun_asal']	= $this->akun->getByJenis(['23', '34']);
		$data['akun_lawan']	= $this->akun->getByJenis(['21', '22', '29']);
		$data['akun_pajak']	= $this->akun->getByJenis('31');
		$data['mata_uang']	= $this->mata_uang->getAll();
		$data['ppn']		= $this->ppn;
		$data['min_date']	= $this->min_date;
		$data['max_date']	= $this->max_date;
		
		$this->form_validation->set_rules('status_jurnal', 'Status Jurnal', 'required');
		$this->form_validation->set_rules('jenis_saldo', 'Jenis Saldo', 'required');
		$this->form_validation->set_rules('tanggal', 'Tanggal Transaksi', 'required');
		$this->form_validation->set_rules('jatuh_tempo', 'Tanggal Jatuh Tempo', 'required');
		$this->form_validation->set_rules('faktur_pajak', 'Faktur Pajak', 'required');
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
			redirect( base_url('penjualan') );
		}
	}
	
	public function edit($id)
	{
		$transaksi	= $this->penjualan->getById($id);
		$transaksi['tanggal_transaksi']	= $this->dateView($transaksi['tanggal_transaksi']);
		$transaksi['jatuh_tempo']		= $this->dateView($transaksi['jatuh_tempo']);
		$transaksi['jatuh_tempo_giro']	= $this->dateView($transaksi['jatuh_tempo_giro']);
		
		$data['title']		= 'Ubah Penjualan';
		$data['transaksi']	= $transaksi;
		$data['pelanggan']	= $this->pelanggan->getAll();
		$data['akun_asal']	= $this->akun->getByJenis(['23', '34']);
		$data['akun_lawan']	= $this->akun->getByJenis(['21', '22', '29']);
		$data['akun_pajak']	= $this->akun->getByJenis('31');
		$data['mata_uang']	= $this->mata_uang->getAll();
		$data['ppn']		= $this->ppn;
		$data['min_date']	= $this->min_date;
		$data['max_date']	= $this->max_date;
		
		$this->form_validation->set_rules('kode_transaksi', 'Kode Transaksi', 'required');
		$this->form_validation->set_rules('status_jurnal', 'Status Jurnal', 'required');
		$this->form_validation->set_rules('jenis_saldo', 'Jenis Saldo', 'required');
		$this->form_validation->set_rules('tanggal', 'Tanggal Transaksi', 'required');
		$this->form_validation->set_rules('jatuh_tempo', 'Tanggal Jatuh Tempo', 'required');
		$this->form_validation->set_rules('faktur_pajak', 'Faktur Pajak', 'required');
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
			redirect( base_url('penjualan') );
		}
	}

	public function detail()
	{
		$kode_transaksi	= $_REQUEST['id'];
		$produk			= $this->penjualan->getProduk($kode_transaksi);
		$transaksi		= $this->penjualan->getById($kode_transaksi);
		
		$total_prod = 0; $pph = 0;
		foreach($produk as $key => $p) {
			$jumlah		= $p['qty_produk'] * ($p['harga_produk'] - $p['diskon_produk']);
			$total_prod	= $total_prod + $jumlah;
			$besar_pph	= ($p['besar_pph']) ? $p['besar_pph'] : 0;
			$pph		= $pph + ($jumlah * $besar_pph / 100);
			
			$p['harga_produk']	= $this->moneyView($p['harga_produk']);
			$p['diskon_produk']	= $this->moneyView($p['diskon_produk']);
			$p['jumlah']		= $this->moneyView($jumlah);
			$p['besar_pph']		= ($p['besar_pph']) ? $p['besar_pph'].' %' : '';
            
			$produk[$key] = $p;
		}
		
		$total		= $total_prod * $transaksi['konversi'];
		$ppn		= $transaksi['besar_ppn'] ? ($total * $transaksi['besar_ppn'] / 100) : 0;
		$pph		= $pph * $transaksi['konversi'];
		$total_fin	= ($transaksi['jenis_ppn'] == 'Exclude') ? ($total + $ppn) : $total;
		
		$transaksi['tanggal_transaksi']	= $this->dateView($transaksi['tanggal_transaksi']);
		$transaksi['jatuh_tempo']		= $this->dateView($transaksi['jatuh_tempo']);
		$transaksi['jatuh_tempo_giro']	= $this->dateView($transaksi['jatuh_tempo_giro']);
		$transaksi['konversi']			= $this->moneyView($transaksi['konversi']);
		$transaksi['total_produk']		= $this->moneyView($total_prod);
		$transaksi['total_transaksi']	= $this->moneyView($total);
		$transaksi['total_ppn']			= $this->moneyView($ppn);
		$transaksi['total_pph']			= $this->moneyView($pph);
		$transaksi['total_keseluruhan']	= $this->moneyView($total_fin);
		
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
		redirect( base_url('penjualan') );
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function showProduk()
	{
		$row	= '';
		$produk	= $this->penjualan->getProduk($_REQUEST['kode_transaksi']);
		foreach ($produk as $key => $p) {
		$nilai_pph		= ($p['besar_pph'] == '') ? '' : $p['besar_pph'].' %';
		$total_produk	= ($p['harga_produk'] - $p['diskon_produk']) * $p['qty_produk'];
			$row .= '<tr class="produk-row" id="produk'.$key.'" data-id="'.$key.'">
				<td>'.$p['kode_produk'].' <input type="hidden" name="kode_produk[]" id="kode_produk'.$key.'" value="'.$p['kode_produk'].'"> </td>
				<td>'.$p['nama_produk'].' <input type="hidden" name="nama_produk[]" id="nama_produk'.$key.'" value="'.$p['nama_produk'].'"> </td>
				<td>'.$p['jenis_produk'].' <input type="hidden" name="jenis_produk[]" id="jenis_produk'.$key.'" value="'.$p['jenis_produk'].'"> </td>
				<td>'.$p['qty_produk'].' <input type="hidden" name="qty_produk[]" id="qty_produk'.$key.'" value="'.$p['qty_produk'].'"> </td>
				<td class="text-right">'.$this->moneyView($p['harga_produk']).' <input type="hidden" name="harga_produk[]" id="harga_produk'.$key.'" value="'.$p['harga_produk'].'"> </td>
				<td class="text-right">'.$this->moneyView($p['diskon_produk']).' <input type="hidden" name="diskon_produk[]" id="diskon_produk'.$key.'" value="'.$p['diskon_produk'].'"> </td>
				<td class="text-right">'.$this->moneyView($total_produk).' <input type="hidden" name="total_produk[]" id="total_produk'.$key.'" value="'.$total_produk.'"> </td>
				<td>'.$p['jenis_pph'].' <input type="hidden" name="jenis_pph[]" id="jenis_pph'.$key.'" value="'.$p['jenis_pph'].'"> </td>
				<td>'.$nilai_pph.' <input type="hidden" name="besar_pph[]" id="besar_pph'.$key.'" value="'.$p['besar_pph'].'"> </td>
				<td>'.$p['akun_pph'].' <input type="hidden" name="akun_pph[]" id="akun_pph'.$key.'" value="'.$p['akun_pph'].'"> </td>
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
	
	/**
	 * menampilkan form add produk
	 */
	public function addProduk()
	{
		$data['mata_uang']	= $_REQUEST['mata_uang'];
		$data['pph']		= $this->pph;
		$data['akun_pajak']	= $this->akun->getByJenis('31');
		$data['id_produk']	= $_REQUEST['id_last'];
		
		if($_REQUEST['jenis'] == 'barang') {
			$data['title']	= 'Input Barang';
			$data['produk']	= $this->barang->getAll();
			
			$this->load->view('penjualan/produk/add_barang', $data);
		}
		else if($_REQUEST['jenis'] == 'biaya') {
			$data['title']	= 'Input Biaya';
			$data['biaya']	= $this->akun->getByJenis('34');
			
			$this->load->view('penjualan/produk/add_biaya', $data);
		}
	}
	
	/**
	 * menampilkan form edit produk
	 */
	public function editProduk()
	{
		$data['mata_uang']	= $_REQUEST['mata_uang'];
		$data['pph']		= $this->pph;
		$data['akun_pajak']	= $this->akun->getByJenis('31');
		$data['row']	 	= [
			'id_produk'	=> $_REQUEST['id_produk'],
			'kode'		=> $_REQUEST['kode'],
			'nama'		=> $_REQUEST['nama'],
			'jenis'		=> $_REQUEST['jenis'],
			'qty'		=> $_REQUEST['qty'],
			'harga'		=> $_REQUEST['harga'],
			'diskon'	=> $_REQUEST['diskon'],
			'total'		=> $_REQUEST['total'],
			'jenis_pph'	=> $_REQUEST['jenis_pph'],
			'besar_pph'	=> $_REQUEST['besar_pph'],
			'akun_pph'	=> $_REQUEST['akun_pph'],
		];
		
		if($data['row']['jenis'] == 'barang') {
			$data['title']		= 'Edit Barang';
			$data['produk']		= $this->barang->getAll();
			$this->load->view('penjualan/produk/edit_barang', $data);
		}
		else if($data['row']['jenis'] == 'biaya') {
			$data['title']		= 'Edit Biaya';
			$data['biaya']		= $this->akun->getByJenis('34');
			$this->load->view('penjualan/produk/edit_biaya', $data);
		}
	}
	
	/**
	 * simpan produk
	 * temporary, hanya untuk form penjualan, bukan database
	 */
	public function saveProduk()
	{
		$nilai_pph	= ($_REQUEST['besar_pph'] == '') ? '' : $_REQUEST['besar_pph'].' %';
		$row = '<tr class="produk-row" id="produk'.$_REQUEST['id_produk'].'" data-id="'.$_REQUEST['id_produk'].'">
			<td>'.$_REQUEST['kode'].' <input type="hidden" name="kode_produk[]" id="kode_produk'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['kode'].'"> </td>
			<td>'.$_REQUEST['nama'].' <input type="hidden" name="nama_produk[]" id="nama_produk'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['nama'].'"> </td>
			<td>'.$_REQUEST['jenis'].' <input type="hidden" name="jenis_produk[]" id="jenis_produk'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['jenis'].'"> </td>
			<td>'.$_REQUEST['qty'].' <input type="hidden" name="qty_produk[]" id="qty_produk'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['qty'].'"> </td>
			<td class="text-right">'.$_REQUEST['harga'].' <input type="hidden" name="harga_produk[]" id="harga_produk'.$_REQUEST['id_produk'].'" value="'.$this->moneyFormat($_REQUEST['harga']).'"> </td>
			<td class="text-right">'.$_REQUEST['diskon'].' <input type="hidden" name="diskon_produk[]" id="diskon_produk'.$_REQUEST['id_produk'].'" value="'.$this->moneyFormat($_REQUEST['diskon']).'"> </td>
			<td class="text-right">'.$_REQUEST['total'].' <input type="hidden" name="total_produk[]" id="total_produk'.$_REQUEST['id_produk'].'" value="'.$this->moneyFormat($_REQUEST['total']).'"> </td>
			<td>'.$_REQUEST['jenis_pph'].' <input type="hidden" name="jenis_pph[]" id="jenis_pph'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['jenis_pph'].'"> </td>
			<td>'.$nilai_pph.' <input type="hidden" name="besar_pph[]" id="besar_pph'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['besar_pph'].'"> </td>
			<td>'.$_REQUEST['akun_pph'].' <input type="hidden" name="akun_pph[]" id="akun_pph'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['akun_pph'].'"> </td>
			<td>
				<a class="badge badge-info badge-action btn-edit" data-id="'.$_REQUEST['id_produk'].'">
					<i class="bi bi-pencil-square icon-medium"></i>
				</a>
				<a class="badge badge-danger badge-action btn-delete" data-id="'.$_REQUEST['id_produk'].'">
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
		$nilai_pph	= ($_REQUEST['besar_pph'] == '') ? '' : $_REQUEST['besar_pph'].' %';
		$row = '
			<td>'.$_REQUEST['kode'].' <input type="hidden" name="kode_produk[]" id="kode_produk'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['kode'].'"> </td>
			<td>'.$_REQUEST['nama'].' <input type="hidden" name="nama_produk[]" id="nama_produk'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['nama'].'"> </td>
			<td>'.$_REQUEST['jenis'].' <input type="hidden" name="jenis_produk[]" id="jenis_produk'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['jenis'].'"> </td>
			<td>'.$_REQUEST['qty'].' <input type="hidden" name="qty_produk[]" id="qty_produk'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['qty'].'"> </td>
			<td class="text-right">'.$_REQUEST['harga'].' <input type="hidden" name="harga_produk[]" id="harga_produk'.$_REQUEST['id_produk'].'" value="'.$this->moneyFormat($_REQUEST['harga']).'"> </td>
			<td class="text-right">'.$_REQUEST['diskon'].' <input type="hidden" name="diskon_produk[]" id="diskon_produk'.$_REQUEST['id_produk'].'" value="'.$this->moneyFormat($_REQUEST['diskon']).'"> </td>
			<td class="text-right">'.$_REQUEST['total'].' <input type="hidden" name="total_produk[]" id="total_produk'.$_REQUEST['id_produk'].'" value="'.$this->moneyFormat($_REQUEST['total']).'"> </td>
			<td>'.$_REQUEST['jenis_pph'].' <input type="hidden" name="jenis_pph[]" id="jenis_pph'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['jenis_pph'].'"> </td>
			<td>'.$nilai_pph.' <input type="hidden" name="besar_pph[]" id="besar_pph'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['besar_pph'].'"> </td>
			<td>'.$_REQUEST['akun_pph'].' <input type="hidden" name="akun_pph[]" id="akun_pph'.$_REQUEST['id_produk'].'" value="'.$_REQUEST['akun_pph'].'"> </td>
			<td>
				<a class="badge badge-info badge-action btn-edit" data-id="'.$_REQUEST['id_produk'].'">
					<i class="bi bi-pencil-square icon-medium"></i>
				</a>
				<a class="badge badge-danger badge-action btn-delete" data-id="'.$_REQUEST['id_produk'].'">
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
}