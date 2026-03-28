<div>
    <h2>Registrar Nuevo Almacén</h2>
    <a href="<?= base_url('warehouse') ?>">[ ← Volver al Listado ]</a>
</div>

<br>

<form action="<?= base_url('warehouse/add') ?>" method="post">
    <table border="1">
        <tr>
            <th width="200">Nombre del Almacén</th>
            <td>
                <input type="text" name="name" required placeholder="Nombre único del almacén" size="40">
            </td>
        </tr>
        <tr>
            <th>Administrado por</th>
            <td>
                <select name="contractor_entity_id">
                    <option value="">VPR (Propio)</option>
                    <?php foreach($entities as $entity): ?>
                        <option value="<?= $entity->id ?>"><?= $entity->name ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Dirección</th>
            <td>
                <input type="text" name="address" size="60" placeholder="Dirección completa en Lima/Perú">
            </td>
        </tr>
        <tr>
            <th>Información de Ubicación</th>
            <td>
                <textarea name="location_info" cols="60" rows="5" placeholder="Ej: Link de Google Maps, edificios cercanos, centros comerciales, o referencias específicas para el transportista."></textarea>
                <br>
                <small>* Ingrese links de mapas o edificios de referencia cercanos.</small>
            </td>
        </tr>
    </table>

    <br>

    <div>
        <button type="submit">Registrar Almacén</button>
        <button type="reset">Limpiar Datos</button>
    </div>
</form>