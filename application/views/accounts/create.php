<h2>Registrar Nuevo Usuario</h2>
<p><a href="<?php echo base_url('accounts'); ?>">Volver a la lista</a></p>

<form action="<?php echo base_url('accounts/add'); ?>" method="post">
    <fieldset>
        <legend>Datos Obligatorios</legend>
        <p>
            <label>Nombre Completo:</label><br>
            <input type="text" name="full_name" required>
        </p>
		<p>
			<label>Fecha de Ingreso:</label><br>
			<input type="date" name="hire_date" required>
		</p>
        <p>
            <label>Correo Electrónico (ID de ingreso):</label><br>
            <input type="email" name="email" required>
        </p>
        <p>
            <label>Contraseña:</label><br>
            <input type="password" name="password" required>
        </p>
    </fieldset>

    <fieldset>
        <legend>Asignación y Permisos</legend>
        <p>
            <label>División:</label><br>
            <select name="division_id">
                <option value="">Sin Asignar</option>
                <?php foreach($divisions as $div): ?>
                    <option value="<?php echo $div->id; ?>"><?php echo $div->division_name; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label>Rol de Usuario:</label><br>
            <select name="role">
                <option value="user">USER</option>
                <option value="admin">ADMIN</option>
            </select>
        </p>
    </fieldset>

    <br>
    <button type="submit">Registrar Usuario</button>
</form>