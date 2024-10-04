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


function lis_asigpred(){
}

function focus_asigpred(){
 return 'asigpred';
}


function men_asigpred(){
 $rta=cap_menus('asigpred','pro');
 return $rta;
}


function cap_menus($a,$b='cap',$con='con') {
  $rta = "";
  $acc=rol($a);
  if ($a=='asigpred'  && isset($acc['crear']) && $acc['crear']=='SI'){  
    $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
    $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
	// $rta .= "<li class='icono $a crear'  title='Actualizar'   id='".print_r($_REQUEST)."'   Onclick=\"\"></li>";
  }
  return $rta;
}

function cmp_asigpred(){
	$rta="";
	$t=['id_deriva'=>'','documento'=>'','doc_asignado'=>'','cod_predio'=>'','cod_familia'=>'','tipo_doc'=>'','nombres'=>'','fechanacio'=>'','edad'=>'','sexo'=>'','localidad'=>'','direccion'=>'','telefono1'=>'','telefono2'=>'','telefono3'=>'','predio'=>'','family'=>'','rol'=>'','asignado'=>''];
	$d='';
	if ($d==""){$d=$t;}
	$w='asigpred';
	$o='deriva';
	$key='dOC';
	$c[]=new cmp($o,'e',null,'DATOS DEL USUARIO',$w);
	$c[]=new cmp('id','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('documento','t','20',$d['documento'],$w.' '.$o.' '.$key,'N° Identificación','documento',null,'',true,true,'','col-5');
	$c[]=new cmp('tipo_doc','s',3,$d['tipo_doc'],$w.' '.$o.' '.$key,'tipo doc','tipo_doc',null,'',true,true,'','col-5',"getDatForm('dOC','person',['deriva']);");
	$c[]=new cmp('nombres','t','50',$d['nombres'],$w.' '.$o,'nombres','nombre',null,'',false,false,'','col-5');
	
	$c[]=new cmp('fechanacimiento','d','10',$d['fechanacio'],$w.' '.$o,'fecha nacimiento','fechanacimiento',null,'',false,false,'','col-2'); 
    $c[]=new cmp('edad','n','3',$d['edad'],$w.' '.$o,'edad','edad',null,'',true,false,'','col-15');
	$c[]=new cmp('sexo','s',3,$d['sexo'],$w.' '.$o,'sexo','sexo',null,'',false,false,'','col-15');

	$c[]=new cmp('localidad','s',3,$d['localidad'],$w.' '.$o,'Localidad','localidad',null,'',false,false,'','col-5');
	$c[]=new cmp('direccion','t','20',$d['direccion'],$w.' '.$o,'Direccion','direccion',null,'',false,false,'','col-5');
 	$c[]=new cmp('telefono1','n','10',$d['telefono1'],$w.' '.$o,'Telefono 1','telefono1',null,'',false,false,'','col-15');
	$c[]=new cmp('telefono2','n','10',$d['telefono2'],$w.' '.$o,'Telefono 2','telefono2',null,'',false,false,'','col-15');
	$c[]=new cmp('telefono3','n','10',$d['telefono3'],$w.' '.$o,'Telefono 3','telefono3',null,'',false,false,'','col-15');

	$c[]=new cmp('predio','t','20',$d['predio'],$w.' '.$o,'Cod. Predio','predio',null,'',false,false,'','col-25');
	$c[]=new cmp('family','t','20',$d['family'],$w.' '.$o,'Cod. Familia','family',null,'',false,false,'','col-3');

	$o='infasi';
	$c[]=new cmp($o,'e',null,'GESTIÓN PARA LA ASIGNACIÓN',$w);
	$c[]=new cmp('rol','s',3,$d['rol'],$w.' '.$o,'rol','rol',null,'',true,true,'','col-2',false,['asignado']);
	$c[]=new cmp('asignado','s',3,$d['asignado'],$w.' '.$o,'Asignar A','asignado',null,'',true,true,'','col-5');
 
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}


function get_person(){
	// print_r($_POST);
	$id=divide($_POST['id']);
	$sql="SELECT idpersona,tipo_doc,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,fecha_nacimiento,YEAR(CURDATE())-YEAR(fecha_nacimiento) Edad,sexo,localidad ,direccion,telefono1,telefono2,telefono3,g.idgeo,v.idviv
	from personas p
	LEFT JOIN hog_viv v ON p.vivipersona=v.idviv 
	LEFT JOIN hog_geo g ON v.idpre=g.idgeo 
	WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."')";
	$info=datos_mysql($sql);
	if (!$info['responseResult']) {
		return json_encode (new stdClass);
	}
return json_encode($info['responseResult'][0]);
}

function opc_rol($id=''){
	return opc_sql("SELECT distinct perfil,perfil FROM `usuarios` WHERE  subred in(SELECT subred FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}') AND componente IN(SELECT componente FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}') and estado='A' ORDER BY 1",$id);
}
function opc_asignado($id=''){
	return opc_sql("SELECT id_usuario,nombre FROM `usuarios` WHERE  subred in(SELECT subred FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}') AND componente IN(SELECT componente FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}') AND estado='A' ORDER BY 1",$id);
}
function opc_rolasignado(){
	if($_REQUEST['id']!=''){
		$id=divide($_REQUEST['id']);
		$sql="SELECT id_usuario,CONCAT(id_usuario,'-',nombre) FROM usuarios WHERE subred in(SELECT subred FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}') and componente IN(SELECT componente FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}')  AND estado='A' and perfil='".$id[0]."' ORDER BY 1 ASC";
		$info=datos_mysql($sql);		
		return json_encode($info['responseResult']);
	} 
}

function opc_tipo_doc($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_sexo($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
}

function opc_localidad($id=''){
	return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,'-',descripcion) FROM `catadeta` WHERE idcatalogo=2 ORDER BY cast(idcatadeta as signed)",$id);
}


function gra_asigpred(){
	$documento=cleanTxt($_POST['documento']);
	$asignado = cleanTxt($_POST['asignado']);
	$predio = cleanTxt($_POST['predio']);
	$family = cleanTxt($_POST['family']);

	$sql="INSERT INTO derivacion VALUES(NULL, 
	$documento,
	$asignado,
	$predio,
	$family,
	{$_SESSION['us_sds']},
	DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A');";
	// echo $sql;
  $rta=dato_mysql($sql);
  return $rta;
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($c);
	if ($a=='asigpred' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono admsi1' title='Información de la Facturación' id='".$c['ACCIONES']."' Onclick=\"mostrar('asigpred','pro',event,'','lib.php',7);\"></li>"; //setTimeout(hideExpres,1000,'estado_v',['7']);
		$rta.="<li class='icono crear' title='Nueva Admisión' id='".$c['ACCIONES']."' Onclick=\"newAdmin('{$c['ACCIONES']}');\"></li>";
	}
	if ($a=='adm-lis' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono editar ' title='Editar ' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'asigpred',event,this,'lib.php');Color('adm-lis');\"></li>";  //act_lista(f,this);
	}
	
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>