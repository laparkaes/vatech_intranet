<div>
    <h2>Gestión de Salidas (Outbound)</h2>
</div>

<form method="get" action="<?= base_url('outbound') ?>">
    <fieldset>
        <legend>Filtros</legend>
        Almacén: 
        <select name="warehouse_id">
            <option value="">-- Todos --</option>
            <?php foreach($warehouses as $w): ?>
                <option value="<?= $w->id ?>" <?= ($this->input->get('warehouse_id') == $w->id) ? 'selected' : '' ?>>
                    <?= $w->name ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Buscar</button>
        <a href="<?= base_url('outbound') ?>">[ Limpiar ]</a>
    </fieldset>
</form>

<br>

<table border="1" width="100%" cellpadding="8" style="border-collapse: collapse;">
    <thead>
        <tr bgcolor="#f2f2f2">
            <th>No. Salida</th>
            <th>Ref. Venta</th>
            <th>Cliente</th>
            <th>Almacén</th>
            <th>Estado</th>
            <th>Fecha Creación</th>
            <th>Fecha Despacho</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if(empty($list)): ?>
            <tr>
                <td colspan="8" align="center">No se encontraron órdenes de salida.</td>
            </tr>
        <?php else: ?>
            <?php foreach($list as $row): ?>
                <tr>
                    <td align="center"><strong><?= $row->outbound_number ?></strong></td>
                    <td align="center"><?= $row->sales_number ?: '-' ?></td>
                    <td><?= $row->customer_name ?: 'N/A' ?></td>
                    <td><?= $row->warehouse_name ?></td>
                    <td align="center">
                        <span style="padding: 3px 8px; border-radius: 4px; background: #eee;">
                            <?= $row->status_name ?>
                        </span>
                    </td>
                    <td align="center"><?= date('Y-m-d', strtotime($row->created_at)) ?></td>
                    <td align="center"><?= $row->shipment_date ? date('Y-m-d', strtotime($row->shipment_date)) : '<span style="color:orange;">Pendiente</span>' ?></td>
                    <td align="center">
                        <a href="<?= base_url('outbound/view/'.$row->id) ?>">[ Ver Detalle ]</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>