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


function focus_predios(){
	return 'predios';
   }
   
   
   function men_predios(){
	$rta=cap_menus('predios','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 $acc=rol($a);
	   if ($a=='predios'  && isset($acc['crear']) && $acc['crear']=='SI'){  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	 
	   }
  return $rta;
}
FUNCTION lis_predios(){
	// var_dump($_POST['id']);
	$id=divide($_POST['id']);
	$sql="SELECT `idamb` ACCIONES,idamb 'Cod Registro',`fecha`,FN_CATALOGODESC(34,tipo_activi) Tipo,`nombre` Creó,`fecha_create` 'fecha Creó'
	FROM hog_amb A
	LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario ";
	$sql.="WHERE idvivamb='".$id[0];
	$sql.="' ORDER BY fecha_create";
	// echo $sql;
	$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"predios-lis",5);
   }


function cmp_predios(){
	$rta="<div class='encabezado predios'>TABLA ESTADOS DEL PREDIO</div>
	<div class='contenido' id='predios-lis'>".lis_predios()."</div></div>";
	$hoy=date('Y-m-d');
	$w='predios';
	$d='';
	$o='pred';
	$c[]=new cmp($o,'e',null,'CODIGOS DE PREDIO',$w);
	$c[]=new cmp('sector','n',15,$d,$w.' '.$o,'sector','sector',null,'123456',true,true);
	$c[]=new cmp('manzana','n',6,$d,$w.' '.$o,'manzana','manzana',null,'123',true,true,'','col-5','validDate(this,-60,0);');
	$c[]=new cmp('predio','n',3,$d,$w.' '.$o,'predio','predio',null,'123',true,true,'','col-5');
	$c[]=new cmp('unidad','n',3,$d,$w.' '.$o,'unidad','unidad',null,'123',true,true,'','col-5');
	$c[]=new cmp('codpre','n',3,$d,$w.' '.$o,'Codigo del Predio','codpre',null,'123',true,true,'','col-5');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}



function opc_tipo_activi($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=34 and estado='A' ORDER BY 1",$id);
	}
	function opc_seguro($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_grietas($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_combustible($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_separadas($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_lena($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_ilumina($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_fuma($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_bano($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_cocina($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_elevado($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_electrica($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_elementos($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_barreras($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_zontrabajo($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_agua($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_tanques($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_adecagua($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_sanitari($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_aguaresid($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_terraza($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_recipientes($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_vivaseada($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_separesiduos($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_reutresiduos($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_noresiduos($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_adecresiduos($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_horaresiduos($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_plagas($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_contplagas($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_pracsanitar($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_envaplaguicid($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_consealiment($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_limpcocina($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_cuidcuerpo($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_fechvencim($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_limputensilios($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_adqualime($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_almaquimicos($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_etiqprodu($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_juguetes($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_medicamalma($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_medicvenc($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_adqumedicam($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_medidaspp($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_radiacion($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_contamaire($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_monoxido($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_residelectri($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_duermeelectri($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_vacunasmascot($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_aseamascot($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_alojmascot($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_excrmascot($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_permmascot($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	function opc_salumascot($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
	}
	/* function opc_pilas($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=33 and estado='A' ORDER BY 1",$id);
	}
	function opc_dispmedicamentos($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=33 and estado='A' ORDER BY 1",$id);
	}
	function opc_dispcompu($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=33 and estado='A' ORDER BY 1",$id);
	}
	function opc_dispplamo($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=33 and estado='A' ORDER BY 1",$id);
	}
	function opc_dispbombill($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=33 and estado='A' ORDER BY 1",$id);
	}
	function opc_displlanta($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=33 and estado='A' ORDER BY 1",$id);
	}
	function opc_dispplaguic($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=33 and estado='A' ORDER BY 1",$id);
	}
	function opc_dispaceite($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=33 and estado='A' ORDER BY 1",$id);
	} */
	function opc_raciagua($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=192 and estado='A' ORDER BY 1",$id);
		}

	function gra_ambient(){
		// print_r($_POST);
		$id=divide($_POST['idvivamb']);
		if(count($id)==1){
			$sql = "UPDATE hog_amb SET 
            fecha = TRIM(UPPER('{$_POST['fecha']}')),
            tipo_activi = TRIM(UPPER('{$_POST['tipo_activi']}')),
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
            agua = TRIM(UPPER('{$_POST['agua']}')),
            tanques = TRIM(UPPER('{$_POST['tanques']}')),
            adecagua = TRIM(UPPER('{$_POST['adecagua']}')),
            raciagua = TRIM(UPPER('{$_POST['raciagua']}')),
            sanitari = TRIM(UPPER('{$_POST['sanitari']}')),
            aguaresid = TRIM(UPPER('{$_POST['aguaresid']}')),
            terraza = TRIM(UPPER('{$_POST['terraza']}')),
            recipientes = TRIM(UPPER('{$_POST['recipientes']}')),
            vivaseada = TRIM(UPPER('{$_POST['vivaseada']}')),
            separesiduos = TRIM(UPPER('{$_POST['separesiduos']}')),
            reutresiduos = TRIM(UPPER('{$_POST['reutresiduos']}')),
            noresiduos = TRIM(UPPER('{$_POST['noresiduos']}')),
            adecresiduos = TRIM(UPPER('{$_POST['adecresiduos']}')),
            horaresiduos = TRIM(UPPER('{$_POST['horaresiduos']}')),
            plagas = TRIM(UPPER('{$_POST['plagas']}')),
            contplagas = TRIM(UPPER('{$_POST['contplagas']}')),
            pracsanitar = TRIM(UPPER('{$_POST['pracsanitar']}')),
            envaplaguicid = TRIM(UPPER('{$_POST['envaplaguicid']}')),
            consealiment = TRIM(UPPER('{$_POST['consealiment']}')),
            limpcocina = TRIM(UPPER('{$_POST['limpcocina']}')),
            cuidcuerpo = TRIM(UPPER('{$_POST['cuidcuerpo']}')),
            fechvencim = TRIM(UPPER('{$_POST['fechvencim']}')),
            limputensilios = TRIM(UPPER('{$_POST['limputensilios']}')),
            adqualime = TRIM(UPPER('{$_POST['adqualime']}')),
            almaquimicos = TRIM(UPPER('{$_POST['almaquimicos']}')),
            etiqprodu = TRIM(UPPER('{$_POST['etiqprodu']}')),
            juguetes = TRIM(UPPER('{$_POST['juguetes']}')),
            medicamalma = TRIM(UPPER('{$_POST['medicamalma']}')),
            medicvenc = TRIM(UPPER('{$_POST['medicvenc']}')),
            adqumedicam = TRIM(UPPER('{$_POST['adqumedicam']}')),
            medidaspp = TRIM(UPPER('{$_POST['medidaspp']}')),
            radiacion = TRIM(UPPER('{$_POST['radiacion']}')),
            contamaire = TRIM(UPPER('{$_POST['contamaire']}')),
            monoxido = TRIM(UPPER('{$_POST['monoxido']}')),
            residelectri = TRIM(UPPER('{$_POST['residelectri']}')),
            duermeelectri = TRIM(UPPER('{$_POST['duermeelectri']}')),
            vacunasmascot = TRIM(UPPER('{$_POST['vacunasmascot']}')),
            aseamascot = TRIM(UPPER('{$_POST['aseamascot']}')),
            alojmascot = TRIM(UPPER('{$_POST['alojmascot']}')),
            excrmascot = TRIM(UPPER('{$_POST['excrmascot']}')),
            permmascot = TRIM(UPPER('{$_POST['permmascot']}')),
            salumascot = TRIM(UPPER('{$_POST['salumascot']}')),
            pilas = TRIM(UPPER('{$_POST['pilas']}')),
            dispmedicamentos = TRIM(UPPER('{$_POST['dispmedicamentos']}')),
            dispcompu = TRIM(UPPER('{$_POST['dispcompu']}')),
            dispplamo = TRIM(UPPER('{$_POST['dispplamo']}')),
            dispbombill = TRIM(UPPER('{$_POST['dispbombill']}')),
            displlanta = TRIM(UPPER('{$_POST['displlanta']}')),
            dispplaguic = TRIM(UPPER('{$_POST['dispplaguic']}')),
            dispaceite = TRIM(UPPER('{$_POST['dispaceite']}')),
            usu_update = TRIM(UPPER('{$_SESSION['us_sds']}')),
            fecha_update = DATE_SUB(NOW(), INTERVAL 5 HOUR)
        WHERE idamb = TRIM(UPPER('{$_POST['idvivamb']}'))";
		//   echo $sql;
		}else if(count($id)==2){
		  $sql="INSERT INTO hog_amb VALUES (NULL,trim(upper('{$id[0]}')),trim(upper('{$_POST['fecha']}')),trim(upper('{$_POST['tipo_activi']}')),trim(upper('{$_POST['seguro']}')),trim(upper('{$_POST['grietas']}')),trim(upper('{$_POST['combustible']}')),trim(upper('{$_POST['separadas']}')),trim(upper('{$_POST['lena']}')),trim(upper('{$_POST['ilumina']}')),trim(upper('{$_POST['fuma']}')),trim(upper('{$_POST['bano']}')),trim(upper('{$_POST['cocina']}')),trim(upper('{$_POST['elevado']}')),trim(upper('{$_POST['electrica']}')),trim(upper('{$_POST['elementos']}')),trim(upper('{$_POST['barreras']}')),trim(upper('{$_POST['zontrabajo']}')),trim(upper('{$_POST['agua']}')),trim(upper('{$_POST['tanques']}')),trim(upper('{$_POST['adecagua']}')),trim(upper('{$_POST['raciagua']}')),trim(upper('{$_POST['sanitari']}')),trim(upper('{$_POST['aguaresid']}')),trim(upper('{$_POST['terraza']}')),trim(upper('{$_POST['recipientes']}')),trim(upper('{$_POST['vivaseada']}')),trim(upper('{$_POST['separesiduos']}')),trim(upper('{$_POST['reutresiduos']}')),trim(upper('{$_POST['noresiduos']}')),trim(upper('{$_POST['adecresiduos']}')),trim(upper('{$_POST['horaresiduos']}')),trim(upper('{$_POST['plagas']}')),trim(upper('{$_POST['contplagas']}')),trim(upper('{$_POST['pracsanitar']}')),trim(upper('{$_POST['envaplaguicid']}')),trim(upper('{$_POST['consealiment']}')),trim(upper('{$_POST['limpcocina']}')),trim(upper('{$_POST['cuidcuerpo']}')),trim(upper('{$_POST['fechvencim']}')),trim(upper('{$_POST['limputensilios']}')),trim(upper('{$_POST['adqualime']}')),trim(upper('{$_POST['almaquimicos']}')),trim(upper('{$_POST['etiqprodu']}')),trim(upper('{$_POST['juguetes']}')),trim(upper('{$_POST['medicamalma']}')),trim(upper('{$_POST['medicvenc']}')),trim(upper('{$_POST['adqumedicam']}')),trim(upper('{$_POST['medidaspp']}')),trim(upper('{$_POST['radiacion']}')),trim(upper('{$_POST['contamaire']}')),trim(upper('{$_POST['monoxido']}')),trim(upper('{$_POST['residelectri']}')),trim(upper('{$_POST['duermeelectri']}')),trim(upper('{$_POST['vacunasmascot']}')),trim(upper('{$_POST['aseamascot']}')),trim(upper('{$_POST['alojmascot']}')),trim(upper('{$_POST['excrmascot']}')),trim(upper('{$_POST['permmascot']}')),trim(upper('{$_POST['salumascot']}')),trim(upper('{$_POST['pilas']}')),trim(upper('{$_POST['dispmedicamentos']}')),trim(upper('{$_POST['dispcompu']}')),trim(upper('{$_POST['dispplamo']}')),trim(upper('{$_POST['dispbombill']}')),trim(upper('{$_POST['displlanta']}')),trim(upper('{$_POST['dispplaguic']}')),trim(upper('{$_POST['dispaceite']}')),
		  TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
		//   echo $sql;
		}else{
			
		}
		$rta=dato_mysql($sql);
	  return $rta;
	}

	function get_ambient(){
		if($_REQUEST['id']==''){
			return "";
		  }else{
			$id=divide($_REQUEST['id']);
			$sql="SELECT 
			idamb,fecha,tipo_activi,seguro,grietas,combustible,separadas,lena,ilumina,fuma,bano,cocina,elevado,electrica,elementos,barreras,zontrabajo,agua,tanques,adecagua,raciagua,sanitari,aguaresid,terraza,recipientes,vivaseada,separesiduos,reutresiduos,noresiduos,adecresiduos,horaresiduos,plagas,contplagas,pracsanitar,envaplaguicid,consealiment,limpcocina,cuidcuerpo,fechvencim,limputensilios,adqualime,almaquimicos,etiqprodu,juguetes,medicamalma,medicvenc,adqumedicam,medidaspp,radiacion,contamaire,monoxido,residelectri,duermeelectri,vacunasmascot,aseamascot,alojmascot,excrmascot,permmascot,salumascot,pilas,dispmedicamentos,dispcompu,dispplamo,dispbombill,displlanta,dispplaguic,dispaceite
			FROM hog_amb			
			WHERE idamb ='{$id[0]}'";
			// echo $sql;
			// print_r($id);
			$info=datos_mysql($sql);
			return json_encode($info['responseResult'][0]);
		  } 
	}

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
		if ($a=='ambient-lis' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'ambient',event,this,['fecha','tipo_activi'],'../vivienda/amb.php');\"></li>";  //   act_lista(f,this);
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   