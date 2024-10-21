<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/index_module'); ?>">Menu</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/list_clients'); ?>">Clientes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Cliente</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- Modal tipo documento -->
                        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel">Crear Tipo de Documento</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Aquí irá el formulario que mostraste anteriormente -->
                                        <?php echo form_open('lottery/Documentos/create', ['id' => 'create-documento-form']); ?>
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" id="nombre" name="nombre" class="form-control" required style="text-transform: uppercase;">
                                        </div>
                                        <div class="form-group">
                                            <label for="descripcion">Descripción</label>
                                            <textarea id="descripcion" name="descripcion" class="form-control" required style="text-transform: uppercase;"></textarea>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success">Crear</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal mascota -->
                        <div class="modal fade" id="crearMascotaModal" tabindex="-1" role="dialog" aria-labelledby="crearMascotaLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="crearMascotaLabel">Crear Mascota</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="panel-body">
                                            <?php echo form_open('lottery/Mascotas/create', ['id' => 'crear-mascota-form']); ?>
                                            <div class="form-group">
                                                <label for="especie">Especie*</label>
                                                <input type="text" class="form-control" name="especie" id="especie" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="descripcion">Descripción</label>
                                                <input type="text" class="form-control" name="descripcion" id="descripcion">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4 class="no-margin">Editar Cliente</h4>

                        <!-- Formulario de edición de cliente -->
                        <?php echo form_open('lottery/update_client/' . $cliente['id'], ['id' => 'update-client-form']); ?>
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>

                        <div class="row">
                            <!-- Columna 1 -->
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="tipo_cliente">Tipo de Cliente*</label>
                                    <select id="tipo_cliente" name="tipo_cliente" class="form-control" required>
                                        <option value="cliente" <?php echo ($cliente['tipo_cliente'] == 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                                        <option value="empleado" <?php echo ($cliente['tipo_cliente'] == 'empleado') ? 'selected' : ''; ?>>Empleado</option>
                                        <option value="local" <?php echo ($cliente['tipo_cliente'] == 'local') ? 'selected' : ''; ?>>Local</option>
                                        <option value="proveedor" <?php echo ($cliente['tipo_cliente'] == 'proveedor') ? 'selected' : ''; ?>>Proveedor</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="tipo_persona">Tipo de Persona*</label>
                                    <select id="tipo_persona" name="tipo_persona" class="form-control" required>
                                        <option value="natural" <?php echo ($cliente['tipo_persona'] == 'natural') ? 'selected' : ''; ?>>Natural</option>
                                        <option value="juridica" <?php echo ($cliente['tipo_persona'] == 'juridica') ? 'selected' : ''; ?>>Jurídica</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="nombre">Nombres*</label>
                                    <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $cliente['nombre']; ?>" required>
                                </div>



                                <div class="form-group">
                                    <label for="apellido">Apellidos*</label>
                                    <input type="text" id="apellido" name="apellido" class="form-control" value="<?php echo $cliente['apellido']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="tipo_documento">Tipo de Documento*</label>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <select id="tipo_documento" name="tipo_documento" class="form-control" required>
                                                <?php foreach ($tipos_documentos as $tipo): ?>
                                                    <option value="<?php echo htmlspecialchars($tipo['nombre']); ?>"
                                                        <?php echo ($cliente['tipo_documento'] == $tipo['nombre']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($tipo['nombre']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>






                                <div class="form-group">
                                    <label for="documento_identidad">Documento de Identidad*</label>
                                    <input type="text" id="documento_identidad" name="documento_identidad" class="form-control" value="<?php echo $cliente['documento_identidad']; ?>" required pattern="\d+">
                                    <small class="form-text text-muted">Ingrese solo números sin puntos ni espacios.</small>
                                </div>

                                <div class="form-group">
                                    <label for="sexo">Sexo*</label>
                                    <select id="sexo" name="sexo" class="form-control" required>
                                        <option value="M" <?php echo ($cliente['sexo'] == 'M') ? 'selected' : ''; ?>>Masculino</option>
                                        <option value="F" <?php echo ($cliente['sexo'] == 'F') ? 'selected' : ''; ?>>Femenino</option>
                                        <option value="O" <?php echo ($cliente['sexo'] == 'O') ? 'selected' : ''; ?>>Otro</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="edad">Edad*</label>
                                    <input type="number" id="edad" name="edad" class="form-control" value="<?php echo $cliente['edad']; ?>" readonly>
                                </div>
                            </div>

                            <!-- Columna 2 -->
                            <div class="col-md-6 mb-4">

                                <div class="form-group">
                                    <label for="discapacidad">Discapacidad</label>
                                    <select id="discapacidad" name="discapacidad" class="form-control">
                                        <option value="no" <?php echo ($cliente['discapacidad'] == 'no') ? 'selected' : ''; ?>>No</option>
                                        <option value="visual" <?php echo ($cliente['discapacidad'] == 'visual') ? 'selected' : ''; ?>>Visual</option>
                                        <option value="fisica" <?php echo ($cliente['discapacidad'] == 'fisica') ? 'selected' : ''; ?>>Física</option>
                                        <option value="cognitiva" <?php echo ($cliente['discapacidad'] == 'cognitiva') ? 'selected' : ''; ?>>Cognitiva</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="tiene_mascota">¿Tiene Mascota?*</label>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <select id="tiene_mascota" name="tiene_mascota" class="form-control" required>
                                                <option value="no">No</option>
                                                <?php foreach ($mascotas as $mascota): ?>
                                                    <option value="<?php echo htmlspecialchars($mascota['especie']); ?>"
                                                        <?php echo (isset($mascota_seleccionada) && $mascota_seleccionada === $mascota['especie']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($mascota['especie']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#crearMascotaModal">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>





                                <div class="form-group">
                                    <label for="tiene_vehiculo">¿Tiene Vehículo?</label>
                                    <select id="tiene_vehiculo" name="tiene_vehiculo" class="form-control">
                                        <option value="no" <?php echo ($cliente['tiene_vehiculo'] == 'no') ? 'selected' : ''; ?>>No</option>
                                        <option value="carro" <?php echo ($cliente['tiene_vehiculo'] == 'carro') ? 'selected' : ''; ?>>Carro</option>
                                        <option value="moto" <?php echo ($cliente['tiene_vehiculo'] == 'moto') ? 'selected' : ''; ?>>Moto</option>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="email">Email*</label>
                                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $cliente['email']; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="telefono">Teléfono*</label>
                                    <input type="text" id="telefono" name="telefono" class="form-control" value="<?php echo $cliente['telefono']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="direccion_residencia">Dirección de Residencia</label>
                                    <input type="text" id="direccion_residencia" name="direccion_residencia" class="form-control" value="<?php echo $cliente['direccion_residencia']; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="municipio">Municipio*</label>
                                    <input type="text" id="municipio" name="municipio" class="form-control" value="<?php echo $cliente['municipio']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="barrio">Barrio*</label>
                                    <input type="text" id="barrio" name="barrio" class="form-control" value="<?php echo $cliente['barrio']; ?>" required>
                                </div>
                            </div>

                            <!-- Columna 3 -->
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="estado_civil">Estado Civil</label>
                                    <select id="estado_civil" name="estado_civil" class="form-control">
                                        <option value="soltero" <?php echo ($cliente['estado_civil'] == 'soltero') ? 'selected' : ''; ?>>Soltero</option>
                                        <option value="casado" <?php echo ($cliente['estado_civil'] == 'casado') ? 'selected' : ''; ?>>Casado</option>
                                        <option value="divorciado" <?php echo ($cliente['estado_civil'] == 'divorciado') ? 'selected' : ''; ?>>Divorciado</option>
                                        <option value="viudo" <?php echo ($cliente['estado_civil'] == 'viudo') ? 'selected' : ''; ?>>Viudo</option>
                                        <option value="otro" <?php echo ($cliente['estado_civil'] == 'otro') ? 'selected' : ''; ?>>Otro</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="hijos">Número de Hijos*</label>
                                    <select id="hijos" name="hijos" class="form-control" required>
                                        <?php for ($i = 0; $i <= 3; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($cliente['hijos'] == $i) ? 'selected' : ''; ?>>
                                                <?php echo $i; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <!-- Segundo input que solo se mostrará si se selecciona un valor mayor a 0 -->
                                <div class="form-group" id="hijos_menores_group" style="<?php echo ($cliente['hijos'] > 0) ? '' : 'display: none;'; ?>">
                                    <label for="hijos_menores">¿Tiene hijos menores de 13 años?</label>
                                    <select id="hijos_menores" name="hijos_menores" class="form-control">
                                        <!-- Si el valor de hijos_menores es 'si', pre-seleccionamos 'Sí' -->
                                        <option value="S" <?php echo ($cliente['hijos_menores'] == 'S') ? 'selected' : ''; ?>>Sí</option>
                                        <!-- Si el valor de hijos_menores es 'no', pre-seleccionamos 'No' -->
                                        <option value="N" <?php echo ($cliente['hijos_menores'] == 'N') ? 'selected' : ''; ?>>No</option>
                                    </select>
                                </div>



                                <div class="form-group">
                                    <label for="nivel_academico">Nivel Académico</label>
                                    <select id="nivel_academico" name="nivel_academico" class="form-control">
                                        <option value="ninguno" <?php echo ($cliente['nivel_academico'] == 'ninguno') ? 'selected' : ''; ?>>Ninguno</option>
                                        <option value="primaria" <?php echo ($cliente['nivel_academico'] == 'primaria') ? 'selected' : ''; ?>>Primaria</option>
                                        <option value="bachiller" <?php echo ($cliente['nivel_academico'] == 'bachiller') ? 'selected' : ''; ?>>Bachiller</option>
                                        <option value="pregrado" <?php echo ($cliente['nivel_academico'] == 'pregrado') ? 'selected' : ''; ?>>Pregrado</option>
                                        <option value="posgrado" <?php echo ($cliente['nivel_academico'] == 'posgrado') ? 'selected' : ''; ?>>Posgrado</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="ocupacion">Ocupación*</label>
                                    <input type="text" id="ocupacion" name="ocupacion" class="form-control" value="<?php echo $cliente['ocupacion']; ?>" required>
                                </div>

                                <div class="form-group form-check">
                                    <input type="checkbox" id="datos_personales" name="datos_personales" class="form-check-input" value="1" <?php echo ($cliente['datos_personales'] == '1') ? 'checked' : ''; ?> required>
                                    <label for="datos_personales" class="form-check-label">Aceptar Términos, Condiciones y Datos Personales.</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a href="<?php echo site_url('lottery/list_clients'); ?>" class="btn btn-secondary">Cancelar</a>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(document).ready(function() {
        $('#create-documento-form').on('submit', function(e) {
            e.preventDefault(); // Prevenir el comportamiento por defecto del formulario

            $.ajax({
                url: $(this).attr('action'), // La URL a la que enviar el formulario
                method: 'POST',
                data: $(this).serialize(), // Serializar los datos del formulario
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        // Aquí podrías actualizar el dropdown de "tipo_documento" o cerrar el modal
                        $('#tipo_documento').append(new Option(response.documento.nombre, response.documento.nombre));
                        $('#modal').modal('hide'); // Cerrar el modal
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Hubo un error al intentar crear el tipo de documento.');
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#crear-mascota-form').on('submit', function(event) {
            event.preventDefault(); // Evita el envío tradicional del formulario

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json', // Asegura que la respuesta sea interpretada como JSON
                success: function(response) {
                    if (response.success) {
                        // Cerrar el modal
                        $('#crearMascotaModal').modal('hide');

                        // Añadir la nueva mascota al select de "tiene_mascota"
                        var newOption = $('<option></option>')
                            .val(response.data.especie)
                            .text(response.data.especie);
                        $('#tiene_mascota').append(newOption);

                        // Mostrar mensaje de éxito (opcional)
                        alert('Mascota creada exitosamente');
                    } else if (response.error) {
                        alert('Hubo un error: ' + response.error);
                    } else {
                        alert('Hubo un error desconocido.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Muestra información de error si la petición AJAX falla
                    alert('Error al crear la mascota: ' + textStatus + ' - ' + errorThrown);
                }
            });
        });
    });
</script>
<script>
    // Script para mostrar u ocultar el input de "¿Tiene hijos menores de 13 años?" basado en el valor de "hijos"
    document.getElementById('hijos').addEventListener('change', function() {
        var hijos = this.value;
        var hijosMenoresGroup = document.getElementById('hijos_menores_group');

        // Mostrar u ocultar el grupo de hijos menores si el número de hijos es mayor a 0
        if (hijos > 0) {
            hijosMenoresGroup.style.display = '';
        } else {
            hijosMenoresGroup.style.display = 'none';
        }
    });

    // Ejecutar la función al cargar la página para asegurar el estado correcto inicial
    (function() {
        var hijos = document.getElementById('hijos').value;
        var hijosMenoresGroup = document.getElementById('hijos_menores_group');

        // Mostrar u ocultar el grupo de hijos menores basado en el valor de "hijos" al cargar la página
        if (hijos > 0) {
            hijosMenoresGroup.style.display = '';
        } else {
            hijosMenoresGroup.style.display = 'none';
        }
    })();
</script>
<script>
    // JavaScript para calcular la edad
    document.getElementById('fecha_nacimiento').addEventListener('change', function() {
        var fecha = new Date(this.value);
        var hoy = new Date();
        var edad = hoy.getFullYear() - fecha.getFullYear();
        var mes = hoy.getMonth() - fecha.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fecha.getDate())) {
            edad--;
        }
        document.getElementById('edad').value = edad;
    });

    // JavaScript para cargar comunas dinámicamente
    document.getElementById('municipio').addEventListener('change', function() {
        var municipioId = this.value;
        // Aquí puedes realizar una petición AJAX para cargar las comunas basadas en el municipio seleccionado
        // Ejemplo usando jQuery:
        $.ajax({
            url: '<?php echo site_url('lottery/get_comunas'); ?>',
            type: 'POST',
            data: {
                municipio: municipioId
            },
            success: function(data) {
                $('#comuna').html(data);
                // Opcional: Vaciar el campo barrio si el municipio cambia
                $('#barrio').val('');
            }
        });
    });
</script>

</body>

</html>