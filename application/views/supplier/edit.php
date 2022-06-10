<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<form action="" method="post">
		<input type="hidden" name="kode_supplier" id="kode_supplier" value="<?= $supplier['kode_supplier'] ?>">
		
		<div class="card">
			<div class="card-body px-4">
				<div class="row row-card">
					<div class="col-lg">
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Nama Pelanggan</label>
								<input type="text" class="form-control" name="nama_supplier" id="nama_supplier" placeholder="Masukkan nama supplier" value="<?= set_value('nama_supplier') ? set_value('nama_supplier') : $supplier['nama_supplier'] ?>" required>
								<?= form_error('nama_supplier') ?>
							</div>
						</div>
						
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">NPWP</label>
								<input type="text" class="form-control" name="npwp_supplier" id="npwp_supplier" placeholder="Masukkan NPWP supplier" value="<?= set_value('npwp_supplier') ? set_value('npwp_supplier') : $supplier['npwp'] ?>">
								<?= form_error('npwp_supplier') ?>
							</div>
						</div>
						
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Akun Kas</label>
								<select class="form-control selectpicker" name="akun_kas" id="akun_kas" data-live-search="true" data-size="5">
									<option value="">Pilih Akun Kas</option>
									<?php foreach($kas as $k):?>
									<?php $value = set_value('akun_kas') ? set_value('akun_kas') : $supplier['akun_kas'] ?>
									<?php $pilih = ($value == $k['kode_akun']) ? 'selected' : '' ?>
									<option value="<?= $k['kode_akun'] ?>" <?=$pilih?>>
										<?= $k['kode_akun'].' - '.$k['nama_akun'] ?>
									</option>
									<?php endforeach ?>
								</select>
								<?= form_error('akun_kas') ?>
							</div>
						</div>
						
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Akun Bank</label>
								<select class="form-control selectpicker" name="akun_bank" id="akun_bank" data-live-search="true" data-size="5">
									<option value="">Pilih Akun Bank</option>
									<?php foreach($bank as $b):?>
									<?php $value = set_value('akun_bank') ? set_value('akun_bank') : $supplier['akun_bank'] ?>
									<?php $pilih = ($value == $b['kode_akun']) ? 'selected' : '' ?>
									<option value="<?= $b['kode_akun'] ?>" <?=$pilih?>>
										<?= $b['kode_akun'].' - '.$b['nama_akun'] ?>
									</option>
									<?php endforeach;?>
								</select>
								<?= form_error('akun_bank') ?>
							</div>
						</div>
						
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Akun Hutang</label>
								<select class="form-control selectpicker" name="akun_utang" id="akun_utang" data-live-search="true" data-size="5">
									<option value="">Pilih Akun Hutang</option>
									<?php foreach($utang as $b):?>
									<?php $value = set_value('akun_utang') ? set_value('akun_utang') : $supplier['akun_utang'] ?>
									<?php $pilih = ($value == $b['kode_akun']) ? 'selected' : '' ?>
									<option value="<?= $b['kode_akun'] ?>" <?=$pilih?>>
										<?= $b['kode_akun'].' - '.$b['nama_akun'] ?>
									</option>
									<?php endforeach; ?>
								</select>
								<?= form_error('akun_utang') ?>
							</div>
						</div>
					</div>
					
					<div class="col-lg">
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Email</label>
								<input type="text" name="email_supplier" id="email_supplier" class="form-control" placeholder="Masukkan email supplier" value="<?= set_value('email_supplier') ? set_value('email_supplier') : $supplier['email'] ?>">
								<?= form_error('email_supplier') ?>
							</div>
						</div>
						
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Telepon</label>
								<input type="text" class="form-control phone-mask" name="tlp_supplier" id="tlp_supplier" placeholder="Masukkan no. telepon supplier" value="<?= set_value('tlp_supplier') ? set_value('tlp_supplier') : $supplier['telp'] ?>">
								<?= form_error('tlp_supplier') ?>
							</div>
						</div>
	
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Fax</label>
								<input type="text" class="form-control phone-mask" name="fax_supplier" id="fax_supplier" placeholder="Masukkan no. fax supplier" value="<?= set_value('fax_supplier') ? set_value('fax_supplier') : $supplier['fax'] ?>">
								<?= form_error('fax_supplier') ?>
							</div>
						</div>
						
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Alamat</label>
								<textarea name="alamat_supplier" id="alamat_supplier" maxlength="250" class="form-control" placeholder="Masukkan alamat supplier"><?= set_value('alamat_supplier') ? set_value('alamat_supplier') : $supplier['alamat'] ?></textarea>
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
				<button type="submit" class="btn btn-success">Ubah</button>
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
</script>
