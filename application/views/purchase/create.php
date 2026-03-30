<div>
    <h2>Nueva Orden de Compra (PO)</h2>
    <a href="<?= base_url('purchase') ?>">[ ← Volver a la Lista ]</a>
</div>

<form method="post" action="<?= base_url('purchase/add') ?>">
    <fieldset>
        <legend>Información General y Logística</legend>
        <table border="1">
            <tr>
                <th>Proveedor</th>
                <td>
                    <select name="supplier_id" required>
                        <option value="">-- Seleccionar --</option>
                        <?php foreach($suppliers as $s): ?>
                            <option value="<?= $s->id ?>"><?= $s->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <th>Tipo de Compra</th>
                <td>
                    <select name="po_type" id="po_type" onchange="toggleIncoterms()">
                        <option value="LOCAL">LOCAL (Nacional)</option>
                        <option value="IMPORTADO">IMPORTADO (Importación)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Moneda</th>
                <td>
                    <select name="currency">
                        <option value="USD">USD - Dólares</option>
                        <option value="PEN">PEN - Soles</option>
                    </select>
                </td>
                <th>Incoterms (Solo Importación)</th>
                <td>
                    <input type="text" name="incoterms" id="incoterms_field" placeholder="Ej: CIF, FOB" disabled>
                </td>
            </tr>
            <tr>
                <th>Términos de Pago</th>
                <td>
                    <select name="payment_terms">
                        <option value="Contado">Contado</option>
                        <option value="Net 30">Net 30 días</option>
                        <option value="Net 60">Net 60 días</option>
                        <option value="Anticipado">Pago Anticipado</option>
                    </select>
                </td>
                <th>Método de Envío</th>
                <td>
                    <select name="shipping_method">
                        <option value="MARÍTIMO">Marítimo (SEA)</option>
                        <option value="AÉREO">Aéreo (AIR)</option>
                        <option value="TERRESTRE">Terrestre (LAND)</option>
                        <option value="COURIER">Courier (FedEx/DHL)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Fecha Emisión</th>
                <td><input type="date" name="issue_date" value="<?= date('Y-m-d') ?>" required></td>
                <th>Fecha Entrega Estimada</th>
                <td><input type="date" name="expected_date"></td>
            </tr>
            <tr>
                <th>Notas / Observaciones</th>
                <td colspan="3">
                    <textarea name="notes" rows="2" placeholder="Instrucciones especiales..." style="width:100%"></textarea>
                </td>
            </tr>
        </table>
    </fieldset>

    <fieldset style="margin-top:20px;">
        <legend>Ítems de la Orden</legend>
        <table border="1" id="po_items_table" width="100%">
            <thead>
                <tr>
                    <th>Producto (Item)</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Fecha Entrega (Ítem)</th>
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
        <div style="margin-top:10px;">
            <button type="button" onclick="addRow()">+ Agregar Producto</button>
        </div>
    </fieldset>

    <div style="margin-top:20px;">
        <button type="submit">Guardar Orden de Compra (Borrador)</button>
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
                <button type="button" onclick="removeRow(this)">Eliminar</button>
            </td>
        </tr>
    </table>
</div>

<script>
/**
 * Índice para el control de filas dinámicas
 */
let rowIndex = 1;

/**
 * Agrega una nueva fila de producto a la tabla
 */
function addRow() {
    const tableBody = document.querySelector('#po_items_table tbody');
    const templateRow = document.querySelector('#item_template tr').cloneNode(true);
    
    // Reemplaza el marcador de posición con el índice actual
    templateRow.innerHTML = templateRow.innerHTML.replace(/__INDEX__/g, rowIndex);
    
    tableBody.appendChild(templateRow);
    rowIndex++;
}

/**
 * Elimina la fila seleccionada
 */
function removeRow(btn) {
    btn.closest('tr').remove();
}

/**
 * Activa o desactiva el campo Incoterms según el tipo de compra
 */
function toggleIncoterms() {
    const poType = document.getElementById('po_type').value;
    const incotermsField = document.getElementById('incoterms_field');
    
    // Si es importación, el campo es obligatorio
    if (poType === 'IMPORTADO') {
        incotermsField.disabled = false;
        incotermsField.required = true;
    } else {
        incotermsField.disabled = true;
        incotermsField.required = false;
        incotermsField.value = '';
    }
}
</script>