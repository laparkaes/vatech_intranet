<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        /* Cargar el modelo de accesos para todo el controlador */
        $this->load->model('access_model');
		
		$this->menu = "master";
		$this->menu_sub = "access";
    }

    /**
     * Muestra la lista de configuraciones de acceso (Maestro).
     */
    public function index() {
        if ($this->session->userdata('role') !== 'admin') redirect('dashboard');

        $data['access_list'] = $this->access_model->get_access_list();
        $data['main'] = 'access/index';
        $this->load->view('layout', $data);
    }

    /**
     * Registra un nuevo tipo de acceso.
     */
    public function add() {
        if ($this->session->userdata('role') !== 'admin') redirect('dashboard');

        $data = array(
            'access_name' => $this->input->post('access_name'),
            'description' => $this->input->post('description'),
            'status'      => 1,
            'updated_by'  => $this->session->userdata('user_id')
        );

        if ($this->access_model->insert_access($data)) {
            $this->session->set_flashdata('success', 'Acceso creado exitosamente.');
        }
        redirect('access');
    }

    /**
     * Actualiza un acceso existente.
     */
    public function update() {
		if ($this->session->userdata('role') !== 'admin') redirect('dashboard');

		$id = $this->input->post('id');
		$data = array(
			'access_name' => $this->input->post('access_name'),
			'description' => $this->input->post('description'),
			'status'      => $this->input->post('status'),
			'updated_by'  => $this->session->userdata('user_id'),
			/* Se agrega la fecha actual para el campo updated_at */
			'updated_at'  => date('Y-m-d H:i:s') 
		);

		if ($this->access_model->update_access($id, $data)) {
			$this->session->set_flashdata('success', 'Configuración actualizada.');
		}
		redirect('access');
	}

}