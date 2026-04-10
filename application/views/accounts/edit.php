<div class="pagetitle">
    <h1>Editar Usuario</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item"><a href="<?= base_url("accounts") ?>">Usuarios</a></li>
            <li class="breadcrumb-item active">Editar</li>
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
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-octagon me-1"></i>
                    <?= $this->session->flashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información del Usuario: <?= $user->full_name ?></h5>

                    <form class="row needs-validation g-3" action="<?php echo base_url('accounts/update'); ?>" method="post" novalidate>
                        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">

                        <div class="col-md-12">
                            <label for="inputEmail" class="form-label">Correo Electrónico (No editable)</label>
                            <input type="email" class="form-control bg-light" id="inputEmail" value="<?php echo $user->email; ?>" readonly>
                        </div>

                        <div class="col-md-12">
                            <label for="inputPassword" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="inputPassword" name="password" minlength="6">
                            <div class="form-text">Dejar en blanco para no cambiar la contraseña actual.</div>
							<div class="invalid-feedback">Debe ingresar al menos 6 letras.</div>
                        </div>

                        <div class="col-md-8">
                            <label for="inputName" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="inputName" name="full_name" 
                                   value="<?php echo set_value('full_name', $user->full_name); ?>" required>
                            <div class="invalid-feedback">Campo obligatorio.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="inputHire" class="form-label">Fecha de Ingreso</label>
                            <input type="date" class="form-control" id="inputHire" name="hire_date" 
                                   value="<?php echo set_value('hire_date', $user->hire_date); ?>" required>
                            <div class="invalid-feedback">Campo obligatorio.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="inputDivision" class="form-label">División</label>
                            <select id="inputDivision" class="form-select" name="division_id">
                                <option value="">Sin Asignar</option>
                                <?php foreach($divisions as $div): ?>
                                    <option value="<?php echo $div->id; ?>" 
                                        <?php echo set_select('division_id', $div->id, ($user->division_id == $div->id)); ?>>
                                        <?php echo $div->division_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="inputRole" class="form-label">Rol</label>
                            <?php $is_self = ($user->id == $this->session->userdata('user_id')); ?>
                            <select id="inputRole" class="form-select" name="role" <?php echo $is_self ? 'disabled' : ''; ?>>
                                <option value="user" <?php echo set_select('role', 'user', ($user->role == 'user')); ?>>USER</option>
                                <option value="admin" <?php echo set_select('role', 'admin', ($user->role == 'admin')); ?>>ADMIN</option>
                            </select>
                            <?php if($is_self): ?>
                                <input type="hidden" name="role" value="<?php echo $user->role; ?>">
                            <?php endif; ?>
                        </div>

                        <div class="col-md-3">
                            <label for="inputStatus" class="form-label">Estado</label>
                            <select id="inputStatus" class="form-select" name="status" <?php echo $is_self ? 'disabled' : ''; ?>>
                                <option value="1" <?php echo set_select('status', '1', ($user->status == 1)); ?>>ACTIVO</option>
                                <option value="0" <?php echo set_select('status', '0', ($user->status == 0)); ?>>INACTIVO</option>
                            </select>
                            <?php if($is_self): ?>
                                <input type="hidden" name="status" value="<?php echo $user->status; ?>">
                            <?php endif; ?>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                            <a href="<?= base_url("accounts") ?>" class="btn btn-secondary px-4">Cancelar</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>