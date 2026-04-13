<div class="pagetitle">
    <h1>Detalles de la Entidad</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Mantenimiento</li>
            <li class="breadcrumb-item"><a href="<?= base_url("entity") ?>">Entidades</a></li>
            <li class="breadcrumb-item active">Detalle</li>
        </ol>
    </nav>
</div>

<section class="section profile">
    <div class="row">
        <div class="col-12">
            <div>
                <a href="<?= base_url('entity') ?>" class="btn btn-primary mb-3"><i class="bi bi-arrow-left"></i> Lista</a>
                <a href="<?= base_url('entity/edit/'.$entity->id) ?>" class="btn btn-primary mb-3"><i class="bi bi-pencil"></i> Editar</a>
            </div>

			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Detalle de Entidad</h5>

					<div class="row g-3">
					
						<div class="col-md-6">
							<label class="form-label">Estado</label>
							<input type="text" class="form-control <?= ($entity->status == 1) ? 'text-success' : 'text-danger' ?>" value="<?= ($entity->status == 1) ? 'ACTIVO' : 'INACTIVO' ?>" readonly>
						</div>
						
						<div class="col-md-6">
							<?php 
							$aux = [];
							if ($entity->is_vendor) $aux[] = 'PROVEEDOR';
							if ($entity->is_dealer) $aux[] = 'DISTRIBUIDOR';
							if (!$aux) $aux[] = "-";
							?>
							<label class="form-label">Tipo</label>
							<input type="text" class="form-control" value="<?= implode(", ", $aux) ?>" readonly>
						</div>
						
						<div class="col-md-12">
							<label class="form-label">Nombre</label>
							<input type="text" class="form-control" value="<?= $entity->name ?>" readonly>
						</div>
						
						<div class="col-md-4">
							<label class="form-label">País</label>
							<input type="text" class="form-control" value="<?= $entity->country ?>" readonly>
						</div>
						
						<div class="col-md-4">
							<label class="form-label">Tax ID</label>
							<input type="text" class="form-control" value="<?= $entity->tax_id ?>" readonly>
						</div>
						
						<div class="col-md-4">
							<label class="form-label">Página Web</label>
							<input type="text" class="form-control" value="<?= $entity->website ?>" readonly>
						</div>
						
						<div class="col-md-4">
							<label class="form-label">Teléfono</label>
							<input type="text" class="form-control" value="<?= $entity->phone ?>" readonly>
						</div>
						
						<div class="col-md-4">
							<label class="form-label">Celular</label>
							<input type="text" class="form-control" value="<?= $entity->mobile ?>" readonly>
						</div>
						
						<div class="col-md-4">
							<label class="form-label">Registrado por</label>
							<input type="text" class="form-control" value="<?= !empty($entity->creator_name) ? $entity->creator_name : 'Sistema / Desconocido' ?> (<?= $entity->created_at ?>)" readonly>
						</div>
						
						<div class="col-md-12">
							<label class="form-label">Dirección</label>
							<input type="text" class="form-control" value="<?= $entity->address ? $entity->address : "-" ?>" readonly>
						</div>
						
						<div class="col-md-12">
							<label class="form-label">Descripción / Notas</label>
							<textarea class="form-control" style="height: 100px"><?= nl2br(htmlspecialchars($entity->description)) ?></textarea>
						</div>
					</div>
				</div>
			</div>

            <div class="card mt-4">
                <div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="card-title">Contactos de la Entidad</h5>
						<a href="<?= base_url('entity/contacts/'.$entity->id) ?>" class="btn btn-primary btn-sm"><i class="bi bi-people"></i> Gestionar</a>
						
						<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addContactModal">
							<i class="bi bi-plus-lg"></i> Agregar
						</button>
						<div class="modal fade" id="addContactModal" tabindex="-1" style="display: none;" aria-hidden="true">
							<div class="modal-dialog">
								<form action="<?= base_url('entity/add_contact'); ?>" method="post" class="modal-content needs-validation" novalidate>
									<div class="modal-header">
										<h5 class="modal-title">Registrar Nuevo Contacto</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<div class="row g-3">
										
											<input type="hidden" name="entity_id" value="<?= $entity->id; ?>">
											
											<div class="col-12">
												<label class="form-label fw-bold">Nombre</label>
												<input type="text" name="contact_name" class="form-control" placeholder="Ej: Juan Pérez" required>
												<div class="invalid-feedback">Por favor, ingrese el nombre.</div>
											</div>
											
											<div class="col-12">
												<label class="form-label fw-bold">Cargo</label>
												<input type="text" name="position" class="form-control" placeholder="Ej: Gerente de Ventas">
											</div>
											
											<div class="col-12">
												<label class="form-label fw-bold">Email</label>
												<input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com" required>
												<div class="invalid-feedback">Ingrese un correo válido.</div>
											</div>
											
											<div class="col-12">
												<label class="form-label fw-bold">Teléfono</label>
												<input type="text" name="phone" class="form-control" placeholder="+51 ...">
											</div>
											
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary">Agregar</button>
									</div>
								</form>
							</div>
						</div>
					</div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 100px;">Estado</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Cargo</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($contacts)): ?>
                                    <?php foreach($contacts as $co): ?>
                                    <tr>
                                        <td>
                                            <?php if($co->status == 1): ?>
                                                <span class="badge bg-success-light text-success border border-success-subtle">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger-light text-danger border border-danger-subtle">Eliminado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold"><?= $co->contact_name; ?></td>
                                        <td><?= $co->position ? $co->position : '<span class="text-muted small">-</span>'; ?></td>
                                        <td><?= $co->email; ?></td>
                                        <td><?= $co->phone ? $co->phone : '<span class="text-muted small">-</span>'; ?></td>
                                        <td class="text-center">
                                            <?php if($co->status == 1): ?>
                                                <?php if($co->is_main == 0): ?>
                                                    <div class="btn-group" role="group">
                                                        <a href="<?= base_url('entity/make_main_contact/'.$co->id.'/'.$entity->id); ?>" 
                                                           class="btn btn-outline-primary btn-sm" title="Definir Principal">
                                                            <i class="bi bi-star"></i>
                                                        </a>
                                                        <a href="<?= base_url('entity/delete_contact/'.$co->id.'/'.$entity->id); ?>" 
                                                           class="btn btn-outline-danger btn-sm" 
                                                           onclick="return confirm('¿Está seguro de eliminar este contacto?');" title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-primary small fw-bold">Contacto Principal</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted small italic">Sin acciones</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="bi bi-info-circle me-1"></i> No hay contactos registrados para esta entidad.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>