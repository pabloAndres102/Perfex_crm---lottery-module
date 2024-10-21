<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata('message')): ?>
                    <?php
                    $message = $this->session->flashdata('message');
                    $alert_type = ($message['type'] == 'success') ? 'alert-success' : 'alert-danger';
                    ?>
                    <div class="alert <?php echo $alert_type; ?>">
                        <?php echo $message['content']; ?>
                    </div>
                <?php endif; ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/index_module'); ?>">Menu</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/configurations'); ?>">Configuraciones</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Barrios</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                    <a href="<?php echo site_url('lottery/barrios/create'); ?>" class="btn btn-primary">Añadir Barrio</a>
                        <h1>Listado de Barrios</h1>
                        <div>
                            <?php echo $pagination; ?>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($barrios as $barrio): ?>
                                    <tr>
                                        <td><?php echo $barrio->id; ?></td>
                                        <td><?php echo $barrio->nombre; ?></td>
                                        <td><?php echo $barrio->descripcion; ?></td>
                                        <td>
                                        <a href="<?php echo site_url('lottery/barrios/edit/' . $barrio->id); ?>" class="btn btn-warning">Editar</a>
                                            <!-- Enlace para eliminar -->
                                            <a href="<?php echo site_url('lottery/barrios/delete/' . $barrio->id); ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este barrio?');">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>