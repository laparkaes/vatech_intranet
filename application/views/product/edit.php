<?php 
    /**
     * Obtener la tasa de cambio más reciente de la tabla 'exchange_rates'
     * Valor predeterminado de 3.762 según el último registro del sistema.
     */
    $tasa_sistema = $current_exchange->rate ?? 3.762;
?>

<h1>Editar Producto</h1>

<p>
    <a href="<?php echo base_url('product'); ?>">Volver al Listado</a> | 
    <a href="<?php echo base_url('product/view/'.$product->id); ?>">Ver Detalle</a>
</p>

<hr>

<form action="<?php echo base_url('product/update/'.$product->id); ?>" method="post" id="editProductForm">

    <fieldset>
        <legend>1. Información General</legend>
        <table border="1">
            <tr>
                <th>Tipo</th>
                <td>
                    <select name="type">
                        <option value="GOODS" <?php echo ($product->type == 'GOODS') ? 'selected' : ''; ?>>BIEN (GOODS)</option>
                        <option value="SERVICE" <?php echo ($product->type == 'SERVICE') ? 'selected' : ''; ?>>SERVICIO</option>
                    </select>
                </td>
                <th>Categoría</th>
                <td>
                    <select name="category_id" required>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat->id; ?>" <?php echo ($product->category_id == $cat->id) ? 'selected' : ''; ?>>
                                <?php echo $cat->category_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Código (Modelo)</th>
                <td><input type="text" name="code" value="<?php echo $product->code; ?>"></td>
                <th>Nombre del Producto</th>
                <td><input type="text" name="name" value="<?php echo $product->name; ?>" required></td>
            </tr>
            <tr>
                <th>Marca</th>
                <td><input type="text" name="brand" value="<?php echo $product->brand; ?>"></td>
                <th>País de Origen</th>
                <td><input type="text" name="origin_country" value="<?php echo $product->origin_country; ?>"></td>
            </tr>
            <tr>
                <th>Unidad de Medida</th>
                <td><input type="text" name="unit" value="<?php echo $product->unit; ?>"></td>
                <th>Estado General</th>
                <td>
                    <select name="is_active">
                        <option value="1" <?php echo ($product->is_active == 1) ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo ($product->is_active == 0) ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Descripción</th>
                <td colspan="3">
                    <textarea name="description" rows="3" cols="60"><?php echo $product->description; ?></textarea>
                </td>
            </tr>
        </table>
    </fieldset>

    <br>

    <fieldset>
        <legend>2. Variantes (SKUs) y Precios</legend>
        <p>Tasa de Cambio Actual del Sistema: <strong><?php echo number_format($tasa_sistema, 3); ?></strong></p>
        
        <table border="1" id="variant_table">
            <thead>
                <tr>
                    <th>SKU Code</th>
                    <th>Opción / Valor</th>
                    <th>Peso (Kg)</th>
                    <th>P. Compra USD</th>
                    <th>P. Compra PEN</th>
                    <th>P. Venta USD</th>
                    <th>P. Venta PEN</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($product->items)): ?>
                    <?php foreach($product->items as $item): ?>
                    <tr>
                        <input type="hidden" name="item_ids[]" value="<?php echo $item->id; ?>">
                        
                        <td><input type="text" name="sku_code[]" value="<?php echo $item->sku_code; ?>" required></td>
                        <td>
                            <input type="text" name="option_name[]" value="<?php echo $item->option_name; ?>" placeholder="Ej: Voltaje" size="10"> : 
                            <input type="text" name="option_value[]" value="<?php echo $item->option_value; ?>" placeholder="Ej: 220V" size="10">
                        </td>
                        <td><input type="number" step="0.001" name="weight[]" value="<?php echo $item->weight; ?>" size="6"></td>
                        
                        <td><input type="number" step="0.01" name="purchase_price_usd[]" class="price_input usd_input p_usd" value="<?php echo $item->purchase_price_usd; ?>"></td>
                        <td><input type="number" step="0.01" name="purchase_price_pen[]" class="price_input pen_input p_pen" value="<?php echo $item->purchase_price_pen; ?>"></td>
                        <td><input type="number" step="0.01" name="sale_price_usd[]" class="price_input usd_input s_usd" value="<?php echo $item->sale_price_usd; ?>"></td>
                        <td><input type="number" step="0.01" name="sale_price_pen[]" class="price_input pen_input s_pen" value="<?php echo $item->sale_price_pen; ?>"></td>
                        
                        <td><button type="button" onclick="removeRow(this)">Eliminar</button></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <button type="button" id="add_variant">Añadir Nueva Variante</button>
    </fieldset>

    <br>

    <p>
        <button type="submit">Guardar Todos los Cambios</button>
        <button type="button" onclick="window.history.back();">Cancelar</button>
    </p>

</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
/**
 * Lógica de Front-end para la gestión de precios y variantes
 */
$(document).ready(function() {
    // Tasa extraída directamente de la base de datos (exchange_rates)
    const TASA = <?php echo $tasa_sistema; ?>;

    /**
     * Añadir dinámicamente una nueva fila de variante
     */
    $('#add_variant').click(function() {
        let newRow = `<tr>
            <input type="hidden" name="item_ids[]" value="NEW">
            <td><input type="text" name="sku_code[]" required></td>
            <td><input type="text" name="option_name[]" size="10"> : <input type="text" name="option_value[]" size="10"></td>
            <td><input type="number" step="0.001" name="weight[]" size="6"></td>
            <td><input type="number" step="0.01" name="purchase_price_usd[]" class="price_input usd_input p_usd"></td>
            <td><input type="number" step="0.01" name="purchase_price_pen[]" class="price_input pen_input p_pen"></td>
            <td><input type="number" step="0.01" name="sale_price_usd[]" class="price_input usd_input s_usd"></td>
            <td><input type="number" step="0.01" name="sale_price_pen[]" class="price_input pen_input s_pen"></td>
            <td><button type="button" onclick="removeRow(this)">Eliminar</button></td>
        </tr>`;
        $('#variant_table tbody').append(newRow);
    });

    /**
     * Cálculo automático de conversión de moneda al perder el foco (blur)
     */
    $(document).on('blur', '.price_input', function() {
        if (TASA <= 0) return;
        let valorRaw = $(this).val();
        if (valorRaw === "") return;

        let valor = parseFloat(valorRaw);
        let $fila = $(this).closest('tr');
        if (isNaN(valor)) valor = 0;

        // Si se ingresa USD, calcular PEN automáticamente si el campo PEN está vacío
        if ($(this).hasClass('usd_input')) {
            let resPen = (valor * TASA).toFixed(2);
            let target = $(this).hasClass('p_usd') ? '.p_pen' : '.s_pen';
            if ($fila.find(target).val() === "") $fila.find(target).val(resPen);
        } 
        // Si se ingresa PEN, calcular USD automáticamente si el campo USD está vacío
        else if ($(this).hasClass('pen_input')) {
            let resUsd = (valor / TASA).toFixed(2);
            let target = $(this).hasClass('p_pen') ? '.p_usd' : '.s_usd';
            if ($fila.find(target).val() === "") $fila.find(target).val(resUsd);
        }
    });
});

/**
 * Confirmación antes de eliminar una fila de la vista
 */
function removeRow(btn) {
    if (confirm('¿Está seguro de eliminar esta variante?')) {
        $(btn).closest('tr').remove();
    }
}
</script>