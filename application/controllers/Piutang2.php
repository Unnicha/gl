<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Piutang2 extends CI_Controller
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
			$this->min_date	= isset($_SESSION['bulan_aktif']) ? date('d-m-Y', strtotime($active_date)) : '';
			$this->max_date	= isset($_SESSION['bulan_aktif']) ? date('t-m-Y', strtotime($active_date)) : '';
		}

		public function index()
		{
			$data['title'] = 'Pembayaran Piutang';
			$this->libtemplate->main('piutang/index', $data);
		}
		
		public function view()
		{
			$data['title'] = 'Penjualan';
			$this->load->view('piutang2/view', $data);
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
			$bulan	= $this->session->userdata('tahun_aktif').'-'.$this->session->userdata('bulan_aktif');
			
			$data = [];
			$transaksi	= $this->piutang->getAll($cari, $offset, $limit, $order, $bulan);
			$countData	= $this->piutang->countAll($cari, $bulan);
			
			foreach($transaksi as $k) {
				
				$row	= [];
				$row[]	= ++$offset.'.';
				$row[]	= Globals::dateView($k['tanggal_transaksi']);
				$row[]	= $k['kode_transaksi'];
				$row[]	= $k['nama_pelanggan'];
				$row[]	= Globals::moneyView($k['jumlah_bayar']);
				$row[]	= '
					<a class="btn-detail badge badge-primary badge-action" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="left" title="Detail">
						<i class="bi bi-info-circle icon-medium"></i>
					</a>
					<a href="'.base_url('piutang/edit/'.$k['kode_transaksi']).'" class="badge badge-info badge-action" data-toggle="tooltip" data-placement="left" title="Ubah">
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
			$pembayaran['tanggal_transaksi'] = Globals::dateView($pembayaran['tanggal_transaksi']);
			
			$data['title']		= 'Ubah Pembayaran';
			$data['pembayaran']	= $pembayaran;
			$data['pelanggan']	= [$this->pelanggan->getById($pembayaran['pelanggan'])];
			$data['akun_asal']	= $this->akun->getByJenis(['21', '22']);
			$data['mata_uang']	= $this->mata_uang->getAll();
			$data['min_date']	= $this->min_date;
			$data['max_date']	= $this->max_date;
			
			$this->form_validation->set_rules('kode_transaksi', 'Kode Transaksi', 'required');
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
			$kode_transaksi	= $_REQUEST['id'];
			
			$piutang	= $this->piutang->getById($kode_transaksi);
			$pembayaran	= $this->piutang->getDetail($kode_transaksi);
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
			$tanggal	= ($_REQUEST['tanggal']) ? Globals::dateFormat($_REQUEST['tanggal']) : date('Y-m-d');
			$kurs		= $this->kurs->getKurs($mataUang, $tanggal);
			echo ($kurs) ? $kurs['nilai_kurs'] : '';
		}
		
		public function getByPenjualan()
		{
			if ($this->input->post('penjualan', true))
			$transaksi	= $this->penjualan->getById($this->input->post('penjualan', true));
			else
			$transaksi	= $this->piutang->getById($this->input->post('kode_transaksi', true));
			
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
			if ($this->input->post('kode_pembayaran', true))
			$piutang = $this->piutang->getDetail($this->input->post('kode_pembayaran', true));
			// untuk tambah pembayaran dengan kode penjualan tertentu
			else
			$piutang = [$this->penjualan->getById($this->input->post('penjualan', true))];
			
			$row = '';
			foreach ($piutang as $key => $p) {
				$penjualan	= $this->penjualan->getByInvoice($p['faktur_jual']);
				$row		.= $this->piutangRow($penjualan, $key);
			}
			echo $row;
		}
		
		/**
		 * menampilkan form add produk
		 */
		public function addPiutang()
		{
			$pelanggan	= $this->input->post('pelanggan', true);
			$pembayaran	= $this->input->post('pembayaran', true);
			$mata_uang	= $this->input->post('mata_uang', true);
			$selected	= ($this->input->post('selected', true)) ? $this->input->post('selected', true) : [];
			
			$saved	= $pembayaran ? [] : '';
			if ($pembayaran) {
				$pembayaran	= $this->piutang->getDetail($pembayaran);
				foreach ($pembayaran as $b) {
					$saved[] = $b['faktur_jual'];
				}
			}
			
			$penjualan	= $this->penjualan->getPiutang($pelanggan, $saved);
			foreach ($penjualan as $key => $jual) {
				// unset jika penjualan sudah ditambahkan
				if (in_array($jual['faktur_jual'], $selected)) {
					unset($penjualan[$key]);
				} else {
					$ppn		= ($jual['jenis_ppn'] == 'Exclude') ? (100 + $jual['besar_ppn']) : 100;
					$tagihan	= ($jual['total_produk'] - $jual['diskon_luar']) * $ppn / 100;
					
					$jual['jumlah_tagihan']		= Globals::moneyView($tagihan);
					$jual['tanggal_transaksi']	= Globals::dateView($jual['tanggal_transaksi']);
					$jual['jatuh_tempo']		= Globals::dateView($jual['jatuh_tempo']);
					$jual['status']				= ($jual['mata_uang'] == $mata_uang) ? '' : 'disabled';
					
					$penjualan[$key] = $jual;
				}
			}
			
			$data['title']		= 'Pilih Invoice';
			$data['id_row']		= $this->input->post('id_last');
			$data['penjualan']	= $penjualan;
			
			$this->load->view('piutang/bayar_tambah', $data);
		}
		
		public function detailPiutang() 
		{
			$produk	= $this->penjualan->getProdukById($_REQUEST['id_produk']);
			echo json_encode($produk);
		}
		
		public function deletePiutang() 
		{
			$data['text']	= 'Yakin ingin menghapus produk?';
			$data['button']	= '
				<a class="btn btn-danger" onClick="fixDelete('.$_REQUEST['id'].')">Hapus</a>
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" tabindex="1">
					Batal
				</button>';
				
			$this->load->view('template/confirm', $data);
		}
		
		public function savePiutang() 
		{
			$invoice = $this->input->post('invoice', true);
			if ($invoice) {
				$id_row		= $this->input->post('id_row', true);
				$penjualan	= $this->penjualan->getByPelanggan($this->input->post('pelanggan', true));
				
				$row = '';
				foreach ($penjualan as $p) {
					if (in_array($p['faktur_jual'], $invoice)) {
						$row .= $this->piutangRow($p, $id_row);
						$id_row++;
					}
				}
				echo $row;
			}
		}
		
		public function piutangRow($penjualan, $id_row)
		{
			$ppn		= ($penjualan['jenis_ppn'] == 'Exclude') ? (100 + $penjualan['besar_ppn']) : 100;
			$tagihan	= ($penjualan['total_produk'] - $penjualan['diskon_luar']) * $ppn / 100;
			$tagihan	= round($tagihan, 2);
			
			$callback['sisa'] = '';
			$callback['row'] = '
				<tr class="piutang-row" id="piutangRow'.$id_row.'" data-id="'.$id_row.'">
					<td>'.($id_row + 1).'.</td>
					<td>'.$penjualan['faktur_jual'].' <input type="hidden" name="faktur_jual[]" id="faktur_jual'.$id_row.'" value="'.$penjualan['faktur_jual'].'"> </td>
					<td>'.Globals::dateView($penjualan['tanggal_transaksi']).'</td>
					<td>'.Globals::dateView($penjualan['jatuh_tempo']).'</td>
					<td class="text-right">'.Globals::moneyView($tagihan).' <input type="hidden" name="jumlah_tagihan[]" id="jumlah_tagihan'.$id_row.'" value="'.$tagihan.'"> </td>
					<td class="text-right">'.Globals::moneyView($tagihan).' <input type="hidden" name="jumlah_dibayar[]" id="jumlah_dibayar'.$id_row.'" value="'.$tagihan.'"> </td>
					<td class="text-right">'.Globals::moneyView($tagihan).' <input type="hidden" name="jumlah_sisa[]" id="jumlah_sisa'.$id_row.'" value="'.$tagihan.'"> </td>
					<td>
						<a class="badge badge-danger badge-action btn-delete" data-id="'.$id_row.'">
							<i class="bi bi-trash-fill icon-medium"></i>
						</a>
					</td>
				</tr>';
			return $callback;
		}
	}