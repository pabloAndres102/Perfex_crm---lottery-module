<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Categorias_locales_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Obtener todas las categorías de locales
    public function get_categorias_locales()
    {
        return $this->db->get(db_prefix() . 'lottery_categorias_locales')->result_array();
    }

    // Obtener una categoría específica
    public function get_categoria_local($id)
    {
        return $this->db->where('id', $id)->get(db_prefix() . 'lottery_categorias_locales')->row();
    }

    // Verificar si existe una categoría con el mismo nombre
    public function categoria_local_exists($nombre)
    {
        return $this->db->where('nombre', $nombre)->get(db_prefix() . 'lottery_categorias_locales')->row();
    }

    // Agregar una nueva categoría
    public function add_categoria_local($data)
    {
        $this->db->insert(db_prefix() . 'lottery_categorias_locales', $data);
        return $this->db->insert_id();
    }

    // Editar una categoría
    public function edit_categoria_local($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'lottery_categorias_locales', $data);
        return $this->db->affected_rows();
    }

    // Eliminar una categoría
    public function delete_categoria_local($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'lottery_categorias_locales');
        return $this->db->affected_rows();
    }
}
