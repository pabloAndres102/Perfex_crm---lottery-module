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
                        <li class="breadcrumb-item active" aria-current="page">Mascotas</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                        <h2>Lista de Mascotas</h2>
                        <a href="<?php echo site_url('lottery/Mascotas/create'); ?>" class="btn btn-success">Agregar Mascota</a>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Especie</th>
                                    <th>Descripcion</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mascotas as $mascota) { ?>
                                    <tr>
                                        <td><?php echo $mascota['especie']; ?></td>
                                        <td><?php echo $mascota['descripcion']; ?></td>
                                        <td>
                                            <a href="<?php echo site_url('lottery/Mascotas/edit/' . $mascota['id']); ?>" class="btn btn-warning">Editar</a>
                                            <a href="<?php echo site_url('lottery/Mascotas/delete/' . $mascota['id']); ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta mascota?');">Eliminar</a>
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