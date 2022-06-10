<div class="content container-fluid">
	<?php if($this->session->flashdata('notification')) : ?>
		<div class="notification" data-val="yes"></div>
	<?php endif; ?>
	<?php if($this->session->flashdata('warning')) : ?>
		<div class="warning" data-val="yes"></div>
	<?php endif; ?>
	
	<div class="row content-title">
		<div class="col-sm">
			<div class="mb-2 mb-sm-0"><?= $title ?></div>
		</div>
			
		<div class="col-sm-auto">
			<a href="<?= base_url() ?>laporan_penjualan/tampilan" class="btn btn-sm btn-primary">
				Ganti Tampilan
			</a> 
		</div>
	</div>
	
	<div class="card shadow mb-3">
		<div class=" card-body tab-content">
			<table id="myTable" width=100% class="table table-list table-striped table-bordered mt-3 nowrap">
				<thead class="text-center">
					<tr>
						<th>No.</th>
						<th>Tanggal</th>
						<th>Faktur Jual</th>
						<th>Surat Jalan</th>
						<th>Pelanggan</th>
						<th>Kode Barang</th>
						<th>Nama Barang</th>
						<th>Qty</th>
						<th>Satuan</th>
						<th>Harga Satuan</th>
						<th>Diskon</th>
						<th>Jumlah</th>
						<th>Diskon Luar</th>
						<th>DPP</th>
						<th>% PPN</th>
						<th>Nilai PPN</th>
						<th>Total</th>
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
	<div class="modal-dialog modal-xl modal-dialog-scrollable modal-lg">
		<div class="modal-content showDetail">
			<!-- Tampilkan Data -->
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
			'searching'		: false,
			'lengthChange'	: false,
			'order'			: [[1, 'desc'], [2, 'desc']],
			'pageLength'	: 10,
			'ajax'			: {
				'url'	: '<?= base_url() ?>laporan_penjualan/table',
				'type'	: 'post',
				'data'	: function (e) { 
					e.tab = 'detail';
				},
			},
			'columns' : [
				{ name: 'num' },
				{ name: 'tanggal_transaksi' },
				{ name: 'faktur_retur_jual' },
				{ name: 'surat_jalan' },
				{ name: 'pelanggan' },
				{ name: 'kode_barang' },
				{ name: 'nama_barang' },
				{ name: 'qty_produk' },
				{ name: 'satuan_produk' },
				{ name: 'harga_produk' },
				{ name: 'diskon_produk' },
				{ name: 'jumlah_produk' },
				{ name: 'diskon_luar' },
				{ name: 'dpp' },
				{ name: 'besar_ppn' },
				{ name: 'nilai_ppn' },
				{ name: 'total' },
			],
			'scrollX'			: true,
			'scrollCollapse'	: true,
			'columnDefs'		: [
				{ 'class': 'text-right', 'targets': [9,10,11,12,13,15,16] },
				{ 'sortable': false, 'targets': [0] },
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
