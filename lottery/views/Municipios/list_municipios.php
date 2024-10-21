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
                        <li class="breadcrumb-item active" aria-current="page">Municipios</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                    <a href="<?php echo site_url('lottery/municipios/create'); ?>" class="btn btn-primary">Añadir Municipio</a>
                        <h1>Listado de Municipios</h1>
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
                                <?php foreach ($municipios as $municipio): ?>
                                    <tr>
                                        <td><?php echo $municipio->id; ?></td>
                                        <td><?php echo $municipio->nombre; ?></td>
                                        <td><?php echo $municipio->descripcion; ?></td>
                                        <td>
                                        <a href="<?php echo site_url('lottery/municipios/edit/' . $municipio->id); ?>" class="btn btn-warning">Editar</a>
                                            <!-- Enlace para eliminar -->
                                            <a href="<?php echo site_url('lottery/municipios/delete/' . $municipio->id); ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este municipio?');">Eliminar</a>
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