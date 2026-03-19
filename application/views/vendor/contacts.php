<h2>Gestión de Contactos: <?php echo $vendor->vendor_name; ?></h2>

<div>
    <a href="<?php echo base_url('vendor/view/'.$vendor->id); ?>"><button type="button">Volver a Detalles</button></a>
</div>

<br>

<fieldset>
    <legend>Registrar Nuevo Contacto</legend>
    <form action="<?php echo base_url('vendor/add_contact'); ?>" method="post">
        <input type="hidden" name="vendor_id" value="<?php echo $vendor->id; ?>">
        
        <label>Nombre:</label>
        <input type="text" name="contact_name" required>
        
        <label>Cargo:</label>
        <input type="text" name="position">
        
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Teléfono:</label>
        <input type="text" name="phone">
        
        <button type="submit">Añadir</button>
    </form>
</fieldset>

<br>

<table border="1">
    <thead>
        <tr>
            <th>Type</th>
            <th>Name</th>
            <th>Position</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($contacts as $co): ?>
        <tr>
            <td><?php echo ($co->is_main == 1) ? '<strong>Main</strong>' : 'Additional'; ?></td>
            <td><?php echo $co->contact_name; ?></td>
            <td><?php echo $co->position; ?></td>
            <td><?php echo $co->email; ?></td>
            <td><?php echo $co->phone; ?></td>
            <td>
                <?php if($co->is_main == 0): ?>
                    <a href="<?php echo base_url('vendor/delete_contact/'.$co->id.'/'.$vendor->id); ?>" 
                       onclick="return confirm('¿Está seguro de eliminar este contacto?');">
                        <button type="button">Eliminar</button>
                    </a>
                <?php else: ?>
                    <small>N/A (Principal)</small>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>