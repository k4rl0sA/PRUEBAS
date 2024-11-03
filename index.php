<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    require_once __DIR__ . '/libs/config.php';
    require_once __DIR__ . '/libs/gestion.php';
} catch (Exception $e) {
    echo "Error cargando archivos: " . $e->getMessage();
    exit();
}

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
}

include_once('./login/frmlogin.php');
?>
