<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $my_access = array();

    public function __construct() {
        parent::__construct();

        /* 1. Verificar sesión global */
        if (!$this->session->userdata('user_id')) {
            redirect('/');
        }

        /* 2. Cargar permisos del usuario */
        $this->load_user_permissions();
		
		$this->menu = "";
		$this->menu_sub = "";
    }

    private function load_user_permissions() {
        $user_id = $this->session->userdata('user_id');
        $user_role = $this->session->userdata('role');

        /* Si es admin, no necesita filtrar, pero cargamos una marca */
        if ($user_role === 'admin') {
            $this->my_access = 'ALL';
        } else {
            $this->db->select('a.access_name');
            $this->db->from('access_requests ar');
            $this->db->join('access a', 'ar.access_id = a.id');
            $this->db->where('ar.user_id', $user_id);
            $this->db->where('ar.status', 'APPROVED');
            $query = $this->db->get();

            foreach ($query->result() as $row) {
                $this->my_access[] = $row->access_name;
            }
        }

        /* 3. Compartir con todas las Vistas automáticamente */
        $this->load->vars([
            'my_access' => $this->my_access,
            'user_role' => $user_role
        ]);
    }
}