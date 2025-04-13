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
    ]
];

$randomIndex = array_rand($data);
echo json_encode($data[$randomIndex]);
?>
