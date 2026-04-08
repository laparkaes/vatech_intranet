<div>
    <h1>Detalle de Entrada: <?= $inbound['header']->inbound_number ?></h1>
    
    <p>
        <a href="<?= site_url('inbound') ?>">[Volver a la Lista]</a>
    </p>

    <div>
        <?php if ($inbound['header']->status_id != 29): ?>
            <p>
                <a href="<?= site_url('inbound/edit/'.$inbound['header']->id) ?>">
                    <strong>[Editar / Confirmar Ingreso]</strong>
                </a>
            </p>
        <?php else: ?>
            <p>
                <strong>[Ingreso Finalizado - Edición Bloqueada]</strong>
            </p>
        <?php endif; ?>
    </div>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <td valign="top">
                <h3>Datos de Entrada</h3>
                <table border="0">
                    <tr>
                        <td><strong>Nro. Entrada:</strong></td>
                        <td><?= $inbound['header']->inbound_number ?></td>
                    </tr>
                    <tr>
                        <td><strong>Origen:</strong></td>
                        <td><?= $inbound['header']->source_type_name ?? 'N/A' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Almacén:</strong></td>
                        <td><?= $inbound['header']->warehouse_name ?></td>
                    </tr>
                    <tr>
                        <td><strong>Fecha Esperada:</strong></td>
                        <td><?= $inbound['header']->expected_date ?></td>
                    </tr>
                    <tr>
                        <td><strong>Fecha Llegada:</strong></td>
                        <td><?= $inbound['header']->arrival_date ? date('Y-m-d H:i', strtotime($inbound['header']->arrival_date)) : '-' ?></td>
                    </tr>
                    <tr>
                        <td><strong>Estado Actual:</strong></td>
                        <td><?= $inbound['header']->status_name ?></td>
                    </tr>
                </table>
                
                <p><strong>Notas:</strong></p>
                <p>
                    <?= !empty($inbound['header']->notes) ? nl2br(htmlspecialchars($inbound['header']->notes)) : 'Sin observaciones.' ?>
                </p>
            </td>
        </tr>
    </table>

    <br>

    <h3>Lista de Productos</h3>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Bin</th>
                <th>Plan</th>
                <th>Recibida</th>
                <th>Dañada</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inbound['items'] as $item): ?>
            <tr>
                <td>
                    <strong><?= $item->product_name ?></strong><br>
                    <small><?= $item->product_option ?></small>
                </td>
                <td><?= $item->bin_location ?? '-' ?></td>
                <td><?= number_format($item->expected_qty) ?></td>
                <td><?= number_format($item->received_qty) ?></td>
                <td><?= number_format($item->damaged_qty) ?></td>
                <td><?= $item->item_status_name ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>