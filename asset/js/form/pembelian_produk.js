var base_url = $('#base_url').html()
$('.selectpicker').selectpicker();

$('.mask_money').mask('#.##0,00', {
	reverse: true,
	placeholder: '0,00',
})

$('#kode').change(function() {
	$("#nama").val('');
	$("#qty").attr('max', '');
	$("#err-qty").html('');
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
	var action		= $('#formAction').html()
	var id			= $('#id_row').val();
	var kode		= $('#kode').val();
	var qty			= $('#qty').val();
	var harga		= $('#harga').val();
	var diskon		= $('#diskon').val();
	
	$('#err-kode').html('');
	$('#err-qty').html('');
	$('#err-harga').html('');
	$('#err-harga').html('');
	$('#err-diskon').html('');
	
	if (kode.trim() == '') {
		$('#err-kode').html('Barang belum dipilih');
		$('#kode').focus()
		return false;
	} else if (qty.trim() == '' ) {
		$('#err-qty').html('Quantity belum diisi');
		$('#qty').focus();
		return false;
	} else if (harga.trim() == '' ) {
		$('#err-harga').html('Harga belum diisi');
		$('#harga').focus();
		return false;
	} else if (harga.length < 3) {
		$('#err-harga').html('Masukkan minimal 3 angka');
		$('#harga').focus();
		return false;
	} else if (diskon.trim() != '' && diskon.length < 3) {
		$('#err-diskon').html('Masukkan minimal 3 angka');
		$('#diskon').focus();
		return false;
	} else {
		$.ajax({
			type	: 'POST',
			url		: (action == 'add') ? base_url+'pembelian/saveProduk' : base_url+'pembelian/updateProduk',
			data	: {
				'id_row'	: id,
				'kode'		: kode,
				'qty'		: qty,
				'harga'		: harga,
				'diskon'	: (diskon != '') ? diskon : '0,00',
				'total'		: $('#total').val(),
			},
			success	: function(result) {
				$("#modalForm").modal('hide');
				if (action == 'add') {
					$('#listProduk').append(result);
				} else {
					$('#produkRow'+id).html(result);
				}
			},
			complete : function() {
				totalProduk();
				totalTransaksi();
			}
		});
	}
}
