<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Obtener lista de productos con precios actualizados desde el historial
     * Se usa una subconsulta para traer el precio más reciente de cada ítem
     */
    public function get_products($limit = null, $offset = null, $search = array()) {
        $this->db->select('
            p.*, 
            pc.category_name, 
            u.full_name as editor_name,
            ph.purchase_price_usd, 
            ph.purchase_price_pen, 
            ph.sale_price_usd, 
            ph.sale_price_pen,
            ph.applied_rate
        ');
        $this->db->from('products p');
        $this->db->join('product_categories pc', 'p.category_id = pc.id', 'left');
        $this->db->join('users u', 'p.updated_by = u.id', 'left');
        
        /* Join con product_items */
        $this->db->join('product_items pi', 'p.id = pi.product_id', 'left');
        
        /* Join con el historial de precios para obtener solo el último registro */
        $this->db->join('product_price_history ph', 'ph.item_id = pi.id', 'left');
        
        /* Filtro para asegurar que solo traemos el historial más reciente por ítem */
        $this->db->where('(ph.id = (SELECT MAX(id) FROM product_price_history WHERE item_id = pi.id) OR ph.id IS NULL)', NULL, FALSE);
        
        $this->db->group_by('p.id'); 

        // Filtros de búsqueda
        if (!empty($search['keyword'])) {
            $this->db->group_start();
            $this->db->like('p.name', $search['keyword']);
            $this->db->or_like('p.code', $search['keyword']);
            $this->db->group_end();
        }

        if (!empty($search['category_id'])) {
            $this->db->where('p.category_id', $search['category_id']);
        }

        $this->db->order_by('p.id', 'DESC');

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    /**
     * Obtener detalle del producto con sus ítems y los precios más recientes de cada uno
     */
    public function get_product_detail($id) {
        $this->db->select('p.*, pc.category_name, u.full_name as editor_name');
        $this->db->from('products p');
        $this->db->join('product_categories pc', 'p.category_id = pc.id', 'left');
        $this->db->join('users u', 'p.updated_by = u.id', 'left');
        $this->db->where('p.id', $id);
        
        $product = $this->db->get()->row();

        if ($product) {
            /* Cargar los ítems con sus precios actuales desde el historial */
            $this->db->select('pi.*, ph.purchase_price_usd, ph.purchase_price_pen, ph.sale_price_usd, ph.sale_price_pen, ph.applied_rate');
            $this->db->from('product_items pi');
            $this->db->join('product_price_history ph', 'ph.item_id = pi.id', 'left');
            $this->db->where('pi.product_id', $id);
            /* Subconsulta para el último precio de cada ítem */
            $this->db->where('(ph.id = (SELECT MAX(id) FROM product_price_history WHERE item_id = pi.id) OR ph.id IS NULL)', NULL, FALSE);
            
            $product->items = $this->db->get()->result();
        }

        return $product;
    }

    /**
     * Insertar un nuevo producto. 
     * Nota: Los precios deben insertarse en product_price_history después de obtener el ID del ítem.
     */
    public function insert_product($product_data, $items_data = array()) {
        $this->db->trans_start();

        // 1. Registrar información principal en 'products'
        $this->db->insert('products', $product_data);
        $product_id = $this->db->insert_id();

        // 2. Registrar las variantes (ítems)
        if (!empty($items_data) && $product_id) {
            foreach ($items_data as $item) {
                $item['product_id'] = $product_id;
                
                // Separar datos de precio para la tabla de historial si existen
                $price_data = isset($item['price_info']) ? $item['price_info'] : null;
                unset($item['price_info']); // No existe en product_items

                $this->db->insert('product_items', $item);
                $item_id = $this->db->insert_id();

                // 3. Si hay info de precio, registrar en el historial
                if ($price_data) {
                    $price_data['item_id'] = $item_id;
                    $price_data['created_by'] = $product_data['updated_by'];
                    $this->db->insert('product_price_history', $price_data);
                }
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status() ? $product_id : false;
    }

    public function update_product($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    public function is_sku_duplicate($sku, $exclude_id = null) {
        $this->db->where('sku_code', $sku);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results('product_items') > 0;
    }

    /**
     * Gestión de variantes individuales
     */
    public function add_item($item_data) {
        return $this->db->insert('product_items', $item_data);
    }

    public function update_item($item_id, $item_data) {
        $this->db->where('id', $item_id);
        return $this->db->update('product_items', $item_data);
    }

    /**
     * Registrar historial de precios (Audit Trail)
     * Se debe usar cada vez que el precio de un ítem cambie
     */
    public function insert_price_history($data) {
        return $this->db->insert('product_price_history', $data);
    }

    public function get_categories() {
        $this->db->where('status', 1);
        $this->db->order_by('category_name', 'ASC');
        return $this->db->get('product_categories')->result();
    }

	/**
	 * Obtiene la lista de productos con sus categorías, editores y todas sus variantes con el último precio.
	 * * @param string|null $keyword Palabra clave para buscar en el nombre del producto.
	 * @param int|null $category_id ID de la categoría para filtrar.
	 * @param string|null $type Tipo de producto (GOODS/SERVICE).
	 * @return array Lista de objetos de productos con una propiedad 'items' que contiene sus variantes.
	 */
	public function get_products_with_items($keyword = null, $category_id = null, $type = null) {
		// 1. Configurar la consulta principal para la tabla 'products'
		$this->db->select('p.*, c.category_name, u.full_name as editor_name');
		$this->db->from('products p');
		
		// Joins para obtener el nombre de la categoría y el nombre completo del editor (usuario)
		$this->db->join('product_categories c', 'p.category_id = c.id', 'left');
		$this->db->join('users u', 'p.updated_by = u.id', 'left');

		// Aplicar filtros si existen
		if (!empty($keyword)) {
			$this->db->like('p.name', $keyword);
		}
		if (!empty($category_id)) {
			$this->db->where('p.category_id', $category_id);
		}
		if (!empty($type)) {
			$this->db->where('p.type', $type);
		}

		// Ordenar por ID de forma descendente (los más recientes primero)
		$this->db->order_by('p.id', 'DESC');
		
		$query = $this->db->get();
		$products = $query->result();

		// 2. Para cada producto encontrado, buscar sus ítems (variantes) y sus precios actuales
		foreach ($products as &$p) {
			/**
			 * Seleccionamos los campos necesarios de 'product_items' y los precios de 'product_price_history'.
			 * Usamos un JOIN para traer la información de precios vinculada a cada ítem.
			 */
			$this->db->select('
				pi.id as item_id,
				pi.sku_code,
				pi.option_name,
				pi.option_value,
				ph.purchase_price_usd,
				ph.purchase_price_pen,
				ph.sale_price_usd,
				ph.sale_price_pen,
				ph.applied_rate
			');
			$this->db->from('product_items pi');
			
			/**
			 * Se une con la tabla de historial de precios.
			 * Nota: Si un ítem tiene múltiples registros de historial, se requiere una lógica adicional 
			 * para traer 'solo el último'. En este caso, ordenamos por ph.id DESC.
			 */
			$this->db->join('product_price_history ph', 'ph.item_id = pi.id', 'inner');
			$this->db->where('pi.product_id', $p->id);
			
			// Aseguramos que traemos los registros más recientes del historial
			$this->db->order_by('ph.id', 'DESC');
			
			/**
			 * Si desea evitar duplicados de variantes cuando hay muchos historiales, 
			 * puede habilitar un agrupamiento por pi.id.
			 */
			// $this->db->group_by('pi.id'); 

			$p->items = $this->db->get()->result();
		}

		return $products;
	}
}