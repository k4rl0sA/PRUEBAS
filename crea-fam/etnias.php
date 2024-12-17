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
	$days=fechas_app('etnias');
	$c[]=new cmp($o,'e',null,'SESIONES ETNIAS',$w);
	$c[]=new cmp('idsesetn','h',15,$_POST['id'],$w.' '.$o,'id','idg',null,'####',false,false);
	$c[]=new cmp('fecha','d','10',$d,$w.' '.$o,'Fecha Sesion','fecha',null,null,true,true,'','col-15',"validDate(this,$days,0);");
	$c[]=new cmp('sesi_nu','s','3',$d,$w.' '.$o,'Sesion N°','sesi_nu',null,null,true,true,'','col-35');
	$c[]=new cmp('moti_con','s','3',$d,$w.' '.$o,'Motivo Consulta','moti_con',null,null,true,true,'','col-5');
	$c[]=new cmp('des_sin','t','100',$d,$w.' '.$o,'Descripcion Sintoma','des_sin',null,null,true,true,'','col-10');

	$o='espvit';
	$c[]=new cmp($o,'e',null,'ESPACIO VITAL',$w);
	$c[]=new cmp('seguro','s','3',$d,$w.' '.$o,'Vivienda en un lugar seguro (sin: remoción en masa, inundaciones - ronda hídrica, avalanchas)','seguro',null,null,true,true,'','col-10');
	$c[]=new cmp('peso','sd','4',$d,$w.' '.$o,'Peso (Kg) (0.82 = 820 Gramos)','peso','rgxpeso','##.#',false,$x,'','col-2',"Zsco('zscore','../vsp/dntsevymod.php');");
    $c[]=new cmp('talla','sd','5',$d,$w.' '.$o,'Talla (Cm) (75.2 =Cm,mm)','talla','rgxtalla','###.#',false,$x,'','col-2',"Zsco('zscore','../vsp/dntsevymod.php');");
    $c[]=new cmp('zscore','t','20',$d,$w.' '.$bl.' '.$o,'Zscore','zscore',null,null,false,false,'','col-2');
    $c[]=new cmp('clasi_nutri','s','3',$d,$w.' '.$ob.' '.$o,'Clasificación Nutricional','clasi_nutri',null,null,false,false,'','col-2');


	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}




	function gra_etnias(){
		// print_r($_POST);
		$id=divide($_POST['idvivamb']);
		if(count($id)==2){
			$sql = "UPDATE hog_amb SET 
            seguro = TRIM(UPPER('{$_POST['seguro']}')),
            grietas = TRIM(UPPER('{$_POST['grietas']}')),
            combustible = TRIM(UPPER('{$_POST['combustible']}')),
            separadas = TRIM(UPPER('{$_POST['separadas']}')),
            lena = TRIM(UPPER('{$_POST['lena']}')),
            ilumina = TRIM(UPPER('{$_POST['ilumina']}')),
            fuma = TRIM(UPPER('{$_POST['fuma']}')),
            bano = TRIM(UPPER('{$_POST['bano']}')),
            cocina = TRIM(UPPER('{$_POST['cocina']}')),
            elevado = TRIM(UPPER('{$_POST['elevado']}')),
            electrica = TRIM(UPPER('{$_POST['electrica']}')),
            elementos = TRIM(UPPER('{$_POST['elementos']}')),
            barreras = TRIM(UPPER('{$_POST['barreras']}')),
            zontrabajo = TRIM(UPPER('{$_POST['zontrabajo']}')),
            usu_update = TRIM(UPPER('{$_SESSION['us_sds']}')),
            fecha_update = DATE_SUB(NOW(), INTERVAL 5 HOUR)
        WHERE idamb = TRIM(UPPER('{$_POST['idvivamb']}'))";
			// echo $sql;
		}else if(count($id)==1){
		  $sql="INSERT INTO hog_amb VALUES (NULL,trim(upper('{$id[0]}')),trim(upper('{$_POST['fecha']}')),trim(upper('{$_POST['tipo_activi']}')),trim(upper('{$_POST['seguro']}')),trim(upper('{$_POST['grietas']}')),trim(upper('{$_POST['combustible']}')),trim(upper('{$_POST['separadas']}')),trim(upper('{$_POST['lena']}')),trim(upper('{$_POST['ilumina']}')),trim(upper('{$_POST['fuma']}')),trim(upper('{$_POST['bano']}')),trim(upper('{$_POST['cocina']}')),trim(upper('{$_POST['elevado']}')),trim(upper('{$_POST['electrica']}')),trim(upper('{$_POST['elementos']}')),trim(upper('{$_POST['barreras']}')),trim(upper('{$_POST['zontrabajo']}')),trim(upper('{$_POST['agua']}')),trim(upper('{$_POST['tanques']}')),trim(upper('{$_POST['adecagua']}')),trim(upper('{$_POST['raciagua']}')),trim(upper('{$_POST['sanitari']}')),trim(upper('{$_POST['aguaresid']}')),trim(upper('{$_POST['terraza']}')),trim(upper('{$_POST['recipientes']}')),trim(upper('{$_POST['vivaseada']}')),trim(upper('{$_POST['separesiduos']}')),trim(upper('{$_POST['reutresiduos']}')),trim(upper('{$_POST['noresiduos']}')),trim(upper('{$_POST['adecresiduos']}')),trim(upper('{$_POST['horaresiduos']}')),trim(upper('{$_POST['plagas']}')),trim(upper('{$_POST['contplagas']}')),trim(upper('{$_POST['pracsanitar']}')),trim(upper('{$_POST['envaplaguicid']}')),trim(upper('{$_POST['consealiment']}')),trim(upper('{$_POST['limpcocina']}')),trim(upper('{$_POST['cuidcuerpo']}')),trim(upper('{$_POST['fechvencim']}')),trim(upper('{$_POST['limputensilios']}')),trim(upper('{$_POST['adqualime']}')),trim(upper('{$_POST['almaquimicos']}')),trim(upper('{$_POST['etiqprodu']}')),trim(upper('{$_POST['juguetes']}')),trim(upper('{$_POST['medicamalma']}')),trim(upper('{$_POST['medicvenc']}')),trim(upper('{$_POST['adqumedicam']}')),trim(upper('{$_POST['medidaspp']}')),trim(upper('{$_POST['radiacion']}')),trim(upper('{$_POST['contamaire']}')),trim(upper('{$_POST['monoxido']}')),trim(upper('{$_POST['residelectri']}')),trim(upper('{$_POST['duermeelectri']}')),trim(upper('{$_POST['vacunasmascot']}')),trim(upper('{$_POST['aseamascot']}')),trim(upper('{$_POST['alojmascot']}')),trim(upper('{$_POST['excrmascot']}')),trim(upper('{$_POST['permmascot']}')),trim(upper('{$_POST['salumascot']}')),trim(upper('{$_POST['pilas']}')),trim(upper('{$_POST['dispmedicamentos']}')),trim(upper('{$_POST['dispcompu']}')),trim(upper('{$_POST['dispplamo']}')),trim(upper('{$_POST['dispbombill']}')),trim(upper('{$_POST['displlanta']}')),trim(upper('{$_POST['dispplaguic']}')),trim(upper('{$_POST['dispaceite']}')),
		  TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
			// echo $sql;
		}else{
			
		}
		$rta=dato_mysql($sql);
	  return $rta;
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
		if($_REQUEST['id']!=''){
					$id=divide($_REQUEST['id']);
					$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='238' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
					$info=datos_mysql($sql);
					return json_encode($info['responseResult']);
			}
	}

	function opc_moti_con($id=''){
		if($_REQUEST['id']!=''){
					$id=divide($_REQUEST['id']);
					$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='238' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
					$info=datos_mysql($sql);
					return json_encode($info['responseResult']);
			}
	}

	function opc_des_sin($id=''){
		if($_REQUEST['id']!=''){
					$id=divide($_REQUEST['id']);
					$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='238' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
					$info=datos_mysql($sql);
					return json_encode($info['responseResult']);
			}
	}


	function opc_seguro($id=''){
		if($_REQUEST['id']!=''){
					$id=divide($_REQUEST['id']);
					$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='238' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
					$info=datos_mysql($sql);
					return json_encode($info['responseResult']);
			}
	}