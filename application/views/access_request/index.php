<h2>Solicitudes de Acceso</h2>

<hr>

<h3>Solicitudes Pendientes (Por Procesar)</h3>
<table border="1">
    <thead>
        <tr>
            <th>No.</th>
            <th>Solicitante</th>
            <th>Acceso Solicitado</th>
            <th>Motivo</th>
            <th>Fecha Solicitud</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($pending_requests)): ?>
            <?php $count_p = 1; foreach($pending_requests as $req): ?>
            <tr>
                <td><?php echo $count_p++; ?></td>
                <td><?php echo $req->applicant_name; ?></td>
                <td><strong><?php echo $req->access_name; ?></strong></td>
                <td><?php echo $req->reason; ?></td>
                <td><?php echo $req->created_at; ?></td>
                <td>
                    <form action="<?php echo base_url('access/update_request_status'); ?>" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $req->id; ?>">
                        <input type="hidden" name="status" value="APPROVED">
                        <button type="submit" onclick="return confirm('¿Aprobar esta solicitud?')">Aprobar</button>
                    </form>

                    <form action="<?php echo base_url('access/update_request_status'); ?>" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $req->id; ?>">
                        <input type="hidden" name="status" value="REJECTED">
                        <button type="submit" onclick="return confirm('¿Rechazar esta solicitud?')">Rechazar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" align="center">No hay solicitudes pendientes.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<br><br>

<h3>Historial de Solicitudes (Completados)</h3>
<table border="1">
    <thead>
        <tr>
            <th>No.</th>
            <th>Solicitante</th>
            <th>Acceso</th>
            <th>Estado</th>
            <th>Fecha Solicitud</th>
            <th>Procesado por</th>
            <th>Fecha Proceso</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($completed_requests)): ?>
            <?php $count_c = 1; foreach($completed_requests as $req): ?>
            <tr>
                <td><?php echo $count_c++; ?></td>
                <td><?php echo $req->applicant_name; ?></td>
                <td><?php echo $req->access_name; ?></td>
                <td>
                    <strong>
                        <?php echo ($req->status === 'APPROVED') ? 'APROBADO' : 'RECHAZADO'; ?>
                    </strong>
                </td>
                <td><?php echo $req->created_at; ?></td>
                <td><?php echo $req->editor_name ? $req->editor_name : '-'; ?></td>
                <td><?php echo $req->updated_at ? $req->updated_at : '-'; ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" align="center">No hay historial de solicitudes.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>