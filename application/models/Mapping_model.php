<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo Genérico para la gestión de Mappings
 * Proporciona datos maestros para dropdowns y validaciones del sistema.
 */
class Mapping_model extends CI_Model {

    /**
     * Obtiene una lista de mapeos activos filtrados por categoría.
     * @param string $category Categoría definida en la tabla (ej. 'po_status', 'currency').
     * @return array Lista de objetos con los mapeos.
     */
    public function get_list($category) {
        return $this->db->where([
                            'category'  => $category, 
                            'is_active' => 1
                        ])
                        ->order_by('sort_order', 'ASC')
                        ->get('mappings')
                        ->result();
    }

    /**
     * Obtiene el ID de un mapeo específico basándose en su categoría y valor de código.
     * @param string $category Categoría del mapeo.
     * @param string $code Valor real almacenado (ej. 'Registrado', 'USD').
     * @return int|null Retorna el ID del registro o NULL si no existe.
     */
    public function get_id_by_code($category, $code) {
        $row = $this->db->get_where('mappings', [
            'category'   => $category, 
            'code_value' => $code
        ])->row();

        return $row ? (int)$row->id : NULL;
    }

    /**
     * Obtiene el nombre descriptivo (display_name) basándose en el ID.
     * Útil para mostrar etiquetas rápidas sin necesidad de JOINs complejos.
     */
    public function get_display_name($id) {
        $row = $this->db->get_where('mappings', ['id' => $id])->row();
        return $row ? $row->display_name : '';
    }
}