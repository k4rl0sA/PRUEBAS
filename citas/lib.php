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

function lis_solcita(){
	$info=datos_mysql("SELECT COUNT(*) total FROM adm_usunew
	WHERE 1 ".whe_solcita());
	$total=$info['responseResult'][0]['total'];
	$regxPag=10;
	$pag=(isset($_POST['pag-creausu']))? ($_POST['pag-creausu']-1)* $regxPag:0; 

	
	$sql="SELECT id_usu Caso,DOCUMENTO,NOMBRES,CORREO,PERFIL,TERRITORIO,BINA,COMPONENTE,USU_CREO CREO,FECHA_CREATE CREO,ESTADO
	FROM adm_usunew 
	 WHERE 1 ";
	$sql.=whe_solcita();
	$sql.=" ORDER BY fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"creausu",$regxPag);
} 

function whe_solcita() {
    $sql = "";
	if ($_POST['fidpersona'])
		$sql .= " AND id_persona like '%".$_POST['fidpersona']."%'";
	if ($_POST['fdigita'])
		$sql .= " AND usu_creo ='".$_POST['fdigita']."' ";
	if ($_POST['festado'])
		$sql .= " AND estado = '".$_POST['festado']."' ";
	if ($_POST['fdes']) {
		if ($_POST['fhas']) {
			$sql .= " AND fecha_create >='".$_POST['fdes']." 00:00:00' AND fecha_create <='".$_POST['fhas']." 23:59:59'";
		} else {
			$sql .= " AND fecha_create >='".$_POST['fdes']." 00:00:00' AND fecha_create <='". $_POST['fdes']." 23:59:59'";
		}
	}
	return $sql;
}


function focus_solcita(){
 return 'creausu';
}

function men_solcita(){
 $rta=cap_menus('creausu','pro');
 return $rta;
} 


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='creausu'){  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
  	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  }
  return $rta;
}


function cmp_solcita(){
	$t=['id_persona'=>'','tipo_doc'=>'','nombre1'=>'','nombre2'=>'','apellido1'=>'','apellido2'=>'',
 'fecha_nacimiento'=>'','genero'=>'','observaciones'=>'','fecha'=>'','direccion'=>'','nacionalidad'=>'',
 'tipo_cita'=>'','tel1'=>'','tel2'=>'','tel3'=>'','fecha_create'=>'','motivo'=>'',
 'sexo'=>'','tipo'=>'','card'=>'','gast'=>'','gine'=>'','mein'=>'','nudi'=>'','ofta'=>'','ortr'=>'','pedi'=>'',
 'psic'=>'','psiq'=>'','urol'=>'','pyd'=>''];
 $w='frecuencia';
  $d='';//get_solcita(); 
  //~ var_dump($d);
 if ($d=="") {$d=$t;}
 $u=($d['id_persona']=='')?true:false;
 $o='percit';
 $key='dOC';
 $rta=" <span class='mensaje' id='".$w."-msj' ></span>";
 $c[]=new cmp($o,'e',null,'SOLICITAR CITA',$w);
 $c[]=new cmp('key','h',50,$_POST['id'],$w.' '.$o,'',0,'','','',false,'','col-4');
  //~ $c[]=new cmp('ipe','h',10,$_POST['id'],$w,'','idp',null,'','',''); 
 $c[]=new cmp('idp','n',18,$d['id_persona'],$w.' '.$o.' '.$key,'N° Identificación',0,'rgxdfnum','#################',true,$u,'','col-4');
 $c[]=new cmp('tdo','s',3,$d['tipo_doc'],$w.' '.$o.' '.$key,'Tipo Documento','tipo_doc',null,null,true,$u,'','col-3',,"getDatForm('dOC','person',['percit']);");
 $c[]=new cmp('no1','t',20,$d['nombre1'],$w.' '.$o,'Primer Nombre','nombre1',null,null,false,false,'','col-3');
 $c[]=new cmp('no2','t',20,$d['nombre2'],$w.' '.$o,'Segundo Nombre','nombre2',null,null,false,false,'','col-4');
 $c[]=new cmp('ap1','t',20,$d['apellido1'],$w.' '.$o,'Primer Apellido','apellido1',null,null,false,false,'','col-3');
 $c[]=new cmp('ap2','t',20,$d['apellido2'],$w.' '.$o,'Segundo Apellido','apellido2',null,null,false,false,'','col-3');
 $c[]=new cmp('edi','o',2,'',$w.' '.$o,'Actualiza Fecha ?','edi',null,null,false,true,'','col-2','enableUpd(this,\'afec\');');
 $c[]=new cmp('fen','d',10,$d['fecha_nacimiento'],$w.' afec '.$o,'Fecha de Nacimiento','fecha_nacimiento',null,null,false,false,'','col-4');
 $c[]=new cmp('gen','s',3,$d['sexo'],$w.' '.$o,'Sexo','genero',null,null,false,false,'','col-3');
 $c[]=new cmp('te1','t',10,$d['tel1'],$w.' '.$o,'Teléfono 1','',null,null,true,false,'','col-3');
 $c[]=new cmp('te2','t',10,$d['tel2'],$w.' '.$o,'Teléfono 2','',null,null,true,false,'','col-4');
 $c[]=new cmp('te3','h',10,$d['tel3'],$w.' '.$o,'Teléfono 3','',null,null,true,false,'','col-4');
 $c[]=new cmp('edi2','o',2,'',$w.' '.$o,'Actualiza Dirección ?','edi',null,null,false,true,'','col-2','enableUpd(this,\'adir\');');
 $c[]=new cmp('dir','t',20,$d['direccion'],$w.' adir '.$o,'Direccion','',null,null,false,false,'','col-6');
 $c[]=new cmp('tipo','s',3,$d['tipo'],$w.' '.$o,'Tipo de Cita','tipo_cita',null,null,false,true,'','col-3');
 

 $w='especialidades';
 $o='espec';
 $c[]=new cmp(null,'e',null,'ESPECIALIDADES',$w);
 $c[]=new cmp('card','o',2,$d['card'],$w.' '.$o,'Cardiologia',null,null,true,false,'','col-0');
$c[]=new cmp('gast','o',2,$d['gast'],$w.' '.$o,'Gastroenterologia',null,null,true,false,'','col-0');
$c[]=new cmp('gine','o',2,$d['gine'],$w.' '.$o,'Ginecobstetricia',null,null,true,false,'','col-0');
$c[]=new cmp('mein','o',2,$d['mein'],$w.' '.$o,'Medicina Interna',null,null,true,false,'','col-0');
$c[]=new cmp('nudi','o',2,$d['nudi'],$w.' '.$o,'Nutricion y Dietetica',null,null,true,false,'','col-0');
$c[]=new cmp('ofta','o',2,$d['ofta'],$w.' '.$o,'Oftalmologia',null,null,true,false,'','col-0');
$c[]=new cmp('ortr','o',2,$d['ortr'],$w.' '.$o,'Ortopedia y/o Traumatologia',null,null,true,false,'','col-0');
$c[]=new cmp('pedi','o',2,$d['pedi'],$w.' '.$o,'Pediatria',null,null,true,false,'','col-0');
$c[]=new cmp('psic','o',2,$d['psic'],$w.' '.$o,'Psicologia',null,null,true,false,'','col-0');
$c[]=new cmp('psiq','o',2,$d['psiq'],$w.' '.$o,'Psiquiatria',null,null,true,false,'','col-0');
$c[]=new cmp('urol','o',2,$d['urol'],$w.' '.$o,'Urologia',null,null,true,false,'','col-0');

 $w='promocion';
 $o='pyd';
 $c[]=new cmp(null,'e',null,'PROMOCION Y DETECCCIÒN TEMPRANA',$w);
 $c[]=new cmp('pyd','s',3,$d['pyd'],$w.' '.$o,'Promocion y Detecciòn','pyd',null,null,false,true,'','col-0');
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

function gra_creausu(){
  
	$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
	$usu=divide($rta["responseResult"][0]['usu']);
 
	$rta=datos_mysql("select FN_CATALOGODESC(218,'".$_POST['perfil']."') AS perfil ,FN_CATALOGODESC(202,'".$_POST['territorio']."') AS terr,FN_CATALOGODESC(217,'".$_POST['bina']."') AS bina;");
	$data=$rta["responseResult"][0];


	$sql1 = "INSERT INTO usuarios VALUES (?,?,?,?,?,?,?,?,?)";
	if (isset($data['bina'])) {
		$equ =$data['bina'];
	} elseif(isset($data['terr'])) {
		$equ =$data['terr'];
	}else{
		$equ ='';
	}
	
	$params1 = [
		['type' => 'i', 'value' => $_POST['documento']],
		['type' => 's', 'value' => $_POST['nombre']],
		['type' => 's', 'value' => $_POST['correo']],
		['type' => 'z', 'value' => '$2y$10$U1.jyIhJweaZQlJK6jFauOAeLxEOTJX8hlWzJ6wF5YVbYiNk1xfma'],
		['type' => 's', 'value' => $data['perfil']],
		['type' => 'i', 'value' => $usu[2]],
		['type' => 's', 'value' => $equ],
		['type' => 's', 'value' => $usu[4]],
		['type' => 's', 'value' => 'P']];
		$rta2 = mysql_prepd($sql1, $params1);

	if (strpos($rta2, "Correctamente")!== false) {
		$rta = "Se ha Insertado: 1 Registro Correctamente.";
		$sql = "INSERT INTO adm_usunew VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
   

   $params = [
	['type' => 'i', 'value' => NULL],
	['type' => 'i', 'value' => $_POST['documento']],
	['type' => 's', 'value' => $_POST['nombre']],
	['type' => 's', 'value' => $_POST['correo']],
	['type' => 's', 'value' => $data['perfil']],
	['type' => 's', 'value' => $data['terr']],
	['type' => 's', 'value' => $data['bina']],
	['type' => 'i', 'value' => $usu[2]],
	['type' => 's', 'value' => $usu[4]],
	['type' => 'i', 'value' => $_SESSION['us_sds']],
	['type' => 's', 'value' => date("Y-m-d H:i:s")],
	['type' => 's', 'value' => NULL],
	['type' => 's', 'value' => NULL],
	['type' => 's', 'value' => 'R']];
	$rta1 = mysql_prepd($sql, $params);
	} else {
		$rta = "Error: msj['No se puede crear la solicitud, el usuario ya se ha creado anteriormente']";
	}
	return $rta;
}

function opc_tipo_doc($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_genero($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
}

function opc_pyd($id=''){
	return opc_sql("SELECT `idcatadeta`,concat(idcatadeta,' - ',descripcion) FROM `catadeta` WHERE idcatalogo=39 and estado='A' AND idcatadeta<16 ORDER BY LENGTH(idcatadeta), idcatadeta",$id);	
}
function opc_tipo_cita($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=146 and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta",$id);	
}
function opc_estados($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=11 and estado='A' ORDER BY 1",$id);
}


function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// var_dump($a);
// var_dump($rta);
	if ($a=='creausu' && $b=='acciones'){
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