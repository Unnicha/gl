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
							<td><?=$supplier['kode_supplier']?></td>
						</tr>
						<tr>
							<td class="title">Nama Pelanggan</td>
							<td><?=$supplier['nama_supplier']?></td>
						</tr>
						<tr>
							<td class="title">Alamat</td>
							<td><?=$supplier['alamat']?></td>
						</tr>
						<tr>
							<td class="title">NPWP</td>
							<td><?= $supplier['npwp'] ?></td>
						</tr>
						<tr>
							<td class="title">Telepon</td>
							<td><?= $supplier['telp'] ?></td>
						</tr>
						<tr>
							<td class="title">Fax</td>
							<td><?= $supplier['fax'] ?></td>
						</tr>
						<tr>
							<td class="title">Akun Kas</td>
							<td><?= $supplier['akun_kas'].' - '.$supplier['nama_kas'] ?></td>
						</tr>
						<tr>
							<td class="title">Akun Bank</td>
							<td><?= $supplier['akun_bank'].' - '.$supplier['nama_bank'] ?></td>
						</tr>
						<tr>
							<td class="title">Akun Utang</td>
							<td><?= $supplier['akun_utang'].' - '.$supplier['nama_utang'] ?></td>
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