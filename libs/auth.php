<?php
// Verificar si un usuario está autenticado
function is_logged_in() {
    return isset($_SESSION[SESSION_NAME]) && !empty($_SESSION[SESSION_NAME]);
}

// Logout de usuario: Destruye la sesión
function logout() {
    session_unset();   // Eliminar variables de sesión
    session_destroy(); // Destruir la sesión
    header("Location: index.php");
    exit();
}
?>
