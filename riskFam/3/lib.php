<?php
header('Content-Type: application/json');

// Datos simulados
$personal = [
    [
        "document" => "123456789",
        "sex" => "Masculino",
        "gender" => "Hombre",
        "nationality" => "Colombiana",
        "birthDate" => "1990-05-15",
        "lifestage" => "Adulto",
        "age" => 33
    ]
];

$riesgos = [
    "socioeconomic" => [
        "name" => "Nivel Socioeconómico",
        "value" => rand(0, 100),
        "weight" => 0.20,
        "description" => "Impacta directamente el acceso a bienes y servicios esenciales."
    ],
    "familyStructure" => [
        "name" => "Estructura Familiar",
        "value" => rand(0, 100),
        "weight" => 0.15,
        "description" => "Influye en el apoyo social, la funcionalidad y la estabilidad del hogar."
    ],
    "healthConditions" => [
        "name" => "Condiciones de Salud",
        "value" => rand(0, 100),
        "weight" => 0.20,
        "description" => "Determina la calidad de vida y el acceso al tratamiento médico."
    ],
    "socialVulnerability" => [
        "name" => "Vulnerabilidad Social",
        "value" => rand(0, 100),
        "weight" => 0.15,
        "description" => "Considera factores como la violencia, el desplazamiento y la exclusión social."
    ],
    "accessToHealth" => [
        "name" => "Acceso a Servicios de Salud",
        "value" => rand(0, 100),
        "weight" => 0.10,
        "description" => "Clave para la prevención y el cuidado de enfermedades."
    ],
    "livingEnvironment" => [
        "name" => "Entorno de Vida",
        "value" => rand(0, 100),
        "weight" => 0.10,
        "description" => "Evalúa las condiciones de la vivienda y su impacto en la salud."
    ],
    "demographics" => [
        "name" => "Características Demográficas",
        "value" => rand(0, 100),
        "weight" => 0.10,
        "description" => "Incluye edad, género y otras variables que influyen en la exposición al riesgo."
    ]
];

$randomIndex = array_rand($personal);
echo json_encode($personal[$randomIndex]);
?>
