<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config.php'; // Asegúrate de que el archivo de configuración sea correcto

try {
    $pdo = new PDO("mysql:host={$dbConfig['s']};dbname={$dbConfig['bd']}", $dbConfig['u'], $dbConfig['p']);
    echo "Conexión exitosa";
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
