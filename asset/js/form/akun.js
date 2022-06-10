
	$(document).ready(function() {
		$('.selectpicker').selectpicker();
		
		// respon untuk field Kode Akun
		$('#kode_akun').keyup(function() {
			$('#cekKode').removeAttr('class').html('');
			if($(this).val().trim().length > 0) {
				$.ajax({
					url : "<?= base_url() ?>akun/cekKode",
					method : "POST",
					data : {
						kode_akun : $(this).val(),
					},
					success: function(data){
						var result = jQuery.parseJSON(data);
						$('#cekKode').addClass(result.class).html(result.msg);
					},
				});
			} else {
				$('#cekKode').addClass('text-danger').html('Kode Akun harus diisi.');
			}
		})
		
		// ganti option Jenis Akun
		function getJenis() {
			// var val = '<?=set_value('jenis')?>';
			$.ajax({
				url : "<?= base_url() ?>akun/getJenis",
				method : "POST",
				data : {
					tipe	: $('#tipe').val(),
					value	: val,
					gol		: $('#golongan').val(),
				},
				success : function(data) {
					$('#jenis').html(data)
						.selectpicker('val', val)
						.selectpicker('refresh');
				},
				complete : function() {
					if($('#golongan').val() == 'LABARUGI' && $('#tipe').val() == 'Induk') {
						$('#jenis').removeAttr('required');
					}
					// pilihInduk();
				}
			});
		}
		getJenis();
		
		// ganti option Induk
		function pilihInduk() {
			var tipe = $('#tipe').val();
			// var val = '<?= set_value('induk') ?>';
			$.ajax({
				url : "<?= base_url() ?>akun/getInduk",
				method : "POST",
				data : {
					tipe	: tipe,
					value	: val,
					tingkat	: $('#tingkat').val(),
				},
				success: function(data){
					// if(tipe == 'Anak') {
					// 	$('#induk').attr('required', 'required');
					// } else {
					// 	$('#induk').removeAttr('required');
					// }
					$('#induk').html(data).selectpicker('refresh')
								.selectpicker('val', val);
				},
				complete : function() {
					saldoVal();
				}
			});
		}
		
		function saldoVal() {
			if( $('#tipe').val() == 'Induk' ) {
				$('#saldo_awal').attr('readonly', 'readonly')
								.attr('placeholder', '-')
								.removeAttr('required')
				$('#saldo_normal').html('<option value="-">-</option>').selectpicker('refresh');
			} else {
				$('#saldo_awal').removeAttr('readonly')
								.attr('required', 'required')
								.attr('placeholder', 'Masukkan jumlah saldo awal')
				$('#saldo_normal').html('<option value="">Pilih Jenis Saldo</option>'+
										'<option value="Debit">Debit</option>'+
										'<option value="Kredit">Kredit</option>')
										.selectpicker('refresh')
			}
			// $('#saldo_normal').selectpicker('val', '<?=set_value('saldo_normal')?>');
		}
		
		$('#golongan').change(function() {
			getJenis();
		})
		$('#tipe').change(function(){ 
			getJenis();
			saldoVal()
		});
		$('#tingkat').keyup(function(){ 
			pilihInduk();
		});
		$('#induk').change(function() {
			if ($(this).val() != '')
			$('#kode_akun').val( $(this).val() );
		})
	});