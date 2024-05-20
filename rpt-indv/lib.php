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
		$sql .= " AND P.idpersona like '%".$_POST['fidentificacion']."%'";
	if ($_POST['floc'])
			$sql .= " AND G.localidad='".$_POST['floc']."'";
	if ($_POST['fter'])
			$sql .= " AND G.territorio='".$_POST['fter']."'";
		return $sql;
	}
	

function cmp_rptindv(){

	$t=['Usuario'=>'','Tipo_Documento'=>'','N°_Documento'=>'','Sexo'=>'','Genero'=>'','Nacionalidad'=>'','Curso_de_Vida'=>'','Localidad'=>'',
	'Direccion'=>'','Telefono_Contacto'=>'','Rango_Edad'=>'','imc'=>'','Puntaje_Oms'=>'','Puntaje_Findrisc'=>'','imc'=>'30',
	'ophi_actividad'=>'','ophi_evento'=>'','ophi_comportamiento'=>'','ophi_identidad1'=>'','ophi_identidad2'=>'','ophi_identidad3'=>'','ophi_identidad4'=>'',
	 'ophi_identidad5'=>'','ophi_identidad6'=>'','ophi_identidad7'=>'', 'ophi_identidad8'=>'','ophi_identidad9'=>'','ophi_identidad10'=>'',
	 'ophi_copetencia1'=>'','ophi_copetencia2'=>'','ophi_copetencia3'=>'','ophi_copetencia4'=>'','ophi_copetencia5'=>'','ophi_copetencia6'=>'',
	  'ophi_copetencia7'=>'','ophi_copetencia8'=>'','ophi_copetencia9'=>'',	'ophi_ambiente1'=>'','ophi_ambiente2'=>'','ophi_ambiente3'=>'',
	  'ophi_ambiente4'=>'','ophi_ambiente5'=>'','ophi_ambiente6'=>'','ophi_ambiente7'=>'','ophi_ambiente8'=>'','ophi_ambiente9'=>'','ophi_psicologico'=>'',
	  'ophi_social'=>'','ophi_manejo'=>'']; 
	 $d=get_rptindv(); 
	//  $d="";
	 if ($d=="") {$d=$t;}
	// var_dump($d);
	$rta='
	<div class="title-risk">Identificación</div>
    <div class="user-info section medium-risk">
        <div class="user-details">
            <div class="user-name">'.$d['Usuario'].'</div>
            <div><b>Documento:</b> '.$d["Tipo_Documento"].' '.$d["N°_Documento"].'</div>
            <div><b>Sexo:</b> '.$d["Sexo"].'</div>
            <div><b>Género:</b> '.$d["Genero"].'</div>
            <div><b>Nacionalidad:</b> '.$d["Nacionalidad"].'</div>
        </div>
        <div class="risk-info">
            <div class="extra-info"><b>Curso de Vida:</b> '.$d["Rango_Edad"].'</div>
            <div class="risk-level medium-risk"><span class="point medium-risk"></span> Riesgo Medio</div>
        </div>
    </div>

    <div class="title-risk">Ubicación</div>
    <div class="user-info section">
        <div class="user-details">
            <div><b>Localidad:</b> '.$d["Localidad"].'</div>
            <div><b>Dirección:</b> '.$d["Direccion"].'</div>
            <div><b>Teléfono:</b> '.$d["Telefono_Contacto"].'</div>
        </div>
    </div>

     <div class="title-risk">Caracterización</div>
    <div class="user-info section">
        <div class="user-detail">
            <div><b>OMS</b> '.$d["Puntaje_Oms"].'</div>
            <div><br></div>

            <div class="btn-group">

              <div class="btn-contain">
                <span class="custom-btn low-risk">Delgadez</span>
                <div class="btn-value low-risk"> 30</div>
              </div>

              <div class="btn-contain">
                <span class="custom-btn normal-risk">Normal</span>
                <div class="btn-value normal-risk"> 20</div>
              </div>

              <div class="btn-contain">
                <span class="custom-btn medium-risk">Sobrepeso</span>
                <div class="btn-value medium-risk"> 20</div>
              </div>

              <div class="btn-contain">
                <span class="custom-btn high-risk">Obesidad</span>
                <div class="btn-value high-risk"> 20</div>
              </div>
            </div>
         </div>
         <div class="user-details">
            <div><b>SRQ:</b> 20</div>
            <div><b>Findrisc:</b> '.$d["Puntaje_Findrisc"].'</div>
         </div>
        <div class="user-details">
            <div><b>RQC:</b> 30</div>
            <div><b>COPE 28:</b> 80</div>
        </div>
        <div class="user-details">
            <div><b>EPOC:</b> 80</div>
        </div>
    </div>
    <div class="title-risk">Atención Individual</div>
    <div class="user-info section">
        <div class="user-details">
            <div><b>Zarith:</b> 30</div>
            <div><b>Hamilton:</b> 30</div>
        </div>
        <div class="user-details">
            <div><b>Zung:</b> 30</div>
            <div><b>Ophi II:</b> 30</div>
        </div>
    </div>
</div>
</div>';


/* 	$t=['tam_ophi'=>'','ophi_tipodoc'=>'','ophi_nombre'=>'','ophi_idpersona'=>'','ophi_fechanacimiento'=>'','ophi_puntaje'=>'','ophi_momento'=>'','ophi_edad'=>'','ophi_lugarnacimiento'=>'','ophi_condicionsalud'=>'','ophi_estadocivil'=>'','ophi_escolaridad'=>'',
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
	$c[]=new cmp('ophi_condicionsalud','a','3',$d['ophi_condicionsalud'],$w.' '.$o,'Condicion de salud','ophi_condicionsalud',null,'',true,$u,'','col-5');
 */	return $rta;
   }

   function get_rptindv(){
	// print_r($_POST);
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		 
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
		WHERE P.idpersona ='{$id[1]}' AND P.tipo_doc='{$id[0]}'";
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
	