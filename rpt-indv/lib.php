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



function lis_rptindv(){
	$info=datos_mysql("SELECT COUNT(*) total FROM personas P LEFT JOIN hog_viv V ON P.vivipersona = V.idviv	LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN hog_tam_apgar A ON P.idpersona = A.idpersona AND P.tipo_doc = A.tipodoc AND P.vivipersona = V.idviv
		LEFT JOIN hog_tam_findrisc F ON P.tipo_doc = F.tipodoc AND P.idpersona = F.idpersona LEFT JOIN hog_tam_oms O ON P.tipo_doc = O.tipodoc AND P.idpersona = O.idpersona WHERE '1' ".whe_rptindv());
	$total=$info['responseResult'][0]['total'];
	$regxPag=10;
	$pag=(isset($_POST['pag-rptindv']))? ($_POST['pag-rptindv']-1)* $regxPag:0;

	$sql="SELECT  concat_ws('_',P.tipo_doc,P.idpersona ) as ACCIONES,
	G.localidad AS Localidad, G.territorio AS Territorio,G.direccion AS Direccion, CONCAT(V.complemento1, ' ', V.nuc1, ' ', V.complemento2, ' ', V.nuc2, ' ', V.complemento3, ' ', V.nuc3) AS Complementos, V.telefono1 AS Telefono_Contacto,
	P.vivipersona AS Cod_Familia,
	P.tipo_doc AS Tipo_Documento, P.idpersona AS N°_Documento, CONCAT(P.nombre1, ' ', P.nombre2, ' ', P.apellido1, ' ', P.apellido2) AS Usuario, P.sexo AS Sexo, P.genero AS Genero, P.nacionalidad AS Nacionalidad,
	TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS Curso_de_Vida,
		CASE
			WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 0 AND 5 THEN 'Primera Infancia'
			WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 6 AND 11 THEN 'Infancia'
			WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 12 AND 17 THEN 'Adolescencia'
			WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 18 AND 28 THEN 'Juventud'
			WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 29 AND 59 THEN 'Adultez'
			WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) >= 60 THEN 'Vejez'
			ELSE 'Edad Desconocida'
		END AS Rango_Edad,
	A.puntaje AS Puntaje_Apgar, A.descripcion AS Riesgo_Apgar,
	F.puntaje AS Puntaje_Findrisc, F.descripcion AS Riesgo_Findrisc,
	O.puntaje AS Puntaje_Oms, O.descripcion AS Riesgo_Oms
	FROM personas P 
	LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
	LEFT JOIN hog_geo G ON V.idpre = G.idgeo
	LEFT JOIN hog_tam_apgar A ON P.idpersona = A.idpersona AND P.tipo_doc = A.tipodoc AND P.vivipersona = V.idviv
	LEFT JOIN hog_tam_findrisc F ON P.tipo_doc = F.tipodoc AND P.idpersona = F.idpersona
	LEFT JOIN hog_tam_oms O ON P.tipo_doc = O.tipodoc AND P.idpersona = O.idpersona
	WHERE '1' ";
	$sql.=whe_rptindv();
	$sql.="ORDER BY P.fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"rptindv",$regxPag);
} 

function whe_rptindv() {
	$sql = "";
	if ($_POST['fidentificacion'])
		$sql .= " AND ophi_idpersona like '%".$_POST['fidentificacion']."%'";
	if ($_POST['fpersona']){
		if($_POST['fpersona'] == '2'){ //mayor de edad
			$sql .= " AND TIMESTAMPDIFF(YEAR, P.fecha_nacimiento, CURDATE()) <= 18 ";
		}else{ //menor de edad
			$sql .= " AND TIMESTAMPDIFF(YEAR, P.fecha_nacimiento, CURDATE()) > 18 ";
		}
	}
	return $sql;
}

function cmp_rptindv(){
	$rta="";
	$t=['tam_ophi'=>'','ophi_tipodoc'=>'','ophi_nombre'=>'','ophi_idpersona'=>'','ophi_fechanacimiento'=>'','ophi_puntaje'=>'','ophi_momento'=>'','ophi_edad'=>'','ophi_lugarnacimiento'=>'','ophi_condicionsalud'=>'','ophi_estadocivil'=>'','ophi_escolaridad'=>'',
	 'ophi_ocupacion'=>'','ophi_rutina'=>'','ophi_rol'=>'',	 'ophi_actividad'=>'','ophi_evento'=>'','ophi_comportamiento'=>'', 
	 'ophi_identidad1'=>'','ophi_identidad2'=>'','ophi_identidad3'=>'','ophi_identidad4'=>'',
	 'ophi_identidad5'=>'','ophi_identidad6'=>'','ophi_identidad7'=>'', 'ophi_identidad8'=>'','ophi_identidad9'=>'','ophi_identidad10'=>'',
	 'ophi_copetencia1'=>'','ophi_copetencia2'=>'','ophi_copetencia3'=>'','ophi_copetencia4'=>'','ophi_copetencia5'=>'',
	 'ophi_copetencia6'=>'', 'ophi_copetencia7'=>'','ophi_copetencia8'=>'','ophi_copetencia9'=>'',	'ophi_ambiente1'=>'',
	 'ophi_ambiente2'=>'','ophi_ambiente3'=>'','ophi_ambiente4'=>'','ophi_ambiente5'=>'','ophi_ambiente6'=>'',
	 'ophi_ambiente7'=>'','ophi_ambiente8'=>'','ophi_ambiente9'=>'','ophi_psicologico'=>'','ophi_social'=>'','ophi_manejo'=>'']; 
	$w='rptindv';
	$d=get_rptindv(); 
	if ($d=="") {$d=$t;}
	$u = ($d['tam_ophi']!='') ? false : true ;
	$o='datos';
    $key='srch';
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('idophi','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('ophi_idpersona','n','20',$d['ophi_idpersona'],$w.' '.$o.' '.$key,'N° Identificación','ophi_idpersona',null,'',false,$u,'','col-2');
	$c[]=new cmp('ophi_tipodoc','s','3',$d['ophi_tipodoc'],$w.' '.$o.' '.$key,'Tipo Identificación','ophi_tipodoc',null,'',false,$u,'','col-25',"getDatForm('srch','person','datos');");
	$c[]=new cmp('ophi_nombre','t','50',$d['ophi_nombre'],$w.' '.$o,'nombres','ophi_nombre',null,'',false,false,'','col-4');
	$c[]=new cmp('ophi_fechanacimiento','d','10',$d['ophi_fechanacimiento'],$w.' '.$o,'fecha nacimiento','ophi_fechanacimiento',null,'',false,false,'','col-15');
    $c[]=new cmp('ophi_edad','n','3',$d['ophi_edad'],$w.' '.$o,'edad','ophi_edad',null,'',true,false,'','col-1');
    $c[]=new cmp('ophi_estadocivil','s','3',$d['ophi_estadocivil'],$w.' '.$o,'Estado Civil','estado_civil',null,'',true,$u,'','col-3');
    // $c[]=new cmp('ophi_escolaridad','s','3',$d['ophi_escolaridad'],$w.' '.$o,'Nivel de escolaridad','niv_educativo',null,'',true,$u,'','col-3');
	$c[]=new cmp('ophi_condicionsalud','a','3',$d['ophi_condicionsalud'],$w.' '.$o,'Condicion de salud','ophi_condicionsalud',null,'',true,$u,'','col-5');
    // $c[]=new cmp('ophi_ocupacion','a','3',$d['ophi_ocupacion'],$w.' '.$o,'Ocupación Principal:(Diferente a la del cuidado)	','ophi_ocupacion',null,'',true,$u,'','col-5');

	$o='info';
	$c[]=new cmp($o,'e',null,'INFORMACIÓN',$w);

	$c[]=new cmp('ophi_rutina','a','3',$d['ophi_rutina'],$w.' '.$o,'RUTINAS DIARIAS','ophi_rutina',null,'',true,$u,'','col-10');
    $c[]=new cmp('ophi_rol','a','3',$d['ophi_rol'],$w.' '.$o,'ROLES OCUPACIONALES ','ophi_rol',null,'',true,$u,'','col-10');
    $c[]=new cmp('ophi_actividad','a','3',$d['ophi_actividad'],$w.' '.$o,'ELECCIÓN DE ACTIVIDAD/OCUPACIÓN ','ophi_actividad',null,'',true,$u,'','col-10');
    $c[]=new cmp('ophi_evento','a','3',$d['ophi_evento'],$w.' '.$o,'EVENTOS CRÍTICOS DE LA VIDA ','ophi_evento',null,'',true,$u,'','col-10');
    $c[]=new cmp('ophi_comportamiento','a','3',$d['ophi_comportamiento'],$w.' '.$o,'AMBIENTES DE COMPORTAMIENTO OCUPACIONAL ','ophi_comportamiento',null,'',true,$u,'','col-10');

	$o='identidad';
	$c[]=new cmp($o,'e',null,'ESCALA DE IDENTIDAD OCUPACIONAL ',$w);
	$c[]=new cmp('ophi_identidad1','s',3,$d['ophi_identidad1'],$w.' '.$o,'Tienes metas personales o proyectos ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_identidad2','s',3,$d['ophi_identidad2'],$w.' '.$o,'Identificar un estilo de vida ocupacional ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_identidad3','s',3,$d['ophi_identidad3'],$w.' '.$o,'Espera éxito acepta responsabilidades ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_identidad4','s',3,$d['ophi_identidad4'],$w.' '.$o,'Valora habilidades y limitaciones ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_identidad5','s',3,$d['ophi_identidad5'],$w.' '.$o,'Tiene compromisos y valores ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_identidad6','s',3,$d['ophi_identidad6'],$w.' '.$o,'Reconoce identidades y obligaciones ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_identidad7','s',3,$d['ophi_identidad7'],$w.' '.$o,'Tiene interés ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_identidad8','s',3,$d['ophi_identidad8'],$w.' '.$o,'Se sintió efectivo en el (pasado)','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_identidad9','s',3,$d['ophi_identidad9'],$w.' '.$o,'Encontró sentido, satisfacciones en su estilo de vida (pasado)','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_identidad10','s',3,$d['ophi_identidad10'],$w.' '.$o,'Hizo elecciones ocupacionales ','salud_mental',null,null,true,$u,'','col-10');

	$o='copetencias';
	$c[]=new cmp($o,'e',null,'ESCALA DE COMPETENCIA OCUPACIONAL ',$w);
	$c[]=new cmp('ophi_copetencia1','s',3,$d['ophi_copetencia1'],$w.' '.$o,'Mantiene un estilo de vida satisfactorio','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_copetencia2','s',3,$d['ophi_copetencia2'],$w.' '.$o,'Cumple con las expectativas de sus roles ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_copetencia3','s',3,$d['ophi_copetencia3'],$w.' '.$o,'Trabaja hacia metas ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_copetencia4','s',3,$d['ophi_copetencia4'],$w.' '.$o,'Cubre los estándares de desenvolvimiento personal ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_copetencia5','s',3,$d['ophi_copetencia5'],$w.' '.$o,'Organiza su tiempo para cumplir responsabilidades ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_copetencia6','s',3,$d['ophi_copetencia6'],$w.' '.$o,'Participa en intereses ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_copetencia7','s',3,$d['ophi_copetencia7'],$w.' '.$o,'Cumplió con sus roles (pasado)','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_copetencia8','s',3,$d['ophi_copetencia8'],$w.' '.$o,'Mantuvo hábitos (pasado)','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_copetencia9','s',3,$d['ophi_copetencia9'],$w.' '.$o,'Logro satisfacción (pasado)','salud_mental',null,null,true,$u,'','col-10');

	$o='ambiente';
	$c[]=new cmp($o,'e',null,'ESCALA DE AMBIENTES DE COMPORTAMIENTOS OCUPACIONALES ',$w);
	$c[]=new cmp('ophi_ambiente1','s',3,$d['ophi_ambiente1'],$w.' '.$o,'Formas ocupacionales de vida en el hogar ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_ambiente2','s',3,$d['ophi_ambiente2'],$w.' '.$o,'Formas ocupacionales del rol principal productivo ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_ambiente3','s',3,$d['ophi_ambiente3'],$w.' '.$o,'Formas ocupacionales de diversión ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_ambiente4','s',3,$d['ophi_ambiente4'],$w.' '.$o,'Grupo social en la vida hogareña ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_ambiente5','s',3,$d['ophi_ambiente5'],$w.' '.$o,'Grupo social del principal rol productivo ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_ambiente6','s',3,$d['ophi_ambiente6'],$w.' '.$o,'Grupo social de diversión ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_ambiente7','s',3,$d['ophi_ambiente7'],$w.' '.$o,'Espacios físicos, objetos y recursos en la vida hogareña ','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_ambiente8','s',3,$d['ophi_ambiente8'],$w.' '.$o,'Espacios físicos, objetos y recursos en el rol productivo','salud_mental',null,null,true,$u,'','col-10');
	$c[]=new cmp('ophi_ambiente9','s',3,$d['ophi_ambiente9'],$w.' '.$o,'Espacios físicos, objetos y recursos en los ambientes de diversión','salud_mental',null,null,true,$u,'','col-10');
	$o='concepto';
	$c[]=new cmp($o,'e',null,'CONCEPTO OCUPACIONAL ',$w);
    $c[]=new cmp('ophi_psicologico','a','3',$d['ophi_psicologico'],$w.' '.$o,'COMPONENTE PSICOLOGICO ','ophi_psicologico',null,'',true,$u,'','col-10');
    $c[]=new cmp('ophi_social','a','3',$d['ophi_social'],$w.' '.$o,'COMPONENTE SOCIAL ','ophi_social',null,'',true,$u,'','col-10');
    $c[]=new cmp('ophi_manejo','a','3',$d['ophi_manejo'],$w.' '.$o,'COMPONENTE MENEJO DE SI MISMO ','ophi_manejo',null,'',true,$u,'','col-10');

	$o='totales';
	$c[]=new cmp($o,'e',null,'Resultado ',$w);
	$c[]=new cmp('ophi_momento','t',20,$d['ophi_momento'],$w.' '.$o,'Momento','ophi_momento',null,'',false,false,'','col-6');
	$c[]=new cmp('ophi_puntaje','t',3,$d['ophi_puntaje'],$w.' '.$o,'Puntaje','ophi_puntaje',null,'',false,false,'','col-4');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	
	return $rta;
   }

   function get_rptindv(){
	// print_r($_POST);
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		 
		$sql="SELECT `tam_ophi`,`ophi_idpersona`,`ophi_tipodoc`,
		FN_CATALOGODESC(116,ophi_momento) ophi_momento,`ophi_condicionsalud`,`ophi_rutina`,`ophi_rol`, 
		`ophi_actividad`,`ophi_evento`,`ophi_comportamiento`,
		`ophi_identidad1`,`ophi_identidad2`,`ophi_identidad3`,
		`ophi_identidad4`,`ophi_identidad5`,`ophi_identidad6`,
		`ophi_identidad7`,`ophi_identidad8`,`ophi_identidad9`,
		`ophi_identidad10`,`ophi_copetencia1`,`ophi_copetencia2`,
		`ophi_copetencia3`,`ophi_copetencia4`,`ophi_copetencia5`,
		`ophi_copetencia6`,`ophi_copetencia7`,`ophi_copetencia8`,
		`ophi_copetencia9`,`ophi_ambiente1`,`ophi_ambiente2`,
		`ophi_ambiente3`,`ophi_ambiente4`,
		`ophi_ambiente5`,`ophi_ambiente6`,`ophi_ambiente7`,`ophi_ambiente8`,`ophi_ambiente9`,`ophi_psicologico`,`ophi_social`,
		`ophi_manejo`,`ophi_puntaje`,O.estado,P.idpersona,P.tipo_doc,concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) ophi_nombre,P.fecha_nacimiento ophi_fechanacimiento,YEAR(CURDATE())-YEAR(P.fecha_nacimiento) ophi_edad,estado_civil ophi_estadocivil
		FROM `hog_tam_ophi` O
		LEFT JOIN personas P ON O.ophi_idpersona = P.idpersona and O.ophi_tipodoc=P.tipo_doc
		LEFT JOIN personas_datocomp C ON O.ophi_idpersona = C.dc_documento AND O.ophi_tipodoc=C.dc_documento
		WHERE ophi_idpersona ='{$id[0]}' AND ophi_tipodoc='{$id[1]}' AND ophi_momento = '{$id[2]}'  ";
		// echo $sql;
		$info=datos_mysql($sql);
				return $info['responseResult'][0];
		}
	} 


function get_person(){
	// print_r($_POST);
	$id=divide($_POST['id']);
$sql="SELECT idpersona,tipo_doc,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,fecha_nacimiento,YEAR(CURDATE())-YEAR(fecha_nacimiento) Edad,estado_civil
FROM personas 
left JOIN personas_datocomp ON idpersona=dc_documento and tipo_doc=dc_tipo_doc
	WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."')";
	// echo $sql;
	$info=datos_mysql($sql);
	if (!$info['responseResult']) {
		return json_encode (new stdClass);
	}
return json_encode($info['responseResult'][0]);
}

function focus_rptindv(){
	return 'rptindv';
   }
   
function men_rptindv(){
	$rta=cap_menus('rptindv','pro');
	return $rta;
   }

   function cap_menus($a,$b='cap',$con='con') {
	$rta = ""; 
	$acc=rol($a);
	if ($a=='rptindv') {  
		$rta .= "<li class='icono $a  grabar' title='Grabar' Onclick=\"grabar('$a',this);\" ></li>";
	}
	return $rta;
  }
   
function gra_rptindv(){
	$id=$_POST['idophi'];
	//print_r($_POST);
	if($id != "0"){
		return "No es posible actualizar el tamizaje";
	}else{

	$infodata_ophi=datos_mysql("SELECT ophi_momento,ophi_idpersona FROM hog_tam_ophi
		 WHERE ophi_idpersona = {$_POST['ophi_idpersona']} AND ophi_momento = 2 ");
	if (isset($infodata_ophi['responseResult'][0])){
		return "Ya se realizo los dos momentos";
	}else{
		$infodata2_ophi=datos_mysql("SELECT ophi_momento,ophi_idpersona FROM hog_tam_ophi
		 WHERE ophi_idpersona = {$_POST['ophi_idpersona']} AND ophi_momento = 1 ");
		if (isset($infodata2_ophi['responseResult'][0])){
			$idmomento = 2;
		}else{
			$idmomento = 1;
		}
	}

	/* nivel_educativo=TRIM(UPPER('{$_POST['ophi_escolaridad']}')),
	ocupacion=TRIM(UPPER('{$_POST['ophi_ocupacion']}')), */
	$sql3="UPDATE personas_datocomp SET 
	estadocivil=TRIM(UPPER('{$_POST['ophi_estadocivil']}')),
	`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
	`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
	WHERE idpersona ={$_POST['ophi_idpersona']} AND tipo_doc={$_POST['ophi_tipodoc']}";
	$rta3=dato_mysql($sql3);

	$suma_iden = (
		$_POST['ophi_identidad1']+
		$_POST['ophi_identidad2']+
		$_POST['ophi_identidad3']+
		$_POST['ophi_identidad4']+
		$_POST['ophi_identidad5']+
		$_POST['ophi_identidad6']+
		$_POST['ophi_identidad7']+
		$_POST['ophi_identidad8']+
		$_POST['ophi_identidad9']+
		$_POST['ophi_identidad10']
	);

	$suma_comp = (
		$_POST['ophi_copetencia1']+
		$_POST['ophi_copetencia2']+
		$_POST['ophi_copetencia3']+
		$_POST['ophi_copetencia4']+
		$_POST['ophi_copetencia5']+
		$_POST['ophi_copetencia6']+
		$_POST['ophi_copetencia7']+
		$_POST['ophi_copetencia8']+
		$_POST['ophi_copetencia9']
	);

	$suma_ambi = (
		$_POST['ophi_ambiente1']+
		$_POST['ophi_ambiente2']+
		$_POST['ophi_ambiente3']+
		$_POST['ophi_ambiente4']+
		$_POST['ophi_ambiente5']+
		$_POST['ophi_ambiente6']+
		$_POST['ophi_ambiente7']+
		$_POST['ophi_ambiente8']+
		$_POST['ophi_ambiente9']
	);


	$suma_ophi = ($suma_iden+$suma_comp+$suma_ambi);

		$sql="INSERT INTO hog_tam_ophi VALUES (null,
		TRIM(UPPER('{$_POST['ophi_tipodoc']}')),
		TRIM(UPPER('{$_POST['ophi_idpersona']}')),
		{$idmomento},
		TRIM(UPPER('{$_POST['ophi_condicionsalud']}')),
		TRIM(UPPER('{$_POST['ophi_rutina']}')),
		TRIM(UPPER('{$_POST['ophi_rol']}')),
		TRIM(UPPER('{$_POST['ophi_actividad']}')),
		TRIM(UPPER('{$_POST['ophi_evento']}')),
		TRIM(UPPER('{$_POST['ophi_comportamiento']}')),
		TRIM(UPPER('{$_POST['ophi_identidad1']}')),
		TRIM(UPPER('{$_POST['ophi_identidad2']}')),
		TRIM(UPPER('{$_POST['ophi_identidad3']}')),
		TRIM(UPPER('{$_POST['ophi_identidad4']}')),
		TRIM(UPPER('{$_POST['ophi_identidad5']}')),
		TRIM(UPPER('{$_POST['ophi_identidad6']}')),
		TRIM(UPPER('{$_POST['ophi_identidad7']}')),
		TRIM(UPPER('{$_POST['ophi_identidad8']}')),
		TRIM(UPPER('{$_POST['ophi_identidad9']}')),
		TRIM(UPPER('{$_POST['ophi_identidad10']}')),
		TRIM(UPPER('{$_POST['ophi_copetencia1']}')),
		TRIM(UPPER('{$_POST['ophi_copetencia2']}')),
		TRIM(UPPER('{$_POST['ophi_copetencia3']}')),
		TRIM(UPPER('{$_POST['ophi_copetencia4']}')),
		TRIM(UPPER('{$_POST['ophi_copetencia5']}')),
		TRIM(UPPER('{$_POST['ophi_copetencia6']}')),
		TRIM(UPPER('{$_POST['ophi_copetencia7']}')),
		TRIM(UPPER('{$_POST['ophi_copetencia8']}')),
		TRIM(UPPER('{$_POST['ophi_copetencia9']}')),
		TRIM(UPPER('{$_POST['ophi_ambiente1']}')),
		TRIM(UPPER('{$_POST['ophi_ambiente2']}')),
		TRIM(UPPER('{$_POST['ophi_ambiente3']}')),
		TRIM(UPPER('{$_POST['ophi_ambiente4']}')),
		TRIM(UPPER('{$_POST['ophi_ambiente5']}')),
		TRIM(UPPER('{$_POST['ophi_ambiente6']}')),
		TRIM(UPPER('{$_POST['ophi_ambiente7']}')),
		TRIM(UPPER('{$_POST['ophi_ambiente8']}')),
		TRIM(UPPER('{$_POST['ophi_ambiente9']}')),
		TRIM(UPPER('{$_POST['ophi_psicologico']}')),
		TRIM(UPPER('{$_POST['ophi_social']}')),
		TRIM(UPPER('{$_POST['ophi_manejo']}')),
		'{$suma_ophi}',
		TRIM(UPPER('{$_SESSION['us_sds']}')),
		DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
		// echo $sql;
	}
	  $rta=dato_mysql($sql);
	  return $rta; 
	}


	function opc_ophi_tipodoc($id=''){
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

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
	   // $rta=iconv('UTF-8','ISO-8859-1',$rta);
	   // var_dump($a);
	   // var_dump($rta);
		   if ($a=='rptindv' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('rptindv','pro',event,'','lib.php',7,'rptindv');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
	