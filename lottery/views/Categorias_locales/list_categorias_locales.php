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
                        <li class="breadcrumb-item active" aria-current="page">Categorías</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">Categorías de Locales</h4><br>
                        <a href="<?php echo site_url('lottery/Categorias_locales/create'); ?>" class="btn btn-success">Agregar Categoría</a>
                        <div class="clearfix"></div>
                        <hr>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categorias as $categoria) { ?>
                                    <tr>
                                        <td><?php echo $categoria['nombre']; ?></td>
                                        <td><?php echo $categoria['descripcion']; ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('lottery/Categorias_locales/edit/' . $categoria['id']); ?>" class="btn btn-default btn-icon"><i class="fa fa-pencil"></i></a>
                                            <a href="<?php echo admin_url('lottery/Categorias_locales/delete/' . $categoria['id']); ?>" class="btn btn-danger btn-icon"><i class="fa fa-remove"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>