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
                        <li class="breadcrumb-item active" aria-current="page">Sorteos</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><strong>Listado de Sorteos</strong></h4><br>
                        <div class="form-group">
                            <select id="num-rows" class="form-control" style="width: auto; display: inline-block;">
                                <option value="all">Todo</option>
                                <option value="3">3</option>
                                <option value="6">6</option>
                                <option value="10">10</option>
                            </select>
                            <button id="export-btn" class="btn btn-secondary">Exportar a CSV</button>
                            <button id="refresh-btn" class="btn btn-secondary"><i class="fa fa-refresh"></i></button>
                            <a href="<?php echo site_url('lottery/create_draw'); ?>" class="btn btn-primary">Crear Nuevo Sorteo</a>
                        </div>
                        <div class="form-group text-right">
                            <label for="filter-start-date">Fecha de Inicio:</label>
                            <input type="date" id="filter-start-date" class="form-control" style="display: inline-block; width: auto;">
                            <label for="filter-end-date">Fecha de Finalización:</label>
                            <input type="date" id="filter-end-date" class="form-control" style="display: inline-block; width: auto;">
                        </div>
                        <!-- Formulario de búsqueda -->
                        <div class="form-group">
                            <input type="text" id="search" class="form-control" placeholder="Buscar sorteos">
                        </div>


                        <table class="table table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Valor por Factura</th>
                                    <th>Cantidad participantes</th>
                                    <th>Boletas generadas</th>
                                    <th>Patrocinador</th>
                                    <th>Descripción</th>
                                    <th>Ganador</th>
                                    <th>Fecha de inicio</th>
                                    <th>Fecha de finalización</th>
                                    <th>Foto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="draws-tbody">
                                <?php foreach ($draws as $draw) : ?>
                                    <tr>
                                        <td><?php echo $draw['nombre']; ?></td>
                                        <td><?php echo number_format($draw['valor_por_factura'], 0, '', '.'); ?></td>
                                        <td><?php echo $draw['cantidad_participantes']; ?></td>
                                        <td><?php echo ($draw['boletas_generadas'] == null || $draw['boletas_generadas'] == 0) ? 0 : $draw['boletas_generadas']; ?></td>
                                        <td><?php echo $draw['patrocinadores_nombres']; ?></td>
                                        <td><?php echo $draw['descripcion']; ?></td>
                                        <td id="ganador-<?php echo $draw['id']; ?>" data-ganador-id="<?php echo $draw['ganador_cliente_id']; ?>" data-ganador-nombre="<?php echo $draw['ganador_nombre']; ?>" data-ganador-apellido="<?php echo $draw['ganador_apellido']; ?>"></td>
                                        <td><?php echo $draw['fecha_inicio']; ?></td>
                                        <td><?php echo $draw['fecha_finalizacion']; ?></td>
                                        <td id="foto-<?php echo $draw['id']; ?>" data-foto-url="<?php echo !empty($draw['foto']) ? base_url('uploads/sorteos/' . $draw['foto']) : ''; ?>"></td>
                                        <td>
                                            <a href="<?php echo site_url('lottery/view_draw_clients/' . $draw['id']); ?>" class="btn btn-info btn-sm">Ver sorteo</a>
                                            <a href="<?php echo site_url('lottery/edit_draw/' . $draw['id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <!-- Modal para mostrar los detalles del ganador -->
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

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Campos de fecha para filtrar
    const startDateInput = document.getElementById('filter-start-date');
    const endDateInput = document.getElementById('filter-end-date');
    const tableRows = document.querySelectorAll('#draws-tbody tr');

    // Función de filtrado de fechas
    function filterByDate() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        tableRows.forEach(row => {
            // Obtener la fecha de inicio y finalización de la fila
            const rowStartDate = new Date(row.cells[7].textContent); // Columna de "Fecha de inicio"
            const rowEndDate = new Date(row.cells[8].textContent);   // Columna de "Fecha de finalización"

            // Comparar las fechas para ver si el sorteo cae dentro del rango especificado
            if ((!isNaN(startDate) && rowStartDate < startDate) || (!isNaN(endDate) && rowEndDate > endDate)) {
                row.style.display = 'none';
            } else {
                row.style.display = '';
            }
        });
    }

    // Añadir eventos a los campos de fecha para que filtren al cambiar
    startDateInput.addEventListener('change', filterByDate);
    endDateInput.addEventListener('change', filterByDate);
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const tableRows = document.querySelectorAll('#draws-tbody tr');

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
    document.getElementById('refresh-btn').addEventListener('click', function() {
        // Restablecer el campo de búsqueda
        document.getElementById('search').value = '';

        // Restablecer el selector de número de filas
        document.getElementById('num-rows').value = 'all';

        // Mostrar todas las filas de la tabla
        const tableRows = document.querySelectorAll('#draws-tbody tr');
        tableRows.forEach(row => {
            row.style.display = '';
        });
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
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const numRowsSelect = document.getElementById('num-rows');
        const tableRows = document.querySelectorAll('#draws-tbody tr');

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
        const rows = document.querySelectorAll('#draws-tbody tr');

        // Agregar encabezados, asegurándote de no incluir la columna "Acciones"
        csvContent += "Nombre,Valor por Factura,Cantidad participantes,Boletas generadas,Patrocinador,Descripción,Ganador,Fecha de inicio,Fecha de finalización\n";

        // Iterar sobre las filas visibles y agregar los datos al CSV
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const cells = row.querySelectorAll('td');
                const rowData = [];

                // Solo agregar datos de las celdas que no sean de las acciones
                cells.forEach((cell, index) => {
                    // Evitar la última columna (que es la de acciones, índice 14 en este caso)
                    if (index < cells.length - 2) {
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
        link.setAttribute("download", "informe_sorteos.csv");
        document.body.appendChild(link);

        // Hacer clic automáticamente para iniciar la descarga
        link.click();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejar el enlace del ganador
        document.querySelectorAll('td[id^="ganador-"]').forEach(function(td) {
            var ganadorId = td.getAttribute('data-ganador-id');
            var ganadorNombre = td.getAttribute('data-ganador-nombre');
            var ganadorApellido = td.getAttribute('data-ganador-apellido');

            if (ganadorId && ganadorId > 0) {
                td.innerHTML = `<a href="javascript:void(0)" class="view-winner" data-id="${ganadorId}">${ganadorNombre} ${ganadorApellido}</a>`;
            }
        });

        // Manejar la visualización de la foto
        document.querySelectorAll('td[id^="foto-"]').forEach(function(td) {
            var fotoUrl = td.getAttribute('data-foto-url');

            // Si hay una URL de foto válida, mostrar la imagen, de lo contrario mostrar "Sin foto"
            if (fotoUrl) {
                td.innerHTML = `<img src="${fotoUrl}" alt="Foto del sorteo" style="width: 100px; height: auto;">`;
            } else {
                td.innerHTML = `<span class="text-muted">Sin foto</span>`;
            }
        });
    });
</script>
</body>

</html>