<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para la Gestión de Órdenes de Compra (Purchase Orders)
 * Utiliza referencias a la tabla 'mappings' por ID para mayor integridad.
 */
class Purchase_model extends CI_Model {

    /**
     * Obtiene todas las PO con nombres desde mappings
     */
	public function get_all_pos() {
		$this->db->select('
			po.*, 
			e.name as supplier_name, 
			m_status.display_name as status_name,
			m_status.code_value as status_code, 
			m_currency.display_name as currency_name,
			m_type.display_name as po_type_name
		');
		$this->db->from('purchase_orders po');
		$this->db->join('entities e', 'po.supplier_id = e.id');
		
		// Mappings Join 세트
		$this->db->join('mappings m_status', 'po.status = m_status.id', 'left');
		$this->db->join('mappings m_currency', 'po.currency = m_currency.id', 'left');
		$this->db->join('mappings m_type', 'po.po_type = m_type.id', 'left');
		
		$this->db->order_by('po.created_at', 'DESC');
		return $this->db->get()->result();
	}
    
    /**
     * Guarda la cabecera de la PO y sus ítems detallados dentro de una transacción.
     * @param array $po_data Datos de la cabecera (incluye IDs de mappings).
     * @param array $items Lista de ítems a comprar.
     * @return bool Estado de la transacción.
     */
    public function save_full_po($po_data, $items) {
        $this->db->trans_start();

        // A. Insertar Encabezado de la PO
        $this->db->insert('purchase_orders', $po_data);
        $po_id = $this->db->insert_id();

        $total_order_amount = 0;

        // B. Insertar Detalles con validación de existencia del ítem
        if (!empty($items)) {
            foreach ($items as $item) {
                $exists = $this->db->get_where('product_items', ['id' => $item['item_id']])->num_rows();
                
                if ($exists > 0) {
                    $subtotal = $item['quantity'] * $item['unit_price'];
                    $total_order_amount += $subtotal;

                    $detail_data = [
                        'po_id'         => $po_id,
                        'item_id'       => $item['item_id'],
                        'quantity'      => $item['quantity'],
                        'unit_price'    => $item['unit_price'],
                        'delivery_date' => !empty($item['delivery_date']) ? $item['delivery_date'] : $po_data['expected_date']
                    ];
                    $this->db->insert('purchase_order_items', $detail_data);
                } else {
                    $this->db->trans_rollback();
                    return false;
                }
            }
        }

        // C. Actualizar el monto total calculado en la cabecera
        $this->db->where('id', $po_id);
        $this->db->update('purchase_orders', ['total_amount' => $total_order_amount]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Obtiene la información completa de una PO específica mediante JOINs múltiples.
     * @param int $id ID de la Orden de Compra.
     * @return object Datos detallados del encabezado.
     */
    public function get_po_header($id) {
		$this->db->select('
			po.*, 
			e.name as supplier_name, 
			e.tax_id as supplier_tax_id,
			u.full_name as creator_name,
			app.full_name as approver_name,
			m_status.display_name as status_name,
			m_status.code_value as status_code,
			m_currency.display_name as currency_name,
			m_type.display_name as po_type_name,
			m_incoterms.display_name as incoterms_name,
			m_shipping.display_name as shipping_name,
			m_payment.display_name as payment_name
		');
		$this->db->from('purchase_orders po');
		$this->db->join('entities e', 'po.supplier_id = e.id', 'left');
		$this->db->join('users u', 'po.created_by = u.id', 'left');
		$this->db->join('users app', 'po.approved_by = app.id', 'left');
		
		/* Mappings Join 세트 */
		$this->db->join('mappings m_status', 'po.status = m_status.id', 'left');
		$this->db->join('mappings m_currency', 'po.currency = m_currency.id', 'left');
		$this->db->join('mappings m_type', 'po.po_type = m_type.id', 'left');
		$this->db->join('mappings m_incoterms', 'po.incoterms = m_incoterms.id', 'left');
		$this->db->join('mappings m_shipping', 'po.shipping_method = m_shipping.id', 'left');
		$this->db->join('mappings m_payment', 'po.payment_terms = m_payment.id', 'left');
		
		$this->db->where('po.id', $id);
		return $this->db->get()->row();
	}
	
    /**
     * Actualiza el estado de la PO y registra la auditoría si es aprobación o rechazo.
     * @param int $id ID de la PO.
     * @param int $status_id ID del mapeo de estado.
     * @param int $user_id ID del usuario que realiza la acción.
     * @return bool Resultado de la actualización.
     */
    public function update_po_status($id, $status_id, $user_id, $comment = '') {
		$data = [
			'status' => $status_id,
			'approved_by' => $user_id,
			'approved_at' => date('Y-m-d H:i:s'),
			'approver_comment' => $comment // DB에 이 컬럼을 추가하시면 좋습니다.
		];
		
		$this->db->where('id', $id);
		return $this->db->update('purchase_orders', $data);
	}
    
    /**
     * Obtiene los ítems detallados vinculados a una PO.
     * @param int $id ID de la PO.
     * @return array Lista de productos asociados.
     */
    public function get_po_items($id) {
        $this->db->select('poi.*, p.name as product_name, pi.option as product_option, p.code as product_code');
        $this->db->from('purchase_order_items poi');
        $this->db->join('product_items pi', 'poi.item_id = pi.id');
        $this->db->join('products p', 'pi.product_id = p.id');
        $this->db->where('poi.po_id', $id);
        return $this->db->get()->result();
    }

	public function update_po_full($id, $header, $items) {
		$this->db->trans_start();

		// 1. 헤더 업데이트
		$this->db->where('id', $id);
		$this->db->update('purchase_orders', $header);

		// 2. 기존 상세 아이템 삭제
		$this->db->where('po_id', $id);
		$this->db->delete('purchase_order_items');

		// 3. 신규 아이템 삽입 및 총액 계산
		$total_amount = 0;
		foreach ($items as $item) {
			$subtotal = $item['quantity'] * $item['unit_price'];
			$total_amount += $subtotal;

			$this->db->insert('purchase_order_items', [
				'po_id'         => $id,
				'item_id'       => $item['item_id'],
				'quantity'      => $item['quantity'],
				'unit_price'    => $item['unit_price'],
				'delivery_date' => $item['delivery_date']
			]);
		}

		// 4. 총액 다시 업데이트
		$this->db->where('id', $id);
		$this->db->update('purchase_orders', ['total_amount' => $total_amount]);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

}