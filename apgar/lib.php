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


function lis_tamApgar(){ //CAMBIO EN LIS TABLA PERSON RELACIONES  (TODOS LOS LEFT JOIN), cambiar el id de acciones en el sql
	if (!empty($_POST['fidentificacion']) || !empty($_POST['ffam'])) {
		$info=datos_mysql("SELECT COUNT(*) total from hog_tam_apgar O
		LEFT JOIN person P ON O.idpeople = P.idpeople
		LEFT JOIN hog_fam V ON P.vivipersona = V.id_fam
		LEFT JOIN hog_geo G ON V.idpre = G.idgeo
		LEFT JOIN usuarios U ON O.usu_creo=U.id_usuario
		where ".whe_tamApgar());
		$total=$info['responseResult'][0]['total'];
		$regxPag=12;
		$pag=(isset($_POST['pag-tamApgar']))? (intval($_POST['pag-tamApgar'])-1)* $regxPag:0;

		$sql="SELECT O.idpeople ACCIONES,id_apgar 'Cod Registro',V.id_fam 'Cod Familia',P.idpersona Documento,FN_CATALOGODESC(1,P.tipo_doc) 'Tipo de Documento',CONCAT_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,`puntaje` Puntaje,`descripcion` Descripcion, U.nombre Creo,U.subred,U.perfil perfil
	FROM hog_tam_apgar O
		LEFT JOIN person P ON O.idpeople = P.idpeople
		LEFT JOIN hog_fam V ON P.vivipersona = V.id_fam
		LEFT JOIN hog_geo G ON V.idpre = G.idgeo
		LEFT JOIN usuarios U ON O.usu_creo=U.id_usuario
		WHERE ";
	$sql.=whe_tamApgar();
	$sql.=" ORDER BY O.fecha_create DESC";
	//echo $sql;
	$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"tamApgar",$regxPag);
	}else{
		return "<div class='error' style='padding: 12px; background-color:#00a3ffa6;color: white; border-radius: 25px; z-index:100; top:0;text-transform:none'>
                <strong style='text-transform:uppercase'>NOTA:</strong>Por favor Ingrese el numero de documento ó familia a Consultar
                <span style='margin-left: 15px; color: white; font-weight: bold; float: right; font-size: 22px; line-height: 20px; cursor: pointer; transition: 0.3s;' onclick=\"this.parentElement.style.display='none';\">&times;</span>
            </div>";
	}
}

function whe_tamApgar() { //CAMBIO FILTROS DEJAR ESTOS
	$sql = '1';
    if (!empty($_POST['fidentificacion'])) {
        $sql .= " AND P.idpersona = '".$_POST['fidentificacion']."'";
    }
    if (!empty($_POST['ffam'])) {
        $sql .= " AND V.id_fam = '".$_POST['ffam']."'";
    }
    return $sql;
}

function cmp_tamApgar(){
	$rta="";
	$t=['id_apgar'=>'','momento'=>'','tipodoc'=>'','idpersona'=>'','apgar_nombre'=>'','apgar_fechanacimiento'=>'','apgar_edad'=>'','ayuda_fam'=>'','fam_comprobl'=>'','fam_percosnue'=>'','fam_feltrienf'=>'','fam_comptiemjun'=>'','sati_famayu'=>'','sati_famcompro'=>'','sati_famapoemp'=>'','sati_famemosion'=>'','sati_famcompar'=>'','puntaje'=>'','descripcion'=>''];
	$w='tamapgar';
	$d=get_tamApgar();
	if ($d=="") {$d=$t;}
	$u = ($d['id_apgar']!='') ? false : true ;
	$o='datos';
    $key='apg';
	var_dump($d);
	$days=fechas_app('vivienda');//CAMBIO SE ADD ESTA LINEA
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('id','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('idpersona','n','20',$d['idpersona'],$w.' '.$o.' '.$key,'N° Identificación','idpersona',null,'',false,$u,'','col-2');//CAMBIO CAMBIAR  T POR N
	$c[]=new cmp('tipodoc','s','3',$d['tipo_doc'],$w.' '.$o.' '.$key,'Tipo Identificación','tipodoc',null,'',false,$u,'','col-25',"getDatForm('apg','person',['datos']);setTimeout(function() {TamizxApgar('edad');}, 1000);");
	$c[]=new cmp('nombre','t','50',$d['apgar_nombre'],$w.' '.$o,'nombres','nombre',null,'',false,false,'','col-4');
	$c[]=new cmp('fechanacimiento','d','10',$d['apgar_fechanacimiento'],$w.' '.$o,'fecha nacimiento','fechanacimiento',null,'',false,false,'','col-15');
    $c[]=new cmp('edad','n','3',$d['apgar_edad'],$w.' '.$o,'edad','edad',null,'',true,false,'','col-3');
	$c[]=new cmp('fecha_toma','d','10','',$w.' '.$o,'fecha de la Toma','fecha_toma',null,'',true,true,'','col-2',"validDate(this,$days,0);"); //CAMBIO SE ADD ESTA LINEA


	$o=' cuestionario1 oculto ';
				$c[]=new cmp($o,'e',null,'APGAR FAMILIAR 7 A 17 AÑOS',$w);
				$c[]=new cmp('ayuda_fam','s','3',$d['ayuda_fam'],$w.' '.$o,'Cuando algo le preocupa, puede pedir ayuda a su familia','respmenor',null,null,false,true,'','col-10');
				$c[]=new cmp('fam_comprobl','s','3',$d['fam_comprobl'],$w.' '.$o,'Le gusta la manera como su familia habla y comparte los problemas','respmenor',null,null,false,true,'','col-10');
				$c[]=new cmp('fam_percosnue','s','3',$d['fam_percosnue'],$w.' '.$o,'Le gusta como su familia le permite hacer las cosas nuevas que quiere hacer','respmenor',null,null,false,true,'','col-10');
				$c[]=new cmp('fam_feltrienf','s','3',$d['fam_feltrienf'],$w.' '.$o,'Le gusta lo que su familia hace cuando está feliz, triste, enfadado','respmenor',null,null,false,true,'','col-10');
				$c[]=new cmp('fam_comptiemjun','s','3',$d['fam_comptiemjun'],$w.' '.$o,'Le gusta como su familia y él comparten tiempo juntos','respmenor',null,null,false,true,'','col-10');


	$o=' cuestionario2 oculto ';
				$c[]=new cmp($o,'e',null,'APGAR FAMILIAR 18 AÑOS EN ADELANTE',$w);
				$c[]=new cmp('sati_famayu','s','3',$d['sati_famayu'],$w.' '.$o,'Me siento satisfecho con la ayuda que recibo de mi familia cuando tengo algún problema o necesidad','respmayor',null,null,false,true,'','col-10');
				$c[]=new cmp('sati_famcompro','s','3',$d['sati_famcompro'],$w.' '.$o,'Me siento satisfecho con la forma en que mi familia habla de las cosas y comparte los problemas conmigo','respmayor',null,null,false,true,'','col-10');
				$c[]=new cmp('sati_famapoemp','s','3',$d['sati_famapoemp'],$w.' '.$o,'Me siento satisfecho con la forma como mi familia acepta y apoya mis deseos de emprender nuevas actividades','respmayor',null,null,false,true,'','col-10');
				$c[]=new cmp('sati_famemosion','s','3',$d['sati_famemosion'],$w.' '.$o,'Me siento satisfecho con la forma como mi familia expresa afecto y responde a mis emociones como rabia, tristeza o amor','respmayor',null,null,false,true,'','col-10');
				$c[]=new cmp('sati_famcompar','s','3',$d['sati_famcompar'],$w.' '.$o,'Me siento satisfecho con la manera como compartimos en mi familia el tiempo para estar juntos, los espacios en la casa o el dinero ','respmayor',null,null,false,true,'','col-10');

	$o='totalresul';
				$c[]=new cmp($o,'e',null,'TOTAL',$w);
				$c[]=new cmp('puntaje','t','2',$d['puntaje'],$w.' '.$o,'Puntaje','puntaje',null,null,false,false,'','col-5');
				$c[]=new cmp('descripcion','t','3',$d['descripcion'],$w.' '.$o,'Descripcion','descripcion',null,null,false,false,'','col-5');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();

	return $rta;
   }

   function get_tamApgar(){
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);//CAMBIO SELECT
		print_r($_POST);
		$sql="SELECT `id_apgar`,O.idpeople,`ayuda_fam`,`fam_comprobl`,`fam_percosnue`,`fam_feltrienf`,`fam_comptiemjun`,`sati_famayu`,`sati_famcompro`,`sati_famapoemp`,`sati_famemosion`,`sati_famcompar`,`puntaje`,`descripcion`,
        O.estado,P.idpersona,P.tipo_doc,concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) apgar_nombre,P.fecha_nacimiento apgar_fechanacimiento,YEAR(CURDATE())-YEAR(P.fecha_nacimiento) apgar_edad
		FROM `hog_tam_apgar` O
		LEFT JOIN person P ON O.idpeople = P.idpeople
			WHERE P.idpeople ='{$id[0]}'";
		// echo $sql;
		$info=datos_mysql($sql);
			return $info['responseResult'][0];
		}
	}


function get_person(){//CAMBIO TABLA PERSON DEL FROM LINEA 136
	//  print_r($_POST);
	$id=divide($_POST['id']);
$sql="SELECT idpersona,tipo_doc,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,fecha_nacimiento,YEAR(CURDATE())-YEAR(fecha_nacimiento) Edad
from person
	WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."')";
	$info=datos_mysql($sql);
	if (!$info['responseResult']) {
		return json_encode (new stdClass);
	}
return json_encode($info['responseResult'][0]);
}

function focus_tamApgar(){
	return 'tamApgar';
   }

function men_tamApgar(){
	$rta=cap_menus('tamApgar','pro');
	return $rta;
   }

   function cap_menus($a,$b='cap',$con='con') {
	$rta = "";
	$acc=rol($a);
	if ($a=='tamApgar') {
		$rta .= "<li class='icono $a  grabar' title='Grabar' Onclick=\"grabar('$a',this);\" ></li>";

	}
	return $rta;
  }

function gra_tamApgar(){
	$id=$_POST['id'];
	// print_r($_POST);
	if($id != "0"){
		return "No es posible actualizar el tamizaje";
	}else{
		// echo $sql;
		$idp=datos_mysql("SELECT idpeople FROM person WHERE idpersona = {$_POST['idpersona']} AND tipo_doc ='{$_POST['tipodoc']}'");//CAMBIO ADD linea
		if (isset($idp['responseResult'][0])){//CAMBIO ADD linea
			$idper = $idp['responseResult'][0]['idpeople'];//CAMBIO ADD linea
		}//CAMBIO ADD linea

		if ($_POST['fam_comprobl']!='' || $_POST['sati_famcompro']!=''){
			$pre1 = ($_POST['ayuda_fam']) ?  $_POST['ayuda_fam'] : 0;
			$pre2 = ($_POST['fam_comprobl']) ?  $_POST['fam_comprobl'] : 0;
    		$pre3 = ($_POST['fam_percosnue']) ?  $_POST['fam_percosnue']  : 0;
    		$pre4 = ($_POST['fam_feltrienf']) ?  $_POST['fam_feltrienf']  : 0;
    		$pre5 = ($_POST['fam_comptiemjun']) ?  $_POST['fam_comptiemjun']  : 0;
			$pre6 = ($_POST['sati_famayu']) ?  $_POST['sati_famayu']  : 0;
    		$pre7 = ($_POST['sati_famcompro']) ?  $_POST['sati_famcompro']  : 0;
    		$pre8 = ($_POST['sati_famapoemp']) ?  $_POST['sati_famapoemp']  : 0;
    		$pre9 = ($_POST['sati_famemosion']) ?  $_POST['sati_famemosion']  : 0;
    		$pre10 = ($_POST['sati_famcompar']) ?  $_POST['sati_famcompar']  : 0;

			$suma_apgar = ($pre1+$pre2+$pre3+$pre4+$pre5+$pre6+$pre7+$pre8+$pre9+$pre10);


			$ed=$_POST['edad'];
			if($ed>17){
				switch ($suma_apgar) {
					case ($suma_apgar >= 0 && $suma_apgar <=9 ):
						$des='DISFUNCIÓN FAMILIAR SEVERA';
						break;
					case ($suma_apgar >= 10 && $suma_apgar <= 12):
					$des='DISFUNCIÓN FAMILIAR MODERADA';
					break;
				case ($suma_apgar >= 13 && $suma_apgar <= 16):
					$des='DISFUNCIÓN FAMILIAR LEVE';
					break;
				case ($suma_apgar >= 17 && $suma_apgar <= 20):
						$des='FUNCIÓN FAMILIAR NORMAL';
					break;
				default:
					$des='Error en el rango, por favor valide';
					break;
			}
			}else{
				switch ($suma_apgar) {
					case ($suma_apgar >= 0 && $suma_apgar <=3 ):
						$des='DISFUNCIÓN FAMILIAR SEVERA';
						break;
					case ($suma_apgar >= 4 && $suma_apgar <= 6):
						$des='DISFUNCIÓN FAMILIAR MODERADA';
						break;
					case ($suma_apgar >= 7 && $suma_apgar <= 10):
						$des='FUNCIÓN FAMILIAR NORMAL';
						break;
					default:
						$des='Error en el rango, por favor valide';
						break;
				}
				// echo "ES MENOR DE EDAD ".$ed.' '.print_r($_POST);
			}
//cambio ipersona por idpeople en la variable $idper
			$sql="INSERT INTO hog_tam_apgar VALUES (null,
			{$idper},
			trim(upper('{$_POST['fecha_toma']}')),
			trim(upper('{$_POST['ayuda_fam']}')),
			trim(upper('{$_POST['fam_comprobl']}')),
			trim(upper('{$_POST['fam_percosnue']}')),
			trim(upper('{$_POST['fam_feltrienf']}')),
			trim(upper('{$_POST['fam_comptiemjun']}')),
			trim(upper('{$_POST['sati_famayu']}')),
			trim(upper('{$_POST['sati_famcompro']}')),
			trim(upper('{$_POST['sati_famapoemp']}')),
			trim(upper('{$_POST['sati_famemosion']}')),
			trim(upper('{$_POST['sati_famcompar']}')),
			'{$suma_apgar}',
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
  return $sql;
}


	function opc_tipodoc($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}

	function opc_respmenor($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=37 and estado='A' ORDER BY 1",$id);
	}
	function opc_respmayor($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=173 and estado='A' ORDER BY 1",$id);
	}

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
	   // $rta=iconv('UTF-8','ISO-8859-1',$rta);
	   // var_dump($a);
	   // var_dump($rta);
		   if ($a=='tamApgar' && $b=='acciones'){
			$rta="<nav class='menu right'>";
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('tamApgar','pro',event,'','lib.php',7,'tamApgar');setTimeout(hiddxedad,300,'edad','cuestionario1','cuestionario2');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }

	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
