<?php
ini_set('display_errors','1');
require_once '../libs/gestion.php';
$perf=perfil($_POST['tb']);
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


function lis_asigrelevo(){
	$info=datos_mysql("SELECT COUNT(*) total 
	FROM personas P 
  LEFT JOIN asigrelevo S ON P.idpersona = S.documento AND P.tipo_doc = S.tipo_doc 
  LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
  LEFT JOIN hog_geo G ON V.idpre = G.idgeo
  WHERE P.cuidador = 'SI'".whe_asigrelevo());

	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-asigrelevo']))? ($_POST['pag-asigrelevo']-1)* $regxPag:0;

	$sql="SELECT 
	CONCAT(P.tipo_doc, '_', P.idpersona) AS ACCIONES, 
	P.tipo_doc AS 'Tipo Documento',
	P.idpersona AS 'N° Documento',
	CONCAT(P.nombre1, ' ', P.apellido1) AS Nombre,
	G.subred AS 'subred',
	G.sector_catastral AS 'sector castastral',
	G.nummanzana AS manzana,
	G.predio_num AS predio,
	FN_CATALOGODESC(38, S.estado) AS estado     
  FROM personas P 
  LEFT JOIN asigrelevo S ON P.idpersona = S.documento AND P.tipo_doc = S.tipo_doc 
  LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
  LEFT JOIN hog_geo G ON V.idpre = G.idgeo
  WHERE P.cuidador = 'SI'";
/* 	$sql="SELECT DISTINCT concat(P.tipo_doc,'_',idpersona) ACCIONES, P.tipo_doc AS 'Tipo Documento',idpersona AS 'N° Documento', CONCAT(nombre1, ' ',apellido1) AS Nombre,
	sector_catastral 'sector castastral',nummanzana manzana,predio_num predio,FN_CATALOGODESC(38,S.estado) estado     
	FROM personas P 
	    LEFT JOIN asigrelevo S ON P.idpersona=S.documento AND P.tipo_doc=S.tipo_doc 
		LEFT JOIN hog_viv V ON P.vivipersona=V.idviv
		left join hog_geo G ON V.idgeo=concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v)
	        WHERE P.cuidador='SI'  and G.subred=(SELECT subred FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}' AND estado = 'A')"; */
	$sql.=whe_asigrelevo();
	// echo $sql;
	$sql.=" ORDER BY P.fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"asigrelevo",$regxPag);
} 

function whe_asigrelevo() {
	$sql = " AND G.subred = (SELECT subred FROM usuarios WHERE id_usuario = '{$_SESSION['us_sds']}' AND estado = 'A') ";
	if ($_POST['fseca'])
		$sql .= "AND sector_catastral = '".$_POST['fseca']."' ";
	if ($_POST['fmanz'])
		$sql .= "AND nummanzana ='".$_POST['fmanz']."' ";
	if ($_POST['fdigita'])
		$sql .= "AND S.doc_asignado =".$_POST['fdigita']."";
	if ($_POST['fdoc'])
		$sql .= "AND P.idpersona like '%".$_POST['fdoc']."%'";
	if ($_POST['festado'] && $_POST['festado']!='NULL')
		$sql .= "  AND S.estado ='".$_POST['festado']."'";
	if ($_POST['festado'] && $_POST['festado']=='NULL' )
		$sql .= " AND S.estado  IS NULL ";
		// echo $_POST['festado'];
		// '&fseca=&fmanz=&fpred=&festado=NULL&fdigita=52970051'
	return $sql;
}


function focus_asigrelevo(){
 return 'asigrelevo';
}

function men_asigrelevo(){
 $rta=cap_menus('asigrelevo','pro');
 return $rta;
} 


function cap_menus($a,$b='cap',$con='con') {
  $rta = "";
  $acc=rol($a);
  if ($a=='asigrelevo' && isset($acc['crear']) && $acc['crear']=='SI'){  
    $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
    $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
    
  }
  return $rta;
}


function cmp_asigrelevo(){
	$rta="";
	$t=['idpersona'=>'','tipo_doc'=>'','asignado'=>''];
	$w='asigpsico';
    //  $d=get_asigpsico();
    $d='';
	 if ($d=="") {$d=$t;}
	$o='asicas';
	$c[]=new cmp($o,'e',null,'ASIGNACIÓN DE CASOS',$w);
	$c[]=new cmp('id','h',15,$_POST['id'],$w.' '.$o,'id','id',null,'####',false,false);
	$c[]=new cmp('estado_cierre','s',3,$d['estado_cierre'],$w.' '.$o,'estado_cierre','estado_cierre',null,null,true,true,'','col-5',"enbValue('estado_cierre','Rel','7');");
	$c[]=new cmp('asignado','s',3,$d['asignado'],$w.' Rel '.$o,'Asignado','asignado',null,null,true,true,'','col-5');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}





function get_asigrelevo(){
	if($_POST['idgeo']=='0'){
		return "";
	}else{
		$id=divide($_POST['idgeo']);
		$sql="SELECT estrategia,subred,zona,localidad,upz,barrio,territorio,microterritorio,sector_catastral,direccion,direccion_nueva,nummanzana,predio_num,unidad_habit,vereda,vereda_nueva,
		cordx,cordy,estrato,asignado,estado_v,motivo_estado 
		FROM `hog_geo` WHERE  estrategia='{$id[0]}' AND sector_catastral='{$id[1]}' AND nummanzana='{$id[2]}' AND predio_num='{$id[3]}' AND unidad_habit='{$id[4]}' AND estado_v='{$id[5]}'";

		$info=datos_mysql($sql);
		return $info['responseResult'][0];
	} 
}



function gra_asigrelevo(){
	$id=divide($_POST['id']);
	$estado_cierre = cleanTxt($_POST['estado_cierre']);
	$sql="INSERT INTO asigrelevo VALUES 
	(NULL,
	$estado_cierre,
	TRIM(UPPER('{$id[0]}')),
	TRIM(UPPER('{$id[1]}')),
	TRIM(UPPER('{$_POST['asignado']}')),
	TRIM(UPPER('{$_SESSION['us_sds']}')),
	DATE_SUB(NOW(),INTERVAL 5 HOUR),NULL,NULL,'1');";
	// echo $sql;
  $rta=dato_mysql($sql);
  return $rta;
}

function opc_estado_cierre($id=''){
	return opc_sql("SELECT `idcatadeta`, descripcion FROM `catadeta` WHERE idcatalogo=219 AND estado='A' ORDER BY 1", $id);
}

function opc_tipo_doc($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_asignado($id=''){
	$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
	$usu=divide($rta["responseResult"][0]['usu']);
	$subred = ($usu[1]=='ADM') ? '1,2,3,4,5' : $usu[2] ;
	return opc_sql("SELECT `id_usuario`,concat(perfil,' - ',nombre) FROM `usuarios` WHERE `perfil`IN ('TSOREL','ENFREL','RELENF','AUXREL','TOPREL','LEFREL','LARREL') AND componente='EAC' AND subred IN(".$subred.") ORDER BY (2)",$id);
}




function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='asigrelevo' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono asigna1' title='Asignar Usuario' id='".$c['ACCIONES']."' Onclick=\"mostrar('asigrelevo','pro',event,'','lib.php',7);\"></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
