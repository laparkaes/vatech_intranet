<div class="pagetitle">
	<h1>Divisiones</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
			<li class="breadcrumb-item">Maestro</li>
			<li class="breadcrumb-item active">Divisiones</li>
		</ol>
	</nav>
</div>

<section class="section">
	<div class="row">
		<div class="col-lg-4">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Nueva División</h5>
					<form class="row needs-validation g-3" action="<?php echo base_url('division/add'); ?>" method="post" novalidate>
						<div class="col-md-12">
							<label for="inputDivision" class="form-label">Nombre de División</label>
							<input type="text" class="form-control" id="inputDivision" name="division_name" required>
							<div class="invalid-feedback">Campo obligatorio.</div>
						</div>
						<div class="col-md-12">
							<label for="inputParent" class="form-label">División Superior</label>
							<select class="form-select" id="inputParent" name="parent_id">
								<option value="">-</option>
								<?php foreach($parent_divisions as $p): ?>
									<option value="<?php echo $p->id; ?>"><?php echo $p->division_name; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="text-center pt-3">
							<button type="submit" class="btn btn-primary">Registrar</button>
							<button type="reset" class="btn btn-secondary">Reset</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
		
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Lista</h5>
					<table class="table align-middle text-center">
						<thead>
							<tr>
								<th>No.</th>
								<th>Nombre de División</th>
								<th>División Superior</th>
								<th>Estado</th>
								<th>Acción</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$count = 1; 
							foreach($list as $item): 
							?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo $item->division_name; ?></td>
								<td><?php echo $item->parent_name ? $item->parent_name : '-'; ?></td>
								<td>
									<?php if($item->status == 1): ?>
										<span class="text-success">ACTIVO</span>
									<?php else: ?>
										<span class="text-danger">INACTIVO</span>
									<?php endif; ?>
								</td>
								<td>
									<button type="button" 
											class="btn btn-sm btn-primary edit-btn" 
											data-bs-toggle="modal" 
											data-bs-target="#editModal"
											data-id="<?php echo $item->id; ?>"
											data-name="<?php echo htmlspecialchars($item->division_name); ?>"
											data-parent="<?php echo $item->parent_id; ?>"
											data-status="<?php echo $item->status; ?>">
										<i class="bi bi-pencil-square"></i>
									</button>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar División</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo base_url('division/update'); ?>" method="post" class="needs-validation" novalidate>
                <div class="modal-body">
					<div class="row g-3">
						<input type="hidden" name="id" id="edit_id">

						<div class="col-md-12">
							<label class="form-label">Nombre de División</label>
							<input type="text" name="division_name" id="edit_name" class="form-control" required>
						</div>

						<div class="col-md-12">
							<label class="form-label">División Superior</label>
							<select name="parent_id" id="edit_parent" class="form-select">
								<option value="">-</option>
								<?php foreach($parent_divisions as $p): ?>
									<option value="<?php echo $p->id; ?>"><?php echo $p->division_name; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="col-md-12">
							<label class="form-label">Estado</label>
							<select name="status" id="edit_status" class="form-select">
								<option value="1">ACTIVO</option>
								<option value="0">INACTIVO</option>
							</select>
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-btn');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // 버튼에서 데이터 추출
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const parent = this.getAttribute('data-parent');
            const status = this.getAttribute('data-status');

            // 모달 필드에 데이터 삽입
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_parent').value = parent;
            document.getElementById('edit_status').value = status;
			
			// 자기 자신을 상위 부서로 선택하지 못하도록 처리 (로직 보강 시 필요)
			const parentSelect = document.getElementById('edit_parent');
			Array.from(parentSelect.options).forEach(option => {
				option.disabled = (option.value == id); 
			});
        });
    });
});
</script>