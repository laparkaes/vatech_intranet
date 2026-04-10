<div class="pagetitle">
	<h1>Nuevo Usuario</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
			<li class="breadcrumb-item">Sistema</li>
			<li class="breadcrumb-item"><a href="<?= base_url("accounts") ?>">Usuarios</a></li>
			<li class="breadcrumb-item active">Registrar</li>
		</ol>
	</nav>
</div>

<section class="section">
	<div class="row">
		<div class="col-lg-6 mx-auto">
			<a href="<?= base_url("accounts") ?>" class="btn btn-primary mb-3"><i class="bi bi-arrow-left"></i> Volver a la lista</a>
		</div>
	</div>
	<div class="row">	
		<div class="col-lg-6 mx-auto">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Registrar Nuevo Usuario</h5>
					<form class="row needs-validation g-3" action="<?php echo base_url('accounts/add'); ?>" method="post" novalidate>
						<div class="col-md-12">
							<label for="inputEmail" class="form-label">Correo Electrónico</label>
							<input type="email" class="form-control" id="inputEmail" name="email" value="<?php echo set_value('email'); ?>" required>
							<div class="invalid-feedback">Campo obligatorio en formato de correo electrónico.</div>
						</div>
						<div class="col-md-12">
							<label for="inputPassword" class="form-label">Contraseña</label>
							<input type="password" class="form-control" id="inputPassword" name="password" required>
							<div class="invalid-feedback">Campo obligatorio.</div>
						</div>
						<div class="col-md-8">
							<label for="inputName" class="form-label">Nombre Completo</label>
							<input type="text" class="form-control" id="inputName" name="full_name" value="<?php echo set_value('full_name'); ?>" required>
							<div class="invalid-feedback">Campo obligatorio.</div>
						</div>
						<div class="col-md-4">
							<label for="inputHire" class="form-label">Fecha de Ingreso</label>
							<input type="date" class="form-control" id="inputHire" name="hire_date" value="<?php echo set_value('hire_date'); ?>" required>
							<div class="invalid-feedback">Campo obligatorio.</div>
						</div>
						<div class="col-md-6">
							<label for="inputDivision" class="form-label">División</label>
							<select id="inputDivision" class="form-select" name="division_id">
								<option value="">Sin Asignar</option>
								<?php foreach($divisions as $div): ?>
									<option value="<?php echo $div->id; ?>" <?php echo set_select('division_id', $div->id); ?>><?php echo $div->division_name; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-6">
							<label for="inputRole" class="form-label">Rol</label>
							<select id="inputRole" class="form-select" name="role">
								<option value="user" <?php echo set_select('role', 'user'); ?>>USER</option>
								<option value="admin" <?php echo set_select('role', 'admin'); ?>>ADMIN</option>
							</select>
						</div>
						<div class="text-center">
							<button type="submit" class="btn btn-primary">Registrar</button>
							<button type="reset" class="btn btn-secondary">Reestablecer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

