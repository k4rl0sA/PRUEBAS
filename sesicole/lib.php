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



function lis_sesigcole(){
	$total="SELECT COUNT(*) AS total FROM (
		SELECT G.idgeo AS ACCIONES,G.idgeo AS Cod_Predio,H.direccion,H.sector_catastral Sector,H.nummanzana AS Manzana,H.predio_num AS predio,H.unidad_habit AS 'Unidad',FN_CATALOGODESC(2,H.localidad) AS 'Localidad', H.upz AS PRUEBA ,U1.nombre,G.fecha_create,FN_CATALOGODESC(44,G.estado_v) AS estado 
		FROM geo_gest G	LEFT JOIN hog_geo H ON G.idgeo = H.idgeo LEFT JOIN usuarios U ON H.subred = U.subred	LEFT JOIN usuarios U1 ON H.usu_creo = U1.id_usuario
			WHERE G.estado_v IN ('7') ".whe_sesigcole()." AND U.id_usuario = '{$_SESSION['us_sds']}') AS Subquery";
	$info=datos_mysql($total);
	$total=$info['responseResult'][0]['total']; 
	$regxPag=5;
	$pag=(isset($_POST['pag-sesigcole']))? ($_POST['pag-sesigcole']-1)* $regxPag:0;

	
$sql="SELECT G.idgeo AS ACCIONES,
	G.idgeo AS Cod_Predio,
	H.direccion,
	H.sector_catastral Sector,
	H.nummanzana AS Manzana,
	H.predio_num AS predio,
	H.unidad_habit AS 'Unidad',
	FN_CATALOGODESC(2,H.localidad) AS 'Localidad',
	U1.nombre,
	G.fecha_create,
	FN_CATALOGODESC(44,G.estado_v) AS estado
	FROM geo_gest G
	LEFT JOIN hog_geo H ON G.idgeo = H.idgeo
	LEFT JOIN usuarios U ON H.subred = U.subred
	LEFT JOIN usuarios U1 ON H.usu_creo = U1.id_usuario 
WHERE G.estado_v in('7') ".whe_sesigcole()." 
	AND U.id_usuario = '{$_SESSION['us_sds']}'
	ORDER BY nummanzana, predio_num
	LIMIT $pag, $regxPag";
//  echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"sesigcole",$regxPag);
}	

function whe_sesigcole() {
	$sql = "";
	if (!empty($_POST['fpred']) && $_POST['fdigita']) {
		$sql .= " AND G.idgeo = '" . $_POST['fpred'] . "' AND G.usu_creo ='" . $_POST['fdigita'] . "'";
	}else{
		$sql .="AND G.idgeo ='0'";
	} 
	return $sql;
}

function focus_sesigcole(){
 return 'sesigcole';
}

function men_sesigcole(){
 $rta=cap_menus('sesigcole','pro');
 return $rta;
} 

function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='sesigcole'){  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
  	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  }
  return $rta;
}

function cmp_sesigcole(){
	$rta="";
	$t=['fecha_int'=>'','activi'=>'','luga'=>'','temati1'=>'','desc_temati1'=>'','temati2'=>'','desc_temati2'=>'','temati3'=>'','desc_temati3'=>'','temati4'=>'','desc_temati4'=>'','temati5'=>'','desc_temati5'=>'','temati6'=>'','desc_temati6'=>'','temati7'=>'','desc_temati7'=>'','temati8'=>'','desc_temati8'=>''];
	$d=get_sesigcole();
	if ($d==""){$d=$t;}
	// var_dump($_POST);
	$id=divide($_POST['id']);
    $w="sesigcole";
	$o='infbas';
	// var_dump($p);
	$days=fechas_app('vivienda');
	$o='Secgi';
	$c[]=new cmp($o,'e',null,'SESIONES GRUPALES Y COLECTIVAS',$w);
	$c[]=new cmp('id','h','20', $_POST['id'] ,$w.' '.$o,'','',null,null,true,false,'','col-1');
	$c[]=new cmp('fecha_int','d','10',$d['fecha_int'],$w.' '.$o,'fecha_Intervencion','fecha_int',null,null,true,true,'','col-15',"validDate(this,$days,0);"); 
	$c[]=new cmp('activi','s','15',$d['activi'],$w.' '.$o,'Tipo de Actividad','fm1',null,null,true,true,'','col-25');
	$c[]=new cmp('luga','t','15',$d['luga'],$w.' '.$o,'Lugar','rta',null,null,true,true,'','col-6',"fieldsValue('agen_intra','aIM','1',true);");
	$c[]=new cmp('temati1','s','3',$d['temati1'],$w.' '.$o,'tematica 1','temati1',null,null,true,true,'','col-15',"selectDepend('temati1','desc_temati1');");
	$c[]=new cmp('desc_temati1','s','3',$d['desc_temati1'],$w.' '.$o,'Descripcion tematica 1','desc_temati1',null,null,true,true,'','col-35');
    $c[]=new cmp('temati2','s','3',$d['temati2'],$w.' '.$o,'tematica 2','temati2',null,null,false,true,'','col-15',"selectDepend('temati2','desc_temati2');");
    $c[]=new cmp('desc_temati2','s','3',$d['desc_temati2'],$w.' '.$o,'Descripcion tematica 2','desc_temati2',null,null,false,true,'','col-35');
    $c[]=new cmp('temati3','s','3',$d['temati3'],$w.' '.$o,'tematica 3','temati3',null,null,false,true,'','col-15',"selectDepend('temati3','desc_temati3');");
    $c[]=new cmp('desc_temati3','s','3',$d['desc_temati3'],$w.' '.$o,'Descripcion tematica 3','desc_temati3',null,null,false,true,'','col-35');
    $c[]=new cmp('temati4','s','3',$d['temati4'],$w.' '.$o,'tematica 4','temati4',null,null,false,true,'','col-15',"selectDepend('temati4','desc_temati4');");
    $c[]=new cmp('desc_temati4','s','3',$d['desc_temati4'],$w.' '.$o,'Descripcion tematica 4','desc_temati4',null,null,false,true,'','col-35');
	$c[]=new cmp('temati5','s','3',$d['temati5'],$w.' '.$o,'tematica 5','temati5',null,null,false,true,'','col-15',"selectDepend('temati5','desc_temati5');");
    $c[]=new cmp('desc_temati5','s','3',$d['desc_temati5'],$w.' '.$o,'Descripcion tematica 5','desc_temati5',null,null,false,true,'','col-35');
	$c[]=new cmp('temati6','s','3',$d['temati6'],$w.' '.$o,'tematica 6','temati6',null,null,false,true,'','col-15',"selectDepend('temati6','desc_temati6');");
    $c[]=new cmp('desc_temati6','s','3',$d['desc_temati6'],$w.' '.$o,'Descripcion tematica 6','desc_temati6',null,null,false,true,'','col-35');
	$c[]=new cmp('temati7','s','3',$d['temati7'],$w.' '.$o,'tematica 7','temati7',null,null,false,true,'','col-15',"selectDepend('temati7','desc_temati7');");
    $c[]=new cmp('desc_temati7','s','3',$d['desc_temati7'],$w.' '.$o,'Descripcion tematica 7','desc_temati7',null,null,false,true,'','col-35');
	$c[]=new cmp('temati8','s','3',$d['temati8'],$w.' '.$o,'tematica 8','temati8',null,null,false,true,'','col-15',"selectDepend('temati8','desc_temati8');");
    $c[]=new cmp('desc_temati8','s','3',$d['desc_temati8'],$w.' '.$o,'Descripcion tematica 8','desc_temati8',null,null,false,true,'','col-35');



	// $c[]=new cmp('medico','s',15,$d,$w.' der '.$o,'Asignado','medico',null,null,false,false,'','col-5');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function get_sesigcole(){
	return '';
}

function gra_sesigcole(){
	var_dump($_POST);
/* $id=divide($_POST['variable']);
$sql = "INSERT INTO variable VALUES(?,?,?,?,?,?,?,DATE_SUB(NOW(),INTERVAL 5 HOUR),?,?,?)";
$params = [
['type' => 'i', 'value' => NULL],
['type' => 's', 'value' => $id[0]],
['type' => 's', 'value' => $_POST['variable']],
['type' => 's', 'value' => $_POST['variable']],
['type' => 's', 'value' => $_POST['variable']],
['type' => 'i', 'value' => $_SESSION['us_sds']],
['type' => 's', 'value' => NULL],
['type' => 's', 'value' => NULL],
['type' => 's', 'value' => 'A']
];
$rta = mysql_prepd($sql, $params);
return $rta; */
	// return $rta;

}

function get_personOld(){
	// print_r($_REQUEST);
	$id=divide($_POST['id']);
	$info=datos_mysql("select idpersona from person where idpersona ='".$id[0]."'");
	if (!$info['responseResult']) {
		$sql="SELECT encuentra,idpersona,tipo_doc,nombre1,nombre2,apellido1,apellido2,fecha_nacimiento,
		sexo,genero,oriensexual,nacionalidad,estado_civil,niveduca,abanesc,ocupacion,tiemdesem,vinculo_jefe,etnia,pueblo,idioma,discapacidad,regimen,eapb,
		afiliaoficio,sisben,catgosisb,pobladifer,incluofici,cuidador,perscuidada,tiempo_cuidador,cuidador_unidad,vinculo,tiempo_descanso,
		descanso_unidad,reside_localidad,localidad_vive,transporta
		FROM `personas` 
   	WHERE idpersona ='".$id[0]."' AND tipo_doc='".$id[1]."'";
	$info=datos_mysql($sql);
	if (!$info['responseResult']) {
		return json_encode (new stdClass);
	}
	return json_encode($info['responseResult'][0]);
	}else{
		// return json_encode (new stdClass);
		return $rta="Error: El usuario con este número de documento ya se encuentra registrado.";

	}
} 


function opc_temati1desc_temati1($id=''){
	if($_REQUEST['id']!=''){
				$id=divide($_REQUEST['id']);
				$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
				$info=datos_mysql($sql);
				return json_encode($info['responseResult']);
		}
}
function opc_temati2desc_temati2($id=''){
	if($_REQUEST['id']!=''){
				$id=divide($_REQUEST['id']);
				$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
				$info=datos_mysql($sql);
				return json_encode($info['responseResult']);
		}
}function opc_temati3desc_temati3($id=''){
	if($_REQUEST['id']!=''){
				$id=divide($_REQUEST['id']);
				$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
				$info=datos_mysql($sql);
				return json_encode($info['responseResult']);
		}
}function opc_temati4desc_temati4($id=''){
	if($_REQUEST['id']!=''){
				$id=divide($_REQUEST['id']);
				$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
				$info=datos_mysql($sql);
				return json_encode($info['responseResult']);
		}
}function opc_temati5desc_temati5($id=''){
	if($_REQUEST['id']!=''){
				$id=divide($_REQUEST['id']);
				$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
				$info=datos_mysql($sql);
				return json_encode($info['responseResult']);
		}
}function opc_temati6desc_temati6($id=''){
	if($_REQUEST['id']!=''){
				$id=divide($_REQUEST['id']);
				$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
				$info=datos_mysql($sql);
				return json_encode($info['responseResult']);
		}
}function opc_temati7desc_temati7($id=''){
	if($_REQUEST['id']!=''){
				$id=divide($_REQUEST['id']);
				$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
				$info=datos_mysql($sql);
				return json_encode($info['responseResult']);
		}
}function opc_temati8desc_temati8($id=''){
	if($_REQUEST['id']!=''){
				$id=divide($_REQUEST['id']);
				$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
				$info=datos_mysql($sql);
				return json_encode($info['responseResult']);
		}
}

function opc_tipose($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_fm1($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_temati1($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=237 and estado='A' ORDER BY 1",$id);
}

function opc_desc_temati1($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_temati2($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=237 and estado='A' ORDER BY 1",$id);
}

function opc_desc_temati2($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_temati3($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=237 and estado='A' ORDER BY 1",$id);
}

function opc_desc_temati3($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_temati4($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=237 and estado='A' ORDER BY 1",$id);
}

function opc_desc_temati4($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_temati5($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=237 and estado='A' ORDER BY 1",$id);
}

function opc_desc_temati5($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_temati6($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=237 and estado='A' ORDER BY 1",$id);
}

function opc_desc_temati6($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_temati7($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=237 and estado='A' ORDER BY 1",$id);
}

function opc_desc_temati7($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_temati8($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=237 and estado='A' ORDER BY 1",$id);
}

function opc_desc_temati8($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_rta($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=4 and estado='A' ORDER BY 1",$id);
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('U	TF-8','ISO-8859-1',$rta);
// var_dump($a);
	if ($a=='sesigcole' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono asigna1' title='Crear Sesion' id='".$c['ACCIONES']."' Onclick=\"mostrar('sesigcole','pro',event,'','lib.php',7);\"></li>";
		$rta.="<li title='Crear Integrantes de la Actividad' Onclick=\"mostrar('sespers','pro',event,'','sesiperson.php',7,'sespers');\"><i class='fa-solid fa-person-circle-plus ico' id='".$c['ACCIONES']."'></i></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
