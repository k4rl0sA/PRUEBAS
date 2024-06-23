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

// Función para obtener y exportar datos
function exp_datos($conexion) {
    try {
        // Realizar tu consulta SQL aquí
        $query = "SELECT * FROM tabla_datos"; // Ejemplo de consulta
        
        $statement = $conexion->prepare($query);
        $statement->execute();
        
        // Obtener todos los resultados como un array asociativo
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Crear un archivo Excel
        $excelFile = 'datos_exportados.xls'; // Nombre del archivo Excel

        // Crear el contenido del archivo Excel
        $content = '';
        
        // Encabezados de columna
        $columns = array_keys($results[0]); // Suponiendo que la primera fila contiene los encabezados
        $content .= implode("\t", $columns) . "\n";

        // Datos
        foreach ($results as $data) {
            $content .= implode("\t", $data) . "\n";
        }

        // Encabezados para descargar el archivo
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $excelFile . '"');
        header('Cache-Control: max-age=0');

        // Salida del archivo Excel
        echo $content;
        exit;
        
    } catch (PDOException $e) {
        die("Error al exportar datos: " . $e->getMessage());
    }
}

// Lógica principal para manejar las solicitudes
if (isset($_GET['funcion'])) {
    $funcion = $_GET['funcion'];
    
    $conexion = getConnection(); // Obtener la conexión a la base de datos
    
    switch ($funcion) {
        case 'exp_datos':
            exp_datos($conexion);
            break;
        // Otros casos para otras funciones si las tienes definidas
        default:
            echo "Función no válida";
            break;
    }
}
?>
