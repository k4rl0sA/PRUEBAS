<?php
ini_set('display_errors','1');
require_once "../libs/gestion.php";
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


function lis_tamoms(){
	$info=datos_mysql("SELECT COUNT(*) total from hog_tam_oms O 
	LEFT JOIN person P ON O.idpeople = P.idpeople
		LEFT JOIN hog_fam V ON P.vivipersona = V.id_fam
		LEFT JOIN hog_geo G ON V.idpre = G.idgeo 
		LEFT JOIN usuarios U ON O.usu_creo=id_usuario
	 where ".whe_tamoms());
	$total=$info['responseResult'][0]['total'];
	$regxPag=12;
	$pag=(isset($_POST['pag-tamoms']))? ($_POST['pag-tamoms']-1)* $regxPag:0;
	
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(O.idpersona,'_',O.tipodoc) ACCIONES,idoms 'Cod Registro',O.idpersona Documento,FN_CATALOGODESC(1,O.tipodoc) 'Tipo de Documento',CONCAT_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,`puntaje` Puntaje,descripcion  
	FROM hog_tam_oms O 
	LEFT JOIN person P ON O.idpeople = P.idpeople
		LEFT JOIN hog_fam V ON P.vivipersona = V.id_fam
		LEFT JOIN hog_geo G ON V.idpre = G.idgeo 
		LEFT JOIN usuarios U ON O.usu_creo=id_usuario
	WHERE ";
	$sql.=whe_tamoms();
	$sql.=" ORDER BY O.fecha_create DESC";
	//echo $sql;
	$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"tamoms",$regxPag);
	
}

function whe_tamoms() {
	$sql = '1';
    if (!empty($_POST['fidentificacion'])) {
        $sql .= " AND P.idpersona = '".$_POST['fidentificacion']."'";
    }
    if (!empty($_POST['ffam'])) {
        $sql .= " AND V.id_fam = '".$_POST['ffam']."'";
    }
    return $sql;
}


function cmp_tamoms(){
	$rta="<div class='encabezado oms'>TABLA oms</div><div class='contenido' id='oms-lis'>".lis_oms()."</div></div>";
	$a=['idoms'=>'','diabetes'=>'','fuma'=>'','tas'=>'','puntaje'=>'','descripcion'=>'',];
	$p=['idoms'=>'','idpeople'=>'','Fecha_toma'=>'','diabetes'=>'','fuma'=>'','tas'=>'','puntaje'=>'','descripcion'=>'']; 
	$w='tamoms';
	$d=get_tamoms(); 
	if ($d=="") {$d=$t;}
	$u = ($d['idoms']!='') ? false : true ;
	$o='datos';
    $key='oms';
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('id','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('idpersona','t','20',$d['idpersona'],$w.' '.$o.' '.$key,'N° Identificación','idpersona',null,'',false,$u,'','col-3');
	$c[]=new cmp('tipodoc','s','3',$d['tipodoc'],$w.' '.$o.' '.$key,'Tipo Identificación','tipodoc',null,'',false,$u,'','col-3',"getDatForm('oms','person','datos');setTimeout(function() {hiddxTamiz('edad', 'pruoms',17);}, 1000);");
	$c[]=new cmp('nombre','t','50',$d['nombre'],$w.' '.$o,'nombres','nombre',null,'',false,false,'','col-4');
	$c[]=new cmp('sexo','s','3',$d['sexo'],$w.' '.$o,'Sexo','sexo',null,'',false,false,'','col-2');
	$c[]=new cmp('fechanacimiento','d','10',$d['fechanacimiento'],$w.' '.$o,'fecha nacimiento','fechanacimiento',null,'',false,false,'','col-3');
    $c[]=new cmp('edad','n','3',$d['edad'],$w.' '.$o,'edad en Años','edad',null,'',true,false,'','col-2');

	$o='pruoms oculto';
 	$c[]=new cmp($o,'e',null,'PRUEBA OMS Riesgo Cardiovascular',$w);
 	$c[]=new cmp('fuma','s',2,$d['fuma'],$w.' '.$o,'Fuma','fuma',null,null,false,true,'','col-25');
	$c[]=new cmp('diabetes','s',3,$d['diabetes'],$w.' '.$o,'Tiene Diabetes','diabetes',null,null,false,true,'','col-3');
	$c[]=new cmp('tas','n',3,$d['tas'],$w.' '.$o,'Presión Sistólica (mmHg)','tas',null,null,false,true,'','col-2');

	$o='totalresul';
	$c[]=new cmp($o,'e',null,'TOTAL',$w);
	$c[]=new cmp('puntaje','t','10',$d['puntaje'],$w.' '.$o,'Puntaje','puntaje',null,null,false,false,'','col-5');
	$c[]=new cmp('descripcion','t','50',$d['descripcion'],$w.' '.$o,'Descripcion','descripcion',null,null,false,false,'','col-5');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	
	return $rta;
   }

   function get_tamoms(){
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		// print_r($_POST);
		$sql="SELECT idoms,O.`idpersona`,O.`tipodoc`,
		diabetes,fuma,tas,puntaje,descripcion,
		O.estado,P.idpersona,P.tipo_doc,concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) nombre,sexo,P.fecha_nacimiento fechanacimiento,TIMESTAMPDIFF(YEAR,fecha_nacimiento, CURDATE()) edad
		FROM `hog_tam_oms` O
		LEFT JOIN person P ON O.idpersona = P.idpersona and O.tipodoc=P.tipo_doc
		WHERE O.idpersona ='{$id[0]}' AND O.tipodoc='{$id[1]}'";
		// echo $sql;
		$info=datos_mysql($sql);
				return $info['responseResult'][0];
		}
	} 


function get_person(){
	// print_r($_POST);
	$id=divide($_POST['id']);
	$sql="SELECT idpersona,tipo_doc,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,sexo ,fecha_nacimiento,TIMESTAMPDIFF(YEAR,fecha_nacimiento, CURDATE()) edad
from personas
WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."');";
	
	// return json_encode($sql);
	$info=datos_mysql($sql);
	if (!$info['responseResult']) {
		return json_encode (new stdClass);
	}
return json_encode($info['responseResult'][0]);
}

function focus_tamoms(){
	return 'tamoms';
   }
   
function men_tamoms(){
	$rta=cap_menus('tamoms','pro');
	return $rta;
   }

   function cap_menus($a,$b='cap',$con='con') {
	$rta = ""; 
	$acc=rol($a);
	if ($a=='tamoms') {  
		$rta .= "<li class='icono $a  grabar' title='Grabar' Onclick=\"grabar('$a',this);\" ></li>";
	}
	return $rta;
  }
   
function gra_tamoms(){
	
	$diab = ($_POST['diabetes']==1) ? 'SI' : 'NO';
	$fuma = ($_POST['fuma']==1) ? 'SI' : 'NO';

$sql2="SELECT CASE
        WHEN {$_POST['edad']} < 50 THEN 40
        WHEN {$_POST['edad']} >= 50 AND {$_POST['edad']} < 60 THEN 50
        WHEN {$_POST['edad']} >= 60 AND {$_POST['edad']} < 70 THEN 60
        ELSE 70
    END anios,
     CASE
        WHEN  {$_POST['tas']}< 140 THEN 120
        WHEN  {$_POST['tas']}>= 140 AND {$_POST['tas']} < 160 THEN 140
        WHEN  {$_POST['tas']}>= 160 AND {$_POST['tas']} < 180 THEN 160
        ELSE 180
    END ten;";	
$info=datos_mysql($sql2);
$año=$info['responseResult'][0]['anios'];
$ten=$info['responseResult'][0]['ten'];


$sql1="SELECT puntaje,clasificacion from oms 
where diabetes='{$diab}' AND sexo='{$_POST['sexo']}' AND fuma='{$fuma}' 
AND edad=$año AND tas=$ten;";

// echo $sql1;
$info=datos_mysql($sql1);
$suma_oms=$info['responseResult'][0]['puntaje'];
$des=$info['responseResult'][0]['clasificacion'];

	if($_POST['id']==0){
			// echo "ES MENOR DE EDAD ".$ed.' '.print_r($_POST);
		$sql="INSERT INTO hog_tam_oms VALUES (null,
		trim(upper('{$_POST['tipodoc']}')),trim(upper('{$_POST['idpersona']}')),trim(upper('{$_POST['diabetes']}')),trim(upper('{$_POST['fuma']}')),trim(upper('{$_POST['tas']}')),
		'{$suma_oms}',
		trim(upper('{$des}')),
		TRIM(UPPER('{$_SESSION['us_sds']}')),
		DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
		// echo $sql;
		$rta=dato_mysql($sql);
		// print_r($_POST);
		// return 'TAMIZAJE NO APLICA PARA LA EDAD';
	}else{
		$id=divide($_POST['id']);
		$sql="UPDATE hog_tam_oms SET  
		diabetes=trim(upper('{$_POST['diabetes']}')),fuma=trim(upper('{$_POST['fuma']}')),tas=trim(upper('{$_POST['tas']}')),puntaje=trim(upper('{$_POST['puntaje']}')),descripcion=trim(upper('{$_POST['descripcion']}')),
		usu_update=TRIM(UPPER('{$_SESSION['us_sds']}')),fecha_update=DATE_SUB(NOW(), INTERVAL 5 HOUR)
		where tipodoc='{$id[0]}' AND idpersona='$id[1]'";
		$rta=dato_mysql($sql);
	}
  return $rta; 
}


	function opc_tipodoc($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}
	function opc_sexo($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
	}
	function opc_fuma($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
	}
	function opc_diabetes($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
	}
	


	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
	   // $rta=iconv('UTF-8','ISO-8859-1',$rta);
	   // var_dump($a);
	   // var_dump($rta);
		   if ($a=='tamoms' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('tamoms','pro',event,'','lib.php',7,'tamoms');setTimeout(hiddxedad,300,'edad','prufin');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
	