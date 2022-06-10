<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<form action="" method="post">
		<div class="card mb-4" >
			<div class="card-body px-4">
				<div class="row justify-content-center" style="margin-bottom: -.9rem;">
					<div class="col">
						<div class="form-group row">
							<div class="col">
								<label>Nama</label>
								<input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkan Nama" value="<?= set_value('nama') ?>" required>
								<?= form_error('nama') ?>
							</div>  
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Username</label>
								<input type="text" class="form-control" name="username" id="username" placeholder="Masukkan Username" value="<?= set_value('username') ?>" required>
								<?= form_error('username') ?>
							</div>  
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Password</label>
								<input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Password" value="<?= set_value('password') ?>" required>
								<?= form_error('password') ?>
							</div>  
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Konfirmasi Password</label>
								<input type="password" class="form-control" name="confirm" id="confirm" placeholder="Masukkan Konfirmasi Password" value="<?= set_value('confirm') ?>" required>
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
				<a href="<?= base_url() ?>admin" class="btn btn-secondary">
					<span class="text">Batal</span>
				</a>
			</div>
		</div>
	</form> 
</div>
                   