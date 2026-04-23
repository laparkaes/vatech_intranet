<div class="pagetitle">
    <h1>Gestión de Tipo de Cambio</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Configuración</li>
            <li class="breadcrumb-item active">Tipo de Cambio</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addExchangeModal">
                <i class="bi bi-plus"></i> Registrar Tasa
            </button>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Historial de Tasas</h5>
                    
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Par de Divisas</th>
                                <th scope="col">Tasa (Rate)</th>
                                <th scope="col">Promedio (30)</th>
                                <th scope="col">Fecha Efectiva</th>
                                <th scope="col">Responsable</th>
                                <th class="text-end" scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($rates)): ?>
                                <?php foreach($rates as $r): ?>
                                <tr>
                                    <td><?php echo $start_no++; ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border">1 <?php echo $r->base_currency; ?></span>
                                        <i class="bi bi-arrow-right text-muted mx-1"></i>
                                        <span class="badge bg-light text-dark border"><?php echo $r->target_currency; ?></span>
                                    </td>
                                    <td><strong><?php echo number_format($r->rate, 4); ?></strong></td>
                                    <td>
                                        <span class="text-secondary small">avg: <?php echo number_format($r->avg_30last, 4); ?></span>
                                    </td>
                                    <td><?php echo $r->effective_date; ?></td>
                                    <td><small class="text-muted"><?php echo $r->user_name; ?></small></td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary edit-btn" 
                                                data-bs-toggle="modal" data-bs-target="#editExchangeModal"
                                                data-id="<?php echo $r->id; ?>"
                                                data-base="<?php echo $r->base_currency; ?>"
                                                data-target="<?php echo $r->target_currency; ?>"
                                                data-rate="<?php echo $r->rate; ?>"
                                                data-date="<?php echo $r->effective_date; ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No se encontraron registros.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <?php echo (isset($total_rows) && $total_rows > 30) ? $pagination : '<ul class="pagination justify-content-center"><li class="page-item active"><a class="page-link" href="#">1</a></li></ul>'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
    // 리다이렉트 후 전달된 임시 데이터가 있는지 확인
    $temp = $this->session->flashdata('temp_data'); 
?>

<div class="modal fade" id="addExchangeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo base_url('exchange/add'); ?>" method="post" class="modal-content needs-validation" novalidate>
            <div class="modal-header">
                <h5 class="modal-title">Registrar Nueva Tasa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Moneda Base</label>
                        <select name="base_currency" class="form-select" required>
                            <option value="USD" <?php echo (isset($temp['base_currency']) && $temp['base_currency'] == 'USD') ? 'selected' : ''; ?>>USD - Dólar</option>
                            <option value="PEN" <?php echo (isset($temp['base_currency']) && $temp['base_currency'] == 'PEN') ? 'selected' : ''; ?>>PEN - Sol</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Moneda Destino</label>
                        <select name="target_currency" class="form-select" required>
                            <option value="PEN" <?php echo (!isset($temp) || (isset($temp['target_currency']) && $temp['target_currency'] == 'PEN')) ? 'selected' : ''; ?>>PEN - Sol</option>
                            <option value="USD" <?php echo (isset($temp['target_currency']) && $temp['target_currency'] == 'USD') ? 'selected' : ''; ?>>USD - Dólar</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tasa (Rate)</label>
                        <input type="number" name="rate" class="form-control" step="0.0001" 
                               value="<?php echo $temp['rate'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha Efectiva</label>
                        <input type="date" name="effective_date" class="form-control" 
                               value="<?php echo $temp['effective_date'] ?? date('Y-m-d'); ?>" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Tasa</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editExchangeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo base_url('exchange/update'); ?>" method="post" class="modal-content needs-validation" novalidate>
            <div class="modal-header">
                <h5 class="modal-title">Editar Tipo de Cambio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="col-md-6">
                        <label class="form-label">Moneda Base</label>
                        <select name="base_currency" id="edit_base" class="form-select" required>
                            <option value="USD">USD - Dólar</option>
                            <option value="PEN">PEN - Sol</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Moneda Destino</label>
                        <select name="target_currency" id="edit_target" class="form-select" required>
                            <option value="PEN">PEN - Sol</option>
                            <option value="USD">USD - Dólar</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tasa (Rate)</label>
                        <input type="number" name="rate" id="edit_rate" class="form-control" step="0.0001" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha Efectiva</label>
                        <input type="date" name="effective_date" id="edit_date" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.getAttribute('data-id');
            document.getElementById('edit_base').value = this.getAttribute('data-base');
            document.getElementById('edit_target').value = this.getAttribute('data-target');
            document.getElementById('edit_rate').value = this.getAttribute('data-rate');
            document.getElementById('edit_date').value = this.getAttribute('data-date');
        });
    });
});
</script>