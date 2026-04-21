<?php
class Exchange_model extends CI_Model {

    /* Obtener historial de tasas ordenadas por fecha */
    public function get_all_rates() {
		$this->db->select('er.*, u.full_name as user_name');
		$this->db->from('exchange_rates er');
		$this->db->join('users u', 'er.created_by = u.id', 'left');
		$this->db->order_by('er.effective_date', 'DESC');
		return $this->db->get()->result();
	}

    /* Insertar nueva tasa */
    public function insert_rate($data) {
        return $this->db->insert('exchange_rates', $data);
    }

    /**
	 * Obtiene la tasa más reciente filtrando por la moneda base.
	 * Explicación: Se cambió 'currency_code' por 'base_currency' para coincidir con la estructura de la tabla.
	 */
	public function get_latest_rate($base = 'USD') {
		// Se ajusta el nombre de la columna según el error 1054
		$this->db->where('base_currency', $base); 
		$this->db->order_by('effective_date', 'DESC');
		$this->db->limit(1);
		
		$query = $this->db->get('exchange_rates');
		return $query->row();
	}

	/* Obtener una tasa especifica por ID */
	public function get_rate_by_id($id) {
		return $this->db->get_where('exchange_rates', array('id' => $id))->row();
	}

	/* Actualizar datos de la tasa */
	public function update_rate($id, $data) {
		$this->db->where('id', $id);
		return $this->db->update('exchange_rates', $data);
	}
	
	/* Verificar si ya existe un registro para la misma moneda y fecha */
	public function check_duplicate($base, $target, $date, $exclude_id = null) {
		$this->db->where('base_currency', $base);
		$this->db->where('target_currency', $target);
		$this->db->where('effective_date', $date);
		
		// Si es una edicion, excluir el ID actual de la verificacion
		if ($exclude_id !== null) {
			$this->db->where('id !=', $exclude_id);
		}
		
		$query = $this->db->get('exchange_rates');
		return $query->num_rows() > 0;
	}

	/**
	 * 특정 날짜 이전의 최신 데이터 30개를 찾아 평균 환율 계산
	 * @param string $base    기준 통화
	 * @param string $target  대상 통화
	 * @param string $date    기준일 (해당일 포함 이전 데이터 검색)
	 * @param int    $limit   평균을 구할 데이터 개수 (기본 30개)
	 * @return float          최근 n개 데이터의 평균 환율
	 */
	public function get_rolling_average($base, $target, $date, $limit = 30) {
		// 1. 최신 데이터 n개를 가져오는 서브쿼리 작성
		$this->db->select('rate');
		$this->db->from('exchange_rates');
		$this->db->where('base_currency', $base);
		$this->db->where('target_currency', $target);
		$this->db->where('effective_date <=', $date); // 기준일 포함 이전 데이터
		$this->db->order_by('effective_date', 'DESC');
		$this->db->limit($limit);
		
		$subquery = $this->db->get_compiled_select();

		// 2. 위에서 추출한 n개 데이터를 대상으로 평균(AVG) 계산
		// SQL 구조: SELECT AVG(rate) FROM (SELECT rate FROM ... LIMIT 30) as temp
		$query = $this->db->query("SELECT AVG(rate) as rolling_avg FROM ($subquery) as temp_table");
		$row = $query->row();

		return $row->rolling_avg ? (float)$row->rolling_avg : 0.0;
	}
	
	/**
	 * 환율 테이블의 전체 레코드 개수 반환 (페이지네이션용)
	 */
	public function count_all_rates() {
		return $this->db->count_all('exchange_rates'); // 테이블명 'exchange_rates'를 실제 이름으로 확인하세요.
	}

	/**
	 * 페이지네이션을 위한 구간별 데이터 조회
	 * @param int $limit  가져올 개수 (30)
	 * @param int $start  시작 오프셋
	 */
	public function get_rates_paged($limit, $start) {
		$this->db->select('er.*, u.full_name as user_name');
		$this->db->from('exchange_rates er');
		$this->db->join('users u', 'er.created_by = u.id', 'left');
		$this->db->order_by('er.effective_date', 'DESC'); // 최신순 정렬
		$this->db->limit($limit, $start);
		
		$query = $this->db->get();
		return $query->result();
	}
}