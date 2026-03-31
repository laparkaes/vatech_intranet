<div>
    <h2>Nueva Orden de Compra (PO)</h2>
    <div>
        <a href="<?= base_url('purchase') ?>">[ ← Volver a la Lista ]</a>
    </div>
</div>

<form method="post" action="<?= base_url('purchase/add') ?>">
    <fieldset>
        <legend>Información General y Logística</legend>
        <table border="1">
            <tr>
                <th>Proveedor</th>
                <td>
                    <select name="supplier_id" required>
                        <option value="">-- Seleccionar Proveedor --</option>
                        <?php foreach($suppliers as $s): ?>
                            <option value="<?= $s->id ?>"><?= $s->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <th>Tipo de Compra</th>
                <td>
                    <select name="po_type" id="po_type" required>
                        <?php foreach($po_types as $pt): ?>
                            <option value="<?= $pt->id ?>"><?= $pt->display_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Moneda</th>
                <td>
                    <select name="currency" required>
                        <?php foreach($currencies as $c): ?>
                            <option value="<?= $c->id ?>"><?= $c->display_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <th>Incoterms</th>
                <td>
                    <select name="incoterms" id="incoterms_field">
                        <option value="">-- No aplica / Seleccionar --</option>
                        <?php foreach($incoterms as $in): ?>
                            <option value="<?= $in->id ?>"><?= $in->display_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Términos de Pago</th>
                <td>
                    <select name="payment_terms" required>
                        <?php foreach($payment_terms as $py): ?>
                            <option value="<?= $py->id ?>"><?= $py->display_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <th>Método de Envío</th>
                <td>
                    <select name="shipping_method" required>
                        <?php foreach($shipping_methods as $sm): ?>
                            <option value="<?= $sm->id ?>"><?= $sm->display_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Fecha Emisión</th>
                <td><input type="date" name="issue_date" value="<?= date('Y-m-d') ?>" required></td>
                <th>Fecha Entrega Est.</th>
                <td><input type="date" name="expected_date"></td>
            </tr>
			<tr>
                <th>Almacén de Destino</th>
                <td colspan="3">
                    <select name="warehouse_id" required>
                        <option value="">-- Seleccionar Almacén de Destino --</option>
                        <?php foreach($warehouses as $w): ?>
                            <option value="<?= $w->id ?>"><?= $w->name ?> (<?= $w->address ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: blue;">* Este será el almacén donde se generará el ingreso (Inbound) tras la aprobación.</small>
                </td>
            </tr>
            <tr>
                <th>Notas</th>
                <td colspan="3">
                    <textarea name="notes" rows="2" placeholder="Notas internas o instrucciones de envío..."></textarea>
                </td>
            </tr>
        </table>
    </fieldset>

    <fieldset>
        <legend>Ítems de la Orden</legend>
        <table border="1" id="po_items_table">
            <thead>
                <tr>
                    <th>Producto (Item)</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Fecha Entrega</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr class="item-row">
                    <td>
                        <select name="items[0][item_id]" required>
                            <option value="">-- Seleccionar Item --</option>
                            <?php foreach($items as $i): ?>
                                <option value="<?= $i->id ?>"><?= $i->name ?> - <?= $i->option ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="number" name="items[0][quantity]" value="1" min="1" required></td>
                    <td><input type="number" name="items[0][unit_price]" step="0.01" required></td>
                    <td><input type="date" name="items[0][delivery_date]"></td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
        
        <div>
            <button type="button" onclick="addRow()">+ Agregar Producto</button>
        </div>
    </fieldset>

    <div>
        <button type="submit">
            Registrar Orden de Compra
        </button>
    </div>
</form>

<div id="item_template" style="display:none;">
    <table>
        <tr>
            <td>
                <select name="items[__INDEX__][item_id]" required>
                    <option value="">-- Seleccionar Item --</option>
                    <?php foreach($items as $i): ?>
                        <option value="<?= $i->id ?>"><?= $i->name ?> - <?= $i->option ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><input type="number" name="items[__INDEX__][quantity]" value="1" min="1" required></td>
            <td><input type="number" name="items[__INDEX__][unit_price]" step="0.01" required></td>
            <td><input type="date" name="items[__INDEX__][delivery_date]"></td>
            <td>
                <button type="button" onclick="removeRow(this)">X</button>
            </td>
        </tr>
    </table>
</div>

<script>
let rowIndex = 1;

function addRow() {
    const tableBody = document.querySelector('#po_items_table tbody');
    const templateRow = document.querySelector('#item_template tr').cloneNode(true);
    templateRow.innerHTML = templateRow.innerHTML.replace(/__INDEX__/g, rowIndex);
    tableBody.appendChild(templateRow);
    rowIndex++;
}

function removeRow(btn) {
    if(confirm('¿Está seguro de eliminar este ítem?')) {
        btn.closest('tr').remove();
    }
}
</script>