<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/bootstrap.css') ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/bootstrap.min.css') ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/bootstrap-me.css') ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/bootstrap-icons.css') ?>">
		
		<!-- Additional CSS -->
		<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/datatables.min.css') ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/dataTables.bootstrap4.min.css') ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/buttons.dataTables.min.css') ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/bootstrap-select.min.css') ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/select2.min.css') ?>">
		<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/datepicker.min.css') ?>">
		
		<title><?= $title; ?></title>
	</head>

	<body>
		<?php date_default_timezone_set('Asia/Jakarta'); ?>
		
		<!-- Core JavaScript-->
		<script type="text/javascript" src="<?= base_url() ?>asset/js/jquery.js"></script>
		<!-- Additional JavaScript-->
		<script type="text/javascript" src="<?= base_url() ?>asset/js/bootstrap.bundle.min.js"></script>
		
		<?= $head; ?>
		<div class="row">
			<?= $sidebar; ?>
			
			<div class="main col-md-9 col-lg-10 ml-sm-auto px-0">
				<div>
					<?= $content; ?>
					<?= $foot; ?>
				</div>
				<!-- <div class="container-fluid"></div> -->
			</div>
		</div>
		
		<!-- Modal untuk Logout -->
		<div class="modal fade" id="logout" tabindex="0" aria-labelledby="logoutLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content mx-auto" style="width:400px">
					<div class="modal-header pl-4">
						<h5 class="modal-title" id="logoutLabel">Log Out</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body py-4 px-4">
						<div class="row mb-4">
							<div class="col">
								<font size="4">
									Apakah Anda akan keluar?
								</font>
							</div>
						</div>
						<div class="row float-right">
							<div class="col">
								<button type="button" tabindex="1" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
								<a class="btn btn-danger" href="<?= base_url() ?>logout">Keluar</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Modal untuk Notifikasi -->
		<div class="modal fade" id="modalNotif" tabindex="-1" aria-labelledby="notifLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content text-center" style="border-radius : 2rem">
					<div class="modal-body p-4">
						<div class="container">
							<h3 class="my-4"><?= $this->session->flashdata('notification'); ?></h3>
							<i class="bi bi-check-circle-fill" style="font-size:100px; color:green"></i>
						</div>
					</div>
					<small class="mt-5"></small>
				</div>
			</div>
		</div>
		
		<!-- Modal untuk Warning -->
		<div class="modal fade" id="modalWarning" tabindex="-1" aria-labelledby="warningLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content text-center" style="border-radius : 2rem">
					<div class="modal-body p-4">
						<div class="container">
							<h3 class="my-4"><?= $this->session->flashdata('warning'); ?></h3>
							<i class="bi bi-x-circle-fill" style="font-size:100px; color:red"></i>
						</div>
					</div>
					<small class="mt-5"></small>
				</div>
			</div>
		</div>
		
		<!-- Core plugin JavaScript-->
		<!-- <link rel="stylesheet" type="text/javascript" href="<?= base_url('asset/js/jquery.js') ?>"> -->
		<script>
			function closeSidebar() {
				$('#sidebarMenu').addClass('d-none')
				$('.main').removeClass('col-md-9 col-lg-10').addClass('col-12')
				$("#buttonSidebar").attr('onClick', 'openSidebar()');
			}

			function openSidebar() {
				$('#sidebarMenu').removeClass('d-none')
				$('.main').removeClass('col-12').addClass('col-md-9 col-lg-10')
				$("#buttonSidebar").attr('onClick', 'closeSidebar()');
			}
		</script>
	</body>
</html>