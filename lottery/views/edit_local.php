<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/index_module'); ?>">Menu</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/list_locales'); ?>">Locales</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar local</li>
                    </ol>
                </nav>
                <div class="panel_s">
                <div class="modal fade" id="crearCategoriaModal" tabindex="-1" role="dialog" aria-labelledby="crearCategoriaModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="crearCategoriaModalLabel">Crear nueva categoría</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="panel-body">
                                        <?php echo form_open('lottery/Categorias_locales/create', ['id' => 'crear-categoria-form']); ?>
                                        <div class="form-group">
                                            <label for="nombre">Nombre*</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="descripcion">Descripción</label>
                                            <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success">Crear Categoría</button>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container mt-5">
                        <h1 class="mb-4">Editar Local Comercial</h1>

                        <!-- Mostrar mensaje de error o éxito -->
                        <?php if ($this->session->flashdata('message')): ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('message')['type']; ?>">
                                <?php echo $this->session->flashdata('message')['content']; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formulario de edición del local -->
                        <form id="localForm" action="<?php echo site_url('lottery/update_local/' . $local['id']); ?>" method="post" enctype="multipart/form-data">
                            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                            <?php echo form_hidden('existing_foto', htmlspecialchars($local['fotos'] ?? '', ENT_QUOTES, 'UTF-8')); ?>
                            <div class="form-group">
                                <label for="nombre">Nombre*</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo set_value('nombre', $local['nombre']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="local">Ubicación*</label>
                                <input type="text" name="local" id="local" class="form-control" value="<?php echo set_value('local', $local['local']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="piso_o_nivel">Piso o nivel*</label>
                                <input type="text" name="piso_o_nivel" id="piso_o_nivel" class="form-control" value="<?php echo set_value('piso_o_nivel', $local['piso_o_nivel']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="ubicacion">Número de local*</label>
                                <input type="text" name="ubicacion" id="ubicacion" class="form-control" value="<?php echo set_value('ubicacion', $local['ubicacion']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="categoria">Categoría*</label>
                                <div class="row">
                                    <div class="col-md-10">
                                        <select name="categoria" id="categoria" class="form-control" required>
                                            <option value="">Seleccione una categoría</option>
                                            <?php foreach ($categoria_locales as $categoria): ?>
                                                <option value="<?= htmlspecialchars($categoria['nombre']); ?>"
                                                    <?php if ($categoria['nombre'] === $local['categoria']): ?> selected <?php endif; ?>>
                                                    <?= htmlspecialchars($categoria['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#crearCategoriaModal">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <textarea name="descripcion" id="descripcion" class="form-control"><?php echo set_value('descripcion', $local['descripcion']); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="whatsapp">Teléfono:</label>
                                <input type="number" name="whatsapp" id="whatsapp" class="form-control" value="<?php echo set_value('whatsapp', $local['whatsapp']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo set_value('email', $local['email']); ?>" maxlength="255">
                            </div>

                            <div class="form-group">
                                <label for="website">Sitio web:</label>
                                <input type="url" name="website" id="website" class="form-control" value="<?php echo set_value('website', $local['website']); ?>">
                            </div>

                            <div class="form-group d-flex align-items-center">
                                <label for="fotos" class="mr-2">Foto del Local</label>
                                <input type="file" class="form-control-file mr-3" id="fotos" name="fotos">

                                <div id="existing-image" class="ml-3">
                                    <?php if (!empty($local['fotos'])): ?>
                                        <img src="<?php echo base_url('uploads/locales/' . htmlspecialchars($local['fotos'])); ?>" alt="Imagen Actual" style="width: 150px; height: auto;">
                                    <?php else: ?>
                                        <p>No hay foto actual.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group" hidden>
                                <label for="ficha_catastro">Ficha catastro:</label>
                                <input type="number" name="ficha_catastro" id="ficha_catastro" class="form-control" value="<?php echo set_value('ficha_catastro', $local['ficha_catastro']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="activo">¿Está Activo?:</label>
                                <select name="activo" id="activo" class="form-control" required>
                                    <option value="1" <?php echo set_select('activo', '1', $local['activo'] == 1); ?>>Sí</option>
                                    <option value="0" <?php echo set_select('activo', '0', $local['activo'] == 0); ?>>No</option>
                                </select>
                            </div>


                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <a href="<?php echo site_url('lottery/list_locales'); ?>" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#modal-form').on('submit', function(e) {
            e.preventDefault(); // Evita el envío normal del formulario

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'), // La URL debe apuntar a tu controlador
                data: $(this).serialize(), // Envía los datos del formulario
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Si se creó la categoría exitosamente
                        alert('Categoría creada exitosamente: ' + response.data.nombre);
                        // Aquí puedes agregar la nueva opción al select, si es necesario
                        $('#categoria').append(new Option(response.data.nombre, response.data.nombre));
                        // Cerrar el modal, si es necesario
                        $('#modal').modal('hide');
                    } else {
                        // Si hubo un error
                        alert(response.error);
                    }
                },
                error: function(xhr, status, error) {
                    // Manejo de errores en la solicitud
                    alert('Ocurrió un error: ' + error);
                }
            });
        });
    });
</script>
<script>
    document.getElementById('localForm').addEventListener('submit', function(e) {
        var fotosInput = document.getElementById('fotos');
        var allowedExtensions = ['png', 'jpeg', 'jpg'];
        var files = fotosInput.files;

        for (var i = 0; i < files.length; i++) {
            var fileExtension = files[i].name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                alert('Solo se permiten archivos .png y .jpeg');
                e.preventDefault();
                return;
            }
        }
    });
</script>
<?php init_tail(); ?>
</body>

</html>