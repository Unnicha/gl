<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="asset/css/bootstrap-select.min.css">
		<link rel="stylesheet" type="text/css" href="asset/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
		<!-- <link rel="stylesheet" type="text/css" href="asset/css/login_util.css"> -->
		<link rel="stylesheet" type="text/css" href="asset/css/login.css">
	
		<title><?= $title ?></title>
	</head>
	
	<body>
		<div class="limiter">
			<div class="container-login">
				
				<div class="card-login">
					<span class="login-form-title">
						<?= $title ?>
					</span>
					<?php // echo 'perusahaan '; print_r($perusahaan) ?>
					
					<form method="post" class="login-form validate-form flex-sb flex-w">
						<input type="hidden" id="base_url" value="<?= base_url() ?>">
						<input type="hidden" name="redirect_to" value="<?= $redirect_to ?>">
						
						<div class="form-group row">
							<div class="col">
								<label class="label-input">Perusahaan</label>
								<select class="form-control selectpicker" name="perusahaan" id="perusahaan" required>
									<option value="">Pilih Perusahaan</option>
									<?php foreach($perusahaan as $p): ?>
									<?php $pilih = set_value('perusahaan') ? 'selected' : ''; ?>
									<option value="<?= $p['kode_perusahaan'] ?>" <?=$pilih?>>
										<?= $p['kode_perusahaan'].' - '.$p['nama_perusahaan'] ?>
									</option>
									<?php endforeach ?>
								</select>
								<?= form_error('perusahaan') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-input">Tahun</label>
								<select class="form-control selectpicker" name="tahun" id="tahun" required>
									<option value="">Pilih Tahun</option>
								</select>
								<?= form_error('tahun') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-input">Bulan</label>
								<select class="form-control selectpicker" name="bulan" id="bulan" required>
									<option value="">Pilih Bulan</option>
									<?php foreach($bulan as $b): ?>
									<?php $pilih = set_value('bulan') ? 'selected' : ''; ?>
									<option value="<?= $b['id'] ?>" <?=$pilih?>><?= $b['nama'] ?></option>
									<?php endforeach ?>
								</select>
								<?= form_error('bulan') ?>
							</div>
						</div>
	
						<div class="row mt-5">
							<div class="col">
								<button class="login-form-btn">Pilih</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	
		<script src="asset/js/jquery.js"></script>
		<script src="asset/js/bootstrap.bundle.min.js"></script>
		<script src="asset/js/bootstrap-select.min.js"></script>
		<script src="asset/js/view/pilih_perusahaan.js"></script>
		<script>
			$('.selectpicker').selectpicker()
		</script>
	</body>
</html>
