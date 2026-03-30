<div>
    <h2>Órdenes de Compra (Purchase Orders)</h2>
    <div>
        <a href="<?= base_url('purchase/create') ?>">[ + Crear Nueva Orden ]</a>
    </div>
    
    <br>

    <table border="1" width="100%">
        <thead>
            <tr>
                <th>N° de Orden</th>
                <th>Proveedor</th>
                <th>Tipo</th>
                <th>Moneda</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha de Emisión</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($orders)): ?>
                <tr>
                    <td colspan="8" align="center">No hay órdenes de compra registradas.</td>
                </tr>
            <?php else: ?>
                <?php foreach($orders as $o): ?>
                    <tr>
                        <td align="center"><strong><?= $o->po_number ?></strong></td>
                        <td><?= $o->supplier_name ?></td>
                        <td align="center"><?= $o->po_type ?></td>
                        <td align="center"><?= $o->currency ?></td>
                        <td align="right"><?= number_format($o->total_amount, 2) ?></td>
                        <td align="center"><?= $o->status ?></td>
                        <td align="center"><?= $o->issue_date ?></td>
                        <td align="center">
                            <a href="<?= base_url('purchase/view/'.$o->id) ?>">[ Ver ]</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>