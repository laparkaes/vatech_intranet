<div>
    <h2>Gestión de Almacenes</h2>
    <a href="<?= base_url('warehouse/create') ?>">+ Registrar Nuevo Almacén</a>
</div>

<div>
    <form method="get" action="<?= base_url('warehouse/index') ?>">
        <input type="text" name="keyword" value="<?= htmlspecialchars($this->input->get('keyword')) ?>" placeholder="Buscar por nombre o dirección...">
        <button type="submit">Buscar</button>
        <?php if($this->input->get('keyword')): ?>
            <a href="<?= base_url('warehouse') ?>">Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<table border="1">
    <thead>
        <tr>
            <th>Nombre del Almacén</th>
            <th>Administrado por</th>
            <th>Dirección</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($warehouses)): ?>
            <?php foreach($warehouses as $row): ?>
                <tr>
                    <td><?= $row->name ?></td>
                    <td>
                        <?= $row->entity_name ?: 'VPR (Propio)' ?>
                    </td>
                    <td><?= $row->address ?></td>
                    <td>
                        <?= ($row->is_active) ? 'Activo' : 'Inactivo' ?>
                    </td>
                    <td>
                        <a href="<?= base_url('warehouse/view/'.$row->id) ?>">Ver Detalle</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No se encontraron registros de almacenes.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div>
    <?= $pagination_links ?>
</div>