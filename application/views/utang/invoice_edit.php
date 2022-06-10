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
				<input type="text" class="form-control" id="invoice" name="invoice" value="<?= $pembelian['faktur_beli'] ?>" readonly>
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
					<input type="text" class="form-control" id="tgl_beli" name="tgl_beli" value="<?= $pembelian['tanggal_transaksi'] ?>" readonly>
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
					<input type="text" class="form-control" id="jatuh_tempo" name="jatuh_tempo" value="<?= $pembelian['jatuh_tempo'] ?>" readonly>
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
					<input type="text" class="form-control mask_money" id="tagihan" name="tagihan" value="<?= $pembelian['total_tagihan'] ?>" readonly>
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
					<input type="text" class="form-control mask_money" id="sisa" name="sisa" value="<?= $pembelian['sisa_tagihan'] ?>" readonly>
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
					<input type="text" class="form-control mask_money" id="bayar" name="bayar" value="<?= $pembelian['jumlah_dibayar'] ?>">
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
<div class="d-none" id="formAction">updateUtang</div>
<script type="text/javascript" src="<?= base_url('asset/js/form/utang_bayar.js') ?>"></script>
