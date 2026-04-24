<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_model extends CI_Model {

    /* --- Funciones para Tabla 'access' (Maestro) --- */

    /**
     * Obtiene la lista de configuraciones de acceso con el nombre del editor
     */
    public function get_access_list() {
        $this->db->select('a.*, u.full_name as updated_user_name');
        $this->db->from('access a');
        $this->db->join('users u', 'a.updated_by = u.id', 'left');
        $this->db->order_by('a.access_name', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Obtiene solo los accesos activos para el formulario de solicitud
     */
    public function get_active_access() {
        $this->db->where('status', 1);
        return $this->db->get('access')->result();
    }

    /**
     * Inserta un nuevo tipo de acceso
     */
    public function insert_access($data) {
        return $this->db->insert('access', $data);
    }

    /**
     * Actualiza un tipo de acceso existente
     */
    public function update_access($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('access', $data);
    }

    /**
     * Obtiene todas las solicitudes con información de joins
     */
    public function get_all_requests() {
        $this->db->select('ar.*, u.full_name as applicant_name, a.access_name, editor.full_name as editor_name');
        $this->db->from('access_requests ar');
        $this->db->join('users u', 'ar.user_id = u.id');
        $this->db->join('access a', 'ar.access_id = a.id');
        $this->db->join('users editor', 'ar.updated_by = editor.id', 'left');
        $this->db->order_by('ar.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Inserta una nueva solicitud de acceso
     */
    public function insert_request($data) {
        return $this->db->insert('access_requests', $data);
    }

    /**
     * Actualiza el estado de una solicitud (Aprobar/Rechazar)
     */
    public function update_request_status($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('access_requests', $data);
    }
	
	/**
	 * Obtiene los IDs de los accesos que el usuario ya tiene aprobados
	 */
	public function get_user_approved_access_ids($user_id) {
		$this->db->select('access_id');
		$this->db->from('access_requests');
		$this->db->where('user_id', $user_id);
		$this->db->where('status', 'APPROVED');
		$query = $this->db->get();
		
		$ids = array();
		foreach ($query->result() as $row) {
			$ids[] = $row->access_id;
		}
		return $ids;
	}

	/**
	 * Obtiene las solicitudes de acceso filtradas por su estado.
	 * Se utiliza 'full_name' según la estructura de la tabla 'users'.
	 */
	public function get_requests_by_status($type = 'PENDING') {
		/* * Seleccionamos los campos necesarios.
		 * Se cambia 'u.name' por 'u.full_name' para coincidir con su DB.
		 */
		$this->db->select('ar.*, u.full_name as applicant_name, a.access_name, ed.full_name as editor_name');
		$this->db->from('access_requests ar');
		
		/* Unir con la tabla de usuarios para obtener el nombre del solicitante */
		$this->db->join('users u', 'ar.user_id = u.id'); 
		
		/* Unir con el maestro de accesos */
		$this->db->join('access a', 'ar.access_id = a.id');
		
		/* Unir con el usuario que procesó la solicitud (Administrador) */
		$this->db->join('users ed', 'ar.updated_by = ed.id', 'left');

		/* Aplicar filtro según el estado de la solicitud */
		if ($type === 'PENDING') {
			$this->db->where('ar.status', 'PENDING');
		} else {
			/* Para el historial, traer registros aprobados o rechazados */
			$this->db->where_in('ar.status', ['APPROVED', 'REJECTED']);
		}

		/* Ordenar por fecha de creación para ver lo más reciente primero */
		$this->db->order_by('ar.created_at', 'DESC');
		
		return $this->db->get()->result();
	}
}