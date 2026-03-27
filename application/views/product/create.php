<?php 
    // Recuperar datos previos en caso de error de validación
    $old = $this->session->flashdata('old_input'); 
    
    // Obtener la tasa de cambio desde el controlador
    $tasa_cambio = $current_rate->rate ?? 0;
?>

<form action="<?php echo base_url('product/add'); ?>" method="post">
    
    <fieldset>
        <legend>Información del Producto</legend>
        
        <label>Tipo:</label><br>
        <select name="type">
            <option value="GOODS" <?php echo (isset($old['type']) && $old['type'] == 'GOODS') ? 'selected' : ''; ?>>BIEN (GOODS)</option>
            <option value="SERVICE" <?php echo (isset($old['type']) && $old['type'] == 'SERVICE') ? 'selected' : ''; ?>>SERVICIO</option>
        </select>
        <br><br>

        <label>Categoría:</label><br>
        <select name="category_id" required>
            <option value="">Seleccione Categoría</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?php echo $cat->id; ?>" <?php echo (isset($old['category_id']) && $old['category_id'] == $cat->id) ? 'selected' : ''; ?>>
                    <?php echo $cat->category_name; ?> 
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Nombre del Producto:</label><br>
        <input type="text" name="name" value="<?php echo $old['name'] ?? ''; ?>" required>
        <br><br>

        <label>Marca:</label><br>
        <input type="text" name="brand" value="<?php echo $old['brand'] ?? ''; ?>">
        <br><br>

        <label>País de Origen:</label><br>
        <select name="origin_country">
            <option value="">Seleccione País</option>
            <?php 
                $paises = ['Corea del Sur', 'Perú', 'China', 'EE.UU.', 'Otros'];
                foreach($paises as $p): 
                    $selected = (isset($old['origin_country']) && $old['origin_country'] == $p) ? 'selected' : '';
            ?>
                <option value="<?php echo $p; ?>" <?php echo $selected; ?>><?php echo $p; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Unidad de Medida:</label><br>
        <input type="text" name="unit" value="<?php echo $old['unit'] ?? 'Unit'; ?>">
        <br><br>
        
        <label>Descripción:</label><br>
        <textarea name="description" rows="3" cols="50"><?php echo $old['description'] ?? ''; ?></textarea>
    </fieldset>

    <br>

    <fieldset>
        <legend>Variantes y Precios</legend>
        
        <p>Tasa de Cambio Referencial: 1 USD = <?php echo number_format($tasa_cambio, 3); ?> PEN</p>
        <input type="hidden" name="applied_rate" value="<?php echo $tasa_cambio; ?>">

        <table border="1" id="variant_table">
            <thead>
                <tr>
                    <th>SKU Code</th>
                    <th>Opción</th>
                    <th>Valor</th>
                    <th>P. Compra (USD)</th>
                    <th>P. Compra (PEN)</th>
                    <th>P. Venta (USD)</th>
                    <th>P. Venta (PEN)</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($old['sku_code'])): ?>
                    <?php foreach($old['sku_code'] as $i => $sku): ?>
                    <tr>
                        <td><input type="text" name="sku_code[]" value="<?php echo $sku; ?>" required></td>
                        <td><input type="text" name="option_name[]" value="<?php echo $old['option_name'][$i] ?? ''; ?>"></td>
                        <td><input type="text" name="option_value[]" value="<?php echo $old['option_value'][$i] ?? ''; ?>"></td>
                        <td><input type="number" step="0.01" lang="en" name="purchase_price_usd[]" class="price_input usd_input p_usd" value="<?php echo $old['purchase_price_usd'][$i] ?? ''; ?>"></td>
                        <td><input type="number" step="0.01" lang="en" name="purchase_price_pen[]" class="price_input pen_input p_pen" value="<?php echo $old['purchase_price_pen'][$i] ?? ''; ?>"></td>
                        <td><input type="number" step="0.01" lang="en" name="sale_price_usd[]" class="price_input usd_input s_usd" value="<?php echo $old['sale_price_usd'][$i] ?? ''; ?>"></td>
                        <td><input type="number" step="0.01" lang="en" name="sale_price_pen[]" class="price_input pen_input s_pen" value="<?php echo $old['sale_price_pen'][$i] ?? ''; ?>"></td>
                        <td><button type="button" onclick="$(this).closest('tr').remove();">Eliminar</button></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td><input type="text" name="sku_code[]" required></td>
                        <td><input type="text" name="option_name[]"></td>
                        <td><input type="text" name="option_value[]"></td>
                        <td><input type="number" step="0.01" lang="en" name="purchase_price_usd[]" class="price_input usd_input p_usd"></td>
                        <td><input type="number" step="0.01" lang="en" name="purchase_price_pen[]" class="price_input pen_input p_pen"></td>
                        <td><input type="number" step="0.01" lang="en" name="sale_price_usd[]" class="price_input usd_input s_usd"></td>
                        <td><input type="number" step="0.01" lang="en" name="sale_price_pen[]" class="price_input pen_input s_pen"></td>
                        <td>-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <button type="button" id="add_row">Añadir Variante</button>
    </fieldset>

    <br>
    <button type="submit">Registrar Producto</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Tasa para cálculos automáticos en el cliente
    const TASA_CAMBIO = <?php echo $tasa_cambio; ?>;

    // Lógica de cálculo bidireccional
    $(document).on('input', '.price_input', function() {
        if (TASA_CAMBIO <= 0) return;
        let val = parseFloat($(this).val());
        let $row = $(this).closest('tr');

        if ($(this).hasClass('usd_input')) {
            let penVal = (val * TASA_CAMBIO).toFixed(2);
            if ($(this).hasClass('p_usd')) $row.find('.p_pen').val(penVal);
            else $row.find('.s_pen').val(penVal);
        } else if ($(this).hasClass('pen_input')) {
            let usdVal = (val / TASA_CAMBIO).toFixed(2);
            if ($(this).hasClass('p_pen')) $row.find('.p_usd').val(usdVal);
            else $row.find('.s_usd').val(usdVal);
        }
    });

    // Añadir nuevas filas a la tabla de variantes
    $('#add_row').click(function() {
        let row = `<tr>
            <td><input type="text" name="sku_code[]" required></td>
            <td><input type="text" name="option_name[]"></td>
            <td><input type="text" name="option_value[]"></td>
            <td><input type="number" step="0.01" lang="en" name="purchase_price_usd[]" class="price_input usd_input p_usd"></td>
            <td><input type="number" step="0.01" lang="en" name="purchase_price_pen[]" class="price_input pen_input p_pen"></td>
            <td><input type="number" step="0.01" lang="en" name="sale_price_usd[]" class="price_input usd_input s_usd"></td>
            <td><input type="number" step="0.01" lang="en" name="sale_price_pen[]" class="price_input pen_input s_pen"></td>
            <td><button type="button" onclick="$(this).closest('tr').remove();">Eliminar</button></td>
        </tr>`;
        $('#variant_table tbody').append(row);
    });
});
</script>