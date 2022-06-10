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
			<a href="<?= base_url() ?>penjualan2/tambah" class="btn btn-sm btn-primary">
				<i class="bi-plus-lg"></i>
				Add
			</a> 
		</div>
	</div>
	
	<div class="card shadow">
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs" id="myTabs">
				<a class="nav-link tabs active" id="penjualan">Penjualan</a>
				<a class="nav-link tabs" id="retur_penjualan">Retur</a>
				<a class="nav-link tabs" id="piutang">Pembayaran</a>
			</ul>
		</div>
		
		<div class="tab-content p-3">
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

<!-- Modal Hapus -->
<div class="modal fade modalConfirm" tabindex="-1" aria-labelledby="modalConfirmLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content mx-auto showConfirm" style="width:400px">
		</div>
	</div>
</div>

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
		
		$('.tabs').click(function () {
			$('a').removeClass('active');
			viewTab( $(this).attr('id') );
			$(this).addClass('active');
		})
		
		function viewTab(tab='') {
			tab = (tab == '') ? 'penjualan' : tab;
			$.ajax({
				type	: 'POST',
				data	: { 'tab' : tab },
				url		: '<?= base_url() ?>'+tab+'2/view',
				success	: function(data) {
					$('.tab-content').html(data);
				}
			})
		}
		viewTab()
	})
</script>
