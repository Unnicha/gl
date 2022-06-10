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
							<td class="title">Nama Akun</td>
							<td><?=$perkiraan['kode_akun'].' - '.$perkiraan['nama_akun']?></td>
						</tr>
						<tr>
							<td class="title">Golongan</td>
							<td><?=$perkiraan['golongan']?></td>
						</tr>
						<tr>
							<td class="title">Jenis Akun</td>
							<td><?= $perkiraan['nama_jenis'] ?></td>
						</tr>
						<tr>
							<td class="title">Tipe</td>
							<td><?= $perkiraan['tipe_akun'] ?></td>
						</tr>
						<tr>
							<td class="title">Tingkat</td>
							<td><?= $perkiraan['tingkat'] ?></td>
						</tr>
						<tr>
							<td class="title">Induk</td>
							<td><?= $perkiraan['induk'].' - '.$perkiraan['nama_induk'] ?></td>
						</tr>
						<tr>
							<td class="title">Saldo Normal</td>
							<td><?= $perkiraan['saldo_normal'] ?></td>
						</tr>
						<tr>
							<td class="title">Saldo Awal</td>
							<td><?= $perkiraan['saldo_awal'] ?></td>
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