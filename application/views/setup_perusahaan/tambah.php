<div class="content container-fluid">
	<div class="row content-title">
		<div class="col-sm">
			<div class="mb-2 mb-sm-0"><?=$title?></div>
		</div>
			
		<div class="col-sm-auto">
			<a href="<?= base_url() ?>perusahaan" class="close">
				<h1 class="bi bi-x mb-0" style="line-height: .5;"></h1>
			</a>
		</div>
	</div>
	
	<form action="" method="post">
		<div class="card mb-4">
			<div class="card-body px-4">
				<div class="row" style="margin-bottom: -.9rem;">
					<div class="col">
						<div class="form-group row">
							<div class="col">
								<label>Pilih Perusahaan</label>
								<select class="form-control selectpicker" name="perusahaan" id="perusahaan" required>
									<option value="">Pilih Perusahaan</option>
									<?php foreach($perusahaan as $p): ?>
									<?php $pilih = set_value('perusahaan') ? 'selected' : ''; ?>
									<option value="<?= $p['kode_perusahaan'] ?>" <?=$pilih?>>
										<?= $p['kode_perusahaan'].' - '.$p['nama_perusahaan'] ?>
									</option>
									<?php endforeach ?>
								</select>
								<?= form_error('kode') ?>
							</div>  
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Tahun Buku</label>
								<input type="text" class="form-control datepicker" name="tahun" id="tahun" placeholder="Masukkan tahun buku" value="<?= set_value('tahun') ?>" required readonly>
								<?= form_error('tahun') ?>
							</div>  
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Tanggal Mulai</label>
								<div class="input-group">
									<div class="input-group-prepend btn-date">
										<span class="input-group-text">
											<i class="bi bi-calendar icon-small"></i>
										</span>
									</div>
									<input type="text" class="form-control datepicker" name="tgl_mulai" id="tgl_mulai" value="<?= set_value('tgl_mulai') ?>" placeholder="Masukkan Tanggal" autocomplete="off" readonly required>
								</div>
								<?= form_error('tgl_mulai') ?>
							</div>  
						</div>
						
						<!-- <div class="form-group row">
							<div class="col">
								<label>Nama Database</label>
								<input type="text" class="form-control" name="database" id="database" placeholder="Masukkan nama database" value="<?= set_value('database') ?>" required>
								<?= form_error('database') ?>
							</div>
						</div> -->
					</div>
				</div>
			</div>
			<!-- card-body -->
		</div>
		<!-- card -->
		
		<div class="row">
			<div class="col">
				<button type="submit" class="btn btn-success">Submit</button>
				<a href="<?= base_url() ?>perusahaan" class="btn btn-secondary">
					<span class="text">Batal</span>
				</a>
			</div>
		</div>					
	</form> 
</div>
<!-- container-fluid -->

<script type="text/javascript" src="<?= base_url() ?>asset/js/datepicker.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/bootstrap-select.min.js"></script>
<script>
	$('.selectpicker').selectpicker();
	
	$('#tgl_mulai').datepicker({
		format		: 'dd-mm-yyyy',
		autoHide	: true,
	})
	
	$('#tahun').datepicker({
		format		: 'yyyy',
		autoHide	: true,
	})
</script>