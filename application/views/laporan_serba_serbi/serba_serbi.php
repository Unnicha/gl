<h5 class="text-center"><?= $setting['header'] ?></h5>
<p class="text-center mb-0"><?= $setting['tanggal'] ?></p>

<div class="row">
	<div class="col">
		<p class="mb-0">Akun Asal : <?= $setting['akun_asal'] ?></p>
		<p>Mata Uang : <?= $setting['mata_uang'] ?></p>
	</div>
</div>

<table id="myTable" width=100% class="table table-list table-striped table-bordered mt-3 nowrap">
	<thead class="text-center">
		<tr>
			<th>No.</th>
			<th>Tanggal</th>
			<th>Transaksi</th>
			<th>Akun Lawan</th>
			<th>Nama Lawan</th>
			<th>Keterangan</th>
			<th>Debit</th>
			<th>Kredit</th>
			<th>Saldo</th>
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
		//view table
		var table = $('#myTable').DataTable({
			'processing'	: true,
			'serverSide'	: true,
			// 'ordering'		: false,
			'searching'		: false,
			'lengthChange'	: false,
			'pageLength'	: 100,
			'order'			: [[1, 'asc'], [2, 'asc']],
			'ajax'			: {
				'url'	: '<?= base_url() ?>laporan_serba_serbi/table',
				'type'	: 'post',
				'data'	: function (e) { 
					e.tab = 'serba_serbi';
				},
			},
			'columns' : [
				{ name: 'num' },
				{ name: 'tanggal_transaksi' },
				{ name: 'kode_transaksi' },
				{ name: 'kode_akun' },
				{ name: 'nama_akun' },
				{ name: 'ket_transaksi' },
				{ name: 'debit' },
				{ name: 'kredit' },
				{ name: 'saldo' },
			],
			'scrollX'			: true,
			'scrollCollapse'	: true,
			'columnDefs'		: [
				{ 'sortable': false, 'targets': [0, 5, 6, 7, 8] },
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
				url		: '<?= base_url() ?>laporan_serba_serbi/detail',
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
