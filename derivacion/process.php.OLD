<?php
session_start();
ini_set("display_errors", 1);
// require_once "../lib/php/gestion.php";

// Inicia el buffer de salida
ob_start();

header('Content-Type: application/json');

// Inicializa la respuesta con error por defecto
$response = [
    'status' => 'error',
    'message' => '',
    'progress' => 0
];

// Simula la consulta del perfil del usuario
// $perfil = datos_mysql("SELECT perfil FROM usuarios WHERE id_usuario='" . $_SESSION["us_sds"] . "'");

// Verifica si la consulta fue exitosa y actualiza el mensaje
/* if (!empty($perfil)) {
    $response['status'] = 'success';
    $response['message'] = 'Consulta exitosa';
} else {
    $response['message'] = 'No se encontró el perfil del usuario';
}
 */
$response['message'] = 'Consulta exitosa';

// Captura cualquier salida previa
$output = ob_get_clean();

// Si hay alguna salida previa, la incluimos en el mensaje para depuración
/* if (!empty($output)) {
    $response['message'] = 'Error del servidor: ' . $output;
} */

// Envía la respuesta JSON
echo json_encode($response);
?>


/* if (in_array($perfil['responseResult'][0]['perfil'], ['GEO', 'ADM', 'TECFAM', 'SUPHOG'])) {
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
            $totalRows = count(file($file));  // Contamos las filas para obtener el total de registros

            $_SESSION['progress'] = 0; // Inicializamos el progreso en la sesión

            if ($ope == 'insert') {
                while (($campo = fgetcsv($handle, 1024, $delimit)) !== false) {
                    if ($nFil !== 1) {
                        $sql = "INSERT INTO " . $tab . " VALUES(";
                        for ($i = 0; $i < $ncol; $i++) {
                            $sql .= ($i + 1 == $ncol) ? ($campo[$i] != 'NULL' ? "'" . trim($campo[$i]) . "'" : "NULL") : ($campo[$i] != 'NULL' ? "'" . trim($campo[$i]) . "'," : "NULL,");
                        }
                        $sql .= ");";
                        $r = dato_mysql($sql);

                        if (!preg_match('/Error/i', $r)) {
                            $ok++;
                        }
                    }
                    // Actualizamos el progreso en cada fila procesada
                    $_SESSION['progress'] = intval(($nFil / $totalRows) * 100);
                    $nFil++;
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
} */

