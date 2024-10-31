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

function lis_homes(){
	$info=datos_mysql("SELECT COUNT(DISTINCT V.idgeo) total from hog_viv V LEFT JOIN `hog_geo` G ON V.idgeo = CONCAT(G.estrategia, '_', G.sector_catastral, '_', G.nummanzana, '_', G.predio_num, '_', G.unidad_habit, '_', G.estado_v) where 1 ".whe_homes()." AND estado_v='7'");
	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-homes']))? ($_POST['pag-homes']-1)* $regxPag:0;
    $sql="SELECT DISTINCT(concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v)) ACCIONES, 
		FN_CATALOGODESC(42,`estrategia`) estrategia, 
		sector_catastral 'Sector Catastral', 
		nummanzana Manzana, 
		predio_num Predio, 
		FN_CATALOGODESC(3,zona) zona, 
		FN_CATALOGODESC(2,localidad) 'Localidad', 
		G.usu_creo,G.fecha_create,FN_CATALOGODESC(44,`estado_v`) estado
		FROM `hog_viv` V 
LEFT JOIN `hog_geo` G ON V.idgeo = CONCAT(G.estrategia, '_', G.sector_catastral, '_', G.nummanzana, '_', G.predio_num, '_', G.unidad_habit, '_', G.estado_v)
 WHERE 1 ";
	$sql.=whe_homes();
	$sql.=" AND estado_v='7' ORDER BY fecha_create DESC";
	$sql.=' LIMIT '.$pag.','.$regxPag;
 echo $sql;
	
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"homes",$regxPag);
}

function whe_homes() {
	$sql = "";
	if ($_POST['fsector'])
		$sql .= " AND sector_catastral = '".$_POST['fsector']."'";
	if ($_POST['fmanz'])
		$sql .= " AND nummanzana = '".$_POST['fmanz']."'";
	if ($_POST['fpred'])
		$sql .= " AND predio_num = '".$_POST['fpred']."'";
	if ($_POST['fdigita'])
		
		$sql .= " AND usu_creo ='".$_POST['fdigita']."'";
	
	return $sql;
}
