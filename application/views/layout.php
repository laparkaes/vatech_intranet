<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Vatech Peru</title>
	<meta content="" name="description">
	<meta content="" name="keywords">
	<link href="<?= base_url() ?>assets/img/favicon.ico" rel="icon">
	<link href="<?= base_url() ?>assets/img/favicon.ico" rel="apple-touch-icon">
	<link href="<?= base_url() ?>assets/fonts.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/quill/quill.snow.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/quill/quill.bubble.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/simple-datatables/style.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet">

    <script src="<?= base_url() ?>assets/vendor/jquery-3.6.0.min.js"></script>
</head>
<body>

	<header id="header" class="header fixed-top d-flex align-items-center">

		<div class="d-flex align-items-center justify-content-between">
			<a href="<?= base_url() ?>" class="logo d-flex align-items-center">
				<img src="<?= base_url() ?>assets/img/logo.png" alt="">
			</a>
			<i class="bi bi-list toggle-sidebar-btn"></i>
		</div>

		<nav class="header-nav ms-auto">
			<ul class="d-flex align-items-center">

				<li class="nav-item dropdown pe-3">

					<a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
						<span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $this->session->userdata('name'); ?></span>
					</a>

					<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
					
					<li class="dropdown-header">
						<h6><?php echo $this->session->userdata('name'); ?></h6>
						<span><?php echo $this->session->userdata('division_name'); ?></span>
					</li>

					<li>
						<hr class="dropdown-divider">
					</li>

					<li>
						<a class="dropdown-item d-flex align-items-center" href="#">
							<i class="bi bi-gear"></i>
							<span>Account Settings</span>
						</a>
					</li>
					
					<li>
						<a class="dropdown-item d-flex align-items-center" href="<?php echo base_url('access/access_request'); ?>">
							<i class="bi bi-door-open"></i>
							<span>Mis Accesos</span>
						</a>
					</li>
					
					<li>
						<hr class="dropdown-divider">
					</li>

					<li>
						<a class="dropdown-item d-flex align-items-center" href="<?php echo base_url('auth/logout'); ?>">
							<i class="bi bi-box-arrow-right"></i>
							<span>Salir</span>
						</a>
					</li>

					</ul>
				</li>

			</ul>
		</nav>
	</header>
	
	<aside id="sidebar" class="sidebar">
		<ul class="sidebar-nav" id="sidebar-nav">

			<li class="nav-item">
				<a class="nav-link <?= $this->menu === '' ? "" : "collapsed" ?>" href="<?= base_url() ?>dashboard" >
					<i class="bi bi-grid"></i>
					<span>Dashboard</span>
				</a>
			</li>

			<?php if ($user_role === 'admin' || in_array('Compras', $my_access)): ?>
			<li class="nav-item">
				<a class="nav-link <?= $this->menu === 'purchase' ? "" : "collapsed" ?>" data-bs-target="#purchase-nav" data-bs-toggle="collapse" href="#">
					<i class="bi bi-cart"></i><span>Compras</span><i class="bi bi-chevron-down ms-auto"></i>
				</a>
				<ul id="purchase-nav" class="nav-content collapse <?= $this->menu === 'purchase' ? "show" : "" ?>" data-bs-parent="#sidebar-nav">
					<li>
						<a href="<?php echo base_url('purchase'); ?>" class="<?= $this->menu_sub === 'purchase' ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Compras</span>
						</a>
					</li>
					<li>
						<a href="<?php echo base_url('vendor'); ?>" class="<?= $this->menu_sub === 'vendor' ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Proveedores</span>
						</a>
					</li>
				</ul>
			</li>
			<?php endif; ?>

			<?php if ($user_role === 'admin' || in_array('Ventas', $my_access)): ?>
			<li class="nav-item">
				<a class="nav-link <?= $this->menu === 'sale' ? "" : "collapsed" ?>" data-bs-target="#sale-nav" data-bs-toggle="collapse" href="#">
					<i class="bi bi-upc-scan"></i><span>Ventas</span><i class="bi bi-chevron-down ms-auto"></i>
				</a>
				<ul id="sale-nav" class="nav-content collapse <?= $this->menu === 'sale' ? "show" : "" ?>" data-bs-parent="#sidebar-nav">
					<li>
						<a href="<?php echo base_url('sale'); ?>" class="<?= $this->menu_sub === 'sale' ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Ventas</span>
						</a>
					</li>
					<li>
						<a href="<?php echo base_url('distributor'); ?>" class="<?= $this->menu_sub === 'distributor' ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Distribuidores</span>
						</a>
					</li>
					<li>
						<a href="<?php echo base_url('exchange'); ?>" class="<?= $this->menu_sub === 'exchange' ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Tipo de Cambio</span>
						</a>
					</li>
				</ul>
			</li>
			<?php endif; ?>

			<?php if ($user_role === 'admin' || in_array('Logística', $my_access)): ?>
			<li class="nav-item">
				<a class="nav-link collapsed" data-bs-target="#logistic-nav" data-bs-toggle="collapse" href="#">
					<i class="bi bi-truck"></i><span>Logística</span><i class="bi bi-chevron-down ms-auto"></i>
				</a>
				<ul id="logistic-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
					<li>
						<a href="<?php echo base_url('inbound'); ?>">
							<i class="bi bi-circle"></i><span>Ingresos</span>
						</a>
					</li>
					<li>
						<a href="<?php echo base_url('outbound'); ?>">
							<i class="bi bi-circle"></i><span>Salidas</span>
						</a>
					</li>
					<li>
						<a href="<?php echo base_url('inventory'); ?>">
							<i class="bi bi-circle"></i><span>Inventario</span>
						</a>
					</li>
					<li>
						<a href="<?php echo base_url('warehouse'); ?>">
							<i class="bi bi-circle"></i><span>Almacenes</span>
						</a>
					</li>
					<li>
						<a href="<?php echo base_url('product'); ?>">
							<i class="bi bi-circle"></i><span>Productos</span>
						</a>
					</li>
				</ul>
			</li>
			<?php endif; ?>

			<?php if ($user_role === 'admin' || in_array('Sistema', $my_access)): ?>
			<li class="nav-item">
				<a class="nav-link <?= $this->menu === 'system' ? "" : "collapsed" ?>" data-bs-target="#system-nav" data-bs-toggle="collapse" href="#">
					<i class="bi bi-pc-display"></i><span>Sistema</span><i class="bi bi-chevron-down ms-auto"></i>
				</a>
				<ul id="system-nav" class="nav-content collapse <?= $this->menu === 'system' ? "show" : "" ?>" data-bs-parent="#sidebar-nav">
					<li>
						<a href="<?php echo base_url('accounts'); ?>" class="<?= $this->menu_sub === 'accounts' ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Usuarios</span>
						</a>
					</li>
					<li>
						<a href="<?php echo base_url('access/requests'); ?>" class="<?= $this->menu_sub === 'access_request' ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Solicitudes de Acceso</span>
						</a>
					</li>
				</ul>
			</li>
			<?php endif; ?>

			<?php if ($user_role === 'admin' || in_array('Maestro', $my_access)): ?>
			<li class="nav-item">
				<a class="nav-link <?= $this->menu === 'master' ? "" : "collapsed" ?>" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
					<i class="bi bi-database"></i><span>Maestro</span><i class="bi bi-chevron-down ms-auto"></i>
				</a>
				<ul id="master-nav" class="nav-content collapse <?= $this->menu === 'master' ? "show" : "" ?>" data-bs-parent="#sidebar-nav">
					<li>
						<a href="<?php echo base_url('division'); ?>" class="<?= $this->menu_sub === 'division' ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Divisiones</span>
						</a>
					</li>
					<li>
						<a href="<?php echo base_url('entity'); ?>" class="<?= $this->menu_sub === 'entity' ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Entidades</span>
						</a>
					</li>
					<li>
						<a href="<?php echo base_url('access'); ?>" class="<?= $this->menu_sub === 'access' ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Accesos</span>
						</a>
					</li>
				</ul>
			</li>
			<?php endif; ?>
		</ul>
	</aside>

	<main id="main" class="main">
	
		<div class="row">
			<div class="col">
			
				<?php if($this->session->flashdata('error')): ?>
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Error:</strong> <?php echo $this->session->flashdata('error'); ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
				<?php endif; ?>

				<?php if($this->session->flashdata('success')): ?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong>Éxito:</strong> <?php echo $this->session->flashdata('success'); ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
				<?php endif; ?>
				
			</div>
		</div>
	
		<?php $this->load->view($main); ?>
	</main>


	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

	<script src="<?= base_url() ?>assets/vendor/apexcharts/apexcharts.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/chart.js/chart.umd.js"></script>
	<script src="<?= base_url() ?>assets/vendor/echarts/echarts.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/quill/quill.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/simple-datatables/simple-datatables.js"></script>
	<script src="<?= base_url() ?>assets/vendor/tinymce/tinymce.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/php-email-form/validate.js"></script>

	<script src="<?= base_url() ?>assets/js/main.js"></script>

</body>
</html>