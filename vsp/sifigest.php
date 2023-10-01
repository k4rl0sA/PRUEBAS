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



function focus_sifigest(){
  return 'sifigest';
 }
 
 
 function men_sifigest(){
  $rta=cap_menus('sifigest','pro');
  return $rta;
 }
 
 
 function cap_menus($a,$b='cap',$con='con') {
   $rta = ""; 
   $acc=rol($a);
   $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
   $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";

   return $rta;
 }


 FUNCTION seg_sifigest(){
	// var_dump($_POST['id']);
	$id=divide($_POST['id']);
	$sql="SELECT `id_sifigest` ACCIONES,
  tipo_doc,documento,fecha_seg Fecha,numsegui Seguimiento,FN_CATALOGODESC(87,evento) EVENTO,FN_CATALOGODESC(73,estado_s) estado,cierre_caso Cierra,
    fecha_cierre 'Fecha de Cierre',nombre Creó 
FROM vsp_sifigest A
	LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario ";
	$sql.="WHERE tipo_doc='".$id[1]."' AND documento='".$id[0];
	$sql.="' ORDER BY fecha_create";
	// echo $sql;
	$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"sifigest-lis",5);
   }


function cmp_sifigest(){
	$rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div>
	<div class='contenido' id='sifigest-lis'>".seg_sifigest()."</div></div>";
	$w='sifigest';
  $d='';
	$o='inf';
  // $nb='disa oculto';
  $ob='Ob';
  $no='nO';
  $bl='bL';
  $x=false;
  $block=['hab','acc','infpue','infacc'];
  $event=divide($_POST['id']);
  $ev=$event[3];

	$c[]=new cmp('id_sifigest','h','50',$_POST['id'],$w.' '.$o,'Id de sifigest','id_sifigest',null,null,false,false,'','col-2');
  $c[]=new cmp('fecha_seg','d','10',$d,$w.' '.$o,'Fecha Seguimiento','fecha_seg',null,null,true,true,'','col-2','validDate(this,-2,0)');
  $c[]=new cmp('numsegui','s','3',$d,$w.' '.$o,'Seguimiento N°','numsegui',null,null,true,true,'','col-2');
  $c[]=new cmp('evento','s','3',$ev,$w.' '.$o,'Evento','evento',null,null,false,false,'','col-2');
  $c[]=new cmp('estado_s','s','3',$d,$w.' '.$o,'Estado','estado_s',null,null,true,true,'','col-2',"enabFielSele(this,true,['motivo_estado'],['3']);EnabEfec(this,['hab','acc','infpue','infacc'],['Ob'],['nO'],['bL']);");
  $c[]=new cmp('motivo_estado','s','3',$d,$w.' '.$o,'Motivo de Estado','motivo_estado',null,null,false,$x,'','col-2');
  
    $c[]=new cmp('etapa','s','3',$d,$w.' '.$o,'Etapa','etapa',null,null,false,true,'','col-2');
    $c[]=new cmp('sema_gest','s','3',$d,$w.' '.$o,'Semanas De Gestación/ Días Pos-Evento','sema_gest',null,null,false,true,'','col-3');
    

    $o='hab';
    $c[]=new cmp($o,'e',null,'GESTANTES ',$w);
    $c[]=new cmp('asis_ctrpre','s','2',$d,$w.' '.$o,'¿Asiste A Controles Prenatales?','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('exam_lab','s','2',$d,$w.' '.$o,'¿Cuenta Con Exámenes De Laboratorio Al Día?','rta',null,null,false,$x,'','col-3');
    $c[]=new cmp('esqu_vacuna','s','3',$d,$w.' '.$o,'¿Tiene Esquema De Vacunación Completo?','rta',null,null,false,$x,'','col-3');
    $c[]=new cmp('cons_micronutr','s','2',$d,$w.' '.$o,'¿Consume Micronutrientes?','rta',null,null,false,$x,'','col-2');
    
    
    $o='infpue';
    $c[]=new cmp($o,'e',null,'DESPUES DE LA GESTACION (PUERPERIO Y/O POSTERIOR AL PUERPERIO) ',$w);
    $c[]=new cmp('fecha_obstetrica','d','10',$d,$w.' '.$o,'Fecha Evento Obstetrico','fecha_obstetrica',null,null,false,$x,'','col-3');
    $c[]=new cmp('edad_gesta','s','3',$d,$w.' '.$o,'Edad gestacional en el momento del evento obstetrico','edad_gesta',null,null,false,$x,'','col-4');
    $c[]=new cmp('resul_gest','s','3',$d,$w.' '.$o,'Resultado de la gestación','resul_gest',null,null,false,$x,'','col-3');
    $c[]=new cmp('meto_fecunda','s','2',$d,$w.' '.$o,'¿Cuenta Con Método de Regulación de la fecundidad?','rta',null,null,false,$x,'','col-35');
    $c[]=new cmp('cual','s','3',$d,$w.' '.$o,'¿Cuál?','cual',null,null,false,$x,'','col-3');
    $c[]=new cmp('confir_sificong','s','2',$d,$w.' '.$o,'¿Es un caso confirmado de sífilis congénita?','rta',null,null,false,$x,'','col-35');
    $c[]=new cmp('resul_ser_recnac','s','3',$d,$w.' '.$o,'Resultado de serológia del recién nacido','resul_ser_recnac',null,null,false,$x,'','col-3');
    $c[]=new cmp('trata_recnac','s','3',$d,$w.' '.$o,'Tratamiento Del Recién Nacido','trata_recnac',null,null,false,$x,'','col-3');
    $c[]=new cmp('fec_conser_1tri2','d','10',$d,$w.' '.$o,'Fecha Control Serológico 3 Meses11','fec_conser_1tri2',null,null,false,$x,'','col-2');
    $c[]=new cmp('resultado','s','3',$d,$w.' '.$o,'Resultado12','resultado',null,null,false,$x,'','col-2');
    
    $o='infacc';
    $c[]=new cmp($o,'e',null,'GESTANTE Y/O PUERPERA) ',$w);
    $c[]=new cmp('ctrl_serol1t','s','2',$d,$w.' '.$o,'Control Serológico 1er Trimestre?','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('fec_conser_1tri1','d','10',$d,$w.' '.$o,'Fecha Control Serológico 1er Trimestre','fec_conser_1tri1',null,null,false,$x,'','col-2');
    $c[]=new cmp('resultado_1','s','3',$d,$w.' '.$o,'Resultado 1','resultado_1',null,null,false,$x,'','col-1');
    $c[]=new cmp('ctrl_serol2t','s','2',$d,$w.' '.$o,'Control Serológico 2do Trimestre','rta',null,null,false,$x,'','col-35');
    $c[]=new cmp('fec_conser_2tri','d','10',$d,$w.' '.$o,'Fecha Control Serológico 2do Trimestre','fec_conser_2tri',null,null,false,$x,'','col-25');
    $c[]=new cmp('resultado_2','s','3',$d,$w.' '.$o,'Resultado 2','resultado_2',null,null,false,$x,'','col-1');
    $c[]=new cmp('ctrl_serol3t','s','2',$d,$w.' '.$o,'Fecha Control Serológico 3er Trimestre','rta',null,null,false,$x,'','col-35');
    $c[]=new cmp('fec_conser_3tri','d','10',$d,$w.' '.$o,'Control Serológico 3er Trimestre','fec_conser_3tri',null,null,false,$x,'','col-2');
    $c[]=new cmp('resultado_3','s','3',$d,$w.' '.$o,'Resultado 3','resultado_3',null,null,false,$x,'','col-1');
    
    $c[]=new cmp('fec_1dos_trages1','d','10',$d,$w.' '.$o,'Fecha Primera Dosis De Tratamiento De La Gestante','fec_1dos_trages1',null,null,false,$x,'','col-35');
    $c[]=new cmp('fec_2dos_trages1','d','10',$d,$w.' '.$o,'Fecha Segunda Dosis De Tratamiento De La Gestante','fec_2dos_trages1',null,null,false,$x,'','col-35');
    $c[]=new cmp('fec_3dos_trages1','d','10',$d,$w.' '.$o,'Fecha Tercera Dosis De Tratamiento De La Gestante','fec_3dos_trages1',null,null,false,$x,'','col-3');
    
    $c[]=new cmp('pri_con_sex','s','3',$d,$w.' '.$o,'Primer Contacto Sexual','pri_con_sex',null,null,false,$x,'','col-25');
    
    $c[]=new cmp('fec_apl_tra_1dos1','d','10',$d,$w.' '.$o,'Fecha Aplicación Tratamiento Primera Dosis','fec_apl_tra_1dos1',null,null,false,$x,'','col-25');
    $c[]=new cmp('fec_apl_tra_2dos1','d','10',$d,$w.' '.$o,'Fecha Aplicación Tratamiento Segunda Dosis','fec_apl_tra_2dos1',null,null,false,$x,'','col-25');
    $c[]=new cmp('fec_apl_tra_3dos1','d','10',$d,$w.' '.$o,'Fecha Aplicación Tratamiento Tercera Dosis','fec_apl_tra_3dos1',null,null,false,$x,'','col-25');
    
    $c[]=new cmp('seg_con_sex','s','3',$d,$w.' '.$o,'Segundo Contacto Sexual','seg_con_sex',null,null,false,$x,'','col-25');
    $c[]=new cmp('fec_apl_tra_1dos2','d','10',$d,$w.' '.$o,'Fecha Aplicación  Tratamiento Primera Dosis4','fec_apl_tra_1dos2',null,null,false,$x,'','col-25');
    $c[]=new cmp('fec_apl_tra_2dos2','d','10',$d,$w.' '.$o,'Fecha Aplicación Tratamiento Segunda Dosis5','fec_apl_tra_2dos2',null,null,false,$x,'','col-25');
    $c[]=new cmp('fec_apl_tra_3dos2','d','10',$d,$w.' '.$o,'Fecha Aplicación Tratamiento Tercera Dosis6','fec_apl_tra_3dos2',null,null,false,$x,'','col-25');
    
    $c[]=new cmp('prese_reinfe','s','2',$d,$w.' '.$o,'¿Presenta Reinfección?','rta',null,null,false,$x,'','col-25');
    $c[]=new cmp('fec_1dos_trages2','d','10',$d,$w.' '.$o,'Primera Dosis De Tratamiento De La Gestante7','fec_1dos_trages2',null,null,false,$x,'','col-25');
    $c[]=new cmp('fec_2dos_trages2','d','10',$d,$w.' '.$o,'Segunda Dosis De Tratamiento De La Gestante8','fec_2dos_trages2',null,null,false,$x,'','col-25');
    $c[]=new cmp('fec_3dos_trages2','d','10',$d,$w.' '.$o,'Tercera Dosis De Tratamiento De La Gestante9','fec_3dos_trages2',null,null,false,$x,'','col-25');
    
    $c[]=new cmp('fec_1dos_trapar','d','10',$d,$w.' '.$o,'Fecha Primera Dosis De Tratamiento de la Pareja','fec_1dos_trapar',null,null,false,$x,'','col-35');
    $c[]=new cmp('fec_2dos_trapar','d','10',$d,$w.' '.$o,'Fecha Segunda Dosis De Tratamiento de la Pareja','fec_2dos_trapar',null,null,false,$x,'','col-35');
    $c[]=new cmp('fec_3dos_trapar','d','10',$d,$w.' '.$o,'Fecha Tercera Dosis De Tratamiento de la Pareja','fec_3dos_trapar',null,null,false,$x,'','col-3');
   
       
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
function opc_etapa($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='136' and estado='A' ORDER BY 1",$id);
}
function opc_sema_gest($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='137' and estado='A' ORDER BY LENGTH(idcatadeta)",$id);
}
function opc_resultado_1($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='' and estado='A' ORDER BY 1",$id);
}
function opc_resultado_2($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='' and estado='A' ORDER BY 1",$id);
}
function opc_resultado_3($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='' and estado='A' ORDER BY 1",$id);
}
function opc_pri_con_sex($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='' and estado='A' ORDER BY 1",$id);
}
function opc_seg_con_sex($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='' and estado='A' ORDER BY 1",$id);
}
function opc_edad_gesta($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='' and estado='A' ORDER BY 1",$id);
}
function opc_resul_gest($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='' and estado='A' ORDER BY 1",$id);
}
function opc_cual($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='' and estado='A' ORDER BY 1",$id);
}
function opc_resul_ser_recnac($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='' and estado='A' ORDER BY 1",$id);
}
function opc_trata_recnac($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='' and estado='A' ORDER BY 1",$id);
}
function opc_resultado($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo='' and estado='A' ORDER BY 1",$id);
}
function opc_ruta($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=79 and estado='A' ORDER BY 1",$id);
}
function opc_novedades($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=77 and estado='A' ORDER BY 1",$id);
}

function gra_sifigest(){
  // print_r($_POST);
  $id=divide($_POST['id_sifigest']);
  if(count($id)==5){
    $sql="UPDATE vsp_sifigest SET 
    etapa=trim(upper('{$_POST['etapa']}')),sema_gest=trim(upper('{$_POST['sema_gest']}')),asis_ctrpre=trim(upper('{$_POST['asis_ctrpre']}')),exam_lab=trim(upper('{$_POST['exam_lab']}')),esqu_vacuna=trim(upper('{$_POST['esqu_vacuna']}')),cons_micronutr=trim(upper('{$_POST['cons_micronutr']}')),fec_conser_1tri1=trim(upper('{$_POST['fec_conser_1tri1']}')),resultado_1=trim(upper('{$_POST['resultado_1']}')),fec_conser_2tri=trim(upper('{$_POST['fec_conser_2tri']}')),resultado_2=trim(upper('{$_POST['resultado_2']}')),fec_conser_3tri=trim(upper('{$_POST['fec_conser_3tri']}')),resultado_3=trim(upper('{$_POST['resultado_3']}')),fec_1dos_trages1=trim(upper('{$_POST['fec_1dos_trages1']}')),fec_2dos_trages1=trim(upper('{$_POST['fec_2dos_trages1']}')),fec_3dos_trages1=trim(upper('{$_POST['fec_3dos_trages1']}')),pri_con_sex=trim(upper('{$_POST['pri_con_sex']}')),fec_apl_tra_1dos1=trim(upper('{$_POST['fec_apl_tra_1dos1']}')),fec_apl_tra_2dos1=trim(upper('{$_POST['fec_apl_tra_2dos1']}')),fec_apl_tra_3dos1=trim(upper('{$_POST['fec_apl_tra_3dos1']}')),seg_con_sex=trim(upper('{$_POST['seg_con_sex']}')),fec_apl_tra_1dos2=trim(upper('{$_POST['fec_apl_tra_1dos2']}')),fec_apl_tra_2dos2=trim(upper('{$_POST['fec_apl_tra_2dos2']}')),fec_apl_tra_3dos2=trim(upper('{$_POST['fec_apl_tra_3dos2']}')),prese_reinfe=trim(upper('{$_POST['prese_reinfe']}')),fec_1dos_trages2=trim(upper('{$_POST['fec_1dos_trages2']}')),fec_2dos_trages2=trim(upper('{$_POST['fec_2dos_trages2']}')),fec_3dos_trages2=trim(upper('{$_POST['fec_3dos_trages2']}')),fec_1dos_trapar=trim(upper('{$_POST['fec_1dos_trapar']}')),fec_2dos_trapar=trim(upper('{$_POST['fec_2dos_trapar']}')),fec_3dos_trapar=trim(upper('{$_POST['fec_3dos_trapar']}')),fecha_obstetrica=trim(upper('{$_POST['fecha_obstetrica']}')),edad_gesta=trim(upper('{$_POST['edad_gesta']}')),resul_gest=trim(upper('{$_POST['resul_gest']}')),meto_fecunda=trim(upper('{$_POST['meto_fecunda']}')),cual=trim(upper('{$_POST['cual']}')),confir_sificong=trim(upper('{$_POST['confir_sificong']}')),resul_ser_recnac=trim(upper('{$_POST['resul_ser_recnac']}')),trata_recnac=trim(upper('{$_POST['trata_recnac']}')),fec_conser_1tri2=trim(upper('{$_POST['fec_conser_1tri2']}')),resultado=trim(upper('{$_POST['resultado']}')),estrategia_1=trim(upper('{$_POST['estrategia_1']}')),estrategia_2=trim(upper('{$_POST['estrategia_2']}')),acciones_1=trim(upper('{$_POST['acciones_1']}')),desc_accion1=trim(upper('{$_POST['desc_accion1']}')),acciones_2=trim(upper('{$_POST['acciones_2']}')),desc_accion2=trim(upper('{$_POST['desc_accion2']}')),acciones_3=trim(upper('{$_POST['acciones_3']}')),desc_accion3=trim(upper('{$_POST['desc_accion3']}')),activa_ruta=trim(upper('{$_POST['activa_ruta']}')),ruta=trim(upper('{$_POST['ruta']}')),novedades=trim(upper('{$_POST['novedades']}')),signos_covid=trim(upper('{$_POST['signos_covid']}')),caso_afirmativo=trim(upper('{$_POST['caso_afirmativo']}')),otras_condiciones=trim(upper('{$_POST['otras_condiciones']}')),observaciones=trim(upper('{$_POST['observaciones']}')),cierre_caso=trim(upper('{$_POST['cierre_caso']}')),fecha_cierre=trim(upper('{$_POST['fecha_cierre']}')),redu_riesgo_cierre=trim(upper('{$_POST['redu_riesgo_cierre']}')),
    `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
    WHERE id_sifigest =TRIM(UPPER('{$id[0]}'))";
    // echo $sql;
  }else if(count($id)==3){
    $sql="INSERT INTO vsp_sifigest VALUES (NULL,trim(upper('{$id[1]}')),trim(upper('{$id[0]}')),
    trim(upper('{$_POST['fecha_seg']}')),trim(upper('{$_POST['numsegui']}')),trim(upper('{$_POST['evento']}')),trim(upper('{$_POST['estado_s']}')),trim(upper('{$_POST['motivo_estado']}')),trim(upper('{$_POST['etapa']}')),trim(upper('{$_POST['sema_gest']}')),trim(upper('{$_POST['asis_ctrpre']}')),trim(upper('{$_POST['exam_lab']}')),trim(upper('{$_POST['esqu_vacuna']}')),trim(upper('{$_POST['cons_micronutr']}')),trim(upper('{$_POST['fec_conser_1tri1']}')),trim(upper('{$_POST['resultado_1']}')),trim(upper('{$_POST['fec_conser_2tri']}')),trim(upper('{$_POST['resultado_2']}')),trim(upper('{$_POST['fec_conser_3tri']}')),trim(upper('{$_POST['resultado_3']}')),trim(upper('{$_POST['fec_1dos_trages1']}')),trim(upper('{$_POST['fec_2dos_trages1']}')),trim(upper('{$_POST['fec_3dos_trages1']}')),trim(upper('{$_POST['pri_con_sex']}')),trim(upper('{$_POST['fec_apl_tra_1dos1']}')),trim(upper('{$_POST['fec_apl_tra_2dos1']}')),trim(upper('{$_POST['fec_apl_tra_3dos1']}')),trim(upper('{$_POST['seg_con_sex']}')),trim(upper('{$_POST['fec_apl_tra_1dos2']}')),trim(upper('{$_POST['fec_apl_tra_2dos2']}')),trim(upper('{$_POST['fec_apl_tra_3dos2']}')),trim(upper('{$_POST['prese_reinfe']}')),trim(upper('{$_POST['fec_1dos_trages2']}')),trim(upper('{$_POST['fec_2dos_trages2']}')),trim(upper('{$_POST['fec_3dos_trages2']}')),trim(upper('{$_POST['fec_1dos_trapar']}')),trim(upper('{$_POST['fec_2dos_trapar']}')),trim(upper('{$_POST['fec_3dos_trapar']}')),trim(upper('{$_POST['fecha_obstetrica']}')),trim(upper('{$_POST['edad_gesta']}')),trim(upper('{$_POST['resul_gest']}')),trim(upper('{$_POST['meto_fecunda']}')),trim(upper('{$_POST['cual']}')),trim(upper('{$_POST['confir_sificong']}')),trim(upper('{$_POST['resul_ser_recnac']}')),trim(upper('{$_POST['trata_recnac']}')),trim(upper('{$_POST['fec_conser_1tri2']}')),trim(upper('{$_POST['resultado']}')),trim(upper('{$_POST['estrategia_1']}')),trim(upper('{$_POST['estrategia_2']}')),trim(upper('{$_POST['acciones_1']}')),trim(upper('{$_POST['desc_accion1']}')),trim(upper('{$_POST['acciones_2']}')),trim(upper('{$_POST['desc_accion2']}')),trim(upper('{$_POST['acciones_3']}')),trim(upper('{$_POST['desc_accion3']}')),trim(upper('{$_POST['activa_ruta']}')),trim(upper('{$_POST['ruta']}')),trim(upper('{$_POST['novedades']}')),trim(upper('{$_POST['signos_covid']}')),trim(upper('{$_POST['caso_afirmativo']}')),trim(upper('{$_POST['otras_condiciones']}')),trim(upper('{$_POST['observaciones']}')),trim(upper('{$_POST['cierre_caso']}')),trim(upper('{$_POST['fecha_cierre']}')),trim(upper('{$_POST['redu_riesgo_cierre']}')),
    TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
    // echo $sql;
  }
    $rta=dato_mysql($sql);
    return $rta;
  } 


  function get_sifigest(){
    if($_REQUEST['id']==''){
      return "";
    }else{
      $id=divide($_REQUEST['id']);
      $sql="SELECT concat(id_sifigest,'_',tipo_doc,'_',documento,'_',numsegui,'_',evento),
      fecha_seg,numsegui,evento,estado_s,motivo_estado,etapa,sema_gest,asis_ctrpre,exam_lab,esqu_vacuna,cons_micronutr,fec_conser_1tri1,resultado_1,fec_conser_2tri,resultado_2,fec_conser_3tri,resultado_3,fec_1dos_trages1,fec_2dos_trages1,fec_3dos_trages1,pri_con_sex,fec_apl_tra_1dos1,fec_apl_tra_2dos1,fec_apl_tra_3dos1,seg_con_sex,fec_apl_tra_1dos2,fec_apl_tra_2dos2,fec_apl_tra_3dos2,prese_reinfe,fec_1dos_trages2,fec_2dos_trages2,fec_3dos_trages2,fec_1dos_trapar,fec_2dos_trapar,fec_3dos_trapar,fecha_obstetrica,edad_gesta,resul_gest,meto_fecunda,cual,confir_sificong,resul_ser_recnac,trata_recnac,fec_conser_1tri2,resultado,estrategia_1,estrategia_2,acciones_1,desc_accion1,acciones_2,desc_accion2,acciones_3,desc_accion3,activa_ruta,ruta,novedades,signos_covid,caso_afirmativo,otras_condiciones,observaciones,cierre_caso,fecha_cierre,redu_riesgo_cierre
      FROM vsp_sifigest
      WHERE id_sifigest ='{$id[0]}'";
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
	if ($a=='sifigest-lis' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";	
		$rta.="<li class='icono editar' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'sifigest',event,this,['fecha_seg','numsegui','evento','estado_s','motivo_estado'],'sifigest.php');\"></li>";
	}
	
 return $rta;
}


function bgcolor($a,$c,$f='c'){
  $rta="";
  return $rta;
   }