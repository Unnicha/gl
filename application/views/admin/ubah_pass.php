<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<form action="" method="post">
		<div class="card mb-4">
			<div class="card-body px-4">
				<input type="hidden" id="id_user" name="id_user" value="<?= $admin['id_user'] ?>">
				
				<div class="form-group row">
					<div class="col">
						<label>Nama User</label>
						<input type="text" class="form-control" name="nama" id="nama" value="<?= $admin['nama'] ?>" readonly>
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
				<div class="row">
					<div class="col">
						<label>Konfirmasi Password</label>
						<input type="password" class="form-control" name="confirm" id="confirm" placeholder="Konfirmasi password">
						<?= form_error('confirm') ?>
					</div>
				</div>
			</div>
			<!-- card-body -->
		</div>
		<!-- card -->
		
		<div class="row">
			<div class="col">
				<button type="submit" class="btn btn-success">Submit</button>
				<a href="<?= base_url() ?>admin" class="btn btn-secondary">
					<span class="text">Batal</span>
				</a>
				<button type="reset" class="btn btn-light">Reset</button>
			</div>
		</div>
	</form> 
</div>