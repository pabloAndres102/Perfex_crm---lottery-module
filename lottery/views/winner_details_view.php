<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    .card {
        border-radius: 12px;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #343a40;
        color: #fff;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: #f2f2f2;
    }

    .thead-dark th {
        text-align: center;
        font-size: 1.25rem;
    }

    h3 {
        font-weight: 700;
        color: #4a4a4a;
    }
</style>
<div class="panel_s">
    <div class="panel-body">
        <div class="container mt-5">
            <h3 class="text-center mb-4">Detalles del Ganador</h3>
            <div class="card shadow-lg">
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th colspan="2" class="text-center">Información del Ganador</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Asesor</th>
                                <td><?php echo $client['asesor']; ?></td>
                            </tr>
                            <tr>
                                <th>Nombre</th>
                                <td><?php echo $client['nombre']; ?></td>
                            </tr>
                            <tr>
                                <th>Apellido</th>
                                <td><?php echo $client['apellido']; ?></td>
                            </tr>
                            <tr>
                                <th>Documento de Identidad</th>
                                <td><?php echo $client['documento_identidad']; ?></td>
                            </tr>
                            <tr>
                                <th>Nivel Académico</th>
                                <td><?php echo $client['nivel_academico']; ?></td>
                            </tr>
                            <tr>
                                <th>Sexo</th>
                                <td><?php echo $client['sexo']; ?></td>
                            </tr>
                            <tr>
                                <th>Edad</th>
                                <td><?php echo $client['edad']; ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo $client['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Teléfono</th>
                                <td><?php echo $client['telefono']; ?></td>
                            </tr>
                            <tr>
                                <th>Municipio</th>
                                <td><?php echo $client['municipio']; ?></td>
                            </tr>
                            <tr>
                                <th>Dirección de Residencia</th>
                                <td><?php echo $client['direccion_residencia']; ?></td>
                            </tr>
                            <tr>
                                <th>Barrio</th>
                                <td><?php echo $client['barrio']; ?></td>
                            </tr>
                            <tr>
                                <th>Estado Civil</th>
                                <td><?php echo $client['estado_civil']; ?></td>
                            </tr>
                            <tr>
                                <th>Hijos</th>
                                <td><?php echo $client['hijos']; ?></td>
                            </tr>
                            <tr>
                                <th>Ocupación</th>
                                <td><?php echo $client['ocupacion']; ?></td>
                            </tr>
                            <tr>
                                <th>Tipo de Cliente</th>
                                <td><?php echo $client['tipo_cliente']; ?></td>
                            </tr>
                            <tr>
                                <th>Datos Personales</th>
                                <td><?php echo $client['datos_personales']; ?></td>
                            </tr>
                            <tr>
                                <th>Fecha de Creación</th>
                                <td><?php echo $client['fecha_creacion']; ?></td>
                            </tr>
                            <tr>
                                <th>Fecha de Actualización</th>
                                <td><?php echo $client['fecha_actualizacion']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>