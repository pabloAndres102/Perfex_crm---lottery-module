<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
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
                        <li class="breadcrumb-item active" aria-current="page">Facturas</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><strong>Lista de facturas</strong></h4><br>
                        <center><mark><small style="color:darkgoldenrod;">Metricas generales</small></mark></center><br>
                        <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-6 tw-gap-2">
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Clientes
                                </span>
                                <span class="text-dark tw-truncate sm:tw-text-clip"><?php echo $total_clientes; ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Facturas registradas
                                </span>
                                <span class="text-danger tw-truncate sm:tw-text-clip"><?php echo $total_facturas; ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Compra promedio
                                </span>
                                <span class="text-success tw-truncate sm:tw-text-clip"><?php echo $factura_promedio; ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">Valor total</span>
                                <span class="text-danger tw-truncate sm:tw-text-clip"><?php echo $valor_total; ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Local líder
                                </span>
                                <span class="text-info tw-truncate sm:tw-text-clip"><?php echo $local_lider; ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Sorteo líder
                                </span>
                                <span class="text-danger tw-truncate sm:tw-text-clip"><?php echo $sorteo_lider; ?></span>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <button id="export-btn" class="btn btn-secondary">Exportar a CSV</button>

                            <a href="<?php echo site_url('lottery/create_invoice'); ?>" class="btn btn-primary mb-3">Crear factura</a>
                            <!-- Botón con float a la derecha -->

                            <button id="filter-btn" class="btn btn-secondary" style="float: right; margin-left: 10px;">
                                <i class="fa fa-filter"></i> Filtros
                            </button>

                            <a class="btn btn-secondary mb-3" href="<?php echo site_url('lottery/list_invoices'); ?>" style="float: right; margin-right: 0,1px;">
                                <i class="fa fa-refresh"></i>
                            </a>

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
                                            <form method="GET" action="<?php echo site_url('lottery/list_invoices'); ?>" class="filter-form">
                                                <div class="form-group">
                                                    <label for="search">Número de Factura:</label>
                                                    <input type="text" id="search" name="search" class="form-control" placeholder="# Factura" value="<?php echo htmlspecialchars($variable ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="search_name">Nombre del Cliente:</label>
                                                    <input type="text" id="search_name" name="search_name" class="form-control" placeholder="Nombre cliente" value="<?php echo htmlspecialchars($variable ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="search_local">Nombre del local:</label>
                                                    <input type="text" id="search_local" name="search_local" class="form-control" placeholder="Nombre local" value="<?php echo htmlspecialchars($variable ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="search_sorteo">Nombre del Sorteo:</label>
                                                    <input type="text" id="search_sorteo" name="search_sorteo" class="form-control" placeholder="Nombre sorteo" value="<?php echo htmlspecialchars($variable ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="fecha_inicio">Fecha Inicio:</label>
                                                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : ''; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="fecha_fin">Fecha Fin:</label>
                                                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="<?php echo isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : ''; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="num_filas">Filas a mostrar:</label>
                                                    <select id="num_filas" name="num_filas" class="form-control" style="width: auto; display: inline-block;">
                                                        <option value="10" <?php echo isset($_GET['num_filas']) && $_GET['num_filas'] == '10' ? 'selected' : ''; ?>>10</option>
                                                        <option value="25" <?php echo isset($_GET['num_filas']) && $_GET['num_filas'] == '25' ? 'selected' : ''; ?>>25</option>
                                                        <option value="50" <?php echo isset($_GET['num_filas']) && $_GET['num_filas'] == '50' ? 'selected' : ''; ?>>50</option>
                                                        <option value="100" <?php echo isset($_GET['num_filas']) && $_GET['num_filas'] == '100' ? 'selected' : ''; ?>>100</option>
                                                        <option value="custom" id="custom-num-filas">Personalizado</option>
                                                    </select>
                                                    <input type="number" id="custom-num-filas-input" name="num_filas" class="form-control" style="width: auto; display: none; margin-left: 10px;" placeholder="Escribe un número" disabled />
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                                                </div>
                                            </form>


                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <?php if ($this->session->flashdata('message')): ?>
                            <?php
                            $message = $this->session->flashdata('message');
                            $alert_type = ($message['type'] == 'success') ? 'alert-success' : 'alert-danger';
                            ?>
                            <div class="alert <?php echo $alert_type; ?>">
                                <?php echo $message['content']; ?>
                            </div>
                        <?php endif; ?>


                        <div id="metrics">
                            <!-- Aquí se mostrarán los datos de métricas -->
                        </div>
                        <!-- Campo de búsqueda -->


                        <table class="table table-bordered invoiceTable">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Número de Factura</th>
                                    <th>Local</th>
                                    <th>Valor</th>
                                    <th>Sorteo asociado</th>
                                    <th>Fecha de Emisión</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="invoice-table-body">
                                <?php foreach ($facturas as $factura): ?>
                                    <tr>
                                        <td><?php echo $factura['cliente_nombre'] . ' ' . $factura['apellido']; ?></td>
                                        <td><?php echo $factura['numero_factura']; ?></td>
                                        <td><?php echo $factura['nombre_local']; // Mostrar nombre del local 
                                            ?></td>
                                        <td><?php echo number_format($factura['valor'], 0, '', '.'); ?></td>
                                        <td><?php echo $factura['nombre_sorteo']; // Mostrar nombre del sorteo 
                                            ?></td>
                                        <td><?php echo $factura['fecha_emision']; ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('lottery/edit_invoice/' . $factura['id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                                            <a href="<?php echo admin_url('lottery/delete_invoice/' . $factura['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta factura?');">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="pagination-links">
                            <?php echo $pagination; ?>
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
        const rows = document.querySelectorAll('#invoice-table-body tr');

        // Agregar encabezados, asegurándote de no incluir la columna "Acciones"
        csvContent += "Nombre,Numero de factura,Local,Valor,Sorteo asociado,Fecha de emisión\n";

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
        link.setAttribute("download", "informe_facturas.csv");
        document.body.appendChild(link);

        // Hacer clic automáticamente para iniciar la descarga
        link.click();
    });
</script>
<script>
    function updateMetrics() {
        var numRows = document.getElementById('numRows').value;

        // Hacer una solicitud AJAX al servidor para obtener los datos actualizados
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'lottery/list_invoices?rows=' + numRows, true);

        xhr.onload = function() {
            if (this.status == 200) {
                // Actualizar la tabla y las métricas con los nuevos datos
                var data = JSON.parse(this.responseText);

                // Actualizar la tabla con las facturas
                var invoiceTable = document.getElementById('invoiceTable');
                invoiceTable.innerHTML = ''; // Limpia la tabla
                data.facturas.forEach(function(factura) {
                    var row = '<tr>' +
                        '<td>' + factura.cliente_nombre + ' ' + factura.apellido + '</td>' +
                        '<td>' + factura.nombre_local + '</td>' +
                        '<td>' + factura.nombre_sorteo + '</td>' +
                        '<td>' + factura.valor + '</td>' +
                        '</tr>';
                    invoiceTable.innerHTML += row;
                });

                // Actualizar las métricas
                document.getElementById('total_clientes').textContent = data.total_clientes;
                document.getElementById('total_facturas').textContent = data.total_facturas;
                document.getElementById('factura_promedio').textContent = data.factura_promedio;
                document.getElementById('valor_total').textContent = data.valor_total;
                document.getElementById('local_lider').textContent = data.local_lider;
                document.getElementById('sorteo_lider').textContent = data.sorteo_lider;
            }
        };

        xhr.send();
    }
</script>
</body>

</html>