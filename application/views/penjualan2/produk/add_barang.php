<!-- Modal Header -->
<div class="modal-header">
	<h4 class="modal-title"><?=$title?></h4>
	
	<button type="button" class="close" data-dismiss="modal">
		<span aria-hidden="true">&times;</span>
		<span class="sr-only">Close</span>
	</button>
</div>

<!-- Modal Body -->
<div class="modal-body">
	<form action="" method="post" name="formProduk">
		<input type="hidden" name="id_produk" id="id_produk" value="<?= $id_produk ?>">
		<input type="hidden" name="nama" id="nama">
		<input type="hidden" name="jenis" id="jenis" value="barang">
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="produk">Barang</label>
			<div class="col-lg">
				<select name="produk" id="produk" class="form-control selectpicker" data-live-search="true" data-size="5">
					<option value="">--Pilih Barang--</option>
					<?php foreach($produk as $k) : ?>
					<?php $pilih = (set_value('produk') == $k['kode_barang']) ? 'selected' : '' ?>
					<option value="<?=$k['kode_barang']?>" <?=$pilih?>>
						<?= $k['kode_barang'].' - '.$k['nama_barang'] ?>
					</option>
					<?php endforeach; ?>
				</select>
				<small class="text-danger" id="err-produk"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="qty">Quantity</label>
			<div class="col-lg">
				<input type="number" min="1" class="form-control" id="qty" name="qty" placeholder="Masukkan quantity barang">
				<small class="text-danger" id="err-qty"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="harga">Harga Satuan</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text font-small"><?=$mata_uang?></span>
					</div>
					<input type="text" class="form-control mask_money" id="harga" name="harga" placeholder="Masukkan harga barang">
				</div>
				<small class="text-danger" id="err-harga"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="diskon">Diskon</label>
			<div class="col-lg">
				<input type="text" class="form-control mask_money" id="diskon" name="diskon" placeholder="Masukkan diskon per item">
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
<script type="text/javascript" src="<?= base_url('asset/js/form/penjualan_barang.js') ?>"></script>
