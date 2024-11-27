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

function lis_tamzung(){
	// concat(zung_idpersona,'_',zung_tipodoc,'_',zung_momento) ACCIONES,
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(zung_idpersona,'_',zung_tipodoc,'_',zung_momento) ACCIONES,tam_zung 'Cod. Registro',zung_idpersona Documento,FN_CATALOGODESC(1,zung_tipodoc) 'Tipo de Documento',CONCAT_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres, 
	FN_CATALOGODESC(21,P.sexo) Sexo,FN_CATALOGODESC(116,zung_momento) Momento,`zung_puntaje` Puntaje ,`zung_analisis` Analisis 
FROM hog_tam_zung O
LEFT JOIN personas P ON O.zung_idpersona = P.idpersona
		WHERE '1'='1'";
	$sql.=whe_tamzung();
	$sql.=" ORDER BY 1";

	 $sql1="SELECT * 
	  FROM `hog_tam_zung` WHERE 1";
	$sql1.=whe_tamzung();	
	//echo $sql;
		$_SESSION['sql_tamzung']=$sql1;
		$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"tamzung",20);
}

function whe_tamzung() {
	$sql = "";
	if ($_POST['fidentificacion'])
		$sql .= " AND zung_idpersona like '%".$_POST['fidentificacion']."%'";
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

function cmp_tamzung(){
	$rta="";
	$t=['tam_zung'=>'','zung_tipodoc'=>'','zung_nombre'=>'','zung_idpersona'=>'','zung_fechanacimiento'=>'','zung_puntaje'=>'','zung_momento'=>'','zung_analisis'=>'','zung_edad'=>'','zung_anuncio1'=>'','zung_anuncio2'=>'','zung_anuncio3'=>'','zung_anuncio4'=>'','zung_anuncio5'=>'','zung_anuncio6'=>'','zung_anuncio7'=>'','zung_anuncio8'=>'','zung_anuncio9'=>'','zung_anuncio10'=>'','zung_anuncio11'=>'','zung_anuncio12'=>'','zung_anuncio13'=>'','zung_anuncio14'=>'','zung_anuncio15'=>'','zung_anuncio16'=>'','zung_anuncio17'=>'','zung_anuncio18'=>'','zung_anuncio19'=>'','zung_anuncio20'=>'']; 
	$w='tamzung';
	$d=get_tamzung(); 
	if ($d=="") {$d=$t;}
	$u = ($d['tam_zung']!='') ? false : true ;
	$o='datos';
    $key='srch';
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('idzung','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('zung_idpersona','n','20',$d['zung_idpersona'],$w.' '.$o.' '.$key,'N° Identificación','zung_idpersona',null,'',false,$u,'','col-2');
	$c[]=new cmp('zung_tipodoc','s','3',$d['zung_tipodoc'],$w.' '.$o.' '.$key,'Tipo Identificación','zung_tipodoc',null,'',false,$u,'','col-25','getDatForm(\'srch\',\'person\',[\'datos\']);');
	$c[]=new cmp('zung_nombre','t','50',$d['zung_nombre'],$w.' '.$o,'nombres','zung_nombre',null,'',false,false,'','col-4');
	$c[]=new cmp('zung_fechanacimiento','d','10',$d['zung_fechanacimiento'],$w.' '.$o,'fecha nacimiento','zung_fechanacimiento',null,'',false,false,'','col-15');
    $c[]=new cmp('zung_edad','n','3',$d['zung_edad'],$w.' '.$o,'edad','zung_edad',null,'',true,false,'','col-1');
    
	$o='actv';
	$c[]=new cmp($o,'e',null,'Escala',$w);
	$c[]=new cmp('zung_anuncio1','s',3,$d['zung_anuncio1'],$w.' '.$o,'1. Me siento triste y deprimido.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio2','s',3,$d['zung_anuncio2'],$w.' '.$o,'2. Por las mañanas me siento mejor que por las tardes.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio3','s',3,$d['zung_anuncio3'],$w.' '.$o,'3. Frecuentemente tengo ganas de llorar y a veces lloro.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio4','s',3,$d['zung_anuncio4'],$w.' '.$o,'4. Me cuesta mucho dormir o duermo mal por las noches.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio5','s',3,$d['zung_anuncio5'],$w.' '.$o,'5. Ahora tengo tanto apetito como antes.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio6','s',3,$d['zung_anuncio6'],$w.' '.$o,'6. Todavía me siento atraído por el sexo opuesto.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio7','s',3,$d['zung_anuncio7'],$w.' '.$o,'7. Creo que estoy adelgazando.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio8','s',3,$d['zung_anuncio8'],$w.' '.$o,'8. Estoy estreñido.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio9','s',3,$d['zung_anuncio9'],$w.' '.$o,'9. Tengo palpitaciones.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio10','s',3,$d['zung_anuncio10'],$w.' '.$o,'10. Me canso por cualquier cosa.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio11','s',3,$d['zung_anuncio11'],$w.' '.$o,'11. Mi cabeza está tan despejada como antes.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio12','s',3,$d['zung_anuncio12'],$w.' '.$o,'12. Hago las cosas con la misma facilidad que antes.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio13','s',3,$d['zung_anuncio13'],$w.' '.$o,'13. Me siento agitado e intranquilo y no puedo estar quieto.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio14','s',3,$d['zung_anuncio14'],$w.' '.$o,'14. Tengo esperanza y confío en el futuro.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio15','s',3,$d['zung_anuncio15'],$w.' '.$o,'15. Me siento más irritable que habitualmente.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio16','s',3,$d['zung_anuncio16'],$w.' '.$o,'16. Encuentro fácil tomar decisiones.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio17','s',3,$d['zung_anuncio17'],$w.' '.$o,'17. Me creo útil y necesario para la gente.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio18','s',3,$d['zung_anuncio18'],$w.' '.$o,'18. Encuentro agradable vivir, mi vida es plena.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio19','s',3,$d['zung_anuncio19'],$w.' '.$o,'19. Creo que sería mejor para los demás si me muriera.','escala',null,null,true,$u,'','col-10');
	$c[]=new cmp('zung_anuncio20','s',3,$d['zung_anuncio20'],$w.' '.$o,'20. Me gustan las mismas cosas que solían agradarme.','escala',null,null,true,$u,'','col-10');

	$o='inter';
	$c[]=new cmp($o,'e',null,'INTERPRETACIÓN ',$w);
    $c[]=new cmp('zung_puntaje','n','3',$d['zung_puntaje'],$w.' '.$o,'Total','zung_puntaje',null,'',false,false,'','col-1');
    $c[]=new cmp('zung_momento','t','20',$d['zung_momento'],$w.' '.$o,'Momento','zung_momento',null,'',false,false,'','col-3');
    $c[]=new cmp('zung_analisis','t','100',$d['zung_analisis'],$w.' '.$o,'Analisis','zung_analisis',null,'',false,false,'','col-6');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	
	return $rta;
   }


   function get_tamzung(){
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		// print_r($_POST);
		$sql="SELECT `tam_zung`,`zung_idpersona`,`zung_tipodoc`,
		FN_CATALOGODESC(116,zung_momento) zung_momento, `zung_anuncio1`, `zung_anuncio2`, `zung_anuncio3`, `zung_anuncio4`, `zung_anuncio5`, `zung_anuncio6`, `zung_anuncio7`, `zung_anuncio8`, `zung_anuncio9`, `zung_anuncio10`, `zung_anuncio11`, `zung_anuncio12`, `zung_anuncio13`, `zung_anuncio14`, `zung_anuncio15`, `zung_anuncio16`, `zung_anuncio17`, `zung_anuncio18`, `zung_anuncio19`, `zung_anuncio20`,`zung_analisis`,`zung_puntaje`,O.estado,P.idpersona,P.tipo_doc,concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) zung_nombre,P.fecha_nacimiento zung_fechanacimiento,YEAR(CURDATE())-YEAR(P.fecha_nacimiento) zung_edad 
		FROM `hog_tam_zung` O
		LEFT JOIN personas P ON O.zung_idpersona = P.idpersona and O.zung_tipodoc=P.tipo_doc
		WHERE zung_idpersona ='{$id[0]}' AND zung_tipodoc='{$id[1]}' AND zung_momento = '{$id[2]}'  ";
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

function focus_tamzung(){
	return 'tamzung';
   }
   
function men_tamzung(){
	$rta=cap_menus('tamzung','pro');
	return $rta;
   }

   function cap_menus($a,$b='cap',$con='con') {
	$rta = ""; 
	$acc=rol($a);
	if ($a=='tamzung') {  
		$rta .= "<li class='icono $a  grabar' title='Grabar' Onclick=\"grabar('$a',this);\" ></li>";
	}
	return $rta;
  }
   
function gra_tamzung(){
	$id=$_POST['idzung'];
	//print_r($_POST);
	if($id != "0"){
		return "No es posible actualizar el tamizaje";
	}else{

	$infodata_zung=datos_mysql("SELECT zung_momento,zung_idpersona FROM hog_tam_zung
		 WHERE zung_idpersona = '{$_POST['zung_idpersona']}' AND zung_momento = 2 ");
	if (isset($infodata_zung['responseResult'][0])){
		return "Ya se realizo los dos momentos";
	}else{
		$infodata2_zung=datos_mysql("SELECT zung_momento,zung_idpersona FROM hog_tam_zung
		 WHERE zung_idpersona = '{$_POST['zung_idpersona']}' AND zung_momento = 1 ");
		if (isset($infodata2_zung['responseResult'][0])){
			$idmomento = 2;
		}else{
			$idmomento = 1;
		}
	}

	
	$suma_zung = (
		$_POST['zung_anuncio1']+
		$_POST['zung_anuncio2']+
		$_POST['zung_anuncio3']+
		$_POST['zung_anuncio4']+
		$_POST['zung_anuncio5']+
		$_POST['zung_anuncio6']+
		$_POST['zung_anuncio7']+
		$_POST['zung_anuncio8']+
		$_POST['zung_anuncio9']+
		$_POST['zung_anuncio10']+
		$_POST['zung_anuncio11']+
		$_POST['zung_anuncio12']+
		$_POST['zung_anuncio13']+
		$_POST['zung_anuncio14']+
		$_POST['zung_anuncio15']+
		$_POST['zung_anuncio16']+
		$_POST['zung_anuncio17']+
		$_POST['zung_anuncio18']+
		$_POST['zung_anuncio19']+
		$_POST['zung_anuncio20']
	);

	if($suma_zung <= 28){
		$escala_zung = 'Ausencia de depresión';
	}else if($suma_zung >= 29 && $suma_zung <= 41){
		$escala_zung = 'Depresión leve';
	}else if($suma_zung >= 42 && $suma_zung <= 53){
		$escala_zung = 'Depresión moderada';
	}else{
		$escala_zung = 'Depresión grave';
	}


		$sql="INSERT INTO hog_tam_zung VALUES (null,
		TRIM(UPPER('{$_POST['zung_idpersona']}')),
		TRIM(UPPER('{$idmomento}')),
		TRIM(UPPER('{$_POST['zung_tipodoc']}')),
		TRIM(UPPER('{$_POST['zung_anuncio1']}')),
		TRIM(UPPER('{$_POST['zung_anuncio2']}')),
		TRIM(UPPER('{$_POST['zung_anuncio3']}')),
		TRIM(UPPER('{$_POST['zung_anuncio4']}')),
		TRIM(UPPER('{$_POST['zung_anuncio5']}')),
		TRIM(UPPER('{$_POST['zung_anuncio6']}')),
		TRIM(UPPER('{$_POST['zung_anuncio7']}')),
		TRIM(UPPER('{$_POST['zung_anuncio8']}')),
		TRIM(UPPER('{$_POST['zung_anuncio9']}')),
		TRIM(UPPER('{$_POST['zung_anuncio10']}')),
		TRIM(UPPER('{$_POST['zung_anuncio11']}')),
		TRIM(UPPER('{$_POST['zung_anuncio12']}')),
		TRIM(UPPER('{$_POST['zung_anuncio13']}')),
		TRIM(UPPER('{$_POST['zung_anuncio14']}')),
		TRIM(UPPER('{$_POST['zung_anuncio15']}')),
		TRIM(UPPER('{$_POST['zung_anuncio16']}')),
		TRIM(UPPER('{$_POST['zung_anuncio17']}')),
		TRIM(UPPER('{$_POST['zung_anuncio18']}')),
		TRIM(UPPER('{$_POST['zung_anuncio19']}')),
		TRIM(UPPER('{$_POST['zung_anuncio20']}')),
		TRIM(UPPER('{$escala_zung}')),
		TRIM(UPPER('{$suma_zung}')),
		TRIM(UPPER('{$_SESSION['us_sds']}')),
		DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
		//echo $sql;
	}
	  $rta=dato_mysql($sql);
	//   return "correctamente";
	  return $rta;
	}



	function opc_zung_tipodoc($id=''){
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
	function opc_escala($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=119 and estado='A'  ORDER BY 1 ",$id);
	}

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
	   // $rta=iconv('UTF-8','ISO-8859-1',$rta);
	   // var_dump($a);
	   // var_dump($rta);
		   if ($a=='tamzung' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('tamzung','pro',event,'','lib.php',7,'tamzung');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }