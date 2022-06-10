<div class="content container-fluid">
	<!-- <div class="row">
		<div class="col">
			<div class="content-title"><?=$title?></div>
		</div>
	</div> -->
	<?php
		$pilih = $this->session->userdata('laporan_penjualan');
	?>
	
	<div class="card shadow">
		<div class="card-body px-4">
			<h5 class="mb-4">Pilih Tampilan</h5>
			
			<form class="m-0" action="" method="post">
				<div class="row">
					<div class="col">
						<div class="form-group row">
							<div class="col">
								<label class="label-bold" for="jenis_transaksi">Jenis Transaksi</label>
								<select name="jenis_transaksi" id="jenis_transaksi" class="form-control selectpicker">
									<?php
										$p1 = $pilih['jenis_transaksi'] == 'penjualan' ? 'selected' : '';
										$r1 = $pilih['jenis_transaksi'] == 'retur' ? 'selected' : '';
									?>
									<option value="">Pilih Jenis Transaksi</option>
									<option value="penjualan" <?= $p1 ?>>Penjualan</option>
									<option value="retur" <?= $r1 ?>>Retur Penjualan</option>
								</select>
								<?= form_error('jenis_transaksi') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold" for="jenis_laporan">Jenis Laporan</label>
								<select name="jenis_laporan" id="jenis_laporan" class="form-control selectpicker">
									<option value="">Pilih Jenis Laporan</option>
									<option value="detail" <?= $pilih['jenis_laporan'] == 'detail' ? 'selected' : '' ?> >
										Laporan Detail
									</option>
									<option value="bulanan" <?= $pilih['jenis_laporan'] == 'bulanan' ? 'selected' : '' ?> >
										Laporan Bulanan
									</option>
								</select>
								<?= form_error('jenis_laporan') ?>
							</div>
						</div>
						
						<div class="form-row mb-3">
							<!-- input Tanggal Awal -->
							<div class="col">
								<label class="label-bold">Tanggal Awal</label>
								<div class="input-group">
									<div class="input-group-prepend btn-date">
										<span class="input-group-text">
											<i class="bi bi-calendar icon-small"></i>
										</span>
									</div>
									<input type="text" class="form-control datepicker" name="tanggal_awal" id="tanggal_awal" data-target="#err-tanggal_awal" min="<?= $min_date ?>" max="<?= $max_date ?>" placeholder="dd/mm/yyyy" value="<?= $min_date ?>" autocomplete="off">
								</div>
								<p class="mb-0" id="err-tanggal_awal"></p>
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
									<input type="text" class="form-control datepicker" name="tanggal_akhir" id="tanggal_akhir" data-target="#err-tanggal_akhir" min="<?= $min_date ?>" max="<?= $max_date ?>" placeholder="dd/mm/yyyy" value="<?= $max_date ?>" autocomplete="off">
								</div>
								<p class="mb-0" id="err-tanggal_akhir"></p>
							</div>
						</div>
					</div>
					
					<div class="col">
						<div class="form-group row">
							<div class="col">
								<label class="label-bold" for="jenis_pajak">Jenis Pajak</label>
								<select name="jenis_pajak" id="jenis_pajak" class="form-control selectpicker">
									<option value="">Semuanya</option>
									<option value="Include" <?= $pilih['jenis_pajak'] == 'Include' ? 'selected' : '' ?> >
										Include
									</option>
									<option value="Exclude" <?= $pilih['jenis_pajak'] == 'Exclude' ? 'selected' : '' ?> >
										Exclude
									</option>
									<option value="PPN" <?= $pilih['jenis_pajak'] == 'PPN' ? 'selected' : '' ?> >
										PPN
									</option>
									<option value="Non-PPN" <?= $pilih['jenis_pajak'] == 'Non-PPN' ? 'selected' : '' ?> >
										Non-PPN

									</option>
								</select>
								<?= form_error('jenis_pajak') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold" for="pelanggan">Pelanggan</label>
								<select name="pelanggan" id="pelanggan" class="form-control selectpicker" data-live-search="true" data-size="7">
									<option value="">Semua Pelanggan</option>
									<?php foreach($pelanggan as $p) : ?>
									<option value="<?= $p['kode_pelanggan'] ?>" <?= $pilih['pelanggan'] == $p['kode_pelanggan'] ? 'selected' : '' ?> >
										<?= $p['nama_pelanggan'] ?>
									</option>
									<?php endforeach ?>
								</select>
								<?= form_error('pelanggan') ?>
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col">
								<label class="label-bold" for="barang">Barang</label>
								<select name="barang" id="barang" class="form-control selectpicker" data-live-search="true" data-size="7">
									<option value="">Semua Barang</option>
									<?php foreach($barang as $b) : ?>
									<option value="<?= $b['kode_barang'] ?>" <?= $pilih['barang'] == $b['kode_barang'] ? 'selected' : '' ?> >
										<?= $b['nama_barang'] ?>
									</option>
									<?php endforeach ?>
								</select>
								<?= form_error('barang') ?>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row text-right mt-4">
					<div class="col">
						<button type="submit" class="btn btn-primary">Tampilkan</button>
					</div>
				</div>
			</form> 
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url() ?>asset/js/bootstrap-select.min.js"></script>
<script>
	$('.selectpicker').selectpicker()
</script>
