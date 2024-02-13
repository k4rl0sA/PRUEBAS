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


function focus_cancinfa(){
  return 'cancinfa';
 }
 
 
 function men_cancinfa(){
  $rta=cap_menus('cancinfa','pro');
  return $rta;
 }
 
 
 function cap_menus($a,$b='cap',$con='con') {
   $rta = ""; 
   $acc=rol($a);
   $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
   $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
   
   return $rta;
 }


 FUNCTION lis_cancinfa(){
	// var_dump($_POST['id']);
	$id=divide($_POST['id']);
	$sql="SELECT `id_cancinfa` ACCIONES,id_cancinfa  'Cod Registro',
tipo_doc,documento,fecha_seg Fecha,numsegui Seguimiento,FN_CATALOGODESC(87,evento) EVENTO,FN_CATALOGODESC(73,estado_s) estado,cierre_caso Cierra,
fecha_cierre 'Fecha de Cierre',nombre Creó 
FROM vsp_cancinfa A
	LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario ";
$sql.="WHERE tipo_doc='".$id[1]."' AND documento='".$id[0];
$sql.="' ORDER BY fecha_create";
	// echo $sql;
	$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"cancinfa-lis",5);
   }


function cmp_cancinfa(){
	$rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div>
	<div class='contenido' id='cancinfa-lis'>".lis_cancinfa()."</div></div>";
	$w='cancinfa';
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
  

	  $c[]=new cmp('id_cancinfa','h','50',$_POST['id'],$w.' '.$o,'','id_cancinfa',null,null,false,true,'','col-2');
    
  $c[]=new cmp('fecha_seg','d','10',$d,$w.' '.$o,'Fecha Seguimiento','fecha_seg',null,null,true,true,'','col-2','validDate(this,-22,0);');
  $c[]=new cmp('numsegui','s','3',$d,$w.' '.$o,'Seguimiento N°','numsegui',null,null,true,true,'','col-2',"staEfe('numsegui','sta');EnabEfec(this,['hab','acc'],['Ob'],['nO'],['bL'])");
  $c[]=new cmp('evento','s','3',$ev,$w.' '.$o,'Evento','evento',null,null,false,false,'','col-2');
  $c[]=new cmp('estado_s','s','3',$d,$w.' sTa '.$o,'Estado','estado_s',null,null,true,true,'','col-2',"enabFielSele(this,true,['motivo_estado'],['3']);EnabEfec(this,['hab','acc'],['Ob'],['nO'],['bL']);");
  $c[]=new cmp('motivo_estado','s','3',$d,$w.' '.$o,'Motivo de Estado','motivo_estado',null,null,false,$x,'','col-2');
    
    $o='hab';
    $c[]=new cmp($o,'e',null,'INFORMACIÓN ',$w);
    $c[]=new cmp('diagnosticado','s','2',$d,$w.' '.$o,'¿DX Confirmado?','rta',null,null,false,$x,'','col-15',"enabOthSi('diagnosticado','cI');");
    $c[]=new cmp('fecha_dx','d','10',$d,$w.' cI '.$bl.' '.$bl.' '.$o,'Fecha de diagnóstico confirmado','fecha_dx',null,null,false,$x,'','col-2','validDate(-2500,0);');
    
    $c[]=new cmp('tratamiento','s','10',$d,$w.' cI '.$bl.' '.$o,'Cuenta con Tratamiento','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('asiste_control','s','2',$d,$w.' cI '.$bl.' '.$no.' '.$o,'¿Asiste a controles con especialista?','rta',null,null,false,$x,'','col-2',"enabOthSi('asiste_control','tO');");
    $c[]=new cmp('cual_espe','t','500',$d,$w.' tO '.$bl.' '.$no.' '.$o,'Cuál o Cuales especialistas','cual_espe',null,null,false,$x,'','col-2');
    $c[]=new cmp('trata_orde','s','3',$d,$w.' cI '.$bl.' '.$no.' '.$o,'Tratamiento ordenado','trata_orde',null,null,false,$x,'','col-2',"enbValsCls('trata_orde',['fC','Fq','fR','Fo']);");
    $c[]=new cmp('fecha_cirug','d','10',$d,$w.' fC '.$bl.' '.$no.' '.$o,'Fecha de Cirugía','fecha_cirug',null,null,false,$x,'','col-2','validDate(-2500,0);');
    $c[]=new cmp('fecha_quimio','d','10',$d,$w.' Fq '.$bl.' '.$no.' '.$o,'Fecha de inicio de Quimioterapia','fecha_quimio',null,null,false,$x,'','col-2','validDate(-2500,0);');
    $c[]=new cmp('fecha_radiote','d','10',$d,$w.' fR '.$bl.' '.$no.' '.$o,'Fecha de inicio de Radioterapia','fecha_radiote',null,null,false,$x,'','col-2','validDate(-2500,0);');
    $c[]=new cmp('fecha_otro','d','10',$d,$w.' Fo '.$bl.' '.$no.' '.$o,'Fecha de Otro','fecha_otro',null,null,false,$x,'','col-2','validDate(-2500,0);');
    $c[]=new cmp('otro_cual','t','500',$d,$w.' Fo '.$bl.' '.$no.' '.$o,'¿Otro Cuál?','otro_cual',null,null,false,$x,'','col-4'); 
    
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
    $c[]=new cmp('motivo_cierre','s','2',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Motivo Cierre','motivo_cierre',null,null,false,$x,'','col-55');
    $c[]=new cmp('fecha_cierre','d','10',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Fecha de Cierre','fecha_cierre',null,null,false,$x,'','col-25');
    //igual
    
   
    $c[]=new cmp('supera_problema','s','2',$d,$w.' cc '.$no.' '.$o,'Se han superado las necesidades en la categoria problemas prácticos','rta',null,null,false,$x,'','col-35');
    $c[]=new cmp('supera_emocional','s','2',$d,$w.' cc '.$no.' '.$o,'Se han superado las necesidades en la categoria estado emocional','rta',null,null,false,$x,'','col-35');
    $c[]=new cmp('supera_dolor','s','2',$d,$w.' cc '.$no.' '.$o,'Se han superado las necesidades en la categoria Valoración del dolor','rta',null,null,false,$x,'','col-3');
    $c[]=new cmp('supera_funcional','s','2',$d,$w.' cc '.$no.' '.$o,'Se han superado las necesidades en la categoria valoracion funcional','rta',null,null,false,$x,'','col-35');
    $c[]=new cmp('supera_educacion','s','2',$d,$w.' cc '.$no.' '.$o,'Se han superado las necesidades en la categoria Educación','rta',null,null,false,$x,'','col-35');
    $c[]=new cmp('redu_riesgo_cierre','s','2',$d,$w.' cc '.$no.' '.$o,'¿Reduccion del riesgo?','rta',null,null,false,$x,'','col-3');
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
function opc_liker_dificul($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=78 and estado='A' ORDER BY 1",$id);
}
function opc_liker_emocion($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=78 and estado='A' ORDER BY 1",$id);
}
function opc_liker_decision($id=''){  
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=78 and estado='A' ORDER BY 1",$id);
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
function opc_trata_orde($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=93 and estado='A' ORDER BY 1",$id);
}

function opc_estrategia_1($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=90 and estado='A' ORDER BY 1",$id);
}
function opc_estrategia_2($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=90 and estado='A' ORDER BY 1",$id);
}
function opc_acciones_1($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
}
function opc_desc_accion1($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
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



function gra_cancinfa(){
  
  $fecha_dx = ($_POST['fecha_dx']!=='') ? "trim(upper('".$_POST['fecha_dx']."'))":"NULL";
  $fecha_cirug = ($_POST['fecha_cirug']) ? "trim(upper('".$_POST['fecha_cirug']."'))":"NULL";
  $fecha_quimio = ($_POST['fecha_quimio']) ? "trim(upper('".$_POST['fecha_quimio']."'))":"NULL";
  $fecha_radiote = ($_POST['fecha_radiote']) ? "trim(upper('".$_POST['fecha_radiote']."'))":"NULL";
  $fecha_otro = ($_POST['fecha_otro']) ? "trim(upper('".$_POST['fecha_otro']."'))":"NULL";
  $fecha_cierre = ($_POST['fecha_cierre']) ? "trim(upper('".$_POST['fecha_cierre']."'))":"NULL";

  $id=divide($_POST['id_cancinfa']);
if (($smbina = $_POST['fusers_bina'] ?? null) && is_array($smbina)) {$smbin = implode(",",str_replace("'", "", $smbina));}	
  if(count($id)==5){
    $sql="UPDATE vsp_cancinfa SET 
    diagnosticado=trim(upper('{$_POST['diagnosticado']}')),fecha_dx=trim(upper('{$_POST['fecha_dx']}')),tratamiento=trim(upper('{$_POST['tratamiento']}')),asiste_control=trim(upper('{$_POST['asiste_control']}')),cual_espe=trim(upper('{$_POST['cual_espe']}')),trata_orde=trim(upper('{$_POST['trata_orde']}')),fecha_cirug=trim(upper('{$_POST['fecha_cirug']}')),fecha_quimio=trim(upper('{$_POST['fecha_quimio']}')),fecha_radiote=trim(upper('{$_POST['fecha_radiote']}')),fecha_otro=trim(upper('{$_POST['fecha_otro']}')),otro_cual=trim(upper('{$_POST['otro_cual']}')),estrategia_1=trim(upper('{$_POST['estrategia_1']}')),estrategia_2=trim(upper('{$_POST['estrategia_2']}')),acciones_1=trim(upper('{$_POST['acciones_1']}')),desc_accion1=trim(upper('{$_POST['desc_accion1']}')),acciones_2=trim(upper('{$_POST['acciones_2']}')),desc_accion2=trim(upper('{$_POST['desc_accion2']}')),acciones_3=trim(upper('{$_POST['acciones_3']}')),desc_accion3=trim(upper('{$_POST['desc_accion3']}')),activa_ruta=trim(upper('{$_POST['activa_ruta']}')),ruta=trim(upper('{$_POST['ruta']}')),novedades=trim(upper('{$_POST['novedades']}')),signos_covid=trim(upper('{$_POST['signos_covid']}')),caso_afirmativo=trim(upper('{$_POST['caso_afirmativo']}')),otras_condiciones=trim(upper('{$_POST['otras_condiciones']}')),observaciones=trim(upper('{$_POST['observaciones']}')),cierre_caso=trim(upper('{$_POST['cierre_caso']}')),motivo_cierre = TRIM(UPPER('{$_POST['motivo_cierre']}')),fecha_cierre=trim(upper('{$_POST['fecha_cierre']}')),supera_problema=trim(upper('{$_POST['supera_problema']}')),supera_emocional=trim(upper('{$_POST['supera_emocional']}')),supera_dolor=trim(upper('{$_POST['supera_dolor']}')),supera_funcional=trim(upper('{$_POST['supera_funcional']}')),supera_educacion=trim(upper('{$_POST['supera_educacion']}')),redu_riesgo_cierre=trim(upper('{$_POST['redu_riesgo_cierre']}')),
    `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
    WHERE id_cancinfa =TRIM(UPPER('{$id[0]}'))";
    // echo $sql;
  }else if(count($id)==4){
    $sql="INSERT INTO vsp_cancinfa VALUES (NULL,trim(upper('{$id[1]}')),trim(upper('{$id[0]}')),
    trim(upper('{$_POST['fecha_seg']}')),trim(upper('{$_POST['numsegui']}')),trim(upper('{$_POST['evento']}')),trim(upper('{$_POST['estado_s']}')),trim(upper('{$_POST['motivo_estado']}')),trim(upper('{$_POST['diagnosticado']}')),$fecha_dx,trim(upper('{$_POST['tratamiento']}')),trim(upper('{$_POST['asiste_control']}')),trim(upper('{$_POST['cual_espe']}')),trim(upper('{$_POST['trata_orde']}')),$fecha_cirug,$fecha_quimio,$fecha_radiote,$fecha_otro,trim(upper('{$_POST['otro_cual']}')),trim(upper('{$_POST['estrategia_1']}')),trim(upper('{$_POST['estrategia_2']}')),trim(upper('{$_POST['acciones_1']}')),trim(upper('{$_POST['desc_accion1']}')),trim(upper('{$_POST['acciones_2']}')),trim(upper('{$_POST['desc_accion2']}')),trim(upper('{$_POST['acciones_3']}')),trim(upper('{$_POST['desc_accion3']}')),trim(upper('{$_POST['activa_ruta']}')),trim(upper('{$_POST['ruta']}')),trim(upper('{$_POST['novedades']}')),trim(upper('{$_POST['signos_covid']}')),trim(upper('{$_POST['caso_afirmativo']}')),trim(upper('{$_POST['otras_condiciones']}')),trim(upper('{$_POST['observaciones']}')),trim(upper('{$_POST['cierre_caso']}')),trim(upper('{$_POST['motivo_cierre']}')),$fecha_cierre,trim(upper('{$_POST['supera_problema']}')),trim(upper('{$_POST['supera_emocional']}')),trim(upper('{$_POST['supera_dolor']}')),trim(upper('{$_POST['supera_funcional']}')),trim(upper('{$_POST['supera_educacion']}')),trim(upper('{$_POST['redu_riesgo_cierre']}')),trim(upper('{$smbin}')),
    TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A');";
    // echo $sql;
  }
    $rta=dato_mysql($sql);
    return $rta;
  } 

  function get_cancinfa(){
    if($_REQUEST['id']==''){
      return "";
    }else{
      $id=divide($_REQUEST['id']);
      $sql="SELECT concat(id_cancinfa,'_',tipo_doc,'_',documento,'_',numsegui,'_',evento),
      fecha_seg,numsegui,evento,estado_s,motivo_estado,diagnosticado,fecha_dx,tratamiento,asiste_control,cual_espe,trata_orde,fecha_cirug,fecha_quimio,fecha_radiote,fecha_otro,otro_cual,estrategia_1,estrategia_2,acciones_1,desc_accion1,acciones_2,desc_accion2,acciones_3,desc_accion3,activa_ruta,ruta,novedades,signos_covid,caso_afirmativo,otras_condiciones,observaciones,cierre_caso,motivo_cierre,fecha_cierre,supera_problema,supera_emocional,supera_dolor,supera_funcional,supera_educacion,redu_riesgo_cierre,users_bina
      FROM vsp_cancinfa
      WHERE id_cancinfa ='{$id[0]}'";
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
	if ($a=='cancinfa' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";	
    $rta.="<li class='icono editar' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'cancinfa',event,this,['fecha_seg','numsegui','evento','estado_s','motivo_estado'],'cancinfa.php');\"></li>";
	}
	
 return $rta;
}


function bgcolor($a,$c,$f='c'){
  $rta="";
  return $rta;
   }
