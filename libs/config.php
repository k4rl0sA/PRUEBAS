<?php
$comy = array(
    'gitapps.site' => [
        's' => 'localhost',
        'u' => 'u470700275_08',
        'p' => 'z9#KqH!YK2VEyJpT',
        'bd' => 'u470700275_08',
        'port' => '3306',
        'charset' => 'utf8mb4'
    ],
    'pruebasiginf.site' => [
        's' => 'localhost',
        'u' => 'u470700275_17',
        'p' => 'z9#KqH!YK2VEyJpT',
        'bd' => 'u470700275_17',
        'port' => '3306',
        'charset' => 'utf8mb4'
    ]
);

// Configuración de errores
$mostrar_errores = true; // Cambiar a false en producción
$error_log_path = '../errors.log';
error_reporting(E_ALL);
ini_set('display_errors', $mostrar_errores ? '1' : '0');

// Configuración de entorno
$entorno = 'prod'; // Cambiar a 'dev' o 'test' según sea necesario

// Configuración de la aplicación
$app_name = 'GTAPS';
$app_version = '1.0.0';

// Configuración de sesión
$session_timeout = 3600; // Tiempo en segundos
define('SESSION_NAME', 'us_sds');

// Configuración de seguridad
$hash_algorithm = 'sha256';
$encryption_key = 'tu_clave_secreta';

// Configuración de API
$api_base_url = 'https://api.example.com/';
$api_key = 'tu_api_key';
$api_timeout = 30;

// Configuración de correo electrónico
$mail_host = 'smtp.example.com';
$mail_username = 'tu_correo@example.com';
$mail_password = 'tu_contraseña';
$mail_port = 587;
$mail_encryption = 'tls'; 

// Configuración de archivos
$ruta_upload = '/public_html/upload/';
$temp_file_path = '/tmp/';

// Configuración de registro
$log_level = 'info';

?>