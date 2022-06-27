<nav id="sidebarMenu" class="col-md-3 col-lg-2 sidebar">
	<div class="sidebar-sticky pt-0 pb-5">
		<!-- <div class="display-perusahaan"><?= $this->session->userdata('nama_perusahaan') ?></div> -->
		
		<!-- Home -->
		<a href="<?= base_url() ?>home" class="sidebar-menu" id="home" data-id="home">
			Home
		</a>
		
		<!-- Menu Master -->
		<a class="sidebar-menu collapsed" id="menu_master" data-toggle="collapse" data-target="#submenu_master" aria-expanded="false" aria-controls="submenu_master">
			Master
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="submenu_master" aria-labelledby="menu_master">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>akun" data-id="akun">
				Akun
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>barang" data-id="barang">
				Barang
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>mata_uang" data-id="mata_uang">
				Mata Uang
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>kurs" data-id="kurs">
				Kurs
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>penamaan" data-id="penamaan">
				Penamaan
			</a>
		</div>
		
		<!-- Menu Transaksi -->
		<a class="sidebar-menu collapsed" id="menu_transaksi" data-toggle="collapse" data-target="#submenu_transaksi" aria-expanded="false" aria-controls="submenu_transaksi">
			Transaksi
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="submenu_transaksi" aria-labelledby="menu_transaksi">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>kas" data-id="kas">
				Kas
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>bank" data-id="bank">
				Bank
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>serba_serbi" data-id="serba_serbi">
				Serba-Serbi
			</a>
		</div>
		
		<!-- Menu Penjualan -->
		<a class="sidebar-menu collapsed" id="menu_penjualan" data-toggle="collapse" data-target="#submenu_penjualan" aria-expanded="false" aria-controls="submenu_penjualan">
			Penjualan
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="submenu_penjualan" aria-labelledby="menu_penjualan">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pelanggan" data-id="pelanggan">
				Pelanggan
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>penjualan" data-id="penjualan">
				Penjualan
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>retur_penjualan" data-id="retur_penjualan">
				Retur Penjualan
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>piutang" data-id="piutang">
				Pembayaran
			</a>
		</div>
		
		<!-- Menu Penjualan -->
		<a class="sidebar-menu collapsed" id="menu_penjualan2" data-toggle="collapse" data-target="#submenu_penjualan2" aria-expanded="false" aria-controls="submenu_penjualan2">
			Penjualan V2
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="submenu_penjualan2" aria-labelledby="menu_penjualan2">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pelanggan" data-id="pelanggan">
				Pelanggan
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>penjualan2" data-id="penjualan2">
				Penjualan
			</a>
		</div>
		
		<!-- Menu Pembelian -->
		<a class="sidebar-menu collapsed" id="menu_pembelian" data-toggle="collapse" data-target="#submenu_pembelian" aria-expanded="false" aria-controls="submenu_pembelian">
			Pembelian
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="submenu_pembelian" aria-labelledby="menu_pembelian">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>supplier" data-id="supplier">
				Supplier
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pembelian" data-id="pembelian">
				Pembelian
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>retur_pembelian" data-id="retur_pembelian">
				Retur Pembelian
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>hutang" data-id="hutang">
				Pembayaran
			</a>
		</div>
		
		<!-- Menu Buku Besar -->
		<a class="sidebar-menu collapsed" id="menu_buku" data-toggle="collapse" data-target="#submenu_buku" aria-expanded="false" aria-controls="submenu_buku">
			Buku Besar
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="submenu_buku" aria-labelledby="menu_buku">
			<a class="sidebar-submenu nav-link" href="#" data-id="buku_kas">
				Buku Kas
			</a>
			<a class="sidebar-submenu nav-link" href="#" data-id="buku_bank">
				Buku Bank
			</a>
			<a class="sidebar-submenu nav-link" href="#" data-id="buku_umum">
				Buku Umum
			</a>
		</div>
		
		<!-- Menu Laporan Transaksi -->
		<a class="sidebar-menu collapsed" id="menu_laporan_trasaksi" data-toggle="collapse" data-target="#submenu_laporan_trasaksi" aria-expanded="false" aria-controls="submenu_laporan_trasaksi">
			Laporan Transaksi
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="submenu_laporan_trasaksi" aria-labelledby="menu_laporan_trasaksi">
			<a class="sidebar-submenu nav-link" href="<?= base_url() ?>laporan_serba_serbi" data-id="laporan_serba_serbi">
				Serba Serbi
			</a>
			<a class="sidebar-submenu nav-link" href="<?= base_url() ?>laporan_penjualan" data-id="laporan_penjualan">
				Penjualan & Retur
			</a>
			<a class="sidebar-submenu nav-link" href="<?= base_url() ?>laporan_pembelian" data-id="laporan_pembelian">
				Pembelian & Retur
			</a>
			<a class="sidebar-submenu nav-link" href="<?= base_url() ?>laporan_piutang" data-id="laporan_piutang">
				Piutang & Pembayaran
			</a>
			<a class="sidebar-submenu nav-link" href="<?= base_url() ?>laporan_hutang" data-id="laporan_hutang">
				Hutang & Pembayaran
			</a>
		</div>
		
		<!-- Menu Laporan -->
		<a class="sidebar-menu collapsed" id="menu_laporan" data-toggle="collapse" data-target="#submenu_laporan" aria-expanded="false" aria-controls="submenu_laporan">
			Laporan Keuangan
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="submenu_laporan" aria-labelledby="menu_laporan">
			<a class="sidebar-submenu nav-link" href="#" data-id="neraca_lajur">
				Neraca Lajur
			</a>
			<a class="sidebar-submenu nav-link" href="#" data-id="cash_flow">
				Cash Flow
			</a>
			<a class="sidebar-submenu nav-link" href="#" data-id="trial_balance">
				Trial Balance
			</a>
			<a class="sidebar-submenu nav-link" href="#" data-id="neraca">
				Neraca
			</a>
			<a class="sidebar-submenu nav-link" href="#" data-id="laba_rugi">
				Laba Rugi
			</a>
			<a class="sidebar-submenu nav-link" href="#" data-id="bukti_jurnal">
				Bukti Jurnal
			</a>
			<a class="sidebar-submenu nav-link" href="#" data-id="nilai_akhir_barang">
				Nilai Akhir Barang
			</a>
		</div>
		
		<!-- Menu User -->
		<a class="sidebar-menu collapsed" id="menu_user" data-toggle="collapse" data-target="#submenu_user" aria-expanded="false" aria-controls="submenu_user">
			User
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="submenu_user" aria-labelledby="menu_user">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>admin" data-id="admin">
				Admin
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>anggota" data-id="anggota">
				Anggota
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>perusahaan" data-id="perusahaan">
				Perusahaan
			</a>
			<!-- <a class="sidebar-submenu nav-link" href="<?=base_url()?>akses_perusahaan" data-id="akses_perusahaan">
				Akses Perusahaan
			</a> -->
		</div>
		
		<!-- Menu Authority -->
		<a class="sidebar-menu collapsed" id="headingTwo" data-toggle="collapse" data-target="#menu2" aria-expanded="false" aria-controls="menu2">
			Authority
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="menu2" aria-labelledby="headingTwo">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>sign_in" data-id="sign_in">
				Sign In
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>sign_up" data-id="sign_up">
				Sign Up
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>reset" data-id="reset">
				Reset Password
			</a>
		</div>

		<!-- Logout -->
		<a href="#" class="sidebar-menu" data-toggle="modal" data-target="#logout">
			Logout
		</a>
	</div>
</nav>

<script>
	$(document).ready(function() {
		var path1	= window.location.pathname;
		var arr1	= path1.split('/');
		
		if( !arr1[2] ) {
			$('#home').addClass('active');
		} else {
			$("#sidebarMenu .sidebar-sticky a").each(function() {
				if( $(this).data('id') == arr1[2] ) {
					if( $(this).hasClass('sidebar-menu') ) {
						$(this).addClass('active')
					}
					else if( $(this).hasClass('sidebar-submenu') ) {
						var parent = $(this).parent();
						
						$(this).addClass('active'); // menambahkan class active pada submenu yang dipilih
						$(this).parent().addClass('show'); // menampilkan submenu dari menu yang dipilih
						$(this).parent().prev() // merujuk ke elemen yang ada diatas parent
								// .addClass('active') // menambahkan class active pada induk submenu yang dipilih
								.removeClass('collapsed') // menyesuaikan panah yang disamping menu
								.attr('aria-expanded', 'true');
					}
				}
			});
		}
	});
</script>
