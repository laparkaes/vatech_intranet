<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('product_model');
        $this->load->model('exchange_model');
		
		$this->menu = "logistic";
		$this->menu_sub = "products";
    }

    /**
     * Muestra el listado maestro (Index)
     */
	public function index() {
	
		// 1. 검색 데이터 수집 (계정 컨트롤러와 동일한 방식)
		$search = [
			'name'        => $this->input->get('name'),
			'category_id' => $this->input->get('category_id'),
			'type'        => $this->input->get('type'),
			'status'      => $this->input->get('status')
		];
		
		// 2. 페이지네이션 설정
		$config['base_url'] = base_url('product/index');
		$config['total_rows'] = $this->product_model->count_all_products($search); // 모델 함수 인자 변경 필요
		$config['per_page'] = 30;
		$config['uri_segment'] = 3; 
		$config['reuse_query_string'] = TRUE; // 검색어 유지

		// 3. Bootstrap 5 스타일 적용 (계정 컨트롤러의 스타일 복사)
		$config['full_tag_open'] = '<ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<<';
		$config['last_link'] = '>>';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '>';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['attributes'] = array('class' => 'page-link');

		$this->pagination->initialize($config);

		// 4. 현재 페이지(offset) 및 데이터 가져오기
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		// 제품 목록 가져오기 (계정 컨트롤러 방식처럼 $search 배열 전달)
		$data['products'] = $this->product_model->get_products_paged($config['per_page'], $page, $search);
		
		// 5. 뷰에 전달할 데이터 구성
		$data['pagination_links'] = $this->pagination->create_links();
		$data['total_rows'] = $config['total_rows'];
		$data['start_no']   = $page + 1; // 뷰에서 순번 계산용
		$data['search']     = $search;   // 뷰의 검색 필드 유지용
		
		// 카테고리 목록 (검색 필터용)
		$data['categories'] = $this->db->get('product_categories')->result();
		
		$data['main'] = 'product/index';
		$this->load->view('layout', $data);
	}

    /**
     * Muestra el formulario de registro de nuevo producto
     */
    public function create() {
        $data['categories'] = $this->db->get('product_categories')->result();
        $data['current_rate'] = $this->db->order_by('id', 'DESC')->get('exchange_rates', 1)->row();
        
        $data['main'] = 'product/create';
        $this->load->view('layout', $data);
    }

    /**
     * Procesa el registro de un nuevo producto (Actualizado: sin SKU, con Option y Dimensions)
     */
    public function add() {
        $user_id = $this->session->userdata('user_id');
        
        $product_data = array(
            'type'           => $this->input->post('type'),
            'category_id'    => $this->input->post('category_id'),
            'code'           => $this->input->post('code') ?: null, 
            'name'           => $this->input->post('name'),
            'brand'          => $this->input->post('brand'),
            'origin_country' => $this->input->post('origin_country'),
            'unit'           => $this->input->post('unit'),
            'description'    => $this->input->post('description'),
            'is_active'      => 1,
            'updated_by'     => $user_id
        );

        $this->db->trans_start();

        // 1. Insertar producto principal
        $this->db->insert('products', $product_data);
        $product_id = $this->db->insert_id();

        // 2. Procesar variantes (items) - Se eliminó SKU, se integró Option y Dimensions
        $options    = $this->input->post('option');
        $dimensions = $this->input->post('dimensions');
        $weights    = $this->input->post('weight');
        $p_usd      = $this->input->post('purchase_price_usd');
        $p_pen      = $this->input->post('purchase_price_pen');
        $s_usd      = $this->input->post('sale_price_usd');
        $s_pen      = $this->input->post('sale_price_pen');

        if (!empty($options) && is_array($options)) {
            foreach ($options as $i => $opt) {
                $opt = trim($opt);
                if (empty($opt)) continue;

                // Insertar variante del producto (product_items)
                $item_data = array(
                    'product_id' => $product_id,
                    'option'     => $opt,
                    'dimensions' => $dimensions[$i] ?? null,
                    'weight'     => !empty($weights[$i]) ? $weights[$i] : null,
                    'status'     => 1,
                    'updated_by' => $user_id
                );
                $this->db->insert('product_items', $item_data);
                $item_id = $this->db->insert_id();

                // 3. Calcular Tasa de Cambio Aplicada (Inverse Calculation: PEN / USD)
                $val_s_usd = floatval($s_usd[$i]);
                $val_s_pen = floatval($s_pen[$i]);
                $calculated_rate = ($val_s_usd > 0) ? ($val_s_pen / $val_s_usd) : 0;

                // 4. Registrar el historial de precios
                $price_data = array(
                    'item_id'            => $item_id,
                    'purchase_price_usd' => $p_usd[$i],
                    'purchase_price_pen' => $p_pen[$i],
                    'sale_price_usd'     => $s_usd[$i],
                    'sale_price_pen'     => $s_pen[$i],
                    'applied_rate'       => number_format($calculated_rate, 4, '.', ''),
                    'created_by'         => $user_id
                );
                $this->db->insert('product_price_history', $price_data);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Error en la base de datos durante el registro.');
            redirect('product/create');
        } else {
            $this->session->set_flashdata('success', 'Producto registrado exitosamente.');
            redirect('product');
        }
    }
    
    /**
     * Vista de detalles del producto
     */
    public function view($id) {
        $data['product'] = $this->product_model->get_product_detail($id);
        if (empty($data['product'])) show_404();
        
        $data['main'] = 'product/view';
        $this->load->view('layout', $data);
    }

    /**
     * Muestra el formulario de edición
     */
    public function edit($id) 
    {
        $data['product'] = $this->product_model->get_product_detail($id);
        $data['categories'] = $this->product_model->get_categories();
        $data['current_exchange'] = $this->db->order_by('id', 'DESC')->get('exchange_rates', 1)->row(); 

        $data['main'] = 'product/edit';
        $this->load->view('layout', $data);
    }

    /**
     * Actualiza la información del producto y sus variantes
     */
    public function update($id)
    {
        $product = $this->product_model->get_product_detail($id);
        if (!$product) show_404();

        $current_user_id = $this->session->userdata('user_id');
        $item_ids = $this->input->post('item_ids');
        $options  = $this->input->post('option');

        // 1. Recoger datos maestros
        $product_data = array(
            'type'           => $this->input->post('type'),
            'category_id'    => $this->input->post('category_id'),
            'code'           => $this->input->post('code'),
            'name'           => $this->input->post('name'),
            'brand'          => $this->input->post('brand'),
            'origin_country' => $this->input->post('origin_country'),
            'unit'           => $this->input->post('unit'),
            'description'    => $this->input->post('description'),
            'is_active'      => $this->input->post('is_active'),
            'updated_at'     => date('Y-m-d H:i:s'),
            'updated_by'     => $current_user_id
        );

        $this->db->trans_start();

        // Actualizar producto principal
        $this->product_model->update_product($id, $product_data);

        // 2. Limpieza de variantes eliminadas
        $current_items_in_post = array();
        if (!empty($item_ids)) {
            foreach ($item_ids as $id_val) {
                if ($id_val !== 'NEW') $current_items_in_post[] = $id_val;
            }
        }
        $this->db->where('product_id', $id);
        if (!empty($current_items_in_post)) {
            $this->db->where_not_in('id', $current_items_in_post);
        }
        $this->db->delete('product_items');

        // 3. Procesar variantes e historial
        $dimensions = $this->input->post('dimensions');
        $weights    = $this->input->post('weight');
        $p_usd = $this->input->post('purchase_price_usd');
        $p_pen = $this->input->post('purchase_price_pen');
        $s_usd = $this->input->post('sale_price_usd');
        $s_pen = $this->input->post('sale_price_pen');

        if (!empty($options)) {
            foreach ($item_ids as $index => $item_id) {
                $val_s_usd = (float)$s_usd[$index];
                $val_s_pen = (float)$s_pen[$index];
                $tasa_calculada = ($val_s_usd > 0) ? ($val_s_pen / $val_s_usd) : 0;

                $item_data = array(
                    'product_id' => $id,
                    'option'     => $options[$index],
                    'dimensions' => $dimensions[$index] ?? null,
                    'weight'     => $weights[$index],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $current_user_id
                );

                if ($item_id === 'NEW') {
                    $this->db->insert('product_items', $item_data);
                    $target_id = $this->db->insert_id();
                } else {
                    $this->product_model->update_item($item_id, $item_data);
                    $target_id = $item_id;
                }

                if ($target_id) {
                    $price_data = array(
                        'item_id'            => $target_id,
                        'purchase_price_usd' => $p_usd[$index],
                        'purchase_price_pen' => $p_pen[$index],
                        'sale_price_usd'     => $s_usd[$index],
                        'sale_price_pen'     => $s_pen[$index],
                        'applied_rate'       => round($tasa_calculada, 4),
                        'created_at'         => date('Y-m-d H:i:s'),
                        'created_by'         => $current_user_id
                    );
                    $this->product_model->insert_price_history($price_data);
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            redirect('product/edit/'.$id.'?status=error');
        } else {
            redirect('product/view/'.$id.'?status=updated');
        }
    }

	/**
     * Elimina un producto y todos sus registros relacionados.
     * Utiliza transacciones para asegurar que no queden datos huérfanos.
     * @param int $id Identificador del producto a eliminar
     */
    public function delete($id)
    {
        // 1. Verificar si el producto existe antes de intentar eliminar
        $product = $this->product_model->get_product_detail($id);
        if (!$product) {
            $this->session->set_flashdata('error', 'El producto no existe o ya ha sido eliminado.');
            redirect('product');
        }

        $this->db->trans_start();

        // 2. Obtener los IDs de las variantes (items) para limpiar el historial de precios
        $this->db->select('id');
        $this->db->where('product_id', $id);
        $items = $this->db->get('product_items')->result();

        if (!empty($items)) {
            $item_ids = array_column($items, 'id');
            
            // A. Eliminar historial de precios (Tabla: product_price_history)
            $this->db->where_in('item_id', $item_ids);
            $this->db->delete('product_price_history');

            // B. Eliminar las variantes (Tabla: product_items)
            $this->db->where('product_id', $id);
            $this->db->delete('product_items');
        }

        // 3. Eliminar el producto maestro (Tabla: products)
        $this->db->where('id', $id);
        $this->db->delete('products');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // Si algo falla en la transacción
            $this->session->set_flashdata('error', 'Hubo un error al intentar eliminar el producto.');
        } else {
            // Éxito
            $this->session->set_flashdata('success', 'El producto y sus componentes han sido eliminados correctamente.');
        }

        redirect('product');
    }
	
}