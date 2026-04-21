<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('entity_model');
        
        // 메뉴 활성화 (구매/공급망 관리 섹션으로 가정)
        $this->menu = "purchase"; 
        $this->menu_sub = "vendor";
    }

    /**
     * Muestra el listado de Proveedores (Vendors) con filtros y paginación
     */
    public function index() {
        // 1. 검색 데이터 수집 (role은 vendor로 고정)
        $search = [
            'name'   => $this->input->get('name'),
            'tax_id' => $this->input->get('tax_id'),
            'role'   => 'vendor', // 공급업체 목록이므로 고정
            'status' => $this->input->get('status')
        ];

        // 2. 페이지네이션 설정
        $this->load->library('pagination');
        
        $config['base_url'] = base_url('vendor/index');
        $config['total_rows'] = $this->entity_model->count_all_entities($search);
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = TRUE;

        // 부트스트랩 5 스타일 적용
        $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul>';
        $config['attributes'] = array('class' => 'page-link');
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

        $this->pagination->initialize($config);

        // 3. 데이터 조회
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['vendors'] = $this->entity_model->get_entities_paged($config['per_page'], $page, $search);

        // 4. 뷰 전달 데이터
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start_no'] = $page + 1;
        $data['search'] = $search;
        
        $data['main'] = 'vendor/index';
        $this->load->view('layout', $data);
    }

    /**
     * Formulario de creación de Proveedor
     */
    public function create() {
        $data['countries'] = $this->entity_model->get_countries();
        $data['main'] = 'vendor/create';
        $this->load->view('layout', $data);
    }

    /**
     * Procesa el registro de un nuevo proveedor
     */
    public function add() {
        $country_id = $this->input->post('country_id');
        $tax_id     = $this->input->post('tax_id');

        if ($this->entity_model->check_duplicate($country_id, $tax_id)) {
            $this->session->set_flashdata('error', 'El RUC/Tax ID ya existe para este país.');
            redirect('vendor/create');
            return;
        }

        $entity_data = array(
            'name'        => $this->input->post('name'),
            'country_id'  => $country_id,
            'tax_id'      => $tax_id,
            'is_vendor'   => 1, // 공급업체 등록이므로 강제 설정
            'is_dealer'   => $this->input->post('is_dealer') ? 1 : 0,
            'phone'       => $this->input->post('phone'),
            'mobile'      => $this->input->post('mobile'),
            'address'     => $this->input->post('address'),
            'website'     => $this->input->post('website'),
            'description' => $this->input->post('description'),
            'status'      => 1,
            'created_by'  => $this->session->userdata('user_id')
        );

        // 초기 연락처 처리 (선택사항)
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
                        'is_main'      => $this->input->post('is_main')[$key],
                        'status'       => 1
                    );
                }
            }
        }

        if ($this->entity_model->register_entity_with_contacts($entity_data, $contacts_batch)) {
            $this->session->set_flashdata('success', 'Proveedor registrado con éxito.');
            redirect('vendor');
        } else {
            $this->session->set_flashdata('error', 'Error al procesar el registro.');
            redirect('vendor/create');
        }
    }

    /**
     * Vista de edición de Proveedor
     */
    public function edit($id) {
        $data['vendor'] = $this->entity_model->get_entity_details($id);
        if (empty($data['vendor'])) { show_404(); }

        $data['contacts'] = $this->entity_model->get_entity_contacts($id);
        $data['countries'] = $this->entity_model->get_countries();
        $data['main'] = 'vendor/edit';
        $this->load->view('layout', $data);
    }

    /**
     * Actualiza la información del proveedor
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
            $this->session->set_flashdata('success', 'Información del proveedor actualizada.');
            redirect('vendor/view/'.$id);
        } else {
            $this->session->set_flashdata('error', 'No se pudo realizar la actualización.');
            redirect('vendor/edit/'.$id);
        }
    }

    /**
     * Vista detallada del proveedor
     */
    public function view($id) {
        $data['vendor'] = $this->entity_model->get_entity_details($id);
        if (empty($data['vendor'])) { show_404(); }
        
        $data['contacts'] = $this->entity_model->get_entity_contacts($id);
        $data['main'] = 'vendor/view';
        $this->load->view('layout', $data);
    }

    /* --- Contact Management (Proveedor 전용) --- */

    public function add_contact() {
        $entity_id = $this->input->post('entity_id');
        $email = $this->input->post('email');

        if ($this->entity_model->check_active_email_exists($email)) {
            $this->session->set_flashdata('error', 'El correo electrónico ya está en uso.');
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
            $this->session->set_flashdata('success', 'Contacto añadido al proveedor.');
        }
        redirect('vendor/view/'.$entity_id);
    }

    public function update_single_contact() {
        $contact_id = $this->input->post('contact_id');
        $entity_id  = $this->input->post('entity_id');

        $data = array(
            'contact_name' => $this->input->post('contact_name'),
            'position'     => $this->input->post('position'),
            'email'        => $this->input->post('email'),
            'phone'        => $this->input->post('phone')
        );

        if ($this->entity_model->update_contact($contact_id, $data)) {
            $this->session->set_flashdata('success', 'Contacto actualizado.');
        } else {
            $this->session->set_flashdata('error', 'Error al actualizar el contacto.');
        }
        redirect('vendor/view/'.$entity_id);
    }

    public function make_main_contact($contact_id, $entity_id) {
        $this->entity_model->set_main_contact($contact_id, $entity_id);
        $this->session->set_flashdata('success', 'Contacto principal actualizado.');
        redirect('vendor/view/'.$entity_id);
    }

    public function delete_contact($contact_id, $entity_id) {
        $this->entity_model->soft_delete_contact($contact_id);
        redirect('vendor/view/'.$entity_id);
    }
}