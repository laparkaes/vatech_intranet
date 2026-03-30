<div>
    <h2>Editar y Re-enviar Orden de Compra: <?= htmlspecialchars($po->po_number) ?></h2>
    <a href="<?= base_url('purchase/view/'.$po->id) ?>">[ ← Cancelar y Volver ]</a>
</div>

<?php if (!empty($po->approver_comment)): ?>
<div>
    <h4>⚠️ Motivo de Rechazo / Observación:</h4>
    <p>
        <?= nl2br(htmlspecialchars($po->approver_comment)) ?>
    </p>
    <small>* Por favor, corrija los puntos mencionados antes de re-enviar.</small>
</div>
<?php endif; ?>

<form method="post" action="<?= base_url('purchase/update/'.$po->id) ?>">
    <input type="hidden" name="po_id" value="<?= $po->id ?>">
    <input type="hidden" name="po_number" value="<?= $po->po_number ?>">

    <fieldset>
        <legend>Información de Cabecera</legend>
        <table border="1">
            <tr>
                <th>Proveedor</th>
                <td>
                    <select name="supplier_id" required>
                        <?php foreach($suppliers as $s): ?>
                            <option value="<?= $s->id ?>" <?= ($s->id == $po->supplier_id) ? 'selected' : '' ?>><?= $s->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <th>Tipo de Compra</th>
                <td>
                    <select name="po_type" required>
                        <?php foreach($po_types as $pt): ?>
                            <option value="<?= $pt->id ?>" <?= ($pt->id == $po->po_type) ? 'selected' : '' ?>><?= $pt->display_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Moneda</th>
                <td>
                    <select name="currency" required>
                        <?php foreach($currencies as $c): ?>
                            <option value="<?= $c->id ?>" <?= ($c->id == $po->currency) ? 'selected' : '' ?>><?= $c->display_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <th>Incoterms</th>
                <td>
                    <select name="incoterms">
                        <option value="">-- No aplica --</option>
                        <?php foreach($incoterms as $in): ?>
                            <option value="<?= $in->id ?>" <?= ($in->id == $po->incoterms) ? 'selected' : '' ?>><?= $in->display_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Términos de Pago</th>
                <td>
                    <select name="payment_terms" required>
                        <?php foreach($payment_terms as $py): ?>
                            <option value="<?= $py->id ?>" <?= ($py->id == $po->payment_terms) ? 'selected' : '' ?>><?= $py->display_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <th>Método de Envío</th>
                <td>
                    <select name="shipping_method" required>
                        <?php foreach($shipping_methods as $sm): ?>
                            <option value="<?= $sm->id ?>" <?= ($sm->id == $po->shipping_method) ? 'selected' : '' ?>><?= $sm->display_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Fecha Emisión</th>
                <td><input type="date" name="issue_date" value="<?= $po->issue_date ?>" required></td>
                <th>Fecha Entrega Est.</th>
                <td><input type="date" name="expected_date" value="<?= $po->expected_date ?>"></td>
            </tr>
            <tr>
                <th>Notas / Ajustes</th>
                <td colspan="3">
                    <textarea name="notes" rows="2"><?= htmlspecialchars($po->notes) ?></textarea>
                </td>
            </tr>
        </table>
    </fieldset>

    <fieldset>
        <legend>Detalle de Ítems</legend>
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
                <?php if(!empty($po_items)): ?>
                    <?php foreach($po_items as $idx => $detail): ?>
                    <tr>
                        <td>
                            <input type="hidden" name="items[<?= $idx ?>][item_id]" value="<?= $detail->item_id ?>">
                            <strong><?= htmlspecialchars($detail->product_name) ?></strong><br>
                            <small><?= htmlspecialchars($detail->product_option) ?></small>
                        </td>
                        <td><input type="number" name="items[<?= $idx ?>][quantity]" value="<?= $detail->quantity ?>" min="0.01" step="0.01" required></td>
                        <td><input type="number" name="items[<?= $idx ?>][unit_price]" value="<?= $detail->unit_price ?>" step="0.01" required></td>
                        <td><input type="date" name="items[<?= $idx ?>][delivery_date]" value="<?= $detail->delivery_date ?>"></td>
                        <td>
                            <button type="button" onclick="removeRow(this)">X</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div>
            <button type="button" onclick="addRow()">+ Agregar Producto</button>
        </div>
    </fieldset>

    <div>
        <button type="submit">
            💾 Guardar Cambios y Solicitar Aprobación
        </button>
    </div>
</form>

<div id="item_template" style="display:none;">
    <table>
        <tr>
            <td>
                <select name="items[__INDEX__][item_id]" required>
                    <option value="">-- Seleccionar --</option>
                    <?php foreach($items as $p): ?>
                        <?php foreach($p->items as $i): ?>
                            <option value="<?= $i->item_id ?>"><?= $p->name ?> - <?= $i->option ?></option>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><input type="number" name="items[__INDEX__][quantity]" value="1" min="0.01" step="0.01" required></td>
            <td><input type="number" name="items[__INDEX__][unit_price]" value="0.00" step="0.01" required></td>
            <td><input type="date" name="items[__INDEX__][delivery_date]"></td>
            <td>
                <button type="button" onclick="removeRow(this)">X</button>
            </td>
        </tr>
    </table>
</div>

<script>
/**
 * Inicializa el índice basado en la cantidad de ítems existentes
 */
let rowIndex = <?= isset($po_items) ? count($po_items) : 0 ?>;

/**
 * Agrega una nueva fila a la tabla de ítems
 */
function addRow() {
    const tableBody = document.querySelector('#po_items_table tbody');
    const templateRow = document.querySelector('#item_template tr').cloneNode(true);
    
    // Reemplaza el marcador __INDEX__ con el valor actual
    templateRow.innerHTML = templateRow.innerHTML.replace(/__INDEX__/g, rowIndex);
    tableBody.appendChild(templateRow);
    
    rowIndex++;
}

/**
 * Elimina la fila seleccionada tras confirmar la acción
 */
function removeRow(btn) {
    const rows = document.querySelectorAll('#po_items_table tbody tr');
    if(rows.length > 1) {
        if(confirm('¿Está seguro de eliminar este ítem?')) {
            btn.closest('tr').remove();
        }
    } else {
        alert('La orden debe tener al menos un ítem.');
    }
}
</script>