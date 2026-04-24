<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exchange extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Exchange_model');
		
		$this->menu = "sale";
		$this->menu_sub = "exchange";
    }

    /**
     * Muestra el listado de tasas con paginación y promedio de 30 días
     */
    public function index() {
		$this->load->library('pagination');

		// 1. Configuración de Paginación
		$config['base_url'] = base_url('exchange/index');
		$config['total_rows'] = $this->Exchange_model->count_all_rates();
		$config['per_page'] = 30;
		$config['uri_segment'] = 3;
		$config['reuse_query_string'] = TRUE;

		// Bootstrap 5 스타일 적용
		$config['full_tag_open'] = '<ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul>';
		$config['attributes'] = array('class' => 'page-link');
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		// 2. 데이터 조회
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$rates = $this->Exchange_model->get_rates_paged($config['per_page'], $page);

		// 3. 30개 데이터 기준 평균 계산
		foreach ($rates as &$r) {
			$r->avg_30last = $this->Exchange_model->get_rolling_average($r->base_currency, $r->target_currency, $r->effective_date, 30);
		}

		$data['rates'] = $rates;
		$data['total_rows'] = $config['total_rows'];
		$data['start_no'] = $page + 1;
		$data['pagination'] = $this->pagination->create_links();
		$data['main'] = 'exchange/index'; 
		
		$this->load->view('layout', $data);
	}

    /**
	 * Registro con Validaciones
	 */
	public function add() {
		$base   = $this->input->post('base_currency');
		$target = $this->input->post('target_currency');
		$date   = $this->input->post('effective_date');
		$rate   = (float)$this->input->post('rate'); // 형변환

		// 1. 환율 0 또는 음수 체크
		if ($rate <= 0) {
			$this->session->set_flashdata('error', 'La tasa (rate) debe ser mayor que cero.');
			$this->session->set_flashdata('temp_data', $this->input->post());
			redirect('exchange/index');
			return;
		}

		// 2. 중복 체크
		if ($this->Exchange_model->check_duplicate($base, $target, $date)) {
			$this->session->set_flashdata('error', "Ya existe un registro para $base/$target en la fecha $date.");
			$this->session->set_flashdata('temp_data', $this->input->post());
			redirect('exchange/index');
			return;
		}

		$data = array(
			'base_currency'   => $base,
			'target_currency' => $target,
			'rate'            => $rate,
			'effective_date'  => $date,
			'created_by'      => $this->session->userdata('user_id') ?? 1
		);

		if ($this->Exchange_model->insert_rate($data)) {
			$this->session->set_flashdata('success', 'Tipo de cambio registrado con éxito.');
		} else {
			$this->session->set_flashdata('temp_data', $this->input->post());
			$this->session->set_flashdata('error', 'Error al procesar el registro.');
		}
		
		redirect('exchange/index');
	}

	/**
	 * Actualización con Validaciones
	 */
	public function update() {
		$id     = $this->input->post('id');
		$base   = $this->input->post('base_currency');
		$target = $this->input->post('target_currency');
		$date   = $this->input->post('effective_date');
		$rate   = (float)$this->input->post('rate');

		// 1. 환율 0 또는 음수 체크
		if ($rate <= 0) {
			$this->session->set_flashdata('error', 'La tasa (rate) debe ser mayor que cero.');
			// 수정 시에는 해당 페이지 또는 모달 유지를 위해 index로 가되 에러 메시지 전달
			redirect('exchange/index'); 
			return;
		}

		// 2. 중복 체크 (본인 제외)
		if ($this->Exchange_model->check_duplicate($base, $target, $date, $id)) {
			$this->session->set_flashdata('error', 'No se puede actualizar. Existe otro registro idéntico.');
			redirect('exchange/index');
			return;
		}

		$data = array(
			'base_currency'   => $base,
			'target_currency' => $target,
			'rate'            => $rate,
			'effective_date'  => $date,
			'created_by'      => $this->session->userdata('user_id') ?? 1
		);

		if ($this->Exchange_model->update_rate($id, $data)) {
			$this->session->set_flashdata('success', 'Actualización completada.');
		} else {
			$this->session->set_flashdata('error', 'Error al intentar actualizar.');
		}
		redirect('exchange/index');
	}
  
	public function edit($id) {
        $data['rate_item'] = $this->Exchange_model->get_rate_by_id($id);
        if (!$data['rate_item']) {
            $this->session->set_flashdata('error', 'Registro no encontrado.');
            redirect('exchange/index');
        }
        
        $data['main'] = 'exchange/edit';
        $this->load->view('layout', $data);
    }

	public function aux(){
		
		$url = "https://serums-proyecto.onrender.com/api/hospitales/map?profesion=MEDICINA&grado_dificultad=GD-5&serums_periodo=2026-I";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // HTTPS 인증서 무시 필요 시

		$response = curl_exec($ch);
		curl_close($ch);

		// 데이터 변환 및 저장
		$result = json_decode($response, true);
		
		//echo "Total: ".count($result)." instituciones<br/><br/><br/>";
		
		$rows = [];
		$row = [
			'id',
			//'profesion',
			//'profesiones',
			'institucion',
			'departamento',
			'provincia',
			'distrito',
			'grado_dificultad',
			'codigo_renipress_modular',
			'nombre_establecimiento',
			'presupuesto',
			'categoria',
			'zaf',
			'ze',
			//'imagenes',
			'lat',
			'lng',
			'coordenadas_fuente',
			//'override_updated_at',
			'updated_at',
			//'encaps_puntaje_2025_i',
			//'encaps_serumista_2025_i',
			'serums_ofertas',
			'encaps_2025_i_periodo',
			'encaps_2025_i_modalidad',
			'encaps_2025_i_profesion',
			'encaps_2025_i_serumista',
			'encaps_2025_i_nota',
		];
		
		$rows[] = $row;
		//print_r($row); echo "<br/><br/>";
		
		foreach($result as $i => $item_result){
			if ($i > 1) break;
			
			$details = $this->aux_($item_result['id']);
			
			//print_r($details);
		
			$id = (string)$details['id'];
			$profesion = $details['profesion'];
			$profesiones = $details['profesiones'];
			$institucion = $details['institucion'];
			$departamento = $details['departamento'];
			$provincia = $details['provincia'];
			$distrito = $details['distrito'];
			$grado_dificultad = $details['grado_dificultad'];
			$codigo_renipress_modular = $details['codigo_renipress_modular'];
			$nombre_establecimiento = $details['nombre_establecimiento'];
			$presupuesto = $details['presupuesto'];
			$categoria = $details['categoria'];
			$zaf = $details['zaf'];
			$ze = $details['ze'];
			$imagenes = $details['imagenes'];
			$lat = $details['lat'];
			$lng = $details['lng'];
			$coordenadas_fuente = $details['coordenadas_fuente'];
			$override_updated_at = $details['override_updated_at'];
			$updated_at = $details['updated_at'];
			$encaps_puntaje_2025_i = $details['encaps_puntaje_2025_i'];
			$encaps_serumista_2025_i = $details['encaps_serumista_2025_i'];
			
			
			$serums_ofertas = $details['serums_ofertas'];
			
			$aux = [];
					
			foreach($details['serums_ofertas'] as $v){
				
				if ($v['profesion'] === 'MEDICINA'){
				
					$aux[] = $v['periodo'].", ".$v['modalidad'].", ".$v['profesion'].", ".$v['plazas']." Plazas";

				}
			}
			
			$serums_ofertas = implode("<br/>", $aux);
			$serums_ofertas = $aux ? $aux[0] : "";
			
			//$serums_resumen = $details['serums_resumen']; //no se necesita
			
			$encaps_2025_i = $details['encaps_2025_i'];
			
			foreach($details['encaps_2025_i'] as $v){
				
				if ($v['profesion'] === 'MEDICINA'){
					//print_r($v); 
					
					$entries = $v['entries'];
					foreach($entries as $entry){
						//print_r($entry); 
						
						$encaps_2025_i_periodo = $v['periodo'];
						$encaps_2025_i_modalidad = $v['modalidad'];
						$encaps_2025_i_profesion = $v['profesion'];
						$encaps_2025_i_serumista = $entry['serumista'];
						$encaps_2025_i_nota = $entry['nota'];
						
						$row = [
							$id,	
							//$profesion,	
							//$profesiones,	
							$institucion,	
							$departamento,	
							$provincia,	
							$distrito,	
							$grado_dificultad,	
							$codigo_renipress_modular,	
							$nombre_establecimiento,	
							$presupuesto,	
							$categoria,	
							$zaf,	
							$ze,	
							//$imagenes,	
							$lat,	
							$lng,	
							$coordenadas_fuente,	
							//$override_updated_at,	
							$updated_at,	
							//$encaps_puntaje_2025_i,	
							//$encaps_serumista_2025_i,	
							$serums_ofertas,
							$encaps_2025_i_periodo,	
							$encaps_2025_i_modalidad,	
							$encaps_2025_i_profesion,	
							$encaps_2025_i_serumista,	
							$encaps_2025_i_nota,
						];
						
						$rows[] = $row;
						
						//print_r($row); echo "<br/><br/>";
						
						//echo $encaps_2025_i_nota."<br/><br/><br/><br/>";
					}
				}
			}
			
			
		}
		
		
		echo "<table>";
		
		foreach($rows as $row){
			
			//print_r($row);
			
			/*
			*/
			echo "<tr>";
			
			foreach($row as $item) echo "<td>".$item."</td>";
			
			echo "</tr>";
		}
		
		echo "</table>";
		
		
	}
	
	public function aux_($id){
		
		$url = "https://serums-proyecto.onrender.com/api/hospitales/".$id;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // HTTPS 인증서 무시 필요 시

		$response = curl_exec($ch);
		curl_close($ch);

		// 데이터 변환 및 저장
		$result = json_decode($response, true);
		
		return $result;
	}
}