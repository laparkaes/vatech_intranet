<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor extends MY_Controller {

    public function __construct() {
        parent::__construct();
        /* Cargar modelos y librerías necesarias */
        $this->load->model('vendor_model');
    }

    /**
     * Muestra el Maestro de Entidades filtrado por proveedores (is_vendor = 1)
     */
    public function index() {
        $data['vendors'] = $this->vendor_model->get_entities_by_role('is_vendor');
        $data['main'] = 'vendor/index';
        $this->load->view('layout', $data);
    }

    public function create() {
        $data['countries'] = $this->vendor_model->get_countries();
        $data['main'] = 'vendor/create';
        $this->load->view('layout', $data);
    }

    /**
     * Registra una nueva entidad con sus respectivos roles y contactos
     */
    public function add() {
        $country_id = $this->input->post('country_id');
        $tax_id     = $this->input->post('tax_id');
        $emails     = $this->input->post('contact_email');

        /* Validación de duplicados en la tabla entities */
        if ($this->vendor_model->check_duplicate($country_id, $tax_id)) {
            $this->session->set_flashdata('error', 'Error: El RUC ya existe para este país.');
            redirect('vendor/create');
            return;
        }

        $entity_data = array(
            'vendor_name' => $this->input->post('vendor_name'),
            'country_id'  => $country_id,
            'tax_id'      => $tax_id,
            'is_vendor'   => $this->input->post('is_vendor') ? 1 : 0,
            'is_dealer'   => $this->input->post('is_dealer') ? 1 : 0,
            'phone'       => $this->input->post('phone'),
            'mobile'      => $this->input->post('mobile'),
            'website'     => $this->input->post('website'),
            'description' => $this->input->post('description'),
            'status'      => 1,
            'created_by'  => $this->session->userdata('user_id')
        );

        $names  = $this->input->post('contact_name');
        $roles  = $this->input->post('position');
        $phones = $this->input->post('indiv_phone');
        $mains  = $this->input->post('is_main');

        $contacts_batch = array();
        if (!empty($names)) {
            for ($i = 0; $i < count($names); $i++) {
                $contacts_batch[] = array(
                    'contact_name' => $names[$i],
                    'position'     => $roles[$i],
                    'email'        => $emails[$i],
                    'phone'        => $phones[$i],
                    'is_main'      => $mains[$i],
                    'status'       => 1
                );
            }
        }

        if ($this->vendor_model->register_entity_with_contacts($entity_data, $contacts_batch)) {
            $this->session->set_flashdata('success', 'Registro completado exitosamente.');
            redirect('vendor');
        } else {
            $this->session->set_flashdata('error', 'Error al registrar en la base de datos.');
            redirect('vendor/create');
        }
    }

    public function view($id) {
        $vendor = $this->vendor_model->get_vendor_details($id);
        if (empty($vendor)) show_404();

        $data['vendor'] = $vendor;
        $data['contacts'] = $this->vendor_model->get_vendor_contacts($id);
        $data['main'] = 'vendor/view';
        $this->load->view('layout', $data);
    }

    public function edit($id) {
        $vendor = $this->vendor_model->get_vendor_details($id);
        if (empty($vendor)) redirect('vendor');

        $data['countries'] = $this->vendor_model->get_countries();
        $data['vendor'] = $vendor;
        $data['main'] = 'vendor/edit';
        $this->load->view('layout', $data);
    }

    public function update() {
        $id = $this->input->post('id');
        $update_data = array(
            'vendor_name' => $this->input->post('vendor_name'),
            'is_vendor'   => $this->input->post('is_vendor') ? 1 : 0,
            'is_dealer'   => $this->input->post('is_dealer') ? 1 : 0,
            'phone'       => $this->input->post('phone'),
            'mobile'      => $this->input->post('mobile'),
            'website'     => $this->input->post('website'),
            'description' => $this->input->post('description'),
            'status'      => $this->input->post('status')
        );

        if ($this->vendor_model->update_vendor($id, $update_data)) {
            $this->session->set_flashdata('success', 'Información actualizada correctamente.');
        }
        redirect('vendor/view/'.$id);
    }
	
	/**
	 * Pantalla dedicada para la gestión de contactos de un proveedor específico.
	 * @param int $vendor_id ID del proveedor.
	 */
	public function contacts($vendor_id) {
		/* Obtener datos básicos del vendor para el encabezado */
		$data['vendor'] = $this->vendor_model->get_vendor_details($vendor_id);
		if (empty($data['vendor'])) { show_404(); }

		/* Obtener la lista completa de contactos */
		$data['contacts'] = $this->vendor_model->get_vendor_contacts($vendor_id);
		
		$data['main'] = 'vendor/contacts';
		$this->load->view('layout', $data);
	}

	/**
	 * Procesa la adición de un nuevo contacto verificando duplicidad entre activos.
	 */
	public function add_contact() {
		$vendor_id = $this->input->post('vendor_id');
		$email = $this->input->post('email');

		/* 1. Validar duplicidad solo con contactos ACTIVOS */
		if ($this->vendor_model->check_active_email_exists($email)) {
			/* Mensaje de error: El correo ya está asignado a alguien que trabaja actualmente */
			$this->session->set_flashdata('error', 'El correo electrónico ya pertenece a un contacto activo.');
			redirect('vendor/contacts/'.$vendor_id);
			return;
		}

		/* 2. Preparar datos si no hay conflicto de Email */
		$contact_data = array(
			'vendor_id'    => $vendor_id,
			'contact_name' => $this->input->post('contact_name'),
			'position'     => $this->input->post('position'),
			'email'        => $email,
			'phone'        => $this->input->post('phone'),
			'is_main'      => 0,
			'status'       => 1 // Se registra como activo por defecto
		);

		/* 3. Ejecutar la inserción en vendor_contacts */
		if ($this->vendor_model->insert_contact($contact_data)) {
			$this->session->set_flashdata('success', 'Nuevo contacto registrado exitosamente.');
		} else {
			$this->session->set_flashdata('error', 'No se pudo registrar el contacto debido a un error de base de datos.');
		}
		
		redirect('vendor/contacts/'.$vendor_id);
	}

	/**
	 * Desactiva un contacto (Soft Delete) para mantener integridad referencial.
	 */
	public function delete_contact($contact_id, $vendor_id) {
		/* Cambiar estado en lugar de borrar físicamente de la tabla */
		if ($this->vendor_model->soft_delete_contact($contact_id)) {
			$this->session->set_flashdata('success', 'Contacto desactivado del listado.');
		} else {
			$this->session->set_flashdata('error', 'No se pudo procesar la solicitud.');
		}
		redirect('vendor/contacts/'.$vendor_id);
	}

}