<h2>Detalles del Proveedor: <?php echo $vendor->vendor_name; ?></h2>

<div>
    <a href="<?php echo base_url('vendor'); ?>"><button type="button">Volver al Listado</button></a>
    
    <a href="<?php echo base_url('vendor/edit/'.$vendor->id); ?>"><button type="button">Editar Información</button></a>
    
    <a href="<?php echo base_url('vendor/contacts/'.$vendor->id); ?>"><button type="button">Gestionar Contactos</button></a>
</div>

<br>

<table border="1">
    <tr>
        <th>ID</th>
        <td><?php echo $vendor->id; ?></td>
        <th>Status</th>
        <td><?php echo ($vendor->status == 1) ? 'ACTIVO' : 'INACTIVO'; ?></td>
    </tr>
    <tr>
        <th>Vendor Name</th>
        <td colspan="3"><strong><?php echo $vendor->vendor_name; ?></strong></td>
    </tr>
    <tr>
        <th>Country</th>
        <td><?php echo $vendor->country; ?></td>
        <th>Tax ID / RUC</th>
        <td><?php echo $vendor->tax_id; ?></td>
    </tr>
    <tr>
        <th>Phone</th>
        <td><?php echo $vendor->phone; ?></td>
        <th>Mobile</th>
        <td><?php echo $vendor->mobile; ?></td>
    </tr>
    <tr>
        <th>Website</th>
        <td colspan="3">
            <?php if($vendor->website): ?>
                <a href="<?php echo $vendor->website; ?>" target="_blank"><?php echo $vendor->website; ?></a>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th>Description</th>
        <td colspan="3"><?php echo nl2br($vendor->description); ?></td>
    </tr>
	<tr>
        <th>Registrado por</th>
        <td colspan="3">
            <?php echo !empty($vendor->creator_name) ? $vendor->creator_name : 'Sistema / Desconocido'; ?>
        </td>
    </tr>
</table>

<p>
    <small>Created at: <?php echo $vendor->created_at; ?></small><br>
    <small>Vendor ID: <?php echo $vendor->id; ?></small>
</p>

<br>

<h3>Contactos Registrados</h3>
<table border="1">
    <thead>
        <tr>
            <th>Type</th>
            <th>Contact Name</th>
            <th>Position</th>
            <th>Email</th>
            <th>Direct Phone</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($contacts)): ?>
            <?php foreach($contacts as $co): ?>
            <tr>
                <td>
                    <?php echo ($co->is_main == 1) ? '<strong>Main</strong>' : 'Additional'; ?>
                </td>
                <td><?php echo $co->contact_name; ?></td>
                <td><?php echo $co->position; ?></td>
                <td><?php echo $co->email; ?></td>
                <td><?php echo $co->phone; ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No hay contactos registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<p><small>Created at: <?php echo $vendor->created_at; ?></small></p>