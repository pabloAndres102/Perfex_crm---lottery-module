<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barrios extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barrios_model'); // Cargamos el modelo
        $this->load->library('pagination'); // Cargamos la librería de paginación
    }


    public function config($id)
    {
        // Eliminar el barrio
        $this->Barrios_model->delete_barrio($id);
        set_alert('success', 'Barrio eliminado correctamente.');
        redirect('lottery/configurations');
    }

    public function index()
    {
        // Configuración de paginación
        $config['base_url'] = admin_url('lottery/barrios/index'); // URL base para paginación
        $config['total_rows'] = $this->Barrios_model->get_count_barrios(); // Contar el total de barrios
        $config['per_page'] = 10; // Número de barrios por página
        $config['uri_segment'] = 4; // El segmento de la URI donde está el número de página
        $config['use_page_numbers'] = TRUE; // Usar números de página
        $config['full_tag_open'] = '<ul class="pagination">'; // Etiquetas para la paginación
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'Primera';
        $config['last_link'] = 'Última';
        $config['next_link'] = 'Siguiente';
        $config['prev_link'] = 'Anterior';
        
        $this->pagination->initialize($config); // Inicializar la paginación

        // Obtener los barrios con paginación
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1; // Obtener la página actual
        $data['barrios'] = $this->Barrios_model->get_barrios_paginated($config['per_page'], $page); // Obtener los barrios
        $data['title'] = 'Listado de Barrios';
        $data['pagination'] = $this->pagination->create_links(); // Generar los enlaces de paginación

        $this->load->view('lottery/Barrios/list_barrios', $data); // Vista para mostrar los barrios
    }

    // Crear barrio
    public function create()
    {
        // Si el método es POST, validar y guardar el barrio
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $this->input->post('nombre');
            $descripcion = $this->input->post('descripcion');
            $this->Barrios_model->insert_barrio($nombre, $descripcion);
            set_alert('success', 'Barrio creado correctamente.');
            redirect('lottery/barrios');
        }

        $data['title'] = 'Crear Barrio';
        $this->load->view('lottery/Barrios/create_barrio', $data); // Vista para crear barrio
    }

    // Editar barrio
    public function edit($id)
    {
        // Obtener los datos del barrio por id
        $data['barrio'] = $this->Barrios_model->get_barrio_by_id($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar y actualizar el barrio
            $nombre = $this->input->post('nombre');
            $descripcion = $this->input->post('descripcion');
            $this->Barrios_model->update_barrio($id, $nombre, $descripcion);
            set_alert('success', 'Barrio actualizado correctamente.');
            redirect('lottery/barrios');
        }

        $data['title'] = 'Editar Barrio';
        $this->load->view('lottery/Barrios/edit_barrio', $data); // Vista para editar barrio
    }

    // Eliminar barrio
    public function delete($id)
    {
        // Eliminar el barrio
        $this->Barrios_model->delete_barrio($id);
        set_alert('success', 'Barrio eliminado correctamente.');
        redirect('lottery/barrios');
    }
}
