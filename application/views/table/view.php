<div class="content container-fluid">
	<div class="row mb-3">
		<div class="col">
			<div class="content-title"><?=$title?></div>
		</div>
		
		<div class="col-sm-auto">
			<a href="<?= base_url(); ?>pages/table/add" class="btn btn-primary">
				<i class="bi-plus-circle"></i>
				Add
			</a> 
		</div>
	</div>
	
	<div class="content-body">
		<div class="card card-shadow">
			<div class="card-body p-4">
				<table id="myTable" width=100% class="table table-striped mt-3">
					<thead class="text-center">
						<tr>
							<th scope="col">No.</th>
							<th scope="col">Nama Klien</th>
							<th scope="col">Nama Dokumen</th>
							<th scope="col">Masa</th>
							<th scope="col">Tahun</th>
							<th scope="col">Tanggal Terima</th>
							<th scope="col">Format</th>
							<th scope="col">Penerima</th>
							<th scope="col">Scan</th>
							<th scope="col">Keterangan</th>
							<th scope="col">Rekap</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					
					<tbody class="text-center">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal Verifikasi -->
<div class="modal fade modalVerif" tabindex="-1" aria-labelledby="modalVerifLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content mx-auto showVerif">
			<!-- Tampilkan Data -->
		</div>
	</div>
</div>

<!-- Modal Hapus -->
<div class="modal fade modalConfirm" tabindex="-1" aria-labelledby="modalConfirmLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content mx-auto showConfirm" style="width:400px">
		</div>
	</div>
</div>

<script type="text/javascript" src="<?=base_url()?>asset/js/dataTables.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>asset/js/dataTables.buttons.min.js"></script>
<script> 
	$(document).ready(function () {
		//pop up message success
		if($('.notification').data('val') == 'yes') {
			$('#modalNotif').modal('show');
			setTimeout(function(){ $('#modalNotif').modal('hide'); },2000);
		}
		//pop up message failed
		if($('.warning').data('val') == 'yes') {
			$('#modalWarning').modal('show');
			setTimeout(function(){ $('#modalWarning').modal('hide'); },2000);
		}
	
		//view table
		var table = $('#myTable').DataTable({
			// 'processing'	: true,
			// 'serverSide'	: true,
			// 'responsive'	: true,
			// 'ordering'		: false,
			// 'lengthChange'	: false,
			// 'pageLength'	: 8,
			// 'ajax'			: {
			// 	'url'	: '<?=base_url()?>data_masuk/page',
			// 	'type'	: 'post',
			// 	'data'	: function (e) { 
			// 		e.klien = $('#klien').val(); 
			// 		e.bulan = $('#bulan').val(); 
			// 		e.tahun = $('#tahun').val();
			// 	},
			// },
		});
		
		//show tooltip
		$('#myTable').on('mouseover', '[data-toggle="tooltip"]', function() {
			$(this).tooltip();
		})
		
		$('#myTable').on('click', '.btn-hapus', function() {
			$.ajax({
				type	: 'POST',
				url		: '<?= base_url(); ?>data_masuk/delete',
				data	: {
					'id'	: $(this).data('id'),
				},
				success	: function(data) {
					$(".modalConfirm").modal('show');
					$(".showConfirm").html(data);
				}
			})
		})
	})
</script>