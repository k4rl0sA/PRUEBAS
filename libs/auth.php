<?php
// Inicializar sesión si aún no se ha iniciado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si un usuario está autenticado
function is_logged_in() {
    return isset($_SESSION['us_sds']) && !empty($_SESSION['us_sds']);
}

// Logout de usuario: Destruye la sesión
function logout() {
    session_unset();   // Eliminar variables de sesión
    session_destroy(); // Destruir la sesión
    header("Location: index.php");
    exit();
}
?>
