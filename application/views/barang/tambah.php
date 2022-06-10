<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<div class="content-body my-4">
		<form action="" method="post">
			<div class="card shadow">
				<div class="card-body px-4">
					<div class="row" style="margin-bottom: -15px;">
						<div class="col">
							<div class="form-group row">
								<label class="col-md-3 col-lg-2 col-form-label">Kode Barang</label>
								<div class="col">
									<input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Masukkan Kode Barang" value="<?= set_value('kode_barang'); ?>" required>
									<?=form_error('kode_barang')?>
								</div>  
							</div>
		
							<div class="form-group row">
								<label class="col-md-3 col-lg-2 col-form-label">Nama Barang</label>
								<div class="col">
									<input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Masukkan Nama Barang" value="<?= set_value('nama_barang'); ?>" required>
									<?=form_error('nama_barang')?>
								</div>  
							</div>
							
							<div class="form-group row">
								<label class="col-form-label col-lg-2">Proses</label>
								<div class="col">
									<div class="mt-2">
										<?php 
											$hpp	= set_value('proses') == 'HPP' ? 'checked' : '';
											$nonhpp	= set_value('proses') == 'NON HPP' ? 'checked' : '';
										?>
										<div class="form-check form-check-inline mr-3">
											<input class="form-check-input" type="radio" name="proses" id="hpp" value="HPP" <?=$hpp?>>
											<label class="form-check-label">HPP</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="proses" id="nonhpp" value="NON HPP" <?=$nonhpp?>>
											<label class="form-check-label">NON HPP</label>
										</div>
									</div>
									<?= form_error('proses') ?>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-md-3 col-lg-2 col-form-label">Satuan</label>
								<div class="col">
									<input type="text" class="form-control" name="satuan" id="satuan" placeholder="Masukkan Satuan" value="<?= set_value('satuan'); ?>" required>
									<?=form_error('satuan')?>
								</div>  
							</div>
							
							<div class="form-group row">
								<label class="col-md-3 col-lg-2 col-form-label">Stock Awal</label>
								<div class="col">
									<input type="text" class="form-control" name="stock_awal" id="stock_awal" placeholder="Masukkan jumlah stock awal" value="<?= set_value('stock_awal'); ?>" required>
									<?=form_error('stock_awal')?>
								</div>  
							</div>
							
							<div class="form-group row">
								<label class="col-md-3 col-lg-2 col-form-label">Nilai Awal</label>
								<div class="col">
									<input type="text" class="form-control" name="nilai_awal" id="nilai_awal" placeholder="Masukkan nilai awal barang" value="<?= set_value('nilai_awal'); ?>" required>
									<?=form_error('nilai_awal')?>
								</div>  
							</div>
							
							<div class="form-group row">
								<label class="col-md-3 col-lg-2 col-form-label">Jumlah</label>
								<div class="col">
									<input type="text" class="form-control" name="jumlah" id="jumlah" readonly>
									<?=form_error('jumlah')?>
								</div>  
							</div>
						</div>
					</div>
				</div>
				<!-- card-body -->
			</div>
			<!-- card -->
			
			<div class="row mt-4">
				<div class="col">
					<button type="submit" class="btn btn-success">Submit</button>
					<a href="<?= base_url() ?>barang" class="btn btn-secondary">
						<span class="text">Batal</span>
					</a>
				</div>
			</div>
		</form> 
	</div>
	<!-- content-body -->
</div>
<!-- container-fluid -->

<script> 
	$(document).ready(function(){
		
		function getJumlah() {
			var stok	= $('#stock_awal').val();
			var nilai	= $('#nilai_awal').val();
			$('#jumlah').val(stok * nilai);
		}
		getJumlah();
		
		$('#stock_awal').keyup(function(){ 
			getJumlah();
		}); 
		$('#nilai_awal').keyup(function(){ 
			getJumlah();
		}); 
	});
</script>
