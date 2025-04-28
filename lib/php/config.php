<?php
define('SESSION_NAME', 'us_sds');
session_save_path($session_path);
session_name(SESSION_NAME);

$comy = array(
    'siginf-sds.com' => [
        's' => 'localhost',
        'u' => 'u470700275_06',
        'p' => 'z9#KqH!YK2VEyJpT',
        'bd' => 'u470700275_06',
        'port' => '3306',
        'charset' => 'utf8mb4'
    ],
    'pruebagtaps.site' => [
        's' => 'localhost',
        'u' => 'u470700275_17',
        'p' => 'testPassword',
        'bd' => 'u470700275_17',
        'port' => '3306',
        'charset' => 'utf8mb4'
    ]
);

// Variable para mostrar errores
$error_log_path = '/path/to/error.log';
// Configuración de errores
$mostrar_errores = true; // Cambiar a false en producción
error_reporting(E_ALL);
ini_set('display_errors', $mostrar_errores ? '1' : '0');

// Configuración de entorno
$entorno = 'produccion';

// Configuración de la aplicación
$app_name = 'Mi Aplicación';
$app_version = '1.0.0';

// Configuración de sesión
$session_timeout = 3600; // Tiempo de espera de sesión en segundos
$session_name = 'us_sds';

// Configuración de seguridad
$hash_algorithm = 'sha256';
$encryption_key = 'tu_clave_secreta';

// Configuración de API
$api_base_url = 'https://api.example.com/';
$api_key = 'tu_api_key';

// Configuración de correo electrónico
$mail_host = 'smtp.example.com';
$mail_username = 'tu_correo@example.com';
$mail_password = 'tu_contraseña';
$mail_port = 587;
$mail_encryption = 'tls';
