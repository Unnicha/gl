<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<form action="" method="post">
		<div class="d-none" id="base_url"><?= base_url() ?></div>
		<input type="hidden" id="kode_transaksi" name="kode_transaksi" value="<?= $transaksi['kode_transaksi'] ?>">
		
		<div class="card">
			<div class="card-body px-4">
				<div class="row" style="margin-bottom: -15px;">
					<div class="col-lg">
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Status Jurnal</label>
								<?php 
									$status	= set_value('status_jurnal') ? set_value('status_jurnal') : $transaksi['status_jurnal'];
									$mutasi	= $status == 'Mutasi' ? 'selected' : '';
									$peny	= $status == 'Penyesuaian' ? 'selected' : '';
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
									$saldo	= set_value('jenis_saldo') ? set_value('jenis_saldo') : $transaksi['jenis_saldo'];
									$debit	= $saldo == 'Debit' ? 'selected' : '';
									$kredit	= $saldo == 'Kredit' ? 'selected' : '';
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
								<label class="label-bold"  for="no_voucher">No. Voucher</label>
								<input type="text" class="form-control" name="no_voucher" id="no_voucher" value="<?= set_value('no_voucher') ? set_value('no_voucher') : $transaksi['no_voucher'] ?>" required>
								<?= form_error('no_voucher'); ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold"  for="tanggal">Tanggal Transaksi</label>
								<div class="input-group">
									<input type="text" class="form-control datepicker" name="tanggal" id="tanggal" min="<?=$min_date?>" max="<?=$max_date?>" value="<?= set_value('tanggal') ? set_value('tanggal') : $transaksi['tanggal_transaksi'] ?>" autocomplete="off" readonly required>
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
											<?php $value = set_value('mata_uang') ? set_value('mata_uang') : $transaksi['mata_uang']; ?>
											<?php $pilih = ($value == $m['kode_mu']) ? 'selected' : ''; ?>
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
											<input type="text" class="form-control mask_money" name="konversi" id="konversi" value="<?= set_value('konversi') ? set_value('konversi') : $transaksi['konversi'] ?>" required>
										</div>
										<?= form_error('jumlah') ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-lg">
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Akun Kas</label>
								<select name="akun_asal" id="akun_asal" class="form-control selectpicker" data-live-search="true" data-size="5" required>
									<option value="">--Pilih Akun--</option>
									<?php foreach($akun_asal as $k) : ?>
									<?php $value = set_value('akun_asal') ? set_value('akun_asal') : $transaksi['akun_asal']; ?>
									<?php $pilih = ($value == $k['kode_akun']) ? 'selected' : '' ?>
									<option value="<?=$k['kode_akun']?>" data-subtext="<?=$k['nama_jenis']?>" <?=$pilih?>><?= $k['kode_akun'].' - '.$k['nama_akun'] ?></option>
									<?php endforeach; ?>
								</select>
								<?= form_error('akun_asal'); ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Akun Lawan</label>
								<select name="akun_lawan" id="akun_lawan" class="form-control selectpicker" data-live-search="true" data-size="5" required>
									<option value="">--Pilih Akun--</option>
									<?php foreach($akun_lawan as $k) : ?>
									<?php $value = set_value('akun_lawan') ? set_value('akun_lawan') : $transaksi['akun_lawan']; ?>
									<?php $pilih = ($value == $k['kode_akun']) ? 'selected' : '' ?>
									<option value="<?=$k['kode_akun']?>" data-subtext="<?=$k['nama_jenis']?>" <?=$pilih?>><?= $k['kode_akun'].' - '.$k['nama_akun'] ?></option>
									<?php endforeach; ?>
								</select>
								<?= form_error('akun_lawan'); ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Jumlah</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text icon-small mata-uang"></span>
									</div>
									<input type="text" class="form-control mask_money" name="jumlah" id="jumlah" value="<?= set_value('jumlah') ? set_value('jumlah') : $transaksi['jumlah'] ?>" required>
								</div>
								<?= form_error('konversi') ?>
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
								<input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Masukkan Keterangan" value="<?= set_value('keterangan') ? set_value('keterangan') : $transaksi['ket_transaksi'] ?>" required>
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
