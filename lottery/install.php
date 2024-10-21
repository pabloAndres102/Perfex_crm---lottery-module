<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

// Crear la tabla 'clientes' si no existe
if (!$CI->db->table_exists(db_prefix() . 'lottery_clientes')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "lottery_clientes` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `asesor` VARCHAR(255) NOT NULL,
        `nombre` VARCHAR(255) NOT NULL,
        `apellido` VARCHAR(255) NOT NULL,
        `documento_identidad` VARCHAR(100) NOT NULL,
        `tipo_documento` VARCHAR(100) NOT NULL,  -- Nueva columna
        `tipo_persona` VARCHAR(100) NOT NULL,    -- Nueva columna
        `discapacidad` VARCHAR(255)  NOT NULL,  -- Nueva columna
        `tiene_mascota` VARCHAR(255) NOT NULL, -- Nueva columna
        `tiene_vehiculo` VARCHAR(255) NOT NULL,-- Nueva columna
        `sexo` ENUM('M', 'F', 'O') NOT NULL,
        `edad` INT(11) NOT NULL,
        `fecha_cumpleanos` VARCHAR(255) NULL,
        `email` VARCHAR(255) NOT NULL,
        `telefono` VARCHAR(20) NOT NULL,
        `municipio` VARCHAR(255) NOT NULL,
        `barrio` VARCHAR(255) NOT NULL,
        `estado_civil` VARCHAR(100) NOT NULL,
        `hijos` INT(11) DEFAULT 0,
        `nivel_academico` VARCHAR(255) NOT NULL,
        `ocupacion` VARCHAR(255) NOT NULL,
        `tipo_cliente` VARCHAR(255) NOT NULL,
        `datos_personales` ENUM('S', 'N') NOT NULL,
        `direccion_residencia` VARCHAR(255) NOT NULL, 
        `fecha_creacion` DATETIME NOT NULL,
        `fecha_actualizacion` DATETIME NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

// Verifica si la tabla 'lottery_clientes' existe
if ($CI->db->table_exists(db_prefix() . 'lottery_clientes')) {
    // Verifica si la columna 'hijos_menores' ya existe en la tabla
    if (!$CI->db->field_exists('hijos_menores', db_prefix() . 'lottery_clientes')) {
        // Si la columna no existe, la agregamos
        $CI->db->query('ALTER TABLE `' . db_prefix() . "lottery_clientes` 
            ADD `hijos_menores` ENUM('S', 'N') NOT NULL DEFAULT 'N' AFTER `hijos`;");
    }
}



// Crear la tabla 'locales_comerciales' si no existe
if (!$CI->db->table_exists(db_prefix() . 'lottery_locales_comerciales')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "lottery_locales_comerciales` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `nombre` VARCHAR(255) NOT NULL,
        `ubicacion` VARCHAR(255) NOT NULL,
        `descripcion` TEXT NULL,
        `fotos` TEXT NULL,
        `whatsapp` VARCHAR(20) NULL,
        `email` VARCHAR(255) NULL,
        `local` VARCHAR(255) NULL,
        `activo` TINYINT(1) NOT NULL DEFAULT 1,
        `ficha_catastro` VARCHAR(50) NULL,
        `website` VARCHAR(255) NULL,
        `piso_o_nivel` VARCHAR(50) NULL,
        `categoria` VARCHAR(100) NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}


// Crear la tabla 'sorteo' si no existe y agregar la columna 'ganador_cliente_id'
if (!$CI->db->table_exists(db_prefix() . 'lottery_sorteo')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "lottery_sorteo` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `nombre` VARCHAR(255) NOT NULL,
        `descripcion` TEXT NULL,
        `foto` TEXT NULL,
        `valor_por_factura` DECIMAL(10,2) NOT NULL,
        `patrocinador_id` JSON NULL, -- Cambiar a JSON para almacenar múltiples patrocinadores
        `fecha_inicio` DATETIME NOT NULL,
        `fecha_finalizacion` DATETIME NOT NULL,
        `ganador_cliente_id` INT(11) DEFAULT NULL, -- Se agrega la columna aquí
        PRIMARY KEY (`id`),
        FOREIGN KEY (`ganador_cliente_id`) REFERENCES `" . db_prefix() . "lottery_clientes`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

// Crear la tabla 'sorteo_clientes' si no existe
if (!$CI->db->table_exists(db_prefix() . 'lottery_sorteo_clientes')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "lottery_sorteo_clientes` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `sorteo_id` INT(11) NOT NULL,
        `cliente_id` INT(11) NOT NULL,
        `valor_factura` INT(11) NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`sorteo_id`) REFERENCES `" . db_prefix() . "lottery_sorteo`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`cliente_id`) REFERENCES `" . db_prefix() . "lottery_clientes`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'lottery_facturas')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "lottery_facturas` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `cliente_id` INT(11) NOT NULL,
        `numero_factura` VARCHAR(255) NOT NULL,
        `valor` DECIMAL(10, 2) NOT NULL,
        `fecha_emision` DATETIME NOT NULL,      
        `sorteo_id` INT(11) NOT NULL,  /* Nuevo campo para asociar el sorteo */
        `local_id` INT(11) NOT NULL,   /* Nuevo campo para asociar el local */
        PRIMARY KEY (`id`),
        FOREIGN KEY (`cliente_id`) REFERENCES `" . db_prefix() . "lottery_clientes`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`sorteo_id`) REFERENCES `" . db_prefix() . "lottery_sorteo`(`id`) ON DELETE CASCADE,  /* Relación con sorteo */
        FOREIGN KEY (`local_id`) REFERENCES `" . db_prefix() . "lottery_locales_comerciales`(`id`) ON DELETE CASCADE  /* Relación con local */
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}



/////////////////////////////////////// -------- CONFIGURATIONS ----------- ///////////////////////////////////////////////////


// Crea la tabla lottery_barrios si no existe
if (!$CI->db->table_exists(db_prefix() . 'lottery_barrios')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "lottery_barrios` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `nombre` VARCHAR(255) NOT NULL,
            `descripcion` TEXT NULL,
            `fecha_creacion` DATETIME NOT NULL,
            `fecha_modificacion` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

// Crea la tabla lottery_municipios si no existe
if (!$CI->db->table_exists(db_prefix() . 'lottery_municipios')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "lottery_municipios` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nombre` VARCHAR(255) NOT NULL,
                `descripcion` TEXT NULL,
                `fecha_creacion` DATETIME NOT NULL,
                `fecha_modificacion` DATETIME NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

// Crea la tabla lottery_mascotas si no existe
if (!$CI->db->table_exists(db_prefix() . 'lottery_mascotas')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "lottery_mascotas` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `especie` VARCHAR(255) NOT NULL,
                `descripcion` VARCHAR(255) NOT NULL,
                `fecha_creacion` DATETIME NOT NULL,
                `fecha_modificacion` DATETIME NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'lottery_tipos_documentos')) {
    // Crear la tabla lottery_tipos_documentos con prefijo
    $CI->db->query('CREATE TABLE `' . db_prefix() . "lottery_tipos_documentos` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `nombre` VARCHAR(255) NOT NULL,
        `descripcion` TEXT,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

// Crea la tabla lottery_categorias_locales si no existe
if (!$CI->db->table_exists(db_prefix() . 'lottery_categorias_locales')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "lottery_categorias_locales` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `nombre` VARCHAR(255) NOT NULL,
        `descripcion` TEXT NULL,
        `fecha_creacion` DATETIME NOT NULL,
        `fecha_modificacion` DATETIME NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
