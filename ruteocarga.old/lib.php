<?php
ini_set('display_errors','1');
require_once "../libs/gestion.php";
if ($_POST['a']!='opc') $perf=perfil($_POST['tb']);
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
function perfil1($a = null) {
    if ($a === null) $a = $_SESSION['us_sds'];
    $per = datos_mysql("SELECT FN_PERFIL({$a}) AS perfil");
    $perfil = $per["responseResult"][0]['perfil'];
    return $perfil;
}

function lis_rut_geo(){
	$info=datos_mysql("SELECT COUNT(*) total FROM vspgeo WHERE 1 ".whe_rut_geo());
	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-rut_geo']))? ($_POST['pag-rut_geo']-1)* $regxPag:0;
	// $total=1;$pag=1;$regxPag=5;
	
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,idvspgeo ACCIONES,
		`tipo_doc`, `documento`, `nombres`, `telefono1`,FN_CATALOGODESC(87,evento1) EVENTO_1, FN_CATALOGODESC(87,evento2) EVENTO_2, FN_CATALOGODESC(87,evento3) EVENTO_3,FN_CATALOGODESC(87,evento4) EVENTO_4,`direccion_origen`,`localidad`,`predio_num`, `unidad_habit`,`obs_geo`,`equipo` 
  FROM  vspgeo WHERE 1 ";
	$sql.=whe_rut_geo();
	$sql.=" ORDER BY fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"rut_geo",$regxPag);
	}

function whe_rut_geo() {
 	$sql = "";
	if ($_POST['fdoc'])
		$sql .= " AND documento like '%".$_POST['fdoc']."%'"; 
	if ($_POST['fevento'])
		$sql .= " AND evento1='".$_POST['fevento']."' OR evento2='".$_POST['fevento']."' OR evento3='".$_POST['fevento']."' OR evento4='".$_POST['fevento']."' ";
	if(perfil1()=='ADM'){

	}else{
		if ($_POST['flocal']) $sql .= " AND localidad ='".$_POST['flocal']."'";
	}
	return $sql; 
}


function focus_rut_geo(){
 return 'rut_geo';
}


function men_rut_geo(){
 $rta=cap_menus('rut_geo','pro');
 return $rta;
}


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  $acc=rol($a);
  if ($a=='rut_geo' && isset($acc['crear']) && $acc['crear']=='SI'){  
    $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
    $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  }
  return $rta;
}


function cmp_rut_geo(){
 $rta="";
 
 $hoy=date('Y-m-d');

 $t=['estrategia'=>'','subred'=>'','zona'=>'','localidad'=>'','upz'=>'','barrio'=>'','obs_geo'=>'','obs_gen'=>'','microterritorio'=>'','sector_catastral'=>'','direccion'=>'',
 'direccion_nueva'=>'','nummanzana'=>'','predio_num'=>'','unidad_habit'=>'','vereda'=>'','vereda_nueva'=>'',
 'cordx'=>'','cordy'=>'','estrato'=>'','asignado'=>'','estado_v'=>'','motivo_estado'=>''];

 $w='rut_geo';
 $d=get_rut_geo(); 
 if ($d=="") {$d=$t;}
 $u=($d['sector_catastral']=='')?true:false;
//  $key=$d['sector_catastral'].'_'.$d['nummanzana'].'_'.$d['predio_num'].'_'.$d['unidad_habit'].'_'.$d['estrategia'].'_'.$d['estado_v'];


 $o='infgen';
 $c[]=new cmp($o,'e',null,'INFORMACIÓN GENERAL',$w);
 $c[]=new cmp('fuente','s','3',$d['fuente'],$w.' '.$o,'Fuente','fuente',null,null,true,false,'','col-2');
 $c[]=new cmp('estrategia','s','3',$d['estrategia'],$w.' '.$o,'Estrategia','estrategia',null,null,true,false,'','col-2');
 $c[]=new cmp('direccion_origen','t','50',$d['direccion_origen'],$w.' '.$o,'Direccion de Origen','direccion_origen',null,null,true,false,'','col-2');
 $c[]=new cmp('subred','s','3',$d['subred'],$w.' '.$o,'Subred','subred',null,null,true,false,'','col-2');
 $c[]=new cmp('localidad','s','3',$d['localidad'],$w.' '.$o,'Localidad','localidad',null,null,true,false,'','col-2');
 $c[]=new cmp('upz','s','3',$d['upz'],$w.' '.$o,'Upz','upz',null,null,true,false,'','col-2');
 $c[]=new cmp('barrio','t','8',$d['barrio'],$w.' '.$o,'Barrio','barrio',null,null,true,false,'','col-2');
 $c[]=new cmp('sector_catastral','n','6',$d['sector_catastral'],$w.' '.$o,'Sector de Catastral','sector_catastral',null,null,true,false,'','col-2');
 $c[]=new cmp('nummanzana','n','3',$d['nummanzana'],$w.' '.$o,'Nummanzana','nummanzana',null,null,true,false,'','col-2');
 $c[]=new cmp('predio_num','n','3',$d['predio_num'],$w.' '.$o,'Predio de Num','predio_num',null,null,true,false,'','col-2');
 $c[]=new cmp('unidad_habit','n','3',$d['unidad_habit'],$w.' '.$o,'Unidad de Habit','unidad_habit',null,null,true,false,'','col-2');
 $c[]=new cmp('tipo_doc','t','3',$d['tipo_doc'],$w.' '.$o,'Tipo de Doc','tipo_doc',null,null,true,false,'','col-2');
 $c[]=new cmp('documento','t','18',$d['documento'],$w.' '.$o,'Documento','documento',null,null,true,false,'','col-2');
 $c[]=new cmp('nombres','t','50',$d['nombres'],$w.' '.$o,'Nombres','nombres',null,null,false,true,'','col-2');
 $c[]=new cmp('telefono1','t','10',$d['telefono1'],$w.' '.$o,'Telefono1','telefono1',null,null,false,true,'','col-2');
 $c[]=new cmp('telefono2','t','10',$d['telefono2'],$w.' '.$o,'Telefono2','telefono2',null,null,false,true,'','col-2');
 $c[]=new cmp('telefono3','t','10',$d['telefono3'],$w.' '.$o,'Telefono3','telefono3',null,null,false,true,'','col-2');
 $c[]=new cmp('confir_llama','o','2',$d['confir_llama'],$w.' '.$o,'Confir de Llama','confir_llama',null,null,false,true,'','col-2');
 $c[]=new cmp('obs_geo','t','500',$d['obs_geo'],$w.' '.$o,'Obs de Geo','obs_geo',null,null,false,true,'','col-2');
 $c[]=new cmp('obs_gen','t','500',$d['obs_gen'],$w.' '.$o,'Obs de Gen','obs_gen',null,null,true,true,'','col-2');
 $c[]=new cmp('evento1','s','3',$d['evento1'],$w.' '.$o,'Evento1','evento1',null,null,false,true,'','col-2');
 $c[]=new cmp('evento2','s','3',$d['evento2'],$w.' '.$o,'Evento2','evento2',null,null,false,true,'','col-2');
 $c[]=new cmp('evento3','s','3',$d['evento3'],$w.' '.$o,'Evento3','evento3',null,null,false,true,'','col-2');
 $c[]=new cmp('evento4','s','3',$d['evento4'],$w.' '.$o,'Evento4','evento4',null,null,false,true,'','col-2');

 for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
//  $rta .="<div class='encabezado integrantes'>TABLA DE INTEGRANTES DE LA FAMILIA</div><div class='contenido' id='integrantes-lis' >".lis_integrantes1()."</div></div>";
 return $rta;
}

function opc_fuente($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
}

function opc_estrategia($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=42 and estado='A' ORDER BY 1",$id);
}
function opc_subred($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=72 and estado='A' ORDER BY 1",$id);
}
function opc_territorio($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=3 and estado='A' ORDER BY 1",$id);
}
function opc_localidad($id=''){
	return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,'-',descripcion) FROM `catadeta` WHERE idcatalogo=2 ORDER BY cast(idcatadeta as signed)",$id);
}
function opc_barrio($id=''){
	return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,'-',descripcion) FROM `catadeta` WHERE idcatalogo=20 and estado='A' ORDER BY 1",$id);
}
function opc_upz($id=''){
	return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,'-',descripcion) FROM `catadeta` WHERE idcatalogo=7 and estado='A' ORDER BY 1",$id);
}
function opc_asignado($id=''){
	// $asig = ($id=='') ? $_SESSION['us_sds'] : $id ;
	return opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE equipo IN('FAM') AND subred='".subred()."' ORDER BY 1",$id);
}
function opc_estado($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=44 and estado='A' ORDER BY 1",$id);
}
function opc_evento1($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 and estado='A' ORDER BY 1",$id);
}
function opc_evento2($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 and estado='A' ORDER BY 1",$id);
}
function opc_evento3($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 and estado='A' ORDER BY 1",$id);
}
function opc_evento4($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 and estado='A' ORDER BY 1",$id);
}


function get_rut_geo(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT fuente,estrategia,direccion_origen,subred,localidad,upz,barrio,sector_catastral,nummanzana,predio_num,unidad_habit,tipo_doc,documento,nombres,telefono1,telefono2,telefono3,confir_llama,obs_geo,obs_gen,evento1,evento2,evento3,evento4
		FROM `vspgeo` WHERE idvspgeo='{$id[0]}'";

// sector_catastral,'_',nummanzana,'_',predio_num,'_',estrategia,'_',estado_v
		$info=datos_mysql($sql);
    	// echo $sql."=>".$_POST['id'];
		return $info['responseResult'][0];
	} 
}


 
function gra_rut_geo(){
	
	 $sql="INSERT INTO hog_geo VALUES 
	(NULL,TRIM(UPPER('{$_POST['estrategia']}')),
	TRIM(UPPER('{$_POST['subred']}')),
	TRIM(UPPER('{$_POST['zona']}')),
	TRIM(UPPER('{$_POST['localidad']}')),
	TRIM(UPPER('{$_POST['upz']}')),
	TRIM(UPPER('{$_POST['barrio']}')),
	TRIM(UPPER('{$_POST['territorio']}')),
	TRIM(UPPER('{$_POST['microterritorio']}')),
	TRIM(UPPER('{$_POST['sector_catastral']}')),
	TRIM(UPPER('{$_POST['direccion']}')),
	TRIM(UPPER('{$_POST['direccion_nueva']}')),
	TRIM(UPPER('{$_POST['nummanzana']}')),
	TRIM(UPPER('{$_POST['predio_num']}')),
	TRIM(UPPER('{$_POST['unidad_habit']}')),
	TRIM(UPPER('{$_POST['vereda']}')),
	TRIM(UPPER('{$_POST['vereda_nueva']}')),
	TRIM(UPPER('{$_POST['cordx']}')),
	TRIM(UPPER('{$_POST['cordy']}')),
	TRIM(UPPER('{$_POST['cordxn']}')),
	TRIM(UPPER('{$_POST['cordyn']}')),
	TRIM(UPPER('{$_POST['estrato']}')),
	TRIM(UPPER('{$_POST['asignado']}')),
	TRIM(UPPER('{$_POST['estado_v']}')),
	TRIM(UPPER('{$_POST['motivo_estado']}')),
	TRIM(UPPER('{$_SESSION['us_sds']}')),
	DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL);";
	// echo $sql;
  $rta=dato_mysql($sql);
  return $rta;
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='rut_geo' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono mapa1' title='Editar Información Geografica' id='".$c['ACCIONES']."' Onclick=\"mostrar('rut_geo','pro',event,'','lib.php',7);\"></li>"; //setTimeout(hideExpres,1000,'estado_v',['7']);
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
