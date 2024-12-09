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

function focus_sespers(){
	return 'sespers';
   }
   
   function men_sespers(){
	$rta=cap_menus('sespers','pro');
	return $rta;
   } 
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 if ($a=='sespers'){  
	   $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
		 $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
	 }
	 return $rta;
   }
   

function cmp_sespers(){
	$rta="";
	$t=['idpersona'=>'','tipo_doc'=>'','nombre1'=>'','nombre2'=>'','apellido1'=>'','apellido2'=>'','fecha_nacimiento'=>'','sexo'=>'','genero'=>'','etnia'=>'','pueblo'=>'','nacionalidad'=>'','regimen'=>'','eapb'=>''];
	$d=get_sespers();
	if ($d==""){$d=$t;}
	// var_dump($_POST);
	$id=divide($_POST['id']);
    $w="alertas";
	$o='infbas';
	$key='pEr';
	// var_dump($p);
	$days=fechas_app('vivienda');
		
	$o='Sesper';
	$c[]=new cmp($o,'e',null,'IDENTIFICACIÓN DE PERSONAS',$w);
	$c[]=new cmp('idp','h',15,$_POST['id'],$w.' '.$o,'id','id',null,'####',false,false);
	$c[]=new cmp('idpersona','n','18','',$w.' '.$key.' '.$o,'Identificación <a href="https://www.adres.gov.co/consulte-su-eps" target="_blank">     Abrir ADRES</a>','idpersona',null,null,true,true,'','col-4');
	$c[]=new cmp('tipo_doc','s','3','',$w.' '.$key.' '.$o,'Tipo documento','tipo_doc',null,null,true,true,'','col-4',"getDatForm('pEr','personOld',['infgen'],this);");
	$c[]=new cmp('nombre1','t','30','',$w.' '.$o,'Primer Nombre','nombre1',null,null,true,true,'','col-2');
	$c[]=new cmp('nombre2','t','30','',$w.' '.$o,'Segundo Nombre','nombre2',null,null,false,true,'','col-2');
	$c[]=new cmp('apellido1','t','30','',$w.' '.$o,'Primer Apellido','apellido1',null,null,true,true,'','col-2');
	$c[]=new cmp('apellido2','t','30','',$w.' '.$o,'Segundo Apellido','apellido2',null,null,false,true,'','col-2');
	$c[]=new cmp('fecha_nacimiento','d','','',$w.' '.$o,'Fecha de nacimiento','fecha_nacimiento',null,null,true,true,'','col-2',"validDate(this,-43800,0);",[],"child14('fecha_nacimiento','osx');Ocup5('fecha_nacimiento','OcU');");
	$c[]=new cmp('sexo','s','3','',$w.' '.$o,'Sexo','sexo',null,null,true,true,'','col-2');
	$c[]=new cmp('genero','s','3','',$w.' '.$o,'Genero','genero',null,null,true,true,'','col-2');
	$c[]=new cmp('etnia','s','3','',$w.' '.$o,'Pertenencia Etnica','etnia',null,null,true,true,'','col-2',"enabEtni('etnia','ETn','idi');");
	$c[]=new cmp('pueblo','s','50','',$w.' ETn cmhi '.$o,'pueblo','pueblo',null,null,false,true,'','col-2');
	$c[]=new cmp('nacionalidad','s','3','',$w.' '.$o,'nacionalidad','nacionalidad',null,null,true,true,'','col-2');
	$c[]=new cmp('regimen','s','3','',$w.' '.$o,'regimen','regimen',null,null,true,true,'','col-2',"enabAfil('regimen','eaf');enabEapb('regimen','rgm');");
	$c[]=new cmp('eapb','s','3','',$w.' rgm '.$o,'eapb','eapb',null,null,true,true,'','col-2');

	// $c[]=new cmp('medico','s',15,$d,$w.' der '.$o,'Asignado','medico',null,null,false,false,'','col-5');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function get_sespers(){
	return '';
}

function get_person(){
	//  print_r($_REQUEST);
	 $id=divide($_REQUEST['id']);
	if($_REQUEST['id']=='' || count($id)!=2){
		return "";
	}else{
		$sql="SELECT concat_ws('_',idpeople,vivipersona),encuentra,idpersona,tipo_doc,nombre1,nombre2,
		apellido1,apellido2,fecha_nacimiento,sexo,genero,oriensexual,nacionalidad,estado_civil,
		niveduca,abanesc,ocupacion,tiemdesem,vinculo_jefe,etnia,pueblo,idioma,discapacidad,regimen,eapb,
		afiliaoficio,sisben,catgosisb,pobladifer,incluofici,cuidador,perscuidada,tiempo_cuidador,
		cuidador_unidad,vinculo,tiempo_descanso,descanso_unidad,reside_localidad,localidad_vive,
		transporta
		FROM `person`
		WHERE idpeople ='{$id[0]}'" ;
		// echo $sql;
		// print_r($id);
		$info=datos_mysql($sql);
		if (!$info['responseResult']) {
			return '';
		}
	return $info['responseResult'][0];
	} 
}
function get_personOld(){
	// print_r($_REQUEST);
	$id=divide($_POST['id']);
	$info=datos_mysql("select idpersona from person where idpersona ='".$id[0]."'");
	if (!$info['responseResult']) {
		$sql="SELECT encuentra,idpersona,tipo_doc,nombre1,nombre2,apellido1,apellido2,fecha_nacimiento,
		sexo,genero,oriensexual,nacionalidad,estado_civil,niveduca,abanesc,ocupacion,tiemdesem,vinculo_jefe,etnia,pueblo,idioma,discapacidad,regimen,eapb,
		afiliaoficio,sisben,catgosisb,pobladifer,incluofici,cuidador,perscuidada,tiempo_cuidador,cuidador_unidad,vinculo,tiempo_descanso,
		descanso_unidad,reside_localidad,localidad_vive,transporta
		FROM `personas` 
   	WHERE idpersona ='".$id[0]."' AND tipo_doc='".$id[1]."'";
	$info=datos_mysql($sql);
	if (!$info['responseResult']) {
		return json_encode (new stdClass);
	}
	return json_encode($info['responseResult'][0]);
	}else{
		// return json_encode (new stdClass);
		return $rta="Error: El usuario con este número de documento ya se encuentra registrado.";

	}
} 

function opc_tipo_doc($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 2",$id);
}
function opc_sexo($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY CAST(idcatadeta AS UNSIGNED)",$id);
}
function opc_nacionalidad($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=30 and estado='A' ORDER BY CAST(idcatadeta AS UNSIGNED)",$id);
}
function opc_etnia($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=16 and estado='A' ORDER BY CAST(idcatadeta AS UNSIGNED)",$id);
}
function opc_regimen($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=17 and estado='A' ORDER BY CAST(idcatadeta AS UNSIGNED)",$id);
}
function opc_eapb($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=18 and estado='A' ORDER BY CAST(idcatadeta AS UNSIGNED)",$id);
}
function opc_genero($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=19 and estado='A' ORDER BY CAST(idcatadeta AS UNSIGNED)",$id);
}
function opc_pueblo($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=15 and estado='A' ORDER BY CAST(idcatadeta AS UNSIGNED)",$id);
}
