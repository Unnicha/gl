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
								<input type="text" class="form-control" name="nama" id="nama" value="<?= $anggota['nama'] ?>" readonly>
								<?= form_error('nama') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Password Baru</label>
								<input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password baru" autofocus>
								<?= form_error('password') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Konfirmasi Password</label>
								<input type="password" class="form-control" name="confirm" id="confirm" placeholder="Konfirmasi password">
								<?= form_error('confirm') ?>
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