<div class="modal-header">
	<h4 class="modal-title">Filter Laporan</h4>
</div>

<div class="modal-body">
	<?php $filter = $this->session->userdata('laporan_pembelian') ?>
	
	<div class="row" id="formDisplay">
		<div class="col p-2">
			<div class="form-group row">
				<div class="col">
					<label class="label-bold" for="jenis_transaksi">Jenis Transaksi</label>
					<select name="jenis_transaksi" id="jenis_transaksi" data-err="#err-jenis_transaksi" class="form-control selectpicker">
						<option value="">Pilih Jenis Transaksi</option>
						<option value="pembelian" <?= $filter['jenis_transaksi'] == 'pembelian' ? 'selected' : ''; ?>>
							Pembelian
						</option>
						<option value="retur" <?= $filter['jenis_transaksi'] == 'retur' ? 'selected' : ''; ?>>
							Retur Pembelian
						</option>
					</select>
					<small class="text-danger" id="err-jenis_transaksi"></small>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col">
					<label class="label-bold" for="jenis_laporan">Jenis Laporan</label>
					<select name="jenis_laporan" id="jenis_laporan" data-err="#err-jenis_laporan" class="form-control selectpicker">
						<option value="">Pilih Jenis Laporan</option>
						<option value="detail" <?= $filter['jenis_laporan'] == 'detail' ? 'selected' : '' ?> >
							Laporan Detail
						</option>
						<option value="bulanan" <?= $filter['jenis_laporan'] == 'bulanan' ? 'selected' : '' ?> >
							Laporan Bulanan
						</option>
					</select>
					<small class="text-danger" id="err-jenis_laporan"></small>
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
		</div>
		
		<div class="col p-2">
			<div class="form-group row">
				<div class="col">
					<label class="label-bold" for="jenis_pajak">Jenis Pajak</label>
					<select name="jenis_pajak" id="jenis_pajak" data-err="#err-jenis_pajak" class="form-control selectpicker">
						<option value="">Semua</option>
						<option value="Include" <?= $filter['jenis_pajak'] == 'Include' ? 'selected' : '' ?> >
							Include
						</option>
						<option value="Exclude" <?= $filter['jenis_pajak'] == 'Exclude' ? 'selected' : '' ?> >
							Exclude
						</option>
						<option value="PPN" <?= $filter['jenis_pajak'] == 'PPN' ? 'selected' : '' ?> >
							PPN
						</option>
						<option value="Non-PPN" <?= $filter['jenis_pajak'] == 'Non-PPN' ? 'selected' : '' ?> >
							Non-PPN
						</option>
					</select>
					<small class="text-danger" id="err-jenis_pajak"></small>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col">
					<label class="label-bold" for="supplier">Supplier</label>
					<select name="supplier" id="supplier" data-err="#err-supplier" class="form-control selectpicker" data-live-search="true" data-size="7">
						<option value="">Semua Supplier</option>
						<?php foreach($supplier as $p) : ?>
						<option value="<?= $p['kode_supplier'] ?>" <?= $filter['supplier'] == $p['kode_supplier'] ? 'selected' : '' ?> >
							<?= $p['nama_supplier'] ?>
						</option>
						<?php endforeach ?>
					</select>
					<small class="text-danger" id="err-supplier"></small>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col">
					<label class="label-bold" for="barang">Barang</label>
					<select name="barang" id="barang" data-err="#err-barang" class="form-control selectpicker" data-live-search="true" data-size="7">
						<option value="">Semua Barang</option>
						<?php foreach($barang as $b) : ?>
						<option value="<?= $b['kode_barang'] ?>" <?= $filter['barang'] == $b['kode_barang'] ? 'selected' : '' ?> >
							<?= $b['nama_barang'] ?>
						</option>
						<?php endforeach ?>
					</select>
					<small class="text-danger" id="err-barang"></small>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row text-right mb-3">
		<div class="col">
			<button type="button" class="btn btn-primary" onclick="tampilkan()">Tampilkan</button>
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
		
		// validasi jenis_transaksi
		if ( $('#jenis_transaksi').val() == '' ) {
			$('#err-jenis_transaksi').html('Belum dipilih');
			tampil = false;
		}
		// validasi jenis_laporan
		if ( $('#jenis_laporan').val() == '' ) {
			$('#err-jenis_laporan').html('Belum dipilih');
			tampil = false;
		}
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
				url		: '<?= base_url() ?>laporan_pembelian/display',
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
