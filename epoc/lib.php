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


function lis_tamcope(){
	// concat(cope_idpersona,'_',cope_tipodoc,'_',cope_momento) ACCIONES,
	$info=datos_mysql("SELECT COUNT(*) total from hog_tam_cope O LEFT JOIN personas P ON O.cope_idpersona = P.idpersona where 1 ".whe_tamcope() ." AND O.usu_creo ='".$_SESSION['us_sds']."'");
	$total=$info['responseResult'][0]['total'];
	$regxPag=12;

	$sql="SELECT concat(cope_idpersona,'_',cope_tipodoc,'_',cope_momento) ACCIONES,tam_cope  'Cod. registro',cope_idpersona Documento,FN_CATALOGODESC(1,cope_tipodoc) 'Tipo de Documento',CONCAT_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres, 
	FN_CATALOGODESC(21,P.sexo) Sexo,FN_CATALOGODESC(135,cope_momento) Momento,`cope_puntajea` 'Pts. Afrontamiento',cope_descripciona Descripcion, `cope_puntajee` 'Pts. Evitación',cope_descripcione Descripción
	FROM hog_tam_cope O
	LEFT JOIN personas P ON O.cope_idpersona = P.idpersona
		WHERE 1 ";
	$sql.=whe_tamcope();
	$sql.=" AND O.usu_creo ='".$_SESSION['us_sds']."'";
	$sql.=" ORDER BY 1";

		$datos=datos_mysql($sql);
		return create_table($total,$datos["responseResult"],"tamcope",$regxPag);
}

function whe_tamcope() {
	$sql = "";
	if ($_POST['fidentificacion'])
		$sql .= " AND cope_idpersona like '%".$_POST['fidentificacion']."%'";
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

function cmp_tamcope(){
	$rta="";
	$t=['tam_cope'=>'','cope_tipodoc'=>'','cope_nombre'=>'','cope_idpersona'=>'','cope_fechanacimiento'=>'','cope_puntajea'=>'','cope_momento'=>'','cope_reporta'=>'','cope_edad'=>'','cope_lugarnacimiento'=>'','cope_condicionsalud'=>'','cope_estadocivil'=>'','cope_escolaridad'=>'','cope_pregunta1'=>'','cope_pregunta2'=>'','cope_pregunta3'=>'','cope_pregunta4'=>'','cope_pregunta5'=>'','cope_pregunta6'=>'','cope_pregunta7'=>'','cope_pregunta8'=>'','cope_pregunta9'=>'','cope_pregunta10'=>'','cope_pregunta11'=>'','cope_pregunta12'=>'','cope_pregunta13'=>'','cope_pregunta14'=>'','cope_pregunta15'=>'','cope_pregunta16'=>'','cope_pregunta17'=>'','cope_pregunta18'=>'','cope_pregunta19'=>'','cope_pregunta20'=>'','cope_pregunta21'=>'','cope_pregunta22'=>'','cope_pregunta23'=>'','cope_pregunta24'=>'','cope_pregunta25'=>'','cope_pregunta26'=>'','cope_pregunta27'=>'','cope_pregunta28'=>'','cope_evaluacion1'=>'','cope_evaluacion2'=>'','cope_evaluacion3'=>'','cope_evaluacion4'=>'','cope_evaluacion5'=>'','cope_evaluacion6'=>'','cope_evaluacion7'=>'','cope_evaluacion8'=>'','cope_evaluacion9'=>'','cope_evaluacion10'=>'','cope_evaluacion11'=>'','cope_evaluacion12'=>'','cope_evaluacion13'=>'','cope_evaluacion14'=>'','cope_evaluacion15'=>'','cope_evaluacion16'=>'','cope_evaluacion17'=>'','cope_evaluacion18'=>'','cope_evaluacion19'=>'','cope_evaluacion20'=>'','cope_evaluacion21'=>'','cope_evaluacion22'=>'','cope_evaluacion23'=>'','cope_evaluacion24'=>'','cope_evaluacion25'=>'','cope_evaluacion26'=>'','cope_evaluacion27'=>'','cope_evaluacion28'=>'','cope_puntajee'=>'','cope_descripciona'=>'','cope_descripcione'=>'','interpretacion'=>''	 ]; 
	$w='tamcope';
	$d=get_tamcope(); 
	if ($d=="") {$d=$t;}
	$u = ($d['tam_cope']!='') ? false : true ;
	$o='datos';
    $key='srch';
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('idcope','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('cope_idpersona','t','20',$d['cope_idpersona'],$w.' '.$o.' '.$key,'N° Identificación','cope_idpersona',null,'',false,$u,'','col-15');
	$c[]=new cmp('cope_tipodoc','s','3',$d['cope_tipodoc'],$w.' '.$o.' '.$key,'Tipo Identificación','cope_tipodoc',null,'',false,$u,'','col-2','getDatForm(\'srch\',\'person\',[\'datos\']);');
	$c[]=new cmp('cope_nombre','t','50',$d['cope_nombre'],$w.' '.$o,'nombres','cope_nombre',null,'',false,false,'','col-4');
	$c[]=new cmp('cope_fechanacimiento','d','10',$d['cope_fechanacimiento'],$w.' '.$o,'fecha nacimiento','cope_fechanacimiento',null,'',false,false,'','col-15');
    $c[]=new cmp('cope_edad','n','3',$d['cope_edad'],$w.' '.$o,'edad','cope_edad',null,'',true,false,'','col-1');
	$c[]=new cmp('cope_reporta','s','3',$d['cope_reporta'],$w.' '.$o,'Caso reportado','cope_reporta',null,'',true,$u,'','col-3');
	$c[]=new cmp('cope_momento','s','3',$d['cope_momento'],$w.' '.$o,'Tipo','tipo_activi',null,'',true,$u,'','col-3');
	
	$o='info';
	$c[]=new cmp($o,'e',null,'INFORMACIÓN',$w);
	$c[]=new cmp('cope_pregunta1','s','3',$d['cope_pregunta1'],$w.' '.$o,'1. Intento conseguir que alguien me ayude o aconseje sobre que hacer.	','caracterizacion',null,'',true,$u,'','col-5');
  	$c[]=new cmp('cope_pregunta2','s','3',$d['cope_pregunta2'],$w.' '.$o,'2. Concentro mis esfuerzos en hacer algo sobre la situacion en la que estoy.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta3','s','3',$d['cope_pregunta3'],$w.' '.$o,'3. Acepto la realidad de lo que ha sucedido.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta4','s','3',$d['cope_pregunta4'],$w.' '.$o,'4. Recurro al trabajo o a otras actividades para apartar las cosas de mi mente.			','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta5','s','3',$d['cope_pregunta5'],$w.' '.$o,'5. Me digo a mi mismo "esto no es real".','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta6','s','3',$d['cope_pregunta6'],$w.' '.$o,'6. Intento proponer una estrategia sobre que hacer.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta7','s','3',$d['cope_pregunta7'],$w.' '.$o,'7. Hago bromas sobre ello.	','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta8','s','3',$d['cope_pregunta8'],$w.' '.$o,'8. Me critico a mi mismo.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta9','s','3',$d['cope_pregunta9'],$w.' '.$o,'9. Consigo apoyo emocional de otros.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta10','s','3',$d['cope_pregunta10'],$w.' '.$o,'10. Tomo medidas para intentar que la situacion mejore.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta11','s','3',$d['cope_pregunta11'],$w.' '.$o,'11. Renuncio a intentar ocuparme de ello.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta12','s','3',$d['cope_pregunta12'],$w.' '.$o,'12. Digo cosas para dar rienda suelta a mis sentimientos desagradables.	','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta13','s','3',$d['cope_pregunta13'],$w.' '.$o,'13. Me niego a creer que haya sucedido.	','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta14','s','3',$d['cope_pregunta14'],$w.' '.$o,'14. Intento verlo con otros ojos, para hacer que parezca mas positivo.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta15','s','3',$d['cope_pregunta15'],$w.' '.$o,'15. Utilizo alcohol u otras drogas para hacerme sentir mejor.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta16','s','3',$d['cope_pregunta16'],$w.' '.$o,'16. Intento hallar consuelo en mi religión o creencias espirituales.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta17','s','3',$d['cope_pregunta17'],$w.' '.$o,'17. Consigo el consuelo y la comprensión de alguien.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta18','s','3',$d['cope_pregunta18'],$w.' '.$o,'18. Busco algo bueno en lo que esta sucediendo.	','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta19','s','3',$d['cope_pregunta19'],$w.' '.$o,'19. Me río de la situacion.	','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta20','s','3',$d['cope_pregunta20'],$w.' '.$o,'20. Rezo o medito.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta21','s','3',$d['cope_pregunta21'],$w.' '.$o,'21. Aprendo a vivir con ello.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta22','s','3',$d['cope_pregunta22'],$w.' '.$o,'22. Hago algo para pensar menos en ello, tal como ir al cine o ver la televisión.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta23','s','3',$d['cope_pregunta23'],$w.' '.$o,'23. Expreso mis sentimientos negativos.	','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta24','s','3',$d['cope_pregunta24'],$w.' '.$o,'24. útilizo alcohol u otras drogas para ayudarme a superarlo.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta25','s','3',$d['cope_pregunta25'],$w.' '.$o,'25. Renuncio al intento de hacer frente al problema.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta26','s','3',$d['cope_pregunta26'],$w.' '.$o,'26. Pienso detenidamente sobre los pasos a seguir.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta27','s','3',$d['cope_pregunta27'],$w.' '.$o,'27. Me hecho la culpa de los que ha sucedido.','caracterizacion',null,'',true,$u,'','col-5');
	$c[]=new cmp('cope_pregunta28','s','3',$d['cope_pregunta28'],$w.' '.$o,'28. Consigo que otras personas me ayuden o aconsejen.','caracterizacion',null,'',true,$u,'','col-5');
	

	$o='totales';
	$c[]=new cmp($o,'e',null,'Resultado ',$w);
	$c[]=new cmp('cope_puntajea','t',3,$d['cope_puntajea'],$w.' '.$o,'Puntaje Caracterización','cope_puntajea',null,'',false,false,'','col-5');
	$c[]=new cmp('cope_descripciona','t',3,$d['cope_descripciona'],$w.' '.$o,'Descripcion Caracterización','cope_descripciona',null,'',false,false,'','col-5');
	$c[]=new cmp('cope_puntajee','t',3,$d['cope_puntajee'],$w.' '.$o,'Puntaje Caracterización','cope_puntajee',null,'',false,false,'','col-5');
	$c[]=new cmp('cope_descripcione','t',3,$d['cope_descripcione'],$w.' '.$o,'Descripcion Caracterización','cope_descripcione',null,'',false,false,'','col-5');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	
	return $rta;
   }

   function get_tamcope(){
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		// print_r($_POST);
		$sql="SELECT `tam_cope`,`cope_idpersona`,`cope_tipodoc`,
		cope_momento,cope_reporta,cope_pregunta1,cope_pregunta2,cope_pregunta3,cope_pregunta4,cope_pregunta5,cope_pregunta6,cope_pregunta7,cope_pregunta8,cope_pregunta9,cope_pregunta10,cope_pregunta11,cope_pregunta12,cope_pregunta13,cope_pregunta14,cope_pregunta15,cope_pregunta16,cope_pregunta17,cope_pregunta18,cope_pregunta19,cope_pregunta20,cope_pregunta21,cope_pregunta22,cope_pregunta23,cope_pregunta24,cope_pregunta25,cope_pregunta26,cope_pregunta27,cope_pregunta28,cope_puntajea,cope_descripciona,cope_puntajee,cope_descripcione,
		P.idpersona,P.tipo_doc,concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) cope_nombre,P.fecha_nacimiento cope_fechanacimiento,YEAR(CURDATE())-YEAR(P.fecha_nacimiento) cope_edad
		FROM `hog_tam_cope` O
		LEFT JOIN personas P ON O.cope_idpersona = P.idpersona and O.cope_tipodoc=P.tipo_doc
		LEFT JOIN personas_datocomp C ON O.cope_idpersona = C.dc_documento AND O.cope_tipodoc=C.dc_documento
		WHERE cope_idpersona ='{$id[0]}' AND cope_tipodoc='{$id[1]}' AND cope_momento = '{$id[2]}' ";
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
left JOIN personas_datocomp ON idpersona=dc_documento and tipo_doc=dc_tipo_doc
	WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."')";
	// return print_r(json_encode($sql));
	$info=datos_mysql($sql);
	if (!$info['responseResult']) {
		return json_encode (new stdClass);
	}
return json_encode($info['responseResult'][0]);
}

function focus_tamcope(){
	return 'tamcope';
   }
   
function men_tamcope(){
	$rta=cap_menus('tamcope','pro');
	return $rta;
   }

   function cap_menus($a,$b='cap',$con='con') {
	$rta = ""; 
	$acc=rol($a);
	if ($a=='tamcope') {  
		$rta .= "<li class='icono $a  grabar' title='Grabar' Onclick=\"grabar('$a',this);\" ></li>";
	}
	return $rta;
  }
   
function gra_tamcope(){
	$id=$_POST['idcope'];
	//print_r($_POST);
	if($id != "0"){
		return "No es posible actualizar el tamizaje";
	}else{

	/* $infodata_cope=datos_mysql("SELECT cope_momento,cope_idpersona FROM hog_tam_cope
		 WHERE cope_idpersona = {$_POST['cope_idpersona']} AND cope_momento = 2 ");
	if (isset($infodata_cope['responseResult'][0])){
		return "Ya se realizo los dos momentos";
	}else{
		$infodata2_cope=datos_mysql("SELECT cope_momento,cope_idpersona FROM hog_tam_cope
		 WHERE cope_idpersona = {$_POST['cope_idpersona']} AND cope_momento = 1 ");
		if (isset($infodata2_cope['responseResult'][0])){
			$idmomento = 2;
		}else{
			$idmomento = 1;
		} */
	

	/* $sql3="UPDATE personas_datocomp SET 
	estadocivil=TRIM(UPPER('{$_POST['cope_estadocivil']}')),
	nivel_educativo=TRIM(UPPER('{$_POST['cope_escolaridad']}')),
	ocupacion=TRIM(UPPER('{$_POST['cope_ocupacion']}')),
	`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
	`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
	WHERE idpersona ={$_POST['cope_idpersona']} AND tipo_doc={$_POST['cope_tipodoc']}";
	 $rta3=dato_mysql($sql3); */

	$suma_afronta = (
		
		$_POST['cope_pregunta2']+
		$_POST['cope_pregunta6']+
		$_POST['cope_pregunta9']+
		$_POST['cope_pregunta1']+
		$_POST['cope_pregunta16']+
		$_POST['cope_pregunta14']+
		$_POST['cope_pregunta3']+
		$_POST['cope_pregunta12']+

		$_POST['cope_pregunta10']+
		$_POST['cope_pregunta26']+
		$_POST['cope_pregunta17']+
		$_POST['cope_pregunta28']+
		$_POST['cope_pregunta20']+
		$_POST['cope_pregunta18']+
		$_POST['cope_pregunta21']+
		$_POST['cope_pregunta23']
	);

	$suma_evita = (
		$_POST['cope_pregunta5']+
		$_POST['cope_pregunta7']+
		$_POST['cope_pregunta4']+
		$_POST['cope_pregunta8']+
		$_POST['cope_pregunta11']+
		$_POST['cope_pregunta15']+

		$_POST['cope_pregunta13']+
		$_POST['cope_pregunta19']+
		$_POST['cope_pregunta22']+
		$_POST['cope_pregunta27']+
		$_POST['cope_pregunta25']+
		$_POST['cope_pregunta24']
	);


	switch ($suma_afronta) {
		case ($suma_afronta >15 && $suma_afronta < 33):
			$desafr='RIESGO BAJO';
			break;
		case ($suma_afronta >32 && $suma_afronta < 49):
				$desafr='RIESGO MEDIO';
			break;
		case ($suma_afronta >48 && $suma_afronta < 65):
				$desafr='RIESGO ALTO';
			break;
		default:
			$desafr='Error en el rango, por favor valide';
			break;
	}

	switch ($suma_evita) {
		case ($suma_evita >11 && $suma_evita <24):
			$desevit='RIESGO BAJO';
			break;
		case ($suma_evita >23 && $suma_evita < 36):
			$desevit='RIESGO MEDIO';
			break;
		case ($suma_evita >35 && $suma_evita < 49):
				$desevit='RIESGO ALTO';
			break;
		default:
			$desevit='Error en el rango, por favor valide';
			break;
	}
	// $suma_cope = ($su	ma_afronta+$suma_comp+$suma_ambi);

		$sql="INSERT INTO hog_tam_cope VALUES (
			null,
		TRIM(UPPER('{$_POST['cope_tipodoc']}')),
		TRIM(UPPER('{$_POST['cope_idpersona']}')),
		TRIM(UPPER('{$_POST['cope_momento']}')),
		TRIM(UPPER('{$_POST['cope_reporta']}')),
		TRIM(UPPER('{$_POST['cope_pregunta1']}')),
		TRIM(UPPER('{$_POST['cope_pregunta2']}')),
		TRIM(UPPER('{$_POST['cope_pregunta3']}')),
		TRIM(UPPER('{$_POST['cope_pregunta4']}')),
		TRIM(UPPER('{$_POST['cope_pregunta5']}')),
		TRIM(UPPER('{$_POST['cope_pregunta6']}')),
		TRIM(UPPER('{$_POST['cope_pregunta7']}')),
		TRIM(UPPER('{$_POST['cope_pregunta8']}')),
		TRIM(UPPER('{$_POST['cope_pregunta9']}')),
		TRIM(UPPER('{$_POST['cope_pregunta10']}')),
		TRIM(UPPER('{$_POST['cope_pregunta11']}')),
		TRIM(UPPER('{$_POST['cope_pregunta12']}')),
		TRIM(UPPER('{$_POST['cope_pregunta13']}')),
		TRIM(UPPER('{$_POST['cope_pregunta14']}')),
		TRIM(UPPER('{$_POST['cope_pregunta15']}')),
		TRIM(UPPER('{$_POST['cope_pregunta16']}')),
		TRIM(UPPER('{$_POST['cope_pregunta17']}')),
		TRIM(UPPER('{$_POST['cope_pregunta18']}')),
		TRIM(UPPER('{$_POST['cope_pregunta19']}')),
		TRIM(UPPER('{$_POST['cope_pregunta20']}')),
		TRIM(UPPER('{$_POST['cope_pregunta21']}')),
		TRIM(UPPER('{$_POST['cope_pregunta22']}')),
		TRIM(UPPER('{$_POST['cope_pregunta23']}')),
		TRIM(UPPER('{$_POST['cope_pregunta24']}')),
		TRIM(UPPER('{$_POST['cope_pregunta25']}')),
		TRIM(UPPER('{$_POST['cope_pregunta26']}')),
		TRIM(UPPER('{$_POST['cope_pregunta27']}')),
		TRIM(UPPER('{$_POST['cope_pregunta28']}')),
		'{$suma_afronta}',
		'{$desafr}',
		'{$suma_evita}',
		'{$desevit}',
		TRIM(UPPER('{$_SESSION['us_sds']}')),
		DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
		// echo $sql;
	}
	  $rta=dato_mysql($sql);
	  return $rta; 
}


	function opc_cope_reporta($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=120 and estado='A' ORDER BY 1",$id);
	}
	function opc_tipo_activi($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=34 and estado='A' ORDER BY 1",$id);
		}
	function opc_cope_tipodoc($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}
	function opc_sexo($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
	}
	function opc_departamento($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=105 and estado='A' ORDER BY 1",$id);
	}
	function opc_salud_mental($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=104 and estado='A' ORDER BY 1",$id);
	}
	function opc_caracterizacion($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=135 and estado='A' ORDER BY 1",$id);
	}

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
	   // $rta=iconv('UTF-8','ISO-8859-1',$rta);
	//    var_dump($a);
	//    var_dump($rta);
		   if ($a=='tamcope' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('tamcope','pro',event,'','lib.php',7,'tamcope');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
