<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mascotas extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lottery/Mascotas_model');
    }

    // Listar todas las mascotas
    public function index()
    {
        $data['mascotas'] = $this->Mascotas_model->get_mascotas();
        $this->load->view('lottery/Mascotas/list_mascotas', $data);
    }

    // Crear una nueva mascota
    public function create()
    {
        if ($this->input->post()) {
            $especie = $this->input->post('especie');

            // Verificar si la mascota ya existe
            if ($this->Mascotas_model->mascota_exists($especie)) {
                // Si la petición es AJAX, respondemos con un JSON
                if ($this->input->is_ajax_request()) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'La especie "' . $especie . '" ya existe.'
                    ]);
                } else {
                    $this->session->set_flashdata('error', 'La especie "' . $especie . '" ya existe.');
                    redirect('lottery/Mascotas');
                }
            } else {
                // Agregar la nueva mascota
                $data = $this->input->post();
                $this->Mascotas_model->add_mascota($data);

                // Si es una petición AJAX, devolvemos la nueva mascota creada
                if ($this->input->is_ajax_request()) {
                    echo json_encode([
                        'success' => true,
                        'data' => $data
                    ]);
                } else {
                    $this->session->set_flashdata('success', 'Mascota creada exitosamente.');
                    redirect('lottery/Mascotas');
                }
            }
        } else {
            $this->load->view('lottery/Mascotas/create_mascota');
        }
    }



    // Editar una mascota existente
    public function edit($id)
    {
        if ($this->input->post()) {
            $especie = $this->input->post('especie'); // Obtén el nuevo nombre

            // Obtener el registro actual de la mascota para comparar nombres
            $mascota_actual = $this->Mascotas_model->get_mascota($id);

            // Verificar si el nuevo nombre ya existe, excluyendo el nombre actual de la mascota que se está editando
            if ($especie != $mascota_actual->nombre && $this->Mascotas_model->mascota_exists($especie)) {
                // Si ya existe otra mascota con el mismo nombre, mostrar alerta
                $this->session->set_flashdata('error', 'La especie: "' . $especie . '" ya existe.');
                redirect('lottery/Mascotas');
            } else {
                // Si el nombre no está duplicado, proceder con la actualización
                $data = $this->input->post();
                $this->Mascotas_model->edit_mascota($id, $data);

                // Mostrar mensaje de éxito
                $this->session->set_flashdata('success', 'Mascota creada exitosamente.');
                redirect('lottery/Mascotas');
            }
        } else {
            $data['mascota'] = $this->Mascotas_model->get_mascota($id);
            $this->load->view('lottery/Mascotas/edit_mascota', $data);
        }
    }

    // Eliminar una mascota
    public function delete($id)
    {
        $this->Mascotas_model->delete_mascota($id);
        redirect('lottery/Mascotas');
    }
}
