<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<form action="" method="post">
		<div class="card mb-4">
			<div class="card-body px-4">
				<input type="hidden" id="id_user" name="id_user" value="<?= $admin['id_user'] ?>">
				
				<div class="form-group row">
					<div class="col">
						<label for="nama">Nama</label>
						<input type="text" class="form-control" name="nama" id="nama" value="<?= $admin['nama'] ?>">
						<?= form_error('nama') ?>
					</div>
				</div>
				<div class="form-group row">
					<div class="col">
						<label for="username">Username</label>
						<input type="text" class="form-control" name="username" id="username" value="<?= $admin['username'] ?>">
						<?= form_error('username') ?>
					</div>
				</div>
				<div class="form-group row">
					<div class="col">
						<label for="password">Password</label>
						<input type="password" class="form-control" name="password" id="password" value="<?= $admin['password'] ?>">
						<?= form_error('password') ?>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<label for="password">Konfirmasi Password</label>
						<input type="password" class="form-control" name="confirm" id="confirm">
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