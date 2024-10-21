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
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/documentos'); ?>">Documentos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear documento</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">Crear Tipo de Documento</h4>
                        <?php echo form_open('lottery/Documentos/create', ['id' => 'create-documento-form']); ?>
                        <div>
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" required style="text-transform: uppercase;">
                        </div>
                        <div>
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" required style="text-transform: uppercase;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Crear</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php init_tail(); ?>