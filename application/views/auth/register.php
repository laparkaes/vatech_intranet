<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al Sistema - VPR ERP</title>
</head>
<body>
	<h1>Crear Nueva Cuenta</h1>

	<?php if($this->session->flashdata('error')): ?>
		<p><strong>Error:</strong> <?php echo $this->session->flashdata('error'); ?></p>
	<?php endif; ?>

	<form action="<?php echo base_url('auth/register_process'); ?>" method="post">
		<div>
			<label>Nombre Completo:</label><br>
			<input type="text" name="full_name" required>
		</div>
		<div>
			<label>Correo Electrónico:</label><br>
			<input type="email" name="email" required>
		</div>
		<div>
			<label>Contraseña:</label><br>
			<input type="password" name="password" required>
		</div>
		<br>
		<button type="submit">Registrar Usuario</button>
	</form>

	<hr>
	<p><a href="<?php echo base_url('auth'); ?>">Volver al inicio de sesión</a></p>
</body>
</html>