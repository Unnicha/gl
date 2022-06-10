<script type="text/javascript" src="<?= base_url() ?>asset/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/datepicker.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/jquery.mask.min.js"></script>


<div class="d-none" id="base_url"><?= base_url() ?></div>
<div class="content container-fluid">
	<div class="row content-title">
		<div class="col-sm">
			<div class="mb-2 mb-sm-0"><?=$title?></div>
		</div>
			
		<div class="col-sm-auto">
			<a href="<?= base_url() ?>retur_pembelian" class="close">
				<h1 class="bi bi-x mb-0" style="line-height: .5;"></h1>
			</a>
		</div>
	</div>
	
	<form id="myForm" action="" method="post" novalidate>
		<input type="hidden" name="pembelian" id="pembelian" value="<?=$pembelian?>">
		<input type="hidden" name="akun_ppn" id="akun_ppn" value="">
		
		<!-- Detail Transaksi -->
		<div class="card mb-4">
			<div class="card-body">
				<div class="row" style="margin-bottom: -.9rem;">
					<div class="col-lg">
						<!-- pilih Supplier -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Supplier</label>
								<select name="supplier" id="supplier" data-target="#err-supplier" class="form-control selectpicker" data-live-search="true" data-size="5" required>
									<option value="">Pilih Supplier</option>
									<?php foreach($supplier as $p) : ?>
									<?php $value = (set_value('supplier')) ? set_value('supplier') : $pembelian['supplier'] ?>
									<?php $pilih = ($value == $p['kode_supplier']) ? 'selected' : '' ?>
									<option value="<?=$p['kode_supplier']?>" <?=$pilih?>><?= $p['kode_supplier'].' - '.$p['nama_supplier'] ?></option>
									<?php endforeach ?>
								</select>
								<p class="mb-0" id="err-supplier"></p>
							</div>
						</div>
						
						<!-- pilih Faktur Pembelian -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Faktur Pembelian</label>
								<select name="faktur_beli" id="faktur_beli" data-target="#err-faktur_beli" class="form-control selectpicker" data-live-search="true" data-size="5" required>
									<option value="">Pilih Faktur Pembelian</option>
								</select>
								<p class="mb-0" id="err-faktur_beli"></p>
							</div>
						</div>
						
						<!-- input Tanggal Retur -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Tanggal Retur</label>
								<div class="input-group">
									<div class="input-group-prepend btn-date">
										<span class="input-group-text">
											<i class="bi bi-calendar icon-small"></i>
										</span>
									</div>
									<input type="text" name="tanggal" id="tanggal" data-target="#err-tanggal" class="form-control datepicker" min="<?=$min_date?>" placeholder="dd/mm/yyyy" value="<?= set_value('tanggal') ?>" autocomplete="off" required>
								</div>
								<p class="mb-0" id="err-tanggal"></p>
							</div>
						</div>
						
						<div class="form-row mb-3">
							<!-- input Faktur Retur Beli -->
							<div class="col">
								<label class="label-bold">Faktur Retur Beli</label>
								<input type="text" name="faktur_retur" id="faktur_retur" data-target="#err-faktur_retur" class="form-control" placeholder="Masukkan faktur retur beli" value="<?= set_value('faktur_retur') ?>" oninput="autoUppercase(this)" required unique>
								<p class="mb-0" id="err-faktur_retur"></p>
							</div>
							
							<!-- input Surat Jalan -->
							<div class="col">
								<label class="label-bold">Surat Jalan</label>
								<input type="text" name="surat_jalan" id="surat_jalan" data-target="#err-surat_jalan" class="form-control" placeholder="Masukkan surat jalan" value="<?= set_value('surat_jalan') ?>">
								<p class="mb-0" id="err-surat_jalan"></p>
							</div>
						</div>
						
						<!-- input No. Giro -->
						<div class="form-row mb-3">
							<div class="col">
								<label class="label-bold">No. Giro</label>
								<input type="text" name="no_giro" id="no_giro" data-target="#err-no_giro" class="form-control" placeholder="Masukkan No. Giro" value="<?= set_value('no_giro') ?>">
								<p class="mb-0" id="err-no_giro"></p>
							</div>
							
							<!-- input Jatuh Tempo Giro -->
							<div class="col">
								<label class="label-bold">Jatuh Tempo Giro</label>
								<div class="input-group">
									<div class="input-group-prepend btn-date">
										<span class="input-group-text">
											<i class="bi bi-calendar icon-small"></i>
										</span>
									</div>
									<input type="text" name="jatuh_tempo_giro" id="jatuh_tempo_giro" data-target="#err-jatuh_tempo_giro" class="form-control datepicker" min="<?=$min_date?>" value="<?= set_value('jatuh_tempo_giro') ?>" placeholder="dd/mm/yyyy" autocomplete="off">
								</div>
								<p class="mb-0" id="err-jatuh_tempo_giro"></p>
							</div>
						</div>
					</div>
						
					<div class="col-lg">
						<!-- input Akun Asal -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Akun Asal</label>
								<select name="akun_asal" id="akun_asal" data-target="#err-akun_asal" class="form-control selectpicker" data-live-search="true" data-size="5" required>
									<option value="">Pilih Akun Asal</option>
									<?php foreach($akun_asal as $a) : ?>
									<?php $pilih = (set_value('akun_asal') == $a['kode_akun']) ? 'selected' : '' ?>
									<option value="<?=$a['kode_akun']?>" data-subtext="<?=$a['nama_jenis']?>" <?=$pilih?>><?= $a['kode_akun'].' - '.$a['nama_akun'] ?></option>
									<?php endforeach ?>
								</select>
								<p class="mb-0" id="err-akun_asal"></p>
							</div>
						</div>
						
						<!-- input Akun Lawan -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Akun Lawan</label>
								<select name="akun_lawan" id="akun_lawan" data-target="#err-akun_lawan" class="form-control selectpicker" data-live-search="true" data-size="5" required>
									<option value="">Pilih Akun Lawan</option>
									<?php foreach($akun_lawan as $l) : ?>
									<?php $pilih = (set_value('akun_lawan') == $l['kode_akun']) ? 'selected' : '' ?>
									<option value="<?=$l['kode_akun']?>" data-subtext="<?=$l['nama_jenis']?>" <?=$pilih?>><?= $l['kode_akun'].' - '.$l['nama_akun'] ?></option>
									<?php endforeach ?>
								</select>
								<p class="mb-0" id="err-akun_lawan"></p>
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
											<?php $pilih = ($m['kode_mu'] == 'IDR') ? 'selected' : ''; ?>
											<option <?=$pilih?>><?= $m['kode_mu'] ?></option>
											<?php endforeach; ?>
										</select>
										<p class="mb-0" id="err-mata_uang"></p>
									</div>
									
									<!-- input konversi -->
									<div class="col-sm">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text icon-small bg-white">Nilai Konversi</span>
											</div>
											<input type="text" class="form-control mask_money" name="konversi" id="konversi" data-target="#err-konversi" placeholder="Masukkan nilai konversi" value="<?= set_value('konversi') ?>" readonly required>
										</div>
										<p class="mb-0" id="err-konversi"></p>
									</div>
								</div>
							</div>
						</div>
						
						<!-- input Keterangan -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Keterangan</label>
								<textarea name="keterangan" id="keterangan" data-target="#err-keterangan" class="form-control" placeholder="Masukkan Keterangan"><?= set_value('keterangan') ?></textarea>
								<p class="mb-0" id="err-keterangan"></p>
							</div>
						</div>
					</div>
					<!-- col -->
				</div>
			</div>
		</div>
		<!-- End Detail Transaksi -->
		
		<div class="card mb-4">
			<div class="card-body">
				<!-- Produk -->
				<div class="row mb-2">
					<div class="col">
						<p class="p-header">Produk</p>
					</div>
						
					<div class="col-md-auto">
						<!-- button Add Produk -->
						<button type="button" class="btn btn-sm btn-primary add-btn">
							Add
						</button>
					</div>
				</div>
				
				<!-- Table Produk -->
				<table id="table-produk" class="table table-detail table-bordered table-striped table-responsive-lg mb-0">
					<thead>
						<tr>
							<th>Kode</th>
							<th>Nama Produk</th>
							<th>Qty</th>
							<th>Harga</th>
							<th>Diskon</th>
							<th>Jumlah</th>
							<th>Action</th>
						</tr>
					</thead>
					
					<tbody id="listProduk">
						<!-- List Produk Here -->
					</tbody>
				</table>
			</div>
		</div>
		<!-- End Produk -->
		
		<div class="card-deck mb-4">
			<!-- Diskon & PPN -->
			<div class="card">
				<div class="card-body">
					<label class="label-bold">PPN</label>
					<div class="form-row">
						<!-- Input PPN -->
						<div class="col">
							<input type="text" class="form-control font-small" name="jenis_ppn" id="jenis_ppn" data-target="#err-jenis_ppn" placeholder="Jenis PPN" value="<?= set_value('jenis_ppn') ?>" readonly required>
							<p class="mb-0" id="err-jenis_ppn"></p>
						</div>
						
						<!-- input besar PPN -->
						<div class="col">
							<div class="input-group">
								<input type="number" class="form-control font-small" name="besar_ppn" id="besar_ppn" data-target="#err-besar_ppn" min="1" max="100" placeholder="Besar PPN" value="<?= set_value('besar_ppn') ?>" readonly required>
								<div class="input-group-append">
									<span class="input-group-text font-small">%</span>
								</div>
							</div>
							<p class="mb-0" id="err-besar_ppn"></p>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Total Transaksi -->
			<div class="card">
				<div class="card-body">
					<table class="table table-big border-0 mb-0">
						<tr>
							<td class="h6">Netto</td>
							<td class="h6 text-right" id="totalProduk"></td>
						</tr>
						<tr>
							<td class="h6">PPN</td>
							<td class="h6 text-right" id="totalPPN"></td>
						</tr>
						<tr>
							<td class="h6">Total Keseluruhan</td>
							<td class="h6 text-right" id="totalFin"></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		
		<!-- Buttons -->
		<div class="row">
			<div class="col">
				<button type="submit" name="submit" class="btn btn-success" id="btn-submit">Simpan</button>
				<a href="<?= base_url('retur_pembelian') ?>" class="btn btn-secondary">
					<span class="text">Batal</span>
				</a>
			</div>
		</div>
	</form>
	<!-- content-body -->
</div>
<!-- container-fluid -->



<!-- Modal Add / Update Produk -->
<div class="modal fade" id="modalForm" data-backdrop="static" data-keyboard="false" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content" id="showForm">
		</div>
	</div>
</div>

<!-- Modal Hapus Produk -->
<div class="modal fade modalConfirm" tabindex="-1" aria-labelledby="modalConfirmLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content mx-auto showConfirm" style="width:400px">
		</div>
	</div>
</div>



<script type="text/javascript" src="<?= base_url('asset/js/form/retur_pembelian.js') ?>"></script>
