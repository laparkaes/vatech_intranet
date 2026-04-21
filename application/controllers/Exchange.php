<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exchange extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Exchange_model');
    }

    /**
     * Muestra el listado de tasas con paginación y promedio de 30 días
     */
    public function index() {
		$this->load->library('pagination');

		// 1. Configuración de Paginación
		$config['base_url'] = base_url('exchange/index');
		$config['total_rows'] = $this->Exchange_model->count_all_rates();
		$config['per_page'] = 30;
		$config['uri_segment'] = 3;
		$config['reuse_query_string'] = TRUE;

		// Bootstrap 5 스타일 적용
		$config['full_tag_open'] = '<ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul>';
		$config['attributes'] = array('class' => 'page-link');
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		// 2. 데이터 조회
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$rates = $this->Exchange_model->get_rates_paged($config['per_page'], $page);

		// 3. 30개 데이터 기준 평균 계산
		foreach ($rates as &$r) {
			$r->avg_30last = $this->Exchange_model->get_rolling_average($r->base_currency, $r->target_currency, $r->effective_date, 30);
		}

		$data['rates'] = $rates;
		$data['total_rows'] = $config['total_rows'];
		$data['start_no'] = $page + 1;
		$data['pagination'] = $this->pagination->create_links();
		$data['main'] = 'exchange/index'; 
		
		$this->load->view('layout', $data);
	}

    /**
     * Registro con Flash Messages
     */
    public function add() {
        $base   = $this->input->post('base_currency');
        $target = $this->input->post('target_currency');
        $date   = $this->input->post('effective_date');

        if ($this->Exchange_model->check_duplicate($base, $target, $date)) {
            $this->session->set_flashdata('error', "Ya existe un registro para $base/$target en la fecha $date.");
            redirect('exchange/index');
            return;
        }

        $data = array(
            'base_currency'   => $base,
            'target_currency' => $target,
            'rate'            => $this->input->post('rate'),
            'effective_date'  => $date,
            'created_by'      => $this->session->userdata('user_id') ?? 1
        );

        if ($this->Exchange_model->insert_rate($data)) {
            $this->session->set_flashdata('success', 'Tipo de cambio registrado con éxito.');
        } else {
            $this->session->set_flashdata('error', 'Error al procesar el registro.');
        }
        
        redirect('exchange/index');
    }

    /**
     * Actualización con Flash Messages
     */
    public function update() {
        $id     = $this->input->post('id');
        $base   = $this->input->post('base_currency');
        $target = $this->input->post('target_currency');
        $date   = $this->input->post('effective_date');

        if ($this->Exchange_model->check_duplicate($base, $target, $date, $id)) {
            $this->session->set_flashdata('error', 'No se puede actualizar. Existe otro registro idéntico.');
            redirect('exchange/edit/' . $id);
            return;
        }

        $data = array(
            'base_currency'   => $base,
            'target_currency' => $target,
            'rate'            => $this->input->post('rate'),
            'effective_date'  => $date,
            'created_by'      => $this->session->userdata('user_id') ?? 1
        );

        if ($this->Exchange_model->update_rate($id, $data)) {
            $this->session->set_flashdata('success', 'Actualización completada.');
            redirect('exchange/index');
        } else {
            $this->session->set_flashdata('error', 'Error al intentar actualizar.');
            redirect('exchange/edit/' . $id);
        }
    }

    public function edit($id) {
        $data['rate_item'] = $this->Exchange_model->get_rate_by_id($id);
        if (!$data['rate_item']) {
            $this->session->set_flashdata('error', 'Registro no encontrado.');
            redirect('exchange/index');
        }
        
        $data['main'] = 'exchange/edit';
        $this->load->view('layout', $data);
    }
}