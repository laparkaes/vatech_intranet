<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Division extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
		$this->load->model('division_model');
		
		$this->menu = "master";
		$this->menu_sub = "division";
    }

    public function index() {
		$data['list'] = $this->division_model->get_all_divisions(); // 전체 목록
		$data['parent_divisions'] = $this->division_model->get_active_divisions(); // 드롭다운용
		$data['main'] = 'division/index';
		$this->load->view('layout', $data);
	}

    public function add() {
		// parent_id가 빈 문자열로 오면 NULL로 처리
		$parent_id = $this->input->post('parent_id');
		$parent_id = ($parent_id === "") ? NULL : $parent_id;

		$data = array(
			'division_name' => $this->input->post('division_name'),
			'parent_id'     => $parent_id,
			'status'        => 1 // 기본값 활성화
		);

		$this->division_model->insert_division($data);
		$this->session->set_flashdata('success', 'Nueva división creada.');
		redirect('division');
	}

	public function update() {
		$id = $this->input->post('id');
		$parent_id = $this->input->post('parent_id');
		
		// 자기 자신을 상위 부서로 선택했는지 체크 (무한 루프 방지)
		if ($parent_id == $id) {
			$this->session->set_flashdata('error', 'Una división no puede인 être su propia división superior.');
			redirect('division');
			return;
		}

		// 빈 값은 NULL로 처리
		$parent_id = ($parent_id === "") ? NULL : $parent_id;

		$data = array(
			'division_name' => $this->input->post('division_name'),
			'parent_id'     => $parent_id,
			'status'        => $this->input->post('status')
		);

		$this->division_model->update_division($id, $data);
		$this->session->set_flashdata('success', 'División actualizada.');
		redirect('division');
	}

}