<div style="display: flex; justify-content: space-between; align-items: center;">
    <h2>Detalle de Salida (Outbound): <?= $outbound->outbound_number ?></h2>
    <div>
        <a href="<?= base_url('outbound') ?>">[ ← Volver 목록 ]</a>
    </div>
</div>

<hr>

<div style="display: flex; gap: 40px;">
    <div style="flex: 1;">
        <h3>Información de la Orden</h3>
        <table border="1" width="100%" cellpadding="8" style="border-collapse: collapse;">
            <tr>
                <th width="35%" bgcolor="#f2f2f2">Referencia de Venta</th>
                <td><?= $outbound->sales_number ?></td>
            </tr>
            <tr>
                <th bgcolor="#f2f2f2">Cliente</th>
                <td><?= $outbound->customer_name ?></td>
            </tr>
            <tr>
                <th bgcolor="#f2f2f2">Almacén de Salida</th>
                <td><?= $outbound->warehouse_name ?></td>
            </tr>
        </table>
    </div>

    <div style="flex: 1;">
        <h3>Estado de Envío</h3>
        <table border="1" width="100%" cellpadding="8" style="border-collapse: collapse;">
            <tr>
                <th width="35%" bgcolor="#f2f2f2">Estado Actual</th>
                <td><strong><?= $outbound->status_name ?></strong></td>
            </tr>
            <tr>
                <th bgcolor="#f2f2f2">Fecha de Creación</th>
                <td><?= $outbound->created_at ?></td>
            </tr>
            <tr>
                <th bgcolor="#f2f2f2">Fecha de Despacho</th>
                <td><?= $outbound->shipment_date ?: '<span style="color:orange;">Pendiente</span>' ?></td>
            </tr>
        </table>
    </div>
</div>

<br>

<form action="<?= base_url('outbound/confirm_shipment/' . $outbound->id) ?>" method="post" onsubmit="return confirm('¿Confirmar el despacho? Se actualizará el stock real.');">
    <h3>Ítems para Despachar</h3>
    <table border="1" width="100%" cellpadding="8" style="border-collapse: collapse;">
        <thead>
            <tr bgcolor="#f2f2f2">
                <th>No.</th>
                <th>Producto / Opción</th>
                <th>Estado Inventario</th>
                <th width="150">Cant. Solicitada</th>
                <th width="150">Cant. a Despachar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $index => $item): ?>
                <tr>
                    <td align="center"><?= $index + 1 ?></td>
                    <td>
                        <strong><?= $item->product_name ?></strong><br>
                        <small><?= $item->item_option ?></small>
                        <input type="hidden" name="items[<?= $index ?>][oi_id]" value="<?= $item->id ?>">
                        <input type="hidden" name="items[<?= $index ?>][item_id]" value="<?= $item->item_id ?>">
                        <input type="hidden" name="items[<?= $index ?>][status_id]" value="<?= $item->item_status_id ?>">
                    </td>
                    <td align="center"><?= $item->item_status_name ?></td>
                    <td align="right"><?= number_format($item->quantity) ?></td>
                    <td align="center">
                        <?php if ($outbound->status_id == 1): // PENDING ?>
                            <input type="number" name="items[<?= $index ?>][ship_qty]" 
                                   value="<?= $item->quantity ?>" 
                                   min="0" max="<?= $item->quantity ?>" 
                                   style="width: 80px; text-align: right; padding: 5px;">
                        <?php else: ?>
                            <?= number_format($item->quantity) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($outbound->status_id == 1): ?>
        <div style="text-align: right; margin-top: 20px;">
            <button type="submit" style="background-color: #28a745; color: white; padding: 12px 25px; font-weight: bold; border: none; border-radius: 4px; cursor: pointer;">
                CONFIRMAR Y DESPACHAR (Shipment)
            </button>
        </div>
    <?php endif; ?>
</form>
