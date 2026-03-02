<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html class="no-js" lang="es">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="x-ua-compatible" content="ie=edge" />
		<title>Vatech Perú | Latinoamérica</title>
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="<?= base_url() ?>assets/img/favicon.ico" rel="icon">
		
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:wght@200;300;400;500;700&display=swap">
		<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-icons/bootstrap-icons.css">
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/LineIcons.2.0.css"/>
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/animate.css"/>
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/vpe.css"/>
	</head>
	<body>
    
    <div class="preloader">
		<div class="loader">
			<div class="spinner">
				<div class="spinner-container">
					<div class="spinner-rotator">
						<div class="spinner-left">
							<div class="spinner-circle"></div>
						</div>
						<div class="spinner-right">
							<div class="spinner-circle"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<section class="vpe_section">
		<header class="vpe_header">
			<div class="navbar-area">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-12">
							<nav class="navbar navbar-expand-lg">
								<a class="navbar-brand" href="<?= base_url() ?>">
									<img src="<?= base_url() ?>assets/img/logo/logo.png" alt="Logo" />
								</a>
								
								<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbar_vpe" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
									
									<span class="toggler-icon"></span>
									<span class="toggler-icon"></span>
									<span class="toggler-icon"></span>
								</button>
								<div class="collapse navbar-collapse sub-menu-bar " id="navbar_vpe" data-bs-backdrop="false" data-bs-scroll="true">
									<ul id="nav2" class="navbar-nav ms-auto">
										<li class="nav-item">
											<a class="page-scroll <?= $navbar === "home" ? "active" : "" ?>" href="<?= base_url() ?>">Inicio</a>
										</li>
										<li class="nav-item">
											<a class="page-scroll <?= $navbar === "company" ? "active" : "" ?>" href="<?= base_url() ?>company">Compañía</a>
										</li>
										<li class="nav-item">
											<a class="page-scroll <?= $navbar === "product" ? "active" : "" ?>" href="<?= base_url() ?>product">Productos</a>
										</li>
										<li class="nav-item">
											<a class="page-scroll <?= $navbar === "distributor" ? "active" : "" ?>" href="<?= base_url() ?>distributor">Distribuidores</a>
										</li>
										<li class="nav-item">
											<a class="page-scroll <?= $navbar === "institute" ? "active" : "" ?>" href="<?= base_url() ?>institute">Instituto</a>
										</li>
									</ul>
									<a href="<?= base_url() ?>contact" class="button button-sm radius-10 d-none d-lg-flex">Contacto</a>
								</div>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</header>
	</section>
	
	<?php $this->load->view($main); ?>
	
	<footer class="footer">
		<div class="container">
			<div class="widget-wrapper">
				<div class="row">
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="footer-widget wow fadeInUp" data-wow-delay=".2s">
							<div class="logo">
								<a href="#0"> <img src="<?= base_url() ?>assets/img/logo/logo.png" alt=""> </a>
							</div>
							<p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Facilisis nulla placerat amet amet congue.</p>
							<ul class="socials">
								<li> <a href="#0"> <i class="lni lni-facebook-filled"></i> </a> </li>
								<li> <a href="#0"> <i class="lni lni-twitter-filled"></i> </a> </li>
								<li> <a href="#0"> <i class="lni lni-instagram-filled"></i> </a> </li>
								<li> <a href="#0"> <i class="lni lni-linkedin-original"></i> </a> </li>
							</ul>
						</div>
					</div>
					<div class="col-xl-2 offset-xl-1 col-lg-2 col-md-6 col-sm-6">
						<div class="footer-widget wow fadeInUp" data-wow-delay=".3s">
							<h6>Quick Link</h6>
							<ul class="links">
								<li> <a href="#0">Home</a> </li>
								<li> <a href="#0">About</a> </li>
								<li> <a href="#0">Service</a> </li>
								<li> <a href="#0">Contact</a> </li>
							</ul>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
						<div class="footer-widget wow fadeInUp" data-wow-delay=".4s">
							<h6>Services</h6>
							<ul class="links">
								<li> <a href="#0">Web Design</a> </li>
								<li> <a href="#0">Web Development</a> </li>
								<li> <a href="#0">Seo Optimization</a> </li>
								<li> <a href="#0">Blog Writing</a> </li>
							</ul>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-md-6">
						<div class="footer-widget wow fadeInUp" data-wow-delay=".5s">
							<h6>Help & Support</h6>
							<ul class="links">
								<li> <a href="#0">Support Center</a> </li>
								<li> <a href="#0">Live Chat</a> </li>
								<li> <a href="#0">FAQ</a> </li>
								<li> <a href="#0">Terms & Conditions</a> </li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="copyright-wrapper wow fadeInUp" data-wow-delay=".2s">
				<p>© 2026 Vatech. Todos los derechos reservados.</p>
			</div>
		</div>
	</footer>
    
    <a href="#" class="scroll-top"> <i class="lni lni-chevron-up"></i> </a>
    
    <script src="<?= base_url() ?>assets/vendor/jquery-3.7.0.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() ?>assets/js/count-up.min.js"></script>
    <script src="<?= base_url() ?>assets/js/wow.min.js"></script>
    <script src="<?= base_url() ?>assets/js/main.js"></script>
  </body>
</html>
