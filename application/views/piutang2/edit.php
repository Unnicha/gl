<div class="content container-fluid">
	<div class="row content-title">
		<div class="col-sm">
			<div class="mb-2 mb-sm-0"><?=$title?></div>
		</div>
			
		<div class="col-sm-auto">
			<a href="<?= base_url() ?>piutang" class="close">
				<h1 class="bi bi-x mb-0" style="line-height: .5;"></h1>
			</a>
		</div>
	</div>
	
	<form id="myForm" name="myForm" action="" method="post" novalidate>
		<input type="hidden" name="kode_bayar" id="kode_bayar" value="<?=$pembayaran['kode_bayar']?>">
		
		<div class="card mb-4">
			<div class="card-body">
				<div class="row row-card">
					<div class="col-lg">
						<!-- pilih Pelanggan -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Pelanggan</label>
								<select name="pelanggan" id="pelanggan" data-target="#err-pelanggan" class="form-control selectpicker" required>
									<?php foreach($pelanggan as $p) : ?>
									<option value="<?=$p['kode_pelanggan']?>" selected>
										<?= $p['kode_pelanggan'].' - '.$p['nama_pelanggan'] ?>
									</option>
									<?php endforeach ?>
								</select>
								<p class="mb-0" id="err-pelanggan"></p>
							</div>
						</div>
						
						<!-- input Akun Deposit -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Akun Deposit</label>
								<select name="akun_asal" id="akun_asal" data-target="#err-akun_asal" class="form-control selectpicker" data-live-search="true" data-size="5" required>
									<?php foreach($akun_asal as $k) : ?>
									<?php $pilih = ($pembayaran['akun_asal'] == $k['kode_akun']) ? 'selected' : '' ?>
									<option value="<?=$k['kode_akun']?>" data-subtext="<?=$k['nama_jenis']?>" <?=$pilih?>><?= $k['kode_akun'].' - '.$k['nama_akun'] ?></option>
									<?php endforeach ?>
								</select>
								<p class="mb-0" id="err-akun_asal"></p>
							</div>
						</div>
					</div>
					
					<div class="col-lg">
						<!-- input Tanggal Pembayaran -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Tanggal Pembayaran</label>
								<div class="input-group">
									<div class="input-group-prepend btn-date">
										<span class="input-group-text">
											<i class="bi bi-calendar icon-small"></i>
										</span>
									</div>
									<input type="text" name="tanggal" id="tanggal" data-target="#err-tanggal" class="form-control datepicker" placeholder="Masukkan Tanggal" value="<?= $pembayaran['tanggal_bayar'] ?>" autocomplete="off" readonly required>
								</div>
								<p class="mb-0" id="err-tanggal"></p>
							</div>
						</div>
						
						<!-- input mata uang & konversi -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Mata Uang</label>
								<div class="form-row">
									<!-- input mata uang -->
									<div class="col-sm-auto">
										<select class="form-control" name="mata_uang" id="mata_uang" data-target="#err-mata_uang" required>
											<?php foreach($mata_uang as $m) : ?>
											<?php $pilih = ($m['kode_mu'] == $pembayaran['mata_uang']) ? 'selected' : '' ?>
											<option <?=$pilih?>><?= $m['kode_mu'] ?></option>
											<?php endforeach; ?>
										</select>
										<p class="mb-0" id="err-mata_uang"></p>
									</div>
									
									<!-- input konversi -->
									<div class="col-sm">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text icon-small">Nilai Konversi</span>
											</div>
											<input type="text" name="konversi" id="konversi" data-target="#err-konversi" class="form-control mask_money" placeholder="Masukkan nilai konversi" value="<?= $pembayaran['konversi'] ?>" required>
										</div>
										<p class="mb-0" id="err-konversi"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- col-lg -->
				</div>
				<!-- row -->
			</div>
			<!-- cadr-body -->
		</div>
		<!-- card -->
		
		<!-- Piutang -->
		<div class="card mb-4">
			<div class="card-body">
				<div class="row mb-2">
					<div class="col">
						<p class="p-header">Tagihan</p>
					</div>
						
					<div class="col-md-auto">
						<!-- button Add Piutang -->
						<button type="button" class="btn btn-sm btn-primary add-btn">
							Add
						</button>
					</div>
				</div>
				
				<!-- Table Piutang -->
				<table id="table-piutang" class="table table-detail table-bordered table-striped table-responsive-lg mb-0">
					<thead>
						<tr>
							<th>No.</th>
							<th>No. Invoice</th>
							<th>Jatuh Tempo</th>
							<th>Jumlah Tagihan</th>
							<th>Action</th>
						</tr>
					</thead>
					
					<tbody id="listPiutang">
						<!-- List Piutang Here -->
					</tbody>
				</table>
			</div>
		</div>
		<!-- End Piutang -->
		
		<!-- Buttons -->
		<div class="row">
			<div class="col">
				<button type="submit" name="submit" class="btn btn-success" id="btn-submit">Simpan</button>
				<a href="<?= base_url('piutang') ?>" class="btn btn-secondary">
					<span class="text">Batal</span>
				</a>
			</div>
		</div>
	</form>
</div>
<!-- container-fluid -->
<div class="d-none" id="base_url"><?= base_url() ?></div>



<!-- Modal Add / Update Piutang -->
<div class="modal fade" id="modalForm">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="showForm">
		</div>
	</div>
</div>

<!-- Modal Hapus Piutang -->
<div class="modal fade modalConfirm" tabindex="-1" aria-labelledby="modalConfirmLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content mx-auto showConfirm" style="width:400px">
		</div>
	</div>
</div>


<script type="text/javascript" src="<?= base_url('asset/js/bootstrap-select.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('asset/js/datepicker.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('asset/js/jquery.mask.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('asset/js/form/piutang.js') ?>"></script>
