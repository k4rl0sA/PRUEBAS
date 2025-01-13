<?php
// Incluir el archivo de configuración
require_once __DIR__ . '/../01config/config.php';
session_start();
require_once __DIR__ . '/login/login.php';

/* // claves.php o config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 */
// Procesar formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST['user']);
    $password = test_input($_POST['pwd']);

    // Validar usuario
    try {
        $isValid = login($username, $password);
        if ($isValid) {
            $_SESSION[SESSION_NAME] = strtolower($username);
             var_dump($_SESSION);           
            // Redirigir según contraseña
            $redirect = ($password === "Subred2025+") ? "cambio-clave/" : "Inicio/";
            header("Location: $redirect");
            exit();
        } else {
            displayError("Nombre de usuario o contraseña incorrectos.");
        }
    } catch (Exception $e) {
        displayError("Ocurrió un error: " . $e->getMessage());
    }
}

// Función para mostrar errores
function displayError($message) {
    echo "<div class='error'>
            <span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span> 
            <strong>Error!</strong> $message
          </div>";
}

// Función para limpiar entradas
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}