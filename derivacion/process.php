<?php
ini_set("display_errors", 1); 
header('Content-Type: application/json');

// Enviar headers para desactivar buffering en algunos navegadores
header('X-Accel-Buffering: no'); // Para NGINX
header('Cache-Control: no-cache'); // Para asegurar que el cliente no cachee la respuesta
header('Connection: keep-alive');

// Iniciar el buffer de salida
ob_clean();
ob_start();

$response = [
    'status' => 'error',
    'message' => '',
    'progress' => 0,
    'errors' => []
];
try {
    require_once "../lib/php/gestion.php";
    $perfil = datos_mysql("SELECT perfil FROM usuarios WHERE id_usuario='" . $_SESSION["us_sds"] . "'");
    
    if (in_array($perfil['responseResult'][0]['perfil'], ['GEO', 'ADM', 'TECFAM', 'SUPHOG'])) {
        if (isset($_FILES['archivo'])) {
            $file = $_FILES['archivo']['tmp_name'];
            $ext = explode(".", $_FILES['archivo']['name']);
            if (strtolower(end($ext)) == "csv") {
                $handle = fopen($file, "r");
                if ($handle === FALSE) {
                    $response['message'] = "No se pudo abrir el archivo.";
                    echo json_encode($response);
                    exit;
                }

                $nFil = 1; 
                $ok = 0;
                $errors = [];
                $ncol = $_POST['ncol'];
                $tab = $_POST['tab'];
                $totalRows = count(file($file));
                while(($campo = fgetcsv($handle, 1024, ",")) !== false) {
                    if ($nFil !== 1) {
                        $sql = "INSERT INTO " . $tab . " VALUES(";
                        for ($i = 0; $i < $ncol; $i++) {
                            $sql .= ($i + 1 == $ncol) ? "'" . trim($campo[$i]) . "'" : "'" . trim($campo[$i]) . "',";
                        }
                        $sql .= ");";
                        $r = dato_mysql($sql);
                        if (preg_match('/Error/i', $r)) {
                            $errors[] = "Error en la fila $nFil: " . $r;
                        } else {
                            $ok++;
                        }
                    }

                    // Calcular el progreso y enviar actualizaciones al cliente
                    $progress = intval(($nFil / $totalRows) * 100);

                     // Enviar progreso cada 10%
                    if ($progress % 10 === 0) {
                        $response['status'] = 'progress';
                        $response['progress'] = $progress;
                        $response['message'] = "Progreso: $progress%";
                    
                        // Añadir un delimitador (nueva línea) después de cada JSON
                        echo json_encode($response) . "\n"; // Añadir "\n" para facilitar el parseo en el cliente
                        ob_flush();
                        flush();
                    }
                    
                    $nFil++;
                }

                fclose($handle);
                $response['status'] = 'success';
                $response['message'] = "Se han insertado $ok registros correctamente.";
                $response['progress'] = 100;
                echo json_encode($response) . "\n"; // Enviar respuesta final
                ob_flush();
                flush();
            } else {
                $response['message'] = "El archivo tiene una extensión no válida.";
            }
        } else {
            $response['message'] = "No se ha subido ningún archivo.";
        }
    } else {
        $response['message'] = "No tiene permisos para realizar esta acción.";
    }
} catch (Throwable $e) {
    $response['message'] = "Error: " . $e->getMessage();
    echo json_encode($response) . "\n";
    ob_flush();
    flush();
}
?>