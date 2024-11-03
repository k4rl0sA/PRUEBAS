<?php
// Primero, incluye el archivo de configuración
require_once __DIR__ . '/libs/config.php';
require_once __DIR__ . '/libs/auth.php';
require_once __DIR__ . '/libs/gestion.php';

// Configuración de sesión y seguridad
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);

// Ahora puedes usar la constante
session_name(SESSION_NAME); // Asegúrate de usar el nombre de la sesión
session_start();

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
            var_dump('revisando desde index.php despues de la rta de login a la sesion '.$_SESSION);
            // Comentar la redirección para diagnóstico
            header("Location: " . ($pwd === "riesgo2020+" ? "cambio-clave/" : "main/"));
            exit();
            echo "Inicio de sesión exitoso."; // Para verificación
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