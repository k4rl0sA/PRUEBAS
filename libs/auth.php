<?php
// Verificar si un usuario está autenticado
function is_logged_in() {
    if (isset($_SESSION[SESSION_NAME]) && !empty($_SESSION[SESSION_NAME])) {
        return true;
    }
    return false;
}
// Cerrar sesión
function logout() {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>