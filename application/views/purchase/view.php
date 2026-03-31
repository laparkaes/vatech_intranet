<?php
/**
 * Vista de Detalle de Orden de Compra (PO) - Minimal
 */
$current_user_id = $this->session->userdata('user_id');
$user_role = $this->session->userdata('role');

$is_admin = ($user_role === 'admin');
$is_logistics = ($user_role === 'logistics');
$is_creator = ($po->created_by == $current_user_id);
?>

<div>
    <h2>Detalle de Orden de Compra: <?= htmlspecialchars($po->po_number) ?></h2>
    <a href="<?= base_url('purchase') ?>">[ ← Volver a la Lista ]</a>
</div>

<br>

<fieldset>
    <legend>Información General</legend>
    <table border="1">
        <tr>
            <th>Proveedor</th>
            <td><?= htmlspecialchars($po->supplier_name) ?> (<?= htmlspecialchars($po->supplier_tax_id) ?>)</td>
            <th>Estado</th>
            <td><strong><?= strtoupper($po->status_name) ?></strong></td>
        </tr>
        <tr>
            <th>Tipo de Orden</th>
            <td><?= $po->po_type_name ?></td>
            <th>Moneda</th>
            <td><?= $po->currency_name ?></td>
        </tr>
        <tr>
            <th>Incoterms</th>
            <td><?= $po->incoterms_name ? $po->incoterms_name : 'N/A' ?></td>
            <th>Método de Envío</th>
            <td><?= $po->shipping_name ?></td>
        </tr>
        <tr>
            <th>Términos de Pago</th>
            <td><?= $po->payment_name ?></td>
            <th>Fecha de Emisión</th>
            <td><?= $po->issue_date ?></td>
        </tr>
        <tr>
            <th>Fecha Entrega Est.</th>
            <td><?= $po->expected_date ?></td>
            <th>Creado por</th>
            <td><?= htmlspecialchars($po->creator_name) ?></td>
        </tr>
		<tr>
			<th>Almacén de Destino</th>
			<td><?= $po->warehouse_name ?></td>
			<th>Dirección de Entrega</th>
			<td><?= $po->warehouse_address ?></td>
		</tr>
        <tr>
            <th>Notas</th>
            <td colspan="3"><?= nl2br(htmlspecialchars($po->notes)) ?></td>
        </tr>
    </table>
</fieldset>

<br>

<fieldset>
    <legend>Lista de Productos (Ítems)</legend>
    <table border="1">
        <thead>
            <tr>
                <th>N°</th>
                <th>Producto / Descripción</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Subtotal</th>
                <th>Fecha Entrega</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_calc = 0;
            if(!empty($items)):
                foreach($items as $index => $item): 
                    $subtotal = $item->quantity * $item->unit_price;
                    $total_calc += $subtotal;
            ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($item->product_name) ?> - <?= htmlspecialchars($item->product_option) ?></td>
                    <td><?= number_format($item->quantity, 2) ?></td>
                    <td><?= number_format($item->unit_price, 2) ?></td>
                    <td><strong><?= number_format($subtotal, 2) ?></strong></td>
                    <td><?= $item->delivery_date ?></td>
                </tr>
            <?php 
                endforeach; 
            endif;
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">TOTAL ORDEN (<?= $po->currency_name ?>):</th>
                <th><?= number_format($total_calc, 2) ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</fieldset>

<br>

<fieldset>
    <legend>Gestión de la Orden</legend>
    
    <?php if ($po->status_code === 'Registrado'): ?>
        <form method="post" action="<?= base_url('purchase/update_status/'.$po->id) ?>">
            <div>
                <label><strong>Observaciones / Comentarios:</strong></label><br>
                <textarea name="comment" rows="2" placeholder="Ingrese el motivo de su decisión..."></textarea>
            </div>

            <div>
                <?php if ($is_admin || ($is_logistics && !$is_creator)): ?>
                    <button type="submit" name="status_code" value="Aprobado" onclick="return confirm('¿Está seguro de APROBAR esta orden?')">Aprobar Orden</button>
                    <button type="submit" name="status_code" value="Rechazado" onclick="return confirm('¿Está seguro de RECHAZAR esta orden?')">Rechazar Orden</button>
                <?php endif; ?>

                <?php if ($is_creator || $is_admin): ?>
                    <button type="submit" name="status_code" value="Cancelado" onclick="return confirm('¿Está seguro de ANULAR esta orden?')">Anular Orden</button>
                <?php endif; ?>
            </div>
        </form>

    <?php elseif ($po->status_code === 'Rechazado' || $po->status_code === 'Cancelado'): ?>
        <div>
            <p>Estado Actual: <?= $po->status_name ?></p>
            
            <?php if (!empty($po->approver_comment)): ?>
                <div>
                    <strong>Comentario del Revisor:</strong><br>
                    <span><?= nl2br(htmlspecialchars($po->approver_comment)) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($is_creator || $is_admin): ?>
                <div>
                    <a href="<?= base_url('purchase/edit/'.$po->id) ?>">
                        [ Editar y Re-enviar para Aprobación ]
                    </a>
                    <p>* Al editar, la orden volverá al estado "Registrado" para una nueva revisión.</p>
                </div>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <div>
            <p><strong>Estado de la Orden:</strong> <?= strtoupper($po->status_name) ?></p>
            <p>Procesado por: <strong><?= htmlspecialchars($po->approver_name) ?></strong></p>
            <p>Fecha de proceso: <strong><?= $po->approved_at ?></strong></p>
            
            <?php if (!empty($po->approver_comment)): ?>
                <div>
                    <strong>Nota de aprobación:</strong><br>
                    <?= nl2br(htmlspecialchars($po->approver_comment)) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</fieldset>