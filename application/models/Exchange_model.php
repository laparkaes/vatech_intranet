<?php
class Exchange_model extends CI_Model {

    /* Obtener historial de tasas ordenadas por fecha */
    public function get_all_rates() {
		$this->db->select('er.*, u.full_name as user_name');
		$this->db->from('exchange_rates er');
		$this->db->join('users u', 'er.created_by = u.id', 'left');
		$this->db->order_by('er.effective_date', 'DESC');
		return $this->db->get()->result();
	}

    /* Insertar nueva tasa */
    public function insert_rate($data) {
        return $this->db->insert('exchange_rates', $data);
    }

    /**
	 * Obtiene la tasa más reciente filtrando por la moneda base.
	 * Explicación: Se cambió 'currency_code' por 'base_currency' para coincidir con la estructura de la tabla.
	 */
	public function get_latest_rate($base = 'USD') {
		// Se ajusta el nombre de la columna según el error 1054
		$this->db->where('base_currency', $base); 
		$this->db->order_by('effective_date', 'DESC');
		$this->db->limit(1);
		
		$query = $this->db->get('exchange_rates');
		return $query->row();
	}

	/* Obtener una tasa especifica por ID */
	public function get_rate_by_id($id) {
		return $this->db->get_where('exchange_rates', array('id' => $id))->row();
	}

	/* Actualizar datos de la tasa */
	public function update_rate($id, $data) {
		$this->db->where('id', $id);
		return $this->db->update('exchange_rates', $data);
	}
	
	/* Verificar si ya existe un registro para la misma moneda y fecha */
	public function check_duplicate($base, $target, $date, $exclude_id = null) {
		$this->db->where('base_currency', $base);
		$this->db->where('target_currency', $target);
		$this->db->where('effective_date', $date);
		
		// Si es una edicion, excluir el ID actual de la verificacion
		if ($exclude_id !== null) {
			$this->db->where('id !=', $exclude_id);
		}
		
		$query = $this->db->get('exchange_rates');
		return $query->num_rows() > 0;
	}
}