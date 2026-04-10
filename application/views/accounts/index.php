<div class="pagetitle">
	<h1>Usuarios</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
			<li class="breadcrumb-item">Sistema</li>
			<li class="breadcrumb-item active">Usuarios</li>
		</ol>
	</nav>
</div>

<section class="section">
	<div class="row">
		<div class="col-12">
			<a href="<?php echo base_url('accounts/create'); ?>" class="btn btn-primary mb-3"><i class="bi bi-plus"></i> Agregar</a>
			<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#searchModal">
				<i class="bi bi-search"></i> Buscar
			</button>
			<div class="modal fade" id="searchModal" tabindex="-1" style="display: none;" aria-hidden="true">
				<div class="modal-dialog">
					<form method="GET" action="<?= base_url('accounts/index') ?>" class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Buscar Usuario</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3">
								<div class="col-md-12">
									<label class="form-label">Nombre</label>
									<input type="text" name="name" class="form-control" value="<?= $search['name'] ?>">
								</div>
								<div class="col-md-12">
									<label class="form-label">Email</label>
									<input type="text" name="email" class="form-control" value="<?= $search['email'] ?>">
								</div>
								<div class="col-md-12">
									<label class="form-label">División</label>
									<select name="division" class="form-select">
										<option value="">Todas</option>
										<?php foreach($divisions as $d): ?>
											<option value="<?= $d->id ?>" <?= ($search['division'] == $d->id) ? 'selected' : '' ?>><?= $d->division_name ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-md-6">
									<label class="form-label">Perfil</label>
									<select name="role" class="form-select">
										<option value="">Todos los </option>
										<option value="admin" <?= ($search['role'] == 'admin') ? 'selected' : '' ?>>ADMIN</option>
										<option value="user" <?= ($search['role'] == 'user') ? 'selected' : '' ?>>USER</option>
									</select>
								</div>
								<div class="col-md-6">
									<label class="form-label">Estado</label>
									<select name="status" class="form-select">
										<option value="">Todos los Estados</option>
										<option value="1" <?= ($search['status'] === '1') ? 'selected' : '' ?>>ACTIVO</option>
										<option value="0" <?= ($search['status'] === '0') ? 'selected' : '' ?>>INACTIVO</option>
									</select>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Buscar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Lista</h5>
						<table class="table text-center">
							<thead>
								<tr class="align-top">
									<th scope="col">No.</th> 
									<th scope="col">
										Nombre
										<?php if (!empty($search['name'])): ?>
											<br><small class="text-success fw-normal">(<?= $search['name'] ?>)</small>
										<?php endif; ?>
									</th>
									<th scope="col">
										Email
										<?php if (!empty($search['email'])): ?>
											<br><small class="text-success fw-normal">(<?= $search['email'] ?>)</small>
										<?php endif; ?>
									</th>
									<th scope="col">Fecha de Ingreso</th>
									<th scope="col">Antigüedad</th>
									<th scope="col">
										División
										<?php if (!empty($search['division'])): ?>
											<?php 
												// 선택된 부서 ID로 부서명을 찾음
												$selected_div = "";
												foreach($divisions as $d) {
													if($d->id == $search['division']) {
														$selected_div = $d->division_name;
														break;
													}
												}
											?>
											<br><small class="text-success fw-normal">(<?= $selected_div ?>)</small>
										<?php endif; ?>
									</th>
									<th scope="col">
										Perfil
										<?php if (!empty($search['role'])): ?>
											<br><small class="text-success fw-normal">(<?= strtoupper($search['role']) ?>)</small>
										<?php endif; ?>
									</th>
									<th scope="col">
										Estado
										<?php if ($search['status'] !== null && $search['status'] !== ''): ?>
											<br><small class="text-success fw-normal">(<?= ($search['status'] == '1') ? 'ACTIVO' : 'INACTIVO' ?>)</small>
										<?php endif; ?>
									</th>
									<th scope="col">Acciones</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$count = $start_no;
								foreach($users as $user): 
								?>
								<tr class="align-middle">
									<th scope="row"><?php echo $count++; ?></th>
									<td><?php echo $user->full_name; ?></td>
									<td><?php echo $user->email; ?></td>
									<td><?php echo $user->hire_date ? $user->hire_date : "-"; ?></td>
									<td><?php echo $user->hire_date ? $user->tenure : "-"; ?></td>
									<td><?php echo $user->division_name ? $user->division_name : 'Sin Asignar'; ?></td>
									<td><?php echo strtoupper($user->role); ?></td>
									<td><?php echo ($user->status == 1) ? 'ACTIVO' : 'INACTIVO'; ?></td>
									<td>
										<a href="<?php echo base_url('accounts/edit/'.$user->id); ?>">
											<i class="bi bi-pencil-square"></i>
										</a>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						
						<?php if (!$users): ?>
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<i class="bi bi-info-circle me-1"></i> No se encontraron registros o resultados de búsqueda.
						</div>
						<?php endif; ?>
						
						<div class="mt-4">
							<?php if ($total_rows > 30): ?>
								<?php echo $pagination; ?>
							<?php else: ?>
								<ul class="pagination justify-content-center">
									<li class="page-item active"><a class="page-link" href="#">1</a></li>
								</ul>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
	</div>
</section>