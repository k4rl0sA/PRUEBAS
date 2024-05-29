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
	$info=datos_mysql("SELECT COUNT(*) total FROM personas P LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN hog_tam_apgar A ON P.idpersona = A.idpersona AND P.tipo_doc = A.tipodoc AND P.vivipersona = V.idviv LEFT JOIN hog_tam_findrisc F ON P.tipo_doc = F.tipodoc AND P.idpersona = F.idpersona LEFT JOIN hog_tam_oms O ON P.tipo_doc = O.tipodoc AND P.idpersona = O.idpersona LEFT JOIN hog_tam_cope C ON P.tipo_doc = C.cope_tipodoc AND P.idpersona = C.cope_idpersona LEFT JOIN tam_epoc E ON P.tipo_doc = E.tipo_doc AND P.idpersona = E.documento LEFT JOIN hog_tam_zarit Z ON P.tipo_doc = Z.zarit_tipodoc AND P.idpersona = Z.zarit_idpersona LEFT JOIN hog_tam_zung ZU ON P.tipo_doc = ZU.zung_tipodoc AND P.idpersona = ZU.zung_idpersona	LEFT JOIN hog_tam_hamilton H ON P.tipo_doc = H.hamilton_tipodoc AND P.idpersona = H.hamilton_idpersona WHERE '1' ".whe_rptindv());
	$total=$info['responseResult'][0]['total'];
	$regxPag=10;
	$pag=(isset($_POST['pag-rptindv']))? ($_POST['pag-rptindv']-1)* $regxPag:0;

	$sql="SELECT  concat_ws('_',P.tipo_doc,P.idpersona ) as ACCIONES,
		P.tipo_doc AS Tipo_Documento, P.idpersona AS N°_Documento, CONCAT(P.nombre1, ' ', P.nombre2, ' ', P.apellido1, ' ', P.apellido2) AS Usuario,
	FN_CATALOGODESC(2,G.localidad) AS Localidad, G.territorio AS Territorio,G.direccion AS Direccion, 
	CONCAT(V.complemento1, ' ', V.nuc1, ' ', V.complemento2, ' ', V.nuc2, ' ', V.complemento3, ' ', V.nuc3) AS Complementos, V.telefono1 AS Telefono,
	P.vivipersona AS Cod_Familia
	FROM personas P 
	LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
	LEFT JOIN hog_geo G ON V.idpre = G.idgeo
	LEFT JOIN hog_tam_apgar A ON P.idpersona = A.idpersona AND P.tipo_doc = A.tipodoc AND P.vivipersona = V.idviv
	LEFT JOIN hog_tam_findrisc F ON P.tipo_doc = F.tipodoc AND P.idpersona = F.idpersona
	LEFT JOIN hog_tam_oms O ON P.tipo_doc = O.tipodoc AND P.idpersona = O.idpersona
	LEFT JOIN hog_tam_cope C ON P.tipo_doc = C.cope_tipodoc AND P.idpersona = C.cope_idpersona
	LEFT JOIN tam_epoc E ON P.tipo_doc = E.tipo_doc AND P.idpersona = E.documento
	LEFT JOIN hog_tam_zarit Z ON P.tipo_doc = Z.zarit_tipodoc AND P.idpersona = Z.zarit_idpersona
	LEFT JOIN hog_tam_zung ZU ON P.tipo_doc = ZU.zung_tipodoc AND P.idpersona = ZU.zung_idpersona
	LEFT JOIN hog_tam_hamilton H ON P.tipo_doc = H.hamilton_tipodoc AND P.idpersona = H.hamilton_idpersona
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
	'Direccion'=>'','Telefono_Contacto'=>'','Rango_Edad'=>'','IMC'=>'','Puntaje_Oms'=>'','Puntaje_Findrisc'=>'']; 
	 $d=get_rptindv(); 
	//  $d="";
	 if ($d=="") {$d=$t;}
	// var_dump($d);


	$imc = $d['IMC'];
    $delgadez = $normal = $sobrepeso = $obesidad = '';
    if ($imc < 18.5) {
        $delgadez = $imc;
    } elseif ($imc >= 18.5 && $imc < 24.9) {
        $normal = $imc;
    } elseif ($imc >= 25 && $imc < 29.9) {
        $sobrepeso = $imc;
    } elseif ($imc >= 30) {
        $obesidad = $imc;
    }

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
            <div><b>OMS</b> '.$d["Puntaje_Oms"].' '.$d["Riesgo_Oms"].'</div>
            <div><br></div>

            <div class="btn-group">
              <div class="btn-contain">
                <span class="custom-btn low-risk">Delgadez</span>
                <div class="btn-value low-risk"> '..'</div>
              </div>

              <div class="btn-contain">
                <span class="custom-btn normal-risk">Normal</span>
                <div class="btn-value normal-risk">'..'<div>
              </div>

              <div class="btn-contain">
                <span class="custom-btn medium-risk">Sobrepeso</span>
                <div class="btn-value medium-risk">'..'<div>
              </div>

              <div class="btn-contain">
                <span class="custom-btn high-risk">Obesidad</span>
                <div class="btn-value high-risk"> '..'<div>
              </div>
            </div>
         </div>
         <div class="user-details">
            <div><b>SRQ:</b> 20</div>
            <div><b>Findrisc:</b>'.$d["Puntaje_Findrisc"].' '.$d["Riesgo_Findrisc"].'</div>
         </div>
        <div class="user-details">
            <div><b>RQC:</b> 30</div>
            <div><b>COPE 28:</b>'.$d["Puntaje_Cope"].' '.$d["Riesgo_Cope"].'</div>
        </div>
        <div class="user-details">
            <div><b>EPOC:</b> '.$d["Puntaje_Epoc"].' '.$d["Riesgo_Epoc"].'</div>
        </div>
    </div>
    <div class="title-risk">Atención Individual</div>
    <div class="user-info section">
        <div class="user-details">
            <div><b>Zarith:</b> '.$d["Puntaje_Zarit"].' '.$d["Riesgo_Zarit"].'</div>
            <div><b>Hamilton:</b> '.$d["Puntaje_Hamilton"].' '.$d["Riesgo_Hamilton"].'</div>
        </div>
        <div class="user-details">
            <div><b>Zung:</b> '.$d["Puntaje_Zung"].' '.$d["Riesgo_Zung"].'</div>
            <div><b>Ophi II:</b> '.$d["Puntaje_Ophi"].'</div>
        </div>
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
		FN_CATALOGODESC(2,G.localidad) AS Localidad, G.territorio AS Territorio,G.direccion AS Direccion, CONCAT(V.complemento1, ' ', V.nuc1, ' ', V.complemento2, ' ', V.nuc2, ' ', V.complemento3, ' ', V.nuc3) AS Complementos, V.telefono1 AS Telefono_Contacto,
		P.vivipersona AS Cod_Familia,
		P.tipo_doc AS Tipo_Documento, P.idpersona AS N°_Documento, CONCAT(P.nombre1, ' ', P.nombre2, ' ', P.apellido1, ' ', P.apellido2) AS Usuario, FN_CATALOGODESC(21,P.sexo) AS Sexo, FN_CATALOGODESC(19,P.genero) AS Genero, FN_CATALOGODESC(30,P.nacionalidad) AS Nacionalidad,
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
		D.imc AS IMC,
		A.puntaje AS Puntaje_Apgar, LOWER(A.descripcion) AS Riesgo_Apgar,
		F.puntaje AS Puntaje_Findrisc, LOWER(F.descripcion) AS Riesgo_Findrisc,
		O.puntaje AS Puntaje_Oms, LOWER(O.descripcion) AS Riesgo_Oms,
		C.cope_puntajea AS Puntaje_Cope, LOWER(C.cope_descripciona) AS Riesgo_Cope,
		E.puntaje AS Puntaje_Epoc, LOWER(E.descripcion) AS Riesgo_Epoc,
		Z.zarit_puntaje AS Puntaje_Zarit, LOWER(Z.zarit_analisis) AS Riesgo_Zarit,
		ZU.zung_puntaje AS Puntaje_Zung, LOWER(ZU.zung_analisis) AS Riesgo_Zung,
		H.hamilton_total AS Puntaje_Hamilton, LOWER(H.hamilton_analisis) AS Riesgo_Hamilton,
		OP.ophi_puntaje AS Puntaje_Ophi
		FROM personas P 
		LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
		LEFT JOIN hog_geo G ON V.idpre = G.idgeo
		LEFT JOIN personas_datocomp D ON P.tipo_doc = D.dc_tipo_doc AND P.idpersona = D.dc_documento
		LEFT JOIN hog_tam_apgar A ON P.idpersona = A.idpersona AND P.tipo_doc = A.tipodoc AND P.vivipersona = V.idviv
		LEFT JOIN hog_tam_findrisc F ON P.tipo_doc = F.tipodoc AND P.idpersona = F.idpersona
		LEFT JOIN hog_tam_oms O ON P.tipo_doc = O.tipodoc AND P.idpersona = O.idpersona
		LEFT JOIN hog_tam_cope C ON P.tipo_doc = C.cope_tipodoc AND P.idpersona = C.cope_idpersona
		LEFT JOIN tam_epoc E ON P.tipo_doc = E.tipo_doc AND P.idpersona = E.documento
		LEFT JOIN hog_tam_zarit Z ON P.tipo_doc = Z.zarit_tipodoc AND P.idpersona = Z.zarit_idpersona
		LEFT JOIN hog_tam_zung ZU ON P.tipo_doc = ZU.zung_tipodoc AND P.idpersona = ZU.zung_idpersona
		LEFT JOIN hog_tam_hamilton H ON P.tipo_doc = H.hamilton_tipodoc AND P.idpersona = H.hamilton_idpersona
		LEFT JOIN hog_tam_ophi OP ON P.tipo_doc = OP.ophi_tipodoc AND P.idpersona = OP.ophi_idpersona
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
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('rptindv','pro',event,'','lib.php',7,'rptindv');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
	