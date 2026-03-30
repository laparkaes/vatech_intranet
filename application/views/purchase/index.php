<div>
    <h2>Órdenes de Compra (Purchase Orders)</h2>
    
    <div>
        <a href="<?= base_url('purchase/create') ?>">
            [ + Crear Nueva Orden ]
        </a>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div>
            <strong><?= $this->session->flashdata('success') ?></strong>
        </div>
    <?php endif; ?>

    <table border="1">
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
                    <td colspan="8">No hay órdenes de compra registradas.</td>
                </tr>
            <?php else: ?>
                <?php foreach($orders as $o): ?>
                    <tr>
                        <td>
                            <strong><?= $o->po_number ?></strong>
                        </td>
                        <td><?= $o->supplier_name ?></td>
                        <td><?= isset($o->po_type_name) ? $o->po_type_name : '-' ?></td>
                        <td><?= $o->currency_name ?></td>
                        <td>
                            <strong><?= number_format($o->total_amount, 2) ?></strong>
                        </td>
                        <td>
                            <span><?= $o->status_name ?></span>
                        </td>
                        <td><?= $o->issue_date ?></td>
                        <td>
                            <a href="<?= base_url('purchase/view/'.$o->id) ?>">
                                [ Ver Detalle ]
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>