<?php
ini_set('display_errors','1');
require_once "../libs/gestion.php";
$perf=perfil($_POST['tb']);
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

function focus_etnias(){
	return 'etnias';
   }
   
   function men_etnias(){
	$rta=cap_menus('etnias','pro');
	return $rta;
}
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 $acc=rol($a);
	   if ($a=='etnias'  && isset($acc['crear']) && $acc['crear']=='SI'){  
	 $rta .= "<li class='icono $a grabar' title='Grabar' OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	   }
  return $rta;
}

FUNCTION lis_etnias(){
	// var_dump($_POST['id']);
	$id=divide($_POST['id']);
	$sql="SELECT `idamb` ACCIONES,idamb 'Cod Registro',`fecha`,FN_CATALOGODESC(34,tipo_activi) Tipo,`nombre` Creó,`fecha_create` 'fecha Creó'
	FROM hog_amb A
	LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario ";
	$sql.="WHERE idvivamb='".$id[0];
	$sql.="' ORDER BY fecha_create";
	// echo $sql;
	$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"etnias-lis",5);
   }

function cmp_etnias(){
	$rta="<div class='encabezado etnias'>TABLA ETNIAS</div>
	<div class='contenido' id='etnias-lis'>".lis_etnias()."</div></div>";
	$hoy=date('Y-m-d');
	$w='etnias';
	$t=['nombre'=>'','sexo'=>'','edad'=>'','fechanacimiento'=>''];
	$p=get_person();
	if ($p=="") {$p=$t;}
	$d='';
	$o='sesetn';
	$z='zS';
	$days=fechas_app('etnias');
	$o='infusu';
	$c[]=new cmp($o,'e',null,'INFORMACION USUARIO',$w); 
	$c[]=new cmp('nombre','t','80',$p['nombre'],$w.' '.$o,'Nombre','idpersona',null,'',true,false,'','col-4');
	$c[]=new cmp('sexo','t','50',$p['sexo'],$w.' '.$o,'sexo','sexo',null,'',false,false,'','col-15');
	$c[]=new cmp('edad','t','50',$p['edad'],$w.' '.$o,'edad','edad',null,'',false,false,'','col-25');
	$c[]=new cmp('fechanacimiento','d','10',$p['fecha_nacimiento'],$w.' '.$o,'fecha nacimiento','fechanacimiento',null,'',true,false,'','col-2');
    
	$c[]=new cmp($o,'e',null,'SESIONES ETNIAS',$w);
	$c[]=new cmp('idsesetn','h',15,$_POST['id'],$w.' '.$o,'id','idg',null,'####',false,false);
	$c[]=new cmp('fecha','d','10',$d,$w.' '.$o,'Fecha Sesion','fecha',null,null,true,true,'','col-15',"validDate(this,$days,0);");
	$c[]=new cmp('sesi_nu','s','3',$d,$w.' '.$o,'Sesion N°','sesi_nu',null,null,true,true,'','col-35');
	$c[]=new cmp('moti_con','s','3',$d,$w.' '.$o,'Motivo Consulta','moti_con',null,null,true,true,'','col-5');
	$c[]=new cmp('des_sin','t','100',$d,$w.' '.$o,'Descripcion Sintoma','des_sin',null,null,true,true,'','col-10');

	$o='espvit';
	$c[]=new cmp($o,'e',null,'ESPACIO VITAL',$w);
	$c[]=new cmp('peso','sd',6, $d,$w.' '.$z.' '.$o,'Peso (Kg) Mín=0.50 - Máx=150.00','fpe','rgxpeso','###.##',true,true,'','col-2',"valPeso('peso');Zsco('zscore','etnias.php');calImc('peso','talla','imc');");
	$c[]=new cmp('talla','sd',5, $d,$w.' '.$z.' '.$o,'Talla (Cm) Mín=20 - Máx=210','fta','rgxtalla','###.#',true,true,'','col-2',"calImc('peso','talla','imc');Zsco('zscore','etnias.php');valTalla('talla');");
	$c[]=new cmp('imc','t',6, $d,$w.' '.$o,'IMC','imc','','',false,false,'','col-1');
	$c[]=new cmp('zscore','t',15,'',$w.' '.$o,'Z-score','des',null,null,false,false,'','col-35');
	$c[]=new cmp('clasi_nutri','s','3',$d,$w.' '.$o,'Clasificación Nutricional','clasi_nutri',null,null,true,true,'','col-2');
	$c[]=new cmp('peri_cef','sd','4',$d,$w.' '.$o,'Perimetro Cefalico','peri_cef','rgxpeso','##.#',false,true,'','col-2');
    $c[]=new cmp('peri_bra','sd','5',$d,$w.' '.$o,'Perimetro Braquial','peri_bra','rgxtalla','###.#',false,true,'','col-2');
	$c[]=new cmp('frec_res','sd','4',$d,$w.' '.$o,'Frecuencia Respiratoria','frec_res','rgxpeso','##.#',false,true,'','col-2');
    $c[]=new cmp('frec_car','sd','5',$d,$w.' '.$o,'Frecuencia Cardiaca','frec_car','rgxtalla','###.#',false,true,'','col-2');
	$c[]=new cmp('oxige','sd','5',$d,$w.' '.$o,'Oxigeno','oxige','rgxtalla','###.#',false,true,'','col-2');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function get_person(){
	// var_dump($_POST);
  $id=divide($_POST['id']);
    $sql="SELECT CONCAT_WS(' ',p.nombre1, p.apellido1) nombre,p.sexo,
	CONCAT('AÑOS: ',TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()),' MESES: ',
    TIMESTAMPDIFF(MONTH, fecha_nacimiento, CURDATE())- (TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) *12),' DIAS: ',
    DATEDIFF(CURDATE(),DATE_ADD(fecha_nacimiento, INTERVAL TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) YEAR)) %30) edad,p.fecha_nacimiento 
FROM person p 
    WHERE p.idpeople='".$id[0]."'";
      $info=datos_mysql($sql);
      return $info['responseResult'][0];
  }


  function get_zscore(){
	// var_dump($_POST);
	$id=divide($_POST['val']);
	 $fechaNacimiento = new DateTime($id[1]);
	 $fechaActual = new DateTime();
	 $diferencia = $fechaNacimiento->diff($fechaActual);
	 $edadEnDias = $diferencia->days;
	$ind = ($edadEnDias<=730) ? 'PL' : 'PT' ;
	$sex=$id[2];

$sql="SELECT (POWER(($id[0] / (SELECT M FROM tabla_zscore WHERE indicador = '$ind' AND sexo = '$sex[0]' AND edad_dias = $id[3])),
	(SELECT L FROM tabla_zscore WHERE indicador = '$ind' AND sexo = '$sex[0]' AND edad_dias = $id[3])) - 1) / 
	((SELECT L FROM tabla_zscore WHERE indicador = '$ind' AND sexo = '$sex[0]' AND edad_dias = $id[3]) *
 (SELECT S FROM tabla_zscore WHERE indicador = '$ind' AND sexo = '$sex[0]' AND edad_dias = $id[3])) as rta ";
//   echo $sql;
 $info=datos_mysql($sql);
	 if (!$info['responseResult']) {
		return '';
	}else{
		$z=number_format((float)$info['responseResult'][0]['rta'], 6, '.', '');
		switch ($z) {
			case ($z <=-3):
				$des='DESNUTRICIÓN AGUDA SEVERA';
				break;
			case ($z >-3 && $z <=-2):
				$des='DESNUTRICIÓN AGUDA MODERADA';
				break;
			case ($z >-2 && $z <=-1):
				$des='RIESGO DESNUTRICIÓN AGUDA';
				break;
			case ($z>-1 && $z <=1):
					$des='PESO ADECUADO PARA LA TALLA';
				break;
			case ($z >1 && $z <=2):
					$des='RIESGO DE SOBREPESO';
				break;
			case ($z >2 && $z <=3):
					$des='SOBREPESO';
				break;
				case ($z >3):
					$des='OBESIDAD';
				break;
			default:
				$des='Error en el rango, por favor valide';
				break;
		}
		return json_encode($z." = ".$des);
	}
}

	function gra_etnias(){
		print_r($_POST);
		$id=divide($_POST['idsesetn']);
		$zsco=explode("=",$_POST['zscore']?? null);
		$z1=$zsco[0]??null;
		$z2=$zsco[1]??null;
		$sql = "INSERT INTO hog_etnia VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,DATE_SUB(NOW(),INTERVAL 5 HOUR),?,?,?)";
		$params =[
		['type' => 'i', 'value' => NULL],
		['type' => 'i', 'value' => $id[0]],
		['type' => 's', 'value' => $_POST['fecha']],
		['type' => 's', 'value' => $_POST['sesi_nu']],
		['type' => 's', 'value' => $_POST['moti_con']],
		['type' => 's', 'value' => $_POST['des_sin']],
		['type' => 's', 'value' => $_POST['peso']],
		['type' => 's', 'value' => $_POST['talla']],
		['type' => 's', 'value' => $_POST['imc']],
		['type' => 's', 'value' => $z1],
		['type' => 's', 'value' => $z1],
		['type' => 's', 'value' => $_POST['clasi_nutri']],
		['type' => 's', 'value' => $_POST['peri_cef']],
		['type' => 's', 'value' => $_POST['peri_bra']],
		['type' => 's', 'value' => $_POST['frec_res']],
		['type' => 's', 'value' => $_POST['frec_car']],
		['type' => 's', 'value' => $_POST['oxige']],
		['type' => 'i', 'value' => $_SESSION['us_sds']],
		['type' => 's', 'value' => NULL],
		['type' => 's', 'value' => NULL],
		['type' => 's', 'value' => 'A']
		];
		return  $rta= mysql_prepd($sql, $params);
	}

	function get_etnias(){
		// var_dump($_POST);
		if($_REQUEST['id']==''){
			return "";
		  }else{
			$id=divide($_REQUEST['id']);
			$sql="SELECT concat_ws('_',idamb,idvivamb) idamb,fecha,tipo_activi,seguro,grietas,combustible,separadas,lena,ilumina,fuma,bano,cocina,elevado,electrica,elementos,barreras,zontrabajo,agua,tanques,adecagua,raciagua,sanitari,aguaresid,terraza,recipientes,vivaseada,separesiduos,reutresiduos,noresiduos,adecresiduos,horaresiduos,plagas,contplagas,pracsanitar,envaplaguicid,consealiment,limpcocina,cuidcuerpo,fechvencim,limputensilios,adqualime,almaquimicos,etiqprodu,juguetes,medicamalma,medicvenc,adqumedicam,medidaspp,radiacion,contamaire,monoxido,residelectri,duermeelectri,vacunasmascot,aseamascot,alojmascot,excrmascot,permmascot,salumascot,pilas,dispmedicamentos,dispcompu,dispplamo,dispbombill,displlanta,dispplaguic,dispaceite
			FROM hog_amb			
			WHERE idamb ='{$id[0]}'";
			// var_dump($sql);
			$info=datos_mysql($sql);
			return json_encode($info['responseResult'][0]);
		  } 
	}

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
		if ($a=='etnias-lis' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'etnias',event,this,['fecha','tipo_activi'],'amb.php');\"></li>";  //   act_lista(f,this);
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	 }	
	 
	function opc_sesi_nu($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=76 and estado='A' ORDER BY 1",$id);
	}

	function opc_moti_con($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}

	function opc_des_sin($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}


	function opc_clasi_nutri($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=210 and estado='A' ORDER BY 1",$id);
	}
