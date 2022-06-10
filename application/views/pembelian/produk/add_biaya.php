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
		<input type="hidden" name="id_produk" id="id_produk" value="<?= $id_produk ?>">
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="nama">Nama Biaya</label>
			<div class="col-lg">
				<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Biaya">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="produk">Akun Biaya</label>
			<div class="col-lg">
				<select name="produk" id="produk" class="form-control selectpicker" data-live-search="true" data-size="5">
					<option value="">--Pilih Akun Biaya--</option>
					<?php foreach($biaya as $k) : ?>
					<option value="<?=$k['kode_akun']?>" data-nama="<?=$k['nama_akun']?>">
						<?= $k['kode_akun'].' - '.$k['nama_akun'] ?>
					</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="harga">Harga</label>
			<div class="col-lg">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" style="font-size: .9rem;"><?=$mata_uang?></span>
					</div>
					<input type="text" class="form-control mask_money" id="harga" name="harga">
				</div>
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



<script>
	$('.selectpicker').selectpicker();
	
	$('.mask_money').mask('#.##0,00', {
		reverse: true,
		placeholder: '0,00',
	})
	
	function hitungTotal() {
		var harga	= $('#harga').cleanVal()
		var diskon	= ( $('#diskon').val().trim() == '' ) ? 0 : $('#diskon').cleanVal()
		var hitung	= harga - diskon;
		$('#total').val(hitung).mask('#.##0,00', {reverse: true}).trigger('input');
	}
	
	$('#harga').keyup(function() {
		hitungTotal();
	})
	$('#diskon').keyup(function() {
		hitungTotal();
	})
	
	function submitProduk() {
		var angka		= /[0-9.]/;
		var produk		= $('#produk').val();
		var nama		= $('#nama').val();
		var harga		= $('#harga').val();
		
		if(nama.trim() == ''){
			alert('Nama biaya harus diisi.');
			$('#nama').focus();
			return false;
		} else if(produk.trim() == ''){
			alert('Akun Biaya harus dipilih.');
			$('#produk').focus();
			return false;
		} else if(harga.trim() == '' ){
			alert('Harga harus diisi.');
			$('#harga').focus();
			return false;
		} else if(!angka.test(harga)){
			alert('Harga harus berupa angka.');
			$('#harga').focus();
			return false;
		} else {
			$.ajax({
				type :'POST',
				url :'<?= base_url() ?>penjualan/saveProduk',
				data : {
					'id_produk'	: $('#id_produk').val(),
					'jenis'		: 'biaya',
					'kode'		: produk,
					'nama'		: nama,
					'qty'		: '1',
					'harga'		: harga,
					'diskon'	: ($('#diskon').val() == '') ? 0 : $('#diskon').val(),
					'total'		: harga,
				},
				success:function(result){
					$("#modalForm").modal('hide');
					$('#listProduk').append(result);
					totalTransaksi();
				}
			});
		}
    }
</script>