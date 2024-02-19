<?php
require_once "../libs/gestion.php";
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




function focus_ruteresol(){
 return 'ruteresol';
}


function men_ruteresol(){
 $rta=cap_menus('ruteresol','pro');
 return $rta;
}


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='ruteresol'){  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
  }
  return $rta;
}


function cmp_ruteresol(){
 $rta="";
 $t=['id_ruteo'=>'','fecha_update'=>'', 'estado'=>'','famili'=>'','usuario'=>'','cod_admin'=>'','gestion'=>''];
 $w='ruteresol';
 $d=get_ruteresol(); 
 if ($d=="") {$d=$t;}
 $u=($d['id_ruteo']=='')?true:false;
 
 $o='gesres';
 $c[]=new cmp($o,'e',null,'PROCESO GESTIÓN RESOLUTIVA',$w);
 $c[]=new cmp('id','h','20',$d['id_ruteo'],$w.' '.$o,'','',null,null,true,$u,'','col-1');
 $c[]=new cmp('estado','s',3,$d['estado'],$w.' PuE StG '.$o,'estado','estado',null,null,true,true,'','col-2',"changeSelect('estado','famili');");
 $c[]=new cmp('famili','s',3,$d['famili'],$w.' PuE StG '.$o,'famili','famili',null,'',false, true,'','col-3',"changeSelect('famili','usuario');");//N° FAMILIA
 $c[]=new cmp('usuario','s',3,$d['usuario'],$w.' PuE StG '.$o,'usuario','usuario',null,'',false, true,'','col-3',"changeSelect('usuario','cod_admin');"); //TIPO_DOC,DOCUMENTO Y NOMBRE USUARIO
 $c[]=new cmp('cod_admin','s',3,$d['cod_admin'],$w.' PuE StG '.$o,'cod_admin','cod_admin',null,'',true, true,'','col-2');//traer los codigos del usuario de atencion

 for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
 return $rta;
}

function opc_gestion($id=''){
	return opc_sql("SELECT `idcatadeta`, descripcion FROM `catadeta` WHERE idcatalogo=222 AND estado='A' ORDER BY 1", $id);
}

function opc_idgeo($a){
	$id=divide($a);
	$sql="SELECT concat_ws('_',sector_catastral,nummanzana,predio_num,unidad_habit) cod
		 FROM `eac_ruteo` WHERE  id_ruteo='{$id[0]}'";
		 $info=datos_mysql($sql);
		 $cod= $info['responseResult'][0]['cod'];
		 return $cod;
		 /* return	opc_sql("SELECT CONCAT_WS('_',idgeo,estado_v),FN_CATALOGODESC(44,estado_v)
			from hog_geo where 
			sector_catastral='$co[0]' AND nummanzana='$co[1]' AND predio_num='$co[2]' AND unidad_habit='$co[3]' AND estado_v>3",$id);  */
}

function opc_estado($id=''){
	$id=opc_idgeo($_REQUEST['id']);
		$co=divide($id);
		return	opc_sql("SELECT idgeo,FN_CATALOGODESC(44,estado_v)
			from hog_geo where 
			sector_catastral='$co[0]' AND nummanzana='$co[1]' AND predio_num='$co[2]' AND unidad_habit='$co[3]' AND estado_v>3",$id); 
}



function opc_estadofamili(){
	if($_REQUEST['id']!=''){
		$id=divide($_REQUEST['id']);
		$sql="SELECT idviv 'id',concat(idviv,' - ','FAMILIA ',numfam) FROM hog_viv hv where idpre={$id[0]} ORDER BY 1";
		$info=datos_mysql($sql);
		// print_r($sql);
		return json_encode($info['responseResult']);
	} 
}
function opc_famili($id=''){
	// return opc_sql("SELECT `idcatadeta`, descripcion FROM `catadeta` WHERE idcatalogo=0 AND estado='A' ORDER BY 1", $id);
}
function opc_usuario($id=''){
	// return opc_sql("SELECT `idcatadeta`, descripcion FROM `catadeta` WHERE idcatalogo=0 AND estado='A' ORDER BY 1", $id);
}

function opc_familiusuario(){
	if($_REQUEST['id']!=''){
		$id=divide($_REQUEST['id']);
		$sql="SELECT CONCAT_WS('_',tipo_doc,idpersona),CONCAT_WS('-',idpersona,tipo_doc,CONCAT_WS(' ',nombre1,apellido1)) FROM personas p WHERE vivipersona={$id[0]} ORDER BY 1";
		$info=datos_mysql($sql);
		// print_r($sql);
		return json_encode($info['responseResult']);
	} 					
}

function opc_usuariocod_admin(){
	// var_dump($_REQUEST['id']);
	if($_REQUEST['id']!=''){
		$id=divide($_REQUEST['id']);
		$sql="SELECT f.cod_admin cod,f.cod_admin FROM adm_facturacion f WHERE f.tipo_doc='{$id[0]}' AND f.documento='{$id[1]}' ORDER BY 1";
		$info=datos_mysql($sql);
		// print_r($sql);
		return json_encode($info['responseResult']);
	} 					
}

function opc_cod_admin($id=''){
	// return opc_sql("SELECT `idcatadeta`, descripcion FROM `catadeta` WHERE idcatalogo=0 AND estado='A' ORDER BY 1", $id);
}
function opc_fuente($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=33 and estado='A' ORDER BY 1",$id);
}
function opc_subred($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=72 and estado='A' ORDER BY 1",$id);
}
function opc_priorizacion($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=191 and estado='A' ORDER BY 1",$id);
}
function opc_tipo_doc($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_tipo_doc_acu($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_sexo($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
}
function opc_nacionalidad($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=30 and estado='A' ORDER BY 1",$id);
}
function opc_perfil_asignado($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=3 and estado='A' ORDER BY 1",$id);
}
function opc_estado_g($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=35 and estado='A' ORDER BY 1",$id);
}
function opc_localidad($id=''){
	return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,'-',descripcion) FROM `catadeta` WHERE idcatalogo=2 ORDER BY cast(idcatadeta as signed)",$id);
}
function opc_upz($id=''){
	return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,'-',descripcion) FROM `catadeta` WHERE idcatalogo=7 and estado='A' ORDER BY 1",$id);
}
function opc_barrio($id=''){
	return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,'-',descripcion) FROM `catadeta` WHERE idcatalogo=20 and estado='A' ORDER BY 1",$id);
}
function opc_motivo_estado($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=36 and estado='A' ORDER BY 1",$id);
}
function opc_asignado($id=''){
	$co=datos_mysql("select FN_USUARIO(".$_SESSION['us_sds'].") as co;");
	$com=divide($co['responseResult'][0]['co']);
	return opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE `perfil` IN('MED','ENF') AND componente='EAC' and subred='{$com[2]}' ORDER BY 1",$id);
}


function get_ruteresol(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT `id_ruteo`,predio,famili,usuario,cod_admin
		 FROM `eac_ruteo` WHERE  id_ruteo='{$id[0]}'";
		$info=datos_mysql($sql);
    var_dump($info['responseResult'][0]);
		return $info['responseResult'][0];
	} 
}

 
function gra_ruteresol(){
/* 	 $sql="INSERT INTO eac_ruteo VALUES 
	(NULL,TRIM(UPPER('{$_POST['fuente']}')),TRIM(UPPER('{$_POST['fecha_asig']}')),TRIM(UPPER('{$_POST['priorizacion']}')),TRIM(UPPER('{$_POST['tipo_doc']}')),TRIM(UPPER('{$_POST['documento']}')),TRIM(UPPER('{$_POST['nombres']}')),TRIM(UPPER('{$_POST['fecha_nac']}')),TRIM(UPPER('{$_POST['sexo']}')),TRIM(UPPER('{$_POST['nacionalidad']}')),TRIM(UPPER('{$_POST['tipo_doc_acu']}')),TRIM(UPPER('{$_POST['documento_acu']}')),TRIM(UPPER('{$_POST['nombres_acu']}')),TRIM(UPPER('{$_POST['direccion']}')),TRIM(UPPER('{$_POST['telefono1']}')),TRIM(UPPER('{$_POST['telefono2']}')),TRIM(UPPER('{$_POST['telefono']}')),TRIM(UPPER('{$_POST['subred']}')),TRIM(UPPER('{$_POST['localidad']}')),TRIM(UPPER('{$_POST['upz']}')),TRIM(UPPER('{$_POST['barrio']}')),TRIM(UPPER('{$_POST['cordx']}')),TRIM(UPPER('{$_POST['cordy']}')),TRIM(UPPER('{$_POST['perfil_asignado']}')),TRIM(UPPER('{$_POST['fecha_gestion']}')),TRIM(UPPER('{$_POST['estado_g']}')),TRIM(UPPER('{$_POST['motivo_estado']}')),TRIM(UPPER('{$_POST['direccion_nueva']}')),TRIM(UPPER('{$_POST['complemento']}')),TRIM(UPPER('{$_POST['observacion']}')),
	TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A');"; */


	/* $sql="SELECT `id_ruteo`, estrategia,`fuente`, `fecha_asig`, `priorizacion`, `tipo_doc`, `documento`, `nombres`, `fecha_nac`, `sexo`, `nacionalidad`, 
	`tipo_doc_acu`, `documento_acu`, `nombres_acu`, `direccion`, `telefono1`, `telefono2`, `telefono3`, `subred`, `localidad`, `upz`, `barrio`, 
	sector_catastral,nummanzana,predio_num,unidad_habit,`cordx`, `cordy`, `perfil_asignado`,gestion, `fecha_gestion`, `estado_g`, `motivo_estado`, `direccion_nueva`, `complemento`, `observacion`,predio,cod_admin
	FROM `eac_ruteo` WHERE  id_ruteo='{$_POST['id']}'";
	$info=datos_mysql($sql);

	return $info['responseResult'][0]; */

$sql="UPDATE `eac_ruteo` SET 
famili=TRIM(UPPER('{$_POST['famili']})),
usuario=TRIM(UPPER('{$_POST['usuario']}')),
`predio`=TRIM(UPPER('{$_POST['estado']}')),
`cod_admin`=TRIM(UPPER('{$_POST['cod_admin']}')),
`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR)
	WHERE id_ruteo='{$_POST['id']}'";
	//echo $sql;
  $rta=dato_mysql($sql);
  return $rta;
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='ruteresol' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono mapa' title='Ruteo' id='".$c['ACCIONES']."' Onclick=\"mostrar('ruteresol','pro',event,'','lib.php',7);\"></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
