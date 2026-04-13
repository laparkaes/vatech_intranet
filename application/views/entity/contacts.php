<div class="pagetitle">
    <h1>Gestión de Contactos: <strong><?= $entity->name; ?></strong></h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Mantenimiento</li>
            <li class="breadcrumb-item"><a href="<?= base_url("entity") ?>">Entidades</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('entity/view/'.$entity->id); ?>">Detalle</a></li>
            <li class="breadcrumb-item active">Contactos</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <a href="<?= base_url('entity/view/'.$entity->id); ?>" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
					<i class="bi bi-plus-lg"></i> Agregar
				</button>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Lista de Contactos Registrados</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 100px;">Estado</th>
                                    <th scope="col" style="width: 120px;">Tipo</th>
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
                                        <td>
                                            <?php if($co->is_main == 1): ?>
                                                <span class="badge bg-primary"><i class="bi bi-star-fill"></i> Principal</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark border">Adicional</span>
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