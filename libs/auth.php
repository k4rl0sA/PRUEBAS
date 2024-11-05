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

    if (!$user) {
        echo "Usuario no encontrado.";
        return false;
    }

    // Depuración de contraseña ingresada y almacenada
    var_dump('Contraseña ingresada:', $password);
    var_dump('Contraseña guardada en BD:', $user['password']);

    if (password_verify($password, $user['password'])) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[SESSION_NAME] = strtolower($username);
        
        // Verificación inmediata de la variable de sesión
        var_dump('Estado de sesión justo después de asignación:', $_SESSION);
        sleep(2); // Pausa de 2 segundos para permitir verificación
        
        session_write_close();
        return true;
    } else {
        echo "Error: Contraseña incorrecta.";
    }

    // Información adicional de depuración
    var_dump('Ruta de guardado de sesión:', session_save_path());
    if (!is_writable(session_save_path())) {
        echo "Error: No hay permisos de escritura en la ruta de sesión.";
    }
    
    var_dump('Autenticación fallida para usuario: ', $username);
    return false;
}

//
