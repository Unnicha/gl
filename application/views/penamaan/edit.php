<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">  <i class="fas fa-edit"></i>  <?= $title; ?></h1>
<div class="card shadow mb-4">
                    <div class="card-header py-3">
                       <h6 class="m-0 font-weight-bold text-primary">Edit Rekening Lawan</h6>
                    </div>
                    <div class="card-body">

                    <form action="<?php base_url('penamaan/edit'); ?><?= $penamaan['id'];?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $penamaan['id'];?>">

                    <div class="form-group">
                        <label for="kode_mu">Kode Mata Uang</label>
                        <input type="text" class="form-control" name="kode_mu" id="kode_mu" value="<?= $mata_uang['kode_mu']; ?>">
                        <?= form_error('kode_mu', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="nama_mu">Nama Mata Uang</label>
                        <input type="text" class="form-control" name="nama_mu" id="nama_mu" value="<?= $mata_uang['nama_mu']; ?>">
                        <?= form_error('nama_mu', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>

                    

                <button type="submit" class="btn btn-success">Update</button>
                <a href="<?= base_url('mata_uang');?>" class="btn btn-success"><span class="text">Batal</span>
                        </a>
                
                
                </form> 

                </div>
                </div>

        </div>
        <!-- /.container-fluid -->