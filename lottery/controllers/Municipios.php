<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Municipios extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Municipios_model'); // Cargamos el modelo
        $this->load->library('pagination'); // Cargamos la librería de paginación
    }


    public function config($id)
    {
        // Eliminar el municipio
        $this->Municipios_model->delete_municipio($id);
        set_alert('success', 'Municipios eliminado correctamente.');
        redirect('lottery/configurations');
    }

    public function index()
    {
        // Configuración de paginación
        $config['base_url'] = admin_url('lottery/municipios/index'); // URL base para paginación
        $config['total_rows'] = $this->Municipios_model->get_count_Municipios(); // Contar el total de Municipios
        $config['per_page'] = 10; // Número de Municipios por página
        $config['uri_segment'] = 4; // El segmento de la URI donde está el número de página
        $config['use_page_numbers'] = TRUE; // Usar números de página
        $config['full_tag_open'] = '<ul class="pagination">'; // Etiquetas para la paginación
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'Primera';
        $config['last_link'] = 'Última';
        $config['next_link'] = 'Siguiente';
        $config['prev_link'] = 'Anterior';
        
        $this->pagination->initialize($config); // Inicializar la paginación

        // Obtener los Municipios con paginación
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1; // Obtener la página actual
        $data['municipios'] = $this->Municipios_model->get_municipios_paginated($config['per_page'], $page); // Obtener los Municipios
        $data['title'] = 'Listado de Municipios';
        $data['pagination'] = $this->pagination->create_links(); // Generar los enlaces de paginación

        $this->load->view('lottery/Municipios/list_municipios', $data); // Vista para mostrar los Municipios
    }

    // Crear municipio
    public function create()
    {
        // Si el método es POST, validar y guardar el municipio
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $this->input->post('nombre');
            $descripcion = $this->input->post('descripcion');
            $this->Municipios_model->insert_municipio($nombre, $descripcion);
            set_alert('success', 'Municipio creado correctamente.');
            redirect('lottery/municipios');
        }

        $data['title'] = 'Crear Municipios';
        $this->load->view('lottery/Municipios/create_municipio', $data); // Vista para crear Municipios
    }

    // Editar municipio
    public function edit($id)
    {
        // Obtener los datos del municipio por id
        $data['municipio'] = $this->Municipios_model->get_municipio_by_id($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar y actualizar el municipio
            $nombre = $this->input->post('nombre');
            $descripcion = $this->input->post('descripcion');
            $this->Municipios_model->update_municipio($id, $nombre, $descripcion);
            set_alert('success', 'Municipio actualizado correctamente.');
            redirect('lottery/municipios');
        }

        $data['title'] = 'Editar Municipio';
        $this->load->view('lottery/Municipios/edit_municipio', $data); // Vista para editar municipio
    }

    // Eliminar municipio
    public function delete($id)
    {
        // Eliminar el municipio
        $this->Municipios_model->delete_municipio($id);
        set_alert('success', 'Municipio eliminado correctamente.');
        redirect('lottery/municipios');
    }
}
