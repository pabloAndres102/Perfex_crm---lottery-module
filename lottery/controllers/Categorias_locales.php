<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Categorias_locales extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lottery/Categorias_locales_model');
    }

    // Listar todas las categorías
    public function index()
    {
        $data['categorias'] = $this->Categorias_locales_model->get_categorias_locales();
        $this->load->view('lottery/Categorias_locales/list_categorias_locales', $data);
    }

    // Crear una nueva categoría
    public function create()
{
    if ($this->input->post()) {
        $data = $this->input->post();
        $data['fecha_creacion'] = date('Y-m-d H:i:s');

        // Eliminar el campo redirect_url antes de la inserción
        unset($data['redirect_url']);

        // Verificar si ya existe una categoría con el mismo nombre
        if ($this->Categorias_locales_model->categoria_local_exists($data['nombre'])) {
            // Respuesta para AJAX
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'success' => false,
                    'error' => 'El nombre de la categoría ya existe. Intente con otro nombre.'
                ]);
            } else {
                set_alert('warning', 'El nombre de la categoría ya existe. Intente con otro nombre.');
                redirect('lottery/create_local');
            }
        } else {
            $this->Categorias_locales_model->add_categoria_local($data);

            // Respuesta para AJAX
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'success' => true,
                    'data' => $data // Devuelve los datos de la categoría creada
                ]);
            } else {
                // Redirigir según la URL oculta
                $redirect_url = $this->input->post('redirect_url');
                if ($redirect_url) {
                    set_alert('success', 'Categoría creada exitosamente.');
                    redirect($redirect_url);
                } else {
                    set_alert('success', 'Categoría creada exitosamente.');
                    redirect('lottery/Categorias_locales');
                }
            }
        }
    } else {
        $this->load->view('lottery/Categorias_locales/create_categoria_local');
    }
}


    // Editar una categoría existente
    public function edit($id)
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $data['fecha_modificacion'] = date('Y-m-d H:i:s');
            // Verificar si ya existe otra categoría con el mismo nombre (excluyendo la categoría actual)
            $categoria_existente = $this->Categorias_locales_model->categoria_local_exists($data['nombre']);
            if ($categoria_existente && $categoria_existente->id != $id) {
                set_alert('warning', 'El nombre de la categoría ya existe en otro registro. Intente con otro nombre.');
                redirect('lottery/Categorias_locales/edit/' . $id);
            } else {
                $this->Categorias_locales_model->edit_categoria_local($id, $data);
                set_alert('success', 'Categoría actualizada exitosamente.');
                redirect('lottery/Categorias_locales');
            }
        } else {
            $data['categoria'] = $this->Categorias_locales_model->get_categoria_local($id);
            $this->load->view('lottery/Categorias_locales/edit_categoria_local', $data);
        }
    }

    // Eliminar una categoría
    public function delete($id)
    {
        $this->Categorias_locales_model->delete_categoria_local($id);
        set_alert('success', 'Categoría eliminada exitosamente.');
        redirect('lottery/Categorias_locales');
    }
}
