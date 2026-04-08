<?php
class Inbound_model extends CI_Model {

    /**
     * 입고 목록 조회 (창고명 및 상태 명칭 포함)
     */
    public function get_inbound_list() {
        $this->db->select('i.*, m1.display_name as source_type_name, m2.display_name as status_name, w.name as warehouse_name');
        $this->db->from('inbounds i');
        $this->db->join('mappings m1', 'i.source_type_id = m1.id', 'left');
        $this->db->join('mappings m2', 'i.status_id = m2.id', 'left');
        $this->db->join('warehouses w', 'i.warehouse_id = w.id', 'left');
        // 소프트 삭제된 항목 제외 (mappings 테이블에 DELETED 상태가 있다고 가정)
        $this->db->where('m2.code_value !=', 'DELETED'); 
        $this->db->order_by('i.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * 신규 입고 등록 (트랜잭션 적용)
     */
    public function save_inbound($header, $items) {
        $this->db->trans_start();
        
        $this->db->insert('inbounds', $header);
        $inbound_id = $this->db->insert_id();

        foreach ($items as $item) {
            $item['inbound_id'] = $inbound_id;
            $this->db->insert('inbound_items', $item);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * 입고 상세 정보 조회 (헤더 + 아이템 목록)
     */
    public function get_inbound_full($id) {
		// 1. 헤더 정보 조회 (출처, 창고, 상태, 등록자 정보 포함)
		$this->db->select('
			i.*, 
			w.name as warehouse_name, 
			m.display_name as status_name, 
			m2.display_name as source_type_name, 
			u.full_name as creator_name
		');
		$this->db->from('inbounds i');
		$this->db->join('warehouses w', 'i.warehouse_id = w.id', 'left');
		$this->db->join('mappings m', 'i.status_id = m.id', 'left'); // 입고 상태
		$this->db->join('mappings m2', 'i.source_type_id = m2.id', 'left'); // 입고 출처
		$this->db->join('users u', 'i.created_by = u.id', 'left'); // 등록자 성함
		$this->db->where('i.id', $id);
		$header = $this->db->get()->row();

		if (!$header) return null;

		// 2. 아이템 상세 정보 조회 (제품명, 옵션, 아이템 상태, Bin 위치 포함)
		$this->db->select('
			ii.*, 
			p.name as product_name, 
			pi.option as product_option, 
			m.display_name as item_status_name
		');
		$this->db->from('inbound_items ii');
		$this->db->join('product_items pi', 'ii.item_id = pi.id', 'left');
		$this->db->join('products p', 'pi.product_id = p.id', 'left');
		$this->db->join('mappings m', 'ii.item_status_id = m.id', 'left');
		$this->db->where('ii.inbound_id', $id);
		$items = $this->db->get()->result();

		return ['header' => $header, 'items' => $items];
	}
	
	public function update_inbound_full($inbound_id, $header_data, $items_data) {
        $this->load->model('inventory_model');
        $this->load->model('mapping_model'); // 상태 ID 조회를 위해 로드
        
        // mappings 테이블에서 필요한 상태 ID 미리 정의
        $status_available = $this->mapping_model->get_id_by_code('inventory_status', 'AVAILABLE');
        $status_damaged   = $this->mapping_model->get_id_by_code('inventory_status', 'DAMAGED');

        $this->db->trans_start();

        // 1. 헤더 업데이트
        $this->db->where('id', $inbound_id);
        $this->db->update('inbounds', $header_data);

        // 2. 아이템별 처리
        foreach ($items_data as $item) {
            // 이전 데이터 확인 (차이량 계산용)
            $old_item = $this->db->get_where('inbound_items', ['id' => $item['id']])->row();
            
            // 수량 변동폭 계산
            $diff_received = (int)$item['received_qty'] - (int)$old_item->received_qty;
            $diff_damaged  = (int)$item['damaged_qty']  - (int)$old_item->damaged_qty;

            // 아이템 정보 업데이트 (inbound_items 테이블)
            $this->db->where('id', $item['id']);
            $this->db->update('inbound_items', $item);

            // 3. 재고 반영 (ID 기반 호출)
            
            // 정상 제품 재고 반영
            if ($diff_received !== 0) {
                $this->inventory_model->sync_stock(
                    $header_data['warehouse_id'], 
                    $old_item->item_id, 
                    $diff_received, 
                    $status_available, // ID 값 전달
                    $item['bin_location'], 
                    'Inbound', 
                    $inbound_id
                );
            }

            // 불량 제품 재고 반영
            if ($diff_damaged !== 0) {
                $this->inventory_model->sync_stock(
                    $header_data['warehouse_id'], 
                    $old_item->item_id, 
                    $diff_damaged, 
                    $status_damaged, // ID 값 전달
                    $item['bin_location'], 
                    'Inbound', 
                    $inbound_id
                );
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}