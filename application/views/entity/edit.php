<div class="pagetitle">
    <h1>Editar Entidad</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Maestro</li>
            <li class="breadcrumb-item"><a href="<?= base_url("entity") ?>">Entidades</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div>
                <a href="<?= base_url('entity') ?>" class="btn btn-secondary mb-3">
                    <i class="bi bi-arrow-left"></i> Lista
                </a>
            </div>

            <form action="<?= base_url('entity/update'); ?>" method="post" class="needs-validation" novalidate>
                <input type="hidden" name="id" value="<?= $entity->id; ?>">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Datos de Entidad: <?= $entity->name; ?></h5>
                        
                        <div class="row g-3">
                            <div class="col-md-9">
                                <label class="form-label d-block">Tipo</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_vendor" id="is_vendor" value="1" <?= ($entity->is_vendor) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_vendor">Proveedor</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_dealer" id="is_dealer" value="1" <?= ($entity->is_dealer) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_dealer">Distribuidor</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Estado de la Entidad</label>
                                <select name="status" class="form-select">
                                    <option value="1" <?= ($entity->status == 1) ? 'selected' : ''; ?>>Activo</option>
                                    <option value="0" <?= ($entity->status == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Nombre o Razón Social</label>
                                <input type="text" name="name" class="form-control" value="<?= $entity->name; ?>" required>
                                <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-muted">País (No editable)</label>
                                <input type="text" class="form-control bg-light" value="<?= $entity->country_name ?? $entity->country; ?>" readonly>
                                <input type="hidden" name="country_id" value="<?= $entity->country_id; ?>">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-muted">Tax ID / RUC (No editable)</label>
                                <input type="text" name="tax_id" class="form-control bg-light" value="<?= $entity->tax_id; ?>" readonly>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Teléfono Corporativo</label>
                                <input type="text" name="phone" class="form-control" value="<?= $entity->phone; ?>">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Celular/Móvil</label>
                                <input type="text" name="mobile" class="form-control" value="<?= $entity->mobile; ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="address" class="form-control" value="<?= $entity->address; ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Sitio Web</label>
                                <input type="text" name="website" class="form-control" value="<?= $entity->website; ?>">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Descripción / Notas</label>
                                <textarea name="description" class="form-control" style="height: 100px;"><?= $entity->description; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4 shadow-none border">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title text-muted">Contactos Registrados</h5>
                            <small class="text-secondary"><i class="bi bi-info-circle"></i> Los contactos se gestionan en una sección separada.</small>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Cargo</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($contacts)): ?>
                                        <?php foreach($contacts as $contact): ?>
                                        <tr>
                                            <td><?= $contact->contact_name; ?> <?php if($contact->is_main) echo '<span class="badge bg-info text-dark">Principal</span>'; ?></td>
                                            <td><?= $contact->position; ?></td>
                                            <td><?= $contact->email; ?></td>
                                            <td><?= $contact->phone; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">No hay contactos registrados.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-4 mb-5">
                    <button type="submit" class="btn btn-primary px-5">
                        Actualizar Entidad
                    </button>
                    <a href="<?= base_url('entity/view/'.$entity->id); ?>" class="btn btn-secondary px-4">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
// 부트스트랩 유효성 검사 전용 스크립트
(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
})()
</script>