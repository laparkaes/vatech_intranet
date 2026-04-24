<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_request extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        /* Cargar el modelo de accesos para todo el controlador */
        $this->load->model('access_model');
		
		$this->menu = "system";
		$this->menu_sub = "access_request";
    }

	/**
	 * Muestra la lista de solicitudes de acceso clasificadas por su estado.
	 * Solo accesible para usuarios con rol de administrador.
	 */
	public function index() {
		/* Verificar si el usuario actual es administrador */
		if ($this->session->userdata('role') !== 'admin') {
			redirect('dashboard');
		}

		/* Obtener solicitudes pendientes directamente desde la base de datos */
		$data['pending_requests']   = $this->access_model->get_requests_by_status('PENDING');

		/* Obtener solicitudes ya procesadas (Aprobadas o Rechazadas) para el historial */
		$data['completed_requests'] = $this->access_model->get_requests_by_status('COMPLETED');

		/* Cargar la vista dentro del diseño principal (Layout) */
		$data['main'] = 'access_request/index';
		$this->load->view('layout', $data);
	}

    /**
     * Procesa la aprobación o rechazo de una solicitud.
     */
    public function update_request_status() {
        if ($this->session->userdata('role') !== 'admin') redirect('dashboard');

        $id = $this->input->post('id');
        $data = array(
            'status'     => $this->input->post('status'),
            'updated_by' => $this->session->userdata('user_id'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->access_model->update_request_status($id, $data)) {
            $this->session->set_flashdata('success', 'Solicitud actualizada.');
        }
        redirect('access_request/index');
    }

    /**
     * Muestra el formulario para solicitar accesos (Checkboxes).
     */
    public function user() {
		
		$this->menu = "";
		$this->menu_sub = "";
		
		$user_id = $this->session->userdata('user_id');
		
		$data['available_access'] = $this->access_model->get_active_access();
		/* Obtener lista de IDs ya aprobados para este usuario */
		$data['approved_access_ids'] = $this->access_model->get_user_approved_access_ids($user_id);

		$data['main'] = 'access_request/user';
		$this->load->view('layout', $data);
	}

    /**
	 * Procesa el envío de solicitudes de acceso, validando duplicados.
	 */
	public function submit_user_request() {
		$access_ids = $this->input->post('access_ids');
		$reason = $this->input->post('reason');
		$user_id = $this->session->userdata('user_id');

		/* Verificar si se seleccionó al menos un acceso */
		if (empty($access_ids)) {
			$this->session->set_flashdata('error', 'Por favor, seleccione al menos un tipo de acceso.');
			redirect('access_request/user');
			return;
		}

		/* Obtener los IDs de los accesos que el usuario ya tiene aprobados o pendientes */
		/* Para evitar duplicidad total, se recomienda filtrar ambos estados */
		$this->db->select('access_id');
		$this->db->from('access_requests');
		$this->db->where('user_id', $user_id);
		$this->db->where_in('status', ['APPROVED', 'PENDING']);
		$query = $this->db->get();
		
		$existing_ids = array();
		foreach ($query->result() as $row) {
			$existing_ids[] = $row->access_id;
		}

		$success_count = 0;
		$duplicate_count = 0;

		foreach ($access_ids as $access_id) {
			/* Validar si el acceso ya existe en la lista del usuario */
			if (!in_array($access_id, $existing_ids)) {
				$data = array(
					'user_id'    => $user_id,
					'access_id'  => $access_id,
					'reason'     => $reason,
					'status'     => 'PENDING',
					'created_at' => date('Y-m-d H:i:s')
				);

				if ($this->access_model->insert_request($data)) {
					$success_count++;
				}
			} else {
				$duplicate_count++;
			}
		}

		/* Manejo de mensajes de retroalimentación para el usuario */
		if ($success_count > 0) {
			$msg = "Se han enviado " . $success_count . " solicitudes correctamente.";
			if ($duplicate_count > 0) {
				$msg .= " (" . $duplicate_count . " solicitudes fueron omitidas por ya existir).";
			}
			$this->session->set_flashdata('success', $msg);
		} else {
			if ($duplicate_count > 0) {
				$this->session->set_flashdata('error', 'Las solicitudes seleccionadas ya se encuentran aprobadas o en proceso.');
			} else {
				$this->session->set_flashdata('error', 'Error al procesar las solicitudes.');
			}
		}

		redirect('access_request/user');
	}
	
}