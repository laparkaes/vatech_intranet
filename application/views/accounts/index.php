<h2>Lista de Usuarios</h2>

<div style="margin-bottom: 20px;">
    <a href="<?php echo base_url('accounts/create'); ?>">
        <button type="button">Nuevo Usuario</button>
    </a>
</div>

<table border="1">
    <thead>
        <tr>
            <th>No.</th> <th>Nombre</th>
            <th>Email</th>
            <th>Fecha de Ingreso</th>
            <th>Antigüedad</th>
            <th>División</th>
            <th>Perfil</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $count = 1; 
        foreach($users as $user): 
        ?>
        <tr>
            <td><?php echo $count++; ?></td>
            <td><?php echo $user->full_name; ?></td>
            <td><?php echo $user->email; ?></td>
            <td><?php echo $user->hire_date; ?></td>
            <td><strong><?php echo $user->tenure; ?></strong></td>
            <td><?php echo $user->division_name ? $user->division_name : 'Sin Asignar'; ?></td>
            <td><?php echo strtoupper($user->role); ?></td>
            <td><?php echo ($user->status == 1) ? 'ACTIVO' : 'INACTIVO'; ?></td>
            <td>
                <a href="<?php echo base_url('accounts/edit/'.$user->id); ?>">
                    <button type="button">Editar</button>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>