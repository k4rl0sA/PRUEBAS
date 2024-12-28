<?php
require_once "../lib/php/gestion.php";
ini_set('display_errors', '1');
// var_dump($_POST['a'].'-'.$_POST['tb']);
$perf = perfil($_POST['tb']);
if (!isset($_SESSION['us_sds'])) {
    http_response_code(401);
    echo json_encode(['redirect' => '/']);
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

function whe_deriva() {
	$sql = "";
	if ($_POST['fidp'])
		$sql .= " AND documento='".$_POST['fidp']."' ";
	if ($_POST['fest'])
		$sql .= " AND realizado IN (".$_POST['fest'].")";
	/* if ($_POST['fdes']) {
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

function lis_deriva(){	
	$info=datos_mysql("SELECT COUNT(*) total FROM derivaciones WHERE 1 ".whe_deriva());
	$total=$info['responseResult'][0]['total'];
	$regxPag=17;
	$pag=(isset($_POST['pag-deriva']))? ($_POST['pag-deriva']-1)* $regxPag:0; 

	$sql="SELECT  `id_deriva` ACCIONES,`documento`, `tipo_doc` , `derivado_colaborador` Colaborador, `realizado`, `observacion` from derivaciones
	 WHERE 1 ".whe_deriva()." 
	 ORDER BY fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"deriva",$regxPag,"lib.php");
}

function focus_deriva(){
	return 'deriva';
   }
   
   function men_deriva(){
	$rta=cap_menus('deriva','pro');
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

  function cmp_deriva(){
	$rta="";
	$t=['id_deriva'=>'','documento'=>'','tipo_doc'=>'','predio'=>'','derivado_colaborador'=>'','realizado'=>'','observacion'=>''];
	$w='deriva';
	$uPd = $_REQUEST['id']=='0' ? true : false;
	$d=get_deriva(); 
	//print_r($d);
	if ($d=="") {$d=$t;}
	$o='docder';
	$c[]=new cmp('id','h',100,$d['id_deriva'],$w,'',0,'','','',false,'','col-1');
	$c[]=new cmp('doc','t',20,$d['documento'],$w.' '.$o,'Documento','',NULL,'',true,$uPd,'','col-2');
	$c[]=new cmp('tip','s',3,$d['tipo_doc'],$w.' '.$o,'tipo_doc','tipo_doc',null,'',true,$uPd,'','col-2');
	$c[]=new cmp('pre','h',15,$d['predio'],$w.' '.$o,'Predio',null,null,'',false,false,'','col-2');
	$c[]=new cmp('asi','s',12,$d['derivado_colaborador'],$w.' '.$o,'Asignado A','asignado',null,'',true,$uPd,'','col-2');
	$c[]=new cmp('rea','o',1,$d['realizado'],$w.' '.$o,'Realizado',null,null,'',true,true,'','col-2');
	$c[]=new cmp('obs','t',10,$d['observacion'],$w.' '.$o,'Observación',null,null,'',true,true,'Número de 4 a 10 Digitos','col-1');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta.="</div>";
	return $rta;
	}


	function opc_tipo_doc($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}
	function opc_asignado($id=''){
		return opc_sql("SELECT id_usuario,nombre FROM usuarios WHERE perfil in('PROFAM','AUXHOG') and estado='A' ORDER BY 1",$id);
	}


	function get_deriva(){
		if($_POST['id']=='0'){
			return "";
		}else{
			$id=divide($_POST['id']);
			$sql="SELECT * FROM derivaciones WHERE id_deriva='".$id[0]."'";
			$info=datos_mysql($sql);
			return $info['responseResult'][0];		
		} 
	}

/* 	function imp_deriva(){
		$id=$_POST['id'];
		$info['responseResult'][['id_deriva'] => 1,
			['documento'] => '80811594',
		  	['tipo_doc'] => 'CC',
			['predio'] => 452147];
		// $info['responseResult'];
		/* if ($_POST['id']=== 0 ) {
			$sql="SELECT idpersona,tipo_doc,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,fecha_nacimiento,YEAR(CURDATE())-YEAR(fecha_nacimiento) Edad
				from personas
				WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."')";
		$info=datos_mysql($sql);
		if (!$info['responseResult']) {
			return json_encode (new stdClass);
		}	
		}else{

		} 
	return json_encode($info['responseResult'][0]);
	} */

	function btn_deriva(){
		print_r(acceBtns('derivacion'));
		echo json_encode(acceBtns('derivacion'));
	}

   function formato_dato($a,$b,$c,$d){
	$b=strtolower($b);
	$rta=$c[$d];
	if (($a=='deriva') && ($b=='acciones')){
		   $rta="<nav class='menu right'>";
		   $rta.="<li class='fa-solid fa-pen-to-square' title='Editar Derivación' id='".$c['ACCIONES']."' Onclick=\"mostrar('deriva','pro',event,'','lib.php',4);\"></li>";
		   $rta.="</nav>";
	   }    
	return $rta;
   }

function bgcolor($a,$c,$f='c'){
	$rta="";
	//~ if ($a=='transacciones'&&$c['ESTADO']=='A') $rta='green';
	return $rta;
   }
?>
