<div class="content container-fluid">
	<?php if($this->session->flashdata('notification')) : ?>
		<div class="notification" data-val="yes"></div>
	<?php endif; ?>
	<?php if($this->session->flashdata('warning')) : ?>
		<div class="warning" data-val="yes"></div>
	<?php endif; ?>
	
	
	<div class="row content-title">
		<div class="col-sm">
			<div class="mb-2 mb-sm-0"><?=$title?></div>
		</div>
			
		<div class="col-sm-auto">
			<a href="<?= base_url() ?>pembelian/tambah" class="btn btn-primary">
				<i class="bi-plus-lg"></i>
				Add
			</a> 
		</div>
	</div>
	
	<div class="card shadow">
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs" id="myTabs">
				<a class="nav-link" href="<?= base_url() ?>pembelian">Pembelian</a>
				<a class="nav-link active">Jurnal</a>
			</ul>
		</div>
		
		<div class="tab-content p-3">
			<table id="myTable" width=100% class="table table-list table-striped table-bordered mt-3 nowrap">
				<thead class="text-center">
					<tr>
						<th>No.</th>
						<th>Tanggal</th>
						<th>Kode Transaksi</th>
						<th>Kode Akun</th>
						<th>Nama Akun</th>
						<!-- <th>Keterangan</th> -->
						<th>Debit</th>
						<th>Kredit</th>
						<th>Saldo</th>
					</tr>
				</thead>
				
				<tbody class="text-center">
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Detail Proses -->
<div class="modal fade modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<div class="modal-content showDetail">
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
				'url'	: '<?= base_url() ?>pembelian/table',
				'type'	: 'post',
				'data'	: function (e) { 
					e.tab = 'jurnal';
				},
			},
			'columns' : [
				{ name: 'urut' },
				{ name: 'tanggal_transaksi' },
				{ name: 'pembelian.kode_transaksi' },
				{ name: 'kode_akun' },
				{ name: 'nama_akun' },
				// { name: 'ket_jurnal' },
				{ name: 'debit' },
				{ name: 'kredit' },
				{ name: 'saldo' },
			],
			'scrollX'			: true,
			'scrollCollapse'	: true,
			'columnDefs'		: [
				{ 'class': 'text-right px-3', 'targets': [5,6] },
				{ 'class': 'text-right px-3 font-weight-bold', 'targets': [7] },
				{ 'sortable': false, 'targets': [0,5,6,7] },
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
				url		: '<?= base_url() ?>pembelian/detail',
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
				url		: '<?= base_url() ?>pembelian/delete',
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
