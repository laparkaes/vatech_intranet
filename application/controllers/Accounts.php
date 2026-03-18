<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends MY_Controller {

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
		$users = $this->user_model->get_all_users();
		
		// Calcular la antigüedad para cada usuario antes de enviar a la vista
		foreach ($users as $user) {
			$user->tenure = $this->user_model->calculate_tenure($user->hire_date);
		}

		$data['users'] = $users;
		$this->load->model('division_model');
		$data['divisions'] = $this->division_model->get_active_divisions();
		
		$data['main'] = 'accounts/index';
		$this->load->view('layout', $data);
	}
	
	/**
	 * Muestra el formulario de edición para un usuario específico.
	 */
	public function edit($id) {
		if ($this->session->userdata('role') !== 'admin') redirect('dashboard');

		$this->load->model('division_model');
		
		$data['user'] = $this->user_model->get_user_by_id($id); // Método a crear en el modelo
		$data['divisions'] = $this->division_model->get_active_divisions();
		
		$data['main'] = 'accounts/edit';
		$this->load->view('layout', $data);
	}

	/**
	 * Procesa la actualización (Con Fecha de Ingreso)
	 */
	public function update() {
		if ($this->session->userdata('role') !== 'admin') redirect('dashboard');

		$user_id = $this->input->post('user_id');
		$data = array(
			'full_name'   => $this->input->post('full_name'),
			'division_id' => $this->input->post('division_id') ? $this->input->post('division_id') : NULL,
			'hire_date'   => $this->input->post('hire_date'), // Nuevo campo
			'role'        => $this->input->post('role'),
			'status'      => $this->input->post('status')
		);

		// ... (비밀번호 업데이트 로직은 기존과 동일) ...

		$this->user_model->update_user($user_id, $data);
		redirect('accounts');
	}
	
	/**
	 * Muestra el formulario para registrar un nuevo usuario.
	 */
	public function create() {
		if ($this->session->userdata('role') !== 'admin') redirect('dashboard');

		$this->load->model('division_model');
		$data['divisions'] = $this->division_model->get_active_divisions();
		
		$data['main'] = 'accounts/create'; // Nueva vista
		$this->load->view('layout', $data);
	}

	/**
	 * Procesa la inserción del nuevo usuario (Con Fecha de Ingreso)
	 */
	public function add() {
		if ($this->session->userdata('role') !== 'admin') redirect('dashboard');

		$data = array(
			'full_name'   => $this->input->post('full_name'),
			'email'       => $this->input->post('email'),
			'password'    => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
			'division_id' => $this->input->post('division_id') ? $this->input->post('division_id') : NULL,
			'hire_date'   => $this->input->post('hire_date'), // Nuevo campo
			'role'        => $this->input->post('role'),
			'status'      => 1
		);

		$this->user_model->insert_user($data);
		redirect('accounts');
	}
}