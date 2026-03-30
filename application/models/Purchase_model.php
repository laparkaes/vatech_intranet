<?php
class Purchase_model extends CI_Model {

    /**
     * Obtiene todas las PO con el nombre del proveedor
     */
    public function get_all_pos() {
        $this->db->select('po.*, e.name as supplier_name');
        $this->db->from('purchase_orders po');
        $this->db->join('entities e', 'po.supplier_id = e.id');
        $this->db->order_by('po.created_at', 'DESC');
        return $this->db->get()->result();
    }
	
	/**
     * Guarda la PO y sus ítems, incluyendo la fecha de entrega específica por producto
     */
    public function save_full_po($po_data, $items) {
        $this->db->trans_start();

        // A. Insertar Encabezado
        $this->db->insert('purchase_orders', $po_data);
        $po_id = $this->db->insert_id();

        $total_order_amount = 0;

        // B. Insertar Detalles con validación de existencia
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

        // C. Actualizar monto total final
        $this->db->where('id', $po_id);
        $this->db->update('purchase_orders', ['total_amount' => $total_order_amount]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Obtiene el encabezado detallado de una PO
     */
    public function get_po_header($id) {
        $this->db->select('po.*, e.name as supplier_name, e.tax_id as supplier_tax_id, 
                           u1.full_name as creator_name, u2.full_name as approver_name');
        $this->db->from('purchase_orders po');
        $this->db->join('entities e', 'po.supplier_id = e.id');
        $this->db->join('users u1', 'po.created_by = u1.id', 'left');
        $this->db->join('users u2', 'po.approved_by = u2.id', 'left');
        $this->db->where('po.id', $id);
        return $this->db->get()->row();
    }

    /**
     * Actualiza el estado de la PO (Valores: Borrador, Aprobado, Rechazado, etc.)
     */
    public function update_po_status($id, $status, $user_id) {
        if (empty($status)) return FALSE;

        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Solo si es Aprobado o Rechazado se registra la auditoría
        if ($status === 'Aprobado' || $status === 'Rechazado') {
            $data['approved_by'] = $user_id;
            $data['approved_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where('id', $id);
        return $this->db->update('purchase_orders', $data);
    }
	
	/**
	 * Obtiene los ítems detallados de una Orden de Compra específica.
	 * Ajustado según el nombre de columna real 'po_id' en la tabla 'purchase_order_items'.
	 * * @param int $id ID de la Orden de Compra (po_id)
	 * @return array Lista de objetos con los detalles completos del producto
	 */
	public function get_po_items($id) {
		// Selección de campos con alias para la vista
		$this->db->select('
			poi.*, 
			p.name as product_name, 
			pi.option as product_option,
			p.code as product_code
		');
		
		$this->db->from('purchase_order_items poi');
		
		// JOIN con la tabla de variantes (product_items)
		$this->db->join('product_items pi', 'poi.item_id = pi.id');
		
		// JOIN con la tabla maestra de productos (products)
		$this->db->join('products p', 'pi.product_id = p.id');
		
		// Se cambia 'purchase_order_id' por 'po_id' según la estructura de la DB
		$this->db->where('poi.po_id', $id);
		
		return $this->db->get()->result();
	}

}