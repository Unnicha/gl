<div class="modal-header px-4">
	<h4 class="modal-title"><?= $title ?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>

<div class="modal-body p-3">
	<div class="container-fluid p-0">
		<div class="row">
			<div class="col">
				<table class="table table-detail table-striped mb-4">
					<tbody>
						<tr>
							<td scope="row" class="title">Status Jurnal</td>
							<td><?=$transaksi['status_jurnal']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Kode Transaksi</td>
							<td><?=$transaksi['kode_transaksi']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Tanggal Transaksi</td>
							<td><?=$transaksi['tanggal_transaksi']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Jenis Saldo</td>
							<td><?=$transaksi['jenis_saldo']?></td>
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
						<tr>
							<td scope="row" class="title">Jumlah</td>
							<td><?=$transaksi['mata_uang'].' '.$transaksi['jumlah']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Nilai Konversi</td>
							<td>Rp <?=$transaksi['konversi']?></td>
						</tr>
						<tr>
							<td scope="row" class="title">Total</td>
							<td>Rp <?=$transaksi['total']?></td>
						</tr>
					</tbody>
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