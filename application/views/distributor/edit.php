<div class="pagetitle">
    <h1>Editar Distribuidor</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Mantenimiento</li>
            <li class="breadcrumb-item"><a href="<?= base_url("distributor") ?>">Distribuidores</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div>
                <a href="<?= base_url('distributor/view/'.$distributor->id) ?>" class="btn btn-primary mb-3">
                    <i class="bi bi-arrow-left"></i> Volver al Detalle
                </a>
            </div>

            <form action="<?= base_url('distributor/update'); ?>" method="post" class="needs-validation" novalidate>
                <input type="hidden" name="id" value="<?= $distributor->id; ?>">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Datos del Distribuidor: <?= $distributor->name; ?></h5>
                        
                        <div class="row g-3">
                            <div class="col-md-9">
                                <label class="form-label d-block">Tipo</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_vendor" id="is_vendor" value="1" <?= ($distributor->is_vendor) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_vendor">Proveedor</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_dealer" id="is_dealer" value="1" <?= ($distributor->is_dealer) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_dealer">Distribuidor</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Estado de la Entidad</label>
                                <select name="status" class="form-select">
                                    <option value="1" <?= ($distributor->status == 1) ? 'selected' : ''; ?>>Activo</option>
                                    <option value="0" <?= ($distributor->status == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Nombre o Razón Social</label>
                                <input type="text" name="name" class="form-control" value="<?= $distributor->name; ?>" required>
                                <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-muted">País (No editable)</label>
                                <input type="text" class="form-control bg-light" value="<?= $distributor->country_name ?? $distributor->country; ?>" readonly>
                                <input type="hidden" name="country_id" value="<?= $distributor->country_id; ?>">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-muted">Tax ID / RUC (No editable)</label>
                                <input type="text" name="tax_id" class="form-control bg-light" value="<?= $distributor->tax_id; ?>" readonly>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Teléfono Corporativo</label>
                                <input type="text" name="phone" class="form-control" value="<?= $distributor->phone; ?>">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Celular/Móvil</label>
                                <input type="text" name="mobile" class="form-control" value="<?= $distributor->mobile; ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="address" class="form-control" value="<?= $distributor->address; ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Sitio Web</label>
                                <input type="text" name="website" class="form-control" value="<?= $distributor->website; ?>">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Descripción / Notas</label>
                                <textarea name="description" class="form-control" style="height: 100px;"><?= $distributor->description; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 mb-5">
                    <button type="submit" class="btn btn-primary">
                        Actualizar
                    </button>
                    <a href="<?= base_url('distributor/view/'.$distributor->id); ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
// Bootstrap Validation Script
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