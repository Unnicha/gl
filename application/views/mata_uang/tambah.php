<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<div class="content-body my-4">
		<form action="" method="post">
			<div class="card shadow">
				<div class="card-body px-4">
					<div class="row" style="margin-bottom: -15px;">
						<div class="col">
							<div class="form-group row">
								<label class="col-sm-2 col-form-label" for="kode_mu">Kode Mata Uang</label>
								<div class="col">
									<input type="text" class="form-control" name="kode_mu" id="kode_mu" value="<?= set_value('kode_mu') ?>" readonly>
									<?= form_error('kode_mu', '<small class="text-danger">', '</small>') ?>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-sm-2 col-form-label" for="nama_mu">Nama Mata Uang</label>
								<div class="col">
									<select name="nama_mu" id="nama_mu" class="form-control selectpicker" data-live-search="true" data-size="7" required>
										<option value="">Pilih Mata Uang</option>
										<?php foreach($list as $k) : ?>
										<?php $pilih = ($k['nama'] == set_value('nama_mu')) ? 'selected' : '' ?>
										<option value="<?=$k['nama']?>" <?=$pilih?> ><?= $k['nama'] ?></option>
										<?php endforeach ?>
									</select>
									<?= form_error('nama_mu', '<small class="text-danger">', '</small>') ?>
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
					<a href="<?= base_url('mata_uang') ?>" class="btn btn-secondary">
						<span class="text">Batal</span>
					</a>
				</div>
			</div>
		</form> 
	</div>
	<!-- content-body -->
</div>
<!-- container-fluid -->

<script type="text/javascript" src="<?= base_url() ?>asset/js/bootstrap-select.min.js"></script>
<script>
	$('.selectpicker').selectpicker()
	// .selectpicker('val', $(this).val());
	
	$('#nama_mu').change(function() {
		$.ajax({
			type : 'post',
			url : '<?= base_url() ?>mata_uang/getKode',
			data : { name : $(this).val() },
			success : function(d) {
				$('#kode_mu').val(d);
			},
		})
	})
</script>