<div class="pagetitle">
    <h1>Proveedores</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Compras</li>
            <li class="breadcrumb-item active">Proveedores</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <a href="<?php echo base_url('vendor/create'); ?>" class="btn btn-primary mb-3">
                <i class="bi bi-plus"></i> Agregar
            </a>
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="bi bi-search"></i> Buscar
            </button>

            <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="GET" action="<?= base_url('vendor/index') ?>" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Buscar Proveedor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="name" class="form-control" value="<?= $search['name'] ?? '' ?>">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Tax ID</label>
                                    <input type="text" name="tax_id" class="form-control" value="<?= $search['tax_id'] ?? '' ?>">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Estado</label>
                                    <select name="status" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="1" <?= ($search['status'] === '1') ? 'selected' : '' ?>>ACTIVO</option>
                                        <option value="0" <?= ($search['status'] === '0') ? 'selected' : '' ?>>INACTIVO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Lista de Proveedores</h5>

                    <table class="table align-middle">
                        <thead>
                            <tr class="align-top">
                                <th scope="col">No.</th>
                                <th scope="col">País</th>
                                <th scope="col">
                                    Tax ID
                                    <?php if (!empty($search['tax_id'])): ?>
                                        <br><small class="text-success fw-normal">(<?= htmlspecialchars($search['tax_id']) ?>)</small>
                                    <?php endif; ?>
                                </th>
                                <th scope="col">
                                    Nombre
                                    <?php if (!empty($search['name'])): ?>
                                        <br><small class="text-success fw-normal">(<?= htmlspecialchars($search['name']) ?>)</small>
                                    <?php endif; ?>
                                </th>
                                <th scope="col">Type</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Estado</th>
                                <th class="text-end" scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($vendors)): ?>
                                <?php foreach($vendors as $v): ?>
                                <tr class="align-middle">
                                    <td><?php echo $start_no++; ?></td>
                                    <td><?= $v->country_name ? $v->country_name : "-" ?></td>
                                    <td><?php echo $v->tax_id; ?></td>
                                    <td><?php echo $v->name; ?></td>
                                    <td>
                                        <?php if($v->is_vendor): ?>
                                            <div><span class="badge border text-primary border-primary">Proveedor</span></div>
                                        <?php endif; ?>
                                        <?php if($v->is_dealer): ?>
                                            <div><span class="badge border text-success border-success">Distribuidor</span></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($v->phone) echo '<i class="bi bi-telephone"></i> '.$v->phone.'<br>'; ?>
                                        <?php if($v->mobile) echo '<i class="bi bi-phone"></i> '.$v->mobile; ?>
                                        <?php if(!$v->phone && !$v->mobile) echo "-"; ?>
                                    </td>
                                    <td>
                                        <span class="<?= ($v->status == 1) ? 'text-success' : 'text-danger' ?>">
                                            <?= ($v->status == 1) ? 'ACTIVO' : 'INACTIVO' ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?php echo base_url('vendor/view/'.$v->id); ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-info-square"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="py-4">
                                        <div class="alert alert-warning border-0 mb-0">
                                            <i class="bi bi-exclamation-triangle me-1"></i> No se encontraron registros de proveedores.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <?php if (isset($total_rows) && $total_rows > 20): ?>
                            <?php echo $pagination; ?>
                        <?php else: ?>
                            <ul class="pagination justify-content-center">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            </ul>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>