<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/index_module'); ?>">Menu</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/configurations'); ?>">Configuraciones</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Documentos</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                        <h4 class="no-margin">Tipos de Documentos</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documentos as $documento): ?>
                                    <tr>
                                        <td><?php echo strtoupper($documento['nombre']); ?></td>
                                        <td><?php echo strtoupper($documento['descripcion']); ?></td>
                                        <td>
                                            <a href="<?php echo site_url('lottery/Documentos/edit/' . $documento['id']); ?>" class="btn btn-primary">Editar</a>
                                            <a href="<?php echo site_url('lottery/Documentos/delete/' . $documento['id']); ?>" class="btn btn-danger">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <a href="<?php echo site_url('lottery/Documentos/create'); ?>" class="btn btn-success">Crear Tipo de Documento</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>