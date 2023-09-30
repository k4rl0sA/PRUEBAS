<?php
ini_set('display_errors','1');
require_once '../libs/gestion.php';
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


function lis_vsp_asig(){
	$sql1="SELECT COUNT(*) total FROM `vspgeo` C
	LEFT JOIN hog_geo D ON C.estrategia=D.estrategia AND C.sector_catastral=D.sector_catastral AND C.nummanzana=D.nummanzana AND C.predio_num=D.predio_num AND C.unidad_habit=D.unidad_habit AND C.estado_v=D.estado_v
	 WHERE C.estado_v in (1) AND C.subred in (SELECT C.subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')".whe_vsp_asig();
	//  echo $sql1;
	$info=datos_mysql($sql1);
	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-vsp_asig']))? ($_POST['pag-vsp_asig']-1)* $regxPag:0;
	
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(C.estrategia,'_',C.sector_catastral,'_',C.nummanzana,'_',C.predio_num,'_',C.unidad_habit,'_',C.estado_v) ACCIONES,
	FN_CATALOGODESC(42,C.estrategia) estrategia,
	C.sector_catastral,
	C.nummanzana 'Manzana',
	C.predio_num 'predio',
	C.unidad_habit 'Unidad Hab',
	FN_CATALOGODESC(2,C.localidad) 'Localidad',
	C.usu_creo,
	C.fecha_create,
	FN_CATALOGODESC(44,C.estado_v) estado 
  FROM `vspgeo` C
  LEFT JOIN hog_geo D ON C.estrategia=D.estrategia AND C.sector_catastral=D.sector_catastral AND C.nummanzana=D.nummanzana AND C.predio_num=D.predio_num AND C.unidad_habit=D.unidad_habit AND C.estado_v=D.estado_v
  WHERE C.estado_v in (1) AND C.subred in (SELECT C.subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."') and D.asignado='".$_SESSION['us_sds']."'";
//   echo $sql;
	$sql.=whe_vsp_asig();
	$sql.=" ORDER BY C.nummanzana,C.predio_num";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	// echo $sql;
	
	/* $sql1="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v) ACCIONES,
	FN_CATALOGODESC(42,`estrategia`) estrategia,
	sector_catastral,
	nummanzana 'Manzana',
	predio_num 'predio',
	unidad_habit 'Unidad Hab',
	FN_CATALOGODESC(3,zona) zona,
	FN_CATALOGODESC(2,localidad) 'Localidad',
	usu_creo,
	fecha_create,
	FN_CATALOGODESC(44,`estado_v`) estado 
  FROM `hog_geo`";
	$_SESSION['sql_vsp_asig']=$sql1; */
// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"vsp_asig",$regxPag);
	}

function whe_vsp_asig() {
	$sql = "";
	if ($_POST['fseca'])
		$sql .= " AND C.sector_catastral = '".$_POST['fseca']."'";
	if ($_POST['fmanz'])
		$sql .= " AND C.nummanzana ='".$_POST['fmanz']."' ";
	if ($_POST['fpred'])
		$sql .= " AND C.predio_num ='".$_POST['fpred']."' ";
	if ($_POST['festado'])
		$sql .= " AND C.estado_v ='".$_POST['festado']."'";
	if (isset($_POST['fdigita'])){
		if($_POST['fdigita']) $sql .= " AND asignado ='".$_POST['fdigita']."'";
	}else{
		$sql .= " AND C.asignado IN ({$_SESSION['us_sds']})";
	}
	return $sql;
}


function focus_vsp_asig(){
 return 'vsp_asig';
}


function men_vsp_asig(){
 $rta=cap_menus('vsp_asig','pro');
 return $rta;
}


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  $acc=rol($a);
  //print_r($acc);
  if ($a=='vsp_asig'  && isset($acc['crear']) && $acc['crear']=='SI'){  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
  	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  }
  return $rta;
}


function cmp_vsp_asig(){
 $rta="";
 
 $hoy=date('Y-m-d');

 $t=['estrategia'=>'','subred'=>'','zona'=>'','localidad'=>'','upz'=>'','barrio'=>'','territorio'=>'','territorio'=>'','microterritorio'=>'','sector_catastral'=>'','direccion'=>'',
 'direccion_nueva'=>'','nummanzana'=>'','predio_num'=>'','unidad_habit'=>'','vereda'=>'','vereda_nueva'=>'',
 'cordx'=>'','cordy'=>'','estrato'=>'','asignado'=>'','estado_v'=>'','motivo_estado'=>'','tipo_doc'=>'','documento'=>'','nombres'=>'','telefono1'=>'','telefono2'=>'','telefono3'=>'','evento1'=>'','evento2'=>'','evento3'=>'','evento4'=>''];

 $w='vsp_asig';
 $d=get_vsp_asig(); 
 if ($d=="") {$d=$t;}
 $u=($d['sector_catastral']=='')?true:false;
 $key=$d['sector_catastral'].'_'.$d['nummanzana'].'_'.$d['predio_num'].'_'.$d['unidad_habit'].'_'.$d['estrategia'].'_'.$d['estado_v'];
 $o='indcas';
 $c[]=new cmp($o,'e',null,'INFORMACIÓN CASO INDICE',$w);
 $c[]=new cmp('tipo_doc','t','3',$d['tipo_doc'],$w.' '.$o,'Tipo de Documento','tipo_doc',null,null,true,false,'','col-2');
 $c[]=new cmp('documento','t','18',$d['documento'],$w.' '.$o,'Documento','documento',null,null,true,false,'','col-2');
 $c[]=new cmp('nombres','t','50',$d['nombres'],$w.' '.$o,'Nombres','nombres',null,null,false,false,'','col-6');
 $c[]=new cmp('telefono1','n','10',$d['telefono1'],$w.' '.$o,'telefono1','telefono1',null,null,false,false,'','col-35');
 $c[]=new cmp('telefono2','n','10',$d['telefono2'],$w.' '.$o,'telefono2','telefono2',null,null,false,false,'','col-35');
 $c[]=new cmp('telefono3','n','10',$d['telefono3'],$w.' '.$o,'telefono3','telefono3',null,null,false,false,'','col-3');
 $c[]=new cmp('evento1','t','50',$d['evento1'],$w.' '.$o,'evento1','evento1',null,null,false,false,'','col-25');
 $c[]=new cmp('evento2','t','50',$d['evento2'],$w.' '.$o,'evento2','evento2',null,null,false,false,'','col-25');
 $c[]=new cmp('evento3','t','50',$d['evento3'],$w.' '.$o,'evento3','evento3',null,null,false,false,'','col-25');
 $c[]=new cmp('evento4','t','50',$d['evento4'],$w.' '.$o,'evento4','evento4',null,null,false,false,'','col-25');
 $o='infgen';
 $c[]=new cmp($o,'e',null,'INFORMACIÓN GENERAL',$w);
 $c[]=new cmp('idgeo','h','20',$key,$w.' '.$o,'','',null,null,true,$u,'','col-1');
 $c[]=new cmp('estrategia','s','3',$d['estrategia'],$w.' '.$o,'Estrategia','estrategia',null,null,true,$u,'','col-3');
 $c[]=new cmp('subred','s','3',$d['subred'],$w.' '.$o,'Subred','subred',null,null,true,$u,'','col-3');
 $c[]=new cmp('zona','s','3',$d['zona'],$w.' '.$o,'Zona','zona',null,null,true,$u,'','col-4');
 $c[]=new cmp('localidad','s','3',$d['localidad'],$w.' '.$o,'Localidad','localidad',null,null,false,$u,'','col-2',false,['upz']);
 $c[]=new cmp('upz','s','3',$d['upz'],$w.' '.$o,'Upz','upz',null,null,false,$u,'','col-2',false,['bar']);
 $c[]=new cmp('barrio','s','8',$d['barrio'],$w.' '.$o,'Barrio','barrio',null,null,false,$u,'','col-2');
 $c[]=new cmp('territorio','s','3',$d['territorio'],$w.' '.$o,'Territorio','territorio',null,null,false,$u,'','col-2');
 $c[]=new cmp('microterritorio','s','3',$d['microterritorio'],$w.' '.$o,'Microterritorio','microterritorio',null,null,false,$u,'','col-2');
 $c[]=new cmp('sector_catastral','n','6',$d['sector_catastral'],$w.' '.$o,'Sector Catastral (6)','sector_catastral',null,null,true,$u,'','col-2');
 $c[]=new cmp('nummanzana','n','3',$d['nummanzana'],$w.' '.$o,'Nummanzana (3)','nummanzana',null,null,true,$u,'','col-2');
 $c[]=new cmp('predio_num','n','3',$d['predio_num'],$w.' '.$o,'Predio de Num (3)','predio_num',null,null,true,$u,'','col-2');
 $c[]=new cmp('unidad_habit','n','4',$d['unidad_habit'],$w.' '.$o,'Unidad habitacional (3)','unidad_habit',null,null,true,$u,'','col-2');
 $c[]=new cmp('estrato','s','3',$d['estrato'],$w.' '.$o,'Estrato','estrato',null,null,false,$u,'','col-2');
 $c[]=new cmp('direccion','t','50',$d['direccion'],$w.' '.$o,'Direccion','direccion',null,null,false,$u,'','col-4');
 $c[]=new cmp('edi','o',2,'',$w.' '.$o,'Actualiza Dirección ?','edi',null,null,false,true,'','col-2','enableAddr(this,\'adur\',\'adru\',\'zona\');');//enabFiel(this,true,[adi]);updaAddr(this,false,[\'zona\',\'direccion_nueva\',\'vereda_nueva\',\'cordxn\',\'cordyn\'])
 $c[]=new cmp('direccion_nueva','t','50',$d['direccion_nueva'],$w.' adur '.$o,'Direccion Nueva','direccion_nueva',null,null,false,$u,'','col-4');
 
 $c[]=new cmp('vereda','t','50',$d['vereda'],$w.' '.$o,'Vereda','vereda',null,null,false,$u,'','col-4');
 $c[]=new cmp('cordx','t','15',$d['cordx'],$w.' adru '.$o,'Cordx','cordx',null,null,false,$u,'','col-3');
 $c[]=new cmp('cordy','t','15',$d['cordy'],$w.' adru '.$o,'Cordy','cordy',null,null,false,$u,'','col-3');
 $c[]=new cmp('vereda_nueva','t','50',$d['vereda_nueva'],$w.' '.$o,'Vereda Nueva','vereda_nueva',null,null,false,$u,'','col-5');
 $c[]=new cmp('cordxn','t','15',$d['cordx'],$w.' '.$o,'Cordx Nueva','cordx',null,null,false,$u,'','col-25');
 $c[]=new cmp('cordyn','t','15',$d['cordy'],$w.' '.$o,'Cordy Nueva','cordy',null,null,false,$u,'','col-25');

 $c[]=new cmp('asignado','s','3',$d['asignado'],$w.' '.$o,'Asignado','asignado',null,null,false,true,'','col-25');
 $c[]=new cmp('estado_v','s',2,$d['estado_v'],$w.' '.$o,'estado','estado',null,null,true,false,'','col-25','enabFielSele(this,true,[\'motivo_estado\'],[\'5\']);');//hideExpres(\'estado_v\',[\'7\']);
//  $c[]=new cmp('estado_v','s','3',$d['estado_v'],$w.' '.$o,'Estado de V','estado',null,null,true,true,'','col-2');
 $c[]=new cmp('motivo_estado','s','3',$d['motivo_estado'],$w.' '.$o,'Motivo de Estado','motivo_estado',null,null,false,false,'','col-4');


 for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
//  $rta .="<div class='encabezado integrantes'>TABLA DE INTEGRANTES DE LA FAMILIA</div><div class='contenido' id='integrantes-lis' >".lis_integrantes1()."</div></div>";
 return $rta;
}



function opc_estrategia($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=42 and estado='A' ORDER BY 1",$id);
}
function opc_subred($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=72 and estado='A' ORDER BY 1",$id);
}
function opc_zona($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=3 and estado='A' ORDER BY 1",$id);
}
function opc_territorio($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=3 and estado='A' ORDER BY 1",$id);
}
 function opc_microterritorio($id=''){
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
function opc_estrato($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=101 and estado='A' ORDER BY 1",$id);
}
function opc_asignado($id=''){
	// $asig = ($id=='') ? $_SESSION['us_sds'] : $id ;
	return opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`IN('AUX','MED') ORDER BY 2",$id);
}
function opc_estado($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=44 and estado='A' ORDER BY 1",$id);
}
function opc_motivo_estado($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=5 and estado='A' ORDER BY 1",$id);
}
function opc_localidadupz(){
	if($_REQUEST['id']!=''){
		$id=divide($_REQUEST['id']);
		$sql="SELECT idcatadeta 'id',CONCAT(idcatadeta,'-',descripcion) 'desc' FROM `catadeta` WHERE idcatalogo=7 and estado='A' and valor='".$id[0]."' ORDER BY 1";
		$info=datos_mysql($sql);		
		return json_encode($info['responseResult']);
	} 
}
function opc_upzbarrio(){
	if($_REQUEST['id']!=''){
		$id=divide($_REQUEST['id']);
		$sql="SELECT idcatadeta 'id',CONCAT(idcatadeta,'-',descripcion) 'desc' FROM `catadeta` WHERE idcatalogo=20 and estado='A' and valor='".$id[0]."' ORDER BY 1";
		$info=datos_mysql($sql);		
		return json_encode($info['responseResult']);
	} 
}


function get_vsp_asig(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		/* $sql="SELECT estrategia,subred,zona,localidad,upz,barrio,territorio,microterritorio,sector_catastral,direccion,direccion_nueva,nummanzana,predio_num,unidad_habit,vereda,vereda_nueva,
		cordx,cordy,estrato,ifnull(asignado,".$_SESSION['us_sds'].") asignado,estado_v,motivo_estado 
		FROM `hog_geo` "; */
		$sql="SELECT tipo_doc, documento, nombres,telefono1, telefono2, telefono3,FN_CATALOGODESC(87,evento1) evento1,FN_CATALOGODESC(87,evento2) evento2,FN_CATALOGODESC(87,evento3) evento3,FN_CATALOGODESC(87,evento4) evento4,D.estrategia,D.subred,D.localidad,D.upz,D.barrio,D.territorio,D.microterritorio,D.sector_catastral,D.direccion,D.direccion_nueva,D.nummanzana,D.predio_num,D.unidad_habit,D.vereda,vereda_nueva,D.motivo_estado,D.estado_v,D.zona,D.asignado,
		cordx,cordy,estrato 
		FROM vspgeo C LEFT JOIN hog_geo D ON C.estrategia=D.estrategia AND C.sector_catastral=D.sector_catastral AND C.nummanzana=D.nummanzana AND C.predio_num=D.predio_num AND C.unidad_habit=D.unidad_habit AND C.estado_v=D.estado_v
		WHERE  C.estrategia='{$id[0]}' AND C.sector_catastral='{$id[1]}' AND C.nummanzana='{$id[2]}' AND C.predio_num='{$id[3]}' AND C.unidad_habit='{$id[4]}' AND C.estado_v='{$id[5]}'";

// sector_catastral,'_',nummanzana,'_',predio_num,'_',estrategia,'_',estado_v
		$info=datos_mysql($sql);
    	// echo $sql."=>".$_POST['id'];
		return $info['responseResult'][0];
	} 
}

/*

	

	function gra_person(){
		print_r($_POST);
		$id=divide($_POST['idp']);
		if($id[1]==""){
			$sql="UPDATE `personas` SET `tipo_doc`=TRIM(UPPER('{$_POST['tipo_doc']}')),
			`nombre1`=TRIM(UPPER('{$_POST['nombre1']}')),`nombre2`=TRIM(UPPER('{$_POST['nombre2']}')),`apellido1`=TRIM(UPPER('{$_POST['apellido1']}')),
			`apellido2`=TRIM(UPPER('{$_POST['apellido2']}')),`fecha_nacimiento`=TRIM(UPPER('{$_POST['fecha_nacimiento']}')),`sexo`=TRIM(UPPER('{$_POST['sexo']}')),
			`genero`=TRIM(UPPER('{$_POST['genero']}')),`nacionalidad`=TRIM(UPPER('{$_POST['nacionalidad']}')),`discapacidad`=TRIM(UPPER('{$_POST['discapacidad']}')),
			`etnia`=TRIM(UPPER('{$_POST['etnia']}')),`pueblo`=TRIM(UPPER('{$_POST['pueblo']}')),
			`idioma`=TRIM(UPPER('{$_POST['idioma']}')),`regimen`=TRIM(UPPER('{$_POST['regimen']}')),`eapb`=TRIM(UPPER('{$_POST['eapb']}')),
			`localidad`=TRIM(UPPER('{$_POST['localidad']}')),`upz`=TRIM(UPPER('{$_POST['upz']}')),`direccion`=TRIM(UPPER('{$_POST['direccion']}')),
			`telefono1`=TRIM(UPPER('{$_POST['telefono1']}')),`telefono2`=TRIM(UPPER('{$_POST['telefono2']}')),`telefono3`=TRIM(UPPER('{$_POST['telefono3']}')),
			`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
			WHERE idpersona =TRIM(UPPER('{$id[0]}')) AND tipo_doc=TRIM(UPPER('{$id[1]}'))";
			   echo $sql;
			//   echo $sql."    ".$rta;
		}else{
			$sql="INSERT INTO personas VALUES (TRIM(UPPER('{$_POST['idpersona']}')),TRIM(UPPER('{$_POST['tipo_doc']}')),TRIM('{$_POST['nombre1']}'),TRIM(UPPER('{$_POST['nombre2']}')),
			TRIM(UPPER('{$_POST['apellido1']}')),TRIM(UPPER('{$_POST['apellido2']}')),TRIM(UPPER('{$_POST['fecha_nacimiento']}')),TRIM(UPPER('{$_POST['sexo']}')),
			TRIM(UPPER('{$_POST['genero']}')),TRIM(UPPER('{$_POST['nacionalidad']}')),TRIM(UPPER('{$_POST['discapacidad']}')),TRIM(UPPER('{$_POST['etnia']}')),
			TRIM(UPPER('{$_POST['pueblo']}')),TRIM(UPPER('{$_POST['idioma']}')),TRIM(UPPER('{$_POST['regimen']}')),TRIM(UPPER('{$_POST['eapb']}')),
			TRIM(UPPER('{$_POST['localidad']}')),TRIM(UPPER('{$_POST['upz']}')),TRIM(UPPER('{$_POST['direccion']}')),TRIM(UPPER('{$_POST['telefono1']}')),
			TRIM(UPPER('{$_POST['telefono2']}')),TRIM(UPPER('{$_POST['telefono3']}')),TRIM(UPPER('{$_SESSION['us_sds']}')),
			DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL)";
			echo $sql;
		}
	
		  $rta=dato_mysql($sql);
		  
		   //return "correctamente";
		  return $rta;
		} */

 
function gra_vsp_asig(){
	$id=divide($_POST['idgeo']);
	/* $rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
	$usu=divide($rta["responseResult"][0]['usu']); */
	$sql1="UPDATE hog_geo SET  asignado=trim(upper('{$_POST['asignado']}')),
	       equipo=FN_EQUIPO('{$_POST['asignado']}'),
			usu_update=trim(upper('{$_SESSION['us_sds']}')),
			fecha_update=DATE_SUB(NOW(), INTERVAL 5 HOUR)
			WHERE sector_catastral=$id[0] AND nummanzana=$id[1] and predio_num=$id[2] and unidad_habit=$id[3]  and estrategia =$id[4] and estado_v=$id[5] and sector_catastral=$id[0]";
	// echo $sql1;
  $rta=dato_mysql($sql1);
  return $rta;
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='vsp_asig' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono mapa1' title='Editar Información Geografica' id='".$c['ACCIONES']."' Onclick=\"mostrar('vsp_asig','pro',event,'','lib.php',7);\"></li>"; //setTimeout(hideExpres,1000,'estado_v',['7']);
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
