<?php
header('Content-Type: application/json');
require_once "../gestion.php";

// Conexión a la base de datos
$conn = conDB(); // Asumiendo que `conDB()` está en gestion.php

// Obtener documento de la solicitud
$document = $_GET['document'] ?? null;

// Validar que se haya enviado el documento
if (!$document) {
    echo json_encode(["error" => "Documento no proporcionado"]);
    exit;
}

// Consultar datos personales desde la tabla `person`
$sql = "SELECT sex, gender, nationality, birthDate, lifestage, age, location, upz, address, phone 
        FROM person 
        WHERE document = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $document);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "error" => "Documento no encontrado",
        "document" => $document
    ]);
    exit;
}

$data = $result->fetch_assoc();

// Generar factores de riesgo aleatorios (como en el ejemplo original)
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
// Armar la respuesta
$response = array_merge(
    ["document" => $document],
    $data,
    ["riskFactors" => $riesgos]
);
echo json_encode($response);
