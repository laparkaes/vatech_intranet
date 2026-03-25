<h2>Detalles de la Entidad: <?php echo $entity->name; ?></h2>

<div>
    <a href="<?php echo base_url('entity'); ?>"><button type="button">Volver al Listado</button></a>
    <a href="<?php echo base_url('entity/edit/'.$entity->id); ?>"><button type="button">Editar Información</button></a>
    <a href="<?php echo base_url('entity/contacts/'.$entity->id); ?>"><button type="button">Gestionar Contactos</button></a>
</div>

<br>

<table border="1">
    <tr>
        <th>Tax ID / RUC</th><td><?php echo $entity->tax_id; ?></td>
        <th>Roles</th>
        <td>
            <?php if($entity->is_vendor) echo "PROVEEDOR "; ?>
            <?php if($entity->is_dealer) echo "DISTRIBUIDOR"; ?>
            <?php if(!$entity->is_vendor && !$entity->is_dealer) echo "-"; ?>
        </td>
        <th>Estado</th><td><?php echo ($entity->status == 1) ? 'ACTIVO' : 'INACTIVO'; ?></td>
    </tr>
    <tr>
        <th>Nombre o Razón Social</th>
        <td colspan="5"><strong><?php echo $entity->name; ?></strong></td>
    </tr>
    <tr>
        <th>País</th><td><?php echo $entity->country; ?></td>
        <th>Telf. Fijo</th><td><?php echo $entity->phone ? $entity->phone : "-"; ?></td>
        <th>Celular</th><td><?php echo $entity->mobile ? $entity->mobile : "-"; ?></td>
    </tr>
    <tr>
        <th>Dirección</th>
        <td colspan="5"><?php echo $entity->address ? $entity->address : "-"; ?></td>
    </tr>
    <tr>
        <th>Website</th>
        <td colspan="5">
            <?php if($entity->website): ?>
                <a href="<?php echo $entity->website; ?>" target="_blank"><?php echo $entity->website; ?></a>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th>Descripción / Notas</th>
        <td colspan="5"><?php echo nl2br($entity->description); ?></td>
    </tr>
    <tr>
        <th>Registrado por</th>
        <td colspan="5">
            <?php echo !empty($entity->creator_name) ? $entity->creator_name : 'Sistema / Desconocido'; ?>
        </td>
    </tr>
</table>

<p>
    <small>Fecha de registro: <?php echo $entity->created_at; ?></small>
</p>

<h3>Contactos de la Entidad</h3>
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
                <td colspan="6">No hay contactos registrados para esta entidad.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>