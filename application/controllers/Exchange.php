<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exchange extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Exchange_model');
    }

    public function index() {
        $data['rates'] = $this->Exchange_model->get_all_rates();
        
        // Variable 'main' para ser usada en layout.php
        // Asegurarse de que el path sea correcto: 'exchange/index'
        $data['main'] = 'exchange/index'; 
        
        $this->load->view('layout', $data);
    }

    /* Proceso de registro con validacion de duplicados */
	public function add() {
		$base   = $this->input->post('base_currency');
		$target = $this->input->post('target_currency');
		$date   = $this->input->post('effective_date');

		// Validar duplicado antes de insertar
		if ($this->Exchange_model->check_duplicate($base, $target, $date)) {
			echo "<script>
					alert('Error: Ya existe un tipo de cambio registrado para " . $base . "/" . $target . " en la fecha " . $date . ".');
					history.back();
				  </script>";
			return;
		}

		$data = array(
			'base_currency'   => $base,
			'target_currency' => $target,
			'rate'            => $this->input->post('rate'),
			'effective_date'  => $date,
			'created_by'      => $this->session->userdata('user_id') ? $this->session->userdata('user_id') : 1
		);

		$this->Exchange_model->insert_rate($data);
		redirect('exchange/index');
	}
	
	/* Vista de edicion de tasa */
	public function edit($id) {
		$data['rate_item'] = $this->Exchange_model->get_rate_by_id($id);
		if (!$data['rate_item']) {
			redirect('exchange/index');
		}
		
		$data['main'] = 'exchange/edit';
		$this->load->view('layout', $data);
	}

	/* Proceso de actualizacion con validacion de duplicados */
	public function update() {
		$id     = $this->input->post('id');
		$base   = $this->input->post('base_currency');
		$target = $this->input->post('target_currency');
		$date   = $this->input->post('effective_date');

		// Validar duplicado excluyendo el registro actual
		if ($this->Exchange_model->check_duplicate($base, $target, $date, $id)) {
			echo "<script>
					alert('Error: No se puede actualizar. Ya existe otro registro con la misma fecha y par de monedas.');
					history.back();
				  </script>";
			return;
		}

		$data = array(
			'base_currency'   => $base,
			'target_currency' => $target,
			'rate'            => $this->input->post('rate'),
			'effective_date'  => $date,
			'created_by'      => $this->session->userdata('user_id') ? $this->session->userdata('user_id') : 1
		);

		$this->Exchange_model->update_rate($id, $data);
		redirect('exchange/index');
	}
}