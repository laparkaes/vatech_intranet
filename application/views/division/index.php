<h2>Gestión de Divisiones</h2>

<fieldset>
    <legend>Registrar Nueva División</legend>
    <form action="<?php echo base_url('division/save'); ?>" method="post">
        <label>Nombre:</label>
        <input type="text" name="division_name" required>
        
        <label>Descripción:</label>
        <input type="text" name="description">
        
        <button type="submit">Guardar</button>
    </form>
</fieldset>

<br>

<table border="1">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nombre de División</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $count = 1; 
        foreach($list as $item): 
        ?>
        <tr>
            <form action="<?php echo base_url('division/update'); ?>" method="post">
                <td>
                    <?php echo $count++; ?>
                    <input type="hidden" name="id" value="<?php echo $item->id; ?>">
                </td>
                <td>
                    <input type="text" name="division_name" value="<?php echo $item->division_name; ?>" required>
                </td>
                <td>
                    <input type="text" name="description" value="<?php echo $item->description; ?>">
                </td>
                <td>
                    <select name="status">
                        <option value="1" <?php echo ($item->status == 1) ? 'selected' : ''; ?>>ACTIVO</option>
                        <option value="0" <?php echo ($item->status == 0) ? 'selected' : ''; ?>>INACTIVO</option>
                    </select>
                </td>
                <td>
                    <button type="submit">Actualizar</button>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>