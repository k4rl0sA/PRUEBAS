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

/* 
function lis_hog_geoloc(){
	$info=datos_mysql("SELECT COUNT(*) total FROM `hog_geo`  WHERE estado_v in (1,2,3) AND subred in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."') 
  AND concat(sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estrategia) NOT IN (
	SELECT concat(sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estrategia) 
	FROM hog_geo 
	WHERE estado_v in(4,5,6,7))".whe_hog_geoloc());
	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-hog_geoloc']))? ($_POST['pag-hog_geoloc']-1)* $regxPag:0;
	
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v) ACCIONES,
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
  FROM `hog_geo` 
  WHERE estado_v in (1,2,3) AND subred in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."') 
  AND concat(sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estrategia) NOT IN (
	SELECT concat(sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estrategia) 
	FROM hog_geo 
	WHERE estado_v in(4,5,6,7))";
	$sql.=whe_hog_geoloc();
	$sql.=" ORDER BY nummanzana,predio_num";
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
	$_SESSION['sql_hog_geoloc']=$sql1; //
// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"hog_geoloc",$regxPag);
	}
 */

 
 /* function lis_hog_geoloc() {
    $id  = "CONCAT(sector_catastral, '_', nummanzana, '_', predio_num, '_', unidad_habit, '_', estrategia) NOT IN (
        SELECT CONCAT(sector_catastral, '_', nummanzana, '_', predio_num, '_', unidad_habit, '_', estrategia) 
        FROM hog_geo 
        WHERE estado_v IN (4, 5, 6, 7))";
    
    $total = "SELECT COUNT(*) total
	FROM hog_geo  H
	INNER JOIN usuarios U ON H.subred = U.subred
	WHERE estado_v IN (1, 2, 3) AND U.id_usuario='{$_SESSION['us_sds']}' AND $id " . whe_hog_geoloc();
	echo $total;
    $info = datos_mysql($total);
    $total = $info['responseResult'][0]['total'];

    $regxPag = 5;
    $pag = isset($_POST['pag-hog_geoloc']) ? ($_POST['pag-hog_geoloc'] - 1) * $regxPag : 0;

    $sql = "SELECT CONCAT(H.estrategia, '_', H.sector_catastral, '_', H.nummanzana, '_', H.predio_num, '_', H.unidad_habit, '_', H.estado_v) AS ACCIONES,
    FN_CATALOGODESC(42, H.estrategia) AS estrategia,
    H.sector_catastral,
    H.nummanzana AS Manzana,
    H.predio_num AS predio,
    H.unidad_habit AS 'Unidad Hab',
    FN_CATALOGODESC(3, H.zona) AS zona,
    FN_CATALOGODESC(2, H.localidad) AS 'Localidad',
    H.usu_creo,
    H.fecha_create,
    FN_CATALOGODESC(44, H.estado_v) AS estado
FROM 
    hog_geo H
INNER JOIN usuarios U ON H.subred = U.subred
        WHERE estado_v IN (1, 2, 3) AND U.id_usuario='{$_SESSION['us_sds']}' AND $id " . whe_hog_geoloc() . "
        ORDER BY nummanzana, predio_num
        LIMIT $pag, $regxPag";

		// echo $sql;
    $data = datos_mysql($sql);
    return create_table($total, $data["responseResult"], "hog_geoloc", $regxPag);
} */

function lis_hog_geoloc(){
	$total="SELECT count(*) as total
 FROM (
    SELECT DISTINCT CONCAT(H.estrategia, '_', H.sector_catastral, '_', H.nummanzana, '_', H.predio_num, '_', H.unidad_habit, '_', H.estado_v) AS ACCIONES
    from hog_geo H
INNER JOIN usuarios U ON H.subred = U.subred
LEFT JOIN adscrip A ON H.territorio=A.territorio
WHERE H.estado_v IN (1, 2, 3)
  AND U.id_usuario = '{$_SESSION['us_sds']}'" . whe_hog_geoloc() ."
  AND NOT EXISTS (SELECT 1 FROM hog_geo H2 WHERE H2.sector_catastral = H.sector_catastral
      AND H2.nummanzana = H.nummanzana
      AND H2.predio_num = H.predio_num
      AND H2.unidad_habit = H.unidad_habit
      AND H2.estrategia = H.estrategia
      AND H2.estado_v = 7)) as subquery";
$info = datos_mysql($total);
$total = $info['responseResult'][0]['total'];

$regxPag = 5;
$pag = isset($_POST['pag-hog_geoloc']) ? ($_POST['pag-hog_geoloc'] - 1) * $regxPag : 0;

$sql = "SELECT DISTINCT CONCAT(H.estrategia, '_', H.sector_catastral, '_', H.nummanzana, '_', H.predio_num, '_', H.unidad_habit, '_', H.estado_v) AS ACCIONES,
    FN_CATALOGODESC(42, H.estrategia) AS estrategia,direccion,
    H.sector_catastral,
    H.nummanzana AS Manzana,
    H.predio_num AS predio,
    H.unidad_habit AS 'Unidad Hab',
    FN_CATALOGODESC(3, H.zona) AS zona,
    FN_CATALOGODESC(2, H.localidad) AS 'Localidad',
    H.usu_creo,
    H.fecha_create,
    FN_CATALOGODESC(44, H.estado_v) AS estado
	FROM hog_geo H
		INNER JOIN usuarios U ON H.subred = U.subred
		LEFT JOIN adscrip A ON H.territorio=A.territorio
	WHERE H.estado_v IN (1, 2, 3)
  		AND U.id_usuario = '{$_SESSION['us_sds']}' " . whe_hog_geoloc() ."
  		AND NOT EXISTS (SELECT 1 FROM hog_geo H2 WHERE H2.sector_catastral = H.sector_catastral
      	AND H2.nummanzana = H.nummanzana
      	AND H2.predio_num = H.predio_num
      	AND H2.unidad_habit = H.unidad_habit
      	AND H2.estrategia = H.estrategia
      	AND H2.estado_v = 7) 
    ORDER BY nummanzana, predio_num
    LIMIT $pag, $regxPag";

		//  echo $sql;

		$data = datos_mysql($sql);
    return create_table($total, $data["responseResult"], "hog_geoloc", $regxPag);
}


function whe_hog_geoloc() {
	$sql = "";
	if ($_POST['fseca'])
		$sql .= " AND sector_catastral = '".$_POST['fseca']."'";
	if ($_POST['fmanz'])
		$sql .= " AND nummanzana ='".$_POST['fmanz']."' ";
	if ($_POST['fpred'])
		$sql .= " AND predio_num ='".$_POST['fpred']."' ";
	if ($_POST['festado'])
		$sql .= " AND estado_v ='".$_POST['festado']."'";
	if (isset($_POST['fdigita'])){
		if($_POST['fdigita']) $sql .= " AND asignado ='".$_POST['fdigita']."'";
	}else{
		$sql .= "AND (H.territorio IN (SELECT A.territorio FROM adscrip where A.doc_asignado='{$_SESSION['us_sds']}') 
					OR (asignado='{$_SESSION['us_sds']}'))";
		// $sql .= "AND (H.equipo     IN (SELECT U.equipo from usuarios where id_usuario='{$_SESSION['us_sds']}') OR (asignado='{$_SESSION['us_sds']}') OR (H.territorio IN (SELECT A.territorio FROM adscrip where A.doc_asignado='{$_SESSION['us_sds']}')))";
	}
	return $sql;
}


function focus_hog_geoloc(){
 return 'hog_geoloc';
}


function men_hog_geoloc(){
 $rta=cap_menus('hog_geoloc','pro');
 return $rta;
}


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  $acc=rol($a);
  //print_r($acc);
  if ($a=='hog_geoloc'  && isset($acc['crear']) && $acc['crear']=='SI'){
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
  	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  }
  return $rta;
}


function cmp_hog_geoloc(){
 $rta="";
 
 $hoy=date('Y-m-d');

 $t=['estrategia'=>'','subred'=>'','zona'=>'','localidad'=>'','upz'=>'','barrio'=>'','territorio'=>'','territorio'=>'','microterritorio'=>'','sector_catastral'=>'','direccion'=>'',
 'direccion_nueva'=>'','nummanzana'=>'','predio_num'=>'','unidad_habit'=>'','vereda'=>'','vereda_nueva'=>'',
 'cordx'=>'','cordy'=>'','estrato'=>'','asignado'=>'','estado_v'=>'','motivo_estado'=>'','usuario'=>'','nombres'=>'','telefonos'=>'','eventos'=>'','tipo_doc'=>'','documento'=>'','nombres'=>'','telefono1'=>'','telefono2'=>'','telefono3'=>'','fuente'=>'','priorizacion'=>'','observacion'=>''];

 $w='hog_geoloc';
 $d=get_hog_geoloc(); 
 if ($d=="") {$d=$t;}
 $u=($d['sector_catastral']=='')?true:false;
 $key=$d['sector_catastral'].'_'.$d['nummanzana'].'_'.$d['predio_num'].'_'.$d['unidad_habit'].'_'.$d['estrategia'].'_'.$d['estado_v'];
 $asig = (perfil1()=='GEO' || perfil1()=='ADM' ) ? true : false ;
 $esta = (perfil1()!=='GEO') ? true : false;

 $e=get_vsp_asig();
 $f=get_asigruteo();
 if($e=="") {$e=$t;}
 if($f=="") {$f=$t;}


 if($e['usuario']!==''){
 	$o='plcufa';
 	$c[]=new cmp($o,'e',null,'INFROMACIÓN COMPLEMENTARIA PLAN DE CUIDADO FAMILIAR',$w);
 	$c[]=new cmp('tipo_doc','t','50',$e['usuario'],$w.' '.$o,'Usuario','',null,null,false,false,'','col-25');
 	$c[]=new cmp('nombres','t','50',$e['nombres'],$w.' '.$o,'Nombres','',null,null,false,false,'','col-4');
 	$c[]=new cmp('telefono1','n','40',$e['telefonos'],$w.' '.$o,'Telefonos','',null,null,false,false,'','col-35');
 	$c[]=new cmp('evento1','t','100',$e['eventos'],$w.' '.$o,'evento1','evento1',null,null,false,false,'','col-10');
 }

 if($f['documento']!==''){
	$o='infrut';
	$c[]=new cmp($o,'e',null,'INFROMACIÓN COMPLEMENTARIA RUTEO',$w);
	$c[]=new cmp('fuente','s',3,$f['fuente'],$w.' '.$o,'fuente','fuente',null,null,false,false,'','col-3');
	$c[]=new cmp('priorizacion','s',3,$f['priorizacion'],$w.' '.$o,'priorizacion','priorizacion',null,null,false,false,'','col-3');
	$c[]=new cmp('tipo_doc','t','3',$f['tipo_doc'],$w.' '.$o,'Tipo de Documento','tipo_doc',null,null,true,false,'','col-2');
	$c[]=new cmp('documento','t','18',$f['documento'],$w.' '.$o,'Documento','documento',null,null,true,false,'','col-2');
	$c[]=new cmp('nombres','t','50',$f['nombres'],$w.' '.$o,'Nombres','nombres',null,null,false,false,'','col-55');
	$c[]=new cmp('telefono1','n','10',$f['telefono1'],$w.' '.$o,'telefono1','',null,null,false,false,'','col-15');
	$c[]=new cmp('telefono2','n','10',$f['telefono2'],$w.' '.$o,'telefono2','',null,null,false,false,'','col-15');
	$c[]=new cmp('telefono3','n','10',$f['telefono3'],$w.' '.$o,'telefono3','',null,null,false,false,'','col-15');
    $c[]=new cmp('observacion','a','1500',$f['observacion'],$w.' '.$o,'observacion','',null,null,false,false,'','col-10');
}

 $o='infgen';
 $c[]=new cmp($o,'e',null,'INFORMACIÓN GENERAL',$w);
 $c[]=new cmp('idgeo','h','20',$key,$w.' '.$o,'','',null,null,true,$u,'','col-1');
 $c[]=new cmp('estrategia','s','3',$d['estrategia'],$w.' '.$o,'Estrategia','estrategia',null,null,true,$u,'','col-3');
 $c[]=new cmp('subred','s','3',$d['subred'],$w.' '.$o,'Subred','subred',null,null,true,$u,'','col-3');
 $c[]=new cmp('zona','s','3',$d['zona'],$w.' '.$o,'Zona','zona',null,null,true,$u,'','col-4');
 
 $c[]=new cmp('localidad','s','3',$d['localidad'],$w.' '.$o,'Localidad','localidad',null,null,false,$u,'','col-2',false,['upz']);
 $c[]=new cmp('upz','s','3',$d['upz'],$w.' '.$o,'Upz','upz',null,null,false,$u,'','col-25',false,['bar']);
 $c[]=new cmp('barrio','s','8',$d['barrio'],$w.' '.$o,'Barrio','barrio',null,null,false,$u,'','col-35');
 $c[]=new cmp('territorio','t','6',$d['territorio'],$w.' '.$o,'Territorio','territorio',null,null,false,$u,'','col-2');
 
 $c[]=new cmp('microterritorio','t','3',$d['microterritorio'],$w.' '.$o,'Manzana del Cuidado','microterritorio',null,null,false,$u,'','col-2');
 $c[]=new cmp('sector_catastral','n','6',$d['sector_catastral'],$w.' '.$o,'Sector Catastral (6)','sector_catastral',null,null,true,$u,'','col-2');
 $c[]=new cmp('nummanzana','n','3',$d['nummanzana'],$w.' '.$o,'Nummanzana (3)','nummanzana',null,null,true,$u,'','col-15');
 $c[]=new cmp('predio_num','n','3',$d['predio_num'],$w.' '.$o,'Predio de Num (3)','predio_num',null,null,true,$u,'','col-15');
 $c[]=new cmp('unidad_habit','n','4',$d['unidad_habit'],$w.' '.$o,'Unidad habitacional (3)','unidad_habit',null,null,true,$u,'','col-15');
 $c[]=new cmp('estrato','t','2',$d['estrato'],$w.' '.$o,'Estrato','estrato',null,null,false,$u,'','col-15');
 $c[]=new cmp('direccion','t','50',$d['direccion'],$w.' '.$o,'Direccion','direccion',null,null,false,$u,'','col-4');
 $c[]=new cmp('edi','o',2,'',$w.' '.$o,'Actualiza Dirección ?','edi',null,null,false,true,'','col-2','enableAddr(this,\'adur\',\'adru\',\'zona\');');//enabFiel(this,true,[adi]);updaAddr(this,false,[\'zona\',\'direccion_nueva\',\'vereda_nueva\',\'cordxn\',\'cordyn\'])
 $c[]=new cmp('direccion_nueva','t','50',$d['direccion_nueva'],$w.' adur '.$o,'Direccion Nueva','direccion_nueva',null,null,false,$u,'','col-4');
 
 $c[]=new cmp('vereda','t','50',$d['vereda'],$w.' '.$o,'Vereda','vereda',null,null,false,$u,'','col-4');
 $c[]=new cmp('cordx','t','15',$d['cordx'],$w.' '.$o,'Cordx','cordx',null,null,false,$u,'','col-3');
 $c[]=new cmp('cordy','t','15',$d['cordy'],$w.' '.$o,'Cordy','cordy',null,null,false,$u,'','col-3');
 $c[]=new cmp('vereda_nueva','t','50',$d['vereda_nueva'],$w.' adru '.$o,'Vereda Nueva','vereda_nueva',null,null,false,$u,'','col-5');
 $c[]=new cmp('cordxn','t','15',$d['cordx'],$w.' adru '.$o,'Cordx Nueva','cordx',null,null,false,$u,'','col-25');
 $c[]=new cmp('cordyn','t','15',$d['cordy'],$w.' adru '.$o,'Cordy Nueva','cordy',null,null,false,$u,'','col-25');

 $c[]=new cmp('asignado','s','3',$d['asignado'],$w.' '.$o,'Asignado','asignado',null,null,false,$asig,'','col-25');
 $c[]=new cmp('estado_v','s',2,$d['estado_v'],$w.' '.$o,'estado','estado',null,null,true,$esta,'','col-25','enabFielSele(this,true,[\'motivo_estado\'],[\'5\']);');//hideExpres(\'estado_v\',[\'7\']);
 //  $c[]=new cmp('estado_v','s','3',$d['estado_v'],$w.' '.$o,'Estado de V','estado',null,null,true,true,'','col-2');
 $c[]=new cmp('motivo_estado','s','3',$d['motivo_estado'],$w.' '.$o,'Motivo de Estado','motivo_estado',null,null,false,false,'','col-4');


 for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
//  $rta .="<div class='encabezado integrantes'>TABLA DE INTEGRANTES DE LA FAMILIA</div><div class='contenido' id='integrantes-lis' >".lis_integrantes1()."</div></div>";
 return $rta;
}

function opc_fuente($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=33 and estado='A' ORDER BY 1",$id);
}
function opc_priorizacion($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=191 and estado='A' ORDER BY 1",$id);
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
	return opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE componente IN('HOG') and subred in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."') ORDER BY 2",$id);
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


function get_hog_geoloc(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT estrategia,subred,zona,localidad,upz,barrio,territorio,microterritorio,sector_catastral,direccion,direccion_nueva,nummanzana,predio_num,unidad_habit,vereda,vereda_nueva,
		cordx,cordy,estrato,ifnull(asignado,".$_SESSION['us_sds'].") asignado,estado_v,motivo_estado 
		FROM `hog_geo` WHERE  estrategia='{$id[0]}' AND sector_catastral='{$id[1]}' AND nummanzana='{$id[2]}' AND predio_num='{$id[3]}' AND unidad_habit='{$id[4]}' AND estado_v='{$id[5]}'";

// sector_catastral,'_',nummanzana,'_',predio_num,'_',estrategia,'_',estado_v
		$info=datos_mysql($sql);
    	// echo $sql."=>".$_POST['id'];
		return $info['responseResult'][0];
	} 
}

function get_vsp_asig(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT concat(tipo_doc,' - ',documento) usuario, nombres,concat(telefono1,' - ',telefono2,' - ',telefono3) telefonos,
		CONCAT_WS(' - ',FN_CATALOGODESC(87, evento1),FN_CATALOGODESC(87, evento2),FN_CATALOGODESC(87, evento3),FN_CATALOGODESC(87, evento4)) eventos
		 FROM vspgeo C LEFT JOIN hog_geo D ON C.estrategia=D.estrategia AND C.sector_catastral=D.sector_catastral AND C.nummanzana=D.nummanzana AND C.predio_num=D.predio_num AND C.unidad_habit=D.unidad_habit AND C.estado_v=D.estado_v
		WHERE  C.estrategia='{$id[0]}' AND C.sector_catastral='{$id[1]}' AND C.nummanzana='{$id[2]}' AND C.predio_num='{$id[3]}' AND C.unidad_habit='{$id[4]}' AND C.estado_v='{$id[5]}'";
//echo $sql;
// sector_catastral,'_',nummanzana,'_',predio_num,'_',estrategia,'_',estado_v
		$info=datos_mysql($sql);
			return $rta = (!$info['responseResult']) ? '' : $info['responseResult'][0] ;    	
	} 
}

function get_asigruteo(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT tipo_doc, documento, nombres,telefono1, telefono2, telefono3,fuente,priorizacion,observacion 
		FROM eac_ruteo C LEFT JOIN hog_geo D ON C.estrategia=D.estrategia AND C.sector_catastral=D.sector_catastral AND C.nummanzana=D.nummanzana AND C.predio_num=D.predio_num AND C.unidad_habit=D.unidad_habit AND 1=D.estado_v
		WHERE  C.estrategia='{$id[0]}' AND C.sector_catastral='{$id[1]}' AND C.nummanzana='{$id[2]}' AND C.predio_num='{$id[3]}' AND C.unidad_habit='{$id[4]}' AND 1='{$id[5]}'";
		$info=datos_mysql($sql);
    	// echo $sql."=>".$_POST['id'];
		return $rta = (!$info['responseResult']) ? '' : $info['responseResult'][0] ;
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

 
function gra_hog_geoloc(){
	$info=datos_mysql("select equipo as equipo from usuarios where id_usuario='".$_SESSION['us_sds']."';");
	$equipo = (!$info['responseResult']) ? '' : $info['responseResult'][0]['equipo'] ;

	
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
	TRIM(UPPER('{$equipo}')),
	TRIM(UPPER('{$_POST['estado_v']}')),
	TRIM(UPPER('{$_POST['motivo_estado']}')),
	TRIM(UPPER('{$_SESSION['us_sds']}')),
	DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL);";
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
	if ($a=='hog_geoloc' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono mapa1' title='Editar Información Geografica' id='".$c['ACCIONES']."' Onclick=\"mostrar('hog_geoloc','pro',event,'','lib.php',7);\"></li>"; //setTimeout(hideExpres,1000,'estado_v',['7']);
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
