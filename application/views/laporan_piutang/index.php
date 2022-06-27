<div class="content container-fluid">
	<!-- header -->
	<div class="row content-title">
		<div class="col-sm">
			<div class="mb-2 mb-sm-0"><?= $title ?></div>
		</div>
			
		<div class="col-sm-auto">
			<a class="btn btn-sm btn-primary" onclick="popup()" data-toggle="tooltip" data-placement="bottom" title="Filter laporan">
				<i class="bi bi-filter-left"></i>
				Filter
			</a> 
		</div>
	</div>
	
	<!-- body -->
	<div class="card shadow mb-3">
		<div class="card-body tab-content" id="display">
			<div class="alert alert-dark text-center mb-0" role="alert">
				Belum Ada Data
			</div>
		</div>
	</div>
</div>

<!-- Popup Filter -->
<div class="modal fade modalGanti" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalGantiLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content showGanti">
			<!-- Tampilkan Data -->
		</div>
	</div>
</div>

<script> 
	// show Modal Filter
	function popup() {
		$.ajax({
			type	: 'POST',
			url		: '<?= base_url() ?>laporan_piutang/ganti',
			data	: {
				'id' : $(this).data('id'),
			},
			success	: function(data) {
				$(".modalGanti").modal('show');
				$(".showGanti").html(data);
			}
		})
	}
	
	$(document).ready(function () {
		//show tooltip
		$('.content').on('mouseover', '[data-toggle="tooltip"]', function() {
			$(this).tooltip();
		})
		
		popup()
		
		$('#myTable').on('click', '.btn-detail', function() {
			popup()
		})
	})
</script>
