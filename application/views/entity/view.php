<div class="pagetitle">
    <h1>Detalles de la Entidad</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Mantenimiento</li>
            <li class="breadcrumb-item"><a href="<?= base_url("entity") ?>">Entidades</a></li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
</div>

<section class="section profile">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="mb-3">
                <a href="<?= base_url('entity') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver al Listado</a>
                <a href="<?= base_url('entity/edit/'.$entity->id) ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Editar Información</a>
                <a href="<?= base_url('entity/contacts/'.$entity->id) ?>" class="btn btn-info text-white"><i class="bi bi-people"></i> Gestionar Contactos</a>
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
                    <h5 class="card-title">Contactos de la Entidad</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Estado</th>
                                    <th>Tipo</th>
                                    <th>Nombre</th>
                                    <th>Cargo</th>
                                    <th>Email</th>
                                    <th>Teléfono Directo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($contacts)): ?>
                                    <?php foreach($contacts as $co): ?>
                                    <tr>
                                        <td>
                                            <span class="badge <?= ($co->status == 1) ? 'bg-light text-success border' : 'bg-light text-danger border' ?>">
                                                <?= ($co->status == 1) ? 'Activo' : 'Eliminado' ?>
                                            </span>
                                        </td>
                                        <td><?= ($co->is_main == 1) ? '<span class="badge bg-primary">Principal</span>' : 'Adicional' ?></td>
                                        <td class="fw-bold"><?= $co->contact_name ?></td>
                                        <td><?= $co->position ?></td>
                                        <td><?= $co->email ?></td>
                                        <td><?= $co->phone ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay contactos registrados para esta entidad.</td>
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