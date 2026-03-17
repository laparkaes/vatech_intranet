<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_model extends CI_Model {

    // Guardar nueva solicitud
    public function insert_request($data) {
        return $this->db->insert('access_requests', $data);
    }

	/**
	 * Obtiene todas las solicitudes incluyendo el nombre del administrador que procesó
	 */
	public function get_all_requests() {
		$this->db->select('
			ar.*, 
			u_req.full_name as user_name, 
			u_req.email as user_email,
			u_admin.full_name as admin_name
		');
		$this->db->from('access_requests ar');
		$this->db->join('users u_req', 'u_req.id = ar.user_id'); // Usuario que solicita
		$this->db->join('users u_admin', 'u_admin.id = ar.processed_by_id', 'left'); // Admin que procesa
		$this->db->order_by('ar.created_at', 'DESC');
		
		return $this->db->get()->result();
	}
	
	/**
	 * Actualiza el estado de una solicitud de acceso
	 * @param int $id
	 * @param array $data
	 * @return bool
	 */
	public function update_request($id, $data) {
		$this->db->where('id', $id);
		return $this->db->update('access_requests', $data);
	}
}