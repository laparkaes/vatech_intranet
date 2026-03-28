<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VPR ERP</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* 레이아웃 구조 (Positioning) */
        body { 
            margin: 0; 
            padding: 0; 
            display: flex; 
            flex-direction: column; 
            height: 100vh; 
            overflow: hidden; 
        }
        
        header { 
            height: 80px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 0 20px; 
            box-sizing: border-box;
            flex-shrink: 0;
            border-bottom: 1px solid; /* 최소한의 구분선 */
        }

        .wrapper { 
            display: flex; 
            flex: 1; 
            overflow: hidden; 
        }

        nav { 
            width: 200px; 
            padding: 20px; 
            box-sizing: border-box;
            overflow-y: auto;
            flex-shrink: 0;
            border-right: 1px solid; /* 최소한의 구분선 */
        }

        main { 
            flex: 1; 
            padding: 20px; 
            overflow-y: auto; 
            box-sizing: border-box;
        }

        /* 메뉴 리스트 구조 */
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
                        <li><a href="<?php echo base_url('sale'); ?>">Lista de Ventas</a></li>
                        <li><a href="<?php echo base_url('distributor'); ?>">Distribuidores</a></li>
                    </ul>
                </li>
                <?php endif; ?>

				<?php if ($user_role === 'admin' || in_array('Logistics', $my_access)): ?>
                <li>
                    <strong>Logística</strong> <ul>
                        <li><a href="<?php echo base_url('warehouse'); ?>">Almacenes</a></li>
                        
                        <li><a href="<?php echo base_url('inventory'); ?>">Inventario</a></li>
                        
                        <li><a href="<?php echo base_url('inbound'); ?>">Ingresos</a></li>
                        
                        <li><a href="<?php echo base_url('outbound'); ?>">Salidas</a></li>
                    </ul>
                </li>
                <?php endif; ?>
				
                <?php if ($user_role === 'admin' || in_array('Maestro', $my_access)): ?>
                <li>
					<strong>Maestro</strong>
					<ul>
						<li><a href="<?php echo base_url('product'); ?>">Productos</a></li>
						<li><a href="<?php echo base_url('division'); ?>">Divisiones</a></li>
						<li><a href="<?php echo base_url('entity'); ?>">Entidades</a></li>
						<li><a href="<?php echo base_url('exchange'); ?>">Tipos de Cambio</a></li>
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