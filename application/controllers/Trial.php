<?php defined('BASEPATH') OR exit('No direct script access allowed');

    class Trial extends CI_Controller 
    {
        public function __construct()
        {
            parent::__construct();
                $this->libtemplate->user_check();
				$this->load->model('Piutang_model', 'piutang');
				$this->load->model('Penjualan_model', 'penjualan');
				$this->load->model('Retur_penjualan_model', 'retur_penjualan');
        }

        public function index()
        {
            $this->load->view('trial/trial');
            // $this->load->view('trial/trial2');
        }
		
        public function showPiutang()
		{
			$bayar = isset($_GET['kode_pembayaran']) ? $_GET['kode_pembayaran'] : '';
			$jual = isset($_GET['penjualan']) ? $_GET['penjualan'] : '';
			// untuk edit pembayaran piutang
			if ($bayar)
			$piutang = $this->piutang->getDetail($bayar);
			// untuk tambah pembayaran dengan kode penjualan tertentu
			else
			$piutang = [$this->penjualan->getById($jual)];
			
			$row = '';
			foreach ($piutang as $key => $p) {
				$penjualan	= $this->countInvoice($p['faktur_jual']);
				$penjualan['id_row'] = $key;
				$penjualan['jumlah_bayar'] = isset($p['jumlah_bayar']) ? $p['jumlah_bayar'] : '0.00';
				$penjualan['sisa_tagihan'] = isset($p['jumlah_bayar']) ? $p['jumlah_bayar'] + $penjualan['sisa_tagihan'] : $penjualan['sisa_tagihan'];
				echo '<br><br>'.$key; var_dump($p);
				echo '<br><br>'.$key; var_dump($penjualan);
				$row .= $this->piutangRow($penjualan);
			}
			echo '<br><br>'.$row;
		}
		
		public function countInvoice($invoice)
		{
			$penjualan	= $this->penjualan->getByInvoice($invoice);
			$ppn		= ($penjualan['jenis_ppn'] == 'Include') ? 100 : (100 + $penjualan['besar_ppn']);
			$jual		= ($penjualan['total_produk'] - $penjualan['diskon_luar']) * ($ppn / 100);
			$retur		= $penjualan['total_retur'] * ($ppn / 100);
			$sisa		= round($jual, 2) - round($retur, 2) - $penjualan['total_bayar'];
			
			$penjualan['total_tagihan']		= number_format(round($jual, 2),2,'.','');
			$penjualan['sisa_tagihan']		= number_format($sisa,2,'.','');
			$penjualan['tanggal_transaksi']	= Globals::dateView($penjualan['tanggal_transaksi']);
			$penjualan['jatuh_tempo']		= Globals::dateView($penjualan['jatuh_tempo']);
			
			return $penjualan;
		}
		
		public function piutangRow($penjualan)
		{
			$bayar	= isset($penjualan['jumlah_bayar']) ? $penjualan['jumlah_bayar'] : '0.00';
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
					<td class="text-right">'.Globals::moneyView($bayar).' <input type="hidden" name="jumlah_dibayar[]" id="jumlah_dibayar'.$penjualan['id_row'].'" value="'.$bayar.'"> </td>
					<td style="width:fit-content">
						<a class="badge badge-info badge-action btn-edit" data-id="'.$penjualan['id_row'].'">
							<i class="bi bi-pencil-square icon-medium"></i>
						</a>
						<a class="badge badge-danger badge-action btn-delete" data-id="'.$penjualan['id_row'].'">
							<i class="bi bi-trash-fill icon-medium"></i>
						</a>
					</td>';
			if ($action != 'updatePiutang') {
				$callback .= '</tr><br>';
			}
			return $callback;
		}
    }