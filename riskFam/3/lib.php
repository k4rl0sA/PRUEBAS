<?php
header('Content-Type: application/json');

// Datos simulados
$data = [
    [
        "document" => "123456789",
        "sex" => "Masculino",
        "gender" => "Hombre",
        "nationality" => "Colombiana",
        "birthDate" => "1990-05-15",
        "age" => 33
    ],
    [
        "document" => "987654321",
        "sex" => "Femenino",
        "gender" => "Mujer",
        "nationality" => "Mexicana",
        "birthDate" => "1985-12-01",
        "age" => 37
    ],
    [
        "document" => "456789123",
        "sex" => "Masculino",
        "gender" => "Otro",
        "nationality" => "Argentina",
        "birthDate" => "2000-07-20",
        "age" => 22
    ],
    [
        "document" => "321654987",
        "sex" => "Femenino",
        "gender" => "Mujer",
        "nationality" => "Española",
        "birthDate" => "1978-03-10",
        "age" => 45
    ],
    [
        "document" => "789123456",
        "sex" => "Masculino",
        "gender" => "Hombre",
        "nationality" => "Venezolana",
        "birthDate" => "1995-11-05",
        "age" => 27
    ]
];

$randomIndex = array_rand($data);
echo json_encode($data[$randomIndex]);
?>
