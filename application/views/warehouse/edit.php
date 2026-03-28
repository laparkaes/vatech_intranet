<div>
    <h2>Editar Almacén: <?= $warehouse->name ?></h2>
    <a href="<?= base_url('warehouse/view/'.$warehouse->id) ?>">[ ← Cancelar y Volver al Detalle ]</a>
</div>

<br>

<form action="<?= base_url('warehouse/update/'.$warehouse->id) ?>" method="post">
    <table border="1">
        <tr>
            <th width="200">Nombre del Almacén</th>
            <td>
                <input type="text" name="name" value="<?= htmlspecialchars($warehouse->name) ?>" required size="40">
            </td>
        </tr>
        <tr>
            <th>Administrado por</th>
            <td>
                <select name="contractor_entity_id">
                    <option value="">VPR (Propio)</option>
                    <?php foreach($entities as $entity): ?>
                        <option value="<?= $entity->id ?>" <?= ($entity->id == $warehouse->contractor_entity_id) ? 'selected' : '' ?>>
                            <?= $entity->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Dirección</th>
            <td>
                <input type="text" name="address" value="<?= htmlspecialchars($warehouse->address) ?>" size="60">
            </td>
        </tr>
        <tr>
            <th>Información de Ubicación</th>
            <td>
                <textarea name="location_info" cols="60" rows="5" placeholder="Ej: Link de Google Maps, edificios cercanos, centros comerciales, o referencias específicas."><?= htmlspecialchars($warehouse->location_info) ?></textarea>
                <br>
                <small>* Ingrese links de mapas o edificios de referencia cercanos.</small>
            </td>
        </tr>
        <tr>
            <th>Estado</th>
            <td>
                <select name="is_active">
                    <option value="1" <?= ($warehouse->is_active == 1) ? 'selected' : '' ?>>Activo</option>
                    <option value="0" <?= ($warehouse->is_active == 0) ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </td>
        </tr>
    </table>

    <br>

    <div>
        <button type="submit">Actualizar Información</button>
    </div>
</form>