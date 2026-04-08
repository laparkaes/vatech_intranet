<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('sale_model');
        $this->load->model('mapping_model');
    }

    /**
     * 1. Listado de Ventas (판매 목록 조회)
     */
    public function index() {
        $data['list'] = $this->sale_model->get_sale_list();
        
        // Cargar vista con layout
        $data['main'] = 'sale/list';
        $this->load->view('layout', $data);
    }

	public function create() {
		// 1. Entity_model 로드 및 대리점 목록 가져오기
		$this->load->model('entity_model');
		// 모델에 이미 정의된 get_entities_by_role 활용
		$data['customers'] = $this->entity_model->get_entities_by_role('is_dealer');

		// 2. Mappings에서 통화 목록 가져오기
		$data['currencies'] = $this->mapping_model->get_list('currency');
		
		// 3. 창고 및 재고 포함 제품 목록 (Sale_model 활용)
		$this->load->model('sale_model');
		$data['warehouses'] = $this->db->get_where('warehouses', ['is_active' => 1])->result(); 
		$data['products'] = $this->sale_model->get_products_with_stock();

		$data['main'] = 'sale/create';
		$this->load->view('layout', $data);
	}

	public function add() {
		$status_pending = $this->mapping_model->get_id_by_code('sales_status', 'PENDING');

		$header = [
			'sales_number'       => 'SL-' . date('YmdHis'),
			'customer_entity_id' => $this->input->post('customer_id'),
			'warehouse_id'       => $this->input->post('warehouse_id'),
			'status_id'          => $status_pending,
			'currency_id'        => $this->input->post('currency_id'),
			'exchange_rate'      => $this->input->post('exchange_rate') ?: 1.0000,
			'total_amount'       => $this->input->post('grand_total'),
			'sales_date'         => $this->input->post('sales_date'),
			'notes'              => $this->input->post('notes'),
			'created_by'         => $this->session->userdata('user_id') ?: 1,
			'created_at'         => date('Y-m-d H:i:s')
		];

		$items = [];
		foreach ($this->input->post('items') as $val) {
			$items[] = [
				'item_id'    => $val['item_id'],
				'unit_price' => $val['unit_price'],
				'quantity'   => $val['qty']
			];
		}

		// 모델 하나만 호출하여 모든 프로세스(Sales + Outbound) 처리
		if ($this->sale_model->save_sale_with_outbound($header, $items)) {
			$this->session->set_flashdata('success', 'Venta y despacho registrados con éxito.');
			redirect('sale');
		} else {
			$this->session->set_flashdata('error', 'Error al procesar la operación.');
			redirect('sale/create');
		}
	}
	
	public function view($id) {
		$this->load->model('sale_model');
		
		$data['sale'] = $this->sale_model->get_sale_by_id($id);
		if (!$data['sale']) {
			show_404();
		}
		
		$data['items'] = $this->sale_model->get_sale_items($id);
		$data['main'] = 'sale/view';
		$this->load->view('layout', $data);
	}
	
	public function cancel($id) {
		$this->load->model('sale_model');
		
		// 1. 매출 정보 확인
		$sale = $this->sale_model->get_sale_by_id($id);
		if (!$sale) {
			show_404();
		}

		// 2. 이미 취소된 상태(ID: 41)인지 체크
		if ($sale->status_id == 41) {
			$this->session->set_flashdata('error', 'Esta venta ya ha sido anulada.');
			redirect('sale/view/'.$id);
		}

		// 3. 상태 업데이트 (실제 mappings 테이블의 ID인 41 사용)
		$update_data = [
			'status_id' => 41, // 'CANCELLED' 상태의 실제 ID
			'updated_by' => 1, 
			'updated_at' => date('Y-m-d H:i:s')
		];

		if ($this->db->update('sales', $update_data, ['id' => $id])) {
			$this->session->set_flashdata('success', 'La venta ha sido anulada correctamente.');
		} else {
			$this->session->set_flashdata('error', 'Error al intentar anular la venta.');
		}

		redirect('sale/view/'.$id);
	}

}