<?php
require_once 'config.php';
require_once 'gestion.php';
if ($mostrar_errores) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}
session_start();

/* try {
    require_once __DIR__ . '/libs/config.php';
} catch (Exception $e) {
    echo "Error en config.php: " . $e->getMessage();
}
 
try {
    require_once __DIR__ . '/libs/gestion.php';
} catch (Exception $e) {
    echo "Error en gestion.php: " . $e->getMessage();
} */

// Procesa el formulario cuando se envía por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = test_input($_POST['username']);
    $pwd = $_POST['passwd'];
    // Llama a la función de autenticación
    try{
        if (login($name, $pwd)) {
            $_SESSION[SESSION_NAME] = strtolower($name);
            // Verifica si la contraseña es la predeterminada para forzar cambio
            if ($pwd === "riesgo2020+") {
                header("Location: cambio-clave/");
            } else {
                header("Location: main/");
            }
            exit();
        } else {
            echo "<div class='error'>
                    <span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span> 
                    <strong>Error!</strong> Vaya, no hemos encontrado nada que coincida con este nombre de usuario y contraseña en nuestra base de datos.
                  </div>";
            die();
        }
    }catch(Exception $e){
        echo "Error en index.php Raiz: " . $e->getMessage();
    }
}

function login($username, $password) {
    global $pdo;
    $sql = "SELECT id_usuario, nombre, clave FROM usuarios WHERE id_usuario = :username AND estado = 'A'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // Verifica la contraseña usando password_verify
    if ($user && password_verify($password, $user['clave'])) {
        $_SESSION[SESSION_NAME] = $user['id_usuario'];
        $_SESSION['nomb'] = $user['nombre'];
        return true;
    } else {
        return false;
    }
}

// Función para sanitizar la entrada del usuario
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

include_once('./login/frmlogin.php');
?>

