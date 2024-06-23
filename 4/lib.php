<?php
session_start();
ini_set('display_errors', '1');
setlocale(LC_TIME, 'es_CO');
ini_set('memory_limit', '1024M');
date_default_timezone_set('America/Bogota');
setlocale(LC_ALL, 'es_CO');

if (!isset($_SESSION["us_sds"])) {
    http_response_code(302);
    header("Location: /index.php");
    exit();
}

// Función para exportar datos según el parámetro recibido
function exp_datos($conexion) {
    try {
        // Aquí deberías realizar la consulta o proceso necesario para obtener los datos a exportar
        $query = "SELECT * FROM usuarios"; // Ejemplo de consulta
        
        $statement = $conexion->prepare($query);
        $statement->execute();
        
        // Preparar los datos para exportar (aquí puedes usar PHPExcel o alguna librería similar para generar el archivo Excel)
        // En este ejemplo, simplemente se retorna un mensaje para fines ilustrativos
        return "Datos exportados correctamente";
        
    } catch (PDOException $e) {
        return "Error al exportar datos: " . $e->getMessage();
    }
}

// Lógica principal para manejar las solicitudes
if (isset($_GET['funcion'])) {
    $funcion = $_GET['funcion'];
    
    $conexion = getConnection(); // Obtener la conexión a la base de datos
    
    switch ($funcion) {
        case 'exp_datos':
            $resultado = exp_datos($conexion);
            if (is_string($resultado)) {
                // Aquí se podría manejar el resultado de la función, por ejemplo, enviar un mensaje JSON con el resultado
                echo json_encode(array('mensaje' => $resultado));
            }
            break;
        // Otros casos para otras funciones si las tienes definidas
        default:
            echo "Función no válida";
            break;
    }
}
?>
