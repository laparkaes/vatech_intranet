<h2>Registrar Socio de Negocio</h2>

<form action="<?php echo base_url('vendor/add'); ?>" method="post">
    <fieldset>
        <legend>Tipo de Entidad (Roles)</legend>
        <label><input type="checkbox" name="is_vendor" value="1" checked> Es Proveedor (Vendor)</label>
        <label style="margin-left:15px;"><input type="checkbox" name="is_dealer" value="1"> Es Distribuidor (Dealer)</label>
    </fieldset><br>

    <fieldset>
        <legend>Información General</legend>
        <label>Nombre / Razón Social:</label><br>
        <input type="text" name="vendor_name" required><br><br>

        <label>País:</label><br>
        <select name="country_id" required>
            <option value="">-- Seleccione --</option>
            <?php foreach($countries as $c): ?>
                <option value="<?php echo $c->id; ?>"><?php echo $c->country_name; ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Tax ID / RUC:</label><br>
        <input type="text" name="tax_id" required><br><br>

        <label>Teléfono Fijo:</label> <input type="text" name="phone">
        <label>Celular:</label> <input type="text" name="mobile"><br><br>

        <label>Sitio Web:</label><br>
        <input type="text" name="website"><br><br>

        <label>Notas:</label><br>
        <textarea name="description" rows="3" cols="50"></textarea>
    </fieldset><br>

    <fieldset>
        <legend>Contacto Principal</legend>
        <input type="text" name="contact_name[]" placeholder="Nombre" required>
        <input type="text" name="position[]" placeholder="Cargo">
        <input type="email" name="contact_email[]" placeholder="Email" required>
        <input type="text" name="indiv_phone[]" placeholder="Teléfono">
        <input type="hidden" name="is_main[]" value="1">
    </fieldset><br>

    <button type="submit">Guardar Registro</button>
</form>