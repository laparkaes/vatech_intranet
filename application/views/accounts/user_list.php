<h2>Gestión de Usuarios</h2>
<p>Desde aquí puede activar/desactivar cuentas y cambiar roles de usuario.</p>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Completo</th>
            <th>Email</th>
            <th>Rol Actual</th>
            <th>Estado</th>
            <th>Acciones sobre Rol</th>
            <th>Acciones sobre Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?php echo $user->id; ?></td>
            <td><?php echo $user->full_name; ?></td>
            <td><?php echo $user->email; ?></td>
            <td><?php echo strtoupper($user->role); ?></td>
            <td><?php echo ($user->status == 1) ? 'Activo' : 'Inactivo'; ?></td>
			<td>
				<?php if ($user->id !== $this->session->userdata('user_id')): ?>
					<form action="<?php echo base_url('accounts/change_role'); ?>" method="post">
						<input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
						<select name="new_role">
							<option value="user" <?php echo ($user->role == 'user') ? 'selected' : ''; ?>>User</option>
							<option value="admin" <?php echo ($user->role == 'admin') ? 'selected' : ''; ?>>Admin</option>
						</select>
						<button type="submit">Cambiar Rol</button>
					</form>
				<?php else: ?>
					<strong>Actual</strong>
				<?php endif; ?>
			</td>			
			<td>
				<?php if ($user->id !== $this->session->userdata('user_id')): ?>
					<form action="<?php echo base_url('accounts/toggle_status'); ?>" method="post">
						<input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
						<input type="hidden" name="current_status" value="<?php echo $user->status; ?>">
						<button type="submit">
							<?php echo ($user->status == 1) ? 'Desactivar Cuenta' : 'Activar Cuenta'; ?>
						</button>
					</form>
				<?php else: ?>
					<strong>Actual</strong>
				<?php endif; ?>
			</td>
			
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>