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
					
					<form method="post" class="login-form validate-form flex-sb flex-w">
						<input type="hidden" id="base_url" value="<?= base_url() ?>">
						<input type="hidden" name="redirect_to" value="<?= $redirect_to ?>">
						
						<div class="form-group row">
							<div class="col">
								<label class="label-input">Perusahaan</label>
								<input type="text" class="form-control border-0" name="perusahaan" id="perusahaan" value="<?= $this->session->userdata('nama_perusahaan') ?>" readonly>
								<?= form_error('perusahaan') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-input">Tahun</label>
								<select class="form-control selectpicker" name="tahun" id="tahun" required>
									<option value="">Pilih Tahun</option>
									<?php foreach($perusahaan as $a): ?>
									<?php $tahun = $this->session->userdata('tahun_aktif') ?>
									<?php $pilih = ($a['tahun'] == $tahun) ? 'selected' : ''; ?>
									<option value="<?= $a['tahun'] ?>" <?=$pilih?>><?= $a['tahun'] ?></option>
									<?php endforeach ?>
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
									<?php $bulan = $this->session->userdata('bulan_aktif') ?>
									<?php $pilih = ($b['id'] == $bulan) ? 'selected' : ''; ?>
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
		<script>
			$('.selectpicker').selectpicker()
		</script>
	</body>
</html>
