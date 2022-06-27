<h5 class="text-center"><?= $setting['header'] ?></h5>
<p class="text-center mb-0"><?= $setting['tanggal'] ?></p>

<div class="row mb-3">
	<div class="col-md">
		<p class="mb-0">Pelanggan : <?= $setting['pelanggan'] ?></p>
		<p class="mb-0">Barang : <?= $setting['barang'] ?></p>
	</div>
	<div class="col-md-4">
		<p class="mb-0">Jenis Pajak : <?= $setting['pajak'] ?></p>
		<p class="mb-0">Mata Uang : <?= $setting['mata_uang'] ?></p>
	</div>
</div>

<table id="myTable" width=100% class="table table-list table-striped table-bordered mt-3 nowrap">
	<thead class="text-center">
		<tr>
			<th>Bulan</th>
			<th>Qty</th>
			<!-- <th>Jumlah</th> -->
			<!-- <th>Diskon</th> -->
			<th>Total</th>
		</tr>
	</thead>
	
	<tbody class="text-center">
	</tbody>
</table>

<!-- Detail Proses -->
<div class="modal fade modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<div class="modal-content showDetail">
			<!-- Tampilkan Data -->
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url() ?>asset/js/dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/dataTables.bootstrap4.min.js"></script>
<!-- <script type="text/javascript" src="<?= base_url() ?>asset/js/dataTables.fixedColumns.min.js"></script> -->
<!-- <script type="text/javascript" src="<?= base_url() ?>asset/js/dataTables.buttons.min.js"></script> -->
<script> 
	$(document).ready(function () {
		//pop up message success
		if($('.notification').data('val') == 'yes') {
			$('#modalNotif').modal('show');
			setTimeout(function(){ $('#modalNotif').modal('hide'); },2000);
		}
		//pop up message warning
		if($('.warning').data('val') == 'yes') {
			$('#modalWarning').modal('show');
			setTimeout(function(){ $('#modalWarning').modal('hide'); },2000);
		}
	
		//view table
		var table = $('#myTable').DataTable({
			'processing'	: true,
			'serverSide'	: true,
			// 'ordering'		: false,
			'searching'		: false,
			'lengthChange'	: false,
			'pageLength'	: 100,
			'order'			: [[0, 'asc']],
			'ajax'			: {
				'url'	: '<?= base_url() ?>laporan_penjualan/table',
				'type'	: 'post',
				'data'	: function (e) { 
					e.tab = 'bulanan';
				},
			},
			'columns' : [
				{ name: 'id_bulan' },
				{ name: 'qty' },
				// { name: 'jumlah' },
				// { name: 'diskon' },
				{ name: 'total' },
			],
			'scrollX'			: true,
			'scrollCollapse'	: true,
			'columnDefs'		: [
				// { 'sortable': false, 'targets': [0] },
			],
			// 'dom'			: '<"row mb-2"<"col"B>>'
			// 					+'<"row"<"col"l><"col"f>>'
			// 					+'<t>'
			// 					+'<"row mt-2"<"col"i><"col"p>>',
			// 'buttons'		: [
			// 	{
			// 		extend	: 'excel',
			// 		text	: 'Export Excel',
			// 		exportOptions: {
			// 			modifier: {
			// 				page: 'all'
			// 			}
			// 		}
			// 	}
			// ],
		});
		
		//show tooltip
		$('#myTable').on('mouseover', '[data-toggle="tooltip"]', function() {
			$(this).tooltip();
		})
		
		// show Detail
		$('#myTable').on('click', '.btn-detail', function() {
			$.ajax({
				type	: 'POST',
				url		: '<?= base_url() ?>laporan_penjualan/detail',
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
