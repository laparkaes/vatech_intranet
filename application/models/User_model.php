<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo encargado de la gestión de datos de usuarios (VPR ERP)
 */
class User_model extends CI_Model {

    /**
     * Busca un usuario activo por su dirección de correo electrónico
     * @param string $email
     * @return object|null
     */
    public function get_user_by_email($email) {
        // Se utiliza Query Builder para interactuar con la tabla 'users'
        $query = $this->db->get_where('users', [
            'email' => $email, 
            'status' => 1
        ]);
        return $query->row();
    }
	
	/**
	 * Obtiene la lista de todos los usuarios registrados en el sistema.
	 * Se utiliza principalmente en el panel de administración de cuentas.
	 */
	public function get_all_users() {
		// Seleccionar todos los registros de la tabla 'users'
		$query = $this->db->get('users');
		
		// Retornar el resultado como un array de objetos
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
}