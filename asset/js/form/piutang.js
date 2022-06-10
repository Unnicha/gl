var base_url = $('#base_url').html();
$('.selectpicker').selectpicker();

$('.datepicker').each(function() {
	$(this).datepicker({
		format		: 'dd/mm/yyyy',
		autoHide	: true,
		startDate	: $(this).attr('min'),
		endDate		: $(this).attr('max'),
		trigger		: $(this).parent(),
	})
})

function maskMoney() {
	$('.mask_money').unmask().mask('#.##0,00', {
		reverse: true,
		placeholder: '0,00',
	})
}
maskMoney()

function maskDate() {
	$('.datepicker').unmask().mask('00/00/0000', {
		placeholder: 'dd/mm/yyyy',
	})
}
maskDate()

if ($('#kode_transaksi').val() != undefined) {
	showPiutang()
}

$('#tanggal').change(function() {
	getKonversi()
})

function getPenjualan() {
	var penjualan = $('#penjualan').val();
	if (penjualan != '' && penjualan != undefined) {
		$.ajax({
			type	: 'post',
			url		: base_url+'piutang/getByPenjualan',
			data	: {
				'penjualan' : penjualan,
			},
			success	: function(data) {
				if (data != '') {
					var result	= JSON.parse(data);
					$('#pelanggan').html(result.pelanggan_list)
						.selectpicker('refresh').selectpicker('val', result.pelanggan)
					$('#akun_asal').html(result.akun_list)
						.selectpicker('refresh').selectpicker('val', '')
					$('#mata_uang').html(result.uang_list)
						.selectpicker('refresh').selectpicker('val', result.uang)
					maskMoney()
				}
			},
			complete : function() {
				// if ($('#penjualan').val() == undefined)
				getKonversi()
				showPiutang()
			}
		});
	}
}
getPenjualan()

function getKonversi() {
	$.ajax({
		type	: 'post',
		url		: base_url+'piutang/getKonversi',
		data	: {
			'tanggal' 	: $('#tanggal').val(),
			'mata_uang' : $('#mata_uang').val(),
		},
		success	: function(result) {
			$("#konversi").val(result)
			maskMoney()
		},
	});
}

// tampil piutang
function showPiutang() {
	$.ajax({
		type	: 'post',
		url		: base_url+'piutang/showPiutang',
		data	: {
			'kode_pembayaran' : $('#kode_transaksi').val(),
			'penjualan' : $('#penjualan').val(),
		},
		success : function(data) {
			$('#listPiutang').html(data);
		},
		complete : function() {
			totalTransaksi()
			maskMoney()
		},
	})
}

function addPiutang() {
	var selected = $("input[name='faktur_jual[]']").map(function() { return $(this).val() }).get()
	$.ajax({
		type	: 'post',
		url		: base_url+'piutang/addPiutang',
		data	: {
			'kode_bayar'	: $('#kode_transaksi').val(),
			'pelanggan'		: $('#pelanggan').val(),
			'mata_uang'		: $('#mata_uang').val(),
			'id_last'		: $("body tbody").children("tr.piutang-row").length,
			'total_bayar'	: $('#total_bayar').val(),
			'selected'		: selected,
		},
		success : function(result) {
			$("#modalForm").modal('show');
			$("#showForm").html(result);
		},
	})
}

function editPiutang(id_row) {
	$.ajax({
		type	: 'post',
		url		: base_url+'piutang/editPiutang',
		data	: {
			mata_uang	: $('#mata_uang').val(),
			kode_bayar	: $('#kode_transaksi').val(),
			id_row		: id_row,
			invoice		: $('#faktur_jual'+id_row).val(),
			bayar		: $('#jumlah_dibayar'+id_row).val(),
		},
		success : function(result) {
			$("#modalForm").modal('show');
			$("#showForm").html(result);
		},
	})
}

// tambah piutang
$('.add-btn').click(function() {
	addPiutang();
})
	
// edit piutang
$('tbody').on('click', '.btn-edit', function() {
	editPiutang($(this).data('id'));
})

// konfirmasi hapus piutang
$('tbody').on('click', '.btn-delete', function() {
	$.ajax({
		type	: 'post',
		url		: base_url+'piutang/deletePiutang',
		data	: { 'id' : $(this).data('id'), },
		success	: function(data) {
			$(".modalConfirm").modal('show');
			$(".showConfirm").html(data);
		},
	})
})

// fix hapus piutang
function fixDelete(id_row) {
	$(".modalConfirm").modal('hide');
	$('tbody').children('tr#piutangRow'+id_row).remove();
	totalTransaksi()
}

// untuk menghitung jumlah harga piutang
function countPiutang() {
	var hitung	= [];
	var last_id	= $('.piutang-row:last-child').data('id');
	
	if (last_id == undefined) {
		return last_id
	} else {
		var total_tagihan	= 0;
		var total_dibayar	= 0;
		var total_sisa	= 0;
		for (var i=0; i<=last_id; i++) {
			var jumlah_tagihan	= isNaN($('#jumlah_tagihan'+i).val()) ? 0 : ($('#jumlah_tagihan'+i).val() * 1);
			var jumlah_dibayar	= isNaN($('#jumlah_dibayar'+i).val()) ? 0 : ($('#jumlah_dibayar'+i).val() * 1);
			var jumlah_sisa		= isNaN($('#jumlah_sisa'+i).val()) ? 0 : ($('#jumlah_sisa'+i).val() * 1);
			total_tagihan	= total_tagihan + jumlah_tagihan;
			total_dibayar	= total_dibayar + jumlah_dibayar;
			total_sisa		= total_sisa + jumlah_sisa;
		}
		hitung['total_tagihan']	= total_tagihan;
		hitung['total_sisa']	= total_sisa;
		hitung['total_dibayar']	= total_dibayar;
		return hitung
	}
}
	
// fungsi untuk hitung dan tampil total transaksi
function totalTransaksi() {
	var hitung = countPiutang();
	console.log('total transaksi');
	
	if (hitung != undefined) {
		$('.mata-uang').html($('#mata_uang').val());
		$('#total_tagihan').val(hitung['total_sisa']);
		$('#total_bayar').val(hitung['total_dibayar']);
		$('#totalTagihan').html(hitung['total_sisa'].toLocaleString('id-ID', {minimumFractionDigits:'2', maximumFractionDigits:'2'}));
		$('#totalBayar').html(hitung['total_dibayar'].toLocaleString('id-ID', {minimumFractionDigits:'2', maximumFractionDigits:'2'}));
	} else {
		$('#total_tagihan').val('1.100');
		$('#total_bayar').val('1.100');
		$('#totalTagihan').html('0,00');
		$('#totalBayar').html('0,00');
	}
}

const form = document.getElementById('myForm');
form.addEventListener('submit', (e) => {
	var valid = true;
	$('.error-msg').remove();
	$('.form-control').removeClass('has-error');
	
	if (validPiutang() == false) { valid = false; }
	if (validInput() == false) { valid = false; }
	
	if(valid == false) {
		e.preventDefault();
		$('.has-error:first').focus();
	}
})

function validPiutang() {
	if ($('.piutang-row').data('id') == undefined) {
		var msg = '<small class="error-msg text-danger">Piutang harus diisi</small>';
		$('#table-piutang').after(msg).addClass('has-error');
		return false;
	} else 
		return true;
}

function validInput() {
	var valid = true;
	$('#myForm .form-control').each(function() {
		var pelanggan		= $('#pelanggan').val().trim();
		var tanggal			= $('#tanggal').val().trim();
		var min_date		= $('#tanggal').attr('min');
		var akun_asal		= $('#akun_asal').val().trim();
		var mata_uang		= $('#mata_uang').val().trim();
		var konversi		= $('#konversi').val().trim();
		var total_tagihan	= $('#total_tagihan').val().trim();
		var total_bayar		= $('#total_bayar').val().trim();
		
		$('#err-pelanggan').html('')
		$('#err-tanggal').html('')
		$('#err-akun_asal').html('')
		$('#err-mata_uang').html('')
		$('#err-konversi').html('')
		$('#err-piutang').html('')
		
		if (pelanggan == '') {
			$('#err-pelanggan').html('<small class="error-msg text-danger">Pelanggan belum dipilih</small>');
			$('#pelanggan').addClass('has-error');
			valid = false;
		} else if (tanggal == '') {
			$('#err-tanggal').html('<small class="error-msg text-danger">Tanggal belum diisi</small>');
			$('#tanggal').addClass('has-error');
			valid = false;
		} else if (tanggal < min_date) {
			$('#err-tanggal').html('<small class="error-msg text-danger">Tanggal minimal '+min_date+'</small>');
			$('#tanggal').addClass('has-error');
			valid = false;
		} else if (akun_asal == '') {
			$('#err-akun_asal').html('<small class="error-msg text-danger">Akun asal belum diisi</small>');
			$('#akun_asal').addClass('has-error');
			valid = false;
		} else if (mata_uang == '') {
			$('#err-mata_uang').html('<small class="error-msg text-danger">Mata uang belum diisi</small>');
			$('#mata_uang').addClass('has-error');
			valid = false;
		} else if (konversi == '') {
			$('#err-konversi').html('<small class="error-msg text-danger">Konversi belum diisi</small>');
			$('#konversi').addClass('has-error');
			valid = false;
		} else if (total_tagihan == '' || total_tagihan == 0) {
			$('#err-piutang').html('<small class="error-msg text-danger">Tagihan belum diisi</small>');
			$('#listPiutang').addClass('has-error');
			valid = false;
		} else if (total_bayar == '' || total_bayar == 0) {
			$('#err-piutang').html('<small class="error-msg text-danger">Pembayaran belum diisi</small>');
			$('#listPiutang').addClass('has-error');
			valid = false;
		}
	})
	return valid
}