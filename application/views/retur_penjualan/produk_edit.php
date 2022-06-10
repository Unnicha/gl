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
		<input type="hidden" name="id_row" id="id_row" value="<?= $produk['id_row'] ?>">
		<input type="hidden" name="id_produk" id="id_produk" value="<?= $produk['id_produk'] ?>">
		<input type="hidden" name="kode" id="kode" value="<?= $produk['kode'] ?>">
		<input type="hidden" name="nama" id="nama" value="<?= $produk['nama'] ?>">
		
		<div class="form-group row">
			<div class="col-lg">
				<label class="label-bold">Produk</label>
				<input type="text" class="form-control" id="view_produk" name="view_produk" value="<?=$produk['kode'].' - '.$produk['nama'] ?>" readonly>
				<small class="text-danger" id="err-produk"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<div class="col-lg">
				<label class="label-bold">Quantity</label>
				<input type="number" min="1" max="<?= $produk['max_produk'] ?>" class="form-control" id="qty" name="qty" placeholder="Masukkan quantity produk" value="<?=$produk['qty']?>">
				<small class="text-danger" id="err-qty">Sisa stock <?= $produk['max_produk'] ?></small>
			</div>
		</div>
		
		<div class="form-group row">
			<div class="col-lg">
				<label class="label-bold">Harga Satuan</label>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text font-small bg-white"><?=$mata_uang?></span>
					</div>
					<input type="text" class="form-control mask_money" id="harga" name="harga" value="<?=$produk['harga']?>" readonly>
				</div>
				<small class="text-danger" id="err-harga"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<div class="col-lg">
				<label class="label-bold">Diskon</label>
				<input type="text" class="form-control mask_money" id="diskon" name="diskon" value="<?=$produk['diskon']?>" readonly>
				<small class="text-danger" id="err-diskon"></small>
			</div>
		</div>
		
		<div class="form-group row">
			<div class="col-lg">
				<label class="label-bold">Total</label>
				<input type="text" class="form-control" id="total" name="total" readonly>
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
<script type="text/javascript" src="<?= base_url('asset/js/form/retur_penjualan_produk.js') ?>"></script>