<?php
// Incluir la configuración antes de establecer la sesión
require_once __DIR__ . '/libs/config.php';
// Configuración de sesión y seguridad
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);
// Establecer el nombre de la sesión antes de iniciar
session_name(SESSION_NAME);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
var_dump(session_id(), $_SESSION); // Para depuración
// Incluir archivos de funciones
try {
    require_once __DIR__ . '/libs/gestion.php';
} catch (Exception $e) {
    echo "Error cargando archivos: " . $e->getMessage();
    exit();
}
// Verificar si el usuario ya está autenticado
if (is_logged_in()) {
    header("Location: main/");
    exit();
}
// Procesar el formulario de autenticación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = test_input($_POST['username']);
    $pwd = $_POST['passwd'];

    try {
        if (login($name, $pwd)) {
            $_SESSION[SESSION_NAME] = strtolower($name);
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
    include_once('./login/frmlogin.php');
}
?>