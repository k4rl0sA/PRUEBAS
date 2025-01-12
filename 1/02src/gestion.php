<?php
require_once __DIR__ . '/../01config/config.php';
ini_set('memory_limit','1024M');
// Verificar si la sesión ya ha sido iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function db_connect() {
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if ($con->connect_error) {
        throw new Exception('Error en la conexión a la base de datos: ' . $con->connect_error);
    }
    return $con;
}

$req = (isset($_REQUEST['a'])) ? $_REQUEST['a'] : '';
switch ($req) {
	case '';
	break;
	case 'exportar':
    $now=date("ymd");
		header_csv($_REQUEST['b'] .'_'.$now.'.csv');
    $info=datos_mysql($_SESSION['tot_' . $_REQUEST['b']]);
		$total=$info['responseResult'][0]['total'];
		if ($rs = mysqli_query($GLOBALS[isset($_REQUEST['con']) ? $_REQUEST['con'] : 'con'], $_SESSION['sql_' . $_REQUEST['b']])) {
			$ts = mysqli_fetch_array($rs, MYSQLI_ASSOC);
			echo csv($ts, $rs,$total);
		} else {
			echo "Error " . $GLOBALS['con']->errno . ": " . $GLOBALS['con']->error;
      $GLOBALS['con']->close();
		}
		break;
}

function datos_mysql($sql,$resulttype = MYSQLI_ASSOC, $pdbs = false){
    $arr = ['code' => 0, 'message' => '', 'responseResult' => []];
    $con = $GLOBALS['con'];
  if (!$con) {
    $arr['code'] = 30;
    $arr['message'] = 'No hay conexión activa a la base de datos.';
    log_error($_SESSION["us_sds"] . ' = Connection error');
    return $arr;
  }
  try {
    $con->set_charset('utf8');
    $rs = $con->query($sql);
    if (!$rs) {
      log_error($_SESSION["us_sds"] . ' Error en la consulta: ' . $con->error, $con->errno);
      throw new mysqli_sql_exception("Error en la consulta: " . $con->error, $con->errno);
    }
    fetch($con, $rs, $resulttype, $arr);
  } catch (mysqli_sql_exception $e) {
    echo json_encode(['code' => 30, 'message' => 'Error BD', 'errors' => ['code' => $e->getCode(), 'message' => $e->getMessage()]]);
    log_error($_SESSION["us_sds"].'=>'.$e->getCode().'='.$e->getMessage());
  }finally {
    // $GLOBALS['con']->close();
  }
  return $arr;
  }

  function log_error($message) {
    if (!is_dir('../logs')) {
      mkdir('../logs', 0777, true);
  }
    file_put_contents('../logs/file.log', "[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL, FILE_APPEND);
  }