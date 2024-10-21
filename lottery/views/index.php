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
                        <li class="breadcrumb-item active" aria-current="page">Crear Cliente</li>
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


                        <h4 class="no-margin mb-4"><strong>Agregar Nuevo Cliente</strong></h4>
                        <br>
                        <?php echo form_open('lottery/insert_client', ['id' => 'insert-client-form']); ?>
                        <div class="row">
                            <!-- Columna 1 -->
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label for="tipo_cliente">Tipo de Cliente*</label>
                                    <select id="tipo_cliente" name="tipo_cliente" class="form-control" required>
                                        <option value="cliente">Cliente</option>
                                        <option value="empleado">Empleado</option>
                                        <option value="local">Local</option>
                                        <option value="proveedor">Proveedor</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="tipo_persona">Tipo de Persona*</label>
                                    <select id="tipo_persona" name="tipo_persona" class="form-control" required>
                                        <option value="natural">Natural</option>
                                        <option value="juridica">Jurídica</option>
                                    </select>
                                </div>

                                <!-- Asesor oculto -->
                                <input type="hidden" name="asesor" value="<?php echo $this->session->userdata('user_id'); ?>">

                                <div class="form-group">
                                    <label for="nombre">Nombres*</label>
                                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="apellido">Apellidos*</label>
                                    <input type="text" id="apellido" name="apellido" class="form-control" required>
                                </div>



                                <div class="form-group">
                                    <label for="tipo_documento">Tipo de Documento*</label>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <select id="tipo_documento" name="tipo_documento" class="form-control" required>
                                                <?php foreach ($tipos_documentos as $tipo): ?>
                                                    <option value="<?php echo htmlspecialchars($tipo['nombre']); ?>">
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
                                    <input type="number" id="documento_identidad" name="documento_identidad" class="form-control" required pattern="\d+">
                                    <small class="form-text text-muted">Ingrese solo números sin puntos ni espacios.</small>
                                </div>



                                <div class="form-group">
                                    <label for="sexo">Sexo*</label>
                                    <select id="sexo" name="sexo" class="form-control" required>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                        <option value="O">Otro</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="fecha_cumpleanos">Fecha de nacimiento*</label>
                                    <input type="date" id="fecha_cumpleanos" name="fecha_cumpleanos" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="edad">Edad*</label>
                                    <input type="number" id="edad" name="edad" class="form-control" readonly>
                                </div>
                            </div>

                            <!-- Columna 2 -->
                            <div class="col-md-6 mb-4">

                                <div class="form-group">
                                    <label for="discapacidad">Discapacidad*</label>
                                    <select id="discapacidad" name="discapacidad" class="form-control" required>
                                        <option value="no">No</option>
                                        <option value="visual">Visual</option>
                                        <option value="fisica">Física</option>
                                        <option value="cognitiva">Cognitiva</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="tiene_mascota">¿Tiene Mascota?*</label>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <select id="tiene_mascota" name="tiene_mascota" class="form-control" required>
                                                <option value="no">No</option>
                                                <?php foreach ($mascotas as $mascota): ?>
                                                    <option value="<?php echo htmlspecialchars($mascota['especie']); ?>">
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
                                    <label for="tiene_vehiculo">¿Tiene Vehículo?*</label>
                                    <select id="tiene_vehiculo" name="tiene_vehiculo" class="form-control" required>
                                        <option value="no">No</option>
                                        <option value="carro">Carro</option>
                                        <option value="moto">Moto</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="telefono">Teléfono*</label>
                                    <input type="text" id="telefono" name="telefono" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="direccion_residencia">Dirección de Residencia*</label>
                                    <input type="text" id="direccion_residencia" name="direccion_residencia" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="municipio">Municipio</label>
                                    <input type="text" id="municipio" name="municipio" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="barrio">Barrio</label>
                                    <input type="text" id="barrio" name="barrio" class="form-control">
                                </div>
                            </div>

                            <!-- Columna 3 -->
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="estado_civil">Estado Civil*</label>
                                    <select id="estado_civil" name="estado_civil" class="form-control" required>
                                        <option value="soltero">Soltero</option>
                                        <option value="casado">Casado</option>
                                        <option value="union_libre">Unión libre</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="hijos">Número de Hijos*</label>
                                    <select id="hijos" name="hijos" class="form-control" required>
                                        <?php for ($i = 0; $i <= 3; $i++): ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <!-- Segundo input que solo se mostrará si se selecciona un valor mayor a 0 -->
                                <div class="form-group" id="hijos_menores_group" style="display: none;">
                                    <label for="hijos_menores">¿Tiene hijos menores de 13 años?</label>
                                    <select id="hijos_menores" name="hijos_menores" class="form-control">
                                        <option value="">Seleccione una opción</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="nivel_academico">Nivel Académico*</label>
                                    <select id="nivel_academico" name="nivel_academico" class="form-control" required>
                                        <option value="ninguno">Ninguno</option>
                                        <option value="primaria">Primaria</option>
                                        <option value="bachiller">Bachiller</option>
                                        <option value="pregrado">Pregrado</option>
                                        <option value="posgrado">Posgrado</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="ocupacion">Ocupación</label>
                                    <select id="ocupacion" name="ocupacion" class="form-control" required>
                                        <option value="empleado">Empleado</option>
                                        <option value="independiente">Independiente</option>
                                        <option value="ama_de_casa">Ama de Casa</option>
                                        <option value="pensionado">Pensionado</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <!-- Campo hidden para enviar 'N' si el checkbox no está marcado -->
                                        <input type="hidden" name="datos_personales" value="N">
                                        <input type="checkbox" id="datos_personales" name="datos_personales" value="S" required>
                                        Aceptar Términos, Condiciones y Datos Personales.*
                                    </label>
                                </div>

                            </div>
                        </div>

                        <!-- Botón de Envío -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Guardar Cliente</button>
                        </div>

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
    // Función para mostrar/ocultar el input basado en el valor del select de hijos
    document.getElementById('hijos').addEventListener('change', function() {
        var hijosValue = this.value;
        var hijosMenoresGroup = document.getElementById('hijos_menores_group');

        // Si se selecciona un número mayor a 0, mostramos el segundo input
        if (hijosValue > 0) {
            hijosMenoresGroup.style.display = 'block';
        } else {
            hijosMenoresGroup.style.display = 'none';
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función para calcular la edad en base a la fecha de cumpleaños
        document.getElementById('fecha_cumpleanos').addEventListener('change', function() {
            const fechaNacimiento = new Date(this.value);
            const hoy = new Date();
            let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
            const mes = hoy.getMonth() - fechaNacimiento.getMonth();
            if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                edad--;
            }
            document.getElementById('edad').value = edad;
        });

        // Cargar las comunas basadas en el municipio seleccionado
        document.getElementById('municipio').addEventListener('change', function() {
            const municipio = this.value;
            // Aquí deberías hacer una llamada AJAX para cargar las comunas basadas en el municipio
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#documento_identidad').on('blur', function() {
            const documento_identidad = $(this).val();

            // Hacer la solicitud AJAX
            $.ajax({
                url: '<?php echo site_url('lottery/validate_document'); ?>',
                type: 'POST',
                data: {
                    documento_identidad: documento_identidad
                },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        // Mostrar mensaje de error
                        alert(response.message);

                        // Limpiar el campo documento_identidad
                        $('#documento_identidad').val('');
                    }
                },
                error: function() {
                    alert('Error en la validación del documento.');
                }
            });
        });
    });
</script>


</body>

</html>