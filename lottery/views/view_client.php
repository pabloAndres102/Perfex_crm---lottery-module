<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<style>
    .client-details {
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .client-details h4 {
        color: #333;
        font-weight: bold;
    }

    .client-details p {
        font-size: 16px;
        color: #555;
        margin: 10px 0;
    }

    .breadcrumb {
        background: none;
        padding: 0;
        margin-bottom: 20px;
    }

    .btn-secondary {
        background-color: #007bff;
        border-color: #007bff;
    }
</style>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/index_module'); ?>">Menu</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/list_clients'); ?>">Clientes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalles del Cliente</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body client-details">
                        <!-- Mostrar mensaje flash -->
                        <?php if ($this->session->flashdata('message')): ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('message')['type']; ?>">
                                <?php echo $this->session->flashdata('message')['content']; ?>
                            </div>
                        <?php endif; ?>
                        <h4 class="no-margin mb-4">Detalles del Cliente</h4>
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Asesor:</strong> <?php echo $client->asesor; ?></p>
                                <p><strong>Nombre:</strong> <?php echo $client->nombre; ?></p>
                                <p><strong>Apellido:</strong> <?php echo $client->apellido; ?></p>
                                <p><strong>Tipo de documento:</strong> <?php echo $client->tipo_documento; ?></p>
                                <p><strong>Documento de Identidad:</strong> <?php echo $client->documento_identidad; ?></p>
                                <p><strong>Nivel Académico:</strong> <?php echo $client->nivel_academico; ?></p>
                                <p><strong>Sexo:</strong> <?php echo $client->sexo; ?></p>
                                <p><strong>Fecha de cumpleaños:</strong> <?php echo $client->fecha_cumpleanos; ?></p>
                                <p><strong>Edad:</strong> <?php echo $client->edad; ?></p>
                                <p><strong>Email:</strong> <?php echo $client->email; ?></p>
                                <p><strong>Teléfono:</strong> <?php echo $client->telefono; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Municipio:</strong> <?php echo $client->municipio; ?></p>
                                <p><strong>Dirección de Residencia:</strong> <?php echo $client->direccion_residencia; ?></p>
                                <p><strong>Barrio:</strong> <?php echo $client->barrio; ?></p>
                                <p><strong>Estado Civil:</strong> <?php echo $client->estado_civil; ?></p>
                                <p><strong>Hijos:</strong> <?php echo $client->hijos; ?></p>
                                <p><strong>Hijos menores de 13 años:</strong>
                                    <?php echo ($client->hijos_menores == 'S') ? 'Sí' : 'No'; ?>
                                </p>

                                <p><strong>Ocupación:</strong> <?php echo $client->ocupacion; ?></p>
                                <p><strong>Tipo de Cliente:</strong> <?php echo $client->tipo_cliente; ?></p>
                                <p><strong>Tipo de Persona:</strong> <?php echo $client->tipo_persona; ?></p>
                                <p><strong>Discapacidad:</strong> <?php echo $client->discapacidad; ?></p>
                                <p><strong>Tiene Mascota:</strong> <?php echo $client->tiene_mascota; ?></p>
                                <p><strong>Tiene Vehículo:</strong> <?php echo $client->tiene_vehiculo; ?></p>
                                <p><strong>Datos Personales:</strong> <?php echo ($client->datos_personales == 'S') ? 'Sí' : 'No'; ?></p>
                                <p><strong>Fecha de Creación:</strong> <?php echo $client->fecha_creacion; ?></p>
                                <p><strong>Fecha de Actualización:</strong> <?php echo $client->fecha_actualizacion; ?></p>
                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <a href="<?php echo site_url('lottery/list_clients'); ?>" class="btn btn-secondary">Volver al listado</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>