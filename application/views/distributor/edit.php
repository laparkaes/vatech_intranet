<h2>Editar Distribuidor: <?php echo $distributor->name; ?></h2>

<form action="<?php echo base_url('distributor/update'); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $distributor->id; ?>">

    <fieldset>
        <legend>Información de Registro (No editable)</legend>
        
        <label>País de Origen:</label>
        <input type="text" value="<?php echo $distributor->country; ?>" readonly>
        <input type="hidden" name="country_id" value="<?php echo $distributor->country_id; ?>">

        <br>

        <label>Tax ID / RUC:</label>
        <input type="text" name="tax_id" value="<?php echo $distributor->tax_id; ?>" readonly>
        <p><small>* El Tax ID y el país no pueden ser modificados por razones de integridad contable.</small></p>
    </fieldset>

    <fieldset>
        <legend>Roles y Estado Operativo</legend>
        
        <input type="checkbox" name="is_vendor" value="1" <?php echo ($distributor->is_vendor) ? 'checked' : ''; ?>>
        <label>Es Proveedor (Vendor)</label>
        
        <br>

        <input type="checkbox" name="is_dealer" value="1" <?php echo ($distributor->is_dealer) ? 'checked' : ''; ?>>
        <label>Es Distribuidor (Dealer)</label>
        
        <br><br>

        <label>Estado de la Entidad:</label>
        <select name="status">
            <option value="1" <?php echo ($distributor->status == 1) ? 'selected' : ''; ?>>Activo</option>
            <option value="0" <?php echo ($distributor->status == 0) ? 'selected' : ''; ?>>Inactivo</option>
        </select>
    </fieldset>

    <fieldset>
        <legend>Datos Actualizables</legend>
        
        <label>Nombre o Razón Social:</label>
        <input type="text" name="name" value="<?php echo $distributor->name; ?>" required>

        <br>

        <label>Teléfono Corporativo:</label>
        <input type="text" name="phone" value="<?php echo $distributor->phone; ?>">

        <br>

        <label>Celular/Móvil:</label>
        <input type="text" name="mobile" value="<?php echo $distributor->mobile; ?>">

        <br>

        <label>Sitio Web:</label>
        <input type="text" name="website" value="<?php echo $distributor->website; ?>">

        <br>

        <label>Dirección:</label>
        <textarea name="address"><?php echo $distributor->address; ?></textarea>

        <br>

        <label>Descripción/Notas:</label>
        <textarea name="description"><?php echo $distributor->description; ?></textarea>
    </fieldset>

    <br>

    <div>
        <button type="submit">Actualizar Información</button>
        <a href="<?php echo base_url('distributor/view/'.$distributor->id); ?>">
            <button type="button">Cancelar</button>
        </a>
    </div>
</form>