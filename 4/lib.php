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
