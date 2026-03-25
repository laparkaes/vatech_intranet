<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distributor extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Carga del modelo de entidades para manejar Distribuidores
        $this->load->model('entity_model');
    }

    /**
     * Muestra el listado de Distribuidores (is_dealer = 1)
     */
    public function index() {
        $data['distributors'] = $this->entity_model->get_entities_by_role('is_dealer');
        $data['main'] = 'distributor/index';
        $this->load->view('layout', $data);
    }

    /**
     * Muestra el formulario para registrar un nuevo distribuidor
     */
    public function create() {
        $data['countries'] = $this->entity_model->get_countries();
        $data['main'] = 'distributor/create';
        $this->load->view('layout', $data);
    }

    /**
     * Procesa la inserción de un nuevo distribuidor y 자서 sus contactos
     */
    public function add() {
        $country_id = $this->input->post('country_id');
        $tax_id     = $this->input->post('tax_id');

        // Verificar duplicados por RUC/Tax ID y País
        if ($this->entity_model->check_duplicate($country_id, $tax_id)) {
            $this->session->set_flashdata('error', 'El RUC/Tax ID ya existe para este país.');
            redirect('distributor/create');
            return;
        }

        $entity_data = array(
            'name'        => $this->input->post('name'),
            'country_id'  => $country_id,
            'tax_id'      => $tax_id,
            'is_vendor'   => $this->input->post('is_vendor') ? 1 : 0,
            'is_dealer'   => $this->input->post('is_dealer') ? 1 : 0,
            'phone'       => $this->input->post('phone'),
            'mobile'      => $this->input->post('mobile'),
            'address'     => $this->input->post('address'),
            'website'     => $this->input->post('website'),
            'description' => $this->input->post('description'),
            'status'      => 1,
            'created_by'  => $this->session->userdata('user_id')
        );

        $contacts_batch = array();
        $names = $this->input->post('contact_name');
        if (!empty($names)) {
            foreach ($names as $key => $val) {
                if(!empty($val)) {
                    $contacts_batch[] = array(
                        'contact_name' => $val,
                        'position'     => $this->input->post('position')[$key],
                        'email'        => $this->input->post('contact_email')[$key],
                        'phone'        => $this->input->post('indiv_phone')[$key],
                        'is_main'      => isset($this->input->post('is_main')[$key]) ? 1 : 0,
                        'status'       => 1
                    );
                }
            }
        }

        if ($this->entity_model->register_entity_with_contacts($entity_data, $contacts_batch)) {
            $this->session->set_flashdata('success', 'Distribuidor registrado con éxito.');
            redirect('distributor');
        } else {
            $this->session->set_flashdata('error', 'Error al procesar el registro.');
            redirect('distributor/create');
        }
    }

    /**
     * Muestra el formulario de edición para un distribuidor existente
     */
    public function edit($id) {
        $data['distributor'] = $this->entity_model->get_entity_details($id);
        if (empty($data['distributor'])) {
            show_404();
        }

        $data['countries'] = $this->entity_model->get_countries();
        $data['main'] = 'distributor/edit';
        $this->load->view('layout', $data);
    }

    /**
     * Actualiza la información del distribuidor
     */
    public function update() {
        $id = $this->input->post('id');
        
        $update_data = array(
            'name'        => $this->input->post('name'),
            'country_id'  => $this->input->post('country_id'),
            'tax_id'      => $this->input->post('tax_id'),
            'is_vendor'   => $this->input->post('is_vendor') ? 1 : 0,
            'is_dealer'   => $this->input->post('is_dealer') ? 1 : 0,
            'phone'       => $this->input->post('phone'),
            'mobile'      => $this->input->post('mobile'),
            'website'     => $this->input->post('website'),
            'address'     => $this->input->post('address'),
            'description' => $this->input->post('description'),
            'status'      => $this->input->post('status')
        );

        if ($this->entity_model->update_entity($id, $update_data)) {
            $this->session->set_flashdata('success', 'Información actualizada correctamente.');
            redirect('distributor/view/'.$id);
        } else {
            $this->session->set_flashdata('error', 'No se pudo actualizar la información.');
            redirect('distributor/edit/'.$id);
        }
    }

    /**
     * Muestra la información detallada de un distribuidor
     */
    public function view($id) {
        $data['distributor'] = $this->entity_model->get_entity_details($id);
        if (empty($data['distributor'])) { show_404(); }
        $data['contacts'] = $this->entity_model->get_entity_contacts($id);
        $data['main'] = 'distributor/view';
        $this->load->view('layout', $data);
    }

    /**
     * Pantalla de gestión de contactos para el distribuidor
     */
    public function contacts($entity_id) {
        $data['distributor'] = $this->entity_model->get_entity_details($entity_id);
        if (empty($data['distributor'])) { show_404(); }
        $data['contacts'] = $this->entity_model->get_entity_contacts($entity_id);
        $data['main'] = 'distributor/contacts';
        $this->load->view('layout', $data);
    }

    /**
     * Define un contacto como el principal para la entidad
     */
    public function make_main_contact($contact_id, $entity_id) {
        $this->entity_model->set_main_contact($contact_id, $entity_id);
        redirect('distributor/contacts/'.$entity_id);
    }

    /**
     * Realiza un borrado lógico de un contacto
     */
    public function delete_contact($contact_id, $entity_id) {
        $this->entity_model->soft_delete_contact($contact_id);
        redirect('distributor/contacts/'.$entity_id);
    }

    /**
     * Añade un nuevo contacto a un distribuidor existente
     */
    public function add_contact() {
        $entity_id = $this->input->post('distributor_id');
        $email = $this->input->post('email');

        if ($this->entity_model->check_active_email_exists($email)) {
            $this->session->set_flashdata('error', 'El correo electrónico ya está registrado.');
        } else {
            $data = array(
                'entity_id'    => $entity_id,
                'contact_name' => $this->input->post('contact_name'),
                'position'     => $this->input->post('position'),
                'email'        => $email,
                'phone'        => $this->input->post('phone'),
                'status'       => 1
            );
            $this->entity_model->insert_contact($data);
        }
        redirect('distributor/contacts/'.$entity_id);
    }
}