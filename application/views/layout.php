<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>VPR ERP</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Solo Estructura de Diseño (CSS de Posicionamiento) */
        body { 
            margin: 0; 
            padding: 0; 
            display: flex; 
            flex-direction: column; 
            height: 100vh; 
            overflow: hidden; 
        }
        
        /* Header: Altura fija de 80px */
        header { 
            height: 80px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 0 20px; 
            box-sizing: border-box;
            border-bottom: 1px solid; /* Solo línea divisoria básica */
        }

        /* Contenedor Principal */
        .wrapper { 
            display: flex; 
            flex: 1; 
            overflow: hidden; 
        }

        /* Sidebar: Ancho fijo de 200px */
        nav { 
            width: 200px; 
            padding: 20px; 
            box-sizing: border-box;
            overflow-y: auto;
            border-right: 1px solid; /* Solo línea divisoria básica */
        }

        /* Main Content Area */
        main { 
            flex: 1; 
            padding: 20px; 
            overflow-y: auto; 
            box-sizing: border-box;
        }

        /* Estructura básica de listas de menú */
        nav ul { list-style: none; padding: 0; margin: 0; }
        nav ul li { margin-bottom: 10px; }
        nav ul ul { padding-left: 15px; }
    </style>
</head>
<body>

    <header>
        <div>
            <h1>VPR ERP</h1>
        </div>
        <div>
            <span>Usuario: <?php echo $this->session->userdata('name'); ?></span>
            <a href="<?php echo base_url('auth/logout'); ?>">Logout</a>
        </div>
    </header>

    <div class="wrapper">
		<nav>
			<ul>
				<li><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
				
				<?php if ($user_role === 'admin' || in_array('Compras', $my_access)): ?>
				<li>
					<strong>Compras</strong>
					<ul>
						<li><a href="<?php echo base_url('purchase'); ?>">Lista de Compras</a></li>
						<li><a href="<?php echo base_url('vendor'); ?>">Proveedores</a></li>
					</ul>
				</li>
				<?php endif; ?>

				<?php if ($user_role === 'admin' || in_array('Ventas', $my_access)): ?>
				<li>
					<strong>Ventas</strong>
					<ul>
						<li><a href="<?php echo base_url('sales'); ?>">Lista de Ventas</a></li>
						<li><a href="<?php echo base_url('distributor'); ?>">Distribuidores</a></li>
					</ul>
				</li>
				<?php endif; ?>

				<?php if ($user_role === 'admin' || in_array('Maestro', $my_access)): ?>
				<li>
					<strong>Maestro</strong>
					<ul>
						<li><a href="<?php echo base_url('products'); ?>">Productos</a></li>
						<li><a href="<?php echo base_url('division'); ?>">Divisiones</a></li>
					</ul>
				</li>
				<?php endif; ?>

				<?php if ($user_role === 'admin' || in_array('Sistema', $my_access)): ?>
				<li>
					<strong>Sistema</strong>
					<ul>
						<li><a href="<?php echo base_url('accounts'); ?>">Usuarios</a></li>
						<li>
							<a href="<?php echo base_url('access'); ?>">Accesos</a>
							<ul>
								<li><a href="<?php echo base_url('access/requests'); ?>">Solicitudes</a></li>
							</ul>
						</li>
					</ul>
				</li>
				<?php endif; ?>

				<li><a href="<?php echo base_url('access/access_request'); ?>">Solicitud de Acceso</a></li>
			</ul>
		</nav>

        <main>
            <?php if($this->session->flashdata('error')): ?>
                <p><strong>Error:</strong> <?php echo $this->session->flashdata('error'); ?></p>
            <?php endif; ?>

            <?php if($this->session->flashdata('success')): ?>
                <p><strong>Success:</strong> <?php echo $this->session->flashdata('success'); ?></p>
            <?php endif; ?>

            <?php $this->load->view($main); ?>
        </main>
    </div>

</body>
</html>