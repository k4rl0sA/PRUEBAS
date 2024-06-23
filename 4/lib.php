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
        $query = "SELECT * FROM usuarios"; // Ejemplo de consulta
        
        $statement = $conexion->prepare($query);
        $statement->execute();
        
        // Obtener todos los resultados como un array asociativo
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Contar el total de registros
        $totalRegistros = count($results);

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

        // Agregar total de registros como última fila
        $content .= "Total de registros:\t" . $totalRegistros . "\n";

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

function getConnection() {
	$env = ($_SERVER['SERVER_NAME']!=='www.siginf-sds.com') ? 'prod' : 'pru' ;
	$comy=array('prod' => ['s'=>'localhost','u' => 'u470700275_06','p' => 'z9#KqH!YK2VEyJpT','bd' => 'u470700275_06'],'pru'=>['s'=>'localhost','u' => 'u470700275_17','p' => 'z9#KqH!YK2VEyJpT','bd' => 'u470700275_17']);
	$dsn = 'mysql:host='.$comy[$env]['s'].';dbname='.$comy[$env]['bd'].';charset=utf8';
	$username = $comy[$env]['u'];
	$password = $comy[$env]['p'];
	$options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	];
	try {
		return new PDO($dsn, $username, $password, $options);
	} catch (PDOException $e) {
		die("Error de conexión: " . $e->getMessage());
	}
  }
