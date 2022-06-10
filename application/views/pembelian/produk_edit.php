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
	<form action="" method="post">
		<input type="hidden" name="id_row" id="id_row" value="<?=$produk['id_row']?>">
		<input type="hidden" name="kode" id="kode" value="<?=$produk['kode']?>">
		<input type="hidden" name="nama" id="nama" value="<?=$produk['nama']?>">
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4" for="produk">Produk</label>
			<div class="col-lg">
				<input type="text" class="form-control" id="produk" name="produk" value="<?=$produk['kode'].' - '.$produk['nama']?>" readonly>
				<small class="text-danger" id="err-produk"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4" for="qty">Quantity</label>
			<div class="col-lg">
				<input type="number" min="1" class="form-control" id="qty" name="qty" placeholder="Masukkan quantity produk" value="<?=$produk['qty']?>">
				<small class="text-danger" id="err-qty"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4" for="harga">Harga Satuan</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text font-small"><?=$produk['mata_uang']?></span>
					</div>
					<input type="text" class="form-control mask_money" id="harga" name="harga" value="<?=$produk['harga']?>">
				</div>
				<small class="text-danger" id="err-harga"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4" for="diskon">Diskon</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text font-small"><?=$produk['mata_uang']?></span>
					</div>
					<input type="text" class="form-control mask_money" id="diskon" name="diskon" value="<?=$produk['diskon']?>">
				</div>
				<small class="text-danger" id="err-diskon"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4" for="total">Total</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text font-small"><?=$produk['mata_uang']?></span>
					</div>
					<input type="text" class="form-control" id="total" name="total" readonly>
				</div>
				<small class="text-danger" id="err-total"></small>
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
<div class="d-none" id="formAction">edit</div>
<script type="text/javascript" src="<?= base_url('asset/js/form/pembelian_produk.js') ?>"></script>
