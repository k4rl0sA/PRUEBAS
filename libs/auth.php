<?php
function is_logged_in() {
    var_dump('valido en auth.php='.session_id(), $_SESSION);
    return isset($_SESSION[SESSION_NAME]) && !empty($_SESSION[SESSION_NAME]);
}
// Función para iniciar sesión
function login($username, $password) {
    global $pdo;
    echo 'valor de pdo';
    var_dump($pdo);
    // Busca al usuario en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // Verifica si el usuario existe y si la contraseña es correcta
    var_dump('Ruta de guardado de sesión:', session_save_path());
    if (!is_writable(session_save_path())) {
        echo "Error: No hay permisos de escritura en la ruta de sesión.";
    }

    if ($user && password_verify($password, $user['password'])) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[SESSION_NAME] = strtolower($username); // Establecer la sesión
        var_dump('Usuario autenticado: ', $user); // Muestra los detalles del usuario
        var_dump('Estado de sesión después de login:', $_SESSION); // Verificar
        session_write_close(); // Forzar el guardado de la sesión
        return true; // Autenticación exitosa
    }
    var_dump('Autenticación fallida para usuario: ', $username);
    return false; // Falló la autenticación
}
function logout() {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>