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



function focus_apopsicduel(){
  return 'apopsicduel';
 }
 
 
 function men_apopsicduel(){
  $rta=cap_menus('apopsicduel','pro');
  return $rta;
 }
 
 
 function cap_menus($a,$b='cap',$con='con') {
   $rta = ""; 
   $acc=rol($a);
   $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
   $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
 
   return $rta;
 }


 FUNCTION seg_apopsicduel(){
	// var_dump($_POST['id']);
	$id=divide($_POST['id']);
	$sql="SELECT `id_psicduel` ACCIONES, id_psicduel 'Cod Registro',
tipo_doc,documento,fecha_seg Fecha,numsegui Seguimiento,FN_CATALOGODESC(87,evento) EVENTO,FN_CATALOGODESC(73,estado_s) estado,cierre_caso Cierra,
fecha_cierre 'Fecha de Cierre',nombre Creó 
FROM vsp_apopsicduel A
	LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario ";
$sql.="WHERE tipo_doc='".$id[1]."' AND documento='".$id[0];
	$sql.="' ORDER BY fecha_create";
	// echo $sql;
	$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"apopsicduel-lis",5);
   }


function cmp_apopsicduel(){
	$rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div>
	<div class='contenido' id='apopsicduel-lis'>".seg_apopsicduel()."</div></div>";
	$w='apopsicduel';
  $d='';
	$o='inf';
	
	//agregar en todas
  $ob='Ob';
  $no='nO';
  $bl='bL';
  $x=false;
  $event=divide($_POST['id']);
  $ev=$event[3];
   $block=['hab','acc'];
  $event=divide($_POST['id']);
  $ev=$event[3];
  

// $nb='disa oculto';

  $c[]=new cmp('id_psicduel','h','50',$_POST['id'],$w.' '.$o,'id_psicduel',null,null,false,true,'','col-2');
  $c[]=new cmp('fecha_seg','d','10',$d,$w.' '.$o,'Fecha Seguimiento','fecha_seg',null,null,true,true,'','col-2','validDate(this,-60,0);');
  $c[]=new cmp('numsegui','s','3',$d,$w.' '.$o,'Seguimiento N°','numsegui',null,null,true,true,'','col-2',"staEfe('numsegui','sta');EnabEfec(this,['hab','acc'],['Ob'],['nO'],['bL'])");
  $c[]=new cmp('evento','s','3',$ev,$w.' '.$o,'Evento','evento',null,null,false,false,'','col-2');
  $c[]=new cmp('estado_s','s','3',$d,$w.' sTa '.$o,'Estado','estado_s',null,null,true,true,'','col-2',"enabFielSele(this,true,['motivo_estado'],['3']);EnabEfec(this,['hab','acc'],['Ob'],['nO'],['bL']);");
  $c[]=new cmp('motivo_estado','s','3',$d,$w.' '.$o,'Motivo de Estado','motivo_estado',null,null,false,$x,'','col-2');
    
    $o='hab';
    $c[]=new cmp($o,'e',null,'INFORMACIÓN ',$w);
    $c[]=new cmp('causa_duelo','s','',$d,$w.' '.$o,'Causa deL Duelo','causa_duelo',null,null,false,$x,'','col-2');
    $c[]=new cmp('fecha_defun','d','10',$d,$w.' '.$o,'Fecha de Defunción','fecha_defun',null,null,false,$x,'','col-2');
    $c[]=new cmp('parent_fallec','s','3',$d,$w.' '.$o,'Parentesco o Relación con elFallec','parent_fallec',null,null,false,$x,'','col-2');
    $c[]=new cmp('lugar_defun','s','3',$d,$w.' '.$o,'Lugar de Defunción','lugar_defun',null,null,false,$x,'','col-2');
    $c[]=new cmp('vincu_afect','s','3',$d,$w.' '.$o,'Vinculo Afectivo con el Caso','vincu_afect',null,null,false,$x,'','col-2');
    $c[]=new cmp('senti_ident_1','s','3',$d,$w.' '.$o,'Sentimientos y Emociones Identificados 1','senti_ident_1',null,null,false,$x,'','col-2');
    $c[]=new cmp('senti_ident_2','s','3',$d,$w.' '.$o,'Sentimientos y Emociones Identificados 2','senti_ident_2',null,null,false,$x,'','col-2');
    $c[]=new cmp('senti_ident_3','s','3',$d,$w.' '.$no.' '.$o,'Sentimientos y Emociones Identificados 3','senti_ident_3',null,null,false,$x,'','col-2');
    $c[]=new cmp('etapa_duelo','s','3',$d,$w.' '.$o,'Etapa del Duelo','etapa_duelo',null,null,false,$x,'','col-2');
    $c[]=new cmp('sintoma_duelo_1','s','3',$d,$w.' '.$o,'Síntomas Asociados al Duleo que Generan Malestar 1','sintoma_duelo_1',null,null,false,$x,'','col-2');
    $c[]=new cmp('sintoma_duelo_2','s','3',$d,$w.' '.$o,'Síntomas Asociados al Duleo que Generan Malestar 2','sintoma_duelo_2',null,null,false,$x,'','col-2');
    $c[]=new cmp('sintoma_duelo_3','s','3',$d,$w.' '.$no.' '.$o,'Síntomas Asociados al Duleo que Generan Malestar 3','sintoma_duelo_3',null,null,false,$x,'','col-2');
    
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
    $c[]=new cmp('cierre_caso','s','2',$d,$w.' '.$ob.' '.$o,'Cierre de Caso','rta',null,null,true,true,'','col-2','enabFincas(this,\'cc\');');
    //igual
    $c[]=new cmp('motivo_cierre','s','2',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Motivo Cierre','motivo_cierre',null,null,false,$x,'','col-55');
    $c[]=new cmp('fecha_cierre','d','10',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Fecha de Cierre','fecha_cierre',null,null,false,$x,'','col-25');
    $c[]=new cmp('liker_dificul','s','3',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Liker Factores Psicosociales','liker_dificul',null,null,false,$x,'','col-25');
    $c[]=new cmp('liker_emocion','s','3',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Liker Expresion Emocional','liker_emocion',null,null,false,$x,'','col-25');
    $c[]=new cmp('liker_decision','s','3',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Liker Manejo del Dolor','liker_decision',null,null,false,$x,'','col-25');
    $c[]=new cmp('redu_riesgo_cierre','s','2',$d,$w.' cc '.$bl.' '.$no.' '.$o,'¿Reduccion del riesgo?','rta',null,null,false,$x,'','col-25');
    $c[]=new cmp('users_bina[]','m','60',$d,$w.' '.$ob.' '.$o,'Usuarios Equipo','bina',null,null,false,true,'','col-5');
  
	
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
function opc_causa_duelo($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=80 and estado='A' ORDER BY 1",$id);
}
function opc_parent_fallec($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=81 and estado='A' ORDER BY 1",$id);
}
function opc_lugar_defun($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=82 and estado='A' ORDER BY 1",$id);
}
function opc_vincu_afect($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=83 and estado='A' ORDER BY 1",$id);
}
function opc_senti_ident_1($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=84 and estado='A' ORDER BY 1",$id);
}
function opc_senti_ident_2($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=84 and estado='A' ORDER BY 1",$id);
}
function opc_senti_ident_3($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=84 and estado='A' ORDER BY 1",$id);
}
function opc_etapa_duelo($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=85 and estado='A' ORDER BY 1",$id);
}
function opc_sintoma_duelo_1($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=86 and estado='A' ORDER BY 1",$id);
}
function opc_sintoma_duelo_2($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=86 and estado='A' ORDER BY 1",$id);
}
function opc_sintoma_duelo_3($id=''){
return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=86 and estado='A' ORDER BY 1",$id);
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


function gra_apopsicduel(){
  // print_r($_POST);
  $id=divide($_POST['id_psicduel']);
  if (($smbina = $_POST['fusers_bina'] ?? null) && is_array($smbina)) {$smbin = implode(",",str_replace("'", "", $smbina));}
  if(count($id)==5){
    $sql="UPDATE vsp_apopsicduel SET 
    causa_duelo=trim(upper('{$_POST['causa_duelo']}')),fecha_defun=trim(upper('{$_POST['fecha_defun']}')),parent_fallec=trim(upper('{$_POST['parent_fallec']}')),lugar_defun=trim(upper('{$_POST['lugar_defun']}')),vincu_afect=trim(upper('{$_POST['vincu_afect']}')),senti_ident_1=trim(upper('{$_POST['senti_ident_1']}')),senti_ident_2=trim(upper('{$_POST['senti_ident_2']}')),senti_ident_3=trim(upper('{$_POST['senti_ident_3']}')),etapa_duelo=trim(upper('{$_POST['etapa_duelo']}')),sintoma_duelo_1=trim(upper('{$_POST['sintoma_duelo_1']}')),sintoma_duelo_2=trim(upper('{$_POST['sintoma_duelo_2']}')),sintoma_duelo_3=trim(upper('{$_POST['sintoma_duelo_3']}')),estrategia_1=trim(upper('{$_POST['estrategia_1']}')),estrategia_2=trim(upper('{$_POST['estrategia_2']}')),acciones_1=trim(upper('{$_POST['acciones_1']}')),desc_accion1=trim(upper('{$_POST['desc_accion1']}')),acciones_2=trim(upper('{$_POST['acciones_2']}')),desc_accion2=trim(upper('{$_POST['desc_accion2']}')),acciones_3=trim(upper('{$_POST['acciones_3']}')),desc_accion3=trim(upper('{$_POST['desc_accion3']}')),activa_ruta=trim(upper('{$_POST['activa_ruta']}')),ruta=trim(upper('{$_POST['ruta']}')),novedades=trim(upper('{$_POST['novedades']}')),signos_covid=trim(upper('{$_POST['signos_covid']}')),caso_afirmativo=trim(upper('{$_POST['caso_afirmativo']}')),otras_condiciones=trim(upper('{$_POST['otras_condiciones']}')),observaciones=trim(upper('{$_POST['observaciones']}')),cierre_caso=trim(upper('{$_POST['cierre_caso']}')),fecha_cierre=trim(upper('{$_POST['fecha_cierre']}')),motivo_cierre = TRIM(UPPER('{$_POST['motivo_cierre']}')),liker_dificul=trim(upper('{$_POST['liker_dificul']}')),liker_emocion=trim(upper('{$_POST['liker_emocion']}')),liker_decision=trim(upper('{$_POST['liker_decision']}')),redu_riesgo_cierre=trim(upper('{$_POST['redu_riesgo_cierre']}')),
    `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
    WHERE id_apopsicduel =TRIM(UPPER('{$id[0]}'))";
      // echo $sql;
  }else if(count($id)==4){
    $sql="INSERT INTO vsp_apopsicduel VALUES (NULL,trim(upper('{$id[1]}')),trim(upper('{$id[0]}')),
    trim(upper('{$_POST['fecha_seg']}')),trim(upper('{$_POST['numsegui']}')),trim(upper('{$_POST['evento']}')),trim(upper('{$_POST['estado_s']}')),trim(upper('{$_POST['motivo_estado']}')),trim(upper('{$_POST['causa_duelo']}')),trim(upper('{$_POST['fecha_defun']}')),trim(upper('{$_POST['parent_fallec']}')),trim(upper('{$_POST['lugar_defun']}')),trim(upper('{$_POST['vincu_afect']}')),trim(upper('{$_POST['senti_ident_1']}')),trim(upper('{$_POST['senti_ident_2']}')),trim(upper('{$_POST['senti_ident_3']}')),trim(upper('{$_POST['etapa_duelo']}')),trim(upper('{$_POST['sintoma_duelo_1']}')),trim(upper('{$_POST['sintoma_duelo_2']}')),trim(upper('{$_POST['sintoma_duelo_3']}')),trim(upper('{$_POST['estrategia_1']}')),trim(upper('{$_POST['estrategia_2']}')),trim(upper('{$_POST['acciones_1']}')),trim(upper('{$_POST['desc_accion1']}')),trim(upper('{$_POST['acciones_2']}')),trim(upper('{$_POST['desc_accion2']}')),trim(upper('{$_POST['acciones_3']}')),trim(upper('{$_POST['desc_accion3']}')),trim(upper('{$_POST['activa_ruta']}')),trim(upper('{$_POST['ruta']}')),trim(upper('{$_POST['novedades']}')),trim(upper('{$_POST['signos_covid']}')),trim(upper('{$_POST['caso_afirmativo']}')),trim(upper('{$_POST['otras_condiciones']}')),trim(upper('{$_POST['observaciones']}')),trim(upper('{$_POST['cierre_caso']}')),trim(upper('{$_POST['fecha_cierre']}')),trim(upper('{$_POST['motivo_cierre']}')),trim(upper('{$_POST['liker_dificul']}')),trim(upper('{$_POST['liker_emocion']}')),trim(upper('{$_POST['liker_decision']}')),trim(upper('{$_POST['redu_riesgo_cierre']}')),trim(upper('{$smbin}')),
      TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
      // echo $sql;
    }
      $rta=dato_mysql($sql);
      return $rta;
  } 

  function get_apopsicduel(){
    if($_REQUEST['id']==''){
      return "";
    }else{
      $id=divide($_REQUEST['id']);
      $sql="SELECT concat(id_psicduel,'_',tipo_doc,'_',documento,'_',numsegui,'_',evento),
      fecha_seg,numsegui,evento,estado_s,motivo_estado,causa_duelo,fecha_defun,parent_fallec,lugar_defun,vincu_afect,senti_ident_1,senti_ident_2,senti_ident_3,etapa_duelo,sintoma_duelo_1,sintoma_duelo_2,sintoma_duelo_3,estrategia_1,estrategia_2,acciones_1,desc_accion1,acciones_2,desc_accion2,acciones_3,desc_accion3,activa_ruta,ruta,novedades,signos_covid,caso_afirmativo,otras_condiciones,observaciones,cierre_caso,motivo_cierre,fecha_cierre,liker_dificul,liker_emocion,liker_decision,redu_riesgo_cierre,users_bina
      FROM vsp_apopsicduel
      WHERE id_psicduel ='{$id[0]}'";
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
	if ($a=='apopsicduel-lis' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";	
    $rta.="<li class='icono editar' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'apopsicduel',event,this,['fecha_seg','numsegui','evento','estado_s','motivo_estado'],'apopsicduel.php');\"></li>";
	}
	
 return $rta;
}


function bgcolor($a,$c,$f='c'){
  $rta="";
  return $rta;
   }
