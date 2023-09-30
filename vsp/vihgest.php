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



function focus_vihgest(){
  return 'vihgest';
 }
 
 
 function men_vihgest(){
  $rta=cap_menus('vihgest','pro');
  return $rta;
 }
 
 
 function cap_menus($a,$b='cap',$con='con') {
   $rta = ""; 
   $acc=rol($a);
   $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
   $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  
   return $rta;
 }


 FUNCTION seg_vihgest(){
	// var_dump($_POST['id']);
	$id=divide($_POST['id']);
	$sql="SELECT `id_vihgestacio` ACCIONES,
  tipo_doc,documento,fecha_seg Fecha,numsegui Seguimiento,FN_CATALOGODESC(87,evento) EVENTO,FN_CATALOGODESC(73,estado_s) estado,cierre_caso Cierra,
  fecha_cierre 'Fecha de Cierre',nombre Creó 
  FROM vsp_vihgest A
	LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario ";
	$sql.="WHERE tipo_doc='".$id[1]."' AND documento='".$id[0];
	$sql.="' ORDER BY fecha_create";
	// echo $sql;
	$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"vihgest-lis",5);
   }


function cmp_vihgest(){
	$rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div>
	<div class='contenido' id='vihgest-lis'>".seg_vihgest()."</div></div>";
	$w='vihgest';
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
  

	$c[]=new cmp('id_vihgestacio','h','50',$_POST['id'],$w.' '.$o,'Id de vihgest','id_vihgestacio',null,null,false,false,'','col-2');
  $c[]=new cmp('fecha_seg','d','10',$d,$w.' '.$o,'Fecha Seguimiento','fecha_seg',null,null,true,true,'','col-2','validDate(this,-2,0)');
  $c[]=new cmp('numsegui','s','3',$d,$w.' '.$o,'Seguimiento N°','numsegui',null,null,true,true,'','col-2');
  $c[]=new cmp('evento','s','3',$ev,$w.' '.$o,'Evento','evento',null,null,false,false,'','col-2');
  $c[]=new cmp('estado_s','s','3',$d,$w.' '.$o,'Estado','estado_s',null,null,true,true,'','col-2',"enabFielSele(this,true,['motivo_estado'],['3']);EnabEfec(this,['hab','acc'],['Ob'],['nO'],['bL']);");
  $c[]=new cmp('motivo_estado','s','3',$d,$w.' '.$o,'Motivo de Estado','motivo_estado',null,null,false,$x,'','col-2');
  
    
    $o='hab';
    $c[]=new cmp($o,'e',null,'INFORMACIÓN ',$w);
    
    
    
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
    $c[]=new cmp('cierre_caso','s','2',$d,$w.' '.$o,'Cierre de Caso','rta',null,null,false,$x,'','col-1','enabFincas(this,\'cc\');');
    //igual

    $c[]=new cmp('fecha_cierre','d','10',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Fecha de Cierre','fecha_cierre',null,null,false,$x,'','col-15');
    $c[]=new cmp('liker_dificul','s','3',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Liker de Dificultades','liker_dificul',null,null,false,$x,'','col-2');
    $c[]=new cmp('liker_emocion','s','3',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Liker de Emociones','liker_emocion',null,null,false,$x,'','col-2');
    $c[]=new cmp('liker_decision','s','3',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Liker de Decisiones','liker_decision',null,null,false,$x,'','col-2');
    $c[]=new cmp('redu_riesgo_cierre','s','2',$d,$w.' cc '.$bl.' '.$no.' '.$o,'¿Reduccion del riesgo?','rta',null,null,false,$x,'','col-15');
	
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
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
function opc_liker_dificul($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=78 and estado='A' ORDER BY 1",$id);
}
function opc_liker_emocion($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=78 and estado='A' ORDER BY 1",$id);
}
function opc_liker_decision($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=78 and estado='A' ORDER BY 1",$id);
}


function gra_vihgest(){
// print_r($_POST);
$id=divide($_POST['id_vihgestacio']);
  if(count($id)==5){
    $sql="UPDATE vsp_vihgest SET 
    etapa=trim(upper('{$_POST['etapa']}')),sema_gest=trim(upper('{$_POST['sema_gest']}')),asis_ctrpre=trim(upper('{$_POST['asis_ctrpre']}')),exam_lab=trim(upper('{$_POST['exam_lab']}')),esqu_vacuna=trim(upper('{$_POST['esqu_vacuna']}')),cons_micronutr=trim(upper('{$_POST['cons_micronutr']}')),fec_pruerap1=trim(upper('{$_POST['fec_pruerap1']}')),fec_cargaviral1=trim(upper('{$_POST['fec_cargaviral1']}')),resul_cargaviral1=trim(upper('{$_POST['resul_cargaviral1']}')),asis_provih1=trim(upper('{$_POST['asis_provih1']}')),cual1=trim(upper('{$_POST['cual1']}')),adhe_tra_antirre1=trim(upper('{$_POST['adhe_tra_antirre1']}')),fecha_obstetrica=trim(upper('{$_POST['fecha_obstetrica']}')),edad_gesta=trim(upper('{$_POST['edad_gesta']}')),resul_gest=trim(upper('{$_POST['resul_gest']}')),meto_fecunda=trim(upper('{$_POST['meto_fecunda']}')),cual_metodo=trim(upper('{$_POST['cual_metodo']}')),asiste_control=trim(upper('{$_POST['asiste_control']}')),vacuna_comple=trim(upper('{$_POST['vacuna_comple']}')),lacmate_comple=trim(upper('{$_POST['lacmate_comple']}')),fec_pruerap2=trim(upper('{$_POST['fec_pruerap2']}')),fec_cargaviral2=trim(upper('{$_POST['fec_cargaviral2']}')),resul_cargaviral2=trim(upper('{$_POST['resul_cargaviral2']}')),asis_provih2=trim(upper('{$_POST['asis_provih2']}')),cual2=trim(upper('{$_POST['cual2']}')),adhe_tra_antirre2=trim(upper('{$_POST['adhe_tra_antirre2']}')),recnac_proxi=trim(upper('{$_POST['recnac_proxi']}')),formu_lact=trim(upper('{$_POST['formu_lact']}')),tarros_mes=trim(upper('{$_POST['tarros_mes']}')),caso_con_tmi=trim(upper('{$_POST['caso_con_tmi']}')),asis_provih_rn=trim(upper('{$_POST['asis_provih_rn']}')),cargaviral_1mes=trim(upper('{$_POST['cargaviral_1mes']}')),fec_cargaviral3=trim(upper('{$_POST['fec_cargaviral3']}')),resultado1=trim(upper('{$_POST['resultado1']}')),cargaviral_4mes=trim(upper('{$_POST['cargaviral_4mes']}')),fec_cargaviral_4mes=trim(upper('{$_POST['fec_cargaviral_4mes']}')),resultado2=trim(upper('{$_POST['resultado2']}')),estrategia_1=trim(upper('{$_POST['estrategia_1']}')),estrategia_2=trim(upper('{$_POST['estrategia_2']}')),acciones_1=trim(upper('{$_POST['acciones_1']}')),desc_accion1=trim(upper('{$_POST['desc_accion1']}')),acciones_2=trim(upper('{$_POST['acciones_2']}')),desc_accion2=trim(upper('{$_POST['desc_accion2']}')),acciones_3=trim(upper('{$_POST['acciones_3']}')),desc_accion3=trim(upper('{$_POST['desc_accion3']}')),activa_ruta=trim(upper('{$_POST['activa_ruta']}')),ruta=trim(upper('{$_POST['ruta']}')),        
    `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
    WHERE id_vihgestacio =TRIM(UPPER('{$id[0]}'))";
    // echo $sql;
  }else if(count($id)==3){
    $sql="INSERT INTO vsp_vihgest VALUES (NULL,trim(upper('{$id[1]}')),trim(upper('{$id[0]}')),
    trim(upper('{$_POST['fecha_seg']}')),trim(upper('{$_POST['numsegui']}')),trim(upper('{$_POST['evento']}')),trim(upper('{$_POST['estado_s']}')),trim(upper('{$_POST['motivo_estado']}')),trim(upper('{$_POST['etapa']}')),trim(upper('{$_POST['sema_gest']}')),trim(upper('{$_POST['asis_ctrpre']}')),trim(upper('{$_POST['exam_lab']}')),trim(upper('{$_POST['esqu_vacuna']}')),trim(upper('{$_POST['cons_micronutr']}')),trim(upper('{$_POST['fec_pruerap1']}')),trim(upper('{$_POST['fec_cargaviral1']}')),trim(upper('{$_POST['resul_cargaviral1']}')),trim(upper('{$_POST['asis_provih1']}')),trim(upper('{$_POST['cual1']}')),trim(upper('{$_POST['adhe_tra_antirre1']}')),trim(upper('{$_POST['fecha_obstetrica']}')),trim(upper('{$_POST['edad_gesta']}')),trim(upper('{$_POST['resul_gest']}')),trim(upper('{$_POST['meto_fecunda']}')),trim(upper('{$_POST['cual_metodo']}')),trim(upper('{$_POST['asiste_control']}')),trim(upper('{$_POST['vacuna_comple']}')),trim(upper('{$_POST['lacmate_comple']}')),trim(upper('{$_POST['fec_pruerap2']}')),trim(upper('{$_POST['fec_cargaviral2']}')),trim(upper('{$_POST['resul_cargaviral2']}')),trim(upper('{$_POST['asis_provih2']}')),trim(upper('{$_POST['cual2']}')),trim(upper('{$_POST['adhe_tra_antirre2']}')),trim(upper('{$_POST['recnac_proxi']}')),trim(upper('{$_POST['formu_lact']}')),trim(upper('{$_POST['tarros_mes']}')),trim(upper('{$_POST['caso_con_tmi']}')),trim(upper('{$_POST['asis_provih_rn']}')),trim(upper('{$_POST['cargaviral_1mes']}')),trim(upper('{$_POST['fec_cargaviral3']}')),trim(upper('{$_POST['resultado1']}')),trim(upper('{$_POST['cargaviral_4mes']}')),trim(upper('{$_POST['fec_cargaviral_4mes']}')),trim(upper('{$_POST['resultado2']}')),trim(upper('{$_POST['estrategia_1']}')),trim(upper('{$_POST['estrategia_2']}')),trim(upper('{$_POST['acciones_1']}')),trim(upper('{$_POST['desc_accion1']}')),trim(upper('{$_POST['acciones_2']}')),trim(upper('{$_POST['desc_accion2']}')),trim(upper('{$_POST['acciones_3']}')),trim(upper('{$_POST['desc_accion3']}')),trim(upper('{$_POST['activa_ruta']}')),trim(upper('{$_POST['ruta']}')),trim(upper('{$_POST['novedades']}')),trim(upper('{$_POST['signos_covid']}')),trim(upper('{$_POST['caso_afirmativo']}')),trim(upper('{$_POST['otras_condiciones']}')),trim(upper('{$_POST['observaciones']}')),trim(upper('{$_POST['cierre_caso']}')),trim(upper('{$_POST['fecha_cierre']}')),trim(upper('{$_POST['redu_riesgo_cierre']}')),
    TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
    // echo $sql;
  }
    $rta=dato_mysql($sql);
    return $rta;
  } 


  function get_vihgest(){
    if($_REQUEST['id']==''){
      return "";
    }else{
      $id=divide($_REQUEST['id']);
      $sql="SELECT concat(id_vihgestacio,'_',tipo_doc,'_',documento,'_',numsegui,'_',evento),
      fecha_seg,numsegui,evento,estado_s,motivo_estado,etapa,sema_gest,asis_ctrpre,exam_lab,esqu_vacuna,cons_micronutr,fec_pruerap1,fec_cargaviral1,resul_cargaviral1,asis_provih1,cual1,adhe_tra_antirre1,fecha_obstetrica,edad_gesta,resul_gest,meto_fecunda,cual_metodo,asiste_control,vacuna_comple,lacmate_comple,fec_pruerap2,fec_cargaviral2,resul_cargaviral2,asis_provih2,cual2,adhe_tra_antirre2,recnac_proxi,formu_lact,tarros_mes,caso_con_tmi,asis_provih_rn,cargaviral_1mes,fec_cargaviral3,resultado1,cargaviral_4mes,fec_cargaviral_4mes,resultado2,estrategia_1,estrategia_2,acciones_1,desc_accion1,acciones_2,desc_accion2,acciones_3,desc_accion3,activa_ruta,ruta,novedades,signos_covid,caso_afirmativo,otras_condiciones,observaciones,cierre_caso,fecha_cierre,redu_riesgo_cierre
      FROM vsp_vihgest
      WHERE id_vihgestacio ='{$id[0]}'";
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
	if ($a=='vihgest-lis' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";	
		$rta.="<li class='icono editar' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'vihgest',event,this,['fecha_seg','numsegui','evento','estado_s','motivo_estado'],'vihgest.php');\"></li>";
	}
	
 return $rta;
}


function bgcolor($a,$c,$f='c'){
  $rta="";
  return $rta;
   }