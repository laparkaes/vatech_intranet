<h2>Maestro de Proveedores (Vendors)</h2>

<?php if($this->session->flashdata('success')): ?>
    <p><strong><?php echo $this->session->flashdata('success'); ?></strong></p>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <p><strong><?php echo $this->session->flashdata('error'); ?></strong></p>
<?php endif; ?>

<div>
    <a href="<?php echo base_url('vendor/create'); ?>">
        <button type="button">Registrar Nuevo Proveedor</button>
    </a>
</div>

<br>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre de la Empresa</th>
            <th>País de Origen</th>
            <th>Tax ID / RUC</th>
            <th>Contacto Corporativo</th>
            <th>Sitio Web y Notas</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($vendors)): ?>
            <?php foreach($vendors as $v): ?>
            <tr>
                <td><?php echo $v->id; ?></td>
                
                <td><strong><?php echo $v->vendor_name; ?></strong></td>
                
                <td><?php echo $v->country; ?></td>
                
                <td><?php echo $v->tax_id; ?></td>
                
                <td>
                    <?php if($v->phone): ?>
                        Telf: <?php echo $v->phone; ?><br>
                    <?php endif; ?>
                    <?php if($v->mobile): ?>
                        Cel: <?php echo $v->mobile; ?>
                    <?php endif; ?>
                    <?php if(!$v->phone && !$v->mobile): ?>
                        <span>Sin teléfono</span>
                    <?php endif; ?>
                </td>
                
                <td>
                    <?php if($v->website): ?>
                        <a href="<?php echo $v->website; ?>" target="_blank">Visitar Sitio</a><br>
                    <?php endif; ?>
                    <small><?php echo $v->description; ?></small>
                </td>
                
                <td>
                    <?php echo ($v->status == 1) ? 'ACTIVO' : 'INACTIVO'; ?>
                </td>
                
                <td>
                    <a href="<?php echo base_url('vendor/view/'.$v->id); ?>">
                        <button type="button">Gestionar</button>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No se han encontrado proveedores registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<p><small>* Los proveedores se ordenan alfabéticamente.</small></p>