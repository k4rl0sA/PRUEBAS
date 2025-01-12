<?php
require_once __DIR__ . '/../01config/config.php';

ini_set('memory_limit','1024M');
if (!isset(session_name)) {
    header("Location: /index.php"); 
    exit;
  }

  echo "Archivo gestion.php cargado correctamente.";

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