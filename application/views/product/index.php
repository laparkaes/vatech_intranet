<div>
    <div>
        <h1>Maestro de Productos y Servicios</h1>
        <a href="<?php echo base_url('product/create'); ?>">Registrar Nuevo Producto</a>
    </div>

    <br>

    <table border="1" width="100%">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Categoría</th>
                <th>Producto</th>
                <th>SKU / Variante</th>
                <th>Opción</th>
                <th>P. Venta USD</th>
                <th>P. Venta PEN</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($products)): ?>
                <?php foreach($products as $p): ?>
                    <?php $items = $p->items ?? []; ?>

                    <?php if(!empty($items)): ?>
                        <?php foreach($items as $item): ?>
                        <tr>
                            <td><?php echo $p->type; ?></td>
                            <td><?php echo $p->category_name; ?></td>
                            <td><b><?php echo $p->name; ?></b></td>
                            
                            <td><?php echo $item->sku_code; ?></td>
                            <td>
                                <?php echo !empty($item->option_name) ? $item->option_name . ': ' : ''; ?>
                                <?php echo $item->option_value; ?>
                            </td>
                            <td><?php echo number_format($item->sale_price_usd, 2); ?></td>
                            <td><?php echo number_format($item->sale_price_pen, 2); ?></td>

                            <td><?php echo ($p->is_active == 1) ? 'Activo' : 'Inactivo'; ?></td>
                            <td>
                                <a href="<?php echo base_url('product/view/'.$p->id); ?>">Ver</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td><?php echo $p->type; ?></td>
                            <td><?php echo $p->category_name; ?></td>
                            <td><b><?php echo $p->name; ?></b></td>
                            
                            <td></td> <td></td> <td></td> <td></td> <td><?php echo ($p->is_active == 1) ? 'Activo' : 'Inactivo'; ?></td>
                            <td>
                                <a href="<?php echo base_url('product/view/'.$p->id); ?>">Ver</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No se encontraron registros.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>