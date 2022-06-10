var base_url = $('#base_url').html()
$('.selectpicker').selectpicker();

function maskInput() {
	$('.mask_money').unmask().mask('#.##0,00', {
		reverse: true,
		placeholder: '0,00',
	})
}
maskInput()

$('#invoice').change(function () {
	getInvoice()
})

function getInvoice() {
	$.ajax({
		type	: 'POST',
		url		: base_url+'piutang/getInvoice',
		data	: {
			'invoice'	: $('#invoice').val(),
		},
		success	: function(result) {
			if (result) {
				var data	= JSON.parse(result);
				$("#tgl_jual").val(data.tanggal_transaksi);
				$("#jatuh_tempo").val(data.jatuh_tempo);
				$("#tagihan").val(data.total_tagihan);
				$("#sisa").val(data.sisa_tagihan);
			}
		},
		complete : function() {
			maskMoney()
		}
	});
}

function submitInvoice() {
	var id_row		= $('#id_row').val();
	var invoice		= $('#invoice').val().trim();
	var bayar		= $('#bayar').val().trim();
	var val_bayar	= $('#bayar').cleanVal();
	var val_sisa	= $('#sisa').cleanVal();
	var action		= $('#formAction').html();
	
	$('#err-invoice').html('');
	$('#err-bayar').html('');
	
	if (invoice == '') {
		$('#err-invoice').html('Belum diisi');
		$('#invoice').focus();
		return false;
	} else if (val_bayar == '' || val_bayar == 0) {
		$('#err-bayar').html('Belum diisi');
		$('#bayar').focus();
		return false;
	} else if (val_bayar - val_sisa > 0) {
		$('#err-bayar').html('Jumlah melebihi sisa tagihan');
		$('#bayar').focus();
		return false;
	} else {
		$.ajax({
			type	: 'POST',
			url		: base_url+'piutang/savePiutang',
			data	: {
				'id_row'		: id_row,
				'invoice'		: invoice,
				'tanggal_jual'	: $('#tgl_jual').val(),
				'jatuh_tempo'	: $('#jatuh_tempo').val(),
				'total_tagihan'	: $('#tagihan').val(),
				'sisa_tagihan'	: $('#sisa').val(),
				'jumlah_bayar'	: bayar,
				'action'		: action,
			},
			success	: function(result) {
				$("#modalForm").modal('hide');
				if (action == 'savePiutang') {
					$('#listPiutang').append(result);
				} else {
					$('#piutangRow'+id_row).html(result);
				}
			},
			complete : function() {
				// totalPiutang()
				totalTransaksi()
			}
		});
	}
}
