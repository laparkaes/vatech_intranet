<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador de Compras (Purchase Orders)
 * Gestiona la lista y creación de órdenes de compra.
 */
class Purchase extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('purchase_model');
    }

    /**
     * Lista de Órdenes de Compra
     */
    public function index() {
        $data['orders'] = $this->purchase_model->get_all_pos();
        $data['main'] = 'purchase/index';
        $this->load->view('layout', $data);
    }

    /**
     * Muestra el formulario para crear una nueva Orden de Compra (PO)
     */
    public function create() {
        // Se filtran las entidades que son proveedores (is_vendor) y están activas (status)
        $data['suppliers'] = $this->db->get_where('entities', [
            'is_vendor' => 1, 
            'status'    => 1
        ])->result();
        
        // Obtiene la lista de productos e ítems para la selección en el formulario
        $this->db->select('pi.id, p.name, pi.option');
        $this->db->from('product_items pi');
        $this->db->join('products p', 'pi.product_id = p.id');
        $data['items'] = $this->db->get()->result();

        $data['main'] = 'purchase/create';
        $this->load->view('layout', $data);
    }
	
	/**
     * Procesa el guardado de la Orden de Compra con campos de negocio avanzados
     */
    public function add() {
        $this->load->library('form_validation');

        // Validaciones básicas
        $this->form_validation->set_rules('supplier_id', 'Proveedor', 'required|numeric');
        $this->form_validation->set_rules('issue_date', 'Fecha de Emisión', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->create(); 
        } else {
            // Preparación de datos del encabezado (Header)
            $po_data = [
                'po_number'       => 'VPR-PO-' . date('Ymd-His'), 
                'supplier_id'     => $this->input->post('supplier_id'),
                'po_type'         => $this->input->post('po_type'),
                'currency'        => $this->input->post('currency'),
                'incoterms'       => $this->input->post('incoterms'),
                'payment_terms'   => $this->input->post('payment_terms'), // Términos de pago
                'shipping_method' => $this->input->post('shipping_method'), // Método de envío
                'issue_date'      => $this->input->post('issue_date'),
                'expected_date'   => $this->input->post('expected_date'),
                'notes'           => $this->input->post('notes'), // Observaciones adicionales
                'status'          => 'Draft', 
                'created_by'      => $this->session->userdata('user_id') ?: 1
            ];

            $items = $this->input->post('items');

            // Guardado mediante transacción en el modelo
            $result = $this->purchase_model->save_full_po($po_data, $items);

            if ($result) {
                $this->session->set_flashdata('success', 'Orden de Compra guardada exitosamente en modo Borrador.');
                redirect('purchase');
            } else {
                $this->session->set_flashdata('error', 'Error al guardar la orden. Verifique la integridad de los datos.');
                redirect('purchase/create');
            }
        }
    }

	/**
	 * Ver el detalle de una Orden de Compra (PO)
	 * Se ajusta al mismo estilo de renderizado que la función create()
	 * * @param int $id ID de la Orden de Compra
	 */
	public function view($id) {
		// 1. Obtener los datos del encabezado (Header)
		$data['po'] = $this->purchase_model->get_po_header($id);
		
		// 2. Obtener los ítems detallados (Items)
		$data['items'] = $this->purchase_model->get_po_items($id);

		// Validar si la PO existe en la base de datos
		if (!$data['po']) {
			show_404();
		}

		// 3. DEFINIR LA RUTA DE LA VISTA (Igual que en create)
		// El layout se encargará de cargar esta ruta usando $this->load->view($main)
		$data['main'] = 'purchase/view';

		// 4. CARGAR EL LAYOUT CON TODOS LOS DATOS
		$this->load->view('layout', $data);
	}
	
	/**
	 * Procesa la actualización de estado con validación de datos post
	 */
	public function update_status($id) {
		$current_user_id = $this->session->userdata('user_id');
		$user_role = $this->session->userdata('role');

		// 1. Validar si el ID existe
		$po = $this->purchase_model->get_po_header($id);
		if (!$po) {
			show_404();
		}

		// 2. Obtener el estado del formulario y validar que no esté vacío
		$status = $this->input->post('status');
		if (empty($status)) {
			// Si el post viene vacío, forzamos un error para no borrar el dato en DB
			show_error("El estado enviado está vacío. Verifique el formulario.");
		}

		// 3. Verificación de permisos (Admin o Logística que no sea el creador)
		$is_admin = ($user_role === 'admin');
		$is_logistics = ($user_role === 'logistics');
		$is_not_creator = ($po->created_by != $current_user_id);

		if ($is_admin || ($is_logistics && $is_not_creator)) {
			// 4. Ejecutar actualización en el modelo
			if ($this->purchase_model->update_po_status($id, $status, $current_user_id)) {
				redirect('purchase/view/' . $id);
			} else {
				show_error("Error técnico al actualizar en la base de datos.");
			}
		} else {
			show_error("No tiene permisos para realizar esta operación.", 403);
		}
	}

	/**
	 * Cancela (Anula) una Orden de Compra.
	 * Solo se permite si la PO está en estado 'Draft'.
	 * * @param int $id ID de la Orden de Compra
	 */
	public function cancel($id) {
		$po = $this->purchase_model->get_po_header($id);
		$current_user = $this->session->userdata('user_id');

		// Validación de seguridad: Solo el creador y solo si es Draft
		if ($po && $po->status === 'Draft' && $po->created_by == $current_user) {
			$result = $this->purchase_model->update_status($id, 'Cancelled');
			
			if ($result) {
				$this->session->set_flashdata('success', 'La Orden de Compra ha sido anulada correctamente.');
			} else {
				$this->session->set_flashdata('error', 'Error al intentar anular la orden.');
			}
		} else {
			$this->session->set_flashdata('error', 'No tiene permisos para anular esta orden o el estado no lo permite.');
		}

		redirect('purchase/view/'.$id);
	}

}