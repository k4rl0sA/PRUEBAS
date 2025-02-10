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



function focus_mme(){
  return 'mme';
 }
 
 
 function men_mme(){
  $rta=cap_menus('mme','pro');
  return $rta;
 }
 
 
 function cap_menus($a,$b='cap',$con='con') {
  $rta = "";
  $acc=rol($a);
  if ($a=='mme' && isset($acc['crear']) && $acc['crear']=='SI') {  
   $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
    }
  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";  
  return $rta;
}


 FUNCTION lis_mme(){
	// var_dump($_POST['id']);
	$id = isset($_POST['id']) ? divide($_POST['id']) : (isset($_POST['id_mme']) ? divide($_POST['id_mme']) : null);
  $info=datos_mysql("SELECT COUNT(*) total FROM vsp_mme A LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario 
  WHERE A.estado = 'A' AND A.idpeople='".$id[0]."'");
	$total=$info['responseResult'][0]['total'];
	$regxPag=4;
  $pag=(isset($_POST['pag-mme']))? ($_POST['pag-mme']-1)* $regxPag:0;


  
	$sql="SELECT `id_mme` ACCIONES,id_mme  'Cod Registro',
P.tipo_doc,P.idpersona,fecha_seg Fecha,numsegui Seguimiento,FN_CATALOGODESC(87,evento) EVENTO,FN_CATALOGODESC(73,estado_s) estado,cierre_caso Cierra,
    fecha_cierre 'Fecha de Cierre',nombre Creó 
FROM vsp_mme A
	LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario
  LEFT JOIN   person P ON A.idpeople=P.idpeople";
	$sql.=" WHERE A.estado = 'A' AND A.idpeople='".$id[0]; 
	$sql.="' ORDER BY A.fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	// echo $sql;
	$datos=datos_mysql($sql);
  return create_table($total,$datos["responseResult"],"mme",$regxPag,'../vsp/mme.php');
   }


function cmp_mme(){
	$rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div>
	<div class='contenido' id='mme-lis'>".lis_mme()."</div></div>";
	$w='mme';
  $d='';
	$o='inf';
  $ob='Ob';
  $no='nO';
  $bl='bL';
  $x=false;
  $block=['hab','acc','infpue','infacc'];
  $event=divide($_POST['id']);
  $ev=$event[2];
  $days=fechas_app('vsp');
  $p=get_persona();
	$c[]=new cmp('id_mme','h','50',$_POST['id'],$w.' '.$o,'Id de mme','id_mme',null,null,false,false,'','col-0');
  $c[]=new cmp('fecha_seg','d','10',$d,$w.' '.$o,'Fecha Seguimiento','fecha_seg',null,null,true,true,'','col-2',"validDate(this,$days,0);");
  $c[]=new cmp('numsegui','s','3',$d,$w.' '.$o,'Seguimiento N°','numsegui',null,null,true,true,'','col-2',"staEfe('numsegui','sta');EnabEfec(this,['hab','acc','info'],['Ob'],['nO'],['bL'])");
  $c[]=new cmp('evento','s','3',$ev,$w.' '.$o,'Evento','evento',null,null,false,false,'','col-2');
  $c[]=new cmp('tiposeg','s','3',$ev,$w.' '.$o,'Tipo de Seguimiento','tiposeg',null,null,false,true,'','col-2',"enabEtap('tiposeg',['aST']);enabEtap('tiposeg',['AcR']);");
  $c[]=new cmp('estado_s','s','3',$d,$w.' sTa '.$o,'Estado','estado_s',null,null,true,true,'','col-2',"enabFielSele(this,true,['motivo_estado'],['3']);EnabEfec(this,['hab','acc','info'],['Ob'],['nO'],['bL']);");
  $c[]=new cmp('motivo_estado','s','3',$d,$w.' '.$o,'Motivo de Estado','motivo_estado',null,null,false,$x,'','col-3');
  //$c[]=new cmp('sexo','h','50',$p['sexo'],$w.' '.$o,'sexo','sexo',null,'',false,false,'','col-1');
	//$c[]=new cmp('fechanacimiento','h','10',$p['fecha_nacimiento'],$w.' '.$o,'fecha nacimiento','fechanacimiento',null,'',true,false,'','col-2');  

  $c[]=new cmp('etapa','s','3',$d,$w.' hab '.$o,'Etapa','etapa',null,null,false,false,'','col-2',"enabEtap('etapa',['pRe','PuE','PYg']);weksEtap('etapa','PeT');valiEgreHosp();EnabDepe2fiel('IMc','3','1','tiposeg','etapa',false,true);");
  $c[]=new cmp('sema_gest','s','3',$d,$w.' PeT hab '.$o,'Semanas De Gestación/ Días Pos-Evento','sema_gest',null,null,false,false,'','col-3');
  $c[]=new cmp('gestaciones','s','3',$d,$w.' PeT hab '.$o,'Gestaciones','fobs',null,null,false,false,'','col-2');  
  $c[]=new cmp('partos','s','3',$d,$w.' PeT hab '.$o,'Partos','fobs',null,null,false,false,'','col-2');  
  $c[]=new cmp('abortos','s','3',$d,$w.' PeT hab '.$o,'Abortos','fobs',null,null,false,false,'','col-2');
  $c[]=new cmp('cesareas','s','3',$d,$w.' PeT hab '.$o,'Cesareas','fobs',null,null,false,false,'','col-2');
  $c[]=new cmp('vivos','s','3',$d,$w.' PeT hab '.$o,'Vivos','fobs',null,null,false,false,'','col-2');
  $c[]=new cmp('muertos','s','3',$d,$w.' PeT hab '.$o,'Muertos','fobs',null,null,false,false,'','col-2');  
    
  $o='gest';
    $c[]=new cmp($o,'e',null,'GESTANTES ',$w);
    $c[]=new cmp('fecha_egre','d','10',$d,$w.' HOs '.$o,'Fecha de Egreso Hospitalario','fecha_egre',null,null,false,false,'','col-2',"validDate(this,$days,0);");
    $c[]=new cmp('edad_padre','t',2,$d,$w.' HOs '.$o,'Edad del Padre','fpe','rgxpeso','##',false,false,'','col-2');
    $c[]=new cmp('asis_ctrpre','s','2',$d,$w.' pRe '.$o,'¿Asiste A Controles Prenatales?','rta',null,null,false,$x,'','col-2',"enabOthNo('asis_ctrpre','CtP');disaOthNo('asis_ctrpre','CPn');");
    $c[]=new cmp('ing_ctrpre','s','2',$d,$w.' CtP '.$o,'Ingreso a Control Prenatal Antes de la Semana 10','rta',null,null,false,false,'','col-2',"enabOthNo('ing_ctrpre','S10');");//se habilita cuando la pregunta anterior es SI
    $c[]=new cmp('cpn','s','2',$d,$w.' S10 '.$o,'¿Cuantos CPN?','cpn',null,null,false,false,'','col-2') ;//se habilita cuando la pregunta anterior es SI
    $c[]=new cmp('porque_no','t','500',$d,$w.' CPn '.$o,'¿Por Qué?','porque_no',null,null,false,false,'','col-4');// se habilita cuando es un NO en la pregunta de asiste a controles prenatales
    $c[]=new cmp('exam_lab','s','2',$d,$w.' pRe '.$o,'¿Cuenta Con Exámenes De Laboratorio Al Día?','rta',null,null,false,$x,'','col-3');
    $c[]=new cmp('esqu_vacuna','s','3',$d,$w.' pRe '.$o,'¿Tiene Esquema De Vacunación Completo?','rta',null,null,false,$x,'','col-3');
    $c[]=new cmp('cons_micronutr','s','2',$d,$w.' pRe '.$o,'¿Consume Micronutrientes?','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('trata_farma','s','3',$d,$w.' pRe '.$o,'Tratamiento Farmacologico','rta',null,null,false,$x,'','col-2',"enabOthSi('trata_farma','FaR');");
    $c[]=new cmp('tipo_tratafarma','m','3',$d,$w.' pRe FaR '.$o,'Tipo de Tratamiento','tipo_tratafarma',null,null,false,$x,'','col-2');
    $c[]=new cmp('cualtra','t','100',$d,$w.' '.$o,'Ingrese CÚAL si, anteriormente selecciono OTRO','cual',null,null,false,true,'','col-4');
    $c[]=new cmp('adhe_tratafarma','s','3',$d,$w.' pRe '.$o,'Adhe de Tratafarma','rta',null,null,false,$x,'','col-2',"disaOthNo('adhe_tratafarma','nAd');");
    $c[]=new cmp('porque_noadh','t','500',$d,$w.' nAd '.$o,'¿Por Qué?','porque_noadh',null,null,false,false,'','col-4');
    $c[]=new cmp('peso','sd',6,$d,$w.' pRe IMc '.$o,'Peso (Kg) Mín=0.50 - Máx=150.00','fpe','rgxpeso','##.#',false,$x,'','col-2');
    $c[]=new cmp('talla','sd',5,$d,$w.' pRe IMc '.$o,'Talla (Cm) Mín=40 - Máx=210','fta','rgxtalla','###.#',false,$x,'','col-2',"calImc('peso','talla','imc');");
    $c[]=new cmp('imc','t','20',$d,$w.' '.$o,'Imc','imc',null,null,false,false,'','col-2');
    $c[]=new cmp('clasi_nutri','s','3',$d,$w.' '.$bl.' pRe  '.$o,'Clasificación Nutricional','clasi_nutri',null,null,false,false,'','col-2');
    $c[]=new cmp('signos_alarma_seg','s','2',$d,$w.' pRe '.$o,'Identifica signos de alarma al momento del seguimiento','rta',null,null,false,$x,'','col-2',"enabOthSi('signos_alarma_seg','SiA');");
    $c[]=new cmp('descr_sigalarma','t','500',$d,$w.' SiA '.$o,'Descripcion del signo de alarma','descr_sigalarma',null,null,false,false,'','col-4');
    $c[]=new cmp('entrega_medic_labo','t','500',$d,$w.' pRe '.$o,'Entrega de medicamentos y realización de laboratorios en casa','entrega_medic_labo',null,null,false,$x,'','col-4');

    $o='infpue';
    $c[]=new cmp($o,'e',null,'DESPUES DE LA GESTACION (PUERPERIO Y/O POSTERIOR AL PUERPERIO) ',$w);
    $c[]=new cmp('fecha_obstetrica','d','10',$d,$w.' PuE '.$o,'Fecha Evento Obstetrico','fecha_obstetrica',null,null,false,$x,'','col-3',"validDate(this,-400,0);");
    $c[]=new cmp('edad_gesta','s','3',$d,$w.' PuE '.$o,'Edad gestacional en el momento del evento obstetrico','edad_gesta',null,null,false,$x,'','col-4');
    $c[]=new cmp('resul_gest','s','3',$d,$w.' PuE '.$o,'Resultado de la gestación','resul_gest',null,null,false,$x,'','col-3',"enabClasValu('resul_gest',['ncvmor','mOr','NOm']);");
    $c[]=new cmp('meto_fecunda','s','3',$d,$w.' PuE '.$o,'¿Cuenta Con Método de Regulación de la fecundidad?','rta',null,null,false,$x,'','col-35',"enabOthSi('meto_fecunda','MFe');disaOthNo('meto_fecunda','Nme')");
    $c[]=new cmp('cualmet','s','3',$d,$w.' PuE MFe '.$o,'¿Cuál?','cual',null,null,false,$x,'','col-3',"enabFielSele(this,true,['otro_cual'],['7']);");
    $c[]=new cmp('otro_cual','t','500',$d,$w.' MFe mEt '.$no.' '.$o,'Otro de Cual','otro_cual',null,null,false,false,'','col-35');
    $c[]=new cmp('motivo_nofecund','t','500',$d,$w.' Nme '.$o,'Motivo de no acceso a Método','motivo_nofecund',null,null,false,false,'','col-4');

    $c[]=new cmp('control_mac','s','3',$d,$w.' PuE '.$o,'¿Tiene control MAC?','rta',null,null,false,$x,'','col-2',"enabOthSi('control_mac','MAc');");
    $c[]=new cmp('fecha_control_mac','d','10',$d,$w.' PuE MAc '.$o,'Fecha de control MAC','fecha_control_mac',null,null,false,$x,'','col-2',"validDate(this,-400,0);");
    $c[]=new cmp('ctrl_postpar_espe','s','3',$d,$w.' PuE '.$o,'¿Tiene control post parto con especialista?','rta',null,null,false,$x,'','col-2',"enabOthSi('ctrl_postpar_espe','Esp');");
    $c[]=new cmp('fecha_postpar_espe','d','10',$d,$w.' PuE Esp '.$o,'Fecha de control post parto con especialista','fecha_postpar_espe',null,null,false,$x,'','col-2');
    $c[]=new cmp('asis_ctrl_postpar_espe','s','3',$d,$w.' PuE aST '.$o,'¿Asistió a control post parto?','rta2',null,null,false,$x,'','col-2',"disaOthNo('asis_ctrl_postpar_espe','NPp');validDate(this,-400,0);");
    $c[]=new cmp('porque_no_postpar','t','500',$d,$w.' PuE MFe NPp '.$o,'¿Por que?','porque_no_postpar',null,null,false,$x,'','col-4');
    $c[]=new cmp('consul_apoy_lacmater','s','3',$d,$w.' PuE '.$o,'¿Tiene consulta apoyo lactancia materna?','rta',null,null,false,$x,'','col-2',"enabOthSi('consul_apoy_lacmater','aLM');");
    $c[]=new cmp('signos_alarma','s','3',$d,$w.' PuE '.$o,'Identifica signos de alarma al momento del seguimiento','rta',null,null,false,$x,'','col-2',"enabOthSi('signos_alarma','dsA');");
    $c[]=new cmp('desc_sigala','t','500',$d,$w.' PuE dsA '.$o,'Descripcion del signo de alarma','desc_sigala',null,null,false,$x,'','col-4');
    $c[]=new cmp('disc_ges','s','3',$d,$w.' PuE '.$o,'¿La gestante presenta alguna discapacidad o secuela posterior al evento obstetrico?','rta',null,null,false,$x,'','col-3',"enabOthSi('disc_ges','dGp');");
    $c[]=new cmp('cual_disc_ges','t','500',$d,$w.' PuE dGp '.$o,'¿Cúal?','cual_disc_ges',null,null,false,$x,'','col-4');
    $c[]=new cmp('fecha_apoy_lacmater','d','10',$d,$w.' PuE aLM '.$o,'Fecha de consulta apoyo lactancia materna','fecha_apoy_lacmater',null,null,false,$x,'','col-2');
    
    $o='NOm';
    $c[]=new cmp($o,'e',null,'NACIDO VIVO',$w);
    $c[]=new cmp('peso_rcnv','sd','4',$d,$w.' PuE '.$o,'Peso del Recien Nacido Vivo','peso','rgxpeso','##.#',false,$x,'','col-2');
    $c[]=new cmp('ctrl_recinac','s','3',$d,$w.' PuE '.$o,'Asiste a control de recién nacido','rta2',null,null,false,$x,'','col-2',"enabOthSi('ctrl_recinac','CrT');");
    $c[]=new cmp('fecha_ctrl_nac','d','10',$d,$w.' PuE CrT '.$o,'Fecha de control de recién nacido','fecha_ctrl_nac',null,null,false,$x,'','col-2');
    $c[]=new cmp('asis_ctrl_recinac','s','3',$d,$w.' PuE AcR '.$o,'Asistió a control de recién nacido','rta2',null,null,false,$x,'','col-2',"disaOthNo('asis_ctrl_recinac','CrT1');");
    $c[]=new cmp('porque_norec','t','500',$d,$w.' CrT1 '.$o,'¿Por Qué?','porque_norec',null,null,false,false,'','col-3');
    $c[]=new cmp('ult_peso','sd','4',$d,$w.' PuE '.$o,'Último peso registrado','ult_peso','rgxpeso','##.#',false,false,'','col-2');
    $c[]=new cmp('consul_lacmate','s','3',$d,$w.' PuE '.$o,'¿Tiene consulta apoyo lactancia materna?','rta',null,null,false,$x,'','col-2',"enabOthSi('consul_lacmate','FlM');disaOthNo('consul_lacmate','nLM');");
    $c[]=new cmp('porque_nolact','t','500',$d,$w.' nLM '.$o,'¿Por Qué?','porque_nolact',null,null,false,$x,'','col-3');
    $c[]=new cmp('fecha_consul_lacmate','d','10',$d,$w.' PuE FlM '.$o,'Fecha de Consul_Lacmate','fecha_consul_lacmate',null,null,false,$x,'','col-2');
    $c[]=new cmp('asiste_ctrl_cyd','s','3',$d,$w.' PuE '.$o,'¿Asiste a Controles de Crecimiento y Desarrollo o plan canguro?','rta2',null,null,false,$x,'','col-4');
    $c[]=new cmp('vacuna_comple','s','3',$d,$w.' PuE '.$o,'¿Tiene esquema de vacunación completo para la edad?','rta',null,null,false,$x,'','col-3');
    $c[]=new cmp('lacmate_exclu','s','3',$d,$w.' PuE '.$o,'¿Recibe lactancia materna exclusiva?','rta',null,null,false,$x,'','col-2');
    $c[]=new cmp('signos_alarma','s','3',$d,$w.' PuE '.$o,'¿La madre identifica signos de alarma?','rta',null,null,false,$x,'','col-2');

    $o='info';
    $c[]=new cmp($o,'e',null,'INFORMACION GENERAL',$w);
    $c[]=new cmp('cam_sign','s','3',$d,$w.' PuE '.$o,'Considera que ha tenido cambios significativos recientes a nivel emocional, psicológico o comportamental (por ejemplo cambios de estado de ánimo o dificultades en el sueño)','rta',null,null,false,$x,'','col-4');
    $c[]=new cmp('qui_vida','s','3',$d,$w.' PuE '.$o,'Por su condición ha recientemente en quitarse la vida o lo ha intentado','rta',null,null,false,$x,'','col-3');
    $c[]=new cmp('viv_malt','s','3',$d,$w.' PuE '.$o,'Ha vivenciado de manera reciente algún tipo de violencia o maltrato en su familia','rta',null,null,false,$x,'','col-3');
    $c[]=new cmp('adec_red','s','3',$d,$w.' PuE '.$o,'Considera que cuenta con una adecuada red de apoyo social para cuidad, mantener y mejorar su estado de salud física y mental','rta',null,null,false,$x,'','col-3');
    $c[]=new cmp('fecha_egreopost','d','10',$d,$w.' '.$no.' '.$o,'¿Finalización de caso 42 dìas post EGRESO HOSPITALARIO ?','fecha_egreopost',null,null,false,$x,'','col-3',"validDate(this,0,50);");
    
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
    $c[]=new cmp('activa_ruta','s','2',$d,$w.' '.$o,'Ruta Activada','rta',null,null,false,$x,'','col-2','enabRuta(this,\'rt\');');
    $c[]=new cmp('ruta','s','3',$d,$w.' '.$no.' rt '.$bl.' '.$o,'Ruta','ruta',null,null,false,$x,'','col-2');
    $c[]=new cmp('novedades','s','3',$d,$w.' '.$no.' '.$o,'Novedades','novedades',null,null,false,$x,'','col-2');
    //$c[]=new cmp('signos_covid','s','2',$d,$w.' '.$o,'¿Signos y Síntomas para Covid19?','rta',null,null,false,$x,'','col-2','enabCovid(this,\'cv\');');
    //$c[]=new cmp('caso_afirmativo','t','500',$d,$w.' cv '.$bl.' '.$no.' '.$o,'Relacione Cuales signos y sintomas, Y Atención Recibida Hasta el Momento','caso_afirmativo',null,null,false,$x,'','col-4');
    $c[]=new cmp('otras_condiciones','t','500',$d,$w.' cv '.$bl.' '.$no.' '.$o,'Otras Condiciones de Riesgo que Requieren una Atención Complementaria.','otras_condiciones',null,null,false,$x,'','col-4');
    $c[]=new cmp('observaciones','a','50',$d,$w.' '.$ob.' '.$o,'Observaciones','observaciones',null,null,true,true,'','col-10');
    $c[]=new cmp('cierre_caso','s','2',$d,$w.' '.$o,'Cierre de Caso','rta',null,null,false,$x,'','col-1','enabFincas(this,\'cc\');');
    //igual
    $c[]=new cmp('motivo_cierre','s','2',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Motivo Cierre','motivo_cierre',null,null,false,$x,'','col-55');    
    $c[]=new cmp('fecha_cierre','d','10',$d,$w.' cc '.$bl.' '.$no.' '.$o,'Fecha de Cierre','fecha_cierre',null,null,false,$x,'','col-15',"validDate(this,$days,0);");

    $c[]=new cmp('conti_segespecial','s','3',$d,$w.' cc '.$o,'Continua en seguimiento por especialista','rta',null,null,false,$x,'','col-2',"enabOthSi('conti_segespecial','seP');");
    $c[]=new cmp('cual_segespecial','t','500',$d,$w.' cc seP '.$o,'Cual de Segespecial','cual_segespecial',null,null,false,$x,'','col-2');
    $c[]=new cmp('recomen_cierre','a','50',$d,$w.' cc '.$o,'Recomen de Cierre','recomen_cierre',null,null,false,$x,'','col-4');

    $c[]=new cmp('redu_riesgo_cierre','s','2',$d,$w.' cc '.$bl.' '.$no.' '.$o,'¿Reduccion del riesgo?','rta',null,null,false,$x,'','col-15');
    $c[]=new cmp('equipo','m','60',$d,$w.' '.$ob.' '.$o,'Usuarios Equipo','bina',null,null,false,true,'','col-5');
	
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}
function opc_bina($id=''){
  return opc_sql("SELECT id_usuario, nombre  from usuarios u WHERE equipo=(select equipo from usuarios WHERE id_usuario='{$_SESSION['us_sds']}') and estado='A'  ORDER BY 2;",$id);
}
function opc_tiposeg($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=243 and estado='A'  ORDER BY 1 ",$id);
}
function opc_fobs($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=244 and estado='A' ORDER BY cast(idcatadeta AS UNSIGNED)",$id);
}
function opc_cpn($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=245 and estado='A'  ORDER BY cast(idcatadeta AS UNSIGNED)",$id);
}
function opc_tipo_tratafarma($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=246 and estado='A'  ORDER BY 1 ",$id);
}
function opc_motivo_cierre($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=198 and estado='A'  ORDER BY 1 ",$id);
}
function opc_rta($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
}
function opc_rta2($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=88 and estado='A' ORDER BY 1",$id);
}
function opc_clasi_nutri($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=210 and estado='A' ORDER BY 1",$id);
  }
function opc_estado_s($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=73 and estado='A' ORDER BY 1",$id);
  }
  function opc_motivo_estado($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=74 and estado='A' ORDER BY 1",$id);
  }
function opc_evento($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 and estado='A' ORDER BY 1",$id);
  }
function opc_numsegui($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=76 and estado='A' ORDER BY 1",$id);
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
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=136 and estado='A' ORDER BY 1",$id);
}
function opc_sema_gest($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=137 ORDER BY LPAD(idcatadeta, 2, '0') ASC",$id);
}
function opc_edad_gesta($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=137 and estado='A' ORDER BY LPAD(idcatadeta, 2, '0') ASC",$id);
}
function opc_resul_gest($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=193 and estado='A' ORDER BY 1",$id);
}
function opc_cual($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=138 and estado='A' ORDER BY 1",$id);
}
function opc_novedades($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=77 and estado='A' ORDER BY 1",$id);
}
function opc_ruta($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=79 and estado='A' ORDER BY 1",$id);
}
function opc_equ(){
  $sql="SELECT equipo FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}'";
  $info=datos_mysql($sql);		
  return $info['responseResult'][0]['equipo'];
}


function gra_mme(){
  // print_r($_POST);
  $id=divide($_POST['id_mme']);
  $smbina = isset($_POST['fequipo'])?(is_array($_POST['fequipo'])?implode("-", $_POST['fequipo']):implode("-",array_map('trim',explode(",",str_replace("'","",$_POST['fequipo']))))):'';
  if(count($id)==4){
    $sql = "update vsp_mme SET observaciones=?,usu_update=?,fecha_update=DATE_SUB(NOW(),INTERVAL 5 HOUR) WHERE id_mme=?";
		$params = [
      ['type' => 's', 'value' => $_POST['observaciones']],
      ['type' => 'i', 'value' => $_SESSION['us_sds']],
			['type' => 's', 'value' =>$id[0] ]
    ];
    return  $rta= mysql_prepd($sql, $params);

    /* $sql="UPDATE vsp_mme SET 
    etapa=trim(upper('{$_POST['etapa']}')),sema_gest=trim(upper('{$_POST['sema_gest']}')),asis_ctrpre=trim(upper('{$_POST['asis_ctrpre']}')),exam_lab=trim(upper('{$_POST['exam_lab']}')),esqu_vacuna=trim(upper('{$_POST['esqu_vacuna']}')),cons_micronutr=trim(upper('{$_POST['cons_micronutr']}')),trata_farma=trim(upper('{$_POST['trata_farma']}')),adhe_tratafarma=trim(upper('{$_POST['adhe_tratafarma']}')),peso=trim(upper('{$_POST['peso']}')),talla=trim(upper('{$_POST['talla']}')),imc=trim(upper('{$_POST['imc']}')),clasi_nutri=trim(upper('{$_POST['clasi_nutri']}')),fecha_obstetrica=trim(upper('{$_POST['fecha_obstetrica']}')),edad_gesta=trim(upper('{$_POST['edad_gesta']}')),resul_gest=trim(upper('{$_POST['resul_gest']}')),meto_fecunda=trim(upper('{$_POST['meto_fecunda']}')),cual=trim(upper('{$_POST['cual']}')),otro_cual=trim(upper('{$_POST['otro_cual']}')),motivo_nofecund=trim(upper('{$_POST['motivo_nofecund']}')),control_mac=trim(upper('{$_POST['control_mac']}')),fecha_control_mac=trim(upper('{$_POST['fecha_control_mac']}')),ctrl_postpar_espe=trim(upper('{$_POST['ctrl_postpar_espe']}')),fecha_postpar_espe=trim(upper('{$_POST['fecha_postpar_espe']}')),consul_apoy_lacmater=trim(upper('{$_POST['consul_apoy_lacmater']}')),fecha_apoy_lacmater=trim(upper('{$_POST['fecha_apoy_lacmater']}')),peso_rcnv=trim(upper('{$_POST['peso_rcnv']}')),consul_lacmate=trim(upper('{$_POST['consul_lacmate']}')),fecha_consul_lacmate=trim(upper('{$_POST['fecha_consul_lacmate']}')),asiste_ctrl_cyd=trim(upper('{$_POST['asiste_ctrl_cyd']}')),vacuna_comple=trim(upper('{$_POST['vacuna_comple']}')),lacmate_exclu=trim(upper('{$_POST['lacmate_exclu']}')),signos_alarma=trim(upper('{$_POST['signos_alarma']}')),signos_alarma_seg=trim(upper('{$_POST['signos_alarma_seg']}')),descr_sigalarma=trim(upper('{$_POST['descr_sigalarma']}')),entrega_medic_labo=trim(upper('{$_POST['entrega_medic_labo']}')),estrategia_1=trim(upper('{$_POST['estrategia_1']}')),estrategia_2=trim(upper('{$_POST['estrategia_2']}')),acciones_1=trim(upper('{$_POST['acciones_1']}')),desc_accion1=trim(upper('{$_POST['desc_accion1']}')),acciones_2=trim(upper('{$_POST['acciones_2']}')),desc_accion2=trim(upper('{$_POST['desc_accion2']}')),acciones_3=trim(upper('{$_POST['acciones_3']}')),desc_accion3=trim(upper('{$_POST['desc_accion3']}')),activa_ruta=trim(upper('{$_POST['activa_ruta']}')),ruta=trim(upper('{$_POST['ruta']}')),novedades=trim(upper('{$_POST['novedades']}')),signos_covid=trim(upper('{$_POST['signos_covid']}')),caso_afirmativo=trim(upper('{$_POST['caso_afirmativo']}')),otras_condiciones=trim(upper('{$_POST['otras_condiciones']}')),observaciones=trim(upper('{$_POST['observaciones']}')),cierre_caso=trim(upper('{$_POST['cierre_caso']}')),motivo_cierre=trim(upper('{$_POST['motivo_cierre']}')),fecha_cierre=trim(upper('{$_POST['fecha_cierre']}')),conti_segespecial=trim(upper('{$_POST['conti_segespecial']}')),cual_segespecial=trim(upper('{$_POST['cual_segespecial']}')),recomen_cierre=trim(upper('{$_POST['recomen_cierre']}')),redu_riesgo_cierre=trim(upper('{$_POST['redu_riesgo_cierre']}')),
    `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
    WHERE id_mme =TRIM(UPPER('{$id[0]}'))"; */
    // echo $sql;

  }else if(count($id)==3){
    $eq=opc_equ();
    $id=divide($_POST['id_mme']);
    $sql = "INSERT INTO vsp_mme VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,DATE_SUB(NOW(),INTERVAL 5 HOUR),?,?,?)";
    $params =[
    ['type' => 'i', 'value' => NULL],//1
    ['type' => 'i', 'value' => $id[0]],//2
    ['type' => 's', 'value' => $_POST['fecha_seg']],//3
    ['type' => 's', 'value' => $_POST['numsegui']],//4
    ['type' => 's', 'value' => $_POST['evento']],//5
    ['type' => 's', 'value' => $_POST['tiposeg']],//6
    ['type' => 's', 'value' => $_POST['estado_s']],//7
    ['type' => 's', 'value' => $_POST['motivo_estado']],//8
    ['type' => 's', 'value' => $_POST['etapa']],//9
    ['type' => 's', 'value' => $_POST['sema_gest']],//10
    ['type' => 's', 'value' => $_POST['gestaciones']],//11
    ['type' => 's', 'value' => $_POST['partos']],//12
    ['type' => 's', 'value' => $_POST['abortos']],//13
    ['type' => 's', 'value' => $_POST['cesareas']],//14
    ['type' => 's', 'value' => $_POST['vivos']],//15
    ['type' => 's', 'value' => $_POST['muertos']],//16
    ['type' => 's', 'value' => $_POST['fecha_egre']],//17
    ['type' => 's', 'value' => $_POST['edad_padre']],//18
    ['type' => 's', 'value' => $_POST['asis_ctrpre']],//19
    ['type' => 's', 'value' => $_POST['ing_ctrpre']],//20
    ['type' => 's', 'value' => $_POST['cpn']],//21
    ['type' => 's', 'value' => $_POST['porque_no']],//22
    ['type' => 's', 'value' => $_POST['exam_lab']],//23
    ['type' => 's', 'value' => $_POST['esqu_vacuna']],//24
    ['type' => 's', 'value' => $_POST['cons_micronutr']],//25
    ['type' => 's', 'value' => $_POST['trata_farma']],//26
    ['type' => 's', 'value' => $_POST['tipo_tratafarma']],//27
    ['type' => 's', 'value' => $_POST['cualtra']],//28
    ['type' => 's', 'value' => $_POST['adhe_tratafarma']],//29
    ['type' => 's', 'value' => $_POST['porque_noadh']],//30
    ['type' => 's', 'value' => $_POST['peso']],//31
    ['type' => 's', 'value' => $_POST['talla']],//32
    ['type' => 's', 'value' => $_POST['imc']],//33
    ['type' => 's', 'value' => $_POST['clasi_nutri']],//34
    ['type' => 's', 'value' => $_POST['signos_alarma_seg']],//35
    ['type' => 's', 'value' => $_POST['descr_sigalarma']],//36
    ['type' => 's', 'value' => $_POST['entrega_medic_labo']],//37
    ['type' => 's', 'value' => $_POST['fecha_obstetrica']],//38
    ['type' => 's', 'value' => $_POST['edad_gesta']],//39
    ['type' => 's', 'value' => $_POST['resul_gest']],//40
    ['type' => 's', 'value' => $_POST['meto_fecunda']],//41
    //['type' => 's', 'value' => $_POST['cual']],//42
    //['type' => 's', 'value' => $_POST['resul_gest']],//43
    //['type' => 's', 'value' => $_POST['meto_fecunda']],//44
    ['type' => 's', 'value' => $_POST['cualmet']],//42
    ['type' => 's', 'value' => $_POST['otro_cual']],//43
    ['type' => 's', 'value' => $_POST['motivo_nofecund']],//44
    ['type' => 's', 'value' => $_POST['control_mac']],//45
    ['type' => 's', 'value' => $_POST['fecha_control_mac']],//46
    ['type' => 's', 'value' => $_POST['ctrl_postpar_espe']],//47
    ['type' => 's', 'value' => $_POST['fecha_postpar_espe']],//48
    ['type' => 's', 'value' => $_POST['asis_ctrl_postpar_espe']],//49
    ['type' => 's', 'value' => $_POST['porque_no_postpar']],//50
    ['type' => 's', 'value' => $_POST['consul_apoy_lacmater']],//51
    ['type' => 's', 'value' => $_POST['signos_alarma']],//52
    ['type' => 's', 'value' => $_POST['desc_sigala']],//53
    ['type' => 's', 'value' => $_POST['disc_ges']],//54
    ['type' => 's', 'value' => $_POST['cual_disc_ges']],//55
    ['type' => 's', 'value' => $_POST['fecha_apoy_lacmater']],//56
    ['type' => 's', 'value' => $_POST['peso_rcnv']],//57
    ['type' => 's', 'value' => $_POST['ctrl_recinac']],//58
    ['type' => 's', 'value' => $_POST['fecha_ctrl_nac']],//59
    ['type' => 's', 'value' => $_POST['asis_ctrl_recinac']],//60
    ['type' => 's', 'value' => $_POST['porque_norec']],//61
    ['type' => 's', 'value' => $_POST['ult_peso']],//62
    ['type' => 's', 'value' => $_POST['consul_lacmate']],//63
    ['type' => 's', 'value' => $_POST['porque_nolact']],//64
    ['type' => 's', 'value' => $_POST['fecha_consul_lacmate']],//65
    ['type' => 's', 'value' => $_POST['asiste_ctrl_cyd']],//66
    ['type' => 's', 'value' => $_POST['vacuna_comple']],//67
    ['type' => 's', 'value' => $_POST['lacmate_exclu']],//68
    ['type' => 's', 'value' => $_POST['signos_alarma']],//69
    ['type' => 's', 'value' => $_POST['cam_sign']],//70
    ['type' => 's', 'value' => $_POST['qui_vida']],//71
    ['type' => 's', 'value' => $_POST['viv_malt']],//72
    ['type' => 's', 'value' => $_POST['adec_red']],//73
    ['type' => 's', 'value' => $_POST['fecha_egreopost']],//74
    ['type' => 's', 'value' => $_POST['estrategia_1']],//75
    ['type' => 's', 'value' => $_POST['estrategia_2']],//76
    ['type' => 's', 'value' => $_POST['acciones_1']],//77
    ['type' => 's', 'value' => $_POST['desc_accion1']],//78
    ['type' => 's', 'value' => $_POST['acciones_2']],//79
    ['type' => 's', 'value' => $_POST['desc_accion2']],//80
    ['type' => 's', 'value' => $_POST['acciones_3']],//81
    ['type' => 's', 'value' => $_POST['desc_accion3']],//82
    ['type' => 's', 'value' => $_POST['activa_ruta']],//83
    ['type' => 's', 'value' => $_POST['ruta']],//84
    ['type' => 's', 'value' => $_POST['novedades']],//85
    ['type' => 's', 'value' => $_POST['otras_condiciones']],//86
    ['type' => 's', 'value' => $_POST['observaciones']],//87
    ['type' => 's', 'value' => $_POST['cierre_caso']],//88
    ['type' => 's', 'value' => $_POST['motivo_cierre']],//89
    ['type' => 's', 'value' => $_POST['fecha_cierre']],//90
    ['type' => 's', 'value' => $_POST['conti_segespecial']],//91
    ['type' => 's', 'value' => $_POST['cual_segespecial']],//92
    ['type' => 's', 'value' => $_POST['recomen_cierre']],//93
    ['type' => 's', 'value' => $_POST['redu_riesgo_cierre']],//94
    ['type' => 's', 'value' => $smbina],//95
    ['type' => 'i', 'value' => $_SESSION['us_sds']],//96
    ['type' => 's', 'value' => NULL],//98
    ['type' => 's', 'value' => NULL],//99
    ['type' => 's', 'value' => 'A']//100
    ];
    //return count($params).' '.json_encode($params).' '.$sql; 
    return  $rta= mysql_prepd($sql, $params);
   /*  $sql="INSERT INTO vsp_mme VALUES (NULL,trim(upper('{$id[0]}')),
    trim(upper('{$_POST['fecha_seg']}')),trim(upper('{$_POST['numsegui']}')),trim(upper('{$_POST['evento']}')),trim(upper('{$_POST['estado_s']}')),trim(upper('{$_POST['motivo_estado']}')),trim(upper('{$_POST['etapa']}')),trim(upper('{$_POST['sema_gest']}')),trim(upper('{$_POST['asis_ctrpre']}')),trim(upper('{$_POST['exam_lab']}')),trim(upper('{$_POST['esqu_vacuna']}')),trim(upper('{$_POST['cons_micronutr']}')),trim(upper('{$_POST['trata_farma']}')),trim(upper('{$_POST['adhe_tratafarma']}')),trim(upper('{$_POST['peso']}')),trim(upper('{$_POST['talla']}')),trim(upper('{$_POST['imc']}')),trim(upper('{$_POST['clasi_nutri']}')),trim(upper('{$_POST['fecha_obstetrica']}')),trim(upper('{$_POST['edad_gesta']}')),trim(upper('{$_POST['resul_gest']}')),trim(upper('{$_POST['meto_fecunda']}')),trim(upper('{$_POST['cual']}')),trim(upper('{$_POST['otro_cual']}')),trim(upper('{$_POST['motivo_nofecund']}')),trim(upper('{$_POST['control_mac']}')),trim(upper('{$_POST['fecha_control_mac']}')),trim(upper('{$_POST['ctrl_postpar_espe']}')),trim(upper('{$_POST['fecha_postpar_espe']}')),trim(upper('{$_POST['consul_apoy_lacmater']}')),trim(upper('{$_POST['fecha_apoy_lacmater']}')),trim(upper('{$_POST['peso_rcnv']}')),trim(upper('{$_POST['consul_lacmate']}')),trim(upper('{$_POST['fecha_consul_lacmate']}')),trim(upper('{$_POST['asiste_ctrl_cyd']}')),trim(upper('{$_POST['vacuna_comple']}')),trim(upper('{$_POST['lacmate_exclu']}')),trim(upper('{$_POST['signos_alarma']}')),trim(upper('{$_POST['signos_alarma_seg']}')),trim(upper('{$_POST['descr_sigalarma']}')),trim(upper('{$_POST['entrega_medic_labo']}')),trim(upper('{$_POST['estrategia_1']}')),trim(upper('{$_POST['estrategia_2']}')),trim(upper('{$_POST['acciones_1']}')),trim(upper('{$_POST['desc_accion1']}')),trim(upper('{$_POST['acciones_2']}')),trim(upper('{$_POST['desc_accion2']}')),trim(upper('{$_POST['acciones_3']}')),trim(upper('{$_POST['desc_accion3']}')),trim(upper('{$_POST['activa_ruta']}')),trim(upper('{$_POST['ruta']}')),trim(upper('{$_POST['novedades']}')),trim(upper('{$_POST['signos_covid']}')),trim(upper('{$_POST['caso_afirmativo']}')),trim(upper('{$_POST['otras_condiciones']}')),trim(upper('{$_POST['observaciones']}')),trim(upper('{$_POST['cierre_caso']}')),trim(upper('{$_POST['motivo_cierre']}')),trim(upper('{$_POST['fecha_cierre']}')),trim(upper('{$_POST['conti_segespecial']}')),trim(upper('{$_POST['cual_segespecial']}')),trim(upper('{$_POST['recomen_cierre']}')),trim(upper('{$_POST['redu_riesgo_cierre']}')),
    trim(upper('{$smbin}')),'{$eq}',TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')"; */
    // echo $sql;
  }
  } 


  function get_mme(){
    if($_REQUEST['id']==''){
      return "";
    }else{
      $id=divide($_REQUEST['id']);
      $sql="SELECT concat(id_mme,'_',idpeople,'_',numsegui,'_',evento),
      fecha_seg,numsegui,evento,tiposeg,estado_s,motivo_estado,etapa,sema_gest,gestaciones,partos,abortos,cesareas,vivos,muertos,fecha_egre,edad_padre,asis_ctrpre,ing_ctrpre,cpn,porque_no,exam_lab,esqu_vacuna,cons_micronutr,trata_farma,tipo_tratafarma,cualtra,adhe_tratafarma,porque_noadh,peso,talla,imc,clasi_nutri,signos_alarma_seg,descr_sigalarma,entrega_medic_labo,fecha_obstetrica,edad_gesta,resul_gest,meto_fecunda,cualmet,otro_cual,motivo_nofecund,control_mac,fecha_control_mac,ctrl_postpar_espe,fecha_postpar_espe,asis_ctrl_postpar_espe,porque_no_postpar,consul_apoy_lacmater,signos_alarma,desc_sigala,disc_ges,cual_disc_ges,fecha_apoy_lacmater,peso_rcnv,ctrl_recinac,fecha_ctrl_nac,asis_ctrl_recinac,porque_norec,ult_peso,consul_lacmate,porque_nolact,fecha_consul_lacmate,asiste_ctrl_cyd,vacuna_comple,lacmate_exclu,signos_alarma_lac,cam_sign,qui_vida,viv_malt,adec_red,fecha_egreopost,estrategia_1,estrategia_2,acciones_1,desc_accion1,acciones_2,desc_accion2,acciones_3,desc_accion3,activa_ruta,ruta,novedades,otras_condiciones,observaciones,cierre_caso,motivo_cierre,fecha_cierre,conti_segespecial,cual_segespecial,recomen_cierre,redu_riesgo_cierre,users_bina
      FROM vsp_mme
      WHERE id_mme ='{$id[0]}'";
      // echo $sql;
      // print_r($id);
      $info=datos_mysql($sql);
      return json_encode($info['responseResult'][0]);
    } 
  }

  function get_persona(){
    if($_POST['id']==0){
      return "";
    }else{
       $id=divide($_POST['id']);
      $sql="SELECT FN_CATALOGODESC(21,sexo) sexo,fecha_nacimiento,fecha, 
      FN_EDAD(fecha_nacimiento,CURDATE()),
      TIMESTAMPDIFF(YEAR,fecha_nacimiento, CURDATE() ) AS ano,
        TIMESTAMPDIFF(MONTH,fecha_nacimiento ,CURDATE() ) % 12 AS mes,
        DATEDIFF(CURDATE(), DATE_ADD(fecha_nacimiento,INTERVAL TIMESTAMPDIFF(MONTH, fecha_nacimiento, CURDATE()) MONTH)) AS dia
		from person P left join hog_carac V ON vivipersona=id_viv 
		WHERE idpeople='".$id[0]."'";
      // echo $sql;
      $info=datos_mysql($sql);
          return $info['responseResult'][0];
      }
    }


function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='mme' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";	
    $rta.="<li class='icono editar' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'mme',event,this,['fecha_seg','numsegui','evento','estado_s','motivo_estado','tiposeg','cierre_caso'],'../vsp/mme.php');\"></li>";
	}
	
 return $rta;
}


function bgcolor($a,$c,$f='c'){
  $rta="";
  return $rta;
   }
