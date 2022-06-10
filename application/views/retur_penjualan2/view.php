<table id="myTable" width=100% class="table table-list table-striped table-bordered mt-3 nowrap">
	<thead class="text-center">
		<tr>
			<th>No.</th>
			<th>Tanggal</th>
			<th>Kode Transaksi</th>
			<th>Pelanggan</th>
			<th>Total</th>
			<th>Action</th>
		</tr>
	</thead>
	
	<tbody class="text-center">
	</tbody>
</table>

<script type="text/javascript" src="<?= base_url() ?>asset/js/dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/dataTables.buttons.min.js"></script>
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
			// 'lengthChange'	: false,
			'order'			: [[1, 'desc'], [2, 'desc']],
			'pageLength'	: 7,
			'ajax'			: {
				'url'	: '<?= base_url() ?>retur_penjualan2/table',
				'type'	: 'post',
				'data'	: function (e) { 
					e.tab = 'transaksi';
				},
			},
			'columns' : [
				{ name: 'num' },
				{ name: 'tanggal_transaksi' },
				{ name: 'kode_transaksi' },
				{ name: 'total_transaksi' },
				{ name: 'act' },
			],
			'scrollX'			: true,
			'scrollCollapse'	: true,
			'columnDefs'		: [
				{ 'class': 'text-right px-3', 'targets': [4] },
				{ 'sortable': false, 'targets': [4,5] },
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
				url		: '<?= base_url() ?>retur_penjualan2/detail',
				data	: {
					'id' : $(this).data('id'),
				},
				success	: function(data) {
					$(".modalDetail").modal('show');
					$(".showDetail").html(data);
				}
			})
		})
		
		// action Hapus
		$('#myTable').on('click', '.btn-hapus', function() {
			$.ajax({
				type	: 'POST',
				url		: '<?= base_url() ?>retur_penjualan2/delete',
				data	: {
					'id' : $(this).data('id'),
				},
				success	: function(data) {
					$(".modalConfirm").modal('show');
					$(".showConfirm").html(data);
				}
			})
		})
	})
</script>
