<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Division extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('division_model');
        if (!$this->session->userdata('is_logged_in') || $this->session->userdata('role') !== 'admin') {
            redirect('auth');
        }
    }

    public function index() {
        $data['list'] = $this->division_model->get_all_divisions();
        $data['main'] = 'division/index';
        $this->load->view('layout', $data);
    }

    public function add() {
        $data = array(
            'division_name' => $this->input->post('division_name'),
            'description'   => $this->input->post('description')
        );
        $this->division_model->insert_division($data);
        $this->session->set_flashdata('success', 'Nueva división creada.');
        redirect('division');
    }

    public function update() {
        $id = $this->input->post('id');
        $data = array(
            'division_name' => $this->input->post('division_name'),
            'description'   => $this->input->post('description'),
            'status'        => $this->input->post('status')
        );
        $this->division_model->update_division($id, $data);
        $this->session->set_flashdata('success', 'División actualizada.');
        redirect('division');
    }
}