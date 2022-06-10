<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<form action="" method="post">
		<div class="card">
			<div class="card-body px-4">
				<div class="row row-card">
					<div class="col">
						<!-- <div class="form-group row">
							<label class=" col-form-label col-md-3 col-lg-2">Kode Pelanggan</label>
							<div class="col">
								<input type="text" class="form-control" name="kode_pelanggan" id="kode_pelanggan" placeholder="Masukkan kode pelanggan" value="<?= set_value('kode_pelanggan'); ?>" required>
								<div id="error-kode"><?= form_error('kode_pelanggan') ?></div>
							</div>
						</div> -->
						
						<div class="form-group row">
							<label class=" col-form-label col-md-3 col-lg-2">Nama Pelanggan</label>
							<div class="col">
								<input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" placeholder="Masukkan nama pelanggan" value="<?= set_value('nama_pelanggan'); ?>" required>
								<?= form_error('nama_pelanggan') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<label class=" col-form-label col-md-3 col-lg-2">NPWP</label>
							<div class="col">
								<input type="text" class="form-control" name="npwp_pelanggan" id="npwp_pelanggan" placeholder="Masukkan NPWP pelanggan" value="<?= set_value('npwp_pelanggan'); ?>">
								<?= form_error('npwp_pelanggan') ?>
							</div>
						</div>
	
						<div class="form-group row">
							<label class=" col-form-label col-md-3 col-lg-2">Alamat</label>
							<div class="col">
								<textarea name="alamat_pelanggan" id="alamat_pelanggan" maxlength="250" class="form-control" placeholder="Masukkan alamat pelanggan"><?= set_value('alamat_pelanggan'); ?></textarea>
								<?= form_error('alamat_pelanggan') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<label class=" col-form-label col-md-3 col-lg-2">Email</label>
							<div class="col">
								<input type="text" class="form-control" name="email_pelanggan" id="email_pelanggan" placeholder="Masukkan email pelanggan" value="<?= set_value('email_pelanggan'); ?>">
								<?= form_error('email_pelanggan') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<label class=" col-form-label col-md-3 col-lg-2">Telepon</label>
							<div class="col">
								<input type="text" class="form-control phone-mask" name="tlp_pelanggan" id="tlp_pelanggan" placeholder="Masukkan no. telepon pelanggan" value="<?= set_value('tlp_pelanggan'); ?>">
								<?= form_error('tlp_pelanggan') ?>
							</div>
						</div>
	
						<div class="form-group row">
							<label class=" col-form-label col-md-3 col-lg-2">Fax</label>
							<div class="col">
								<input type="text" class="form-control phone-mask" name="fax_pelanggan" id="fax_pelanggan" placeholder="Masukkan no. fax pelanggan" value="<?= set_value('fax_pelanggan'); ?>">
								<?= form_error('fax_pelanggan') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<label class=" col-form-label col-md-3 col-lg-2">Akun Kas</label>
							<div class="col">
								<select class="form-control selectpicker" name="akun_kas" id="akun_kas" data-live-search="true" data-size="5" required>
								<option value="">Pilih Akun Kas</option>
									<?php foreach($kas as $k): ?>
									<?php $pilih = set_value('akun_kas') ? 'selected' : ''; ?>
									<option value="<?= $k['kode_akun'] ?>" <?=$pilih?>>
										<?= $k['kode_akun'].' - '.$k['nama_akun'] ?>
									</option>
									<?php endforeach ?>
								</select>
								<?= form_error('akun_kas') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<label class=" col-form-label col-md-3 col-lg-2">Akun Bank</label>
							<div class="col">
								<select class="form-control selectpicker" name="akun_bank" id="akun_bank" data-live-search="true" data-size="5" required>
								<option value="">Pilih Akun Bank</option>
									<?php foreach($bank as $k): ?>
									<?php $pilih = set_value('akun_bank') ? 'selected' : ''; ?>
									<option value="<?= $k['kode_akun'] ?>" <?=$pilih?>>
										<?= $k['kode_akun'].' - '.$k['nama_akun'] ?>
									</option>
									<?php endforeach ?>
								</select>
								<?= form_error('akun_bank') ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- card-body -->
		</div>
		<!-- card -->
		
		<div class="row mt-4">
			<div class="col">
				<button type="submit" class="btn btn-success">Submit</button>
				<a href="<?= base_url('pelanggan') ?>" class="btn btn-secondary">
					<span class="text">Batal</span>
				</a>
				<button type="reset" class="btn btn-link">Reset</button>
			</div>
		</div>
	</form>
</div>
<!-- container-fluid -->

<script type="text/javascript" src="<?= base_url() ?>asset/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/jquery.mask.min.js"></script>
<script>
	$('#selectpicker').selectpicker();
	
	$('#npwp_pelanggan').mask('00.000.000.0-000.000', {placeholder: '00.000.000.0-000.000'})
	$('.phone-mask').mask('(000) 0000 0000', {placeholder: '(000) 0000 0000'})
	
	$('#kode_pelanggan').keyup(function() {
		$.ajax({
			url : '<?= base_url() ?>pelanggan/cekKode',
			method : 'post',
			data : {
				kode : $('#kode_pelanggan').val(),
			},
			success : function(result) {
				$('#error-kode').html(result);
			}
		})
	})
</script>
