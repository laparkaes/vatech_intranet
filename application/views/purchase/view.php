<?php
/**
 * Vista de Detalle de Orden de Compra (PO)
 * 로직과 데이터만 남긴 순수 HTML 구조
 */

$status_map = [
    'Borrador'   => 'BORRADOR',
    'Aprobado'   => 'APROBADO',
    'Rechazado'  => 'RECHAZADO',
    'Cancelado'  => 'ANULADO / CANCELADO',
    'Parcial'    => 'RECEPCIÓN PARCIAL',
    'Completado' => 'COMPLETADO'
];

$display_status = isset($status_map[$po->status]) ? $status_map[$po->status] : strtoupper($po->status);

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
            <td><strong><?= $display_status ?></strong></td>
        </tr>
        <tr>
            <th>Tipo de Orden</th>
            <td><?= $po->po_type ?></td>
            <th>Moneda</th>
            <td><?= $po->currency ?> (TC: <?= number_format($po->exchange_rate, 4) ?>)</td>
        </tr>
        <tr>
            <th>Incoterms</th>
            <td><?= $po->incoterms ? $po->incoterms : 'N/A' ?></td>
            <th>Método de Envío</th>
            <td><?= $po->shipping_method ?></td>
        </tr>
        <tr>
            <th>Términos de Pago</th>
            <td><?= $po->payment_terms ?></td>
            <th>Fecha de Emisión</th>
            <td><?= $po->issue_date ?></td>
        </tr>
        <tr>
            <th>Fecha Entrega Est.</th>
            <td><?= $po->expected_date ?></td>
            <th>Creado por</th>
            <td><?= htmlspecialchars($po->creator_name) ?></td>
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
                <th>Precio Unit. (<?= $po->currency ?>)</th>
                <th>Subtotal (<?= $po->currency ?>)</th>
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
                    <td><?= number_format($subtotal, 2) ?></td>
                    <td><?= $item->delivery_date ?></td>
                </tr>
            <?php 
                endforeach; 
            endif;
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">TOTAL ORDEN:</th>
                <th><?= number_format($total_calc, 2) ?></th>
                <th><?= $po->currency ?></th>
            </tr>
        </tfoot>
    </table>
</fieldset>

<br>

<fieldset>
    <legend>Gestión de la Orden</legend>
    
    <?php if ($po->status === 'Borrador'): ?>
        <table>
            <tr>
                <?php if ($is_admin || ($is_logistics && !$is_creator)): ?>
                    <td>
                        <form method="post" action="<?= base_url('purchase/update_status/'.$po->id) ?>">
                            <input type="hidden" name="status" value="Aprobado">
                            <button type="submit" onclick="return confirm('¿Confirmar APROBACIÓN?')">Aprobar Orden</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="<?= base_url('purchase/update_status/'.$po->id) ?>">
                            <input type="hidden" name="status" value="Rechazado">
                            <button type="submit" onclick="return confirm('¿Confirmar RECHAZO?')">Rechazar Orden</button>
                        </form>
                    </td>
                <?php endif; ?>

                <?php if ($is_creator || $is_admin): ?>
                    <td>
                        <form method="post" action="<?= base_url('purchase/cancel/'.$po->id) ?>">
                            <button type="submit" onclick="return confirm('¿Confirmar ANULACIÓN?')">Anular Orden (Cancelado)</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        </table>
    <?php else: ?>
        <p>Estado actual: <strong><?= $display_status ?></strong></p>
        <?php if (!empty($po->approved_at)): ?>
            <p>Procesado el: <?= $po->approved_at ?> por <?= htmlspecialchars($po->approver_name) ?></p>
        <?php endif; ?>
    <?php endif; ?>
</fieldset>