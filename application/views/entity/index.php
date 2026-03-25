<h2>Maestro de Entidades (Proveedores y Distribuidores)</h2>

<?php if($this->session->flashdata('success')): ?>
    <p><strong><?php echo $this->session->flashdata('success'); ?></strong></p>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <p><strong><?php echo $this->session->flashdata('error'); ?></strong></p>
<?php endif; ?>

<div>
    <a href="<?php echo base_url('entity/create'); ?>">
        <button type="button">Registrar Nueva Entidad</button>
    </a>
</div>

<br>

<table border="1">
    <thead>
        <tr>
            <th>Tax ID / RUC</th>
            <th>Nombre de la Entidad</th>
            <th>Rol</th>
            <th>País</th>
            <th>Contacto Corporativo</th>
            <th>Estado</th>
            <th>Registrado por</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($entities)): ?>
            <?php foreach($entities as $e): ?>
                <tr>
                    <td><?php echo $e->tax_id; ?></td>
                    <td><strong><?php echo $e->name; ?></strong></td>
                    <td align="center">
                        <?php if($e->is_vendor) echo "<strong>[V]</strong> "; ?>
                        <?php if($e->is_dealer) echo "<strong>[D]</strong>"; ?>
                        <?php if(!$e->is_vendor && !$e->is_dealer) echo "-"; ?>
                    </td>
                    <td><?php echo $e->country; ?></td>
                    <td>
                        <?php if($e->phone): ?>
                            Telf: <?php echo $e->phone; ?><br>
                        <?php endif; ?>
                        
                        <?php if($e->mobile): ?>
                            Cel: <?php echo $e->mobile; ?>
                        <?php endif; ?>

                        <?php if(!$e->phone && !$e->mobile): ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td align="center">
                        <?php echo ($e->status == 1) ? 'ACTIVO' : 'INACTIVO'; ?>
                    </td>
                    <td>
                        <small><?php echo $e->creator_name; ?></small>
                    </td>
                    <td>
                        <a href="<?php echo base_url('entity/view/'.$e->id); ?>">
                            <button type="button">Gestionar</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" align="center">No se encontraron registros de entidades.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div>
    <p><small>* Leyenda: [V] Proveedor (Vendor) / [D] Distribuidor (Dealer)</small></p>
</div>