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
        $this->db->select('pi.id, p.name, pi.option, p.brand, 
                           IFNULL(SUM(CASE WHEN inv.stock_status = "Available" THEN inv.quantity ELSE 0 END), 0) as available_stock');
        $this->db->from('product_items pi');
        $this->db->join('products p', 'pi.product_id = p.id', 'left');
        $this->db->join('inventory inv', 'pi.id = inv.item_id', 'left');
        $this->db->group_by('pi.id');
        $this->db->where('p.is_active', 1);
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
}