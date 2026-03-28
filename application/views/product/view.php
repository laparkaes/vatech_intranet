<h1>Detalle del Producto</h1>

<p>
    <a href="<?php echo base_url('product'); ?>">Volver al Listado</a> | 
    <a href="<?php echo base_url('product/edit/'.$product->id); ?>">Editar Información General</a> | 
    <a href="<?php echo base_url('product/delete/'.$product->id); ?>" onclick="return confirm('¿Está seguro de eliminar este producto?');">Eliminar Producto</a>
</p>

<hr>

<table border="1">
    <tr>
        <th>ID</th>
        <td><?php echo $product->id; ?></td>
        <th>Estado</th>
        <td><?php echo ($product->is_active == 1) ? 'ACTIVO' : 'INACTIVO'; ?></td>
    </tr>
    <tr>
        <th>Tipo</th>
        <td><?php echo $product->type; ?></td>
        <th>Código</th>
        <td><?php echo $product->code; ?></td>
    </tr>
    <tr>
        <th>Nombre</th>
        <td colspan="3"><?php echo $product->name; ?></td>
    </tr>
    <tr>
        <th>Categoría</th>
        <td><?php echo $product->category_name; ?></td>
        <th>Marca</th>
        <td><?php echo $product->brand; ?></td>
    </tr>
    <tr>
        <th>País</th>
        <td><?php echo $product->origin_country; ?></td>
        <th>Unidad</th>
        <td><?php echo $product->unit; ?></td>
    </tr>
    <tr>
        <th>Descripción</th>
        <td colspan="3"><?php echo nl2br($product->description); ?></td>
    </tr>
    <tr>
        <th>Auditoría</th>
        <td colspan="3">
            Creado: <?php echo $product->created_at; ?><br>
            Actualizado: <?php echo $product->updated_at; ?><br>
            Editor: <?php echo $product->editor_name; ?>
        </td>
    </tr>
</table>

<br>

<br>

<table border="1">
    <thead>
        <tr>
            <th>Opción</th>
            <th>Dimensiones (LxWxH)</th> <th>Peso (kg)</th>
            <th>Compra USD</th>
            <th>Compra PEN</th>
            <th>Venta USD</th>
            <th>Venta PEN</th>
            <th>T.C. Aplicado</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($product->items)): ?>
            <?php foreach($product->items as $item): ?>
                <tr>
                    <td><?php echo $item->option; ?></td>
                    <td><?php echo !empty($item->dimensions) ? $item->dimensions : '-'; ?></td>
                    <td><?php echo number_format($item->weight, 2); ?></td>
                    <td><?php echo number_format($item->purchase_price_usd, 2); ?></td>
                    <td><?php echo number_format($item->purchase_price_pen, 2); ?></td>
                    <td><?php echo number_format($item->sale_price_usd, 2); ?></td>
                    <td><?php echo number_format($item->sale_price_pen, 2); ?></td>
                    <td><?php echo number_format($item->applied_rate, 3); ?></td>
                    <td><?php echo ($item->status == 1) ? 'Disponible' : 'Inactivo'; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">Sin variantes registradas.</td> </tr>
        <?php endif; ?>
    </tbody>
</table>