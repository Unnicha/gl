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
		<input type="hidden" name="id_produk" id="id_produk" value="<?= $row['id_produk'] ?>">
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="nama">Nama Biaya</label>
			<div class="col-lg">
				<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Biaya" value="<?=$row['nama']?>">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="produk">Akun Biaya</label>
			<div class="col-lg">
				<select name="produk" id="produk" class="form-control selectpicker" data-live-search="true" data-size="5" required>
					<option value="">--Pilih Akun Biaya--</option>
					<?php foreach($biaya as $k) : ?>
					<?php $pilih = ($row['kode'] == $k['kode_akun']) ? 'selected' : '' ?>
					<option value="<?=$k['kode_akun']?>" data-nama="<?=$k['nama_akun']?>" <?=$pilih?>>
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
					<input type="text" class="form-control mask_money" id="harga" name="harga" placeholder="Masukkan harga" value="<?=$row['harga']?>">
				</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="diskon">Diskon</label>
			<div class="col-lg">
				<input type="text" class="form-control mask_money" id="diskon" name="diskon" placeholder="Masukkan diskon per item" value="<?=$row['diskon']?>">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="total">Total</label>
			<div class="col-lg">
				<input type="text" class="form-control" id="total" name="total" readonly>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="jenis_pph">Jenis PPH</label>
			<div class="col">
				<select name="jenis_pph" id="jenis_pph" class="form-control selectpicker" data-live-search="true" data-size="5" required>
				<option value="">-Pilih Jenis PPH-</option>
					<?php foreach($pph as $k) : ?>
					<?php $pilih = ($row['jenis_pph'] == $k) ? 'selected' : '' ?>
					<option value="<?=$k?>" <?=$pilih?>><?= $k ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="besar_pph">Besar PPH</label>
			<div class="col-lg">
				<div class="input-group">
					<input type="number" min="1" max="100" class="form-control" id="besar_pph" name="besar_pph" placeholder="Masukkan besar PPH" value="<?=$row['besar_pph']?>">
					<div class="input-group-append">
						<span class="input-group-text" style="font-size: .9rem;">%</span>
					</div>
				</div>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-3" for="akun_pph">Akun PPH</label>
			<div class="col">
				<select name="akun_pph" id="akun_pph" class="form-control selectpicker" data-live-search="true" data-size="5" required>
					<option value="">--Pilih Akun--</option>
					<?php foreach($akun_pajak as $k) : ?>
					<?php $pilih = ($row['akun_pph'] == $k['kode_akun']) ? 'selected' : '' ?>
					<option value="<?=$k['kode_akun']?>" <?=$pilih?>>
						<?= $k['kode_akun'].' - '.$k['nama_akun'] ?>
					</option>
					<?php endforeach; ?>
				</select>
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
	hitungTotal();
	
	$('#harga').keyup(function() {
		hitungTotal();
	})
	$('#diskon').keyup(function() {
		hitungTotal();
	})
	
	function submitProduk() {
		var angka		= /[0-9.]/;
		var id_produk	= $('#id_produk').val();
		var produk		= $('#produk').val();
		var nama		= $('#nama').val();
		var harga		= $('#harga').val();
		var jenis_pph	= $('#jenis_pph').val();
		var besar_pph	= $('#besar_pph').val();
		var akun_pph	= $('#akun_pph').val();
		
		if(nama.trim() == '') {
			alert('Nama biaya harus diisi.');
			$('#nama').focus();
			return false;
		} else if(produk.trim() == '') {
			alert('Akun Biaya belum dipilih.');
			$('#produk').focus();
			return false;
		} else if(harga.trim() == '' ) {
			alert('Harga belum diisi.');
			$('#harga').focus();
			return false;
		} else if(!angka.test(harga)) {
			alert('Harga harus berupa angka.');
			$('#harga').focus();
			return false;
		// } else if(jenis_pph.trim() == '') {
		// 	alert('Jenis PPH harus diisi.');
		// 	$('#jenis_pph').focus();
		// 	return false;
		} else if(jenis_pph.trim() != '' && besar_pph.trim() == '') {
			alert('Besar PPH harus diisi.');
			$('#besar_pph').focus();
			return false;
		} else if(jenis_pph.trim() != '' && (besar_pph > 100 || besar_pph < 1)) {
			alert('Besar PPH diantara 1 - 100.');
			$('#besar_pph').focus();
			return false;
		} else if(jenis_pph.trim() != '' && akun_pph.trim() == '') {
			alert('Akun PPH harus dipilih.');
			$('#akun_pph').focus();
			return false;
		} else {
			$.ajax({
				type :'POST',
				url :'<?= base_url() ?>penjualan/updateProduk',
				data : {
					'id_produk'	: id_produk,
					'jenis'		: 'biaya',
					'kode'		: produk,
					'nama'		: nama,
					'qty'		: '1',
					'harga'		: harga,
					'diskon'	: ($('#diskon').val() == '') ? 0 : $('#diskon').val(),
					'total'		: harga,
					'jenis_pph'	: jenis_pph,
					'besar_pph'	: besar_pph,
					'akun_pph'	: akun_pph,
				},
				success:function(result){
					$("#modalForm").modal('hide');
					$('#produk'+id_produk).html(result);
					totalTransaksi();
				}
			});
		}
    }
</script>