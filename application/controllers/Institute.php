<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Institute extends CI_Controller {

	public function index(){
		
		$data = [
			"navbar" => "institute",
			"main" => "institute",
		];
		
		$this->load->view('layout', $data);
	}
}
