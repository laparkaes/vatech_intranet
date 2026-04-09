<?php
class Outbound_model extends CI_Model {

	public function get_outbound_by_id($id) {
		$this->db->select('
			o.*, 
			m.display_name as status_name, 
			w.name as warehouse_name, 
			s.sales_number, 
			e.name as customer_name,
			u.full_name as creator_name
		');
		$this->db->from('outbounds o');
		$this->db->join('mappings m', 'o.status_id = m.id', 'left');
		$this->db->join('warehouses w', 'o.warehouse_id = w.id', 'left');
		$this->db->join('sales s', 'o.sales_id = s.id', 'left');
		$this->db->join('entities e', 's.customer_entity_id = e.id', 'left');
		$this->db->join('users u', 'o.created_by = u.id', 'left');
		$this->db->where('o.id', $id);
		return $this->db->get()->row();
	}

	public function get_outbound_items($outbound_id) {
		$this->db->select('oi.*, p.name as product_name, pi.option as item_option, pi.barcode, m.display_name as item_status_name');
		$this->db->from('outbound_items oi');
		$this->db->join('product_items pi', 'oi.item_id = pi.id', 'left');
		$this->db->join('products p', 'pi.product_id = p.id', 'left');
		$this->db->join('mappings m', 'oi.item_status_id = m.id', 'left');
		$this->db->where('oi.outbound_id', $outbound_id);
		return $this->db->get()->result();
	}

	public function get_outbound_list($warehouse_id = null, $status_id = null) {
		$this->db->select('
			o.*, 
			m.display_name as status_name, 
			w.name as warehouse_name, 
			s.sales_number, 
			e.name as customer_name,
			u.full_name as creator_name
		');
		$this->db->from('outbounds o');
		$this->db->join('mappings m', 'o.status_id = m.id', 'left');
		$this->db->join('warehouses w', 'o.warehouse_id = w.id', 'left');
		$this->db->join('sales s', 'o.sales_id = s.id', 'left');
		$this->db->join('entities e', 's.customer_entity_id = e.id', 'left');
		$this->db->join('users u', 'o.created_by = u.id', 'left');

		if ($warehouse_id) {
			$this->db->where('o.warehouse_id', $warehouse_id);
		}
		if ($status_id) {
			$this->db->where('o.status_id', $status_id);
		}

		$this->db->order_by('o.created_at', 'DESC');
		return $this->db->get()->result();
	}

    // 1. 매출 데이터를 기반으로 Outbound 레코드 생성
    public function create_from_sale($sale_id, $items) {
        $sale = $this->db->get_where('sales', ['id' => $sale_id])->row();

        // 출고 마스터 생성
        $outbound_data = [
            'outbound_number' => str_replace('SL', 'OUT', $sale->sales_number),
            'status_id'       => 1, // 예: PENDING 상태 ID
            'sales_id'        => $sale_id,
            'warehouse_id'    => $sale->warehouse_id,
            'created_by'      => $sale->created_by
        ];
        $this->db->insert('outbounds', $outbound_data);
        $outbound_id = $this->db->insert_id();

        // 출고 품목 생성
        foreach ($items as $item) {
            $this->db->insert('outbound_items', [
                'outbound_id'    => $outbound_id,
                'item_id'        => $item['item_id'],
                'quantity'       => $item['quantity'],
                'item_status_id' => 34 // Available 상태 ID (프로젝트 기준에 맞춰 수정)
            ]);
        }
        return $outbound_id;
    }

    // 2. 실제 출고 처리 (재고 차감 및 로그 기록)
    public function ship_outbound($outbound_id) {
        $this->db->trans_start();

        $items = $this->db->get_where('outbound_items', ['outbound_id' => $outbound_id])->result();
        $outbound = $this->db->get_where('outbounds', ['id' => $outbound_id])->row();

        foreach ($items as $item) {
            // inventory 테이블 재고 차감
            $this->db->set('quantity', 'quantity - ' . (int)$item->quantity, FALSE);
            $this->db->where([
                'warehouse_id'    => $outbound->warehouse_id,
                'item_id'         => $item->item_id,
                'stock_status_id' => $item->item_status_id
            ]);
            $this->db->update('inventory');

            // inventory_logs 기록 (Kardex 반영)
            // 기존에 작성하신 log 기록 함수를 여기서 호출하세요.
        }

        // 출고 상태 완료 업데이트
        $this->db->update('outbounds', ['status_id' => 2, 'shipment_date' => date('Y-m-d H:i:s')], ['id' => $outbound_id]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}