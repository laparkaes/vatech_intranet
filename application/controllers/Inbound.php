<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbound extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Carga de modelos
        $this->load->model('inbound_model');
        $this->load->model('mapping_model');
        // Agregar lógica de seguridad común o verificación de login aquí si es necesario
    }

    /**
     * 1. Listado de Entradas (Consulta de la lista de ingresos)
     */
    public function index() {
        $data['list'] = $this->inbound_model->get_inbound_list();
        
        // Aplicar diseño de layout
        $data['main'] = 'inbound/list';
        $this->load->view('layout', $data);
    }

    /**
     * 2. Formulario de Nueva Entrada (Registro de nuevo ingreso)
     */
    public function create() {
        // 1. Cargar modelos necesarios
        $this->load->model('mapping_model');
        $this->load->model('product_model'); // Cargar modelo de productos

        // 2. Cargar datos de Mappings y Almacenes
        $data['sources'] = $this->mapping_model->get_list('inbound_source');
        $data['warehouses'] = $this->db->get_where('warehouses', ['is_active' => 1])->result();
        
        /**
         * 3. Uso del modelo de productos (Llamada a método del modelo en lugar de acceso directo a DB)
         * Se utiliza get_products() para obtener la lista completa de ítems disponibles.
         */
        $data['products'] = $this->product_model->get_products(null, null, []); 

        // Aplicar diseño de layout
        $data['main'] = 'inbound/create';
        $this->load->view('layout', $data);
    }

    /**
     * 2-1. Procesar Registro de Nueva Entrada
     */
    public function add() {
        // Obtener IDs de estados iniciales (PENDING)
        $status_pending = $this->mapping_model->get_id_by_code('inbound_status', 'PENDING');
        $item_status_pending = $this->mapping_model->get_id_by_code('inbound_item_status', 'PENDING');

        // Configuración de datos del encabezado (según campos de structure.sql)
        $header = [
            'inbound_number' => 'INB-' . date('YmdHis'),
            'source_type_id' => $this->input->post('source_type_id'),
            'warehouse_id'   => $this->input->post('warehouse_id'),
            'expected_date'  => $this->input->post('expected_date'),
            'status_id'      => $status_pending,
            'notes'          => $this->input->post('notes'),
            'created_by'     => $this->session->userdata('user_id') ?: 1
        ];

        // Configuración de datos de ítems
        $items = [];
        $post_items = $this->input->post('items');
        if (!empty($post_items)) {
            foreach ($post_items as $val) {
                $items[] = [
                    'item_id'        => $val['item_id'],
                    'expected_qty'   => $val['qty'],
                    'item_status_id' => $item_status_pending
                ];
            }
        }

        if ($this->inbound_model->save_inbound($header, $items)) {
            $this->session->set_flashdata('success', 'Entrada registrada exitosamente.');
            redirect('inbound');
        } else {
            $this->session->set_flashdata('error', 'Error al registrar la entrada.');
            redirect('inbound/create');
        }
    }

    /**
     * 3. Detalle de Entrada (Consulta de información detallada)
     */
    public function view($id) {
        $data['inbound'] = $this->inbound_model->get_inbound_full($id);
        
        if (!$data['inbound']) {
            show_404();
        }

        // Aplicar diseño de layout
        $data['main'] = 'inbound/view';
        $this->load->view('layout', $data);
    }

    /**
     * 4-1. Actualizar Registro (Proceso de edición)
     */
    // Formulario de edición: Bloquear acceso si la entrada ya está finalizada (ID: 29)
    public function edit($id) {
        $data['inbound'] = $this->inbound_model->get_inbound_full($id);
        if (!$data['inbound']) show_404();

        if ($data['inbound']['header']->status_id == 29) {
            $this->session->set_flashdata('error', 'Esta entrada ya ha sido finalizada y no se puede editar.');
            redirect('inbound/view/' . $id);
            return;
        }

        $data['warehouses'] = $this->db->get_where('warehouses', ['is_active' => 1])->result();
        $data['main'] = 'inbound/edit';
        $this->load->view('layout', $data);
    }

    /**
     * Procesar actualización: Asignación automática de estados según cantidad y verificación de finalización.
     */
    public function update_process() {
        $inbound_id = $this->input->post('inbound_id');
        
        // 1. Reconfirmar estado actual (Seguridad y prevención de duplicados)
        $current = $this->inbound_model->get_inbound_full($inbound_id);
        if ($current['header']->status_id == 29) {
            $this->session->set_flashdata('error', 'No se pueden realizar cambios en un ingreso ya finalizado.');
            redirect('inbound/view/' . $inbound_id);
            return;
        }

        $items_post = $this->input->post('items');
        $items_data = [];
        $all_items_processed = true; // Flag para verificar si todos los ítems han sido procesados

        // 2. Procesamiento de datos de ítems y determinación de estados
        foreach ($items_post as $item) {
            $expected = (int)$item['expected_qty'];
            $received = (int)$item['received_qty'];
            $damaged = (int)$item['damaged_qty'];
            $processed_sum = $received + $damaged; // Suma de recibidos + dañados

            // Si alguna cantidad procesada es menor a la esperada, el proceso total no está completo
            if ($processed_sum < $expected) {
                $all_items_processed = false;
            }

            // Asignación de estados detallados por ítem
            if ($received === 0 && $damaged === 0) {
                $item_status = 31; // PENDING
            } elseif ($received >= $expected) {
                $item_status = 32; // RECEIVED (o EXCESS)
            } elseif ($processed_sum > 0 && $processed_sum < $expected) {
                $item_status = 33; // PARTIAL
            } else {
                $item_status = 32; // Recepción estándar
            }

            $items_data[] = [
                'id'             => $item['id'],
                'received_qty'   => $received,
                'damaged_qty'    => $damaged,
                'bin_location'   => $item['bin_location'],
                'item_status_id' => $item_status
            ];
        }

        // 3. Configuración de datos del encabezado
        $header_data = [
            'warehouse_id'  => $this->input->post('warehouse_id'),
            'expected_date' => $this->input->post('expected_date'),
            'notes'         => $this->input->post('notes'),
            'updated_by'    => $this->session->userdata('user_id') // Registro de información del usuario
        ];

        // 4. Lógica de determinación de estado general
        // Solo se marca como Finalizado (29) si la suma de (recibidos + dañados) de todos los ítems es igual o mayor a lo esperado.
        if ($all_items_processed === true) {
            $header_data['status_id'] = 29; // Ingreso Finalizado
            $header_data['arrival_date'] = date('Y-m-d H:i:s');
            $success_msg = 'Entrada confirmada y finalizada exitosamente.';
        } else {
            // Si falta algún ítem, se mantiene el estado de progreso (ej. 28 - In Progress)
            $header_data['status_id'] = 28; 
            $success_msg = 'Cantidades actualizadas, pero aún quedan ítems pendientes.';
        }

        // 5. Ejecución de transacción en DB
        if ($this->inbound_model->update_inbound_full($inbound_id, $header_data, $items_data)) {
            $this->session->set_flashdata('success', $success_msg);
        } else {
            $this->session->set_flashdata('error', 'Error al procesar la entrada.');
        }

        redirect('inbound/view/' . $inbound_id);
    }
    
}