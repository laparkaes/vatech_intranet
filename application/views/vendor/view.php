<h2><?php echo $vendor->name; ?></h2>

<div>
    <a href="<?php echo base_url('vendor'); ?>"><button type="button">Volver al Listado</button></a>
    <a href="<?php echo base_url('vendor/edit/'.$vendor->id); ?>"><button type="button">Editar Información</button></a>
    <a href="<?php echo base_url('vendor/contacts/'.$vendor->id); ?>"><button type="button">Gestionar Contactos</button></a>
</div>

<table border="1">
    <tr>
        <th>Tax ID / RUC</th><td><?php echo $vendor->tax_id; ?></td>
        <th>Roles</th>
        <td>
            <?php if($vendor->is_vendor) echo "PROVEEDOR "; ?>
            <?php if($vendor->is_dealer) echo "DISTRIBUIDOR"; ?>
        </td>
        <th>Estado</th><td><?php echo ($vendor->status == 1) ? 'ACTIVO' : 'INACTIVO'; ?></td>
    </tr>
    <tr>
        <th>Nombre</th>
        <td colspan="5"><strong><?php echo $vendor->name; ?></strong></td>
    </tr>
    <tr>
        <th>País</th><td><?php echo $vendor->country; ?></td>
        <th>Telf.</th><td><?php echo $vendor->phone ? $vendor->phone : "-"; ?></td>
        <th>Cel.</th><td><?php echo $vendor->mobile ? $vendor->mobile : "-"; ?></td>
    </tr>
    <tr>
        <th>Website</th><td colspan="5"><?php echo $vendor->website ? $vendor->website : "-"; ?></td>
    </tr>
    <tr>
        <th>Descripción</th>
        <td colspan="5"><?php echo nl2br($vendor->description); ?></td>
    </tr>
    <tr>
        <th>Registrado por</th>
        <td colspan="5">
            <?php echo !empty($vendor->creator_name) ? $vendor->creator_name : 'Sistema / Desconocido'; ?>
        </td>
    </tr>
</table>

<p>
    <small>Created at: <?php echo $vendor->created_at; ?></small>
</p>

<h3>Contactos Registrados</h3>
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
                <td colspan="6">No hay contactos registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>