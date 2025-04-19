<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Cargar config y funciones necesarias
require_once __DIR__ . '/../../libs/gestion.php';
// Obtener el documento desde la URL
$document = $_GET['document'] ?? null;
if (!$document) {
    echo json_encode(["error" => "Documento no proporcionado."]);
    exit;
}
// Consultar datos personales desde la tabla person
$sql = "SELECT 
    idpersona AS document,
    FN_CATALOGODESC(21,sexo) AS sex,
    FN_CATALOGODESC(19,genero) AS gender,
    FN_CATALOGODESC(30,nacionalidad) AS nationality,
    fecha_nacimiento AS birthDate,
    TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS age,
    CASE 
        WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 0 AND 5 THEN 'PRIMERA INFANCIA'
        WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 6 AND 11 THEN 'INFANCIA'
        WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 12 AND 17 THEN 'ADOLESCENCIA'
        WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 18 AND 28 THEN 'JUVENTUD'
        WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 29 AND 59 THEN 'ADULTEZ'
        WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) >= 60 THEN 'VEJEZ'
        ELSE ''
    END AS life_course,
    CONCAT_WS(' - ',G.localidad,FN_CATALOGODESC(2,G.localidad)) AS location,
    G.upz,
    G.direccion AS address,
    P.telefono1 AS phone
FROM person P
LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
LEFT JOIN hog_geo G ON F.idpre = G.idgeo
WHERE idpersona = '$document' 
        LIMIT 1";
$res = datos_mysql($sql);
if ($res['code'] !== 0 || empty($res['responseResult'])) {
    echo json_encode(["error" => "Documento no encontrado", "document" => $document]);
    exit;
}
// Datos de la persona
$datos = $res['responseResult'][0];
// Generar factores de riesgo aleatorios
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
// Devolver respuesta
echo json_encode(array_merge($datos, ["riskFactors" => $riesgos]));
