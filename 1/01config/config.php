<?php
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        throw new Exception("El archivo .env no existe en la ruta especificada: $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorar comentarios
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        // Separar clave y valor
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // Establecer la variable de entorno
        putenv("$key=$value");
        $_ENV[$key] = $value; // También puedes almacenar en $_ENV si lo prefieres
    }
}

// Cargar el archivo .env
loadEnv(__DIR__ . '/.env');

// Validar variables obligatorias
$requiredEnv = ['DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME', 'SESSION_NAME'];
foreach ($requiredEnv as $key) {
    if (empty(getenv($key))) {
        throw new Exception("La variable $key no está definida o está vacía en el archivo .env.");
    }
}

// Definiciones de constantes
define('SESSION_NAME', getenv('SESSION_NAME'));
define('DB_HOST', getenv('DB_HOST'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_PORT', getenv('DB_PORT') ?: 3306); // Valor por defecto
define('DB_CHARSET', 'utf8mb4');

// Configuración de errores
$error_log_path = getenv('ERROR_LOG_PATH'); // Ruta al archivo de log
$mostrar_errores = filter_var(getenv('MOSTRAR_ERRORES'), FILTER_VALIDATE_BOOLEAN); // Asegurar true/false
if ($mostrar_errores) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
}
ini_set('log_errors', '1');
ini_set('error_log', $error_log_path);

// Configuración de sesión
session_save_path(getenv('SESSION_SAVE_PATH')); // Cambiar a una ruta segura
if (!is_dir($session_save_path)) {
    mkdir($session_save_path, 0755, true);
}
session_name(SESSION_NAME); // Establecer el nombre de la sesión
session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'] ?? '',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // Solo HTTPS
    'httponly' => true,    // No accesible por JavaScript
    'samesite' => 'Strict' // Previene CSRF
]);
session_start(); // Iniciar la sesión después de establecer los parámetros

// Configuración de seguridad
$hash_algorithm = getenv('HASH_ALGORITHM') ?: 'sha256'; // Valor por defecto
$encryption_key = getenv('ENCRYPTION_KEY');
if (empty($encryption_key)) {
    throw new Exception("La clave de encriptación (ENCRYPTION_KEY) no está definida o está vacía.");
}

// Configuración de API
$api_base_url = getenv('API_BASE_URL');
$api_key = getenv('API_KEY');

// Configuración de correo electrónico
$mail_host = getenv('MAIL_HOST');
$mail_username = getenv('MAIL_USERNAME');
$mail_password = getenv('MAIL_PASSWORD');
$mail_port = getenv('MAIL_PORT') ?: 587; // Valor por defecto
$mail_encryption = getenv('MAIL_ENCRYPTION') ?: 'tls'; // Valor por defecto

// Validaciones adicionales para el correo
if (!$mail_host || !$mail_username || !$mail_password) {
    throw new Exception("Las configuraciones de correo electrónico no están completas.");
}

// Configuración de la aplicación
$app_name = 'Mi Aplicación';
$app_version = '1.0.0';