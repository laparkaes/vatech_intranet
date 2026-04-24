<div class="pagetitle">
    <h1>Configuración de Maestro de Accesos</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Maestro</li>
            <li class="breadcrumb-item active">Accesos</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addAccessModal">
                <i class="bi bi-plus"></i> Registrar
            </button>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Lista</h5>
                    
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Nombre</th>
                                <th scope="col" style="width: 400px;">Descripción</th>
                                <th scope="col">Estado</th>
								<th scope="col">Actualizado por</th>
                                <th class="text-end" scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($access_list)): ?>
                                <?php $count = 1; foreach($access_list as $access): ?>
                                <tr>
                                    <td><?php echo $count; $count++; ?></td>
                                    <td><?php echo $access->access_name; ?></td>
                                    <td><?php echo htmlspecialchars($access->description); ?></td>
                                    <td>
                                        <?php if($access->status == 1): ?>
                                            <span class="text-success">Activo</span>
                                        <?php else: ?>
                                            <span class="text-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
										<div><?php echo $access->updated_user_name ?: '-'; ?></div>
										<div><small class="text-muted"><?php echo $access->updated_at ?: $access->created_at; ?></div>
									</td>
                                    <td class="text-end">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary edit-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editAccessModal"
                                                data-id="<?php echo $access->id; ?>"
                                                data-name="<?php echo htmlspecialchars($access->access_name); ?>"
                                                data-desc="<?php echo htmlspecialchars($access->description); ?>"
                                                data-status="<?php echo $access->status; ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">No hay accesos registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addAccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo base_url('access/add'); ?>" method="post" class="modal-content needs-validation" novalidate>
            <div class="modal-header">
                <h5 class="modal-title">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="access_name" class="form-control" placeholder="Ej: Compras" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Descripción del módulo"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editAccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo base_url('access/update'); ?>" method="post" class="modal-content needs-validation" novalidate>
            <div class="modal-header">
                <h5 class="modal-title">Editar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <input type="hidden" name="id" id="edit_access_id">
                    <div class="col-md-12">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="access_name" id="edit_access_name" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" id="edit_access_desc" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Estado</label>
                        <select name="status" id="edit_access_status" class="form-select" required>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 수정 버튼 클릭 시 데이터 바인딩 로직
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // 버튼의 data-* 속성에서 정보 추출
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const desc = this.getAttribute('data-desc');
            const status = this.getAttribute('data-status');

            // 모달 내부 필드에 매핑
            document.getElementById('edit_access_id').value = id;
            document.getElementById('edit_access_name').value = name;
            document.getElementById('edit_access_desc').value = desc;
            document.getElementById('edit_access_status').value = status;
        });
    });
});
</script>