<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<form action="" method="post">
		<input type="hidden" name="kode_pelanggan" id="kode_pelanggan" value="<?= $pelanggan['kode_pelanggan'] ?>">
		
		<div class="card">
			<div class="card-body px-4">
				<div class="row row-card">
					<div class="col-lg">
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Nama Pelanggan</label>
								<input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" placeholder="Masukkan nama pelanggan" value="<?= set_value('nama_pelanggan') ? set_value('nama_pelanggan') : $pelanggan['nama_pelanggan'] ?>" required>
								<?= form_error('nama_pelanggan') ?>
							</div>
						</div>
						
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">NPWP</label>
								<input type="text" class="form-control" name="npwp_pelanggan" id="npwp_pelanggan" placeholder="Masukkan NPWP pelanggan" value="<?= set_value('npwp_pelanggan') ? set_value('npwp_pelanggan') : $pelanggan['npwp'] ?>">
								<?= form_error('npwp_pelanggan') ?>
							</div>
						</div>
						
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Akun Kas</label>
								<select class="form-control selectpicker" name="akun_kas" id="akun_kas" data-live-search="true" data-size="5">
									<option value="">Pilih Akun Kas</option>
									<?php foreach($kas as $k):?>
									<?php $value = set_value('akun_kas') ? set_value('akun_kas') : $pelanggan['akun_kas'] ?>
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
									<?php $value = set_value('akun_bank') ? set_value('akun_bank') : $pelanggan['akun_bank'] ?>
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
								<label class="label-bold">Akun Piutang</label>
								<select class="form-control selectpicker" name="akun_piutang" id="akun_piutang" data-live-search="true" data-size="5">
									<option value="">Pilih Akun Piutang</option>
									<?php foreach($utang as $b):?>
									<?php $value = set_value('akun_piutang') ? set_value('akun_piutang') : $pelanggan['akun_piutang'] ?>
									<?php $pilih = ($value == $b['kode_akun']) ? 'selected' : '' ?>
									<option value="<?= $b['kode_akun'] ?>" <?=$pilih?>>
										<?= $b['kode_akun'].' - '.$b['nama_akun'] ?>
									</option>
									<?php endforeach; ?>
								</select>
								<?= form_error('akun_piutang') ?>
							</div>
						</div>
					</div>
					
					<div class="col-lg">
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Email</label>
								<input type="text" name="email_pelanggan" id="email_pelanggan" class="form-control" placeholder="Masukkan email pelanggan" value="<?= set_value('email_pelanggan') ? set_value('email_pelanggan') : $pelanggan['email'] ?>">
								<?= form_error('email_pelanggan') ?>
							</div>
						</div>
						
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Telepon</label>
								<input type="text" class="form-control phone-mask" name="tlp_pelanggan" id="tlp_pelanggan" placeholder="Masukkan no. telepon pelanggan" value="<?= set_value('tlp_pelanggan') ? set_value('tlp_pelanggan') : $pelanggan['telp'] ?>">
								<?= form_error('tlp_pelanggan') ?>
							</div>
						</div>
	
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Fax</label>
								<input type="text" class="form-control phone-mask" name="fax_pelanggan" id="fax_pelanggan" placeholder="Masukkan no. fax pelanggan" value="<?= set_value('fax_pelanggan') ? set_value('fax_pelanggan') : $pelanggan['fax'] ?>">
								<?= form_error('fax_pelanggan') ?>
							</div>
						</div>
						
						<div class="row mb-3">
							<div class="col">
								<label class="label-bold">Alamat</label>
								<textarea name="alamat_pelanggan" id="alamat_pelanggan" maxlength="250" class="form-control" placeholder="Masukkan alamat pelanggan"><?= set_value('alamat_pelanggan') ? set_value('alamat_pelanggan') : $pelanggan['alamat'] ?></textarea>
								<?= form_error('alamat_pelanggan') ?>
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
				<a href="<?= base_url('pelanggan') ?>" class="btn btn-secondary">
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
	
	$('#npwp_pelanggan').mask('00.000.000.0-000.000', {placeholder: '00.000.000.0-000.000'})
	$('.phone-mask').mask('(000) 0000 0000', {placeholder: '(000) 0000 0000'})
</script>
