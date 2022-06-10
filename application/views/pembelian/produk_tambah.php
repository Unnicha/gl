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
			<label class="col-form-label col-lg-4" for="kode">Produk</label>
			<div class="col-lg">
				<select name="kode" id="kode" class="form-control selectpicker" data-live-search="true" data-size="5">
					<option value="">-Pilih Produk-</option>
					<?php foreach($produk as $k) : ?>
					<?php $pilih = (set_value('kode') == $k['kode_barang']) ? 'selected' : '' ?>
					<option value="<?=$k['kode_barang']?>" <?=$pilih?>>
						<?= $k['kode_barang'].' - '.$k['nama_barang'] ?>
					</option>
					<?php endforeach; ?>
				</select>
				<small class="text-danger" id="err-kode"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4" for="qty">Quantity</label>
			<div class="col-lg">
				<input type="number" min="1" class="form-control" id="qty" name="qty" placeholder="Masukkan quantity">
				<small class="text-danger" id="err-qty"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4" for="harga">Harga Satuan</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text font-small"><?=$mata_uang?></span>
					</div>
					<input type="text" class="form-control mask_money" id="harga" name="harga">
				</div>
				<small class="text-danger" id="err-harga"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4" for="diskon">Diskon per Item</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text font-small"><?=$mata_uang?></span>
					</div>
					<input type="text" class="form-control mask_money" id="diskon" name="diskon">
				</div>
				<small class="text-danger" id="err-diskon"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4" for="total">Total</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text font-small"><?=$mata_uang?></span>
					</div>
					<input type="text" class="form-control" id="total" name="total" readonly>
				</div>
			</div>
		</div>
		
		<div class="row text-right">
			<div class="col">
				<button type="button" class="btn btn-primary submitBtn" onclick="submitProduk()">Simpan</button>
			</div>
		</div>
	</form>
</div>



<div class="d-none" id="base_url"><?= base_url() ?></div>
<div class="d-none" id="formAction">add</div>
<script type="text/javascript" src="<?= base_url('asset/js/form/pembelian_produk.js') ?>"></script>
