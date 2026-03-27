<div>
    <div>
        <h1>Detalle del Producto</h1>
        <a href="<?php echo base_url('product'); ?>">Volver al Listado</a> | 
        <a href="<?php echo base_url('product/edit/'.$product->id); ?>">Editar Información General</a>
    </div>

    <br>

    <table border="1">
        <tr>
            <td><strong>ID</strong></td>
            <td><?php echo $product->id; ?></td>
            <td><strong>Estado</strong></td>
            <td><?php echo ($product->is_active == 1) ? 'Activo' : 'Inactivo'; ?></td>
        </tr>
        <tr>
            <td><strong>Tipo</strong></td>
            <td><?php echo ($product->type == 'GOODS') ? 'BIEN (GOODS)' : 'SERVICIO'; ?></td>
            <td><strong>Código</strong></td>
            <td><?php echo $product->code; ?></td>
        </tr>
        <tr>
            <td><strong>Nombre</strong></td>
            <td colspan="3"><?php echo $product->name; ?></td>
        </tr>
        <tr>
            <td><strong>Categoría</strong></td>
            <td><?php echo $product->category_name; ?></td>
            <td><strong>Marca</strong></td>
            <td><?php echo $product->brand; ?></td>
        </tr>
        <tr>
            <td><strong>País de Origen</strong></td>
            <td><?php echo $product->origin_country; ?></td>
            <td><strong>Unidad de Medida</strong></td>
            <td><?php echo $product->unit; ?></td>
        </tr>
        <tr>
            <td><strong>Descripción</strong></td>
            <td colspan="3"><?php echo nl2br($product->description); ?></td>
        </tr>
        <tr>
            <td><strong>Última Modificación</strong></td>
            <td colspan="3">
                <?php echo date('d/m/Y H:i', strtotime($product->updated_at)); ?> 
                (Por: <?php echo $product->editor_name ? $product->editor_name : 'Sistema'; ?>)
            </td>
        </tr>
    </table>

    <br>

    <div>
        <h2>Variantes (SKUs) y Precios Actuales</h2>
        <table border="1">
            <thead>
                <tr>
                    <th rowspan="2">Código SKU</th>
                    <th rowspan="2">Opción / Valor</th>
                    <th colspan="2">P. Compra (Actual)</th>
                    <th colspan="2">P. Venta (Actual)</th>
                    <th rowspan="2">T.C. Aplicado</th>
                    <th rowspan="2">Estado</th>
                </tr>
                <tr>
                    <th>USD</th>
                    <th>PEN</th>
                    <th>USD</th>
                    <th>PEN</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($product->items)): ?>
                    <?php foreach($product->items as $item): ?>
                        <tr>
                            <td><strong><?php echo $item->sku_code; ?></strong></td>
                            <td><?php echo $item->option_name; ?>: <?php echo $item->option_value; ?></td>
                            
                            <td>$ <?php echo number_format($item->purchase_price_usd ?? 0, 2); ?></td>
                            <td>S/ <?php echo number_format($item->purchase_price_pen ?? 0, 2); ?></td>
                            <td>$ <?php echo number_format($item->sale_price_usd ?? 0, 2); ?></td>
                            <td>S/ <?php echo number_format($item->sale_price_pen ?? 0, 2); ?></td>
                            
                            <td><?php echo number_format($item->applied_rate ?? 0, 3); ?></td>
                            <td><?php echo ($item->status == 1) ? 'Disponible' : 'Inactivo'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No hay variantes registradas para este producto.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>