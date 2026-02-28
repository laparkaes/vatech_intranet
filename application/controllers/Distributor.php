<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distributor extends CI_Controller {

	public function index(){
		
		$data = [
			"navbar" => "distributor",
			"main" => "distributor",
		];
		
		$this->load->view('layout', $data);
	}
}
