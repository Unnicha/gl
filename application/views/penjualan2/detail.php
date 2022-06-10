<div class="modal-header px-4">
	<h4 class="modal-title"><?= $title ?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>

<div class="modal-body p-3">
	<div class="container-fluid p-0">
		<div class="row text-right mb-3">
			<div class="col">
				<a href="<?= base_url('penjualan/edit/'.$transaksi['kode_transaksi']) ?>" class="btn btn-info btn-action" data-toggle="tooltip" data-placement="left" title="Ubah">
					<span class="bi bi-pencil-square icon-medium"></span>
					Ubah
				</a>
				<a href="<?= base_url('retur_penjualan/tambah/'.$transaksi['kode_transaksi']) ?>" class="btn btn-warning btn-action" data-toggle="tooltip" data-placement="left" title="Retur">
					<span class="bi bi-arrow-left-right icon-medium"></span>
					Retur
				</a>
				<?php if ($transaksi['jenis_pembayaran'] == 'Kredit') : ?>
				<a href="<?= base_url('piutang/tambah/'.$transaksi['kode_transaksi']) ?>" class="btn btn-success btn-action" data-toggle="tooltip" data-placement="left" title="Pembayaran">
					<span class="bi bi-cash icon-medium"></span>
					Bayar
				</a>
				<?php endif ?>
				<a class="btn btn-hapus btn-danger btn-action" data-id="'.$k['kode_transaksi'].'" data-toggle="tooltip" data-placement="right" title="Hapus">
					<span class="bi bi-trash icon-medium"></span>
					Hapus
				</a>
			</div>
		</div>
		
		<div class="row mb-3">
			<div class="col-xl">
				<table class="table table-detail-border">
					<tbody>
						<tr>
							<td scope="row" class="title">Kode Transaksi</td>
							<td><?=$transaksi['kode_transaksi']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Status Jurnal</td>
							<td><?=$transaksi['status_jurnal']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Tanggal Transaksi</td>
							<td><?=$transaksi['tanggal_transaksi']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Faktur Jual</td>
							<td><?=$transaksi['faktur_jual']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Surat Jalan</td>
							<td><?=$transaksi['surat_jalan']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Jatuh Tempo</td>
							<td><?=$transaksi['jatuh_tempo']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">No. Giro</td>
							<td><?=$transaksi['no_giro']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Jatuh Tempo Giro</td>
							<td><?=$transaksi['jatuh_tempo_giro']?></td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="col-xl">
				<table class="table table-detail-border">
					<tbody>
						<tr>
							<td scope="row" class="title">Jenis Pembayaran</td>
							<td><?=$transaksi['jenis_pembayaran']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Mata Uang</td>
							<td><?=$transaksi['mata_uang']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Nilai Konversi</td>
							<td><?='Rp '. $transaksi['konversi'] ?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Pelanggan</td>
							<td><?=$transaksi['kode_pelanggan'].' - '.$transaksi['nama_pelanggan']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Akun Asal</td>
							<td><?=$transaksi['akun_asal'].' - '.$transaksi['nama_asal']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Akun Lawan</td>
							<td><?=$transaksi['akun_lawan'].' - '.$transaksi['nama_lawan']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Keterangan</td>
							<td><?=$transaksi['ket_transaksi']?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<h6 class="px-3 mb-3">Detail Produk</h6>
		
		<div class="row mb-3">
			<div class="col">
				<table class="table table-detail-border table-bordered table-responsive-lg">
					<thead>
						<tr>
							<th>Kode</th>
							<th>Produk</th>
							<th>Qty</th>
							<th>Harga</th>
							<th>Diskon</th>
							<th>Jumlah</th>
						</tr>
					</thead>
					
					<tbody>
						<?php foreach($produk as $p) : ?>
						<tr>
							<td><?= $p['kode_produk'] ?></td>
							<td><?= $p['nama_produk'] ?></td>
							<td><?= $p['qty_produk'] ?></td>
							<td class="text-right"><?= $p['harga_produk'] ?></td>
							<td class="text-right"><?= $p['diskon_produk'] ?></td>
							<td class="text-right"><?= $p['jumlah'] ?></td>
						</tr>
						<?php endforeach ?>
					</tbody>
					
					<tfoot class="border-top">
						<tr class="font-weight-bold">
							<td colspan="4">Total Transaksi</td>
							<td><?= $transaksi['mata_uang'] ?></td>
							<td class="text-right"><?= $transaksi['total_produk'] ?></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		
		<h6 class="px-3 mb-3">Total</h6>
		
		<div class="row mb-3">
			<div class="col-xl">
				<table class="table table-detail-border">
					<tbody>
						<tr>
							<td scope="row" class="title">Jenis PPN</td>
							<td><?=$transaksi['jenis_ppn']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Besar PPN</td>
							<td><?=$transaksi['besar_ppn'] ? $transaksi['besar_ppn'].' %' : '-'?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Akun PPN</td>
							<td><?=$transaksi['akun_ppn'].' - '.$transaksi['nama_ppn']?></td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="col-xl">
				<table class="table table-detail-border">
					<tbody>
						<tr>
							<td scope="row" class="title">Diskon Luar</td>
							<td class="text-right"><?= $transaksi['mata_uang'] ?></td>
							<td class="text-right"><?= $transaksi['diskon_luar'] ?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Netto</td>
							<td class="text-right"><?= $transaksi['mata_uang'] ?></td>
							<td class="text-right"><?= $transaksi['netto'] ?></td>
						</tr>
						<tr>
							<td scope="row" class="title">PPN</td>
							<td class="text-right"><?= $transaksi['mata_uang'] ?></td>
							<td class="text-right"><?= $transaksi['total_ppn'] ?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Total Keseluruhan</td>
							<td class="text-right"><?= $transaksi['mata_uang'] ?></td>
							<td class="text-right"><?= $transaksi['total_fin'] ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<style>
	.modal-body {
		background-color: whitesmoke;
	}
	.table {
		background-color: white;
	}
</style>

<script>
	$('[data-toggle="tooltip"]').mouseover(function() {
		$(this).tooltip();
	});
</script>