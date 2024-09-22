<?php
ini_set("display_errors", 1);
header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'message' => '',
    'progress' => 0
];

try {
    require_once "../lib/php/gestion.php";
    $perfil = datos_mysql("SELECT perfil FROM usuarios WHERE id_usuario='" . $_SESSION["us_sds"] . "'");
    if (!empty($perfil)) {
        $response['status'] = 'success';
        $response['message'] = 'Consulta exitosa';
    } else {
        $response['message'] = 'No se encontró el perfil del usuario';
    }
} catch (Throwable $e) {
    // Capturamos cualquier error en la carga del archivo gestion.php
    $response['message'] = 'Error al cargar gestion.php: ' . $e->getMessage();
}
echo json_encode($response);
?>