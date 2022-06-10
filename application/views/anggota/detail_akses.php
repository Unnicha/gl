<div class="modal-header px-4">
	<h4 class="modal-title"><?= $title ?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>

<div class="modal-body p-4">
	<div class="container-fluid p-0">
		<!-- <div class="row mb-1">
			<div class="col">
				<h6>Akses Perusahaan</h6>
			</div>
			
			<div class="col-auto">
				<a href="<?= base_url('anggota/ubah_akses/'.$id_user) ?>" class="btn btn-sm btn-primary">Ubah</a>
			</div>
		</div> -->
		
		<div class="row">
			<div class="col p-0">
				<table class="table table-bordered table-striped mb-0">
					<thead>
						<tr>
							<th>No.</th>
							<th>Perusahaan</th>
							<th>Tahun</th>
							<th>Otoritas</th>
						</tr>
					</thead>
					
					<tbody>
						<?php if($akses) : ?>
							<?php foreach($akses as $num => $a) : ?>
								<tr>
									<td><?= $num + 1 ?>.</td>
									<td><?= $a['nama_perusahaan'] ?></td>
									<td><?= $a['tahun'] ?></td>
									<td>
										<a href="#" class="badge badge-info badge-action">
											<i class="bi bi-search pr-1"></i>
											<small>Lihat Otoritas</small>
										</a>
									</td>
								</tr>
							<?php endforeach ?>
						<?php else : ?>
							<tr>
								<td colspan="3" class="text-center">
									No data available
								</td>
							</tr>
						<?php endif ?>
					</tbody>
				</table>
			</div>
		</div>
		
		<!-- <div class="row">
			<div class="col">
				<h6>Otoritas Lainnya</h6>
				
				<table class="table table-bordered table-striped">
					<tbody>
						<tr>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div> -->
	</div>
</div>

<div class="modal-footer px-4">
	<a href="<?= base_url('anggota/ubah_akses/'.$id_user) ?>" class="btn btn-sm btn-primary">Ubah</a>
</div>

<script>
	$('[data-toggle="tooltip"]').mouseover(function() {
		$(this).tooltip();
	});
</script>