<div class="content container-fluid">
	<?php if($this->session->flashdata('notification')) : ?>
		<div class="notification" data-val="yes"></div>
	<?php endif; ?>
	<?php if($this->session->flashdata('warning')) : ?>
		<div class="warning" data-val="yes"></div>
	<?php endif; ?>
	
	<div class="row">
		<div class="col">
			<div class="content-title"><?=$title?></div>
		</div>
    </div>
		<div class="row">
		<div class="col">
			<b>Nama User : </b>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col">
			<b>Daftar Perusahaan </b>
		</div>
	</div>
	<br>
    <div class="content-body">
		<div class="card shadow">
			<div class="card-body px-4">
				<table id="myTable" width=100% class="table table-striped mt-3">
					<thead class="text-center">
						<tr>
                            <th>No</th>
							<th>Nama Perusahaan</th>
							<th>Akses</th>
						</tr>
					</thead>
                    <tbody class="text-center">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="row mt-4">
						<div class="col">
							<button type="submit" class="btn btn-success">Submit</button>
							<a href="<?= base_url('user/akses_perusahaan');?>" class="btn btn-secondary">
								<span class="text">Batal</span>
							</a>
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
			'processing'	: true,
			'serverSide'	: true,
			// 'ordering'		: false,
			// 'lengthChange'	: false,
			'pageLength'	: 6,
			'ajax'			: {
				'url'	: '<?=base_url()?>user/perusahaan/table',
				'type'	: 'post',
				'data'	: function (e) { 

				},
			},
			'columns' : [
                { name: 'num'},
				{ name: 'nama_perusahaan' },
				{ name: 'akses' },
				
			],
			// 'scrollY'			: "300px",
			'scrollX'			: true,
			'scrollCollapse'	: true,
			'columnDefs'		: [
				{ 'width': '700', 'targets': 1 },
				{ 'sortable': false, 'targets': [2] },
			],
			'fixedColumns'	: true,
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
				url		: '<?= base_url(); ?>master/barang/detail',
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
				url		: '<?= base_url(); ?>master/barang/delete',
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
