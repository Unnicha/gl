<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<div class="content-body mb-4">
		<div class="card shadow">
			<div class="card-body px-4">
				<form action="" method="post">
					<div class="form-group row">
						<label class="col-sm-2 col-form-label" for="kode_akun">Kode Akun</label>
						<div class="col">
							<input type="text" class="form-control" name="kode_akun" id="kode_akun" placeholder="Masukkan kode akun" value="<?= set_value('kode_akun') ?>" required>
							<small id="cekKode"></small>
							<?= form_error('kode_akun', '<small class="text-danger">', '</small>') ?>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-2 col-form-label" for="nama_akun">Nama Akun</label>
						<div class="col">
							<input type="text" class="form-control" name="nama_akun" id="nama_akun" placeholder="Masukkan nama akun" value="<?= set_value('nama_akun') ?>" required>
							<?= form_error('nama_akun', '<small class="text-danger">', '</small>') ?>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-form-label col-lg-2">Golongan</label>
						<div class="col">
							<div class="radio-option">
								<?php 
									$neraca		= set_value('gol') == 'NERACA' ? 'checked' : '';
									$rugilaba	= set_value('gol') == 'RUGILABA' ? 'checked' : '';
								?>
								<div class="form-check form-check-inline ">
									<input class="form-check-input" type="radio" name="gol" id="neraca" value="NERACA" <?=$neraca?>>
									<label class="form-check-label" for="neraca">NERACA</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="gol" id="rugilaba" value="RUGILABA" <?=$rugilaba?>>
									<label class="form-check-label" for="rugilaba">RUGI LABA</label>
								</div>
							</div>
							<?= form_error('gol', '<small class="text-danger">', '</small>') ?>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Tipe</label>
						<div class="col">
							<select class="form-control selectpicker" name="tipe" id="tipe" required>
								<option value="">Pilih tipe</option>
								<?php foreach($tipe as $tp) : ?>
								<option><?= $tp ?></option>
								<?php endforeach ?>
							</select>
							<?= form_error('tipe', '<small class="text-danger">', '</small>') ?>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Jenis</label>
						<div class="col">
							<select class="form-control selectpicker" name="jenis" id="jenis" data-live-search="true" data-size="5" required>
								<option value="">---</option>
							</select>
							<?= form_error('jenis', '<small class="text-danger">', '</small>') ?>
						</div>
					</div>
					
					<div class="form-group row">
						<label  class="col-sm-2 col-form-label" for="induk">Pilih Induk</label>
						<div class="col">
							<select name="induk" id="induk" class="form-control selectpicker" data-live-search="true" data-size="5">
								<option value="">---</option>
							</select>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-2 col-form-label" for="tingkat">Tingkat</label>
						<div class="col">
							<input type="number" min="1" max="5" class="form-control tingkat" name="tingkat" id="tingkat" placeholder="Masukkan tingkat rekening" value="<?= set_value('tingkat') ?>" required>
							<?= form_error('tingkat', '<small class="text-danger">', '</small>') ?>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-form-label col-lg-2">Saldo Normal</label>
						<div class="col">
							<div class="radio-option">
								<?php 
									$debet	= set_value('saldo_normal') == 'Debit' ? 'checked' : '';
									$kredit	= set_value('saldo_normal') == 'Kredit' ? 'checked' : '';
								?>
								<div class="form-check form-check-inline ">
									<input class="form-check-input" type="radio" name="saldo_normal" id="debet" value="Debit" <?=$debet?>>
									<label class="form-check-label" for="debet">Debit</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="saldo_normal" id="kredit" value="Kredit" <?=$kredit?>>
									<label class="form-check-label" for="kredit">Kredit</label>
								</div>
							</div>
							<?= form_error('saldo_normal', '<small class="text-danger">', '</small>') ?>
						</div>
					</div>
					
					<div class="form-group row">
						<label  class="col-sm-2 col-form-label">Saldo Awal</label>
						<div class="col">
							<div class="form-row">
								<div class="col-auto">
									<select name="nilai_saldo_awal" id="nilai_saldo_awal" class="form-control">
										<option value="Debit">Debit</option>
										<option value="Kredit">Kredit</option>
									</select>
								</div>
								
								<div class="col">
									<input type="text" class="form-control" name="saldo_awal" id="saldo_awal" placeholder="Masukkan jumlah saldo awal" value="<?= set_value('saldo_awal') ?>" required>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row mt-4">
						<div class="col">
							<button type="submit" class="btn btn-success">Submit</button>
							<a href="<?= base_url('akun');?>" class="btn btn-secondary">
								<span class="text">Batal</span>
							</a>
						</div>
					</div>
				</form> 
			</div>
			<!-- card-body -->
		</div>
		<!-- card -->
	</div>
	<!-- content-body -->
</div>
<!-- container-fluid -->

<script type="text/javascript" src="<?= base_url() ?>asset/js/bootstrap-select.min.js"></script>
<script>
	$(document).ready(function(){
		$('.selectpicker').selectpicker();
		
		$('#kode_akun').keyup(function() {
			$.ajax({
				url : "<?= base_url('akun/cekKode');?>",
				method : "POST",
				data : {
					kode_akun : $(this).val(),
				},
				success: function(data){
					var result = jQuery.parseJSON(data);
					$('#cekKode').addClass(result.class).html(result.message);
				},
			});
		})
		
		function getJenis() {
			$.ajax({
				url : "<?= base_url('akun/getJenis');?>",
				method : "POST",
				data : {tipe: $('#tipe').val()},
				success: function(data){
					$('#jenis').html(data).selectpicker('refresh')
								.selectpicker('val', '<?=set_value('jenis')?>');
				},
			});
		}
		getJenis();
		
		function pilihInduk() {
			var tipe = $('#tipe').val();
			$.ajax({
				url : "<?=base_url('akun/getInduk');?>",
				method : "POST",
				data : { tipe: tipe },
				success: function(data){
					if(tipe == 'Anak')
					$('#induk').attr('required', 'required');
					$('#induk').html(data).selectpicker('refresh')
								.selectpicker('val', '<?=set_value('jenis')?>');
				},
			});
		}
		pilihInduk()
		
		$('#tipe').change(function(){ 
			getJenis();
			pilihInduk();
			
			if( $(this).val() == 'Induk' ) {
				$('#saldo_awal').attr('readonly', 'readonly')
								.attr('placeholder', '-')
								.removeAttr('required')
			} else {
				$('#saldo_awal').removeAttr('readonly')
								.attr('required', 'required')
								.attr('placeholder', 'Masukkan jumlah saldo awal')
			}
		});
	});
</script>
