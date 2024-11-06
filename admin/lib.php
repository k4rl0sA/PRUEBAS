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

function perfilUsu(){
	$perfi=datos_mysql("SELECT perfil FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}'");
	$perfil = (!$perfi['responseResult']) ? '' : $perfi['responseResult'][0]['perfil'] ;
	return $perfil; 
}


function cmp_gestionusu(){
	$rta="";
	$hoy=date('Y-m-d');
	$t=['gestion'=>'','perfil'=>'','documento'=>'','usuarios'=>'','nombre'=>'','correo'=>'','subred'=>'','bina'=>'','territorio'=>'','perfiln'=>''];
	$d='';
	if ($d==""){$d=$t;}
	$w='adm_usuarios';
	$o='infusu';
	$c[]=new cmp($o,'e',null,'GESTIÓN DE USUARIOS',$w);
	$c[]=new cmp('gestion','s','3',$d['gestion'],$w.' '.$o,'Acción','gestion',null,'',true,true,'','col-2',"enabLoca('gestion','GsT');enClSe('gestion','GsT',[['Rpw'],['Rpw'],['cUS'],['cRL']]);");
	$c[]=new cmp('perfil','s','3',$d['perfil'],$w.' '.$o,'Perfil','perfil',null,'',true,true,'','col-1',"enClSeDe('gestion','perfil','prF',[[],['TEr'],[],['bIN'],[]]);",['usuarios']);
	$c[]=new cmp('documento','t','20',$d['documento'],$w.' GsT cUS '.$o,'N° Documento','documento',null,'',false,false,'','col-15');
	$c[]=new cmp('nombre','t','50',$d['nombre'],$w.' GsT cUS '.$o,'Nombres y Apellidos','nombre',null,'',false,false,'','col-3');
	$c[]=new cmp('correo','t','30',$d['correo'],$w.' GsT cUS '.$o,'Correo','correo',null,'',false,false,'','col-25');
	$c[]=new cmp('bina','s','3',$d['bina'],$w.'  prF bIN '.$o,'bina','bina',null,'',false,false,'','col-2');
	$c[]=new cmp('territorio','s','3',$d['territorio'],$w.' prF TEr '.$o,'territorio','territorio',null,'',false,false,'','col-2');
	$c[]=new cmp('usuarios','s','20',$d['usuarios'],$w.' cRL Rpw  GsT '.$o,'Usuarios','usuarios',null,'',false,false,'','col-4');
	$c[]=new cmp('perfiln','s','3',$d['perfiln'],$w.' GsT cRL '.$o,'Perfil Nuevo','Perfil',null,'',true,false,'','col-15',"enClSe('perfiln','prF',[['bIN'],['TEr']]);");
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta.="<center><button style='background-color:#4d4eef;border-radius:12px;color:white;padding:12px;text-align:center;cursor:pointer;' type='button' Onclick=\"grabar('adm_usuarios','adm_usuarios');\">Guardar</button></center>";
	return $rta;
}


function lis_adm_usuarios(){
	/* $info=datos_mysql("SELECT COUNT(*) total FROM `adm_usuarios` C 
	JOIN usuarios U ON C.usu_creo = U.id_usuario 
	WHERE U.subred IN (select subred from usuarios where id_usuario='{$_SESSION['us_sds']}') AND usu_creo='{$_SESSION['us_sds']}'".whe_adm_usuarios());
	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-adm_usuarios']))? ($_POST['pag-adm_usuarios']-1)* $regxPag:0;
	
	$sql="SELECT id_gestusu ACCIONES, 
	accion,documento,C.nombres,C.correo,C.perfil,C.subred,bina_territorio,C.componente,respuesta,U.nombre,fecha_create,C.estado
	FROM `adm_usuarios` C 
	JOIN usuarios U ON C.usu_creo = U.id_usuario
	WHERE U.subred IN (select subred from usuarios where id_usuario='{$_SESSION['us_sds']}')  AND usu_creo='{$_SESSION['us_sds']}'";
	$sql.=whe_adm_usuarios();
	$sql.=" ORDER BY fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	// echo $sq;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"adm_usuarios",$regxPag); */
}

function whe_adm_usuarios() {
	$sql = "";
	 if ($_POST['fcaso'])
		$sql .= " AND id_gestusu = '".$_POST['fcaso']."'";
	if ($_POST['fdes']) {
		if ($_POST['fhas']) {
			$sql .= " AND fecha_create >='".$_POST['fdes']." 00:00:00' AND fecha_create <='".$_POST['fhas']." 23:59:59'";
		} else {
			$sql .= " AND fecha_create >='".$_POST['fdes']." 00:00:00' AND fecha_create <='". $_POST['fdes']." 23:59:59'";
		}
	}
	return $sql;
}

function cmp_planos(){
	$rta="";
	//$until_day_open=17;//dia del mes fecha abierta
	//$ini = (date('d')>$until_day_open) ? -date('d'):-date('d')-30 ;//fechas abiertas hasta un determinado dia
	$ini=date('d')<11 ?-date('d')-31:-date('d');//normal
	$t=['proceso'=>'','rol'=>'','documento'=>'','usuarios'=>'','descarga'=>'','fechad'=>'','fechah'=>''];
	$d='';
	if ($d==""){$d=$t;}
	$w='gestion';
	$o='infusu';
	$c[]=new cmp($o,'e',null,'DESCARGA DE PLANOS',$w);
	$c[]=new cmp('proceso','s',3,$d['proceso'],$w.' DwL '.$o,'Proceso','proceso',null,'',true,true,'','col-35');
	$c[]=new cmp('fechad','d',10,$d['fechad'],$w.' DwL '.$o,'Desde','proceso',null,'',true,true,'','col-2',"validDate(this,$ini,0)");
	$c[]=new cmp('fechah','d',10,$d['fechah'],$w.' DwL '.$o,'Hasta','proceso',null,'',true,true,'','col-2',"validDate(this,$ini,0)");
	// $c[]=new cmp('descarga','t',100,$d['descarga'],$w.' '.$o,'Ultima Descarga','rol',null,'',false,false,'','col-5');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta.="<center><button style='background-color:#4d4eef;border-radius:12px;color:white;padding:12px;text-align:center;cursor:pointer;' type='button' Onclick=\"DownloadCsv('lis','planos','fapp');grabar('gestion',this);\">Descargar</button></center>";//DownloadCsv('lis','plano','DwL
	return $rta;
}

function gra_gestion(){
	/* $name=get_tabla($_POST['proceso']);
	if($name!=='[]'){
		return "Error: msj['Ya se realizo la descarga por el usuario $name']";
		exit;
	}else{ */
	$sql="INSERT INTO planos 
	VALUES(NULL,(SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."'),'1',trim(upper('{$_POST['proceso']}')),
	'{$_POST['fechad']}','{$_POST['fechah']}',TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR))";
	/* 
	1=descargar
	2=actualizar
	3=restaurar
	4=crear
	5=rol
	6=adscripcion 
	*/
		// echo $sq;
		$rta=dato_mysql($sql);
  return $rta;
	// }
}

function gra_adm_usuarios(){
$gestion = cleanTxt($_POST['gestion']);
$documento = cleanTxt($_POST['documento']);
$nombre = cleanTxt($_POST['nombre']);
$correo = cleanTxt($_POST['correo']);
$perfil = cleanTxt($_POST['perfil']);
$perfiln = cleanTxt($_POST['perfiln']);
$bina_territorio = cleanTxt($_POST['bina_territorio']);
$componente = cleanTxt($_POST['componente']);

	$sql="INSERT INTO adm_usuarios 
	VALUES(NULL,$gestion,$documento,$nombre,$correo,$perfil,(SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."'),
	$bina_territorio,$componente,$perfiln,{$_SESSION['us_sds']},DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
	$rta=dato_mysql($sql);
	return $rta;
}

function get_tabla($a){
	//`atencion_fechaatencion`, `atencion_codigocups`, `atencion_finalidadconsulta`, `atencion_peso`, `atencion_talla`, `atencion_sistolica`, `atencion_diastolica`, `atencion_abdominal`, `atencion_brazo`, `atencion_diagnosticoprincipal`, `atencion_diagnosticorelacion1`, `atencion_diagnosticorelacion2`, `atencion_diagnosticorelacion3`, `atencion_fertil`, `atencion_preconcepcional`, `atencion_metodo`, `atencion_anticonceptivo`, `atencion_planificacion`, `atencion_mestruacion`, `atencion_gestante`, `atencion_gestaciones`, `atencion_partos`, `atencion_abortos`, `atencion_cesarias`, `atencion_vivos`, `atencion_muertos`, `atencion_vacunaciongestante`, `atencion_edadgestacion`, `atencion_ultimagestacion`, `atencion_probableparto`, `atencion_prenatal`, `atencion_fechaparto`, `atencion_rpsicosocial`, `atencion_robstetrico`, `atencion_rtromboembo`, `atencion_rdepresion`, `atencion_sifilisgestacional`, `atencion_sifiliscongenita`, `atencion_morbilidad`, `atencion_hepatitisb`, `atencion_vih`, `atencion_cronico`, `atencion_asistenciacronica`, `atencion_tratamiento`, `atencion_vacunascronico`, `atencion_menos5anios`, `atencion_esquemavacuna`, `atencion_signoalarma`, `atencion_cualalarma`, `atencion_dxnutricional`, `atencion_eventointeres`, `atencion_evento`, `atencion_cualevento`, `atencion_sirc`, `atencion_rutasirc`, `atencion_remision`, `atencion_cualremision`, `atencion_ordenpsicologia`, `atencion_ordenvacunacion`, `atencion_vacunacion`, `atencion_ordenlaboratorio`, `atencion_laboratorios`, `atencion_ordenimagenes`, `atencion_imagenes`, `atencion_ordenmedicamentos`, `atencion_medicamentos`, `atencion_rutacontinuidad`, `atencion_continuidad`, `atencion_relevo`  ON a.atencion_idpersona = b.idpersona AND a.atencion_tipodoc = b.tipo_doc
	$hoy=date('Y-m-d');
	$sql="SELECT u.nombre nombre FROM planos m left join usuarios u ON m.usu_creo=u.id_usuario
 	where	accion=1 
	AND m.subred=(SELECT subred FROM usuarios where id_usuario='{$_SESSION['us_sds']}') 
	AND tabla='$a' AND DATE(fecha_create)='$hoy'";
	// echo $sq;
	$info=datos_mysql($sql);
	// echo $info;
	if (isset($info['responseResult'][0])) {
		return $info['responseResult'][0]['nombre'];
	}else{
		return '[]';
	}
}

function lis_planos() {
	$clave = random_bytes(32);
    switch ($_REQUEST['proceso']) {
        case '1':
			$tab = "Asignacion Predios";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_asigpre($tab);
		break;
        case '2':
			$tab = "Gestion Predios";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_gestpre($tab);
           break;
        case '3':
			$tab = "Caracterizaciones";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_caract($tab);
			break;
		case '4':
			$tab = "Plan_de_Cuidado_Familiar";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_plancui($tab);
            break;	
        case '5':
			$tab = "Compromisos_Plan_de_Cuidado_Familiar";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_plancomp($tab);
            break;
        case '6':
			$tab = "Toma_de_Medidas_y_Signos";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_signos($tab);
            break;	
        case '7':
			$tab = "Toma_de_Alertas";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_alertas($tab);
            break;	
        case '8':
			$tab = "Riesgos_Ambientales";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_riesamb($tab);
            break;	
        case '9':
			$tab = "Eventos_VSP_Generados";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_eventos($tab);
            break;	
        case '10':
			$tab = "VSP_Acompañamiento_Psicosocial";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_acompsic($tab);
            break;
        case '11':
			$tab = "VSP_Apoyo_Psicosocial_En_Duelo";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_apopsicduel($tab);
            break;
        case '12':
			$tab = "VSP_BPN_Pretermino";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_bpnpret($tab);
            break;
        case '13':
			$tab = "VSP_BPN_a_Termino";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_bpnterm($tab);
            break;
        case '14':
			$tab = "VSP_Cancer_Infantil";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_cancinfa($tab);
            break;
        case '15':
			$tab = "VSP_Conducta_Suicida";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_condsuic($tab);
            break;
        case '16':
			$tab = "VSP_Cronicos";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_cronicos($tab);
            break;
        case '17':
			$tab = "VSP_DNT_Severa_y_Moderada";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_dntsevymod($tab);
            break;
        case '18':
			$tab = "VSP_Era_Ira";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_eraira($tab);
            break;
        case '19':
			$tab = "VSP_Gestantes";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_Gestantes($tab);
            break;
        case '20':
			$tab = "VSP_HB_Gestacional";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_hbgest($tab);
            break;
        case '21':
			$tab = "VSP_Morbilidad_Materna_Extrema";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_mme($tab);
            break;
        case '22':
			$tab = "VSP_Hipotiroidismo";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_mnehosp($tab);
            break;
        case '23':
			$tab = "VSP_Otros_Casos_Priorizados";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_otroprio($tab);
            break;
        case '24':
			$tab = "VSP_Salud_Oral";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_saludoral($tab);
            break;
        case '25':
			$tab = "VSP_Sifilis_Congenita";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_sificong($tab);
            break;
        case '26':
			$tab = "VSP_Sifilis_Gestacional";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_sifigest($tab);
            break;
        case '27':
			$tab = "VSP_VIH_Gestacional";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_vihgest($tab);
            break;
        case '28':
			$tab = "VSP_Violencia_En_Gestantes";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_violges($tab);
            break;
        case '29':
			$tab = "VSP_Violencia_Reiterada";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_violreite($tab);
            break;
        case '30':
			$tab = "Cargue_Eventos_Vsp";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_vspgeo($tab);
            break;
        case '40':
			$tab = "Adscripcion_Territorial";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_adscrip($tab);
            break;
        case '41':
			$tab = "Casos_Relevo_Cuidador";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_asigrelevo($tab);
            break;
         case '42':
			$tab = "Tamizaje_Cope";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_cope($tab);
            break;    
        case '43':
			$tab = "Plano_Ajustes";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_ajustes($tab);
            break;
        case '44':
			$tab = "Plano_Actualizacion_Caracterizacion";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_actucarac($tab);
            break;    
        case '45':
			$tab = "Casos_Psicologia";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_asigpsico($tab);
            break;
        case '46':
			$tab = "Casos_Derivados_Eac";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_derivaeac($tab);
            break;
        case '47':
			$tab = "Tamizaje_Epoc";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_epoc($tab);
            break;
        default:
            break;    
    }
}




function lis_asigpre($txt){
	$sql="SELECT G.subred AS Subred, G.idgeo AS Cod_Predio, CONCAT('_', G.sector_catastral, G.nummanzana, G.predio_num, G.unidad_habit) AS Cod_Sector_Catastral, 
U.id_usuario AS Cod_Asignado, U.nombre AS Nombre_Asignado, U.perfil AS Perfil_Asignado, A.fecha_create AS Fecha_Asignacion, 
U1.id_usuario AS Cod_Quien_Asigno, U1.nombre AS Nombre_Quien_Asigno, U1.perfil AS Perfil_Quien_Asigno  

FROM `geo_asig` A
LEFT JOIN hog_geo G ON A.idgeo=G.idgeo
LEFT JOIN usuarios U ON A.doc_asignado=U.id_usuario
LEFT JOIN usuarios U1 ON A.usu_create=U1.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	
	$tot="SELECT count(*) as total FROM `geo_asig` A  LEFT JOIN hog_geo G ON A.idgeo=G.idgeo LEFT JOIN usuarios U ON A.doc_asignado=U.id_usuario LEFT JOIN usuarios U1 ON A.usu_create=U1.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred();
	$tot.=whe_date();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_gestpre($txt){
	$sql="SELECT G.subred AS Subred, G.idgeo AS Cod_Predio, A.id_ges AS Cod_Registro, A.direccion_nueva AS Direccion_Nueva, A.vereda_nueva AS Vereda_Nueva, A.cordxn AS Coordenada_X_Nueva,A.cordyn AS Coordenada_Y_Nueva, FN_CATALOGODESC(44,A.estado_v) AS Estado_Visita, FN_CATALOGODESC(5,A.motivo_estado) AS Motivo_Estado, U.id_usuario AS Cod_Usuario, U.nombre AS Nombre_Usuario, U.perfil AS Perfil_Usuario, A.fecha_create AS Fecha_Creacion 
FROM `geo_gest` A
LEFT JOIN hog_geo G ON A.idgeo=G.idgeo
LEFT JOIN usuarios U ON A.usu_creo=U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	
	$tot="SELECT count(*) as total FROM `geo_gest` A  LEFT JOIN hog_geo G ON A.idgeo=G.idgeo LEFT JOIN usuarios U ON A.usu_creo=U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred();
	$tot.=whe_date();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_caract($txt){
	$sql="SELECT  
G.idgeo Cod_Predio,F.id_fam AS Cod_Familia,V.id_viv AS Cod_Registro,G.subred AS Subred,FN_CATALOGODESC(3,G.zona) AS Zona,G.localidad AS Localidad, FN_CATALOGODESC(7,G.upz) AS Upz, G.barrio AS Barrio, G.direccion AS Direccion, G.cordx AS Cordenada_X, G.cordy AS Cordenada_Y, G.estrato AS Estrato, 

F.numfam AS Familia_N°,concat(F.complemento1,' ',F.nuc1,' ',F.complemento2,' ',F.nuc2,' ',F.complemento3,' ',F.nuc3) AS Complementos,F.telefono1 AS Telefono_1,F.telefono2 AS Telefono_2,F.telefono3 AS Telefono_3,

V.fecha AS Fecha_Caracterizacion,FN_CATALOGODESC(215,V.motivoupd) AS Motivo_Caracterizacion, FN_CATALOGODESC(87,V.eventoupd) AS Evento_Notificado, V.fechanot AS Fecha_Notificacion ,V.equipo AS Equipo_Caracterizacion,

FN_CATALOGODESC(166,V.crit_epi) AS CRITERIO_EPIDE,FN_CATALOGODESC(167,V.crit_geo) AS CRITERIO_GEO,FN_CATALOGODESC(168,V.estr_inters) AS ESTRATEGIAS_INTERSEC,FN_CATALOGODESC(169,V.fam_peretn) AS FAM_PERTEN_ETNICA,FN_CATALOGODESC(170,V.fam_rurcer) AS FAMILIAS_RURALIDAD_CER,

FN_CATALOGODESC(4,V.tipo_vivienda) AS TIPO_VIVIENDA,FN_CATALOGODESC(8,V.tenencia) AS TENENCIA_VIVIENDA,V.dormitorios AS DORMITORIOS,V.actividad_economica AS USO_ACTIVIDAD_ECONO, FN_CATALOGODESC(10,V.tipo_familia) AS TIPO_FAMILIA, V.personas AS N°_PERSONAS, FN_CATALOGODESC(13,V.ingreso) AS INGRESO_ECONOMICO_FAM,

V.seg_pre1 AS SEGURIDAD_ALIMEN_PREG1,V.seg_pre2 AS SEGURIDAD_ALIMEN_PREG2,V.seg_pre3 AS SEGURIDAD_ALIMEN_PREG3,V.seg_pre4 AS SEGURIDAD_ALIMEN_PREG4,V.seg_pre5 AS SEGURIDAD_ALIMEN_PREG5,V.seg_pre6 AS SEGURIDAD_ALIMEN_PREG6,V.seg_pre7 AS SEGURIDAD_ALIMEN_PREG7,V.seg_pre8 AS SEGURIDAD_ALIMEN_PREG8,

V.subsidio_1 AS SUBSIDIO_SDIS1,V.subsidio_2 AS SUBSIDIO_SDIS2,V.subsidio_3 AS SUBSIDIO_SDIS3,V.subsidio_4 AS SUBSIDIO_SDIS4,V.subsidio_5 AS SUBSIDIO_SDIS5,V.subsidio_6 AS SUBSIDIO_SDIS6,V.subsidio_7 AS SUBSIDIO_SDIS7,V.subsidio_8 AS SUBSIDIO_SDIS8,V.subsidio_9 AS SUBSIDIO_SDIS9,V.subsidio_10 AS SUBSIDIO_SDIS10,V.subsidio_11 AS SUBSIDIO_SDIS11,V.subsidio_12 AS SUBSIDIO_SDIS12,V.subsidio_13 AS SUBSIDIO_ICBF1,V.subsidio_14 AS SUBSIDIO_ICBF2,V.subsidio_15 AS SUBSIDIO15_SECRE_HABIT,V.subsidio_16 AS SUBSIDIO_CONSEJERIA,V.subsidio_17 AS SUBSIDIO_ONGS, V.subsidio_18 AS SUBSIDIO_FAMILIAS_ACCION,V.subsidio_19 AS SUBSIDIO_RED_UNIDOS,V.subsidio_20 AS SUBSIDIO_SECADE,

V.energia AS SERVICIO_ENERGIA,V.gas AS SERVICIO_GAS_NATURAL,V.acueducto AS SERVICIO_ACUEDUCTO,V.alcantarillado AS SERVICIO_ALCANTAR,V.basuras AS SERVICIO_BASURAS,V.pozo AS POZO,V.aljibe AS ALJIBE,
V.perros AS ANIMALES_PERROS,V.numero_perros AS N°_PERROS,V.perro_vacunas AS N°_PERROS_NOVACU,V.perro_esterilizado AS N°_PERROS_NOESTER,V.gatos AS ANIMALES_GATOS,V.numero_gatos AS N°_GATOS,V.gato_vacunas AS N°_GATOS_NOVACU,V.gato_esterilizado AS N°_GATOS_NOESTER,V.otros AS OTROS_ANIMALES,

V.facamb1 AS FACTORES_AMBIEN_PRE1,V.facamb2 AS FACTORES_AMBIEN_PRE2,V.facamb3 AS FACTORES_AMBIEN_PRE3,V.facamb4 AS FACTORES_AMBIEN_PRE4,V.facamb5 AS FACTORES_AMBIEN_PRE5,V.facamb6 AS FACTORES_AMBIEN_PRE6,V.facamb7 AS FACTORES_AMBIEN_PRE7,V.facamb8 AS FACTORES_AMBIEN_PRE8,V.facamb9 AS FACTORES_AMBIEN_PRE9,V.observacion AS OBSERVACIONES,

U.id_usuario AS Cod_Usuario, U.nombre AS Nombre_Usuario, U.perfil AS Perfil_Usuario, V.fecha_create AS Fecha_Creacion
FROM `hog_carac` V
LEFT JOIN hog_fam F ON V.idfam = F.id_fam
LEFT JOIN hog_geo G ON F.idpre = G.idgeo
LEFT JOIN usuarios U ON V.usu_create=U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred1();
	$sql.=whe_date1();
	
	$tot="SELECT count(*) as total FROM `hog_carac` V LEFT JOIN hog_fam F ON V.idfam = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON V.usu_create=U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred1();
	$tot.=whe_date1();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_plancui($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,C.idviv AS Cod_Familia,C.id AS Cod_Registro,G.subred AS Subred,C.fecha AS Fecha_Caracterizacion,
FN_CATALOGODESC(22,C.accion1) AS Accion_1,FN_CATALOGODESC(75,C.desc_accion1) AS Descipcion_Accion1,
FN_CATALOGODESC(22,C.accion2) AS Accion_2,FN_CATALOGODESC(75,C.desc_accion2) AS Descipcion_Accion2,
FN_CATALOGODESC(22,C.accion3) AS Accion_3,FN_CATALOGODESC(75,C.desc_accion3) AS Descipcion_Accion3,
FN_CATALOGODESC(22,C.accion4) AS Accion_4,FN_CATALOGODESC(75,C.desc_accion4) AS Descipcion_Accion4,
C.observacion AS Obervaciones, C.usu_creo AS Usuario_Creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, U.equipo AS Equipo_Creo, C.fecha_create AS Fecha_Creacion

FROM `hog_plancuid` C
LEFT JOIN hog_fam F ON C.idviv = F.id_fam
LEFT JOIN hog_geo G ON F.idpre = G.idgeo
LEFT JOIN usuarios U ON C.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred2();
	$sql.=whe_date2();
	
	$tot="SELECT count(*) as total FROM `hog_plancuid` C  LEFT JOIN hog_fam F ON C.idviv = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON C.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred2();
	$tot.=whe_date2();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_plancomp($txt){
	$sql="SELECT
G.idgeo Cod_Predio,C.idviv AS Cod_Familia,C.idcon AS Cod_Registro,G.subred AS Subred,C.compromiso AS Compromiso_Concertado, FN_CATALOGODESC(26,C.equipo) AS Equipo, C.cumple AS Cumple_Compromiso,
C.usu_creo AS Usuario_Creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, U.equipo AS Equipo_Creo, C.fecha_create AS Fecha_Creacion
FROM `hog_planconc` C
LEFT JOIN hog_plancuid P ON P.idviv = C.idviv
LEFT JOIN hog_fam F ON C.idviv = F.id_fam
LEFT JOIN hog_geo G ON F.idpre = G.idgeo
LEFT JOIN usuarios U ON C.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred3();
	$sql.=whe_date3();
	
	$tot="SELECT count(*) as total FROM `hog_planconc` C  LEFT JOIN hog_plancuid P ON P.idviv = C.idviv LEFT JOIN hog_fam F ON C.idviv = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON C.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred3();
	$tot.=whe_date3();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_signos($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,F.id_fam AS Cod_Familia,S.id_signos AS Cod_Registro,G.subred AS Subred,
P.tipo_doc AS Tipo_Documento, P.idpersona AS N°_Documento, P.nombre1 AS Primer_Nombre, P.nombre2 AS Segundo_Nombre, P.apellido1 AS Primer_Apellido, P.apellido2 AS Seundo_Apellido, P.fecha_nacimiento AS Fecha_Nacimiento, FN_CATALOGODESC(21,P.sexo) AS Sexo,
S.fecha_toma AS Fecha_Toma, S.peso AS PESO, S.talla AS TALLA, S.imc AS IMC, S.tas AS Tension_Sistolica, S.tad AS Tension_Diastolica, S.frecard AS Frecuencia_Cardiaca, S.satoxi AS Saturacion_Oxigeno, S.peri_abdomi AS Perimetro_Abdominal, S.peri_braq AS Perimetro_Braquial, S.zscore AS ZSCORE, S.glucom AS Glucometria,

S.usu_create AS Usuario_Creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, U.equipo AS Equipo_Creo, S.fecha_create AS Fecha_Creacion
FROM `hog_signos` S
LEFT JOIN person P ON S.idpeople = P.idpeople
LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam
LEFT JOIN hog_geo G ON F.idpre = G.idgeo
LEFT JOIN usuarios U ON S.usu_create = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred4();
	$sql.=whe_date4();
	
	$tot="SELECT count(*) as total FROM `hog_signos` S LEFT JOIN person P ON S.idpeople = P.idpeople LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON S.usu_create = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred4();
	$tot.=whe_date4();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_alertas($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,F.id_fam AS Cod_Familia,A.id_alert AS Cod_Registro,G.subred AS Subred,
P.tipo_doc AS Tipo_Documento, P.idpersona AS N°_Documento, P.nombre1 AS Primer_Nombre, P.nombre2 AS Segundo_Nombre, P.apellido1 AS Primer_Apellido, P.apellido2 AS Seundo_Apellido, P.fecha_nacimiento AS Fecha_Nacimiento, FN_CATALOGODESC(21,P.sexo) AS Sexo,
FN_CATALOGODESC(176,A.cursovida) AS Curso_de_Vida, A.fecha AS Fecha, FN_CATALOGODESC(34,A.tipo) AS Tipo_Intervencion,FN_CATALOGODESC(166,A.crit_epi) AS Criterio_Epidemiologico, 

FN_CATALOGODESC(170,A.men_dnt) AS Menor_Con_DNT, FN_CATALOGODESC(170,A.men_sinctrl) AS Menor_Sin_Control, FN_CATALOGODESC(170,A.gestante) AS Usuaria_Gestante, FN_CATALOGODESC(170,A.etapgest) AS Etapa_Gestacional, FN_CATALOGODESC(170,A.ges_sinctrl) AS Gestante_Sin_Control, FN_CATALOGODESC(170,A.cronico) AS Usuario_Cronico, FN_CATALOGODESC(170,A.cro_hiper) AS Dx_Hipertencion, FN_CATALOGODESC(170,A.cro_diabe) AS Dx_Diabetes, FN_CATALOGODESC(170,A.cro_epoc) AS Dx_Epoc, FN_CATALOGODESC(170,A.cro_sinctrl) AS Cronico_Sin_Control, FN_CATALOGODESC(170,A.esq_vacun) AS Esquema_de_vacunacion_Completo, 

A.alert1 AS Alerta_N°_1, A.selmul1 AS  Descripcion_Alerta_N°_1, A.alert2 AS Alerta_N°_2, A.selmul2 AS  Descripcion_Alerta_N°_2, A.alert3 AS Alerta_N°_3,A.selmul3 AS  Descripcion_Alerta_N°_3, A.alert4 AS Alerta_N°_4,A.selmul4 AS  Descripcion_Alerta_N°_4, A.alert5 AS Alerta_N°_5,A.selmul5 AS  Descripcion_Alerta_N°_5,A.alert6 AS Alerta_N°_6,A.selmul6 AS  Descripcion_Alerta_N°_6,

FN_CATALOGODESC(170,A.agen_intra) AS Agendamiento_Promotor, A.servicio AS Serivicio_Agendado, A.fecha_cita AS Fecha_de_la_Cita, A.hora_cita AS Hora_de_la_Cita, A.lugar_cita AS Lugar_de_la_Cita, FN_CATALOGODESC(170,A.deriva_pf) AS Derivacion_a_PCF,FN_CATALOGODESC(87,A.evento_pf) AS Evento_PCF,

A.usu_creo AS Usuario_Creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, U.equipo AS Equipo_Creo, A.fecha_create AS Fecha_Creacion

FROM `hog_alert` A
LEFT JOIN person P ON A.idpeople = P.idpeople
LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam
LEFT JOIN hog_geo G ON F.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred5();
	$sql.=whe_date5();
	
	$tot="SELECT count(*) as total FROM `hog_alert` A LEFT JOIN person P ON A.idpeople = P.idpeople LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred5();
	$tot.=whe_date5();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_riesamb($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,F.id_fam AS Cod_Familia,A.idamb AS Cod_Registro,G.subred AS Subred,
A.fecha AS Fecha_Seguimiento,FN_CATALOGODESC(34,A.tipo_activi) AS Tipo_Seguimiento,
A.seguro AS seguro,A.grietas AS grietas,A.combustible AS combustible,A.separadas AS separadas,A.lena AS lena,A.ilumina AS ilumina,A.fuma AS fuma,A.bano AS bano,A.cocina AS cocina,
A.elevado AS elevado,A.electrica AS electrica,A.elementos AS elementos,A.barreras AS barreras,A.zontrabajo AS zontrabajo,A.agua AS agua,A.tanques AS tanques,A.adecagua AS adecagua,
A.raciagua AS raciagua,A.sanitari AS sanitari,A.aguaresid AS aguaresid,A.terraza AS terraza,A.recipientes AS recipientes,A.vivaseada AS vivaseada,A.separesiduos AS separesiduos,A.reutresiduos AS reutresiduos,
A.noresiduos AS noresiduos,A.adecresiduos AS adecresiduos,A.horaresiduos AS horaresiduos,A.plagas AS plagas,A.contplagas AS contplagas,A.pracsanitar AS pracsanitar,A.envaplaguicid AS envaplaguicid,A.consealiment AS consealiment,A.limpcocina AS limpcocina,A.cuidcuerpo AS cuidcuerpo,A.fechvencim AS fechvencim,A.limputensilios AS limputensilios,A.adqualime AS adqualime,A.almaquimicos AS almaquimicos,A.etiqprodu AS etiqprodu,A.juguetes AS juguetes,A.medicamalma AS medicamalma,A.medicvenc AS medicvenc,A.adqumedicam AS adqumedicam,A.medidaspp AS medidaspp,A.radiacion AS radiacion,A.contamaire AS contamaire,A.monoxido AS monoxido,A.residelectri AS residelectri,A.duermeelectri AS duermeelectri,A.vacunasmascot AS vacunasmascot,A.aseamascot AS aseamascot,A.alojmascot AS alojmascot,A.excrmascot AS excrmascot,A.permmascot AS permmascot,A.salumascot AS salumascot,A.pilas AS pilas,A.dispmedicamentos AS dispmedicamentos,A.dispcompu AS dispcompu,A.dispplamo AS dispplamo,A.dispbombill AS dispbombill,A.displlanta AS displlanta,
A.dispplaguic AS dispplaguic,A.dispaceite AS dispaceite,
A.usu_creo AS Usuario_Creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, U.equipo AS Equipo_Creo, A.fecha_create AS Fecha_Creacion

 FROM `hog_amb` A
LEFT JOIN hog_fam F ON A.idvivamb = F.id_fam
LEFT JOIN hog_geo G ON F.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario
 WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred6();
	$sql.=whe_date6();
	
	$tot="SELECT count(*) as total FROM `hog_amb` A LEFT JOIN hog_fam F ON A.idvivamb = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred6();
	$tot.=whe_date6();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_eventos($txt){
	$sql="SELECT
G.idgeo Cod_Predio,F.id_fam AS Cod_Familia,A.id_eve AS Cod_Registro,G.subred AS Subred,
P.tipo_doc AS Tipo_Documento, P.idpersona AS N°_Documento, P.nombre1 AS Primer_Nombre, P.nombre2 AS Segundo_Nombre, P.apellido1 AS Primer_Apellido, P.apellido2 AS Seundo_Apellido, P.fecha_nacimiento AS Fecha_Nacimiento, FN_CATALOGODESC(21,P.sexo) AS Sexo, A.docum_base AS Documento_de_Base,FN_CATALOGODESC(87,A.evento) AS Evento_PCF,A.fecha_even AS Fecha_Generacion_Evento,
A.usu_creo AS Usuario_Creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, U.equipo AS Equipo_Creo, A.fecha_create AS Fecha_Creacion
FROM `vspeve` A
LEFT JOIN person P ON A.idpeople = P.idpeople
LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam
LEFT JOIN hog_geo G ON F.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred7();
	$sql.=whe_date7();
	
	$tot="SELECT count(*) as total FROM `vspeve` A LEFT JOIN person P ON A.idpeople = P.idpeople LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred7();
	$tot.=whe_date7();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_acompsic($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,F.id_fam AS Cod_Familia,A.id_acompsic AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,

FN_CATALOGODESC(170,A.autocono) AS Preg_1,FN_CATALOGODESC(170,A.cumuni_aser) AS Preg_2,FN_CATALOGODESC(170,A.toma_decis) AS Preg_3,FN_CATALOGODESC(170,A.pensa_crea) AS Preg_4,FN_CATALOGODESC(170,A.manejo_emo) AS Preg_5,FN_CATALOGODESC(170,A.rela_interp) AS Preg_6,FN_CATALOGODESC(170,A.solu_prob) AS Preg_7,FN_CATALOGODESC(170,A.pensa_critico) AS Preg_8,FN_CATALOGODESC(170,A.manejo_tension) AS Preg_9,FN_CATALOGODESC(170,A.empatia) AS Preg_10,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,

FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,


FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,
A.liker_dificul AS Liker_Dificultad,A.liker_emocion AS Liker_Emocion,A.liker_decision AS Liker_Decision,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.users_bina AS Usuarios_Equipo,

A.usu_creo AS Usuario_Creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, U.equipo AS Equipo_Creo, A.fecha_create AS Fecha_Creacion

FROM `vsp_acompsic` A
 
LEFT JOIN person P ON A.idpeople = P.idpeople
LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam
LEFT JOIN hog_geo G ON F.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred8();
	$sql.=whe_date8();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_acompsic` A LEFT JOIN person P ON A.idpeople = P.idpeople LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred8();
	$tot.=whe_date8();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_apopsicduel($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_psicduel AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,

FN_CATALOGODESC(80,A.causa_duelo) AS Causa_Duelo,A.fecha_defun AS Fecha_Defuncion,FN_CATALOGODESC(81,A.parent_fallec) AS Parentesco_Fallecido,FN_CATALOGODESC(82,A.lugar_defun) AS Lugar_defuncion,FN_CATALOGODESC(83,A.vincu_afect) AS Vinculo_Afectivo,FN_CATALOGODESC(84,A.senti_ident_1) AS Sentimientos_Emosiones_1,FN_CATALOGODESC(84,A.senti_ident_2) AS Sentimientos_Emosiones_2,FN_CATALOGODESC(84,A.senti_ident_3) AS Sentimientos_Emosiones_3,FN_CATALOGODESC(85,A.etapa_duelo) AS Etapa_Duelo,FN_CATALOGODESC(86,A.sintoma_duelo_1) AS Sintomas_Malestar_Duelo1,FN_CATALOGODESC(86,A.sintoma_duelo_2) AS Sintomas_Malestar_Duelo2,FN_CATALOGODESC(86,A.sintoma_duelo_3) AS Sintomas_Malestar_Duelo3,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre, FN_CATALOGODESC(78,A.liker_dificul) AS Liker_Dificultad,FN_CATALOGODESC(78,A.liker_emocion) AS Liker_Emocion,FN_CATALOGODESC(78,A.liker_decision) AS Liker_Decision,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina

FROM `vsp_apopsicduel` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_apopsicduel` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_bpnpret($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_bpnpret AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento, FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento, FN_CATALOGODESC(87,A.evento) AS Evento, FN_CATALOGODESC(73,A.estado_s) AS Estado, FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,
FN_CATALOGODESC(95,A.sem_ges) AS Semanas_Gestacion, FN_CATALOGODESC(170,A.asiste_control) AS Asiste_control_CYD, FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo, FN_CATALOGODESC(170,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva, A.peso AS 'Peso_(Kg)', A.talla AS 'Talla (cm)', FN_CATALOGODESC(96,A.edad_ges) AS Edad_Gestacional, FN_CATALOGODESC(97,A.diag_nutri) AS Dx_Nutricional_Fenton, A.zscore AS Zscore, FN_CATALOGODESC(98,A.clasi_nutri) AS Clasificacion_Nutricional, FN_CATALOGODESC(170,A.gana_peso) AS Evidencia_Ganancia_Peso, FN_CATALOGODESC(99,A.gana_peso_dia) AS Ganancia_Peso_Diaria, FN_CATALOGODESC(170,A.signos_alarma) AS Signos_Alarma, FN_CATALOGODESC(170,A.signos_alarma_seg) AS Signos_Alarma_Seguimiento,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.desc_accion2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_3,FN_CATALOGODESC(75,A.acciones_3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina
FROM `vsp_bpnpret` A

LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_bpnpret` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_bpnterm($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_bpnterm AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,


P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,

FN_CATALOGODESC(170,A.asiste_control) AS Asiste_control_CYD,FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva,A.peso AS 'Peso_(Kg)',A.talla AS 'Talla (cm)',A.zscore AS Zscore,FN_CATALOGODESC(98,A.clasi_nutri) AS Clasificacion_Nutricional,FN_CATALOGODESC(170,A.gana_peso) AS Evidencia_Ganancia_Peso,FN_CATALOGODESC(99,A.gana_peso_dia) AS Ganancia_Peso_Diaria,FN_CATALOGODESC(170,A.signos_alarma) AS Signos_Alarma,FN_CATALOGODESC(170,A.signos_alarma_seg) AS Signos_Alarma_Seguimiento,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina
FROM `vsp_bpnterm` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_bpnterm` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_cancinfa($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_cancinfa AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,FN_CATALOGODESC(170,A.diagnosticado) AS Dx_Confirmado,A.fecha_dx AS Fecha_Dx_Confirmado,FN_CATALOGODESC(170,A.tratamiento) AS Cuenta_Tratamiento,FN_CATALOGODESC(170,A.asiste_control) AS Asiste_control_Especialista,A.cual_espe AS Cual_Especilista,FN_CATALOGODESC(93,A.trata_orde) AS Tratamiento_Ordenado,A.fecha_cirug AS Fecha_Cirugia,A.fecha_quimio AS Fecha_Quimioterapia,A.fecha_radiote AS Fecha_Radioterapia,A.fecha_otro AS Fecha_Otro,A.otro_cual AS Otro_Cual,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,
A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.supera_problema) AS Supera_Problemas_Practicos,
FN_CATALOGODESC(170,A.supera_emocional) AS Supera_Estado_Emocional,FN_CATALOGODESC(170,A.supera_dolor) AS Supera_Valoracion_Dolor,FN_CATALOGODESC(170,A.supera_funcional) AS Supera_Valoracion_Funcional,FN_CATALOGODESC(170,A.supera_educacion) AS Supera_Necesidades_Educacion,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina

FROM `vsp_cancinfa` A

LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_cancinfa` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_condsuic($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_condsuic AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,


P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,FN_CATALOGODESC(197,A.tipo_caso) AS Tipo_Poblacion,FN_CATALOGODESC(136,A.etapa) AS Etapa,FN_CATALOGODESC(137,A.sema_gest) AS Semanas_Gestacion_Posevento,

FN_CATALOGODESC(170,A.asis_ctrpre) AS Asiste_control_Prenatal,FN_CATALOGODESC(170,A.exam_lab) AS Examenes_Laboratorio,FN_CATALOGODESC(170,A.esqu_vacuna) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.cons_micronutr) AS Consume_Micronutrientes,

A.fecha_obstetrica AS Fecha_Evento_Obstetrico,FN_CATALOGODESC(137,A.edad_gesta) AS Edad_Gestacional_Evento,FN_CATALOGODESC(193,A.resul_gest) AS Resultado_Gestacion,FN_CATALOGODESC(170,A.meto_fecunda) AS Cuenta_Metodo_Fecundidad,FN_CATALOGODESC(130,A.cual) AS Cual_Metodo,A.peso_nacer AS Peso_RN_Nacer,FN_CATALOGODESC(170,A.asiste_control) AS Asiste_control_CYD,FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva,

FN_CATALOGODESC(170,A.persis_morir) AS Persiste_Idea_Morir,FN_CATALOGODESC(170,A.proce_eapb) AS Proceso_Psicoterapéutico,FN_CATALOGODESC(170,A.otra_conduc) AS Otra_Conducta_Suicida,FN_CATALOGODESC(139,A.cual_conduc) AS Cual_Conducta,FN_CATALOGODESC(170,A.conduc_otrofam) ASConducta_Suicida_OtroFam,FN_CATALOGODESC(170,A.tam_cope) AS Tamizaje_Cope,FN_CATALOGODESC(140,A.total_afron) AS Cope_Afrontamiento,FN_CATALOGODESC(141,A.total_evita) AS Cope_Evitacion,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.aplica_tamiz) AS Aplica_Tamizaje_Cope,FN_CATALOGODESC(78,A.liker_dificul) AS Liker_Dificultades,FN_CATALOGODESC(78,A.liker_emocion) AS Liker_Emociones,FN_CATALOGODESC(78,A.liker_decision) AS Liker_Decisiones,FN_CATALOGODESC(140,A.cope_afronta) AS Cope_Afrontamiento,FN_CATALOGODESC(141,A.cope_evitacion) AS Cope_Evitacion,FN_CATALOGODESC(142,A.incremen_afron) AS Estrategia_Afrontamiento,FN_CATALOGODESC(143,A.incremen_evita) AS Estrategia_Evitacion,
FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina
 FROM `vsp_condsuic` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_condsuic` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_cronicos($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_cronicos AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,


P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,

FN_CATALOGODESC(170,A.condi_diag) AS Cronico_Diagnosticado,FN_CATALOGODESC(170,A.dx1) AS Hipertension,FN_CATALOGODESC(170,A.dx2) AS Diabetes,FN_CATALOGODESC(170,A.dx3) AS Epoc,FN_CATALOGODESC(170,A.asiste_control) AS Asiste_Controles_Cronico,FN_CATALOGODESC(170,A.trata_farma) AS Tratamiento_Farmacologico,FN_CATALOGODESC(170,A.adhere_tratami) AS Adherente_al_Tratamiento,FN_CATALOGODESC(170,A.mantien_dieta) AS Mantiene_Dieta_Recomendada,FN_CATALOGODESC(155,A.actividad_fisica) AS Actividad_Fisica,FN_CATALOGODESC(170,A.metodo_fecun) AS Cuenta_Metodo_Fecundidad,FN_CATALOGODESC(138,A.cual) AS Cual_Metodo,FN_CATALOGODESC(170,A.hemoglobina) AS Hemoglobina,A.fecha_hemo AS Fecha_Hemoglobina,A.valor_hemo AS Valor_Hemoglobina,A.tas AS Tension_Arterial_Sistolica,A.tad AS Tension_Arterial_Diastolica,A.glucometria AS Glucometria,A.peso AS 'Peso_(Kg)',A.talla AS 'Talla_(Cm)',A.imc AS Imc,A.peri_cintura AS Perimetro_Cintura,FN_CATALOGODESC(170,A.fuma) AS '¿Fuma?',

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina

FROM `vsp_cronicos` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_cronicos` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_dntsevymod($txt){
	$sql="SELECT
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_dntsevymod AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,

FN_CATALOGODESC(156,A.patolo_base) AS Patologia_de_Base,FN_CATALOGODESC(195,A.segui_medico) AS Seguimiento_Medico,FN_CATALOGODESC(170,A.asiste_control) AS Asiste_Controles_CYD,FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(88,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva,FN_CATALOGODESC(88,A.lacmate_comple) AS Lactancia_Materna_Complementaria,FN_CATALOGODESC(88,A.alime_complemen) AS Alimentacion_Complementaria,A.peso AS 'Peso_(Kg)',A.talla AS 'Talla_(Cm)',A.zscore AS Zscore,FN_CATALOGODESC(98,A.clasi_nutri) AS Clasificacion_Nutricional,FN_CATALOGODESC(170,A.gana_peso) AS Ganancia_Peso,FN_CATALOGODESC(158,A.trata_desnutri) AS Tratamiento_Desnutricion,A.tratamiento AS Tratamiento,FN_CATALOGODESC(196,A.consume_fruyverd) AS Come_Frutas_Verduras,FN_CATALOGODESC(196,A.consume_carnes) AS Consume_Carnes,FN_CATALOGODESC(196,A.consume_azucares) AS Consume_Azucar,FN_CATALOGODESC(196,A.actividad_fisica) AS Realiza_Actividad_Fisica,
FN_CATALOGODESC(170,A.apoyo_alimentario) AS Apoyo_Alimentario,FN_CATALOGODESC(170,A.signos_alarma) AS Signos_Alarma,FN_CATALOGODESC(170,A.signos_alarma_seg) AS Signos_Alarma_Seguimiento,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina
FROM `vsp_dntsevymod` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_dntsevymod` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_eraira($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_eraira AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,


P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,


A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,

FN_CATALOGODESC(170,A.asiste_control) AS Asiste_Controles_CYD,FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(88,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva,FN_CATALOGODESC(88,A.lacmate_comple) AS Lactancia_Materna_Complementaria,FN_CATALOGODESC(88,A.alime_complemen) AS Alimentacion_Complementaria,FN_CATALOGODESC(88,A.adecua_oxi) AS 'Administracion_Inhalador/Oxigeno',FN_CATALOGODESC(88,A.adhe_tratam) AS Adherencia_al_Tratamiento,FN_CATALOGODESC(170,A.signos_alarma) AS Signos_Alarma,FN_CATALOGODESC(170,A.signos_alarma_seg) AS Signos_Alarma_Seguimiento,FN_CATALOGODESC(170,A.adhe_lavamano) AS Tecnica_Lavado_Manos,FN_CATALOGODESC(170,A.reing_hospita) AS Reingreso_Hospitalario,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina
 FROM `vsp_eraira` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_eraira` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_gestantes($txt){
	$sql="SELECT  
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_gestante AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,


P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,FN_CATALOGODESC(136,A.etapa) AS Etapa,FN_CATALOGODESC(137,A.sema_gest) AS Semanas_Gestacion_Posevento,

FN_CATALOGODESC(170,A.asis_ctrpre) AS Asiste_control_Prenatal,FN_CATALOGODESC(170,A.exam_lab) AS Examenes_Laboratorio,FN_CATALOGODESC(170,A.esqu_vacuna) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.cons_micronutr) AS Consume_Micronutrientes,A.peso AS 'Peso_(Kg)',A.talla AS 'Talla_(Cm)',A.imc AS Imc,FN_CATALOGODESC(210,A.clasi_nutri) AS Clasificacion_Nutricional,FN_CATALOGODESC(170,A.gana_peso) AS Evidencia_Ganancia_Peso,FN_CATALOGODESC(205,A.cant_ganapesosem) AS Ganancia_Peso_Semanal,FN_CATALOGODESC(204,A.ante_patogest) AS Antecedentes_Patologicos,FN_CATALOGODESC(196,A.num_frutas) AS Come_Frutas_Verduras,FN_CATALOGODESC(196,A.num_carnes) AS Consume_Carnes,FN_CATALOGODESC(196,A.num_azucar) AS Consume_Azucar,
FN_CATALOGODESC(196,A.cant_actifisica) AS Realiza_Actividad_Fisica,FN_CATALOGODESC(170,A.adop_recomenda) AS Adopta_Recomendaciones_Nt,FN_CATALOGODESC(170,A.apoy_alim) AS Apoyo_Alimentario,A.fecha_obstetrica AS Fecha_Evento_Obstetrico,FN_CATALOGODESC(137,A.edad_gesta) AS Edad_Gestacional_Evento,FN_CATALOGODESC(193,A.resul_gest) AS Resultado_Gestacion,FN_CATALOGODESC(170,A.meto_fecunda) AS Cuenta_Metodo_Fecundidad,
FN_CATALOGODESC(138,A.cual) AS Cual_Metodo,A.peso_nacer AS Peso_RN_Nacer,FN_CATALOGODESC(170,A.asiste_control) AS Asiste_control_CYD,FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo,
FN_CATALOGODESC(170,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina
FROM `vsp_gestantes` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_gestantes` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_hbgest($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_hbgestacio AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,


P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,FN_CATALOGODESC(136,A.etapa) AS Etapa,FN_CATALOGODESC(137,A.sema_gest) AS Semanas_Gestacion_Posevento,

FN_CATALOGODESC(170,A.asis_ctrpre) AS Asiste_control_Prenatal,FN_CATALOGODESC(170,A.exam_lab) AS Examenes_Laboratorio,FN_CATALOGODESC(170,A.esqu_vacuna) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.cons_micronutr) AS Consume_Micronutrientes,A.fecha_obstetrica AS Fecha_Evento_Obstetrico,FN_CATALOGODESC(137,A.edad_gesta) AS Edad_Gestacional_Evento,FN_CATALOGODESC(193,A.resul_gest) AS Resultado_Gestacion,FN_CATALOGODESC(170,A.meto_fecunda) AS Cuenta_Metodo_Fecundidad,FN_CATALOGODESC(130,A.cual) AS Cual_Metodo,FN_CATALOGODESC(170,A.asiste_control) AS Asiste_control_CYD,FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.lacmate_comple) AS Lactancia_Materna_Exclusiva,FN_CATALOGODESC(170,A.vacuna_hb) AS Rn_con_Vacuna_HB,A.fec_hb_recnac AS Fecha_Vacuna_HB,FN_CATALOGODESC(170,A.reci_inmunoglo) AS Recibe_Inmunoglobulina,FN_CATALOGODESC(170,A.seg_eps) AS Seguimiento_EPS,FN_CATALOGODESC(170,A.antige_super1) AS Antigeno_de_Superficie,FN_CATALOGODESC(187,A.resultado1) AS Resultado_Antigeno,FN_CATALOGODESC(170,A.anticor_igm_hb1) AS AntiCore_Igm_HB,FN_CATALOGODESC(187,A.resultado2) AS Resultado_AntiCore,FN_CATALOGODESC(170,A.anticor_toigm_hb1) AS AntiCore_Total_Igm_HB,FN_CATALOGODESC(187,A.resultado3) AS Resultado_AntiCore_Total,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina
FROM `vsp_hbgest` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_hbgest` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_mme($txt){
	$sql=" WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	// echo $sql;
	$tot=" WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred();
	$tot.=whe_date();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_mnehosp($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_mnehosp AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,


P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,

FN_CATALOGODESC(92,A.even_prio) AS Evento_Priorizado,FN_CATALOGODESC(170,A.asiste_control) AS Asiste_Controles_CYD,FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(88,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva,FN_CATALOGODESC(88,A.lacmate_comple) AS Lactancia_Materna_Complementaria,FN_CATALOGODESC(88,A.alime_complemen) AS Alimentacion_Complementaria,FN_CATALOGODESC(170,A.adhe_tratam) AS Adherencia_al_tratamiento,FN_CATALOGODESC(170,A.ira_eda) AS Presenta_IRA_o_EDA,FN_CATALOGODESC(170,A.signos_alarma_seg) AS Signos_Alarma_Seguimiento,FN_CATALOGODESC(170,A.reing_hospita) AS Reingreso_Hospitalario,FN_CATALOGODESC(170,A.signos_alarma) AS Signos_Alarma,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina

FROM `vsp_mnehosp` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_mnehosp` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_otroprio($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_otroprio AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina

FROM `vsp_otroprio` A

LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_otroprio` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_saludoral($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_saludoral AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,

FN_CATALOGODESC(91,A.clasi_riesgo) AS Clasificacion_Riesgo,FN_CATALOGODESC(170,A.sangra_cepilla) AS Sangrado_Cepillado_Dental,FN_CATALOGODESC(170,A.evide_anormal) AS Evidencia_Autoexamen_Anormal,
A.explica_breve AS Explique_Brevemente,FN_CATALOGODESC(170,A.urg_odonto) AS Urgencia_Odontologica,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,

FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,
A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,A.mejora_practica AS Mejora_Practicas,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina

FROM `vsp_saludoral` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_saludoral` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_sificong($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_sificong AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,

FN_CATALOGODESC(170,A.asiste_control) AS Asiste_Controles_CYD,FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva,FN_CATALOGODESC(170,A.altera_desarr) AS Alteraciones_del_Desarrollo,FN_CATALOGODESC(170,A.serologia) AS Primera_Serologia,A.fecha_serolo AS Fecha_Serologia,FN_CATALOGODESC(94,A.resul_ser) AS Resultado_Serologia,FN_CATALOGODESC(170,A.trata_rn) AS Tratamiento_RN,FN_CATALOGODESC(170,A.ctrl_serolo) AS Control_Serologia,A.fecha_controlser AS Fecha_Control_Serologia,FN_CATALOGODESC(94,A.resul_controlser) AS Resultado_Control_Serologia,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina

FROM `vsp_sificong` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_sificong` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_sifigest($txt){
	$sql="SELECT 
	G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_sifigest AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,
	P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,
	A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,FN_CATALOGODESC(136,A.etapa) AS Etapa,FN_CATALOGODESC(137,A.sema_gest) AS Semanas_Gestacion_Posevento,
	
	FN_CATALOGODESC(170,A.asis_ctrpre) AS Asiste_control_Prenatal,FN_CATALOGODESC(170,A.exam_lab) AS Examenes_Laboratorio,FN_CATALOGODESC(170,A.esqu_vacuna) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.cons_micronutr) AS Consume_Micronutrientes,A.fecha_obstetrica AS Fecha_Evento_Obstetrico,FN_CATALOGODESC(137,A.edad_gesta) AS Edad_Gestacional_Evento,FN_CATALOGODESC(193,A.resul_gest) AS Resultado_Gestacion,FN_CATALOGODESC(170,A.meto_fecunda) AS Cuenta_Metodo_Fecundidad,FN_CATALOGODESC(138,A.cual) AS Cual_Metodo,FN_CATALOGODESC(170,A.confir_sificong) AS RN_Confir_Sífilis_Congénita,FN_CATALOGODESC(94,A.resul_ser_recnac) AS Resultado_Serologia_RN,FN_CATALOGODESC(199,A.trata_recnac) AS Tratamiento_RN,FN_CATALOGODESC(70,A.serol_3meses) AS RN_Serologia_3meses,A.fec_conser_1tri2 AS Fecha_Serologia_3meses,FN_CATALOGODESC(94,A.resultado) AS Resultado_Serologia_3meses,FN_CATALOGODESC(170,A.ctrl_serol1t) AS Control_Serologia_1Trimestre,A.fec_conser_1tri1 AS Fecha_Serologia_1Trimestre,FN_CATALOGODESC(94,A.resultado_1) AS Resultado_Serologia_1Trimestre,FN_CATALOGODESC(170,A.ctrl_serol2t) AS Control_Serologia_2Trimestre,A.fec_conser_2tri AS Fecha_Serologia_2Trimestre,FN_CATALOGODESC(94,A.resultado_2) AS Resultado_Serologia_2Trimestre,FN_CATALOGODESC(170,A.ctrl_serol3t) AS Control_Serologia_3Trimestre,A.fec_conser_3tri AS Fecha_Serologia_3Trimestre,FN_CATALOGODESC(94,A.resultado_3) AS Resultado_Serologia_3Trimestre,
	
	FN_CATALOGODESC(170,A.initratasif) AS Inicio_Tratamiento_Sifilis_Ges,A.fec_1dos_trages1 AS 1Dosis,A.fec_2dos_trages1 AS 2Dosis,A.fec_3dos_trages1 AS 3Dosis,
	
	FN_CATALOGODESC(200,A.pri_con_sex) AS Primer_Contacto_Sexual,
FN_CATALOGODESC(207,A.initratasif1) AS Contacto_Sexual_Inicia_Tratamiento,A.fec_apl_tra_1dos1 AS Fecha_Primera_Dosis,A.fec_apl_tra_2dos1 AS Fecha_Segunda_Dosis,A.fec_apl_tra_3dos1 AS Fecha_Tercera_Dosis,
FN_CATALOGODESC(200,A.seg_con_sex) AS Segundo_Contacto_Sexual,FN_CATALOGODESC(207,A.initratasif2) AS Contacto_Sexual_Inicia_Tratamiento,A.fec_apl_tra_1dos2 AS Fecha_Primera_Dosis,A.fec_apl_tra_2dos2 AS Fecha_Segunda_Dosis,A.fec_apl_tra_3dos2 AS Fecha_Tercera_Dosis,FN_CATALOGODESC(170,A.prese_reinfe) AS Presenta_Reinfeccion,FN_CATALOGODESC(207,A.initratasif3) AS Tratamiento_Reinfeccion,A.fec_1dos_trages2 AS Fecha_Primera_Dosis,A.fec_2dos_trages2 AS Fecha_Segunda_Dosis,A.fec_3dos_trages2 AS Fecha_Tercera_Dosis,
FN_CATALOGODESC(200,A.reinf_1con) AS Primer_Contacto_Sexual,FN_CATALOGODESC(207,A.initratasif4) AS Contacto_Sexual_Inicia_Tratamiento,A.fec_1dos_trapar AS Fecha_Primera_Dosis,A.fec_2dos_trapar AS Fecha_Segunda_Dosis,A.fec_3dos_trapar AS Fecha_Tercera_Dosis,
FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina
FROM `vsp_sifigest` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_sifigest` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

/*
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_sifigest AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,FN_CATALOGODESC(136,A.etapa) AS Etapa,FN_CATALOGODESC(137,A.sema_gest) AS Semanas_Gestacion_Posevento,

FN_CATALOGODESC(170,A.asis_ctrpre) AS Asiste_control_Prenatal,FN_CATALOGODESC(170,A.exam_lab) AS Examenes_Laboratorio,FN_CATALOGODESC(170,A.esqu_vacuna) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.cons_micronutr) AS Consume_Micronutrientes,A.fecha_obstetrica AS Fecha_Evento_Obstetrico,FN_CATALOGODESC(137,A.edad_gesta) AS Edad_Gestacional_Evento,FN_CATALOGODESC(193,A.resul_gest) AS Resultado_Gestacion,FN_CATALOGODESC(170,A.meto_fecunda) AS Cuenta_Metodo_Fecundidad,FN_CATALOGODESC(138,A.cual) AS Cual_Metodo,FN_CATALOGODESC(170,A.confir_sificong) AS RN_Confir_Sífilis_Congénita,FN_CATALOGODESC(94,A.resul_ser_recnac) AS Resultado_Serologia_RN,FN_CATALOGODESC(199,A.trata_recnac) AS Tratamiento_RN,FN_CATALOGODESC(70,A.serol_3meses) AS RN_Serologia_3meses,A.fec_conser_1tri2 AS Fecha_Serologia_3meses,FN_CATALOGODESC(94,A.resultado) AS Resultado_Serologia_3meses,FN_CATALOGODESC(170,A.ctrl_serol1t) AS Control_Serologia_1Trimestre,A.fec_conser_1tri1 AS Fecha_Serologia_1Trimestre,FN_CATALOGODESC(94,A.resultado_1) AS Resultado_Serologia_1Trimestre,FN_CATALOGODESC(170,A.ctrl_serol2t) AS Control_Serologia_2Trimestre,A.fec_conser_2tri AS Fecha_Serologia_2Trimestre,FN_CATALOGODESC(94,A.resultado_2) AS Resultado_Serologia_2Trimestre,FN_CATALOGODESC(170,A.ctrl_serol3t) AS Control_Serologia_3Trimestre,A.fec_conser_3tri AS Fecha_Serologia_3Trimestre,FN_CATALOGODESC(94,A.resultado_3) AS Resultado_Serologia_3Trimestre,

FN_CATALOGODESC(170,A.initratasif) AS Inicio_Tratamiento_Sifilis_Ges,
A.fec_1dos_trages1 AS Fecha_Primera_Dosis,A.fec_2dos_trages1 AS Fecha_Segunda_Dosis,A.fec_3dos_trages1 AS Fecha_Tercera_Dosis,

FN_CATALOGODESC(200,A.pri_con_sex) AS Primer_Contacto_Sexual,
FN_CATALOGODESC(207,A.initratasif1) AS Contacto_Sexual_Inicia_Tratamiento,A.fec_apl_tra_1dos1 AS Fecha_Primera_Dosis,A.fec_apl_tra_2dos1 AS Fecha_Segunda_Dosis,A.fec_apl_tra_3dos1 AS Fecha_Tercera_Dosis,
FN_CATALOGODESC(200,A.seg_con_sex) AS Segundo_Contacto_Sexual,FN_CATALOGODESC(207,A.initratasif2) AS Contacto_Sexual_Inicia_Tratamiento,A.fec_apl_tra_1dos2 AS Fecha_Primera_Dosis,A.fec_apl_tra_2dos2 AS Fecha_Segunda_Dosis,A.fec_apl_tra_3dos2 AS Fecha_Tercera_Dosis,FN_CATALOGODESC(170,A.prese_reinfe) AS Presenta_Reinfeccion,FN_CATALOGODESC(207,A.initratasif3) AS Tratamiento_Reinfeccion,A.fec_1dos_trages2 AS Fecha_Primera_Dosis,A.fec_2dos_trages2 AS Fecha_Segunda_Dosis,A.fec_3dos_trages2 AS Fecha_Tercera_Dosis,
FN_CATALOGODESC(200,A.reinf_1con) AS Primer_Contacto_Sexual,FN_CATALOGODESC(207,A.initratasif4) AS Contacto_Sexual_Inicia_Tratamiento,A.fec_1dos_trapar AS Fecha_Primera_Dosis,A.fec_2dos_trapar AS Fecha_Segunda_Dosis,A.fec_3dos_trapar AS Fecha_Tercera_Dosis,
FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,
A.users_bina AS Usuarios_Bina
*/


function lis_vihgest($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_vihgestacio AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,FN_CATALOGODESC(136,A.etapa) AS Etapa,FN_CATALOGODESC(137,A.sema_gest) AS Semanas_Gestacion_Posevento,

FN_CATALOGODESC(170,A.asis_ctrpre) AS Asiste_control_Prenatal,FN_CATALOGODESC(170,A.exam_lab) AS Examenes_Laboratorio,FN_CATALOGODESC(170,A.esqu_vacuna) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.cons_micronutr) AS Consume_Micronutrientes,A.fecha_obstetrica AS Fecha_Evento_Obstetrico,FN_CATALOGODESC(137,A.edad_gesta) AS Edad_Gestacional_Evento,FN_CATALOGODESC(193,A.resul_gest) AS Resultado_Gestacion,FN_CATALOGODESC(170,A.meto_fecunda) AS Cuenta_Metodo_Fecundidad,FN_CATALOGODESC(138,A.cual_metodo) AS Cual_Metodo,FN_CATALOGODESC(170,A.asiste_control) AS Asiste_control_CYD,FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.lacmate_comple) AS Lactancia_Materna_Exclusiva,FN_CATALOGODESC(170,A.recnac_proxi) AS Rn_Recibio_Profilaxis,FN_CATALOGODESC(170,A.formu_lact) AS Recibe_Formula_Lactea,FN_CATALOGODESC(209,A.tarros_mes) AS Tarros_Mes,FN_CATALOGODESC(170,A.caso_con_tmi) AS Caso_Conf_Transmisi_Mater_Infa,FN_CATALOGODESC(170,A.asis_provih_rn) AS RN_Asiste_Programa_VIH,FN_CATALOGODESC(170,A.cargaviral_1mes) AS Carga_Viral_1Mes,A.fecha_carga1mes AS Fecha_Carga_Viral_1Mes,FN_CATALOGODESC(208,A.resul_carga1mes) AS Resultado_Carga_Viral_1Mes,FN_CATALOGODESC(170,A.cargaviral_4mes) AS Carga_Viral_4Mes,A.fecha_carga4mes AS Fecha_Carga_Viral_4Mes,FN_CATALOGODESC(208,A.resul_carga4mes) AS Resultado_Carga_Viral_4Mes,FN_CATALOGODESC(170,A.prueba_rapida) AS Tiene_Prueba_Rapida,A.fec_pruerap1 AS Fecha_Prueba_Rapida,FN_CATALOGODESC(170,A.carga_viral) AS Carga_Viral,A.fec_cargaviral1 AS Fecha_Carga_Viral,FN_CATALOGODESC(208,A.resul_cargaviral1) AS Resultado_Carga_Viral,FN_CATALOGODESC(170,A.asis_provih1) AS Asiste_Programa_VIH,A.cual1 AS Cual,FN_CATALOGODESC(170,A.adhe_tra_antirre1) AS Adherente_Antirretroviral,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,

FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina

FROM `vsp_vihgest` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_vihgest` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_violges($txt){
	$sql="SELECT
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_gestante AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,
P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,
A.fecha_seg AS Fecha_Seguimiento, FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento, FN_CATALOGODESC(87,A.evento) AS Evento, FN_CATALOGODESC(73,A.estado_s) AS Estado,
FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado, FN_CATALOGODESC(136,A.etapa) AS Etapa, FN_CATALOGODESC(137,A.sema_gest) AS Semanas_Gestacion_Posevento,
FN_CATALOGODESC(170,A.asis_ctrpre) AS Asiste_control_Prenatal, FN_CATALOGODESC(170,A.exam_lab) AS Examenes_Laboratorio, FN_CATALOGODESC(170,A.esqu_vacuna) AS Esquema_Vacuna_Completo, FN_CATALOGODESC(170,A.cons_micronutr) AS Consume_Micronutrientes, 
A.fecha_obstetrica AS Fecha_Evento_Obstetrico, FN_CATALOGODESC(137,A.edad_gesta) AS Edad_Gestacional_Evento, FN_CATALOGODESC(193,A.resul_gest) AS Resultado_Gestacion,
FN_CATALOGODESC(170,A.meto_fecunda) AS Cuenta_Metodo_Fecundidad, FN_CATALOGODESC(138,A.cual) AS Cual_Metodo, FN_CATALOGODESC(170,A.peso_nacer) AS Peso_RN_Nacer,
FN_CATALOGODESC(170,A.asiste_control) AS Asiste_control_CYD, FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo, FN_CATALOGODESC(170,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva, FN_CATALOGODESC(170,A.persis_riesgo) AS Persisten_Riesgos_Asociados, FN_CATALOGODESC(170,A.apoy_sector) AS Apoyo_Otro_Sector,
FN_CATALOGODESC(89,A.cual_sec) AS Cual_Sector, FN_CATALOGODESC(170,A.tam_cope) AS Aplica_Tamizaje_Cope, FN_CATALOGODESC(140,A.total_afron) AS Cope_Afrontamiento,
FN_CATALOGODESC(141,A.total_evita) AS Cope_Evitacion,
FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta, FN_CATALOGODESC(79,A.ruta) AS Ruta, FN_CATALOGODESC(77,A.novedades) AS Novedades, FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid, A.caso_afirmativo AS Relacione_Cuales, A.otras_condiciones AS Otras_Condiciones, A.observaciones AS Observaciones,
FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,
FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre, A.fecha_cierre AS Fecha_Cierre, FN_CATALOGODESC(170,A.aplica_tamiz) AS Aplica_Tamizaje_Cope, FN_CATALOGODESC(78,A.liker_dificul) AS Liker_Dificultades, FN_CATALOGODESC(78,A.liker_emocion) AS Liker_Emociones, FN_CATALOGODESC(78,A.liker_decision) AS Liker_Decisiones, FN_CATALOGODESC(140,A.cope_afronta) AS Cope_Afrontamiento, FN_CATALOGODESC(141,A.cope_evitacion) AS Cope_Evitacion, FN_CATALOGODESC(142,A.incremen_afron) AS Estrategia_Afrontamiento, FN_CATALOGODESC(143,A.incremen_evita) AS Estrategia_Evitacion, FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina
FROM `vsp_violges` A
LEFT JOIN personas P ON A.tipo_doc = P.tipo_doc AND A.documento=P.idpersona
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON V.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_violreite` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";	
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}


function lis_violreite($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_violreite AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,

FN_CATALOGODESC(88,A.asiste_control) AS Asiste_Controles_CYD,FN_CATALOGODESC(88,A.vacuna_comple) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(88,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva,FN_CATALOGODESC(88,A.lacmate_comple) AS Lactancia_Materna_Complementaria,FN_CATALOGODESC(88,A.alime_complemen) AS Alimentacion_Complementaria,FN_CATALOGODESC(170,A.riesgo_violen) AS Persisten_Riesgos_Violencia,FN_CATALOGODESC(170,A.apoyo_sector) AS Apoyo_Otro_Sector,FN_CATALOGODESC(89,A.cual_sector) AS Cual_Sector,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(78,A.liker_dificul) AS Liker_Dificultades,FN_CATALOGODESC(78,A.liker_emocion) AS Liker_Emociones,FN_CATALOGODESC(78,A.liker_decision) AS Liker_Decisiones,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create AS Fecha_Creacion,A.equipo_bina AS Cod_Bina,A.users_bina AS Usuarios_Bina
FROM `vsp_violreite` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_violreite` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";	
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function whe_subred() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(A.fecha_create) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred1() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date1(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(V.fecha) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
} 

function whe_subred2() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date2(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(C.fecha) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred3() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date3(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(P.fecha) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred4() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date4(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(S.fecha_toma) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred5() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date5(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(A.fecha) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred6() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date6(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(A.fecha) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred7() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date7(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(A.fecha_even) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}


function whe_subred8() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date8(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(A.fecha_seg) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred12() {
	$sql= " AND (A.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date12(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(A.fecha_create) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred13() {
	$sql= " AND (R.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date13(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(R.fecha_gestion) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred14() {
	$sql= " AND (U.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_subred15() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date15(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(P.fecha_create) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}
function whe_subred16() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date16(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(C.fecha_create) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}
function whe_subred17() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date17(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(A.fecha_create) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}


function whe_subred18() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date18(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(R.rel_validacion18) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred19() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date19(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(R.rel_validacion2) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred20() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date20(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(V.fechaupd) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred22() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_subred23() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}



function encript($texto, $clave) {
    $txtcript = openssl_encrypt($texto, 'aes-256-ecb', $clave, 0);
    return base64_encode($txtcript);
}

function decript($txtcript, $clave) {
    $txtcript = base64_decode($txtcript);
    $texto = openssl_decrypt($txtcript, 'aes-256-ecb', $clave, 0);
    return $texto;
}

function lis_homes(){
/* $sql1="SELECT * FROM CARACTERIZACION C"; 
$sql1.=whe_data();
	$sql1.=" ORDER BY 1 ASC;";
	$_SESSION['sql_caracterizacion']=$sql1;
	$rta = array(
		'type' => 'OK','msj'=>$sql1
	);
	echo json_encode($rta); */
}

function opc_bina($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=217 and estado='A' and valor=(SELECT subred FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}') ORDER BY 1",$id);
}
function opc_territorio($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=202 and estado='A' and valor=(SELECT subred FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}')  ORDER BY 1",$id);
}
function opc_subred($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=72 and estado='A' and idcatadeta in(1,2,4,3) ORDER BY 1",$id);
}
function opc_perfil($id=''){
	$com=datos_mysql("SELECT CASE WHEN componente = 'EAC' THEN 2 WHEN componente = 'HOG' THEN 1 END as componente FROM usuarios WHERE id_usuario ='{$_SESSION['us_sds']}'");
	$comp = $com['responseResult'][0]['componente'] ;
	// return $comp;
	return opc_sql("SELECT idcatadeta, descripcion FROM `catadeta` WHERE idcatalogo = 218 AND estado = 'A' AND valor='1'",$id);
}
function opc_gestion($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=216 and estado='A' ORDER BY 1",$id);
}

function opc_proceso($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=206 and estado='A' ORDER BY LPAD(idcatadeta,2,'0')",$id);
}

function opc_tarea($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
}

function opc_rol($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
}

function opc_usuarios($id=''){
	return opc_sql("SELECT id_usuario,concat_ws(' - ',id_usuario,nombre,perfil) FROM usuarios WHERE  estado='A' AND componente=(SELECT componente FROM usuarios WHERE id_usuario ='{$_SESSION['us_sds']}') ORDER BY 1",$id);
}

function opc_perfilusuarios($id=''){
	
	if($_REQUEST['id']!=''){
		$id=divide($_REQUEST['id']);
		$sql="SELECT id_usuario,concat_ws(' - ',id_usuario,nombre,perfil) FROM usuarios WHERE estado='A' AND componente=(SELECT componente FROM usuarios WHERE id_usuario ='{$_SESSION['us_sds']}') AND perfil=(SELECT descripcion FROM `catadeta` WHERE idcatalogo=218 AND idcatadeta=$id[0]) ORDER BY 1";
		$info=datos_mysql($sql);		
		// var_dump($sql);
		return json_encode($info['responseResult']);
	} 
}


function focus_administracion(){
 return 'administracion';
}


function men_administracion(){
 $rta=cap_menus('administracion','pro');
 return $rta;
}

function focus_gestionusu(){
	return 'homes1';
}
function men_gestionusu(){
	$rta=cap_menus('homes','pro');
	return $rta;
}
function focus_planos(){
	return 'homes1';
}
function men_planos(){
	$rta=cap_menus('homes','pro');
	return $rta;
}

function cap_menus($a,$b='cap',$con='con') {
  $rta = "";
  $acc=rol($a);
  if ($a=='administracion'  && isset($acc['crear']) && $acc['crear']=='SI'){  
    $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
    $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
	// $rta .= "<li class='icono $a crear'  title='Actualizar'   id='".print_r($_POST)."'   Onclick=\"mostrar('administracion','pro',event,'','lib.php',7);\"></li>";
  }
  return $rta;
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='administracion' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono admsi1' title='Información de la Facturación' id='".$c['ACCIONES']."' Onclick=\"mostrar('administracion','pro',event,'','lib.php',7);\"></li>"; //setTimeout(hideExpres,1000,'estado_v',['7']);
	}
	if ($a=='adm-lis' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono editar ' title='Editar ' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'administracion',event,this,'lib.php');\"></li>";  //act_lista(f,this);
		// $rta.="<li class='icono editar' title='Editar Información de Facturación' id='".$c['ACCIONES']."' Onclick=\"getData('administracion','pro',event,'','lib.php',7);\"></li>"; //setTimeout(hideExpres,1000,'estado_v',['7']);
	}
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
