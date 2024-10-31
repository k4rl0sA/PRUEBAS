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



function lis_asigsegui(){
	$info=datos_mysql("SELECT COUNT(*) total FROM hog_atencion A 
	LEFT JOIN personas P ON A.atencion_idpersona=P.idpersona AND A.atencion_tipodoc=P.tipo_doc 
	  LEFT JOIN asigsegui S ON P.idpersona=S.documento AND P.tipo_doc=S.tipo_doc  
	  LEFT JOIN hog_viv V ON P.vivipersona=V.idviv
	 left join hog_geo G ON V.idgeo=concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v)
	WHERE 1 OR atencion_ordenpsicologia='SI' OR atencion_ordenvacunacion='SI' OR atencion_ordenlaboratorio='SI' OR 
	atencion_ordenimagenes='SI' OR	atencion_ordenmedicamentos='SI' OR atencion_ordenvacunacion='SI'".whe_asigsegui());
	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-asigsegui']))? ($_POST['pag-asigsegui']-1)* $regxPag:0; ////modificar el nombre de la tabla que se requiera[]

	// ,atencion_ordenpsicologia,atencion_ordenvacunacion,atencion_ordenlaboratorio,atencion_ordenimagenes,atencion_ordenmedicamentos,atencion_ordenvacunacion
	$sql="SELECT DISTINCT concat(A.atencion_tipodoc,'_',A.atencion_idpersona) ACCIONES, A.atencion_tipodoc,A.atencion_idpersona Documento,
	CONCAT(P.nombre1, ' ',P.apellido1) AS Nombre,sector_catastral 'sector castastral',nummanzana manzana,predio_num predio,FN_CATALOGODESC(38,S.estado) estado
	FROM hog_atencion A 
	LEFT JOIN personas P ON A.atencion_idpersona=P.idpersona AND A.atencion_tipodoc=P.tipo_doc 
	  LEFT JOIN asigsegui S ON P.idpersona=S.documento AND P.tipo_doc=S.tipo_doc  
	  LEFT JOIN hog_viv V ON P.vivipersona=V.idviv
	 left join hog_geo G ON V.idgeo=concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v)
	 WHERE 1 ";
	$sql.=whe_asigsegui();
	$sql.=" AND (atencion_ordenpsicologia='SI' OR atencion_ordenvacunacion='SI' OR atencion_ordenlaboratorio='SI' OR	atencion_ordenimagenes='SI' OR	atencion_ordenmedicamentos='SI' OR atencion_ordenvacunacion='SI')";
	$sql.=" ORDER BY A.fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"asigsegui",$regxPag);
} 

function whe_asigsegui() {
	$sql = "";
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


function focus_asigsegui(){
 return 'asigsegui';
}

function men_asigsegui(){
 $rta=cap_menus('asigsegui','pro');
 return $rta;
} 


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='asigsegui'){  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  $rta .= "<li class='icono $a cancelar'    title='Cerrar'          Onclick=\"ocultar('".$a."','".$b."');\" >";
  }
  return $rta;
}


function cmp_asigsegui(){
	$rta="";
	$t=['idpersona'=>'','tipo_doc'=>'','asignado'=>''];
	$w='asigpsico';
   //  $d=get_asigpsico();
   $d='';
	 if ($d=="") {$d=$t;}
	$o='asicas';
	$c[]=new cmp($o,'e',null,'ASIGNACIÓN DE CASOS',$w);
	   $c[]=new cmp('id','h',15,$_POST['id'],$w.' '.$o,'id','id',null,'####',false,false);
	   $c[]=new cmp('asignado','s','3',$d['asignado'],$w.' '.$o,'Asignado','asignado',null,null,true,true,'','col-5');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}





function get_asigsegui(){
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

function gra_asigsegui(){
	$id=divide($_POST['id']);
	 $sql="INSERT INTO asigsegui VALUES 
	(NULL,
	TRIM(UPPER('{$id[0]}')),
	TRIM(UPPER('{$id[1]}')),
	TRIM(UPPER('{$_POST['asignado']}')),
	TRIM(UPPER('{$_SESSION['us_sds']}')),
	DATE_SUB(NOW(),INTERVAL 5 HOUR),NULL,NULL,'1');";
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
	return opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE `perfil`IN ('ENF','AUX') AND componente='EAC' AND subred IN(".$subred.") ORDER BY 1",$id);
}



 

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='asigsegui' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono asigna1' title='Asignar Usuario' id='".$c['ACCIONES']."' Onclick=\"mostrar('asigsegui','pro',event,'','lib.php',7);\"></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
