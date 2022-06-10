var base_url = $('#base_url').html()
$('.selectpicker').selectpicker();

$('.mask_money').mask('#.##0,00', {
	reverse: true,
	placeholder: '0,00',
})

$('#produk').change(function() {
	$.ajax({
		type	: 'post',
		url		: base_url + 'penjualan/maxProduk',
		data	: {
			'kode' : $(this).val(),
		},
		success : function(data) {
			if( data != 'null' ) {
				var result = jQuery.parseJSON(data);
				$("#nama").val(result.nama);
				$("#qty").attr('max', result.stock);
				$("#err-qty").html('Sisa stock '+result.stock);
			}
		},
	})
})

function hitungTotal() {
	var qty		= $('#qty').val()
	var harga	= $('#harga').cleanVal()
	var diskon	= $('#diskon').cleanVal()
	var hitung	= qty * (harga - diskon);
	$('#total').val(hitung).mask('#.##0,00', {reverse: true}).trigger('input');
}
hitungTotal();

$('#qty').keyup(function() { hitungTotal(); })
		.change(function() { hitungTotal(); })
$('#harga').keyup(function() { hitungTotal(); })
$('#diskon').keyup(function() { hitungTotal(); })

function submitProduk() {
	var angka		= /[0-9.]/;
	var produk		= $('#produk').val();
	var nama		= $('#nama').val();
	var qty			= $('#qty').val();
	var max			= $('#qty').attr('max');
	var harga		= $('#harga').val();
	var diskon		= $('#diskon').val();
	var total		= $('#total').val();
	// var jenis_pph	= $('#jenis_pph').val();
	// var besar_pph	= $('#besar_pph').val();
	// var akun_pph	= $('#akun_pph').val();
	
	$('#err-produk').html('');
	$('#err-qty').html('');
	$('#err-qty').html('');
	$('#err-harga').html('');
	$('#err-harga').html('');
	$('#err-diskon').html('');
	// $('#err-besarpph').html('');
	// $('#err-akunpph').html('');
	
	if(produk.trim() == ''){
		$('#err-produk').html('Barang belum dipilih.');
		$('#produk').focus()
		return false;
	} else if(qty.trim() == '' ){
		$('#err-qty').html('Quantity belum diisi.');
		$('#qty').focus();
		return false;
	} else if(qty > (max-0)){
		$('#err-qty').html('Quantity maksimal '+max+'.');
		$('#qty').focus();
		return false;
	} else if(harga.trim() == '' ){
		$('#err-harga').html('Harga belum diisi.');
		$('#harga').focus();
		return false;
	} else if(!angka.test(harga)){
		$('#err-harga').html('Harga harus berupa angka.');
		$('#harga').focus();
		return false;
	} else if(diskon.trim() != '' && !angka.test(diskon)){
		$('#err-diskon').html('Diskon harus berupa angka.');
		$('#diskon').focus();
		return false;
	// } else if(jenis_pph.trim() != '' && besar_pph.trim() == ''){
	// 	$('#err-besarpph').html('Besar PPH harus diisi.');
	// 	$('#besar_pph').focus();
	// } else if(jenis_pph.trim() != '' && akun_pph.trim() == ''){
	// 	$('#err-akunpph').html('Akun PPH harus dipilih.');
	// 	$('#akun_pph').focus();
	// 	return false;
	} else{
		$.ajax({
			type	: 'POST',
			url		: base_url + 'penjualan/saveProduk',
			data	: {
				'id_produk'	: $('#id_produk').val(),
				'jenis'		: 'barang',
				'kode'		: produk,
				'nama'		: nama,
				'qty'		: qty,
				'harga'		: harga,
				'diskon'	: (diskon) ? diskon : 0,
				'total'		: total,
				// 'jenis_pph'	: jenis_pph,
				// 'besar_pph'	: besar_pph,
				// 'akun_pph'	: akun_pph,
			},
			success: function(result){
				$("#modalForm").modal('hide');
				$('#listProduk').append(result);
				totalTransaksi()
			}
		});
	}
}