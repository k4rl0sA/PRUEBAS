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



function lis_asigpsico(){
	$info=datos_mysql("SELECT  COUNT(DISTINCT P.idpersona,P.tipo_doc) total FROM  personas P
	INNER JOIN eac_atencion A ON P.tipo_doc = A.atencion_tipodoc AND P.idpersona = A.atencion_idpersona
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN relaciones_geo R ON V.idviv = R.id_viv
LEFT JOIN hog_geo G ON R.id_geo = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario
LEFT JOIN asigpsico S ON A.atencion_idpersona = S.documento AND A.atencion_tipodoc = S.tipo_doc
	
	WHERE A.atencion_ordenpsicologia='SI' ".whe_asigpsico());
	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-asigpsico']))? ($_POST['pag-asigpsico']-1)* $regxPag:0;

	/* $sql="SELECT DISTINCT concat(P.tipo_doc,'_',idpersona) ACCIONES, P.tipo_doc AS 'Tipo Documento',idpersona AS 'N° Documento', CONCAT(nombre1, ' ',apellido1) AS Nombre,
	sector_catastral 'sector catastral',nummanzana manzana,predio_num predio,FN_CATALOGODESC(38,S.estado) estado
	FROM  personas P
	RIGHT JOIN eac_atencion A ON P.idpersona=A.atencion_idpersona AND P.tipo_doc=A.atencion_tipodoc 
	LEFT JOIN asigpsico S ON P.idpersona=S.documento AND P.tipo_doc=S.tipo_doc
	LEFT JOIN hog_viv V ON P.vivipersona=V.idviv
	left join hog_geo G ON V.idgeo=concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v)
	WHERE 1 AND A.atencion_ordenpsicologia='SI' "; */
	$sql="SELECT 
   DISTINCT CONCAT(P.tipo_doc, '_', P.idpersona) AS ACCIONES,
    P.tipo_doc AS 'Tipo Documento',
    P.idpersona AS 'N° Documento',
    CONCAT(P.nombre1, ' ', P.apellido1) AS Nombre, 
    FN_CATALOGODESC(2, G.localidad) AS LOCALIDAD,
    FN_CATALOGODESC(20, G.barrio) AS BARRIO,
    G.sector_catastral AS 'sector catastral',
    G.nummanzana AS manzana,
    G.predio_num AS predio
FROM `personas` P
INNER JOIN eac_atencion A ON P.tipo_doc = A.atencion_tipodoc AND P.idpersona = A.atencion_idpersona
INNER JOIN hog_viv V ON P.vivipersona = V.idviv
INNER JOIN relaciones_geo R ON V.idviv = R.id_viv
INNER JOIN hog_geo G ON R.id_geo = G.idgeo
INNER JOIN usuarios U ON A.usu_creo = U.id_usuario
LEFT JOIN asigpsico S ON A.atencion_idpersona = S.documento AND A.atencion_tipodoc = S.tipo_doc
WHERE (A.atencion_ordenpsicologia = 'SI')";
	$sql.=whe_asigpsico();
	$sql.=" ORDER BY A.fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
//echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"asigpsico",$regxPag);
} 

function whe_asigpsico() {
	$sql = " AND R.subred = (SELECT subred FROM usuarios WHERE id_usuario = '{$_SESSION['us_sds']}' AND estado = 'A') ";
	if ($_POST['fdocumento'])
		$sql .= " AND S.documento = '".$_POST['fdocumento']."'";
	if ($_POST['fseca'])
		$sql .= " AND sector_catastral = '".$_POST['fseca']."'";
	if ($_POST['fmanz'])
		$sql .= " AND nummanzana ='".$_POST['fmanz']."' ";
	if ($_POST['fdigita'])
		$sql .= " AND S.doc_asignado ='".$_POST['fdigita']."'";
	if ($_POST['festado'] && $_POST['festado']<>'NULL' )
		$sql .= " AND S.estado ='".$_POST['festado']."'";
	if ($_POST['festado'] && $_POST['festado']=='NULL' )
		$sql .= " AND S.estado  IS NULL ";
	return $sql;
}


function focus_asigpsico(){
 return 'asigpsico';
}

function men_asigpsico(){
 $rta=cap_menus('asigpsico','pro');
 return $rta;
} 


function cap_menus($a,$b='cap',$con='con') {
  $rta = "";
  $acc=rol($a);
  if ($a=='asigpsico'  && isset($acc['crear']) && $acc['crear']=='SI'){  
    $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
    $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  
  }
  return $rta;
}


function cmp_asigpsico(){
 $rta="";
 $t=['idpersona'=>'','tipo_doc'=>'','asignado'=>''];
 $w='asigpsico';
//  $d=get_asigpsico();
$d='';
  if ($d=="") {$d=$t;}
 $o='asicas';
 $c[]=new cmp($o,'e',null,'ASIGNACIÓN DE CASOS',$w);
	$c[]=new cmp('id','h',15,$_POST['id'],$w.' '.$o,'id','id',null,'####',false,false);
	/* $rta .="<div class='encabezado asigna'>TABLA DE ASIGNACION DE PSICOLOGIA</div>
	<div class='contenido' id='datos-lis' >".lista_asignados()."</div></div>"; */
	$c[]=new cmp('asignado','s','3',$d['asignado'],$w.' '.$o,'Asignado','asignado',null,null,true,true,'','col-5');
 for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
 return $rta;
}

/* function lista_asignados(){ //revisar
	$id=divide($_POST['id']);
		$sql="SELECT concat(idpersona,'_',tipo_doc,'_',vivipersona) ACCIONES,idpersona 'Identificación',FN_CATALOGODESC(1,tipo_doc) 'Tipo de Documento',
		concat_ws(' ',nombre1,nombre2,apellido1,apellido2) 'Nombre',fecha_nacimiento 'Nació',
		FN_CATALOGODESC(21,sexo) 'Sexo',FN_CATALOGODESC(19,genero) 'Genero',FN_CATALOGODESC(30,nacionalidad) 'Nacionalidad'
		FROM `personas` 
			WHERE '1'='1' and vivipersona='".$id[0]."'";
		$sql.=" ORDER BY fecha_create";
		// echo $sql;
			$datos=datos_mysql($sql);
		return panel_content($datos["responseResult"],"datos-lis",5);
		} */

function focus_person(){
	return 'person';
}




/* 
function get_asigpsico(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
		$usu=divide($rta["responseResult"][0]['usu']);
		// var_dump($usu[1]);
		$subred = ($usu[1]=='ADM') ? '1,2,3,4,5' : $usu[2] ;
		$sql="SELECT nombre FROM usuarios where `perfil`='PSI' AND subred IN(".$subred.") ORDER BY 1";
		$info=datos_mysql($sql);
		return $info['responseResult'][0];
	} 
}
 */


function gra_asigpsico(){
	$id=divide($_POST['id']);
	 $sql="INSERT INTO asigpsico VALUES 
	(NULL,
	TRIM(UPPER('{$id[0]}')),
	TRIM(UPPER('{$id[1]}')),
	TRIM(UPPER('{$_POST['asignado']}')),
	TRIM(UPPER('{$_SESSION['us_sds']}')),
	DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'1');";
	// echo $sql;
  $rta=dato_mysql($sql);
  return $rta;
}


 function opc_tipo_doc($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_asignado($id=''){
	$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
	$usu=divide($rta["responseResult"][0]['usu']);
	$subred = ($usu[1]=='ADM') ? '1,2,3,4,5' : $usu[2] ;
	return opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`='PSIEAC' AND subred IN(".$subred.") AND componente='EAC' ORDER BY 2",$id);
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='asigpsico' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono asigna1' title='Asignar Usuario' id='".$c['ACCIONES']."' Onclick=\"mostrar('asigpsico','pro',event,'','lib.php',7);\"></li>";
	}
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
