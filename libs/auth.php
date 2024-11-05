<?php
function is_logged_in() {
    var_dump('valido en auth.php='.session_id(), $_SESSION);
    return isset($_SESSION[SESSION_NAME]) && !empty($_SESSION[SESSION_NAME]);
}

// Función para iniciar sesión
function login($username, $password) {
    global $pdo; // Asegúrate de que $pdo esté definido y conectado a la base de datos
    $sql = "SELECT id_usuario, nombre, clave FROM usuarios WHERE id_usuario = :username AND estado = 'A'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['clave'])) {
        $_SESSION['us_sds'] = $user['id_usuario'];
        $_SESSION['nomb'] = $user['nombre'];
        return true;
    } else {
        return false;
    }
}
?>
