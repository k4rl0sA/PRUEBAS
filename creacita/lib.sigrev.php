<?php
require_once "../libs/gestion.php";
ini_set('display_errors','0');

if (!isset($_SESSION['us_riesgo'])) die("<script>window.top.location.href='/';</script>");
else {
  $rta="";
  switch ($_POST['a']){
  case 'csv': 
    header_csv ($_REQUEST['tb'].'.csv');
    $rs=array('','');    
    echo csv($rs);
    die;
    break;
  default:
    eval('$rta='.$_POST['a'].'_'.$_POST['tb'].'();');
    if (is_array($rta)) json_encode($rta);
	else echo $rta;
  }   
}

function divide($a){
	$id=explode("_", $a);
	return ($id);
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

function lis_solcita(){
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,
	concat(id_persona,'_',tipo_doc,'_',tipo_cita,'_',realizada) ACCIONES,
`id_persona` ID,FN_CATALOGODESC(1,tipo_doc) Tipo_Documento,FN_CATALOGODESC(38,`punto_atencion`) 'Punto de Control',FN_CATALOGODESC(39,tipo_cita) 'Tipo Cita',`realizada`,FN_CATALOGODESC(82,observaciones) Observaciones,IF(motivo = 1,'ORDEN',if(motivo=2,'EXAMEN',motivo)) motivo,`fecha_create`,`estado`
from solcita WHERE '1'='1'";
	$sql.=whe_solcita();
	$sql.=" ORDER BY 10 DESC";
//~ echo $sql;
	$sql1="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,
	concat(id_persona,'_',tipo_doc,'_',tipo_cita,'_',realizada) ACCIONES,
`id_persona` ID,FN_CATALOGODESC(1,tipo_doc) Tipo_Documento,FN_CATALOGODESC(38,`punto_atencion`) 'Punto de Control',FN_CATALOGODESC(39,tipo_cita) 'Tipo Cita',`realizada`,FN_CATALOGODESC(82,observaciones) Observaciones,IF(motivo = 1,'ORDEN',if(motivo=2,'EXAMEN',motivo)) motivo,`fecha_create`,`estado`, usu_creo
from solcita WHERE '1'='1'";
	$sql1.=whe_solcita();
	$sql1.=" ORDER BY 10 DESC";
	$_SESSION['sql_solcita']=$sql1;
	$datos=datos_mysql($sql);
return panel_content($datos["responseResult"],"solcita",19);
}

function focus_solcita(){
 return 'solcita';
}

function men_solcita(){
 $rta=cap_menus('solcita','pro');
 return $rta;
}


function get_solcita(){
	if ($_POST['id']){
		$id=divide($_POST['id']);			
	$sql="SELECT T1.fecha_create,T1.id_persona,T1.tipo_doc,T2.nombre1,T2.nombre2,T2.apellido1,T2.apellido2,T2.fecha_nacimiento,T2.genero,T3.fecha,T1.observaciones,motivo,
	punto_atencion,tipo_cita 
	from solcita T1 
	left join personas T2 ON T1.id_persona=T2.idpersona
	LEFT join caracterizacion T3 ON T2.ficha=T3.idficha 
	WHERE T1.id_persona='{$id[0]}' AND T1.tipo_doc=upper('{$id[1]}') AND tipo_cita='{$id[2]}' AND REALIZADA='{$id[3]}'";
		$info=datos_mysql($sql);
		 //~ echo $sql;
		 
		return $info['responseResult'][0];
	}
}

function get_persona(){
	if ($_REQUEST['id']){
		$id=divide($_REQUEST['id']);
		$sql="SELECT T1.idpersona,T1.tipo_doc,T1.nombre1,T1.nombre2,T1.apellido1,T1.apellido2,T1.fecha_nacimiento,T1.genero,T2.fecha
	 FROM personas T1
	 LEFT join caracterizacion T2 ON T1.ficha=T2.idficha
	 WHERE T1.idpersona='".$id[0]."' AND T1.tipo_doc=upper('".$id[1]."')";
		$info=datos_mysql($sql);
		return json_encode($info['responseResult'][0]);
	}
}
function get_persona_ext(){
	if ($_REQUEST['id']){
		$id=divide($_REQUEST['id']);
		$sql="SELECT idpersona,tipo_doc,nombre1,nombre2,apellido1,apellido2,fecha_nacimiento,genero,fecha_envio
	 FROM personas1 
	 WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."')";
	 //~ echo $sql;
		$info=datos_mysql($sql);
		return json_encode($info['responseResult'][0]);
	}
}


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  //~ $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
  //~ $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  $rta .= "<li class='icono $a cancelar'    title='Cerrar'          Onclick=\"ocultar('".$a."','".$b."');\" >";
  return $rta;
}

function cmp_solcita(){
 $t=['id_persona'=>'','tipo_doc'=>'','nombre1'=>'','nombre2'=>'','apellido1'=>'','apellido2'=>'',
 'fecha_nacimiento'=>'','genero'=>'','observaciones'=>'','fecha'=>'','direccion'=>'','nacionalidad'=>'','tipo_cita'=>'','tel1'=>'','tel2'=>'','fecha_create'=>'','motivo'=>''];
 $w='frecuencia';
  $d='';//get_solcita(); 
  //~ var_dump($d);
 if ($d=="") {$d=$t;}
 $u=($d['id_persona']=='')?true:false;
 $o='percit';
 $rta=" <span class='mensaje' id='".$w."-msj' ></span>";
 $c[]=new cmp($o,'e',null,'SOLICITAR CITA',$w);
 $c[]=new cmp('key','h',50,$_POST['id'],$w.' '.$o,'',0,'','','',false,'','col-4');
  //~ $c[]=new cmp('ipe','h',10,$_POST['id'],$w,'','idp',null,'','',''); 
 $c[]=new cmp('idp','n',18,$d['id_persona'],$w.' '.$o,'N° Identificación',0,'rgxdfnum','#################',true,$u,'','col-4');
  $c[]=new cmp('tdo','s',3,$d['tipo_doc'],$w.' '.$o,'Tipo Documento','tipo_doc',null,null,true,$u,'','col-3','getPerson');
 $c[]=new cmp('no1','t',20,$d['nombre1'],$w.' '.$o,'Primer Nombre','nombre1',null,null,false,false,'','col-3');
 $c[]=new cmp('no2','t',20,$d['nombre2'],$w.' '.$o,'Segundo Nombre','nombre2',null,null,false,false,'','col-4');
 $c[]=new cmp('ap1','t',20,$d['apellido1'],$w.' '.$o,'Primer Apellido','apellido1',null,null,false,false,'','col-3');
 $c[]=new cmp('ap2','t',20,$d['apellido2'],$w.' '.$o,'Segundo Apellido','apellido2',null,null,false,false,'','col-3');
 $c[]=new cmp('fen','d',10,$d['fecha_nacimiento'],$w.' '.$o,'Fecha de Nacimiento','fecha_nacimiento',null,null,false,true,'','col-4');
 $c[]=new cmp('gen','s',3,$d['sexo'],$w.' '.$o,'Sexo','genero',null,null,false,false,'','col-3');
 $c[]=new cmp('te1','t',10,$d['tel1'],$w.' '.$o,'Teléfono 1','eapb',null,null,true,false,'','col-3');
 $c[]=new cmp('te2','t',10,$d['tel2'],$w.' '.$o,'Teléfono 2','etnia',null,null,true,false,'','col-4');
 $c[]=new cmp('te3','h',10,$d['tel3'],$w.' '.$o,'Teléfono 3','etnia',null,null,true,false,'','col-4');
 $c[]=new cmp('dir','t',20,$d['direccion'],$w.' '.$o,'Direccion','etnia',null,null,false,true,'','col-6');
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
 //$rta.="<div id='tblConsulta'>".lis_citasUsuario()."</div>";
 $rta.="<div class='campo frecuencia percit col-10'><center><button style='background-color:#65cc67;border-radius:12px;color:white;padding:8px;text-align:center;cursor:pointer;' type='button' Onclick=\"grabar('frecuencia',this);\">Guardar</button></center></div>";
 return $rta;
}

 function lis_citasUsuario(){
	 $id=divide($_POST['id']);
	$sql="SELECT `id_persona`, `tipo_doc`,FN_CATALOGODESC(39,tipo_cita) `tipo de cita`, 
	`observaciones` 
	FROM `solcita` 
	WHERE `id_persona`='{$id[0]}' AND `tipo_doc`='{$id[1]}' AND `realizada`='NO'";
//~ echo $sql;
	$datos=datos_mysql($sql);
return panel_content($datos["responseResult"],"citasUsuario",5);
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

function gra_frecuencia(){
	if ($_POST['mot3']){
		 $mot="'".$_POST['mot3']."'";
	 }else if($_POST['mot2']){
		 $mot="'".$_POST['mot2']."'";
	 }else{
		 $mot='NULL';
	 }
	 
 if ($_POST['key']){
	 $id=divide($_POST['key']);
	$sql="UPDATE solcita SET punto_atencion='{$_POST['pun']}', tipo_cita='{$_POST['cit']}',usu_update='".$_SESSION['us_riesgo']."',observaciones=UPPER('{$_POST['obs']}'),
 motivo=".$mot.",fecha_update=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
 WHERE id_persona={$id[0]} AND tipo_doc=UPPER('{$id[1]}') AND tipo_cita='{$id[2]}' AND `realizada`='NO';";
 }else{
	 $sql="INSERT INTO solcita VALUES ({$_POST['idp']},UPPER('{$_POST['tdo']}'),'{$_POST['pun']}','{$_POST['cit']}','NO',upper('{$_POST['obs']}'),".$mot.",'{$_SESSION['us_riesgo']}',DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A');";
 }
	//~ echo $sql;
	$rta=dato_mysql($sql);
return $rta;
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
  if ($a=='solcita'&& $b=='id'){$rta= "<div class='txt-center'>".$c['ID']."</div>";}
  //~ var_dump($c);
 if (($a=='solcita') && ($b=='acciones'))    {
		$rta="<nav class='menu right'>";
	if ($c['realizada']=='NO'){
		$rta.="<li class='icon editar min' title='Editar Frecuencia' id='".$c['ACCIONES']."' Onclick=\"mostrar('solcita','pro',event,'','lib.php',4);hideMotiv();loadMotiv();\"></li>"; //hideMotiv();
	}
		$rta.="</nav>";
	}    
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>