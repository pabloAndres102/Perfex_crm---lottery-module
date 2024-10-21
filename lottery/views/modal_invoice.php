<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div class="panel_s">
    <div class="panel-body">
        <form action="<?php echo admin_url('lottery/insert_invoice'); ?>" method="post">
            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>

            <!-- Campo de búsqueda -->
            <div class="form-group">
                <label>Buscar Cliente por Documento de Identidad</label>
                <input type="text" id="client_search" class="form-control" placeholder="Buscar por documento de identidad" />
            </div>

            <!-- Selección de cliente -->
            <div class="form-group">
                <label for="cliente_id">Cliente:</label>
                <select name="cliente_id" id="cliente_id" class="form-control" multiple>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?php echo $cliente['id']; ?>" data-documento="<?php echo $cliente['documento_identidad']; ?>">
                            <?php echo $cliente['nombre'] . ' ' . $cliente['apellido']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Selección de local comercial -->
            <div class="form-group">
                <label for="local_id">Local Comercial:</label>
                <select name="local_id" id="local_id" class="form-control" required>
                    <option value="">Seleccione un local</option>
                    <?php foreach ($locales as $local): ?>
                        <option value="<?php echo $local['id']; ?>">
                            <?php echo $local['nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Selección de sorteo -->
            <div class="form-group">
                <label for="sorteo_id">Sorteo:</label>
                <select name="sorteo_id" id="sorteo_id" class="form-control" required>
                    <option value="">Seleccione un sorteo</option>
                    <?php foreach ($sorteos as $sorteo): ?>
                        <option value="<?php echo $sorteo['id']; ?>">
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


<?php init_tail(); ?>