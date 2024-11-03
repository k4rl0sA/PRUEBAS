<?php
function is_logged_in() {
    var_dump('en auth.php='.session_id(), $_SESSION);
    return isset($_SESSION[SESSION_NAME]) && !empty($_SESSION[SESSION_NAME]);
}
// Función para iniciar sesión
function login($username, $password) {
    global $pdo; // Asegúrate de que $pdo está definido en el ámbito
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Aquí verifica si el usuario existe y si la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) { // Asegúrate de que las contraseñas se guarden hasheadas
        $_SESSION[SESSION_NAME] = strtolower($username); // Establecer la sesión
        return true;
    }
    return false;
}
function logout() {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>