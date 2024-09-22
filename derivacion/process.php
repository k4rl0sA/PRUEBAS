<?php
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'message' => '',
    'progress' => 0
];

try {
    require_once "../lib/php/gestion.php";
    
    // Si no hay errores hasta aquí, podrías continuar procesando.
    $response['status'] = 'success';
    $response['message'] = 'Consulta exitosa';
} catch (Throwable $e) {
    // Capturamos cualquier error en la carga del archivo gestion.php
    $response['message'] = 'Error al cargar gestion.php: ' . $e->getMessage();
}

echo json_encode($response);
?>
