<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al Sistema - VPR ERP</title>
</head>
<body>
	<div class="login-container">
		<h1>VPR ERP</h1>
		
		<?php if($this->session->flashdata('error')): ?>
			<p><strong>Error:</strong> <?php echo $this->session->flashdata('error'); ?></p>
			<br/>
			<br/>
		<?php endif; ?>

		<?php if($this->session->flashdata('success')): ?>
			<p><strong>Éxito:</strong> <?php echo $this->session->flashdata('success'); ?></p>
			<br/>
			<br/>
		<?php endif; ?>

		<form action="<?php echo base_url('auth/login_process'); ?>" method="post">
			<div class="form-group">
				<label>Correo Electrónico</label>
				<input type="email" name="email" required placeholder="ejemplo@vpr.pe">
			</div>
			<div class="form-group">
				<label>Contraseña</label>
				<input type="password" name="password" required placeholder="********">
			</div>
			<br/>
			<button type="submit">Entrar al Sistema</button>
			<p><a href="<?php echo base_url('auth/forgot_password'); ?>">¿Olvidó su contraseña?</a></p>
			<p>¿No tienes una cuenta? <a href="<?php echo base_url('auth/register'); ?>">Regístrate aquí</a></p>
		</form>
	</div>
</body>
</html>