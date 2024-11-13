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


function lis_tamepoc(){
	$info=datos_mysql("SELECT COUNT(*) total from tam_epoc O 
	LEFT JOIN personas P ON O.documento = P.idpersona
	LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
	LEFT JOIN hog_geo G ON V.idpre = G.idgeo 
	LEFT JOIN usuarios U ON O.usu_creo=id_usuario where ".whe_tamepoc());
	$total=$info['responseResult'][0]['total'];
	$regxPag=12;
	$pag=(isset($_POST['pag-tamepoc']))? ($_POST['pag-tamepoc']-1)* $regxPag:0;
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(O.documento,'_',O.tipo_doc,'_') ACCIONES,id_epoc 'Cod Registro',O.documento Documento,FN_CATALOGODESC(1,O.tipo_doc) 'Tipo de Documento',CONCAT_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,`puntaje` Puntaje,`descripcion`Descripcion, U.nombre Creo,U.perfil perfil 
	 FROM tam_epoc O 
	LEFT JOIN personas P ON O.documento = P.idpersona
		LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
		LEFT JOIN hog_geo G ON V.idpre = G.idgeo 
		LEFT JOIN usuarios U ON O.usu_creo=id_usuario
	WHERE ";
	$sql.=whe_tamepoc();
	$sql.=" ORDER BY O.fecha_create DESC";
	//echo $sql;
	$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"tamepoc",$regxPag);
	
}

function whe_tamepoc() {
	$fefin=date('Y-m-d');
	$feini=date('Y-m-d',strtotime($fefin.'- 4 days')); 
	$sql = " G.subred=(SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	if ($_POST['fidentificacion']){
		$sql .= " AND O.documento = '".$_POST['fidentificacion']."'";
	}else{
		$sql.=" AND DATE(O.fecha_create) BETWEEN '$feini' and '$fefin'"; 
	}
	return $sql;
}

function cmp_tamepoc(){
	$rta="";
	$t=['id_epoc'=>'','tipo_doc'=>'','documento'=>'','epoc_nombre'=>'','epoc_fechanacimiento'=>'','epoc_edad'=>'','tose_muvedias'=>'','tiene_flema'=>'','aire_facil'=>'','mayor'=>'','fuma'=>'','puntaje'=>'','descripcion'=>'']; 
	$w='tamepoc';
	$d=get_tamepoc(); 
	if ($d=="") {$d=$t;}
	$u = ($d['id_epoc']!='') ? false : true ;
	$o='datos';
    $key='epo';
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('id','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('documento','t','20',$d['documento'],$w.' '.$o.' '.$key,'N° Identificación','documento',null,'',false,$u,'','col-2');
	$c[]=new cmp('tipo_doc','s','3',$d['tipo_doc'],$w.' '.$o.' '.$key,'Tipo Identificación','tipo_doc',null,'',false,$u,'','col-25','getDatForm(\'epo\',\'person\',[\'datos\']);setTimeout(hiddxedad,500,\'edad\',\'cuestionario1\',\'cuestionario2\');');
	$c[]=new cmp('nombre','t','50',$d['epoc_nombre'],$w.' '.$o,'nombres','nombre',null,'',false,false,'','col-4');
	$c[]=new cmp('fechanacimiento','d','10',$d['epoc_fechanacimiento'],$w.' '.$o,'fecha nacimiento','fechanacimiento',null,'',false,false,'','col-15');
    $c[]=new cmp('edad','n','3',$d['epoc_edad'],$w.' '.$o,'edad','edad',null,'',true,false,'','col-3');
   
    //$c[]=new cmp('act','o','3','',$w.' '.$o,'Desea continuar','act',null,'',true,$u,'','col-3');//,'hiddxedad(\'edad\',\'cuestionario1\',\'cuestionario2\');'
	$o=' cuestionario1 oculto ';
	$c[]=new cmp($o,'e',null,'TAMIZAJE DE EPOC',$w);
	$c[]=new cmp('tose_muvedias','s','3',$d['tose_muvedias'],$w.' '.$o,'¿Tose muchas veces la mayoria de los días?','respuesta',null,null,false,true,'','col-10');
	$c[]=new cmp('tiene_flema','s','3',$d['tiene_flema'],$w.' '.$o,'¿tiene flemas o mocos la mayoria de los días?','respuesta',null,null,false,true,'','col-10');
	$c[]=new cmp('aire_facil','s','3',$d['aire_facil'],$w.' '.$o,'¿Se queda sin aire mas facilmente que otras personas de su edad?','respuesta',null,null,false,true,'','col-10');
	$c[]=new cmp('mayor','s','3',$d['mayor'],$w.' '.$o,'¿Es mayor de 40 años?	','respuesta',null,null,false,true,'','col-10');
	$c[]=new cmp('fuma','s','3',$d['fuma'],$w.' '.$o,'¿Actualmente fuma o es un exfumador?','respuesta',null,null,false,true,'','col-10');

	$o='totalresul';
	$c[]=new cmp($o,'e',null,'TOTAL',$w);
	$c[]=new cmp('puntaje','t','2',$d['puntaje'],$w.' '.$o,'Puntaje','puntaje',null,null,false,false,'','col-5');
	$c[]=new cmp('descripcion','t','3',$d['descripcion'],$w.' '.$o,'Descripcion','descripcion',null,null,false,false,'','col-5');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	
	return $rta;
   }

   function get_tamepoc(){
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		// print_r($_POST);
		$sql="SELECT `id_epoc`,O.`documento`,O.`tipo_doc`,
		`tose_muvedias`,`tiene_flema`,`aire_facil`,`mayor`,`fuma`,`puntaje`,`descripcion`,
        O.estado,P.idpersona,P.tipo_doc,concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) epoc_nombre,P.fecha_nacimiento epoc_fechanacimiento,YEAR(CURDATE())-YEAR(P.fecha_nacimiento) epoc_edad
		FROM `tam_epoc` O
		LEFT JOIN personas P ON O.documento = P.idpersona and O.tipo_doc=P.tipo_doc
		WHERE O.documento ='{$id[0]}' AND O.tipo_doc='{$id[1]}' ";
		// echo $sql;
		$info=datos_mysql($sql);
				return $info['responseResult'][0];
		}
	} 


function get_person(){
	// print_r($_POST);
	$id=divide($_POST['id']);
$sql="SELECT idpersona,tipo_doc,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,fecha_nacimiento,YEAR(CURDATE())-YEAR(fecha_nacimiento) Edad
from personas
	WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."')";
	$info=datos_mysql($sql);
	if (!$info['responseResult']) {
		return json_encode (new stdClass);
	}
return json_encode($info['responseResult'][0]);
}

function focus_tamepoc(){
	return 'tamepoc';
   }
   
function men_tamepoc(){
	$rta=cap_menus('tamepoc','pro');
	return $rta;
   }

   function cap_menus($a,$b='cap',$con='con') {
	$rta = ""; 
	$acc=rol($a);
	if ($a=='tamepoc') {  
		$rta .= "<li class='icono $a  grabar' title='Grabar' Onclick=\"grabar('$a',this);\" ></li>";
		
	}
	return $rta;
  }
   
function gra_tamepoc(){
	$id=$_POST['id'];
	//print_r($_POST);
	if($id != "0"){
		return "No es posible actualizar el tamizaje";
	}else{
		$infodata_epoc=datos_mysql("SELECT documento FROM tam_epoc WHERE documento = {$_POST['documento']} ");
	if (isset($infodata_epoc['responseResult'][0])){
		return "Ya se realizo los dos momentos";
	}else{
		$infodata2_apgar=datos_mysql("SELECT documento FROM tam_epoc
		 WHERE documento = {$_POST['documento']} ");
		if (isset($infodata2_apgar['responseResult'][0])){
			$idmomento = 2;
		}else{
			$idmomento = 1;
		}
	}

	$suma_epoc = (
		    intval($_POST['tose_muvedias'])+
			intval($_POST['tiene_flema'])+
			intval($_POST['aire_facil'])+
			intval($_POST['mayor'])+
			intval($_POST['fuma'])
		);

		switch ($suma_epoc) {
				case $suma_epoc == 0:
					$des='RIESGO BAJO';
					break;
				case ($suma_epoc >0 && $suma_epoc < 3):
					$des='RIESGO BAJO';
					break;
				case ($suma_epoc > 2 ):
						$des='RIESGO ALTO';
					break;
				default:
					$des='Error en el rango, por favor valide';
					break;
			}
		{
	if($_POST['id']==0){
		$id=$_POST['id'];
		
		
			// echo "ES MENOR DE EDAD ".$ed.' '.print_r($_POST);
	/* $tose = ($_POST['tose_muvedias']==1) ? 'SI' : 'NO' ;
	$flema = ($_POST['tiene_flema']==1) ? 'SI' : 'NO' ;
	$aire = ($_POST['aire_facil']==1) ? 'SI' : 'NO' ;
	$mayor = ($_POST['mayor']==1) ? 'SI' : 'NO' ;
	$fuma = ($_POST['fuma']==1) ? 'SI' : 'NO' ; */

		$sql="INSERT INTO tam_epoc VALUES (null,
		
		trim(upper('{$_POST['tipo_doc']}')),
		trim(upper('{$_POST['documento']}')),
		trim(upper('{$_POST['tose_muvedias']}')),
		trim(upper('{$_POST['tiene_flema']}')),
		trim(upper('{$_POST['aire_facil']}')),
		trim(upper('{$_POST['mayor']}')),
		trim(upper('{$_POST['fuma']}')),
		'{$suma_epoc}',
		trim(upper('{$des}')),
		TRIM(UPPER('{$_SESSION['us_sds']}')),
		DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
		// echo $sql;
		$rta=dato_mysql($sql);
	}else{
		// print_r($_POST);
		return 'TAMIZAJE NO APLICA PARA LA EDAD';
	}
		
	}
  return $rta; 
}
}

	function opc_tipo_doc($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}

	function opc_respuesta($id=''){
	return opc_sql("SELECT `valor`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
	}
	

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
	   // $rta=iconv('UTF-8','ISO-8859-1',$rta);
	   // var_dump($a);
	   // var_dump($rta);
		   if ($a=='tamepoc' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('tamepoc','pro',event,'','lib.php',7,'tamepoc');setTimeout(hiddxedad,300,'edad','cuestionario1','cuestionario2');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
	