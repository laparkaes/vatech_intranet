<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outbound extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('outbound_model');
    }

    public function index() {
        // 필터용 데이터 (창고 목록 등)
        $data['warehouses'] = $this->db->get_where('warehouses', ['is_active' => 1])->result();
        
        // 목록 데이터 가져오기
        $warehouse_id = $this->input->get('warehouse_id');
        $data['list'] = $this->outbound_model->get_outbound_list($warehouse_id);

        $data['main'] = 'outbound/index';
        $this->load->view('layout', $data);
    }

}