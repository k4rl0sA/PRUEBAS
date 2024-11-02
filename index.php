<?php
session_start();
require_once 'config.php';
require_once 'gestion.php';
ini_set('display_errors', '1');
// Procesa el formulario cuando se envía por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = test_input($_POST['username']);
    $pwd = $_POST['passwd'];
    // Llama a la función de autenticación
    if (login($name, $pwd)) {
        $_SESSION["us_sds"] = strtolower($name);
        // Verifica si la contraseña es la predeterminada para forzar cambio
        if ($pwd === "riesgo2020+") {
            header("Location: cambio-clave.php");
        } else {
            header("Location: main.php");
        }
        exit();
    } else {
        echo "<div class='error'>
                <span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span> 
                <strong>Error!</strong> Vaya, no hemos encontrado nada que coincida con este nombre de usuario y contraseña en nuestra base de datos.
              </div>";
        die();
    }
}

// Función para sanitizar la entrada del usuario
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

include_once('./login/frmlogin.php');
?>

