<?php
 require_once '../libs/gestion.php';
ini_set('display_errors','1');
if (!isset($_SESSION['us_sds'])) die("<script>window.top.location.href='/';</script>");
else {
  $rta="";
  switch ($_POST['a']){
  case 'csv': 
    header_csv ($_REQUEST['tb'].'.csv');
    $rs=array('','');    
    echo csv($rs,'');
    die;
    break;
  default:
    eval('$rta='.$_POST['a'].'_'.$_POST['tb'].'();');
    if (is_array($rta)) json_encode($rta);
	else echo $rta;
  }   
}


function lis_agendamiento(){
  $sql = "";
	if ($_POST['fidpersona'])
		$sql .= " AND id_persona like '%".$_POST['fidpersona']."%'";
	if ($_POST['fdigita'])
		$sql .= " AND usu_creo ='".$_POST['fdigita']."' ";
	if ($_POST['festado'])
		$sql .= " AND estado = '".$_POST['festado']."' ";
	if ($_POST['fdes']) {
		if ($_POST['fhas']) {
			$sql .= " AND fecha_cita >='".$_POST['fdes']."' AND fecha_cita <='".$_POST['fhas']."'";
		} else {
			$sql .= " AND fecha_cita >='".$_POST['fdes']."' AND fecha_cita <='". $_POST['fdes']."'";
		}
	}
	return '';
}

function whe_agendamiento() {
	$sql = "";
  if ($_POST['fidpersona'])
		$sql .= " AND id_persona like '%".$_POST['fidpersona']."%'";
	if ($_POST['fdigita'])
		$sql .= " AND usu_creo ='".$_POST['fdigita']."' ";
	if ($_POST['festado'])
		$sql .= " AND estado = '".$_POST['festado']."' ";
	if ($_POST['fdes']) {
		if ($_POST['fhas']) {
			$sql .= " AND fecha_cita >='".$_POST['fdes']."' AND fecha_cita <='".$_POST['fhas']."'";
		} else {
			$sql .= " AND fecha_cita >='".$_POST['fdes']."' AND fecha_cita <='". $_POST['fdes']."'";
		}
}   
    return $sql;
}


function focus_agendamiento(){
 return 'agendamiento';
}

function men_agendamiento(){
 $rta=cap_menus('agendamiento','pro');
 return $rta;
} 


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='agendamiento'){  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
  	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  }
  return $rta;
}


function cmp_agendamiento(){
	$rta="";
	return $rta;
}

function get_gestuser(){
	
}

function gra_gestuser(){
    $id=divide($_POST['variable']);
    $sql = "INSERT INTO variable VALUES(?,?,?,?,?,?,?,DATE_SUB(NOW(),INTERVAL 5 HOUR),?,?,?)";
    $params =[
    ['type' => 'i', 'value' => NULL],
    ['type' => 'i', 'value' => $_POST['variable']],
    ['type' => 's', 'value' => $_POST['variable']],
    ['type' => 's', 'value' => $_POST['variable']],
    ['type' => 's', 'value' => $_POST['variable']],
    ['type' => 's', 'value' => $_POST['variable']],
    ['type' => 's', 'value' => $_POST['variable']],
    ['type' => 'i', 'value' => $_SESSION['us_sds']],
    ['type' => 's', 'value' => NULL],
    ['type' => 's', 'value' => NULL],
    ['type' => 's', 'value' => 'A']
    ];
    return  $rta= mysql_prepd($sql, $params);
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// var_dump($rta);
	if ($a=='gestuser' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";		
		// $rta.="<li class='icono asigna1' title='Asignar Usuario' id='".$c['ACCIONES']."' Onclick=\"mostrar('gestuser','pro',event,'','lib.php',7);\"></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
