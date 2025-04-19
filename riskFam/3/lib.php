<?php
// Configuración inicial
ini_set('display_errors', 1); // Mostrar errores para depuración
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/php_errors.log');

// Headers para respuesta JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Iniciar sesión (si es necesario)
session_start();

// Verificar si el script se está llamando directamente
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    // Validar autenticación si es necesario
    if (!isset($_SESSION["us_sds"])) {
        echo json_encode(['error' => 'No autenticado']);
        exit;
    }
}

// Incluir archivo de gestión de base de datos
require_once __DIR__.'/../libs/gestion.php';

// Función principal
try {
    // Validar parámetro document
    if (!isset($_GET['document']) || empty($_GET['document'])) {
        throw new Exception('Documento no proporcionado');
    }

    $document = $_GET['document'];
    
    // Validar formato del documento
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
    // Verificar conexión a la base de datos
    if (!isset($GLOBALS['con']) || !$GLOBALS['con']) {
        throw new Exception("Error de conexión a la base de datos");
    }

    $sql = "SELECT 
                nombre1, nombre2, apellido1, apellido2, 
                fecha_nacimiento, sexo, genero, nacionalidad,
                telefono1, telefono2, correo,
                localidad_vive
            FROM `person` 
            WHERE idpersona = ?";
    
    $stmt = $GLOBALS['con']->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparando consulta: ".$GLOBALS['con']->error);
    }
    
    $stmt->bind_param("s", $document);
    if (!$stmt->execute()) {
        throw new Exception("Error ejecutando consulta: ".$stmt->error);
    }
    
    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception("Error obteniendo resultados: ".$stmt->error);
    }
    
    $person = $result->fetch_assoc();
    $stmt->close();
    
    if (!$person) {
        return null;
    }
    
    // Procesar datos
    $sexMap = ['M' => 'Masculino', 'F' => 'Femenino'];
    $genderMap = ['H' => 'Hombre', 'M' => 'Mujer'];
    
    $birthDate = new DateTime($person['fecha_nacimiento']);
    $today = new DateTime();
    $age = $birthDate->diff($today)->y;
    
    // Determinar etapa de vida
    if ($age < 12) $lifestage = 'Niño';
    elseif ($age < 18) $lifestage = 'Adolescente';
    elseif ($age < 60) $lifestage = 'Adulto';
    else $lifestage = 'Adulto Mayor';
    
    return [
        "sex" => $sexMap[$person['sexo']] ?? $person['sexo'],
        "gender" => $genderMap[$person['genero']] ?? $person['genero'],
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