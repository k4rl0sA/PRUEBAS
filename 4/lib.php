<?php
 require_once '../libs/main.php';
ini_set('display_errors','1');
if (!isset($_SESSION['us_sds'])) die("<script>window.top.location.href='/';</script>");
else {
  $rta="";
    eval('$rta='.$_POST['a'].'_'.$_POST['tb'].'();');
    if (is_array($rta)) json_encode($rta);
	else echo $rta;
}

function exp_usuarios(){
	$sql = "SELECT id_usuario, nombre, clave, correo FROM usuarios";
	exportarDatos($sql,'usuarios');
}

var_dump($_POST);


function getConnection() {
	$env = ($_SERVER['SERVER_NAME']==='www.siginf-sds.com') ? 'prod' : 'pru' ;
	$comy=array('prod' => ['s'=>'localhost','u' => 'u470700275_06','p' => 'z9#KqH!YK2VEyJpT','bd' => 'u470700275_06'],'pru'=>['s'=>'localhost','u' => 'u470700275_17','p' => 'z9#KqH!YK2VEyJpT','bd' => 'u470700275_17']);
	$dsn = 'mysql:host='.$comy[$env]['s'].';dbname='.$comy[$env]['bd'].';charset=utf8';
	$username = $comy[$env]['u'];
	$password = $comy[$env]['p'];
	$options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	];
	try {
		return new PDO($dsn, $username, $password, $options);
	} catch (PDOException $e) {
		die("Error de conexión: " . $e->getMessage());
	}
  }
  
  $con= getConnection();
  
  function exportarDatos($sql,$name) {
	  $con = getConnection();
	  $stmt = $con->prepare($sql);
	  $stmt->execute();
	  $rta = $stmt->fetchAll();
	  $totalRegistros = count($rta);
	  if ($totalRegistros > 0) {
		  $rta[] = ["Total de registros" => $totalRegistros];
	  } else {
		  $rta[] = ["Total de registros" => 0];
	  }
	  header("Content-Type: application/vnd.ms-excel");
	  header("Content-Disposition: attachment; filename={$name}.xls");
	  header("Pragma: no-cache");
	  header("Expires: 0");
	  $separator = "\t";
	  if (count($rta) > 0) {
		$keys = array_keys($rta[0]);
		echo implode($separator, $keys) . "\n";
	}
	foreach ($rta as $row) {
	  echo implode($separator, array_values($row)) . "\n";
  }
  }