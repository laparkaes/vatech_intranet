<h2>Registrar Socio de Negocio (Nueva Entidad)</h2>

<div>
    <a href="<?php echo base_url('entity'); ?>">Volver al Listado</a>
</div>

<br>

<?php if($this->session->flashdata('error')): ?>
    <p><strong>Error: <?php echo $this->session->flashdata('error'); ?></strong></p>
<?php endif; ?>

<form action="<?php echo base_url('entity/add'); ?>" method="post">
    
    <fieldset>
        <legend>Tipo de Entidad (Roles)</legend>
        <p>Puede seleccionar uno o ambos roles según corresponda.</p>
        
        <input type="checkbox" name="is_vendor" id="is_vendor" value="1">
        <label for="is_vendor">Es Proveedor (Vendor)</label>
        
        <input type="checkbox" name="is_dealer" id="is_dealer" value="1">
        <label for="is_dealer">Es Distribuidor (Dealer)</label>
    </fieldset>

    <br>

    <fieldset>
        <legend>Información General de la Empresa</legend>
        
        <p>
            <label>Nombre / Razón Social:</label><br>
            <input type="text" name="name" required>
        </p>

        <p>
            <label>País:</label><br>
            <select name="country_id" required>
                <option value="">-- Seleccione un país --</option>
                <?php foreach($countries as $c): ?>
                    <option value="<?php echo $c->id; ?>"><?php echo $c->country_name; ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label>Tax ID / RUC:</label><br>
            <input type="text" name="tax_id" required>
        </p>

        <p>
            <label>Teléfono Fijo:</label>
            <input type="text" name="phone">
        </p>
        
        <p>
            <label>Celular/Móvil:</label>
            <input type="text" name="mobile">
        </p>

        <p>
            <label>Dirección:</label><br>
            <textarea name="address" rows="2"></textarea>
        </p>

        <p>
            <label>Sitio Web (URL):</label><br>
            <input type="text" name="website" placeholder="https://example.com">
        </p>

        <p>
            <label>Descripción / Notas Internas:</label><br>
            <textarea name="description" rows="3"></textarea>
        </p>
    </fieldset>

    <br>

    <fieldset>
        <legend>Contacto Principal (Representante Legal/Comercial)</legend>
        <p>Este contacto será asignado como el contacto principal de la entidad.</p>
        
        <p>
            <label>Nombre Completo:</label><br>
            <input type="text" name="contact_name[]" required>
        </p>

        <p>
            <label>Cargo/Puesto:</label><br>
            <input type="text" name="position[]">
        </p>

        <p>
            <label>Email:</label><br>
            <input type="email" name="contact_email[]" required>
        </p>

        <p>
            <label>Teléfono Directo:</label><br>
            <input type="text" name="indiv_phone[]">
        </p>

        <input type="hidden" name="is_main[]" value="1">
    </fieldset>

    <br>

    <div>
        <button type="submit">Guardar Registro</button>
        <a href="<?php echo base_url('entity'); ?>">Cancelar</a>
    </div>

</form>