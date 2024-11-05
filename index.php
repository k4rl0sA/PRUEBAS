<?php
// Primero, incluye el archivo de configuración
require_once __DIR__ . '/libs/config.php';
require_once __DIR__ . '/libs/auth.php';
require_once __DIR__ . '/libs/gestion.php';

// Configuración de sesión y seguridad
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);

// Configura el nombre de la sesión
session_name(SESSION_NAME);
session_set_cookie_params([
    'lifetime' => 86400, // Vida de la cookie por un día
    'path' => '/',
    'domain' => 'pruebasiginf.site', // Asegúrate de usar el dominio correcto
    'secure' => false, // Cambia a `true` si estás usando HTTPS
    'httponly' => true,
    'samesite' => 'Lax',
]);

$session_path = __DIR__ . '/../sesiones/';
if (!is_dir($session_path)) {
    mkdir($session_path, 0777, true); // 0777 asegura que PHP puede escribir
}
session_save_path($session_path);

session_start();

var_dump(session_save_path());
if (!is_writable(session_save_path())) {
    echo "Error: La ruta de la sesión no tiene permisos de escritura.";
}

// Depuración
var_dump('valido desde index.php ID de sesión: ', session_id());
var_dump('valido desde index.php Contenido de la sesión: ', $_SESSION);

// Procesar el formulario de autenticación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = test_input($_POST['username']);
    $pwd = $_POST['passwd'];

    try {
        if (login($name, $pwd)) {
            $_SESSION[SESSION_NAME] = strtolower($name);
            session_write_close(); // Guardar la sesión
            var_dump('Verificación post-login en index.php:', $_SESSION);
            // Redirección (comentar para pruebas)
            header("Location: " . ($pwd === "riesgo2020+" ? "cambio-clave/" : "main/"));
            exit();
        } else {
            echo "<div class='error'><strong>Error!</strong> Usuario o contraseña incorrectos.</div>";
        }
    } catch (Exception $e) {
        error_log("Error en index.php: " . $e->getMessage());
        echo "Error en la autenticación.";
    }
} else {
    // Muestra el formulario de inicio de sesión
    ?>
    <form action="index.php" method="POST">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>

        <label for="passwd">Contraseña:</label>
        <input type="password" id="passwd" name="passwd" required>

        <button type="submit">Iniciar sesión</button>
    </form>
    <?php
}
?>
