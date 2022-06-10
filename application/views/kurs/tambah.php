<div class="content container-fluid">
	<div class="content-title"><?= $title ?></div>
	
	<div class="content-body mb-4">
		<form action="" method="post" id="myForm">
			<div class="card">
				<div class="card-body px-4">
					<div class="row" style="margin-bottom: -.9rem;">
						<div class="col-md-6">
							<div class="form-group row">
								<div class="col">
									<label>Mata Uang</label>
									<select class="form-control selectpicker" name="mata_uang" id="mata_uang" required>
										<option value="" selected>-Pilih Mata Uang-</option>
										<?php foreach($mata_uang as $m): ?>
										<?php $pilih = set_value('mata_uang') ? 'selected' : ''; ?>
										<option value="<?= $m['kode_mu'] ?>" <?=$pilih?>>
											<?= $m['kode_mu'].' - '.$m['nama_mu'] ?>
										</option>
										<?php endforeach ?>
									</select>
									<?= form_error('mata_uang', '<small class="text-danger">', '</small>') ?>
								</div>
							</div>
							
							<div class="form-group row">
								<div class="col">
									<label>Tanggal</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="bi bi-calendar icon-small"></i>
											</span>
										</div>
										<input type="text" class="form-control datepicker" name="tanggal" id="tanggal" value="<?= set_value('tanggal') ?>" placeholder="Masukkan Tanggal" autocomplete="off" required>
									</div>
									<?= form_error('tanggal', '<small class="text-danger">', '</small>') ?>
								</div>
							</div>
							
							<div class="form-group row">
								<div class="col">
									<label>Nilai Kurs</label>
									<input type="text" class="form-control" name="nilai_kurs" id="nilai_kurs" value="<?= set_value('nilai_kurs'); ?>" placeholder="Masukkan Nilai Kurs" required>
									<?= form_error('nilai_kurs', '<small class="text-danger">', '</small>') ?>
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
					<a href="<?= base_url('kurs') ?>" class="btn btn-secondary">
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
<script type="text/javascript" src="<?= base_url() ?>asset/js/datepicker.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/jquery.mask.min.js"></script>
<script>
	$('.selectpicker').selectpicker()
	
	$('.datepicker').each(function() {
		$(this).datepicker({
			format		: 'dd/mm/yyyy',
			autoHide	: true,
			trigger		: $(this).parent(),
		})
	})

	function maskDate() {
		$('.datepicker').unmask().mask('00/00/0000', {
			placeholder: 'dd/mm/yyyy',
		})
	}
	maskDate()
	
	$('#nilai_kurs').mask("#.##0,00", {reverse: true});
</script>
