<?php 
    // Recuperar datos previos en caso de error de validación (배열 형태)
    $old = $this->session->flashdata('old_input'); 
    // Obtener la tasa de cambio (Valor por defecto 3.80)
    $tasa_cambio = $current_rate->rate ?? 3.80;
?>

<h2>Registrar Nuevo Producto / Servicio</h2>

<form action="<?php echo base_url('product/add'); ?>" method="post" id="productForm">
    
    <fieldset>
        <legend>1. Información General</legend>
        
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

        <label>Código del Producto (Modelo):</label><br>
        <input type="text" name="code" value="<?php echo $old['code'] ?? ''; ?>" required>
        <br><br>

        <label>Nombre del Producto:</label><br>
        <input type="text" name="name" value="<?php echo $old['name'] ?? ''; ?>" required size="50">
        <br><br>

        <label>Marca:</label><br>
        <input type="text" name="brand" value="<?php echo $old['brand'] ?? 'Vatech'; ?>">
        <br><br>

        <label>País de Origen:</label><br>
        <select name="origin_country">
            <?php 
                $paises = ['Corea del Sur', 'Perú', 'Italia', 'China', 'EE.UU.', 'Otros'];
                foreach($paises as $p): 
                    $selected = (isset($old['origin_country']) && $old['origin_country'] == $p) ? 'selected' : '';
            ?>
                <option value="<?php echo $p; ?>" <?php echo $selected; ?>><?php echo $p; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Unidad de Medida:</label><br>
        <input type="text" name="unit" value="<?php echo $old['unit'] ?? 'EA'; ?>" size="10">
        <br><br>

        <label>Descripción:</label><br>
        <textarea name="description" rows="3" cols="60"><?php echo $old['description'] ?? ''; ?></textarea>
    </fieldset>

    <br>

    <fieldset>
        <legend>2. Detalles de Variantes y Precios</legend>
        
        <p>
            <strong>Tasa de Cambio Referencial:</strong> 1 USD = <?php echo number_format($tasa_cambio, 3); ?> PEN
            <input type="hidden" name="applied_rate" value="<?php echo $tasa_cambio; ?>">
        </p>

        <table border="1" id="variant_table" width="100%">
            <thead>
                <tr>
                    <th>SKU Code</th>
                    <th>Nombre Opción</th>
                    <th>Valor Opción</th>
                    <th>Peso (Kg)</th>
                    <th>P. Compra (USD)</th>
                    <th>P. Compra (PEN)</th>
                    <th>P. Venta (USD)</th>
                    <th>P. Venta (PEN)</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Si existen datos previos (error de validación), reconstruir las filas
                $num_filas = isset($old['sku_code']) ? count($old['sku_code']) : 1;
                for($i=0; $i < $num_filas; $i++): 
                ?>
                <tr>
                    <td><input type="text" name="sku_code[]" value="<?php echo $old['sku_code'][$i] ?? ''; ?>" required></td>
                    <td><input type="text" name="option_name[]" value="<?php echo $old['option_name'][$i] ?? ''; ?>" placeholder="Ej: Voltaje"></td>
                    <td><input type="text" name="option_value[]" value="<?php echo $old['option_value'][$i] ?? ''; ?>" placeholder="Ej: 220V"></td>
                    <td><input type="number" step="0.01" name="weight[]" value="<?php echo $old['weight'][$i] ?? ''; ?>" size="5"></td>
                    <td><input type="number" step="0.01" name="purchase_price_usd[]" class="price_input usd_input p_usd" value="<?php echo $old['purchase_price_usd'][$i] ?? ''; ?>"></td>
                    <td><input type="number" step="0.01" name="purchase_price_pen[]" class="price_input pen_input p_pen" value="<?php echo $old['purchase_price_pen'][$i] ?? ''; ?>"></td>
                    <td><input type="number" step="0.01" name="sale_price_usd[]" class="price_input usd_input s_usd" value="<?php echo $old['sale_price_usd'][$i] ?? ''; ?>"></td>
                    <td><input type="number" step="0.01" name="sale_price_pen[]" class="price_input pen_input s_pen" value="<?php echo $old['sale_price_pen'][$i] ?? ''; ?>"></td>
                    <td>
                        <?php if($i > 0): ?>
                            <button type="button" onclick="$(this).closest('tr').remove();">Eliminar</button>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        
        <br>
        <button type="button" id="add_row">Añadir Nueva Variante</button>
    </fieldset>

    <br>

    <div align="right">
        <button type="button" onclick="location.href='<?php echo base_url('product'); ?>'">Cancelar</button>
        <button type="submit">Registrar Producto</button>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const TASA = <?php echo $tasa_cambio; ?>;

    /**
	 * Manejo de eventos para el cálculo automático de precios.
	 * Se utiliza 'blur' (focusout) para evitar saltos de cursor mientras el usuario escribe.
	 */
	$(document).on('blur', '.price_input', function() {
		// Si la tasa de cambio es 0 o negativa, no realizar cálculos.
		if (TASA <= 0) return;

		let valorRaw = $(this).val();
		
		// Si el campo está vacío al salir, no realizar ninguna acción.
		if (valorRaw === "") return;

		let valor = parseFloat(valorRaw);
		let $fila = $(this).closest('tr');

		// Si el valor no es un número válido, asignar 0.
		if (isNaN(valor)) valor = 0;

		/**
		 * Lógica para campos en Dólares (USD)
		 */
		if ($(this).hasClass('usd_input')) {
			let resultadoPen = (valor * TASA).toFixed(2);
			
			if ($(this).hasClass('p_usd')) {
				// Actualizar Precio de Compra en Soles (PEN) solo si está vacío.
				let $p_pen = $fila.find('.p_pen');
				if ($p_pen.val() === "") {
					$p_pen.val(resultadoPen);
				}
			} else {
				// Actualizar Precio de Venta en Soles (PEN) solo si está vacío.
				let $s_pen = $fila.find('.s_pen');
				if ($s_pen.val() === "") {
					$s_pen.val(resultadoPen);
				}
			}

		/**
		 * Lógica para campos en Soles (PEN)
		 */
		} else if ($(this).hasClass('pen_input')) {
			let resultadoUsd = (valor / TASA).toFixed(2);
			
			if ($(this).hasClass('p_pen')) {
				// Actualizar Precio de Compra en Dólares (USD) solo si está vacío.
				let $p_usd = $fila.find('.p_usd');
				if ($p_usd.val() === "") {
					$p_usd.val(resultadoUsd);
				}
			} else {
				// Actualizar Precio de Venta en Dólares (USD) solo si está vacío.
				let $s_usd = $fila.find('.s_usd');
				if ($s_usd.val() === "") {
					$s_usd.val(resultadoUsd);
				}
			}
		}
	});

    // Agregar nueva fila de variante dinámicamente
    $('#add_row').click(function() {
        let filaHtml = `<tr>
            <td><input type="text" name="sku_code[]" required></td>
            <td><input type="text" name="option_name[]"></td>
            <td><input type="text" name="option_value[]"></td>
            <td><input type="number" step="0.01" name="weight[]"></td>
            <td><input type="number" step="0.01" name="purchase_price_usd[]" class="price_input usd_input p_usd"></td>
            <td><input type="number" step="0.01" name="purchase_price_pen[]" class="price_input pen_input p_pen"></td>
            <td><input type="number" step="0.01" name="sale_price_usd[]" class="price_input usd_input s_usd"></td>
            <td><input type="number" step="0.01" name="sale_price_pen[]" class="price_input pen_input s_pen"></td>
            <td><button type="button" onclick="$(this).closest('tr').remove();">Eliminar</button></td>
        </tr>`;
        $('#variant_table tbody').append(filaHtml);
    });
});
</script>