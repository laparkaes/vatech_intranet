<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>VPR ERP - Sistema de Gestión</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
		<h1>VPR ERP</h1>
		<p>
			Usuario: <?php echo $this->session->userdata('name'); ?> 
			(<?php echo $this->session->userdata('email'); ?>) | 
			<a href="<?php echo base_url('auth/logout'); ?>">Cerrar Sesión</a>
		</p>
	
        <nav>
            <ul>
                <li><a href="<?php echo base_url('dashboard'); ?>"><strong>Dashboard</strong></a></li>

                <li>
                    <strong>Compras</strong>
                    <ul>
                        <li><a href="<?php echo base_url('purchase'); ?>">Compras</a></li>
                    </ul>
                </li>

                <li>
                    <strong>Ventas</strong>
                    <ul>
                        <li><a href="<?php echo base_url('sales'); ?>">Ventas</a></li>
                    </ul>
                </li>

                <li>
                    <strong>Maestros</strong>
                    <ul>
                        <li><a href="<?php echo base_url('products'); ?>">Productos</a></li>
						<li><a href="<?php echo base_url('vendor'); ?>">Proveedores</a></li>
                        <li><a href="<?php echo base_url('distributor'); ?>">Distribuidores</a></li>
                    </ul>
                </li>
				
                <li>
                    <strong>Sistema</strong>
                    <ul>
                        <li><a href="<?php echo base_url('accounts'); ?>">Usuarios</a></li>
                        <li>
							<a href="#">Accesos</a>
							<ul>
								<li><a href="<?php echo base_url('access/access_request'); ?>">Nueva Solicitud</a></li>
								<?php if($this->session->userdata('role') === 'admin'): ?>
								<li><a href="<?php echo base_url('access/request_list'); ?>">Gestión de Solicitudes</a></li>
								<?php endif; ?>
							</ul>
						</li>
                        <li><a href="<?php echo base_url('system'); ?>">Configuración</a></li>
                        <li><a href="<?php echo base_url('reports'); ?>">Reportes</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <hr>

	<main>
        <?php if($this->session->flashdata('error')): ?>
            <p><strong>AVISO:</strong> <?php echo $this->session->flashdata('error'); ?></p>
        <?php endif; ?>

        <?php if($this->session->flashdata('success')): ?>
            <p><strong>ÉXITO:</strong> <?php echo $this->session->flashdata('success'); ?></p>
        <?php endif; ?>

        <?php $this->load->view($main); ?>
    </main>

    <hr>
    <footer>
        <p>&copy; 2026 VPR - Sistema de Gestión Interna</p>
    </footer>
</body>
</html>