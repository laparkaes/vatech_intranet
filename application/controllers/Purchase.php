<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador de Compras (Purchase Orders)
 * Gestión centralizada de PO utilizando Mapping_model para integridad de datos.
 */
class Purchase extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Carga de modelos necesarios
        $this->load->model('purchase_model');
		$this->load->model('entity_model');
        $this->load->model('mapping_model');
    }

    /**
     * Lista principal de Órdenes de Compra
     */
    public function index() {
        $data['orders'] = $this->purchase_model->get_all_pos();
        $data['main'] = 'purchase/index';
        $this->load->view('layout', $data);
    }

    /**
     * Muestra el formulario de creación cargando opciones desde Mapping_model
     */
    public function create() {
        // 1. Obtener proveedores activos (Entities)
        $data['suppliers'] = $this->db->get_where('entities', [
            'is_vendor' => 1, 
            'status'    => 1
        ])->result();
        
        // 2. Obtener ítems de productos disponibles
        $this->db->select('pi.id, p.name, pi.option');
        $this->db->from('product_items pi');
        $this->db->join('products p', 'pi.product_id = p.id');
        $data['items'] = $this->db->get()->result();

        // 3. [추가] 입고 대상 창고 목록 불러오기 (Warehouses)
        // DB structure에 정의된 warehouses 테이블을 참조합니다.
        $data['warehouses'] = $this->db->get_where('warehouses', [
            'is_active' => 1
        ])->result();

        // 4. Carga de catálogos dinámicos desde Mapping_model
        $data['po_types']         = $this->mapping_model->get_list('po_type');
        $data['currencies']       = $this->mapping_model->get_list('currency');
        $data['shipping_methods'] = $this->mapping_model->get_list('shipping_method');
        $data['incoterms']        = $this->mapping_model->get_list('incoterms');
        $data['payment_terms']    = $this->mapping_model->get_list('payment_terms');

        $data['main'] = 'purchase/create';
        $this->load->view('layout', $data);
    }
    
    /**
     * Procesa la inserción de una nueva PO
     */
    public function add() {
        $this->load->library('form_validation');

        // 1. Validaciones de servidor (Se agrega validación para warehouse_id)
        $this->form_validation->set_rules('supplier_id', 'Proveedor', 'required|numeric');
        $this->form_validation->set_rules('warehouse_id', 'Almacén', 'required|numeric'); // 추가
        $this->form_validation->set_rules('issue_date', 'Fecha de Emisión', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->create(); 
        } else {
            $initial_status_id = $this->mapping_model->get_id_by_code('po_status', 'Registrado');

            $po_data = [
                'po_number'       => 'VPR-PO-' . date('Ymd-His'), 
                'supplier_id'     => $this->input->post('supplier_id'),
                'warehouse_id'    => $this->input->post('warehouse_id'), // 추가: DB 컬럼명과 일치해야 함
                'po_type'         => $this->input->post('po_type'),
                'currency'        => $this->input->post('currency'),
                'incoterms'       => $this->input->post('incoterms'),
                'payment_terms'   => $this->input->post('payment_terms'),
                'shipping_method' => $this->input->post('shipping_method'),
                'issue_date'      => $this->input->post('issue_date'),
                'expected_date'   => $this->input->post('expected_date'),
                'notes'           => $this->input->post('notes'),
                'status'          => $initial_status_id,
                'created_by'      => $this->session->userdata('user_id') ?: 1
            ];

            $items = $this->input->post('items');

            $result = $this->purchase_model->save_full_po($po_data, $items);

            if ($result) {
                $this->session->set_flashdata('success', 'Orden de Compra registrada exitosamente.');
                redirect('purchase');
            } else {
                $this->session->set_flashdata('error', 'Error crítico al guardar la orden.');
                redirect('purchase/create');
            }
        }
    }

    /**
     * Visualización detallada de una Orden de Compra (PO)
     */
    public function view($id) {
        $data['po'] = $this->purchase_model->get_po_header($id);
        $data['items'] = $this->purchase_model->get_po_items($id);

        if (!$data['po']) { 
            show_404(); 
        }

        $data['main'] = 'purchase/view';
        $this->load->view('layout', $data);
    }
    
	/**
     * 통합 상태 업데이트 (Aprobar/Rechazar/Cancelar) 및 입고(Inbound) 자동 생성
     */
    public function update_status($id) {
        $current_user_id = $this->session->userdata('user_id');
        $user_role = $this->session->userdata('role');

        $po = $this->purchase_model->get_po_header($id);
        if (!$po) show_404();

        $status_code = $this->input->post('status_code');
        $comment = $this->input->post('comment');

        $status_id = $this->mapping_model->get_id_by_code('po_status', $status_code);
        if (!$status_id) show_error("Estado inválido.");

        // 권한 체크 (기존 로직 유지)
        $is_admin = ($user_role === 'admin');
        $is_logistics = ($user_role === 'logistics');
        $is_creator = ($po->created_by == $current_user_id);

        $can_process = false;
        if ($status_code === 'Cancelado') {
            if ($is_creator || $is_admin) $can_process = true;
        } else {
            if ($is_admin || ($is_logistics && !$is_creator)) $can_process = true;
        }

        if ($can_process) {
            // 트랜잭션 시작 (상태 업데이트와 입고 생성을 한 번에 처리)
            $this->db->trans_start();

            // 1. PO 상태 업데이트
            $this->purchase_model->update_po_status($id, $status_id, $current_user_id, $comment);

            // 2. [추가] 승인(Aprobado)인 경우에만 입고 데이터 생성
            if ($status_code === 'Aprobado') {
                $this->_generate_inbound_from_po($id, $po, $current_user_id);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', "Error al procesar la aprobación.");
            } else {
                $this->session->set_flashdata('success', "La orden ha sido actualizada a: $status_code");
            }
        } else {
            show_error("Acceso denegado: No tiene permisos.", 403);
        }

        redirect('purchase/view/' . $id);
    }

    /**
     * Genera automáticamente un registro de Inbound (entrada) al aprobar una PO.
     * Basado en la estructura de base de datos refactorizada.
     * * @param int $po_id
     * @param object $po (Cabecera de la PO)
     * @param int $user_id
     * @return int|bool
     */
    private function _generate_inbound_from_po($po_id, $po, $user_id) {
        // 1. Obtener los ítems detallados de la PO
        $items = $this->purchase_model->get_po_items($po_id);
        if (empty($items)) return false;

        // 2. Obtener IDs de mapeo (Categorías: inbound_source, inbound_status, inbound_item_status)
        $source_type_id = $this->mapping_model->get_id_by_code('inbound_source', 'PURCHASE_ORDER');
        $status_id      = $this->mapping_model->get_id_by_code('inbound_status', 'PENDING');
        $item_status_id = $this->mapping_model->get_id_by_code('inbound_item_status', 'PENDING');

        // 3. Preparar datos de cabecera de Inbound
        $inbound_data = [
            'inbound_number' => 'INB-' . date('Ymd') . '-' . str_pad($po_id, 4, '0', STR_PAD_LEFT),
            'source_type_id' => $source_type_id,
            'status_id'      => $status_id,
            'source_id'      => $po_id,
            'warehouse_id'   => $po->warehouse_id,
            'expected_date'  => $po->expected_date,
            'notes'          => "Generado automáticamente desde PO: " . $po->po_number,
            'created_by'     => $user_id,
            'created_at'     => date('Y-m-d H:i:s')
        ];
        
        // Insertar cabecera
        $this->db->insert('inbounds', $inbound_data);
        $inbound_id = $this->db->insert_id();

        // 4. Crear ítems detallados de Inbound (inbound_items)
        foreach ($items as $item) {
            $this->db->insert('inbound_items', [
                'inbound_id'     => $inbound_id,
                'item_id'        => $item->item_id,
                'expected_qty'   => $item->quantity,
                'received_qty'   => 0,
                'damaged_qty'    => 0,
                'item_status_id' => $item_status_id, // ID de la categoría inbound_item_status
                'bin_location'   => NULL             // Se asignará durante la inspección física
            ]);
        }

        return $inbound_id;
    }

	/**
     * Muestra el formulario de edición para una PO existente.
     * 창고 미지정 사유 등으로 반려된 PO를 수정할 수 있도록 창고 목록을 추가로 로드합니다.
     */
    public function edit($id)
    {
        // 1. Cargar modelos necesarios
        $this->load->model('purchase_model');
        $this->load->model('product_model');
        $this->load->model('entity_model');
        $this->load->model('mapping_model');

        // 2. Obtener datos de la PO (Cabecera e Ítems)
        // get_po_header는 이미 warehouses 테이블과 JOIN되도록 수정되었습니다.
        $po = $this->purchase_model->get_po_header($id);
        
        // Seguridad: Si la PO no existe, redirigir
        if (!$po) {
            show_404();
        }

        // 3. Cargar datos para los selectores (Dropdowns)
        $data['suppliers'] = $this->entity_model->get_entities_by_role('is_vendor');

        // [추가] 목적지 창고 리스트 로드 (is_active인 항목만)
        $data['warehouses'] = $this->db->get_where('warehouses', ['is_active' => 1])->result();

        // Mappings 데이터 로드
        $data['po_types']         = $this->mapping_model->get_list('po_type');
        $data['currencies']       = $this->mapping_model->get_list('currency');
        $data['incoterms']        = $this->mapping_model->get_list('incoterms');
        $data['shipping_methods'] = $this->mapping_model->get_list('shipping_method');
        $data['payment_terms']    = $this->mapping_model->get_list('payment_terms');

        // 4. Datos específicos de la PO para la vista
        $data['po']       = $po;
        $data['po_items'] = $this->purchase_model->get_po_items($id);
        
        // 뷰의 <select>와 Template에서 사용하는 전체 제품 리스트
        $data['items'] = $this->product_model->get_products_with_items();

        // 5. Configurar el Layout
        $data['main'] = 'purchase/edit'; 
        $this->load->view('layout', $data);
    }

	/**
     * 수정 데이터 저장 및 재기안
     */
    public function update($id) 
    {
        // 1. Obtener los datos enviados por POST
        $post_data = $this->input->post();
        
        // 2. Obtener el ID del estado 'Registrado' (Pendiente)
        $registered_status_id = $this->mapping_model->get_id_by_code('po_status', 'Registrado');
        
        // 3. Preparar los datos de la cabecera (Header)
        $header = [
            'supplier_id'      => $post_data['supplier_id'],
            'warehouse_id'     => $post_data['warehouse_id'], // [추가] 선택한 창고 ID 반영
            'po_type'          => $post_data['po_type'],
            'currency'         => $post_data['currency'],
            'incoterms'        => $post_data['incoterms'],
            'payment_terms'    => $post_data['payment_terms'],
            'shipping_method'  => $post_data['shipping_method'],
            'issue_date'       => $post_data['issue_date'],
            'expected_date'    => $post_data['expected_date'],
            'notes'            => $post_data['notes'],
            'status'           => $registered_status_id, // Volver al flujo de aprobación
            'approved_by'      => NULL,                  // Resetear info de aprobación previa
            'approved_at'      => NULL,
            'approver_comment' => NULL                   // Limpiar el motivo del rechazo anterior
        ];

        // 4. Ejecutar la actualización completa (Cabecera e Ítems)
        if ($this->purchase_model->update_po_full($id, $header, $post_data['items'])) {
            
            // Mensaje de éxito
            $success_msg = "La Orden de Compra #{$post_data['po_number']} ha sido actualizada y re-enviada para su aprobación.";
            $this->session->set_flashdata('success', $success_msg);
            
            redirect('purchase/view/' . $id);
        } else {
            show_error("Se ha producido un error al intentar actualizar la Orden de Compra.");
        }
    }

}