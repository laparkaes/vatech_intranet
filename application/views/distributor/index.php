<div class="pagetitle">
    <h1>Distribuidores</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Maestro</li>
            <li class="breadcrumb-item active">Distribuidores</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <a href="<?php echo base_url('distributor/create'); ?>" class="btn btn-primary mb-3">
                <i class="bi bi-plus"></i> Agregar
            </a>
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="bi bi-search"></i> Buscar
            </button>

            <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="GET" action="<?= base_url('distributor/index') ?>" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Buscar Distribuidor</h5>
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
                    <h5 class="card-title">Lista de Distribuidores</h5>

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
                            <?php if(!empty($distributors)): ?>
                                <?php foreach($distributors as $d): ?>
                                <tr class="align-middle">
                                    <td><?php echo $start_no++; ?></td>
                                    <td><?= $d->country_name ? $d->country_name : "-" ?></td>
                                    <td><?php echo $d->tax_id; ?></td>
                                    <td><strong><?php echo $d->name; ?></strong></td>
                                    <td>
                                        <?php if($d->is_vendor): ?>
                                            <div><span class="badge border text-primary border-primary">Proveedor</span></div>
                                        <?php endif; ?>
                                        <?php if($d->is_dealer): ?>
                                            <div><span class="badge border text-success border-success">Distribuidor</span></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($d->phone) echo '<i class="bi bi-telephone"></i> '.$d->phone.'<br>'; ?>
                                        <?php if($d->mobile) echo '<i class="bi bi-phone"></i> '.$d->mobile; ?>
                                        <?php if(!$d->phone && !$d->mobile) echo "-"; ?>
                                    </td>
                                    <td>
                                        <span class="<?= ($d->status == 1) ? 'text-success' : 'text-danger' ?>">
                                            <?= ($d->status == 1) ? 'ACTIVO' : 'INACTIVO' ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?php echo base_url('distributor/view/'.$d->id); ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-info-square"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="py-4">
                                        <div class="alert alert-warning border-0 mb-0">
                                            <i class="bi bi-exclamation-triangle me-1"></i> No se encontraron registros de distribuidores.
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