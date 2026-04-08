<div>
    <h1>Nueva Entrada (Inbound)</h1>
    
    <p>
        <a href="<?= site_url('inbound') ?>">Inbound</a> / Nuevo
    </p>

    <form action="<?= site_url('inbound/add') ?>" method="post" id="form-inbound">
        <fieldset>
            <legend>Información General</legend>
            <table border="0" cellpadding="5">
                <tr>
                    <td>
                        <label>Tipo de Origen</label><br>
                        <select name="source_type_id" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach($sources as $src): ?>
                                <option value="<?= $src->id ?>"><?= $src->display_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <label>Almacén de Destino</label><br>
                        <select name="warehouse_id" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach($warehouses as $wh): ?>
                                <option value="<?= $wh->id ?>"><?= $wh->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <label>Fecha Esperada</label><br>
                        <input type="date" name="expected_date" value="<?= date('Y-m-d') ?>" required>
                    </td>
                    <td>
                        <label>Notas</label><br>
                        <input type="text" name="notes" placeholder="Referencia u observaciones">
                    </td>
                </tr>
            </table>
        </fieldset>

        <br>

        <fieldset>
            <legend>Ítems de Entrada</legend>
            <p align="right">
                <button type="button" onclick="addItemRow()">Añadir Ítem</button>
            </p>
            
            <table border="1" width="100%" cellpadding="5" cellspacing="0" id="items-table">
                <thead>
                    <tr>
                        <th width="60%">Producto / Ítem (Nombre - Código)</th>
                        <th width="25%">Cantidad Planificada</th>
                        <th width="15%">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    </tbody>
            </table>
            <p><small>* Seleccione el producto y defina la cantidad que espera recibir.</small></p>
        </fieldset>

        <br>

        <div align="right">
            <a href="<?= site_url('inbound') ?>">Cancelar</a>
            &nbsp;
            <button type="submit">Registrar Entrada</button>
        </div>
    </form>
</div>

<script>
    const productList = <?= json_encode($products); ?>; 

    function addItemRow() {
        const tableBody = document.querySelector('#items-table tbody');
        const rowId = Date.now();
        
        let productOptions = '<option value="">Seleccionar Producto...</option>';
        productList.forEach(function(prod) {
            const displayName = prod.name + " [" + prod.code + "]";
            productOptions += '<option value="' + prod.id + '">' + displayName + '</option>';
        });

        const row = document.createElement('tr');
        row.id = 'row_' + rowId;
        row.innerHTML = `
            <td>
                <select name="items[${rowId}][item_id]" required style="width: 95%;">
                    ${productOptions}
                </select>
            </td>
            <td>
                <input type="number" name="items[${rowId}][qty]" min="1" value="1" required> Utd
            </td>
            <td align="center">
                <button type="button" onclick="removeRow(${rowId})">Eliminar</button>
            </td>
        `;
        
        tableBody.appendChild(row);
    }

    function removeRow(id) {
        const row = document.getElementById('row_' + id);
        if (row) {
            row.parentNode.removeChild(row);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        addItemRow();
    });
</script>