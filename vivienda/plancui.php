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


function focus_planDCui(){
	return 'planDCui';
   }
   
   
   function men_planDCui(){
	$rta=cap_menus('planDCui','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 $acc=rol($a);
	   if ($a=='planDCui'  && isset($acc['crear']) && $acc['crear']=='SI'){  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	 
	   }
  return $rta;
}
FUNCTION lis_planDCui(){
	// var_dump($_POST['id']);
	$id=divide($_POST['id']);
	$sql="SELECT `idamb` ACCIONES,idamb 'Cod Registro',`fecha`,FN_CATALOGODESC(34,tipo_activi) Tipo,`nombre` Creó,`fecha_create` 'fecha Creó'
	FROM hog_amb A
	LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario ";
	$sql.="WHERE idvivamb='".$id[0];
	$sql.="' ORDER BY fecha_create";
	// echo $sql;
	$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"planDCui-lis",5);
   }


function cmp_planDCui(){
	$rta="";
	$t=['id'=>'','fecha'=>'','accion1'=>'','desc_accion1'=>'','accion2'=>'','desc_accion2'=>'','accion3'=>'','desc_accion3'=>'','accion4'=>'','desc_accion4'=>'','observacion'=>''];
	$d=get_planDCui();
	if ($d==""){$d=$t;}
	$u=($d['id']=='')?true:false;
	$hoy=date('Y-m-d');
    $w="planDCui";
	$o='accide';
	$e="";
	$key='pln';
	$c[]=new cmp($o,'e',null,'ACCIONES PROMOCIONALES Y DE IDENTIFICACIÓN DE RIESGOS REALIZADOS EN LA CARACTERIZACIÓN FAMILIAR',$w);
	$c[]=new cmp('idp','h',15,$_POST['id'],$w.' '.$key.' '.$o,'id','id',null,'####',false,false);
	$c[]=new cmp('fecha_caracteriza','d','10',$d['fecha'],$w.' '.$o,'fecha_caracteriza','fecha_caracteriza',null,null,true,true,'','col-2','validDate(this,-3,0);');
	$c[]=new cmp('accion1','s',3,$d['accion1'],$w.' '.$o,'Accion 1','accion1',null,null,true,true,'','col-3',"selectDepend('accion1','desc_accion1','plancui.php');");
	$c[]=new cmp('desc_accion1','s',3,$d['desc_accion1'],$w.' '.$o,'Descripcion Accion 1','desc_accion1',null,null,true,true,'','col-5');
    $c[]=new cmp('accion2','s','3',$d['accion2'],$w.' '.$o,'Accion 2','accion2',null,null,false,true,'','col-5','selectDepend(\'accion2\',\'desc_accion2\',\'plancui.php\');');
    $c[]=new cmp('desc_accion2','s','3',$d['desc_accion2'],$w.' '.$o,'Descripcion Accion 2','desc_accion2',null,null,false,true,'','col-5');
    $c[]=new cmp('accion3','s','3',$d['accion3'],$w.' '.$o,'Accion 3','accion3',null,null,false,true,'','col-5','selectDepend(\'accion3\',\'desc_accion3\',\'plancui.php\');');
    $c[]=new cmp('desc_accion3','s','3',$d['desc_accion3'],$w.' '.$o,'Descripcion Accion 3','desc_accion3',null,null,false,true,'','col-5');
    $c[]=new cmp('accion4','s','3',$d['accion4'],$w.' '.$o,'Accion 4','accion4',null,null,false,true,'','col-5','selectDepend(\'accion4\',\'desc_accion4\',\'plancui.php\');');
    $c[]=new cmp('desc_accion4','s','3',$d['desc_accion4'],$w.' '.$o,'Descripcion Accion 4','desc_accion3',null,null,false,true,'','col-5');
	$c[]=new cmp('observacion','a',500,$d['observacion'],$w.' '.$o,'Observacion','observacion',null,null,true,true,'','col-10');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

	function gra_planDCui(){
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

	function get_planDCui(){
		if (!$_POST['id']) {
			return '';
		}
		$id = divide($_POST['id']);
		$sql = "SELECT concat(A.idviv,'_',A.id) 'id',fecha,accion1,desc_accion1,accion2,desc_accion2,accion3,desc_accion3,accion4,desc_accion4,observacion,P.compromiso,P.equipo,P.cumple
		FROM hog_plancuid A
		LEFT JOIN hog_planconc P ON A.idviv=P.idviv
		WHERE P.idviv='{$id[0]}' and P.idcon='{$id[1]}'";
		// echo $sql;		
		$info = datos_mysql($sql);
		// echo $sql; 
		// print_r($info['responseResult'][0]);
		if (!$info['responseResult']) {
			return '';
		}else{
			return json_encode($info['responseResult'][0]);
		}
	}

    function opc_accion1desc_accion1($id=''){
        if($_REQUEST['id']!=''){
                    $id=divide($_REQUEST['id']);
                    $sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
                    $info=datos_mysql($sql);
                    return json_encode($info['responseResult']);
            }
        }
        
        function opc_accion2desc_accion2($id=''){
          if($_REQUEST['id']!=''){
                $id=divide($_REQUEST['id']);
                $sql="SELECT idcatadeta,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
                $info=datos_mysql($sql);		
                return json_encode($info['responseResult']);
              }
          }
          function opc_accion3desc_accion3($id=''){
            if($_REQUEST['id']!=''){
                  $id=divide($_REQUEST['id']);
                  $sql="SELECT idcatadeta 'id',descripcion 'asc' FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
                  $info=datos_mysql($sql);		
                  return json_encode($info['responseResult']);
                }
            }
            function opc_accion4desc_accion4($id=''){
            if($_REQUEST['id']!=''){
                  $id=divide($_REQUEST['id']);
                  $sql="SELECT idcatadeta 'id',descripcion 'asc' FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
                  $info=datos_mysql($sql);		
                  return json_encode($info['responseResult']);
                }
            }
        
        function opc_desc_accion1($id=''){
          return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
          }
        function opc_desc_accion2($id=''){
            return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
        }
        function opc_desc_accion3($id=''){
            return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
        }
        function opc_accion1($id=''){
        return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
        }
        function opc_accion2($id=''){
        return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
        }
        function opc_accion3($id=''){
        return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
        }
        function opc_accion4($id=''){
        return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
        }
        
        function opc_equipo($id=''){
            return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=26 and estado='A' ORDER BY 1",$id);
        } 


	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
		if ($a=='planDCui-lis' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'planDCui',event,this,['fecha','tipo_activi'],'../vivienda/amb.php');\"></li>";  //   act_lista(f,this);
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   