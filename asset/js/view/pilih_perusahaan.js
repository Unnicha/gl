var base_url = $('#base_url').val()

$('#perusahaan').change(function() {
	getTahun()
})

function getTahun() {
	$.ajax({
		type : 'post',
		url : base_url+'home/getTahun',
		data : {
			'perusahaan'	: $('#perusahaan').val(),
		},
		success : function(result) {
			$('#tahun').html(result).selectpicker('refresh').selectpicker('val', '')
		},
	})
}