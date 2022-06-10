<script type="text/javascript" src="<?= base_url() ?>asset/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/datepicker.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/jquery.mask.min.js"></script>


<div class="d-none" id="base_url"><?=base_url()?></div>
<div class="content container-fluid">
	<div class="row content-title">
		<div class="col-sm">
			<div class="mb-2 mb-sm-0"><?=$title?></div>
		</div>
			
		<div class="col-sm-auto">
			<a href="<?= base_url() ?>pembelian" class="close">
				<h1 class="bi bi-x mb-0" style="line-height: .5;"></h1>
			</a>
		</div>
	</div>
	
	<form id="myForm" action="" method="post" novalidate>
		<input type="hidden" name="kode_transaksi" id="kode_transaksi" value="<?= $transaksi['kode_transaksi'] ?>">
		
		<!-- Detail Transaksi -->
		<div class="card mb-4">
			<div class="card-body">
			<div class="row" style="margin-bottom: -.9rem;">
					<div class="col-lg">
						<!-- input Supplier -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Supplier</label>
								<select class="form-control selectpicker" name="supplier" id="supplier" data-target="#err-supplier" data-live-search="true" data-size="5" required>
									<option value="">Pilih Supplier</option>
									<?php foreach($supplier as $p) : ?>
									<?php $pilih = ($transaksi['supplier'] == $p['kode_supplier']) ? 'selected' : '' ?>
									<option value="<?=$p['kode_supplier']?>" <?=$pilih?>><?= $p['kode_supplier'].' - '.$p['nama_supplier'] ?></option>
									<?php endforeach; ?>
								</select>
								<p class="mb-0" id="err-supplier"></p>
							</div>
						</div>
						
						<!-- input Jenis Pembayaran -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Jenis Pembayaran</label>
								<select class="form-control selectpicker" name="jenis_pembayaran" id="jenis_pembayaran" data-target="#err-jenis_pembayaran" required>
									<option value="">Jenis Pembayaran</option>
									<?php foreach($jenis_trx as $j) : ?>
										<?php $pilih = ($transaksi['jenis_pembayaran'] == $j) ? 'selected' : '' ?>
										<option value="<?=$j?>" <?=$pilih?>><?= $j ?></option>
									<?php endforeach ?>
								</select>
								<p class="mb-0" id="err-jenis_pembayaran"></p>
							</div>
						</div>
						
						<div class="form-row mb-3">
							<!-- input Tanggal Transaksi -->
							<div class="col">
								<label class="label-bold">Tanggal Transaksi</label>
								<div class="input-group">
									<div class="input-group-prepend btn-date">
										<span class="input-group-text">
											<i class="bi bi-calendar icon-small"></i>
										</span>
									</div>
									<input type="text" class="form-control datepicker" name="tanggal" id="tanggal" data-target="#err-tanggal" min="<?=$min_date?>" max="<?=$max_date?>" placeholder="Masukkan Tanggal" value="<?= $transaksi['tanggal_transaksi'] ?>" autocomplete="off" required>
								</div>
								<p class="mb-0" id="err-tanggal"></p>
							</div>
							
							<!-- input Tanggal Jatuh Tempo -->
							<div class="col">
								<label class="label-bold">Tanggal Jatuh Tempo</label>
								<div class="input-group">
									<div class="input-group-prepend btn-date">
										<span class="input-group-text">
											<i class="bi bi-calendar icon-small"></i>
										</span>
									</div>
									<input type="text" class="form-control datepicker" name="jatuh_tempo" id="jatuh_tempo" data-target="#err-jatuh_tempo" min="<?=$min_date?>" value="<?= $transaksi['jatuh_tempo'] ?>" placeholder="Masukkan Tanggal" autocomplete="off" required> 
								</div>
								<p class="mb-0" id="err-jatuh_tempo"></p>
							</div>
						</div>
						
						<div class="form-row mb-3">
							<!-- input Faktur Beli -->
							<div class="col">
								<label class="label-bold">Faktur Beli</label>
								<input type="text" class="form-control" name="faktur_beli" id="faktur_beli" data-target="#err-faktur_beli" placeholder="Masukkan faktur beli" value="<?= $transaksi['faktur_beli'] ?>" required>
								<p class="mb-0" id="err-faktur_beli"></p>
							</div>
							
							<!-- input Surat Jalan -->
							<div class="col">
								<label class="label-bold">Surat Jalan</label>
								<input type="text" class="form-control" name="surat_jalan" id="surat_jalan" data-target="#err-surat_jalan" placeholder="Masukkan surat jalan" value="<?= $transaksi['surat_jalan'] ?>">
								<p class="mb-0" id="err-surat_jalan"></p>
							</div>
						</div>
						
						<div class="form-row mb-3">
							<!-- input No. Giro -->
							<div class="col">
								<label class="label-bold">No. Giro</label>
								<input type="text" class="form-control" name="no_giro" id="no_giro" data-target="#err-no_giro" placeholder="Masukkan No. Giro" value="<?= $transaksi['no_giro'] ?>">
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
									<input type="text" class="form-control datepicker" name="jatuh_tempo_giro" id="jatuh_tempo_giro" data-target="#err-jatuh_tempo_giro" min="<?=$min_date?>" value="<?= $transaksi['jatuh_tempo_giro'] ?>" placeholder="Masukkan Tanggal" autocomplete="off">
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
								<select class="form-control selectpicker" name="akun_asal" id="akun_asal" data-target="#err-akun_asal" data-live-search="true" data-size="5" required>
									<option value="">Pilih Akun Asal</option>
									<?php foreach($akun_asal as $k) : ?>
									<?php $pilih = ($transaksi['akun_asal'] == $k['kode_akun']) ? 'selected' : '' ?>
									<option value="<?=$k['kode_akun']?>" data-subtext="<?=$k['nama_jenis']?>" <?=$pilih?>><?= $k['kode_akun'].' - '.$k['nama_akun'] ?></option>
									<?php endforeach; ?>
								</select>
								<p class="mb-0" id="err-akun_asal"></p>
							</div>
						</div>
						
						<!-- input Akun Lawan -->
						<div class="form-group row">
							<div class="col">
								<label class="label-bold">Akun Lawan</label>
								<select class="form-control selectpicker" name="akun_lawan" id="akun_lawan" data-target="#err-akun_lawan" data-live-search="true" data-size="5" required>
									<option value="">Pilih Akun Lawan</option>
									<?php foreach($akun_lawan as $k) : ?>
									<?php $pilih = ($transaksi['akun_lawan'] == $k['kode_akun']) ? 'selected' : '' ?>
									<option value="<?=$k['kode_akun']?>" data-subtext="<?=$k['nama_jenis']?>" <?=$pilih?>><?= $k['kode_akun'].' - '.$k['nama_akun'] ?></option>
									<?php endforeach; ?>
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
											<?php $value = ($m['kode_mu'] == $transaksi['mata_uang']) ? $m['kode_mu'] : ''; ?>
											<?php $pilih = ($m['kode_mu'] == $value) ? 'selected' : ''; ?>
											<option <?=$pilih?>><?= $m['kode_mu'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									
									<!-- input konversi -->
									<div class="col-sm">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text font-small">Nilai Konversi</span>
											</div>
											<input type="text" name="konversi" id="konversi" data-target="#err-konversi" class="form-control mask_money" placeholder="Masukkan nilai konversi" value="<?= $transaksi['konversi'] ?>" numeric="numeric" required>
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
								<textarea class="form-control" name="keterangan" id="keterangan" data-target="#err-keterangan"><?= $transaksi['ket_transaksi'] ?></textarea>
								<p class="mb-0" id="err-keterangan"></p>
							</div>
						</div>
					</div>
					<!-- col -->
				</div>
				<!-- row -->
			</div>
			<!-- card-body -->
		</div>
		<!-- card -->
		
		<!-- Produk -->
		<div class="card mb-4">
			<div class="card-body card_produk">
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
		<!-- End Detail Produk -->
		
		<div class="card-deck mb-4">
			<!-- card Diskon & Uang muka -->
			<div class="card">
				<div class="card-body">
					<label class="label-bold">Diskon Luar</label>
					<div class="form-group row">
						<div class="col">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text mata-uang font-small"></span>
								</div>
								<input type="text" class="form-control mask_money" name="diskon_luar" id="diskon_luar" data-target="#err-diskon_luar" min="0" placeholder="Nilai Diskon" value="<?= $transaksi['diskon_luar'] ?>" numeric="numeric">
							</div>
							<p class="mb-0" id="err-diskon_luar"></p>
						</div>
					</div>
					
					<label class="label-bold">PPN</label>
					<div class="form-row">
						<!-- input jenis PPN -->
						<div class="col">
							<select class="form-control selectpicker" name="jenis_ppn" id="jenis_ppn" data-target="#err-jenis_ppn" required>
								<option value="">Jenis PPN</option>
								<?php foreach($jenis_ppn as $p) : ?>
								<?php $pilih = ($transaksi['jenis_ppn'] == $p) ? 'selected' : ''; ?>
								<option value="<?=$p?>" <?=$pilih?>><?=$p?></option>
								<?php endforeach ?>
							</select>
							<p class="mb-0" id="err-jenis_ppn"></p>
						</div>
						
						<!-- input besar PPN -->
						<div class="col">
							<div class="input-group">
								<input type="text" class="form-control" name="besar_ppn" id="besar_ppn" data-target="#err-besar_ppn" placeholder="Besar PPN" value="<?= $transaksi['besar_ppn'] ?>" required>
								<div class="input-group-append">
									<span class="input-group-text font-small">%</span>
								</div>
							</div>
							<p class="mb-0" id="err-besar_ppn"></p>
						</div>
					</div>
				</div>
			</div>
			<!-- End Card PPN -->
			
			<!-- card Total Keseluruhan -->
			<div class="card">
				<div class="card-body">
					<table class="table table-big border-0 mb-0">
						<tr>
							<td class="h6">Diskon</td>
							<td class="h6 text-right" id="totalDiskon"></td>
						</tr>
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
		
		<div class="row">
			<div class="col">
				<button type="submit" name="submit" class="btn btn-success" id="btn-submit">Simpan</button>
				<a href="<?= base_url('pembelian') ?>" class="btn btn-secondary">
					<span class="text">Batal</span>
				</a>
			</div>
		</div>
	</form>
</div>
<!-- container-fluid -->



<!-- Modal Add / Update Produk -->
<div class="modal fade" id="modalForm" data-backdrop="static" data-keyboard="false" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
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



<script type="text/javascript" src="<?= base_url() ?>asset/js/form/pembelian.js"></script>
<script>
	showProduk();
</script>
