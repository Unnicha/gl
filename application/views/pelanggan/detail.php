<div class="modal-header px-4">
	<h4 class="modal-title"><?= $judul ?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>

<div class="modal-body p-3">
	<div class="container-fluid p-0">
		<div class="row">
			<div class="col">
				<table class="table table-detail mb-4">
					<tbody>
						<tr>
							<td class="title">Kode Pelanggan</td>
							<td><?=$pelanggan['kode_pelanggan']?></td>
						</tr>
						<tr>
							<td class="title">Nama Pelanggan</td>
							<td><?=$pelanggan['nama_pelanggan']?></td>
						</tr>
						<tr>
							<td class="title">Alamat</td>
							<td><?=$pelanggan['alamat']?></td>
						</tr>
						<tr>
							<td class="title">NPWP</td>
							<td><?= $pelanggan['npwp'] ?></td>
						</tr>
						<tr>
							<td class="title">Telepon</td>
							<td><?= $pelanggan['telp'] ?></td>
						</tr>
						<tr>
							<td class="title">Fax</td>
							<td><?= $pelanggan['fax'] ?></td>
						</tr>
						<tr>
							<td class="title">Akun Kas</td>
							<td><?= $pelanggan['akun_kas'].' - '.$pelanggan['nama_kas'] ?></td>
						</tr>
						<tr>
							<td class="title">Akun Bank</td>
							<td><?= $pelanggan['akun_bank'].' - '.$pelanggan['nama_bank'] ?></td>
						</tr>
						<tr>
							<td class="title">Akun Piutang</td>
							<td><?= $pelanggan['akun_piutang'].' - '.$pelanggan['nama_piutang'] ?></td>
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