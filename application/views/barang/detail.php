<div class="modal-header px-4">
	<h4 class="modal-title"><?= $title ?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>

<div class="modal-body p-3">
	<div class="container-fluid p-0">
		<div class="row">
			<div class="col">
				<table class="table table-detail table-striped mb-4">
					<tbody>
						<tr>
                        <td scope="row" class="title">Kode Barang</td>
							<td><?=$barang['kode_barang']?></td>
						</tr>
                        <td scope="row" class="title">Nama Barang</td>
							<td><?=$barang['nama_barang']?></td>
						</tr>
                        <td scope="row" class="title">Satuani</td>
							<td><?=$barang['satuan']?></td>
						</tr>
                        <td scope="row" class="title">Stock Awal</td>
							<td><?=$barang['stock_awal']?></td>
						</tr>
                        <td scope="row" class="title">Nilai Awal</td>
							<td><?=$barang['nilai_awal']?></td>
						</tr>
                        <td scope="row" class="title">Jumlah</td>
							<td><?=$barang['jumlah']?></td>
						</tr>
                        <td scope="row" class="title">Proses</td>
							<td><?=$barang['proses']?></td>
						</tr>
                        </tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	$('[data-toggle="tooltip"]').mouseover(function() {
		$(this).tooltip();
	});
</script>