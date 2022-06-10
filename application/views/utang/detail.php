<div class="modal-header px-4">
	<h4 class="modal-title"><?= $title ?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>

<div class="modal-body p-3">
	<div class="container-fluid p-0">
		<div class="row mb-4">
			<div class="col-lg">
				<table class="table table-detail-border table-striped">
					<tbody>
						<tr>
							<td scope="row" class="title">Status Jurnal</td>
							<td><?=$transaksi['status_jurnal']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Tanggal Transaksi</td>
							<td><?=$transaksi['tanggal_transaksi']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Kode Transaksi</td>
							<td><?=$transaksi['kode_transaksi']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Jenis Saldo</td>
							<td><?=$transaksi['jenis_saldo']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Mata Uang</td>
							<td><?=$transaksi['mata_uang']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Nilai Konversi</td>
							<td><?='Rp '. number_format($transaksi['konversi'],2,',','.')?></td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="col-lg">
				<table class="table table-detail-border table-striped">
					<tbody>
						<tr>
							<td scope="row" class="title">Supplier</td>
							<td><?=$transaksi['kode_supplier'].' - '.$transaksi['nama_supplier']?></td>
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
							<td scope="row" class="title">Akun PPN</td>
							<td><?=$transaksi['akun_ppn'].' - '.$transaksi['nama_ppn']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Keterangan</td>
							<td><?=$transaksi['ket_transaksi']?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<h6 class="px-3 mb-3">Detail Pembayaran</h6>
		
		<div class="row mb-4">
			<div class="col">
				<table class="table table-detail-border table-striped table-bordered">
					<thead>
						<tr>
							<th>No.</th>
							<th>Invoice</th>
							<th>Jatuh Tempo</th>
							<th>Jumlah Bayar</th>
							<th>Jenis PPN</th>
						</tr>
					</thead>
					
					<tbody>
						<?php foreach($pembayaran as $key => $p) : ?>
						<tr>
							<td><?= $key + 1 ?>.</td>
							<td><?= $p['faktur_beli'] ?></td>
							<td><?= Globals::dateView($p['jatuh_tempo']) ?></td>
							<td class="text-right"><?= Globals::moneyView($p['jumlah_bayar']) ?></td>
							<td><?= $p['jenis_ppn'] ?></td>
						</tr>
						<?php endforeach ?>
					</tbody>
					
					<tfoot class="border-top">
						<tr class="font-weight-bold">
							<td colspan="3">Total Pembayaran</td>
							<td class="text-right"><?= $transaksi['total_bayar'] ?></td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	$('[data-toggle="tooltip"]').mouseover(function() {
		$(this).tooltip();
	});
</script>