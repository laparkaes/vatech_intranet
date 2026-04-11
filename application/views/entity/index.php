<div class="pagetitle">
    <h1>Entidades</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Maestro</li>
            <li class="breadcrumb-item active">Entidades</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <a href="<?php echo base_url('entity/create'); ?>" class="btn btn-primary mb-3">
                <i class="bi bi-plus"></i> Agregar
            </a>
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="bi bi-search"></i> Buscar
            </button>

            <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="GET" action="<?= base_url('entity/index') ?>" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Buscar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Nombre / Razón Social</label>
                                    <input type="text" name="name" class="form-control" value="<?= $search['name'] ?? '' ?>">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Tax ID / RUC</label>
                                    <input type="text" name="tax_id" class="form-control" value="<?= $search['tax_id'] ?? '' ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Rol</label>
                                    <select name="role" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="vendor" <?= ($search['role'] == 'vendor') ? 'selected' : '' ?>>Proveedor (Vendor)</option>
                                        <option value="dealer" <?= ($search['role'] == 'dealer') ? 'selected' : '' ?>>Distribuidor (Dealer)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
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
                    <h5 class="card-title">Lista</h5>
                    <table class="table align-middle text-center">
                        <thead>
							<tr class="align-top">
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
								<th scope="col">
									Rol
									<?php if (!empty($search['role'])): ?>
										<br><small class="text-success fw-normal">
											(<?= ($search['role'] == 'vendor') ? 'Proveedor' : 'Distribuidor' ?>)
										</small>
									<?php endif; ?>
								</th>
								<th scope="col">País</th>
								<th scope="col">Contacto</th>
								<th scope="col">
									Estado
									<?php if ($search['status'] !== null && $search['status'] !== ''): ?>
										<br><small class="text-success fw-normal">
											(<?= ($search['status'] == '1') ? 'ACTIVO' : 'INACTIVO' ?>)
										</small>
									<?php endif; ?>
								</th>
								<th scope="col">Acciones</th>
							</tr>
						</thead>
                        <tbody>
                            <?php if(!empty($entities)): ?>
                                <?php foreach($entities as $e): ?>
                                <tr class="align-middle">
                                    <td><?php echo $e->tax_id; ?></td>
                                    <td class="text-start"><?php echo $e->name; ?></td>
                                    <td>
                                        <?php if($e->is_vendor): ?>
										<div><span class="badge border text-primary border-primary">Proveedor</span></div>
                                        <?php endif; ?>
                                        <?php if($e->is_dealer): ?>
										<div><span class="badge border text-success border-success">Distribuidor</span></div>
                                        <?php endif; ?>
                                        <?php if(!$e->is_vendor && !$e->is_dealer) echo "-"; ?>
                                    </td>
                                    <td><?= $e->country_name ? $e->country_name : "-" ?></td>
                                    <td>
                                        <?php if($e->phone) echo '<i class="bi bi-telephone"></i> '.$e->phone.'<br>'; ?>
                                        <?php if($e->mobile) echo '<i class="bi bi-phone"></i> '.$e->mobile; ?>
                                        <?php if(!$e->phone && !$e->mobile) echo "-"; ?>
                                    </td>
                                    <td>
                                        <span class="<?= ($e->status == 1) ? 'text-success' : 'text-danger' ?>">
                                            <?= ($e->status == 1) ? 'ACTIVO' : 'INACTIVO' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('entity/view/'.$e->id); ?>">
                                            <i class="bi bi-info-square"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="py-4">
                                        <div class="alert alert-warning border-0 mb-0">
                                            <i class="bi bi-exclamation-triangle me-1"></i> No se encontraron registros de entidades.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <?php if (isset($total_rows) && $total_rows > 30): ?>
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