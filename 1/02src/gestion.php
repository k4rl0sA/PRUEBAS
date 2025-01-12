<?php
require_once __DIR__ . '/../01config/config.php';
ini_set('memory_limit','1024M');
// Verificar si la sesión ya ha sido iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si la sesión está activa
if (!isset($_SESSION["us_subred"])) {
    header("Location: /index.php"); // Redirigir si no hay sesión activa
    exit;
}

$sesion = $_SESSION["us_subred"];

function db_connect() {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if ($con->connect_error) {
        throw new Exception('Error en la conexión a la base de datos: ' . $con->connect_error);
    }
    return $con;
}

$req = $_REQUEST['a'] ?? '';

switch ($req) {
    case 'exportar':
        exportarDatos();
        break;
    default:
        // Manejar otros casos si es necesario
        break;
}

function exportarDatos() {
    $now = date("ymd");
    header_csv($_REQUEST['b'] . '_' . $now . '.csv');
    $info = datos_mysql($_SESSION['tot_' . $_REQUEST['b']]);
    $total = $info['responseResult'][0]['total'] ?? 0; // Manejar caso si no hay resultados

    if ($rs = mysqli_query($GLOBALS['con'], $_SESSION['sql_' . $_REQUEST['b']])) {
        $ts = mysqli_fetch_array($rs, MYSQLI_ASSOC);
        echo csv($ts, $rs, $total);
    } else {
        echo "Error " . $GLOBALS['con']->errno . ": " . $GLOBALS['con']->error;
        $GLOBALS['con']->close();
    }
}


function datos_mysql($sql,$resulttype = MYSQLI_ASSOC, $pdbs = false){
    $arr = ['code' => 0, 'message' => '', 'responseResult' => []];
    $con = db_connect();
  if (!$con) {
    $arr['code'] = 30;
    $arr['message'] = 'No hay conexión activa a la base de datos.';
    log_error($sesion . ' = Connection error');
    return $arr;
  }
  try {
    $con->set_charset('utf8');
    $rs = $con->query($sql);
    if (!$rs) {
      log_error($sesion . ' Error en la consulta: ' . $con->error, $con->errno);
      throw new mysqli_sql_exception("Error en la consulta: " . $con->error, $con->errno);
    }
    fetch($con, $rs, $resulttype, $arr);
  } catch (mysqli_sql_exception $e) {
    echo json_encode(['code' => 30, 'message' => 'Error BD', 'errors' => ['code' => $e->getCode(), 'message' => $e->getMessage()]]);
    log_error($sesion.'=>'.$e->getCode().'='.$e->getMessage());
  }finally {
    $con->close();
  }
  return $arr;
  }

function log_error($message) {
    $logDir = '../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }
    file_put_contents($logDir . '/file.log', "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL, FILE_APPEND);
}

  function fetch(&$con, &$rs, $resulttype, &$arr) {
	if ($rs === TRUE) {
		$arr['responseResult'][] = ['affected_rows' => $con->affected_rows];
	}else {
		if ($rs === FALSE) {
			die(json_encode(['code' => $con->errno, 'message' => $con->error]));
		}
		while ($r = $rs->fetch_array($resulttype)) {
			$arr['responseResult'][] = $r;
		}
		$rs->free();
	}
	return $arr;
}

function opc_arr($a = [], $b = "", $c = true) { 
    // $a = arreglo de datos, $b = dato previamente seleccionado
    $rta = "<option value='' class='alerta'>SELECCIONE</option>";
    $on = "";	
    
    if ($a != null) {
        for ($f = 0; $f < count($a); $f++) {
            $on = "";			
            if (is_array($a[$f]) && isset($a[$f]['v']) && isset($a[$f]['l'])) {
                // Compara el valor seleccionado con 'v' y 'l'
                $valor = strtoupper($a[$f]['v']);
                $label = strtoupper($a[$f]['l']);
                
                if ($valor == strtoupper($b) || $label == strtoupper($b)) {
                    $on = " selected='selected' ";
                } else {
                    if ($c === false) $on = " disabled='disabled' ";
                }
                $rta .= "<option $on value='".$a[$f]['v']."'>".$a[$f]['l']."</option>\n";
            } else if (!is_array($a[$f])) {
                // Manejo de elementos que no siguen el formato de 'v' y 'l'
                if (strtoupper($a[$f]) == strtoupper($b)) {
                    $on = " selected='selected' ";
                    $rta .= "<option $on value='".$a[$f]."'>".$a[$f]."</option>\n";
                } else {
                    if ($c === false) $on = " disabled='disabled' ";
                    $rta .= "<option $on value='".$a[$f]."'>".$a[$f]."</option>\n";
                }
            }
        }
    }
    
    return $rta;
  }