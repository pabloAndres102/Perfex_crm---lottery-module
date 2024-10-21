<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Documentos_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Obtener todos los tipos de documentos
    public function get_documentos()
    {
        return $this->db->get('lottery_tipos_documentos')->result_array();
    }

    // Obtener un tipo de documento por su ID
    public function get_documento($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('lottery_tipos_documentos')->row();
    }

    // Verificar si un tipo de documento ya existe
    public function documento_exists($nombre)
    {
        $this->db->where('nombre', $nombre);
        return $this->db->get('lottery_tipos_documentos')->num_rows() > 0;
    }

    // Agregar un nuevo tipo de documento
    public function add_documento($data)
    {
        $this->db->insert('lottery_tipos_documentos', $data);
        return $this->db->insert_id();
    }

    // Editar un tipo de documento existente
    public function edit_documento($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('lottery_tipos_documentos', $data);
        return $this->db->affected_rows();
    }

    // Eliminar un tipo de documento
    public function delete_documento($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('lottery_tipos_documentos');
        return $this->db->affected_rows();
    }
}
