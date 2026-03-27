<div>
    <h1>Editar Tipo de Cambio</h1>
    <a href="<?php echo base_url('exchange'); ?>">Cancelar y Volver</a>

    <br><br>

    <form method="post" action="<?php echo base_url('exchange/update'); ?>">
        <input type="hidden" name="id" value="<?php echo $rate_item->id; ?>">
        
        <table border="1">
            <tr>
                <td>1 Unidad de (Base)</td>
                <td>
                    <select name="base_currency" required>
                        <option value="USD" <?php echo ($rate_item->base_currency == 'USD') ? 'selected' : ''; ?>>USD - Dólar</option>
                        <option value="PEN" <?php echo ($rate_item->base_currency == 'PEN') ? 'selected' : ''; ?>>PEN - Sol</option>
                    </select>
                </td>
                <td>Equivale a (Target)</td>
                <td>
                    <input type="number" name="rate" step="0.0001" value="<?php echo $rate_item->rate; ?>" lang="en" required>
                </td>
                <td>
                    <select name="target_currency" required>
                        <option value="PEN" <?php echo ($rate_item->target_currency == 'PEN') ? 'selected' : ''; ?>>PEN - Sol</option>
                        <option value="USD" <?php echo ($rate_item->target_currency == 'USD') ? 'selected' : ''; ?>>USD - Dólar</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Fecha Efectiva</td>
                <td colspan="4">
                    <input type="date" name="effective_date" value="<?php echo $rate_item->effective_date; ?>" required>
                    <button type="submit">Actualizar Tasa (Override Autor)</button>
                </td>
            </tr>
        </table>
    </form>
    <p>* Al actualizar, su usuario quedará registrado como el último responsable de esta tasa.</p>
</div>