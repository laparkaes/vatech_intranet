<div class="pagetitle">
    <h1>Detalles del Proveedor</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Mantenimiento</li>
            <li class="breadcrumb-item"><a href="<?= base_url("vendor") ?>">Proveedores</a></li>
            <li class="breadcrumb-item active">Detalle</li>
        </ol>
    </nav>
</div>

<section class="section profile">
    <div class="row">
        <div class="col-12">
            <div>
                <a href="<?= base_url('vendor') ?>" class="btn btn-primary mb-3"><i class="bi bi-arrow-left"></i> Lista</a>
                <a href="<?= base_url('vendor/edit/'.$vendor->id) ?>" class="btn btn-primary mb-3"><i class="bi bi-pencil"></i> Editar</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Detalle de Proveedor</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <input type="text" class="form-control <?= ($vendor->status == 1) ? 'text-success' : 'text-danger' ?>" value="<?= ($vendor->status == 1) ? 'ACTIVO' : 'INACTIVO' ?>" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <?php 
                            $aux = [];
                            if ($vendor->is_vendor) $aux[] = 'PROVEEDOR';
                            if ($vendor->is_dealer) $aux[] = 'DISTRIBUIDOR';
                            if (!$aux) $aux[] = "-";
                            ?>
                            <label class="form-label">Tipo</label>
                            <input type="text" class="form-control" value="<?= implode(", ", $aux) ?>" readonly>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" value="<?= $vendor->name ?>" readonly>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">País</label>
                            <input type="text" class="form-control" value="<?= $vendor->country ?>" readonly>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Tax ID</label>
                            <input type="text" class="form-control" value="<?= $vendor->tax_id ?>" readonly>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Página Web</label>
                            <input type="text" class="form-control" value="<?= $vendor->website ?>" readonly>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" value="<?= $vendor->phone ?>" readonly>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Celular</label>
                            <input type="text" class="form-control" value="<?= $vendor->mobile ?>" readonly>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Registrado por</label>
                            <input type="text" class="form-control" value="<?= !empty($vendor->creator_name) ? $vendor->creator_name : 'Sistema / Desconocido' ?> (<?= $vendor->created_at ?>)" readonly>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" value="<?= $vendor->address ? $vendor->address : "-" ?>" readonly>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Descripción / Notas</label>
                            <textarea class="form-control" style="height: 100px" readonly><?= $vendor->description ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Contactos</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addContactModal">
                            <i class="bi bi-plus-lg"></i> Agregar
                        </button>
                        
                        <div class="modal fade" id="addContactModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="<?= base_url('vendor/add_contact'); ?>" method="post" class="modal-content needs-validation" novalidate>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Registrar Nuevo Contacto</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <input type="hidden" name="entity_id" value="<?= $vendor->id; ?>">
                                            <div class="col-12">
                                                <label class="form-label">Nombre</label>
                                                <input type="text" name="contact_name" class="form-control" required>
                                                <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Cargo</label>
                                                <input type="text" name="position" class="form-control">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" required>
                                                <div class="invalid-feedback">Ingrese un correo válido.</div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Teléfono</label>
                                                <input type="text" name="phone" class="form-control">
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
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Cargo</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col" class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($contacts)): ?>
                                    <?php foreach($contacts as $co): ?>
                                    <tr>
                                        <td>
                                            <?php if($co->status == 1): ?>
                                                <span class="text-success">Activo</span>
                                            <?php else: ?>
                                                <span class="text-danger">Eliminado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $co->contact_name; ?></td>
                                        <td><?= $co->position ? $co->position : '-'; ?></td>
                                        <td><?= $co->email; ?></td>
                                        <td><?= $co->phone ? $co->phone : '-'; ?></td>
                                        <td class="text-end">
                                            <?php if($co->status == 1): ?>
                                            <div class="btn-group" role="group">
                                                <?php if($co->is_main == 0): ?>
                                                <a href="<?= base_url('vendor/make_main_contact/'.$co->id.'/'.$vendor->id); ?>" 
                                                   class="btn btn-outline-success btn-sm" onclick="return confirm('¿Está seguro de elegir como contacto principal?');" title="Definir Principal">
                                                    <i class="bi bi-star"></i>
                                                </a>
                                                <?php else: ?>
                                                <button type="button" class="btn btn-primary btn-sm" disabled>
                                                    <i class="bi bi-star-fill"></i>
                                                </button>
                                                <?php endif; ?>
                                                
                                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                                        title="Editar Contacto"
                                                        onclick="openEditContactModal(<?= htmlspecialchars(json_encode($co)); ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                
                                                <a href="<?= base_url('vendor/delete_contact/'.$co->id.'/'.$vendor->id); ?>" 
                                                   class="btn btn-outline-danger btn-sm" 
                                                   onclick="return confirm('¿Está seguro de eliminar este contacto?');" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="bi bi-info-circle me-1"></i> No hay contactos registrados.
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

<div class="modal fade" id="editContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('vendor/update_single_contact'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Contacto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="contact_id" id="edit_contact_id">
                    <input type="hidden" name="entity_id" value="<?= $vendor->id; ?>">

                    <div class="mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="contact_name" id="edit_contact_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cargo</label>
                        <input type="text" name="position" id="edit_position" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono Directo</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditContactModal(contact) {
    document.getElementById('edit_contact_id').value = contact.id;
    document.getElementById('edit_contact_name').value = contact.contact_name;
    document.getElementById('edit_position').value = contact.position;
    document.getElementById('edit_email').value = contact.email;
    document.getElementById('edit_phone').value = contact.phone;

    var editModal = new bootstrap.Modal(document.getElementById('editContactModal'));
    editModal.show();
}
</script>