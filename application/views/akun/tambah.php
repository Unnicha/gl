<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	
	<form action="" method="post">
		<div class="card">
			<div class="card-body">
				<div class="row row-card">
					<div class="col-lg">
						<!-- Golongan -->
						<div class="form-group row">
							<div class="col">
								<label>Golongan</label>
								<select class="form-control selectpicker" name="golongan" id="golongan" required>
									<option value="">Pilih Golongan</option>
									<?php foreach($golongan as $g) : ?>
									<?php $pilih = (set_value('golongan') == $g) ? 'selected' : ''; ?>
									<option value="<?=$g?>" <?=$pilih?>><?= $g ?></option>
									<?php endforeach ?>
								</select>
								<?= form_error('golongan') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Tipe</label>
								<select class="form-control selectpicker" name="tipe" id="tipe" required>
									<option value="">Pilih tipe</option>
									<?php foreach($tipe as $tp) : ?>
									<?php $pilih = (set_value('tipe') == $tp) ? 'selected' : ''; ?>
									<option value="<?=$tp?>" <?=$pilih?>><?= $tp ?></option>
									<?php endforeach ?>
								</select>
								<?= form_error('tipe') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Tingkat</label>
								<input type="number" min="1" max="3" class="form-control tingkat" name="tingkat" id="tingkat" placeholder="Masukkan tingkat akun" value="<?= set_value('tingkat') ?>" required>
								<?= form_error('tingkat') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Jenis Akun</label>
								<select class="form-control selectpicker" name="jenis" id="jenis" data-live-search="true" data-size="5" required>
									<option value="">Pilih Jenis</option>
									<?php foreach ($jenis as $j) : ?>
										<?php $pilih = (set_value('jenis') == $j['id_jenis']) ? 'selected' : '' ?>
										<option value="<?= $j['id_jenis'] ?>" <?=$pilih?> ><?= $j['nama_jenis'] ?></option>
									<?php endforeach ?>
								</select>
								<?= form_error('jenis') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Pilih Induk</label>
								<select name="induk" id="induk" class="form-control selectpicker" data-live-search="true" data-size="5">
									<option value="">Pilih Induk</option>
									<?php foreach ($induk as $i) : ?>
										<?php $pilih = ($i['kode_akun'] == set_value('induk')) ? 'selected' : '' ?>
										<option value="<?= $i['kode_akun'] ?>" <?=$pilih?> ><?= $i['kode_akun'] .' - '. $i['nama_akun'] ?></option>
									<?php endforeach ?>
								</select>
								<?= form_error('induk') ?>
							</div>
						</div>
					</div>
					
					<div class="col">
						<div class="form-group row">
							<div class="col">
								<label>Kode Akun</label>
								<input type="text" class="form-control" name="kode_akun" id="kode_akun" placeholder="Masukkan kode akun" value="<?= set_value('kode_akun') ?>" autocomplete="off" oninput="let p=this.selectionStart;this.value=this.value.toUpperCase();this.setSelectionRange(p, p);" required>
								<p id="cekKode" class="mb-0"><?= form_error('kode_akun') ?></p>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Nama Akun</label>
								<input type="text" class="form-control" name="nama_akun" id="nama_akun" placeholder="Masukkan nama akun" value="<?= set_value('nama_akun') ?>" required>
								<?= form_error('nama_akun') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Saldo Normal</label>
								<select class="form-control selectpicker" name="saldo_normal" id="saldo_normal" data-noneSelectedText="Pilih Jenis Saldo">
									<option value="">Pilih Jenis Saldo</option>
									<option value="Debit">Debit</option>
									<option value="Kredit">Kredit</option>
								</select>
								<?= form_error('saldo_normal') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label>Saldo Awal</label>
								<div class="form-row">
									<div class="col-auto">
										<select class="form-control selectpicker" name="nilai_saldo_awal" id="nilai_saldo_awal">
											<option value="Debit">Debit</option>
											<option value="Kredit">Kredit</option>
										</select>
									</div>
									
									<div class="col">
										<input type="text" class="form-control mask_money" name="saldo_awal" id="saldo_awal" placeholder="Masukkan jumlah saldo awal" value="<?= set_value('saldo_awal') ?>" required>
										<?= form_error('saldo_awal') ?>
									</div>
								</div>
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
				<button type="submit" name="submit" class="btn btn-success">Submit</button>
				<a href="<?= base_url() ?>akun" class="btn btn-secondary">
					<span class="text">Batal</span>
				</a>
			</div>
		</div>
	</form> 
</div>
<!-- container-fluid -->

<script type="text/javascript" src="<?= base_url() ?>asset/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/jquery.mask.min.js"></script>
<script>
	$(document).ready(function() {
		$('.selectpicker').selectpicker();
		
		$('#golongan').change(function() {
			getJenis();
		})
		$('#tipe').change(function() { 
			getJenis();
			saldoVal()
		});
		$('#tingkat').keyup(function() { pilihInduk() }).change(function(){ pilihInduk() });
		$('#kode_akun').keyup(function() {
			cekKode()
		})
		// $('#induk').change(function() {
		// 	if ($(this).val() != '')
		// 	$('#kode_akun').val( $(this).val() );
		// })
		
		function maskInput() {
			$('.mask_money').mask('#.##0,00', {
				reverse: true,
				placeholder: '0,00',
			})
		}
		maskInput()
		
		// ganti option Jenis Akun
		function getJenis() {
			var tipe = $('#tipe').val();
			if (tipe != '') {
				var golongan = $('#golongan').val()
				if (golongan == 'LABARUGI' && tipe == 'Induk') {
					$('#jenis').html('<option value="">---</option>')
						.selectpicker('refresh').selectpicker('val', '')
						.removeAttr('required');
				} else {
					var jenis = $('#jenis').val();
					$.ajax({
						url : "<?= base_url() ?>akun/getJenis",
						method : "POST",
						data : {
							'tipe'	: tipe,
							'value'	: jenis,
							'gol'	: golongan,
						},
						success : function(data) {
							$('#jenis').html(data).selectpicker('refresh').selectpicker('val', jenis);
						},
					});
				}
			}
		}
		getJenis();
		
		// ganti option Induk
		function pilihInduk() {
			var tipe = $('#tipe').val();
			var val = '<?= set_value('induk') ?>';
			$.ajax({
				url : "<?= base_url() ?>akun/getInduk",
				method : "POST",
				data : {
					tipe	: tipe,
					value	: val,
					tingkat	: $('#tingkat').val(),
				},
				success: function(data) {
					$('#induk').html(data).selectpicker('refresh')
								.selectpicker('val', val);
				},
				complete : function() {
					saldoVal();
				}
			});
		}
		
		function cekKode() {
			$('#cekKode').removeAttr('class').html('');
			var format		= /^[a-zA-Z0-9.]+$/;
			var kode_akun	= $('#kode_akun').val().trim();
			if(kode_akun != '') {
				if (kode_akun.match(format) && kode_akun.endsWith(".") == false) {
					$.ajax({
						url : "<?= base_url() ?>akun/cekKode",
						method : "POST",
						data : {
							kode_akun : $('#kode_akun').val(),
						},
						success: function(data){
							$('#cekKode').html(data);
						},
					});
				} else {
					$('#cekKode').html('<small class="text-danger">Format belum benar</small>');
				}
			} else {
				$('#cekKode').html('<small class="text-danger">Kode Akun harus diisi</small>');
			}
		}
		
		function saldoVal() {
			var option = '<option value="">Pilih Jenis Saldo</option>'+
				'<option value="Debit">Debit</option>'+
				'<option value="Kredit">Kredit</option>';
			
			if( $('#tipe').val() == 'Induk' ) {
				$('#saldo_awal').removeAttr('required')
					.attr('readonly', 'readonly').attr('placeholder', '-')
				$('#saldo_normal').html('<option value="">-</option>')
					.selectpicker('refresh').selectpicker('val', '');
			} else {
				$('#saldo_awal').removeAttr('readonly')
					.attr('required', 'required').attr('placeholder', 'Masukkan jumlah saldo awal')
				$('#saldo_normal').html(option).selectpicker('refresh').selectpicker('val', '')
			}
			$('#saldo_normal').selectpicker('val', '');
		}
	});
</script>
