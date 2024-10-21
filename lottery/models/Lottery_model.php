<?php
class Lottery_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insert_client($data)
    {
        return $this->db->insert(db_prefix() . 'lottery_clientes', $data);
    }
    public function insert_client_modal($data)
    {
        return $this->db->insert(db_prefix() . 'lottery_clientes', $data);
    }

    public function delete_client($id)
    {
        return $this->db->delete(db_prefix() . 'lottery_clientes', array('id' => $id));
    }

    public function get_client_by_id($id)
    {
        return $this->db->get_where(db_prefix() . 'lottery_clientes', array('id' => $id))->row_array();
    }
    public function get_client_by_id2($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get(db_prefix() . 'lottery_clientes');
        return $query->row();
    }
    public function get_client_by_document($documento_identidad)
    {
        $this->db->where('documento_identidad', $documento_identidad);
        $query = $this->db->get(db_prefix() . 'lottery_clientes');
        return $query->row();
    }

    public function update_client($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'lottery_clientes', $data);
    }


    // ------------------------------------------------
    public function insert_draw($data)
    {
        return $this->db->insert(db_prefix() . 'lottery_sorteo', $data);;
    }

    public function get_all_draws()
    {
        return $this->db->get(db_prefix() . 'lottery_sorteo')->result_array();
    }



    public function update_draw($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'lottery_sorteo', $data);
    }



    public function get_draw_by_id($draw_id)
    {
        return $this->db->get_where(db_prefix() . 'lottery_sorteo', ['id' => $draw_id])->row_array();
    }

    public function get_all_clients()
    {
        return $this->db->get(db_prefix() . 'lottery_clientes')->result_array();
    }

    public function get_clients_by_draw($draw_id)
    {
        // Seleccionar todos los campos de la tabla 'clientes' y el campo 'valor_factura' de la tabla 'sorteo_clientes'
        $this->db->select('c.*, sc.valor_factura');
        $this->db->from(db_prefix() . 'lottery_sorteo_clientes sc');
        $this->db->join(db_prefix() . 'lottery_clientes c', 'sc.cliente_id = c.id', 'inner');
        $this->db->where('sc.sorteo_id', $draw_id);
        $query = $this->db->get();

        // Retornar el resultado como un array de resultados
        return $query->result_array();
    }


    public function update_invoice_value_by_client_and_draw($cliente_id, $sorteo_id, $valor)
    {
        // Actualizar el valor de la factura sumando el nuevo valor al existente
        $this->db->set('valor', 'valor + ' . (float)$valor, FALSE);
        $this->db->where('cliente_id', $cliente_id);
        $this->db->where('sorteo_id', $sorteo_id); // Asegurarse de que se actualice para el sorteo correcto
        return $this->db->update(db_prefix() . 'lottery_facturas');
    }


    public function associate_client_to_draw($sorteo_id, $cliente_id, $valor_factura)
    {
        // Verificar si ya existe un registro para este cliente y sorteo
        $this->db->where('sorteo_id', $sorteo_id);
        $this->db->where('cliente_id', $cliente_id);
        $query = $this->db->get(db_prefix() . 'lottery_sorteo_clientes');

        if ($query->num_rows() > 0) {
            // Si existe, actualizar el valor
            $row = $query->row();
            $nuevo_valor = $row->valor_factura + $valor_factura;
            $this->db->set('valor_factura', $nuevo_valor);
            $this->db->where('id', $row->id);
            $this->db->update(db_prefix() . 'lottery_sorteo_clientes');

            $this->db->where('cliente_id', $cliente_id);
            $this->db->where('sorteo_id', $sorteo_id); // Asegúrate de que estás usando el sorteo correcto
            $this->db->set('valor', $nuevo_valor);
            $this->db->update(db_prefix() . 'lottery_facturas');
        } else {
            // Si no existe, insertar un nuevo registro
            $data = array(
                'sorteo_id' => $sorteo_id,
                'cliente_id' => $cliente_id,
                'valor_factura' => $valor_factura
            );
            $this->db->insert(db_prefix() . 'lottery_sorteo_clientes', $data);
        }
    }


    public function clear_clients_from_draw($draw_id)
    {
        return $this->db->delete(db_prefix() . 'lottery_sorteo_clientes', ['sorteo_id' => $draw_id]);
    }

    ////////////////////////////////////////////////// ////////////////////////////////////////////////// 

    public function insert_invoice($data)
    {
        return $this->db->insert(db_prefix() . 'lottery_facturas', $data);
    }

    public function update_invoice_value_by_client($cliente_id, $additional_value)
    {
        // Obtener el valor actual de la factura para el cliente dado
        $this->db->where('cliente_id', $cliente_id);
        $query = $this->db->get(db_prefix() . 'lottery_facturas');

        if ($query->num_rows() > 0) {
            // Suponemos que solo hay una factura por cliente, si hay más de una deberías considerar cómo manejarlas.
            $current_value = $query->row()->valor;

            // Sumar el valor adicional al valor existente
            $new_value = $current_value + $additional_value;

            // Actualizar el registro con el nuevo valor en la tabla lottery_facturas
            $this->db->where('cliente_id', $cliente_id);
            $this->db->update(db_prefix() . 'lottery_facturas', array('valor' => $new_value));

            // También actualizar el valor en la tabla lottery_sorteo_clientes
            $this->db->where('cliente_id', $cliente_id);
            $this->db->update(db_prefix() . 'lottery_sorteo_clientes', array('valor_factura' => $new_value));
        }
    }






    // Obtener todas las facturas
    public function get_paginated_invoices($limit, $offset, $cliente_id = null, $search = null, $search_name = null, $search_sorteo = null, $search_local = null, $fecha_inicio = null, $fecha_fin = null)
    {
        $this->db->select('f.*, c.nombre as cliente_nombre, c.apellido, l.nombre as nombre_local, s.nombre as nombre_sorteo');
        $this->db->from(db_prefix() . 'lottery_facturas f');
        $this->db->join(db_prefix() . 'lottery_clientes c', 'f.cliente_id = c.id', 'inner');
        $this->db->join(db_prefix() . 'lottery_locales_comerciales l', 'f.local_id = l.id', 'inner');
        $this->db->join(db_prefix() . 'lottery_sorteo s', 'f.sorteo_id = s.id', 'inner');

        // Filtro por cliente
        if ($cliente_id !== null) {
            $this->db->where('f.cliente_id', $cliente_id);
        }

        // Filtro por nombre del sorteo
        if ($search_sorteo !== null) {
            $this->db->like('s.nombre', $search_sorteo, 'both');
        }

        // Filtro por búsqueda de número de factura
        if ($search !== null) {
            $this->db->like('f.numero_factura', $search, 'both');
        }

        // Filtro por nombre o apellido del cliente
        if ($search_name !== null) {
            $this->db->group_start(); // Comienza un grupo de condiciones OR
            $this->db->like('c.nombre', $search_name, 'both');  // Buscar en el nombre
            $this->db->or_like('c.apellido', $search_name, 'both');  // Buscar en el apellido
            $this->db->group_end(); // Finaliza el grupo de condiciones OR
        }

        // Filtro por nombre del local
        if ($search_local !== null) {
            $this->db->like('l.nombre', $search_local, 'both');
        }

        // Filtros por fecha
        if ($fecha_inicio && $fecha_fin) {
            $this->db->where('f.fecha_emision >=', $fecha_inicio);
            $this->db->where('f.fecha_emision <=', $fecha_fin);
        } elseif ($fecha_inicio) {
            $this->db->where('f.fecha_emision >=', $fecha_inicio);
        } elseif ($fecha_fin) {
            $this->db->where('f.fecha_emision <=', $fecha_fin);
        }

        // Aplicar límite y offset
        $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }




    public function count_invoices($search = null, $fecha_inicio = null, $fecha_fin = null, $cliente_id = null)
    {
        $this->db->from(db_prefix() . 'lottery_facturas f');

        // Filtro por cliente si es proporcionado
        if ($cliente_id !== null) {
            $this->db->where('f.cliente_id', $cliente_id);
        }

        // Filtro por búsqueda de número de factura si es proporcionado
        if ($search !== null) {
            $this->db->like('f.numero_factura', $search, 'both');  // Búsqueda con LIKE para coincidencias parciales
        }

        // Filtros por fecha si están presentes
        if ($fecha_inicio && $fecha_fin) {
            $this->db->where('f.fecha_emision >=', $fecha_inicio);
            $this->db->where('f.fecha_emision <', date('Y-m-d', strtotime($fecha_fin . ' +1 day')));  // Ajustar fecha final
        } elseif ($fecha_inicio) {
            $this->db->where('f.fecha_emision >=', $fecha_inicio);
        } elseif ($fecha_fin) {
            $this->db->where('f.fecha_emision <', date('Y-m-d', strtotime($fecha_fin . ' +1 day')));
        }

        return $this->db->count_all_results();  // Devuelve el número total de facturas filtradas
    }




    public function get_invoice_by_id($id)
    {
        return $this->db->get_where(db_prefix() . 'lottery_facturas', array('id' => $id))->row_array();
    }
    public function update_invoice($id, $data)
    {

        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'lottery_facturas', $data);
    }

    public function delete_invoice($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete(db_prefix() . 'lottery_facturas');
    }

    // locales comerciales
    public function insert_local($data)
    {
        return $this->db->insert(db_prefix() . 'lottery_locales_comerciales', $data);
    }

    public function get_all_locales()
    {
        return $this->db->get(db_prefix() . 'lottery_locales_comerciales')->result_array();
    }

    public function get_local_by_id($id)
    {
        return $this->db->get_where(db_prefix() . 'lottery_locales_comerciales', array('id' => $id))->row_array();
    }

    public function update_local($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'lottery_locales_comerciales', $data);
    }

    public function update_local_status($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update('lottery_locales_comerciales', array('activo' => $status));
    }
    public function get_all_patrocinadores()
    {
        $query = $this->db->get(db_prefix() . 'lottery_locales_comerciales'); // Asegúrate de que el nombre de la tabla es correcto
        return $query->result_array();
    }
}
