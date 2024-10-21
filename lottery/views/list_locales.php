<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/index_module'); ?>">Menu</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Locales</li>
                    </ol>
                </nav>

                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="mb-4">Lista Locales Comerciales</h4>
                        <div class="form-group">
                            <select id="num-rows" class="form-control" style="width: auto; display: inline-block;">
                                <option value="all">Todo</option>
                                <option value="3">3</option>
                                <option value="6">6</option>
                                <option value="10">10</option>
                            </select>
                            <button id="export-btn" class="btn btn-secondary">Exportar a CSV</button>
                            <!-- <?php echo form_open_multipart('lottery/importar_locales', ['id' => 'importar_locales']); ?>
                                <input type="file" name="userfile" required>
                                <button type="submit" name="submit">Importar</button>
                                <?php echo form_close(); ?> -->
                            <button id="refresh-btn" class="btn btn-secondary"><i class="fa fa-refresh"></i></button>
                            <a href="<?php echo site_url('lottery/create_local'); ?>" class="btn btn-primary mb-3">Agregar Nuevo Local</a>

                        </div>
                        <!-- Mostrar mensaje de éxito o error -->
                        <?php if ($this->session->flashdata('message')): ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('message')['type']; ?>">
                                <?php echo $this->session->flashdata('message')['content']; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Campo de búsqueda -->
                        <div class="form-group mb-3">
                            <input type="text" id="search" class="form-control" placeholder="Buscar locales">
                        </div>


                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Ubicación</th>
                                    <th>Local</th>
                                    <th>Descripción</th>
                                    <th>Telefono</th>
                                    <th>Email</th>
                                    <th>Fotos</th>
                                    <!-- <th>Ficha catastro</th> -->
                                    <th>¿Está Activo?</th>
                                    <th>Categoria</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="locales-tbody">
                                <?php foreach ($locales as $local): ?>
                                    <tr>
                                        <td><?php echo $local['nombre']; ?></td>
                                        <td><?php echo $local['ubicacion']; ?></td>
                                        <td><?php echo $local['local']; ?></td>
                                        <td><?php echo $local['descripcion']; ?></td>
                                        <td><?php echo $local['whatsapp']; ?></td>
                                        <td><?php echo $local['email']; ?></td>
                                        <td>
                                            <?php
                                            // Verificar si el campo fotos no está vacío o null
                                            if (!empty($local['fotos'])) {
                                                // Separar las fotos por comas
                                                $fotos = explode(',', $local['fotos']);

                                                // Recorrer las fotos y renderizarlas
                                                foreach ($fotos as $foto):
                                                    // Generar la URL de la imagen
                                                    $foto_url = base_url('uploads/locales/' . $foto);
                                            ?>
                                                    <img src="<?php echo $foto_url; ?>" alt="Foto del local" style="width: 100px; height: auto; margin-right: 5px;">
                                            <?php
                                                endforeach;
                                            } else {
                                                // Si no hay fotos, mostrar un mensaje o una imagen predeterminada
                                                echo 'Sin imagen';
                                            }
                                            ?>
                                        </td>
                                        <!-- <td>
                                            <?php echo !empty($local['ficha_catastro']) ? $local['ficha_catastro'] : 'Sin ficha'; ?>
                                        </td> -->
                                        <td><?php echo $local['activo'] ? 'Sí' : 'No'; ?></td>
                                        <td>
                                            <?php echo !empty($local['categoria']) ? $local['categoria'] : 'Sin categoría'; ?>
                                        </td>

                                        <td>
                                            <a href="<?php echo site_url('lottery/edit_local/' . $local['id']); ?>" class="btn btn-warning btn-sm">Editar</a>
                                            <?php if ($local['activo']): ?>
                                                <a href="<?php echo site_url('lottery/toggle_local_status/' . $local['id']); ?>" class="btn btn-secondary btn-sm">Desactivar</a>
                                            <?php else: ?>
                                                <a href="<?php echo site_url('lottery/toggle_local_status/' . $local['id']); ?>" class="btn btn-success btn-sm">Activar</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Agregar JavaScript para el filtrado -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const searchInput = document.getElementById('search');
                            const tableRows = document.querySelectorAll('#locales-tbody tr');

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
                            const tableRows = document.querySelectorAll('#locales-tbody tr');
                            tableRows.forEach(row => {
                                row.style.display = '';
                            });
                        });
                    </script>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const searchInput = document.getElementById('search');
                            const numRowsSelect = document.getElementById('num-rows');
                            const tableRows = document.querySelectorAll('#locales-tbody tr');

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
                            const rows = document.querySelectorAll('#locales-tbody tr');

                            // Agregar encabezados, asegurándote de no incluir la columna "Acciones"
                            csvContent += "Nombre,Ubicación,Descripción,WhatsApp,Email\n";

                            // Iterar sobre las filas visibles y agregar los datos al CSV
                            rows.forEach(row => {
                                if (row.style.display !== 'none') {
                                    const cells = row.querySelectorAll('td');
                                    const rowData = [];

                                    // Solo agregar datos de las celdas que no sean de las acciones
                                    cells.forEach((cell, index) => {
                                        // Evitar la última columna (que es la de acciones, índice 14 en este caso)
                                        if (index < cells.length - 3) {
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
                            link.setAttribute("download", "informe_locales.csv");
                            document.body.appendChild(link);

                            // Hacer clic automáticamente para iniciar la descarga
                            link.click();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body>

</html>