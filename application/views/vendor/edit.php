<h2>Editar: <?php echo $vendor->vendor_name; ?></h2>

<form action="<?php echo base_url('vendor/update'); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $vendor->id; ?>">

    <fieldset>
        <legend>Roles y Estado</legend>
        <label><input type="checkbox" name="is_vendor" value="1" <?php echo $vendor->is_vendor ? 'checked' : ''; ?>> Proveedor</label>
        <label><input type="checkbox" name="is_dealer" value="1" <?php echo $vendor->is_dealer ? 'checked' : ''; ?>> Distribuidor</label>
        <label style="margin-left:20px;">Estado:</label>
        <select name="status">
            <option value="1" <?php echo ($vendor->status == 1) ? 'selected' : ''; ?>>ACTIVO</option>
            <option value="0" <?php echo ($vendor->status == 0) ? 'selected' : ''; ?>>INACTIVO</option>
        </select>
    </fieldset><br>

    <fieldset>
        <legend>Datos Actualizables</legend>
        <label>Nombre / Razón Social:</label><br>
        <input type="text" name="vendor_name" value="<?php echo $vendor->vendor_name; ?>" required><br><br>

        <label>Telf. Fijo:</label> <input type="text" name="phone" value="<?php echo $vendor->phone; ?>">
        <label>Celular:</label> <input type="text" name="mobile" value="<?php echo $vendor->mobile; ?>"><br><br>

        <label>Website:</label><br>
        <input type="text" name="website" value="<?php echo $vendor->website; ?>"><br><br>

        <label>Notas:</label><br>
        <textarea name="description" rows="3" cols="50"><?php echo $vendor->description; ?></textarea>
    </fieldset><br>

    <button type="submit">Actualizar Información</button>
</form>