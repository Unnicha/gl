<div class="content container-fluid">
	<div class="row">
		<!-- Page Heading -->
		<div class="col">
			<div class="content-title"><?= $title ?></div>
		</div>
		<div class="col-auto">
			<a href="<?= base_url('penamaan/tambah') ?>" class="btn btn-primary">
				<i class="bi-plus-lg"></i>
				Add
			</a>
		</div>
	</div>
	
	<div class="table-responsive">
		<table class="table table-list table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th>No</th>
					<th>Kode Rekening</th>
					<th>Nama Rekening</th>
					<th>Kode Penamaan</th>
					<th>Aksi</th>
				</tr>
			</thead>
		   
			<tbody>
				<?php $i=1; ?>
				<?php foreach($penamaan as $p) : ?>
				<tr>
					<td><?= $i++; ?></td>
					<td><?= $brt['no_rekening'] ?></td>
					<td><?= $brt['nama'] ?></td>
					<td><?= $brt['penamaan'] ?></td>
					<td>
					<a href="<?= base_url('penamaan/edit/'.$p['id']) ?>" class="badge badge-success">
						<i class="fas fa-edit"></i>
						Edit</a>
					<a href="<?= base_url('penamaan/delete/'.$p['id']) ?> " class="badge badge-danger" onclick="return confirm('Apakah Anda Yakin Akan Menghapus Data Supplier?');">
					   <i class="far fa-trash-alt"></i>
					   Hapus</a>
					</td>
					
				</tr>
				<?php endforeach; ?>
		  
			</tbody>
		</table>
	</div>
</div>