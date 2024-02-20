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



function lis_creausu(){
	$info=datos_mysql("SELECT COUNT(*) total FROM hog_atencion A 
	LEFT JOIN personas P ON A.atencion_idpersona=P.idpersona AND A.atencion_tipodoc=P.tipo_doc 
	  LEFT JOIN asigsegui S ON P.idpersona=S.documento AND P.tipo_doc=S.tipo_doc  
	  LEFT JOIN hog_viv V ON P.vivipersona=V.idviv
	 left join hog_geo G ON V.idgeo=concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v)
	WHERE 1 OR atencion_ordenpsicologia='SI' OR atencion_ordenvacunacion='SI' OR atencion_ordenlaboratorio='SI' OR 
	atencion_ordenimagenes='SI' OR	atencion_ordenmedicamentos='SI' OR atencion_ordenvacunacion='SI'".whe_creausu());
	$total=$info['responseResult'][0]['total'];
	$regxPag=10;
	$pag=(isset($_POST['pag-creausu']))? ($_POST['pag-creausu']-1)* $regxPag:0; 

	// ,atencion_ordenpsicologia,atencion_ordenvacunacion,atencion_ordenlaboratorio,atencion_ordenimagenes,atencion_ordenmedicamentos,atencion_ordenvacunacion
	$sql="SELECT DISTINCT concat(A.atencion_tipodoc,'_',A.atencion_idpersona) ACCIONES, A.atencion_tipodoc,A.atencion_idpersona Documento,
	CONCAT(P.nombre1, ' ',P.apellido1) AS Nombre,sector_catastral 'sector castastral',nummanzana manzana,predio_num predio,FN_CATALOGODESC(38,S.estado) estado
	FROM hog_atencion A 
	LEFT JOIN personas P ON A.atencion_idpersona=P.idpersona AND A.atencion_tipodoc=P.tipo_doc 
	  LEFT JOIN asigsegui S ON P.idpersona=S.documento AND P.tipo_doc=S.tipo_doc  
	  LEFT JOIN hog_viv V ON P.vivipersona=V.idviv
	 left join hog_geo G ON V.idgeo=concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v)
	 WHERE 1 ";
	$sql.=whe_creausu();
	$sql.=" AND (atencion_ordenpsicologia='SI' OR atencion_ordenvacunacion='SI' OR atencion_ordenlaboratorio='SI' OR	atencion_ordenimagenes='SI' OR	atencion_ordenmedicamentos='SI' OR atencion_ordenvacunacion='SI')";
	$sql.=" ORDER BY A.fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"creausu",$regxPag);
} 

function whe_creausu() {
	$sql = "";
	if ($_POST['festado'] && $_POST['festado']=='NULL' )
		$sql .= " AND S.estado  IS NULL ";
	return $sql;
}


function focus_creausu(){
 return 'creausu';
}

function men_creausu(){
 $rta=cap_menus('creausu','pro');
 return $rta;
} 


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='creausu'){  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  }
  return $rta;
}


function cmp_creausu(){
	$rta="";
	$hoy=date('Y-m-d');
	$t=['gestion'=>'','perfil'=>'','documento'=>'','nombre'=>'','correo'=>'','bina'=>'','territorio'=>''];
	$d='';
	if ($d==""){$d=$t;}
	$w='adm_usuarios';
	$o='infusu';
	$c[]=new cmp($o,'e',null,'GESTIÓN DE USUARIOS',$w);
	$c[]=new cmp('documento','n',20,$d['documento'],$w.' '.$o,'N° Documento','documento',null,'',false,true,'','col-15');
	$c[]=new cmp('nombre','t',50,$d['nombre'],$w.' '.$o,'Nombres y Apellidos','nombre',null,'',false,true,'','col-3');
	$c[]=new cmp('correo','t',30,$d['correo'],$w.' '.$o,'Correo','correo',null,'',false,true,'','col-25');
	$c[]=new cmp('perfil','s',3,$d['perfil'],$w.' '.$o,'Perfil','perfil',null,'',true,true,'','col-1',"enClSeDe('perfil',['TEr','bIN']);");
	$c[]=new cmp('bina','s',3,$d['bina'],$w.' bIN '.$o,'Bina','bina',null,'',false,false,'','col-2');
	$c[]=new cmp('territorio','s',3,$d['territorio'],$w.' TEr '.$o,'Territorio','territorio',null,'',false,false,'','col-2');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}





function get_creausu(){
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

function gra_creausu(){
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



function opc_asignado($id=''){
	$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
	$usu=divide($rta["responseResult"][0]['usu']);
	$subred = ($usu[1]=='ADM') ? '1,2,3,4,5' : $usu[2] ;
	return opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE `perfil`IN ('ENF','AUX') AND componente='EAC' AND subred IN(".$subred.") ORDER BY 1",$id);
}
function opc_perfil($id=''){
	$com=datos_mysql("SELECT CASE WHEN componente = 'EAC' THEN 2 WHEN componente = 'HOG' THEN 1 END as componente FROM usuarios WHERE id_usuario ='{$_SESSION['us_sds']}'");
	$comp = $com['responseResult'][0]['componente'] ;
	// return $comp;
	// var_dump("SELECT CASE WHEN componente = 'EAC' THEN 2 WHEN componente = 'HOG' THEN 1 END as componente FROM usuarios WHERE id_usuario ='{$_SESSION['us_sds']}'");
	return opc_sql("SELECT idcatadeta, descripcion FROM `catadeta` WHERE idcatalogo = 218 AND estado = 'A' AND valor='$comp'",$id);
}
function opc_bina($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=217 and estado='A' and valor=(SELECT subred FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}') ORDER BY 1",$id);
}
function opc_territorio($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=202 and estado='A' and valor=(SELECT subred FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}')  ORDER BY 1",$id);
}
function opc_subred($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=72 and estado='A' and idcatadeta in(1,2,4,3) ORDER BY 1",$id);
}


 

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='creausu' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono asigna1' title='Asignar Usuario' id='".$c['ACCIONES']."' Onclick=\"mostrar('creausu','pro',event,'','lib.php',7);\"></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
