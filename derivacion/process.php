<?php 
ini_set("display_errors", 1); 
header('Content-Type: application/json');
$response = [ 'status' => 'error', 'message' => '', 'progress' => 0 ];

try { 
    require_once "../lib/php/gestion.php";
    $perfil = datos_mysql("SELECT perfil FROM usuarios WHERE id_usuario='" . $_SESSION["us_sds"] . "'");
    
    if (in_array($perfil['responseResult'][0]['perfil'], ['GEO', 'ADM', 'TECFAM', 'SUPHOG'])) { 
        if (isset($_FILES['archivo'])) {
            $file = $_FILES['archivo']['tmp_name'];
            $name = $_FILES['archivo']['name'];
            $type = $_FILES['archivo']['type'];
            $size = $_FILES['archivo']['size'];
            $ext = explode(".", $name);
            $delimit = ",";
            
            if (strtolower(end($ext)) == "csv") { 
                $handle = fopen($file, "r"); 
                
                if ($handle === FALSE) {
                    $response['message'] = "No se pudo abrir el archivo " . $name; 
                    echo json_encode($response);
                    exit;
                }
                
                $nFil = 1; 
                $ok = 0; 
                $ncol = $_POST['ncol'];
                $tab = $_POST['tab'];
                $ope = isset($_POST['ope']) ? $_POST['ope'] : 'insert'; 
                $totalRows = count(file($file)); // Contamos las filas para obtener el total de registros 
                
                if ($ope == 'insert') {
                    while (($campo = fgetcsv($handle, 1024, $delimit)) !== false) {
                        if ($nFil !== 1) {
                            $sql = "INSERT INTO " . $tab . " VALUES("; 
                            
                            for ($i = 0; $i < $ncol; $i++) {
                                $sql .= ($i + 1 == $ncol) 
                                    ? ($campo[$i] != 'NULL' ? "'" . trim($campo[$i]) . "'" : "NULL") 
                                    : ($campo[$i] != 'NULL' ? "'" . trim($campo[$i]) . "'," : "NULL,");
                            } 
                            
                            $sql .= ");"; 
                            $r = dato_mysql($sql);
                            
                            if (!preg_match('/Error/i', $r)) {
                                $ok++;
                            }
                        }
                        
                        $progress = intval(($nFil / $totalRows) * 100);
                        $_SESSION['progress'] = $progress;
                        $nFil++;
                        
                        // Enviar progreso cada 10%
                       /*  if ($progress % 10 == 0) {
                            $response['status'] = 'progress';
                            $response['progress'] = $progress;
                            echo json_encode($response);
                            ob_flush();
                            flush();
                        } */
                    }
                    
                    fclose($handle);
                    $response['status'] = 'success';
                    $response['message'] = "Se han insertado " . $ok . " Registro(s) correctamente.";
                    $response['progress'] = 100;
                }
            } else {
                $response['message'] = "El archivo contiene una extensión inválida: " . strtolower(end($ext));
            }
        } else {
            $response['message'] = "No se ha subido ningún archivo.";
        }
    } else {
        $response['message'] = "No tiene el perfil permitido para cargar el archivo CSV.";
    }
    
} catch (Throwable $e) {
    $response['message'] = 'Error al cargar gestion.php: ' . $e->getMessage(); 
}

echo json_encode($response);
