<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña - VPR ERP</title>
</head>
<body>
    <h1>Recuperar Contraseña</h1>
    <p>Ingrese su correo electrónico para recibir una nueva contraseña temporal.</p>

    <?php if($this->session->flashdata('error')): ?>
        <p><strong>Error:</strong> <?php echo $this->session->flashdata('error'); ?></p>
    <?php endif; ?>

    <form action="<?php echo base_url('auth/reset_password_process'); ?>" method="post">
        <div>
            <label>Correo Electrónico:</label><br>
            <input type="email" name="email" required>
        </div>
        <br>
        <button type="submit">Enviar Nueva Contraseña</button>
    </form>

    <hr>
    <p><a href="<?php echo base_url('auth'); ?>">Volver al inicio de sesión</a></p>
</body>
</html>