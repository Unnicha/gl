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
		<input type="hidden" name="id_row" id="id_row" value="<?= $id_row ?>">
		<input type="hidden" name="kode" id="kode">
		<input type="hidden" name="nama" id="nama">
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="id_produk">Produk</label>
			<div class="col-lg">
				<select name="id_produk" id="id_produk" class="form-control selectpicker" data-live-search="true" data-size="5">
					<option value="">--Pilih Produk--</option>
					<?php foreach($produk as $k) : ?>
					<?php $pilih = (set_value('id_produk') == $k['id_produk']) ? 'selected' : '' ?>
					<option value="<?=$k['id_produk']?>" <?=$pilih?>>
						<?= $k['kode_produk'].' - '.$k['nama_produk'] ?>
					</option>
					<?php endforeach; ?>
				</select>
				<small class="text-danger" id="err-produk"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="qty">Quantity</label>
			<div class="col-lg">
				<input type="number" min="1" class="form-control" id="qty" name="qty" placeholder="Masukkan quantity produk">
				<small class="text-danger" id="err-qty"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="harga">Harga Satuan</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text font-small" style="background-color: white;"><?=$mata_uang?></span>
					</div>
					<input type="text" class="form-control mask_money" id="harga" name="harga" readonly>
				</div>
				<small class="text-danger" id="err-harga"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="diskon">Diskon</label>
			<div class="col-lg">
				<input type="text" class="form-control mask_money" id="diskon" name="diskon" readonly>
				<small class="text-danger" id="err-diskon"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="total">Total</label>
			<div class="col-lg">
				<input type="text" class="form-control" id="total" name="total" readonly>
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
<script type="text/javascript" src="<?= base_url('asset/js/form/retur_penjualan_produk.js') ?>"></script>
