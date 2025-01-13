<?php
require_once __DIR__ . '/../02src/gestion.php';
ini_set('display_errors', '1');
$_POST['a'].'-'.$_POST['tb'];
$perf = perfil($_POST['tb']);
if (!isset($_SESSION[SESSION_NAME])) {
    http_response_code(401);
    echo json_encode(['redirect' => '/01/03public/']);
    exit();
} else {
	$rta = "";
	switch ($_POST['a']) {
		case 'csv':
			header_csv($_REQUEST['tb'] . '.csv');
			$rs = array('', '');
			echo csv($rs, '');
			break;
		default:
			if (isset($_REQUEST['t']) && $_REQUEST['t'] == 'json') {
				header('Content-Type: application/json');
			} else {
				header('Content-Type: text/html; charset=UTF-8');
			}
			$func = $_POST['a'] . '_' . $_POST['tb'];
			echo $func;
			if (function_exists($func)) {
				$rta = $func();
			} else {
				http_response_code(400);
				echo json_encode(['error' => 'Función no encontrada']);
				exit();
			}
			// Si $rta es un arreglo, devolverlo como JSON
			if (is_array($rta)) {
				echo json_encode($rta);
			} else {
				echo $rta; // Si no es un arreglo, devolver la respuesta directamente
			}
	}
}
