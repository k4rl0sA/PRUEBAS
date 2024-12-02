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


function lis_tamzarit(){
	// concat(zarit_idpersona,'_',zarit_tipodoc,'_',zarit_momento) ACCIONES,
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(zarit_idpersona,'_',zarit_tipodoc,'_',zarit_momento) ACCIONES,tam_zarit 'Cod Registro',zarit_idpersona Documento,FN_CATALOGODESC(1,zarit_tipodoc) 'Tipo de Documento',CONCAT_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres, 
	FN_CATALOGODESC(21,P.sexo) Sexo,FN_CATALOGODESC(116,zarit_momento) Momento,`zarit_puntaje` Puntaje 
FROM hog_tam_zarit O
LEFT JOIN personas P ON O.zarit_idpersona = P.idpersona
		WHERE '1'='1'";
	$sql.=whe_tamzarit();
	$sql.=" ORDER BY 1";

	 $sql1="SELECT * 
	  FROM `hog_tam_zarit` WHERE 1";
	$sql1.=whe_tamzarit();	
	//echo $sql;
		$_SESSION['sql_tamzarit']=$sql1;
		$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"tamzarit",20);
}

function whe_tamzarit() {
	$sql = "";
	if ($_POST['fidentificacion'])
		$sql .= " AND zarit_idpersona like '%".$_POST['fidentificacion']."%'";
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

function cmp_tamzarit(){
	$rta="";
	$t=['tam_zarit'=>'','zarit_tipodoc'=>'','zarit_nombre'=>'','zarit_idpersona'=>'','zarit_fechanacimiento'=>'','zarit_puntaje'=>'','zarit_momento'=>'','zarit_edad'=>'','zarit_rutina'=>'','zarit_rol'=>'',	 'zarit_actividad'=>'','zarit_evento'=>'','zarit_comportamiento'=>'','zarit_valor21'=>'','zarit_valor22'=>'','zarit_analisis'=>'','zarit_puntaje'=>'']; 
	$w='tamzarit';
	$d=get_tamzarit(); 
	if ($d=="") {$d=$t;}
	$u = ($d['tam_zarit']!='') ? false : true ;
	$o='datos';
    $key='srch';
	$days=fechas_app('PSICOLOGIA');
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('idzarit','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('zarit_idpersona','n','20',$d['zarit_idpersona'],$w.' '.$o.' '.$key,'N° Identificación','zarit_idpersona',null,'',false,$u,'','col-2');
	$c[]=new cmp('zarit_tipodoc','s','3',$d['zarit_tipodoc'],$w.' '.$o.' '.$key,'Tipo Identificación','zarit_tipodoc',null,'',false,$u,'','col-25','getDatForm(\'srch\',\'person\',[\'datos\']);');
	$c[]=new cmp('zarit_nombre','t','50',$d['zarit_nombre'],$w.' '.$o,'nombres','zarit_nombre',null,'',false,false,'','col-4');
	$c[]=new cmp('zarit_fechanacimiento','d','10',$d['zarit_fechanacimiento'],$w.' '.$o,'fecha nacimiento','zarit_fechanacimiento',null,'',false,false,'','col-15');
    $c[]=new cmp('zarit_edad','n','3',$d['zarit_edad'],$w.' '.$o,'edad','zarit_edad',null,'',true,false,'','col-1');
	$c[]=new cmp('fecha_toma','d','10','',$w.' '.$o,'fecha de la Toma','fecha_toma',null,'',true,true,'','col-2',"validDate(this,$days,0);");
    
	$o='actv';
	$c[]=new cmp($o,'e',null,'Valoración',$w);
	$c[]=new cmp('valor1','s',3,'',$w.' '.$o,'Piensa que su familiar / persona cuidada le pide más ayuda de la realmente necesita','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor2','s',3,'',$w.' '.$o,'Piensa que debido al tiempo que dedica a su familiar no tiene suficiente tiempo para usted','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor3','s',3,'',$w.' '.$o,'Se siente agobiado por intentar compatibilizar el cuidado de su familiar / persona cuidada con otras responsabilidades (trabajo, familia)','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor4','s',3,'',$w.' '.$o,'Siente vergüenza por la conducta de su familiar / persona cuidada','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor5','s',3,'',$w.' '.$o,'Se siente enfadado cuando está cerca de su familiar/ persona cuidada','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor6','s',3,'',$w.' '.$o,'Piensa que el cuidar de su familiar / persona cuidada afecta negativamente la relación que usted tiene con otros miembros de su familia','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor7','s',3,'',$w.' '.$o,'Tiene miedo por el futuro de su familiar / persona cuidada','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor8','s',3,'',$w.' '.$o,'Piensa que su familiar / persona cuidada depende de usted','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor9','s',3,'',$w.' '.$o,'Se siente tenso cuando está cerca de su familiar','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor10','s',3,'',$w.' '.$o,'Piensa que su salud ha empeorado debido a tener que cuidar de su familiar / persona cuidada','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor11','s',3,'',$w.' '.$o,'Piensa que no tiene tanta intimidad como le gustaría debido a tener que cuidar de su familiar / persona cuidada','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor12','s',3,'',$w.' '.$o,'Piensa que su vida social se ha visto afectada negativamente por tener que cuidar de su familiar / persona cuidada','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor13','s',3,'',$w.' '.$o,'Se siente incómodo por distanciarse de sus amistades debido a tener que cuidar de su familiar / persona cuidada','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor14','s',3,'',$w.' '.$o,'Piensa que su familiar le considera a Usted  la única persona que le puede cuidar','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor15','s',3,'',$w.' '.$o,'Piensa que no tiene suficientes ingresos económicos para los gastos de cuidar a su familiar / persona cuidada, además de sus otros gastos','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor16','s',3,'',$w.' '.$o,'Piensa que no será capaz de cuidar a su familiar / persona cuidada por mucho más tiempo','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor17','s',3,'',$w.' '.$o,'Siente que ha perdido el control de su vida desde que comenzó la enfermedad de su familiar / persona cuidada','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor18','s',3,'',$w.' '.$o,'Desearía poder dejar el cuidado de su familiar / persona cuidada a otra persona','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor19','s',3,'',$w.' '.$o,'Se siente indeciso sobre qué hacer con su familiar / persona cuidada','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor20','s',3,'',$w.' '.$o,'Piensa que debería hacer más por su familiar / persona cuidada','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor21','s',3,'',$w.' '.$o,'Piensa que podría cuidar mejor a su familiar / persona cuidada','valoracion',null,null,true,$u,'','col-10');
	$c[]=new cmp('valor22','s',3,'',$w.' '.$o,'En general, se siente "cargado" por el hecho de cuidar a su familiar / persona cuidada (grado de carga)','valoracion',null,null,true,$u,'','col-10');

	$o='totales';
	$c[]=new cmp($o,'e',null,'Resultado ',$w);
	$c[]=new cmp('puntaje','t',3,'',$w.' '.$o,'Total','zarit_puntaje',null,'',false,false,'','col-3');
	$c[]=new cmp('momento','t',20,'',$w.' '.$o,'Momento','zarit_momento',null,'',false,false,'','col-3');
	$c[]=new cmp('analisis','t',3,'',$w.' '.$o,'Analisis','zarit_analisis',null,'',false,false,'','col-4');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	
	return $rta;
   }

  /*  function get_tamzarit(){
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		// print_r($_POST);
		$sql="SELECT `tam_zarit`,`zarit_idpersona`,`zarit_tipodoc`,
		FN_CATALOGODESC(116,zarit_momento) zarit_momento,`zarit_valor1`,`zarit_valor2`,`zarit_valor3`, 
		`zarit_valor4`,`zarit_valor5`,`zarit_valor6`,
		`zarit_valor7`,`zarit_valor8`,`zarit_valor9`,
		`zarit_valor10`,`zarit_valor11`,`zarit_valor12`,
		`zarit_valor13`,`zarit_valor14`,`zarit_valor15`,
		`zarit_valor16`,`zarit_valor17`,`zarit_valor18`,
		`zarit_valor19`,`zarit_valor20`,`zarit_valor21`,`zarit_puntaje`,`zarit_analisis`,
		`zarit_valor22`,O.estado,P.idpersona,P.tipo_doc,concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) zarit_nombre,P.fecha_nacimiento zarit_fechanacimiento,YEAR(CURDATE())-YEAR(P.fecha_nacimiento) zarit_edad
		FROM `hog_tam_zarit` O
		LEFT JOIN personas P ON O.zarit_idpersona = P.idpersona and O.zarit_tipodoc=P.tipo_doc
	
		WHERE zarit_idpersona ='{$id[0]}' AND zarit_tipodoc='{$id[1]}' AND zarit_momento = '{$id[2]}'  ";
		// echo $sql;
		$info=datos_mysql($sql);
				return $info['responseResult'][0];
		}
	}  */

	function get_tamzarit(){
		if($_POST['id']==0){
			return "";
		}else{
			 $id=divide($_POST['id']);
			// print_r($_POST);
			$sql="SELECT P.idpeople,P.idpersona zung_idpersona,P.tipo_doc zung_tipodoc,
			concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) zung_nombre,P.fecha_nacimiento zung_fechanacimiento,
			TIMESTAMPDIFF(YEAR, P.fecha_nacimiento, CURDATE()) AS zung_edad
			FROM person P
			WHERE P.idpeople ='{$id[0]}'";
			// echo $sql; 
			$info=datos_mysql($sql);
					return $info['responseResult'][0];
			}
		} 

/* function get_person(){
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
} */

function focus_tamzarit(){
	return 'tamzarit';
   }
   
function men_tamzarit(){
	$rta=cap_menus('tamzarit','pro');
	return $rta;
   }

   function cap_menus($a,$b='cap',$con='con') {
	$rta = ""; 
	$acc=rol($a);
	if ($a=='tamzarit') {  
		$rta .= "<li class='icono $a  grabar' title='Grabar' Onclick=\"grabar('$a',this);\" ></li>";
		
	}
	return $rta;
  }
   
function gra_tamzarit(){
	$id=$_POST['idzarit'];
	//print_r($_POST);
	if($id != "0"){
		return "No es posible actualizar el tamizaje";
	}else{

	$infodata_zarit=datos_mysql("SELECT zarit_momento,zarit_idpersona FROM hog_tam_zarit
		 WHERE zarit_idpersona = '{$_POST['zarit_idpersona']}' AND zarit_momento = 2 ");
	if (isset($infodata_zarit['responseResult'][0])){
		return "Ya se realizo los dos momentos";
	}else{
		$infodata2_zarit=datos_mysql("SELECT zarit_momento,zarit_idpersona FROM hog_tam_zarit
		 WHERE zarit_idpersona = '{$_POST['zarit_idpersona']}' AND zarit_momento = 1 ");
		if (isset($infodata2_zarit['responseResult'][0])){
			$idmomento = 2;
		}else{
			$idmomento = 1;
		}
	}

	
		$suma_zarit = (
			$_POST['zarit_valor1']+
			$_POST['zarit_valor2']+
			$_POST['zarit_valor3']+
			$_POST['zarit_valor4']+
			$_POST['zarit_valor5']+
			$_POST['zarit_valor6']+
			$_POST['zarit_valor7']+
			$_POST['zarit_valor8']+
			$_POST['zarit_valor9']+
			$_POST['zarit_valor10']+
			$_POST['zarit_valor11']+
			$_POST['zarit_valor12']+
			$_POST['zarit_valor13']+
			$_POST['zarit_valor14']+
			$_POST['zarit_valor15']+
			$_POST['zarit_valor16']+
			$_POST['zarit_valor17']+
			$_POST['zarit_valor18']+
			$_POST['zarit_valor19']+
			$_POST['zarit_valor20']+
			$_POST['zarit_valor21']+
			$_POST['zarit_valor22']
		);

		if($suma_zarit <= 47){
			$escala_zarit = 'No hay sobrecarga';
		}else if($suma_zarit >= 47 && $suma_zarit <= 55){
			$escala_zarit = 'Hay sobrecarga leve';
		}else{
			$escala_zarit = 'Sobrecarga intensa';
		}

		$sql="INSERT INTO hog_tam_zarit VALUES (null,
		TRIM(UPPER('{$_POST['zarit_idpersona']}')),
		{$idmomento},
		TRIM(UPPER('{$_POST['zarit_tipodoc']}')),
		TRIM(UPPER('{$_POST['zarit_valor1']}')),
		TRIM(UPPER('{$_POST['zarit_valor2']}')),
		TRIM(UPPER('{$_POST['zarit_valor3']}')),
		TRIM(UPPER('{$_POST['zarit_valor4']}')),
		TRIM(UPPER('{$_POST['zarit_valor5']}')),
		TRIM(UPPER('{$_POST['zarit_valor6']}')),
		TRIM(UPPER('{$_POST['zarit_valor7']}')),
		TRIM(UPPER('{$_POST['zarit_valor8']}')),
		TRIM(UPPER('{$_POST['zarit_valor9']}')),
		TRIM(UPPER('{$_POST['zarit_valor10']}')),
		TRIM(UPPER('{$_POST['zarit_valor11']}')),
		TRIM(UPPER('{$_POST['zarit_valor12']}')),
		TRIM(UPPER('{$_POST['zarit_valor13']}')),
		TRIM(UPPER('{$_POST['zarit_valor14']}')),
		TRIM(UPPER('{$_POST['zarit_valor15']}')),
		TRIM(UPPER('{$_POST['zarit_valor16']}')),
		TRIM(UPPER('{$_POST['zarit_valor17']}')),
		TRIM(UPPER('{$_POST['zarit_valor18']}')),
		TRIM(UPPER('{$_POST['zarit_valor19']}')),
		TRIM(UPPER('{$_POST['zarit_valor20']}')),
		TRIM(UPPER('{$_POST['zarit_valor21']}')),
		TRIM(UPPER('{$_POST['zarit_valor22']}')),
		'{$escala_zarit}',
		'{$suma_zarit}',
		TRIM(UPPER('{$_SESSION['us_sds']}')),
		DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
		// echo $sql;
	}
	  $rta=dato_mysql($sql);
	  return $rta; 
	}


	function opc_zarit_tipodoc($id=''){
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

	function opc_valoracion($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=118 and estado='A'  ORDER BY 1 ",$id);
	}

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
	   // $rta=iconv('UTF-8','ISO-8859-1',$rta);
	   // var_dump($a);
	   // var_dump($rta);
		   if ($a=='tamzarit' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('tamzarit','pro',event,'','lib.php',7,'tamzarit');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
	