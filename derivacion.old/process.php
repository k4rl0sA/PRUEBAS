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
    // Se debe usar consultas preparadas para mayor seguridad
    $perfil = datos_mysql("SELECT perfil FROM usuarios WHERE id_usuario='" . $_SESSION["us_sds"] . "'");
    
    if (in_array($perfil['responseResult'][0]['perfil'], ['GEO', 'ADM', 'TECFAM', 'SUPHOG'])) {
        if (isset($_FILES['archivo'])) {
            $file = $_FILES['archivo']['tmp_name'];
            $ext = explode(".", $_FILES['archivo']['name']);
            if (strtolower(end($ext)) == "csv") {
                $handle = fopen($file, "r");
                if ($handle === FALSE) {
                    $response['errors'] = "No se pudo abrir el archivo.";
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
                        if(count($campo) != $ncol){
                            $errors[] = "La fila $nFil tiene un número incorrecto de columnas.";
                            break;
                        }else{
                            $sql = "INSERT INTO " . $tab . " VALUES(";
                            for ($i = 0; $i < $ncol; $i++) {
                                $sql .= ($i + 1 == $ncol) ? "'" . trim($campo[$i]) . "'" : "'" . trim($campo[$i]) . "',";
                            }
                            $sql .= ");";
                            $r = dato_mysql($sql);
                            if (preg_match('/Error/i', $r)) {
                                $errors[] = "Fila $nFil: " . $r;
                            } else {
                                $ok++;
                            }
                        }
                    }else{
                        if(count($campo) != $ncol){
                            $errors[] = "El archivo tiene un número incorrecto de columnas, valida la información";
                            break;
                        }
                    }

                    // Calcular el progreso y enviar actualizaciones al cliente
                    $progress = intval(($nFil / $totalRows) * 100);

                     // Enviar progreso cada 10%
                    if ($progress % 10 === 0 || !empty($errors)) {
                        $response['status'] = 'progress';
                        $response['progress'] = $progress;
                        $response['message'] = "Progreso: $progress%";
                        $response['errors'] = $errors;
                        echo json_encode($response) . "\n";
                        ob_flush();
                        flush();
                        $errors = [];
                    }
                    
                    $nFil++;
                }

                fclose($handle);
                $response['status'] = 'success';
                $response['message'] = "Se han insertado $ok registros correctamente de " . ($totalRows - 1) . " en Total";
                $response['progress'] = 100;
                $response['errors'] = $errors;
                echo json_encode($response) . "\n";
                ob_flush();
                flush();
            } else {
                $response['errors'] = "El archivo tiene una extensión no válida.";
            }
        } else {
            $response['errors'] = "No se ha subido ningún archivo.";
        }
    } else {
        $response['errors'] = "No tiene permisos para realizar esta acción.";
    }
} catch (Throwable $e) {
    $response['errors'] = "Error: " . $e->getMessage();
    echo json_encode($response) . "\n";
    ob_flush();
    flush();
}
echo json_encode($response) . "\n";
?>
