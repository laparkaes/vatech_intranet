<div>
    <h2>Historial de Movimientos de Inventario (Kardex)</h2>
    <a href="<?= base_url('inventory') ?>">[ ← Volver al Stock Actual ]</a>
</div>

<br>

<form method="get" action="<?= base_url('inventory/kardex') ?>">
    <table border="1">
        <tr>
            <th>Almacén</th>
            <td>
                <select name="warehouse_id">
                    <option value="">-- Todos --</option>
                    <?php foreach($warehouses as $w): ?>
                        <option value="<?= $w->id ?>" <?= ($this->input->get('warehouse_id') == $w->id) ? 'selected' : '' ?>>
                            <?= $w->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <th>Desde (Inicio)</th>
            <td><input type="date" name="start_date" value="<?= $this->input->get('start_date') ?>"></td>
            <th>Hasta (Fin)</th>
            <td><input type="date" name="end_date" value="<?= $this->input->get('end_date') ?>"></td>
            <td>
                <button type="submit">Consultar Kardex</button>
            </td>
        </tr>
    </table>
</form>

<br>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>Fecha / Hora</th>
            <th>Almacén</th>
            <th>Producto (Opción)</th>
            <th>Tipo</th>
            <th>Estado</th>
            <th>Cant. Anterior</th>
            <th>Cambio</th>
            <th>Cant. Final</th>
            <th>Referencia / Motivo</th>
        </tr>
    </thead>
    <tbody>
        <?php if(empty($logs)): ?>
            <tr>
                <td colspan="9" align="center">No hay movimientos registrados para los filtros seleccionados.</td>
            </tr>
        <?php else: ?>
            <?php foreach($logs as $log): ?>
                <tr>
                    <td align="center"><?= $log->created_at ?></td>
                    <td><?= $log->warehouse_name ?></td>
                    <td>
                        <strong><?= $log->product_base_name ?></strong><br>
                        <small><?= $log->item_option ?></small>
                    </td>
                    <td align="center"><strong><?= $log->type ?></strong></td>
                    <td align="center"><?= $log->status_name ?></td>
                    <td align="right"><?= number_format($log->qty_before) ?></td>
                    <td align="right" style="color: <?= ($log->qty_change > 0) ? 'blue' : 'red' ?>;">
                        <?= ($log->qty_change > 0) ? '+' : '' ?><?= number_format($log->qty_change) ?>
                    </td>
                    <td align="right"><strong><?= number_format($log->qty_after) ?></strong></td>
                    <td><?= $log->reason ?> (ID Ref: <?= $log->reference_id ?: '-' ?>)</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>