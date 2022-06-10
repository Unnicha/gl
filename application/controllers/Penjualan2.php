<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Penjualan2 extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->libtemplate->user_check();
			$this->load->model('Penjualan_model', 'penjualan');
			$this->load->model('Piutang_model', 'piutang');
			$this->load->model('Pelanggan_model', 'pelanggan');
			$this->load->model('Akun_model', 'akun');
			$this->load->model('Uang_model', 'mata_uang');
			$this->load->model('Kurs_model', 'kurs');
			$this->load->model('Barang_model', 'barang');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<small class="text-danger">', '</small>');
			unset($_SESSION['produk']);
			
			$this->redirect = 'penjualan2';
			$this->ppn = ['Include', 'Exclude', 'Non PPN'];
			$this->jenis_pembayaran = ['Tunai', 'Transfer Bank', 'Kredit'];
			// untuk membatasi tanggal transaksi sesuai bulan & tahun aktif
			$active_date	= $_SESSION['bulan_aktif'].'/01/'.$_SESSION['tahun_aktif'];
			$this->min_date	= ($this->session->userdata('bulan_aktif')) ? date('d/m/Y', strtotime($active_date)) : '';
			$this->max_date	= ($this->session->userdata('bulan_aktif')) ? date('t/m/Y', strtotime($active_date)) : '';
		}
		
		public function index()
		{
			$data['title'] = 'Penjualan V2';
			$this->libtemplate->main('penjualan2/index', $data);
		}
		
		public function view()
		{
			$data['title'] = 'Penjualan V2';
			$this->load->view('penjualan2/view', $data);
		}
		
		public function jurnal()
		{
			$data['title'] = 'Penjualan V2';
			$this->libtemplate->main('penjualan2/jurnal', $data);
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
			
			// table transaksi
			$data = [];
			$transaksi	= $this->penjualan->getTransaksi($cari, $offset, $limit, $order, $bulan);
			$countData	= $this->penjualan->countTransaksi($cari, $bulan);
			
			foreach($transaksi as $value) {
				$invoice	= $this->penjualan->getByInvoice($value['faktur_jual']);
				
				$ppn		= 0;
				$diskon		= ($value['diskon_luar']) ? $value['diskon_luar'] : 0;
				$ppn		= ($value['jenis_ppn'] == 'Exclude') ? (100 + $value['besar_ppn']) : 100;
				$totalJual	= round((($value['total_produk'] - $diskon) * ($ppn / 100)), 2);
				$totalRetur	= round(($invoice['total_retur'] * ($ppn / 100)), 2);
				
				$status	= '<small class="font-weight-bold text-danger">UNPAID</small>';
				if ($invoice['total_bayar']) {
					$status = '<small class="font-weight-bold text-danger">PARTIAL</small>';
					if (($totalJual - $totalRetur) == $invoice['total_bayar']) {
						$status = '<small class="font-weight-bold text-success">PAID</small>';
					}
				}
				
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= Globals::dateView($value['tanggal_transaksi']);
				$row[]	= $value['kode_transaksi'];
				$row[]	= $value['nama_pelanggan'];
				$row[]	= Globals::dateView($value['jatuh_tempo']);
				$row[]	= Globals::moneyView($totalJual);
				$row[]	= $status;
				$row[]	= '
					<a class="btn-detail badge badge-primary badge-action" data-id="'.$value['kode_transaksi'].'" data-toggle="tooltip" data-placement="left" title="Detail">
						<i class="bi bi-info-circle icon-medium"></i>
					</a>
					<a class="btn-hapus badge badge-danger badge-action" data-id="'.$value['kode_transaksi'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
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
		
		public function tambah()
		{
			$akun_ppn	= $this->akun->getByJenis('31');
			
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
			$this->form_validation->set_rules('faktur_jual', 'Faktur Jual', 'required');
			$this->form_validation->set_rules('pelanggan', 'Pelanggan', 'required');
			$this->form_validation->set_rules('mata_uang', 'Mata Uang', 'required');
			$this->form_validation->set_rules('konversi', 'Nilai Konversi', 'required');
			$this->form_validation->set_rules('akun_asal', 'Rekening Asal', 'required');
			$this->form_validation->set_rules('akun_lawan', 'Rekening Lawan', 'required');
			$this->form_validation->set_rules('kode_produk[]', 'Produk', 'required');
			
			if($this->form_validation->run() == FALSE) {
				$this->libtemplate->main('penjualan2/tambah', $data);
			} else {
				if ($this->penjualan->add() > 0) {
					$this->session->set_flashdata('notification', 'Berhasil ditambahkan!');
				}
				redirect($this->redirect);
			}
		}
		
		public function edit($id)
		{
			$akun_ppn	= $this->akun->getByJenis('31');
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
				$this->libtemplate->main('penjualan2/edit', $data);
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

			$this->load->view('penjualan2/detail', $data);
		}
		
		public function delete()
		{
			$id				= $_REQUEST['id'];
			$data['text']	= 'Yakin ingin menghapus transaksi?';
			$data['button']	= '
				<a href="penjualan2/fixDelete/'.$id.'" class="btn btn-danger">Hapus</a>
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
		
		public function getKonversi()
		{
			$mataUang	= $this->input->post('mata_uang');
			$tanggal	= $this->input->post('tanggal');
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
			$akun = $this->akun->getById($akun);
			
			echo json_encode([
				'value'	=> $akun['kode_akun'],
				'show'	=> $akun['kode_akun'].' - '.$akun['nama_akun'],
			]);
		}
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		public function showProduk()
		{
			$row	= '';
			$produk	= $this->penjualan->getProduk($_REQUEST['kode_transaksi']);
			foreach ($produk as $key => $p) {
				$total_produk	= ($p['harga_produk'] - $p['diskon_produk']) * $p['qty_produk'];
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
			$qty	= ($_REQUEST['qty']) ? $_REQUEST['qty'] : 0;
			$produk	= $this->barang->getById($_REQUEST['kode']);
			$produk['stock'] = $this->barang->getStock($_REQUEST['kode']);
			$produk['stock'] = $produk['stock'] + $qty;
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
			
			$this->load->view('penjualan2/produk_tambah', $data);
		}
		
		/**
		 * menampilkan form edit produk
		 */
		public function editProduk()
		{
			$data['title']		= 'Edit Barang';
			// $data['produk']		= $this->barang->getAll();
			$data['mata_uang']	= $_REQUEST['mata_uang'];
			$data['produk']		= $_REQUEST;
			
			$this->load->view('penjualan2/produk_edit', $data);
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
			$konversi	= str_replace(['.', ','], ['', '.'], $_REQUEST['konversi']);
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
				'total_rupiah'	=> Globals::moneyView($total_fin * $konversi),
			];
			echo json_encode($callback);
		}
	}