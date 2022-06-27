<div class="modal-header">
	<h4 class="modal-title">Filter Laporan</h4>
</div>

<?php $filter = $this->session->userdata('laporan_hutang') ?>

<div class="modal-body">
	<div class="row mb-3" id="formDisplay">
		<div class="col p-0">
			<div class="form-group row">
				<div class="col">
					<label class="label-bold" for="jenis_laporan">Jenis Laporan</label>
					<select name="jenis_laporan" id="jenis_laporan" data-err="#err-jenis_laporan" class="form-control selectpicker">
						<option value="">Pilih Jenis Laporan</option>
						<option value="hutang" <?= $filter['jenis_laporan'] == 'hutang' ? 'selected' : ''; ?>>
							Laporan Hutang
						</option>
						<option value="bayar" <?= $filter['jenis_laporan'] == 'bayar' ? 'selected' : ''; ?>>
							Laporan Pembayaran Hutang
						</option>
					</select>
					<small class="text-danger" id="err-jenis_laporan"></small>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col">
					<label class="label-bold" for="akun_asal">Akun Hutang</label>
					<select name="akun_asal" id="akun_asal" data-err="#err-akun_asal" class="form-control selectpicker" data-live-search="true" data-size="7">
						<option value="">Pilih Akun Hutang</option>
						<?php foreach($akun_asal as $a) : ?>
						<option 
							value="<?= $a['kode_akun'] ?>" 
							data-subtext="<?= $a['kode_akun'] ?>"
							<?= $filter['akun_asal'] == $a['kode_akun'] ? 'selected' : '' ?> 
						>
							<?= $a['nama_akun'] ?>
						</option>
						<?php endforeach ?>
					</select>
					<small class="text-danger" id="err-akun_asal"></small>
				</div>
			</div>
			
			<div class="form-row mx-2 mb-3">
				<!-- input Tanggal Awal -->
				<div class="col">
					<label class="label-bold">Tanggal Awal</label>
					<div class="input-group">
						<div class="input-group-prepend btn-date">
							<span class="input-group-text">
								<i class="bi bi-calendar icon-small"></i>
							</span>
						</div>
						<input type="text" name="tanggal_awal" id="tanggal_awal" data-err="#err-tanggal_awal" class="form-control datepicker" data-target="#err-tanggal_awal" min="<?= $min_date ?>" max="<?= $max_date ?>" placeholder="dd/mm/yyyy" value="<?= $filter ? $filter['tanggal_awal'] : $min_date ?>" autocomplete="off">
					</div>
					<small class="text-danger" id="err-tanggal_awal"></small>
				</div>
				
				<!-- input Tanggal Akhir -->
				<div class="col">
					<label class="label-bold">Tanggal Akhir</label>
					<div class="input-group">
						<div class="input-group-prepend btn-date">
							<span class="input-group-text">
								<i class="bi bi-calendar icon-small"></i>
							</span>
						</div>
						<input type="text" name="tanggal_akhir" id="tanggal_akhir" data-err="#err-tanggal_akhir" class="form-control datepicker" data-target="#err-tanggal_akhir" min="<?= $filter ? $filter['tanggal_awal'] : $min_date ?>" max="<?= $max_date ?>" placeholder="dd/mm/yyyy" value="<?= $filter ? $filter['tanggal_akhir'] : $max_date ?>" autocomplete="off">
					</div>
					<small class="text-danger" id="err-tanggal_akhir"></small>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col">
					<label class="label-bold" for="mata_uang">Mata Uang</label>
					<select name="mata_uang" id="mata_uang" data-err="#err-mata_uang" class="form-control selectpicker" data-live-search="true" data-size="5">
						<option value="">Semua</option>
						<?php foreach($mata_uang as $u) : ?>
						<option value="<?= $u['kode_mu'] ?>" <?= $filter['mata_uang'] == $u['kode_mu'] ? 'selected' : '' ?> >
							<?= $u['kode_mu'].' - '.$u['nama_mu'] ?>
						</option>
						<?php endforeach ?>
					</select>
					<small class="text-danger" id="err-mata_uang"></small>
				</div>
			</div>
			
			<div class="row text-right mt-4">
				<div class="col">
					<button type="button" class="btn btn-primary" onclick="tampilkan()">Tampilkan</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url() ?>asset/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/datepicker.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>asset/js/jquery.mask.min.js"></script>
<script>
	$('.selectpicker').selectpicker()
	
	$('.datepicker').each(function() {
		$(this).datepicker({
			format: 'dd/mm/yyyy',
			autoHide: true,
			trigger: $(this).parent(),
			zIndex: 1150,
			// date: $(this).attr('min'),
			startDate: $(this).attr('min'),
			endDate: $(this).attr('max'),
		})
	})
	
	function maskDate() {
		$('.datepicker').unmask().mask('00/00/0000', {
			placeholder: 'dd/mm/yyyy',
		})
	}
	maskDate()
	
	$('#tanggal_awal').change(function() {
		var value = $(this).val();
		var min = $(this).attr('min');
		var max = $(this).attr('max');
		
		value = value.split('/').reverse().join('-');
		min = min.split('/').reverse().join('-');
		max = max.split('/').reverse().join('-');
		
		if ( value < min ) {
			$(this).val( $(this).attr('min') );
			$('#tanggal_akhir').attr('min', $(this).attr('min'))
		} else if ( value > max ) {
			$(this).val( $(this).attr('max') );
			$('#tanggal_akhir').attr('min', $(this).attr('max'))
		}
		validasi_tanggal_akhir()
	})
	
	$('#tanggal_akhir').change(function() {
		validasi_tanggal_akhir()
	})
	
	function validasi_tanggal_akhir() {
		var field = $('#tanggal_akhir');
		var value = field.val();
		var min = field.attr('min');
		var max = field.attr('max');
		
		value = value.split('/').reverse().join('-');
		min = min.split('/').reverse().join('-');
		max = max.split('/').reverse().join('-');
		
		if ( value < min ) {
			field.val( field.attr('min') );
		} else if ( value > max ) {
			field.val( field.attr('max') );
		}
	}
	
	function tampilkan() {
		var tampil = true;
		var value = '';
		var min = '';
		var max = '';
		$('.text-danger').html('');
		
		// validasi jenis_laporan
		if ( $('#jenis_laporan').val() == '' ) {
			$('#err-jenis_laporan').html('Belum dipilih');
			tampil = false;
		}
		// validasi akun_asal
		// if ( $('#akun_asal').val() == '' ) {
		// 	$('#err-akun_asal').html('Belum dipilih');
		// 	tampil = false;
		// }
		// validasi tanggal_awal
		if ( $('#tanggal_awal').val() == '' ) {
			$('#err-tanggal_awal').html('Belum dipilih');
			tampil = false;
		}
		// validasi tanggal_akhir
		if ( $('#tanggal_akhir').val() == '' ) {
			$('#err-tanggal_akhir').html('Belum dipilih');
			tampil = false;
		}
		
		if (tampil) {
			var counter	= 0;
			var id		= []
			var value	= []
			$('#formDisplay .form-control').each(function() {
				if ($(this).attr('id') != undefined) {
					id[counter]	= $(this).attr('id');
					value[counter] = $(this).val();
					counter++
				}
			})
			
			$.ajax({
				type	: 'POST',
				url		: '<?= base_url() ?>laporan_hutang/display',
				data	: {
					'id' : id,
					'value' : value,
				},
				success	: function(data) {
					$(".modalGanti").modal('hide');
					$("#display").html(data);
				}
			})
		}
	}
</script>
