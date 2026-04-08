<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Listado de Ventas</h1>
        <a href="<?= site_url('sale/create') ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nueva Venta
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nº Venta</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Almacén</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Vendedor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($list)): ?>
                            <?php foreach($list as $row): ?>
                            <tr>
                                <td><strong><?= $row->sales_number ?></strong></td>
                                <td><?= $row->sales_date ?></td>
                                <td><?= $row->customer_name ?></td>
                                <td><?= $row->warehouse_name ?></td>
                                <td class="text-right"><?= number_format($row->total_amount, 2) ?></td>
                                <td class="text-center">
                                    <span class="badge badge-info"><?= $row->status_label ?></span>
                                </td>
                                <td><?= $row->creator_name ?></td>
                                <td class="text-center">
                                    <a href="<?= site_url('sale/view/'.$row->id) ?>" class="btn btn-sm btn-info">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No se encontraron registros de ventas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>