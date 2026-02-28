<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	public function index(){
		
		$data = [
			"navbar" => "product",
			"main" => "product",
		];
		
		$this->load->view('layout', $data);
	}
}
