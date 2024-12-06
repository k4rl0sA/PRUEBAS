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
	$rta="<div class='encabezado medid'>TABLA DE ALERTAS</div>
	<div class='contenido' id='alertas-lis'></div></div>";
	// $t=['nombres'=>'','fechanacimiento'=>'','edad'=>'','peso'=>'','talla'=>'','imc'=>'','tas'=>'','tad'=>'','glucometria'=>'','perime_braq'=>'','perime_abdom'=>'','percentil'=>'','zscore'=>'','findrisc'=>'','oms'=>'','alert1'=>'','alert2'=>'','alert3'=>'','alert4'=>'','alert5'=>'','alert6'=>'','alert7'=>'','alert8'=>'','alert9'=>'','alert10'=>'','select1'=>'','selmul1'=>'[]','selmul2'=>'[]','selmul3'=>'[]','selmul4'=>'[]','selmul5'=>'[]','selmul6'=>'[]','selmul7'=>'[]','selmul8'=>'[]','selmul9'=>'[]','selmul10'=>'[]','fecha'=>'','tipo'=>''];
		// if ($d==""){$d=$t;}
	// var_dump($_POST);
	$id=divide($_POST['id']);
	$d='';
    $w="alertas";
	$o='infbas';
	// var_dump($p);
	
		
	$o='alert';
	$c[]=new cmp($o,'e',null,'ALERTAS',$w); 
	$c[]=new cmp('alert1','s',15,$d,$w.' '.$o,'Alerta N° 1','alert',null,null,true,true,'','col-1',"enabAlert(this,'cRoN');",['fselmul1'],false,'alertas.php');
	$c[]=new cmp('selmul1','m',3,$d,$w.' cRoN '.$o,'Descripcion Alerta N° 1','selmul1',null,'',false,false,'','col-4');
	$c[]=new cmp('alert2','s',15,$d,$w.' '.$o,'Alerta N° 2','alert',null,null,false,true,'','col-1',"enabAlert(this,'etv');",['fselmul2'],false,'alertas.php');
	$c[]=new cmp('selmul2','m',3,$d,$w.' etv '.$o,'Descripcion Alerta N° 2','selmul2',null,'',false,false,'','col-4');
	$c[]=new cmp('alert3','s',15,$d,$w.' '.$o,'Alerta N° 3','alert',null,null,false,true,'','col-1',"enabAlert(this,'nut');",['fselmul3'],false,'alertas.php');
	$c[]=new cmp('selmul3','m',3,$d,$w.' nut '.$o,'Descripcion Alerta N° 3','selmul3',null,'',false,false,'','col-4');
	$c[]=new cmp('alert4','s',15,$d,$w.' '.$o,'Alerta N° 4','alert',null,null,false,true,'','col-1',"enabAlert(this,'psi');",['fselmul4'],false,'alertas.php');
	$c[]=new cmp('selmul4','m',3,$d,$w.' psi '.$o,'Descripcion Alerta N° 4','selmul4',null,'',false,false,'','col-4');
	$c[]=new cmp('alert5','s',15,$d,$w.' '.$o,'Alerta N° 5','alert',null,null,false,true,'','col-1',"enabAlert(this,'inf');",['fselmul5'],false,'alertas.php');
	$c[]=new cmp('selmul5','m',3,$d,$w.' inf '.$o,'Descripcigon Alerta N° 5','selmul5',null,'',false,false,'','col-4');
	$c[]=new cmp('alert6','s',15,$d,$w.' '.$o,'Alerta N° 6','alert',null,null,false,true,'','col-1',"enabAlert(this,'muj');",['fselmul6'],false,'alertas.php');
	$c[]=new cmp('selmul6','m',3,$d,$w.' muj '.$o,'Descripcion Alerta N° 6','selmul6',null,'',false,false,'','col-4');
	
	$c[]=new cmp('agen_intra','s',15,$d,$w.' '.$o,'Agendamiento Intramural','rta',null,null,true,true,'','col-1',"fieldsValue('agen_intra','aIM','1',true);");
	$c[]=new cmp('servicio','t',15,$d,$w.' aIM '.$o,'Servicio Agendado','servicio',null,null,false,false,'','col-15');
	$c[]=new cmp('fecha_cita','d','10',$d,$w.' aIM '.$o,'Fecha de la Cita','fecha_cita',null,'',false,false,'','col-15',"validDate(this,0,60);");
	$c[]=new cmp('hora_cita','c','10',$d,$w.' aIM '.$o,'Hora de la Cita','hora_cita',null,'',false,false,'','col-15');
	$c[]=new cmp('lugar_cita','t',15,$d,$w.' aIM '.$o,'Lugar de la Cita','lugar_cita',null,null,false,false,'','col-15');
	
	// $c[]=new cmp('medico','s',15,$d,$w.' der '.$o,'Asignado','medico',null,null,false,false,'','col-5');
	$c[]=new cmp('deriva_pf','s',15,$d,$w.' '.$o,'Deriva a PCF','rta',null,null,true,true,'','col-1',"enabOthSi('deriva_pf','pCf');");
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function get_sesigcole(){
	
}

function gra_sesigcole(){

	return $rta;
}

function opc_tipose($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_tipo_vivienda($id=''){
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
