<div>
    <h1>Confirmar Ingreso: <?= $inbound['header']->inbound_number ?></h1>
    
    <p>
        <a href="<?= site_url('inbound/view/'.$inbound['header']->id) ?>">[Cancelar]</a>
    </p>

    <form action="<?= site_url('inbound/update_process') ?>" method="post">
        <input type="hidden" name="inbound_id" value="<?= $inbound['header']->id ?>">
        
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <td valign="top" width="30%">
                    <h3>Información General</h3>
                    
                    <p>
                        <label>Nro. Entrada (Solo lectura)</label><br>
                        <input type="text" value="<?= $inbound['header']->inbound_number ?>" readonly>
                    </p>
                    
                    <p>
                        <label>Origen</label><br>
                        <input type="text" value="<?= $inbound['header']->source_type_name ?? 'N/A' ?>" readonly>
                    </p>
                    
                    <hr>
                    
                    <p>
                        <label>Almacén</label><br>
                        <select name="warehouse_id">
                            <?php foreach ($warehouses as $w): ?>
                                <option value="<?= $w->id ?>" <?= $w->id == $inbound['header']->warehouse_id ? 'selected' : '' ?>>
                                    <?= $w->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    
                    <p>
                        <label>Fecha Esperada</label><br>
                        <input type="date" name="expected_date" value="<?= $inbound['header']->expected_date ?>">
                    </p>
                    
                    <p>
                        <label>Notas</label><br>
                        <textarea name="notes" rows="3"><?= $inbound['header']->notes ?></textarea>
                    </p>
                </td>

                <td valign="top">
                    <h3>Confirmación de Items</h3>
                    <table border="1" cellpadding="5" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Plan</th>
                                <th>Recibida</th>
                                <th>Dañada</th>
                                <th>Ubicación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inbound['items'] as $index => $item): ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="items[<?= $index ?>][id]" value="<?= $item->id ?>">
                                    <input type="hidden" name="items[<?= $index ?>][expected_qty]" value="<?= $item->expected_qty ?>">
                                    <strong><?= $item->product_name ?></strong><br>
                                    <small><?= $item->product_option ?></small>
                                </td>
                                <td><?= number_format($item->expected_qty) ?></td>
                                <td>
                                    <input type="number" name="items[<?= $index ?>][received_qty]" value="<?= $item->received_qty ?>" min="0">
                                </td>
                                <td>
                                    <input type="number" name="items[<?= $index ?>][damaged_qty]" value="<?= $item->damaged_qty ?>" min="0">
                                </td>
                                <td>
                                    <input type="text" name="items[<?= $index ?>][bin_location]" value="<?= $item->bin_location ?>" placeholder="Bin">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <p>
                        <button type="submit">
                            <strong>Guardar y Finalizar Ingreso</strong>
                        </button>
                    </p>
                </td>
            </tr>
        </table>
    </form>
</div>