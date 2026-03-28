<div>
    <h2>Detalle del Almacén: <?= $warehouse->name ?></h2>
    
    <a href="<?= base_url('warehouse') ?>">[ Volver al Listado ]</a> | 
    <a href="<?= base_url('warehouse/edit/'.$warehouse->id) ?>">[ Editar Información ]</a> | 
    
    <?php if($warehouse->is_active): ?>
        <a href="<?= base_url('warehouse/status/'.$warehouse->id.'/0') ?>" onclick="return confirm('¿Desea desactivar este almacén?');">[ Desactivar Almacén ]</a>
    <?php else: ?>
        <a href="<?= base_url('warehouse/status/'.$warehouse->id.'/1') ?>" onclick="return confirm('¿Desea activar este almacén nuevamente?');">[ Activar Almacén ]</a>
    <?php endif; ?>
</div>

<br>

<table border="1">
    <tr>
        <th width="200">Nombre del Almacén</th>
        <td><strong><?= $warehouse->name ?></strong></td>
    </tr>
    <tr>
        <th>Administrado por</th>
        <td><?= $warehouse->entity_name ?: 'VPR (Propio)' ?></td>
    </tr>
    <tr>
        <th>Dirección</th>
        <td><?= $warehouse->address ?></td>
    </tr>
    <tr>
        <th>Referencia de Ubicación</th>
        <td><?= $warehouse->location_info ?></td>
    </tr>
    <tr>
        <th>Estado Actual</th>
        <td>
            <strong><?= ($warehouse->is_active) ? 'ACTIVO' : 'INACTIVO' ?></strong>
        </td>
    </tr>
    <tr>
        <th>Última Actualización</th>
        <td><?= $warehouse->updated_at ?></td>
    </tr>
</table>