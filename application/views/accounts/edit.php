<h2>Editar Usuario</h2>
<a href="<?php echo base_url('accounts'); ?>">Volver a la lista</a>

<form action="<?php echo base_url('accounts/update'); ?>" method="post">
    <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">

    <fieldset>
        <legend>Información Personal</legend>
        <p>
            <label>Nombre Completo:</label><br>
            <input type="text" name="full_name" value="<?php echo $user->full_name; ?>" required>
        </p>
		<p>
			<label>Fecha de Ingreso:</label><br>
			<input type="date" name="hire_date" value="<?php echo $user->hire_date; ?>">
		</p>
        <p>
            <label>Correo Electrónico (No editable):</label><br>
            <input type="email" value="<?php echo $user->email; ?>" readonly>
        </p>
        <p>
            <label>Nueva Contraseña (Dejar en blanco para no cambiar):</label><br>
            <input type="password" name="password">
        </p>
    </fieldset>

    <fieldset>
        <legend>Configuración de Sistema</legend>
        <p>
            <label>División:</label><br>
            <select name="division_id">
                <option value="">Sin Asignar</option>
                <?php foreach($divisions as $div): ?>
                    <option value="<?php echo $div->id; ?>" <?php echo ($user->division_id == $div->id) ? 'selected' : ''; ?>>
                        <?php echo $div->division_name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label>Rol:</label><br>
            <select name="role" <?php echo ($user->id == $this->session->userdata('user_id')) ? 'disabled' : ''; ?>>
                <option value="user" <?php echo ($user->role == 'user') ? 'selected' : ''; ?>>USER</option>
                <option value="admin" <?php echo ($user->role == 'admin') ? 'selected' : ''; ?>>ADMIN</option>
            </select>
            <?php if($user->id == $this->session->userdata('user_id')): ?>
                <input type="hidden" name="role" value="<?php echo $user->role; ?>">
            <?php endif; ?>
        </p>
        <p>
            <label>Estado:</label><br>
            <select name="status" <?php echo ($user->id == $this->session->userdata('user_id')) ? 'disabled' : ''; ?>>
                <option value="1" <?php echo ($user->status == 1) ? 'selected' : ''; ?>>ACTIVO</option>
                <option value="0" <?php echo ($user->status == 0) ? 'selected' : ''; ?>>INACTIVO</option>
            </select>
            <?php if($user->id == $this->session->userdata('user_id')): ?>
                <input type="hidden" name="status" value="<?php echo $user->status; ?>">
            <?php endif; ?>
        </p>
    </fieldset>

    <br>
    <button type="submit">Guardar Cambios</button>
</form>