<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .modal {
        display: none;
        /* Oculto por defecto */
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background-color: rgba(0, 0, 0, 0.5);
        /* Fondo semi-transparente */
    }

    .modal-dialog {
        position: relative;
        margin: 5% auto;
        width: 90%;
        max-width: 800px;
    }

    .modal-content {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
    }

    .close {
        background: none;
        border: none;
        font-size: 1.5em;
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Modal para creación de clientes -->
                <div id="clientCreationModal" class="modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Crear Cliente</h5>
                                <button type="button" class="close" onclick="closeClientModal()" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <iframe id="clientIframe" style="width: 100%; height: 400px;" frameborder="0"></iframe>
                            </div>
                            <div class="modal-footer">
                                <!-- Botón de cerrar adicional -->
                                <button type="button" class="btn btn-secondary" onclick="closeClientModal()">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>



                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/list_clients'); ?>">Clientes</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('lottery/list_draws'); ?>">Sorteos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Asociar clientes a sorteo</li>
                    </ol>
                </nav>
                <div class="panel_s">
                    <div class="panel-body">
                        <h4>Asociar Clientes al Sorteo: <?php echo $draw['nombre']; ?></h4>
                        <form action="<?php echo site_url('lottery/save_clients_to_draw'); ?>" method="POST">
                            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                            <input type="hidden" name="draw_id" value="<?php echo $draw['id']; ?>" />

                            <div class="form-group">
                                <label>Buscar por Documento de Identidad</label>
                                <input type="text" id="client_search" class="form-control" placeholder="Buscar por documento de identidad" />
                            </div>

                            <div class="form-group">
                                <label>Seleccionar Clientes</label>
                                <select name="client_ids[]" id="client_ids" class="form-control" multiple>
                                    <?php foreach ($clients as $client) : ?>
                                        <option value="<?php echo $client['id']; ?>"
                                            data-documento="<?php echo $client['documento_identidad']; ?>"
                                            <?php echo in_array($client['id'], $selected_clients) ? 'selected' : ''; ?>>
                                            <?php echo $client['nombre'] . ' ' . $client['apellido'] . ' - ' . $client['documento_identidad']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div id="factura_fields">
                                <!-- Los campos para seleccionar facturas se agregarán aquí mediante JavaScript -->
                            </div>

                            <button type="submit" class="btn btn-primary">Guardar</button>

                            <a id="create_client_link" href="<?php echo site_url('lottery/modal_client'); ?>" class="btn btn-warning mb-3" target="_blank" style="display: none;">Crear Cliente</a>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clientSelect = document.getElementById('client_ids');
        const facturaFields = document.getElementById('factura_fields');
        const clientSearch = document.getElementById('client_search');
        const createClientLink = document.getElementById('create_client_link');
        const drawValorPorFactura = <?php echo $draw['valor_por_factura']; ?>; // Valor del sorteo
        const form = document.querySelector('form'); // Selecciona el formulario

        // Elemento para mostrar el mensaje cuando no se encuentra un cliente
        const noClientsMessage = document.createElement('div');
        noClientsMessage.id = 'no_clients_message';
        noClientsMessage.classList.add('alert', 'alert-warning');
        noClientsMessage.style.display = 'none'; // Inicialmente oculto
        clientSearch.parentElement.appendChild(noClientsMessage);

        // Elemento para mostrar el mensaje cuando no hay facturas disponibles
        const noFacturasMessage = document.createElement('div');
        noFacturasMessage.id = 'no_facturas_message';
        noFacturasMessage.classList.add('alert', 'alert-warning');
        noFacturasMessage.style.display = 'none'; // Inicialmente oculto
        facturaFields.appendChild(noFacturasMessage);

        function updateFacturaFields() {
            facturaFields.innerHTML = ''; // Limpiar campos existentes
            const selectedOptions = Array.from(clientSelect.selectedOptions);

            selectedOptions.forEach(option => {
                const clientId = option.value;
                const label = option.textContent.split(' - ')[0]; // Obtener solo nombre y apellido

                // Realizar una solicitud AJAX para obtener las facturas del cliente
                fetch(`<?php echo site_url('lottery/get_client_invoices'); ?>/${clientId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const fieldContainer = document.createElement('div');
                            fieldContainer.classList.add('form-group');
                            fieldContainer.innerHTML = `
                                <label>Seleccionar Facturas para ${label}</label>
                                ${data.map(factura => `
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="factura_ids[${clientId}][]" value="${factura.id}" data-valor="${factura.valor}" id="factura_${clientId}_${factura.id}">
                                        <label class="form-check-label" for="factura_${clientId}_${factura.id}">
                                            Factura N° ${factura.numero_factura} - Valor: $${factura.valor}
                                        </label>
                                    </div>
                                `).join('')}
                                <div class="form-group">
                                    <label>Total Facturas para ${label}</label>
                                    <input type="text" name="total_valor_factura[${clientId}]" id="total_valor_factura_${clientId}" class="form-control" readonly />
                                </div>
                            `;
                            facturaFields.appendChild(fieldContainer);
                        } else {
                            const fieldContainer = document.createElement('div');
                            fieldContainer.classList.add('form-group');
                            noFacturasMessage.innerHTML = `
                                <p><strong>Advertencia:</strong> No hay facturas disponibles para ${label}</p>
                                <a href="<?php echo site_url('lottery/create_invoice'); ?>?client_id=${clientId}" class="btn btn-warning">Crear factura</a>
                            `;
                            noFacturasMessage.style.display = 'block'; // Mostrar el mensaje
                            fieldContainer.appendChild(noFacturasMessage);
                            facturaFields.appendChild(fieldContainer);
                        }
                    });
            });
        }

        function updateTotalFactura(clientId) {
            const checkboxes = document.querySelectorAll(`#factura_fields input[name="factura_ids[${clientId}][]"]:checked`);
            const totalInput = document.getElementById(`total_valor_factura_${clientId}`);

            let total = 0;
            checkboxes.forEach(checkbox => {
                total += parseFloat(checkbox.getAttribute('data-valor'));
            });

            totalInput.value = total.toFixed(2); // Mostrar el total formateado

            return total; // Devuelve el total de las facturas seleccionadas
        }

        function validateFacturas() {
            const selectedOptions = Array.from(clientSelect.selectedOptions);
            let isValid = true;

            selectedOptions.forEach(option => {
                const clientId = option.value;
                const totalFacturas = updateTotalFactura(clientId); // Calcula el total de facturas seleccionadas para cada cliente

                if (totalFacturas < drawValorPorFactura) {
                    alert(`El total de las facturas seleccionadas para el cliente ${option.textContent} ($${totalFacturas.toFixed(2)}) es menor que el valor requerido por el sorteo ($${drawValorPorFactura}).`);
                    isValid = false;
                }
            });

            return isValid; // Solo si todas las validaciones pasan, permite el envío del formulario
        }

        function filterClients() {
            const searchTerm = clientSearch.value.toLowerCase();
            const options = clientSelect.querySelectorAll('option');
            let hasResults = false;

            options.forEach(option => {
                const documento = option.getAttribute('data-documento').toLowerCase();
                if (documento.includes(searchTerm)) {
                    option.style.display = '';
                    hasResults = true;
                } else {
                    option.style.display = 'none';
                }
            });

            // Mostrar u ocultar el mensaje y el enlace basado en los resultados de la búsqueda
            if (!hasResults && searchTerm) {
                noClientsMessage.innerHTML = `
    <p><strong>Advertencia:</strong> El cliente con el documento de identidad ingresado no está registrado.</p>
    <a href="javascript:void(0);" class="btn btn-warning mb-3" onclick="openClientModal('<?php echo site_url('lottery/modal_client'); ?>')">Crear Cliente</a>
`;

                noClientsMessage.style.display = 'block'; // Mostrar el mensaje
                createClientLink.style.display = 'none'; // Ocultar el enlace de crear cliente del formulario
            } else {
                noClientsMessage.style.display = 'none'; // Ocultar el mensaje
                createClientLink.style.display = 'none'; // Ocultar el enlace de crear cliente del formulario
            }
        }

        clientSearch.addEventListener('input', filterClients);
        clientSelect.addEventListener('change', updateFacturaFields);
        facturaFields.addEventListener('change', function(event) {
            if (event.target.matches('input[type="checkbox"]')) {
                const clientId = event.target.name.match(/\d+/)[0]; // Extraer el ID del cliente del nombre del checkbox
                updateTotalFactura(clientId);
            }
        });

        updateFacturaFields(); // Inicializar campos al cargar
    });
</script>
<script>
    // Función para abrir el modal y cargar la URL en el iframe
    function openClientModal(url) {
        const modal = document.getElementById('clientCreationModal');
        const iframe = document.getElementById('clientIframe');

        iframe.src = url; // Establecer la URL de creación de clientes en el iframe
        modal.style.display = 'block'; // Mostrar el modal
    }

    // Función para cerrar el modal
    function closeClientModal() {
        const modal = document.getElementById('clientCreationModal');
        const iframe = document.getElementById('clientIframe');

        iframe.src = ''; // Limpiar la URL del iframe
        modal.style.display = 'none'; // Ocultar el modal
        window.location.reload();
    }
    
</script>



<?php init_tail(); ?>
</body>

</html>