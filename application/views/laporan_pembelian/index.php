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
	<div class="modal-dialog modal-dialog-scrollable modal-lg">
		<div class="modal-content showGanti">
			<!-- Tampilkan Data -->
		</div>
	</div>
</div>

<!-- Detail Proses -->
<div class="modal fade modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable modal-lg">
		<div class="modal-content showDetail">
			<!-- Tampilkan Data -->
		</div>
	</div>
</div>

<script> 
	// show Modal Filter
	function popup() {
		$.ajax({
			type	: 'POST',
			url		: '<?= base_url() ?>laporan_pembelian/ganti',
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
		
		// show Detail
		$('#myTable').on('click', '.btn-detail', function() {
			$.ajax({
				type	: 'POST',
				url		: '<?= base_url() ?>laporan_pembelian/detail',
				data	: {
					'id' : $(this).data('id'),
				},
				success	: function(data) {
					$(".modalDetail").modal('show');
					$(".showDetail").html(data);
				}
			})
		})
	})
</script>
