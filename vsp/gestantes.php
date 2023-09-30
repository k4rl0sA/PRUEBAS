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



function focus_gestantes(){
  return 'gestantes';
 }
 
 
 function men_gestantes(){
  $rta=cap_menus('gestantes','pro');
  return $rta;
 }
 
 
 function cap_menus($a,$b='cap',$con='con') {
   $rta = ""; 
   $acc=rol($a);
   $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
   $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  
   return $rta;
 }


 FUNCTION seg_gestantes(){
	// var_dump($_POST['id']);
	$id=divide($_POST['id']);
	$sql="SELECT `id_gestante` ACCIONES,
tipo_doc,documento,fecha_seg Fecha,numsegui Seguimiento,FN_CATALOGODESC(87,evento) EVENTO,FN_CATALOGODESC(73,estado_s) estado,cierre_caso Cierra,
fecha_cierre 'Fecha de Cierre',nombre Creó 
FROM vsp_gestantes A
	LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario ";
$sql.="WHERE tipo_doc='".$id[1]."' AND documento='".$id[0];
	$sql.="' ORDER BY fecha_create";
	// echo $sql;
	$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"gestantes-lis",5);
   }


function cmp_gestantes(){
	$rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div>
	<div class='contenido' id='gestantes-lis'>".seg_gestantes()."</div></div>";
	$w='gestantes';
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
  

	$c[]=new cmp('id_gestante','h','50',$_POST['id'],$w.' '.$o,'','id_gestante',null,null,false,true,'','col-2');
  $c[]=new cmp('fecha_seg','d','10',$d,$w.' '.$o,'Fecha Seguimiento','fecha_seg',null,null,true,true,'','col-2','validDate(this,-2,0)');
  $c[]=new cmp('numsegui','s','3',$d,$w.' '.$o,'Seguimiento N°','numsegui',null,null,true,true,'','col-2');
  $c[]=new cmp('evento','s','3',$ev,$w.' '.$o,'Evento','evento',null,null,false,false,'','col-2');
  $c[]=new cmp('estado_s','s','3',$d,$w.' '.$o,'Estado','estado_s',null,null,true,true,'','col-2',"enabFielSele(this,true,['motivo_estado'],['3']);EnabEfec(this,['hab','acc'],['Ob'],['nO'],['bL']);");
  $c[]=new cmp('motivo_estado','s','3',$d,$w.' '.$o,'Motivo de Estado','motivo_estado',null,null,false,$x,'','col-2');
  $c[]=new cmp('etapa','s','3',$d,$w.' '.$o,'Etapa','etapa',null,null,false,true,'','col-15');
  
  $o='hab';
  $c[]=new cmp($o,'e',null,'INFORMACIÓN GESTANTE',$w);
    $c[]=new cmp('sema_gest','s','3',$d,$w.' '.$o,'Sema de Gest','sema_gest',null,null,false,$x,'','col-2');
    $c[]=new cmp('asis_ctrpre','s','2',$d,$w.' '.$o,'Asis de Ctrpre','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('exam_lab','s','2',$d,$w.' '.$o,'Exam de Lab','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('esqu_vacuna','s','3',$d,$w.' '.$o,'Esqu de Vacuna','esqu_vacuna',null,null,false,$x,'','col-2');
    $c[]=new cmp('cons_micronutr','s','2',$d,$w.' '.$o,'Cons de Micronutr','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('peso','','3,2',$d,$w.' '.$o,'Peso','peso',null,null,false,$x,'','col-2');
    $c[]=new cmp('talla','n','3',$d,$w.' '.$o,'Talla','talla',null,null,false,$x,'','col-2');
    $c[]=new cmp('imc','','3,2',$d,$w.' '.$o,'Imc','imc',null,null,false,$x,'','col-2');
    $c[]=new cmp('clas_nutri','s','3',$d,$w.' '.$o,'Clas de Nutri','clas_nutri',null,null,false,$x,'','col-2');
    $c[]=new cmp('gana_peso','s','2',$d,$w.' '.$o,'Gana de Peso','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('cant_ganapesosem','s','3',$d,$w.' '.$o,'Cant de Ganapesosem','cant_ganapesosem',null,null,false,$x,'','col-2');
    $c[]=new cmp('ante_patogest','s','3',$d,$w.' '.$o,'Ante de Patogest','ante_patogest',null,null,false,$x,'','col-2');
    $c[]=new cmp('num_frutas','s','2',$d,$w.' '.$o,'Num de Frutas','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('num_carnes','s','3',$d,$w.' '.$o,'Num de Carnes','num_carnes',null,null,false,$x,'','col-2');
    $c[]=new cmp('num_azucar','s','3',$d,$w.' '.$o,'Num de Azucar','num_azucar',null,null,false,$x,'','col-2');
    $c[]=new cmp('cant_actifisica','s','3',$d,$w.' '.$o,'Cant de Actifisica','cant_actifisica',null,null,false,$x,'','col-2');
    $c[]=new cmp('adop_recomenda','s','2',$d,$w.' '.$o,'Adop de Recomenda','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('apoy_alim','s','2',$d,$w.' '.$o,'Apoy de Alim','rta',null,null,false,$x,'','col-2');

    $o='hab';
    $c[]=new cmp($o,'e',null,'INFORMACIÓN PUERPERIA',$w);
    $c[]=new cmp('fecha_obstetrica','d','10',$d,$w.' '.$o,'Fecha de Obstetrica','fecha_obstetrica',null,null,false,$x,'','col-2');
    $c[]=new cmp('edad_gesta','s','3',$d,$w.' '.$o,'Edad de Gesta','edad_gesta',null,null,false,$x,'','col-2');
    $c[]=new cmp('resul_gest','s','2',$d,$w.' '.$o,'Resul de Gest','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('meto_fecunda','s','2',$d,$w.' '.$o,'Meto de Fecunda','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('cual','s','3',$d,$w.' '.$o,'Cual','cual',null,null,false,$x,'','col-2');
    $c[]=new cmp('peso_nacer','n','4',$d,$w.' '.$o,'Peso de Nacer','peso_nacer',null,null,false,$x,'','col-2');
    $c[]=new cmp('asiste_control','s','2',$d,$w.' '.$o,'Asiste de Control','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('vacuna_comple','s','2',$d,$w.' '.$o,'Vacuna de Comple','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('lacmate_exclu','s','2',$d,$w.' '.$o,'Lacmate de Exclu','rta',null,null,false,$x,'','col-2');

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
function opc_esqu_vacuna($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
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
function opc_etapa($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
  }
  function opc_sema_gest($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
  }
  function opc_asis_ctrpre($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
  }
  function opc_clas_nutri($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
  }
  function opc_cant_ganapesosem($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
  }
  function opc_ante_patogest($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
  }
  function opc_num_carnes($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
  }
  function opc_num_azucar($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
  }
  function opc_cant_actifisica($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
  }
  function opc_edad_gesta($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
  }
  function opc_cual($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo= and estado='A' ORDER BY 1",$id);
  }

function gra_gestantes(){
  // print_r($_POST);
  $id=divide($_POST['id_gestante']);
  if(count($id)==5){
    $sql="UPDATE vsp_gestantes SET 
   etapa=trim(upper('{$_POST['etapa']}')),sema_gest=trim(upper('{$_POST['sema_gest']}')),asis_ctrpre=trim(upper('{$_POST['asis_ctrpre']}')),exam_lab=trim(upper('{$_POST['exam_lab']}')),esqu_vacuna=trim(upper('{$_POST['esqu_vacuna']}')),cons_micronutr=trim(upper('{$_POST['cons_micronutr']}')),peso=trim(upper('{$_POST['peso']}')),talla=trim(upper('{$_POST['talla']}')),imc=trim(upper('{$_POST['imc']}')),clas_nutri=trim(upper('{$_POST['clas_nutri']}')),gana_peso=trim(upper('{$_POST['gana_peso']}')),cant_ganapesosem=trim(upper('{$_POST['cant_ganapesosem']}')),ante_patogest=trim(upper('{$_POST['ante_patogest']}')),num_frutas=trim(upper('{$_POST['num_frutas']}')),num_carnes=trim(upper('{$_POST['num_carnes']}')),num_azucar=trim(upper('{$_POST['num_azucar']}')),cant_actifisica=trim(upper('{$_POST['cant_actifisica']}')),adop_recomenda=trim(upper('{$_POST['adop_recomenda']}')),apoy_alim=trim(upper('{$_POST['apoy_alim']}')),fecha_obstetrica=trim(upper('{$_POST['fecha_obstetrica']}')),edad_gesta=trim(upper('{$_POST['edad_gesta']}')),resul_gest=trim(upper('{$_POST['resul_gest']}')),meto_fecunda=trim(upper('{$_POST['meto_fecunda']}')),cual=trim(upper('{$_POST['cual']}')),peso_nacer=trim(upper('{$_POST['peso_nacer']}')),asiste_control=trim(upper('{$_POST['asiste_control']}')),vacuna_comple=trim(upper('{$_POST['vacuna_comple']}')),lacmate_exclu=trim(upper('{$_POST['lacmate_exclu']}')),estrategia_1=trim(upper('{$_POST['estrategia_1']}')),estrategia_2=trim(upper('{$_POST['estrategia_2']}')),acciones_1=trim(upper('{$_POST['acciones_1']}')),desc_accion1=trim(upper('{$_POST['desc_accion1']}')),acciones_2=trim(upper('{$_POST['acciones_2']}')),desc_accion2=trim(upper('{$_POST['desc_accion2']}')),acciones_3=trim(upper('{$_POST['acciones_3']}')),desc_accion3=trim(upper('{$_POST['desc_accion3']}')),activa_ruta=trim(upper('{$_POST['activa_ruta']}')),ruta=trim(upper('{$_POST['ruta']}')),novedades=trim(upper('{$_POST['novedades']}')),signos_covid=trim(upper('{$_POST['signos_covid']}')),caso_afirmativo=trim(upper('{$_POST['caso_afirmativo']}')),otras_condiciones=trim(upper('{$_POST['otras_condiciones']}')),observaciones=trim(upper('{$_POST['observaciones']}')),cierre_caso=trim(upper('{$_POST['cierre_caso']}')),fecha_cierre=trim(upper('{$_POST['fecha_cierre']}')),redu_riesgo_cierre=trim(upper('{$_POST['redu_riesgo_cierre']}')),
    `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
    WHERE id_gestante =TRIM(UPPER('{$id[0]}'))";
      // echo $sql;
  }else if(count($id)==3){
    $sql="INSERT INTO vsp_gestantes VALUES (NULL,trim(upper('{$id[1]}')),trim(upper('{$id[0]}')),
   trim(upper('{$_POST['fecha_seg']}')),trim(upper('{$_POST['numsegui']}')),trim(upper('{$_POST['evento']}')),trim(upper('{$_POST['estado_s']}')),trim(upper('{$_POST['motivo_estado']}')),trim(upper('{$_POST['etapa']}')),trim(upper('{$_POST['sema_gest']}')),trim(upper('{$_POST['asis_ctrpre']}')),trim(upper('{$_POST['exam_lab']}')),trim(upper('{$_POST['esqu_vacuna']}')),trim(upper('{$_POST['cons_micronutr']}')),trim(upper('{$_POST['peso']}')),trim(upper('{$_POST['talla']}')),trim(upper('{$_POST['imc']}')),trim(upper('{$_POST['clas_nutri']}')),trim(upper('{$_POST['gana_peso']}')),trim(upper('{$_POST['cant_ganapesosem']}')),trim(upper('{$_POST['ante_patogest']}')),trim(upper('{$_POST['num_frutas']}')),trim(upper('{$_POST['num_carnes']}')),trim(upper('{$_POST['num_azucar']}')),trim(upper('{$_POST['cant_actifisica']}')),trim(upper('{$_POST['adop_recomenda']}')),trim(upper('{$_POST['apoy_alim']}')),trim(upper('{$_POST['fecha_obstetrica']}')),trim(upper('{$_POST['edad_gesta']}')),trim(upper('{$_POST['resul_gest']}')),trim(upper('{$_POST['meto_fecunda']}')),trim(upper('{$_POST['cual']}')),trim(upper('{$_POST['peso_nacer']}')),trim(upper('{$_POST['asiste_control']}')),trim(upper('{$_POST['vacuna_comple']}')),trim(upper('{$_POST['lacmate_exclu']}')),trim(upper('{$_POST['estrategia_1']}')),trim(upper('{$_POST['estrategia_2']}')),trim(upper('{$_POST['acciones_1']}')),trim(upper('{$_POST['desc_accion1']}')),trim(upper('{$_POST['acciones_2']}')),trim(upper('{$_POST['desc_accion2']}')),trim(upper('{$_POST['acciones_3']}')),trim(upper('{$_POST['desc_accion3']}')),trim(upper('{$_POST['activa_ruta']}')),trim(upper('{$_POST['ruta']}')),trim(upper('{$_POST['novedades']}')),trim(upper('{$_POST['signos_covid']}')),trim(upper('{$_POST['caso_afirmativo']}')),trim(upper('{$_POST['otras_condiciones']}')),trim(upper('{$_POST['observaciones']}')),trim(upper('{$_POST['cierre_caso']}')),trim(upper('{$_POST['fecha_cierre']}')),trim(upper('{$_POST['redu_riesgo_cierre']}')),
      TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
      // echo $sql;
    }
      $rta=dato_mysql($sql);
      return $rta;
  } 

  function get_gestantes(){
    if($_REQUEST['id']==''){
      return "";
    }else{
      $id=divide($_REQUEST['id']);
      $sql="SELECT concat(id_gestante,'_',tipo_doc,'_',documento,'_',numsegui,'_',evento),
      fecha_seg,numsegui,evento,estado_s,motivo_estado,etapa,sema_gest,asis_ctrpre,exam_lab,esqu_vacuna,cons_micronutr,peso,talla,imc,clas_nutri,gana_peso,cant_ganapesosem,ante_patogest,num_frutas,num_carnes,num_azucar,cant_actifisica,adop_recomenda,apoy_alim,fecha_obstetrica,edad_gesta,resul_gest,meto_fecunda,cual,peso_nacer,asiste_control,vacuna_comple,lacmate_exclu,estrategia_1,estrategia_2,acciones_1,desc_accion1,acciones_2,desc_accion2,acciones_3,desc_accion3,activa_ruta,ruta,novedades,signos_covid,caso_afirmativo,otras_condiciones,observaciones,cierre_caso,fecha_cierre,redu_riesgo_cierre
      FROM vsp_gestantes
      WHERE id_gestante ='{$id[0]}'";
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
	if ($a=='gestantes-lis' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";	
    $rta.="<li class='icono editar' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'gestantes',event,this,['fecha_seg','numsegui','evento','estado_s','motivo_estado'],'gestantes.php');\"></li>";
	}
 return $rta;
}


function bgcolor($a,$c,$f='c'){
  $rta="";
  return $rta;
   }