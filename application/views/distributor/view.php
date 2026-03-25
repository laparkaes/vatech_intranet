<h2>Detalles del Distribuidor: <?php echo $distributor->name; ?></h2>

<div>
    <a href="<?php echo base_url('distributor'); ?>"><button type="button">Volver al Listado</button></a>
    <a href="<?php echo base_url('distributor/edit/'.$distributor->id); ?>"><button type="button">Editar Información</button></a>
    <a href="<?php echo base_url('distributor/contacts/'.$distributor->id); ?>"><button type="button">Gestionar Contactos</button></a>
</div>

<br>

<table border="1">
    <tr>
        <th>Tax ID / RUC</th><td><?php echo $distributor->tax_id; ?></td>
        <th>Roles</th>
        <td>
            <?php if($distributor->is_vendor) echo "PROVEEDOR "; ?>
            <?php if($distributor->is_dealer) echo "DISTRIBUIDOR"; ?>
        </td>
        <th>Estado</th><td><?php echo ($distributor->status == 1) ? 'ACTIVO' : 'INACTIVO'; ?></td>
    </tr>
    <tr>
        <th>Nombre o Razón Social</th>
        <td colspan="5"><strong><?php echo $distributor->name; ?></strong></td>
    </tr>
    <tr>
        <th>País</th><td><?php echo $distributor->country; ?></td>
        <th>Telf. Fijo</th><td><?php echo $distributor->phone ? $distributor->phone : "-"; ?></td>
        <th>Celular</th><td><?php echo $distributor->mobile ? $distributor->mobile : "-"; ?></td>
    </tr>
    <tr>
        <th>Dirección</th>
        <td colspan="5"><?php echo $distributor->address ? $distributor->address : "-"; ?></td>
    </tr>
    <tr>
        <th>Website</th>
        <td colspan="5">
            <?php if($distributor->website): ?>
                <a href="<?php echo $distributor->website; ?>" target="_blank"><?php echo $distributor->website; ?></a>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th>Descripción / Notas</th>
        <td colspan="5"><?php echo nl2br($distributor->description); ?></td>
    </tr>
    <tr>
        <th>Registrado por</th>
        <td colspan="5">
            <?php echo !empty($distributor->creator_name) ? $distributor->creator_name : 'Sistema / Desconocido'; ?>
        </td>
    </tr>
</table>

<p>
    <small>Fecha de registro: <?php echo $distributor->created_at; ?></small>
</p>

<h3>Contactos del Distribuidor</h3>
<table border="1">
    <thead>
        <tr>
            <th>Estado</th>
            <th>Tipo</th>
            <th>Nombre</th>
            <th>Cargo</th>
            <th>Email</th>
            <th>Teléfono Directo</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($contacts)): ?>
            <?php foreach($contacts as $co): ?>
            <tr>
                <td>
                    <?php echo ($co->status == 1) ? 'Activo' : '<strong>Eliminado</strong>'; ?>
                </td>
                <td>
                    <?php echo ($co->is_main == 1) ? '<strong>Principal</strong>' : 'Adicional'; ?>
                </td>
                <td><?php echo $co->contact_name; ?></td>
                <td><?php echo $co->position; ?></td>
                <td><?php echo $co->email; ?></td>
                <td><?php echo $co->phone; ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No hay contactos registrados para este distribuidor.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>