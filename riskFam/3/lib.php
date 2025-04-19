<?php
// Configuración inicial
ini_set('display_errors', 0); // Desactivar visualización de errores en producción
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/php_errors.log');

// Headers para respuesta JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Validar autenticación si es necesario
session_start();
if (!isset($_SESSION["us_sds"])) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

// Conexión a la base de datos (usando tu configuración existente)
require_once '../libs/gestion.php';

// Función principal
try {
    // Validar parámetro document
    if (!isset($_GET['document']) || empty($_GET['document'])) {
        throw new Exception('Documento no proporcionado');
    }

    $document = $_GET['document'];
    
    // Validar formato del documento (ajusta según tus necesidades)
    if (!is_numeric($document)) {
        throw new Exception('Documento no válido');
    }

    // Obtener datos personales
    $personData = get_person_data($document);
    
    if (!$personData) {
        throw new Exception('Documento no encontrado');
    }

    // Obtener factores de riesgo
    $riskFactors = get_risk_factors($document);

    // Preparar respuesta exitosa
    $response = [
        'success' => true,
        'document' => $document,
        'person' => $personData,
        'riskFactors' => $riskFactors
    ];

    echo json_encode($response);

} catch (Exception $e) {
    // Manejo de errores
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

/**
 * Obtiene datos personales desde la base de datos
 */
function get_person_data($document) {
    $sql = "SELECT 
                nombre1, nombre2, apellido1, apellido2, 
                fecha_nacimiento, sexo, genero, nacionalidad,
                telefono1, telefono2, correo,
                localidad_vive
            FROM `person` 
            WHERE idpersona = ?";
    
    $params = [['type' => 's', 'value' => $document]];
    $data = datos_mysql($sql, $params);
    
    if (empty($data['responseResult'])) {
        return null;
    }

    $person = $data['responseResult'][0];
    
    // Mapear sexo
    $sexMap = ['M' => 'Masculino', 'F' => 'Femenino'];
    $sex = $sexMap[$person['sexo']] ?? $person['sexo'];
    
    // Mapear género
    $genderMap = ['H' => 'Hombre', 'M' => 'Mujer'];
    $gender = $genderMap[$person['genero']] ?? $person['genero'];
    
    // Calcular edad
    $birthDate = new DateTime($person['fecha_nacimiento']);
    $today = new DateTime();
    $age = $birthDate->diff($today)->y;
    
    // Determinar etapa de vida
    if ($age < 12) $lifestage = 'Niño';
    elseif ($age < 18) $lifestage = 'Adolescente';
    elseif ($age < 60) $lifestage = 'Adulto';
    else $lifestage = 'Adulto Mayor';
    
    return [
        "sex" => $sex,
        "gender" => $gender,
        "nationality" => $person['nacionalidad'],
        "birthDate" => $person['fecha_nacimiento'],
        "lifestage" => $lifestage,
        "age" => $age,
        "location" => $person['localidad_vive'],
        "phone" => $person['telefono1'],
        "email" => $person['correo'],
        "fullName" => trim("{$person['nombre1']} {$person['nombre2']} {$person['apellido1']} {$person['apellido2']}"),
        "upz" => "UPZ-".rand(100, 199), // Ejemplo - reemplaza con datos reales
        "address" => "Calle ".rand(1, 200)." #".rand(1, 100)."-".rand(1, 100) // Ejemplo - reemplaza con datos reales
    ];
}

/**
 * Obtiene factores de riesgo (versión básica)
 */
function get_risk_factors() {
    return [
        "socioeconomic" => [
            "name" => "Nivel Socioeconómico",
            "value" => rand(0, 100),
            "weight" => 0.18,
            "description" => "Impacta directamente el acceso a bienes y servicios esenciales."
        ],
        "familyStructure" => [
            "name" => "Estructura Familiar",
            "value" => rand(0, 100),
            "weight" => 0.20,
            "description" => "Influye en el apoyo social, la funcionalidad y la estabilidad del hogar."
        ],
        "socialVulnerability" => [
            "name" => "Vulnerabilidad Social",
            "value" => rand(0, 100),
            "weight" => 0.12,
            "description" => "Considera factores como la violencia, el desplazamiento y la exclusión social."
        ],
        "accessToHealth" => [
            "name" => "Acceso a Servicios de Salud",
            "value" => rand(0, 100),
            "weight" => 0.10,
            "description" => "Clave para la prevención y el cuidado de enfermedades."
        ],
        "livingEnvironment" => [
            "name" => "Entorno Habitacional",
            "value" => rand(0, 100),
            "weight" => 0.10,
            "description" => "Evalúa las condiciones de la vivienda y su impacto en la salud."
        ],
        "demographics" => [
            "name" => "Características Demográficas",
            "value" => rand(0, 100),
            "weight" => 0.30,
            "description" => "Incluye edad, género y otras variables que influyen en la exposición al riesgo."
        ]
    ];
}
?>