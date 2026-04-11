<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Division_model extends CI_Model {

    /**
     * Obtiene todas las divisiones (para el administrador)
     */
    public function get_all_divisions() {
		$this->db->select('d1.*, d2.division_name as parent_name');
		$this->db->from('divisions d1');
		$this->db->join('divisions d2', 'd1.parent_id = d2.id', 'left'); // 자기 참조 조인
		$this->db->order_by('d1.id', 'ASC');
		return $this->db->get()->result();
	}

    /**
     * Obtiene solo las divisiones activas (para los select de usuarios)
     */
    public function get_active_divisions() {
        $this->db->where('status', 1);
        return $this->db->get('divisions')->result();
    }

    public function insert_division($data) {
        return $this->db->insert('divisions', $data);
    }

    public function update_division($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('divisions', $data);
    }
}