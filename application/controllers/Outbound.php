<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outbound extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('outbound_model');
		$this->load->model('mapping_model');
    }

    public function index() {
        // 필터용 데이터 (창고 목록 등)
        $data['warehouses'] = $this->db->get_where('warehouses', ['is_active' => 1])->result();
        
        // 목록 데이터 가져오기
        $warehouse_id = $this->input->get('warehouse_id');
        $data['list'] = $this->outbound_model->get_outbound_list($warehouse_id);

        $data['main'] = 'outbound/index';
        $this->load->view('layout', $data);
    }
	
	public function view($id) {
		$data['outbound'] = $this->outbound_model->get_outbound_by_id($id);
		if (!$data['outbound']) {
			show_404();
		}

		$data['items'] = $this->outbound_model->get_outbound_items($id);
		$data['main'] = 'outbound/view';
		$this->load->view('layout', $data);
	}
	
	public function confirm_shipment($id) {
		$this->load->model('outbound_model');
		$this->load->model('mapping_model');
		// 별도의 inventory_model이 없다면 아래와 같이 직접 DB 쿼리를 사용해도 무방합니다.

		// 1. 출고 마스터 정보 조회
		$outbound = $this->outbound_model->get_outbound_by_id($id);
		if (!$outbound || $outbound->status_id != 1) { // 1: PENDING
			$this->session->set_flashdata('error', 'La orden no existe o ya fue despachada.');
			redirect('outbound');
		}

		$ship_items = $this->input->post('items');
		$user_id = $this->session->userdata('user_id') ?: 1;
		
		// 트랜잭션 시작 (재고 차감 + 로그 기록 + 상태 변경을 하나의 묶음으로 처리)
		$this->db->trans_start();

		foreach ($ship_items as $post_item) {
			$qty_to_ship = (int)$post_item['ship_qty'];
			if ($qty_to_ship <= 0) continue;

			$item_id = $post_item['item_id'];
			$status_id = $post_item['status_id'];
			$warehouse_id = $outbound->warehouse_id;

			// [개선사항 1] 현재 재고 수량 확인 (qty_before)
			$current_inventory = $this->db->get_where('inventory', [
				'warehouse_id'    => $warehouse_id,
				'item_id'         => $item_id,
				'stock_status_id' => $status_id
			])->row();

			$qty_before = $current_inventory ? (int)$current_inventory->quantity : 0;
			$qty_after = $qty_before - $qty_to_ship;

			// [개선사항 2] 실제 재고 차감 (inventory 테이블)
			// 레코드가 없는 경우를 대비해 replace나 insert 로직을 쓸 수도 있으나, 
			// 출고는 반드시 재고가 있는 상태에서 진행되므로 update를 기본으로 합니다.
			$this->db->set('quantity', $qty_after);
			$this->db->where([
				'warehouse_id'    => $warehouse_id,
				'item_id'         => $item_id,
				'stock_status_id' => $status_id
			]);
			$this->db->update('inventory');

			// [개선사항 3] 상세 Kardex 로그 기록 (inventory_logs)
			$this->db->insert('inventory_logs', [
				'warehouse_id'    => $warehouse_id,
				'item_id'         => $item_id,
				'stock_status_id' => $status_id,
				'type'            => 'Outbound', // 업로드해주신 SQL의 enum('Inbound','Outbound'...)에 맞춤
				'reference_id'    => $id,
				'qty_before'      => $qty_before,
				'qty_change'      => -$qty_to_ship,
				'qty_after'       => $qty_after,
				'reason'          => 'Despacho de venta: ' . $outbound->sales_number,
				'created_by'      => $user_id,
				'created_at'      => date('Y-m-d H:i:s')
			]);

			// [개선사항 4] 실제 출하된 수량으로 outbound_items 업데이트
			$this->db->update('outbound_items', 
				['quantity' => $qty_to_ship], 
				['id' => $post_item['oi_id']]
			);
		}

		// 2. 전체 출고 건 상태를 SHIPPED로 변경
		$shipped_status_id = $this->mapping_model->get_id_by_code('outbound_status', 'SHIPPED');
		$this->db->update('outbounds', [
			'status_id'     => $shipped_status_id,
			'shipment_date' => date('Y-m-d H:i:s')
		], ['id' => $id]);

		$this->db->trans_complete();

		// 결과 알림
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Error al procesar el despacho en la base de datos.');
		} else {
			$this->session->set_flashdata('success', '¡Despacho confirmado! El inventario ha sido actualizado.');
		}

		redirect('outbound/view/' . $id);
	}

}