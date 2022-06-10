<div class="content container-fluid">
	<div class="content-title"><?=$title?></div>
	<form action="" method="POST">
		<input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
				<b><label for="user" class="col-form-label">Nama Lengkap</label></b>
				<div class="col">
				<input id="user" name="user" class="form-control" value="<?= $user['nama_lengkap'] ?>">

		
	<div class="content-body my-3">
		<div class="card shadow mb-4">
				<div class="card-header">
					<h6 class="mb-0">Daftar Perusahaan</h6>
				</div>
                <div class="card-body card_perusahaan">
                <table id="myTable" width=100% class="table table-bordered">
						<thead>
							<tr>
                                <th>No</th>
								<th>Nama Perusahaan</th>
								<th>Akses</th>
								
							</tr>
						</thead>
                        <tbody>
							
                            <?php $i=1; ?>
                                <?php foreach ($data_perusahaan as $kp) : ?>
                                <?php if( in_array($kp['id_perusahaan'], $akses)) {
									$pilih='selected'; 
								} else {
									$pilih='';
								}
								?>
								
                                    <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $kp['nama_perusahaan']; ?></td>
                                           <td>
										   <input type="checkbox" name="kode_perusahaan[]" id="centangSemua" value="<?= $kp['id_perusahaan']; ?>" <?php echo $pilih; ?> >
                                               </a>
                                           </td>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
								</form>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row mt-4">
				<div class="col">
					<button type="submit" class="btn btn-success" id="btn-submit">Simpan</button>
					<a href="<?= base_url('user/data_anggota');?>" class="btn btn-secondary">
						<span class="text">Batal</span>
					</a>
<div class="viewmodal" style="display: none;"></div>
