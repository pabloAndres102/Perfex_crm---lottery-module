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
                        <li class="breadcrumb-item active" aria-current="page">Editar barrio</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                    <form  action="<?php echo site_url('lottery/barrios/edit/' . $barrio->id); ?>" method="post" enctype="multipart/form-data">
                    <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                            <div>
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="<?php echo $barrio->nombre; ?>" required>
                            </div>
                            <div>
                                <label for="descripcion">Descripción</label>
                                <textarea id="descripcion" name="descripcion" required><?php echo $barrio->descripcion; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Actualizar Barrio</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php init_tail(); ?>