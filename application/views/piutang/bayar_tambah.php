<!-- Modal Header -->
<div class="modal-header">
	<h5 class="modal-title"><?=$title?></h5>
	
	<button type="button" class="close" data-dismiss="modal">
		<span aria-hidden="true">&times;</span>
		<span class="sr-only">Close</span>
	</button>
</div>

<!-- Modal Body -->
<div class="modal-body">
	<form action="" method="post" name="formProduk">
		<!-- <input type="hidden" name="totalBayar" id="totalBayar" value="<?=$totalBayar?>"> -->
		<input type="hidden" name="id_row" id="id_row" value="<?=$id_row?>">
		
		<div class="row">
			<div class="col">
				<table id="myTable" width=100% class="table table-list table-striped table-bordered nowrap">
					<thead class="text-center">
						<tr>
							<th>No.</th>
							<th>No. Invoice</th>
							<th>Tanggal</th>
							<th>Jatuh Tempo</th>
							<th>Mata Uang</th>
							<th>Tagihan</th>
							<th>Pilih</th>
						</tr>
					</thead>
					
					<tbody class="text-center">
						<?php if ($penjualan) : ?>
							<?php $num = 0; ?>
							<?php foreach ($penjualan as $p) : ?>
								<tr>
									<td><?= ++$num ?>.</td>
									<td><?= $p['faktur_jual'] ?></td>
									<td><?= $p['tanggal_transaksi'] ?></td>
									<td><?= $p['jatuh_tempo'] ?></td>
									<td><?= $p['mata_uang'] ?></td>
									<td class="text-right"><?= $p['jumlah_tagihan'] ?></td>
									<td>
										<div class="form-check">
											<input type="checkbox" name="faktur[]" id="faktur<?=$num?>" class="form-check-input" value="<?= $p['faktur_jual'] ?>" <?= $p['status'] ?>>
											<label class="form-check-label" for="faktur<?=$num?>">Pilih</label>
										</div>
									</td>
								</tr>
							<?php endforeach ?>
						<?php else : ?>
							<tr>
								<td colspan="7" class="text-center">Tidak ada transaksi</td>
							</tr>
						<?php endif ?>
					</tbody>
				</table>
				
				<!-- Error Message -->
				<small class="text-muted" id="err-invoice">Hanya bisa memilih transaksi dengan mata uang yang sama</small>
			</div>
		</div>
	</form>
	
	<div class="row text-right">
		<div class="col">
			<button type="button" class="btn btn-primary submitBtn" onclick="submitInvoice()">Tambahkan</button>
		</div>
	</div>
</div>



<div class="d-none" id="base_url"><?= base_url() ?></div>
<div class="d-none" id="formAction">add</div>
<script type="text/javascript" src="<?= base_url('asset/js/form/piutang_bayar.js') ?>"></script>
