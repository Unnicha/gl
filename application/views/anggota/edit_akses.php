<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<form action="" method="post">
		<input type="hidden" id="id_user" name="id_user" value="<?= $anggota['id_user'] ?>">
		
		<div class="card mb-4">
			<div class="card-body px-4">
				<div class="row" style="margin-bottom: -.9rem;">
					<div class="col">
						<div class="form-group row">
							<div class="col">
								<label>Nama User</label>
								<input type="text" class="form-control" name="nama" id="nama" value="<?= $anggota['nama'] ?>">
								<?= form_error('nama') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Pilih Perusahaan</label>
								<div class="row">
									<div class="col">
										<select id="perusahaan" name="perusahaan[]" multiple="multiple" style="width:100%" required>
											<?php foreach($perusahaan as $p) : ?>
												<option value="<?= $p['id_setup'] ?>" <?=$p['pilih']?>><?= $p['nama_perusahaan'].' - '.$p['tahun'] ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<?= form_error('perusahaan') ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- card-body -->
		</div>
		<!-- card -->
		
		<div class="row">
			<div class="col">
				<button type="submit" class="btn btn-success">Submit</button>
				<a href="<?= base_url() ?>anggota" class="btn btn-secondary">
					<span class="text">Batal</span>
				</a>
				<button type="reset" class="btn btn-light">Reset</button>
			</div>
		</div>
	</form> 
</div>

<script type="text/javascript" src="<?= base_url() ?>asset/js/select2.min.js"></script>
<script>
	$(document).ready(function() {
		$('#perusahaan').select2({
			placeholder: "Pilih perusahaan",
			allowClear: true,
			language: "id",
			maximumSelectionLength : 50,
		});
	});
</script>
