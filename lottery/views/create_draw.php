<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/index_module'); ?>">Menu</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/list_draws'); ?>">Sorteos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear sorteo</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">Crear Sorteo</h4>
                        <!-- Añadir enctype para permitir subida de archivos -->
                        <?php echo form_open_multipart('lottery/insert_draw', ['id' => 'create-draw-form']); ?>

                        <div class="form-group">
                            <label for="nombre">Nombre del Sorteo</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="valor_por_factura">Valor por Factura</label>
                            <input type="number" step="0.01" id="valor_por_factura" name="valor_por_factura" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="patrocinador_id">Patrocinador</label>
                            <!-- Campo de búsqueda -->
                            <input type="text" id="search-patrocinador" class="form-control mb-2" placeholder="Buscar patrocinador...">

                            <!-- Select para elegir patrocinador -->
                            <select id="patrocinador_id" name="patrocinador_id[]" class="form-control" multiple>
                                <option value="">Selecciona un patrocinador</option>
                                <?php foreach ($locales_comerciales as $local): ?>
                                    <option value="<?php echo $local['id']; ?>"><?php echo $local['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="foto">Foto del Sorteo</label>
                            <input type="file" id="foto" name="foto" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de inicio</label>
                            <input type="datetime-local" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="fecha_finalizacion">Fecha de finalización</label>
                            <input type="datetime-local" id="fecha_finalizacion" name="fecha_finalizacion" class="form-control" required>
                        </div>

                        <!-- Campo para subir la foto -->


                        <button type="submit" class="btn btn-primary">Crear Sorteo</button>
                        <a href="<?php echo site_url('lottery/list_draws'); ?>" class="btn btn-secondary">Cancelar</a>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body>
<script>
    document.getElementById('search-patrocinador').addEventListener('input', function () {
        let filter = this.value.toLowerCase();
        let options = document.querySelectorAll('#patrocinador_id option');
        
        options.forEach(function (option) {
            let text = option.textContent || option.innerText;
            if (text.toLowerCase().includes(filter)) {
                option.style.display = '';  // Mostrar opción si coincide
            } else {
                option.style.display = 'none';  // Ocultar opción si no coincide
            }
        });
    });
</script>
</html>