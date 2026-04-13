<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entity extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('entity_model');
		
		$this->menu = "master";
		$this->menu_sub = "entity";
    }

    /**
     * Muestra el listado maestro de todas las entidades registradas
     */
	public function index() {
		// 1. 검색 데이터 수집 (GET)
		$search = [
			'name'   => $this->input->get('name'),
			'tax_id' => $this->input->get('tax_id'),
			'role'   => $this->input->get('role'),
			'status' => $this->input->get('status')
		];

		// 2. 페이지네이션 설정
		$this->load->library('pagination');
		
		$config['base_url'] = base_url('entity/index');
		$config['total_rows'] = $this->entity_model->count_all_entities($search);
		$config['per_page'] = 20;
		$config['uri_segment'] = 3;
		$config['reuse_query_string'] = TRUE;

		// 부트스트랩 5 스타일 설정
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

		// 3. 데이터 조회
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['entities'] = $this->entity_model->get_entities_paged($config['per_page'], $page, $search);

		// 4. 뷰 전달 데이터
		$data['pagination'] = $this->pagination->create_links();
		$data['total_rows'] = $config['total_rows'];
		$data['start_no'] = $page + 1;
		$data['search'] = $search;
		
		$data['main'] = 'entity/index';
		$this->load->view('layout', $data);
	}

    /**
     * Muestra el formulario para el registro de una nueva entidad (Proveedor/Distribuidor)
     */
    public function create() {
        $data['countries'] = $this->entity_model->get_countries();
        $data['main'] = 'entity/create';
        $this->load->view('layout', $data);
    }

    /**
     * Procesa la inserción de una nueva entidad y sus contactos relacionados
     */
    public function add() {
        $country_id = $this->input->post('country_id');
        $tax_id     = $this->input->post('tax_id');

        /**
         * Validación de duplicados basada en el Tax ID y el País
         */
        if ($this->entity_model->check_duplicate($country_id, $tax_id)) {
            $this->session->set_flashdata('error', 'El RUC/Tax ID ya existe para este país.');
            redirect('entity/create');
            return;
        }

        $entity_data = array(
            'name'        => $this->input->post('name'),
            'country_id'  => $country_id,
            'tax_id'      => $tax_id,
            'is_vendor'   => $this->input->post('is_vendor') ? 1 : 0,
            'is_dealer'   => $this->input->post('is_dealer') ? 1 : 0,
            'phone'       => $this->input->post('phone'),
            'mobile'      => $this->input->post('mobile'),
            'address'     => $this->input->post('address'),
            'website'     => $this->input->post('website'),
            'description' => $this->input->post('description'),
            'status'      => 1,
            'created_by'  => $this->session->userdata('user_id')
        );

        /**
         * Preparación del lote de contactos iniciales
         */
        $contacts_batch = array();
        $names = $this->input->post('contact_name');
        if (!empty($names)) {
            foreach ($names as $key => $val) {
                if(!empty($val)) {
                    $contacts_batch[] = array(
                        'contact_name' => $val,
                        'position'     => $this->input->post('position')[$key],
                        'email'        => $this->input->post('contact_email')[$key],
                        'phone'        => $this->input->post('indiv_phone')[$key],
                        'is_main'      => isset($this->input->post('is_main')[$key]) ? 1 : 0,
                        'status'       => 1
                    );
                }
            }
        }

        if ($this->entity_model->register_entity_with_contacts($entity_data, $contacts_batch)) {
            $this->session->set_flashdata('success', 'Entidad registrada con éxito en el sistema.');
            redirect('entity');
        } else {
            $this->session->set_flashdata('error', 'Error al procesar el registro de la entidad.');
            redirect('entity/create');
        }
    }

    /**
     * Muestra el formulario de edición para una entidad existente
     */
    public function edit($id) {
        $data['entity'] = $this->entity_model->get_entity_details($id);
        if (empty($data['entity'])) {
            show_404();
        }

        $data['countries'] = $this->entity_model->get_countries();
        $data['main'] = 'entity/edit';
        $this->load->view('layout', $data);
    }

    /**
     * Actualiza la información general y los roles de la entidad
     */
    public function update() {
        $id = $this->input->post('id');
        
        $update_data = array(
            'name'        => $this->input->post('name'),
            'country_id'  => $this->input->post('country_id'),
            'tax_id'      => $this->input->post('tax_id'),
            'is_vendor'   => $this->input->post('is_vendor') ? 1 : 0,
            'is_dealer'   => $this->input->post('is_dealer') ? 1 : 0,
            'phone'       => $this->input->post('phone'),
            'mobile'      => $this->input->post('mobile'),
            'website'     => $this->input->post('website'),
            'address'     => $this->input->post('address'),
            'description' => $this->input->post('description'),
            'status'      => $this->input->post('status')
        );

        if ($this->entity_model->update_entity($id, $update_data)) {
            $this->session->set_flashdata('success', 'Información de la entidad actualizada correctamente.');
            redirect('entity/view/'.$id);
        } else {
            $this->session->set_flashdata('error', 'No se pudo realizar la actualización.');
            redirect('entity/edit/'.$id);
        }
    }

    /**
     * Muestra la vista detallada de una entidad y sus contactos
     */
    public function view($id) {
        $data['entity'] = $this->entity_model->get_entity_details($id);
        if (empty($data['entity'])) { 
            show_404(); 
        }
        $data['contacts'] = $this->entity_model->get_entity_contacts($id);
        $data['main'] = 'entity/view';
        $this->load->view('layout', $data);
    }

    /**
     * Interfaz para la gestión exclusiva de contactos de una entidad
     */
    public function contacts($entity_id) {
        $data['entity'] = $this->entity_model->get_entity_details($entity_id);
        if (empty($data['entity'])) { 
            show_404(); 
        }
        $data['contacts'] = $this->entity_model->get_entity_contacts($entity_id);
        $data['main'] = 'entity/contacts';
        $this->load->view('layout', $data);
    }

    /**
     * Establece un contacto específico como el principal para comunicaciones
     */
    public function make_main_contact($contact_id, $entity_id) {
		if ($this->entity_model->set_main_contact($contact_id, $entity_id)) {
			// 성공 메시지: "Contacto principal actualizado con éxito."
			$this->session->set_flashdata('success', 'Contacto principal actualizado con éxito.');
		} else {
			// 오류 메시지: "Ocurrió un error al actualizar el contacto."
			$this->session->set_flashdata('error', 'Ocurrió un error al actualizar el contacto.');
		}

		// 담당자 관리 페이지에서 작업을 수행하므로 해당 페이지로 리다이렉트하는 것이 자연스럽습니다.
		redirect('entity/view/'.$entity_id);
	}

    /**
     * Desactiva de forma lógica un contacto del sistema
     */
    public function delete_contact($contact_id, $entity_id) {
        $this->entity_model->soft_delete_contact($contact_id);
        redirect('entity/view/'.$entity_id);
    }

    /**
     * Añade un nuevo contacto de forma individual a una entidad existente
     */
    public function add_contact() {
        $entity_id = $this->input->post('entity_id');
        $email = $this->input->post('email');

        /**
         * Verificación de existencia de correo electrónico activo
         */
        if ($this->entity_model->check_active_email_exists($email)) {
            $this->session->set_flashdata('error', 'El correo electrónico ingresado ya está en uso.');
        } else {
            $data = array(
                'entity_id'    => $entity_id,
                'contact_name' => $this->input->post('contact_name'),
                'position'     => $this->input->post('position'),
                'email'        => $email,
                'phone'        => $this->input->post('phone'),
                'status'       => 1
            );
            $this->entity_model->insert_contact($data);
            $this->session->set_flashdata('success', 'Contacto añadido correctamente.');
        }
        redirect('entity/view/'.$entity_id);
    }
}