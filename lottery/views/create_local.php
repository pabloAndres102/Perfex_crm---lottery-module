<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/index_module'); ?>">Menú</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/list_locales'); ?>">Locales</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear local</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <!-- Modal para crear categoría -->
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
                                        <input type="hidden" name="redirect_url" value="lottery/create_local"> <!-- Campo oculto -->
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

                    <div class="panel-body">
                        <h4>Crear Nuevo Local Comercial</h4>
                        <form id="localForm" action="<?php echo site_url('lottery/insert_local'); ?>" method="post" enctype="multipart/form-data">
                            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>

                            <div class="form-group">
                                <label for="nombre">Nombre*</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="local">Ubicacion*</label>
                                <input type="text" name="local" id="local" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="piso_o_nivel">Piso o nivel*</label>
                                <input type="text" name="piso_o_nivel" id="piso_o_nivel" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="local_ubicacion">Número de local*</label>
                                <input type="text" name="local_ubicacion" id="local_ubicacion" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="categoria">Categoría*</label>
                                <div class="row">
                                    <div class="col-md-10">
                                        <select name="categoria" id="categoria" class="form-control" required>
                                            <option value="">Seleccione una categoría</option>
                                            <?php foreach ($categoria_locales as $categoria): ?>
                                                <option value="<?= htmlspecialchars($categoria['nombre']); ?>">
                                                    <?= htmlspecialchars($categoria['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#crearCategoriaModal">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>





                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="whatsapp">Telefono:</label>
                                <input type="number" name="whatsapp" id="whatsapp" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" name="email" id="email" class="form-control" maxlength="255">
                            </div>

                            <div class="form-group">
                                <label for="website">Sitio web:</label>
                                <input type="url" name="website" id="website" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="fotos">Foto:</label>
                                <input type="file" name="fotos[]" id="fotos" class="form-control" multiple accept="image/*">
                            </div>

                            <div class="form-group" hidden>
                                <label for="ficha_catastro">Ficha catastro:</label>
                                <input type="number" name="ficha_catastro" id="ficha_catastro" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="activo">¿Está Activo?:</label>
                                <select name="activo" id="activo" class="form-control" required>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Crear Local Comercial</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#crear-categoria-form').on('submit', function(e) {
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
                    // Cerrar el modal
                    $('#crearCategoriaModal').modal('hide');
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