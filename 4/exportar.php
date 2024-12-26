<?php
session_start();
ini_set('display_errors','1');
setlocale(LC_TIME, 'es_CO');
ini_set('memory_limit','1024M');
date_default_timezone_set('America/Bogota');
setlocale(LC_ALL,'es_CO');

if (!isset($_SESSION["us_sds"])) {
    http_response_code(302);
    header("Location: /index.php"); 
    exit();
}

function getConnection() {
    $env='prod';
    $comy=array('prod' => ['s'=>'localhost','u' => 'u470700275_17','p' => 'z9#KqH!YK2VEyJpT','bd' => 'u470700275_17']);
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

function exportarDatos($sql) {
    $con = getConnection();
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll();
    $totalRegistros = count($resultados);
    if ($totalRegistros > 0) {
        $resultados[] = ["Total de registros" => $totalRegistros];
    } else {
        $resultados[] = ["Total de registros" => 0];
    }
    return $resultados;
}

// Consulta SQL de ejemplo
$sql = "SELECT id_usuario, nombre, clave, correo FROM usuarios";
$datos = exportarDatos($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=datos.xls");
header("Pragma: no-cache");
header("Expires: 0");

$separator = "\t";

// Imprimir nombres de columnas
echo "ID" . $separator . "Nombre" . $separator . "Apellido" . $separator . "Email" . "\n";

// Imprimir filas de datos
foreach ($datos as $row) {
    echo implode($separator, array_values($row)) . "\n";
}
?>
