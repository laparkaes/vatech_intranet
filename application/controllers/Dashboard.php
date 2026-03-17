<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Verificación de sesión
        if (!$this->session->userdata('is_logged_in')) {
            redirect('auth');
        }
    }

    /**
     * Carga el Dashboard utilizando el sistema de Layout
     */
    public function index() {
        // Se define la ruta de la vista principal que irá dentro del layout
        $data['main'] = 'dashboard/index';
        
        // Se carga el layout y se le pasan los datos
        $this->load->view('layout', $data);
    }
}