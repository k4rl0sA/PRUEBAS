<?php
require_once "../libs/gestion.php";
ini_set('display_errors','1');
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


function lis_tamWhodas(){
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(whodas_idpersona,'_',whodas_tipodoc,'_',whodas_momento) ACCIONES,tam_whodas 'Cod Registro',whodas_idpersona Documento,FN_CATALOGODESC(1,whodas_tipodoc) 'Tipo de Documento',CONCAT_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres, 
	FN_CATALOGODESC(21,P.sexo) Sexo,FN_CATALOGODESC(116,whodas_momento) Momento,`porcentaje_total` Puntaje 
FROM hog_tam_whodas O
LEFT JOIN personas P ON O.whodas_idpersona = P.idpersona
		WHERE '1'='1'";
	$sql.=whe_tamWhodas();
	$sql.=" ORDER BY 1";

		$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"tamWhodas",20);
}

function whe_tamWhodas() {
	$sql = "";
	if ($_POST['fidentificacion'])
		$sql .= " AND whodas_idpersona like '%".$_POST['fidentificacion']."%'";
	if ($_POST['fsexo'])
		$sql .= " AND P.sexo ='".$_POST['fsexo']."' ";
	if ($_POST['fpersona']){
		if($_POST['fpersona'] == '2'){ //mayor de edad
			$sql .= " AND TIMESTAMPDIFF(YEAR, P.fecha_nacimiento, CURDATE()) <= 18 ";
		}else{ //menor de edad
			$sql .= " AND TIMESTAMPDIFF(YEAR, P.fecha_nacimiento, CURDATE()) > 18 ";
		}
	}
	return $sql;
}

function cmp_tamWhodas(){
	$rta="";
	$t=['tam_whodas'=>'','whodas_tipodoc'=>'','whodas_nombre'=>'','whodas_idpersona'=>'','whodas_fechanacimiento'=>'','whodas_puntaje'=>'','whodas_momento'=>'','whodas_edad'=>'','whodas_lugarnacimiento'=>'','whodas_condicionsalud'=>'','whodas_estadocivil'=>'','whodas_escolaridad'=>'',
	 'whodas_ocupacion'=>'','whodas_rutina'=>'','whodas_rol'=>'',	 'whodas_actividad'=>'','whodas_evento'=>'','whodas_comportamiento'=>'',

	 'whodas_comprension1'=>'','whodas_comprension2'=>'','whodas_comprension3'=>'','whodas_comprension4'=>'',
	 'whodas_comprension5'=>'','whodas_comprension6'=>'','whodas_moverse1'=>'', 'whodas_moverse2'=>'','whodas_moverse3'=>'','whodas_moverse4'=>'',
	 'whodas_moverse5'=>'','whodas_cuidado1'=>'','whodas_cuidado2'=>'','whodas_cuidado3'=>'','whodas_cuidado4'=>'',
	 'whodas_relacionarce1'=>'', 'whodas_relacionarce2'=>'','whodas_relacionarce3'=>'','whodas_relacionarce4'=>'',	'whodas_relacionarce5'=>'',
	 'whodas_actividades1'=>'','whodas_actividades2'=>'','whodas_actividades3'=>'','whodas_actividades4'=>'','whodas_actividades5'=>'',
	 'whodas_actividades6'=>'','whodas_actividades7'=>'','whodas_actividades8'=>'','whodas_participacion1'=>'','whodas_participacion2'=>'','whodas_participacion3'=>'','whodas_participacion4'=>'','whodas_participacion5'=>'','whodas_participacion6'=>'','whodas_participacion7'=>'','whodas_participacion8'=>'','whodas_dias1'=>'','whodas_dias2'=>'','whodas_dias3'=>'','porcentaje_comprension'=>'','porcentaje_moverse'=>'','porcentaje_cuidado'=>'','porcentaje_relacionarce'=>'','porcentaje_actividades'=>'','porcentaje_participacion'=>'','porcentaje_total'=>'','whodas_analisis'=>''];

	$w='tamwhodas';
	$d=get_tamWhodas(); 
	if ($d=="") {$d=$t;}
	$u = ($d['tam_whodas']!='') ? false : true ;
	$o='datos';
    $key='srch';

	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('idwhodas','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('whodas_idpersona','t','20',$d['whodas_idpersona'],$w.' '.$o.' '.$key,'N° Identificación','whodas_idpersona',null,'',false,$u,'','col-2');
	$c[]=new cmp('whodas_tipodoc','s','3',$d['whodas_tipodoc'],$w.' '.$o.' '.$key,'Tipo Identificación','whodas_tipodoc',null,'',false,$u,'','col-25','getDatForm(\'srch\',\'person\',[\'datos\']);');
	$c[]=new cmp('whodas_nombre','t','50',$d['whodas_nombre'],$w.' '.$o,'nombres','whodas_nombre',null,'',false,false,'','col-4');
	$c[]=new cmp('whodas_fechanacimiento','d','10',$d['whodas_fechanacimiento'],$w.' '.$o,'fecha nacimiento','whodas_fechanacimiento',null,'',false,false,'','col-15');
    $c[]=new cmp('whodas_edad','n','3',$d['whodas_edad'],$w.' '.$o,'edad','whodas_edad',null,'',true,false,'','col-1');
    
	$o='comprencion';
	$c[]=new cmp($o,'e',null,'1. Comprensión y comunicación ',$w);
	$c[]=new cmp('whodas_comprension1','s',3,$d['whodas_comprension1'],$w.' '.$o,'Concentrarse en algo durante 10 minutos','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_comprension2','s',3,$d['whodas_comprension2'],$w.' '.$o,'Recordar las cosas importantes que tiene que hacer','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_comprension3','s',3,$d['whodas_comprension3'],$w.' '.$o,'Analizar y encontrar soluciones a los problemas de la vida diaria','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_comprension4','s',3,$d['whodas_comprension4'],$w.' '.$o,'Aprender una nueva tarea, como por ejemplo, llegar a un lugar nuevo','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_comprension5','s',3,$d['whodas_comprension5'],$w.' '.$o,'Entender en general lo que dice la gente','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_comprension6','s',3,$d['whodas_comprension6'],$w.' '.$o,'Iniciar o mantener una conversación','nivel',null,null,true,$u,'','col-10');

	$o='capacidad';
	$c[]=new cmp($o,'e',null,'2. Capacidad para moverse en su alrededor (entorno)',$w);
	$c[]=new cmp('whodas_moverse1','s',3,$d['whodas_moverse1'],$w.' '.$o,'Estar de pie durante largos períodos de tiempo, como por ejemplo, 30 minutos','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_moverse2','s',3,$d['whodas_moverse2'],$w.' '.$o,'Ponerse de pie cuando estaba sentado','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_moverse3','s',3,$d['whodas_moverse3'],$w.' '.$o,'Moverse dentro de su casa','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_moverse4','s',3,$d['whodas_moverse4'],$w.' '.$o,'Salir de su casa','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_moverse5','s',3,$d['whodas_moverse5'],$w.' '.$o,'Andar largas distancias como un kilómetro (o algo equivalente)','nivel',null,null,true,$u,'','col-10');

	$o='cuidado';
	$c[]=new cmp($o,'e',null,'3. Cuidado personal ',$w);
	$c[]=new cmp('whodas_cuidado1','s',3,$d['whodas_cuidado1'],$w.' '.$o,'Lavarse todo el cuerpo (bañarse)','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_cuidado2','s',3,$d['whodas_cuidado2'],$w.' '.$o,'Vestirse','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_cuidado3','s',3,$d['whodas_cuidado3'],$w.' '.$o,'Comer','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_cuidado4','s',3,$d['whodas_cuidado4'],$w.' '.$o,'Estar solo/a durante unos día','nivel',null,null,true,$u,'','col-10');

	$o='relacion';
	$c[]=new cmp($o,'e',null,'4. Relacionarse con otras personas ',$w);
	$c[]=new cmp('whodas_relacionarce1','s',3,$d['whodas_relacionarce1'],$w.' '.$o,'Relacionarse con personas que no conoce','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_relacionarce2','s',3,$d['whodas_relacionarce2'],$w.' '.$o,'Mantener una amistad','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_relacionarce3','s',3,$d['whodas_relacionarce3'],$w.' '.$o,'Llevar bien con personas cercanas a usted','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_relacionarce4','s',3,$d['whodas_relacionarce4'],$w.' '.$o,'Hacer nuevos amigos','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_relacionarce5','s',3,$d['whodas_relacionarce5'],$w.' '.$o,'Tener relaciones sexuales','nivel',null,null,true,$u,'','col-10');

	$o='actividad';
	$c[]=new cmp($o,'e',null,'5. Actividades de la vida diaria',$w);
	$c[]=new cmp('whodas_actividades1','s',3,$d['whodas_actividades1'],$w.' '.$o,'Cumplir con sus quehaceres de la casa','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_actividades2','s',3,$d['whodas_actividades2'],$w.' '.$o,'Realizar bien los quehaceres más importantes de la casa','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_actividades3','s',3,$d['whodas_actividades3'],$w.' '.$o,'Acabar todos los quehaceres que tenía que hacer en la casa','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_actividades4','s',3,$d['whodas_actividades4'],$w.' '.$o,'Acabar sus quehaceres de la casa tan rápido como era necesario','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_actividades5','s',3,$d['whodas_actividades5'],$w.' '.$o,'Llevar a cabo su trabajo diario o las actividades escolares','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_actividades6','s',3,$d['whodas_actividades6'],$w.' '.$o,'Realizar bien las tareas más importantes de su trabajo o de la escuela','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_actividades7','s',3,$d['whodas_actividades7'],$w.' '.$o,'Acabar todo el trabajo que necesitaba hacer','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_actividades8','s',3,$d['whodas_actividades8'],$w.' '.$o,'Acabar su trabajo tan rápido como era necesario','nivel',null,null,true,$u,'','col-10');

	$o='participacion';
	$c[]=new cmp($o,'e',null,'6. Participación en sociedad',$w);
	$c[]=new cmp('whodas_participacion1','s',3,$d['whodas_participacion1'],$w.' '.$o,'Dificultad para participar, al mismo nivel que el resto de las personas, en actividades de la comunidad (p.e. fiestas, actividades religiosas u otras)','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_participacion2','s',3,$d['whodas_participacion2'],$w.' '.$o,'Dificultades debido a barreras u obstáculos existentes en su alrededor (entorno)','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_participacion3','s',3,$d['whodas_participacion3'],$w.' '.$o,'Dificultad para vivir con dignidad o respeto debido a las actitudes y acciones de otras personas','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_participacion4','s',3,$d['whodas_participacion4'],$w.' '.$o,'Cantidad de tiempo que ha dedicado a su "condición de salud" o a las consecuencias de la misma','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_participacion5','s',3,$d['whodas_participacion5'],$w.' '.$o,'Qué impacto emocional (qué tanto le ha afectado) su "condición de salud"','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_participacion6','s',3,$d['whodas_participacion6'],$w.' '.$o,'Qué impacto económico ha tenido usted o su familia debido a su "condición de salud"','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_participacion7','s',3,$d['whodas_participacion7'],$w.' '.$o,'Dificultad que ha tenido usted y/o su familia debido a su "condición de salud"','nivel',null,null,true,$u,'','col-10');
	$c[]=new cmp('whodas_participacion8','s',3,$d['whodas_participacion8'],$w.' '.$o,'Dificultad que ha tenido para realizar cosas que le ayuden a relajarse o disfrutar','nivel',null,null,true,$u,'','col-10');

	$o='dias';
	$c[]=new cmp($o,'e',null,'Días',$w);
	$c[]=new cmp('whodas_dias1','n',2,$d['whodas_dias1'],$w.' '.$o,'En los últimos 30 días, cuántos días ha tenido estas dificultades','nivel',null,null,true,$u,'','col-10','validardias');
	$c[]=new cmp('whodas_dias2','n',2,$d['whodas_dias2'],$w.' '.$o,'En los últimos 30 días, cuántos días no pudo realizar ninguna de sus actividades habituales (nada) o del trabajo debido a su "condición de salud"','nivel',null,null,true,$u,'','col-10','validardias');
	$c[]=new cmp('whodas_dias3','n',2,$d['whodas_dias3'],$w.' '.$o,'En los últimos 30 días, sin contar los días en que no pudo realizar "ninguna de sus actividades", cuántos días tuvo que recortar o reducir sus actividades habituales o del trabajo debido a su "condición de salud"','nivel',null,null,true,$u,'','col-10','validardias');
	
	$o='analisis';
	$c[]=new cmp($o,'e',null,'Analisis ',$w);
  $c[]=new cmp('porcentaje_comprension','t',20,$d['porcentaje_comprension'],$w.' '.$o,'Comprensión','porcentaje_comprension',null,'',false,false,'','col-15');
  $c[]=new cmp('porcentaje_moverse','t',20,$d['porcentaje_moverse'],$w.' '.$o,'Moverse','porcentaje_moverse',null,'',false,false,'','col-15');
  $c[]=new cmp('porcentaje_cuidado','t',20,$d['porcentaje_cuidado'],$w.' '.$o,'Cuidado','porcentaje_cuidado',null,'',false,false,'','col-15');
  $c[]=new cmp('porcentaje_relacionarce','t',20,$d['porcentaje_relacionarce'],$w.' '.$o,'Relacionarce','porcentaje_relacionarce',null,'',false,false,'','col-15');
  $c[]=new cmp('porcentaje_actividades','t',20,$d['porcentaje_actividades'],$w.' '.$o,'Actividades','porcentaje_actividades',null,'',false,false,'','col-15');
  $c[]=new cmp('porcentaje_participacion','t',20,$d['porcentaje_participacion'],$w.' '.$o,'Participacion','porcentaje_participacion',null,'',false,false,'','col-25');
  $c[]=new cmp('porcentaje_total','t',20,$d['porcentaje_total'],$w.' '.$o,'Puntaje Total','porcentaje_total',null,'',false,false,'','col-3');
  $c[]=new cmp('whodas_analisis','t',20,$d['whodas_analisis'],$w.' '.$o,'Clasificación','porcentaje_total',null,'',false,false,'','col-4');
  $c[]=new cmp('whodas_momento','t',20,$d['whodas_momento'],$w.' '.$o,'Momento','whodas_momento',null,'',false,false,'','col-3');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	
	return $rta;
   }

   function get_tamWhodas(){
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		// print_r($_POST);
		$sql="SELECT `tam_whodas`,`whodas_idpersona`,`whodas_tipodoc`,
		FN_CATALOGODESC(116,whodas_momento) whodas_momento,`whodas_comprension1`,`whodas_comprension2`,`whodas_comprension3`, 
		`whodas_comprension4`,`whodas_comprension5`,`whodas_comprension6`,
		`whodas_moverse1`,`whodas_moverse2`,`whodas_moverse3`,
		`whodas_moverse4`,`whodas_moverse5`,`whodas_cuidado1`,
		`whodas_cuidado2`,`whodas_cuidado3`,`whodas_cuidado4`,
		`whodas_relacionarce1`,`whodas_relacionarce2`,`whodas_relacionarce3`,
		`whodas_relacionarce4`,`whodas_relacionarce5`,`whodas_actividades1`,
		`whodas_actividades2`,`whodas_actividades3`,`whodas_actividades4`,
		`whodas_actividades5`,`whodas_actividades6`,`whodas_actividades7`,
		`whodas_actividades8`,`whodas_participacion1`,`whodas_participacion2`,
		`whodas_participacion3`,`whodas_participacion4`,`whodas_participacion5`,
		`whodas_participacion6`,`whodas_participacion7`,`whodas_participacion8`,
		`whodas_dias1`,`whodas_dias2`,`whodas_dias3`,
		`porcentaje_comprension`,`porcentaje_moverse`,`porcentaje_cuidado`,
		`porcentaje_relacionarce`,`porcentaje_actividades`,
		`porcentaje_participacion`,`porcentaje_total`,whodas_analisis,O.estado,P.idpersona,P.tipo_doc,concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) whodas_nombre,P.fecha_nacimiento whodas_fechanacimiento,YEAR(CURDATE())-YEAR(P.fecha_nacimiento) whodas_edad
		FROM `hog_tam_whodas` O
		LEFT JOIN personas P ON O.whodas_idpersona = P.idpersona and O.whodas_tipodoc=P.tipo_doc
		WHERE whodas_idpersona ='{$id[0]}' AND whodas_tipodoc='{$id[1]}' AND whodas_momento = '{$id[2]}'";
		// echo $sql;
		$info=datos_mysql($sql);
				return $info['responseResult'][0];
		}
	} 

function get_person(){
	// print_r($_POST);
	$id=divide($_POST['id']);
$sql="SELECT idpersona,tipo_doc,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,fecha_nacimiento,YEAR(CURDATE())-YEAR(fecha_nacimiento) Edad
FROM personas 
	WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."')";
	// echo $sql;
	$info=datos_mysql($sql);
	if (!$info['responseResult']) {
		return '';
	}
return json_encode($info['responseResult'][0]);
}

function focus_tamWhodas(){
	return 'tamWhodas';
   }
   
function men_tamWhodas(){
	$rta=cap_menus('tamWhodas','pro');
	return $rta;
   }

   function cap_menus($a,$b='cap',$con='con') {
	$rta = ""; 
	$acc=rol($a);
	if ($a=='tamWhodas') {  
		$rta .= "<li class='icono $a  grabar' title='Grabar' Onclick=\"grabar('$a',this);\" ></li>";
		
	}
	return $rta;
  }
   
function gra_tamWhodas(){
	$id=$_POST['idwhodas'];
	//print_r($_POST);
	if($id != "0"){
		return "No es posible actualizar el tamizaje";
	}else{

	$infodata_whodas=datos_mysql("SELECT whodas_momento,whodas_idpersona FROM hog_tam_whodas
		 WHERE whodas_idpersona = '{$_POST['whodas_idpersona']}' AND whodas_momento = 2 ");
	if (isset($infodata_whodas['responseResult'][0])){
		return "Ya se realizo los dos momentos";
	}else{
		$infodata2_whodas=datos_mysql("SELECT whodas_momento,whodas_idpersona FROM hog_tam_whodas
		 WHERE whodas_idpersona = '{$_POST['whodas_idpersona']}' AND whodas_momento = 1 ");
		if (isset($infodata2_whodas['responseResult'][0])){
			$idmomento = 2;
		}else{
			$idmomento = 1;
		}
	}

	

	$suma_com = (
			$_POST['whodas_comprension1']+
			$_POST['whodas_comprension2']+
			$_POST['whodas_comprension3']+
			$_POST['whodas_comprension4']+
			$_POST['whodas_comprension5']+
			$_POST['whodas_comprension6']
		);

	$suma_mov = (
			$_POST['whodas_moverse1']+
			$_POST['whodas_moverse2']+
			$_POST['whodas_moverse3']+
			$_POST['whodas_moverse4']+
			$_POST['whodas_moverse5']
		);

	$suma_cui = (
			$_POST['whodas_cuidado1']+
			$_POST['whodas_cuidado2']+
			$_POST['whodas_cuidado3']+
			$_POST['whodas_cuidado4']
		);

	$suma_rel = (
			$_POST['whodas_relacionarce1']+
			$_POST['whodas_relacionarce2']+
			$_POST['whodas_relacionarce3']+
			$_POST['whodas_relacionarce4']+
			$_POST['whodas_relacionarce5']
		);

	$suma_act = (
			$_POST['whodas_actividades1']+
			$_POST['whodas_actividades2']+
			$_POST['whodas_actividades3']+
			$_POST['whodas_actividades4']+
			$_POST['whodas_actividades5']+
			$_POST['whodas_actividades6']+
			$_POST['whodas_actividades7']+
			$_POST['whodas_actividades8']
		);

	$suma_par = (
			$_POST['whodas_participacion1']+
			$_POST['whodas_participacion2']+
			$_POST['whodas_participacion3']+
			$_POST['whodas_participacion4']+
			$_POST['whodas_participacion5']+
			$_POST['whodas_participacion6']+
			$_POST['whodas_participacion7']+
			$_POST['whodas_participacion8']
		);

		$suma_whodas = ($suma_com+$suma_mov+$suma_cui+$suma_rel+$suma_act+$suma_par);

	// var_dump($numero);
	switch ($suma_whodas) {
		case ($suma_whodas >= 1 && $suma_whodas <= 36):
			$pnt=' Ninguna Discapacidad';
			break;
		case ($suma_whodas >= 37 && $suma_whodas <= 72):
			$pnt=' Discapacidad Leve';
			break;
		case ($suma_whodas >= 73 && $suma_whodas <= 108):
			$pnt=' Discapacidad Moderada';
			break;
		case ($suma_whodas >= 109 && $suma_whodas <= 144):
				$pnt=' Discapacidad Severa';
			break;
		case ($suma_whodas >= 145 && $suma_whodas <= 180):
				$pnt=' Discapacidad Severa';
			break;
		default:
			$pnt='Error en el rango, por favor valide';
			break;
	}

	
		$sql="INSERT INTO hog_tam_whodas VALUES (null,
		TRIM(UPPER('{$_POST['whodas_idpersona']}')),
		{$idmomento},
		TRIM(UPPER('{$_POST['whodas_tipodoc']}')),
		TRIM(UPPER('{$_POST['whodas_comprension1']}')),
		TRIM(UPPER('{$_POST['whodas_comprension2']}')),
		TRIM(UPPER('{$_POST['whodas_comprension3']}')),
		TRIM(UPPER('{$_POST['whodas_comprension4']}')),
		TRIM(UPPER('{$_POST['whodas_comprension5']}')),
		TRIM(UPPER('{$_POST['whodas_comprension6']}')),
		TRIM(UPPER('{$_POST['whodas_moverse1']}')),
		TRIM(UPPER('{$_POST['whodas_moverse2']}')),
		TRIM(UPPER('{$_POST['whodas_moverse3']}')),
		TRIM(UPPER('{$_POST['whodas_moverse4']}')),
		TRIM(UPPER('{$_POST['whodas_moverse5']}')),
		TRIM(UPPER('{$_POST['whodas_cuidado1']}')),
		TRIM(UPPER('{$_POST['whodas_cuidado2']}')),
		TRIM(UPPER('{$_POST['whodas_cuidado3']}')),
		TRIM(UPPER('{$_POST['whodas_cuidado4']}')),
		TRIM(UPPER('{$_POST['whodas_relacionarce1']}')),
		TRIM(UPPER('{$_POST['whodas_relacionarce2']}')),
		TRIM(UPPER('{$_POST['whodas_relacionarce3']}')),
		TRIM(UPPER('{$_POST['whodas_relacionarce4']}')),
		TRIM(UPPER('{$_POST['whodas_relacionarce5']}')),
		TRIM(UPPER('{$_POST['whodas_actividades1']}')),
		TRIM(UPPER('{$_POST['whodas_actividades2']}')),
		TRIM(UPPER('{$_POST['whodas_actividades3']}')),
		TRIM(UPPER('{$_POST['whodas_actividades4']}')),
		TRIM(UPPER('{$_POST['whodas_actividades5']}')),
		TRIM(UPPER('{$_POST['whodas_actividades6']}')),
		TRIM(UPPER('{$_POST['whodas_actividades7']}')),
		TRIM(UPPER('{$_POST['whodas_actividades8']}')),
		TRIM(UPPER('{$_POST['whodas_participacion1']}')),
		TRIM(UPPER('{$_POST['whodas_participacion2']}')),
		TRIM(UPPER('{$_POST['whodas_participacion3']}')),
		TRIM(UPPER('{$_POST['whodas_participacion4']}')),
		TRIM(UPPER('{$_POST['whodas_participacion5']}')),
		TRIM(UPPER('{$_POST['whodas_participacion6']}')),
		TRIM(UPPER('{$_POST['whodas_participacion7']}')),
		TRIM(UPPER('{$_POST['whodas_participacion8']}')),
		TRIM(UPPER('{$_POST['whodas_dias1']}')),
		TRIM(UPPER('{$_POST['whodas_dias2']}')),
		TRIM(UPPER('{$_POST['whodas_dias3']}')),
		'{$suma_com}',
		'{$suma_mov}',
		'{$suma_cui}',
		'{$suma_rel}',
		'{$suma_act}',
		'{$suma_par}',
		$suma_whodas,
		'$pnt',
		TRIM(UPPER('{$_SESSION['us_sds']}')),
		DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
		// echo $sql;
	}
	  $rta=dato_mysql($sql);
	  return $rta; 
	}


	function opc_whodas_tipodoc($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}
	function opc_sexo($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
	}
	function opc_momento($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=116 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_departamento($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=105 and estado='A' ORDER BY 1",$id);
	}
	function opc_salud_mental($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=104 and estado='A' ORDER BY 1",$id);
	}
	function opc_estado_civil($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=47 and estado='A' ORDER BY 1",$id);
	}
	function opc_niv_educativo($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=52 and estado='A' ORDER BY 1",$id);
	}
	function opc_nivel($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=162 and estado='A'  ORDER BY 1 ",$id);
}

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
	   // $rta=iconv('UTF-8','ISO-8859-1',$rta);
	   // var_dump($a);
	   // var_dump($rta);
		   if ($a=='tamWhodas' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('tamWhodas','pro',event,'','lib.php',7,'tamWhodas');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
	