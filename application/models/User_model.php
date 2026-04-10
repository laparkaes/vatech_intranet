<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo encargado de la gestión de datos de usuarios (VPR ERP)
 */
class User_model extends CI_Model {
	
	public function get_user_by_id($id) {
		$this->db->where('id', $id);
		return $this->db->get('users')->row();
	}
	
    /**
     * Busca un usuario activo por su dirección de correo electrónico
     * @param string $email
     * @return object|null
     */
    public function get_user_by_email($email) {
		$this->db->select('u.*, d.division_name');
		$this->db->from('users u');
		$this->db->join('divisions d', 'u.division_id = d.id', 'left'); // 부서가 없을 경우를 대비해 left join
		$this->db->where('u.email', $email);
		$this->db->where('u.status', 1);
		
		$query = $this->db->get();
		return $query->row();
	}
	
	/**
	 * Obtiene todos los usuarios con el nombre de su división correspondiente.
	 */
	public function get_all_users() {
		$this->db->select('u.*, d.division_name');
		$this->db->from('users u');
		// Unir con la tabla de divisiones (LEFT JOIN para no excluir usuarios sin división)
		$this->db->join('divisions d', 'u.division_id = d.id', 'left');
		$this->db->order_by('u.created_at', 'DESC');
		
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Actualiza los datos de un usuario específico.
	 * Se usa para cambiar el ROL o el ESTADO (status).
	 */
	public function update_user($user_id, $data) {
		$this->db->where('id', $user_id);
		return $this->db->update('users', $data);
	}
	
	/**
     * Inserta un nuevo registro de usuario
     * @param array $data
     * @return bool
     */
    public function insert_user($data) {
        // Insertar datos en la tabla 'users'
        return $this->db->insert('users', $data);
    }
	
	/**
	 * Actualiza la contraseña de un usuario específico
	 * @param string $email
	 * @param string $new_password_hashed
	 * @return bool
	 */
	public function update_password($email, $new_password_hashed) {
		$this->db->set('password', $new_password_hashed);
		$this->db->where('email', $email);
		return $this->db->update('users');
	}
	
	/**
	 * Verifica si un correo electrónico ya está registrado en la base de datos
	 * @param string $email
	 * @return bool
	 */
	public function check_email_exists($email) {
		// Buscar cualquier registro con el mismo email, sin importar el estado
		$query = $this->db->get_where('users', array('email' => $email));
		
		if ($query->num_rows() > 0) {
			return TRUE; // El correo ya existe
		} else {
			return FALSE; // El correo está disponible
		}
	}
	
	/**
	 * Actualiza la fecha y hora del último inicio de sesión del usuario.
	 */
	public function update_last_login($user_id) {
		$this->db->where('id', $user_id);
		return $this->db->update('users', array(
			'last_login' => date('Y-m-d H:i:s')
		));
	}
	
	/**
	 * Calcula la antigüedad del empleado en formato "XY ZM"
	 * @param string $hire_date
	 * @return string
	 */
	public function calculate_tenure($hire_date) {
		if (!$hire_date) return "N/A";

		$today = new DateTime();
		$start_date = new DateTime($hire_date);
		$interval = $start_date->diff($today);

		$years = $interval->y;
		$months = $interval->m;

		return "{$years}Y {$months}M";
	}
	
	/**
	 * 현재 활성화된(status=1) 사용자 중 관리자 권한을 가진 계정이 있는지 확인합니다.
	 * @return bool
	 */
	public function has_admin_exists() {
		$this->db->where('role', 'admin');
		$this->db->where('status', 1);
		$query = $this->db->get('users');
		
		return ($query->num_rows() > 0);
	}
	
	/**
	 * 검색 조건이 포함된 페이징 사용자 목록 가져오기
	 */
	public function get_users_paged($limit, $start, $search = []) {
		$this->db->select('u.*, d.division_name');
		$this->db->from('users u');
		$this->db->join('divisions d', 'u.division_id = d.id', 'left');

		// 검색 조건 동적 추가
		if (!empty($search['name'])) $this->db->like('u.full_name', $search['name']);
		if (!empty($search['email'])) $this->db->like('u.email', $search['email']);
		if (!empty($search['division'])) $this->db->where('u.division_id', $search['division']);
		if (!empty($search['role'])) $this->db->where('u.role', $search['role']);
		if (isset($search['status']) && $search['status'] !== '') $this->db->where('u.status', $search['status']);

		$this->db->limit($limit, $start);
		$this->db->order_by('u.created_at', 'DESC');
		
		return $this->db->get()->result();
	}

	/**
	 * 검색 조건이 포함된 전체 사용자 수 카운트
	 */
	public function count_all_users($search = []) {
		$this->db->from('users u');
		if (!empty($search['name'])) $this->db->like('u.full_name', $search['name']);
		if (!empty($search['email'])) $this->db->like('u.email', $search['email']);
		if (!empty($search['division'])) $this->db->where('u.division_id', $search['division']);
		if (!empty($search['role'])) $this->db->where('u.role', $search['role']);
		if (isset($search['status']) && $search['status'] !== '') $this->db->where('u.status', $search['status']);
		
		return $this->db->count_all_results();
	}
}