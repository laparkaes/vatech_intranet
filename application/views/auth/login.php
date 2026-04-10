<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>VPR - Ingresar</title>
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
</head>
<body>

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
		
	<main>
		<div class="container">
			<section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
							<div class="card mb-3">
								<div class="card-body">
									<div class="pt-4 pb-2 text-center">
										<img src="<?= base_url() ?>assets/img/logo.png" alt="">
									</div>
									<form class="row g-3 needs-validation pt-3" action="<?php echo base_url('auth/login_process'); ?>" method="post" novalidate>
										<div class="col-12">
											<label for="yourUsername" class="form-label">Correo Electrónico</label>
											<div class="input-group has-validation">
												<span class="input-group-text" id="inputGroupPrepend">@</span>
												<input type="email" name="email" class="form-control" id="yourUsername" placeholder="ejemplo@vatechglobal.com" required>
												<div class="invalid-feedback">Ingrese correo electrónico.</div>
											</div>
										</div>
										<div class="col-12">
											<label for="yourPassword" class="form-label">Contraseña</label>
											<input type="password" name="password" class="form-control" id="yourPassword" placeholder="********" required>
											<div class="invalid-feedback">Ingrese contraseña.</div>
										</div>
										<div class="col-12">
											<button class="btn btn-primary w-100" type="submit">Ingresar</button>
										</div>
										<div class="col-12">
											<p class="small mb-0"><a href="<?php echo base_url('auth/register'); ?>">Crear usuario</a></p>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
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