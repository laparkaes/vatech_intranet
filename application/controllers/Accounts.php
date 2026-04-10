<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
		$this->load->model('division_model');
		
        // Restricción: Solo el rol 'admin' puede acceder a este controlador
        if ($this->session->userdata('role') !== 'admin') {
            // Establecer el mensaje de error en español
            $this->session->set_flashdata('error', 'No cuenta con los privilegios de administrador necesarios.');
            redirect('dashboard');
        }
		
		$this->menu = "system";
		$this->menu_sub = "accounts";
    }

    /**
     * Lista todos los usuarios registrados en el sistema
     */
    public function index() {

		// 검색 데이터 수집 (GET 방식)
		$search = [
			'name'     => $this->input->get('name'),
			'email'    => $this->input->get('email'),
			'division' => $this->input->get('division'),
			'role'     => $this->input->get('role'),
			'status'   => $this->input->get('status')
		];

		// 페이지네이션 설정
		$config['base_url'] = base_url('accounts/index');
		$config['total_rows'] = $this->user_model->count_all_users($search);
		$config['per_page'] = 20;
		$config['uri_segment'] = 3;
		$config['reuse_query_string'] = TRUE; // 검색 쿼리 스트링 유지 (중요)

		// 3. 부트스트랩 5 스타일 적용 (제공된 뷰가 Bootstrap 기반이므로)
		$config['full_tag_open'] = '<ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<<';
		$config['last_link'] = '>>';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '>';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['attributes'] = array('class' => 'page-link');

		$this->pagination->initialize($config);

		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$users = $this->user_model->get_users_paged($config['per_page'], $page, $search);

		foreach ($users as $user) {
			$user->tenure = $this->user_model->calculate_tenure($user->hire_date);
		}

		$data['users'] = $users;
		$data['divisions'] = $this->division_model->get_active_divisions(); // 검색 필터용
		$data['pagination'] = $this->pagination->create_links();
		$data['total_rows'] = $config['total_rows'];
		$data['start_no'] = $page + 1;
		$data['search'] = $search; // 뷰에 검색어 유지용
		
		$data['main'] = 'accounts/index';
		$this->load->view('layout', $data);
	}
	
	/**
	 * Muestra el formulario de edición para un usuario específico.
	 */
	public function edit($id) {
		if ($this->session->userdata('role') !== 'admin') redirect('dashboard');

		$data['user'] = $this->user_model->get_user_by_id($id); // Método a crear en el modelo
		$data['divisions'] = $this->division_model->get_active_divisions();
		
		$data['main'] = 'accounts/edit';
		$this->load->view('layout', $data);
	}

	/**
	 * Procesa la actualización (Con Fecha de Ingreso)
	 */
	public function update() {
		// 1. 권한 체크
		if ($this->session->userdata('role') !== 'admin') redirect('dashboard');

		$user_id = $this->input->post('user_id');
		$password = $this->input->post('password');
		$error_msg = "";

		// 2. 비밀번호 검증 (입력된 경우에만 최소 6자리 체크)
		if (!empty($password) && strlen($password) < 6) {
			$error_msg = 'La contraseña debe tener al menos 6 caracteres.';
		}

		// 3. 오류 발생 시 처리 (입력값 유지를 위해 edit 메서드 직접 호출)
		if (!empty($error_msg)) {
			$this->session->set_flashdata('error', $error_msg);
			
			// 중요: redirect 대신 기존 edit 메서드를 호출하여 POST 데이터를 유지
			// edit($id) 메서드가 수정 폼을 로드한다고 가정합니다.
			$this->edit($user_id); 
			return;
		}

		// 4. 기본 업데이트 데이터 구성
		$data = array(
			'full_name'   => $this->input->post('full_name'),
			'division_id' => $this->input->post('division_id') ? $this->input->post('division_id') : NULL,
			'hire_date'   => $this->input->post('hire_date') ? $this->input->post('hire_date') : NULL,
			'role'        => $this->input->post('role'),
			'status'      => $this->input->post('status')
		);

		// 5. 비밀번호가 입력된 경우에만 데이터 배열에 추가
		if (!empty($password)) {
			$data['password'] = password_hash($password, PASSWORD_BCRYPT);
		}

		// 6. DB 업데이트 실행
		if ($this->user_model->update_user($user_id, $data)) {
			$this->session->set_flashdata('success', 'Usuario actualizado con éxito.');
		} else {
			$this->session->set_flashdata('error', 'Error al actualizar el usuario.');
		}

		redirect('accounts');
	}
	
	/**
	 * Muestra el formulario para registrar un nuevo usuario.
	 */
	public function create() {
		if ($this->session->userdata('role') !== 'admin') redirect('dashboard');

		$data['divisions'] = $this->division_model->get_active_divisions();
		
		$data['main'] = 'accounts/create'; // Nueva vista
		$this->load->view('layout', $data);
	}

	/**
	 * Procesa la inserción del nuevo usuario (Con Fecha de Ingreso)
	 */
	public function add() {
		if ($this->session->userdata('role') !== 'admin') redirect('dashboard');

		$email = $this->input->post('email');
		$password = $this->input->post('password');

		// 1. 검증 로직
		$error_msg = "";
		if (strlen($password) < 6) {
			$error_msg = 'La contraseña debe tener al menos 6 caracteres.';
		} elseif ($this->user_model->check_email_exists($email)) {
			$error_msg = 'El correo electrónico ya está registrado.';
		}

		// 2. 오류 발생 시 처리
		if (!empty($error_msg)) {
			$this->session->set_flashdata('error', $error_msg);
			
			// 중요: redirect 대신 등록 폼을 보여주는 메서드를 직접 호출하여 POST 값을 유지
			$this->create(); 
			return;
		}

		// 3. 정상 등록 로직 (이후 동일)
		$data = array(
			'full_name'   => $this->input->post('full_name'),
			'email'       => $email,
			'password'    => password_hash($password, PASSWORD_BCRYPT),
			'division_id' => $this->input->post('division_id') ? $this->input->post('division_id') : NULL,
			'hire_date'   => $this->input->post('hire_date') ? $this->input->post('hire_date') : NULL,
			'role'        => $this->input->post('role') ? $this->input->post('role') : NULL,
			'status'      => 1
		);

		$this->user_model->insert_user($data);
		redirect('accounts');
	}
}