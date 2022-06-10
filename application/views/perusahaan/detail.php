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
				<table class="table table-big table-striped mb-4">
					<tbody>
						<tr>
							<td class="title">Kode Perusahaan</td>
							<td><?=$perusahaan['kode_perusahaan']?></td>
						</tr>
						<tr>
							<td class="title">Nama Perusahaan</td>
							<td><?=$perusahaan['nama_perusahaan']?></td>
						</tr>
						<tr>
							<td class="title">Alamat</td>
							<td><?=$perusahaan['alamat']?></td>
						</tr>
						<tr>
							<td class="title">Telepon</td>
							<td><?=$perusahaan['tlp']?></td>
						</tr>
						<tr>
							<td class="title">Fax</td>
							<td><?=$perusahaan['fax']?></td>
						</tr>
						<tr>
							<td class="title">NPWP</td>
							<td><?=$perusahaan['npwp']?></td>
						</tr>
						<tr>
							<td class="title">Kode Pajak</td>
							<td><?=$perusahaan['kode_pajak']?></td>
						</tr>
						<tr>
							<td class="title">Tanggal PKP</td>
							<td><?=$perusahaan['tgl_pkp']?></td>
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