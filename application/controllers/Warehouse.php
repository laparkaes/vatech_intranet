<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador de Almacenes (Warehouses)
 * Implementado con la misma estructura del módulo de Productos.
 */
class Warehouse extends MY_Controller {

    public function __construct() {
        parent::__construct();
        /* Carga de modelos necesarios */
        $this->load->model('warehouse_model');
        $this->load->library('session');
    }

    /**
     * Muestra el listado de almacenes (Index) con paginación
     */
    public function index() {
        $this->load->library('pagination');

        $keyword = $this->input->get('keyword');
        
        // 1. Configuración de la paginación (30 por página)
        $config['base_url'] = base_url('warehouse/index');
        $config['total_rows'] = $this->warehouse_model->count_all_warehouses($keyword);
        $config['per_page'] = 30;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = TRUE; // Para mantener el keyword al navegar

        // Estilos para la paginación (Consistente con Product)
        $config['full_tag_open'] = '<div class="pagination">';
        $config['full_tag_close'] = '</div>';

        $this->pagination->initialize($config);

        // 2. Obtener el número de página actual (offset)
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // 3. Obtener almacenes con límite y offset
        $data['warehouses'] = $this->warehouse_model->get_warehouses_paged($config['per_page'], $page, $keyword);
        $data['pagination_links'] = $this->pagination->create_links();
        
        $data['main'] = 'warehouse/index';
        $this->load->view('layout', $data);
    }

    /**
     * Muestra el formulario para registrar un nuevo almacén
     */
    public function create() {
        // Obtener entidades activas para el select de administración
        $data['entities'] = $this->db->get_where('entities', ['status' => 1])->result();
        
        $data['main'] = 'warehouse/create';
        $this->load->view('layout', $data);
    }

    /**
     * Procesa el registro de un nuevo almacén con validación de nombre único
     */
    public function add() {
        $user_id = $this->session->userdata('user_id');
        $name = $this->input->post('name');

        // 1. Validar duplicidad de nombre
        $exists = $this->db->get_where('warehouses', ['name' => $name])->row();
        if ($exists) {
            $this->session->set_flashdata('error', 'El nombre del almacén ya existe. Por favor, use uno diferente.');
            redirect('warehouse/create');
        }
        
        $data = array(
            'name'                 => $name,
            'address'              => $this->input->post('address'),
            'location_info'        => $this->input->post('location_info'),
            'contractor_entity_id' => $this->input->post('contractor_entity_id') ?: null,
            'is_active'            => 1,
            'updated_by'           => $user_id
        );

        if ($this->warehouse_model->insert($data)) {
            $this->session->set_flashdata('success', 'Almacén registrado exitosamente.');
            redirect('warehouse');
        } else {
            $this->session->set_flashdata('error', 'Error al registrar el almacén.');
            redirect('warehouse/create');
        }
    }

    /**
     * Vista de detalles del almacén
     */
    public function view($id) {
        $data['warehouse'] = $this->warehouse_model->get_warehouse_detail($id);
        if (empty($data['warehouse'])) show_404();
        
        $data['main'] = 'warehouse/view';
        $this->load->view('layout', $data);
    }

    /**
     * Muestra el formulario de edición
     */
    public function edit($id) {
        $data['warehouse'] = $this->warehouse_model->get_warehouse_by_id($id);
        if (!$data['warehouse']) show_404();

        $data['entities'] = $this->db->get_where('entities', ['status' => 1])->result();
        
        $data['main'] = 'warehouse/edit';
        $this->load->view('layout', $data);
    }

    /**
     * Actualiza la información del almacén con validación de nombre único (excluyendo el actual)
     */
    public function update($id) {
        $user_id = $this->session->userdata('user_id');
        $name = $this->input->post('name');

        // 1. Validar duplicidad de nombre (excluyendo el ID actual)
        $this->db->where('name', $name);
        $this->db->where('id !=', $id);
        $exists = $this->db->get('warehouses')->row();

        if ($exists) {
            $this->session->set_flashdata('error', 'El nombre ya está en uso por otro almacén.');
            redirect('warehouse/edit/'.$id);
        }
        
        $data = array(
            'name'                 => $name,
            'address'              => $this->input->post('address'),
            'location_info'        => $this->input->post('location_info'),
            'contractor_entity_id' => $this->input->post('contractor_entity_id') ?: null,
            'is_active'            => $this->input->post('is_active'),
            'updated_at'           => date('Y-m-d H:i:s'),
            'updated_by'           => $user_id
        );

        if ($this->warehouse_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Información del almacén actualizada.');
            redirect('warehouse/view/'.$id);
        } else {
            $this->session->set_flashdata('error', 'Error al actualizar el almacén.');
            redirect('warehouse/edit/'.$id);
        }
    }

    /**
     * Cambia el estado del almacén a Inactivo (Soft Delete)
     * En lugar de eliminar el registro, se desactiva.
     */
    public function delete($id) {
        $user_id = $this->session->userdata('user_id');
        
        $data = array(
            'is_active' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $user_id
        );

        if ($this->warehouse_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Almacén desactivado correctamente.');
        } else {
            $this->session->set_flashdata('error', 'Error al intentar desactivar el almacén.');
        }
        redirect('warehouse');
    }

    /**
     * Cambia el estado del almacén (Activar/Desactivar)
     * @param int $id ID del almacén
     * @param int $status 1 para activar, 0 para desactivar
     */
    public function status($id, $status) {
        $user_id = $this->session->userdata('user_id');
        
        $data = array(
            'is_active' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $user_id
        );

        if ($this->warehouse_model->update($id, $data)) {
            $msg = ($status == 1) ? 'Almacén activado.' : 'Almacén desactivado.';
            $this->session->set_flashdata('success', $msg);
        } else {
            $this->session->set_flashdata('error', 'Error al cambiar el estado.');
        }
        redirect('warehouse/view/'.$id);
    }

}