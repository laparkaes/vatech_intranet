<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('session');
        $this->load->helper('url');

        // Verificar autenticación básica
        if (!$this->session->userdata('is_logged_in')) {
            redirect('auth');
        }

        // Restricción: Solo el rol 'admin' puede acceder a este controlador
        if ($this->session->userdata('role') !== 'admin') {
            // Establecer el mensaje de error en español
            $this->session->set_flashdata('error', 'Acceso denegado: No cuenta con los privilegios de administrador necesarios.');
            redirect('dashboard');
        }
    }

    /**
     * Lista todos los usuarios registrados en el sistema
     */
    public function index() {
        $data['users'] = $this->user_model->get_all_users();
        $data['main'] = 'accounts/user_list';
        $this->load->view('layout', $data);
    }

	/**
	 * Cambia el rol de un usuario específico.
	 * Solo los administradores pueden realizar esta acción y no pueden cambiarse su propio rol.
	 */
	public function change_role() {
		// 1. Verificación de seguridad: solo ADMIN puede ejecutar este método
		// (Aunque el constructor ya lo valida, es una buena práctica de seguridad)
		if ($this->session->userdata('role') !== 'admin') {
			$this->session->set_flashdata('error', 'No tiene permisos para realizar esta operación.');
			redirect('dashboard');
			return;
		}

		$user_id = $this->input->post('user_id');
		$new_role = $this->input->post('new_role');
		$current_admin_id = $this->session->userdata('user_id');

		// 2. Restricción: No permitir que el admin se cambie su propio rol
		if ($user_id == $current_admin_id) {
			$this->session->set_flashdata('error', 'No puede cambiar su propio rol por razones de seguridad.');
			redirect('accounts');
			return;
		}

		// 3. Proceder con la actualización si supera las validaciones
		$data = array('role' => $new_role);
		
		if ($this->user_model->update_user($user_id, $data)) {
			$this->session->set_flashdata('success', 'Rol de usuario actualizado correctamente.');
		} else {
			$this->session->set_flashdata('error', 'Error al intentar actualizar el rol.');
		}

		redirect('accounts');
	}
	
	/**
	 * Alterna el estado activo/inactivo del usuario.
	 * Restringe la posibilidad de que un administrador se desactive a sí mismo.
	 */
	public function toggle_status() {
		// 1. Verificación de seguridad básica (Solo administradores)
		if ($this->session->userdata('role') !== 'admin') {
			$this->session->set_flashdata('error', 'Acceso denegado.');
			redirect('dashboard');
			return;
		}

		$user_id = $this->input->post('user_id');
		$current_status = $this->input->post('current_status');
		$current_admin_id = $this->session->userdata('user_id');

		// 2. Restricción: No permitir que el admin desactive su propia cuenta
		if ($user_id == $current_admin_id) {
			$this->session->set_flashdata('error', 'No puede desactivar su propia cuenta por seguridad del sistema.');
			redirect('accounts');
			return;
		}

		// 3. Proceder con el cambio de estado (1 a 0 / 0 a 1)
		$new_status = ($current_status == 1) ? 0 : 1;

		if ($this->user_model->update_user($user_id, array('status' => $new_status))) {
			$this->session->set_flashdata('success', 'Estado del usuario actualizado correctamente.');
		} else {
			$this->session->set_flashdata('error', 'Error al intentar actualizar el estado del usuario.');
		}

		redirect('accounts');
	}
	
}