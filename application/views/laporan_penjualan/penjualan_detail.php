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
			// 'pageLength'	: 10,
			'order'			: [[1, 'asc'], [2, 'asc']],
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
				{ name: 'faktur_jual' },
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
