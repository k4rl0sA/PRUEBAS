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


function focus_bpnpret(){
  return 'bpnpret';
 }
 
 
 function men_bpnpret(){
  $rta=cap_menus('bpnpret','pro');
  return $rta;
 }
 
 
 function cap_menus($a,$b='cap',$con='con') {
   $rta = ""; 
   $acc=rol($a);
   $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
   $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
   
   return $rta;
 }


 FUNCTION seg_bpnpret(){
	// var_dump($_POST['id']);
	$id=divide($_POST['id']);
	$sql="SELECT `id_bpnpret` ACCIONES,
  tipo_doc,documento,fecha_seg Fecha,numsegui Seguimiento,FN_CATALOGODESC(87,evento) EVENTO,FN_CATALOGODESC(73,estado_s) estado,cierre_caso Cierra,
fecha_cierre 'Fecha de Cierre',nombre Creó 
FROM vsp_bpnpret A
	LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario ";
	$sql.="WHERE tipo_doc='".$id[1]."' AND documento='".$id[0];
	$sql.="' ORDER BY fecha_create";
	// echo $sql;
	$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"bpnpret-lis",5);
   }


function cmp_bpnpret(){
	$rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div>
	<div class='contenido' id='bpnpret-lis'>".seg_bpnpret()."</div></div>";
	$w='bpnpret';
  $d='';
	$o='inf';
// $nb='disa oculto';
$ob='Ob';
  $no='nO';
  $bl='bL';
  $x=false;
   $block=['hab','acc'];
  $event=divide($_POST['id']);
  $ev=$event[3];
  

	$c[]=new cmp('id_bpnpret','h','50',$_POST['id'],$w.' '.$o,'Id de bpnpret','id_bpnpret',null,null,false,false,'','col-2');
  
  $c[]=new cmp('fecha_seg','d','10',$d,$w.' '.$o,'Fecha Seguimiento','fecha_seg',null,null,true,true,'','col-2','validDate(this,-2,0)');
  $c[]=new cmp('numsegui','s','3',$d,$w.' '.$o,'Seguimiento N°','numsegui',null,null,true,true,'','col-2',"staEfe('numsegui','sta');");
  $c[]=new cmp('evento','s','3',$ev,$w.' '.$o,'Evento','evento',null,null,false,false,'','col-2');
  $c[]=new cmp('estado_s','s','3',$d,$w.' sTa '.$o,'Estado','estado_s',null,null,true,true,'','col-2',"enabFielSele(this,true,['motivo_estado'],['3']);EnabEfec(this,['hab','acc'],['Ob'],['nO'],['bL']);");
  $c[]=new cmp('motivo_estado','s','3',$d,$w.' '.$o,'Motivo de Estado','motivo_estado',null,null,false,$x,'','col-2');

    
    $o='hab';
    $c[]=new cmp($o,'e',null,'INFORMACIÓN ',$w);
    $c[]=new cmp('sem_ges','s','3',$d,$w.' '.$o,'Semana gestacional de nacimiento','sem_ges',null,null,false,$x,'','col-2');
    $c[]=new cmp('asiste_control','s','2',$d,$w.' '.$o,'¿Asiste a Controles de Crecimiento y Desarrollo o plan canguro?','rta',null,null,false,$x,'','col-4');
    $c[]=new cmp('vacuna_comple','s','2',$d,$w.' '.$o,'¿Tiene esquema de vacunación completo para la edad?','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('lacmate_exclu','s','3',$d,$w.' '.$o,'¿Recibe lactancia materna exclusiva?','lacmate_exclu',null,null,false,$x,'','col-2');
    $c[]=new cmp('peso','n','3',$d,$w.' '.$o,'Peso (Gramos 800)','peso',null,null,false,$x,'','col-2');
    $c[]=new cmp('talla','sd','5',$d,$w.' '.$o,'Talla (Cm.Mm 20.2)','talla','rgx3in1fl',null,false,$x,'','col-2');
    $c[]=new cmp('edad_ges','s','3',$d,$w.' '.$o,'Edad Gestacional Corregida','edad_ges',null,null,false,$x,'','col-2');
    $c[]=new cmp('diag_nutri','s','3',$d,$w.' '.$o,'Diagnóstico Nutricional FENTON P/EG','diag_nutri',null,null,false,$x,'','col-2');
    $c[]=new cmp('zscore','t','500',$d,$w.' '.$o,'Z SCORE','zscore',null,null,false,$x,'','col-2');
    $c[]=new cmp('clasi_nutri','s','3',$d,$w.' '.$o,'Clasificacion Nutricional','clasi_nutri',null,null,false,$x,'','col-3');
    $c[]=new cmp('gana_peso','s','2',$d,$w.' '.$o,'¿Se evidencia ganancia de peso?','rta',null,null,false,$x,'','col-2',"enabOthSi('gana_peso','gP');");
    $c[]=new cmp('gana_peso_dia','s','3',$d,$w.' gP '.$o,'Ganancia de Peso Diaria','gana_peso_dia',null,null,false,$x,'','col-3');
    $c[]=new cmp('signos_alarma','s','2',$d,$w.' '.$o,'¿El cuidador identifica signos de alarma?','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('signos_alarma_seg','s','2',$d,$w.' '.$o,'Menor con signos de alarma al momento del seguimiento','rta',null,null,false,$x,'','col-4');
    
    
    $o='acc';
    $c[]=new cmp($o,'e',null,'INFORMACIÓN ACCIONES',$w);
    $c[]=new cmp('estrategia_1','s','3',$d,$w.' '.$o,'Estrategia PF_1','estrategia_1',null,null,false,$x,'','col-5');
    $c[]=new cmp('estrategia_2','s','3',$d,$w.' '.$no.' '.$o,'Estrategia PF_2','estrategia_2',null,null,false,$x,'','col-5');
    $c[]=new cmp('acciones_1','s','3',$d,$w.' '.$o,'Accion 1','acciones_1',null,null,false,$x,'','col-5','selectDepend(\'acciones_1\',\'desc_accion1\',\'../vsp/acompsic.php\');');
    $c[]=new cmp('desc_accion1','s','3',$d,$w.' '.$o,'Descripcion Accion 1','desc_accion1',null,null,false,$x,'','col-5');
    $c[]=new cmp('acciones_2','s','3',$d,$w.' '.$no.' '.$o,'Accion 2','acciones_2',null,null,false,$x,'','col-5','selectDepend(\'acciones_2\',\'desc_accion2\',\'../vsp/acompsic.php\');');
    $c[]=new cmp('desc_accion2','s','3',$d,$w.' '.$no.' '.$o,'Descripcion Accion 2','desc_accion2',null,null,false,$x,'','col-5');
    $c[]=new cmp('acciones_3','s','3',$d,$w.' '.$no.' '.$o,'Accion 3','acciones_3',null,null,false,$x,'','col-5','selectDepend(\'acciones_3\',\'desc_accion3\',\'../vsp/acompsic.php\');');
    $c[]=new cmp('desc_accion3','s','3',$d,$w.' '.$no.' '.$o,'Descripcion Accion 3','desc_accion3',null,null,false,$x,'','col-5');
    $c[]=new cmp('activa_ruta','s','2',$d,$w.' '.$o,'Ruta Activada','rta',null,null,false,$x,'','col-3','enabRuta(this,\'rt\');');
    $c[]=new cmp('ruta','s','3',$d,$w.' '.$no.' rt '.$bl.' '.$o,'Ruta','ruta',null,null,false,$x,'','col-35');
    $c[]=new cmp('novedades','s','3',$d,$w.' '.$no.' '.$o,'Novedades','novedades',null,null,false,$x,'','col-35');
    $c[]=new cmp('signos_covid','s','2',$d,$w.' '.$o,'¿Signos y Síntomas para Covid19?','rta',null,null,false,$x,'','col-2','enabCovid(this,\'cv\');');
    $c[]=new cmp('caso_afirmativo','t','500',$d,$w.' cv '.$bl.' '.$no.' '.$o,'Relacione Cuales signos y sintomas, Y Atención Recibida Hasta el Momento','caso_afirmativo',null,null,false,$x,'','col-4');
    $c[]=new cmp('otras_condiciones','t','500',$d,$w.' cv '.$bl.' '.$no.' '.$o,'Otras Condiciones de Riesgo que Requieren una Atención Complementaria.','otras_condiciones',null,null,false,$x,'','col-4');
    $c[]=new cmp('observaciones','a','1500',$d,$w.' '.$ob.' '.$o,'Observaciones','observaciones',null,null,true,true,'','col-10');
    $c[]=new cmp('cierre_caso','s','2',$d,$w.' '.$o,'Cierre de Caso','rta',null,null,false,$x,'','col-15','enabFincas(this,\'cc\');');
    //igual

    $c[]=new cmp('motivo_cierre','s','2',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Motivo Cierre','motivo_cierre',null,null,false,$x,'','col-55');
    $c[]=new cmp('fecha_cierre','d','10',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Fecha de Cierre','fecha_cierre',null,null,false,$x,'','col-15');
    $c[]=new cmp('redu_riesgo_cierre','s','2',$d,$w.' cc '.$bl.' '.$no.' '.$o,'¿Reduccion del riesgo?','rta',null,null,false,$x,'','col-15');
    $c[]=new cmp('users_bina[]','m','10',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Usuarios Equipo','bina',null,null,false,true,'','col-5');
    
	
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function opc_bina($id=''){
  return opc_sql("SELECT id_usuario, nombre  from usuarios u WHERE equipo=(select equipo from usuarios WHERE id_usuario='{$_SESSION['us_sds']}') and estado='A'  ORDER BY 2;",$id);
}
function opc_motivo_cierre($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=198 and estado='A'  ORDER BY 1 ",$id);
}
function opc_rta($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
  }
function opc_tipo_doc($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_numsegui($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=76 and estado='A' ORDER BY 1",$id);
}
function opc_evento($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 and estado='A' ORDER BY 1",$id);
}
function opc_estado_s($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=73 and estado='A' ORDER BY 1",$id);
}
function opc_motivo_estado($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=74 and estado='A' ORDER BY 1",$id);
}
function opc_sem_ges($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=95 and estado='A' ORDER BY LPAD(idcatadeta,2,'0')",$id);
}
function opc_lacmate_exclu($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=88 and estado='A' ORDER BY 1",$id);
}
function opc_edad_ges($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=96 and estado='A' ORDER BY 1",$id);
}
function opc_diag_nutri($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=97 and estado='A' ORDER BY 1",$id);
}
function opc_clasi_nutri($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=98 and estado='A' ORDER BY 1",$id);
}
function opc_gana_peso_dia($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=99 and estado='A' ORDER BY 1",$id);
}

function opc_acciones_1($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
}
function opc_desc_accion1($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
  }
function opc_estrategia_1($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=90 and estado='A' ORDER BY 1",$id);
}
function opc_estrategia_2($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=90 and estado='A' ORDER BY 1",$id);
}

function opc_acciones_1desc_accion1($id=''){
if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
			$info=datos_mysql($sql);		
			return json_encode($info['responseResult']);
    }
}
function opc_acciones_2desc_accion2($id=''){
  if($_REQUEST['id']!=''){
        $id=divide($_REQUEST['id']);
        $sql="SELECT idcatadeta,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
        $info=datos_mysql($sql);		
        return json_encode($info['responseResult']);
      }
  }
  function opc_acciones_3desc_accion3($id=''){
    if($_REQUEST['id']!=''){
          $id=divide($_REQUEST['id']);
          $sql="SELECT idcatadeta 'id',descripcion 'asc' FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
          $info=datos_mysql($sql);		
          return json_encode($info['responseResult']);
        }
    }
function opc_acciones_2($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
}
function opc_desc_accion2($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
}
function opc_acciones_3($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
}
function opc_desc_accion3($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
}
function opc_ruta($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=79 and estado='A' ORDER BY 1",$id);
}
function opc_novedades($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=77 and estado='A' ORDER BY 1",$id);
}



function gra_bpnpret(){
  // print_r($_POST);
  $id=divide($_POST['id_bpnpret']);
  if(count($id)==5){
    $sql="UPDATE vsp_bpnpret SET 
    sem_ges=trim(upper('{$_POST['sem_ges']}')),asiste_control=trim(upper('{$_POST['asiste_control']}')),vacuna_comple=trim(upper('{$_POST['vacuna_comple']}')),lacmate_exclu=trim(upper('{$_POST['lacmate_exclu']}')),peso=trim(upper('{$_POST['peso']}')),talla=trim(upper('{$_POST['talla']}')),edad_ges=trim(upper('{$_POST['edad_ges']}')),diag_nutri=trim(upper('{$_POST['diag_nutri']}')),zscore=trim(upper('{$_POST['zscore']}')),clasi_nutri=trim(upper('{$_POST['clasi_nutri']}')),gana_peso=trim(upper('{$_POST['gana_peso']}')),gana_peso_dia=trim(upper('{$_POST['gana_peso_dia']}')),signos_alarma=trim(upper('{$_POST['signos_alarma']}')),signos_alarma_seg=trim(upper('{$_POST['signos_alarma_seg']}')),estrategia_1=trim(upper('{$_POST['estrategia_1']}')),estrategia_2=trim(upper('{$_POST['estrategia_2']}')),acciones_1=trim(upper('{$_POST['acciones_1']}')),desc_accion1=trim(upper('{$_POST['desc_accion1']}')),desc_accion2=trim(upper('{$_POST['desc_accion2']}')),desc_accion3=trim(upper('{$_POST['desc_accion3']}')),acciones_2=trim(upper('{$_POST['acciones_2']}')),acciones_3=trim(upper('{$_POST['acciones_3']}')),activa_ruta=trim(upper('{$_POST['activa_ruta']}')),ruta=trim(upper('{$_POST['ruta']}')),novedades=trim(upper('{$_POST['novedades']}')),signos_covid=trim(upper('{$_POST['signos_covid']}')),caso_afirmativo=trim(upper('{$_POST['caso_afirmativo']}')),otras_condiciones=trim(upper('{$_POST['otras_condiciones']}')),observaciones=trim(upper('{$_POST['observaciones']}')),cierre_caso=trim(upper('{$_POST['cierre_caso']}')),motivo_cierre = TRIM(UPPER('{$_POST['motivo_cierre']}')),fecha_cierre=trim(upper('{$_POST['fecha_cierre']}')),redu_riesgo_cierre=trim(upper('{$_POST['redu_riesgo_cierre']}')),users_bina = TRIM(UPPER('{$_POST['users_bina']}')),
    `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
    WHERE id_bpnpret =TRIM(UPPER('{$id[0]}'))";
    // echo $sql;
  }else if(count($id)==4){
    $sql="INSERT INTO vsp_bpnpret VALUES (NULL,trim(upper('{$id[1]}')),trim(upper('{$id[0]}')),
    trim(upper('{$_POST['fecha_seg']}')),trim(upper('{$_POST['numsegui']}')),trim(upper('{$_POST['evento']}')),trim(upper('{$_POST['estado_s']}')),trim(upper('{$_POST['motivo_estado']}')),trim(upper('{$_POST['sem_ges']}')),trim(upper('{$_POST['asiste_control']}')),trim(upper('{$_POST['vacuna_comple']}')),trim(upper('{$_POST['lacmate_exclu']}')),trim(upper('{$_POST['peso']}')),trim(upper('{$_POST['talla']}')),trim(upper('{$_POST['edad_ges']}')),trim(upper('{$_POST['diag_nutri']}')),trim(upper('{$_POST['zscore']}')),trim(upper('{$_POST['clasi_nutri']}')),trim(upper('{$_POST['gana_peso']}')),trim(upper('{$_POST['gana_peso_dia']}')),trim(upper('{$_POST['signos_alarma']}')),trim(upper('{$_POST['signos_alarma_seg']}')),trim(upper('{$_POST['estrategia_1']}')),trim(upper('{$_POST['estrategia_2']}')),trim(upper('{$_POST['acciones_1']}')),trim(upper('{$_POST['desc_accion1']}')),trim(upper('{$_POST['desc_accion2']}')),trim(upper('{$_POST['desc_accion3']}')),trim(upper('{$_POST['acciones_2']}')),trim(upper('{$_POST['acciones_3']}')),trim(upper('{$_POST['activa_ruta']}')),trim(upper('{$_POST['ruta']}')),trim(upper('{$_POST['novedades']}')),trim(upper('{$_POST['signos_covid']}')),trim(upper('{$_POST['caso_afirmativo']}')),trim(upper('{$_POST['otras_condiciones']}')),trim(upper('{$_POST['observaciones']}')),trim(upper('{$_POST['cierre_caso']}')),trim(upper('{$_POST['motivo_cierre']}')),trim(upper('{$_POST['fecha_cierre']}')),trim(upper('{$_POST['redu_riesgo_cierre']}')),trim(upper('{$_POST['users_bina']}')),
    TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
    // echo $sql;
  }
    $rta=dato_mysql($sql);
    return $rta;
  } 


  function get_bpnpret(){
    if($_REQUEST['id']==''){
      return "";
    }else{
      $id=divide($_REQUEST['id']);
      $sql="SELECT concat(id_bpnpret,'_',tipo_doc,'_',documento,'_',numsegui,'_',evento),
      fecha_seg,numsegui,evento,estado_s,motivo_estado,sem_ges,asiste_control,vacuna_comple,lacmate_exclu,peso,talla,edad_ges,diag_nutri,zscore,clasi_nutri,gana_peso,gana_peso_dia,signos_alarma,signos_alarma_seg,estrategia_1,estrategia_2,acciones_1,desc_accion1,desc_accion2,desc_accion3,acciones_2,acciones_3,activa_ruta,ruta,novedades,signos_covid,caso_afirmativo,otras_condiciones,observaciones,cierre_caso,motivo_cierre,fecha_cierre,redu_riesgo_cierre,users_bina
      FROM vsp_bpnpret
      WHERE id_bpnpret ='{$id[0]}'";
      // echo $sql;
      // print_r($id);
      $info=datos_mysql($sql);
      return json_encode($info['responseResult'][0]);
    } 
  }

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='bpnpret-lis' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";	
		$rta.="<li class='icono editar' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'bpnpret',event,this,['fecha_seg','numsegui','evento','estado_s','motivo_estado'],'bpnpret.php');\"></li>";
	}
	
 return $rta;
}


function bgcolor($a,$c,$f='c'){
  $rta="";
  return $rta;
   }
