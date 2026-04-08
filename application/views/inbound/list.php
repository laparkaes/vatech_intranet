<div>
    <h1>Gestión de Entradas (Inbound)</h1>
    <p>
        <a href="<?= site_url('inbound/create') ?>">[+] Nueva Entrada</a>
    </p>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Nro. Entrada</th>
                <th>Origen</th>
                <th>Almacén Destino</th>
                <th>Fecha Esperada</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($list)): ?>
                <?php foreach ($list as $row): ?>
                <tr>
                    <td><strong><?= $row->inbound_number ?></strong></td>
                    <td><?= $row->source_type_name ?></td>
                    <td><?= $row->warehouse_name ?></td>
                    <td><?= $row->expected_date ?></td>
                    <td><?= $row->status_name ?></td>
                    <td>
                        <a href="<?= site_url('inbound/view/'.$row->id) ?>">Ver</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" align="center">No hay datos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>