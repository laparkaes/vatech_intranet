<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

	public function index(){
		
		$data = [
			"navbar" => "contact",
			"main" => "contact",
		];
		
		$this->load->view('layout', $data);
	}
	
	public function send_mail(){
		echo "<div>Correo enviado</div>";
		echo "<a href=".base_url()."contact>Regresar</a>";
	}
}
