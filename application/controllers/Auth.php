<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador para gestionar la autenticación de usuarios
 */
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
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
	 * Procesa el inicio de sesión, verifica el estado de la cuenta 
	 * y actualiza el registro de last_login.
	 */
	public function login_process() {
		$email    = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->user_model->get_user_by_email($email);

		// 1. 사용자 존재 여부 및 비밀번호 확인
		if ($user && password_verify($password, $user->password)) {
			
			// 2. 계정 활성화 상태 확인
			if ($user->status != 1) {
				$this->session->set_flashdata('error', 'Su cuenta está desactivada. Contacte al administrador.');
				redirect('auth');
				return;
			}

			// 3. 마지막 로그인 시간 업데이트
			$this->user_model->update_last_login($user->id);

			// 4. 세션 데이터 생성 (Division 정보 포함)
			$session_data = array(
				'user_id'       => $user->id,
				'email'         => $user->email,
				'name'          => $user->full_name,
				'role'          => $user->role,
				'division_id'   => $user->division_id,   // 부서 ID 저장
				'division_name' => $user->division_name, // 부서명 저장
				'is_logged_in'  => TRUE
			);
			
			$this->session->set_userdata($session_data);

			redirect('dashboard');
		} else {
			// 인증 실패
			$this->session->set_flashdata('error', 'Correo o contraseña incorrectos.');
			redirect('auth');
		}
	}
	
	/**
     * Muestra la página de registro
     */
    public function register() {
		$this->load->view('auth/register');
    }
	
	/**
	 * Procesa la creación de una nueva cuenta con validación de duplicados
	 */
	public function register_process() {
		$full_name = $this->input->post('full_name');
		$email     = $this->input->post('email');
		$password  = $this->input->post('password');

		// 1. 이메일 중복 체크
		if ($this->user_model->check_email_exists($email)) {
			$this->session->set_flashdata('error', 'Este correo electrónico ya está registrado.');
			
			// 입력했던 데이터를 다시 flashdata에 저장
			$this->session->set_flashdata('old_full_name', $full_name);
			$this->session->set_flashdata('old_email', $email);
			
			redirect('auth/register');
			return;
		}

		// 2. 관리자 계정 존재 여부 확인
		// 시스템에 유효한 admin 계정이 하나도 없다면 현재 가입자를 admin으로 설정
		$role = 'user'; // 기본값은 user
		if (!$this->user_model->has_admin_exists()) {
			$role = 'admin';
		}

		// 3. 비밀번호 암호화
		$hashed_password = password_hash($password, PASSWORD_BCRYPT);

		$data = array(
			'full_name' => $full_name,
			'email'     => $email,
			'password'  => $hashed_password,
			'role'      => $role, // 동적으로 결정된 롤 적용
			'status'    => 1
		);

		if ($this->user_model->insert_user($data)) {
			// 성공 시 로직
			$this->session->set_flashdata('success', 'Cuenta creada con éxito.');
			redirect('auth');
		} else {
			// DB 인서트 오류 등 예외 발생 시
			$this->session->set_flashdata('error', 'Error crítico al registrar el usuario.');
			
			// 입력했던 데이터 유지
			$this->session->set_flashdata('old_full_name', $full_name);
			$this->session->set_flashdata('old_email', $email);
			
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