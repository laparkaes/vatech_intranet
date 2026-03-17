<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador para gestionar la autenticación de usuarios
 */
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('session');
    }

    /**
     * Carga la pantalla principal de acceso
     */
    public function index() {
        // Si el usuario ya cuenta con una sesión activa, redirigir al panel principal
        if($this->session->userdata('user_id')) {
            redirect('dashboard'); 
        }
		
		$this->load->view('auth/login');
    }
	
	/**
     * Muestra la página de registro
     */
    public function register() {
		$this->load->view('auth/register');
    }
	
	/**
	 * Muestra el formulario de recuperación de contraseña
	 */
	public function forgot_password() {
		$this->load->view('auth/forgot_password');
	}

	/**
	 * Procesa la recuperación de contraseña y envía el correo
	 */
	public function reset_password_process() {
		$email = $this->input->post('email');
		
		// 1. Verificar si el usuario existe
		$user = $this->user_model->get_user_by_email($email);
		
		if (!$user) {
			$this->session->set_flashdata('error', 'El correo electrónico no existe en nuestro sistema.');
			redirect('auth/forgot_password');
			return;
		}

		// 2. Generar contraseña temporal de 10 caracteres
		$this->load->helper('string');
		$temporary_password = random_string('alnum', 10); // Genera 10 caracteres alfanuméricos

		// 3. Actualizar en la Base de Datos (Encriptada)
		$hashed_password = password_hash($temporary_password, PASSWORD_BCRYPT);
		$this->user_model->update_password($email, $hashed_password);

		/* desarrollar cuando tenga servicio hosting
		
		// 4. Configurar y enviar el correo electrónico
		$this->load->library('email');

		// Nota: Debe configurar los protocolos SMTP en application/config/email.php para que esto funcione
		$this->email->from('no-reply@vpr.pe', 'VPR ERP System');
		$this->email->to($email);
		$this->email->subject('Su Nueva Contraseña Temporal - VPR ERP');
		$this->email->message("
			Hola " . $user->full_name . ",
			
			Se ha generado una nueva contraseña temporal para su acceso al sistema VPR ERP.
			
			Nueva Contraseña: " . $temporary_password . "
			
			Por seguridad, le recomendamos cambiar esta contraseña después de iniciar sesión.
		");
		
		$send_mail = $this->email->send();
		
		*/
		
		$send_mail = true;

		if ($send_mail) {
			
			//importante!!!!! a nivel desarrollo muestra nueva clave
			
			$this->session->set_flashdata('success', 'Se ha enviado una nueva contraseña a su correo electrónico. '.$temporary_password);
			redirect('auth');
		} else {
			// En caso de que el servidor de correo falle
			$this->session->set_flashdata('error', 'No se pudo enviar el correo. Contacte al administrador.');
			redirect('auth/forgot_password');
		}
	}
	
	/**
	 * Procesa el inicio de sesión, verifica el estado de la cuenta 
	 * y actualiza el registro de last_login.
	 */
	public function login_process() {
		$email    = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->user_model->get_user_by_email($email);

		// 1. Verificar si el usuario existe y la contraseña es correcta
		if ($user && password_verify($password, $user->password)) {
			
			// 2. Verificar si la cuenta está activa (status = 1)
			if ($user->status != 1) {
				$this->session->set_flashdata('error', 'Su cuenta está desactivada. Contacte al administrador.');
				redirect('auth');
				return;
			}

			// 3. Actualizar la fecha de último inicio de sesión en la DB
			$this->user_model->update_last_login($user->id);

			// 4. Crear la sesión del usuario
			$session_data = array(
				'user_id'      => $user->id,
                'email'   	   => $user->email,
				'name'         => $user->full_name,
				'role'         => $user->role,
				'is_logged_in' => TRUE
			);
			$this->session->set_userdata($session_data);

			redirect('dashboard');
		} else {
			// Credenciales inválidas
			$this->session->set_flashdata('error', 'Correo o contraseña incorrectos.');
			redirect('auth');
		}
	}
	

	/**
	 * Procesa la creación de una nueva cuenta con validación de duplicados
	 */
	public function register_process() {
		$full_name = $this->input->post('full_name');
		$email     = $this->input->post('email');
		$password  = $this->input->post('password');

		// 1. Verificar si el correo ya existe antes de intentar el INSERT
		if ($this->user_model->check_email_exists($email)) {
			// Enviar mensaje de error si el correo ya está registrado
			$this->session->set_flashdata('error', 'Este correo electrónico ya está registrado. Intente con otro o inicie sesión.');
			redirect('auth/register');
			return; // Detener la ejecución
		}

		// 2. Si no existe, proceder con la encriptación y el registro
		$hashed_password = password_hash($password, PASSWORD_BCRYPT);

		$data = array(
			'full_name' => $full_name,
			'email'     => $email,
			'password'  => $hashed_password,
			'role'  	=> 'user',
			'status'    => 1
		);

		if ($this->user_model->insert_user($data)) {
			$this->session->set_flashdata('success', 'Cuenta creada con éxito. Ya puede iniciar sesión.');
			redirect('auth');
		} else {
			$this->session->set_flashdata('error', 'Error crítico al registrar el usuario en la base de datos.');
			redirect('auth/register');
		}
	}

    /**
     * Finaliza la sesión actual y destruye los datos asociados
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}