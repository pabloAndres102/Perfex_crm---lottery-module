<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Municipios_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Contar todos los Municipios
    public function get_count_municipios()
    {
        return $this->db->count_all(db_prefix() . 'lottery_municipios'); // Contar el total de Municipios
    }

    // Obtener Municipios con paginación
    public function get_municipios_paginated($limit, $start)
    {
        $this->db->limit($limit, ($start - 1) * $limit);
        $query = $this->db->get('lottery_municipios'); // Verifica que la tabla sea 'municipios'
        return $query->result(); // Asegúrate de devolver un array de resultados
    }
    

    // Insertar un nuevo Municipio
    public function insert_municipio($nombre, $descripcion)
    {
        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'fecha_creacion' => date('Y-m-d H:i:s'), // Fecha de creación
            'fecha_modificacion' => date('Y-m-d H:i:s') // Fecha de modificación inicial
        ];
        return $this->db->insert(db_prefix() . 'lottery_municipios', $data); // Inserta un nuevo Municipio
    }

    // Obtener Municipio por ID
    public function get_municipio_by_id($id)
    {
        return $this->db->where('id', $id)
                        ->get(db_prefix() . 'lottery_municipios')
                        ->row(); // Retorna un solo Municipios
    }

    // Actualizar Municipios
    public function update_municipio($id, $nombre, $descripcion)
    {
        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'fecha_modificacion' => date('Y-m-d H:i:s') // Actualizamos la fecha de modificación
        ];
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'lottery_municipios', $data); // Actualiza el Municipio
    }

    // Eliminar Municipio
    public function delete_municipio($id)
    {
        return $this->db->where('id', $id)
                        ->delete(db_prefix() . 'lottery_municipios'); // Elimina el Municipio por ID
    }
}
