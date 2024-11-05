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


function lis_tamfindrisc(){
	$info=datos_mysql("SELECT COUNT(*) total from hog_tam_findrisc O 
	LEFT JOIN person P ON O.idpeople = P.idpeople
		LEFT JOIN hog_fam V ON P.vivipersona = V.id_fam
		LEFT JOIN hog_geo G ON V.idpre = G.idgeo 
		LEFT JOIN usuarios U ON O.usu_creo=id_usuario
	 where ".whe_tamfindrisc());//CAMBIO LEFT JOIN Personas y hog_fam antes hog_viv lineas 25-26
	$total=$info['responseResult'][0]['total'];
	$regxPag=12;
	$pag=(isset($_POST['pag-tamfindrisc']))? ($_POST['pag-tamfindrisc']-1)* $regxPag:0;

	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(O.idpersona,'_',O.tipodoc) ACCIONES,id_findrisc 'Cod registro',O.idpersona Documento,FN_CATALOGODESC(1,O.tipodoc) 'Tipo de Documento',CONCAT_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,`puntaje` Puntaje,`descripcion` descripcion, U.nombre Creo,U.perfil perfil 
	FROM hog_tam_findrisc O 
		LEFT JOIN person P ON O.idpeople = P.idpeople
		LEFT JOIN hog_fam V ON P.vivipersona = V.id_fam
		LEFT JOIN hog_geo G ON V.idpre = G.idgeo 
		LEFT JOIN usuarios U ON O.usu_creo=id_usuario
	WHERE ";//CAMBIO LEFT JOIN Personas y hog_fam antes hog_viv lineas 36-37
	$sql.=whe_tamfindrisc();
	$sql.=" ORDER BY O.fecha_create DESC";
	// echo $sql;
	$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"tamfindrisc",$regxPag);
	
}

function whe_tamfindrisc() {//CAMBIO FILTROS DEJAR ESTOS cambiar todo lo que este dentro de la function
	$sql = '1';
    if (!empty($_POST['fidentificacion'])) {
        $sql .= " AND P.idpersona = '".$_POST['fidentificacion']."'";
    }
    if (!empty($_POST['ffam'])) {
        $sql .= " AND V.id_fam = '".$_POST['ffam']."'";
    }
    return $sql;
}


function lis_findrisc(){//CAMBIO INGRESAR ESTA FUNCION ACORDE AL TAMIZAJE todos los campos 
	// var_dump($_POST['id']);
	$id=divide($_POST['id']);
	$sql="SELECT id_findrisc ACCIONES,
	id_findrisc 'Cod Registro',fecha_toma,descripcion,`nombre` Creó,`fecha_create` 'fecha Creó'
	FROM hog_tam_findrisc A
	LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario ";
	$sql.="WHERE idpeople='".$id[0];
	$sql.="' ORDER BY fecha_create";
	// echo $sql;
	$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"findrisc-lis",5);
}

function cmp_tamfindrisc(){
	//CAMBIO INICIA ACA AJUSTAR ACORDE AL TAMIZAJE y los campos de la BD
	$rta="<div class='encabezado findrisc'>TABLA FINDRISC</div><div class='contenido' id='findrisc-lis'>".lis_findrisc()."</div></div>";
	$a=['id_findrisc'=>'','diabetes'=>'','peso'=>'','talla'=>'','imc'=>'','perimcint'=>'','actifisica'=>'','verduras'=>'','hipertension'=>'','glicemia'=>'','diabfam'=>'','puntaje'=>'','descripcion'=>'']; 
	$p=['id_findrisc'=>'','idpersona'=>'','tipo_doc'=>'','findrisc_nombre'=>'','findrisc_fechanacimiento'=>'','findrisc_edad'=>'','puntaje'=>'','descripcion'=>'']; //CAMBIO ADD LINEA
	$w='tamfindrisc';
	$d=get_tfindrisc();
	var_dump($d);
	if (!isset($d['id_findrisc'])) {
		$d = array_merge($d,$a);
	}
	$u = ($d['id_findrisc']!='') ? false : true ;
	//CAMBIO HASTA AQUI
	$o='datos';
    $key='find';
	// var_dump($d);
	$days=fechas_app('vivienda');//CAMBIO DE ADD ESTA LINEA
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);//CAMBIO DE $d[''] POR '' EN LOS CAMPOS NO PERSONALES
	$c[]=new cmp('id','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('idpersona','t','20',$d['idpersona'],$w.' '.$o.' '.$key,'N° Identificación','idpersona',null,'',false,$u,'','col-3');
	$c[]=new cmp('tipodoc','s','3',$d['tipo_doc'],$w.' '.$o.' '.$key,'Tipo Identificación','tipodoc',null,'',false,$u,'','col-3',"");//setTimeout(hiddxedad,1000,\'edad\',\'find\');
	$c[]=new cmp('nombre','t','50',$d['findrisc_nombre'],$w.' '.$o,'nombres','nombre',null,'',false,false,'','col-4');
	$c[]=new cmp('sexo','s','3',$d['sexo'],$w.' '.$o,'Sexo','sexo',null,'',false,false,'','col-2');
	$c[]=new cmp('fechanacimiento','d','10',$d['findrisc_fechanacimiento'],$w.' '.$o,'fecha nacimiento','fechanacimiento',null,'',false,false,'','col-3');
    $c[]=new cmp('edad','n','3',$d['findrisc_edad'],$w.' '.$o,'edad en Años','edad',null,'',true,false,'','col-2');
	$c[]=new cmp('fecha_toma','d','10','',$w.' '.$o,'fecha de la Toma','fecha_toma',null,'',true,true,'','col-2',"validDate(this,$days,0);"); //CAMBIO SE ADD ESTA LINEA
	$c[]=new cmp('diabetes','s',3,'',$w.' '.$o,'Tiene Diabetes','diabetes',null,null,false,true,'','col-3',"setTimeout(hiddxdiab,500,'diabetes','prufin');");

	$ed=false;
	if($d['findrisc_edad']>11){
		$o='prufin';
		$ed=true;
 		$c[]=new cmp($o,'e',null,'PRUEBA FINDRISC',$w);
 		$c[]=new cmp('peso','t',6,'',$w.' '.$o,'Peso (Kg) Mínimo=0.50 (Kg) - Máximo=150.00 (Kg)','peso','rgxpeso','###.##',true,$ed,'','col-25');
 		$c[]=new cmp('talla','n',3,'',$w.' '.$o,'Talla (Cm) Mínimo=120 (Cm) - Máximo=210 (Cm)','talla','rgxtalla',null,false,$ed,'','col-25',"calImc('peso','talla','imc');");
		$c[]=new cmp('imc','t',6, '',$w.'IMC','imc',null,null,false,false,'','col-2');
		$c[]=new cmp('perimcint','n',3,'',$w.' '.$o,'Perimetro de cintura (Cm) Mínimo=50 (Cm) - Máximo=210 (Cm)','perimcint','rgxperabd',null,false,$ed,'','col-3');
 		$c[]=new cmp('actifisica','s',3,'',$w.' '.$o,'Hace habitualmente (a diario) al menos 30 minutos de actividad física en el trabajo o durante su tiempo libre?','actifisica',null,null,false,$ed,'','col-5');
 		$c[]=new cmp('verduras','s',3,'',$w.' '.$o,'Come verduras o frutas Todos los dias ?','verduras',null,null,false,$ed,'','col-2');
 		$c[]=new cmp('hipertension','s',3,'',$w.' '.$o,'Toma regularmente medicación para la hipertensión ?','hipertension',null,null,false,$ed,'','col-3');
 		$c[]=new cmp('glicemia','s',3,'',$w.' '.$o,'Le han encontrado alguna vez valores de glucosa altos ?','glicemia','rgxgluco',null,false,$ed,'','col-5');
 		$c[]=new cmp('diabfam','s',3,'',$w.' '.$o,'Se le ha diagnosticado diabetes (tipo 1 o tipo 2) a alguno de sus familiares ?','diabfam',null,null,false,$ed,'','col-5');
	}

	$o='totalresul';
	$c[]=new cmp($o,'e',null,'TOTAL',$w);
	$c[]=new cmp('puntaje','t','2','',$w.' '.$o,'Puntaje','puntaje',null,null,false,false,'','col-5');
	$c[]=new cmp('descripcion','t','3','',$w.' '.$o,'Descripcion','descripcion',null,null,false,false,'','col-5');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
   }

   function get_tamfindrisc() { // NUEVA FUNCIÓN ADAPTADA AL TAMIZAJE FINDRISC
    if (empty($_REQUEST['id'])) {
        return "";
    }
    
    $id = divide($_REQUEST['id']);
    $sql = "SELECT A.id_findrisc, P.idpersona, P.tipo_doc,
            concat_ws(' ', P.nombre1, P.nombre2, P.apellido1, P.apellido2) AS findrisc_nombre,
            P.fecha_nacimiento AS findrisc_fechanacimiento, 
            YEAR(CURDATE()) - YEAR(P.fecha_nacimiento) AS findrisc_edad,
            A.fecha_toma, A.diabetes, A.peso, A.talla, A.imc, A.perimcint, 
            A.actifisica, A.verduras, A.hipertension, A.glicemia, A.diabfam, 
            A.puntaje, A.descripcion
            FROM hog_tam_findrisc A
            LEFT JOIN person P ON A.idpeople = P.idpeople
            WHERE A.id_findrisc = '{$id[0]}'";

    $info = datos_mysql($sql);
    $data = $info['responseResult'][0];

    // Datos básicos
    $baseData = [
        'id_findrisc' => $data['id_findrisc'],
        'idpersona' => $data['idpersona'],
        'tipo_doc' => $data['tipo_doc'],
        'findrisc_nombre' => $data['findrisc_nombre'],
        'findrisc_fechanacimiento' => $data['findrisc_fechanacimiento'],
        'findrisc_edad' => $data['findrisc_edad'],
        'fecha_toma' => $data['fecha_toma'] ?? null, // Valor por defecto null si no está definido
    ];
    // Campos adicionales específicos del tamizaje Findrisc
    $edadCampos = [
        'diabetes', 'peso', 'talla', 'imc', 'perimcint', 
        'actifisica', 'verduras', 'hipertension', 
        'glicemia', 'diabfam', 'puntaje', 'descripcion'
    ];
    foreach ($edadCampos as $campo) {
        $baseData[$campo] = $data[$campo];
    }
    return json_encode($baseData);
}


	function get_tfindrisc(){//CAMBIO function nueva
		if($_POST['id']==0){
			return "";
		}else{
			 $id=divide($_POST['id']);
			// print_r($_POST);
			$sql="SELECT id_findrisc,O.idpeople,diabetes,peso,talla,imc,perimcint,actifisica,verduras,hipertension,glicemia,diabfam,puntaje,descripcion,
			O.estado,P.idpersona,P.tipo_doc,concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) findrisc_nombre,P.fecha_nacimiento findrisc_fechanacimiento,YEAR(CURDATE())-YEAR(P.fecha_nacimiento) findrisc_edad
			FROM `hog_tam_findrisc` O
			LEFT JOIN person P ON O.idpeople = P.idpeople
				WHERE P.idpeople ='{$id[0]}'";
			// echo $sql;
			$info=datos_mysql($sql);
				if (!$info['responseResult']) {
					$sql="SELECT P.idpersona,P.tipo_doc,P.sexo,concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) findrisc_nombre,
					P.fecha_nacimiento findrisc_fechanacimiento,
					YEAR(CURDATE())-YEAR(P.fecha_nacimiento) findrisc_edad
					FROM person P 
					WHERE P.idpeople ='{$id[0]}'";
					// echo $sql;
					$info=datos_mysql($sql);
				return $info['responseResult'][0];
				}
			return $info['responseResult'][0];
		}
	}


function get_person(){
	// print_r($_POST);
	$id=divide($_POST['id']);
	$sql="SELECT idpersona,tipo_doc,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,sexo ,fecha_nacimiento,TIMESTAMPDIFF(YEAR,fecha_nacimiento, CURDATE()) edad
from person
WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."');";
	
	// return json_encode($sql);
	$info=datos_mysql($sql);
	if (!$info['responseResult']) {
		return json_encode (new stdClass);
	}
return json_encode($info['responseResult'][0]);
}

function focus_tamfindrisc(){
	return 'tamfindrisc';
   }
   
function men_tamfindrisc(){
	$rta=cap_menus('tamfindrisc','pro');
	return $rta;
   }

   function cap_menus($a,$b='cap',$con='con') {
	$rta = ""; 
	$acc=rol($a);
	if ($a=='tamfindrisc') {  
		$rta .= "<li class='icono $a  grabar' title='Grabar' Onclick=\"grabar('$a',this);\" ></li>";
	}
	return $rta;
  }
   
function gra_tamfindrisc(){
	$a=$_POST['edad'];
			switch (true) {
				case $a < 45 :
					$edad=0;
					break;
				case $a >= 45 && $a <=54 :
					$edad=2;
					break;
				case $a >=55 && $a <=64 :
					$edad=3;
					break;
				case $a > 64 :
					$edad=4;
					break;
				default:
				$edad='Edad Errada';
					break;
			}

		$b=$_POST['imc'];
			switch (true) {
				case $b < 25:
					$imc=0;
					break;
				case $b >24 && $b <31 :
					$imc=1;
					break;
				case $b > 30 :
					$imc=3;
					break;
				default:
				$edad='imc Errado';
					break;
			}
		
			$c=$_POST['sexo'];
			$d=$_POST['perimcint'];
			if($c=='H'){
				switch (true) {
				case $d < 94:
					$cint=0;
					break;
				case $d >93 :
					$cint=4;
					break;
				
				default:
					break;
			}
			}else{
				switch (true) {
				case $d < 90:
					$cint=0;
					break;
				case $d >89 :
					$cint=4;
					break;
				
				default:
					break;
			}
			} 

			$suma_findrisc = ($edad+$imc+$cint+$_POST['actifisica']+$_POST['verduras']+$_POST['hipertension']+$_POST['glicemia']+$_POST['diabfam']);

			switch ($suma_findrisc) {
				case ($suma_findrisc < 10):
					$des='RIESGO BAJO';
					break;
				case ($suma_findrisc >= 10 && $suma_findrisc <= 12):
					$des='RIESGO MODERADO';
					break;
				case ($suma_findrisc >= 13 ):
						$des='RIESGO ALTO';
					break;
					
				default:
					$des='Error en el rango, por favor valide';
					break;
			}
			$id=divide($_POST['id']);
			if(count($id)!==2){
				return "No es posible actualizar el tamizaje";
	}else{
		$id=$_POST['id'];
		$idp=datos_mysql("SELECT idpeople FROM person 
		WHERE idpersona = {$_POST['idpersona']} AND tipo_doc ='{$_POST['tipodoc']}'");//CAMBIO ADD linea
		if (isset($idp['responseResult'][0])){//CAMBIO ADD linea
			$idper = $idp['responseResult'][0]['idpeople'];//CAMBIO ADD linea
		}//CAMBIO ADD linea
		
			// echo "ES MENOR DE EDAD ".$ed.' '.print_r($_POST);

		$sql="INSERT INTO hog_tam_findrisc VALUES (null,
		trim(upper('{$_POST['tipodoc']}')),trim(upper('{$_POST['idpersona']}')),trim(upper('{$_POST['diabetes']}')),trim(upper('{$_POST['peso']}')),trim(upper('{$_POST['talla']}')),trim(upper('{$_POST['imc']}')),trim(upper('{$_POST['perimcint']}')),trim(upper('{$_POST['actifisica']}')),trim(upper('{$_POST['verduras']}')),trim(upper('{$_POST['hipertension']}')),trim(upper('{$_POST['glicemia']}')),trim(upper('{$_POST['diabfam']}')),
		'{$suma_findrisc}',
		trim(upper('{$des}')),
		TRIM(UPPER('{$_SESSION['us_sds']}')),
		DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
		// echo $sql;
		$rta=dato_mysql($sql);
		// print_r($_POST);
		// return 'TAMIZAJE NO APLICA PARA LA EDAD';
	}

  return $rta; 
}


	function opc_tipodoc($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}
	function opc_sexo($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
	}
	function opc_diabetes($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
	}
	function opc_actifisica($id=''){
		return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,' - ',descripcion) FROM `catadeta` WHERE idcatalogo=43 and estado='A' ORDER BY 1",$id);
	}
	function opc_verduras($id=''){
		return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,' - ',descripcion) FROM `catadeta` WHERE idcatalogo=46 and estado='A' ORDER BY 1",$id);
	}
	function opc_hipertension($id=''){
		return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,' - ',descripcion) FROM `catadeta` WHERE idcatalogo=56 and estado='A' ORDER BY 1",$id);
	}
	function opc_glicemia($id=''){
		return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,' - ',descripcion) FROM `catadeta` WHERE idcatalogo=57 and estado='A' ORDER BY 1",$id);
	}
	function opc_diabfam($id=''){
		return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,' - ',descripcion) FROM `catadeta` WHERE idcatalogo=41 and estado='A' ORDER BY 1",$id);
	}
	


	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
	   // $rta=iconv('UTF-8','ISO-8859-1',$rta);
	   // var_dump($a);
	   // var_dump($rta);
		   if ($a=='tamfindrisc' && $b=='acciones'){
			$rta="<nav class='menu right'>";																	//getDatForm('find','person','datos');setTimeout(hiddxedad,500,'edad','prufin');
				$rta.="<li title='Ver'><i class='fa-solid fa-eye ico' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getDataFetch,500,'find',,'person','datos',event,this,'../findrisc/lib.php',['puntaje','descripcion']);\"></i></li>";  //act_lista(f,this);setTimeout(hiddxedad,1000,'edad','prufin');
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
	