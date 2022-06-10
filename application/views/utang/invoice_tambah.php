<!-- Modal Header -->
<div class="modal-header">
	<h5 class="modal-title"><?=$title?></h5>
	
	<button type="button" class="close" data-dismiss="modal">
		<span aria-hidden="true">&times;</span>
		<span class="sr-only">Close</span>
	</button>
</div>

<!-- Modal Body -->
<div class="modal-body">
	<form action="" method="post" name="formProduk">
		<input type="hidden" name="id_row" id="id_row" value="<?=$id_row?>">
		<input type="hidden" name="nama" id="nama">
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4">Invoice</label>
			<div class="col-lg">
				<select name="invoice" id="invoice" class="form-control selectpicker" data-live-search="true" data-size="5">
					<option value="">-Pilih Invoice-</option>
					<?php foreach($pembelian as $p) : ?>
					<?php $pilih = (set_value('invoice') == $p['faktur_beli']) ? 'selected' : '' ?>
					<option value="<?=$p['faktur_beli']?>" data-subtext="<?=$p['tanggal_transaksi']?>" <?=$pilih?>><?= $p['faktur_beli'] ?></option>
					<?php endforeach; ?>
				</select>
				<small class="text-danger" id="err-invoice"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4">Tanggal Transaksi</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text bg-white">
							<i class="bi bi-calendar icon-small"></i>
						</span>
					</div>
					<input type="text" class="form-control" id="tgl_beli" name="tgl_beli" placeholder="dd/mm/yyyy" readonly>
				</div>
				<small class="text-danger" id="err-tgl_beli"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4">Jatuh Tempo</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text bg-white">
							<i class="bi bi-calendar icon-small"></i>
						</span>
					</div>
					<input type="text" class="form-control" id="jatuh_tempo" name="jatuh_tempo" placeholder="dd/mm/yyyy" readonly>
				</div>
				<small class="text-danger" id="err-jatuh_tempo"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4">Total Tagihan</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text font-small bg-white"><?=$mata_uang?></span>
					</div>
					<input type="text" class="form-control mask_money" id="tagihan" name="tagihan" readonly>
				</div>
				<small class="text-danger" id="err-tagihan"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4">Sisa Tagihan</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text font-small bg-white"><?=$mata_uang?></span>
					</div>
					<input type="text" class="form-control mask_money" id="sisa" name="sisa" readonly>
				</div>
				<small class="text-danger" id="err-sisa"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4">Jumlah Dibayar</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text font-small bg-white"><?=$mata_uang?></span>
					</div>
					<input type="text" class="form-control mask_money" id="bayar" name="bayar">
				</div>
				<small class="text-danger" id="err-bayar"></small>
			</div>
		</div>
		
		<div class="row text-right">
			<div class="col">
				<button type="button" class="btn btn-primary submitBtn" onclick="submitInvoice()">Simpan</button>
			</div>
		</div>
	</form>
</div>



<div class="d-none" id="base_url"><?= base_url() ?></div>
<div class="d-none" id="formAction">saveUtang</div>
<script type="text/javascript" src="<?= base_url('asset/js/form/utang_bayar.js') ?>"></script>
