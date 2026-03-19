<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor extends MY_Controller {

    public function __construct() {
        parent::__construct();
        /* Cargar modelos y librerías necesarias */
        $this->load->model('vendor_model');
    }

    /* Muestra la lista principal de proveedores */
    public function index() {
		/* Llamada al modelo con el nombre correcto del método */
		$data['vendors'] = $this->vendor_model->get_all_vendors();
		
		$data['main'] = 'vendor/index';
		$this->load->view('layout', $data);
	}

    /* Muestra el formulario para registrar un nuevo proveedor */
    public function create() {
		/* Obtener lista de países desde el modelo */
		$data['countries'] = $this->vendor_model->get_countries();
		$data['main'] = 'vendor/create';
		$this->load->view('layout', $data);
	}

    /**
	 * Procesa la inserción de un nuevo proveedor y sus múltiples contactos asociados.
	 * Esta función valida duplicidad de empresa (Tax ID + País) y de correos electrónicos.
	 */
	public function add() {
		/* Capturar datos maestros del formulario */
		$country_id = $this->input->post('country_id'); // Usamos ID para integridad con la tabla 'countries'
		$tax_id     = $this->input->post('tax_id');
		$emails     = $this->input->post('contact_email'); // Array proveniente del formulario dinámico

		/* 1. Validación de Duplicados de Empresa (País + Tax ID) */
		/* Se verifica que no exista la misma combinación para evitar registros redundantes */
		if ($this->vendor_model->check_duplicate($country_id, $tax_id)) {
			$this->session->set_flashdata('error', 'Error: Ya existe un proveedor registrado con ese País y Tax ID / RUC.');
			redirect('vendor/create');
			return;
		}

		/* 2. Validación de Duplicados de Email entre los contactos activos */
		/* Retorna el email duplicado si encuentra uno, de lo contrario false */
		$duplicate_email = $this->vendor_model->check_email_duplicates($emails);
		if ($duplicate_email) {
			$this->session->set_flashdata('error', "Error: El correo electrónico [{$duplicate_email}] ya está registrado con otro proveedor activo.");
			redirect('vendor/create');
			return;
		}

		/* 3. Preparar datos de la empresa (Table: vendors) */
		$vendor_data = array(
			'vendor_name' => $this->input->post('vendor_name'),
			'country_id'  => $country_id,
			'tax_id'      => $tax_id,
			'phone'       => $this->input->post('phone'),
			'mobile'      => $this->input->post('mobile'),
			'website'     => $this->input->post('website'),
			'description' => $this->input->post('description'),
			'status'      => 1, // Activo por defecto
			'created_by'  => $this->session->userdata('user_id') // Rastreo de quién registra
		);

		/* 4. Preparar lista de contactos vinculados (Table: vendor_contacts) */
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
					'status'       => 1 // Contacto activo
				);
			}
		}

		/* 5. Ejecución de la transacción en la base de datos */
		/* El modelo debe manejar una transacción SQL para asegurar que se guarden ambos o ninguno */
		if ($this->vendor_model->register_vendor_with_multiple_contacts($vendor_data, $contacts_batch)) {
			$this->session->set_flashdata('success', 'Proveedor 및 담당자 정보가 성공적으로 등록되었습니다.');
			redirect('vendor');
		} else {
			$this->session->set_flashdata('error', 'Error crítico al procesar el registro en la base de datos.');
			redirect('vendor/create');
		}
	}
	
	/**
	 * Muestra la información detallada del proveedor dentro del diseño principal (layout).
	 * @param int $id ID del proveedor.
	 */
	public function view($id) {
		/* Recuperar información principal del vendor */
		$vendor = $this->vendor_model->get_vendor_details($id);
		
		/* Validar existencia del registro */
		if (empty($vendor)) {
			show_404();
		}

		/* Preparar datos para la vista */
		$data['vendor'] = $vendor;
		$data['contacts'] = $this->vendor_model->get_vendor_contacts($id);
		
		/* Definir la vista de contenido para el layout */
		$data['main'] = 'vendor/view'; // Vista específica del contenido
		
		/* Cargar el layout principal que contiene el header/footer/menu */
		$this->load->view('layout', $data);
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
	
	/**
	 * Muestra el formulario de edición cargando la lista de países desde vendor_model.
	 * @param int $id ID del proveedor.
	 */
	public function edit($id) {
		/* 1. Intentar obtener la información detallada del proveedor actual */
		$vendor = $this->vendor_model->get_vendor_details($id);
		
		/* 2. Validación de existencia: Si no existe, redirigir con mensaje de error */
		if (empty($vendor)) {
			/* Notificación para el usuario en la página anterior */
			$this->session->set_flashdata('error', 'El proveedor solicitado no existe en nuestra base de datos.');
			redirect('vendor');
			return; 
		}

		/* 3. Obtener la lista de países usando el método correcto: get_countries() */
		/* Se utiliza el método definido previamente en el vendor_model */
		$data['countries'] = $this->vendor_model->get_countries();

		/* 4. Cargar los datos en la vista de edición */
		$data['vendor'] = $vendor;
		$data['main'] = 'vendor/edit';
		$this->load->view('layout', $data);
	}

	/**
	 * Procesa la actualización de los datos del proveedor.
	 */
	public function update() {
		$id = $this->input->post('id');
		
		/* Solo se permiten actualizar los campos que no comprometen la integridad fiscal */
		$update_data = array(
			'vendor_name' => $this->input->post('vendor_name'),
			'phone'       => $this->input->post('phone'),
			'mobile'      => $this->input->post('mobile'),
			'website'     => $this->input->post('website'),
			'description' => $this->input->post('description'),
			'status'      => $this->input->post('status')
		);

		/* Ejecución de la actualización en el modelo */
		if ($this->vendor_model->update_vendor($id, $update_data)) {
			$this->session->set_flashdata('success', 'La información del proveedor ha sido actualizada.');
		} else {
			$this->session->set_flashdata('error', 'Hubo un error al intentar actualizar los datos.');
		}
		
		redirect('vendor/view/'.$id);
	}

}