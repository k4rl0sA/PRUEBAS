<?php
// require_once __DIR__ . '/../02src/gestion.php';
require_once "../../../lib/php/gestion.php";
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

function whe_prorep() {
	$sql = "";
	if ($_POST['fidp'])
		$sql .= " AND documento='".$_POST['fidp']."' ";
	/*if ($_POST['fest'])
		$sql .= " AND realizado IN (".$_POST['fest'].")";
	 if ($_POST['fdes']) {
		$fefin=date('Y-m-d');
		$feini = date("Y-m-d",strtotime($fefin."- 2 days"));
		if ($_POST['fhas']) {
		      $sql .= " AND fecha_create BETWEEN '$feini 00:00:00' and '$fefin 23:59:59' ";
		} else {
		    $sql .= " AND fecha_create BETWEEN '$feini 00:00:00' and '$feini 23:59:59' ";
		}
	} */
	return $sql;
}

function lis_prorep(){	
	$info=datos_mysql("SELECT COUNT(*) total FROM repor_promo WHERE 1 ".whe_prorep());
	$total=$info['responseResult'][0]['total'];
	$regxPag=17;
	$pag=(isset($_POST['pag-prorep']))? ($_POST['pag-prorep']-1)* $regxPag:0; 

	$sql="SELECT  `id_rep` ACCIONES,`fecha_report`, `cant_report`, `usu_create`, `fecha_create`  from repor_promo
	 WHERE 1 ".whe_prorep()." 
	 ORDER BY fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"prorep",$regxPag,"lib.php");
}

function focus_prorep(){
	return 'prorep';
   }
   
   function men_prorep(){
	$rta=cap_menus('prorep','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	$rta = "";
	// $rta .= "<li class='fa-solid fa-floppy-disk $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
	$rta .= "<button class='frm-btn $a grabar' onclick=\"grabar('$a', this);\"><span class='frm-txt'>Grabar</span><i class='fa-solid fa-floppy-disk icon'></i></button>";
	/* $rta .="<button class='frm-btn $a actualizar' onclick=\"act_lista('".$a."',this);\"'>Actualizar</button>";
	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('".$a."',this);\"></li>"; */
	return $rta;
  }

  function cmp_prorep(){
	$rta="";
	$t=['id_rep'=>'','fecha_report'=>'','cant_report'=>''];
	$w='prorep';
	$uPd = $_REQUEST['id']=='0' ? true : false;
	$d=get_prorep(); 
	//print_r($d);
	if ($d=="") {$d=$t;}
	$o='docder';
	$c[]=new cmp('id','h',100,$d['id_rep'],$w,'',0,'','','',false,'','col-1');
	$c[]=new cmp('fecha_report','d',20,$d['fecha_report'],$w.' '.$o,'Fecha Reporte','',NULL,'',true,$uPd,'','col-2');
	$c[]=new cmp('cant_report','n',3,$d['cant_report'],$w.' '.$o,'Cantidad Caracterizaciones','',null,'',true,$uPd,'','col-2');
	
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta.="</div>";
	return $rta;
	}

	function gra_prorep(){
		$id=divide($_POST['id']);
		$sql = "INSERT INTO repor_promo VALUES(?,?,?,?,DATE_SUB(NOW(),INTERVAL 5 HOUR),?,?,?)";
		$params = [
		['type' => 'i', 'value' => NULL],
		['type' => 's', 'value' => $_POST['fecha_report']],
		['type' => 's', 'value' => $_POST['cant_report']],
		['type' => 'i', 'value' => $_SESSION['us_subred']],
		['type' => 's', 'value' => NULL],
		['type' => 's', 'value' => NULL],
		['type' => 's', 'value' => 'A']
		
		];
		return $rta = mysql_prepd($sql, $params);
	} 
		
	
	function get_prorep(){
		if($_POST['id']=='0'){
			return "";
		}else{
			$id=divide($_POST['id']);
			$sql="SELECT * FROM repor_promo WHERE id_rep='".$id[0]."'";
			$info=datos_mysql($sql);
			return $info['responseResult'][0];		
		} 
	}



	function btn_prorep(){
		print_r(acceBtns('reportep'));
		echo json_encode(acceBtns('reportep'));
	}

   function formato_dato($a,$b,$c,$d){
	$b=strtolower($b);
	$rta=$c[$d];
	if (($a=='prorep') && ($b=='acciones')){
		   $rta="<nav class='menu right'>";
		   $rta.="<li class='fa-solid fa-pen-to-square' title='Editar Derivación' id='".$c['ACCIONES']."' Onclick=\"mostrar('prorep','pro',event,'','lib.php',4);\"></li>";
		   $rta.="</nav>";
	   }    
	return $rta;
   }

function bgcolor($a,$c,$f='c'){
	$rta="";
	//~ if ($a=='transacciones'&&$c['ESTADO']=='A') $rta='green';
	return $rta;
   }
