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
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/barrios'); ?>">Barrios</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear barrio</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open_multipart('lottery/barrios/create', ['id' => 'create-barrio-form']); ?>
                        <div>
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>
                        <div>
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Crear Barrio</button>
                        </form>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php init_tail(); ?>