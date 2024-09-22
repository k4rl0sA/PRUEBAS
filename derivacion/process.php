ini_set("display_errors", 1); 
header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'message' => '',
    'progress' => 0,
    'errors' => []
];

try {
    require_once "../lib/php/gestion.php";  // Conexión y funciones de la BD

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
                $totalRows = count(file($file));  // Contamos todas las filas para conocer el total

                // Iniciar la inserción fila por fila
                while(($campo = fgetcsv($handle, 1024, ",")) !== false) {
                    if ($nFil !== 1) { // Saltar la cabecera
                        $sql = "INSERT INTO " . $tab . " VALUES(";
                        for ($i = 0; $i < $ncol; $i++) {
                            $sql .= ($i + 1 == $ncol) ? "'" . trim($campo[$i]) . "'" : "'" . trim($campo[$i]) . "',";
                        }
                        $sql .= ");";

                        $r = dato_mysql($sql); // Ejecuta la consulta
                        if (preg_match('/Error/i', $r)) {
                            $errors[] = "Error en la fila $nFil: " . $r;  // Guarda el error específico de la fila
                        } else {
                            $ok++;
                        }
                    }

                    // Calcular el progreso
                    $progress = intval(($nFil / $totalRows) * 100);
                    $nFil++;

                    // Enviar progreso cada 10%
                    if ($progress % 10 == 0) {
                        $response['status'] = 'progress';
                        $response['progress'] = $progress;
                        $response['errors'] = $errors;
                        echo json_encode($response);
                        ob_flush();
                        flush();
                    }
                }
                fclose($handle);
                
                $response['status'] = 'success';
                $response['message'] = "Se han insertado $ok registros correctamente.";
                $response['errors'] = $errors;
                $response['progress'] = 100;
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
}

echo json_encode($response);
