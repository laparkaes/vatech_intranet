<div style="display: flex; justify-content: space-between; align-items: center;">
    <h2>Detalle de Venta: <?= $sale->sales_number ?></h2>
    <div style="display: flex; gap: 10px; align-items: center;">
       <?php if ($sale->status_id != 41): ?>
		<a href="<?= base_url('sale/cancel/'.$sale->id) ?>" ...>ANULAR VENTA</a>
	<?php endif; ?>

	<strong style="color: <?= ($sale->status_id == 41) ? 'red' : 'inherit' ?>;">
		<?= $sale->status_name ?>
	</strong>
        
        <a href="<?= base_url('sale') ?>" style="text-decoration: none;">[ ← Volver a la Lista ]</a>
    </div>
</div>

<hr>

<div style="display: flex; gap: 40px;">
    <div style="flex: 1;">
        <h3>Información General</h3>
        <table border="1" width="100%" cellpadding="8">
            <tr>
                <th width="30%" bgcolor="#f2f2f2">Cliente</th>
                <td><?= $sale->customer_name ?></td>
            </tr>
            <tr>
                <th bgcolor="#f2f2f2">Fecha de Venta</th>
                <td><?= $sale->sales_date ?></td>
            </tr>
            <tr>
                <th bgcolor="#f2f2f2">Almacén</th>
                <td><?= $sale->warehouse_name ?></td>
            </tr>
            <tr>
                <th bgcolor="#f2f2f2">Estado</th>
                <td><strong><?= $sale->status_name ?></strong></td>
            </tr>
        </table>
    </div>

    <div style="flex: 1;">
        <h3>Información de Pago</h3>
        <table border="1" width="100%" cellpadding="8">
            <tr>
                <th width="30%" bgcolor="#f2f2f2">Moneda</th>
                <td><?= $sale->currency_name ?></td>
            </tr>
            <tr>
                <th bgcolor="#f2f2f2">Tasa de Cambio</th>
                <td><?= number_format($sale->exchange_rate, 4) ?></td>
            </tr>
            <tr>
                <th bgcolor="#f2f2f2">Monto Total</th>
                <td><strong style="font-size: 1.2em; color: blue;"><?= number_format($sale->total_amount, 2) ?></strong></td>
            </tr>
            <tr>
                <th bgcolor="#f2f2f2">Registrado por</th>
                <td><?= $sale->creator_name ?> (<?= $sale->created_at ?>)</td>
            </tr>
        </table>
    </div>
</div>

<br>

<h3>Ítems de Venta</h3>
<table border="1" width="100%" cellpadding="8" style="border-collapse: collapse;">
    <thead>
        <tr bgcolor="#f2f2f2">
            <th>No.</th>
            <th>Producto / Opción</th>
            <th>Código de Barras</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($items as $index => $item): ?>
            <tr>
                <td align="center"><?= $index + 1 ?></td>
                <td>
                    <strong><?= $item->product_name ?></strong><br>
                    <small><?= $item->item_option ?></small>
                </td>
                <td align="center"><?= $item->barcode ?: '-' ?></td>
                <td align="right"><?= number_format($item->quantity) ?></td>
                <td align="right"><?= number_format($item->unit_price, 2) ?></td>
                <td align="right"><strong><?= number_format($item->total_price, 2) ?></strong></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" align="right" bgcolor="#f2f2f2"><strong>TOTAL TOTAL</strong></td>
            <td align="right"><strong><?= number_format($sale->total_amount, 2) ?></strong></td>
        </tr>
    </tfoot>
</table>

<?php if($sale->notes): ?>
    <br>
    <fieldset>
        <legend>Notas</legend>
        <?= nl2br(htmlentities($sale->notes)) ?>
    </fieldset>
<?php endif; ?>