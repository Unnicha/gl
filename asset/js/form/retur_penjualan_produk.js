var base_url = $('#base_url').html()
$('.selectpicker').selectpicker();

$('#qty').keyup(function() { hitungTotal() }).change(function() { hitungTotal() })

$('#id_produk').change(function() {
	$("#kode").val('');
	$("#nama").val('');
	$("#qty").attr('max', '');
	$("#harga").val('');
	$("#err-qty").html('');
	
	detailProduk()
})

function maskInput() {
	$('.mask_money').unmask().mask('#.##0,00', {
		reverse: true,
		placeholder: '0,00',
	})
}
maskInput()

function detailProduk() {
	$.ajax({
		type	: 'post',
		url		: base_url + 'retur_penjualan/detailProduk',
		data	: {
			'id_produk' : $('#id_produk').val(),
		},
		success : function(data) {
			if( data != '' ) {
				var result = jQuery.parseJSON(data);
				$("#kode").val(result.kode_produk);
				$("#nama").val(result.nama_produk);
				$("#qty").attr('max', result.qty_produk);
				$("#harga").val(result.harga_produk);
				$("#diskon").val(result.diskon_produk);
				$("#err-qty").html('Jumlah stock '+result.qty_produk);
				
				maskInput()
			}
		},
	})
}

function hitungTotal() {
	var qty		= $('#qty').val()
	var harga	= $('#harga').cleanVal()
	var diskon	= $('#diskon').cleanVal()
	var hitung	= qty * (harga - diskon);
	$('#total').val(hitung).mask('#.##0,00', {reverse: true}).trigger('input');
}
hitungTotal();

function submitProduk() {
	var action		= $('#formAction').html()
	var id_row		= $('#id_row').val();
	var id_produk	= $('#id_produk').val();
	var qty			= $('#qty').val();
	var max			= $('#qty').attr('max');
	var harga		= $('#harga').val();
	var diskon		= $('#diskon').val();
	
	$('#err-produk').html('');
	$('#err-qty').html('');
	$('#err-harga').html('');
	$('#err-diskon').html('');
	
	if(id_produk.trim() == '') {
		$('#err-produk').html('Barang belum dipilih.');
		$('#id_produk').focus()
		return false;
	} else if(qty.trim() == '' ) {
		$('#err-qty').html('Quantity belum diisi.');
		$('#qty').focus();
		return false;
	} else if(qty > (max-0)) {
		$('#err-qty').html('Quantity maksimal '+max+'.');
		$('#qty').focus();
		return false;
	} else if(harga.trim() == '' ) {
		$('#err-harga').html('Harga belum diisi.');
		$('#harga').focus();
		return false;
	} else if(diskon.trim() == '' ) {
		$('#err-diskon').html('Diskon belum diisi.');
		$('#diskon').focus();
		return false;
	} else {
		var link = (action == 'add') ? base_url+'retur_penjualan/saveProduk' : base_url+'retur_penjualan/updateProduk';
		$.ajax({
			type	: 'POST',
			url		: link,
			data	: {
				'id_row'	: id_row,
				'id_produk'	: id_produk,
				'kode'		: $('#kode').val(),
				'nama'		: $('#nama').val(),
				'qty'		: qty,
				'harga'		: harga,
				'diskon'	: (diskon != '') ? diskon : '0,00',
				'total'		: $('#total').val(),
			},
			success	: function(result) {
				$("#modalForm").modal('hide');
				if( action == 'add' ) {
					$('#listProduk').append(result);
				} else {
					$('#produkRow'+id_row).html(result);
				}
			},
			complete : function() {
				totalProduk();
				totalTransaksi();
			}
		});
	}
}
