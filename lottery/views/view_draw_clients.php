<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .highlight-winner {
        background-color: yellow;
        /* Puedes cambiar el color de fondo según tu preferencia */
        font-weight: bold;
        /* Puedes hacer que el texto sea más notorio */
    }


    .winner-heading {
        text-align: center;
        font-size: 1.5em;
        margin: 20px 0;
    }
</style>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/index_module'); ?>">Menu</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/list_draws'); ?>">Sorteos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Participantes de sorteo</li>
                    </ol>
                </nav>
                <div class="modal fade" id="winnerModal" tabindex="-1" role="dialog" aria-labelledby="winnerModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="winnerModalLabel">Detalles del Ganador</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <iframe id="winner-iframe" src="" style="width: 100%; height: 600px;" frameborder="0"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <center> <a href="<?= admin_url('lottery/generate_winner/' . $draw['id']) ?>" class="btn btn-success">Generar Ganador</a> </center>
                        <a href="<?php echo site_url('lottery/create_invoice'); ?>" class="btn btn-primary mb-3">Crear factura</a>
                        <br><br>

                        <?php
                        // Almacenar clientes únicos
                        $unique_clients = [];
                        $total_valor_facturas = 0;
                        $total_boletas_individuales = 0;

                        foreach ($clients as $client) {
                            $unique_clients[$client['documento_identidad']] = $client;
                            // Sumar el valor total de las facturas
                            $total_valor_facturas += $client['valor_total_facturas'];

                            // Calcular boletas por cliente, redondeando hacia abajo
                            $boletas_cliente = floor($client['valor_total_facturas'] / $draw['valor_por_factura']);
                            $total_boletas_individuales += $boletas_cliente;
                        }
                        $total_boletas = $total_boletas_individuales;

                        // Encontrar el nombre del ganador
                        $winner_name = 'N/A';
                        if (!empty($draw['ganador_cliente_id'])) {
                            foreach ($clients as $client) {
                                if ($client['id'] == $draw['ganador_cliente_id']) {
                                    $winner_name = $client['nombre'] . ' ' . $client['apellido'];
                                    break;
                                }
                            }
                        }
                        ?>
                        <?php if ($winner_name !== 'N/A'): ?>
                            <div class="winner-heading">
                                <span><strong>¡Ganador del sorteo! </strong></span><br>
                                <a href="javascript:void(0)" class="view-winner" data-id="<?php echo $draw['ganador_cliente_id']; ?>">
                                    <?php echo $winner_name; ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-6 tw-gap-2">
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Nombre
                                </span>
                                <span class="text-dark tw-truncate sm:tw-text-clip"><?php print_r($draw['nombre']); ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Valor por factura
                                </span>
                                <span class="text-danger tw-truncate sm:tw-text-clip"> <?php print_r(number_format($draw['valor_por_factura'], 0, '', '.')); ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Total participantes
                                </span>
                                <span class="text-success tw-truncate sm:tw-text-clip"><?php print_r(count($unique_clients)); ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Monto total
                                </span>
                                <span class="text-success tw-truncate sm:tw-text-clip"><?php print_r($total_valor_facturas); ?></span>
                            </div>
                            <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    Total boletas generadas
                                </span>
                                <span class="text-primary tw-truncate sm:tw-text-clip"><?php print_r($total_boletas); ?></p></span>
                            </div>
                        </div><br>

                        <?php if ($this->session->flashdata('message')): ?>
                            <?php
                            $message = $this->session->flashdata('message');
                            $alert_type = ($message['type'] == 'success') ? 'alert-success' : 'alert-danger';
                            ?>
                            <div class="alert <?php echo $alert_type; ?>">
                                <?php echo $message['content']; ?>
                            </div>
                        <?php endif; ?>
                        <?php
                        // Obtener locales únicos
                        $locales_unicos = array_unique(array_column($clients, 'local_nombre'));
                        ?>

                        <!-- Encabezado del nombre del ganador -->



                        <!-- <div class="form-group">
                            <select id="num-rows" class="form-control" style="width: auto; display: inline-block;">
                                <option value="all">Todo</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>


                            </select>
                            <select id="local-filter" class="form-control" style="width: auto; display: inline-block;">
                                <option value="">Todos los locales</option>
                                <?php foreach ($locales_unicos as $local): ?>
                                    <option value="<?php echo htmlspecialchars($local); ?>"><?php echo htmlspecialchars($local); ?></option>
                                <?php endforeach; ?>
                            </select>

                            <button id="export-btn" class="btn btn-secondary">Exportar a CSV</button>
                            <button id="refresh-btn" class="btn btn-secondary"><i class="fa fa-refresh"></i></button>
                        </div> -->
                        <div class="form-group">
                            <input type="text" id="search" class="form-control" placeholder="Buscar participantes">
                        </div>
                        <table class="table dt-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Barrio</th>
                                    <th>Ocupación</th>
                                    <th>Tipo de Cliente</th>
                                    <th>Local</th>
                                    <th>Total Facturas</th>
                                    <th>Boletas Generadas</th>
                                </tr>
                            </thead>
                            <tbody id="participantes-tbody">
                                <?php foreach ($clients as $client) : ?>
                                    <?php
                                    // Verifica si el cliente es el ganador del sorteo
                                    $is_winner = isset($client['id']) && isset($draw['ganador_cliente_id']) && $client['id'] == $draw['ganador_cliente_id'];

                                    // Mostrar solo si ha generado al menos una boleta
                                    if (isset($client['boletas_generadas']) && $client['boletas_generadas'] > 0):
                                    ?>
                                        <tr style="<?php echo $is_winner ? 'background-color: yellow; font-weight: bold;' : ''; ?>">
                                            <td><?php echo isset($client['nombre']) ? $client['nombre'] : 'N/A'; ?></td>
                                            <td><?php echo isset($client['apellido']) ? $client['apellido'] : 'N/A'; ?></td>
                                            <td><?php echo isset($client['barrio']) ? $client['barrio'] : 'N/A'; ?></td>
                                            <td><?php echo isset($client['ocupacion']) ? $client['ocupacion'] : 'N/A'; ?></td>
                                            <td><?php echo isset($client['tipo_cliente']) ? $client['tipo_cliente'] : 'N/A'; ?></td>
                                            <td><?php echo isset($client['local_nombre']) ? $client['local_nombre'] : 'N/A'; ?></td>
                                            <td><?php echo isset($client['valor_total_facturas']) ? number_format($client['valor_total_facturas'], 0, '', '.') : 'N/A'; ?></td>
                                            <td><?php echo isset($client['boletas_generadas']) ? $client['boletas_generadas'] : 'N/A'; ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const tableRows = document.querySelectorAll('#participantes-tbody tr');

        searchInput.addEventListener('keyup', function() {
            const searchTerm = searchInput.value.toLowerCase();

            tableRows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                let rowText = '';
                for (let i = 0; i < cells.length; i++) {
                    rowText += cells[i].textContent.toLowerCase() + ' ';
                }

                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const numRowsSelect = document.getElementById('num-rows');
        const tableRows = document.querySelectorAll('#participantes-tbody tr');

        // Función para filtrar filas
        function filterRows() {
            const searchTerm = searchInput.value.toLowerCase();
            let visibleRows = 0;
            const numRows = numRowsSelect.value === 'all' ? tableRows.length : parseInt(numRowsSelect.value);

            tableRows.forEach((row, index) => {
                const cells = row.getElementsByTagName('td');
                let rowText = '';
                for (let i = 0; i < cells.length; i++) {
                    rowText += cells[i].textContent.toLowerCase() + ' ';
                }

                if (rowText.includes(searchTerm) && visibleRows < numRows) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Event listener para buscar
        searchInput.addEventListener('keyup', filterRows);

        // Event listener para cambiar número de filas a mostrar
        numRowsSelect.addEventListener('change', filterRows);

        // Filtrar inicialmente para mostrar las primeras filas por defecto
        filterRows();
    });
</script>
<script>
    document.getElementById('export-btn').addEventListener('click', function() {
        let csvContent = "data:text/csv;charset=utf-8,";
        const rows = document.querySelectorAll('#participantes-tbody tr');

        // Agregar encabezados
        csvContent += "Nombre,Apellido,Barrio,Ocupación,Tipo de Cliente,Local,Valor factura,Numero de boletas\n";

        // Iterar sobre las filas visibles y agregar los datos al CSV
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const cells = row.querySelectorAll('td');
                const rowData = [];
                cells.forEach(cell => rowData.push(cell.textContent));
                csvContent += rowData.join(",") + "\n";
            }
        });

        // Crear un enlace de descarga
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "informe_participantes.csv");
        document.body.appendChild(link);

        // Hacer clic automáticamente para iniciar la descarga
        link.click();
    });
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const numRowsSelect = document.getElementById('num-rows');
        const boletasRange = document.getElementById('boletas-range');
        const localFilter = document.getElementById('local-filter');
        const tableRows = document.querySelectorAll('#participantes-tbody tr');

        // Función para filtrar filas
        function filterRows() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedLocal = localFilter.value.toLowerCase();
            const rangeValues = boletasRange.value.split('-');
            const minBoletas = parseInt(rangeValues[0]) || 0;
            const maxBoletas = parseInt(rangeValues[1]) || Infinity; // Sin límite superior
            let visibleRows = 0;
            const numRows = numRowsSelect.value === 'all' ? tableRows.length : parseInt(numRowsSelect.value);

            tableRows.forEach((row) => {
                const cells = row.getElementsByTagName('td');
                let rowText = '';
                let rowLocal = '';
                let rowBoletas = 0;
                let isWinner = false;

                for (let i = 0; i < cells.length; i++) {
                    rowText += cells[i].textContent.toLowerCase() + ' ';
                    if (i === 5) { // La columna 6 es donde está el local
                        rowLocal = cells[i].textContent.toLowerCase();
                    }
                    if (i === 7) { // La columna 8 es donde está el número de boletas
                        rowBoletas = parseInt(cells[i].textContent) || 0;
                    }
                    if (i === 8 && cells[i].textContent.toLowerCase() === 'ganador') { // Suponiendo que la columna 9 indica si es ganador
                        isWinner = true;
                    }
                }

                // Verificar si la fila cumple con los filtros
                const boletasCondition = rowBoletas >= minBoletas && rowBoletas <= maxBoletas;

                if (
                    rowText.includes(searchTerm) &&
                    (selectedLocal === '' || rowLocal === selectedLocal) &&
                    boletasCondition &&
                    visibleRows < numRows
                ) {
                    row.style.display = '';
                    visibleRows++;

                    // Resaltar al ganador
                    if (isWinner) {
                        row.classList.add('highlight-winner');
                    } else {
                        row.classList.remove('highlight-winner'); // Remover en caso de que no lo sea
                    }
                } else {
                    row.style.display = 'none';
                    row.classList.remove('highlight-winner'); // Asegurarse de que se remueva si no es visible
                }
            });
        }

        // Event listeners para los filtros
        searchInput.addEventListener('keyup', filterRows);
        numRowsSelect.addEventListener('change', filterRows);
        localFilter.addEventListener('change', filterRows);
        boletasRange.addEventListener('change', filterRows);

        // Filtrar inicialmente para mostrar las primeras filas por defecto
        filterRows();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleccionar todos los enlaces de ganadores
        const winnerLinks = document.querySelectorAll('.view-winner');

        winnerLinks.forEach(link => {
            link.addEventListener('click', function() {
                const winnerId = this.getAttribute('data-id');

                // Cargar el iframe con la URL de los detalles del cliente
                const iframe = document.getElementById('winner-iframe');
                iframe.src = "<?php echo site_url('lottery/view_winner_details/'); ?>" + winnerId;

                // Mostrar el modal
                $('#winnerModal').modal('show');
            });
        });
    });
    document.getElementById('refresh-btn').addEventListener('click', function() {
        // Restablecer el campo de búsqueda
        document.getElementById('search').value = '';

        // Restablecer el selector de número de filas
        document.getElementById('num-rows').value = 'all';

        // Mostrar todas las filas de la tabla
        const tableRows = document.querySelectorAll('#participantes-tbody tr');
        tableRows.forEach(row => {
            row.style.display = '';
        });
    });
</script>
<?php init_tail(); ?>