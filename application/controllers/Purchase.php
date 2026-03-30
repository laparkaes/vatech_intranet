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
        // Obtener proveedores activos (Entities)
        $data['suppliers'] = $this->db->get_where('entities', [
            'is_vendor' => 1, 
            'status'    => 1
        ])->result();
        
        // Obtener ítems de productos disponibles para la selección
        $this->db->select('pi.id, p.name, pi.option');
        $this->db->from('product_items pi');
        $this->db->join('products p', 'pi.product_id = p.id');
        $data['items'] = $this->db->get()->result();

        // Carga de catálogos dinámicos desde Mapping_model
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

        // Validaciones de servidor
        $this->form_validation->set_rules('supplier_id', 'Proveedor', 'required|numeric');
        $this->form_validation->set_rules('issue_date', 'Fecha de Emisión', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->create(); 
        } else {
            // Obtener el ID del estado inicial 'Registrado' desde el modelo de mapeo
            $initial_status_id = $this->mapping_model->get_id_by_code('po_status', 'Registrado');

            $po_data = [
                'po_number'       => 'VPR-PO-' . date('Ymd-His'), 
                'supplier_id'     => $this->input->post('supplier_id'),
                'po_type'         => $this->input->post('po_type'),       // ID del mapping
                'currency'        => $this->input->post('currency'),      // ID del mapping
                'incoterms'       => $this->input->post('incoterms'),     // ID del mapping
                'payment_terms'   => $this->input->post('payment_terms'), // ID del mapping
                'shipping_method' => $this->input->post('shipping_method'), // ID del mapping
                'issue_date'      => $this->input->post('issue_date'),
                'expected_date'   => $this->input->post('expected_date'),
                'notes'           => $this->input->post('notes'),
                'status'          => $initial_status_id,
                'created_by'      => $this->session->userdata('user_id') ?: 1
            ];

            $items = $this->input->post('items');

            // Ejecutar guardado mediante transacción en el modelo
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
	 * 통합 상태 업데이트 (Aprobar/Rechazar/Cancelar)
	 */
	public function update_status($id) {
		$current_user_id = $this->session->userdata('user_id');
		$user_role = $this->session->userdata('role');

		$po = $this->purchase_model->get_po_header($id);
		if (!$po) show_404();

		// 뷰에서 넘겨준 'Aprobado', 'Rechazado', 'Cancelado' 등의 code_value
		$status_code = $this->input->post('status_code');
		$comment = $this->input->post('comment'); // 반려 사유 등 코멘트

		// DB ID 조회를 위해 Mapping 모델 활용
		$status_id = $this->mapping_model->get_id_by_code('po_status', $status_code);
		if (!$status_id) show_error("Estado inválido.");

		// 권한 체크 로직 통합
		$is_admin = ($user_role === 'admin');
		$is_logistics = ($user_role === 'logistics');
		$is_creator = ($po->created_by == $current_user_id);

		$can_process = false;
		if ($status_code === 'Cancelado') {
			// 취소는 본인 또는 관리자만
			if ($is_creator || $is_admin) $can_process = true;
		} else {
			// 승인/반려는 관리자 또는 본인이 아닌 물류팀만
			if ($is_admin || ($is_logistics && !$is_creator)) $can_process = true;
		}

		if ($can_process) {
			// 모델 업데이트 시 comment도 같이 넘기도록 구성 (아래 모델 코드 참고)
			if ($this->purchase_model->update_po_status($id, $status_id, $current_user_id, $comment)) {
				$this->session->set_flashdata('success', "La orden ha sido actualizada a: $status_code");
			} else {
				$this->session->set_flashdata('error', "Error al actualizar la base de datos.");
			}
		} else {
			show_error("Acceso denegado: No tiene permisos.", 403);
		}

		redirect('purchase/view/' . $id);
	}

	/**
	 * Muestra el formulario para editar y re-enviar una PO rechazada o cancelada.
	 * Utiliza el layout maestro del proyecto.
	 */
	public function edit($id)
	{
		// 1. Cargar modelos necesarios
		$this->load->model('purchase_model');
		$this->load->model('product_model');
		$this->load->model('entity_model');
		$this->load->model('mapping_model');

		// 2. Obtener datos de la PO (Cabecera e Ítems)
		$po = $this->purchase_model->get_po_header($id);
		
		// Seguridad: Si la PO no existe, redirigir
		if (!$po) {
			show_404();
		}

		// 3. Cargar datos para los selectores (Dropdowns)
		$data['suppliers'] = $this->entity_model->get_entities_by_role('is_vendor');

		// DB 테이블의 category 컬럼값과 정확히 일치시켜야 합니다.
		$data['po_types']         = $this->mapping_model->get_list('po_type');        // 확인됨
		$data['currencies']       = $this->mapping_model->get_list('currency');       // 확인됨
		$data['incoterms']        = $this->mapping_model->get_list('incoterms');      // 's' 추가됨
		$data['shipping_methods'] = $this->mapping_model->get_list('shipping_method'); // 확인됨
		$data['payment_terms']    = $this->mapping_model->get_list('payment_terms');    // 's' 추가됨

		// 4. Datos específicos de la PO para la vista
		$data['po']       = $po;
		$data['po_items'] = $this->purchase_model->get_po_items($id);
		
		// 뷰의 <select>와 Template에서 사용하는 전체 제품 리스트
		// product_model에서 가져온 데이터를 뷰의 루프 구조에 맞게 전달합니다.
		$data['items'] = $this->product_model->get_products_with_items();

		// 5. Configurar el Layout (팀장님 스타일: 'main' 변수 활용)
		$data['main'] = 'purchase/edit'; // Carga application/views/purchase/edit.php
		$this->load->view('layout', $data);
	}

	/**
	 * 수정 데이터 저장 및 재기안
	 */
	public function update($id) 
	{
		// 1. Obtener los datos enviados por POST
		$post_data = $this->input->post();
		
		// 2. Obtener el ID del estado 'Registrado' para el flujo de re-aprobación
		// Al editar una PO rechazada, el estado debe volver a 'Registrado' (Pendiente)
		$registered_status_id = $this->mapping_model->get_id_by_code('po_status', 'Registrado');
		
		// 3. Preparar los datos de la cabecera (Header)
		$header = [
			'supplier_id'      => $post_data['supplier_id'],
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
		// Se recomienda que update_po_full elimine los ítems antiguos e inserte los nuevos
		if ($this->purchase_model->update_po_full($id, $header, $post_data['items'])) {
			
			// Mensaje de éxito para el usuario
			$success_msg = "La Orden de Compra #{$post_data['po_number']} ha sido actualizada y re-enviada para su aprobación.";
			$this->session->set_flashdata('success', $success_msg);
			
			redirect('purchase/view/' . $id);
		} else {
			// En caso de error técnico
			show_error("Se ha producido un error al intentar actualizar la Orden de Compra.");
		}
	}

}