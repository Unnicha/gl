    var base_url = $('#base_url').html();
	$('.selectpicker').selectpicker();
	
	$('.datepicker').each(function() {
		$(this).datepicker({
			format		: 'dd/mm/yyyy',
			autoHide	: true,
			startDate	: $(this).attr('min'),
			endDate		: $(this).attr('max'),
			trigger		: $(this).parent(),
			autoPick	: ($(this).attr('id') == 'tanggal') ? true : false,
		})
	})
	
	function maskInput() {
		$('.mask_money').mask('#.##0,00', {
			reverse: true,
			placeholder: '0,00',
		})
	}
	maskInput()
	
	function gantiMatauang() {
		$('.mata-uang').html( $('#mata_uang').val() )
	}
	gantiMatauang()
	
	function konversi() {
		$.ajax({
			type : 'post',
			url : base_url+'getKonversi',
			data : {
				'mata_uang'	: $('#mata_uang').val(),
				'tanggal'	: $('#tanggal').val(),
			},
			success : function(result) {
				$('#konversi').val(result).unmask()
				maskInput()
				total()
			},
		})
	}
	
	function total() {
		$.ajax({
			type : 'post',
			url : base_url+'getTotal',
			data : {
				'konversi'	: $('#konversi').val(),
				'jumlah'	: $('#jumlah').val(),
			},
			success : function(result) {
				$('#total').val(result).unmask()
				maskInput()
			},
		})
	}
	total()
	
	$('#tanggal').change(function() {
		konversi()
	})
	$('#mata_uang').change(function() {
		konversi()
		gantiMatauang()
	})
	$('#jumlah').keyup(function() {
		total()
	})
	$('#konversi').keyup(function() {
		total()
	})