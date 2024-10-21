<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barrios_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Contar todos los barrios
    public function get_count_barrios()
    {
        return $this->db->count_all(db_prefix() . 'lottery_barrios'); // Contar el total de barrios
    }

    // Obtener barrios con paginación
    public function get_barrios_paginated($limit, $start)
    {
        $this->db->order_by('nombre', 'ASC'); // Ordenar por nombre
        return $this->db->get(db_prefix() . 'lottery_barrios', $limit, $start)->result(); // Retorna los barrios con límite y offset
    }

    // Insertar un nuevo barrio
    public function insert_barrio($nombre, $descripcion)
    {
        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'fecha_creacion' => date('Y-m-d H:i:s'), // Fecha de creación
            'fecha_modificacion' => date('Y-m-d H:i:s') // Fecha de modificación inicial
        ];
        return $this->db->insert(db_prefix() . 'lottery_barrios', $data); // Inserta un nuevo barrio
    }

    // Obtener barrio por ID
    public function get_barrio_by_id($id)
    {
        return $this->db->where('id', $id)
                        ->get(db_prefix() . 'lottery_barrios')
                        ->row(); // Retorna un solo barrio
    }

    // Actualizar barrio
    public function update_barrio($id, $nombre, $descripcion)
    {
        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'fecha_modificacion' => date('Y-m-d H:i:s') // Actualizamos la fecha de modificación
        ];
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'lottery_barrios', $data); // Actualiza el barrio
    }

    // Eliminar barrio
    public function delete_barrio($id)
    {
        return $this->db->where('id', $id)
                        ->delete(db_prefix() . 'lottery_barrios'); // Elimina el barrio por ID
    }
}
