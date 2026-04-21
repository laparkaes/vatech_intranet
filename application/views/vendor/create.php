<div class="pagetitle">
    <h1>Registrar Nuevo Proveedor</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Maestro</li>
            <li class="breadcrumb-item"><a href="<?= base_url("vendor") ?>">Proveedores</a></li>
            <li class="breadcrumb-item active">Registrar</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div>
                <a href="<?= base_url('vendor') ?>" class="btn btn-secondary mb-3">
                    <i class="bi bi-arrow-left"></i> Lista
                </a>
            </div>

            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-octagon me-1"></i>
                    <?= $this->session->flashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('vendor/add'); ?>" method="post" class="needs-validation" novalidate>
                
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Datos del Proveedor</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-12 d-none">
                                <label class="form-label">Tipo de Entidad</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="is_vendor" id="is_vendor" value="1">
                                        <label class="form-check-label" for="is_vendor">Proveedor</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="is_dealer" id="is_dealer" value="1" checked>
                                        <label class="form-check-label" for="is_dealer text-primary">Distribuidor</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Nombre / Razón Social</label>
                                <input type="text" name="name" class="form-control" required>
                                <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">País</label>
                                <select name="country_id" class="form-select" required>
                                    <option value="">Seleccionar --</option>
                                    <?php foreach($countries as $c): ?>
                                        <option value="<?= $c->id; ?>"><?= $c->country_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Seleccione un país.</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Tax ID</label>
                                <input type="text" name="tax_id" class="form-control" required>
                                <div class="invalid-feedback">Ingrese el Tax ID.</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="phone" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Celular</label>
                                <input type="text" name="mobile" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Sitio Web</label>
                                <input type="text" name="website" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="address" class="form-control">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Notas / Descripción</label>
                                <textarea name="description" class="form-control" style="height: 80px;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Contactos</h5>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addContactRow()">
                                <i class="bi bi-plus-lg"></i> Agregar Contacto
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table align-middle" id="contactsTable">
                                <thead>
                                    <tr>
                                        <th>Nombre <span class="text-danger">*</span></th>
                                        <th>Cargo</th>
                                        <th>Email <span class="text-danger">*</span></th>
                                        <th>Teléfono</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="contactBody">
                                    <tr>
                                        <td>
                                            <input type="hidden" name="is_main[]" value="1">
                                            <input type="text" name="contact_name[]" class="form-control" required>
                                            <div class="invalid-feedback">Requerido.</div>
                                        </td>
                                        <td>
                                            <input type="text" name="position[]" class="form-control">
                                        </td>
                                        <td>
                                            <input type="email" name="contact_email[]" class="form-control" required>
                                            <div class="invalid-feedback">Email inválido.</div>
                                        </td>
                                        <td>
                                            <input type="text" name="indiv_phone[]" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-light btn-sm" disabled><i class="bi bi-dash-circle"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-4 mb-5">
                    <button type="submit" class="btn btn-primary">
                        Guardar
                    </button>
                    <a href="<?= base_url('vendor'); ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
// 연락처 행 추가 함수
function addContactRow() {
    const tbody = document.getElementById('contactBody');
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td>
            <input type="hidden" name="is_main[]" value="0">
            <input type="text" name="contact_name[]" class="form-control" required>
            <div class="invalid-feedback">Requerido.</div>
        </td>
        <td>
            <input type="text" name="position[]" class="form-control">
        </td>
        <td>
            <input type="email" name="contact_email[]" class="form-control" required>
            <div class="invalid-feedback">Email inválido.</div>
        </td>
        <td>
            <input type="text" name="indiv_phone[]" class="form-control">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeContactRow(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
}

function removeContactRow(btn) {
    const row = btn.closest('tr');
    row.remove();
}

// Bootstrap 유효성 검사 스크립트
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