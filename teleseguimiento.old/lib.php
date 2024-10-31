<?php
 require_once '../libs/gestion.php';
ini_set('display_errors','1');
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


function lis_seguimiento(){
	$info=datos_mysql("SELECT COUNT(*) total from eac_seguimiento T LEFT JOIN asigsegui S ON T.documento = S.documento AND T.tipodoc = S.tipo_doc 
	LEFT JOIN usuarios U ON S.doc_asignado = U.id_usuario where 1 ".whe_seguimiento());
	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-seguimiento']))? ($_POST['pag-seguimiento']-1)* $regxPag:0;
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(T.id_seg) ACCIONES,
	T.tipodoc,T.documento,fechaatencion,ordenamedic,reclamomedic,medicadispensados,ordenalaboratorios,laboratoriostomados
  FROM `eac_seguimiento` T
  LEFT JOIN asigsegui S ON T.documento = S.documento AND T.tipodoc = S.tipo_doc
	LEFT JOIN usuarios U ON S.doc_asignado = U.id_usuario
  WHERE 1 ";
	$sql.=whe_seguimiento();
	$sql.=" ORDER BY T.fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"seguimiento",$regxPag);
	}

function whe_seguimiento() {
	$sql = "";
	if ($_POST['fidpersona'])
		$sql .= " AND documental = '".$_POST['fidpersona']."'";
	if ($_POST['ffecha'])
		$sql .= " AND fechaatencion ='".$_POST['ffecha']."' ";
		$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
		$usu=divide($rta["responseResult"][0]['usu']);
		$subred = ($usu[1]=='ADM') ? '1,2,3,4,5' : $usu[2] ;
		$sql.="  and U.componente='EAC' AND U.subred IN(".$subred.") AND S.doc_asignado='".$_SESSION['us_sds']."'";
	return $sql;
}


function focus_seguimiento(){
 return 'seguimiento';
}


function men_seguimiento(){
 $rta=cap_menus('seguimiento','pro');
 return $rta;
}


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='seguimiento'){  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  $rta .= "<li class='icono $a cancelar'    title='Cerrar'          Onclick=\"ocultar('".$a."','".$b."');\" >";
  }
  return $rta;
}


function cmp_seguimiento(){
 $rta="";
 
 $hoy=date('Y-m-d');

 $t=['id_seg'=>'','tipodoc'=>'','documento'=>'','fechaatencion'=>'','poblacionprior'=>'','riesgossalud'=>'','prioridadfamilia'=>'','fechaproyectada'=>'','realizoafilia'=>'','fechaafilia'=>'','fechaverifica'=>'','ordenamedic'=>'','reclamomedic'=>'','medicadispensados'=>'','ordenalaboratorios'=>'','laboratoriostomados'=>'','tiporuta'=>'','riesgoprioritario'=>'','respuestaEAPB'=>'','fechaservicio'=>'','ordenainter'=>'','nombreinter1'=>'','fechaasignainter1'=>'','asistiointerconsulta1'=>'','fechainterconsulta1'=>'','interconsulta1'=>'','nombreinter2'=>'','fechaasignainter2'=>'','asistiointerconsulta2'=>'','fechainterconsulta2'=>'','interconsulta2'=>'','nombreinter3	'=>'','fechaasignainter3'=>'','asistiointerconsulta3'=>'','fechainterconsulta3'=>'','interconsulta3'=>'','Tempcorporal'=>'','Pulso'=>'','frecuenciaresp	'=>'','presionarte'=>'','recomendaciones'=>'','acogidorecomendaciones'=>'','costadocumplir'=>'','incumplimiento'=>'','poblacion'=>'','riesgos'=>'','prioridad'=>'','fechaproxseg'=>'','requieresegpres'=>'','fechasegupres'=>'','clasificaciseg'=>'','sienteactualmente'=>'','estado_emocional'=>'','necesariopsicologia'=>'','sintioprof'=>'','apoyoinstitucional'=>'','recibeapoyo'=>'','convenio'=>'','fuente'=>'','familia'=>'','cierre'=>'','razoncierre'=>''];

 $w='seguimiento';
 $d=get_seguimiento(); 
 if ($d=="") {$d=$t;}
//  $u=($d['id_seg']=='')?true:false;
$u='false';
 $o='infgen';
 $c[]=new cmp($o,'e',null,'INFORMACIÓN GENERAL',$w);
 $c[]=new cmp('id','h','20','',$w.' '.$o,'','',null,null,true,$u,'','col-1');
 $c[]=new cmp('tipodoc','s','3',$d['tipodoc'],$w.' '.$o,'tipodoc','tipodoc',null,null,true,$u,'','col-3');
 $c[]=new cmp('documento','t','20',$d['documento'],$w.' '.$o,'documento','documento',null,null,true,$u,'','col-3');
 $c[]=new cmp('fechaatencion','d','10',$d['fechaatencion'],$w.' '.$o,'fechaatencion','fechaatencion',null,null,true,$u,'','col-4');
 $c[]=new cmp('poblacionprior','s','3',$d['poblacionprior'],$w.' '.$o,'poblacionprior','poblacionprior',null,null,false,$u,'','col-2',false,['upz']);
 $c[]=new cmp('riesgossalud','s','3',$d['riesgossalud'],$w.' '.$o,'riesgossalud','riesgossalud',null,null,false,$u,'','col-2',false,['bar']);
 $c[]=new cmp('prioridadfamilia','t','8',$d['prioridadfamilia'],$w.' '.$o,'prioridadfamilia','prioridadfamilia',null,null,false,$u,'','col-2');
 $c[]=new cmp('fechaproyectada','d','10',$d['fechaproyectada'],$w.' '.$o,'fechaproyectada','fechaproyectada',null,null,false,$u,'','col-2');
 $c[]=new cmp('realizoafilia','o','2',$d['realizoafilia'],$w.' '.$o,'realizoafilia','realizoafilia',null,null,false,$u,'','col-2');
 $c[]=new cmp('fechaafilia','d','10',$d['fechaafilia'],$w.' '.$o,'Sector Catastral','fechaafilia',null,null,true,$u,'','col-2');
 $c[]=new cmp('fechaverifica','d','10',$d['fechaverifica'],$w.' '.$o,'fechaverifica','fechaverifica',null,null,true,$u,'','col-2');
 $c[]=new cmp('ordenamedic','o','2',$d['ordenamedic'],$w.' '.$o,'Predio de Num','ordenamedic',null,null,true,$u,'','col-2');
 $c[]=new cmp('reclamomedic','o','2',$d['reclamomedic'],$w.' '.$o,'Unidad habitacional','reclamomedic',null,null,true,$u,'','col-2');
 $c[]=new cmp('medicadispensados','o','2',$d['medicadispensados'],$w.' '.$o,'medicadispensados','medicadispensados',null,null,false,$u,'','col-2');
 $c[]=new cmp('ordenalaboratorios','o','2',$d['ordenalaboratorios'],$w.' '.$o,'ordenalaboratorios','ordenalaboratorios',null,null,false,$u,'','col-4');
 $c[]=new cmp('laboratoriostomados','o','2',$d['laboratoriostomados'],$w.' '.$o,'laboratoriostomados ?','laboratoriostomados',null,null,false,true,'','col-2','updaAddr(this,false,[\'zona\',\'direccion_nueva\',\'vereda_nueva\',\'cordxn\',\'cordyn\']);');//enabFiel(this,true,[adi]);
 $c[]=new cmp('tiporuta','s','3',$d['tiporuta'],$w.' '.$o,'tiporuta','tiporuta',null,null,false,$u,'','col-4');
 $c[]=new cmp('riesgoprioritario','t','15',$d['riesgoprioritario'],$w.' '.$o,'riesgoprioritario','riesgoprioritario',null,null,false,$u,'','col-3');
 $c[]=new cmp('respuestaEAPB','t','15',$d['respuestaEAPB'],$w.' '.$o,'respuestaEAPB','respuestaEAPB',null,null,false,$u,'','col-3');
 $c[]=new cmp('fechaservicio','d','10',$d['fechaservicio'],$w.' '.$o,'Vereda Nueva','fechaservicio',null,null,false,$u,'','col-5');
 $c[]=new cmp('ordenainter','o','2',$d['ordenainter'],$w.' '.$o,'Cordx Nueva','ordenainter',null,null,false,$u,'','col-25');
 $c[]=new cmp('nombreinter1','o','2',$d['nombreinter1'],$w.' '.$o,'Cordy Nueva','nombreinter1',null,null,false,$u,'','col-25');
 $c[]=new cmp('fechaasignainter1','d','10',$d['fechaasignainter1'],$w.' '.$o,'fechaasignainter1','fechaasignainter1',null,null,false,$u,'','col-25');
 $c[]=new cmp('asistiointerconsulta1','o','2',$d['asistiointerconsulta1'],$w.' '.$o,'asistiointerconsulta1','asistiointerconsulta1',null,null,true,true,'','col-25');
 $c[]=new cmp('fechainterconsulta1','d','10',$d['fechainterconsulta1'],$w.' '.$o,'Motivo de Estado','fechainterconsulta1',null,null,false,false,'','col-4');
 $c[]=new cmp('interconsulta1','o','2',$d['interconsulta1'],$w.' '.$o,'interconsulta1','interconsulta1',null,null,true,true,'','col-25');
 $c[]=new cmp('nombreinter2','o','2',$d['nombreinter2'],$w.' '.$o,'Cordy Nueva','nombreinter2',null,null,false,$u,'','col-25');
 $c[]=new cmp('fechaasignainter2','d','10',$d['fechaasignainter2'],$w.' '.$o,'fechaasignainter2','fechaasignainter2',null,null,false,$u,'','col-25');
 $c[]=new cmp('asistiointerconsulta2','o','2',$d['asistiointerconsulta2'],$w.' '.$o,'asistiointerconsulta2','asistiointerconsulta2',null,null,true,true,'','col-25');
 $c[]=new cmp('fechainterconsulta2','d','10',$d['fechainterconsulta2'],$w.' '.$o,'Motivo de Estado','fechainterconsulta2',null,null,false,false,'','col-4');
 $c[]=new cmp('interconsulta2','o','2',$d['interconsulta2'],$w.' '.$o,'interconsulta2','interconsulta2',null,null,true,true,'','col-25');
 $c[]=new cmp('nombreinter3','o','2',$d['nombreinter3'],$w.' '.$o,'Cordy Nueva','nombreinter3',null,null,false,$u,'','col-25');
 $c[]=new cmp('fechaasignainter3','d','10',$d['fechaasignainter3'],$w.' '.$o,'fechaasignainter3','fechaasignainter3',null,null,false,$u,'','col-25');
 $c[]=new cmp('asistiointerconsulta3','o','2',$d['asistiointerconsulta3'],$w.' '.$o,'asistiointerconsulta3','asistiointerconsulta3',null,null,true,true,'','col-25');
 $c[]=new cmp('fechainterconsulta3','d','10',$d['fechainterconsulta3'],$w.' '.$o,'Motivo de Estado','fechainterconsulta3',null,null,false,false,'','col-4');
 $c[]=new cmp('interconsulta3','o','2',$d['interconsulta3'],$w.' '.$o,'interconsulta3','interconsulta3',null,null,true,true,'','col-25');
 
 $c[]=new cmp('tempcorporal','n','10',$d['tempcorporal'],$w.' '.$o,'tempcorporal','tempcorporal',null,null,true,$u,'','col-3');
 $c[]=new cmp('pulso','n','10',$d['pulso'],$w.' '.$o,'pulso','pulso',null,null,true,$u,'','col-3');
 $c[]=new cmp('frecuenciaresp','n','10',$d['frecuenciaresp'],$w.' '.$o,'frecuenciaresp','frecuenciaresp',null,null,true,$u,'','col-3');
 $c[]=new cmp('presionarte','n','10',$d['presionarte'],$w.' '.$o,'presionarte','presionarte',null,null,true,$u,'','col-3');

 $c[]=new cmp('recomendaciones','s','3',$d['recomendaciones'],$w.' '.$o,'recomendaciones','recomendaciones',null,null,false,$u,'','col-4');
 $c[]=new cmp('acogidorecomendaciones','s','3',$d['acogidorecomendaciones'],$w.' '.$o,'acogidorecomendaciones','recomendaciones',null,null,false,$u,'','col-4');
 $c[]=new cmp('costadocumplir','o','2',$d['costadocumplir'],$w.' '.$o,'costadocumplir','costadocumplir',null,null,true,true,'','col-25');
 $c[]=new cmp('incumplimiento','o','2',$d['incumplimiento'],$w.' '.$o,'incumplimiento','incumplimiento',null,null,true,true,'','col-25');

 $c[]=new cmp('poblacion','s','3',$d['poblacion'],$w.' '.$o,'poblacion','poblacion',null,null,false,$u,'','col-4');
 $c[]=new cmp('riesgos','s','3',$d['riesgos'],$w.' '.$o,'riesgos','riesgos',null,null,false,$u,'','col-4');
 $c[]=new cmp('prioridad','s','3',$d['prioridad'],$w.' '.$o,'prioridad','prioridad',null,null,false,$u,'','col-4');
 $c[]=new cmp('fechaproxseg','d','10',$d['fechaproxseg'],$w.' '.$o,'Motivo de Estado','fechaproxseg',null,null,false,false,'','col-4');
 $c[]=new cmp('incumplimiento','o','2',$d['incumplimiento'],$w.' '.$o,'incumplimiento','incumplimiento',null,null,true,true,'','col-25');
 $c[]=new cmp('requieresegpres','o','2',$d['requieresegpres'],$w.' '.$o,'requieresegpres','requieresegpres',null,null,true,true,'','col-25');
 $c[]=new cmp('fechasegupres','d','10',$d['fechasegupres'],$w.' '.$o,'Motivo de Estado','fechasegupres',null,null,false,false,'','col-4');
 $c[]=new cmp('clasificaciseg','s','3',$d['clasificaciseg'],$w.' '.$o,'clasificaciseg','clasificaciseg',null,null,false,$u,'','col-4');
 $c[]=new cmp('sienteactualmente','o','2',$d['sienteactualmente'],$w.' '.$o,'sienteactualmente','sienteactualmente',null,null,true,true,'','col-25');
 $c[]=new cmp('estado_emocional','s','3',$d['estado_emocional'],$w.' '.$o,'estado_emocional','estado_emocional',null,null,false,$u,'','col-4');
 $c[]=new cmp('necesariopsicologia','o','2',$d['necesariopsicologia'],$w.' '.$o,'necesariopsicologia','necesariopsicologia',null,null,true,true,'','col-25');
 $c[]=new cmp('sintioprof','o','2',$d['sintioprof'],$w.' '.$o,'sintioprof','sintioprof',null,null,true,true,'','col-25');
 $c[]=new cmp('apoyoinstitucional','o','2',$d['apoyoinstitucional'],$w.' '.$o,'apoyoinstitucional','apoyoinstitucional',null,null,true,true,'','col-25');
 $c[]=new cmp('recibeapoyo','o','2',$d['recibeapoyo'],$w.' '.$o,'recibeapoyo','recibeapoyo',null,null,true,true,'','col-25');
 $c[]=new cmp('convenio','s','3',$d['convenio'],$w.' '.$o,'convenio','convenio',null,null,false,$u,'','col-4');
 $c[]=new cmp('fuente','s','3',$d['fuente'],$w.' '.$o,'fuente','fuente',null,null,false,$u,'','col-4');
 $c[]=new cmp('familia','o','2',$d['familia'],$w.' '.$o,'familia','familia',null,null,true,true,'','col-25');
 $c[]=new cmp('razoncierre','s','3',$d['razoncierre'],$w.' '.$o,'razoncierre','razoncierre',null,null,false,$u,'','col-4');

 for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
//  $rta .="<div class='encabezado integrantes'>TABLA DE INTEGRANTES DE LA FAMILIA</div><div class='contenido' id='integrantes-lis' >".lis_integrantes1()."</div></div>";
 return $rta;
}


function opc_tipodoc($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_poblacionprior($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_riesgossalud($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_tiporuta($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_recomendaciones($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_poblacion($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_riesgos($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_prioridad($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_clasificaciseg($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_estado_emocional($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_convenio($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_fuente($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_razoncierre($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}


function get_seguimiento(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT tipodoc,documento,fechaatencion,poblacionprior,riesgossalud,prioridadfamilia,fechaproyectada,realizoafilia,fechaafilia,fechaverifica,ordenamedic,reclamomedic,medicadispensados,ordenalaboratorios,laboratoriostomados,tiporuta,riesgoprioritario,respuestaEAPB,fechaservicio,ordenainter,nombreinter1,fechaasignainter1,asistiointerconsulta1,fechainterconsulta1,interconsulta1,nombreinter2,fechaasignainter2,asistiointerconsulta2,fechainterconsulta2,interconsulta2,nombreinter3	,fechaasignainter3,asistiointerconsulta3,fechainterconsulta3,interconsulta3,tempcorporal,pulso,frecuenciaresp	,presionarte,recomendaciones,acogidorecomendaciones,costadocumplir,incumplimiento,poblacion,riesgos,prioridad,fechaproxseg,requieresegpres,fechasegupres,clasificaciseg,sienteactualmente,estado_emocional,necesariopsicologia,sintioprof,apoyoinstitucional,recibeapoyo,convenio,fuente,familia,cierre,razoncierre
		FROM `eac_seguimiento` WHERE  id_seg='{$id[0]}'";

// sector_catastral,'_',nummanzana,'_',predio_num,'_',estrategia,'_',estado_v
		$info=datos_mysql($sql);
    	// echo $sql."=>".$_POST['id'];
		return $info['responseResult'][0];
	} 
}

 
function gra_seguimiento(){
	/* $sql="INSERT INTO hog_geo VALUES 
	(NULL,TRIM(UPPER('{$_POST['estrategia']}')),
	TRIM(UPPER('{$_POST['subred']}')),
	TRIM(UPPER('{$_POST['zona']}')),
	TRIM(UPPER('{$_POST['localidad']}')),
	TRIM(UPPER('{$_POST['upz']}')),
	TRIM(UPPER('{$_POST['barrio']}')),
	TRIM(UPPER('{$_POST['territorio']}')),
	TRIM(UPPER('{$_POST['microterritorio']}')),
	TRIM(UPPER('{$_POST['sector_catastral']}')),
	TRIM(UPPER('{$_POST['direccion']}')),
	TRIM(UPPER('{$_POST['direccion_nueva']}')),
	TRIM(UPPER('{$_POST['nummanzana']}')),
	TRIM(UPPER('{$_POST['predio_num']}')),
	TRIM(UPPER('{$_POST['unidad_habit']}')),
	TRIM(UPPER('{$_POST['vereda']}')),
	TRIM(UPPER('{$_POST['vereda_nueva']}')),
	TRIM(UPPER('{$_POST['cordx']}')),
	TRIM(UPPER('{$_POST['cordy']}')),
	TRIM(UPPER('{$_POST['cordxn']}')),
	TRIM(UPPER('{$_POST['cordyn']}')),
	TRIM(UPPER('{$_POST['estrato']}')),
	TRIM(UPPER('{$_POST['asignado']}')),
	TRIM(UPPER('{$_POST['estado_v']}')),
	TRIM(UPPER('{$_POST['motivo_estado']}')),
	TRIM(UPPER('{$_SESSION['us_sds']}')),
	DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL);"; 
	INSERT INTO `eac_seguimiento` (`id_seg`, `tipodoc`, `documento`, `fechaatencion`, `poblacionprior`, `riesgossalud`, `prioridadfamilia`, `fechaproyectada`, `realizoafilia`, `fechaafilia`, `fechaverifica`, `ordenamedic`, `reclamomedic`, `medicadispensados`, `ordenalaboratorios`, `laboratoriostomados`, `tiporuta`, `riesgoprioritario`, `respuestaEAPB`, `fechaservicio`, `ordenainter`, `nombreinter1`, `asistiointerconsulta1`, `fechainterconsulta1`, `interconsulta1`, `nombreinter2`, `fechaasignainter2`, `asistiointerconsulta2`, `fechainterconsulta2`, `interconsulta2`, `nombreinter3`, `fechaasignainter3`, `asistiointerconsulta3`, `fechainterconsulta3`, `fechaintercinsulta3`, `interconsulta3`, `tempcorporal`, `pulso`, `frecuenciaresp`, `presionarte`, `recomendaciones`, `acogidorecomendaciones`, `costadocumplir`, `incumplimiento`, `poblacion`, `riesgos`, `prioridad`, `fechaproxseg`, `requieresegpres`, `fechasegupres`, `clasificaciseg`, `sienteactualmente`, `estado_emocional`, `necesariopsicologia`, `sintioprof`, `apoyoinstitucional`, `recibeapoyo`, `convenio`, `fuente`, `familia`, `cierre`, `razoncierre`, `usu_creo`, `fecha_create`, `usu_update`, `fecha_update`, `estado`, `fechaasignainter1`) VALUES (NULL, 'CC', '80811594', '2023-06-15', '1', '1', '1', '2023-06-14', 'SI', '2023-06-22', '2023-06-15', 'SI', 'SI', 'SI', 'SI', 'SI', '1', '1', '1', '2023-06-28', '1', '1', '1', '2023-06-21', '1', '1', '2023-06-13', 'SI', '2023-06-14', 'SI', 'SI', '2023-06-07', 'SI', '2023-06-14', '2023-06-21', 'SI', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '2023-06-14', '1', '2023-06-14', '1', 'SI', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '80811594', current_timestamp(), '', '', 'A', '2023-06-21');*/
	// echo $sql;
  $rta=dato_mysql($sql);
  return $rta;
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='seguimiento' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono mapa1' title='Editar TeleSeguimiento' id='".$c['ACCIONES']."' Onclick=\"mostrar('seguimiento','pro',event,'','lib.php',7);setTimeout(hideExpres,1000,'estado_v',['7']);\"></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
