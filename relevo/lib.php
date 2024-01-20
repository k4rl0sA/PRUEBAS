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

function lis_relevo(){
	$info=datos_mysql("SELECT COUNT(*) total FROM personas P LEFT JOIN personas_datocomp D ON idpersona=dc_documento AND tipo_doc=dc_tipo_doc
	LEFT JOIN rel_relevo R ON idpersona=rel_documento AND tipo_doc=rel_tipo_doc
	LEFT JOIN hog_viv V ON P.vivipersona=V.idviv
	left join hog_geo G ON V.idgeo=concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v)	
	LEFT JOIN asigrelevo S ON P.idpersona = S.documento AND P.tipo_doc = S.tipo_doc
	LEFT JOIN usuarios U ON S.doc_asignado = U.id_usuario  WHERE P.cuidador='SI' ".whe_relevo());
	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-relevo']))? ($_POST['pag-relevo']-1)* $regxPag:0;

	// var_dump(rol('relevo'));
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(P.tipo_doc,'_',P.idpersona) ACCIONES,
rel_validacion17 Relevo,`idpersona` AS 'N° Documento',concat_ws(' ',`nombre1`,`nombre2`,`apellido1`,`apellido2`) nombres,G.localidad,sector_catastral,nummanzana,direccion
 FROM `personas` P
  LEFT JOIN personas_datocomp D ON idpersona=dc_documento AND tipo_doc=dc_tipo_doc
  LEFT JOIN rel_relevo R ON idpersona=rel_documento AND tipo_doc=rel_tipo_doc
  LEFT JOIN hog_viv V ON P.vivipersona=V.idviv
  left join hog_geo G ON V.idgeo=concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v)	
  LEFT JOIN asigrelevo S ON P.idpersona = S.documento AND P.tipo_doc = S.tipo_doc
  LEFT JOIN usuarios U ON S.doc_asignado = U.id_usuario 
  WHERE cuidador='SI'";
	$sql.=whe_relevo();
	$sql.=" ORDER BY P.fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
// echo $sql;
/* 	$sql1="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(tipo_doc,'_',idpersona) ACCIONES,
	rel_validacion17 Relevo,`idpersona` AS 'N° Documento',concat_ws(' ',`nombre1`,`nombre2`,`apellido1`,`apellido2`) nombres,G.localidad,sector_catastral,nummanzana,direccion
	 FROM `personas` P
	  LEFT JOIN personas_datocomp D ON idpersona=dc_documento AND tipo_doc=dc_tipo_doc
	  LEFT JOIN rel_relevo R ON idpersona=rel_documento AND tipo_doc=rel_tipo_doc
	  LEFT JOIN hog_viv V ON P.vivipersona=V.idviv
	  left join hog_geo G ON V.idgeo=concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v)	
	  WHERE cuidador='SI'";
		$sql1.=whe_relevo();
$_SESSION['sql_relevo']=$sql1; */
 //echo $sql;
	
	$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"relevo",$regxPag);
}

function whe_relevo() {
	$sql = "";
	if ($_POST['fid'])
		$sql .= " AND P.idpersona like '%".$_POST['fid']."%'";
		$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
		$usu=divide($rta["responseResult"][0]['usu']);
		$subred = ($usu[1]=='TSO') ? '1,2,3,4,5' : $usu[2] ;
		$sql.="  and U.componente in ('EAC','ADM') AND U.subred IN(".$subred.") AND S.doc_asignado='".$_SESSION['us_sds']."'";
	return $sql;
}

function focus_relevo(){
	return 'relevo';
}

function men_relevo(){
 $rta=cap_menus('relevo','pro');
 return $rta;
}

/* function lis_session(){
	// print_r($_POST);
	// print_r($_REQUEST);
	$id=divide($_POST['id']);
	$sql="SELECT idsesion ACCIONES,`rel_validacion1` Sesion, `rel_validacion2` Fecha,rel_validacion3 perfil,FN_CATALOGODESC(301,`rel_validacion4`) Actividad,`rel_validacion5`
  	descripcion 
	FROM `rel_sesion`
	WHERE rel_tipo_doc='{$id[0]}' and rel_documento='{$id[1]}'";
	$sql.=" ORDER BY fecha_create";
		//  echo $sql;
		$datos=datos_mysql($sql);
		return panel_content($datos["responseResult"],"session",2);
} */


function cap_menus($a,$b='cap',$con='con') {
	$rta = "";
	$acc=rol($a);
	if($a=='relevo' && isset($acc['crear']) && $acc['crear']=='SI'){
		$rta.= "<li class='icono $a grabar' title='Grabar' OnClick=\"grabar('$a',this);\"></li>";
		$rta.= "<li class='icono $a actualizar' title='Actualizar' Onclick=\"act_lista('$a',this);\"></li>";
	}
	if ($a=='sesiones' && isset($acc['crear']) && $acc['crear']=='SI'){  
		$rta .= "<li class='icono $a grabar' title='Grabar' OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
  	}
	return $rta;
}


function formato_dato($a,$b,$c,$d){
	$b=strtolower($b);
	$rta=$c[$d];
	// $rta=iconv('UTF-8','ISO-8859-1',$rta);
	// var_dump($a);
	// var_dump($c);
	if ($a=='relevo' && $b=='acciones'){
		$rta="<nav class='menu right'>";
		$rta.="<li class='icono mapa' title='Aceptación Relevos' id='".$c['ACCIONES']."' Onclick=\"mostrar('relevo','pro',event,'','lib.php',7);enabDateRel(this,['fre'])\"></li>";
		if($c['Relevo']=='SI'){
			// $perfiles=['LARREL','TOPREL','LEFREL','TSOREL'];
			$perfiles = ['ADM','LARREL', 'FISREL', 'LEFREL', 'TSOREL'];
			$rta .= "<li class='icono editar' title='Sesión' id='{$c['ACCIONES']}' onclick=\"mostrar('sesiones','pro',event,'','sesiones.php',7);setTimeout(chanActi,300,'rel_validacion3','act',['".implode("','",$perfiles)."']);\"></li>";
			// $rta.="<li class='icono editar' title='Sesión' id='".$c['ACCIONES']."' Onclick=\"mostrar('sesiones','pro',event,'','sesiones.php',7);setTimeout(chanActi,300,'rel_validacion3','act',['LARREL','TOPREL','LEFREL','TSOREL']);\"></li>";
			if(perfil1()=='AUXREL' || perfil1()=='ADM' ){		
					$rta.="<li class='icono medida' title='Crear Signos' id='".$c['ACCIONES']."' Onclick=\"mostrar('vitals_signs','pro',event,'','sigvital.php',7);\"></li>";  //getData('plancon',event,this,'id');   act_lista(f,this);
				}
		}
	}
	if ($a=='session' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
			$rta.="<li class='icono editar ' title='Editar Sesión' id='".$c['ACCIONES']."' Onclick=\"Color('session-lis');setTimeout(getData,300,'sesiones',event,this,['rel_validacion1','rel_validacion2','rel_validacion3','rel_validacion4'],'sesiones.php');\"></li>";  //getData('plancon',event,this,'id');   act_lista(f,this);
		}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
	$rta="";
	return $rta;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cmp_relevo() {
	$rta="";
	$hoy=date('Y-m-d');
	$t=['tipo_doc'=>'','idpersona'=>'','nombre1'=>'','fecha_nacimiento'=>'','sexo'=>'','genero'=>'','etnia'=>'','nacionalidad'=>'','regimen'=>'','eapb'=>'','rel_validacion1'=>'','rel_validacion2'=>'','rel_validacion3'=>'','rel_validacion4'=>'','rel_validacion5'=>'','rel_validacion6'=>'','rel_validacion7'=>'','rel_validacion8'=>'','rel_validacion9'=>'','rel_validacion10'=>'','rel_validacion11'=>'','rel_validacion12'=>'','rel_validacion13'=>'','rel_validacion14'=>'','rel_validacion15'=>'','rel_validacion16'=>'','rel_validacion17'=>'','rel_validacion18'=>'','fecha_create'=>'','usu_creo'=>'','fecha_update'=>'','usu_update'=>'','estado'=>'','np_cuida'=>'','cuidado_2'=>'','antecedentes_2'=>'','otro_2'=>'','discapacidad_2'=>'','cuidado_3'=>'','antecedentes_3'=>'','otro_3'=>'','discapacidad_3'=>''];
	$w='relevo';
	$j=get_relevo();$i=get_personas();$d=get_hamilton();$e=get_zarit();$f=get_apgar();$g=get_zung();$h=get_ophi();
	if ($j=="") {$j=$t;} if ($h=="") {$h=$t;}	if ($d=="") {$d=$t;}	if ($e=="") {$e=$t;}	if ($f=="") {$f=$t;}	if ($g=="") {$g=$t;}	if ($i=="") {$i=$t;}
	$u=($j['tipo_doc']=='')?true:false;
	$o='infgen';

	$c[]=new cmp($o,'e',null,'INFORMACIÓN DE LA PERSONA CUIDADORA',$w);	
	$c[]=new cmp('idrel','h','20',$i['tipo_doc'] . "_" . $i['idpersona'] ,$w.' '.$o,'','',null,null,false,$u,'','col-1');
	$c[]=new cmp('rel_tipo_doc','s','3',$i['tipo_doc'],$w.' '.$o,'Tipo documento','rel_tipo_doc',null,null,true,false,'','col-5');
	$c[]=new cmp('rel_documento','t','20',$i['idpersona'],$w.' '.$o,'N° de identificacion','rel_documento',null,null,true,false,'','col-5');
	$c[]=new cmp('rel_nombre1','t','50',$i['nombre1'],$w.' '.$o,'Nombre Completo','rel_nombre1',null,'',false,false,'','col-4');
	$c[]=new cmp('rel_fecha_nacimiento','d','20',$i['fecha_nacimiento'],$w.' '.$o,'Fecha nacimiento','rel_fecha_nacimiento',null,'',false,false,'','col-2');
	$c[]=new cmp('rel_sexo','s','20',$i['sexo'],$w.' '.$o,'Sexo','rel_sexo',null,'',false,false,'','col-2');
	$c[]=new cmp('rel_genero','s','20',$i['genero'],$w.' '.$o,'Genero','rel_genero',null,'',false,false,'','col-2');
	$c[]=new cmp('rel_etnia','s','20',$i['etnia'],$w.' '.$o,'Etnia','rel_etnia',null,'',false,false,'','col-2');
	$c[]=new cmp('rel_nacionalidad','s','20',$i['nacionalidad'],$w.' '.$o,'Nacionalidad','rel_nacionalidad',null,'',false,false,'','col-3');
	$c[]=new cmp('rel_regimen','s','20',$i['regimen'],$w.' '.$o,'Regimen','rel_regimen',null,'',false,false,'','col-2');
	$c[]=new cmp('rel_eapb','s','20',$i['eapb'],$w.' '.$o,'eapb','rel_eapb',null,'',false,false,'','col-3');
	$c[]=new cmp('rel_validacion1','s','2',$j['rel_validacion1'],$w.' '.$o,'ANTECEDENTES PATOLOGICOS DEL CUIDADOR','rel_validacion1',null,null,true,true,'','col-3',"othePath(this,'oth');");
	$c[]=new cmp('rel_validacion2','t','50',$j['rel_validacion2'],$w.' oth '.$o,'Otro, Cual','rel_validacion2',null,null,false,false,'','col-3');

	$o='infgen_2';
	$c[]=new cmp($o,'e',null,'Resultado de tamizajes Iniciales',$w);
	$c[]=new cmp('rel_validacion3','s','3',$j['rel_validacion3'],$w.' '.$o,'Modalidad','rel_validacion3',null,null,true,true,'','col-10',"enabMod(this,'mod');");
	$c[]=new cmp('rel_validacion4','t','50',$d['rel_validacion8'],$w.' '.$o,'ESCALA DE ANSIEDAD HAMILTON-Inicial','rel_validacion4',null,null,false,false,'','col-25');
	$c[]=new cmp('rel_validacion6','t','2',$e['rel_validacion10'],$w.' '.$o,'ZARIT INTERPRETACIÓN - Inicial','rel_validacion6',null,null,false,false,'','col-25');
	$c[]=new cmp('rel_validacion9','t','50',$g['rel_validacion5'],$w.' '.$o,'ESCALA DEPRESIÓN ZUNG PUNTAJE INICIAL','rel_validacion9',null,null,false,false,'','col-25');
	$c[]=new cmp('rel_validacion12','t','50',$h['rel_validacion12'],$w.' '.$o,'OPHI-II INICIAL','rel_validacion12',null,null,false,false,'','col-25');

	$o='infgen_3';
	$c[]=new cmp($o,'e',null,'INFORMACION DE LA PERSONA QUE REQUIERE EL CUIDADO',$w);
	$c[]=new cmp('rel_validacion13','s','18',$j['rel_validacion13'],$w.' care '.$o,'Seleccione Usuario que requiere cuidado','rel_validacion13',null,null,true,true,'','col-2',"validCare('care');");
	$c[]=new cmp('rel_validacion14','s','3',$j['rel_validacion14'],$w.' '.$o,'ANTECEDENTES PATOLOGICOS','rel_validacion14',null,null,true,true,'','col-2',"othePath(this,'ot');");
	$c[]=new cmp('rel_validacion15','t','50',$j['rel_validacion15'],$w.' ot '.$o,'Otro, Cual','rel_validacion15',null,null,false,false,'','col-2');
	$c[]=new cmp('rel_validacion16','s','3',$j['rel_validacion16'],$w.' '.$o,'TIPO DE DISCAPACIDAD','rel_validacion16',null,null,true,true,'','col-2');
	$c[]=new cmp('np_cuida','s','3',$j['np_cuida'],$w.' mod '.$o,'Número de personas al Cuidado','np_cuida',null,null,false,false,'','col-2',"enabCare(this,['cr2','cr3']);");
	$c[]=new cmp('cuidado_2','s','18',$j['cuidado_2'],$w.' cr2 care '.$o,'Seleccione Usuario que requiere cuidado 2','rel_validacion13',null,null,false,false,'','col-3');
	$c[]=new cmp('antecedentes_2','s','3',$j['antecedentes_2'],$w.' cr2 '.$o,'ANTECEDENTES PATOLOGICOS','rel_validacion14',null,null,false,false,'','col-2',"othePath(this,'ot1');");
	$c[]=new cmp('otro_2','t','50',$j['otro_2'],$w.' ot1 cr2 '.$o,'Otro, Cual','otro_2',null,null,false,false,'','col-2');
	$c[]=new cmp('discapacidad_2','s','3',$j['discapacidad_2'],$w.' cr2 '.$o,'TIPO DE DISCAPACIDAD','rel_validacion16',null,null,false,false,'','col-3');
	$c[]=new cmp('cuidado_3','s','18',$j['cuidado_3'],$w.' cr3 care '.$o,'Seleccione Usuario que requiere cuidado 3','rel_validacion13',null,null,false,false,'','col-3');
	$c[]=new cmp('antecedentes_3','s','3',$j['antecedentes_3'],$w.' cr3 '.$o,'ANTECEDENTES PATOLOGICOS','rel_validacion14',null,null,false,false,'','col-2',"othePath(this,'ot2');");
	$c[]=new cmp('otro_3','t','50',$j['otro_3'],$w.' ot2 cr3 '.$o,'Otro, Cual','otro_3',null,null,false,false,'','col-2');
	$c[]=new cmp('discapacidad_3','s','3',$j['discapacidad_3'],$w.' cr3 '.$o,'TIPO DE DISCAPACIDAD','rel_validacion16',null,null,false,false,'','col-3');
	$c[]=new cmp('rel_validacion17','s','3',$j['rel_validacion17'],$w.' '.$o,'Aceptacion Releveos','aler',null,null,true,true,'','col-2',"enabDateRel(this,['fre']);");
	$c[]=new cmp('rel_validacion18','d','10',$j['rel_validacion18'],$w.' fre '.$o,'Fecha de la identificación','rel_validacion18',null,null,false,true,'','col-4','validDate(this,-22)');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	//  $rta .="<div class='encabezado integrantes'>TABLA DE INTEGRANTES DE LA FAMILIA</div><div class='contenido' id='integrantes-lis' >".lis_integrantes1()."</div></div>";
	return $rta;
}

function get_relevo(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT rel_tipo_doc tipo_doc,rel_documento idpersona,concat_ws(' ',nombre1,nombre2,apellido1,apellido2)  nombre1 ,fecha_nacimiento,sexo,genero,etnia,nacionalidad,regimen,eapb,
		rel_validacion1,rel_validacion2,np_cuida,rel_validacion3,rel_validacion13,cuidado_2,antecedentes_2,otro_2,discapacidad_2,cuidado_3,antecedentes_3,otro_3,discapacidad_3,rel_validacion14,rel_validacion15,rel_validacion16,rel_validacion17,rel_validacion18
		FROM rel_relevo R 
		LEFT JOIN personas P ON rel_documento=idpersona AND rel_tipo_doc =tipo_doc
		WHERE rel_tipo_doc='{$id[0]}' AND rel_documento='{$id[1]}'";

		$info=datos_mysql($sql);
		if ($info['responseResult']){
			return $info['responseResult'][0];
		} else {
			return "";
		}
	} 
}

function get_personas(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT tipo_doc,idpersona,concat_ws(' ',nombre1,nombre2,apellido1,apellido2)  nombre1 ,fecha_nacimiento,sexo,genero,etnia,nacionalidad,regimen,eapb,
		rel_validacion1,rel_validacion2,rel_validacion3,rel_validacion13,rel_validacion14,rel_validacion15,rel_validacion16,rel_validacion17,rel_validacion18
		FROM personas P 
		LEFT JOIN rel_relevo R ON idpersona=rel_documento AND tipo_doc=rel_tipo_doc
		WHERE tipo_doc='{$id[0]}' AND idpersona='{$id[1]}'";

		$info=datos_mysql($sql);
		if ($info['responseResult']){
			return $info['responseResult'][0];
		} else {
			return "";
		}
	} 
}
function get_hamilton(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT hamilton_total rel_validacion4,hamilton_analisis rel_validacion8 
		FROM hog_tam_hamilton H
		LEFT JOIN rel_relevo R ON H.hamilton_idpersona=R.rel_documento  AND  H.hamilton_tipodoc=R.rel_tipo_doc
		WHERE
			(H.hamilton_momento = 2 OR 
			(H.hamilton_idpersona IS NULL AND H.hamilton_tipodoc IS NULL AND NOT EXISTS (
				SELECT 1 FROM hog_tam_hamilton WHERE hamilton_idpersona = R.rel_documento AND hamilton_tipodoc = R.rel_tipo_doc
			)) OR 
			(H.hamilton_idpersona IS NOT NULL AND H.hamilton_tipodoc IS NOT NULL AND NOT EXISTS (
				SELECT 1 FROM hog_tam_hamilton WHERE hamilton_idpersona = R.rel_documento AND hamilton_tipodoc = R.rel_tipo_doc AND hamilton_momento = 2
			))) AND hamilton_tipodoc='{$id[0]}' AND hamilton_idpersona='{$id[1]}'";

		$info=datos_mysql($sql);
		if ($info['responseResult']){
			return $info['responseResult'][0];
		} else {
			return "";
		}
	} 
}

function get_zarit(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT zarit_puntaje rel_validacion6,zarit_analisis rel_validacion10 
		FROM hog_tam_zarit H
		LEFT JOIN rel_relevo R ON H.zarit_idpersona=R.rel_documento  AND  H.zarit_tipodoc=R.rel_tipo_doc
		WHERE
			(H.zarit_momento = 2 OR 
			(H.zarit_idpersona IS NULL AND H.zarit_tipodoc IS NULL AND NOT EXISTS (
				SELECT 1 FROM hog_tam_zarit WHERE zarit_idpersona = R.rel_documento AND zarit_tipodoc = R.rel_tipo_doc
			)) OR 
			(H.zarit_idpersona IS NOT NULL AND H.zarit_tipodoc IS NOT NULL AND NOT EXISTS (
				SELECT 1 FROM hog_tam_zarit WHERE zarit_idpersona = R.rel_documento AND zarit_tipodoc = R.rel_tipo_doc AND zarit_momento = 2
			))) AND zarit_tipodoc='{$id[0]}' AND zarit_idpersona='{$id[1]}'";

		$info=datos_mysql($sql);
		if ($info['responseResult']){
			return $info['responseResult'][0];
		} else {
			return "";
		}
	} 
}
 
function get_apgar(){
	/* if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT  rel_validacion7,apgar_analisis rel_validacion11 
		FROM hog_tam_apgar H
		LEFT JOIN rel_relevo R ON H.apgar_idpersona=R.rel_documento  AND  H.apgar_tipodoc=R.rel_tipo_doc
		WHERE
			(H.apgar_momento = 2 OR 
			(H.apgar_idpersona IS NULL AND H.apgar_tipodoc IS NULL AND NOT EXISTS (
				SELECT 1 FROM hog_tam_apgar WHERE apgar_idpersona = R.rel_documento AND apgar_tipodoc = R.rel_tipo_doc
			)) OR 
			(H.apgar_idpersona IS NOT NULL AND H.apgar_tipodoc IS NOT NULL AND NOT EXISTS (
				SELECT 1 FROM hog_tam_apgar WHERE apgar_idpersona = R.rel_documento AND apgar_tipodoc = R.rel_tipo_doc AND apgar_momento = 2
			))) AND apgar_tipodoc='{$id[0]}' AND apgar_idpersona='{$id[1]}'";

		$info=datos_mysql($sql);
		if ($info['responseResult']){
			return $info['responseResult'][0];
		} else {
			return "";
		}
	}  */
}

function get_zung(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT zung_puntaje rel_validacion9,zung_analisis rel_validacion5 
		FROM hog_tam_zung H
		LEFT JOIN rel_relevo R ON H.zung_idpersona=R.rel_documento  AND  H.zung_tipodoc=R.rel_tipo_doc
		WHERE  
			(H.zung_momento = 2 OR 
			(H.zung_idpersona IS NULL AND H.zung_tipodoc IS NULL AND NOT EXISTS (
				SELECT 1 FROM hog_tam_zung WHERE zung_idpersona = R.rel_documento AND zung_tipodoc = R.rel_tipo_doc
			)) OR 
			(H.zung_idpersona IS NOT NULL AND H.zung_tipodoc IS NOT NULL AND NOT EXISTS (
				SELECT 1 FROM hog_tam_zung WHERE zung_idpersona = R.rel_documento AND zung_tipodoc = R.rel_tipo_doc AND zung_momento = 2
			))) AND zung_tipodoc='{$id[0]}' AND zung_idpersona='{$id[1]}'";

		$info=datos_mysql($sql);
		if ($info['responseResult']){
			// print_r($info);
			return $info['responseResult'][0];
		} else {
			return "";
		}
	} 
}

function get_ophi(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		$sql="SELECT ophi_puntaje rel_validacion12 
		FROM hog_tam_ophi H
		LEFT JOIN rel_relevo R ON H.ophi_idpersona=R.rel_documento  AND  H.ophi_tipodoc=R.rel_tipo_doc
		WHERE 
			(H.ophi_momento = 2 OR 
			(H.ophi_idpersona IS NULL AND H.ophi_tipodoc IS NULL AND NOT EXISTS (
				SELECT 1 FROM hog_tam_ophi WHERE ophi_idpersona = R.rel_documento AND ophi_tipodoc = R.rel_tipo_doc
			)) OR 
			(H.ophi_idpersona IS NOT NULL AND H.ophi_tipodoc IS NOT NULL AND NOT EXISTS (
				SELECT 1 FROM hog_tam_ophi WHERE ophi_idpersona = R.rel_documento AND ophi_tipodoc = R.rel_tipo_doc AND ophi_momento = 2
			))) AND ophi_tipodoc='{$id[0]}' AND ophi_idpersona='{$id[1]}'";
		$info=datos_mysql($sql);
		if ($info['responseResult']){
			// echo $sql;
			// var_dump($info['responseResult']);
			return $info['responseResult'][0];
		} else {
			return "";
		}
	} 
}
function gra_relevo(){

	$idrel=divide($_POST['idrel']);
	
	/*if($idrel[0] != ""){ 
	
	echo $sql="UPDATE rel_relevo SET 
				'rel_tipo_doc' = TRIM(upper('{$_POST['rel_tipo_doc']}')),
				'rel_documento' = TRIM(upper('{$_POST['rel_documento']}')),
				'rel_validacion1' = TRIM(upper('{$_POST['rel_validacion1']}')),
				'rel_validacion2' = TRIM(upper('{$_POST['rel_validacion2']}')),
				'rel_validacion3' = TRIM(upper('{$_POST['rel_validacion3']}')),
				'rel_validacion4' = TRIM(upper('{$_POST['rel_validacion4']}')),
				'rel_validacion5' = TRIM(upper('{$_POST['rel_validacion5']}')),
				'rel_validacion6' = TRIM(upper('{$_POST['rel_validacion6']}')),
				'rel_validacion7' = TRIM(upper('{$_POST['rel_validacion7']}')),
				'rel_validacion8' = TRIM(upper('{$_POST['rel_validacion8']}')),
				'rel_validacion9' = TRIM(upper('{$_POST['rel_validacion9']}')),
				'rel_validacion10' = TRIM(upper('{$_POST['rel_validacion10']}')),
				'rel_validacion11' = TRIM(upper('{$_POST['rel_validacion11']}')),
				'rel_validacion12' = TRIM(upper('{$_POST['rel_validacion12']}')),
				'rel_validacion13' = TRIM(upper('{$_POST['rel_validacion13']}')),
				'rel_validacion14' = TRIM(upper('{$_POST['rel_validacion14']}')),
				'rel_validacion15' = TRIM(upper('{$_POST['rel_validacion15']}')),
				'rel_validacion16' = TRIM(upper('{$_POST['rel_validacion16']}')),
				'rel_validacion17' = TRIM(upper('{$_POST['rel_validacion17']}')),
				'rel_validacion18' = TRIM(upper('{$_POST['rel_validacion18']}')),
		`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
		`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
		WHERE rel_tipo_doc='$rel_tipo_doc' AND rel_documento='$rel_documento'"; 
	  //echo $x;
	  //echo $sql."    ".$rta;

	} else {*/

		$val5 = $_POST['rel_validacion5'] ?? null;
		$val7 = $_POST['rel_validacion7'] ?? null;
		$val8 = $_POST['rel_validacion8'] ?? null;
		$val10 = $_POST['rel_validacion10'] ?? null;
		$val11 = $_POST['rel_validacion11'] ?? null;

		$sql="INSERT INTO rel_relevo VALUES (
					null,
					trim(upper('{$_POST['rel_tipo_doc']}')),
					trim(upper('{$_POST['rel_documento']}')),
					trim(upper('{$_POST['rel_validacion1']}')),
					trim(upper('{$_POST['rel_validacion2']}')),
					trim(upper('{$_POST['rel_validacion3']}')),
					trim(upper('{$_POST['rel_validacion4']}')),
					trim(upper('{$val5}')),
					trim(upper('{$_POST['rel_validacion6']}')),
					trim(upper('{$val7}')),
					trim(upper('{$val8}')),
					trim(upper('{$_POST['rel_validacion9']}')),
					trim(upper('{$val10}')),
					trim(upper('{$val11}')),
					trim(upper('{$_POST['rel_validacion12']}')),
					trim(upper('{$_POST['rel_validacion13']}')),
					trim(upper('{$_POST['rel_validacion14']}')),
					trim(upper('{$_POST['rel_validacion15']}')),
					trim(upper('{$_POST['rel_validacion16']}')),
					trim(upper('{$_POST['np_cuida']}')),
					trim(upper('{$_POST['cuidado_2']}')),
					trim(upper('{$_POST['antecedentes_2']}')),
					trim(upper('{$_POST['otro_2']}')),
					trim(upper('{$_POST['discapacidad_2']}')),
					trim(upper('{$_POST['cuidado_3']}')),
					trim(upper('{$_POST['antecedentes_3']}')),
					trim(upper('{$_POST['otro_3']}')),
					trim(upper('{$_POST['discapacidad_3']}')),
					trim(upper('{$_POST['rel_validacion17']}')),
					trim(upper('{$_POST['rel_validacion18']}')),
					DATE_SUB(NOW(), INTERVAL 5 HOUR),
					{$_SESSION['us_sds']},
					NULL,
					NULL,
					'A')";
		//echo $sql;
	//}

	$rta=dato_mysql($sql);
	//return "correctamente";
	return $rta; 
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////






//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

function opc_np_cuida($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 189 and estado='A' ORDER BY 1",$id);
}
function opc_rel_tipo_doc($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 1 and estado='A' ORDER BY 1",$id);
}
function opc_rel_validacion3($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 29 and estado='A' ORDER BY 1",$id);
}
function opc_rel_validacion14($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 28 and estado='A' ORDER BY 1",$id);
}
function opc_rel_sesion($id='') {
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 125 and estado='A' ORDER BY 1",$id);
}
function opc_rel_validacion2($id='') {
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 124 and estado='A' ORDER BY 1",$id);
}
function opc_rel_validacion5($id='') {
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 124 and estado='A' ORDER BY 1",$id);
}
function opc_rel_validacion6($id='') {
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 124 and estado='A' ORDER BY 1",$id);
}
function opc_rel_validacion16($id='') {
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 14 and estado='A' ORDER BY 1",$id);
}
function opc_aler($id=''){
	return opc_sql("SELECT `descripcion`,descripcion,valor FROM `catadeta` WHERE idcatalogo=170 and estado='A'  ORDER BY 1 ",$id);
}
function opc_rel_validacion7($id='') {
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 124 and estado='A' ORDER BY 1",$id);
}
function opc_rel_validacion9($id='') {
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 124 and estado='A' ORDER BY 1",$id);
}
function opc_rel_validacion10($id='') {
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 124 and estado='A' ORDER BY 1",$id);
}
function opc_rel_validacion12($id='') {
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 124 and estado='A' ORDER BY 1",$id);
}
function opc_rel_validacion13($id='') {
	// var_dump($_REQUEST);
	$id=divide($_REQUEST['id']);
		return	opc_sql("SELECT idpersona,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) 'Nombres' 
			from personas where vivipersona=(select vivipersona from personas where idpersona='$id[1]' and  tipo_doc='$id[0]') and idpersona<>'$id[1]'",$id);
			// var_dump($id);
	
	// return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 124 and estado='A' ORDER BY 1",$id);
}
function opc_rel_validacion1($id='') {
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo = 28 and estado='A' ORDER BY 1",$id);
}
function opc_rel_sexo($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
}
function opc_rel_genero($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=49 and estado='A' ORDER BY 1",$id);
}
function opc_rel_nacionalidad($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=30 and estado='A' ORDER BY 1",$id);
}
function opc_rel_regimen($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=56 and estado='A' ORDER BY 1",$id);
}
function opc_rel_eapb($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=18 and estado='A' ORDER BY 1",$id);
}
function opc_en_duda($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=130 and estado='A' ORDER BY 1",$id);
}
function opc_rel_etnia($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=16 and estado='A' ORDER BY 1",$id);
}
?>
