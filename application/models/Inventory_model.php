<?php
class Inventory_model extends CI_Model {

    /**
     * Obtiene el stock actual filtrando por almacén y nombre de producto
     * (DB English fields / Logic Spanish comments)
     */
    public function get_current_stock($warehouse_id = null, $product_name = null) {
        // Seleccionamos campos usando la nueva estructura de items
        $this->db->select('
            i.*, 
            w.name as warehouse_name, 
            p.name as product_base_name, 
            pi.option as item_option, 
            pi.barcode
        ');
        
        $this->db->from('inventory i');
        
        // 1. Unir con almacenes
        $this->db->join('warehouses w', 'i.warehouse_id = w.id');
        
        // 2. Unir con product_items (La unidad real de stock)
        $this->db->join('product_items pi', 'i.item_id = pi.id');
        
        // 3. Unir con products (Para obtener el nombre base del producto)
        $this->db->join('products p', 'pi.product_id = p.id');

        // Aplicar filtros si existen
        if ($warehouse_id) {
            $this->db->where('i.warehouse_id', $warehouse_id);
        }

        if ($product_name) {
            $this->db->like('p.name', $product_name);
        }

        // Ordenar por almacén y luego por nombre de producto
        $this->db->order_by('w.name', 'ASC');
        $this->db->order_by('p.name', 'ASC');
        
        return $this->db->get()->result();
    }
	
	/**
	 * Obtiene el reporte de Kardex filtrado por almacén y rango de fechas
	 */
	public function get_kardex_report($warehouse_id = null, $start_date = null, $end_date = null) {
		// Definir campos a seleccionar (Campos en Inglés)
		$this->db->select('
			l.*, 
			w.name as warehouse_name, 
			p.name as product_base_name, 
			pi.option as item_option
		');
		$this->db->from('inventory_logs l');
		
		// Joins para obtener nombres legibles
		$this->db->join('warehouses w', 'l.warehouse_id = w.id');
		$this->db->join('product_items pi', 'l.item_id = pi.id');
		$this->db->join('products p', 'pi.product_id = p.id');

		// Filtro por Almacén
		if (!empty($warehouse_id)) {
			$this->db->where('l.warehouse_id', $warehouse_id);
		}

		// Filtro por Rango de Fechas (Formato YYYY-MM-DD)
		if (!empty($start_date)) {
			$this->db->where('DATE(l.created_at) >=', $start_date);
		}
		if (!empty($end_date)) {
			$this->db->where('DATE(l.created_at) <=', $end_date);
		}

		// Ordenar por fecha descendente (Lo más reciente primero)
		$this->db->order_by('l.created_at', 'DESC');
		$this->db->order_by('l.id', 'DESC');

		return $this->db->get()->result();
	}

}