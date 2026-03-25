<h2>Lista de Distribuidores</h2>

<?php if($this->session->flashdata('success')): ?>
    <p><strong><?php echo $this->session->flashdata('success'); ?></strong></p>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <p><strong><?php echo $this->session->flashdata('error'); ?></strong></p>
<?php endif; ?>

<div>
    <a href="<?php echo base_url('distributor/create'); ?>">
        <button type="button">Registrar Nuevo Distribuidor</button>
    </a>
</div>

<br>

<table border="1">
    <thead>
        <tr>
            <th>Tax ID / RUC</th>
            <th>Nombre del Distribuidor</th>
            <th>Rol</th>
            <th>País</th>
            <th>Contacto Corporativo</th>
            <th>Sitio Web</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($distributors)): ?>
            <?php foreach($distributors as $d): ?>
                <tr>
                    <td><?php echo $d->tax_id; ?></td>
                    <td><strong><?php echo $d->name; ?></strong></td>
                    <td>
                        <?php if($d->is_vendor) echo "[V]"; ?>
                        <?php if($d->is_dealer) echo "[D]"; ?>
                    </td>
                    <td><?php echo $d->country; ?></td>
                    <td>
                        <?php if($d->phone): ?>
                            Telf: <?php echo $d->phone; ?><br>
                        <?php endif; ?>
                        
                        <?php if($d->mobile): ?>
                            Cel: <?php echo $d->mobile; ?>
                        <?php endif; ?>

                        <?php if(!$d->phone && !$d->mobile): ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($d->website): ?>
                            <a href="<?php echo $d->website; ?>" target="_blank"><?php echo $d->website; ?></a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo ($d->status == 1) ? 'ACTIVO' : 'INACTIVO'; ?>
                    </td>
                    <td>
                        <a href="<?php echo base_url('distributor/view/'.$d->id); ?>">
                            <button type="button">Gestionar</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No se encontraron registros de distribuidores.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div>
    <p><small>* Leyenda: [V] Proveedor (Vendor) / [D] Distribuidor (Dealer)</small></p>
</div>