<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Documentos extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lottery/Documentos_model');
    }

    // Listar todos los tipos de documentos
    public function index()
    {
        $data['documentos'] = $this->Documentos_model->get_documentos();
        $this->load->view('lottery/Documentos/list_documentos', $data);
    }

    public function create()
{
    if ($this->input->post()) {
        $nombre = $this->input->post('nombre');

        // Verificar si el tipo de documento ya existe
        if ($this->Documentos_model->documento_exists($nombre)) {
            $response = [
                'status' => 'error',
                'message' => 'El tipo de documento: "' . $nombre . '" ya existe.'
            ];
        } else {
            $data = $this->input->post();
            $this->Documentos_model->add_documento($data);
            $response = [
                'status' => 'success',
                'message' => 'Tipo de documento creado exitosamente.',
                'documento' => $data  // Podrías devolver los datos si es necesario
            ];
        }

        // Si es una solicitud AJAX, enviar la respuesta en formato JSON
        if ($this->input->is_ajax_request()) {
            echo json_encode($response);
            return;
        } else {
            // Si no es AJAX, redireccionar como normalmente lo harías
            $this->session->set_flashdata($response['status'], $response['message']);
            redirect('lottery/Documentos');
        }
    } else {
        $this->load->view('lottery/Documentos/create_documento');
    }
}


    // Editar un tipo de documento existente
    public function edit($id)
    {
        if ($this->input->post()) {
            $nombre = $this->input->post('nombre');
            $documento_actual = $this->Documentos_model->get_documento($id);

            // Verificar si el nuevo nombre ya existe
            if ($nombre != $documento_actual->nombre && $this->Documentos_model->documento_exists($nombre)) {
                set_alert('danger', 'Ya existe un tipo de documento con el nombre "' . strtoupper($nombre) . '".');
                redirect('lottery/Documentos/edit/' . $id);
            } else {
                $data = $this->input->post();
                $this->Documentos_model->edit_documento($id, $data);

                set_alert('success', 'Tipo de documento editado exitosamente.');
                redirect('lottery/Documentos');
            }
        } else {
            $data['documento'] = $this->Documentos_model->get_documento($id);
            $this->load->view('lottery/Documentos/edit_documento', $data);
        }
    }

    // Eliminar un tipo de documento
    public function delete($id)
    {
        $this->Documentos_model->delete_documento($id);
        set_alert('success', 'Tipo de documento eliminado exitosamente.');
        redirect('lottery/Documentos');
    }
}
