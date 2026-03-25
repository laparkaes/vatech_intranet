<h2>Editar Entidad: <?php echo $entity->name; ?></h2>

<form action="<?php echo base_url('entity/update'); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $entity->id; ?>">

    <fieldset>
        <legend>Información de Registro (No editable)</legend>
        
        <label>País de Origen:</label>
        <input type="text" value="<?php echo $entity->country; ?>" readonly>
        <input type="hidden" name="country_id" value="<?php echo $entity->country_id; ?>">

        <br>

        <label>Tax ID / RUC:</label>
        <input type="text" name="tax_id" value="<?php echo $entity->tax_id; ?>" readonly>
        <p><small>* El Tax ID y el país no pueden ser modificados por razones de integridad contable.</small></p>
    </fieldset>

    <fieldset>
        <legend>Roles y Estado Operativo</legend>
        
        <input type="checkbox" name="is_vendor" value="1" <?php echo ($entity->is_vendor) ? 'checked' : ''; ?>>
        <label>Es Proveedor (Vendor)</label>
        
        <br>

        <input type="checkbox" name="is_dealer" value="1" <?php echo ($entity->is_dealer) ? 'checked' : ''; ?>>
        <label>Es Distribuidor (Dealer)</label>
        
        <br><br>

        <label>Estado de la Entidad:</label>
        <select name="status">
            <option value="1" <?php echo ($entity->status == 1) ? 'selected' : ''; ?>>Activo</option>
            <option value="0" <?php echo ($entity->status == 0) ? 'selected' : ''; ?>>Inactivo</option>
        </select>
    </fieldset>

    <fieldset>
        <legend>Datos Actualizables</legend>
        
        <label>Nombre o Razón Social:</label>
        <input type="text" name="name" value="<?php echo $entity->name; ?>" required>

        <br>

        <label>Teléfono Corporativo:</label>
        <input type="text" name="phone" value="<?php echo $entity->phone; ?>">

        <br>

        <label>Celular/Móvil:</label>
        <input type="text" name="mobile" value="<?php echo $entity->mobile; ?>">

        <br>

        <label>Sitio Web:</label>
        <input type="text" name="website" value="<?php echo $entity->website; ?>">

        <br>

        <label>Dirección:</label>
        <textarea name="address"><?php echo $entity->address; ?></textarea>

        <br>

        <label>Descripción/Notas:</label>
        <textarea name="description"><?php echo $entity->description; ?></textarea>
    </fieldset>

    <br>

    <div>
        <button type="submit">Actualizar Información</button>
        <a href="<?php echo base_url('entity/view/'.$entity->id); ?>">
            <button type="button">Cancelar</button>
        </a>
    </div>
</form>