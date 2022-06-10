	var base_url = $('#base_url').html();
	$('.selectpicker').selectpicker();
	$('.mata-uang').html( $('#mata_uang').val() );
	
	// Trigger 
	$('#pelanggan').change(function() {
		getLawan()
	});
	$('#jenis_pembayaran').change(function() {
		getLawan()
	});
	$('#tanggal').change(function() {
		var value = $(this).val()
		getKonversi();
		$('#jatuh_tempo').attr('min', value).datepicker('setStartDate', value);
	});
	$('#mata_uang').change(function() {
		getKonversi();
	});
	$('#konversi').keyup(function() {
		totalTransaksi()
	});
	$('#diskon_luar').keyup(function() {
		totalTransaksi()
	});
	$('#jenis_ppn').change(function() {
		if ( $(this).val() == 'Include' || $(this).val() == 'Exclude' ) {
			$('#besar_ppn').attr('required', 'required')
			$('#akun_ppn').attr('required', 'required')
		} else {
			$('#besar_ppn').removeAttr('required')
			$('#akun_ppn').removeAttr('required')
		}
		totalTransaksi()
	});
	$('#besar_ppn').keyup(function() {
		totalTransaksi()
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
			format		: 'dd/mm/yyyy',
			autoHide	: true,
			date		: $(this).attr('min'),
			startDate	: $(this).attr('min'),
			endDate		: $(this).attr('max'),
			trigger		: $(this).parent(),
			// autoPick	: ( $(this).attr('id') == 'tanggal' ) ? true : false,
		})
	})
	
	function maskMoney() {
		$('.mask_money').mask('#.##0,00', {
			reverse: true,
			placeholder: '0,00',
		})
	}
	maskMoney()
	
	function maskDate() {
		$('.datepicker').mask('00/00/0000', {
			placeholder: 'dd/mm/yyyy',
		})
	}
	maskDate()
	
	function autoUppercase(field) {
		let p=field.selectionStart;field.value=field.value.toUpperCase();field.setSelectionRange(p, p);
	}
	
	function showProduk() {
		var kode = $('#kode_transaksi').val().trim();
		if (kode != '' && kode != undefined) {
			$.ajax({
				type	: 'post',
				url		: base_url+'penjualan/showProduk',
				data	: {
					kode_transaksi	: kode,
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
	}
	
	// tambah produk
	$('.add-btn').click(function() {
		var kode = $("input[name='kode_produk[]']").map(function(){ return $(this).val(); }).get()
		
		$.ajax({
			type	: 'post',
			url		: base_url+'penjualan/addProduk',
			data	: {
				'mata_uang'	: $('#mata_uang').val(),
				'id_last'	: $("body tbody").children("tr.produk-row").length,
				'kode'		: kode,
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
			url		: base_url+'penjualan/editProduk',
			data	: {
				mata_uang	: $('#mata_uang').val(),
				id_row		: id,
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
			url		: base_url+'penjualan/deleteProduk',
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
		totalProduk()
		totalTransaksi()
	}
	
	// fungsi untuk menampilkan nilai konversi mata uang
	function getKonversi() {
		if ($('#tanggal').val() != '' && $('#mata_uang').val() != '') {
			$.ajax({
				type	: 'post',
				url		: base_url+'penjualan/getKonversi',
				data	: {
					tanggal : $('#tanggal').val(),
					mata_uang : $('#mata_uang').val(),
				},
				success	: function(result) {
					$("#konversi").val(result)
					$('.mata-uang').html( $('#mata_uang').val() );
				},
				complete : function() {
					$("#konversi").unmask()
					maskMoney()
				},
			});
		}
		totalTransaksi()
	}
	getKonversi()
	
	// fungsi untuk menampilkan nilai konversi mata uang
	function getLawan() {
		if ($('#jenis_pembayaran').val() != '' && $('#pelanggan').val() != '') {
			$.ajax({
				type	: 'post',
				url		: base_url+'penjualan/getLawan',
				data	: {
					'pelanggan'	: $('#pelanggan').val(),
					'jenis_trx'	: $('#jenis_pembayaran').val(),
				},
				success	: function(result) {
					if (result) {
						var data = jQuery.parseJSON(result);
						$("#akun_lawan").html(data.option)
							.selectpicker('refresh').selectpicker('val', data.selected)
					}
				},
			});
		}
	}
	getLawan()
	
	// untuk menghitung jumlah harga produk
	function countProduk() {
		var last_id	= $('.produk-row:last-child').data('id');
		if (last_id == undefined) {
			return last_id
		} else {
			var jumProd	= 0;
			var count	= 0;
			for (var i=0; i<=last_id; i++) {
				var totalProd	= isNaN($('#jumlah_produk'+i).val()) ? 0 : ($('#jumlah_produk'+i).val() * 1);
				jumProd = jumProd + totalProd;
				count++
			}
			return jumProd
		}
	}
	
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
	
	// fungsi untuk hitung dan tampil total transaksi
	function totalTransaksi() {
		var total_prod	= $('#total_produk').val();
		var diskon		= $('#diskon_luar').val().trim();
		var besar_ppn	= $('#besar_ppn').val().trim();
		var jenis_ppn	= $('#jenis_ppn').val().trim();
		
		if (total_prod != undefined) {
			$.ajax({
				type : 'post',
				url : base_url+'penjualan/getTotal',
				data : {
					'total_prod'	: (total_prod == undefined) ? 0 : total_prod,
					'diskon'		: (diskon == '') ? 0 : diskon,
					'besar_ppn'		: (besar_ppn == '') ? 0 : besar_ppn,
					'jenis_ppn'		: jenis_ppn,
				},
				success : function(data) {
					if(data != 'null') {
						var result = jQuery.parseJSON(data);
						$('#totalProduk').html(result.total_produk);
						$('#totalDiskon').html(result.diskon);
						$('#totalPPN').html(result.ppn);
						$('#totalFin').html(result.total_fin);
					}
				},
			});
		} else {
			$('#totalProduk').html('0,00');
			$('#totalDiskon').html('0,00');
			$('#totalPPN').html('0,00');
			$('#totalFin').html('0,00');
		}
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
			$('#table-produk').after(msg).addClass('has-error');
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
				// cek ketersediaan faktur jual
				if (field.attr('unique') != undefined) {
					$.ajax({
						type : 'post',
						url : base_url+'penjualan/checkInvoice',
						data : {
							'invoice' : $('#faktur_jual').val(),
						},
						success : function(check) {
							if (check == 'false') {
								var msg = '<small class="error-msg text-danger">No. Faktur sudah digunakan.</small>';
								$(target).addClass('text-danger').html(msg);
								field.addClass('has-error');
								valid = false;
							}
						},
					});
				}
				
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
					if (!isNaN(max) && max != '') {
						if(value < max) {
							var msg = '<small class="error-msg text-danger">Maksimal '+ max +'</small>';
							$(target).addClass('text-danger').html(msg);
							field.addClass('has-error');
							valid = false;
						}
					}
				}
				
				// min / max pada tipe date
				if (field.hasClass('datepicker')) {
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
						console.log(value);
						console.log(date);
					}
					// cek nilai max
					if (max != undefined) {
						var date = max.split('/')
						date = date[2]+'/'+date[1]+'/'+date[0]
						if (value > date) {
							var msg = '<small class="error-msg text-danger">Maksimal '+ max +'</small>';
							$(target).addClass('text-danger').html(msg);
							field.addClass('has-error');
							valid = false;
						}
					}
				}
				
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
						var msg = '<small class="error-msg text-danger">Maksimal '+ max_len +' karakter</small>';
						$(target).addClass('text-danger').html(msg);
						field.addClass('has-error');
						valid = false;
					}
				}
			}
		})
		return valid
	}