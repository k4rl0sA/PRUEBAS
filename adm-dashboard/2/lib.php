<?php
header('Content-Type: application/json');

// Simulación de datos, reemplaza por tus consultas reales
$data = [
    "totalPatients" => 3553128,
    "totalFamilies" => 1570758,
    "pregnantWomen" => 25933,
    "monthlyConsultations" => 89456,
    "lastUpdate" => "hace 1 hora",
    // Distribución por edad (ejemplo)
    "ageDistribution" => [
        "labels" => ["0-5", "6-17", "18-29", "30-44", "45-59", "60+"],
        "values" => [341866, 800000, 900000, 700000, 600000, 659383]
    ],
    // Distribución por género (ejemplo)
    "genderDistribution" => [
        "labels" => ["Femenino", "Masculino", "Otro"],
        "values" => [1800000, 1750000, 53128]
    ],
    // Consultas por especialidad (ejemplo)
    "specialtyConsultations" => [
        "labels" => ["Medicina General", "Pediatría", "Ginecología", "Odontología", "Enfermería"],
        "values" => [40000, 15000, 12000, 8000, 14456]
    ],
    // Indicadores de salud
    "healthIndicators" => [
        "vacunacionCompleta" => 87,
        "controlPrenatal" => 92,
        "tamizajeCancer" => 74,
        "hipertensionControlada" => 68
    ],
    // Personas con discapacidad
    "disability" => [
        "total" => 155409,
        "percentage" => 4.37,
        "distribution" => [
            "labels" => ["Visual", "Auditiva", "Motora", "Cognitiva", "Otra"],
            "values" => [40000, 30000, 50000, 20000, 15409]
        ]
    ],
    // Menores de 5 años
    "under5" => [
        "total" => 341866,
        "percentage" => 9.62,
        "breakdown" => [
            ["label" => "0-1 años", "value" => 68373],
            ["label" => "2-3 años", "value" => 136746],
            ["label" => "4-5 años", "value" => 136747]
        ]
    ],
    // Mayores de 60 años
    "elderly" => [
        "total" => 659383,
        "percentage" => 18.55,
        "distribution" => [
            "labels" => ["60-69", "70-79", "80+"],
            "values" => [300000, 250000, 109383]
        ]
    ],
    // Actividad reciente
    "recentActivity" => [
        [
            "type" => "success",
            "title" => "Campaña de vacunación completada",
            "time" => "Hace 2 horas"
        ],
        [
            "type" => "warning",
            "title" => "Alerta: Aumento de casos respiratorios",
            "time" => "Hace 4 horas"
        ],
        [
            "type" => "info",
            "title" => "Nuevo reporte mensual disponible",
            "time" => "Hace 6 horas"
        ]
    ]
];

echo json_encode($data);