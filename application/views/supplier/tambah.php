<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<form action="" method="post">
		<div class="card">
			<div class="card-body px-4">
				<div class="row row-card">
					<div class="col-lg">
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Nama supplier</label>
								<input type="text" class="form-control" name="nama_supplier" id="nama_supplier" placeholder="Masukkan nama supplier" value="<?= set_value('nama_supplier'); ?>" required>
								<?= form_error('nama_supplier') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">NPWP</label>
								<input type="text" class="form-control" name="npwp_supplier" id="npwp_supplier" placeholder="Masukkan NPWP supplier" value="<?= set_value('npwp_supplier'); ?>">
								<?= form_error('npwp_supplier') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Akun Kas</label>
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
							<div class="col">
								<label class="label-bold">Akun Bank</label>
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
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Akun Hutang</label>
								<select class="form-control selectpicker" name="akun_utang" id="akun_utang" data-live-search="true" data-size="5" required>
								<option value="">Pilih Akun Hutang</option>
									<?php foreach($utang as $k): ?>
									<?php $pilih = set_value('akun_utang') ? 'selected' : ''; ?>
									<option value="<?= $k['kode_akun'] ?>" <?=$pilih?>>
										<?= $k['kode_akun'].' - '.$k['nama_akun'] ?>
									</option>
									<?php endforeach ?>
								</select>
								<?= form_error('akun_utang') ?>
							</div>
						</div>
					</div>
					
					<div class="col-lg">
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Email</label>
								<input type="text" class="form-control" name="email_supplier" id="email_supplier" placeholder="Masukkan email supplier" value="<?= set_value('email_supplier'); ?>">
								<?= form_error('email_supplier') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Telepon</label>
								<input type="text" class="form-control phone-mask" name="tlp_supplier" id="tlp_supplier" placeholder="Masukkan no. telepon supplier" value="<?= set_value('tlp_supplier'); ?>">
								<?= form_error('tlp_supplier') ?>
							</div>
						</div>
	
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Fax</label>
								<input type="text" class="form-control phone-mask" name="fax_supplier" id="fax_supplier" placeholder="Masukkan no. fax supplier" value="<?= set_value('fax_supplier'); ?>">
								<?= form_error('fax_supplier') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Alamat</label>
								<textarea name="alamat_supplier" id="alamat_supplier" maxlength="250" class="form-control" placeholder="Masukkan alamat supplier"><?= set_value('alamat_supplier'); ?></textarea>
								<?= form_error('alamat_supplier') ?>
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
				<a href="<?= base_url('supplier') ?>" class="btn btn-secondary">
					<span class="text">Batal</span>
				</a>
			</div>
		</div>
	</form>
</div>
<!-- container-fluid -->

<script type="text/javascript" src="<?= base_url() ?>asset/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/jquery.mask.min.js"></script>
<script>
	$('#selectpicker').selectpicker();
	
	$('#npwp_supplier').mask('00.000.000.0-000.000', {placeholder: '00.000.000.0-000.000'})
	$('.phone-mask').mask('(000) 0000 0000', {placeholder: '(000) 0000 0000'})
	
	$('#kode_supplier').keyup(function() {
		$.ajax({
			url : '<?= base_url() ?>supplier/cekKode',
			method : 'post',
			data : {
				kode : $('#kode_supplier').val(),
			},
			success : function(result) {
				$('#error-kode').html(result);
			}
		})
	})
</script>
