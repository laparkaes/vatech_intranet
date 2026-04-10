<div class="pagetitle">
    <h1>Productos</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>">Dashboard</a></li>
            <li class="breadcrumb-item">Logística</li>
            <li class="breadcrumb-item active">Productos</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <a href="<?php echo base_url('product/create'); ?>" class="btn btn-primary mb-3"><i class="bi bi-plus"></i> Registrar Nuevo</a>
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#searchModal">
                <i class="bi bi-search"></i> Buscar
            </button>

            <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="GET" action="<?= base_url('product/index') ?>" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Filtrar Productos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Nombre del Producto</label>
                                    <input type="text" name="name" class="form-control" value="<?= $search['name'] ?? '' ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tipo</label>
                                    <select name="type" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="Producto" <?= ($search['type'] ?? '' == 'Producto') ? 'selected' : '' ?>>PRODUCTO</option>
                                        <option value="Servicio" <?= ($search['type'] ?? '' == 'Servicio') ? 'selected' : '' ?>>SERVICIO</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Estado</label>
                                    <select name="status" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="1" <?= ($search['status'] ?? '' === '1') ? 'selected' : '' ?>>ACTIVO</option>
                                        <option value="0" <?= ($search['status'] ?? '' === '0') ? 'selected' : '' ?>>INACTIVO</option>
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
                    <h5 class="card-title">Lista de Productos y Servicios</h5>
                    <table class="table text-center table-hover">
                        <thead>
                            <tr class="align-top">
                                <th scope="col">No.</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Producto</th>
                                <th scope="col">Opción</th>
                                <th scope="col">Precio de Venta</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = $start_no ?? 1;
                            if(!empty($products)): 
                                foreach($products as $p): 
                                    $items = $p->items ?? [];
                                    if(!empty($items)):
                                        foreach($items as $item): 
                            ?>
                            <tr class="align-middle">
                                <th scope="row"><?php echo $count++; ?></th>
                                <td><span class="badge bg-secondary"><?php echo $p->type; ?></span></td>
                                <td><?php echo $p->category_name; ?></td>
                                <td class="text-start"><b><?php echo $p->name; ?></b></td>
                                <td><small><?php echo $item->option; ?></small></td>
                                <td>
									USD <?php 
										// 속성이 있는지 확인하고 없으면 0.00 또는 '-' 표시
										echo isset($item->sale_price_usd) ? number_format($item->sale_price_usd, 2) : '0.00'; 
									?>
									<br/>
									PEN <?php 
										echo isset($item->sale_price_pen) ? number_format($item->sale_price_pen, 2) : '0.00'; 
									?>
								</td>
                                <td>
                                    <?php if($p->is_active == 1): ?>
                                        <span class="text-success">ACTIVO</span>
                                    <?php else: ?>
                                        <span class="text-danger">INACTIVO</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('product/view/'.$p->id); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                        endforeach;
                                    else: 
                            ?>
                            <tr class="align-middle">
                                <th scope="row"><?php echo $count++; ?></th>
                                <td><span class="badge bg-secondary"><?php echo $p->type; ?></span></td>
                                <td><?php echo $p->category_name; ?></td>
                                <td class="text-start"><b><?php echo $p->name; ?></b></td>
                                <td class="text-muted">-</td>
                                <td class="text-muted">-</td>
                                <td class="text-muted">-</td>
                                <td>
                                    <?php echo ($p->is_active == 1) ? '<span class="text-success">ACTIVO</span>' : '<span class="text-danger">INACTIVO</span>'; ?>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('product/view/'.$p->id); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                    endif;
                                endforeach; 
                            else: 
                            ?>
                            <tr>
                                <td colspan="9" class="py-4">
                                    <div class="alert alert-warning d-inline-block mb-0" role="alert">
                                        <i class="bi bi-info-circle me-1"></i> No se encontraron registros.
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <?php if (isset($total_rows) && $total_rows > 30): ?>
                            <?php echo $pagination_links; ?>
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