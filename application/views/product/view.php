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

<table border="1">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Variante</th>
            <th>Peso</th>
            <th>Compra USD</th>
            <th>Compra PEN</th>
            <th>Venta USD</th>
            <th>Venta PEN</th>
            <th>T.C.</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($product->items)): ?>
            <?php foreach($product->items as $item): ?>
                <tr>
                    <td><?php echo $item->sku_code; ?></td>
                    <td><?php echo $item->option_name; ?>: <?php echo $item->option_value; ?></td>
                    <td><?php echo $item->weight; ?></td>
                    <td><?php echo $item->purchase_price_usd; ?></td>
                    <td><?php echo $item->purchase_price_pen; ?></td>
                    <td><?php echo $item->sale_price_usd; ?></td>
                    <td><?php echo $item->sale_price_pen; ?></td>
                    <td><?php echo $item->applied_rate; ?></td>
                    <td><?php echo ($item->status == 1) ? 'Disponible' : 'Inactivo'; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">Sin variantes registradas.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>