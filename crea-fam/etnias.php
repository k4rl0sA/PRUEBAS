0<?php
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
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	 
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
	$d='';
	$o='sesetn';
	$x='true';
	$bl='true';
	$ob='true';
	$days=fechas_app('etnias');
	$c[]=new cmp($o,'e',null,'SESIONES ETNIAS',$w);
	$c[]=new cmp('idsesetn','h',15,$_POST['id'],$w.' '.$o,'id','idg',null,'####',false,false);
	$c[]=new cmp('fecha','d','10',$d,$w.' '.$o,'Fecha Sesion','fecha',null,null,true,true,'','col-15',"validDate(this,$days,0);");
	$c[]=new cmp('sesi_nu','s','3',$d,$w.' '.$o,'Sesion N°','sesi_nu',null,null,true,true,'','col-35');
	$c[]=new cmp('moti_con','s','3',$d,$w.' '.$o,'Motivo Consulta','moti_con',null,null,true,true,'','col-5');
	$c[]=new cmp('des_sin','t','100',$d,$w.' '.$o,'Descripcion Sintoma','des_sin',null,null,true,true,'','col-10');

	$o='espvit';
	$c[]=new cmp($o,'e',null,'ESPACIO VITAL',$w);
	$c[]=new cmp('peso','sd','4',$d,$w.' '.$o,'Peso (Kg) (0.82 = 820 Gramos)','peso','rgxpeso','##.#',false,$x,'','col-2');
    $c[]=new cmp('talla','sd','5',$d,$w.' '.$o,'Talla (Cm) (75.2 =Cm,mm)','talla','rgxtalla','###.#',false,$x,'','col-2');
    $c[]=new cmp('zscore','t','20',$d,$w.' '.$bl.' '.$o,'Zscore','zscore',null,null,false,false,'','col-2');
    $c[]=new cmp('clasi_nutri','s','3',$d,$w.' '.$ob.' '.$o,'Clasificación Nutricional','clasi_nutri',null,null,false,false,'','col-2');
	$c[]=new cmp('peri_cef','sd','4',$d,$w.' '.$o,'Perimetro Cefalico','peri_cef','rgxpeso','##.#',false,$x,'','col-2');
    $c[]=new cmp('peri_bra','sd','5',$d,$w.' '.$o,'Perimetro Braquial','peri_bra','rgxtalla','###.#',false,$x,'','col-2');
	$c[]=new cmp('frec_res','sd','4',$d,$w.' '.$o,'Frecuencia Respiratoria','frec_res','rgxpeso','##.#',false,$x,'','col-2');
    $c[]=new cmp('frec_car','sd','5',$d,$w.' '.$o,'Frecuencia Cardiaca','frec_car','rgxtalla','###.#',false,$x,'','col-2');
	$c[]=new cmp('oxige','sd','5',$d,$w.' '.$o,'Oxigeno','oxige','rgxtalla','###.#',false,$x,'','col-2');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}




	function gra_etnias(){
		// print_r($_POST);
		$id=divide($_POST['idsesetn']);
		$sql = "INSERT INTO variable VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,DATE_SUB(NOW(),INTERVAL 5 HOUR),?,?,?)";
		$params =[
		['type' => 'i', 'value' => NULL],
		['type' => 'i', 'value' => $id[0]],
		['type' => 'i', 'value' => $_POST['fecha']],
		['type' => 's', 'value' => $_POST['sesi_nu']],
		['type' => 's', 'value' => $_POST['moti_con']],
		['type' => 's', 'value' => $_POST['des_sin']],
		['type' => 's', 'value' => $_POST['peso']],
		['type' => 's', 'value' => $_POST['talla']],
		['type' => 's', 'value' => $_POST['zscore']],
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
		if($_REQUEST['id']!=''){
					$id=divide($_REQUEST['id']);
					$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='238' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
					$info=datos_mysql($sql);
					return json_encode($info['responseResult']);
			}
	}
