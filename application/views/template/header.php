<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<nav class="navbar navbar-dark shadow sticky-top">
	<div class="d-inline-flex">
		<button class="navbar-toggler collapsed" type="button" onclick="closeSidebar()" id="buttonSidebar">
			<span class="navbar-toggler-icon"></span>
		</button>
		<a href="<?= base_url() ?>" class="web-title" style="padding: .15rem .75rem;">
			<h3 class="m-0">General Ledger</h3>
		</a>
	</div>
	
	<!-- <div class="d-none d-md-block">
		<a href="#" class="navbar-item active-data"><?= $this->session->userdata('nama_perusahaan') ?></a>
		<a href="#" class="navbar-item active-data"><?= $this->session->userdata('tahun_aktif') ?></a>
		<a href="#" class="navbar-item active-data"><?= $this->session->userdata('bulan_aktif') ?></a>
		
		<div class="dropdown d-inline ml-2">
			<a class="navbar-user d-inline-block" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="bi bi-person-circle align-middle" style="font-size: 24px;"></i>
				<?= $this->session->userdata('nama_user') ?>
			</a>
			
			<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
				<a href="<?= base_url(); ?>admin/profile" class="dropdown-item">
					<i class="bi bi-person-fill mr-1"></i>
					Profile
				</a>
				<a class="dropdown-item btn-logout" data-toggle="modal" data-target="#logout">
					<i class="bi bi-box-arrow-right mr-1"></i>
					Log Out
				</a>
			</div>
		</div>
	</div> -->
	
	<ul class="nav nav-header justify-content-end">
		<li class="nav-item">
			<a class="nav-link" href="<?= base_url() ?>pilih_perusahaan">
				<?= $this->session->userdata('nama_perusahaan') ?>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="<?= base_url() ?>pindah_bulan">
				<?= $this->session->userdata('nama_bulan').' '.$this->session->userdata('tahun_aktif') ?>
			</a>
		</li>
		<li class="nav-item">
			<div class="nav-link dropdown d-inline">
				<a class="navbar-user d-inline-block" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span>
						<i class="bi bi-person-circle align-middle" style="font-size: 24px;"></i>
					</span>
					<?= $this->session->userdata('nama_user') ?>
				</a>
				
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
					<a href="<?= base_url(); ?>admin/profile" class="dropdown-item">
						<i class="bi bi-person-fill mr-1"></i>
						Profile
					</a>
					<a class="dropdown-item btn-logout" data-toggle="modal" data-target="#logout">
						<i class="bi bi-box-arrow-right mr-1"></i>
						Log Out
					</a>
				</div>
			</div>
		</li>
	</ul>
</nav>
