<nav id="sidebarMenu" class="col-md-3 col-lg-2 sidebar">
	<div class="sidebar-sticky pt-0 pb-5">
		<!-- Home -->
		<a href="<?= base_url() ?>" class="sidebar-menu" id="home">
			<i class="bi bi-house-door sidebar-icon"></i>
			Home
		</a>
		<div class=""></div>
		
		<!-- Menu Master -->
		<a class="sidebar-menu collapsed" id="menu_master" data-toggle="collapse" data-target="#submenu_master" aria-expanded="false" aria-controls="submenu_master">
			<i class="bi bi-layers sidebar-icon"></i>
			Master
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="submenu_master" aria-labelledby="menu_master">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>master/akun" data-id="akun">
				Daftar Akun
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>master/barang" data-id="barang">
				Barang
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>master/supplier" data-id="supplier">
				Supplier
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>master/pelanggan" data-id="pelanggan">
				Pelanggan
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>master/mata_uang" data-id="mata_uang">
				Mata Uang
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>master/kurs" data-id="kurs">
				Kurs
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>master/penamaan" data-id="penamaan">
				Penamaan
			</a>
		</div>
		
		<!-- Menu Transaksi -->
		<a class="sidebar-menu collapsed" id="menu_transaksi" data-toggle="collapse" data-target="#submenu_transaksi" aria-expanded="false" aria-controls="submenu_transaksi">
			<i class="bi bi-layers sidebar-icon"></i>
			Transaksi
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="submenu_transaksi" aria-labelledby="menu_transaksi">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>transaksi/kas" data-id="kas">
				Kas
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>transaksi/bank" data-id="bank">
				Bank
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>transaksi/serba_serbi" data-id="serba_serbi">
				Serba-Serbi
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>transaksi/penjualan" data-id="penjualan">
				Penjualan
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>transaksi/retur_penjualan" data-id="retur_penjualan">
				Retur Penjualan
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>transaksi/pembelian" data-id="pembelian">
				Pembelian
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>transaksi/retur_pembelian" data-id="retur_pembelian">
				Retur Pembelian
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>transaksi/hutang" data-id="hutang">
				Bayar Hutang
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>transaksi/piutang" data-id="piutang">
				Bayar Piutang
			</a>
		</div>
		
		<!-- Menu Pages -->
		<a class="sidebar-menu collapsed" id="headingOne" data-toggle="collapse" data-target="#menu1" aria-expanded="false" aria-controls="menu1">
			<i class="bi bi-layers sidebar-icon"></i>
			Pages
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="menu1" aria-labelledby="headingOne">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pages/profile" data-id="profile">
				Profile
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pages/table" data-id="table">
				Table
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pages/blank_page" data-id="blank_page">
				Blank Page
			</a>
		</div>
		
		<!-- Menu Authority -->
		<a class="sidebar-menu collapsed" id="headingTwo" data-toggle="collapse" data-target="#menu2" aria-expanded="false" aria-controls="menu2">
			<i class="bi bi-clock sidebar-icon"></i>
			Authority
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="menu2" aria-labelledby="headingTwo">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pages/sign_in" data-id="sign_in">
				Sign In
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pages/sign_up" data-id="sign_up">
				Sign Up
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pages/reset" data-id="reset">
				Reset Password
			</a>
		</div>
			
		<!-- Menu 3 -->
		<a class="sidebar-menu collapsed" id="headingThree" data-toggle="collapse" data-target="#menu3" aria-expanded="false" aria-controls="menu3">
			<i class="bi bi-box-arrow-in-down sidebar-icon"></i>
			Menu 3
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="menu3" aria-labelledby="headingThree">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pages/blank_page1" data-id="blank_page1">
				Blank Page
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pages/profile1" data-id="profile1">
				Profile
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pages/master1" data-id="master1">
				Master
			</a>
		</div>
			
		<!-- Menu 4 -->
		<a class="sidebar-menu collapsed" id="headingFour" data-toggle="collapse" data-target="#menu4" aria-expanded="false" aria-controls="menu4">
			<i class="bi bi-bar-chart-line sidebar-icon"></i>
			Menu 4
			<span class="menu-arrow"><i class="bi bi-chevron-right"></i></span>
		</a>
		<div class="menus collapse" id="menu4" aria-labelledby="headingFour">
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pages/blank_page2" data-id="blank_page2">
				Blank Page
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pages/profile2" data-id="profile2">
				Profile
			</a>
			<a class="sidebar-submenu nav-link" href="<?=base_url()?>pages/master2" data-id="master2">
				Master
			</a>
		</div>

		<!-- Logout -->
		<a href="#" class="sidebar-menu" data-toggle="modal" data-target="#logout">
			<i class="bi bi-box-arrow-right sidebar-icon"></i>
			Logout
		</a>
	</div>
</nav>

<script>
	$(document).ready(function() {
		var path1	= window.location.pathname;
		var arr1	= path1.split('/');
		
		if( !arr1[3] ) {
			$('#home').addClass('active');
		} else {
			$("#sidebarMenu .sidebar-sticky .sidebar-submenu").each(function() {
				var menu	= $(this)
				var parent	= menu.parent();
				
				if( menu.data('id') == arr1[3] ) {
					menu.addClass('active');
					parent.addClass('show');
					parent.prev().addClass('active');
					parent.prev().removeClass('collapsed').attr('aria-expanded', 'true');
				}
			});
		}
	});
</script>