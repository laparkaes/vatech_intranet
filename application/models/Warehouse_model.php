<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para la gestión de Almacenes (Warehouses)
 * Proporciona métodos para el listado paginado, búsqueda y CRUD.
 */
class Warehouse_model extends CI_Model {

    protected $table = 'warehouses';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Cuenta el total de registros para la paginación, permitiendo filtrar por búsqueda.
     * @param string|null $keyword Término de búsqueda
     * @return int Total de filas
     */
    public function count_all_warehouses($keyword = null) {
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('name', $keyword);
            $this->db->or_like('address', $keyword);
            $this->db->group_end();
        }
        return $this->db->count_all_results($this->table);
    }

    /**
     * Obtiene los almacenes con límite y desplazamiento (Offset) para paginación.
     * @param int $limit Cantidad de registros por página
     * @param int $start Punto de inicio (Offset)
     * @param string|null $keyword Té름ino de búsqueda
     * @return array Listado de almacenes con datos de la entidad relacionada
     */
    public function get_warehouses_paged($limit, $start, $keyword = null) {
        $this->db->select('w.*, e.name as entity_name');
        $this->db->from($this->table . ' w');
        $this->db->join('entities e', 'w.contractor_entity_id = e.id', 'left');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('w.name', $keyword);
            $this->db->or_like('w.address', $keyword);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by('w.id', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Obtiene el detalle de un almacén incluyendo el nombre de la entidad.
     * @param int $id ID del almacén
     * @return object Datos del almacén
     */
    public function get_warehouse_detail($id) {
        $this->db->select('w.*, e.name as entity_name');
        $this->db->from($this->table . ' w');
        $this->db->join('entities e', 'w.contractor_entity_id = e.id', 'left');
        $this->db->where('w.id', $id);
        
        return $this->db->get()->row();
    }

    /**
     * Obtiene un registro simple por su ID.
     * @param int $id ID del almacén
     * @return object Registro del almacén
     */
    public function get_warehouse_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    /**
     * Inserta un nuevo registro de almacén.
     * @param array $data Datos del almacén
     * @return bool
     */
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Actualiza un registro existente.
     * @param int $id ID del almacén
     * @param array $data Datos a actualizar
     * @return bool
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
}