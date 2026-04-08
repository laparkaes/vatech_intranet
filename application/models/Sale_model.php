<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale_model extends CI_Model {

    public function get_sale_list() {
        $this->db->select('s.*, e.name as customer_name, w.name as warehouse_name, 
                           m.display_name as status_label, u.full_name as creator_name');
        $this->db->from('sales s');
        // 고객사 조인
        $this->db->join('entities e', 's.customer_entity_id = e.id', 'left');
        // 창고 조인
        $this->db->join('warehouses w', 's.warehouse_id = w.id', 'left');
        // 상태값 조인
        $this->db->join('mappings m', 's.status_id = m.id', 'left');
        // 생성자(사용자) 조인
        $this->db->join('users u', 's.created_by = u.id', 'left');
        
        $this->db->order_by('s.created_at', 'DESC');
        return $this->db->get()->result();
    }
	
	// 재고 정보가 포함된 제품 아이템 목록 조회
    public function get_products_with_stock() {
		// 현재 데이터베이스의 inventory 테이블 상 Available 상태 ID는 47로 확인됩니다.
		$this->db->select('
			pi.id, 
			p.name, 
			pi.option, 
			p.brand, 
			IFNULL(SUM(CASE WHEN inv.stock_status_id = 47 THEN inv.quantity ELSE 0 END), 0) as available_stock
		');
		$this->db->from('product_items pi');
		$this->db->join('products p', 'pi.product_id = p.id', 'left');
		$this->db->join('inventory inv', 'pi.id = inv.item_id', 'left');
		$this->db->where('p.is_active', 1);
		$this->db->group_by('pi.id');

		// 계산된 가용 재고가 0보다 큰 경우만 필터링
		$this->db->having('available_stock >', 0);
		
		return $this->db->get()->result();
	}

    // 판매 데이터 저장 (트랜잭션)
    public function save_sale($header, $items) {
        $this->db->trans_start();
        
        $this->db->insert('sales', $header);
        $sale_id = $this->db->insert_id();

        foreach ($items as $item) {
            $item['sales_id'] = $sale_id;
            $this->db->insert('sales_items', $item);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
	
	public function save_sale_with_outbound($header, $items) {
		$this->db->trans_start();

		// 1. 매출 마스터 저장
		$this->db->insert('sales', $header);
		$sale_id = $this->db->insert_id();

		// 2. 매출 상세 및 출고 아이템 준비
		$outbound_items = [];
		foreach ($items as $item) {
			// 매출 상세 저장
			$item['sales_id'] = $sale_id;
			$this->db->insert('sales_items', $item);

			// 출고 상세용 데이터 준비
			$outbound_items[] = [
				'item_id'        => $item['item_id'],
				'quantity'       => $item['quantity'],
				'item_status_id' => 47 // AVAILABLE (DB 실제 ID 사용)
			];
		}

		// 3. 아웃바운드 모델을 통해 출고 데이터 생성
		// (동일한 모델 내에서 호출하거나 outbound_model을 로드하여 사용)
		$this->load->model('outbound_model');
		$this->outbound_model->create_from_sale($sale_id, $outbound_items);

		$this->db->trans_complete();
		return ($this->db->trans_status() === FALSE) ? FALSE : $sale_id;
	}
	
	public function get_sale_by_id($id) {
		$this->db->select('
			s.*, 
			e.name as customer_name, 
			w.name as warehouse_name, 
			m.display_name as status_name, 
			cur.display_name as currency_name,
			u1.full_name as creator_name,
			u2.full_name as updater_name
		');
		$this->db->from('sales s');
		$this->db->join('entities e', 's.customer_entity_id = e.id', 'left');
		$this->db->join('warehouses w', 's.warehouse_id = w.id', 'left');
		$this->db->join('mappings m', 's.status_id = m.id', 'left');
		$this->db->join('mappings cur', 's.currency_id = cur.id', 'left');
		$this->db->join('users u1', 's.created_by = u1.id', 'left');
		$this->db->join('users u2', 's.updated_by = u2.id', 'left');
		$this->db->where('s.id', $id);
		return $this->db->get()->row();
	}

	public function get_sale_items($sale_id) {
		$this->db->select('si.*, p.name as product_name, pi.option as item_option, pi.barcode');
		$this->db->from('sales_items si');
		$this->db->join('product_items pi', 'si.item_id = pi.id', 'left');
		$this->db->join('products p', 'pi.product_id = p.id', 'left');
		$this->db->where('si.sales_id', $sale_id);
		return $this->db->get()->result();
	}
}