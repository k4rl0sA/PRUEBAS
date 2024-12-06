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
	$t=['fecha_int'=>'','activ'=>'','luga'=>'','accion1'=>'','desc_accion1'=>'','accion2'=>'','desc_accion2'=>'','accion3'=>'','desc_accion3'=>'','accion4'=>'','desc_accion4'=>''];
	$d=get_sesigcole();
	if ($d==""){$d=$t;}
	// var_dump($_POST);
	$id=divide($_POST['id']);
    $w="alertas";
	$o='infbas';
	// var_dump($p);
	$days=fechas_app('vivienda');
		
	$o='Secgi';
	$c[]=new cmp($o,'e',null,'SESIONES GRUPALES Y COLECTIVAS',$w);
	$c[]=new cmp('fecha_int','d','10',$d['fecha_int'],$w.' '.$o,'fecha_Intervencion','fecha_int',null,null,true,true,'','col-15',"validDate(this,$days,0);"); 
	$c[]=new cmp('activ','s',15,$d,$w.$d['activ'].$o,'Tipo de activiuldad','rta',null,null,true,true,'','col-25');
	$c[]=new cmp('luga','t',15,$d['luga'].$o,'Lugar','rta',null,nl,true,true,'','col-6',"fieldsValue('agen_intra','aIM','1',true);");
	$c[]=new cmp('accion1','s',3,$d['accion1'],$w.' '.$o,'Accion 1','accion1',null,null,true,true,'','col-3',"selectDepend('accion1','desc_accion1','../crea-fam/plancui.php');");
	$c[]=new cmp('desc_accion1','s',3,$d['desc_accion1'],$w.' '.$o,'Descripcion Accion 1','desc_accion1',null,null,true,true,'','col-5');
    $c[]=new cmp('accion2','s','3',$d['accion2'],$w.' '.$o,'Accion 2','accion2',null,null,false,true,'','col-5',"selectDepend('accion2','desc_accion2','../crea-fam/plancui.php');");
    $c[]=new cmp('desc_accion2','s','3',$d['desc_accion2'],$w.' '.$o,'Descripcion Accion 2','desc_accion2',null,null,false,true,'','col-5');
    $c[]=new cmp('accion3','s','3',$d['accion3'],$w.' '.$o,'Accion 3','accion3',null,null,false,true,'','col-5',"selectDepend('accion3','desc_accion3','../crea-fam/plancui.php');");
    $c[]=new cmp('desc_accion3','s','3',$d['desc_accion3'],$w.' '.$o,'Descripcion Accion 3','desc_accion3',null,null,false,true,'','col-5');
    $c[]=new cmp('accion4','s','3',$d['accion4'],$w.' '.$o,'Accion 4','accion4',null,null,false,true,'','col-5',"selectDepend('accion4','desc_accion4','../crea-fam/plancui.php');");
    $c[]=new cmp('desc_accion4','s','3',$d['desc_accion4'],$w.' '.$o,'Descripcion Accion 4','desc_accion3',null,null,false,true,'','col-5');
	




	// $c[]=new cmp('medico','s',15,$d,$w.' der '.$o,'Asignado','medico',null,null,false,false,'','col-5');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function get_sesigcole(){
	return '';
}

function gra_sesigcole(){

	return $rta;
}

function opc_tipose($id=''){
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
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
