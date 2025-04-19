<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
session_start();
require_once '../libs/gestion.php';
ini_set('display_errors','1');
setlocale(LC_TIME, 'es_CO');
date_default_timezone_set('America/Bogota');
setlocale(LC_ALL,'es_CO');

$APP='GTAPS';
if (!isset($_SESSION["us_sds"])) {
  header("Location: /index.php"); 
  exit;
}

// Configuración de la base de datos (tu código existente)
// ... [mantén tu configuración de conexión existente] ...

// Nuevo caso para manejar solicitudes JSON
$req = (isset($_REQUEST['a'])) ? $_REQUEST['a'] : '';
switch ($req) {
    case 'get_person_data':
        header('Content-Type: application/json');
        $document = $_GET['document'] ?? null;
        if ($document) {
            $personData = get_person_data($document);
            $riskFactors = get_risk_factors($document);
            
            if ($personData) {
                $response = array_merge(
                    ["document" => $document],
                    $personData,
                    ["riskFactors" => $riskFactors]
                );
            } else {
                $response = [
                    "error" => "Documento no encontrado",
                    "document" => $document
                ];
            }
        } else {
            $response = [
                "error" => "Documento no proporcionado"
            ];
        }
        echo json_encode($response);
        exit;
        break;
        
    // ... [mantén tus otros casos existentes] ...
}

// Función para obtener datos personales desde la base de datos
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
    
    if (!empty($data['responseResult'])) {
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
        $lifestage = 'Adulto';
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
            "fullName" => trim("{$person['nombre1']} {$person['nombre2']} {$person['apellido1']} {$person['apellido2']}")
        ];
    }
    return null;
}
// Factores de riesgo con valores aleatorios
$riesgos = [
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
// Obtener documento de la solicitud
$document = $_GET['document'] ?? null;

if ($document && isset($personal[$document])) {
    $response = array_merge(
        ["document" => $document],
        $personal[$document],
        ["riskFactors" => $riesgos]
    );
} else {
    $response = [
        "error" => "Documento no encontrado",
        "document" => $document
    ];
}

echo json_encode($response);
?>