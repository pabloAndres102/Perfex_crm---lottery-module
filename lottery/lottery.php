<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Modulo de sorteos
Description: Modulo para manejar sorteos.
Version: 1.0.0
Requires at least: 2.3.*
*/

// Hook de activación del módulo
register_activation_hook('lottery', 'lottery_install');

// Función que se ejecuta al activar el módulo
function lottery_install() {
    include_once(__DIR__ . '/install.php');
}

// Agregar el menú en la barra lateral de Perfex
hooks()->add_action('admin_init', 'lottery_module_menu_item');

// Función para añadir un ítem al menú lateral con submenús
function lottery_module_menu_item() {
    $CI = &get_instance();

    if (has_permission('lottery', '', 'view')) {
        // Agregar el enlace principal en el menú lateral
        $CI->app_menu->add_sidebar_menu_item('lottery-menu', [
            'name'     => 'Sorteos', // Nombre que aparecerá en el menú
            'href'     => '#', // No redirige a una página específica (es un contenedor)
            'position' => 15, // Ajusta la posición en el menú
            'icon'     => 'fa fa-ticket', // Ícono principal que aparecerá junto al nombre
        ]);

        // Agregar las opciones del submenú
        $CI->app_menu->add_sidebar_children_item('lottery-menu', [
            'slug'     => 'lottery-child-1',
            'name'     => 'Facturas', // Primer submenú
            'href'     => admin_url('lottery/list_invoices'), // URL del enlace
            'position' => 1,
            'icon'     => 'fa fa-file-invoice-dollar', // Ícono de facturas (usa el de FontAwesome)
        ]);

        $CI->app_menu->add_sidebar_children_item('lottery-menu', [
            'slug'     => 'lottery-child-2',
            'name'     => 'Clientes', // Segundo submenú
            'href'     => admin_url('lottery/list_clients'), // URL del enlace
            'position' => 2,
            'icon'     => 'fa fa-users', // Ícono de personas
        ]);

        $CI->app_menu->add_sidebar_children_item('lottery-menu', [
            'slug'     => 'lottery-child-3',
            'name'     => 'Sorteos', // Tercer submenú
            'href'     => admin_url('lottery/list_draws'), // URL del enlace
            'position' => 3,
            'icon'     => 'fa fa-ticket-alt', // Ícono de sorteos o tickets
        ]);

        $CI->app_menu->add_sidebar_children_item('lottery-menu', [
            'slug'     => 'lottery-child-4',
            'name'     => 'Locales', // Cuarto submenú
            'href'     => admin_url('lottery/list_locales'), // URL del enlace
            'position' => 4,
            'icon'     => 'fa fa-store', // Ícono de local comercial (ejemplo de tienda o casa)
        ]);

        $CI->app_menu->add_sidebar_children_item('lottery-menu', [
            'slug'     => 'lottery-child-5',
            'name'     => 'Configuraciones', // Quinto submenú
            'href'     => admin_url('lottery/configurations'), // No redirige a ninguna página, es solo un placeholder
            'position' => 6,
            'icon'     => 'fa fa-cogs', // Ícono de configuraciones (engranajes)
        ]);
    }
}

// Opcional: Si quieres inicializar algo en la parte de clientes
// hooks()->add_action('clients_init', 'lottery_init_client');

// Función opcional de inicialización en la parte del cliente
function lottery_init_client() {
    $CI = &get_instance();
    $CI->load->helper('lottery/lottery_helper');
}
