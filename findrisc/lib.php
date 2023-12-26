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
	LEFT JOIN personas P ON O.idpersona = P.idpersona
		LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
		LEFT JOIN hog_geo G ON V.idpre = G.idgeo 
		LEFT JOIN usuarios U ON O.usu_creo=id_usuario
	 where ".whe_tamfindrisc());
	$total=$info['responseResult'][0]['total'];
	$regxPag=12;
	$pag=(isset($_POST['pag-tamfindrisc']))? ($_POST['pag-tamfindrisc']-1)* $regxPag:0;
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(O.idpersona,'_',O.tipodoc) ACCIONES,id_findrisc 'Cod registro',O.idpersona Documento,FN_CATALOGODESC(1,O.tipodoc) 'Tipo de Documento',CONCAT_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,`puntaje` Puntaje,`descripcion` descripcion, U.nombre Creo,U.perfil perfil 
	FROM hog_tam_findrisc O 
		LEFT JOIN personas P ON O.idpersona = P.idpersona
		LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
		LEFT JOIN hog_geo G ON V.idpre = G.idgeo 
		LEFT JOIN usuarios U ON O.usu_creo=id_usuario
	WHERE ";
	$sql.=whe_tamfindrisc();
	$sql.=" ORDER BY O.fecha_create DESC";
	echo $sql;
	$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"tamfindrisc",$regxPag);
	
}

function whe_tamfindrisc() {
	$fefin=date('Y-m-d');
	$feini=date('Y-m-d',strtotime($fefin.'- 4 days')); 
	$sql = " G.subred=(SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	if ($_POST['fidentificacion']){
		$sql .= " AND O.idpersona = '".$_POST['fidentificacion']."'";
	}else{
		$sql.=" AND DATE(O.fecha_create) BETWEEN '$feini' and '$fefin'"; 
	}
		
	return $sql;
}

function cmp_tamfindrisc(){
	$rta="";
	$t=['id_findrisc'=>'','tipodoc'=>'','idpersona'=>'','nombre'=>'','fechanacimiento'=>'','edad'=>'',
	'sexo'=>'','diabetes'=>'','peso'=>'','talla'=>'','imc'=>'','perimcint'=>'','actifisica'=>'','verduras'=>'','hipertension'=>'','glicemia'=>'','diabfam'=>'','puntaje'=>'','descripcion'=>'']; 
	$w='tamfindrisc';
	$d=get_tamfindrisc(); 
	if ($d=="") {$d=$t;}
	$u = ($d['id_findrisc']!='') ? false : true ;
	$o='datos';
    $key='find';
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('id','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('idpersona','t','20',$d['idpersona'],$w.' '.$o.' '.$key,'N° Identificación','idpersona',null,'',false,$u,'','col-3');
	$c[]=new cmp('tipodoc','s','3',$d['tipodoc'],$w.' '.$o.' '.$key,'Tipo Identificación','tipodoc',null,'',false,$u,'','col-3',"getDatForm('find','person','datos');setTimeout(hiddxedad,500,'edad','prufin');");//setTimeout(hiddxedad,1000,\'edad\',\'find\');
	$c[]=new cmp('nombre','t','50',$d['nombre'],$w.' '.$o,'nombres','nombre',null,'',false,false,'','col-4');
	$c[]=new cmp('sexo','s','3',$d['sexo'],$w.' '.$o,'Sexo','sexo',null,'',false,false,'','col-2');
	$c[]=new cmp('fechanacimiento','d','10',$d['fechanacimiento'],$w.' '.$o,'fecha nacimiento','fechanacimiento',null,'',false,false,'','col-3');
    $c[]=new cmp('edad','n','3',$d['edad'],$w.' '.$o,'edad en Años','edad',null,'',true,false,'','col-2');
	$c[]=new cmp('diabetes','s',3,$d['diabetes'],$w.' '.$o,'Tiene Diabetes','diabetes',null,null,false,true,'','col-3',"setTimeout(hiddxdiab,500,'diabetes','prufin');");

	$o='prufin oculto';
 	$c[]=new cmp($o,'e',null,'PRUEBA FINDRISC',$w);
 	$c[]=new cmp('peso','t',6,$d['peso'],$w.' '.$o,'Peso (Kg) Mínimo=0.50 (Kg) - Máximo=150.00 (Kg)','peso','rgxpeso','###.##',true,true,'','col-25');
 	$c[]=new cmp('talla','n',3,$d['talla'],$w.' '.$o,'Talla (Cm) Mínimo=120 (Cm) - Máximo=210 (Cm)','talla','rgxtalla',null,false,true,'','col-25',"calImc('peso',this,'imc');");
	$c[]=new cmp('imc','t',6, $d['imc'],$w,'IMC','imc',null,null,false,false,'','col-2');
	$c[]=new cmp('perimcint','n',3,$d['perimcint'],$w.' '.$o,'Perimetro de cintura (Cm) Mínimo=50 (Cm) - Máximo=210 (Cm)','perimcint','rgxperabd',null,false,true,'','col-3');
 	$c[]=new cmp('actifisica','s',3,$d['actifisica'],$w.' '.$o,'Hace habitualmente (a diario) al menos 30 minutos de actividad física en el trabajo o durante su tiempo libre?','actifisica',null,null,false,true,'','col-5');
 	$c[]=new cmp('verduras','s',3,$d['verduras'],$w.' '.$o,'Come verduras o frutas Todos los dias ?','verduras',null,null,false,true,'','col-2');
 	$c[]=new cmp('hipertension','s',3,$d['hipertension'],$w.' '.$o,'Toma regularmente medicación para la hipertensión ?','hipertension',null,null,false,true,'','col-3');
 	$c[]=new cmp('glicemia','s',3,$d['glicemia'],$w.' '.$o,'Le han encontrado alguna vez valores de glucosa altos ?','glicemia','rgxgluco',null,false,true,'','col-5');
 	$c[]=new cmp('diabfam','s',3,$d['diabfam'],$w.' '.$o,'Se le ha diagnosticado diabetes (tipo 1 o tipo 2) a alguno de sus familiares ?','diabfam',null,null,false,true,'','col-5');

	$o='totalresul';
	$c[]=new cmp($o,'e',null,'TOTAL',$w);
	$c[]=new cmp('puntaje','t','2',$d['puntaje'],$w.' '.$o,'Puntaje','puntaje',null,null,false,false,'','col-5');
	$c[]=new cmp('descripcion','t','3',$d['descripcion'],$w.' '.$o,'Descripcion','descripcion',null,null,false,false,'','col-5');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	
	return $rta;
   }

   function get_tamfindrisc(){
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		// print_r($_POST);
		$sql="SELECT `id_findrisc`,O.`idpersona`,O.`tipodoc`,diabetes,
		peso,talla,imc,perimcint,actifisica,verduras,hipertension,glicemia,diabfam,puntaje,descripcion,
        O.estado,P.idpersona,P.tipo_doc,concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) nombre,sexo,P.fecha_nacimiento fechanacimiento,YEAR(CURDATE())-YEAR(P.fecha_nacimiento) edad
		FROM `hog_tam_findrisc` O
		LEFT JOIN personas P ON O.idpersona = P.idpersona and O.tipodoc=P.tipo_doc
		WHERE O.idpersona ='{$id[0]}' AND O.tipodoc='{$id[1]}'";
		// echo $sql;
		$info=datos_mysql($sql);
				return $info['responseResult'][0];
		}
	} 


function get_person(){
	// print_r($_POST);
	$id=divide($_POST['id']);
$sql="SELECT idpersona,tipo_doc,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,sexo ,fecha_nacimiento,YEAR(CURDATE())-YEAR(fecha_nacimiento) edad
from personas
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

	if($_POST['id']==0){
		$id=$_POST['id'];
		
		
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
	}else{
		$id=divide($_POST['id']);
		$sql="UPDATE hog_tam_findrisc SET  
		diabetes=trim(upper('{$_POST['diabetes']}')),peso=trim(upper('{$_POST['peso']}')),talla=trim(upper('{$_POST['talla']}')),imc=trim(upper('{$_POST['imc']}')),perimcint=trim(upper('{$_POST['perimcint']}')),actifisica=trim(upper('{$_POST['actifisica']}')),verduras=trim(upper('{$_POST['verduras']}')),hipertension=trim(upper('{$_POST['hipertension']}')),glicemia=trim(upper('{$_POST['glicemia']}')),diabfam=trim(upper('{$_POST['diabfam']}')),
		puntaje=trim(upper('{$suma_findrisc}')),descripcion=trim(upper('{$des}')),
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
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('tamfindrisc','pro',event,'','lib.php',7,'tamfindrisc');setTimeout(hiddxedad,1000,'edad','prufin');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
	