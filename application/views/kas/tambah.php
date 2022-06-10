<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<form action="" method="post">
		<div class="d-none" id="base_url"><?= base_url() ?></div>
		
		<div class="card">
			<div class="card-body px-4">
				<div class="row" style="margin-bottom: -15px;">
					<div class="col-md">
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Status Jurnal</label>
								<?php 
									$mutasi	= set_value('status_jurnal') == 'Mutasi' ? 'selected' : '';
									$peny	= set_value('status_jurnal') == 'Penyesuaian' ? 'selected' : '';
								?>
								<select name="status_jurnal" id="status_jurnal" class="form-control selectpicker" required>
									<option value="">--Status Jurnal--</option>
									<option value="Mutasi" <?=$mutasi?>>Mutasi</option>
									<option value="Penyesuaian" <?=$peny?>>Penyesuaian</option>
								</select>
								<?= form_error('status_jurnal') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Jenis Saldo</label>
								<?php 
									$debit	= set_value('jenis_saldo') == 'Debit' ? 'selected' : '';
									$kredit	= set_value('jenis_saldo') == 'Kredit' ? 'selected' : '';
								?>
								<select name="jenis_saldo" id="jenis_saldo" class="form-control selectpicker" required>
									<option value="">--Jenis Saldo--</option>
									<option value="Debit" <?=$debit?> >Debit</option>
									<option value="Kredit" <?=$kredit?> >Kredit</option>
								</select>
								<?= form_error('jenis_saldo') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">No. Voucher</label>
								<input type="text" class="form-control" name="no_voucher" id="no_voucher" value="<?= set_value('no_voucher') ?>" placeholder="Masukkan no. voucher" required>
								<?= form_error('no_voucher') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Tanggal Transaksi</label>
								<div class="input-group">
									<input type="text" class="form-control datepicker" name="tanggal" id="tanggal" min="<?=$min_date?>" max="<?=$max_date?>" value="<?= set_value('tanggal') ?>" placeholder="Masukkan Tanggal" autocomplete="off" readonly required>
									<div class="input-group-append btn-date">
										<span class="input-group-text">
											<i class="bi bi-calendar icon-small"></i>
										</span>
									</div>
								</div>
								<?= form_error('tanggal') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Mata Uang</label>
								<div class="form-row">
									<div class="col-auto pr-0">
										<select name="mata_uang" id="mata_uang" class="form-control" required>
											<?php foreach($mata_uang as $m) : ?>
											<?php $value = (set_value('mata_uang')) ? set_value('mata_uang') : 'IDR'; ?>
											<?php $pilih = $m['kode_mu'] == $value ? 'selected' : ''; ?>
											<option <?=$pilih?>><?= $m['kode_mu'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									
									<div class="col">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text icon-small">Nilai Konversi</span>
												<span class="input-group-text icon-small rupiah">Rp</span>
											</div>
											<input type="text" class="form-control mask_money" name="konversi" id="konversi" value="<?= set_value('konversi') ?>" numeric="numeric" required>
										</div>
										<?= form_error('konversi') ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md">
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Akun Kas</label>
								<select name="akun_asal" id="akun_asal" class="form-control selectpicker" data-live-search="true" data-size="5" required>
									<option value="">--Pilih Akun--</option>
									<?php foreach($akun_asal as $k) : ?>
									<?php $pilih = (set_value('akun_asal') == $k['kode_akun']) ? 'selected' : '' ?>
									<option value="<?=$k['kode_akun']?>" data-subtext="<?=$k['nama_jenis']?>" <?=$pilih?>><?= $k['kode_akun'].' - '.$k['nama_akun'] ?></option>
									<?php endforeach; ?>
								</select>
								<?= form_error('akun_asal') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Akun Lawan</label>
								<select name="akun_lawan" id="akun_lawan" class="form-control selectpicker" data-live-search="true" data-size="5" required>
									<option value="">--Pilih Akun--</option>
									<?php foreach($akun_lawan as $k) : ?>
									<?php $pilih = (set_value('akun_lawan') == $k['kode_akun']) ? 'selected' : '' ?>
									<option value="<?=$k['kode_akun']?>" data-subtext="<?=$k['nama_jenis']?>" <?=$pilih?>><?= $k['kode_akun'].' - '.$k['nama_akun'] ?></option>
									<?php endforeach; ?>
								</select>
								<?= form_error('akun_lawan') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Jumlah</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text icon-small mata-uang"></span>
									</div>
									<input type="text" class="form-control mask_money" name="jumlah" id="jumlah" placeholder="Masukkan jumlah transaksi" value="<?= set_value('jumlah') ?>" required>
								</div>
								<?= form_error('jumlah') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Total</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text icon-small rupiah">Rp</span>
									</div>
									<input type="text" class="form-control mask_money" name="total" id="total" readonly required>
								</div>
								<?= form_error('total') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Keterangan</label>
								<input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Masukkan keterangan" value="<?= set_value('keterangan') ?>" required>
								<?= form_error('keterangan') ?>
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
				<a href="<?= base_url('kas') ?>" class="btn btn-secondary">
					<span class="text">Batal</span>
				</a>
			</div>
		</div>
	</form> 
	<!-- content-body -->
</div>
<!-- container-fluid -->


<script type="text/javascript" src="<?= base_url() ?>asset/js/datepicker.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/jquery.mask.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/form/kas.js"></script>
<script>
	konversi()
</script>
