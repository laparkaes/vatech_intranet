<h2>Solicitud de Acceso al Sistema</h2>
<p>Por favor, seleccione los módulos a los que desea solicitar acceso y proporcione una justificación.</p>

<?php if($this->session->flashdata('success')): ?>
    <p><strong>Éxito:</strong> <?php echo $this->session->flashdata('success'); ?></p>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <p><strong>Error:</strong> <?php echo $this->session->flashdata('error'); ?></p>
<?php endif; ?>

<form action="<?php echo base_url('access/request_access_process'); ?>" method="post">
    <div>
        <label><strong>Módulos Disponibles:</strong></label><br><br>
        
        <input type="checkbox" id="select_all">
        <label for="select_all"><strong>Seleccionar todo</strong></label>
        <hr>

        <?php 
        // Listado de módulos del sistema VPR
        $modules_list = array(
            'purchase'    => 'Compras',
            'vendor'      => 'Vendedores',
            'sales'       => 'Ventas',
            'distributor' => 'Distribuidores',
            'products'    => 'Gestión de Productos',
            'accounts'    => 'Gestión de Cuentas',
            'access'      => 'Gestión de Accesos',
            'system'      => 'Configuración del Sistema',
            'reports'     => 'Reportes'
        );

        foreach($modules_list as $key => $label): ?>
            <input type="checkbox" name="modules[]" value="<?php echo $key; ?>" class="module_checkbox" id="mod_<?php echo $key; ?>">
            <label for="mod_<?php echo $key; ?>"><?php echo $label; ?></label><br>
        <?php endforeach; ?>
    </div>
    
    <br>
    <div>
        <label>Justificación de la Solicitud:</label><br>
        <textarea name="reason" rows="5" cols="50" required placeholder="Explique por qué necesita acceso..."></textarea>
    </div>
    <br>
    <button type="submit">Enviar Solicitudes</button>
</form>

<script>
$(document).ready(function() {
    // Manejar el checkbox de "Seleccionar todo"
    $('#select_all').on('click', function() {
        $('.module_checkbox').prop('checked', this.checked);
    });

    // Desmarcar "Seleccionar todo" si un módulo individual es desmarcado
    $('.module_checkbox').on('click', function() {
        if (!$(this).is(':checked')) {
            $('#select_all').prop('checked', false);
        }
        
        // Si todos están marcados, marcar también el de "Seleccionar todo"
        if ($('.module_checkbox:checked').length == $('.module_checkbox').length) {
            $('#select_all').prop('checked', true);
        }
    });
});
</script>