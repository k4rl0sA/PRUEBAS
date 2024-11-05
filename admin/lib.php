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
	$c[]=new cmp('proceso','s',3,$d['proceso'],$w.' DwL '.$o,'Proceso','proceso',null,'',true,true,'','col-2');
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
			$tab = "Plan_Cuidado_EAC";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_plancueac($tab);
            break;	
        case '5':
			$tab = "Psicologia_Sesion1";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_psico1($tab);
            break;
        case '6':
			$tab = "Psicologia_Sesion2";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_psico2($tab);
            break;	
        case '7':
			$tab = "Psicologia_Sesiones";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_psico3($tab);
            break;	
        case '8':
			$tab = "Psicologia_Sesion_Fin";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_psico4($tab);
            break;	
        case '9':
			$tab = "Relevo_Identificacion";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_relevo1($tab);
            break;	
        case '10':
			$tab = "Relevo_Sesiones";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_relevo2($tab);
            break;	
        case '11':
			$tab = "Ruteo_Gestion";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_ruteo($tab);
            break;	
        case '12':
			$tab = "Caracterizacion_Hogar";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_caracter($tab);
            break;
        case '13':
			$tab = "Plan_Cuidado_Hogar";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_placuid($tab);
            break;
        case '14':
			$tab = "Alertas_Medidas_Hogar";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_alertas($tab);
            break;
        case '15':
			$tab = "Riesgo_Ambiental";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_ambiente($tab);
            break;
        case '16':
			$tab = "Tamizaje_Apgar";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_apgar($tab);
            break;
        case '17':
			$tab = "Tamizaje_Findrisc";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_findrisc($tab);
            break;    
        case '18':
			$tab = "Tamizaje_Oms";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_oms($tab);
            break;
        case '19':
			$tab = "VSP_Acompañamiento_Psicosocial";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_acompsic($tab);
            break;
        case '20':
			$tab = "VSP_Apoyo_Psicosocial_En_Duelo";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_apopsicduel($tab);
            break;
        case '21':
			$tab = "VSP_BPN_Pretermino";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_bpnpret($tab);
            break;
        case '22':
			$tab = "VSP_BPN_a_Termino";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_bpnterm($tab);
            break;
        case '23':
			$tab = "VSP_Cancer_Infantil";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_cancinfa($tab);
            break;
        case '24':
			$tab = "VSP_Conducta_Suicida";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_condsuic($tab);
            break;
        case '25':
			$tab = "VSP_Cronicos";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_cronicos($tab);
            break;
        case '26':
			$tab = "VSP_DNT_Severa_y_Moderada";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_dntsevymod($tab);
            break;
        case '27':
			$tab = "VSP_Era_Ira";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_eraira($tab);
            break;
        case '28':
			$tab = "VSP_Gestantes";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_Gestantes($tab);
            break;
        case '29':
			$tab = "VSP_HB_Gestacional";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_hbgest($tab);
            break;
        case '30':
			$tab = "VSP_Morbilidad_Materna_Extrema";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_mme($tab);
            break;
        case '31':
			$tab = "VSP_Hipotiroidismo";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_mnehosp($tab);
            break;
        case '32':
			$tab = "VSP_Otros_Casos_Priorizados";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_otroprio($tab);
            break;
        case '33':
			$tab = "VSP_Salud_Oral";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_saludoral($tab);
            break;
        case '34':
			$tab = "VSP_Sifilis_Congenita";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_sificong($tab);
            break;
        case '35':
			$tab = "VSP_Sifilis_Gestacional";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_sifigest($tab);
            break;
        case '36':
			$tab = "VSP_VIH_Gestacional";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_vihgest($tab);
            break;
        case '37':
			$tab = "VSP_Violencia_En_Gestantes";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_violges($tab);
            break;
        case '38':
			$tab = "VSP_Violencia_Reiterada";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_violreite($tab);
            break;
        case '39':
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
	$sql= " AND date(A.atencion_fechaatencion) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
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
	$sql= " AND date(A.fecha_ses1) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
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
	$sql= " AND date(A.psi_fecha_sesion) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
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
	$sql= " AND date(V.fecha) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred9() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date9(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(A.fecha) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}

function whe_subred10() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date10(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(A.fecha_create) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
	return $sql;
}


function whe_subred11() {
	$sql= " AND (G.subred) in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date11(){
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
