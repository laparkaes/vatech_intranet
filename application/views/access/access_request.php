<h2>Nueva Solicitud de Acceso</h2>

<p>Seleccione todos los tipos de acceso que requiere y proporcione un motivo para su evaluación.</p>

<form action="<?php echo base_url('access/submit_request'); ?>" method="post">
    <table border="1">
        <thead>
            <tr>
                <th>Seleccionar</th>
                <th>Nombre del Acceso</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($available_access)): ?>
                <?php foreach($available_access as $access): ?>
                <tr>
                    <td align="center">
                        <?php if(in_array($access->id, $approved_access_ids)): ?>
                            <input type="checkbox" disabled checked>
                        <?php else: ?>
                            <input type="checkbox" name="access_ids[]" value="<?php echo $access->id; ?>">
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo $access->access_name; ?></strong>
                        <?php if(in_array($access->id, $approved_access_ids)): ?>
                            <span>(Ya aprobado)</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $access->description; ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" align="center">No hay accesos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>

    <table border="1">
        <tr>
            <th>Motivo de la Solicitud</th>
        </tr>
        <tr>
            <td>
                <textarea name="reason" rows="5" cols="60" placeholder="Indique la razón por la cual solicita estos permisos..." required></textarea>
            </td>
        </tr>
    </table>

    <br>
    
    <button type="submit">Enviar Solicitudes</button>
</form>

<br>

<a href="<?php echo base_url('dashboard'); ?>">Volver al Dashboard</a>