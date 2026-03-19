<?php
class Vendor_model extends CI_Model {

    /**
	 * Obtiene la lista completa de proveedores con el nombre de su país (JOIN).
	 * @return array Lista de proveedores.
	 */
	public function get_all_vendors() {
		$this->db->select('v.*, c.country_name as country');
		$this->db->from('vendors v');
		
		/* Unión corregida por ID para evitar errores 1054 */
		$this->db->join('countries c', 'v.country_id = c.id', 'left');
		
		$this->db->order_by('v.vendor_name', 'ASC');
		return $this->db->get()->result();
	}

    /**
	 * Verifica si ya existe un proveedor registrado con el mismo país y Tax ID.
	 * @param int $country_id ID del país.
	 * @param string $tax_id RUC o Identificación fiscal.
	 * @return boolean True si existe duplicado, False si no.
	 */
	public function check_duplicate($country_id, $tax_id) {
		/* Ajuste de columna: 'country' -> 'country_id' */
		$this->db->where('country_id', $country_id);
		$this->db->where('tax_id', $tax_id);
		$query = $this->db->get('vendors');

		return ($query->num_rows() > 0);
	}

	/**
	 * Verifica si alguno de los correos del array ya existe en contactos activos.
	 * @param array $emails Lista de correos del formulario.
	 * @return string|boolean Retorna el email duplicado o false.
	 */
	public function check_email_duplicates($emails) {
		if (empty($emails)) return false;

		foreach ($emails as $email) {
			$this->db->where('email', $email);
			$this->db->where('status', 1); // Solo validamos contra contactos activos
			$query = $this->db->get('vendor_contacts');

			if ($query->num_rows() > 0) {
				return $email; // Retorna el primer email que cause conflicto
			}
		}
		return false;
	}

    /* Registrar proveedor y contacto inicial en una sola transacción */
    public function register_vendor_with_contact($vendor_data, $contact_data) {
        $this->db->trans_start();

        $this->db->insert('vendors', $vendor_data);
        $vendor_id = $this->db->insert_id();

        $contact_data['vendor_id'] = $vendor_id;
        $this->db->insert('vendor_contacts', $contact_data);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
	
	/**
	 * Registra un vendor y sus contactos en una sola transacción.
	 * @param array $vendor_data Datos para la tabla 'vendors'.
	 * @param array $contacts_batch Lista de contactos para 'vendor_contacts'.
	 * @return boolean Resultado de la operación.
	 */
	public function register_vendor_with_multiple_contacts($vendor_data, $contacts_batch) {
		/* Iniciar transacción SQL */
		$this->db->trans_start();

		/* 1. Insertar el maestro del proveedor (Table: vendors) */
		$this->db->insert('vendors', $vendor_data);
		
		/* Recuperar el ID generado para el nuevo vendor */
		$vendor_id = $this->db->insert_id();

		/* 2. Insertar los contactos vinculados (Table: vendor_contacts) */
		if (!empty($contacts_batch)) {
			foreach ($contacts_batch as $contact) {
				$contact['vendor_id'] = $vendor_id; // Asignar el ID del vendor recién creado
				$this->db->insert('vendor_contacts', $contact);
			}
		}

		/* Finalizar transacción (Commit si todo va bien, Rollback si hay error) */
		$this->db->trans_complete();

		return $this->db->trans_status();
	}
	
	/**
	 * Obtiene la lista de todos los países activos para el select.
	 */
	public function get_countries() {
		$this->db->where('status', 1);
		$this->db->order_by('country_name', 'ASC');
		return $this->db->get('countries')->result();
	}
	
	/**
	 * Obtiene los detalles de un vendor incluyendo el país y el nombre completo del creador.
	 * @param int $id ID del proveedor.
	 */
	public function get_vendor_details($id) {
		/* Usamos 'u.full_name' según la estructura de la tabla 'users' */
		$this->db->select('v.*, c.country_name as country, u.full_name as creator_name');
		$this->db->from('vendors v');
		$this->db->join('countries c', 'v.country_id = c.id', 'left');
		
		/* Vinculación con la tabla de usuarios de VPR */
		$this->db->join('users u', 'v.created_by = u.id', 'left');
		$this->db->where('v.id', $id);
		
		return $this->db->get()->row();
	}

	/**
	 * Obtiene contactos ACTIVOS de un vendor.
	 */
	public function get_vendor_contacts($vendor_id) {
		$this->db->where('vendor_id', $vendor_id);
		$this->db->where('status', 1); // Solo los que no han sido "eliminados"
		$this->db->order_by('is_main', 'DESC');
		return $this->db->get('vendor_contacts')->result();
	}

	/**
	 * Realiza un Soft Delete cambiando el estado a 0.
	 */
	public function soft_delete_contact($id) {
		$this->db->where('id', $id);
		/* No eliminamos el registro, solo lo desactivamos */
		return $this->db->update('vendor_contacts', array('status' => 0));
	}
	
	/**
	 * Inserta un nuevo registro en vendor_contacts.
	 */
	public function insert_contact($data) {
		return $this->db->insert('vendor_contacts', $data);
	}

	/**
	 * Elimina un contacto específico por su ID.
	 */
	public function delete_contact($id) {
		$this->db->where('id', $id);
		return $this->db->delete('vendor_contacts');
	}
	
	/**
	 * Verifica si un correo electrónico ya está en uso por un contacto ACTIVO.
	 * @param string $email Correo a consultar.
	 * @return boolean True si el correo ya está ocupado por alguien activo.
	 */
	public function check_active_email_exists($email) {
		/* Solo buscamos entre los contactos que no han sido borrados lógicamente */
		$this->db->where('email', $email);
		$this->db->where('status', 1); 
		$query = $this->db->get('vendor_contacts');
		
		return ($query->num_rows() > 0);
	}
	
	/**
	 * Actualiza los registros de un vendor específico.
	 * @param int $id ID del vendor.
	 * @param array $data Datos a actualizar.
	 */
	public function update_vendor($id, $data) {
		$this->db->where('id', $id);
		return $this->db->update('vendors', $data);
	}

}