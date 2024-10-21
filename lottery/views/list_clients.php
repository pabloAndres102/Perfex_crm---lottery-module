<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .pagination-links {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
    }

    .pagination-info {
        font-size: 14px;
        color: #333;
    }

    .pagination-controls {
        flex-grow: 1;
        text-align: right;
    }

    .pagination a {
        margin: 0 5px;
        padding: 5px 10px;
        text-decoration: none;
        border: 1px solid #ccc;
        border-radius: 3px;
        color: #007bff;
    }

    .pagination a:hover {
        background-color: #f1f1f1;
    }
</style>
<style>
    .filter-form {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .filter-form .form-group {
        margin-bottom: 15px;
    }

    .filter-form label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    .filter-form input,
    .filter-form select {
        width: 100%;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/index_module'); ?>">Menu</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Clientes</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><strong>Lista de Clientes</strong></h4><br>
                        <!-- <h5>Total de clientes: <?php print_r($clientes_count) ?></h5> -->


                        <center><mark><small style="color:darkgoldenrod;">Métricas generales</small></mark></center><br>
                        <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-6 tw-gap-2">
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Clientes
                                </span>
                                <span class="text-dark tw-truncate sm:tw-text-clip"><?php echo isset($total_clientes) ? $total_clientes : '0'; ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Mujeres
                                </span>
                                <span class="text-danger tw-truncate sm:tw-text-clip"><?php echo isset($numero_mujeres) ? $numero_mujeres : '0'; ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Hombres
                                </span>
                                <span class="text-success tw-truncate sm:tw-text-clip"><?php echo isset($numero_hombres) ? $numero_hombres : '0'; ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">Edad promedio</span>
                                <span class="text-danger tw-truncate sm:tw-text-clip"><?php echo isset($edad_promedio) && is_numeric($edad_promedio) ? round($edad_promedio) : 'N/A'; ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Ocupación líder
                                </span>
                                <span class="text-primary tw-truncate sm:tw-text-clip"><?php echo isset($ocupacion_mas_frecuente) && !empty($ocupacion_mas_frecuente) ? ucfirst($ocupacion_mas_frecuente) : 'N/A'; ?></span>
                            </div>
                        </div><br>

                        <div class="form-group">

                            <button id="export-btn" class="btn btn-secondary">Exportar a CSV</button>

                            <a href="<?php echo site_url('lottery/index'); ?>" class="btn btn-primary mb-3">Crear cliente</a>

                            <button id="filter-btn" class="btn btn-secondary" style="float: right; margin-left: 10px;">
                                <i class="fa fa-filter"></i> Filtros
                            </button>

                            <a class="btn btn-secondary mb-3" href="<?php echo site_url('lottery/list_clients'); ?>" style="float: right; margin-right: 0,1px;">
                                <i class="fa fa-refresh"></i>
                            </a>

                            <?php echo form_open_multipart('lottery/importar_clientes_csv', ['id' => 'import_clients']); ?>
                            <!-- <input type="file" name="file" required>
                            <input type="submit" value="Importar"> -->
                            <?php echo form_close(); ?>


                        </div>
                        <!-- Mostrar mensaje flash -->
                        <?php if ($this->session->flashdata('message')): ?>
                            <?php
                            $message = $this->session->flashdata('message');
                            $alert_type = ($message['type'] == 'success') ? 'alert-success' : 'alert-danger';
                            ?>
                            <div class="alert <?php echo $alert_type; ?>">
                                <?php echo $message['content']; ?>
                            </div>
                        <?php endif; ?>

                        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="filterModalLabel">Filtros</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="GET" action="<?php echo site_url('lottery/list_clients'); ?>" class="filter-form">
                                            <!-- Campo de búsqueda -->
                                            <label for="search">Número de identificacion:</label>
                                            <input type="text" name="search" placeholder="cc" value="<?php echo htmlspecialchars($variable ?? '', ENT_QUOTES, 'UTF-8'); ?>" />

                                            <!-- Inputs de fecha -->
                                            <label for="fecha_inicio">Fecha Inicio:</label>
                                            <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-group" style="width: auto; display: inline-block;" value="<?php echo isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : ''; ?>">

                                            <label for="fecha_fin">Fecha Fin:</label>
                                            <input type="date" id="fecha_fin" name="fecha_fin" class="form-group" style="width: auto; display: inline-block;" value="<?php echo isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : ''; ?>">

                                            <!-- Campo select para la ocupación -->
                                            <label for="ocupacion">Ocupación:</label>
                                            <select id="ocupacion" name="ocupacion" class="form-group" style="width: auto; display: inline-block;">
                                                <option value="">Selecciona ocupación</option>
                                                <option value="empleado" <?php echo isset($_GET['ocupacion']) && $_GET['ocupacion'] == 'empleado' ? 'selected' : ''; ?>>Empleado</option>
                                                <option value="independiente" <?php echo isset($_GET['ocupacion']) && $_GET['ocupacion'] == 'independiente' ? 'selected' : ''; ?>>Independiente</option>
                                                <option value="ama de casa" <?php echo isset($_GET['ocupacion']) && $_GET['ocupacion'] == 'ama_de_casa' ? 'selected' : ''; ?>>Ama de Casa</option>
                                                <option value="pensionado" <?php echo isset($_GET['ocupacion']) && $_GET['ocupacion'] == 'pensionado' ? 'selected' : ''; ?>>Pensionado</option>
                                            </select>
                                            <label for="num_filas">Filas a mostrar:</label>
                                            <select id="num_filas" name="num_filas" class="form-control" style="width: auto; display: inline-block;">
                                                <option value="10" <?php echo isset($_GET['num_filas']) && $_GET['num_filas'] == '10' ? 'selected' : ''; ?>>10</option>
                                                <option value="25" <?php echo isset($_GET['num_filas']) && $_GET['num_filas'] == '25' ? 'selected' : ''; ?>>25</option>
                                                <option value="50" <?php echo isset($_GET['num_filas']) && $_GET['num_filas'] == '50' ? 'selected' : ''; ?>>50</option>
                                                <option value="100" <?php echo isset($_GET['num_filas']) && $_GET['num_filas'] == '100' ? 'selected' : ''; ?>>100</option>
                                                <option value="custom" id="custom-num-filas">Personalizado</option>
                                            </select>
                                            <input type="number" id="custom-num-filas-input" name="num_filas" class="form-control" style="width: auto; display: none; margin-left: 10px;" placeholder="Escribe un número" disabled />
                                            <!-- Botones -->
                                            <a class="btn btn-secondary mb-3" href="<?php echo site_url('lottery/list_clients'); ?>"><i class="fa fa-refresh"></i></a>
                                            <button type="submit" class="btn btn-primary mb-3">Buscar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Botón para crear nuevo cliente -->


                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Asesor</th>
                                    <th>Cliente</th>
                                    <th>Documento de Identidad</th>
                                    <th>Sexo</th>
                                    <th>Edad</th>
                                    <th>Email</th>
                                    <th>Telefono</th>
                                    <th>Municipio</th>
                                    <th>Barrio</th>
                                    <th>Estado Civil</th>
                                    <th>Hijos</th>
                                    <th>Datos personales</th>
                                    <th>Ocupación</th>
                                    <th>Fecha actualizacion</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="clientes-tbody">
                                <?php if (!empty($clientes)): ?>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <tr>
                                            <td><?php echo $cliente['asesor']; ?></td>
                                            <td><?php print_r($cliente['nombre'] . ' ' . $cliente['apellido']); ?></td>
                                            <td><?php echo $cliente['documento_identidad']; ?></td>
                                            <td><?php echo $cliente['sexo']; ?></td>
                                            <td><?php echo $cliente['edad']; ?></td>
                                            <td><?php echo $cliente['email']; ?></td>
                                            <td><?php echo $cliente['telefono']; ?></td>
                                            <td><?php echo $cliente['municipio']; ?></td>
                                            <td><?php echo $cliente['barrio']; ?></td>
                                            <td><?php echo $cliente['estado_civil']; ?></td>
                                            <td><?php echo $cliente['hijos']; ?></td>
                                            <td><?php if ($cliente['datos_personales'] == 'S') {
                                                    echo 'Si';
                                                } else {
                                                    echo 'No';
                                                }
                                                ?></td>
                                            <td><?php echo $cliente['ocupacion']; ?></td>
                                            <td><?php echo $cliente['fecha_actualizacion']; ?></td>
                                            <td>
                                                <!-- Botón para eliminar cliente -->
                                                <a href="<?php echo site_url('lottery/edit_client/' . $cliente['id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                                                <a href="<?php echo site_url('lottery/delete_client/' . $cliente['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">Eliminar</a>
                                                <a href="<?php echo site_url('lottery/list_invoices?cliente_id=' . $cliente['id']); ?>" class="btn btn-success btn-sm">Ver facturas</a>

                                                <a href="<?php echo site_url('lottery/view_client/' . $cliente['id']); ?>" class="btn btn-primary btn-sm">Detalles</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="14" class="text-center">No hay clientes registrados</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <div class="pagination-links">
                            <div class="pagination-info">
                                <!-- Mostrar el rango de resultados -->
                                <span><?php echo $mostrar_desde_hasta; ?></span>
                            </div>
                            <div class="pagination-controls">
                                <!-- Mostrar los enlaces de paginación -->
                                <div class="pagination"><?php echo $pagination; ?></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
    document.getElementById('num_filas').addEventListener('change', function () {
        var customOption = this.value === 'custom';
        var customInput = document.getElementById('custom-num-filas-input');

        // Habilitar o deshabilitar el campo de entrada
        customInput.disabled = !customOption;

        // Si "Personalizado" está seleccionado, ocultar el campo, de lo contrario, mostrarlo
        if (customOption) {
            customInput.style.display = 'inline-block';
        } else {
            customInput.style.display = 'none'; // Mostrarlo cuando no se selecciona "Personalizado"
            customInput.value = ''; // Limpiar el campo cuando no se selecciona "Personalizado"
        }
    });
</script>

<script>
    $(document).ready(function() {
        $('#filter-btn').click(function() {
            $('#filterModal').modal('show');
        });
    });
    
</script>
<script>
    document.getElementById('export-btn').addEventListener('click', function() {
        let csvContent = "data:text/csv;charset=utf-8,";
        const rows = document.querySelectorAll('#clientes-tbody tr');

        // Agregar encabezados, asegurándote de no incluir la columna "Acciones"
        csvContent += "Nombre,Apellido,Documento,Sexo,Edad,Telefono,Ocupación,Municipio,Comuna,Barrio,Estado Civil,Hijos,Datos Personales,Ocupacion\n";

        // Iterar sobre las filas visibles y agregar los datos al CSV
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const cells = row.querySelectorAll('td');
                const rowData = [];

                // Solo agregar datos de las celdas que no sean de las acciones
                cells.forEach((cell, index) => {
                    // Evitar la última columna (que es la de acciones, índice 14 en este caso)
                    if (index < cells.length - 1) {
                        rowData.push(cell.textContent);
                    }
                });

                // Unir los datos de la fila en formato CSV
                csvContent += rowData.join(",") + "\n";
            }
        });

        // Crear un enlace de descarga
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "informe_clientes.csv");
        document.body.appendChild(link);

        // Hacer clic automáticamente para iniciar la descarga
        link.click();
    });
</script>
</body>

</html>