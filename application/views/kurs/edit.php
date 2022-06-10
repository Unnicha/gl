<div class="content container-fluid">
	<div class="content-title"><?= $title ?></div>
	
	<div class="content-body mb-4">
		<form action="" method="post">
			<input type="hidden" name="kode_kurs" id="kode_kurs" value="<?= $kurs['kode_kurs'] ?>">
			
			<div class="card">
				<div class="card-body px-4">
					<div class="row" style="margin-bottom: -.9rem;">
						<div class="col-lg-6 col-md-9">
							<div class="form-group row">
								<div class="col">
									<label>Mata Uang</label>
									<select class="form-control selectpicker" name="mata_uang" id="mata_uang" required>
										<option value="" selected>-Pilih Mata Uang-</option>
										<?php foreach($mata_uang as $m): ?>
										<?php $mt	 = set_value('mata_uang') ? set_value('mata_uang') : $kurs['mata_uang'] ?>
										<?php $pilih = $mt == $m['kode_mu'] ? 'selected' : ''; ?>
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
											<span class="input-group-text icon-small">
												<i class="bi bi-calendar"></i>
											</span>
										</div>
										<input type="text" class="form-control datepicker" name="tanggal" id="tanggal" value="<?= set_value('tanggal') ? set_value('tanggal') : $kurs['tanggal'] ?>" required>
									</div>
									<?= form_error('tanggal', '<small class="text-danger">', '</small>') ?>
								</div>
							</div>
							
							<div class="form-group row">
								<div class="col">
									<label>Nilai Kurs</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text icon-small">Rp</span>
										</div>
										<input type="" class="form-control" name="nilai_kurs" id="nilai_kurs" value="<?= set_value('nilai_kurs') ? set_value('nilai_kurs') : $kurs['nilai_kurs'] ?>" required>
									</div>
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
