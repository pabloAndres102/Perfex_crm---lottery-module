<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .container {
        text-align: center;
    }

    .btn {
        padding: 12px 20px;
        font-size: 16px;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        background-color: #3498db;
        color: white;
        border: 1px solid #2980b9;
        transition: background-color 0.3s ease, border-color 0.3s ease;
        width: 100%;
        /* Botones ocupan el 100% de su contenedor */
        max-width: 300px;
        /* Máximo ancho para los botones */
        margin-bottom: 15px;
    }

    .btn:hover {
        background-color: #2980b9;
        border-color: #1c5985;
    }

    .container h1 {
        text-align: center;
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 40px;
        color: #333;
    }

    .row.justify-content-center {
        display: flex;
        justify-content: center;
        /* Asegura que los botones estén centrados */
    }

    .col-md-6 {
        display: flex;
        flex-direction: column;
        align-items: center;
        /* Centrar los botones dentro de la columna */
    }
</style>




<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="container">
                    <h1>Bienvenido al Módulo de sorteos</h1>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <a href="<?php echo site_url('lottery/list_invoices'); ?>" class="btn btn-primary">Facturas</a>
                            <a href="<?php echo site_url('lottery/list_clients'); ?>" class="btn btn-primary">Clientes</a>
                            <a href="<?php echo site_url('lottery/list_draws'); ?>" class="btn btn-primary">Sorteos</a>
                            <a href="<?php echo site_url('lottery/list_locales'); ?>" class="btn btn-primary">Locales</a>
                            <a href="<?php echo site_url('lottery/configurations'); ?>" class="btn btn-primary">Configurations</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body>

</html>


</html>