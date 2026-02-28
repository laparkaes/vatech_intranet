<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {

	public function index(){
		
		$data = [
			"navbar" => "company",
			"main" => "company",
		];
		
		$this->load->view('layout', $data);
	}
}
