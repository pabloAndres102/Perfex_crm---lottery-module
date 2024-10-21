<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mascotas_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Función para obtener todas las mascotas
    public function get_mascotas()
    {
        return $this->db->get('lottery_mascotas')->result_array();
    }
    public function mascota_exists($especie)
    {
        $this->db->where('especie', $especie);
        $query = $this->db->get('lottery_mascotas');
        if ($query->num_rows() > 0) {
            return true;  // La mascota ya existe
        }
        return false; // No existe
    }

    // Función para obtener una mascota por su ID
    public function get_mascota($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('lottery_mascotas');
        return $query->row(); // Devuelve una sola fila
    }

    // Función para agregar una nueva mascota
    public function add_mascota($data)
    {
        $this->db->insert('lottery_mascotas', $data);
        return $this->db->insert_id();
    }

    // Función para editar una mascota
    public function edit_mascota($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('lottery_mascotas', $data);
        return $this->db->affected_rows();
    }

    // Función para eliminar una mascota
    public function delete_mascota($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('lottery_mascotas');
        return $this->db->affected_rows();
    }
}
