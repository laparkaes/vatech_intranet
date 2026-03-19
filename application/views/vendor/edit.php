<h2>Editar Proveedor: <?php echo $vendor->vendor_name; ?></h2>

<form action="<?php echo base_url('vendor/update'); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $vendor->id; ?>">

    <fieldset>
        <legend>Datos Maestros (Solo Lectura)</legend>
        
        <label>País de Origen:</label><br>
        <select disabled>
            <?php foreach($countries as $c): ?>
                <option value="<?php echo $c->id; ?>" <?php echo ($c->id == $vendor->country_id) ? 'selected' : ''; ?>>
                    <?php echo $c->country_name; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="country_id" value="<?php echo $vendor->country_id; ?>">
        <br>
        <small>* El país de origen no puede ser modificado.</small><br><br>

        <label>Tax ID / RUC:</label><br>
        <input type="text" value="<?php echo $vendor->tax_id; ?>" readonly><br>
        <small>* Para cambios en el RUC, contacte al administrador.</small>
    </fieldset>

    <br>

    <fieldset>
        <legend>Información Actualizable</legend>

        <label>Nombre Comercial / Razón Social:</label><br>
        <input type="text" name="vendor_name" value="<?php echo $vendor->vendor_name; ?>" required><br><br>

        <label>Teléfono de Oficina:</label><br>
        <input type="text" name="phone" value="<?php echo $vendor->phone; ?>"><br><br>

        <label>Celular / WhatsApp:</label><br>
        <input type="text" name="mobile" value="<?php echo $vendor->mobile; ?>"><br><br>

        <label>Sitio Web:</label><br>
        <input type="text" name="website" value="<?php echo $vendor->website; ?>"><br><br>

        <label>Estado Operativo:</label><br>
        <select name="status">
            <option value="1" <?php echo ($vendor->status == 1) ? 'selected' : ''; ?>>ACTIVO</option>
            <option value="0" <?php echo ($vendor->status == 0) ? 'selected' : ''; ?>>INACTIVO</option>
        </select><br><br>

        <label>Notas y Descripción Adicional:</label><br>
        <textarea name="description" rows="5" cols="60"><?php echo $vendor->description; ?></textarea>
    </fieldset>

    <br>
    
    <button type="submit">Guardar Cambios</button>
    <a href="<?php echo base_url('vendor/view/'.$vendor->id); ?>">
        <button type="button">Cancelar</button>
    </a>
</form>