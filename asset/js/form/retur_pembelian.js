var base_url = $('#base_url').html();
$('.selectpicker').selectpicker();

// Trigger
$('#supplier').change(function() {
	getBySupplier()
});
$('#faktur_beli').change(function() {
	getPembelian()
});
$('#no_giro').keyup(function() {
	if( $(this).val().trim() == '' ) {
		$('#jatuh_tempo_giro').removeAttr('required');
	} else {
		$('#jatuh_tempo_giro').attr('required', 'required');
	}
});

$('.datepicker').each(function() {
	$(this).datepicker({
		date		: $(this).attr('min'),
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
	
function autoUppercase(field) {
	let p=field.selectionStart; field.value=field.value.toUpperCase(); field.setSelectionRange(p, p);
}

function cekPembelian() {
	var pembelian = $('#pembelian').val();
	if (pembelian != '' && pembelian != undefined) {
		$.ajax({
			type	: 'post',
			url		: base_url+'retur_pembelian/getByPembelian',
			data	: {
				'pembelian' : pembelian,
				'faktur_beli' : $('#faktur_beli').val(),
			},
			success	: function(data) {
				if (data != '') {
					var result	= JSON.parse(data);
					$('#supplier').html(result.supplier_list)
						.selectpicker('refresh').selectpicker('val', result.supplier)
					$('#faktur_beli').html(result.faktur_list)
						.selectpicker('refresh').selectpicker('val', result.faktur_beli)
					$("#akun_lawan").html(result.lawan_list)
						.selectpicker('refresh').selectpicker('val', '')
					$("#mata_uang").html(result.uang_list)
						.selectpicker('refresh').selectpicker('val', result.mata_uang)
					$("#tanggal").attr('min', result.tanggal_transaksi)
						.datepicker('setStartDate', result.tanggal_transaksi)
					$('#konversi').val(result.konversi)
					$('#jenis_ppn').val(result.jenis_ppn)
					$('#besar_ppn').val(result.besar_ppn)
					$('#akun_ppn').val(result.akun_ppn)
					maskMoney()
				}
			},
		});
	} else if (pembelian == undefined) {
		showProduk()
	}
}
cekPembelian()

// fungsi untuk menampilkan list faktur beli berdasarkan supplier
function getBySupplier() {
	$.ajax({
		type	: 'post',
		url		: base_url+'retur_pembelian/getBySupplier',
		data	: {
			'supplier' : $('#supplier').val(),
		},
		success	: function(result) {
			if (result) {
				var data	= JSON.parse(result);
				$("#faktur_beli").html(data.list_faktur).selectpicker('refresh').selectpicker('val', '')
				$("#akun_lawan").html(data.list_lawan).selectpicker('refresh').selectpicker('val', '')
			}
		},
	});
}

// fungsi untuk mengambil data pembelian
function getPembelian() {
	$.ajax({
		type	: 'post',
		url		: base_url+'retur_pembelian/getPembelian',
		data	: {
			faktur_beli : $('#faktur_beli').val(),
		},
		success	: function(data) {
			var result	= JSON.parse(data);
			var uang	= result.mata_uang ? result.mata_uang : 'IDR';
			
			$("#mata_uang").html(result.uang_list)
				.selectpicker('refresh').selectpicker('val', result.mata_uang)
			$("#tanggal").attr('min', result.min_date).datepicker('setStartDate', result.min_date)
			$('#konversi').val(result.konversi)
			$('#jenis_ppn').val(result.jenis_ppn)
			$('#besar_ppn').val(result.besar_ppn)
			$('#akun_ppn').val(result.akun_ppn)
			maskMoney()
		},
	});
}

// tampil row total produk di table produk
function totalProduk() {
	$('#row_produk').remove();
	var jumProduk	= countProduk()
	if (jumProduk != undefined) {
		var mataUang	= $('#mata_uang').val()
		var rowTotal	= '<tr class="font-weight-bold" id="row_produk" data-id="row_produk">'
			+'<td colspan="4">Total Produk</td>'
			+'<td>'+ mataUang +'</td>'
			+'<td class="text-right">'
				+ jumProduk.toLocaleString('id-ID', {minimumFractionDigits:'2', maximumFractionDigits:'2'}) 
				
			+'</td>'
			+'<td>'+'<input type="hidden" name="total_produk" id="total_produk" value="'+ jumProduk +'"></td>'
		+'</tr>';
		
		$('#listProduk').append(rowTotal);
	}
}

// untuk menghitung jumlah harga produk
function countProduk() {
	var last_id	= $('.produk-row:last-child').data('id');
	if(last_id == undefined) {
		return last_id
	} else {
		var jumProd	= 0;
		var count	= 0;
		for(var i=0; i<=last_id; i++) {
			var totalProd	= isNaN($('#jumlah_produk'+i).val()) ? 0 : ($('#jumlah_produk'+i).val() * 1);
			jumProd = jumProd + totalProd;
			count++
		}
		return jumProd
	}
}
	
// fungsi untuk hitung dan tampil total transaksi
function totalTransaksi() {
	var total_prod	= $('#total_produk').val();
	var besar_ppn	= $('#besar_ppn').val().trim();
	var jenis_ppn	= $('#jenis_ppn').val().trim();
	
	if (total_prod != undefined && besar_ppn != '' && jenis_ppn != '') {
		$.ajax({
			type : 'post',
			url : base_url+'retur_pembelian/getTotal',
			data : {
				'total_prod'	: total_prod,
				'besar_ppn'		: besar_ppn,
				'jenis_ppn'		: jenis_ppn,
			},
			success : function(data) {
				if(data != 'null') {
					var result = jQuery.parseJSON(data);
					$('#totalProduk').html(result.total_produk);
					$('#totalPPN').html(result.ppn);
					$('#totalFin').html(result.total_fin);
				}
			},
		});
	} else {
		$('#totalProduk').html('0,00');
		$('#totalPPN').html('0,00');
		$('#totalFin').html('0,00');
	}
}
totalTransaksi();

// tampil produk
function showProduk() {
	$.ajax({
		type	: 'post',
		url		: base_url+'retur_pembelian/showProduk',
		data	: {
			kode_transaksi	: $('#kode_transaksi').val(),
		},
		success : function(data) {
			$('#listProduk').html(data);
		},
		complete : function() {
			totalProduk()
			totalTransaksi()
		},
	})
}

// tambah produk
$('.add-btn').click(function() {
	var id	= $("input[name='id_produk[]']").map(function(){ return $(this).val() }).get()
	
	$.ajax({
		type	: 'post',
		url		: base_url+'retur_pembelian/addProduk',
		data	: {
			'faktur_beli'	: $('#faktur_beli').val(),
			'mata_uang'		: $('#mata_uang').val(),
			'id_last'		: $("body tbody").children("tr.produk-row").length,
			'id_selected'	: id,
		},
		success : function(result) {
			$("#modalForm").modal('show');
			$("#showForm").html(result);
		},
	})
})

// edit produk
$('tbody').on('click', '.btn-edit', function() {
	var id = $(this).data('id');
	
	$.ajax({
		type	: 'post',
		url		: base_url+'retur_pembelian/editProduk',
		data	: {
			mata_uang	: $('#mata_uang').val(),
			faktur_beli	: $('#faktur_beli').val(),
			id_row		: id,
			id_produk	: $('#id_produk'+id).val(),
			kode		: $('#kode_produk'+id).val(),
			nama		: $('#nama_produk'+id).val(),
			ket			: $('#ket_produk'+id).val(),
			qty			: $('#qty_produk'+id).val(),
			harga		: $('#harga_produk'+id).val(),
			diskon		: $('#diskon_produk'+id).val(),
			total		: $('#jumlah_produk'+id).val(),
		},
		success : function(result) {
			$("#modalForm").modal('show');
			$("#showForm").html(result);
		}
	})
})

// konfirmasi hapus produk
$('tbody').on('click', '.btn-delete', function() {
	$.ajax({
		type	: 'post',
		url		: base_url+'retur_pembelian/deleteProduk',
		data	: { id : $(this).data('id'), },
		success	: function(data) {
			$(".modalConfirm").modal('show');
			$(".showConfirm").html(data);
		},
	})
})

// fix hapus produk
function fixDelete(id) {
	$(".modalConfirm").modal('hide');
	$('tbody').children('tr#produkRow'+id).remove();
	totalProduk();
	totalTransaksi();
}

const form = document.getElementById('myForm');
form.addEventListener('submit', (e) => {
	var valid = true;
	$('.error-msg').remove();
	$('.form-control').removeClass('has-error');
	
	if (validProduk() == false) { valid = false; }
	if (validInput() == false) { valid = false; }
	
	if(valid == false) {
		e.preventDefault();
		$('.has-error:first').focus();
	}
})

function validProduk() {
	if ($('.produk-row').data('id') == undefined) {
		var msg = '<small class="error-msg text-danger">Produk harus diisi</small>';
		$('#table-produk').after( msg );
		return false;
	} else
		return true;
}

function validInput() {
	var valid = true;
	$('#myForm .form-control').each(function() {
		var field	= $(this);
		var value	= field.val().trim();
		var target	= field.data('target');
		var min		= field.attr('min');
		var max		= field.attr('max');
		var min_len	= field.attr('min-length');
		var max_len	= field.attr('max-length');
		
		// required
		if (field.attr('required') == 'required' && value.length == 0) {
			var msg = '<small class="error-msg text-danger">Belum diisi.</small>';
			$(target).addClass('text-danger').html(msg);
			field.addClass('has-error');
			valid = false;
		}
		else if (value.length != 0) {
			// min / max pada tipe number
			if (field.attr('type') == 'number') {
				// cek nilai min
				if (!isNaN(min) && min != '') {
					if(value < min) {
						var msg = '<small class="error-msg text-danger">Minimal '+ min +'</small>';
						$(target).addClass('text-danger').html(msg);
						field.addClass('has-error');
						valid = false;
					}
				}
				// cek nilai max
				else if (!isNaN(max) && max != '') {
					if(value < max) {
						var msg = '<small class="error-msg text-danger">Maximal '+ max +'</small>';
						$(target).addClass('text-danger').html(msg);
						field.addClass('has-error');
						valid = false;
					}
				}
			}
			// min / max pada tipe date
			else if (field.hasClass('datepicker')) {
				value = value.split('/')
				value = value[2]+'/'+value[1]+'/'+value[0]
				
				// cek nilai min
				if (min != undefined) {
					var date = min.split('/')
					date = date[2]+'/'+date[1]+'/'+date[0]
					if (value < date) {
						var msg = '<small class="error-msg text-danger">Minimal '+ min +'</small>';
						$(target).addClass('text-danger').html(msg);
						field.addClass('has-error');
						valid = false;
					}
				}
				// cek nilai max
				if (max != undefined) {
					var date = max.split('/')
					date = date[2]+'/'+date[1]+'/'+date[0]
					if (value > date) {
						var msg = '<small class="error-msg text-danger">Maximal '+ max +'</small>';
						$(target).addClass('text-danger').html(msg);
						field.addClass('has-error');
						valid = false;
					}
				}
			}
			else {
				// cek min length
				if (min_len != undefined) {
					if (value.length < min_len) {
						var msg = '<small class="error-msg text-danger">Minimal '+ min_len +' karakter</small>';
						$(target).addClass('text-danger').html(msg);
						field.addClass('has-error');
						valid = false;
					}
				}
				// cek max length
				if (max_len != undefined) {
					if (value.length < max_len) {
						var msg = '<small class="error-msg text-danger">Maximal '+ max_len +' karakter</small>';
						$(target).addClass('text-danger').html(msg);
						field.addClass('has-error');
						valid = false;
					}
				}
			}
		}
	})
	return valid
}