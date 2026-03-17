<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('access_model');
        $this->load->library('session');
        $this->load->helper('url');

        // Verificar si el usuario está autenticado
        if (!$this->session->userdata('is_logged_in')) {
            redirect('auth');
        }

        // Restringir el acceso a todo el controlador solo para administradores
        if ($this->session->userdata('role') !== 'admin') {
            // Si no es admin, redirigir al dashboard con un mensaje de error
            $this->session->set_flashdata('error', 'No tiene permisos para acceder a esta sección.');
            redirect('dashboard');
        }
    }

    /**
     * Muestra la gestión de accesos dividida en solicitudes pendientes e historial
     */
    public function request_list() {
        // Obtener todos los registros desde el modelo
        $all_requests = $this->access_model->get_all_requests();
        
        // Filtrar solicitudes con estado PENDING
        $data['pending_requests'] = array_filter($all_requests, function($req) {
            return $req->status === 'PENDING';
        });
        
        // Filtrar solicitudes procesadas (APPROVED o REJECTED)
        $data['processed_requests'] = array_filter($all_requests, function($req) {
            return $req->status !== 'PENDING';
        });

        $data['main'] = 'access/request_list';
        $this->load->view('layout', $data);
    }
	
	/**
	 * Muestra el formulario de solicitud de acceso (access_request)
	 */
	public function access_request() {
		$data['main'] = 'access/access_request'; // Nombre de la vista corregido
		$this->load->view('layout', $data);
	}

    /**
     * Procesa la solicitud de acceso enviada desde el Dashboard
     */
    public function request_access_process() {
        $selected_modules = $this->input->post('modules');
        $reason = $this->input->post('reason');

        if (empty($selected_modules)) {
            $this->session->set_flashdata('error', 'Por favor, seleccione al menos un módulo.');
            redirect('dashboard');
            return;
        }

        $success_count = 0;

        foreach ($selected_modules as $module) {
            $data = array(
                'user_id'     => $this->session->userdata('user_id'),
                'module_name' => $module,
                'reason'      => $reason,
                'status'      => 'PENDING'
            );

            if ($this->access_model->insert_request($data)) {
                $success_count++;
            }
        }

        if ($success_count > 0) {
            $this->session->set_flashdata('success', 'Se han enviado ' . $success_count . ' solicitudes correctamente.');
        } else {
            $this->session->set_flashdata('error', 'Error al procesar las solicitudes.');
        }
        
        // El usuario vuelve al dashboard después de solicitar
        redirect('dashboard');
    }

	/**
     * Procesa la solicitud solo si el usuario es administrador
     */
    public function update_status() {
        // Doble verificación de seguridad por rol de administrador
        if ($this->session->userdata('role') !== 'admin') {
            die('Acceso denegado');
        }

        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $admin_comment = $this->input->post('admin_comment');
        $admin_id = $this->session->userdata('user_id');

        if (!$id || !$status || !$admin_id) {
            $this->session->set_flashdata('error', 'Datos de procesamiento incompletos.');
            redirect('access');
            return;
        }

        $update_data = array(
            'status' => $status,
            'admin_comment' => $admin_comment,
            'processed_by_id' => $admin_id, // Se registra el ID del admin de la sesión
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->access_model->update_request($id, $update_data)) {
            $this->session->set_flashdata('success', 'Solicitud actualizada correctamente.');
        }

        redirect('access');
    }
	
}