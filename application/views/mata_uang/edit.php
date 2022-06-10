<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<div class="content-body my-4">
		<form action="" method="post">
			<div class="card shadow">
				<div class="card-body px-4">
					<div class="row" style="margin-bottom: -15px;">
						<div class="col">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label" for="kode_mu">Kode Mata Uang</label>
						<div class="col">
							<input type="text" class="form-control" name="kode_mu" id="kode_mu" value="<?= $mata_uang['kode_mu'] ?>" readonly>
							<?= form_error('kode_mu', '<small class="text-danger">', '</small>') ?>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-2 col-form-label" for="nama_mu">Nama Mata Uang</label>
						<div class="col">
							<input type="text" class="form-control" name="nama_mu" id="nama_mu" placeholder="-Masukkan nama mata uang-" value="<?= set_value('nama_mu') ? set_value('nama_mu') : $mata_uang['nama_mu'] ?>" required>
							<?= form_error('nama_mu', '<small class="text-danger">', '</small>') ?>
						</div>
					</div>
				</div>
				<!-- card-body -->
			</div>
			<!-- card -->
			
			<div class="row mt-4">
				<div class="col">
					<button type="submit" class="btn btn-success">Submit</button>
					<a href="<?= base_url('mata_uang') ?>" class="btn btn-secondary">
						<span class="text">Batal</span>
					</a>
				</div>
			</div>
		</form> 
	</div>
	<!-- content-body -->
</div>
<!-- container-fluid -->
