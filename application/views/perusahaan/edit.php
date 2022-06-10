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
		<input type="hidden" name="kode_perusahaan" id="kode_perusahaan" value="<?= $perusahaan['kode_perusahaan'] ?>">
		
		<div class="card mb-4">
			<div class="card-body px-4">
				<div class="row" style="margin-bottom: -.9rem;">
					<div class="col">
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Kode Perusahaan</label>
							<div class="col">
								<div class="form-inline">
									<input type="text" class="form-control " name="kode_perusahaan" id="kode_perusahaan" value="<?= $perusahaan['kode_perusahaan'] ?>" required readonly>
									
									<!-- <small class="text-muted px-3">3 huruf</small> -->
								</div>
								<?= form_error('kode') ?>
							</div>  
						</div>
						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Nama Perusahaan</label>
							<div class="col">
								<input type="text" class="form-control" name="nama_perusahaan" id="nama_perusahaan" placeholder="Masukkan nama perusahaan" value="<?= set_value('nama_perusahaan') ? set_value('nama_perusahaan') : $perusahaan['nama_perusahaan'] ?>" required>
								<?= form_error('nama_perusahaan') ?>
							</div>  
						</div>
						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Alamat</label>
							<div class="col">
								<textarea class="form-control" name="alamat" id="alamat" placeholder="Masukkan alamat" required><?= set_value('alamat') ? set_value('alamat') : $perusahaan['alamat'] ?></textarea>
								<?= form_error('alamat') ?>
							</div>  
						</div>
						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Telepon</label>
							<div class="col">
								<input type="text" class="form-control phone-mask" name="tlp" id="tlp" placeholder="Masukkan nomor telpon" value="<?= set_value('tlp') ? set_value('tlp') : $perusahaan['tlp'] ?>" required>
								<?= form_error('tlp') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Fax</label>
							<div class="col">
								<input type="text" class="form-control phone-mask" name="fax" id="fax" placeholder="Masukkan nomor fax" value="<?= set_value('fax') ? set_value('fax') : $perusahaan['fax'] ?>" required>
								<?= form_error('fax') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">NPWP</label>
							<div class="col">
								<input type="text" class="form-control" name="npwp" id="npwp" placeholder="Masukkan NPWP" value="<?= set_value('npwp') ? set_value('npwp') : $perusahaan['npwp'] ?>" required>
								<?= form_error('npwp') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Kode Pajak</label>
							<div class="col">
								<input type="text" class="form-control" name="kode_pajak" id="kode_pajak" placeholder="Masukkan kode pajak" value="<?= set_value('kode_pajak') ? set_value('kode_pajak') : $perusahaan['kode_pajak'] ?>" required>
								<?= form_error('kode_pajak') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Tanggal PKP</label>
							<div class="col">
								<div class="input-group">
									<div class="input-group-prepend btn-date">
										<span class="input-group-text">
											<i class="bi bi-calendar icon-small"></i>
										</span>
									</div>
									<input type="text" class="form-control datepicker" name="tgl_pkp" id="tgl_pkp" value="<?= set_value('tgl_pkp') ? set_value('tgl_pkp') : $perusahaan['tgl_pkp'] ?>" placeholder="Masukkan Tanggal" autocomplete="off" readonly required>
								</div>
								<?= form_error('tgl_pkp') ?>
							</div>
						</div>
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
<script type="text/javascript" src="<?= base_url() ?>asset/js/jquery.mask.min.js"></script>
<script>
	$('.selectpicker').selectpicker();
	
	$('.datepicker').each(function() {
		$(this).datepicker({
			format		: 'dd-mm-yyyy',
			autoHide	: true,
			trigger		: $(this).parent(),
			// autoPick	: true,
		})
	})
	
	$('#npwp').mask('00.000.000.0-000.000', {placeholder: '00.000.000.0-000.000'})
	$('.phone-mask').mask('000-0000-0000', {placeholder: '000-0000-0000'})
	
	function maskInput() {
		$('.mask_money').mask('#.##0,00', {
			reverse: true,
			placeholder: '0,00',
		})
	}
	maskInput()
</script>