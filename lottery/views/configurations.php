<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Título de la página -->
                <div class="panel_s">
                    <div class="panel-body">
                        <h1 class="mb-4">Configuraciones del Sistema</h1>
                        <p class="lead">Desde aquí puedes gestionar todos los datos importantes del sistema.</p>
                    </div>
                </div>

                <div class="row">
                    <!-- Menú lateral de configuraciones -->
                    <div class="col-md-3">
                        <div class="panel_s">
                            <div class="panel-body">
                                <h3 class="mb-3">Menú de Configuraciones</h3>
                                <ul class="nav nav-pills nav-stacked">
                                    <!-- Sección de Barrios -->
                                    <li class="nav-item">
                                        <a href="<?= admin_url('lottery/barrios') ?>" class="nav-link">
                                            <i class="fa fa-map-marker-alt"></i> Gestionar Barrios
                                        </a>
                                    </li>

                                    <!-- Sección de municipios -->
                                    <li class="nav-item">
                                        <a href="<?= admin_url('lottery/municipios') ?>" class="nav-link">
                                            <i class="fa fa-map-marker-alt"></i> Gestionar Municipios
                                        </a>
                                    </li>

                                    <!-- Sección de Mascotas -->
                                    <li class="nav-item">
                                        <a href="<?= admin_url('lottery/mascotas') ?>" class="nav-link">
                                            <i class="fa fa-paw"></i> Mascotas
                                        </a>
                                    </li>

                                    <!-- Sección de tipo de documento -->
                                    <li class="nav-item">
                                        <a href="<?= admin_url('lottery/documentos') ?>" class="nav-link">
                                            <i class="fa fa-id-card"></i> Tipos de documento
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="<?= admin_url('lottery/categorias_locales') ?>" class="nav-link">
                                            <i class="fa fa-building"></i> Categorias de local
                                        </a>
                                    </li>


                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Sección principal de contenido -->
                    <div class="col-md-9">
                        <div class="panel_s">
                            <div class="panel-body">
                                <h2 class="mb-4">Bienvenido al Panel de Configuraciones</h2>

                                <div class="list-group">
                                    <!-- Sección de Barrios -->
                                    <a href="<?= admin_url('lottery/barrios') ?>" class="list-group-item list-group-item-action">
                                        <i class="fa fa-map-marker-alt"></i> <strong>Barrios</strong>
                                        <span class="badge badge-info float-right">Gestiona los barrios disponibles en el sistema.</span>
                                    </a>

                                    <!-- Sección de Tipos de Documento -->
                                    <a href="<?= admin_url('lottery/municipios') ?>" class="list-group-item list-group-item-action">
                                        <i class="fa fa-map-marker-alt"></i> <strong>Municipios</strong>
                                        <span class="badge badge-info float-right">Gestiona los municipios disponibles en el sistema.</span>
                                    </a>

                                    <!-- Sección de Mascotas -->
                                    <a href="<?= admin_url('lottery/mascotas') ?>" class="list-group-item list-group-item-action">
                                        <i class="fa fa-paw"></i> <strong>Mascotas</strong>
                                        <span class="badge badge-info float-right">Gestiona las mascotas disponibles en el sistema.</span>
                                    </a>

                                    <!-- Sección de tipo de documentos -->
                                    <a href="<?= admin_url('lottery/documentos') ?>" class="list-group-item list-group-item-action">
                                        <i class="fa fa-id-card"></i> <strong>Tipos de documentos</strong>
                                        <span class="badge badge-info float-right">Gestiona los tipos de documento disponibles en el sistema.</span>
                                    </a>

                                    <!-- Sección de categorias de locales -->
                                    <a href="<?= admin_url('lottery/categorias_locales') ?>" class="list-group-item list-group-item-action">
                                        <i class="fa fa-building"></i> <strong>Categorias de locales</strong>
                                        <span class="badge badge-info float-right">Gestiona las categorias de locales disponibles en el sistema.</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>