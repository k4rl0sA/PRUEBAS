<?php
ini_set('display_errors','1');
require_once "../libs/gestion.php";
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



function focus_alertas(){
	return 'alertas';
}
   
   
function men_alertas(){
	$rta=cap_menus('alertas','pro');
	return $rta;
}
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = "";
	 $acc=rol($a);
	 if ($a=='alertas'  && isset($acc['crear']) && $acc['crear']=='SI'){
	 	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	 return $rta;
	 }
   }

   function lis_alertas(){
		// var_dump($_POST['id']);
		$id=divide($_POST['id']);
		$sql="SELECT id_alert ACCIONES,id_alert AS Cod_Registro,`fecha`,FN_CATALOGODESC(34,tipo) Tipo,`nombre` Creó,`fecha_create` 'fecha Creó'
		FROM hog_alert P
		LEFT JOIN  usuarios U ON P.usu_creo=U.id_usuario ";
		$sql.="WHERE idpeople='".$id[0]."";
		$sql.="' ORDER BY fecha_create";
		// echo $sql;
		$datos=datos_mysql($sql);
		return panel_content($datos["responseResult"],"alertas-lis",5);
   }
 
   function cmp_alertas(){
	$rta="<div class='encabezado medid'>TABLA DE TOMAS DE MEDIDA</div>
	<div class='contenido' id='alertas-lis'>".lis_alertas()."</div></div>";
	// $t=['nombres'=>'','fechanacimiento'=>'','edad'=>'','peso'=>'','talla'=>'','imc'=>'','tas'=>'','tad'=>'','glucometria'=>'','perime_braq'=>'','perime_abdom'=>'','percentil'=>'','zscore'=>'','findrisc'=>'','oms'=>'','alert1'=>'','alert2'=>'','alert3'=>'','alert4'=>'','alert5'=>'','alert6'=>'','alert7'=>'','alert8'=>'','alert9'=>'','alert10'=>'','select1'=>'','selmul1'=>'[]','selmul2'=>'[]','selmul3'=>'[]','selmul4'=>'[]','selmul5'=>'[]','selmul6'=>'[]','selmul7'=>'[]','selmul8'=>'[]','selmul9'=>'[]','selmul10'=>'[]','fecha'=>'','tipo'=>''];
	$p=get_persona();
	// if ($d==""){$d=$t;}
	var_dump($_POST);
	$id=divide($_POST['id']);
	$d='';
    $w="alertas";
	$o='infbas';
	$gest = ($p['sexo']=='MUJER' && ($p['ano']>9 && $p['ano']<56 )) ? true : false ;
	$ocu= ($p['ano']>5) ? true : false ;
	$meses = $p['ano'] * 12 + $p['mes'];
	// $esc=($p['ano']>=5 && $p['ano']<18 ) ? true : false ;
	$ed=$p['ano'];
	switch (true) {
			case $ed>=0 && $ed<=5 :
				$curso=1;
				break;
			case $ed>=6 && $ed<=11 :
				$curso=2;
				break;
			case $ed>=12 && $ed <=17 :
				$curso=3;
				break;
			case $ed>=18 && $ed <=28 :
				$curso=4;
				break;
			case $ed>=29 && $ed <=59 :
				$curso=5;
				break;
			case $ed>=60 :
				$curso=6;
				break;
		default:
			$curso='';
			break;
	}

	$des='des';
	$z='zS';
	$days=fechas_app('vivienda');
	$c[]=new cmp('idp','h',15,$_POST['id'],$w.' '.$o,'id','id',null,'',false,false);
	$c[]=new cmp($o,'e',null,'INFORMACION DE alertas',$w); 
	$c[]=new cmp('idpersona','t','20',$p['idpersona'],$w.' '.$o,'N° Identificación','idpersona',null,'',true,false,'','col-1');
	$c[]=new cmp('tipodoc','t','3',$p['tipo_doc'],$w.' '.$o,'Tipo Identificación','tipodoc',null,'',true,false,'','col-1');
	$c[]=new cmp('nombre','t','50',$p['nombres'],$w.' '.$o,'nombres','nombre',null,'',true,false,'','col-3');
	$c[]=new cmp('sexo','t','50',$p['sexo'],$w.' '.$z.' '.$o,'sexo','sexo',null,'',false,false,'','col-1');
	$c[]=new cmp('fechanacimiento','d','10',$p['fecha_nacimiento'],$w.' '.$z.' '.$o,'fecha nacimiento','fechanacimiento',null,'',true,false,'','col-15');
    $c[]=new cmp('edad','n','3',' Años: '.$p['ano'].' Meses: '.$p['mes'].' Dias:'.$p['dia'],$w.' '.$o,'Edad (Abordaje)','edad',null,'',false,false,'','col-25');
	$c[]=new cmp('cursovida','s','3',$curso,$w.' '.$o,'Curso de Vida','cursovida',null,'',false,false,'','col-25');
	$c[]=new cmp('fecha','d','10',$d,$w.' '.$o,'fecha de la Toma','fecha',null,'',true,true,'','col-15',"validDate(this,$days,0);");
	$c[]=new cmp('tipo','s','3',$d,$w.' '.$o,'Tipo','complemento',null,'',true,true,'','col-15');
	$c[]=new cmp('crit_epi','s','3',$d,$w.' '.$o,'Criterio Epidemiológico','crit_epi',null,true,true,true,'','col-35');
	
	$o='infcom';
	$c[]=new cmp($o,'e',null,'DATOS COMPLEMENTARIOS',$w);
	
	if($p['ano']<5){
		$c[]=new cmp('men_dnt','s','2',$d,$w.' '.$o,'Menor de 5 años con DNT Aguda','rta',null,null,true,true,'','col-15', "fieldsValue('men_dnt','dNt','1',true);");
		$c[]=new cmp('men_sinctrl','s','2',$d,$w.' dNt '.$o,'Sin Atencion Ruta Alteracion Nutricional','rta',null,null,true,true,'','col-15');
	}

	if($gest){
		$c[]=new cmp('gestante','s','2',$d,$w.' '.$o,'El usuario es gestante','rta',null,null,$gest,$gest,'','col-2',"fieldsValue('gestante','eTp','1',true);");
		$c[]=new cmp('etapgest','s','3',$d,$w.' eTp '.$o,'Etapa Gestacional','etapgest',null,'',$gest,false,'','col-25');//true
		$c[]=new cmp('ges_sinctrl','s','3',$d,$w.' eTp '.$o,'Gestante Sin Control','rta',null,'',$gest,false,'','col-25');//true
	}

	$c[]=new cmp('cronico','s','2',$d,$w.' '.$o,'El usuario es cronico','rta',null,null,true,true,'','col-2',"fieldsValue('cronico','cRo','1',true);");
	$c[]=new cmp('cro_hiper','s','2',$d,$w.' cRo '.$o,'Hipertension','rta',null,null,true,false,'','col-2');
	$c[]=new cmp('cro_diabe','s','2',$d,$w.' cRo '.$o,'Diabetes','rta',null,null,true,false,'','col-2');
	$c[]=new cmp('cro_epoc','s','2',$d,$w.' cRo '.$o,'Epoc','rta',null,null,true,false,'','col-2');
	$c[]=new cmp('cro_sinctrl','s','2',$d,$w.' cRo '.$o,'Cronico Sin Control','rta',null,null,true,false,'','col-2');
	$c[]=new cmp('esq_vacun','s','2',$d,$w.' '.$o,'Esquema de Vacunacion Completo','rta',null,null,true,true,'','col-2');
	
	$o='alert';
	$c[]=new cmp($o,'e',null,'ALERTAS',$w); 
	$c[]=new cmp('alert1','s',15,$d,$w.' '.$o,'Alerta N° 1','alert',null,null,true,true,'','col-1',"enabAlert(this,'cro');","['fselmul1'],'alertas.php'");
	$c[]=new cmp('selmul1','m',3,$d,$w.' cro '.$o,'Descripcion Alerta N° 1','selmul1',null,'',false,false,'','col-4');
	$c[]=new cmp('alert2','s',15,$d,$w.' '.$o,'Alerta N° 2','alert',null,null,true,true,'','col-1',"enabAlert(this,'etv');");
	$c[]=new cmp('selmul2[]','m',3,$d,$w.' etv '.$o,'Descripcion Alerta N° 2','selmul2',null,'',false,false,'','col-4');
	$c[]=new cmp('alert3','s',15,$d,$w.' '.$o,'Alerta N° 3','alert',null,null,true,true,'','col-1',"enabAlert(this,'nut');");
	$c[]=new cmp('selmul3[]','m',3,$d,$w.' nut '.$o,'Descripcion Alerta N° 3','selmul3',null,'',false,false,'','col-4');
	$c[]=new cmp('alert4','s',15,$d,$w.' '.$o,'Alerta N° 4','alert',null,null,true,true,'','col-1',"enabAlert(this,'psi');");
	$c[]=new cmp('selmul4[]','m',3,$d,$w.' psi '.$o,'Descripcion Alerta N° 4','selmul4',null,'',false,false,'','col-4');
	$c[]=new cmp('alert5','s',15,$d,$w.' '.$o,'Alerta N° 5','alert',null,null,true,true,'','col-1',"enabAlert(this,'inf');");
	$c[]=new cmp('selmul5[]','m',3,$d,$w.' inf '.$o,'Descripcion Alerta N° 5','selmul5',null,'',false,false,'','col-4');
	$c[]=new cmp('alert6','s',15,$d,$w.' '.$o,'Alerta N° 6','alert',null,null,true,true,'','col-1',"enabAlert(this,'muj');");
	$c[]=new cmp('selmul6[]','m',3,$d,$w.' muj '.$o,'Descripcion Alerta N° 6','selmul6',null,'',false,false,'','col-4');
	
	$c[]=new cmp('agen_intra','s',15,$d,$w.' '.$o,'Agendamiento Intramural','rta',null,null,true,true,'','col-1',"enabAlert(this,'dis');");
	$c[]=new cmp('servicio','o',15,$d,$w.' '.$o,'Servicio Agendado','servicio',null,null,true,true,'','col-15',"enabAlert(this,'etn');");
	$c[]=new cmp('fecha_cita','d','10',$d,$w.' '.$o,'Fecha de la Cita','fecha_cita',null,'',true,true,'','col-15',"validDate(this,$days,0);");
	$c[]=new cmp('hora_cita','d','10',$d,$w.' '.$o,'Hora de la Cita','hora_cita',null,'',true,true,'','col-15',"validDate(this,$days,0);");
	$c[]=new cmp('lugar_cita','o',15,$d,$w.' '.$o,'Lugar de la Cita','lugra_cita',null,null,true,true,'','col-15',"enabAlert(this,'etn');");
	
	$c[]=new cmp('deriva_pf','s',15,$d,$w.' '.$o,'Deriva a PCF','rta',null,null,true,true,'','col-1',"enabOthSi('deriva_pf','pCf');");
	$c[]=new cmp('evento_pf','s',15,$d,$w.' pCf '.$o,'Asigna a PCF','evento',null,null,false,false,'','col-5');
	// $c[]=new cmp('medico','s',15,$d,$w.' der '.$o,'Asignado','medico',null,null,false,false,'','col-5');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
   }


   
   
   function get_persona(){
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		$sql="SELECT idpersona,tipo_doc,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,FN_CATALOGODESC(21,sexo) sexo,fecha_nacimiento,fecha, 
		FN_EDAD(fecha_nacimiento,CURDATE()),
		TIMESTAMPDIFF(YEAR,fecha_nacimiento, CURDATE() ) AS ano,
  		TIMESTAMPDIFF(MONTH,fecha_nacimiento ,CURDATE() ) % 12 AS mes,
		DATEDIFF(CURDATE(), DATE_ADD(fecha_nacimiento,INTERVAL TIMESTAMPDIFF(MONTH, fecha_nacimiento, CURDATE()) MONTH)) AS dia
		from person P left join hog_carac V ON vivipersona=idfam
		WHERE idpeople='".$id[0]."'";
		// echo $sql;
		$info=datos_mysql($sql);
		if (!$info['responseResult']) {
			return '';
		}else{
			return $info['responseResult'][0];
		}
		}
	} 


function gra_alertas(){
	// print_r($_POST);
	if (($smu1 = $_POST['fselmul1'] ?? null) && is_array($smu1)){$sm1 = implode(",",str_replace("'", "", $smu1));}
	if (($smu2 = $_POST['fselmul2'] ?? null) && is_array($smu2)) {$sm2 = implode(",",str_replace("'", "", $smu2));}
	if (($smu3 = $_POST['fselmul3'] ?? null) && is_array($smu3)) {$sm3 = implode(",",str_replace("'", "", $smu3));}
	if (($smu4 = $_POST['fselmul4'] ?? null) && is_array($smu4)) {$sm4 = implode(",",str_replace("'", "", $smu4));}
	if (($smu5 = $_POST['fselmul5'] ?? null) && is_array($smu5)) {$sm5 = implode(",",str_replace("'", "", $smu5));}
	if (($smu6 = $_POST['fselmul6'] ?? null) && is_array($smu6)) {$sm6 = implode(",",str_replace("'", "", $smu6));}
	if (($smu7 = $_POST['fselmul7'] ?? null) && is_array($smu7)) {$sm7 = implode(",",str_replace("'", "", $smu7));}
	if (($smu8 = $_POST['fselmul8'] ?? null) && is_array($smu8)) {$sm8 = implode(",",str_replace("'", "", $smu8));}
	if (($smu9 = $_POST['fselmul9'] ?? null) && is_array($smu9)) {$sm9 = implode(",",str_replace("'", "", $smu9));}
	if (($smu10 = $_POST['fselmul10'] ?? null) && is_array($smu10)) {$sm10 = implode(",",str_replace("'", "", $smu10));}
	$codoral= $_POST['codoral']?? null;

	$id=divide($_POST['idp']);

		$sql="INSERT INTO hog_alert VALUES (NULL,
		trim(upper('{$_POST['tipodoc']}')),trim(upper('{$_POST['idpersona']}')),trim(upper('{$_POST['fecha']}')),
		trim(upper('{$_POST['tipo']}')),trim(upper('{$_POST['crit_epi']}')),trim(upper('{$_POST['cursovida']}')),
		trim(upper('{$_POST['gestante']}')),trim(upper('{$_POST['etapgest']}')),trim(upper('{$_POST['cronico']}')),	
		trim(upper('{$_POST['alert1']}')),trim(upper('{$sm1}')),trim(upper('{$_POST['alert2']}')),trim(upper('{$sm2}')),
		trim(upper('{$_POST['alert3']}')),trim(upper('{$sm3}')),trim(upper('{$_POST['alert4']}')),trim(upper('{$sm4}')),
		trim(upper('{$_POST['alert5']}')),trim(upper('{$sm5}')),trim(upper('{$_POST['alert6']}')),trim(upper('{$sm6}')),
		trim(upper('{$_POST['alert7']}')),trim(upper('{$sm7}')),trim(upper('{$_POST['alert8']}')),trim(upper('{$sm8}')),
		trim(upper('{$_POST['alert9']}')),trim(upper('{$sm9}')),trim(upper('{$codoral}')),
		trim(upper('{$_POST['alert10']}')),trim(upper('{$sm10}')),
		trim(upper('{$_POST['deriva_eac']}')),trim(upper('{$_POST['necesidad_eac']}')),trim(upper('{$_POST['asignado_eac']}')),
		trim(upper('{$_POST['deriva_pf']}')),trim(upper('{$_POST['evento_pf']}'))";

	$sql.="DATE_SUB(NOW(), INTERVAL 5 HOUR),TRIM(UPPER('{$_SESSION['us_sds']}')),null,null,'A')";
		// }
		// echo $sql;
		$rta=dato_mysql($sql);
		//return $rta.' '.$rta1;
		return $rta;
}

function opc_evento($id=''){
	$d=get_persona();
	if($d['sexo']=='M'){
	  if($d['ano']<6){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5,1,2,3) and estado='A' ORDER BY 2",$id);
	  }elseif($d['ano']>5 && $d['ano']<10){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5,2,3) and estado='A' ORDER BY 2",$id); 
	  }elseif($d['ano']>9 && $d['ano']<18){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5,2,3,4) and estado='A' ORDER BY 2",$id); 
	  }elseif($d['ano']>17 && $d['ano']<55){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5,2,4) and estado='A' ORDER BY 2",$id); 
	  }elseif($d['ano']>54){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5) and estado='A' ORDER BY 2",$id); 
	  }
	}else{
	  if($d['ano']<6){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5,1,2,3) and estado='A' ORDER BY 2",$id);
	  }elseif($d['ano']>5 && $d['ano']<18){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5,2,3) and estado='A' ORDER BY 2",$id); 
	  }elseif($d['ano']>17 && $d['ano']<55){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5) and estado='A' ORDER BY 2",$id); 
	  }elseif($d['ano']>54){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5) and estado='A' ORDER BY 2",$id); 
	  }
	}
}

function get_alertas(){
	// print_r($_POST);
	if($_POST['id']==''){
		return '';
	}else{
		$id=divide($_POST['id']);
		// print_r($id);
		$sql1="SELECT TIMESTAMPDIFF(YEAR,fecha_nacimiento, fecha ) AS ano,TIMESTAMPDIFF(MONTH,fecha_nacimiento ,fecha ) % 12 AS mes 
		from person P left join hog_alert D ON P.idpeople=D.idpeople WHERE id_alert='{$id[0]}'";
		$data=datos_mysql($sql1);
		$edad=$data['responseResult'][0];



		$sql="SELECT concat(idpeople,tipo) as id,documento,tipo_doc,
		concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,sexo,fecha_nacimiento,
		FN_EDAD(fecha_nacimiento,V.fecha),
		D.fecha,`tipo`,D.crit_epi,cursovida,gestante,etapgest,cronico,`alert1`,`selmul1`,`alert2`,`selmul2`,`alert3`,`selmul3`, `alert4`, `selmul4`, `alert5`, `selmul5`, `alert6`, `selmul6`, `alert7`, `selmul7`, `alert8`, `selmul8`, `alert9`, `selmul9`, codoral,`alert10`, `selmul10`,
		D.deriva_eac,D.necesidad_eac,D.asignado_eac,deriva_pf";
		$sql.=" FROM hog_alert D
				LEFT JOIN person P ON idpeople=idpeople
				LEFT JOIN hog_carac V ON P.vivipersona=V.idfam
				WHERE id_alert ='{$id[0]}'" ;
	 	$info = datos_mysql($sql);
		 //echo $sql; 
		// print_r($info['responseResult'][0]);
		if (!$info['responseResult']) {
			return '';
		}else{
			return json_encode($info['responseResult'][0]);
		}
		
	}
}

function opc_alert1fselmul1(){
	if($_REQUEST['id']!=''){
		$id=divide($_REQUEST['id']);
		$sql="SELECT idcatadeta 'id',CONCAT(idcatadeta,'-',descripcion) 'desc' FROM `catadeta` WHERE idcatalogo=233 and estado='A' and valor='".$id[0]."' ORDER BY 1";
		$info=datos_mysql($sql);		
		return json_encode($info['responseResult']);
	} 
}

function opc_necesidad($id=''){
	return opc_sql("SELECT `idcatadeta`, descripcion FROM `catadeta` WHERE idcatalogo=225 AND estado='A' ORDER BY LPAD(idcatadeta, 2, '0')", $id);
}
function opc_rta($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
}
function opc_crit_epi($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=166 and estado='A' ORDER BY LPAD(idcatadeta, 2, '0')",$id);
}
function opc_etapgest($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=177 and estado='A' ORDER BY 1",$id);
}
function opc_cursovida($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=176 and estado='A' ORDER BY 1",$id);
}
function opc_medico($id=''){
	return opc_sql("SELECT
	`id_usuario`,
	CONCAT(nombre, ' - ', LEFT(perfil, 3))
FROM
	`usuarios` U
	RIGHT JOIN adscrip A ON U.id_usuario= A.doc_asignado
WHERE
	`perfil` IN('MEDATE', 'ENFATE')
	AND U.subred = (SELECT subred from usuarios where id_usuario={$_SESSION['us_sds']})
	AND	U.id_usuario IN (SELECT doc_asignado FROM adscrip where territorio in (select territorio from adscrip a where doc_asignado={$_SESSION['us_sds']})) 
	AND U.estado = 'A'
ORDER BY
	perfil,2",$id);
}
function opc_pcf($id=''){
	return opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE `perfil` IN('APYFAM')  AND subred=FN_SUBRED({$_SESSION['us_sds']}) AND estado ='A' ORDER BY 2",$id);
}
function  opc_des($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=0 and estado='A' ORDER BY 1",$id);
}
function  opc_fin($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=39 and estado='A' ORDER BY 1",$id);
}
function opc_oms($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=40 and estado='A' ORDER BY 1",$id);
}
function opc_epoc($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=117 and estado='A' ORDER BY 1",$id);
}
function opc_complemento($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=34 and estado='A' ORDER BY 1",$id);
}
function opc_alert($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=231 and estado='A' ORDER BY 1",$id);
}
function opc_selmul1($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=144 and estado='A' ORDER BY 1",$id);
}
function opc_selmul2($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=145 and estado='A' ORDER BY 1",$id);
}
function opc_selmul3($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=146 and estado='A' ORDER BY 1",$id);
}
function opc_selmul4($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=147 and estado='A' ORDER BY 1",$id);
}
function opc_selmul5($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=148 and estado='A' ORDER BY 1",$id);
}
function opc_selmul6($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=149 and estado='A' ORDER BY 1",$id);
}
function opc_selmul7($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=150 and estado='A' ORDER BY 1",$id);
}
function opc_selmul8($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=151 and estado='A' ORDER BY 1",$id);
}
function opc_selmul9($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=152 and estado='A' ORDER BY 1",$id);
}
function opc_selmul10($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=153 and estado='A' ORDER BY 1",$id);
}


function formato_dato($a,$b,$c,$d){
    $b=strtolower($b);
	$rta=$c[$d];
	if ($a=='alertas-lis' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'alertas',event,this,['fecha','tipo_activi'],'../vivienda/alertas.php');\"></li>";  //   act_lista(f,this);
	}
return $rta;
}

function bgcolor($a,$c,$f='c'){
	$rta="";
	return $rta;
}
	   
