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
                <th>Producto</th>
                <th>SKU / Variante</th>
                <th>Opción</th>
                <th>Precio USD</th>
                <th>Precio PEN</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Actualización</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($products)): ?>
                <?php foreach($products as $p): ?>
                    <?php 
                        $items = $p->items ?? [];
                        $rowspan = count($items) > 0 ? count($items) : 1;
                        $first_row = true; 
                    ?>

                    <?php if(!empty($items)): ?>
                        <?php foreach($items as $item): ?>
                        <tr>
                            <?php if($first_row): ?>
                                <td rowspan="<?php echo $rowspan; ?>" align="center" valign="top"><?php echo $p->type; ?></td>
                                <td rowspan="<?php echo $rowspan; ?>" valign="top"><b><?php echo $p->name; ?></b></td>
                            <?php endif; ?>

                            <td><?php echo $item->sku_code; ?></td>
                            <td>
                                <?php echo $item->option_name; ?>: <?php echo $item->option_value; ?>
                            </td>
                            <td align="right"><?php echo number_format($item->sale_price_usd, 2); ?></td>
                            <td align="right"><?php echo number_format($item->sale_price_pen, 2); ?></td>

                            <?php if($first_row): ?>
                                <td rowspan="<?php echo $rowspan; ?>" align="center" valign="top"><?php echo $p->category_name; ?></td>
                                <td rowspan="<?php echo $rowspan; ?>" align="center" valign="top"><?php echo ($p->is_active == 1) ? 'Activo' : 'Inactivo'; ?></td>
                                <td rowspan="<?php echo $rowspan; ?>" valign="top">
                                    <small><?php echo $p->updated_at; ?><br>Por: <?php echo $p->editor_name; ?></small>
                                </td>
                                <td rowspan="<?php echo $rowspan; ?>" align="center" valign="top">
                                    <a href="<?php echo base_url('product/view/'.$p->id); ?>">Ver</a> | 
                                    <a href="<?php echo base_url('product/edit/'.$p->id); ?>">Editar</a>
                                </td>
                                <?php $first_row = false; ?>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td align="center"><?php echo $p->type; ?></td>
                            <td><b><?php echo $p->name; ?></b></td>
                            <td colspan="4" align="center"><i>Sin variantes</i></td>
                            <td align="center"><?php echo $p->category_name; ?></td>
                            <td align="center">Activo</td>
                            <td><small><?php echo $p->updated_at; ?></small></td>
                            <td align="center"><a href="<?php echo base_url('product/edit/'.$p->id); ?>">Editar</a></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" align="center">No se encontraron registros.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>