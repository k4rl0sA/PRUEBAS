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


function lis_agendamiento(){
  $sql = "";
	if ($_POST['fidpersona'])
		$sql .= " AND id_persona like '%".$_POST['fidpersona']."%'";
	if ($_POST['fdigita'])
		$sql .= " AND usu_creo ='".$_POST['fdigita']."' ";
	if ($_POST['festado'])
		$sql .= " AND estado = '".$_POST['festado']."' ";
	if ($_POST['fdes']) {
		if ($_POST['fhas']) {
			$sql .= " AND fecha_cita >='".$_POST['fdes']."' AND fecha_cita <='".$_POST['fhas']."'";
		} else {
			$sql .= " AND fecha_cita >='".$_POST['fdes']."' AND fecha_cita <='". $_POST['fdes']."'";
		}
	}
	return '';
}

function whe_agendamiento() {
	$sql = "";
  if ($_POST['fidpersona'])
		$sql .= " AND id_persona like '%".$_POST['fidpersona']."%'";
	if ($_POST['fdigita'])
		$sql .= " AND usu_creo ='".$_POST['fdigita']."' ";
	if ($_POST['festado'])
		$sql .= " AND estado = '".$_POST['festado']."' ";
	if ($_POST['fdes']) {
		if ($_POST['fhas']) {
			$sql .= " AND fecha_cita >='".$_POST['fdes']."' AND fecha_cita <='".$_POST['fhas']."'";
		} else {
			$sql .= " AND fecha_cita >='".$_POST['fdes']."' AND fecha_cita <='". $_POST['fdes']."'";
		}
}   
    return $sql;
}


function focus_agendamiento(){
 return 'agendamiento';
}

function men_agendamiento(){
 $rta=cap_menus('agendamiento','pro');
 return $rta;
} 


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='agendamiento'){  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
  	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  }
  return $rta;
}


function cmp_agendamiento(){
	$rta="";
  $t=['id_persona'=>'','tipodoc'=>'','nombre1'=>'','nombre2'=>'','apellido1'=>'','apellido2'=>'','tipo_doc'=>'',
 'fecha_nacimiento'=>'','edad'=>'','genero'=>'','tipo_consulta'=>'','punto_atencion'=>'','tipo_cita'=>'','fecha_cita'=>'','hora_cita'=>'','eapb'=>'','telefono1'=>'','telefono2'=>'','nombre_atendio'=>'','observac_cita'=>''];
 $w='agendamiento';
 $d=get_agendamiento(); 
  //~ echo(json_encode($d));
 if ($d=="") {$d=$t;}
 $u=($d['id_persona']=='')?true:false;
 $o='percit';
 $key='pEr';
 $c[]=new cmp($o,'e',null,'AGENDAMIENTO DE USUARIOS',$w);
 $c[]=new cmp('ipe','h',50,$_POST['id'],$w,'','idp',null,'','','');  
 //~ $c[]=new cmp('fcr','h',18,$d['fecha_create'],$w.' '.$o,'',0,'','','',false,'','col-4');
 $c[]=new cmp('idp','n',18,$d['id_persona'],$w.' '.$key.' '.$o,'Identificación <a href="https://www.adres.gov.co/consulte-su-eps" target="_blank">     Abrir ADRES</a>','idpersona','rgxdfnum',null,true,$u,'','col-3');
 $c[]=new cmp('tipo_doc','s',3,$d['tipodoc'],$w.' '.$key.' '.$o,'Tipo documento','tipo_doc',null,null,true,$u,'','col-4',"getDatKey('pEr','personOld','agendamiento',['tipo_doc'],'lib.php');");
 $c[]=new cmp('no1','t',50,$d['nombre1'],$w.' '.$o,'Primer Nombre','nombre1',null,null,false,false,'','col-3');
 $c[]=new cmp('no2','t',50,$d['nombre2'],$w.' '.$o,'Segundo Nombre','nombre2',null,null,false,false,'','col-3');
 $c[]=new cmp('ap1','t',50,$d['apellido1'],$w.' '.$o,'Primer Apellido','apellido1',null,null,false,false,'','col-4');
 $c[]=new cmp('ap2','t',50,$d['apellido2'],$w.' '.$o,'Segundo Apellido','apellido2',null,null,false,false,'','col-3');
 $c[]=new cmp('fen','d',10,$d['fecha_nacimiento'],$w.' '.$o,'Fecha de Nacimiento','fecha_nacimiento',null,null,false,false,'','col-3');
 $c[]=new cmp('eda','t',50,$d['edad'],$w.' '.$o,'Edad','edad',null,null,false,false,'','col-4');
 $c[]=new cmp('gen','s',3,$d['genero'],$w.' '.$o,'Sexo','genero',null,null,false,false,'','col-3');
 $c[]=new cmp('eap','s',3,$d['eapb'],$w.' '.$o,'Eapb','eapb',null,null,false,false,'','col-3');
 $c[]=new cmp('te1','t',10,$d['telefono1'],$w.' '.$o,'Telefono 1','telefono1',null,null,false,false,'','col-2');
 $c[]=new cmp('te2','t',10,$d['telefono2'],$w.' '.$o,'Telefono 2','telefono2',null,null,false,false,'','col-2'); 
 //$c[]=new cmp('con','t',3,$d['tipo_consulta'],$w.' '.$o,'Tipo de Consulta','tconsulta',null,null,true,true,'','col-3'); 
 $c[]=new cmp('pun','t',100,'',$w.' '.$o,'Punto de Atención','punto_atenc',null,null,true,true,'','col-5'); 
 $c[]=new cmp('cit','t',100,'',$w.' '.$o,'Tipo de Cita','tipo_cita',null,null,true,$u,'','col-5'); 
 $c[]=new cmp('fci','d',10,'',$w.' '.$o,'Fecha Cita','fecha',null,null,true,true,'','col-3'); 
 $c[]=new cmp('hci','c',10,'',$w.' '.$o,'Hora Cita','hora',null,null,true,true,'','col-2','validTime');
 $c[]=new cmp('nom','t',100,'',$w,'Persona que Atendio','nombre_atendio',null,null,true,true,'','col-5');
 $c[]=new cmp('obc','a',1000,'',$w.' '.$o,'Observaciones','observacion',null,null,false,true,'','col-10s'); 
 for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
 $rta.="<br>";
 $rta.="</div>";
	return $rta;
}

function get_personOld(){
	// print_r($_POST);
	$id=divide($_POST['id']);

		$sql="SELECT idpeople,idpersona,tipo_doc,nombre1,nombre2,apellido1,apellido2,fecha_nacimiento,
		sexo,genero,etnia,pueblo,nacionalidad,regimen,eapb
		FROM `personas` 
   	WHERE idpersona ='".$id[0]."' AND tipo_doc='".$id[1]."'";
	$info=datos_mysql($sql);
	if (!$info['responseResult']) {
		return json_encode (new stdClass);
	}else{
		return json_encode($info['responseResult'][0]);
	}
} 

function get_agendamiento(){
	// var_dump($_POST);
  $id=divide($_POST['id']);
  if ($id[0]=='0') {
    return '';
  }else{
    $sql="SELECT T1.id_persona,T1.tipodoc,IFNULL(T2.nombre1,T4.nombre1) nombre1,IFNULL(T2.nombre2,T4.nombre2) nombre2,IFNULL(T2.apellido1,T4.apellido1) apellido1,IFNULL(T2.apellido2,T4.apellido2) apellido2,IFNULL(T2.fecha_nacimiento,T4.fecha_nacimiento) fecha_nacimiento, concat('Años= ',timestampdiff(YEAR,IFNULL(T2.fecha_nacimiento,T4.fecha_nacimiento),curdate()), ' Meses= ',MONTH(CURDATE()) - MONTH(IFNULL(T2.fecha_nacimiento,T4.fecha_nacimiento)) + 12 * IF( MONTH(CURDATE()) < MONTH(IFNULL(T2.fecha_nacimiento,T4.fecha_nacimiento)),1, IF(MONTH(CURDATE())=MONTH(IFNULL(T2.fecha_nacimiento,T4.fecha_nacimiento)),IF (DAY(CURDATE()) < DAY(IFNULL(T2.fecha_nacimiento,T4.fecha_nacimiento)),1,0),0)) - IF(MONTH(CURDATE())<>MONTH(IFNULL(T2.fecha_nacimiento,T4.fecha_nacimiento)), (DAY(CURDATE()) < DAY(IFNULL(T2.fecha_nacimiento,T4.fecha_nacimiento))), IF (DAY(CURDATE()) < DAY(IFNULL(T2.fecha_nacimiento,T4.fecha_nacimiento)),1,0 ) ), ' Días= ',DAY(CURDATE())-DAY(IFNULL(T2.fecha_nacimiento,T4.fecha_nacimiento))+30*(DAY(CURDATE()) < DAY(IFNULL(T2.fecha_nacimiento,T4.fecha_nacimiento)))) edad,IFNULL(T2.genero,T4.genero) genero, IFNULL(T2.eapb,T4.eapb) eapb,T3.telefono1 telefono1,T3.telefono2 telefono2,tipo_consulta,punto_atencion,tipo_cita,fecha_cita,hora_cita,nombre_atendio,observac_cita 
        FROM agendamiento T1 
        left join personas T2 ON T1.id_persona=T2.idpersona 
        left join person T4 ON T1.id_persona=T4.idpeople
        left join hog_fam T3 ON T4.vivipersona = T3.id_fam
    WHERE T1.id_persona='".$id[1]."' AND T1.tipodoc=upper('".$id[2]."') AND fecha_cita='".$id[3]."' AND hora_cita='".$id[4]."'";
      $info=datos_mysql($sql);
      return $info['responseResult'][0];
  }
  }

  function opc_tipo_doc($id=''){
   return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
  }

  function opc_genero($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
  }
  function opc_eapb($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=18 and estado='A' ORDER BY 1",$id);
  }
  function opc_tconsulta($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=2 and estado='A' ORDER BY 1",$id);
  }
  function opc_punto_atenc($id=''){
    return opc_sql("SELECT `idcatadeta`,concat(idcatadeta,' - ',descripcion) FROM `catadeta` WHERE idcatalogo=241 and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta",$id);
  }
  function opc_tipo_cita($id=''){
    return opc_sql("SELECT `idcatadeta`,concat(idcatadeta,' - ',descripcion) FROM `catadeta` WHERE idcatalogo=240 and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta",$id);	
  }


  
function gra_gestuser(){
    $id=divide($_POST['variable']);
    $sql = "INSERT INTO variable VALUES(?,?,?,?,?,?,?,DATE_SUB(NOW(),INTERVAL 5 HOUR),?,?,?)";
    $params =[
    ['type' => 'i', 'value' => NULL],
    ['type' => 'i', 'value' => $_POST['variable']],
    ['type' => 's', 'value' => $_POST['variable']],
    ['type' => 's', 'value' => $_POST['variable']],
    ['type' => 's', 'value' => $_POST['variable']],
    ['type' => 's', 'value' => $_POST['variable']],
    ['type' => 's', 'value' => $_POST['variable']],
    ['type' => 'i', 'value' => $_SESSION['us_sds']],
    ['type' => 's', 'value' => NULL],
    ['type' => 's', 'value' => NULL],
    ['type' => 's', 'value' => 'A']
    ];
    return  $rta= mysql_prepd($sql, $params);
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// var_dump($rta);
	if ($a=='gestuser' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";		
		// $rta.="<li class='icono asigna1' title='Asignar Usuario' id='".$c['ACCIONES']."' Onclick=\"mostrar('gestuser','pro',event,'','lib.php',7);\"></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
