<?php if($this->session->flashdata('error')): ?>
    <div>
        <strong>Error:</strong> 
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<?php 
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
				<td>
					<select name="origin_country">
						<option value="">Seleccionar país</option>
						<option value="South Korea" <?php echo ($product->origin_country == 'South Korea') ? 'selected' : ''; ?>>Corea del Sur (South Korea)</option>
						<option value="China" <?php echo ($product->origin_country == 'China') ? 'selected' : ''; ?>>China</option>
						<option value="Italy" <?php echo ($product->origin_country == 'Italy') ? 'selected' : ''; ?>>Italia (Italy)</option>
						<option value="USA" <?php echo ($product->origin_country == 'USA') ? 'selected' : ''; ?>>EE.UU. (USA)</option>
						<option value="Peru" <?php echo ($product->origin_country == 'Peru') ? 'selected' : ''; ?>>Perú</option>
						</select>
				</td>
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
        <legend>2. Variantes (Opciones) y Precios</legend>
        <p>Tasa de Cambio: <?php echo number_format($tasa_sistema, 3); ?></p>
        
        <table border="1" id="variant_table">
            <thead>
                <tr>
                    <th>Opción</th>
                    <th>Dimensiones</th>
                    <th>Peso (Kg)</th>
                    <th>Compra USD</th>
                    <th>Compra PEN</th>
                    <th>Venta USD</th>
                    <th>Venta PEN</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($product->items)): ?>
                    <?php foreach($product->items as $item): ?>
                    <tr>
                        <input type="hidden" name="item_ids[]" value="<?php echo $item->id; ?>">
                        <td><input type="text" name="option[]" value="<?php echo $item->option; ?>" required></td>
                        <td><input type="text" name="dimensions[]" value="<?php echo $item->dimensions; ?>"></td>
                        <td><input type="number" step="0.001" name="weight[]" value="<?php echo $item->weight; ?>"></td>
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

    <div>
        <button type="submit">Guardar Cambios</button>
        <button type="button" onclick="window.history.back();">Cancelar</button>
    </div>

</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const TASA = <?php echo $tasa_sistema; ?>;

    $('#add_variant').click(function() {
        let newRow = `<tr>
            <input type="hidden" name="item_ids[]" value="NEW">
            <td><input type="text" name="option[]" required></td>
            <td><input type="text" name="dimensions[]"></td>
            <td><input type="number" step="0.001" name="weight[]"></td>
            <td><input type="number" step="0.01" name="purchase_price_usd[]" class="price_input usd_input p_usd"></td>
            <td><input type="number" step="0.01" name="purchase_price_pen[]" class="price_input pen_input p_pen"></td>
            <td><input type="number" step="0.01" name="sale_price_usd[]" class="price_input usd_input s_usd"></td>
            <td><input type="number" step="0.01" name="sale_price_pen[]" class="price_input pen_input s_pen"></td>
            <td><button type="button" onclick="removeRow(this)">Eliminar</button></td>
        </tr>`;
        $('#variant_table tbody').append(newRow);
    });

    $(document).on('blur', '.price_input', function() {
        if (TASA <= 0) return;
        let valorRaw = $(this).val();
        if (valorRaw === "") return;
        let valor = parseFloat(valorRaw);
        let $fila = $(this).closest('tr');
        if (isNaN(valor)) valor = 0;

        if ($(this).hasClass('usd_input')) {
            let resPen = (valor * TASA).toFixed(2);
            let target = $(this).hasClass('p_usd') ? '.p_pen' : '.s_pen';
            if ($fila.find(target).val() === "") $fila.find(target).val(resPen);
        } else if ($(this).hasClass('pen_input')) {
            let resUsd = (valor / TASA).toFixed(2);
            let target = $(this).hasClass('p_pen') ? '.p_usd' : '.s_usd';
            if ($fila.find(target).val() === "") $fila.find(target).val(resUsd);
        }
    });
});

function removeRow(btn) {
    if (confirm('¿Eliminar variante?')) {
        $(btn).closest('tr').remove();
    }
}
</script>