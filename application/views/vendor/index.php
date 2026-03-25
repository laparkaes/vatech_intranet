<h2>Maestro de Entidades</h2>

<?php if($this->session->flashdata('success')): ?>
    <p style="color: green;"><strong><?php echo $this->session->flashdata('success'); ?></strong></p>
<?php endif; ?>

<div>
    <a href="<?php echo base_url('vendor/create'); ?>"><button type="button">Registrar Nuevo Socio</button></a>
</div><br>

<table border="1">
    <thead>
        <tr>
            <th>Tax ID / RUC</th>
            <th>Nombre de la Entidad</th>
            <th>Rol</th>
            <th>País</th>
            <th>Contacto</th>
            <th>Sitio Web</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($vendors)): foreach($vendors as $v): ?>
            <tr>
                <td><?php echo $v->tax_id; ?></td>
                <td><strong><?php echo $v->vendor_name; ?></strong></td>
                <td align="center">
                    <?php if($v->is_vendor) echo "[V]"; ?>
                    <?php if($v->is_dealer) echo "[D]"; ?>
                </td>
                <td><?php echo $v->country; ?></td>
                <td>
                    <?php if($v->phone) echo "Telf: ".$v->phone."<br>"; ?>
                    <?php if($v->mobile) echo "Cel: ".$v->mobile; ?>
                    <?php if(!$v->phone && !$v->mobile) echo "-"; ?>
                </td>
                <td><?php echo $v->website ? $v->website : "-"; ?></td>
                <td><?php echo ($v->status == 1) ? 'ACTIVO' : 'INACTIVO'; ?></td>
                <td>
                    <a href="<?php echo base_url('vendor/view/'.$v->id); ?>"><button type="button">Gestionar</button></a>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="8">No se encontraron registros.</td></tr>
        <?php endif; ?>
    </tbody>
</table>