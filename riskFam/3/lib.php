<?php
header('Content-Type: application/json');

// Datos personales simulados
$personal = [
    "123456789" => [
        "sex" => "Masculino",
        "gender" => "Hombre",
        "nationality" => "Colombiana",
        "birthDate" => "1990-05-15",
        "lifestage" => "Adulto",
        "age" => 33,
        "location" => "Localidad 1",
        "upz" => "UPZ 101",
        "address" => "Calle 123 #45-67",
        "phone" => "3001234567"
    ],
    "987654321" => [
        "sex" => "Femenino",
        "gender" => "Mujer",
        "nationality" => "Mexicana",
        "birthDate" => "1985-12-01",
        "lifestage" => "Adulto",
        "age" => 38,
        "location" => "Localidad 2",
        "upz" => "UPZ 202",
        "address" => "Carrera 89 #12-34",
        "phone" => "3102345678"
    ],
    "80811594"=> [
        "sex" => "Masculino",
        "gender" => "Hombre",
        "nationality" => "Colombiana",
        "birthDate" => "1990-05-15",
        "lifestage" => "Adulto",
        "age" => 33
    ],
    "987654321"=>[
        "sex" => "Femenino",
        "gender" => "Mujer",
        "nationality" => "Mexicana",
        "birthDate" => "1985-12-01",
        "lifestage" => "Adulto",
        "age" => 38
    ],
    "456789123"=>[
        "sex" => "Masculino",
        "gender" => "Hombre",
        "nationality" => "Argentina",
        "birthDate" => "2002-07-20",
        "lifestage" => "Joven",
        "age" => 21
    ],
    "321654987"=>[
        "sex" => "Femenino",
        "gender" => "Mujer",
        "nationality" => "Española",
        "birthDate" => "1978-03-10",
        "lifestage" => "Adulto Mayor",
        "age" => 45
    ],
    "654987321"=>[
        "sex" => "Masculino",
        "gender" => "Hombre",
        "nationality" => "Chilena",
        "birthDate" => "1995-11-25",
        "lifestage" => "Adulto",
        "age" => 28
    ],
    "789123456"=>[
        "sex" => "Femenino",
        "gender" => "Mujer",
        "nationality" => "Peruana",
        "birthDate" => "2010-09-03",
        "lifestage" => "Niño",
        "age" => 13
    ]
];

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

// Seleccionar un registro personal aleatorio
$randomIndex = array_rand($personal);
$selectedPerson = $personal[$randomIndex];

// Combinar datos personales y factores de riesgo en un solo objeto
$response = [
    // Datos personales
    "document" => $selectedPerson["document"],
    "sex" => $selectedPerson["sex"],
    "gender" => $selectedPerson["gender"],
    "nationality" => $selectedPerson["nationality"],
    "birthDate" => $selectedPerson["birthDate"],
    "lifestage" => $selectedPerson["lifestage"],
    "age" => $selectedPerson["age"],
    
    // Factores de riesgo
    "riskFactors" => $riesgos
];

// Devolver la respuesta JSON
echo json_encode($response);
?>