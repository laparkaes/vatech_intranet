<div>
    <div>
        <h1>Gestión de Tipos de Cambio (Pares de Divisas)</h1>
    </div>

    <br>

    <div>
        <h3>Registrar Nueva Tasa</h3>
        <form method="post" action="<?php echo base_url('exchange/add'); ?>">
            <table border="1">
                <tr>
                    <td>1 Unidad de (Base)</td>
                    <td>
                        <select name="base_currency" required>
                            <option value="USD">USD - Dólar</option>
                            <option value="PEN">PEN - Sol</option>
                        </select>
                    </td>
                    <td>Equivale a (Target)</td>
                    <td>
                        <input type="number" name="rate" step="0.0001" placeholder="Ej: 3.7550" required>
                    </td>
                    <td>
                        <select name="target_currency" required>
                            <option value="PEN">PEN - Sol</option>
                            <option value="USD">USD - Dólar</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Fecha Efectiva</td>
                    <td colspan="4">
                        <input type="date" name="effective_date" value="<?php echo date('Y-m-d'); ?>" required>
                        <button type="submit">Guardar Tasa</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <br>

    <div>
		<h3>Historial de Tasas</h3>
		<table border="1">
			<thead>
				<tr>
					<th>ID</th>
					<th>Par de Divisas</th>
					<th>Tasa (Rate)</th>
					<th>Fecha Efectiva</th>
					<th>Último Responsable</th> <th>Fecha Registro</th>
					<th>Acciones</th> </tr>
			</thead>
			<tbody>
				<?php foreach($rates as $r): ?>
					<tr>
						<td><?php echo $r->id; ?></td>
						<td>1 <?php echo $r->base_currency; ?> = ? <?php echo $r->target_currency; ?></td>
						<td><strong><?php echo number_format($r->rate, 4); ?></strong></td>
						<td><?php echo $r->effective_date; ?></td>
						<td><?php echo $r->user_name; ?></td>
						<td><?php echo $r->created_at; ?></td>
						<td>
							<a href="<?php echo base_url('exchange/edit/'.$r->id); ?>">Editar</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>