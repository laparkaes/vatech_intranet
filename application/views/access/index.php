<h2>Configuración de Maestro de Accesos</h2>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre del Acceso</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Última Actualización</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($access_list)): ?>
            <?php foreach($access_list as $access): ?>
            <tr>
                <form action="<?php echo base_url('access/update'); ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo $access->id; ?>">
                    
                    <td><?php echo $access->id; ?></td>
                    
                    <td>
                        <input type="text" name="access_name" value="<?php echo htmlspecialchars($access->access_name); ?>" required>
                    </td>
                    
                    <td>
                        <textarea name="description" rows="2" cols="30"><?php echo htmlspecialchars($access->description); ?></textarea>
                    </td>
                    
                    <td>
                        <select name="status">
                            <option value="1" <?php echo ($access->status == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($access->status == 0) ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                    </td>
                    
                    <td>
                        <?php echo $access->updated_user_name ? $access->updated_user_name : '-'; ?><br>
                        <?php 
                            /* Muestra la fecha de actualización si existe, sino la de creación */
                            echo $access->updated_at ? $access->updated_at : $access->created_at; 
                        ?>
                    </td>
                    
                    <td>
                        <button type="submit" onclick="return confirm('¿Actualizar esta configuración?')">Actualizar</button>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" align="center">No hay configuraciones registradas.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<hr>

<h3>Registrar Nuevo Acceso</h3>
<form action="<?php echo base_url('access/add'); ?>" method="post">
    <table border="1">
        <tr>
            <th>Nombre del Acceso</th>
            <td><input type="text" name="access_name" required placeholder="Ej: Compras"></td>
        </tr>
        <tr>
            <th>Descripción</th>
            <td><textarea name="description" rows="2" cols="30" placeholder="Descripción del módulo"></textarea></td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <button type="submit">Registrar Nuevo</button>
            </td>
        </tr>
    </table>
</form>