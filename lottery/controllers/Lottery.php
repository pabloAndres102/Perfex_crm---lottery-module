<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Lottery extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('staff_model');
        $this->load->model('lottery_model');
        $this->load->library('pagination');
    }

    public function modal_client()
    {
        $data = array();
        $this->load->view('modal_client', $data);
    }
    public function modal_invoice()
    {
        $data = array();
        $this->load->view('modal_invoice', $data);
    }

    public function index()
    {
        // Obtener todos los sorteos desde la base de datos
        $data['clientes'] = $this->db->get(db_prefix() . 'lottery_clientes')->result_array();
        $data['tipos_documentos'] = $this->db->get(db_prefix() . 'lottery_tipos_documentos')->result_array();
        $data['mascotas'] = $this->db->get(db_prefix() . 'lottery_mascotas')->result_array();
        // Cargar la vista con los datos
        $this->load->view('lottery/index', $data);
    }

    public function index_module()
    {
        $this->load->view('lottery/index_module');
    }

    public function configurations()
    {
        $this->load->view('lottery/configurations');
    }

    public function list_clients()
    {
        $this->load->library('pagination');

        // Capturar los parámetros de búsqueda
        $search = $this->input->get('search');
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $ocupacion = $this->input->get('ocupacion');  // Capturar el filtro de ocupación
        $num_filas = $this->input->get('num_filas') ? (int) $this->input->get('num_filas') : 10;


        if ($fecha_fin) {
            $fecha_fin = date('Y-m-d', strtotime($fecha_fin . ' +1 day'));
        }

        // Configuración de la paginación
        $config = [];
        $query_string = http_build_query([
            'search' => $search,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'ocupacion' => $ocupacion,
            'num_filas' => $num_filas  // Incluir num_filas en la query string
        ]);

        $config['base_url'] = site_url('lottery/list_clients');  // URL base para la paginación
        $config['suffix'] = '?' . $query_string;
        $config['first_url'] = $config['base_url'] . $config['suffix'];
        $config['per_page'] = $num_filas;   // Número de registros por página
        $config['num_links'] = 5;  // Número de enlaces de paginación

        // Iniciar la consulta sin ejecutarla
        $this->db->from(db_prefix() . 'lottery_clientes'); // Definir la tabla solo una vez

        // Aplicar filtro de búsqueda por nombre o documento de identidad si se ha ingresado un término
        if ($search) {
            $this->db->group_start();  // Abrimos un grupo para agrupar los "OR LIKE"
            $this->db->like('nombre', $search);  // Filtrar por nombre
            $this->db->or_like('documento_identidad', $search);  // Filtrar por documento de identidad
            $this->db->group_end();  // Cerramos el grupo
        }

        // Filtrar por ocupación si se ha ingresado
        if ($ocupacion) {
            $this->db->where('ocupacion', $ocupacion);  // Filtrar por ocupación exacta
        }

        // Filtrar por rango de fechas
        if ($fecha_inicio && $fecha_fin) {
            $this->db->where('fecha_actualizacion >=', $fecha_inicio);
            $this->db->where('fecha_actualizacion <=', $fecha_fin);
        } elseif ($fecha_inicio) {
            $this->db->where('fecha_actualizacion >=', $fecha_inicio);
        } elseif ($fecha_fin) {
            $this->db->where('fecha_actualizacion <=', $fecha_fin);
        }

        // Obtener el total de filas sin ejecutar la consulta de paginación aún
        $config['total_rows'] = $this->db->count_all_results('', FALSE);  // Total de registros filtrados

        // Configuración para la paginación
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        // Inicializar la paginación
        $this->pagination->initialize($config);

        // Obtener el número de la página actual (offset)
        $page = ($this->uri->segment(3)) ? (int) ($this->uri->segment(3)) : 0;

        // Aplicar el límite para la paginación
        $this->db->limit($config['per_page'], $page);

        // Ejecutar la consulta final y obtener los registros paginados de clientes
        $data['clientes'] = $this->db->get()->result_array();  // Obtener los resultados

        // Inicializar contadores y sumas
        $total_clientes = count($data['clientes']);
        $numero_hombres = 0;
        $numero_mujeres = 0;
        $suma_edad = 0;
        $ocupaciones = [];

        // Calcular estadísticas
        foreach ($data['clientes'] as $cliente) {
            if (isset($cliente['sexo'])) {
                if ($cliente['sexo'] == 'M') {
                    $numero_hombres++;
                } elseif ($cliente['sexo'] == 'F') {
                    $numero_mujeres++;
                }
            }

            if (isset($cliente['edad']) && is_numeric($cliente['edad'])) {
                $suma_edad += $cliente['edad'];
            }

            if (isset($cliente['ocupacion'])) {
                $ocupacion = strtolower($cliente['ocupacion']);
                if (isset($ocupaciones[$ocupacion])) {
                    $ocupaciones[$ocupacion]++;
                } else {
                    $ocupaciones[$ocupacion] = 1;
                }
            }
        }

        // Calcular estadísticas adicionales
        $edad_promedio = ($total_clientes > 0) ? ($suma_edad / $total_clientes) : 0;
        $ocupacion_mas_frecuente = !empty($ocupaciones) ? array_keys($ocupaciones, max($ocupaciones))[0] : null;

        // Pasar las estadísticas a la vista
        $data['total_clientes'] = $total_clientes;
        $data['numero_hombres'] = $numero_hombres;
        $data['numero_mujeres'] = $numero_mujeres;
        $data['edad_promedio'] = $edad_promedio;
        $data['ocupacion_mas_frecuente'] = $ocupacion_mas_frecuente;

        // Enlaces de paginación
        $data['pagination'] = $this->pagination->create_links();

        // **Inicio de la corrección: Calcular el rango de resultados a mostrar**
        $start = $page + 1;  // El primer registro (agregamos 1 ya que $page es 0 basado)
        $end = min($start + $config['per_page'] - 1, $config['total_rows']);  // El último registro, no debe superar el total de filas
        $data['mostrar_desde_hasta'] = "Mostrando desde $start hasta $end de {$config['total_rows']} entradas";
        $data['clientes_count'] = $config['total_rows'];
        // **Fin de la corrección**

        // Pasar el término de búsqueda y la ocupación a la vista
        $data['search'] = $search;
        $data['ocupacion'] = $ocupacion;

        // Cargar la vista con los datos
        $this->load->view('lottery/list_clients', $data);
    }




    public function search_client()
    {
        // Obtener el documento de identidad del cliente
        $documento_identidad = $this->input->get('documento_identidad');

        // Buscar el cliente por documento de identidad
        $client = $this->lottery_model->get_client_by_document($documento_identidad);

        // Retornar el cliente como respuesta JSON
        echo json_encode($client);
    }


    public function list_draws()
    {
        // Cargar el modelo
        $this->load->model('lottery_model');

        // Obtener los sorteos
        $this->db->select('s.*, 
            c.nombre AS ganador_nombre, 
            c.apellido AS ganador_apellido, 
            (SELECT COUNT(*) FROM ' . db_prefix() . 'lottery_facturas f WHERE f.sorteo_id = s.id) as cantidad_participantes, 
            FLOOR((SELECT SUM(f.valor) FROM ' . db_prefix() . 'lottery_facturas f WHERE f.sorteo_id = s.id) / s.valor_por_factura) as boletas_generadas, 
            s.patrocinador_id AS patrocinadores_json');

        $this->db->from(db_prefix() . 'lottery_sorteo s');
        $this->db->join(db_prefix() . 'lottery_clientes c', 's.ganador_cliente_id = c.id', 'left');

        $data['draws'] = $this->db->get()->result_array();

        // Recorrer cada sorteo para obtener los nombres de los patrocinadores
        foreach ($data['draws'] as &$draw) {
            $patrocinadores_ids = json_decode($draw['patrocinadores_json'], true);

            if (!empty($patrocinadores_ids)) {
                // Obtener los nombres de los patrocinadores
                $this->db->select('GROUP_CONCAT(nombre SEPARATOR "<br>") AS patrocinadores_nombres');
                $this->db->from(db_prefix() . 'lottery_locales_comerciales');
                $this->db->where_in('id', $patrocinadores_ids);
                $patrocinadores = $this->db->get()->row_array();

                // Asignar los nombres al array del sorteo
                $draw['patrocinadores_nombres'] = $patrocinadores['patrocinadores_nombres'];
            } else {
                $draw['patrocinadores_nombres'] = 'No patrocinadores';
            }
        }

        // Cargar la vista con los datos
        $this->load->view('lottery/list_draws', $data);
    }





    public function create_draw()
    {

        $data['locales_comerciales'] = $this->lottery_model->get_all_locales();
        $this->load->view('lottery/create_draw', $data);
    }

    public function insert_draw()
    {
        $this->load->model('lottery_model');

        // Inicializar el array de datos del sorteo
        $data = array(
            'nombre' => $this->input->post('nombre'),
            'descripcion' => $this->input->post('descripcion'),
            'valor_por_factura' => $this->input->post('valor_por_factura'),
            'fecha_inicio' => $this->input->post('fecha_inicio'),
            'fecha_finalizacion' => $this->input->post('fecha_finalizacion')
        );

        $patrocinador_ids = $this->input->post('patrocinador_id');
        if (!empty($patrocinador_ids)) {
            $data['patrocinador_id'] = json_encode($patrocinador_ids);  // Usar json_encode para almacenar en la base de datos
        } else {
            $data['patrocinador_id'] = null;  // Si no hay patrocinadores, poner en NULL
        }

        // Subir la foto del sorteo si está presente
        if (!empty($_FILES['foto']['name'])) {
            $upload_path = './uploads/sorteos/';

            // Verificar si el directorio existe, si no, crearlo
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true); // 0755 es el permiso, true permite la creación recursiva
            }

            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048; // Limite de 2MB

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                $upload_data = $this->upload->data();
                $data['foto'] = $upload_data['file_name'];
            } else {
                // Si la subida de la foto falla, mostrar un mensaje de error
                $this->session->set_flashdata('message', array('type' => 'error', 'content' => $this->upload->display_errors()));
                redirect('lottery/list_draws');
                return;
            }
        }

        // Insertar los datos del sorteo en la base de datos
        if ($this->lottery_model->insert_draw($data)) {
            $this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Sorteo creado con éxito.'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No se pudo crear el sorteo.'));
        }

        // Redirigir al listado de sorteos
        redirect('lottery/list_draws');
    }

    public function edit_draw($id)
    {
        // Cargar el modelo
        $this->load->model('lottery_model');

        // Obtener los datos del sorteo
        $data['draw'] = $this->lottery_model->get_draw_by_id($id);

        // Obtener la lista de patrocinadores
        $this->load->model('lottery_model'); // Asegúrate de tener el modelo de patrocinadores
        $data['patrocinadores'] = $this->lottery_model->get_all_patrocinadores();

        // Cargar la vista
        $this->load->view('lottery/edit_draw', $data);
    }


    public function update_draw($id)
    {
        // Cargar el modelo
        $this->load->model('lottery_model');

        // Obtener los datos actualizados del formulario
        $draw_data = array(
            'nombre' => $this->input->post('nombre'),
            'descripcion' => $this->input->post('descripcion'),
            'valor_por_factura' => $this->input->post('valor_por_factura'),
            'fecha_inicio' => $this->input->post('fecha_inicio'),
            'fecha_finalizacion' => $this->input->post('fecha_finalizacion'),
        );
        $patrocinador_ids = $this->input->post('patrocinador_id');
        if (!empty($patrocinador_ids)) {
            $draw_data['patrocinador_id'] = json_encode($patrocinador_ids);  // Usar json_encode para almacenar en la base de datos
        } else {
            $draw_data['patrocinador_id'] = null;  // Si no hay patrocinadores, poner en NULL
        }


        // Manejo de la foto
        if (!empty($_FILES['foto']['name'])) {
            // Configuración para la subida de la foto
            $config['upload_path'] = './uploads/sorteos';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = $_FILES['foto']['name'];
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                $upload_data = $this->upload->data();
                $draw_data['foto'] = $upload_data['file_name'];
            } else {
                // Manejo de errores de subida
                $this->session->set_flashdata('message', array('type' => 'error', 'content' => $this->upload->display_errors()));
                redirect('lottery/edit_draw/' . $id);
            }
        }

        // Intentar actualizar los datos del sorteo
        if ($this->lottery_model->update_draw($id, $draw_data)) {
            $this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Sorteo actualizado con éxito.'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No se pudo actualizar el sorteo.'));
        }

        // Redirigir al listado de sorteos
        redirect('lottery/list_draws');
    }


    public function view_client($id)
    {
        $this->load->model('lottery_model');
        $data['client'] = $this->lottery_model->get_client_by_id2($id);

        if (!$data['client']) {
            show_404(); // Mostrar error 404 si no se encuentra el cliente
        }

        $this->load->view('view_client', $data);
    }



    public function insert_client()
    {
        // Cargar el modelo si no está cargado
        $this->load->model('lottery_model');
        $logged_in_staff_id = get_staff_user_id();
        $logged_in_staff = $this->staff_model->get($logged_in_staff_id);

        // Validar y obtener datos del formulario
        $data = array(
            'asesor' => $logged_in_staff->firstname . ' ' . $logged_in_staff->lastname,
            'nombre' => ucfirst(strtolower($this->input->post('nombre'))),
            'apellido' => ucfirst(strtolower($this->input->post('apellido'))),
            'documento_identidad' => $this->input->post('documento_identidad'),
            'nivel_academico' => $this->input->post('nivel_academico'),
            'fecha_cumpleanos' => $this->input->post('fecha_cumpleanos'),
            'sexo' => $this->input->post('sexo'),
            'edad' => $this->input->post('edad'),
            'email' => $this->input->post('email'),
            'telefono' => $this->input->post('telefono'),
            'municipio' => $this->input->post('municipio'),
            'direccion_residencia' => $this->input->post('direccion_residencia'),
            'barrio' => $this->input->post('barrio'),
            'estado_civil' => $this->input->post('estado_civil'),
            'hijos' => $this->input->post('hijos'),
            'ocupacion' => $this->input->post('ocupacion'),
            'tipo_cliente' => $this->input->post('tipo_cliente'),
            'tiene_vehiculo' => $this->input->post('tiene_vehiculo'),
            'tiene_mascota' => $this->input->post('tiene_mascota'),
            'tipo_documento' => $this->input->post('tipo_documento'),
            'discapacidad' => $this->input->post('discapacidad'),
            'tipo_persona' => $this->input->post('tipo_persona'),
            'datos_personales' => $this->input->post('datos_personales'),
            'fecha_creacion' => date('Y-m-d H:i:s'),
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        );
        $hijos_menores = $this->input->post('hijos_menores');
        if ($data['hijos'] > 0) {
            // Si selecciona que tiene hijos, entonces verificar el campo 'hijos_menores'
            $data['hijos_menores'] = ($hijos_menores) ? 'S' : 'N';
        } else {
            $data['hijos_menores'] = 'N'; // Si no tiene hijos, no aplica el campo
        }


        // Insertar datos en la base de datos
        if ($this->lottery_model->insert_client($data)) {
            $client_id = $this->db->insert_id();
            $client_data = $this->lottery_model->get_client_by_id($client_id);

            // Establecer mensaje de éxito con el documento de identidad separado
            $this->session->set_flashdata('message', array(
                'type' => 'success',
                'content' => '¡Cliente creado con éxito!<br>'
                    . 'Nombre: ' . $client_data['nombre'] . ' ' . $client_data['apellido'] . '<br>'
                    . 'Documento de Identidad: ' . $client_data['documento_identidad'] . '<br>'
                    . 'Se ha asignado al asesor: ' . $logged_in_staff->firstname . ' ' . $logged_in_staff->lastname . '.'
            ));

            // Guardar solo el número de documento de identidad en la sesión
            $this->session->set_flashdata('documento_identidad', $client_data['documento_identidad']);
        } else {
            // Establecer mensaje de error
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No se pudo agregar el cliente.'));
        }

        // Redirigir al modal de clientes o al listado de clientes
        redirect('lottery/create_invoice');
    }



    public function insert_client_modal()
    {
        // Cargar el modelo si no está cargado
        $this->load->model('lottery_model');
        $logged_in_staff_id = get_staff_user_id();
        $logged_in_staff = $this->staff_model->get($logged_in_staff_id);

        // Validar y obtener datos del formulario
        $data = array(
            'asesor' => $logged_in_staff->firstname . ' ' . $logged_in_staff->lastname,
            'nombre' => ucfirst(strtolower($this->input->post('nombre'))),
            'apellido' => ucfirst(strtolower($this->input->post('apellido'))),
            'documento_identidad' => $this->input->post('documento_identidad'),
            'nivel_academico' => $this->input->post('nivel_academico'),
            'sexo' => $this->input->post('sexo'),
            'edad' => $this->input->post('edad'),
            'email' => $this->input->post('email'),
            'telefono' => $this->input->post('telefono'),
            'municipio' => $this->input->post('municipio'),
            'direccion_residencia' => $this->input->post('direccion_residencia'),
            'barrio' => $this->input->post('barrio'),
            'estado_civil' => $this->input->post('estado_civil'),
            'hijos' => $this->input->post('hijos'),
            'ocupacion' => $this->input->post('ocupacion'),
            'tipo_cliente' => $this->input->post('tipo_cliente'),
            'datos_personales' => $this->input->post('datos_personales'),
            'fecha_creacion' => date('Y-m-d H:i:s'),
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        );

        // Insertar datos en la base de datos
        if ($this->lottery_model->insert_client($data)) {
            $client_id = $this->db->insert_id();
            $client_data = $this->lottery_model->get_client_by_id($client_id);

            // Establecer mensaje de éxito con el documento de identidad separado
            $this->session->set_flashdata('message', array(
                'type' => 'success',
                'content' => '¡Cliente creado con éxito!<br>'
                    . 'Nombre: ' . $client_data['nombre'] . ' ' . $client_data['apellido'] . '<br>'
                    . 'Documento de Identidad: ' . $client_data['documento_identidad'] . '<br>'
                    . 'Se ha asignado al asesor: ' . $logged_in_staff->firstname . ' ' . $logged_in_staff->lastname . '.'
            ));

            // Guardar solo el número de documento de identidad en la sesión
            $this->session->set_flashdata('documento_identidad', $client_data['documento_identidad']);
        } else {
            // Establecer mensaje de error
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No se pudo agregar el cliente.'));
        }

        // Redirigir al modal de clientes o al listado de clientes
        redirect('lottery/create_invoice');
    }


    public function delete_client($id)
    {
        // Cargar el modelo si no está cargado
        $this->load->model('lottery_model');

        // Intentar eliminar el cliente
        if ($this->lottery_model->delete_client($id)) {
            // Establecer mensaje de éxito
            $this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Cliente eliminado con éxito.'));
        } else {
            // Establecer mensaje de error
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No se pudo eliminar el cliente.'));
        }

        // Redirigir al listado de clientes
        redirect('lottery/list_clients');
    }


    public function edit_client($id)
    {
        // Cargar el modelo
        $this->load->model('lottery_model');
        $data['mascotas'] = $this->db->get(db_prefix() . 'lottery_mascotas')->result_array();
        $data['tipos_documentos'] = $this->db->get(db_prefix() . 'lottery_tipos_documentos')->result_array();
        // Obtener los datos del cliente por ID
        $data['cliente'] = $this->lottery_model->get_client_by_id($id);

        // Cargar la vista del formulario de edición
        $this->load->view('lottery/edit_client', $data);
    }
    public function update_client($id)
    {
        // Cargar el modelo
        $this->load->model('lottery_model');
        $logged_in_staff_id = get_staff_user_id();

        $logged_in_staff = $this->staff_model->get($logged_in_staff_id);
        // Obtener los datos actualizados del formulario
        $client_data = array(
            'asesor' => $logged_in_staff->firstname . ' ' . $logged_in_staff->lastname,
            'nombre' => $this->input->post('nombre'),
            'apellido' => $this->input->post('apellido'),
            'documento_identidad' => $this->input->post('documento_identidad'),
            'direccion_residencia' => $this->input->post('direccion_residencia'),
            'sexo' => $this->input->post('sexo'),
            'edad' => $this->input->post('edad'),
            'email' => $this->input->post('email'),
            'telefono' => $this->input->post('telefono'),
            'municipio' => $this->input->post('municipio'),
            'barrio' => $this->input->post('barrio'),
            'estado_civil' => $this->input->post('estado_civil'),
            'hijos' => $this->input->post('hijos'),
            'ocupacion' => $this->input->post('ocupacion'),
            'tipo_cliente' => $this->input->post('tipo_cliente'),
            'tipo_documento' => $this->input->post('tipo_documento'),
            'tipo_persona' => $this->input->post('tipo_persona'),
            'discapacidad' => $this->input->post('discapacidad'),
            'tiene_mascota' => $this->input->post('tiene_mascota'),
            'tiene_vehiculo' => $this->input->post('tiene_vehiculo'),
            'datos_personales' => $this->input->post('datos_personales'),
            'fecha_actualizacion' => date('Y-m-d H:i:s') // Actualiza la fecha de modificación
        );
        $hijos_menores = $this->input->post('hijos_menores');
        if ($client_data['hijos'] > 0) {
            // Si selecciona que tiene hijos, entonces verificar el campo 'hijos_menores'
            $client_data['hijos_menores'] = ($hijos_menores) ? 'S' : 'N';
        } else {
            $client_data['hijos_menores'] = 'N'; // Si no tiene hijos, no aplica el campo
        }

        // Intentar actualizar los datos del cliente
        if ($this->lottery_model->update_client($id, $client_data)) {
            // Establecer mensaje de éxito
            $this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Cliente actualizado con éxito.'));
        } else {
            // Establecer mensaje de error
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No se pudo actualizar el cliente.'));
        }
        // Redirigir al listado de clientes
        redirect('lottery/list_clients');
    }


    public function associate_clients_to_draw($draw_id)
    {
        // Cargar clientes y sorteos
        $this->load->model('lottery_model');
        $data['draw'] = $this->lottery_model->get_draw_by_id($draw_id);
        $data['clients'] = $this->lottery_model->get_all_clients();
        $data['selected_clients'] = $this->lottery_model->get_clients_by_draw($draw_id);

        // Cargar vista de asociación de clientes
        $this->load->view('lottery/associate_clients', $data);
    }

    public function save_clients_to_draw()
    {
        $this->load->model('lottery_model');
        $draw_id = $this->input->post('draw_id');
        $facturas_por_cliente = $this->input->post('factura_ids'); // Facturas seleccionadas por cliente
        $total_valores = $this->input->post('total_valor_factura'); // Valor total de facturas por cliente

        // Iterar por cada cliente
        foreach ($facturas_por_cliente as $cliente_id => $facturas) {
            $total_factura = isset($total_valores[$cliente_id]) ? $total_valores[$cliente_id] : 0;

            // Guardar el cliente y el valor total de sus facturas en el sorteo
            $this->lottery_model->associate_client_to_draw($draw_id, $cliente_id, $total_factura);

            // También puedes guardar las facturas específicas si lo necesitas
            foreach ($facturas as $factura_id) {
                // Aquí puedes realizar alguna acción si necesitas registrar las facturas individuales
            }
        }

        // Redirigir o mostrar un mensaje de éxito
        redirect('lottery/list_draws');
    }



    public function view_draw_clients($sorteo_id)
{
    // Cargar el sorteo
    $this->db->where('id', $sorteo_id);
    $draw = $this->db->get(db_prefix() . 'lottery_sorteo')->row_array();

    if (!$draw) {
        show_404(); // Mostrar error si el sorteo no existe
    }

    // Obtener los clientes y facturas asociadas a este sorteo y agrupar por documento de identidad
    $this->db->select('
        clientes.id, 
        clientes.nombre, 
        clientes.apellido, 
        clientes.barrio, 
        clientes.ocupacion, 
        clientes.tipo_cliente, 
        clientes.documento_identidad, 
        SUM(facturas.valor) as valor_total_facturas, 
        locales_comerciales.nombre as local_nombre
    ');
    $this->db->from(db_prefix() . 'lottery_facturas as facturas');
    $this->db->join(db_prefix() . 'lottery_clientes as clientes', 'clientes.id = facturas.cliente_id', 'inner');
    $this->db->join(db_prefix() . 'lottery_locales_comerciales as locales_comerciales', 'locales_comerciales.id = facturas.local_id', 'left');
    $this->db->where('facturas.sorteo_id', $sorteo_id);

    // Asegúrate de incluir todas las columnas no agregadas en el GROUP BY
    $this->db->group_by('clientes.id, clientes.nombre, clientes.apellido, clientes.barrio, clientes.ocupacion, clientes.tipo_cliente, clientes.documento_identidad, locales_comerciales.nombre');

    $clients = $this->db->get()->result_array();

    // Calcular las boletas generadas por cada cliente
    foreach ($clients as &$client) {
        $client['boletas_generadas'] = floor($client['valor_total_facturas'] / $draw['valor_por_factura']);
    }

    // Pasar los datos a la vista
    $data['draw'] = $draw;
    $data['clients'] = $clients;

    // Cargar la vista con los datos
    $this->load->view('lottery/view_draw_clients', $data);
}




    public function importar_clientes_csv()
    {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            // Obtener el archivo temporal
            $file_tmp = $_FILES['file']['tmp_name'];

            $logged_in_staff_id = get_staff_user_id();
            $logged_in_staff = $this->staff_model->get($logged_in_staff_id);

            // Abrir el archivo CSV temporal
            if (($handle = fopen($file_tmp, 'r')) !== false) {
                // Leer la primera línea (cabeceras)
                $header = fgetcsv($handle, 1000, ';');

                // Recorrer cada línea del archivo CSV
                while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                    // Crear un array asociativo con los datos
                    $client_data = array_combine($header, $data);

                    // Obtener la fecha de cumpleaños del campo CSV
                    $birthday_str = $client_data['birthday_c']; // Ejemplo: 26/02/98
                    if (!empty($birthday_str)) {
                        // Convertir la fecha en formato d/m/y a un objeto DateTime
                        $birthday = DateTime::createFromFormat('d/m/y', $birthday_str);
                        if ($birthday) {
                            // Calcular la edad
                            $edad = date_diff($birthday, date_create('now'))->y;
                            // Formatear la fecha de cumpleaños como 'm-d' (mes-día)
                            $fecha_cumpleanos = $birthday->format('m-d');
                        } else {
                            $edad = 0; // Si la fecha no es válida, asignar edad 0
                            $fecha_cumpleanos = null; // Si la fecha no es válida
                        }
                    } else {
                        $edad = 0; // Si no hay fecha de nacimiento, asignar edad 0
                        $fecha_cumpleanos = null;
                    }

                    // Preparar los datos para la inserción en la base de datos
                    $data_to_insert = array(
                        'asesor' => $logged_in_staff->firstname . ' ' . $logged_in_staff->lastname,
                        'nombre' => $client_data['first_name_c'],
                        'apellido' => $client_data['last_name_c'],
                        'documento_identidad' => $client_data['identificacion_c'],
                        'tipo_documento' => $client_data['tipo_identificacion_c'],
                        'tipo_persona' => $client_data['tipo_persona_c'],
                        'discapacidad' => isset($client_data['discapacidad_c']) ? $client_data['discapacidad_c'] : '',
                        'tiene_mascota' => isset($client_data['especie_mascota_c']) && !empty($client_data['especie_mascota_c']) ? 'S' : '',
                        'tiene_vehiculo' => isset($client_data['tipo_vehiculo_c']) && !empty($client_data['tipo_vehiculo_c']) ? 'S' : '',
                        'sexo' => $client_data['sexo_c'] === 'male' ? 'M' : ($client_data['sexo_c'] === 'female' ? 'F' : 'O'),
                        'edad' => $edad,
                        'fecha_cumpleanos' => $fecha_cumpleanos, // Mes - Día (m-d)
                        'email' => $client_data['email_address'],
                        'telefono' => $client_data['movil_c'],
                        'municipio' => $client_data['ciudad_c'],
                        'barrio' => $client_data['barrio_c'],
                        'estado_civil' => $client_data['estado_civil_c'],
                        'hijos' => '',
                        'nivel_academico' => $client_data['nivel_academico_c'],
                        'ocupacion' => $client_data['ocupacion_c'],
                        'datos_personales' => 'S',
                        'direccion_residencia' => $client_data['direccion_c'],
                        'fecha_creacion' => date('Y-m-d H:i:s'),
                        'fecha_actualizacion' => date('Y-m-d H:i:s')
                    );

                    // Insertar en la base de datos
                    $this->lottery_model->insert_client($data_to_insert);
                }

                // Cerrar el archivo
                fclose($handle);
                $this->session->set_flashdata('success', 'Clientes importados exitosamente.');

                return true;
                redirect('lottery/list_clientes');
            }

            return false;
        }
    }



    public function importar_locales()
    {

        // Verificar si se ha subido un archivo
        if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['userfile']['tmp_name'];

            $file = $_FILES['userfile'];

            $csv_data = [];
            if (($handle = fopen($file_tmp, 'r')) !== false) {
                $header = fgetcsv($handle); // Leer la primera línea como encabezados
                while (($row = fgetcsv($handle)) !== false) {
                    $csv_data[] = array_combine($header, $row); // Combinar encabezados con los valores
                }
                fclose($handle);
            }

            // Procesar los datos del CSV e insertarlos en la base de datos
            foreach ($csv_data as $row) {
                $nombre = $row['name'];
                $activo = ($row['activo_inactivo'] === 'Inactivo') ? 0 : 1; // Convertir a 0 o 1
                $whatsapp = $row['telefono2']; // Puedes ajustar esto según tus necesidades
                $email = ''; // Si tienes un campo de email, ajusta aquí
                $website = $row['website'];
                $ficha_catastro = $row['ficha_catastro'];
                // $ubicacion = $row['ubicacion'];
                $local = $row['local']; // Ajustar según el mapeo que necesites

                // Preparar los datos para la inserción
                $data_to_insert = array(
                    'nombre' => $nombre,
                    // 'ubicacion' => $ubicacion,
                    'descripcion' => null, // Ajusta si necesitas este campo
                    'fotos' => null, // Ajusta si necesitas este campo
                    'whatsapp' => $whatsapp,
                    'email' => $email,
                    'local' => $local,
                    'activo' => $activo,
                    'ficha_catastro' => $ficha_catastro,
                    'website' => $website,
                    'piso_o_nivel' => null, // Ajusta si tienes un campo para esto
                    'categoria' => null // Ajusta si tienes un campo para esto
                );

                // Insertar los datos en la tabla
                $this->db->insert(db_prefix() . 'lottery_locales_comerciales', $data_to_insert);
            }

            $this->session->set_flashdata('success', 'Locales importados exitosamente.');
            redirect('lottery/list_locales'); // Redirigir a la lista de locales
        }
    }



    public function generate_winner($draw_id)
    {
        // Cargar el modelo
        $this->load->model('lottery_model');
    
        $draw = $this->lottery_model->get_draw_by_id($draw_id);
        $valor_draw = $draw['valor_por_factura'];
    
        // Obtener los clientes asociados al sorteo
        $clients = $this->lottery_model->get_clients_by_draw($draw_id);
    
        // Verificar si hay clientes asociados
        if (count($clients) > 0 && $valor_draw > 0) {
            // Crear un array de oportunidades (tickets) para cada cliente
            $participantes = [];
    
            foreach ($clients as $client) {
                $client_invoice_value = $client['valor_factura']; // Obtener el valor de la factura del cliente
                $client_id = $client['id']; // Usamos 'cliente_id'
    
                // Verificar si el cliente tiene al menos el valor requerido para generar tickets
                if ($client_invoice_value >= $valor_draw) {
                    // Usar el ID del cliente como clave para evitar duplicados
                    $participantes[$client_id] = $client_id;
                }
            }
    
            // Seleccionar un cliente aleatoriamente de los tickets, si hay tickets generados
            if (!empty($participantes)) {
                // Obtener los IDs únicos de los participantes
                $unique_participants = array_values($participantes); // Esto convierte el array asociativo en un array normal
                $winner_client_id = $unique_participants[array_rand($unique_participants)];
    
                // Guardar el ganador en la tabla 'sorteo'
                $this->db->where('id', $draw_id);
                $this->db->update(db_prefix() . 'lottery_sorteo', ['ganador_cliente_id' => $winner_client_id]);
    
                // Obtener el cliente ganador para mostrar detalles
                $winner_client = $this->lottery_model->get_client_by_id($winner_client_id);
    
                // Mostrar el mensaje con el ganador
                $this->session->set_flashdata('message', array('type' => 'success', 'content' => '¡El cliente ganador es: ' . $winner_client['nombre'] . ' ' . $winner_client['apellido'] . '!'));
            } else {
                // Si no hay tickets generados
                $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No se generaron boletas para ningún cliente, Por favor asegurese de que la factura de su cliente cumpla con el valor requerido'));
            }
        } else {
            // Si no hay clientes asociados al sorteo o valor de la factura no disponible
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No hay clientes asociados a este sorteo o el valor de la factura del sorteo no está disponible.'));
        }
    
        // print_r($participantes);
        redirect('lottery/list_draws/');
    }
    


    public function view_winner_details($cliente_id)
    {
        // Obtener detalles del cliente
        $this->load->model('lottery_model');
        $client = $this->lottery_model->get_client_by_id($cliente_id);

        // Pasar los datos del cliente a la vista
        $data['client'] = $client;

        // Cargar la vista del cliente
        $this->load->view('winner_details_view', $data);
    }


    ////////////////////////////////////////////////// ////////////////////////////////////////////////// //////////////////////////////////////////////////

    public function create_invoice()
    {
        // Obtener el parámetro client_id de la URL, si existe
        $client_id = $this->input->get('client_id');
        $data['locales'] = $this->lottery_model->get_all_locales();
        $data['sorteos'] = $this->lottery_model->get_all_draws();
        $data['tipos_documentos'] = $this->db->get(db_prefix() . 'lottery_tipos_documentos')->result_array();
        $data['mascotas'] = $this->db->get(db_prefix() . 'lottery_mascotas')->result_array();
        // Obtener los clientes para seleccionar en la factura
        // $data['clientes'] = $this->lottery_model->get_all_clients();
        $data['client_id'] = $client_id; // Pasar el client_id a la vista

        // Cargar la vista del formulario
        $this->load->view('lottery/create_invoice', $data);
    }


    // Método para insertar una nueva factura
    public function insert_invoice()
    {
        // Recibir los datos del formulario
        $cliente_id = $this->input->post('cliente_id');
        $numero_factura = $this->input->post('numero_factura');
        $valor = $this->input->post('valor');
        $local_id = (int) $this->input->post('local_id');
        $sorteo_id = $this->input->post('sorteo_id');
        $fecha_emision = date('Y-m-d H:i:s');
    
        // Obtener el valor requerido del sorteo
        $this->db->where('id', $sorteo_id);
        $sorteo = $this->db->get(db_prefix() . 'lottery_sorteo')->row();
    
        if (!$sorteo) {
            // Si no se encuentra el sorteo, mostrar un mensaje de error
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Sorteo no encontrado.'));
            redirect('lottery/list_invoices');
            return;
        }
    
        $valor_requerido = $sorteo->valor_por_factura; // Asumiendo que 'valor_por_factura' es el valor requerido
    
        // Validar si el valor de la factura es mayor o igual que el valor requerido
        if ($valor < $valor_requerido) {
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'El valor de la factura debe ser igual o mayor que el valor requerido del sorteo.'));
            redirect('lottery/list_invoices');
            return;
        }
    
        // Verificar si ya existe una factura con el mismo número de factura
        $this->db->where('numero_factura', $numero_factura);
        $query = $this->db->get(db_prefix() . 'lottery_facturas');
    
        if ($query->num_rows() > 0) {
            // Si la factura con el mismo número de factura existe
            $factura_existente = $query->row();
    
            // Comprobamos si el cliente es el mismo
            if ($factura_existente->cliente_id == $cliente_id) {
                // Si el cliente y la factura son iguales, actualizamos la factura
                $data_factura = array(
                    'valor' => $valor,
                    'fecha_emision' => $fecha_emision,
                    'local_id' => $local_id
                );
                $this->db->where('id', $factura_existente->id);
                $this->db->update(db_prefix() . 'lottery_facturas', $data_factura);
    
                // Asociamos el cliente al sorteo con el valor actualizado
                $this->db->where('sorteo_id', $sorteo_id);
                $this->db->where('cliente_id', $cliente_id);
                $this->db->update(db_prefix() . 'lottery_sorteo_clientes', array('valor_factura' => $valor));
    
                // Mensaje de éxito
                $this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Factura actualizada con éxito.'));
            } else {
                // Si el cliente es diferente, mostramos un mensaje de error
                $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'El número de factura ya está registrado con otro cliente. No se puede duplicar.'));
            }
        } else {
            // Si no existe la factura con el mismo número, se crea una nueva factura
            $data_factura = array(
                'cliente_id' => $cliente_id,
                'numero_factura' => $numero_factura,
                'valor' => $valor,
                'local_id' => $local_id,
                'sorteo_id' => $sorteo_id,
                'fecha_emision' => $fecha_emision
            );
    
            // Insertamos la nueva factura en la base de datos
            $this->db->insert(db_prefix() . 'lottery_facturas', $data_factura);
    
            // También asociamos el cliente al sorteo
            $this->db->insert(db_prefix() . 'lottery_sorteo_clientes', array(
                'cliente_id' => $cliente_id,
                'sorteo_id' => $sorteo_id,
                'valor_factura' => $valor
            ));
    
            // Mensaje de éxito
            $this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Factura creada con éxito.'));
        }
    
        // Redirigir al listado de facturas
        redirect('lottery/list_invoices');
    }
    
    







    public function check_invoice()
    {
        $numero_factura = $this->input->post('numero_factura');

        // Verificar si el número de factura ya existe
        $this->db->where('numero_factura', $numero_factura);
        $query = $this->db->get(db_prefix() . 'lottery_factura');

        if ($query->num_rows() > 0) {
            echo json_encode(array('exists' => true));
        } else {
            echo json_encode(array('exists' => false));
        }
    }




    // Método para listar todas las facturas
    public function list_invoices()
    {
        $this->load->library('pagination');

        // Capturar parámetros de la URL
        $cliente_id = $this->input->get('cliente_id');
        $search = $this->input->get('search');
        $search_name = $this->input->get('search_name');
        $search_local = $this->input->get('search_local');
        $search_sorteo = $this->input->get('search_sorteo');
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $num_filas = $this->input->get('num_filas') ? (int) $this->input->get('num_filas') : 10;

        // Validar fechas
        if ($fecha_fin) {
            $fecha_fin = date('Y-m-d', strtotime($fecha_fin));  // Asegurarse de incluir el día completo
        }

        // Construcción de la query string para paginación
        $query_string = http_build_query([
            'search' => $search,
            'search_name' => $search_name,
            'search_sorteo' => $search_sorteo,
            'search_local' => $search_local,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'num_filas' => $num_filas
        ]);

        // Configuración de la paginación
        $config = [];
        $config['base_url'] = site_url('lottery/list_invoices');
        $config['suffix'] = '?' . $query_string;
        $config['first_url'] = $config['base_url'] . $config['suffix'];
        $config['per_page'] = $num_filas;  // Número de registros por página
        $config['num_links'] = 5;  // Número de enlaces de paginación

        // Obtener el total de facturas con los filtros aplicados
        $config['total_rows'] = $this->lottery_model->count_invoices($search, $fecha_inicio, $fecha_fin, $cliente_id);

        // Configuración de la paginación con Bootstrap
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        // Inicializar la paginación
        $this->pagination->initialize($config);

        // Obtener el número de la página actual (offset)
        $page = ($this->uri->segment(3)) ? (int) ($this->uri->segment(3)) : 0;

        // Consultar las facturas paginadas con los filtros
        $facturas = $this->lottery_model->get_paginated_invoices($config['per_page'], $page, $cliente_id, $search, $search_name, $search_sorteo, $search_local, $fecha_inicio, $fecha_fin);

        // Calcular datos adicionales solo si hay facturas
        if (!empty($facturas)) {
            $data['total_facturas'] = count($facturas);
            $clientes = array_unique(array_column($facturas, 'cliente_nombre'));
            $data['total_clientes'] = count($clientes);

            // Calcular el valor total de las facturas
            $valor_total = array_sum(array_column($facturas, 'valor'));
            $data['valor_total'] = number_format($valor_total, 0, '', '.');

            // Calcular la factura promedio
            $data['factura_promedio'] = number_format($valor_total / $data['total_facturas'], 0, '', '.');

            // Calcular el local con más facturas
            $locales = array_count_values(array_column($facturas, 'nombre_local'));
            arsort($locales);  // Ordenar de mayor a menor
            $data['local_lider'] = array_key_first($locales);

            // Calcular el sorteo con más facturas
            $sorteos = array_count_values(array_column($facturas, 'nombre_sorteo'));
            arsort($sorteos);
            $data['sorteo_lider'] = array_key_first($sorteos);
        } else {
            $data['total_facturas'] = 0;
            $data['total_clientes'] = 0;
            $data['factura_promedio'] = 0;
            $data['valor_total'] = 0;
            $data['local_lider'] = 'No disponible';
            $data['sorteo_lider'] = 'No disponible';
        }

        // Enlaces de paginación
        if ($config['total_rows'] > $config['per_page']) {
            $data['pagination'] = $this->pagination->create_links();
        } else {
            $data['pagination'] = ''; // No mostrar enlaces de paginación
        }

        // Pasar los datos a la vista
        $data['facturas'] = $facturas;
        $this->load->view('lottery/list_invoices', $data);
    }





    public function edit_invoice($id)
    {
        $data['factura'] = $this->lottery_model->get_invoice_by_id($id);
        $data['clientes'] = $this->lottery_model->get_all_clients();
        $data['locales'] = $this->lottery_model->get_all_locales(); // Obtener los locales
        $data['sorteos'] = $this->lottery_model->get_all_draws(); // Obtener los sorteos

        $this->load->view('lottery/edit_invoice', $data);
    }

    public function update_invoice($id)
    {
        $numero_factura = $this->input->post('numero_factura');
        $cliente_id = $this->input->post('cliente_id');
        $valor = $this->input->post('valor');
        $local_id = (int) $this->input->post('local_id');
        $sorteo_id = $this->input->post('sorteo_id');

        $data = array(
            'cliente_id' => $cliente_id,
            'numero_factura' => $numero_factura,
            'valor' => $valor,
            'local_id' => $local_id,
            'sorteo_id' => $sorteo_id,
        );

        // Verificar si el número de factura ya existe
        $this->db->where('numero_factura', $numero_factura);
        $query = $this->db->get(db_prefix() . 'lottery_facturas');

        if ($query->num_rows() > 0 && $query->row()->id != $id) {
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'El número de factura ya existe.'));
            redirect('lottery/list_invoices');
            return;
        }

        // Obtener la factura actual para comparar el sorteo_id
        $this->db->where('id', $id);
        $current_factura = $this->db->get(db_prefix() . 'lottery_facturas')->row();

        // Intentar actualizar la factura
        if ($this->lottery_model->update_invoice($id, $data)) {
            // Verificar si el sorteo_id ha cambiado
            if ($current_factura->sorteo_id != $sorteo_id) {
                // Eliminar la asociación anterior en lottery_sorteo_clientes
                $this->db->where('sorteo_id', $current_factura->sorteo_id);
                $this->db->where('cliente_id', $cliente_id);
                $this->db->delete(db_prefix() . 'lottery_sorteo_clientes');

                // Insertar o actualizar la nueva asociación
                $this->lottery_model->associate_client_to_draw($sorteo_id, $cliente_id, $valor);
            } else {
                // Si el sorteo_id no ha cambiado, solo actualizamos el valor de la factura en lottery_sorteo_clientes
                $this->db->where('cliente_id', $cliente_id);
                $this->db->where('sorteo_id', $sorteo_id);
                $this->db->update(db_prefix() . 'lottery_sorteo_clientes', array('valor_factura' => $valor));
            }

            $this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Factura actualizada con éxito.'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No se pudo actualizar la factura.'));
        }

        // Redirigir al listado de facturas
        redirect('lottery/list_invoices');
    }


    // Método para eliminar una factura
    public function delete_invoice($id)
    {
        // Iniciar la transacción
        $this->db->trans_begin();

        // Obtener el cliente_id y sorteo_id asociado a la factura
        $this->db->select('cliente_id, sorteo_id');
        $this->db->where('id', $id);
        $query = $this->db->get(db_prefix() . 'lottery_facturas');

        if ($query->num_rows() > 0) {
            $cliente_id = $query->row()->cliente_id;
            $sorteo_id = $query->row()->sorteo_id;
        } else {
            // Si no se encuentra la factura, finalizar la transacción y redirigir con mensaje de error
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', array(
                'type' => 'error',
                'content' => 'No se pudo encontrar la factura para eliminar.'
            ));
            redirect('lottery/list_invoices');
            return;
        }

        // Eliminar los registros en la tabla 'lottery_sorteo_clientes' basados en cliente_id y sorteo_id
        $this->db->where('cliente_id', $cliente_id);
        $this->db->where('sorteo_id', $sorteo_id);
        $this->db->delete(db_prefix() . 'lottery_sorteo_clientes');

        // Eliminar la factura
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'lottery_facturas');

        // Verificar si hubo errores y decidir si confirmar o revertir la transacción
        if ($this->db->trans_status() === FALSE) {
            // Revertir la transacción si algo salió mal
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', array(
                'type' => 'error',
                'content' => 'No se pudo eliminar la factura. Por favor, intente de nuevo.'
            ));
        } else {
            // Confirmar la transacción si todo fue bien
            $this->db->trans_commit();
            $this->session->set_flashdata('message', array(
                'type' => 'success',
                'content' => 'Factura eliminada con éxito.'
            ));
        }

        // Redirigir al listado de facturas
        redirect('lottery/list_invoices');
    }





    public function get_invoices_by_client($client_id)
    {
        $this->load->model('lottery_model');
        $invoices = $this->lottery_model->get_invoices_by_client($client_id);
        echo json_encode($invoices);
    }

    public function save_clients_to_invoice()
    {
        $client_id = $this->input->post('client_id');
        $invoice_ids = $this->input->post('invoices'); // Array de IDs de facturas

        $this->load->model('lottery_model');

        // Limpiar asociaciones previas
        $this->lottery_model->clear_invoices_from_client($client_id);

        // Asociar facturas seleccionadas
        foreach ($invoice_ids as $invoice_id) {
            $this->lottery_model->associate_invoice_to_client($client_id, $invoice_id);
        }

        $this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Facturas asociadas correctamente.'));
        redirect('lottery/list_clients');
    }


    public function get_client_invoices($client_id)
    {
        // Obtén las facturas del cliente
        $invoices = $this->db->where('cliente_id', $client_id)->get(db_prefix() . 'lottery_facturas')->result_array();
        echo json_encode($invoices);
    }


    public function list_locales()
    {
        // Obtener todos los locales comerciales desde la base de datos
        $data['locales'] = $this->lottery_model->get_all_locales();
        // Cargar la vista con los datos
        $this->load->view('lottery/list_locales', $data);
    }

    public function create_local()
    {
        $data['categoria_locales'] = $this->db->get(db_prefix() . 'lottery_categorias_locales')->result_array();
        $this->load->view('lottery/create_local', $data);
    }

    public function insert_local()
    {
        // Obtener la ubicación ingresada en el formulario y eliminar los espacios en blanco
        $ubicacion = $this->input->post('local');
        $ubicacion_normalizada = preg_replace('/\s+/', '', $ubicacion);  // Eliminar todos los espacios en blanco

        // Verificar si ya existe un local con la misma ubicación, ignorando los espacios en blanco
        $this->db->select('*');
        $locales = $this->db->get('lottery_locales_comerciales')->result();

        foreach ($locales as $local) {
            $ubicacion_existente_normalizada = preg_replace('/\s+/', '', $local->ubicacion); // Normalizar ubicación existente

            if ($ubicacion_existente_normalizada === $ubicacion_normalizada) {
                // Si existe un local con la misma ubicación, establecer mensaje de error
                $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Ya existe un local con esa ubicación'));
                redirect('lottery/list_locales');
                return;
            }
        }

        // Definir el path de subida de las fotos
        $upload_path = FCPATH . 'uploads/locales/';

        // Verificar si el directorio no existe y crearlo
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true); // Crear el directorio con permisos 0755
        }

        // Configuración de la carga de archivos
        $config['upload_path']   = $upload_path;           // Ruta donde se guardarán las fotos
        $config['allowed_types'] = 'jpg|jpeg|png|gif';     // Tipos de archivos permitidos
        $config['max_size']      = 2048;                   // Tamaño máximo en KB (2MB)
        $config['encrypt_name']  = TRUE;                   // Encriptar el nombre del archivo para evitar duplicados

        // Cargar la librería de subida de archivos
        $this->load->library('upload', $config);

        // Inicializar el array para almacenar los nombres de los archivos subidos
        $fotos = array();

        // Verificar si se han subido archivos
        if (!empty($_FILES['fotos']['name'][0])) {
            // Recorrer cada archivo subido
            $files = $_FILES;
            $count = count($_FILES['fotos']['name']);  // Contar la cantidad de archivos subidos

            for ($i = 0; $i < $count; $i++) {
                // Configurar cada archivo de forma individual
                $_FILES['fotos']['name']     = $files['fotos']['name'][$i];
                $_FILES['fotos']['type']     = $files['fotos']['type'][$i];
                $_FILES['fotos']['tmp_name'] = $files['fotos']['tmp_name'][$i];
                $_FILES['fotos']['error']    = $files['fotos']['error'][$i];
                $_FILES['fotos']['size']     = $files['fotos']['size'][$i];

                // Intentar subir el archivo
                if ($this->upload->do_upload('fotos')) {
                    // Obtener la información del archivo subido
                    $uploaded_data = $this->upload->data();
                    // Guardar el nombre del archivo en el array de fotos
                    $fotos[] = $uploaded_data['file_name'];
                } else {
                    // Si hay algún error en la subida, mostrar un mensaje
                    $this->session->set_flashdata('message', array('type' => 'error', 'content' => $this->upload->display_errors()));
                    redirect('lottery/list_locales');
                    return;
                }
            }
        }

        // Convertir los nombres de los archivos en una cadena separada por comas para almacenarlos en la base de datos
        $fotos_str = implode(',', $fotos);

        // Preparar los datos para insertar en la base de datos
        $data = array(
            'nombre'      => $this->input->post('nombre'),
            'ficha_catastro'      => $this->input->post('ficha_catastro'),
            'website'      => $this->input->post('website'),
            'piso_o_nivel'      => $this->input->post('piso_o_nivel'),
            'categoria'      => $this->input->post('categoria'),
            'ubicacion'   => $this->input->post('local'),
            'local'   => $this->input->post('local_ubicacion'),
            'descripcion' => $this->input->post('descripcion'),
            'fotos'       => $fotos_str,  // Guardar los nombres de las fotos subidas
            'whatsapp'    => $this->input->post('whatsapp'),
            'email'       => $this->input->post('email'),
            'activo'      => $this->input->post('activo') ? 1 : 0
        );

        // Insertar los datos en la base de datos
        if ($this->lottery_model->insert_local($data)) {
            // Establecer mensaje de éxito
            $this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Local comercial creado con éxito.'));
        } else {
            // Establecer mensaje de error
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No se pudo crear el local comercial.'));
        }

        // Redirigir al listado de locales
        redirect('lottery/list_locales');
    }


    public function edit_local($id)
    {
        // Obtener los datos del local por ID
        $data['categoria_locales'] = $this->db->get(db_prefix() . 'lottery_categorias_locales')->result_array();
        $data['local'] = $this->lottery_model->get_local_by_id($id);

        // Cargar la vista del formulario de edición
        $this->load->view('lottery/edit_local', $data);
    }

    public function update_local($id)
    {
        // Configurar la carga de archivos
        $config['upload_path'] = './uploads/locales/'; // Asegúrate de que esta carpeta exista y tenga permisos de escritura
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048; // Tamaño máximo en KB
        $config['overwrite'] = FALSE;

        $this->load->library('upload', $config);

        // Obtener los datos actualizados del formulario
        $data = array(
            'nombre'      => $this->input->post('nombre'),
            'ficha_catastro'      => $this->input->post('ficha_catastro'),
            'website'      => $this->input->post('website'),
            'piso_o_nivel'      => $this->input->post('piso_o_nivel'),
            'categoria'      => $this->input->post('categoria'),
            'ubicacion'   => $this->input->post('local'),
            'local'   => $this->input->post('local_ubicacion'),
            'descripcion' => $this->input->post('descripcion'),
            'whatsapp'    => $this->input->post('whatsapp'),
            'email'       => $this->input->post('email'),
            'activo'      => $this->input->post('activo') ? 1 : 0
        );

        // Obtener la foto existente
        $existing_foto = $this->input->post('existing_foto');

        // Manejar la carga de fotos
        if (!empty($_FILES['fotos']['name'])) {
            $_FILES['file']['name'] = $_FILES['fotos']['name'];
            $_FILES['file']['type'] = $_FILES['fotos']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['fotos']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['fotos']['error'];
            $_FILES['file']['size'] = $_FILES['fotos']['size'];

            if ($this->upload->do_upload('file')) {
                $uploaded_file = $this->upload->data('file_name');
                $data['fotos'] = $uploaded_file; // Actualiza con la nueva foto
            } else {
                $this->session->set_flashdata('message', array('type' => 'error', 'content' => $this->upload->display_errors()));
                redirect('lottery/edit_local/' . $id); // Redirigir en caso de error
            }
        } else {
            // Mantener la foto existente si no se sube una nueva
            $data['fotos'] = $existing_foto;
        }

        // Intentar actualizar los datos del local
        if ($this->lottery_model->update_local($id, $data)) {
            // Establecer mensaje de éxito
            $this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Local comercial actualizado con éxito.'));
        } else {
            // Establecer mensaje de error
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No se pudo actualizar el local comercial.'));
        }

        // Redirigir al listado de locales
        redirect('lottery/list_locales');
    }


    public function toggle_local_status($id)
    {
        // Obtener el estado actual del local
        $local = $this->lottery_model->get_local_by_id($id);

        if ($local) {
            // Cambiar el estado de activo
            $nuevo_estado = !$local['activo'];

            // Actualizar el estado en la base de datos
            $actualizado = $this->lottery_model->update_local_status($id, $nuevo_estado);

            if ($actualizado) {
                // Establecer mensaje de éxito dependiendo del estado
                $mensaje = $nuevo_estado ? 'Local comercial activado con éxito.' : 'Local comercial desactivado con éxito.';
                $this->session->set_flashdata('message', array('type' => 'success', 'content' => $mensaje));
            } else {
                // Establecer mensaje de error si no se pudo actualizar
                $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No se pudo actualizar el estado del local comercial.'));
            }
        } else {
            // Establecer mensaje de error si no se encontró el local
            $this->session->set_flashdata('message', array('type' => 'error', 'content' => 'No se encontró el local comercial.'));
        }

        // Redirigir al listado de locales
        redirect('lottery/list_locales');
    }
    public function validate_document()
    {
        // Cargar el modelo si no está cargado
        $this->load->model('lottery_model');

        // Obtener el documento de identidad del request AJAX
        $documento_identidad = $this->input->post('documento_identidad');

        // Verificar si el cliente ya existe
        $existing_client = $this->lottery_model->get_client_by_document($documento_identidad);

        // Responder con un JSON si el cliente existe o no
        if ($existing_client) {
            echo json_encode(array('exists' => true, 'message' => 'El cliente con este documento de identidad ya existe.'));
        } else {
            echo json_encode(array('exists' => false));
        }
    }
}
