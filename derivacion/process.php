<?php
session_start();
ini_set("display_errors", 1);
header('Content-Type: application/json');
$response = [
    'status' => 'error',
    'message' => '',
    'progress' => 0
];
$response['message'] = 'Consulta exitosa';
echo json_encode($response);
?>