<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Nueva Venta (Nuevo Registro)</h1>

    <form action="<?= site_url('sale/add') ?>" method="post" id="saleForm">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Datos Generales</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Cliente / Dealer</label>
                            <select name="customer_id" class="form-control select2" required>
								<option value="">Seleccione Cliente (Distribuidor)</option>
								<?php foreach($customers as $c): ?>
									<option value="<?= $c->id ?>">
										<?= $c->name ?> <?= !empty($c->tax_id) ? '[' . $c->tax_id . ']' : '' ?>
									</option>
								<?php endforeach; ?>
							</select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Moneda</label>
                                    <select name="currency_id" class="form-control" required>
                                        <?php foreach($currencies as $curr): ?>
                                            <option value="<?= $curr->id ?>"><?= $curr->display_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tasa de Cambio (TC)</label>
                                    <input type="number" name="exchange_rate" class="form-control" step="0.0001" value="1.0000">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Almacén de Origen</label>
                            <select name="warehouse_id" class="form-control" required>
                                <?php foreach($warehouses as $w): ?>
                                    <option value="<?= $w->id ?>"><?= $w->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Fecha de Operación</label>
                            <input type="date" name="sales_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Notas / Observaciones</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Detalle de Productos</h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="addRow()">
                            <i class="fas fa-plus"></i> Agregar Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Producto (Stock Disponible)</th>
                                        <th width="140">Precio Unit.</th>
                                        <th width="100">Cantidad</th>
                                        <th width="120">Subtotal</th>
                                        <th width="50"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Total General:</th>
                                        <th>
                                            <span id="display_total">0.00</span>
                                            <input type="hidden" name="grand_total" id="grand_total" value="0">
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <a href="<?= site_url('sale') ?>" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-5">Registrar Venta</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// JS 로직은 이전과 동일하되, Subtotal 계산 로직만 추가하여 사용자가 보기 편하게 개선
let rowIdx = 0;
function addRow() {
    const html = `
    <tr id="row_${rowIdx}">
        <td>
            <select name="items[${rowIdx}][item_id]" class="form-control" required>
                <option value="">Seleccione Producto</option>
                <?php foreach($products as $p): ?>
                <option value="<?= $p->id ?>">
                    [<?= $p->brand ?>] <?= $p->name ?> (Stock: <?= $p->available_stock ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="number" name="items[${rowIdx}][unit_price]" class="form-control price" step="0.01" value="0.00" oninput="updateLineTotal(${rowIdx})"></td>
        <td><input type="number" name="items[${rowIdx}][qty]" class="form-control qty" value="1" oninput="updateLineTotal(${rowIdx})"></td>
        <td><span class="line_total">0.00</span></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${rowIdx})"><i class="fas fa-trash"></i></button></td>
    </tr>`;
    document.querySelector('#itemsTable tbody').insertAdjacentHTML('beforeend', html);
    rowIdx++;
}

function updateLineTotal(id) {
    const row = document.getElementById(`row_${id}`);
    const price = parseFloat(row.querySelector('.price').value) || 0;
    const qty = parseInt(row.querySelector('.qty').value) || 0;
    const lineTotal = price * qty;
    row.querySelector('.line_total').innerText = lineTotal.toLocaleString('en-US', {minimumFractionDigits: 2});
    calcTotal();
}

function removeRow(id) {
    document.getElementById(`row_${id}`).remove();
    calcTotal();
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('.line_total').forEach(span => {
        total += parseFloat(span.innerText.replace(/,/g, '')) || 0;
    });
    document.getElementById('display_total').innerText = total.toLocaleString('en-US', {minimumFractionDigits: 2});
    document.getElementById('grand_total').value = total.toFixed(2);
}

// 최초 행 추가
addRow();
</script>