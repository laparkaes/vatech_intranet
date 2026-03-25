<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entity_model extends CI_Model {

    /**
     * Obtiene los detalles de una entidad (Vendor o Distributor)
     */
    public function get_entity_details($id) {
        /* Se actualiza vendor_name por e.name */
        $this->db->select('e.*, c.country_name as country, u.full_name as creator_name');
        $this->db->from('entities e');
        $this->db->join('countries c', 'e.country_id = c.id', 'left');
        $this->db->join('users u', 'e.created_by = u.id', 'left');
        $this->db->where('e.id', $id);
        return $this->db->get()->row();
    }

	/**
	 * Obtiene el listado maestro de todas las entidades registradas.
	 * Se han corregido los nombres de las columnas según la estructura de la base de datos:
	 * - Tabla 'countries': 'country_name'
	 * - Tabla 'users': 'full_name'
	 * * @return array Lista de todas las entidades con su país y el nombre completo del creador.
	 */
	public function get_all_entities() {
		$this->db->select('e.*, c.country_name as country, u.full_name as creator_name');
		$this->db->from('entities e');
		
		// Relación con la tabla de países
		$this->db->join('countries c', 'c.id = e.country_id', 'left');
		
		// Relación con la tabla de usuarios utilizando 'full_name'
		$this->db->join('users u', 'u.id = e.created_by', 'left');
		
		$this->db->order_by('e.name', 'ASC');
		
		$query = $this->db->get();
		return $query->result();
	}

    /**
     * Obtiene la lista de entidades por rol (is_vendor o is_dealer)
     */
    public function get_entities_by_role($role_field) {
        $this->db->select('e.*, c.country_name as country');
        $this->db->from('entities e');
        $this->db->join('countries c', 'e.country_id = c.id', 'left');
        $this->db->where("e.$role_field", 1);
        $this->db->order_by('e.name', 'ASC'); // Cambio a e.name
        return $this->db->get()->result();
    }

    /**
     * Verifica duplicados por país y tax_id
     */
    public function check_duplicate($country_id, $tax_id) {
        $this->db->where('country_id', $country_id);
        $this->db->where('tax_id', $tax_id);
        return ($this->db->get('entities')->num_rows() > 0);
    }

    /**
     * Registro integral de entidad y contactos
     */
    public function register_entity_with_contacts($entity_data, $contacts_batch) {
        $this->db->trans_start();
        $this->db->insert('entities', $entity_data);
        $entity_id = $this->db->insert_id();

        if (!empty($contacts_batch)) {
            foreach ($contacts_batch as $contact) {
                $contact['entity_id'] = $entity_id;
                $this->db->insert('entity_contacts', $contact);
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Actualización de datos maestros
     */
    public function update_entity($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('entities', $data);
    }

    public function get_countries() {
        return $this->db->order_by('country_name', 'ASC')->get('countries')->result();
    }

    /* --- Gestión de Contactos (entity_contacts) --- */

    public function get_entity_contacts($entity_id) {
        $this->db->where('entity_id', $entity_id);
        $this->db->order_by('is_main', 'DESC');
        $this->db->order_by('status', 'DESC');
        return $this->db->get('entity_contacts')->result();
    }

    public function insert_contact($data) {
        return $this->db->insert('entity_contacts', $data);
    }

    public function soft_delete_contact($id) {
        $this->db->where('id', $id);
        return $this->db->update('entity_contacts', array('status' => 0));
    }

    public function set_main_contact($contact_id, $entity_id) {
        $this->db->trans_start();
        $this->db->where('entity_id', $entity_id);
        $this->db->update('entity_contacts', array('is_main' => 0));
        $this->db->where('id', $contact_id);
        $this->db->update('entity_contacts', array('is_main' => 1));
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function check_active_email_exists($email) {
        $this->db->where('email', $email);
        $this->db->where('status', 1);
        return ($this->db->get('entity_contacts')->num_rows() > 0);
    }
}