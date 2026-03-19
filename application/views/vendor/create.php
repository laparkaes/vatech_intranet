<h2>Registrar Nuevo Proveedor (Vendor)</h2>

<?php if($this->session->flashdata('error')): ?>
    <p style="color: red;"><strong><?php echo $this->session->flashdata('error'); ?></strong></p>
<?php endif; ?>

<form action="<?php echo base_url('vendor/add'); ?>" method="post">
    
    <fieldset>
        <legend>Información General de la Empresa</legend>
        
        <label>Nombre Comercial / Razón Social:</label><br>
        <input type="text" name="vendor_name" required><br><br>

        <label>País de Origen:</label><br>
        <select name="country_id" required> <option value="">-- Seleccione un País --</option>
            <?php foreach($countries as $c): ?>
                <option value="<?php echo $c->id; ?>"> <?php echo $c->country_name; ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Tax ID / RUC (Identificación Fiscal):</label><br>
        <input type="text" name="tax_id" required><br><br>

        <label>Teléfono de la Empresa (Fijo):</label><br>
        <input type="text" name="phone"><br><br>

        <label>Celular de la Empresa (Móvil / WhatsApp):</label><br>
        <input type="text" name="mobile"><br><br>

        <label>Sitio Web:</label><br>
        <input type="text" name="website"><br><br>

        <label>Descripción y Notas (Productos/Servicios):</label><br>
        <textarea name="description" rows="4" cols="50"></textarea>
    </fieldset>

    <br>

    <fieldset>
        <legend>Contactos de la Empresa (Logística, Ventas, etc.)</legend>
        
        <div id="contact-container">
            <div class="contact-item" style="margin-bottom: 20px; border-bottom: 1px solid #000;">
                <h4>Contacto #1 (Representante Principal)</h4>
                
                <label>Nombre Completo:</label><br>
                <input type="text" name="contact_name[]" required><br>

                <label>Cargo / Departamento:</label><br>
                <input type="text" name="position[]" placeholder="Ej: Logistics Manager"><br>

                <label>Correo Electrónico (Único):</label><br>
                <input type="email" name="contact_email[]" required><br>

                <label>Teléfono Directo:</label><br>
                <input type="text" name="indiv_phone[]"><br>

                <input type="hidden" name="is_main[]" value="1">
                <br>
            </div>
        </div>

        <button type="button" id="btn-add-contact">+ Añadir Otro Contacto</button>
    </fieldset>

    <br><br>
    
    <button type="submit">Finalizar Registro de Proveedor</button>
    <a href="<?php echo base_url('vendor'); ?>">Cancelar y Regresar</a>

</form>

<script>
    const container = document.getElementById('contact-container');
    const addBtn = document.getElementById('btn-add-contact');

    function refreshContactNumbers() {
        const items = container.querySelectorAll('.contact-item');
        items.forEach((item, index) => {
            const title = item.querySelector('h4');
            if (title) {
                const label = (index === 0) ? "(Representante Principal)" : "";
                title.innerText = `Contacto #${index + 1} ${label}`;
            }
        });
    }

    addBtn.addEventListener('click', () => {
        const div = document.createElement('div');
        div.className = 'contact-item';
        div.style = "margin-bottom: 20px; border-bottom: 1px solid #000;";
        
        div.innerHTML = `
            <h4></h4> 
            <label>Nombre Completo:</label><br>
            <input type="text" name="contact_name[]" required><br>

            <label>Cargo / Departamento:</label><br>
            <input type="text" name="position[]"><br>

            <label>Correo Electrónico:</label><br>
            <input type="email" name="contact_email[]" required><br>

            <label>Teléfono Directo:</label><br>
            <input type="text" name="indiv_phone[]"><br>

            <input type="hidden" name="is_main[]" value="0">
            <button type="button" class="btn-remove">Eliminar este contacto</button>
            <br><br>
        `;
        
        container.appendChild(div);
        refreshContactNumbers();
    });

    container.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-remove')) {
            e.target.parentElement.remove();
            refreshContactNumbers();
        }
    });

    refreshContactNumbers();
</script>