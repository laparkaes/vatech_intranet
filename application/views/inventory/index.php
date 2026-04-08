<div>
    <h2>Estado Actual del Inventario (Stock)</h2>
    
    <div>
        <a href="<?= base_url('inventory/kardex') ?>">[ Ver Historial de Movimientos ]</a>
    </div>
    
    <br>

    <form method="get" action="<?= base_url('inventory') ?>">
        <fieldset>
            <legend>Filtros de Búsqueda</legend>
            Almacén: 
            <select name="warehouse_id">
                <option value="">-- Todos los Almacenes --</option>
                <?php foreach($warehouses as $w): ?>
                    <option value="<?= $w->id ?>" <?= ($this->input->get('warehouse_id') == $w->id) ? 'selected' : '' ?>>
                        <?= $w->name ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            Producto: 
            <input type="text" name="product_name" value="<?= $this->input->get('product_name') ?>" placeholder="Nombre del producto">
            
            <button type="submit">Buscar</button>
            <a href="<?= base_url('inventory') ?>">[ Limpiar ]</a>
        </fieldset>
    </form>
</div>

<br>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>Almacén</th>
            <th>Código / Barcode</th>
            <th>Producto (Opción)</th>
            <th>Estado Lógico</th>
            <th>Ubicación (Bin)</th>
            <th>Stock Actual</th>
            <th>Última Actualización</th>
        </tr>
    </thead>
    <tbody>
        <?php if(empty($inventory)): ?>
            <tr>
                <td colspan="7" align="center">No se encontraron registros de stock.</td>
            </tr>
        <?php else: ?>
            <?php foreach($inventory as $item): ?>
                <tr>
                    <td><?= $item->warehouse_name ?></td>
                    <td><?= $item->barcode ?: '-' ?></td>
                    <td>
                        <strong><?= $item->product_base_name ?></strong><br>
                        <small><?= $item->item_option ?></small>
                    </td>
                    <td><?php echo $item->status_name; ?></td>
                    <td align="center"><?= $item->bin_location ?: '-' ?></td>
                    <td align="right"><strong><?= number_format($item->quantity) ?></strong></td>
                    <td align="center"><?= $item->updated_at ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>