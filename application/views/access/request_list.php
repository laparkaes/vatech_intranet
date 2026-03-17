<h2>Gestión de Accesos al Sistema</h2>
<p>Administre las solicitudes de permisos de los usuarios para los distintos módulos de VPR.</p>

<?php if($this->session->flashdata('success')): ?>
    <p><strong>Éxito:</strong> <?php echo $this->session->flashdata('success'); ?></p>
<?php endif; ?>

<h3>Solicitudes Pendientes</h3>

<?php if(empty($pending_requests)): ?>
    <p>No existen solicitudes pendientes por procesar.</p>
<?php else: ?>
    <table border="1">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Módulo</th>
                <th>Justificación del Usuario</th>
                <th>Fecha de Solicitud</th>
                <th>Acción del Administrador</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pending_requests as $req): ?>
            <tr>
                <td><?php echo $req->user_name; ?></td>
                <td><?php echo $req->user_email; ?></td>
                <td><?php echo strtoupper($req->module_name); ?></td>
                <td><?php echo $req->reason; ?></td>
                <td><?php echo $req->created_at; ?></td>
                <td>
                    <form action="<?php echo base_url('access/update_status'); ?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $req->id; ?>">
                        <input type="text" name="admin_comment" placeholder="Comentario opcional...">
                        
                        <button type="submit" name="status" value="APPROVED">Aprobar</button>
                        <button type="submit" name="status" value="REJECTED">Rechazar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<hr>

<h3>Historial de Solicitudes Procesadas</h3>

<?php if(empty($processed_requests)): ?>
    <p>No hay registros en el historial de solicitudes.</p>
<?php else: ?>
    <table border="1">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Módulo</th>
                <th>Estado</th>
                <th>Procesado por</th>
                <th>Comentario Admin</th>
                <th>Fecha de Proceso</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($processed_requests as $req): ?>
            <tr>
                <td><?php echo $req->user_name; ?></td>
                <td><?php echo strtoupper($req->module_name); ?></td>
                <td>
                    <strong><?php echo $req->status; ?></strong>
                </td>
                <td><?php echo $req->admin_name; ?></td>
                <td><?php echo $req->admin_comment; ?></td>
                <td><?php echo $req->updated_at; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>