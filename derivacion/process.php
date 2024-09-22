<?php
session_start();
ini_set("display_errors", 1);
require_once "../lib/php/gestion.php";
header('Content-Type: application/json');
$response = [
    'status' => 'error',
    'message' => '',
    'progress' => 0
];
$response['message'] = 'Consulta exitosa';
echo json_encode($response);
?>