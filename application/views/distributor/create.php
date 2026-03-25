<h2>Registrar Socio de Negocio</h2>

<div>
    <a href="<?php echo base_url('vendor'); ?>">
        <button type="button">Volver al Listado</button>
    </a>
</div>

<br>

<?php if($this->session->flashdata('error')): ?>
    <p><strong>Error: <?php echo $this->session->flashdata('error'); ?></strong></p>
<?php endif; ?>

<form action="<?php echo base_url('vendor/add'); ?>" method="post">
    
    <fieldset>
        <legend>Tipo de Entidad (Roles)</legend>
        <input type="checkbox" name="is_vendor" id="is_vendor" value="1" checked>
        <label for="is_vendor">Es Proveedor (Vendor)</label>
        
        <input type="checkbox" name="is_dealer" id="is_dealer" value="1">
        <label for="is_dealer">Es Distribuidor (Dealer)</label>
    </fieldset>

    <br>

    <fieldset>
        <legend>Información General</legend>
        
        <label>Nombre / Razón Social:</label><br>
        <input type="text" name="name" required><br><br>

        <label>País:</label><br>
        <select name="country_id" required>
            <option value="">-- Seleccione --</option>
            <?php foreach($countries as $c): ?>
                <option value="<?php echo $c->id; ?>"><?php echo $c->country_name; ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Tax ID / RUC:</label><br>
        <input type="text" name="tax_id" required><br><br>

        <label>Teléfono Fijo:</label>
        <input type="text" name="phone">
        
        <label>Celular:</label>
        <input type="text" name="mobile"><br><br>

        <label>Dirección:</label><br>
        <textarea name="address" rows="2" cols="50"></textarea><br><br>

        <label>Sitio Web:</label><br>
        <input type="text" name="website"><br><br>

        <label>Notas:</label><br>
        <textarea name="description" rows="3" cols="50"></textarea>
    </fieldset>

    <br>

    <fieldset>
        <legend>Contacto Principal (Representante)</legend>
        <table>
            <tr>
                <td><label>Nombre:</label></td>
                <td><input type="text" name="contact_name[]" required></td>
            </tr>
            <tr>
                <td><label>Cargo:</label></td>
                <td><input type="text" name="position[]"></td>
            </tr>
            <tr>
                <td><label>Email:</label></td>
                <td><input type="email" name="contact_email[]" required></td>
            </tr>
            <tr>
                <td><label>Teléfono:</label></td>
                <td><input type="text" name="indiv_phone[]"></td>
            </tr>
        </table>
        <input type="hidden" name="is_main[]" value="1">
    </fieldset>

    <br>

    <div>
        <button type="submit">Guardar Registro</button>
        <a href="<?php echo base_url('vendor'); ?>">
            <button type="button">Cancelar</button>
        </a>
    </div>

</form>