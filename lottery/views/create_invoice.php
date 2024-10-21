<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .short-input {
        width: 70%;

    }
</style>

<div id="wrapper">
    <div class="content">
        <div id="createClientModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="createClientModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="container">
                            <h4 class="no-margin mb-4"><strong>Agregar Nuevo Cliente</strong></h4>
                            <br>

                            <?php echo form_open('lottery/insert_client_modal', ['id' => 'insert-client-form']); ?>
                            <div>
                                <!-- Primera columna -->
                                <div>
                                    <div>
                                        <input type="hidden" name="asesor" value="<?php echo $this->session->userdata('user_id'); ?>">

                                        <div class="form-group">
                                            <label for="tipo_cliente">Tipo de Cliente*</label>
                                            <select id="tipo_cliente" name="tipo_cliente" class="form-control short-input" required>
                                                <option value="cliente">Cliente</option>
                                                <option value="empleado">Empleado</option>
                                                <option value="local">Local</option>
                                                <option value="proveedor">Proveedor</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="tipo_persona">Tipo de Persona*</label>
                                            <select id="tipo_persona" name="tipo_persona" class="form-control short-input" required>
                                                <option value="natural">Natural</option>
                                                <option value="juridica">Jurídica</option>
                                            </select>
                                        </div>

                                        <label for="nombre">Nombres*</label>
                                        <input type="text" id="nombre" name="nombre" class="form-control short-input" required>

                                        <label for="apellido">Apellidos*</label>
                                        <input type="text" id="apellido" name="apellido" class="form-control short-input" required>

                                        <div class="form-group">
                                            <label for="tipo_documento">Tipo de Documento*</label>
                                            <select name="tipo_documento" name="tipo_documento" class="form-control short-input" required>>
                                                <?php foreach ($tipos_documentos as $tipo): ?>
                                                    <option value="<?php echo htmlspecialchars($tipo['nombre']); ?>">
                                                        <?php echo htmlspecialchars($tipo['nombre']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <label for="documento_identidad">Documento de Identidad*</label>
                                        <input type="number" id="documento_identidad" name="documento_identidad" class="form-control short-input" required>


                                        <label for="sexo">Sexo*</label>
                                        <select id="sexo" name="sexo" class="form-control short-input" required>
                                            <option value="M">Masculino</option>
                                            <option value="F">Femenino</option>
                                            <option value="O">Otro</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Segunda columna -->
                                <div>
                                    <div class="form-group">
                                        <label for="fecha_cumpleaños">Fecha de nacimiento*</label>
                                        <input type="date" id="fecha_cumpleaños" name="fecha_cumpleaños" class="form-control short-input" required>

                                        <label for="edad">Edad*</label>
                                        <input type="number" id="edad" name="edad" class="form-control short-input" readonly>

                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control short-input">

                                        <label for="telefono">Teléfono*</label>
                                        <input type="text" id="telefono" name="telefono" class="form-control short-input" required>
                                    </div>
                                </div>

                                <!-- Tercera columna -->
                                <div>
                                    <div class="form-group">
                                        <label for="direccion_residencia">Dirección de Residencia*</label>
                                        <input type="text" id="direccion_residencia" name="direccion_residencia" class="form-control short-input" required>

                                        <label for="municipio">Municipio</label>
                                        <input type="text" id="municipio" name="municipio" class="form-control short-input">



                                        <label for="barrio">Barrio</label>
                                        <input type="text" id="barrio" name="barrio" class="form-control short-input">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="estado_civil">Estado Civil*</label>
                                    <select id="estado_civil" name="estado_civil" class="form-control short-input" required>
                                        <option value="soltero">Soltero</option>
                                        <option value="casado">Casado</option>
                                        <option value="union_libre">Union Libre</option>
                                        <option value="otro">Otro</option>
                                    </select>

                                    <div class="form-group">
                                        <label for="discapacidad">Discapacidad*</label>
                                        <select id="discapacidad" name="discapacidad" class="form-control short-input" required>
                                            <option value="no">No</option>
                                            <option value="visual">Visual</option>
                                            <option value="fisica">Física</option>
                                            <option value="cognitiva">Cognitiva</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="tiene_mascota">¿Tiene Mascota?* </label>
                                        <select id="tiene_mascota" name="tiene_mascota" class="form-control short-input" required>
                                            <option value="no">No</option>

                                            <?php
                                            // Iterar sobre el array de mascotas y agregar una opción para cada una
                                            foreach ($mascotas as $mascota) {
                                                // Acceder al 'id' y 'especie' de cada mascota
                                                $especie_mascota = htmlspecialchars($mascota['especie']); // Evitar XSS

                                                // Imprimir la opción en el select
                                                echo "<option value=\"$especie_mascota\">$especie_mascota</option>";
                                            }
                                            ?>

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="tiene_vehiculo">¿Tiene Vehículo?*</label>
                                        <select id="tiene_vehiculo" name="tiene_vehiculo" class="form-control short-input" required>
                                            <option value="no">No</option>
                                            <option value="carro">Carro</option>
                                            <option value="moto">Moto</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="hijos">Número de Hijos*</label>
                                        <select id="hijos" name="hijos" class="form-control short-input" required>
                                            <?php for ($i = 0; $i <= 3; $i++): ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>

                                    <!-- Segundo input que solo se mostrará si se selecciona un valor mayor a 0 -->
                                    <div class="form-group" id="hijos_menores_group" style="display: none;">
                                        <label for="hijos_menores">¿Tiene hijos menores de 13 años?</label>
                                        <select id="hijos_menores" name="hijos_menores" class="form-control short-input">
                                            <option value="">Seleccione una opción</option>
                                            <option value="si">Sí</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>

                                    <label for="nivel_academico">Nivel Académico*</label>
                                    <select id="nivel_academico" name="nivel_academico" class="form-control short-input" required>
                                        <option value="ninguno">Ninguno</option>
                                        <option value="primaria">Primaria</option>
                                        <option value="bachiller">Bachiller</option>
                                        <option value="pregrado">Pregrado</option>
                                        <option value="posgrado">Posgrado</option>
                                    </select>
                                </div>



                                <div class="form-group">
                                    <label for="ocupacion">Ocupación*</label>
                                    <select id="ocupacion" name="ocupacion" class="form-control short-input" required>
                                        <option value="empleado">Empleado</option>
                                        <option value="independiente">Independiente</option>
                                        <option value="ama_de_casa">Ama de Casa</option>
                                        <option value="pensionado">Pensionado</option>
                                    </select>

                                    <label>
                                        <input type="hidden" name="datos_personales" value="N">
                                        <input type="checkbox" id="datos_personales" name="datos_personales" value="S" required>
                                        Aceptar Términos, Condiciones y Datos Personales.*
                                    </label>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg">Guardar Cliente</button>
                                </div>
                            </div>


                            <!-- Columna inferior con estado civil, hijos, nivel académico -->







                            <?php echo form_close(); ?>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="closeModalButton" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/index_module'); ?>">Menu</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/list_invoices'); ?>">Facturas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear factura</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <form id="form_invoice" action="<?php echo admin_url('lottery/insert_invoice'); ?>" method="post">
                            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                            <?php if ($this->session->flashdata('message')): ?>
                                <?php $message = $this->session->flashdata('message'); ?>
                                <div class="alert alert-<?php echo $message['type']; ?>">
                                    <?php echo $message['content']; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Campo de búsqueda -->
                            <div class="form-group">
                                <label>Buscar Cliente por Documento de Identidad</label>
                                <input type="number" id="client_search" class="form-control" placeholder="Buscar por documento de identidad" />
                            </div>

                            <div class="form-group">
                                <label for="cliente_id">Cliente:</label>
                                <select name="cliente_id" id="cliente_id" class="form-control" required>
                                    <option value=""></option>
                                </select>
                            </div>


                            <!-- Selección de local comercial -->
                            <div class="form-group">
                                <label for="local_id">Local Comercial:</label>
                                <!-- Campo de búsqueda -->
                                <input type="text" id="searchLocal" placeholder="Buscar local..." class="form-control">

                                <!-- Select con opciones -->
                                <select name="local_id" id="local_id" class="form-control" multiple required>
                                    <option value="">Seleccione un local</option>
                                    <?php foreach ($locales as $local): ?>
                                        <!-- Solo mostrar los locales donde 'activo' sea 1 -->
                                        <?php if ($local['activo'] == 1): ?>
                                            <option value="<?php echo $local['id']; ?>">
                                                <?php echo $local['nombre']; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>



                            <!-- Selección de sorteo -->
                            <!-- Selección de sorteo -->
                            <div class="form-group">
                                <label for="sorteo_id">Sorteo:</label>
                                <select name="sorteo_id" id="sorteo_id" class="form-control" required>
                                    <option value="">Seleccione un sorteo</option>
                                    <?php foreach ($sorteos as $sorteo): ?>
                                        <option value="<?php echo $sorteo['id']; ?>" data-fecha-finalizacion="<?php echo $sorteo['fecha_finalizacion']; ?>">
                                            <?php echo $sorteo['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                            <!-- Número de factura -->
                            <div class="form-group">
                                <label for="numero_factura">Número de Factura:</label>
                                <input type="text" name="numero_factura" id="numero_factura" class="form-control" required>
                            </div>

                            <!-- Valor -->
                            <div class="form-group">
                                <label for="valor">Valor:</label>
                                <input type="number" name="valor" id="valor" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Crear Factura</button>
                        </form>

                        <br>
                        <div id="no_clients_message" class="alert alert-warning" style="display: none;">
                            <p><strong>Advertencia:</strong> El cliente con el documento de identidad ingresado no está registrado.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
    document.addEventListener("DOMContentLoaded", function() {
        // Obtén el formulario por su id
        const form = document.getElementById('form_invoice'); // Cambia 'mi_formulario' por el id de tu formulario
        const sorteoSelect = document.getElementById('sorteo_id');

        form.addEventListener('submit', function(event) {
            const selectedOption = sorteoSelect.options[sorteoSelect.selectedIndex];
            const fechaFinalizacion = selectedOption.getAttribute('data-fecha-finalizacion');

            if (fechaFinalizacion) {
                // Convertir la fecha de finalización a un objeto Date
                const fechaFinal = new Date(fechaFinalizacion);
                const fechaActual = new Date();

                // Compara la fecha actual con la fecha de finalización
                if (fechaFinal < fechaActual) {
                    // Muestra una alerta
                    alert('El sorteo seleccionado ya ha finalizado.');

                    // Evita que se envíe el formulario
                    event.preventDefault();
                }
            }
        });
    });
</script>

<script>
    // Función para ordenar las opciones alfabéticamente
    function sortSelectOptions(selectElement) {
        let optionsArray = Array.from(selectElement.options);
        let firstOption = optionsArray.shift();
        optionsArray.sort((a, b) => a.text.localeCompare(b.text));
        optionsArray.unshift(firstOption);
        selectElement.innerHTML = '';
        optionsArray.forEach(option => selectElement.add(option));
    }

    // Filtrar opciones del select con el campo de búsqueda
    document.getElementById('searchLocal').addEventListener('input', function() {
        let searchValue = this.value.toLowerCase();
        let select = document.getElementById('local_id');
        let options = select.getElementsByTagName('option');

        for (let i = 0; i < options.length; i++) {
            let optionText = options[i].textContent.toLowerCase();
            if (optionText.includes(searchValue)) {
                options[i].style.display = '';
            } else {
                options[i].style.display = 'none';
            }
        }
    });

    // Ejecutar la ordenación al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        let select = document.getElementById('local_id');
        sortSelectOptions(select);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const numeroFacturaInput = document.getElementById('numero_factura');
        const sorteoSelect = document.getElementById('sorteo_id');

        // Función para verificar si la fecha de finalización del sorteo ha pasado
        function verificarFechaSorteo() {
            const selectedOption = sorteoSelect.options[sorteoSelect.selectedIndex];
            const fechaFinalizacion = selectedOption.getAttribute('data-fecha-finalizacion');

            if (fechaFinalizacion) {
                const fechaSorteo = new Date(fechaFinalizacion);
                const fechaActual = new Date();

                // Comprobar si la fecha de finalización ya ha pasado
                if (fechaSorteo < fechaActual) {
                    alert('El sorteo seleccionado ya ha finalizado. No puede crear una factura para este sorteo.');
                    return false;
                }
            }
            return true;
        }

        // Verificar si el número de factura ya existe antes de enviar el formulario
        form.addEventListener('submit', function(event) {
            const numeroFactura = numeroFacturaInput.value;

            // Verificar la fecha del sorteo antes de continuar
            if (!verificarFechaSorteo()) {
                event.preventDefault(); // Detener el envío del formulario
                return;
            }

            // Realizar una petición AJAX para comprobar si el número de factura ya existe
            $.ajax({
                url: '<?php echo site_url('lottery/check_invoice'); ?>',
                type: 'POST',
                data: {
                    numero_factura: numeroFactura
                },
                dataType: 'json',
                async: false, // Para detener el envío hasta obtener la respuesta
                success: function(response) {
                    if (response.exists) {
                        // Si el número de factura existe, mostrar una alerta y detener el envío
                        alert('El número de factura ya existe. Por favor, ingrese un número diferente.');
                        event.preventDefault(); // Detener el envío del formulario
                    }
                }
            });
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Captura el evento de ocultar el modal y recarga la página
        $('#createClientModal').on('hidden.bs.modal', function() {
            location.reload(); // Recarga la página
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función para calcular la edad en base a la fecha de cumpleaños
        document.getElementById('fecha_cumpleaños').addEventListener('change', function() {
            const fechaNacimiento = new Date(this.value);
            const hoy = new Date();
            let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
            const mes = hoy.getMonth() - fechaNacimiento.getMonth();
            if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                edad--;
            }
            document.getElementById('edad').value = edad;
        });


        document.getElementById('municipio').addEventListener('change', function() {
            const municipio = this.value;

        });
    });
</script>

<script>
    document.getElementById('client_search').addEventListener('change', function() {
        const documentoIdentidad = this.value;

        // Hacer la llamada AJAX para buscar el cliente
        fetch(`<?php echo site_url('lottery/search_client'); ?>?documento_identidad=${documentoIdentidad}`)
            .then(response => response.json())
            .then(client => {
                const clienteSelect = document.getElementById('cliente_id');
                clienteSelect.innerHTML = ''; // Limpiar el select

                if (client) {
                    const option = document.createElement('option');
                    option.value = client.id;
                    option.text = `${client.nombre} ${client.apellido}`;
                    clienteSelect.add(option);
                } else {
                    const noClientsMessage = document.getElementById('no_clients_message');
                    $('#createClientModal').modal('show');
                    noClientsMessage.style.display = 'block';
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>
<?php init_tail(); ?>
</body>

</html>