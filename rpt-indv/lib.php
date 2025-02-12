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
	$info=datos_mysql("SELECT COUNT(*) total FROM person P 
	LEFT JOIN hog_fam V ON P.vivipersona = V.id_fam  
	LEFT JOIN hog_geo G ON V.idpre = G.idgeo 
	LEFT JOIN hog_tam_apgar A ON P.idpeople = A.idpeople AND P.vivipersona = V.id_fam 
	LEFT JOIN hog_tam_findrisc F ON  P.idpeople = F.idpeople 
	LEFT JOIN hog_tam_oms O ON  P.idpeople = O.idpeople 
	LEFT JOIN hog_tam_cope C ON P.idpeople = C.idpeople 
	LEFT JOIN hog_tam_epoc E ON  P.idpeople = E.idpeople 
	LEFT JOIN hog_tam_zarit Z ON  P.idpeople = Z.idpeople 
	LEFT JOIN hog_tam_zung ZU ON  P.idpeople = ZU.idpeople	
	LEFT JOIN hog_tam_hamilton H ON  P.idpeople = H.idpeople 
	WHERE '1' ".whe_rptindv());
	$total=$info['responseResult'][0]['total'];
	$regxPag=10;
	$pag=(isset($_POST['pag-rptindv']))? ($_POST['pag-rptindv']-1)* $regxPag:0;

	$sql="SELECT  DISTINCT(concat_ws('_',P.tipo_doc,P.idpeople )) as ACCIONES,
		P.tipo_doc AS Tipo_Documento, P.idpeople AS N°_Documento, CONCAT(P.nombre1, ' ', P.nombre2, ' ', P.apellido1, ' ', P.apellido2) AS Usuario,
	FN_CATALOGODESC(2,G.localidad) AS Localidad, G.direccion AS Direccion, 
	CONCAT(V.complemento1, ' ', V.nuc1, ' ', V.complemento2, ' ', V.nuc2, ' ', V.complemento3, ' ', V.nuc3) AS Complementos, V.telefono1 AS Telefono,
	P.vivipersona AS Cod_Familia
	FROM person P 
	LEFT JOIN hog_fam V ON P.vivipersona = V.id_fam
	LEFT JOIN hog_geo G ON V.idpre = G.idgeo
	LEFT JOIN hog_tam_apgar A ON P.idpeople = A.idpeople  AND P.vivipersona = V.id_fam
	LEFT JOIN hog_tam_findrisc F ON  P.idpeople = F.idpeople
	LEFT JOIN hog_tam_oms O ON  P.idpeople = O.idpeople
	LEFT JOIN hog_tam_cope C ON  P.idpeople = C.idpeople
	LEFT JOIN hog_tam_epoc E ON  P.idpeople = E.idpeople
	LEFT JOIN hog_tam_zarit Z ON  P.idpeople = Z.idpeople
	LEFT JOIN hog_tam_zung ZU ON  P.idpeople = ZU.idpeople
	LEFT JOIN hog_tam_hamilton H ON  P.idpeople = H.idpeople
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
		$sql .= " AND P.idpeople like '%".$_POST['fidentificacion']."%'";
	if ($_POST['floc'])
			$sql .= " AND G.localidad='".$_POST['floc']."'";
	if ($_POST['fter'])
			$sql .= " AND G.territorio='".$_POST['fter']."'";
		return $sql;
	}
	

function cmp_rptindv(){

	$t=['Usuario'=>'','Tipo_Documento'=>'','N°_Documento'=>'','Sexo'=>'','Genero'=>'','Nacionalidad'=>'','Curso_de_Vida'=>'','Localidad'=>'',
	'Direccion'=>'','Telefono_Contacto'=>'','Rango_Edad'=>'','IMC'=>'','Puntaje_Oms'=>'','Puntaje_Findrisc'=>'','Fecha_Nacimiento'=>'','Edad'=>'']; 
	 $d=get_rptindv(); 
	//  $d="";
	 if ($d=="") {$d=$t;}
	// var_dump($d);

	
	// Riesgo Baja-Riesgo Moderada-Riesgo Alto-Riesgo Muy Alto-Riesgo Extremadamente Alto

	$imc = $d['IMC'];
    $delgadez = $normal = $sobrepeso = $obesidad = '';
    if ($imc < 18.5) {
        $delgadez = $imc;
		$Rimc=0;
    } elseif ($imc >= 18.5 && $imc < 24.9) {
        $normal = $imc;
		$Rimc=0;
    } elseif ($imc >= 25 && $imc < 29.9) {
        $sobrepeso = $imc;
		$Rimc=2;
    } elseif ($imc >= 30) {
        $obesidad = $imc;
		$Rimc=3;
    }

	$ophi=intval($d["Puntaje_Ophi"])/ 112*100;
	// var_dump($ophi);
	if($ophi<25){
		$Pophi='Riesgo Alto';
	}elseif($ophi>=26 && $ophi<51){
		$Pophi='Riesgo Alto';
	}elseif($ophi>=51 && $ophi<76){
		$Pophi='Riesgo Medio';
	}elseif($ophi>=76 && $ophi<101){
		$Pophi='Riesgo Bajo';
	}

	$srq = $rqc = $Psrq = $Prqc = '';


	if(intval($d["anos"])<16){
		$rqc=intval($d["Puntaje_Srq"]);
		if($rqc>0){
			$Prqc='Riesgo Alto';
		}else{
			$Prqc='Riesgo Bajo';
		}
	}else{
		$srq=intval($d["Puntaje_Srq"]);
		if($srq>10){
			$Psrq='Riesgo Alto';
		}else{
			$Psrq='Riesgo Bajo';
		}
	}
	


	$Toms = ($d["Riesgo_Oms"]=='') ? 0 : 1 ;
	$Timc = ($imc=='') ? 0 : 1 ;
	$Tsrq = ($srq=='') ? 0 : 1 ;
	$Tfind = ($d["Riesgo_Findrisc"]=='') ? 0 : 1 ;
	$Trqc = ($rqc=='') ? 0 : 1 ;
	$Tcope = ($d["Riesgo_Cope"]=='') ? 0 : 1 ;
	$Tepoc = ($d["Riesgo_Epoc"]=='') ? 0 : 1 ;
	$Tzari = ($d["Riesgo_Zarit"]=='') ? 0 : 1 ;
	$Thami = ($d["Riesgo_Hamilton"]=='') ? 0 : 1 ;
	$Tzung = ($d["Riesgo_Zung"]=='') ? 0 : 1 ;
	$Tophi = ($Pophi=='') ? 0 : 1 ;
	
	


	$Roms = (strpos($d["Riesgo_Oms"], 'alto')) ? 3 : ((strpos($d["Riesgo_Oms"], 'medio')) ? 2 : 0);
	$Repoc = strpos($d["Riesgo_Epoc"],'alto') ? 3 :0;
	$Rfind = (strpos($d["Riesgo_Findrisc"],'alto')) ? 3 : ((strpos($d["Riesgo_Findrisc"],'moderado')) ? 2 :0);
	$Rcope = (strpos($d["Riesgo_Cope"],'alto')) ? 3 : ((strpos($d["Riesgo_Cope"],'medio')) ? 2 :0);
	$Rzari = (strpos($d["Riesgo_Zarit"],'intensa')) ? 3 : ((strpos($d["Riesgo_Zarit"],'leve')) ? 2 :0);
	$Rzung = (strpos($d["Riesgo_Zung"],'grave')) ? 3 : ((strpos($d["Riesgo_Zung"],'moderada')) ? 2 :0);
	$Rhami = (strpos($d["Riesgo_Hamilton"],'severa')) ? 3 : ((strpos($d["Riesgo_Hamilton"],'moderada')) ? 2 :0);
	$Rophi = (strpos($Pophi,'Alto')) ? 3 : ((strpos($Pophi,'Medio')) ? 2 :0);
	$Rsrq1 = strpos($Psrq,'Alto') ? 3 : 0;
	$Rrqc1 = strpos($Prqc,'Alto') ? 3 : 0;

	$total=$Toms+$Timc+$Tepoc+$Tfind+$Tcope+$Tzari+$Tzung+$Thami+$Tophi+$Tsrq+$Trqc;
	$sum=$Rimc+$Roms+$Repoc+$Rfind+$Rcope+$Rzari+$Rzung+$Rhami+$Rophi+$Rsrq1+$Rrqc1;
	
	// var_dump('Total/Suma='.$total.'/'.$sum.'='.$sum/$total);

	$avg=$sum/$total;

if($avg<1.5){
 $Rtotal='Riesgo Bajo';
 $Riesgo='normal';
}elseif($avg>=1.5 && $avg<2.5){
	$Rtotal='Riesgo Medio';
	$Riesgo='medium';
}else{
	$Rtotal='Riesgo Alto';
	$Riesgo='high';
}


	$rta='
	<div class="container">
    <div class="tab-panel">
      <ul class="tab-nav">
        <li class="tabs activ" data-tab="1"><i class="fa-solid fa-book-medical"></i>Individual</li>
        <li class="tabs " data-tab="2"><i class="fa-solid fa-hand-holding-medical"></i>Familiar</li>
        <li class="tabs " data-tab="3"><i class="fa-solid fa-viruses"></i>Analisis</li>
      </ul>
      <div class="tab-content activ" data-content="1">
        <p>
		
		<div class="title-risk">Identificación</div>
		<div class="user-info section '.$Riesgo.'-risk">
			<div class="user-details">
				<div class="user-name">'.$d['Usuario'].'</div>
				<div><b>Documento :</b> '.$d["Tipo_Documento"].' '.$d["N°_Documento"].'</div>
				<div><b>Sexo :</b> '.$d["Sexo"].'</div>
				<div><b>Género :</b> '.$d["Genero"].'</div>
				<div><b>Nacionalidad :</b> '.$d["Nacionalidad"].'</div>
				<div><b>Fecha de Nacimiento :</b> '.$d["Fecha_Nacimiento"].'</div>
				<div><b>Edad :</b> '.$d["Edad"].'</div>
			</div>
			<div class="risk-info">
				<div class="extra-info"><b>Curso de Vida:</b> '.$d["Rango_Edad"].' ('.$d["Edad"].') </div>
				<div class="risk-level '.$Riesgo.'-risk"><span class="point '.$Riesgo.'-risk"></span> '.$Rtotal.'</div>
			</div>
		</div>
	
		<div class="title-risk">Ubicación</div>
		<div class="user-info section">
			<div class="user-details">
				<div><b>Localidad :</b> '.$d["Localidad"].'</div>
				<div><b>Upz :</b> '.$d["Upz"].'</div>
				<div><b>Dirección :</b> '.$d["Direccion"].'</div>
				<div><b>Teléfono :</b> '.$d["Telefono_Contacto"].'</div>
			</div>
		</div>
	
		<div class="title-risk">Atención Individual</div>
			<div class="user-info section">
				<div class="user-detail">
					<div><b>OMS</b> '.$d["Puntaje_Oms"].' '.$d["Riesgo_Oms"].'</div>
					<div><br></div>
	
					<div class="btn-group">
						<div class="btn-contain">
							<span class="custom-btn low-risk">Delgadez</span>
							<div class="btn-value low-risk"> '.$delgadez.'</div>
				  		</div>
						<div class="btn-contain">
							<span class="custom-btn normal-risk">Normal</span>
							<div class="btn-value normal-risk"> '.$normal.'</div>
				  		</div>
				  		<div class="btn-contain">
							<span class="custom-btn medium-risk">Sobrepeso</span>
							<div class="btn-value medium-risk"> '.$sobrepeso.'</div>
				  		</div>
				  		<div class="btn-contain">
							<span class="custom-btn high-risk">Obesidad</span>
							<div class="btn-value high-risk">  '.$obesidad.'</div>
				  		</div>
					</div>
				</div>
			</div>
			
			<div class="user-info section">
				<div class="user-details">
					<div><b class="tooltips">SRQ :<span class="tooltiptext">Self Reporting Questionnaire. Identifica pacientes con alta probabilidad de estar sufriendo una enfermedad mental.(Población mayor a 16 años)</span></b>'.$srq.' '.$Psrq.'</div>
					<div><b class="tooltips">Findrisc :<span class="tooltiptext">Finnish Diabetes Risk Score. Evalua el riesgo de una persona de desarrollar diabetes mellitus tipo 2 en los próximos 10 años.(Población mayor a 17 años)</span></b>'.$d["Puntaje_Findrisc"].' '.$d["Riesgo_Findrisc"].'</div>
			 	</div>
				<div class="user-details">
					<div><b class="tooltips">RQC : <span class="tooltiptext">Reporting Questionnaire for Children. Identifica problemas de salud mental en población infantil no psiquiátrica.(Población entre 5 y 15 años)</span></b>'.$rqc.' '.$Prqc.'</div>
					<div><b class="tooltips">COPE 28 : <span class="tooltiptext">Cuestionario Multidimensional de Afrontamiento (Evalua las diferentes formas de respuesta ante el estrés.)</span></b>'.$d["Puntaje_Cope"].' '.$d["Riesgo_Cope"].'</div>
				</div>
				<div class="user-details">
					<div><b class="tooltips">EPOC : <span class="tooltiptext">Enfermedad Pulmonar Obstructiva Crónica (Población de 40 años o más)</span></b> '.$d["Puntaje_Epoc"].' '.$d["Riesgo_Epoc"].'</div>
				</div>
			</div>
		<div class="user-info section">
			<div class="user-details">
				<div><b class="tooltips">Zarith :<span class="tooltiptext">El Zarit Burden Inventory, Cuantifica el grado de sobrecarga que padecen los cuidadores de las personas dependientes</span></b> '.$d["Puntaje_Zarit"].' '.$d["Riesgo_Zarit"].'</div>
				<div><b class="tooltips">Hamilton :<span class="tooltiptext">Hamilton depresión rating scale (HDRS)) es una escala, heteroaplicada, diseñada para ser utilizada en pacientes diagnosticados previamente de depresión.</span></b> '.$d["Puntaje_Hamilton"].' '.$d["Riesgo_Hamilton"].'</div>
			</div>
			<div class="user-details">
				<div><b class="tooltips">Zung :<span class="tooltiptext">Instrumento que consta de 20 componentes. Explora síntomas relacionados con la presencia de un episodio depresivo mayor.</span></b> '.$d["Puntaje_Zung"].' '.$d["Riesgo_Zung"].'</div>
				<div><b class="tooltips">Ophi II :<span class="tooltiptext">Entrevista Histórica del Desempeño Ocupacional II, Incluye la exploración de la historia ocupacional, la identidad ocupacional, la competencia ocupacional y el impacto del contexto. </span></b> '.$d["Puntaje_Ophi"].' '.$Pophi.'</div>
			</div>
		</div>
	</div>
	</div>

		</p>
      </div>
      <div class="tab-content" data-content="2">

	  	<div id="graficoContainer">
        
    	</div>

        <p>Un buen hábito de salud es evitar el consumo excesivo de azúcares y grasas, ya que esto puede aumentar el
          riesgo de enfermedades crónicas.</p>
      </div>
      <div class="tab-content" data-content="3">
        <p>Un buen hábito de salud es dormir lo suficiente, entre 7 y 8 horas diarias. Esto ayuda a mejorar la
          concentración, la memoria y el sistema inmunológico.</p>
      </div>
    </div>
  </div>';
return $rta;
   }

   function get_rptindv(){
	// print_r($_POST);
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		$sql="SELECT  concat_ws('_',P.tipo_doc,P.idpersona ) as ACCIONES,
		FN_CATALOGODESC(2,G.localidad) AS Localidad, 
		CONCAT(G.upz,' - ', FN_CATALOGODESC(7,G.upz)) AS Upz,
		G.direccion AS Direccion, CONCAT(V.complemento1, ' ', V.nuc1, ' ', V.complemento2, ' ', V.nuc2, ' ', V.complemento3, ' ', V.nuc3) AS Complementos, V.telefono1 AS Telefono_Contacto,
		P.vivipersona AS Cod_Familia,
		P.tipo_doc AS Tipo_Documento, P.idpersona AS N°_Documento, CONCAT(P.nombre1, ' ', P.nombre2, ' ', P.apellido1, ' ', P.apellido2) AS Usuario, FN_CATALOGODESC(21,P.sexo) AS Sexo, FN_CATALOGODESC(19,P.genero) AS Genero, FN_CATALOGODESC(30,P.nacionalidad) AS Nacionalidad,
		P.fecha_nacimiento AS Fecha_Nacimiento,
    	CONCAT(
        	TIMESTAMPDIFF(YEAR, P.fecha_nacimiento, CURDATE()), ' Años, ', 
        	TIMESTAMPDIFF(MONTH, P.fecha_nacimiento, CURDATE()) % 12, ' Meses y ', 
        	DATEDIFF(CURDATE(), DATE_ADD(DATE_ADD(P.fecha_nacimiento, 
            INTERVAL TIMESTAMPDIFF(YEAR, P.fecha_nacimiento, CURDATE()) YEAR), 
            INTERVAL TIMESTAMPDIFF(MONTH, P.fecha_nacimiento, CURDATE()) % 12 MONTH)), ' Días'
    	) AS Edad,
		TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS Curso_de_Vida,
    	CASE
    	    WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 0 AND 5 THEN 'PRIMERA INFANCIA'
    	    WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 6 AND 11 THEN 'INFANCIA'
    	    WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 12 AND 17 THEN 'ADOLESCENCIA'
    	    WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 18 AND 28 THEN 'JUVENTUD'
    	    WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 29 AND 59 THEN 'ADULTEZ'
    	    WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) >= 60 THEN 'VEJEZ'
    	    ELSE 'Edad Desconocida'
    	END AS Rango_Edad,
		TIMESTAMPDIFF(YEAR, P.fecha_nacimiento, CURDATE()) anos,
		D.imc AS IMC,
		A.puntaje AS Puntaje_Apgar, LOWER(A.descripcion) AS Riesgo_Apgar,
		F.puntaje AS Puntaje_Findrisc, LOWER(F.descripcion) AS Riesgo_Findrisc,
		O.puntaje AS Puntaje_Oms, LOWER(O.descripcion) AS Riesgo_Oms,
		C.puntajea AS Puntaje_Cope, LOWER(C.cope_descripciona) AS Riesgo_Cope,
		E.puntaje AS Puntaje_Epoc, LOWER(E.descripcion) AS Riesgo_Epoc,
		Z.puntaje AS Puntaje_Zarit, LOWER(Z.analisis) AS Riesgo_Zarit,
		ZU.zung_puntaje AS Puntaje_Zung, LOWER(ZU.zung_analisis) AS Riesgo_Zung,
		H.hamilton_total AS Puntaje_Hamilton, LOWER(H.hamilton_analisis) AS Riesgo_Hamilton,
		OP.puntaje AS Puntaje_Ophi,
		S.srq_totalsi AS Puntaje_Srq
		FROM personas P 
		LEFT JOIN hog_fam V ON P.vivipersona = V.id_fam
		LEFT JOIN hog_geo G ON V.idpre = G.idgeo
		LEFT JOIN personas_datocomp D ON P.tipo_doc = D.dc_tipo_doc AND P.idpeople = D.dc_documento
		LEFT JOIN hog_tam_srq S ON P.tipo_doc = S.srq_tipodoc AND P.idpeople = S.srq_idpersona
		LEFT JOIN hog_tam_apgar A ON P.idpeople = A.idpersona  AND P.vivipersona = V.id_fam
		LEFT JOIN hog_tam_findrisc F ON  P.idpeople = F.idpersona
		LEFT JOIN hog_tam_oms O ON  P.idpeople = O.idpersona
		LEFT JOIN hog_tam_cope C ON P.tipo_doc = C.cope_tipodoc AND P.idpeople = C.cope_idpersona
		LEFT JOIN hog_tam_epoc E ON P.tipo_doc = E.tipo_doc AND P.idpeople = E.documento
		LEFT JOIN hog_tam_zarit Z ON P.tipo_doc = Z.tipodoc AND P.idpeople = Z.idpersona
		LEFT JOIN hog_tam_zung ZU ON P.tipo_doc = ZU.zung_tipodoc AND P.idpeople = ZU.zung_idpersona
		LEFT JOIN hog_tam_hamilton H ON P.tipo_doc = H.hamilton_tipodoc AND P.idpeople = H.hamilton_idpersona
		LEFT JOIN hog_tam_ophi OP ON P.tipo_doc = OP.tipodoc AND P.idpeople = OP.idpersona
		WHERE P.idpeople ='{$id[1]}' AND P.tipo_doc='{$id[0]}'";
		// echo $sql;
		$info=datos_mysql($sql);
				return $info['responseResult'][0];
		}
	} 

	function gra_rptindv() {
		// Aquí obtén los datos necesarios, por ejemplo:
		$datos = array(
			'riesgo' => rand(0, 100) // Simulando un valor de riesgo aleatorio
		);
	
		$html = '<div class="chart-container zoomable">';
		$html .= '<div id="chart_div" style="width: 100%; height: 100%;"></div>';
		$html .= '</div>';
	
		echo $html;
	}

/* function get_person(){
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
} */

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
   
	function opc_flocfter($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=202 and estado='A' AND valor='' ORDER BY 1",$id);
	}
	

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
	   // $rta=iconv('UTF-8','ISO-8859-1',$rta);
	   // var_dump($a);
	   // var_dump($rta);
		   if ($a=='rptindv' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Reporte De Riesgos' id='".$c['ACCIONES']."' Onclick=\"mostrar('rptindv','pro',event,'','lib.php',7,'rptindv');apiGet('".$c['ACCIONES']."');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
	