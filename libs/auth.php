<?php
require_once 'config.php';
require_once 'gestion.php';

session_start();

// Iniciar sesión
function login($username, $password) {
    $con = db_connect();

    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    $stmt = $con->prepare("SELECT id_usuario, nombre, clave FROM usuarios WHERE id_usuario = ? AND estado = 'A'");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($id_usuario, $nombre, $clave);
    
    if ($stmt->fetch() && password_verify($password, $clave)) {
        $_SESSION[SESSION_NAME] = $id_usuario;
        $_SESSION['nomb'] = $nombre;
        return true;
    }
    
    $stmt->close();
    $con->close();
    return false;
}

// Función para verificar si el usuario está logueado
function is_logged_in() {
    return isset($_SESSION[SESSION_NAME]);
}

// Función para cerrar sesión
function logout() {
    session_unset();
    session_destroy();
}
?>
