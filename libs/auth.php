<?php
// Inicializar sesión si aún no se ha iniciado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si un usuario está autenticado
function is_logged_in() {
    return isset($_SESSION['us_sds']) && !empty($_SESSION['us_sds']);
}

// Login de usuario: Autentica y establece las variables de sesión
function login($username, $password) {
    global $pdo;
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    // Preparar y ejecutar la consulta
    $sql = "SELECT id_usuario, nombre, clave FROM usuarios WHERE id_usuario = :username AND estado = 'A'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($password, $user['clave'])) {
            $_SESSION['us_sds'] = $user['id_usuario'];
            $_SESSION['nomb'] = $user['nombre'];
            return true;
        }
    }
    return false; // Autenticación fallida
}
// Logout de usuario: Destruye la sesión
function logout() {
    session_unset();   // Eliminar variables de sesión
    session_destroy(); // Destruir la sesión
    header("Location: index.php");
    exit();
}
?>
