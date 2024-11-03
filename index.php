<?php
// Configuración de sesión y seguridad
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_only_cookies', 1);

// Iniciar la sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir archivos de configuración y funciones
try {
    require_once __DIR__ . '/libs/config.php';
    require_once __DIR__ . '/libs/gestion.php';
} catch (Exception $e) {
    echo "Error cargando archivos: " . $e->getMessage();
    exit();
}

// Solo procesa el formulario de autenticación
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
    // Si no está autenticado, carga el formulario
    include_once('./login/frmlogin.php');
}
?>

