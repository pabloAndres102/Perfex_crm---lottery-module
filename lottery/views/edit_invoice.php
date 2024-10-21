<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/index_module'); ?>">Menu</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/list_invoices'); ?>">Facturas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar factura</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <form action="<?php echo admin_url('lottery/update_invoice/' . $factura['id']); ?>" method="post">
                            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                            <div class="form-group">
                                <label for="cliente_id">Cliente:</label>
                                <select name="cliente_id" id="cliente_id" class="form-control">
                                    <?php foreach ($clientes as $cliente): ?>
                                        <option value="<?php echo $cliente['id']; ?>" <?php echo $cliente['id'] == $factura['cliente_id'] ? 'selected' : ''; ?>>
                                            <?php echo $cliente['nombre'] . ' ' . $cliente['apellido']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="numero_factura">Número de Factura:</label>
                                <input type="text" name="numero_factura" id="numero_factura" class="form-control" value="<?php echo $factura['numero_factura']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="valor">Valor:</label>
                                <input type="number" step="0.01" class="form-control" name="valor" id="valor" value="<?php echo $factura['valor']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="local_id">Local Comercial:</label>
                                <select name="local_id" id="local_id" class="form-control">
                                    <?php foreach ($locales as $local): ?>
                                        <option value="<?php echo $local['id']; ?>" <?php echo $local['id'] == $factura['local_id'] ? 'selected' : ''; ?>>
                                            <?php echo $local['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sorteo_id">Sorteo:</label>
                                <select name="sorteo_id" id="sorteo_id" class="form-control">
                                    <?php foreach ($sorteos as $sorteo): ?>
                                        <option value="<?php echo $sorteo['id']; ?>" <?php echo $sorteo['id'] == $factura['sorteo_id'] ? 'selected' : ''; ?>>
                                            <?php echo $sorteo['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Actualizar Factura</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const numeroFacturaInput = document.getElementById('numero_factura');

        // Verificar si el número de factura ya existe antes de enviar el formulario
        form.addEventListener('submit', function(event) {
            const numeroFactura = numeroFacturaInput.value;

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
</script>
<?php init_tail(); ?>
</body>

</html>