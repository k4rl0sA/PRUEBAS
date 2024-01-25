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


function lis_rute(){
	$info=datos_mysql("SELECT COUNT(*) total from eac_ruteo where subred in(select subred from usuarios where id_usuario = '{$_SESSION['us_sds']}') ".whe_rute());
	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-rute']))? ($_POST['pag-rute']-1)* $regxPag:0;
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,`id_ruteo` ACCIONES,FN_CATALOGODESC(2,localidad) localidad,sector_catastral 'Sector Catastral',nummanzana Manzana,predio_num predio,FN_CATALOGODESC(33,fuente) Fuente,`fecha_asig` Asignado,FN_CATALOGODESC(191,priorizacion) Priorización
  FROM `eac_ruteo` 
  WHERE 1 ";
	$sql.=" AND  subred in(select subred from usuarios where id_usuario = '{$_SESSION['us_sds']}') ".whe_rute();
	$sql.="ORDER BY fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	$_SESSION['sql_rute']=$sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"rute",$regxPag);
	}

function whe_rute() {
	$sql = "";
	if ($_POST['flocalidad'])
		$sql .= " AND localidad = '".$_POST['flocalidad']."'";
	if ($_POST['fgrupo'])
		$sql .= " AND priorizacion = '".$_POST['fgrupo']."'";
	if ($_POST['ffuente'])
		$sql .= " AND fuente ='".$_POST['ffuente']."' ";
	if ($_POST['fseca'])
		$sql .= " AND sector_catastral = '".$_POST['fseca']."'";
	if ($_POST['fmanz'])
		$sql .= " AND nummanzana ='".$_POST['fmanz']."' ";
	if ($_POST['fpred'])
		$sql .= " AND predio_num ='".$_POST['fpred']."' ";
	return $sql;
}


function focus_rute(){
 return 'rute';
}


function men_rute(){
 $rta=cap_menus('rute','pro');
 return $rta;
}


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='rute'){  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
  }
  return $rta;
}


function cmp_rute(){
 $rta="";
 $t=['id_ruteo'=>'','fuente'=>'','fecha_asig'=>'','priorizacion'=>'','tipo_doc'=>'','documento'=>'','nombres'=>'','fecha_nac'=>'','sexo'=>'',
 'nacionalidad'=>'','tipo_doc_acu'=>'','documento_acu'=>'','nombres_acu'=>'','direccion'=>'','telefono1'=>'','telefono2'=>'','telefono3'=>'',
 'subred'=>'','localidad'=>'','upz'=>'','barrio'=>'','cordx'=>'','cordy'=>'','perfil_asignado'=>'','fecha_gestion'=>'','estado_g'=>'',
 'motivo_estado'=>'','direccion_nueva'=>'', 'complemento'=>'', 'observacion'=>'', 'usu_creo'=>'', 'fecha_create'=>'', 'usu_update'=>'', 
 'fecha_update'=>'', 'estado'=>''];
 $w='rute';
 $d=get_rute(); 
 if ($d=="") {$d=$t;}
 $u=($d['id_ruteo']=='')?true:false;
//  var_dump($d['estado_g']);
 $x=($d['estado_g']=='')?true:false;
 

 $o='segrep';
 $c[]=new cmp($o,'e',null,'SEGUIMIENTO REPORTE',$w);
 $c[]=new cmp('id','h','20',$d['id_ruteo'],$w.' '.$o,'','',null,null,true,$u,'','col-1');
 $c[]=new cmp('estrategia','s','3',$d['estrategia'],$w.' '.$o,'Estrategia','fuente',null,null,false,$u,'','col-25');
 $c[]=new cmp('fuente','s','3',$d['fuente'],$w.' '.$o,'FUENTE O REMITENTE','fuente',null,null,false,$u,'','col-25');
 $c[]=new cmp('fecha_asig','d','10',$d['fecha_asig'],$w.' '.$o,'FECHA DE REMISIÓN A SUBRED','fecha_asig',null,null,false,$u,'','col-25');
 $c[]=new cmp('priorizacion','s','3',$d['priorizacion'],$w.' '.$o,'GRUPO DE POBLACION PRIORIZADA','priorizacion',null,null,false,$u,'','col-25');

 $o='atemed';
 $c[]=new cmp($o,'e',null,'DATOS DEL USUARIO QUE REQUIERE LA ATENCIÓN MEDICA',$w);
 $c[]=new cmp('tipo_doc','s','3',$d['tipo_doc'],$w.' '.$o,'TIPO DE DOCUMENTO','tipo_doc',null,null,false,$u,'','col-2');
 $c[]=new cmp('documento','t','20',$d['documento'],$w.' '.$o,'NUMERO DE DOCUMENTO','documento',null,null,false,$u,'','col-2');
 $c[]=new cmp('nombres','t','80',$d['nombres'],$w.' '.$o,'NOMBRES Y APELLIDOS DEL USUARIO','nombres',null,null,false,$u,'','col-4');
 $c[]=new cmp('fecha_nac','d','10',$d['fecha_nac'],$w.' '.$o,'FECHA DE NACIMIENTO','fecha_nac',null,null,false,$u,'','col-2');
 $c[]=new cmp('sexo','s','3',$d['sexo'],$w.' '.$o,'SEXO','sexo',null,null,false,$u,'','col-2');
 $c[]=new cmp('nacionalidad','s','3',$d['nacionalidad'],$w.' '.$o,'NACIONALIDAD','nacionalidad',null,null,false,$u,'','col-2');

 $o='datacu';
 $c[]=new cmp($o,'e',null,'DATOS DEL ACUDIENTE (Estas variables se diligencian para los menores de edad), o datos del usuario quien coloco la solicitud  PQR',$w);
 $c[]=new cmp('tipo_doc_acu','s','3',$d['tipo_doc_acu'],$w.' '.$o,'TIPO DE DOCUMENTO ACUDIENTE','tipo_doc_acu',null,null,false,$u,'','col-2');
 $c[]=new cmp('documento_acu','t','20',$d['documento_acu'],$w.' '.$o,'DOCUMENTO ACUDIENTE','documento_acu',null,null,false,$u,'','col-2');
 $c[]=new cmp('nombres_acu','t','80',$d['nombres_acu'],$w.' '.$o,'NOMBRES Y APELLIDOS DEL ACUDIENTE','nombres_acu',null,null,false,$u,'','col-6');

 $o='datcon';
 $c[]=new cmp($o,'e',null,'DATOS DE CONTACTO',$w);
 $c[]=new cmp('direccion','t','90',$d['direccion'],$w.' '.$o,'Direccion','direccion',null,null,false,$u,'','col-4');
 $c[]=new cmp('telefono1','n','10',$d['telefono1'],$w.' '.$o,'Telefono 1','telefono1',null,null,false,$u,'','col-2');
 $c[]=new cmp('telefono2','n','10',$d['telefono2'],$w.' '.$o,'Telefono 2','telefono2',null,null,false,$u,'','col-2');
 $c[]=new cmp('telefono3','n','10',$d['telefono3'],$w.' '.$o,'Telefono 3','telefono3',null,null,false,$u,'','col-2');
 $c[]=new cmp('subred','s','3',$d['subred'],$w.' '.$o,'Subred','subred',null,null,false,$u,'','col-3');
 $c[]=new cmp('localidad','s','3',$d['localidad'],$w.' '.$o,'Localidad','localidad',null,null,false,$u,'','col-2');
 $c[]=new cmp('upz','s','3',$d['upz'],$w.' '.$o,'Upz','upz',null,null,false,$u,'','col-2');
 $c[]=new cmp('barrio','s','5',$d['barrio'],$w.' '.$o,'Barrio','barrio',null,null,false,$u,'','col-3');
 $c[]=new cmp('sector_catastral','n','6',$d['sector_catastral'],$w.' '.$o,'Sector Catastral (6)','sector_catastral',null,null,false,$u,'','col-25');
 $c[]=new cmp('nummanzana','n','3',$d['nummanzana'],$w.' '.$o,'Nummanzana (3)','nummanzana',null,null,false,$u,'','col-25');
 $c[]=new cmp('predio_num','n','3',$d['predio_num'],$w.' '.$o,'Predio de Num (3)','predio_num',null,null,false,$u,'','col-25');
 $c[]=new cmp('unidad_habit','n','4',$d['unidad_habit'],$w.' '.$o,'Unidad habitacional (3)','unidad_habit',null,null,false,$u,'','col-25');

 $c[]=new cmp('cordx','t','15',$d['cordx'],$w.' '.$o,'Cordx','cordx',null,null,false,$u,'','col-4');
 $c[]=new cmp('cordy','t','15',$d['cordy'],$w.' '.$o,'Cordy','cordy',null,null,false,$u,'','col-4');
 $c[]=new cmp('perfil_asignado','t','30',$d['perfil_asignado'],$w.' '.$o,'Perfil Asignado','perfil_asignado',null,null,false,$u,'','col-2');

 $o='gesefc';
 $c[]=new cmp($o,'e',null,'PROCESO GESTIÓN EFECTIVA',$w);
 $c[]=new cmp('fecha_gestion','d','10',$d['fecha_gestion'],$w.' '.$o,'Fecha de Gestion','fecha_gestion',null,null,true,$x,'','col-2','validDate(this,-2,0);');
 $c[]=new cmp('estado_g','s',2,$d['estado_g'],$w.' '.$o,'estado','estado_g',null,null,true,$x,'','col-2','enabFielSele(this,[\'motivo_estado\']);');
 $c[]=new cmp('motivo_estado','s','3',$d['motivo_estado'],$w.' '.$o,'motivo_estado','motivo_estado',null,null,false,$x,'','col-2','validState(this,\'estado_g\');');
 $c[]=new cmp('direccion_nueva','t','90',$d['direccion_nueva'],$w.' dir '.$o,'Direccion Nueva','direccion_nueva',null,null,false,false,'','col-2');
 $c[]=new cmp('complemento','t','20',$d['complemento'],$w.' dir '.$o,'complemento','complemento',null,'',false, false,'','col-2');
 $c[]=new cmp('observacion','a',50,$d['observacion'],$w.' '.$o,'Observacion','observacion',null,null,true,$x,'','col-10');

 $o='gesres';
 $c[]=new cmp($o,'e',null,'PROCESO GESTIÓN RESOLUTIVA',$w);
 


 for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
 return $rta;
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
/* function opc_asignado($id=''){
	$co=datos_mysql("select FN_USUARIO(".$_SESSION['us_sds'].") as co;");
	$com=divide($co['responseResult'][0]['co']);
	return opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE `perfil` IN('MED','ENF') AND componente='EAC' and subred='{$com[2]}' ORDER BY 1",$id);
} */


function get_rute(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT `id_ruteo`, estrategia,`fuente`, `fecha_asig`, `priorizacion`, `tipo_doc`, `documento`, `nombres`, `fecha_nac`, `sexo`, `nacionalidad`, 
		`tipo_doc_acu`, `documento_acu`, `nombres_acu`, `direccion`, `telefono1`, `telefono2`, `telefono3`, `subred`, `localidad`, `upz`, `barrio`, 
		sector_catastral,nummanzana,predio_num,unidad_habit,`cordx`, `cordy`, `perfil_asignado`, `fecha_gestion`, `estado_g`, `motivo_estado`, `direccion_nueva`, `complemento`, `observacion`
		 FROM `eac_ruteo` WHERE  id_ruteo='{$id[0]}'";
		$info=datos_mysql($sql);
    // var_dump($info['responseResult'][0]);
		return $info['responseResult'][0];
	} 
}

 
function gra_rute(){
/* 	 $sql="INSERT INTO eac_ruteo VALUES 
	(NULL,TRIM(UPPER('{$_POST['fuente']}')),TRIM(UPPER('{$_POST['fecha_asig']}')),TRIM(UPPER('{$_POST['priorizacion']}')),TRIM(UPPER('{$_POST['tipo_doc']}')),TRIM(UPPER('{$_POST['documento']}')),TRIM(UPPER('{$_POST['nombres']}')),TRIM(UPPER('{$_POST['fecha_nac']}')),TRIM(UPPER('{$_POST['sexo']}')),TRIM(UPPER('{$_POST['nacionalidad']}')),TRIM(UPPER('{$_POST['tipo_doc_acu']}')),TRIM(UPPER('{$_POST['documento_acu']}')),TRIM(UPPER('{$_POST['nombres_acu']}')),TRIM(UPPER('{$_POST['direccion']}')),TRIM(UPPER('{$_POST['telefono1']}')),TRIM(UPPER('{$_POST['telefono2']}')),TRIM(UPPER('{$_POST['telefono']}')),TRIM(UPPER('{$_POST['subred']}')),TRIM(UPPER('{$_POST['localidad']}')),TRIM(UPPER('{$_POST['upz']}')),TRIM(UPPER('{$_POST['barrio']}')),TRIM(UPPER('{$_POST['cordx']}')),TRIM(UPPER('{$_POST['cordy']}')),TRIM(UPPER('{$_POST['perfil_asignado']}')),TRIM(UPPER('{$_POST['fecha_gestion']}')),TRIM(UPPER('{$_POST['estado_g']}')),TRIM(UPPER('{$_POST['motivo_estado']}')),TRIM(UPPER('{$_POST['direccion_nueva']}')),TRIM(UPPER('{$_POST['complemento']}')),TRIM(UPPER('{$_POST['observacion']}')),
	TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A');"; */




$sql="UPDATE `eac_ruteo` SET 
fecha_gestion=TRIM(UPPER('{$_POST['fecha_gestion']}')),
`estado_g`=TRIM(UPPER('{$_POST['estado_g']}')),
`motivo_estado`=TRIM(UPPER('{$_POST['motivo_estado']}')),
`direccion_nueva`=TRIM(UPPER('{$_POST['direccion_nueva']}')),
`complemento`=TRIM(UPPER('{$_POST['complemento']}')),
`observacion`=TRIM(UPPER('{$_POST['observacion']}')),
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
	if ($a=='rute' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono mapa' title='Ruteo' id='".$c['ACCIONES']."' Onclick=\"mostrar('rute','pro',event,'','lib.php',7);\"></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
