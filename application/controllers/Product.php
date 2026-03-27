<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador de Productos
 * Todos los métodos cargan la vista a través de 'layout' para mantener la consistencia de la UI.
 */
class Product extends MY_Controller {

    public function __construct() {
        parent::__construct();
        /* Carga de modelos necesarios */
        $this->load->model('product_model');
        $this->load->model('exchange_model'); 
        $this->load->library('session');
    }

    /**
     * Muestra el listado maestro (Index)
     */
    public function index() {
        $keyword = $this->input->get('keyword');
        $category_id = $this->input->get('category_id');
        $type = $this->input->get('type');

        // Obtener categorías para el filtro desde la tabla correcta
        $data['categories'] = $this->db->get('product_categories')->result();

        // Obtener productos con sus variantes y precios actuales
        $data['products'] = $this->product_model->get_products_with_items($keyword, $category_id, $type);

        // Definir la vista principal y cargar el layout
        $data['main'] = 'product/index';
        $this->load->view('layout', $data);
    }

    /**
     * Muestra el formulario de registro de nuevo producto
     */
    public function create() {
        $data['categories'] = $this->db->get('product_categories')->result();
        
        // Obtener la última tasa de cambio registrada
        $data['current_rate'] = $this->db->order_by('id', 'DESC')->get('exchange_rates', 1)->row();
        
        // Definir la vista principal y cargar el layout
        $data['main'] = 'product/create';
        $this->load->view('layout', $data);
    }

    /**
     * Procesa el registro de un nuevo producto
     */
    public function add() {
        $user_id = $this->session->userdata('user_id');
        
        // Preparar datos básicos del producto (El campo 'code' se guarda como NULL si está vacío)
        $product_data = array(
            'type'           => $this->input->post('type'),
            'category_id'    => $this->input->post('category_id'),
            'code'           => $this->input->post('code') ?: null, 
            'name'           => $this->input->post('name'),
            'brand'          => $this->input->post('brand'),
            'origin_country' => $this->input->post('origin_country'),
            'unit'           => $this->input->post('unit'),
            'description'    => $this->input->post('description'),
            'is_active'      => 1,
            'updated_by'     => $user_id
        );

        $this->db->trans_start();

        // Insertar producto principal
        $this->db->insert('products', $product_data);
        $product_id = $this->db->insert_id();

        // Procesar variantes (items) y sus precios
        $sku_codes    = $this->input->post('sku_code');
        $option_names = $this->input->post('option_name');
        $option_values = $this->input->post('option_value');
        $p_usd = $this->input->post('purchase_price_usd');
        $p_pen = $this->input->post('purchase_price_pen');
        $s_usd = $this->input->post('sale_price_usd');
        $s_pen = $this->input->post('sale_price_pen');
        $applied_rate = $this->input->post('applied_rate');

        if (!empty($sku_codes) && is_array($sku_codes)) {
            foreach ($sku_codes as $i => $sku) {
                $sku = trim($sku);
                if (empty($sku)) continue;

                // Validación de duplicados para el SKU
                $this->db->where('sku_code', $sku);
                if ($this->db->get('product_items')->num_rows() > 0) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('old_input', $this->input->post());
                    $this->session->set_flashdata('error', "El SKU '{$sku}' ya existe.");
                    redirect('product/create');
                    return;
                }

                // Insertar variante del producto
                $item_data = array(
                    'product_id'   => $product_id,
                    'sku_code'     => $sku,
                    'option_name'  => $option_names[$i] ?? null,
                    'option_value' => $option_values[$i] ?? null,
                    'status'       => 1,
                    'updated_by'   => $user_id
                );
                $this->db->insert('product_items', $item_data);
                $item_id = $this->db->insert_id();

                // Registrar el historial de precios inicial
                $price_data = array(
                    'item_id'            => $item_id,
                    'purchase_price_usd' => $p_usd[$i],
                    'purchase_price_pen' => $p_pen[$i],
                    'sale_price_usd'     => $s_usd[$i],
                    'sale_price_pen'     => $s_pen[$i],
                    'applied_rate'       => $applied_rate,
                    'created_by'         => $user_id
                );
                $this->db->insert('product_price_history', $price_data);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('old_input', $this->input->post());
            $this->session->set_flashdata('error', 'Error en la base de datos durante el registro.');
            redirect('product/create');
        } else {
            $this->session->set_flashdata('success', 'Producto registrado exitosamente.');
            redirect('product');
        }
    }

    /**
     * Vista de detalles del producto
     */
    public function view($id) {
        $data['product'] = $this->product_model->get_product_detail($id);
        if (empty($data['product'])) show_404();
        
        $data['main'] = 'product/view';
        $this->load->view('layout', $data);
    }

    /**
     * Muestra el formulario de edición
     */
    public function edit($id) {
        $data['product'] = $this->product_model->get_product_detail($id);
        if (empty($data['product'])) show_404();

        $data['categories'] = $this->db->get('product_categories')->result();
        
        $data['main'] = 'product/edit';
        $this->load->view('layout', $data);
    }
}