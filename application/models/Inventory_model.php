<?php
class Inventory_model extends CI_Model {

    /**
     * Obtiene el stock actual filtrando por almacén y nombre de producto
     * (DB English fields / Logic Spanish comments)
     */
	public function get_current_stock($warehouse_id = null, $product_name = null) {
		// select 문 내부에 SQL 주석(--)이 포함되지 않도록 주의하세요.
		$this->db->select('
			i.*, 
			w.name as warehouse_name, 
			p.name as product_base_name, 
			pi.option as item_option, 
			pi.barcode,
			m.display_name as status_name
		');
		
		$this->db->from('inventory i');
		
		// 조인 설정
		$this->db->join('warehouses w', 'i.warehouse_id = w.id');
		$this->db->join('product_items pi', 'i.item_id = pi.id');
		$this->db->join('products p', 'pi.product_id = p.id');
		
		// mappings 테이블과 조인 (stock_status_id 기준)
		$this->db->join('mappings m', 'i.stock_status_id = m.id', 'left');

		if ($warehouse_id) {
			$this->db->where('i.warehouse_id', $warehouse_id);
		}

		if ($product_name) {
			$this->db->like('p.name', $product_name);
		}

		$this->db->order_by('w.name', 'ASC');
		$this->db->order_by('p.name', 'ASC');
		
		return $this->db->get()->result();
	}
	
	/**
	 * Obtiene el reporte de Kardex filtrado por almacén y rango de fechas
	 */
	public function get_kardex_report($warehouse_id = null, $start_date = null, $end_date = null) {
		$this->db->select('
			l.*, 
			w.name as warehouse_name, 
			p.name as product_base_name, 
			pi.option as item_option,
			m.display_name as status_name
		');
		$this->db->from('inventory_logs l');
		
		$this->db->join('warehouses w', 'l.warehouse_id = w.id');
		$this->db->join('product_items pi', 'l.item_id = pi.id');
		$this->db->join('products p', 'pi.product_id = p.id');
		// stock_status_id 필드를 기준으로 mappings 테이블 조인
		$this->db->join('mappings m', 'l.stock_status_id = m.id', 'left');

		if (!empty($warehouse_id)) {
			$this->db->where('l.warehouse_id', $warehouse_id);
		}

		if (!empty($start_date)) {
			$this->db->where('DATE(l.created_at) >=', $start_date);
		}
		if (!empty($end_date)) {
			$this->db->where('DATE(l.created_at) <=', $end_date);
		}

		$this->db->order_by('l.created_at', 'DESC');
		$this->db->order_by('l.id', 'DESC');

		return $this->db->get()->result();
	}
	
	/**
     * 재고 동기화 및 로그 기록 (공통 메서드)
     */
	public function sync_stock($warehouse_id, $item_id, $qty_change, $status_id, $bin, $type, $ref_id = NULL) {
    // 세션에서 현재 사용자 ID 가져오기
    $user_id = $this->session->userdata('user_id') ?: 1;

    // 1. Inventory 테이블 UPSERT (updated_by 필드 추가)
    $sql = "INSERT INTO inventory (warehouse_id, item_id, stock_status_id, quantity, bin_location, updated_at, updated_by)
            VALUES (?, ?, ?, ?, ?, NOW(), ?)
            ON DUPLICATE KEY UPDATE 
            quantity = quantity + VALUES(quantity),
            bin_location = VALUES(bin_location),
            updated_at = NOW(),
            updated_by = VALUES(updated_by)"; // 업데이트 시에도 사용자 ID 기록
    
    $this->db->query($sql, [$warehouse_id, $item_id, $status_id, $qty_change, $bin, $user_id]);

    // 2. 변동 후 수량 확인 (로그 기록 및 마이너스 재고 방지용)
    $current_inv = $this->db->get_where('inventory', [
        'warehouse_id' => $warehouse_id, 
        'item_id' => $item_id, 
        'stock_status_id' => $status_id
    ])->row();

    // 3. 안전장치: 재고가 마이너스가 되면 0으로 보정
    if ($current_inv && $current_inv->quantity < 0) {
        $this->db->where('id', $current_inv->id)->update('inventory', [
            'quantity' => 0,
            'updated_by' => $user_id
        ]);
    }

    // 4. Inventory Logs 기록
    $log_data = [
        'warehouse_id'    => $warehouse_id,
        'item_id'         => $item_id,
        'stock_status_id' => $status_id,
        'type'            => $type,
        'reference_id'    => $ref_id,
        'qty_before'      => ($current_inv ? $current_inv->quantity : 0) - $qty_change,
        'qty_change'      => $qty_change,
        'qty_after'       => ($current_inv ? $current_inv->quantity : 0),
        'reason'          => "Transaction: $type (Ref: $ref_id)",
        'created_by'      => $user_id
    ];
    return $this->db->insert('inventory_logs', $log_data);
}

}