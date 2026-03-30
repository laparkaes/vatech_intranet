<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador de Inventario (Inventory)
 * Muestra el estado actual del stock por almacén y estado.
 */
class Inventory extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('inventory_model');
    }

    /**
     * Vista principal: Listado de stock actual
     */
    public function index() {
        // Obtener filtros de búsqueda (opcional)
        $warehouse_id = $this->input->get('warehouse_id');
        $product_name = $this->input->get('product_name');

        // Cargar datos necesarios para los filtros
        $data['warehouses'] = $this->db->get_where('warehouses', ['is_active' => 1])->result();
        
        // Obtener el stock actual procesado
        $data['inventory'] = $this->inventory_model->get_current_stock($warehouse_id, $product_name);

        $data['main'] = 'inventory/index';
        $this->load->view('layout', $data);
    }

	/**
	 * Vista de Kardex: Historial de movimientos detallado
	 */
	public function kardex() {
		// 1. Obtener parámetros de búsqueda del formulario (GET)
		$warehouse_id = $this->input->get('warehouse_id');
		$start_date   = $this->input->get('start_date');
		$end_date     = $this->input->get('end_date');

		// 2. Cargar lista de almacenes para el dropdown del filtro
		$data['warehouses'] = $this->db->get_where('warehouses', ['is_active' => 1])->result();
		
		// 3. Obtener los logs de movimientos desde el modelo
		$data['logs'] = $this->inventory_model->get_kardex_report($warehouse_id, $start_date, $end_date);

		// 4. Cargar la vista de Kardex (CSS excluido, solo HTML puro)
		$data['main'] = 'inventory/kardex';
		$this->load->view('layout', $data);
	}

}