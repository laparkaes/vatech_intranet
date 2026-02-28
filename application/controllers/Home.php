<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index(){
		fd
		$data = [
			"navbar" => "home",
			"main" => "home",
		];
		
		$this->load->view('layout', $data);
	}
}
