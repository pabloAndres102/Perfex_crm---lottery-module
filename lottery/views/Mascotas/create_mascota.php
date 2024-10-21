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
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/mascotas'); ?>">Mascotas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear mascota</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <h3>Crear mascota</h3><br>
                        <?php echo form_open('lottery/Mascotas/create'); ?>
                        <div class="form-group">
                            <label for="especie">Especie*</label>
                            <input type="text" class="form-control" name="especie" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripcion</label>
                            <input type="text" class="form-control" name="descripcion">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php init_tail(); ?>