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
	$info=datos_mysql("SELECT COUNT(*) total FROM `adm_usuarios` C 
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
	return create_table($total,$datos["responseResult"],"adm_usuarios",$regxPag);
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
	$ini=-140;
	//$ini=date('d')<11 ?-date('d')-31:-date('d');
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
			$tab = "Geografico";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_geolo($tab);
		break;
        case '2':
			$tab = "Admision_Facturacion";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_admfact($tab);
           break;
        case '3':
			$tab = "Atenciones";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_atencion($tab);
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
        default:
            break;    
    }
}




function lis_geolo($txt){
	$sql="SELECT G.idgeo AS Cod_Registro,CONCAT(G.estrategia, '_', G.sector_catastral, '_', G.nummanzana, '_', G.predio_num, '_', G.unidad_habit, '_', G.estado_v) AS ID_FAMILIAR,
C1.descripcion AS Estrategia,G.subred AS Cod_Subred,C2.descripcion AS Subred,G.zona AS Cod_Zona,C3.descripcion AS Zona,G.localidad AS Cod_Localidad,C4.descripcion AS Localidad,G.upz AS Cod_Upz, C5.descripcion AS Upz,G.barrio AS Cod_Barrio,C6.descripcion AS Barrio,
G.territorio AS Territorio,G.microterritorio AS Manzana_Cuidado,G.sector_catastral AS Sector_Catastral,G.nummanzana AS Numero_Manzana,G.predio_num AS Numero_Predio,G.unidad_habit AS Unidad_Habitacional,
G.direccion AS Direccion,G.vereda AS Vereda,G.cordx AS Coordenada_X,G.cordy AS Coordenda_Y,G.direccion_nueva AS Direccion_Nueva,G.vereda_nueva AS Vereda_Nueva,G.cordxn AS Coordenada_X_Nueva,G.cordyn AS Coordenada_Y_Nueva,G.estrato AS Estrato,G.asignado AS Cod_Usuario_Asignado,U1.nombre AS Usuario_Asignado,G.equipo AS Equipo_Usuario,FN_CATALOGODESC(44,G.estado_v) AS Estado_Visita,FN_CATALOGODESC(5,G.motivo_estado) AS Motivo_Estado,
G.usu_creo,U2.nombre,U2.perfil,G.fecha_create
FROM `hog_geo` G
LEFT JOIN catadeta C1 ON C1.idcatadeta = G.estrategia AND C1.idcatalogo = 42 AND C1.estado = 'A'
LEFT JOIN catadeta C2 ON C2.idcatadeta = G.subred AND C2.idcatalogo = 72 AND C2.estado = 'A'
LEFT JOIN catadeta C3 ON C3.idcatadeta = G.zona AND C3.idcatalogo = 3 AND C3.estado = 'A'
LEFT JOIN catadeta C4 ON C4.idcatadeta = G.localidad AND C4.idcatalogo = 2 AND C4.estado = 'A'
LEFT JOIN catadeta C5 ON C5.idcatadeta = G.upz AND C5.idcatalogo = 7 AND C5.estado = 'A'
LEFT JOIN catadeta C6 ON C6.idcatadeta = G.barrio AND C6.idcatalogo = 20 AND C6.estado = 'A'
LEFT JOIN usuarios U1 ON G.asignado = U1.id_usuario
LEFT JOIN usuarios U2 ON G.usu_creo = U2.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	
	$tot="SELECT count(*) as total FROM `hog_geo` G LEFT JOIN catadeta C1 ON C1.idcatadeta = G.estrategia AND C1.idcatalogo = 42 AND C1.estado = 'A' LEFT JOIN catadeta C2 ON C2.idcatadeta = G.subred AND C2.idcatalogo = 72 AND C2.estado = 'A' LEFT JOIN catadeta C3 ON C3.idcatadeta = G.zona AND C3.idcatalogo = 3 AND C3.estado = 'A' LEFT JOIN catadeta C4 ON C4.idcatadeta = G.localidad AND C4.idcatalogo = 2 AND C4.estado = 'A' LEFT JOIN catadeta C5 ON C5.idcatadeta = G.upz AND C5.idcatalogo = 7 AND C5.estado = 'A' LEFT JOIN catadeta C6 ON C6.idcatadeta = G.barrio AND C6.idcatalogo = 20 AND C6.estado = 'A' LEFT JOIN usuarios U1 ON G.asignado = U1.id_usuario LEFT JOIN usuarios U2 ON G.usu_creo = U2.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred();
	$tot.=whe_date();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_admfact($txt){
	$sql="SELECT G.subred,V.idgeo AS Id_Familiar,V.idviv AS Cod_Familia,
P.tipo_doc,P.idpersona,CONCAT(P.nombre1, ' ', P.nombre2) AS Nombres_Usuario,CONCAT(P.apellido1, ' ', P.apellido2) AS Apellidos_Usuario,P.fecha_nacimiento AS Fecha_Nacimiento,C1.descripcion AS Sexo,C2.descripcion AS Genero,C3.descripcion AS Orientacion_Sexual,C4.descripcion AS Nacionalidad,C5.descripcion AS Estado_Civil,    C6.descripcion AS Nivel_Educativo,C7.descripcion AS Razon_Abandono_Escolar,C8.descripcion AS Ocupacion,C9.descripcion AS Vinculo_Jefe_Hogar,C10.descripcion AS Etnia,    C11.descripcion AS Pueblo_Etnia,P.idioma AS Habla_Español_Etnia,C12.descripcion AS Tipo_Discapacidad,C13.descripcion AS Regimen,C14.descripcion AS Eapb,C15.descripcion AS Grupo_Sisben,P.catgosisb AS Categoria_Sisben,C16.descripcion AS Poblacion_Diferencial,C17.descripcion AS Poblacion_Por_Oficio,

F.soli_admis AS Solicitud_Admision,F.fecha_consulta AS Fecha_Consulta,C18.descripcion AS Tipo_Consulta,F.cod_admin AS Cod_Admision,C19.descripcion AS Cod_Cups,    C20.descripcion AS Finalidad_Consulta,F.cod_factura AS Cod_Facturacion,C21.descripcion AS Estado_Admision,

F.usu_creo,U.nombre,U.perfil,F.fecha_create,F.fecha_update
FROM `adm_facturacion` F
LEFT JOIN personas P ON F.documento = P.idpersona AND F.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN catadeta C1 ON C1.idcatadeta = P.sexo AND C1.idcatalogo = 21 AND C1.estado = 'A'
LEFT JOIN catadeta C2 ON C2.idcatadeta = P.genero AND C2.idcatalogo = 19 AND C2.estado = 'A'
LEFT JOIN catadeta C3 ON C3.idcatadeta = P.oriensexual AND C3.idcatalogo = 49 AND C3.estado = 'A'
LEFT JOIN catadeta C4 ON C4.idcatadeta = P.nacionalidad AND C4.idcatalogo = 30 AND C4.estado = 'A'
LEFT JOIN catadeta C5 ON C5.idcatadeta = P.estado_civil AND C5.idcatalogo = 47 AND C5.estado = 'A'
LEFT JOIN catadeta C6 ON C6.idcatadeta = P.niveduca AND C6.idcatalogo = 180 AND C6.estado = 'A'
LEFT JOIN catadeta C7 ON C7.idcatadeta = P.abanesc AND C7.idcatalogo = 181 AND C7.estado = 'A'
LEFT JOIN catadeta C8 ON C8.idcatadeta = P.ocupacion AND C8.idcatalogo = 175 AND C8.estado = 'A'
LEFT JOIN catadeta C9 ON C9.idcatadeta = P.vinculo_jefe AND C9.idcatalogo = 54 AND C9.estado = 'A'
LEFT JOIN catadeta C10 ON C10.idcatadeta = P.etnia AND C10.idcatalogo = 16 AND C10.estado = 'A'
LEFT JOIN catadeta C11 ON C11.idcatadeta = P.pueblo AND C11.idcatalogo = 15 AND C11.estado = 'A'
LEFT JOIN catadeta C12 ON C12.idcatadeta = P.discapacidad AND C12.idcatalogo = 14 AND C12.estado = 'A'
LEFT JOIN catadeta C13 ON C13.idcatadeta = P.regimen AND C13.idcatalogo = 17 AND C13.estado = 'A'
LEFT JOIN catadeta C14 ON C14.idcatadeta = P.eapb AND C14.idcatalogo = 18 AND C14.estado = 'A'
LEFT JOIN catadeta C15 ON C15.idcatadeta = P.sisben AND C15.idcatalogo = 48 AND C15.estado = 'A'
LEFT JOIN catadeta C16 ON C16.idcatadeta = P.pobladifer AND C16.idcatalogo = 178 AND C16.estado = 'A'
LEFT JOIN catadeta C17 ON C17.idcatadeta = P.incluofici AND C17.idcatalogo = 179 AND C17.estado = 'A'
LEFT JOIN catadeta C18 ON C18.idcatadeta = F.tipo_consulta AND C18.idcatalogo = 182 AND C18.estado = 'A'
LEFT JOIN catadeta C19 ON C19.idcatadeta = F.cod_cups AND C19.idcatalogo = 126 AND C19.estado = 'A'
LEFT JOIN catadeta C20 ON C20.idcatadeta = F.final_consul AND C20.idcatalogo = 127 AND C20.estado = 'A'
LEFT JOIN catadeta C21 ON C21.idcatadeta = F.estado_hist AND C21.idcatalogo = 184 AND C21.estado = 'A'
LEFT JOIN usuarios U ON F.usu_creo=U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred1();
	$sql.=whe_date1();

	$tot="SELECT count(*) as total FROM `adm_facturacion` F	LEFT JOIN personas P ON F.documento = P.idpersona AND F.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN catadeta C1 ON C1.idcatadeta = P.sexo AND C1.idcatalogo = 21 AND C1.estado = 'A' LEFT JOIN catadeta C2 ON C2.idcatadeta = P.genero AND C2.idcatalogo = 19 AND C2.estado = 'A' LEFT JOIN catadeta C3 ON C3.idcatadeta = P.oriensexual AND C3.idcatalogo = 49 AND C3.estado = 'A' LEFT JOIN catadeta C4 ON C4.idcatadeta = P.nacionalidad AND C4.idcatalogo = 30 AND C4.estado = 'A' LEFT JOIN catadeta C5 ON C5.idcatadeta = P.estado_civil AND C5.idcatalogo = 47 AND C5.estado = 'A' LEFT JOIN catadeta C6 ON C6.idcatadeta = P.niveduca AND C6.idcatalogo = 180 AND C6.estado = 'A' LEFT JOIN catadeta C7 ON C7.idcatadeta = P.abanesc AND C7.idcatalogo = 181 AND C7.estado = 'A' LEFT JOIN catadeta C8 ON C8.idcatadeta = P.ocupacion AND C8.idcatalogo = 175 AND C8.estado = 'A' LEFT JOIN catadeta C9 ON C9.idcatadeta = P.vinculo_jefe AND C9.idcatalogo = 54 AND C9.estado = 'A' LEFT JOIN catadeta C10 ON C10.idcatadeta = P.etnia AND C10.idcatalogo = 16 AND C10.estado = 'A' LEFT JOIN catadeta C11 ON C11.idcatadeta = P.pueblo AND C11.idcatalogo = 15 AND C11.estado = 'A' LEFT JOIN catadeta C12 ON C12.idcatadeta = P.discapacidad AND C12.idcatalogo = 14 AND C12.estado = 'A' LEFT JOIN catadeta C13 ON C13.idcatadeta = P.regimen AND C13.idcatalogo = 17 AND C13.estado = 'A' LEFT JOIN catadeta C14 ON C14.idcatadeta = P.eapb AND C14.idcatalogo = 18 AND C14.estado = 'A' LEFT JOIN catadeta C15 ON C15.idcatadeta = P.sisben AND C15.idcatalogo = 48 AND C15.estado = 'A' LEFT JOIN catadeta C16 ON C16.idcatadeta = P.pobladifer AND C16.idcatalogo = 178 AND C16.estado = 'A' LEFT JOIN catadeta C17 ON C17.idcatadeta = P.incluofici AND C17.idcatalogo = 179 AND C17.estado = 'A' LEFT JOIN catadeta C18 ON C18.idcatadeta = F.tipo_consulta AND C18.idcatalogo = 182 AND C18.estado = 'A' LEFT JOIN catadeta C19 ON C19.idcatadeta = F.cod_cups AND C19.idcatalogo = 126 AND C19.estado = 'A' LEFT JOIN catadeta C20 ON C20.idcatadeta = F.final_consul AND C20.idcatalogo = 127 AND C20.estado = 'A' LEFT JOIN catadeta C21 ON C21.idcatadeta = F.estado_hist AND C21.idcatalogo = 184 AND C21.estado = 'A' LEFT JOIN usuarios U ON F.usu_creo=U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred1();
	$tot.=whe_date1();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}



function lis_atencion($txt){
	$sql="SELECT G.subred,V.idgeo AS Id_Familiar,V.numfam AS N°_Familia, A.id_factura AS Cod_Admision,
P.tipo_doc,P.idpersona,CONCAT(P.nombre1, ' ', P.nombre2) AS Nombres_Usuario,CONCAT(P.apellido1, ' ', P.apellido2) AS Apellidos_Usuario,P.fecha_nacimiento AS Fecha_Nacimiento,

FN_CATALOGODESC(21,P.sexo) AS Sexo, FN_CATALOGODESC(19,P.genero) AS Genero, FN_CATALOGODESC(49,P.genero) AS Orientacion_Sexual, FN_CATALOGODESC(30,P.nacionalidad) AS Nacionalidad, FN_CATALOGODESC(30,P.estado_civil) AS Estado_Civil, FN_CATALOGODESC(180,P.niveduca) AS Nivel_Educativo, FN_CATALOGODESC(181,P.abanesc) AS Razón_Abandono_Escolar, FN_CATALOGODESC(175,P.ocupacion) AS Ocupacion , FN_CATALOGODESC(54,P.vinculo_jefe) AS Vinculo_Jefe_Hogar, FN_CATALOGODESC(16,P.etnia) AS Etnia,FN_CATALOGODESC(15,P.pueblo) AS Pueblo_Etnia, P.idioma AS Habla_Español_Etnia, FN_CATALOGODESC(14,P.discapacidad) AS Tipo_Discapacidad, FN_CATALOGODESC(17,P.regimen) AS Regimen, FN_CATALOGODESC(18,P.eapb) AS Eapb, FN_CATALOGODESC(48,P.sisben) AS Grupo_Sisben,P.catgosisb AS Categoria_Sisben, FN_CATALOGODESC(178,P.pobladifer) AS Poblacion_Diferencial, FN_CATALOGODESC(179,P.incluofici) AS Población_Inclusion_Oficio,

A.atencion_fechaatencion,FN_CATALOGODESC(182,A.tipo_consulta) AS TIPO_CONSULTA,FN_CATALOGODESC(126,A.atencion_codigocups) AS CODIGO_CUPS,FN_CATALOGODESC(127,A.atencion_finalidadconsulta) AS FINALIDAD_CONSULTA,
A.atencion_peso,A.atencion_talla,A.atencion_sistolica, A.atencion_diastolica,A.atencion_abdominal,A.atencion_brazo,A.dxnutricional,A.signoalarma,
FN_DESC(3,A.diagnostico1) AS DX1,FN_DESC(3,A.diagnostico2) AS DX2,FN_DESC(3,A.diagnostico3) AS DX3,
A.fertil AS '¿Mujer_Edad_Fertil?',A.preconcepcional AS '¿Consulta_Preconsecional?',A.metodo AS '¿Metodo_Planificacion?',FN_CATALOGODESC(129,A.anticonceptivo) AS '¿Cua_Metodo?', A.planificacion AS Planificacion,A.mestruacion AS Fur,
A.vih AS Prueba_VIH,FN_CATALOGODESC(187,A.resul_vih) AS Resultado_VIH,A.hb AS Prueba_HB,FN_CATALOGODESC(188,A.resul_hb) AS Resultado_HB,A.trepo_sifil AS Trepomina_Sifilis,FN_CATALOGODESC(188,A.resul_sifil) AS Resultado_Trepo_Sifilis,A.pru_embarazo AS Prueba_Embarazo,FN_CATALOGODESC(88,A.resul_emba) AS Resultado_Embarazo,
A.atencion_cronico AS '¿Es_Cronico?',A.gestante AS '¿Es_Gestante?',
GE.edadgestacion AS Edad_Gestacional, GE.fechaparto AS Fecha_Probable_Parto, GE.prenatal AS Sem_Inicio_Controles, GE.rpsicosocial AS Riesgo_Psicosocial, GE.robstetrico AS Riesgo_Obstetrico, GE.rtromboembo AS Riesgo_Tromboembolico,GE.rdepresion AS Riesgo_Depresion, GE.sifilisgestacional AS Sifilis_Gestacional,GE.sifiliscongenita AS Sifilis_Congenita,GE.morbilidad AS Morbilidad_Materna_Extrema,GE.hepatitisb AS Hepatitis_B,GE.vih AS Vih,
A.atencion_ordenpsicologia AS Orden_Psicologia,A.atencion_relevo AS Aplica_Relevo,FN_CATALOGODESC(201,A.prioridad) AS Prioridad,FN_CATALOGODESC(203,A.estrategia) AS Estrategia,
A.usu_creo,U.nombre,
U.perfil,A.fecha_create

FROM `eac_atencion` A
LEFT JOIN personas P ON A.atencion_idpersona = P.idpersona AND A.atencion_tipodoc=P.tipo_doc  
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN eac_gestantes GE ON A.atencion_tipodoc=GE.gestantes_tipo_doc AND A.atencion_idpersona=GE.gestantes_documento
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred2();
	$sql.=whe_date2();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `eac_atencion` A LEFT JOIN personas P ON A.atencion_idpersona = P.idpersona AND A.atencion_tipodoc=P.tipo_doc  LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN eac_gestantes GE ON A.atencion_tipodoc=GE.gestantes_tipo_doc AND A.atencion_idpersona=GE.gestantes_documento LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred2();
	$tot.=whe_date2();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_plancueac($txt){
	$sql="SELECT 
G.subred,V.idgeo AS Id_Familiar,V.idviv AS Cod_Familia,V.telefono1 AS Telefono_1,V.telefono2 AS Telefono_2,V.telefono3 AS Telefono_3,
P.tipo_doc,P.idpersona,CONCAT(P.nombre1, ' ', P.nombre2) AS Nombres_Usuario,CONCAT(P.apellido1, ' ', P.apellido2) AS Apellidos_Usuario,P.fecha_nacimiento AS Fecha_Nacimiento,C1.descripcion AS Sexo,
A.atencion_eventointeres,C34.descripcion AS Atencion_Evento,A.atencion_cualevento,A.atencion_sirc,C35.descripcion AS Atencion_RutaSIRC,A.atencion_remision,    C36.descripcion AS Atencion_CualRemision,A.atencion_ordenvacunacion,C37.descripcion AS Atencion_Vacunacion,A.atencion_ordenlaboratorio,C38.descripcion AS Atencion_Laboratorios,A.atencion_ordenmedicamentos,C39.descripcion AS Atencion_Medicamentos,A.atencion_rutacontinuidad,C40.descripcion AS Atencion_Continuidad,    A.atencion_ordenimagenes,A.atencion_ordenpsicologia AS Orden_Psicologia,A.atencion_relevo AS Aplica_Relevo,C41.descripcion AS Prioridad,C42.descripcion AS Estrategia,
A.usu_creo,U.nombre,U.perfil,A.fecha_create
FROM `eac_atencion` A
LEFT JOIN personas P ON A.atencion_idpersona = P.idpersona AND A.atencion_tipodoc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN catadeta C1 ON C1.idcatadeta = P.sexo AND C1.idcatalogo = 21 AND C1.estado = 'A'
LEFT JOIN catadeta C34 ON C34.idcatadeta = A.atencion_evento AND C34.idcatalogo = 134 AND C34.estado = 'A'
LEFT JOIN catadeta C35 ON C35.idcatadeta = A.atencion_rutasirc AND C35.idcatalogo = 131 AND C35.estado = 'A'
LEFT JOIN catadeta C36 ON C36.idcatadeta = A.atencion_cualremision AND C36.idcatalogo = 132 AND C36.estado = 'A'
LEFT JOIN catadeta C37 ON C37.idcatadeta = A.atencion_vacunacion AND C37.idcatalogo = 185 AND C37.estado = 'A'
LEFT JOIN catadeta C38 ON C38.idcatadeta = A.atencion_laboratorios AND C38.idcatalogo = 133 AND C38.estado = 'A'
LEFT JOIN catadeta C39 ON C39.idcatadeta = A.atencion_medicamentos AND C39.idcatalogo = 186 AND C39.estado = 'A'
LEFT JOIN catadeta C40 ON C40.idcatadeta = A.atencion_continuidad AND C40.idcatalogo = 131 AND C40.estado = 'A'
LEFT JOIN catadeta C41 ON C41.idcatadeta = A.prioridad AND C41.idcatalogo = 134 AND C41.estado = 'A'
LEFT JOIN catadeta C42 ON C42.idcatadeta = A.estrategia AND C42.idcatalogo = 203 AND C42.estado = 'A'
LEFT JOIN usuarios U ON A.usu_creo=U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred2();
	$sql.=whe_date2();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `eac_atencion` A LEFT JOIN personas P ON A.atencion_idpersona = P.idpersona AND A.atencion_tipodoc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv	LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN catadeta C1 ON C1.idcatadeta = P.sexo AND C1.idcatalogo = 21 AND C1.estado = 'A'	LEFT JOIN catadeta C34 ON C34.idcatadeta = A.atencion_evento AND C34.idcatalogo = 134 AND C34.estado = 'A'	LEFT JOIN catadeta C35 ON C35.idcatadeta = A.atencion_rutasirc AND C35.idcatalogo = 131 AND C35.estado = 'A'	LEFT JOIN catadeta C36 ON C36.idcatadeta = A.atencion_cualremision AND C36.idcatalogo = 132 AND C36.estado = 'A'	LEFT JOIN catadeta C37 ON C37.idcatadeta = A.atencion_vacunacion AND C37.idcatalogo = 185 AND C37.estado = 'A'	LEFT JOIN catadeta C38 ON C38.idcatadeta = A.atencion_laboratorios AND C38.idcatalogo = 133 AND C38.estado = 'A'	LEFT JOIN catadeta C39 ON C39.idcatadeta = A.atencion_medicamentos AND C39.idcatalogo = 186 AND C39.estado = 'A'	LEFT JOIN catadeta C40 ON C40.idcatadeta = A.atencion_continuidad AND C40.idcatalogo = 131 AND C40.estado = 'A'	LEFT JOIN catadeta C41 ON C41.idcatadeta = A.prioridad AND C41.idcatalogo = 134 AND C41.estado = 'A'	LEFT JOIN catadeta C42 ON C42.idcatadeta = A.estrategia AND C42.idcatalogo = 203 AND C42.estado = 'A'	LEFT JOIN usuarios U ON A.usu_creo=U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred2();
	$tot.=whe_date2();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}


function lis_psico1($txt){
	$sql="SELECT  V.idgeo AS Id_Familiar,V.numfam AS N°_Familia,
P.tipo_doc,P.idpersona,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(19,P.genero) AS GENERO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(16,P.etnia) AS ETNIA,FN_CATALOGODESC(15,P.pueblo) AS PUEBLO,
A.fecha_ses1,A.tipo_caso,A.cod_admin,A.eva_chips,A.psi_validacion1,A.psi_validacion2,A.psi_validacion3,A.psi_validacion4,A.psi_validacion5,A.psi_validacion6,A.psi_validacion7,
A.psi_validacion8,A.psi_validacion9,A.psi_validacion10,A.psi_validacion11,FN_DESC(3,A.psi_diag12) as DX,A.psi_validacion13,A.psi_validacion14,A.otro,A.psi_validacion15,A.numsesi,
A.usu_creo,
A.fecha_create
FROM `psi_psicologia` A
LEFT JOIN personas P ON A.psi_documento = P.idpersona AND A.psi_tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred3();
	$sql.=whe_date3();
	// echo $sql;
	$tot="SELECT  COUNT(*) total FROM `psi_psicologia` A LEFT JOIN personas P ON A.psi_documento = P.idpersona AND A.psi_tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred3();
	$tot.=whe_date3();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_psico2($txt){
	$sql="SELECT V.idgeo AS Id_Familiar,V.numfam AS N°_Familia,
P.tipo_doc,P.idpersona,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(19,P.genero) AS GENERO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(16,P.etnia) AS ETNIA,FN_CATALOGODESC(15,P.pueblo) AS PUEBLO,
A.psi_tipo_doc,A.psi_documento,A.psi_fecha_sesion,A.cod_admin2,A.psi_validacion1,A.psi_validacion2,A.psi_validacion3,A.psi_validacion4,A.psi_validacion5,A.psi_validacion6,A.psi_validacion7,A.psi_validacion8,
A.psi_validacion9,A.psi_validacion10,A.contin_caso,
A.fecha_create,
A.usu_creo
FROM `psi_sesion2` A
LEFT JOIN personas P ON A.psi_documento = P.idpersona AND A.psi_tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred4();
	$sql.=whe_date4();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `psi_sesion2` A LEFT JOIN personas P ON A.psi_documento = P.idpersona AND A.psi_tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred4();
	$tot.=whe_date4();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_psico3($txt){
	$sql="SELECT G.subred,V.idgeo AS Id_Familiar,V.numfam AS N°_Familia,
P.tipo_doc,P.idpersona,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(19,P.genero) AS GENERO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(16,P.etnia) AS ETNIA,FN_CATALOGODESC(15,P.pueblo) AS PUEBLO,
A.psi_fecha_sesion,FN_CATALOGODESC(125,A.psi_sesion) AS N°_Sesion,A.cod_admin4,A.psi_validacion1,A.psi_validacion2,A.psi_validacion3,A.psi_validacion4,A.psi_validacion5,A.difhacer,A.psi_validacion6,A.psi_validacion7,A.psi_validacion8,A.psi_validacion9,
A.psi_validacion10,A.psi_validacion11,A.psi_validacion12,A.psi_validacion13,A.psi_validacion14,A.psi_validacion15,A.psi_validacion16,A.psi_validacion17,
A.fecha_create,A.usu_creo
FROM `psi_sesiones` A
LEFT JOIN personas P ON A.psi_documento = P.idpersona AND A.psi_tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred4();
	$sql.=whe_date4();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `psi_sesiones` A LEFT JOIN personas P ON A.psi_documento = P.idpersona AND A.psi_tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred4();
	$tot.=whe_date4();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_psico4($txt){
	$sql="SELECT G.subred,V.idgeo AS Id_Familiar,V.numfam AS N°_Familia,
P.tipo_doc,P.idpersona,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(19,P.genero) AS GENERO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(16,P.etnia) AS ETNIA,FN_CATALOGODESC(15,P.pueblo) AS PUEBLO,
A.psi_fecha_sesion,A.cod_admisfin,A.zung_ini,A.hamilton_ini,A.whodas_ini,A.psi_validacion1,A.psi_validacion2,A.psi_validacion3,A.psi_validacion4,A.psi_validacion5,A.psi_validacion6,A.psi_validacion7,
A.psi_validacion8,A.psi_validacion9,A.psi_validacion10,A.psi_validacion11,A.psi_validacion12,A.psi_validacion13,A.psi_validacion14,A.psi_validacion15,A.psi_validacion17,A.psi_validacion18,A.psi_validacion19,
A.fecha_create,A.usu_creo
FROM `psi_sesion_fin` A
LEFT JOIN personas P ON A.psi_documento = P.idpersona AND A.psi_tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred4();
	$sql.=whe_date4();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `psi_sesion_fin` A LEFT JOIN personas P ON A.psi_documento = P.idpersona AND A.psi_tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred4();
	$tot.=whe_date4();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_relevo1($txt){
	$sql=" SELECT G.subred,V.idgeo AS Id_Familiar,V.numfam AS N°_Familia,
P.tipo_doc AS Tipo_Documento_Cuidador,P.idpersona AS N°_Documento_Cuidador,concat(P.nombre1,' ',P.nombre2) AS Nombres_Cuidador,concat(P.apellido1,' ',P.apellido2) AS Apellidos_Cuidador,P.fecha_nacimiento AS Fecha_Nacimiento_Cuidador,FN_CATALOGODESC(21,P.sexo) AS Sexo_Cuidador,FN_CATALOGODESC(19,P.genero) AS Genero_Cuidador,FN_CATALOGODESC(49,P.oriensexual) AS Orientacion_Sexual_Cuidador,FN_CATALOGODESC(30,P.nacionalidad) AS Nacionalidad_Cuidador,FN_CATALOGODESC(16,P.etnia) AS Etnia_Cuidador,FN_CATALOGODESC(15,P.pueblo) AS Pueblo_Etnia_Cuidador,FN_CATALOGODESC(17,P.regimen) AS Regimen_Cuidador,FN_CATALOGODESC(18,P.eapb) AS Eapb_Cuidador,FN_CATALOGODESC(178,P.pobladifer) AS Poblacion_Difer_Cuidador,FN_CATALOGODESC(179,P.incluofici) AS Poblacion_Oficio_Cuidador,FN_CATALOGODESC(14,P.discapacidad) AS Tipo_Discapacidad_Cuidador,FN_CATALOGODESC(28,R.rel_validacion1) AS Antecedentes_Cuidador,R.rel_validacion2 AS Otros_Antecedentes_Cuidador,
FN_CATALOGODESC(29,R.rel_validacion3) AS Modalidad,R.rel_validacion4 AS Resultado_Hamilton,R.rel_validacion6 AS Resultado_Zarit,R.rel_validacion9 AS Resultado_Zung,R.rel_validacion12 AS Resultado_Ophi,
P0.tipo_doc AS Tipo_Documento_Pers_cuidada1,P0.idpersona AS N°_Documento_Pers_cuidada1,concat(P0.nombre1,' ',P0.nombre2) AS Nombres_Pers_cuidada1,concat(P0.apellido1,' ',P0.apellido2) AS Apellidos_Pers_cuidada1,P0.fecha_nacimiento AS Fecha_Nacimiento_Pers_cuidada1,FN_CATALOGODESC(21,P0.sexo) AS Sexo_Pers_cuidada1,FN_CATALOGODESC(19,P0.genero) AS Genero_Pers_cuidada1,FN_CATALOGODESC(49,P0.oriensexual) AS Orientacion_Pers_cuidada1,FN_CATALOGODESC(30,P0.nacionalidad) AS Nacionalidad_Pers_cuidada1,FN_CATALOGODESC(16,P0.etnia) AS Etnia_Pers_cuidada1,FN_CATALOGODESC(15,P0.pueblo) AS Pueblo_Etnia_Pers_cuidada1,FN_CATALOGODESC(17,P0.regimen) AS Regimen_Pers_cuidada1,FN_CATALOGODESC(18,P0.eapb) AS Eapb_Pers_cuidada1,FN_CATALOGODESC(178,P0.pobladifer) AS Poblacion_Difer_Pers_cuidada1,FN_CATALOGODESC(179,P0.incluofici) AS Poblacion_Oficio_Pers_cuidada1,FN_CATALOGODESC(28,R.rel_validacion14) AS Antecedentes_Pers_cuidada1,R.rel_validacion15 AS Otros_Antecedentes_Pers_cuidada1,FN_CATALOGODESC(14,R.rel_validacion16) AS Tipo_Discapacidad_Pers_cuidada1 ,FN_CATALOGODESC(189,R.np_cuida) AS N°_personas_alcuidado,
P1.tipo_doc AS Tipo_Documento_Pers_cuidada2,P1.idpersona AS N°_Documento_Pers_cuidada2,concat(P1.nombre1,' ',P1.nombre2) AS Nombres_Pers_cuidada2,concat(P1.apellido1,' ',P1.apellido2) AS Apellidos_Pers_cuidada2,P1.fecha_nacimiento AS Fecha_Nacimiento_Pers_cuidada2,FN_CATALOGODESC(21,P1.sexo) AS Sexo_Pers_cuidada2,FN_CATALOGODESC(19,P1.genero) AS Genero_Pers_cuidada2,FN_CATALOGODESC(49,P1.oriensexual) AS Orientacion_Pers_cuidada2,FN_CATALOGODESC(30,P1.nacionalidad) AS Nacionalidad_Pers_cuidada2,FN_CATALOGODESC(16,P1.etnia) AS Etnia_Pers_cuidada2,FN_CATALOGODESC(15,P1.pueblo) AS Pueblo_Etnia_Pers_cuidada2,FN_CATALOGODESC(17,P1.regimen) AS Regimen_Pers_cuidada2,FN_CATALOGODESC(18,P1.eapb) AS Eapb_Pers_cuidada2,FN_CATALOGODESC(178,P1.pobladifer) AS Poblacion_Difer_Pers_cuidada2,FN_CATALOGODESC(179,P1.incluofici) AS Poblacion_Oficio_Pers_cuidada2,FN_CATALOGODESC(28,R.antecedentes_2) AS Antecedentes_Pers_cuidada2,R.otro_2 AS Otros_Antecedentes_Pers_cuidada2,FN_CATALOGODESC(14,R.discapacidad_2) AS Tipo_Discapacidad_Pers_cuidada2,
P2.tipo_doc AS Tipo_Documento_Pers_cuidada3,
P2.idpersona AS N°_Documento_Pers_cuidada3,
concat(P2.nombre1,' ',P2.nombre2) AS Nombres_Pers_cuidada3,
concat(P2.apellido1,' ',P2.apellido2) AS Apellidos_Pers_cuidada3,
P2.fecha_nacimiento AS Fecha_Nacimiento_Pers_cuidada3,
FN_CATALOGODESC(21,P2.sexo) AS Sexo_Pers_cuidada3,
FN_CATALOGODESC(19,P2.genero) AS Genero_Pers_cuidada3,
FN_CATALOGODESC(49,P2.oriensexual) AS Orientacion_Pers_cuidada3,
FN_CATALOGODESC(30,P2.nacionalidad) AS Nacionalidad_Pers_cuidada3,
FN_CATALOGODESC(16,P2.etnia) AS Etnia_Pers_cuidada3,
FN_CATALOGODESC(15,P2.pueblo) AS Pueblo_Etnia_Pers_cuidada3,
FN_CATALOGODESC(17,P2.regimen) AS Regimen_Pers_cuidada3,
FN_CATALOGODESC(18,P2.eapb) AS Eapb_Pers_cuidada3,
FN_CATALOGODESC(178,P2.pobladifer) AS Poblacion_Difer_Pers_cuidada3,
FN_CATALOGODESC(179,P2.incluofici) AS Poblacion_Oficio_Pers_cuidada3,
FN_CATALOGODESC(28,R.antecedentes_3) AS Antecedentes_Pers_cuidada3,
R.otro_3 AS Otros_Antecedentes_Pers_cuidada3,FN_CATALOGODESC(14,R.discapacidad_3) AS Tipo_Discapacidad_Pers_cuidada3,
FN_CATALOGODESC(170,R.rel_validacion17) AS Aceptacion_Relevos,R.rel_validacion18 AS Fecha_Identificacion,
R.usu_creo,U.nombre,U.perfil,R.fecha_create
FROM `rel_relevo` R
LEFT JOIN personas P ON R.rel_documento = P.idpersona
LEFT JOIN personas P0 ON R.rel_validacion13 = P0.idpersona
LEFT JOIN personas P1 ON R.cuidado_2 = P1.idpersona
LEFT JOIN personas P2 ON R.cuidado_3 = P2.idpersona
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON R.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	// echo $sql;
	$tot=" SELECT COUNT(*) total FROM `rel_relevo` R LEFT JOIN personas P ON R.rel_documento = P.idpersona LEFT JOIN personas P0 ON R.rel_validacion13 = P0.idpersona LEFT JOIN personas P1 ON R.cuidado_2 = P1.idpersona LEFT JOIN personas P2 ON R.cuidado_3 = P2.idpersona LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON R.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred();
	$tot.=whe_date();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_relevo2($txt){
	$sql="SELECT G.subred,V.idgeo AS Id_Familiar,V.numfam AS N°_Familia,
R.rel_tipo_doc,R.rel_documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,
FN_CATALOGODESC(32,R.rel_validacion1) AS N°_Sesion,R.rel_validacion2 AS Fecha_Sesion,R.rel_validacion3 AS Perfil_Sesion,FN_CATALOGODESC(301,R.rel_validacion4) AS Actividad_Respiro,R.rel_validacion5 AS Descripcion_Intervencion,
FN_CATALOGODESC(103,R.autocuidado) AS Autocuidado,FN_CATALOGODESC(194,R.activesparc) AS Actividades_Esparcimiento,FN_CATALOGODESC(157,R.infeducom) AS Inf_Educa_Comuni_salud,
R.fecha_create,R.usu_creo
 FROM `rel_sesion` R
 LEFT JOIN personas P ON R.rel_documento = P.idpersona 
 LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
 LEFT JOIN hog_geo G ON V.idpre = G.idgeo  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `rel_sesion` R	 LEFT JOIN personas P ON R.rel_documento = P.idpersona LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred();
	$tot.=whe_date();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_ruteo($txt){
	$sql="SELECT R.id_ruteo AS Cod_Ruteo, FN_CATALOGODESC(33,R.fuente) AS Fuente, R.fecha_asig AS Fecha_Asignado, FN_CATALOGODESC(191,R.priorizacion) AS Priorizacion, R.tipo_doc AS Tipo_Documento, R.documento AS N°_Documento, R.nombres AS Nombres_Apellidos, R.fecha_nac AS Fecha_Nacimiento, FN_CATALOGODESC(21,R.sexo) AS Sexo, FN_CATALOGODESC(30,R.nacionalidad) AS Nacionalidad, R.tipo_doc_acu AS Tipo_Documento_Acudiente, R.documento_acu AS N°_Documento_Acudiente, R.nombres_acu AS Nombres_Apellidos_Acudiente, R.direccion AS Direccion, R.telefono1 AS Telefono_1, R.telefono2 AS Telefono_2, R.telefono3 AS Telefono_3, FN_CATALOGODESC(72,R.subred) AS Subred, FN_CATALOGODESC(2,R.localidad) AS Localidad, FN_CATALOGODESC(7,R.upz) AS Upz, FN_CATALOGODESC(20,R.barrio) AS Barrio, R.sector_catastral AS Sector_Catastral, R.nummanzana AS N°_Manzana, R.predio_num AS N°_Predio, R.unidad_habit AS Unidad_Habitacional, R.cordx AS Coordenada_X, R.cordy AS Coordenada_Y, R.perfil_asignado AS Perfil_Asignado, R.fecha_gestion AS Fecha_Gestion, FN_CATALOGODESC(35,R.estado_g) AS Estado_Gestion, FN_CATALOGODESC(36,R.motivo_estado) AS Motivo_Estado_Gestion, R.direccion_nueva AS Direccion_Nueva, R.complemento AS Complementos_Direccion, R.observacion AS Observacion_Gestion, NULL AS Estado_Atencion, Null AS N°_Integrantes_Fam,P.vivipersona AS Cod_Familia, A.atencion_fechaatencion AS Fecha_Consulta, FN_CATALOGODESC(182,A.tipo_consulta) AS Tipo_Consulta, FN_CATALOGODESC(127,A.atencion_finalidadconsulta) AS Finalidad_Consulta, FN_CATALOGODESC(17,P.regimen) AS Regimen, FN_CATALOGODESC(18,P.eapb) AS Eapb, P.afiliaoficio AS Afiliacon_por_Oficio, NULL AS Observacion_Consulta FROM `eac_ruteo` R LEFT JOIN eac_atencion A ON R.tipo_doc=A.atencion_tipodoc AND R.documento=A.atencion_idpersona AND R.estrategia=A.estrategia LEFT JOIN personas P ON A.atencion_tipodoc=P.tipo_doc AND A.atencion_idpersona=P.idpersona  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred13();
	$sql.=whe_date13();
	// echo $sql;
	$tot="SELECT COUNT(*) total	FROM `eac_ruteo` R LEFT JOIN eac_atencion A ON R.tipo_doc=A.atencion_tipodoc AND R.documento=A.atencion_idpersona AND R.estrategia=A.estrategia	LEFT JOIN personas P ON A.atencion_tipodoc=P.tipo_doc AND A.atencion_idpersona=P.idpersona  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred13();
	$tot.=whe_date13();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_caracter($txt){
	$sql="SELECT G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,V.fecha AS FECHA_CARACTERIZACION,FN_CATALOGODESC(165,V.estado_aux) AS ESTADO, V.fechaupd AS Fecha_Actualizacion, FN_CATALOGODESC(215,V.motivoupd) AS Motivo_Actualizacion, FN_CATALOGODESC(87,V.eventoupd) AS Evento_Actualizacion, V.fechanot AS Fecha_Notificacion,concat(V.complemento1,' ',V.nuc1,' ',V.complemento2,' ',V.nuc2,' ',V.complemento3,' ',V.nuc3) AS COMPLEMENTOS,
V.telefono1 AS TELEFONO_1,V.telefono2 AS TELEFONO_2,V.telefono3 AS TELEFONO_3,
FN_CATALOGODESC(166,V.crit_epi) AS CRITERIO_EPIDE,FN_CATALOGODESC(167,V.crit_geo) AS CRITERIO_GEO,FN_CATALOGODESC(168,V.estr_inters) AS ESTRATEGIAS_INTERSEC,FN_CATALOGODESC(169,V.fam_peretn) AS FAM_PERTEN_ETNICA,FN_CATALOGODESC(170,V.fam_rurcer) AS FAMILIAS_RURALIDAD_CER,
FN_CATALOGODESC(4,V.tipo_vivienda) AS TIPO_VIVIENDA,FN_CATALOGODESC(8,V.tendencia) AS TENENCIA_VIVIENDA,V.dormitorios AS DORMITORIOS,V.actividad_economica AS USO_ACTIVIDAD_ECONO, FN_CATALOGODESC(10,V.tipo_familia) AS TIPO_FAMILIA, V.personas AS N°_PERSONAS, FN_CATALOGODESC(13,V.ingreso) AS INGRESO_ECONOMICO_FAM,
V.seg_pre1 AS SEGURIDAD_ALIMEN_PREG1,V.seg_pre2 AS SEGURIDAD_ALIMEN_PREG2,V.seg_pre3 AS SEGURIDAD_ALIMEN_PREG3,V.seg_pre4 AS SEGURIDAD_ALIMEN_PREG4,V.seg_pre5 AS SEGURIDAD_ALIMEN_PREG5,V.seg_pre6 AS SEGURIDAD_ALIMEN_PREG6,V.seg_pre7 AS SEGURIDAD_ALIMEN_PREG7,V.seg_pre8 AS SEGURIDAD_ALIMEN_PREG8,
V.subsidio_1 AS SUBSIDIO_SDIS1,V.subsidio_2 AS SUBSIDIO_SDIS2,V.subsidio_3 AS SUBSIDIO_SDIS3,V.subsidio_4 AS SUBSIDIO_SDIS4,V.subsidio_5 AS SUBSIDIO_SDIS5,V.subsidio_6 AS SUBSIDIO_SDIS6,V.subsidio_7 AS SUBSIDIO_SDIS7,V.subsidio_8 AS SUBSIDIO_SDIS8,V.subsidio_9 AS SUBSIDIO_SDIS9,
V.subsidio_10 AS SUBSIDIO_SDIS10,V.subsidio_11 AS SUBSIDIO_SDIS11,V.subsidio_12 AS SUBSIDIO_SDIS12,V.subsidio_13 AS SUBSIDIO_ICBF1,V.subsidio_14 AS SUBSIDIO_ICBF2,V.subsidio_15 AS SUBSIDIO15_SECRE_HABIT,V.subsidio_16 AS SUBSIDIO_CONSEJERIA,V.subsidio_17 AS SUBSIDIO_ONGS, V.subsidio_18 AS SUBSIDIO_FAMILIAS_ACCION,V.subsidio_19 AS SUBSIDIO_RED_UNIDOS,V.subsidio_20 AS SUBSIDIO_SECADE,
V.energia AS SERVICIO_ENERGIA,V.gas AS SERVICIO_GAS_NATURAL,V.acueducto AS SERVICIO_ACUEDUCTO,V.alcantarillado AS SERVICIO_ALCANTAR,V.basuras AS SERVICIO_BASURAS,V.pozo AS POZO,V.aljibe AS ALJIBE,
V.perros AS ANIMALES_PERROS,V.numero_perros AS N°_PERROS,V.perro_vacunas AS N°_PERROS_NOVACU,V.perro_esterilizado AS N°_PERROS_NOESTER,V.gatos AS ANIMALES_GATOS,V.numero_gatos AS N°_GATOS,V.gato_vacunas AS N°_GATOS_NOVACU,V.gato_esterilizado AS N°_GATOS_NOESTER,V.otros AS OTROS_ANIMALES,V.facamb1 AS FACTORES_AMBIEN_PRE1,V.facamb2 AS FACTORES_AMBIEN_PRE2,V.facamb3 AS FACTORES_AMBIEN_PRE3,V.facamb4 AS FACTORES_AMBIEN_PRE4,V.facamb5 AS FACTORES_AMBIEN_PRE5,V.facamb6 AS FACTORES_AMBIEN_PRE6,V.facamb7 AS FACTORES_AMBIEN_PRE7,V.facamb8 AS FACTORES_AMBIEN_PRE8,V.facamb9 AS FACTORES_AMBIEN_PRE9,V.observacion AS OBSERVACIONES,V.usu_creo AS Cod_Creo,U.nombre AS Nombre_Creo,U.perfil AS Perfil_Creo,V.fecha_create,V.usu_update AS Cod_Edito,U1.nombre AS Nombre_Edito,U1.perfil AS Perfil_Edito, V.fecha_update
FROM `hog_viv` V
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON V.usu_creo = U.id_usuario
LEFT JOIN usuarios U1 ON V.usu_update = U1.id_usuario  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred8();
	$sql.=whe_date8();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `hog_viv` V LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON V.usu_creo = U.id_usuario	LEFT JOIN usuarios U1 ON V.usu_update = U1.id_usuario  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred8();
	$tot.=whe_date8();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}


function lis_placuid($txt){
	$sql="SELECT 
G.subred,G.localidad AS Localidad,G.territorio AS Territorio,V.idviv AS Cod_Familia,V.idgeo AS Id_Familiar ,
A.fecha AS Fecha_Caracterizacion,
FN_CATALOGODESC(22,A.accion1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descipcion_Accion1,
FN_CATALOGODESC(22,A.accion2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descipcion_Accion2,
FN_CATALOGODESC(22,A.accion3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descipcion_Accion3,
FN_CATALOGODESC(22,A.accion4) AS Accion_4,FN_CATALOGODESC(75,A.desc_accion4) AS Descipcion_Accion4,
A.observacion AS Obervaciones,
A.usu_creo AS Usuario_Creo,A.fecha_create AS Fecha_Creacion,
C.compromiso AS Compromisos,
FN_CATALOGODESC(26,C.equipo) AS Equipo,
C.cumple AS Cumple_Compromiso,
C.fecha_create AS Fecha_Creacion_Compromiso,
C.usu_creo AS Usuario_Creo_Compromiso
FROM `hog_planconc` C
LEFT JOIN hog_plancuid A ON C.idviv=A.idviv
LEFT JOIN hog_viv V ON C.idviv = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred9();
	$sql.=whe_date9();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `hog_planconc` C LEFT JOIN hog_plancuid A ON C.idviv=A.idviv LEFT JOIN hog_viv V ON C.idviv = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred9();
	$tot.=whe_date9();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_alertas($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS Id_Familiar,A.idmedidas AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento, P.idpersona AS N°_Documento, P.nombre1 AS Primer_Nombre, P.nombre2 AS Segundo_Nombre, P.apellido1 AS Primer_Apellido, P.apellido2 AS Seundo_Apellido, P.fecha_nacimiento AS Fecha_Nacimiento, FN_CATALOGODESC(176,A.cursovida) AS Curso_Vida, FN_CATALOGODESC(21,P.sexo) AS Sexo, FN_CATALOGODESC(19,P.genero) AS Genero, FN_CATALOGODESC(49,P.oriensexual) AS Orientacion_Sexual, FN_CATALOGODESC(30,P.nacionalidad) AS Nacionalidad ,FN_CATALOGODESC(16,P.etnia) AS ETNIA, FN_CATALOGODESC(15,P.pueblo) AS Pueblo, P.idioma AS Habla_Español, FN_CATALOGODESC(178,P.pobladifer) AS Poblacion_Diferencial, FN_CATALOGODESC(14,P.discapacidad) AS Tipo_Discapacidad, FN_CATALOGODESC(54,P.vinculo_jefe) AS Vinculo_Jefe_Hogar, FN_CATALOGODESC(175,P.ocupacion) AS Ocupacion,FN_CATALOGODESC(17,P.regimen) AS Regimen, FN_CATALOGODESC(18,P.eapb) AS Eapb, P.afiliaoficio AS Afiliacon_por_Oficio, FN_CATALOGODESC(180,P.niveduca) AS Nivel_Educativo, P.abanesc AS Razón_Abandono_Escolar, P.tiemdesem AS Tiempo_Desempleo,

P.cuidador AS Persona_Cuidadora, P.tiempo_cuidador AS Tiempo_Cuidador, FN_CATALOGODESC(67,P.cuidador_unidad) AS Unidad_Medida_Tiempo_Cuidador, FN_CATALOGODESC(54,P.vinculo) AS Vinculo_Persona_Cuidada, P.localidad_vive AS En_Qué_Localidad_Vive, FN_CATALOGODESC(25,P.transporta) AS En_Que_Se_Transporta, P.tiempo_descanso AS Cada_Cuánto_Descansa, FN_CATALOGODESC(67,P.descanso_unidad) AS Unidad_Medida_Tiempo_Descanso,

A.fecha AS FECHA, FN_CATALOGODESC(34,A.tipo) AS TIPO_IDENTIFICACION,FN_CATALOGODESC(166,A.crit_epi) AS CRITERIO_EPIDE,FN_CATALOGODESC(176,A.cursovida) AS CURSO_DE_VIDA,FN_CATALOGODESC(170,A.gestante) AS GESTANTE,FN_CATALOGODESC(177,A.etapgest
) AS ETAPA_GESTACIONAL,FN_CATALOGODESC(170,A.cronico) AS CRONICO,A.alert1 AS ALERTA_CRONICO,A.selmul1 AS OPCIONES_CRONICO,A.alert2 AS ALERTA_ENF_TRANSMI,A.selmul2 AS OPCIONES_TRANSMI,A.alert3 AS ALERTA_NUTRICIONAL,A.selmul3 AS OPCIONES_NUTRICIONAL,A.alert4 AS ALERTA_PSICOSOCIAL,A.selmul4 AS OPCIONES_PSICOSOCIAL,A.alert5 AS ALERTA_INFANCIA,A.selmul5 AS OPCIONES_INFANCIA,A.alert6 AS ALERTA_EN_MUJERES,A.selmul6 AS OPCIONES_EN_MUJERES,A.alert7 AS ALERTAS_DISCAPACIDAD,A.selmul7 AS OPCIONES_DISCAPACIDAD
,A.alert8 AS ALERTAS_COMUNIDAD_ETN,A.selmul8 AS OPCIONES_COM_ETN,A.alert9 AS ALERTA_SALUD_BUCAL,A.selmul9 AS OPCIONES_SALUD_BUCAL,A.codoral AS CLASIFICACION_SO,A.alert10 AS DERIVACION_GENERAL,A.selmul10 AS OPCIONES_DERIVACION,FN_CATALOGODESC(170,A.deriva_eac) AS DERIVACION_EAC,A.asignado_eac AS PROFESIONAL_EAC,FN_CATALOGODESC(170,A.deriva_pf) AS DERIVACION_PF,A.evento_pf AS EVENTO_PF,
A.peso AS PESO,A.talla AS TALLA,A.imc AS IMC,A.tas AS TENSION_SISTOLICA,A.tad AS TENSION_DIASTOLICA,A.glucometria AS GLUCOMETRIA,A.perime_braq AS PERIMETRO_BRAQUIAL,A.percentil AS PERCENTIL,A.zscore AS ZSCORE,A.usu_creo,A.fecha_create

FROM `personas_datocomp` A
LEFT JOIN personas P ON A.dc_documento = P.idpersona AND A.dc_tipo_doc= P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred9();
	$sql.=whe_date9();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `personas_datocomp` A LEFT JOIN personas P ON A.dc_documento = P.idpersona AND A.dc_tipo_doc= P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred9();
	$tot.=whe_date9();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_ambiente($txt){
	$sql="SELECT 
G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,
A.fecha AS Fecha_Seguimiento,FN_CATALOGODESC(34,A.tipo_activi) AS Tipo_Seguimiento,
A.seguro AS seguro,A.grietas AS grietas,A.combustible AS combustible,A.separadas AS separadas,A.lena AS lena,A.ilumina AS ilumina,A.fuma AS fuma,A.bano AS bano,A.cocina AS cocina,
A.elevado AS elevado,A.electrica AS electrica,A.elementos AS elementos,A.barreras AS barreras,A.zontrabajo AS zontrabajo,A.agua AS agua,A.tanques AS tanques,A.adecagua AS adecagua,
A.raciagua AS raciagua,A.sanitari AS sanitari,A.aguaresid AS aguaresid,A.terraza AS terraza,A.recipientes AS recipientes,A.vivaseada AS vivaseada,A.separesiduos AS separesiduos,A.reutresiduos AS reutresiduos,
A.noresiduos AS noresiduos,A.adecresiduos AS adecresiduos,A.horaresiduos AS horaresiduos,A.plagas AS plagas,A.contplagas AS contplagas,A.pracsanitar AS pracsanitar,A.envaplaguicid AS envaplaguicid,A.consealiment AS consealiment,A.limpcocina AS limpcocina,A.cuidcuerpo AS cuidcuerpo,A.fechvencim AS fechvencim,A.limputensilios AS limputensilios,A.adqualime AS adqualime,A.almaquimicos AS almaquimicos,A.etiqprodu AS etiqprodu,A.juguetes AS juguetes,A.medicamalma AS medicamalma,A.medicvenc AS medicvenc,A.adqumedicam AS adqumedicam,A.medidaspp AS medidaspp,A.radiacion AS radiacion,A.contamaire AS contamaire,A.monoxido AS monoxido,A.residelectri AS residelectri,A.duermeelectri AS duermeelectri,A.vacunasmascot AS vacunasmascot,A.aseamascot AS aseamascot,A.alojmascot AS alojmascot,A.excrmascot AS excrmascot,A.permmascot AS permmascot,A.salumascot AS salumascot,A.pilas AS pilas,A.dispmedicamentos AS dispmedicamentos,A.dispcompu AS dispcompu,A.dispplamo AS dispplamo,A.dispbombill AS dispbombill,A.displlanta AS displlanta,
A.dispplaguic AS dispplaguic,A.dispaceite AS dispaceite,
A.fecha_create Fecha_Creacion,U.nombre AS Nombre_Creo,U.perfil AS Perfil
 FROM `hog_amb` A
LEFT JOIN hog_viv V ON A.idvivamb = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred9();
	$sql.=whe_date9();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `hog_amb` A LEFT JOIN hog_viv V ON A.idvivamb = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred9();
	$tot.=whe_date9();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_apgar($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_apgar AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,CONCAT(P.nombre1, ' ', P.nombre2) AS Nombres_Usuario,CONCAT(P.apellido1, ' ', P.apellido2) AS Apellidos_Usuario,P.fecha_nacimiento AS Fecha_Nacimiento,  C1.descripcion AS Sexo,
C2.descripcion AS Apgar_7_A_17_Años_Preg_1,C3.descripcion AS Apgar_7_A_17_Años_Preg_2,C4.descripcion AS Apgar_7_A_17_Años_Preg_3,C5.descripcion AS Apgar_7_A_17_Años_Preg_4,C6.descripcion AS Apgar_7_A_17_Años_Preg_5,
C7.descripcion AS Apgar_Mayor_de_18_Años_Preg_1,C8.descripcion AS Apgar_Mayor_de_18_Años_Preg_2,C9.descripcion AS Apgar_Mayor_de_18_Años_Preg_3,C10.descripcion AS Apgar_Mayor_de_18_Años_Preg_4,C11.descripcion AS Apgar_Mayor_de_18_Años_Preg_5,
A.puntaje,A.descripcion,
A.usu_creo AS Cod_Usuario_Creo,U1.nombre AS Nombre_Creo,U1.perfil AS Perfil_Creo,A.fecha_create AS Fecha_Creacion
FROM `hog_tam_apgar` A
LEFT JOIN personas P ON A.idpersona = P.idpersona AND A.tipodoc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN catadeta C1 ON C1.idcatadeta = P.sexo AND C1.idcatalogo = 21 AND C1.estado = 'A'
LEFT JOIN catadeta C2 ON C2.idcatadeta = A.ayuda_fam AND C2.idcatalogo = 37 AND C2.estado = 'A'
LEFT JOIN catadeta C3 ON C3.idcatadeta = A.fam_comprobl AND C3.idcatalogo = 37 AND C3.estado = 'A'
LEFT JOIN catadeta C4 ON C4.idcatadeta = A.fam_percosnue AND C4.idcatalogo = 37 AND C4.estado = 'A'
LEFT JOIN catadeta C5 ON C5.idcatadeta = A.fam_feltrienf AND C5.idcatalogo = 37 AND C5.estado = 'A'
LEFT JOIN catadeta C6 ON C6.idcatadeta = A.fam_comptiemjun AND C6.idcatalogo = 37 AND C6.estado = 'A'
LEFT JOIN catadeta C7 ON C7.idcatadeta = A.sati_famayu AND C7.idcatalogo = 173 AND C7.estado = 'A'
LEFT JOIN catadeta C8 ON C8.idcatadeta = A.sati_famcompro AND C8.idcatalogo = 173 AND C8.estado = 'A'
LEFT JOIN catadeta C9 ON C9.idcatadeta = A.sati_famapoemp AND C9.idcatalogo = 173 AND C9.estado = 'A'
LEFT JOIN catadeta C10 ON C10.idcatadeta = A.sati_famemosion AND C10.idcatalogo = 173 AND C10.estado = 'A'
LEFT JOIN catadeta C11 ON C11.idcatadeta = A.sati_famcompar AND C11.idcatalogo = 173 AND C11.estado = 'A'
LEFT JOIN usuarios U1 ON A.usu_creo = U1.id_usuario
  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred10();
	$sql.=whe_date10();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `hog_tam_apgar` A LEFT JOIN personas P ON A.idpersona = P.idpersona AND A.tipodoc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN catadeta C1 ON C1.idcatadeta = P.sexo AND C1.idcatalogo = 21 AND C1.estado = 'A' LEFT JOIN catadeta C2 ON C2.idcatadeta = A.ayuda_fam AND C2.idcatalogo = 37 AND C2.estado = 'A' LEFT JOIN catadeta C3 ON C3.idcatadeta = A.fam_comprobl AND C3.idcatalogo = 37 AND C3.estado = 'A' LEFT JOIN catadeta C4 ON C4.idcatadeta = A.fam_percosnue AND C4.idcatalogo = 37 AND C4.estado = 'A' LEFT JOIN catadeta C5 ON C5.idcatadeta = A.fam_feltrienf AND C5.idcatalogo = 37 AND C5.estado = 'A' LEFT JOIN catadeta C6 ON C6.idcatadeta = A.fam_comptiemjun AND C6.idcatalogo = 37 AND C6.estado = 'A' LEFT JOIN catadeta C7 ON C7.idcatadeta = A.sati_famayu AND C7.idcatalogo = 173 AND C7.estado = 'A' LEFT JOIN catadeta C8 ON C8.idcatadeta = A.sati_famcompro AND C8.idcatalogo = 173 AND C8.estado = 'A' LEFT JOIN catadeta C9 ON C9.idcatadeta = A.sati_famapoemp AND C9.idcatalogo = 173 AND C9.estado = 'A' LEFT JOIN catadeta C10 ON C10.idcatadeta = A.sati_famemosion AND C10.idcatalogo = 173 AND C10.estado = 'A' LEFT JOIN catadeta C11 ON C11.idcatadeta = A.sati_famcompar AND C11.idcatalogo = 173 AND C11.estado = 'A' LEFT JOIN usuarios U1 ON G.asignado = U1.id_usuario  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred10();
	$tot.=whe_date10();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_findrisc($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_findrisc AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.peso AS Peso,A.talla AS Talla,A.imc AS Imc,A.perimcint AS Perimetro_Cintura,
FN_CATALOGODESC(43,A.actifisica) AS Actividad_Fisica,FN_CATALOGODESC(46,A.verduras) AS Consumo_Verduras_Frutas,FN_CATALOGODESC(56,A.hipertension) AS Toma_Medicamento_Hiper,
FN_CATALOGODESC(57,A.glicemia) AS Valores_Altos_Glucosa,FN_CATALOGODESC(41,A.diabfam) AS Diabetes_Familiares,
A.puntaje AS Puntaje,A.descripcion AS Clasificacion_Puntaje,
A.usu_creo AS Cod_Creo,U.nombre AS Nombre_Creo,U.perfil AS Perfil_Creo,U.equipo As Equipo,A.fecha_create AS Fecha_Creacion

FROM `hog_tam_findrisc` A
LEFT JOIN personas P ON A.idpersona = P.idpersona AND A.tipodoc= P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred10();
	$sql.=whe_date10();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `hog_tam_findrisc` A LEFT JOIN personas P ON A.idpersona = P.idpersona AND A.tipodoc= P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON V.usu_creo = U.id_usuario  WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred10();
	$tot.=whe_date10();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_oms($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.idoms AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

FN_CATALOGODESC(170,A.diabetes) AS Tiene_Diabetes,FN_CATALOGODESC(170,A.fuma) AS Fuma,A.tas AS Tension_Arterial_Sistolica,A.puntaje AS Puntaje,A.descripcion AS Clasificacion_Puntaje,
A.usu_creo AS Cod_Creo,U.nombre AS Nombre_Creo,U.perfil AS Perfil_Creo,U.equipo As Equipo,A.fecha_create AS Fecha_Creacion
FROM `hog_tam_oms` A
LEFT JOIN personas P ON A.idpersona = P.idpersona AND A.tipodoc= P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred10();
	$sql.=whe_date10();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `hog_tam_oms` A LEFT JOIN personas P ON A.idpersona = P.idpersona AND A.tipodoc= P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON V.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred10();
	$tot.=whe_date10();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_acompsic($txt){
	$sql="SELECT 
G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS ID_FAMILIAR,A.id_acompsic AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad,G.territorio AS Territorio,FN_CATALOGODESC(42,G.estrategia) AS Estrategia,V.numfam AS FAMILIA_N°,

P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS NOMBRES,concat(P.apellido1,' ',P.apellido2) AS APELLIDOS,P.fecha_nacimiento AS FECHA_NACIMIENTO,FN_CATALOGODESC(21,P.sexo) AS SEXO,FN_CATALOGODESC(30,P.nacionalidad) AS NACIONALIDAD,FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,

FN_CATALOGODESC(170,A.autocono) AS Preg_1,FN_CATALOGODESC(170,A.cumuni_aser) AS Preg_2,FN_CATALOGODESC(170,A.toma_decis) AS Preg_3,FN_CATALOGODESC(170,A.pensa_crea) AS Preg_4,FN_CATALOGODESC(170,A.manejo_emo) AS Preg_5,FN_CATALOGODESC(170,A.rela_interp) AS Preg_6,FN_CATALOGODESC(170,A.solu_prob) AS Preg_7,FN_CATALOGODESC(170,A.pensa_critico) AS Preg_8,FN_CATALOGODESC(170,A.manejo_tension) AS Preg_9,FN_CATALOGODESC(170,A.empatia) AS Preg_10,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,

FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,


FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,
A.liker_dificul AS Liker_Dificultad,A.liker_emocion AS Liker_Emocion,A.liker_decision AS Liker_Decision,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,
A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina

FROM `vsp_acompsic` A
 
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
LEFT JOIN hog_geo G ON V.idpre = G.idgeo
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred11();
	$sql.=whe_date11();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vsp_acompsic` A LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred11();
	$tot.=whe_date11();
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

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre, FN_CATALOGODESC(78,A.liker_dificul) AS Liker_Dificultad,FN_CATALOGODESC(78,A.liker_emocion) AS Liker_Emocion,FN_CATALOGODESC(78,A.liker_decision) AS Liker_Decision,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina

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
FN_CATALOGODESC(80,A.sem_ges) AS Semanas_Gestacion, FN_CATALOGODESC(170,A.asiste_control) AS Asiste_control_CYD, FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo, FN_CATALOGODESC(170,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva, A.peso AS 'Peso_(Kg)', A.talla AS 'Talla (cm)', FN_CATALOGODESC(96,A.edad_ges) AS Edad_Gestacional, FN_CATALOGODESC(97,A.diag_nutri) AS Dx_Nutricional_Fenton, A.zscore AS Zscore, FN_CATALOGODESC(98,A.clasi_nutri) AS Clasificacion_Nutricional, FN_CATALOGODESC(170,A.gana_peso) AS Evidencia_Ganancia_Peso, FN_CATALOGODESC(99,A.gana_peso_dia) AS Ganancia_Peso_Diaria, FN_CATALOGODESC(170,A.signos_alarma) AS Signos_Alarma, FN_CATALOGODESC(170,A.signos_alarma_seg) AS Signos_Alarma_Seguimiento,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.desc_accion2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_3,FN_CATALOGODESC(75,A.acciones_3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina
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

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina
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
FN_CATALOGODESC(170,A.supera_emocional) AS Supera_Estado_Emocional,FN_CATALOGODESC(170,A.supera_dolor) AS Supera_Valoracion_Dolor,FN_CATALOGODESC(170,A.supera_funcional) AS Supera_Valoracion_Funcional,FN_CATALOGODESC(170,A.supera_educacion) AS Supera_Necesidades_Educacion,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina

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
FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina
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

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina

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

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina
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

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina
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
FN_CATALOGODESC(130,A.cual) AS Cual_Metodo,A.peso_nacer AS Peso_RN_Nacer,FN_CATALOGODESC(170,A.asiste_control) AS Asiste_control_CYD,FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo,
FN_CATALOGODESC(170,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina
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

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina
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

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,users_bina AS Usuarios_Bina

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

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina

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

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,A.mejora_practica AS Mejora_Practicas,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina

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

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,A.fecha_create Fecha_Creacion,U.equipo AS Cod_Bina,A.users_bina AS Usuarios_Bina

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

FN_CATALOGODESC(170,A.asis_ctrpre) AS Asiste_control_Prenatal,FN_CATALOGODESC(170,A.exam_lab) AS Examenes_Laboratorio,FN_CATALOGODESC(170,A.esqu_vacuna) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.cons_micronutr) AS Consume_Micronutrientes,A.fecha_obstetrica AS Fecha_Evento_Obstetrico,FN_CATALOGODESC(137,A.edad_gesta) AS Edad_Gestacional_Evento,FN_CATALOGODESC(193,A.resul_gest) AS Resultado_Gestacion,FN_CATALOGODESC(170,A.meto_fecunda) AS Cuenta_Metodo_Fecundidad,FN_CATALOGODESC(138,A.cual) AS Cual_Metodo,FN_CATALOGODESC(170,A.confir_sificong) AS RN_Confir_Sífilis_Congénita,FN_CATALOGODESC(94,A.resul_ser_recnac) AS Resultado_Serologia_RN,FN_CATALOGODESC(199,A.trata_recnac) AS Tratamiento_RN,FN_CATALOGODESC(70,A.serol_3meses) AS RN_Serologia_3meses,A.fec_conser_1tri2 AS Fecha_Serologia_3meses,FN_CATALOGODESC(94,A.resultado) AS Resultado_Serologia_3meses,FN_CATALOGODESC(170,A.ctrl_serol1t) AS Control_Serologia_1Trimestre,A.fec_conser_1tri1 AS Fecha_Serologia_1Trimestre,FN_CATALOGODESC(94,A.resultado_1) AS Resultado_Serologia_1Trimestre,FN_CATALOGODESC(170,A.ctrl_serol2t) AS Control_Serologia_2Trimestre,A.fec_conser_2tri AS Fecha_Serologia_2Trimestre,FN_CATALOGODESC(94,A.resultado_2) AS Resultado_Serologia_2Trimestre,FN_CATALOGODESC(170,A.ctrl_serol3t) AS Control_Serologia_3Trimestre,A.fec_conser_3tri AS Fecha_Serologia_3Trimestre,FN_CATALOGODESC(94,A.resultado_3) AS Resultado_Serologia_3Trimestre,FN_CATALOGODESC(170,A.initratasif) AS Inicio_Tratamiento_Sifilis_Ges,A.fec_1dos_trages1 AS Fecha_Primera_Dosis,A.fec_2dos_trages1 AS Fecha_Segunda_Dosis,A.fec_3dos_trages1 AS Fecha_Tercera_Dosis,FN_CATALOGODESC(200,A.pri_con_sex) AS Primer_Contacto_Sexual,FN_CATALOGODESC(207,A.initratasif1) AS Contacto_Sexual_Inicia_Tratamiento,A.fec_apl_tra_1dos1 AS Fecha_Primera_Dosis,A.fec_apl_tra_2dos1 AS Fecha_Segunda_Dosis,A.fec_apl_tra_3dos1 AS Fecha_Tercera_Dosis,FN_CATALOGODESC(200,A.seg_con_sex) AS Segundo_Contacto_Sexual,FN_CATALOGODESC(207,A.initratasif2) AS Contacto_Sexual_Inicia_Tratamiento,A.fec_apl_tra_1dos2 AS Fecha_Primera_Dosis,A.fec_apl_tra_2dos2 AS Fecha_Segunda_Dosis,A.fec_apl_tra_3dos2 AS Fecha_Tercera_Dosis,FN_CATALOGODESC(170,A.prese_reinfe) AS Presenta_Reinfeccion,FN_CATALOGODESC(207,A.initratasif3) AS Tratamiento_Reinfeccion,A.fec_1dos_trages2 AS Fecha_Primera_Dosis,A.fec_2dos_trages2 AS Fecha_Segunda_Dosis,A.fec_3dos_trages2 AS Fecha_Tercera_Dosis,
FN_CATALOGODESC(200,A.reinf_1con) AS Primer_Contacto_Sexual,FN_CATALOGODESC(207,A.initratasif4) AS Contacto_Sexual_Inicia_Tratamiento,A.fec_1dos_trapar AS Fecha_Primera_Dosis,A.fec_2dos_trapar AS Fecha_Segunda_Dosis,A.fec_3dos_trapar AS Fecha_Tercera_Dosis,

FN_CATALOGODESC(90,A.estrategia_1) AS Estrategia_Plan_1,FN_CATALOGODESC(90,A.estrategia_2) AS Estrategia_Plan_2,
FN_CATALOGODESC(22,A.acciones_1) AS Accion_1,FN_CATALOGODESC(75,A.desc_accion1) AS Descripcion_Accion_1,
FN_CATALOGODESC(22,A.acciones_2) AS Accion_2,FN_CATALOGODESC(75,A.desc_accion2) AS Descripcion_Accion_2,
FN_CATALOGODESC(22,A.acciones_3) AS Accion_3,FN_CATALOGODESC(75,A.desc_accion3) AS Descripcion_Accion_3,
FN_CATALOGODESC(170,A.activa_ruta) AS Activacion_Ruta,FN_CATALOGODESC(79,A.ruta) AS Ruta,FN_CATALOGODESC(77,A.novedades) AS Novedades,FN_CATALOGODESC(170,A.signos_covid) AS Signos_Sintomas_Covid,A.caso_afirmativo AS Relacione_Cuales,A.otras_condiciones AS Otras_Condiciones,A.observaciones AS Observaciones,

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,
A.users_bina AS Usuarios_Bina

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

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,
A.users_bina AS Usuarios_Bina

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

A.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(76,A.numsegui) AS N°_Seguimiento,FN_CATALOGODESC(87,A.evento) AS Evento,FN_CATALOGODESC(73,A.estado_s) AS Estado,FN_CATALOGODESC(74,A.motivo_estado) AS Motivo_Estado,FN_CATALOGODESC(136,A.etapa) AS Etapa,FN_CATALOGODESC(137,A.sema_gest) AS Semanas_Gestacion_Posevento,

FN_CATALOGODESC(170,A.asis_ctrpre) AS Asiste_control_Prenatal,FN_CATALOGODESC(170,A.exam_lab) AS Examenes_Laboratorio,FN_CATALOGODESC(170,A.esqu_vacuna) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.cons_micronutr) AS Consume_Micronutrientes,A.fecha_obstetrica AS Fecha_Evento_Obstetrico,FN_CATALOGODESC(137,A.edad_gesta) AS Edad_Gestacional_Evento,
FN_CATALOGODESC(193,A.resul_gest) AS Resultado_Gestacion,FN_CATALOGODESC(170,A.meto_fecunda) AS Cuenta_Metodo_Fecundidad,FN_CATALOGODESC(138,A.cual) AS Cual_Metodo,FN_CATALOGODESC(170,A.peso_nacer) AS Peso_RN_Nacer,FN_CATALOGODESC(170,A.asiste_control) AS Asiste_control_CYD,FN_CATALOGODESC(170,A.vacuna_comple) AS Esquema_Vacuna_Completo,FN_CATALOGODESC(170,A.lacmate_exclu) AS Lactancia_Materna_Exclusiva,

FN_CATALOGODESC(170,A.persis_riesgo) AS Persisten_Riesgos_Asociados,FN_CATALOGODESC(170,A.apoy_sector) AS Apoyo_Otro_Sector,FN_CATALOGODESC(89,A.cual_sec) AS Cual_Sector,FN_CATALOGODESC(170,A.tam_cope) AS Aplica_Tamizaje_Cope,FN_CATALOGODESC(140,A.total_afron) AS Cope_Afrontamiento,FN_CATALOGODESC(141,A.total_evita) AS Cope_Evitacion,


                
FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(170,A.aplica_tamiz) AS Aplica_Tamizaje_Cope,FN_CATALOGODESC(78,A.liker_dificul) AS Liker_Dificultades,FN_CATALOGODESC(78,A.liker_emocion) AS Liker_Emociones,FN_CATALOGODESC(78,A.liker_decision) AS Liker_Decisiones,
FN_CATALOGODESC(140,A.cope_afronta) AS Cope_Afrontamiento,FN_CATALOGODESC(141,A.cope_evitacion) AS Cope_Evitacion,FN_CATALOGODESC(142,A.incremen_afron) AS Estrategia_Afrontamiento,FN_CATALOGODESC(143,A.incremen_evita) AS Estrategia_Evitacion,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,
A.users_bina AS Usuarios_Bina
FROM `vsp_violges` A
LEFT JOIN personas P ON A.documento = P.idpersona AND A.tipo_doc = P.tipo_doc
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

FN_CATALOGODESC(170,A.cierre_caso) AS Cierre_de_Caso,FN_CATALOGODESC(198,A.motivo_cierre) AS Motivo_cierre,A.fecha_cierre AS Fecha_Cierre,FN_CATALOGODESC(78,A.liker_dificul) AS Liker_Dificultades,FN_CATALOGODESC(78,A.liker_emocion) AS Liker_Emociones,FN_CATALOGODESC(78,A.liker_decision) AS Liker_Decisiones,FN_CATALOGODESC(170,A.redu_riesgo_cierre) AS Reduccion_de_Riesgo,
A.users_bina AS Usuarios_Bina
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


function lis_vspgeo($txt){
	$sql="SELECT A.idvspgeo AS Cod_Registro, FN_CATALOGODESC(190,A.fuente) AS Fuente_VSP, FN_CATALOGODESC(42,A.estrategia) AS Estrategia, A.subred AS Cod_Subred,FN_CATALOGODESC(72,A.subred) AS Nombre_Subred, A.localidad AS Cod_Localidad, FN_CATALOGODESC(2,A.localidad) AS Nombre_Localidad, A.upz AS Cod_Upz, FN_CATALOGODESC(7,A.upz) AS Nombre_Upz, A.barrio AS Cod_Barrio, FN_CATALOGODESC(20,A.barrio) AS Nombre_Barrio, A.direccion_origen AS Direccion_Origen, A.sector_catastral AS Sector_Catastral, A.nummanzana AS N°_Manzana, A.predio_num AS N°_Predio, A.unidad_habit AS Unidad_Habitacional, CONCAT(A.estrategia, '_', A.sector_catastral, '_', A.nummanzana, '_', A.predio_num, '_', A.unidad_habit, '_', A.estado_v) AS Id_Familiar, A.tipo_doc AS Tipo_Documento, A.documento AS N°_Documento, A.nombres AS Nombre_Apellidos, A.telefono1 AS Telefono_1, A.telefono2 AS Telefono_2, A.telefono3 AS Telefono_3, A.confir_llama AS Confirmacion_Llamada, FN_CATALOGODESC(87,A.evento1) AS Evento_1, FN_CATALOGODESC(87,A.evento2) AS Evento_2, FN_CATALOGODESC(87,A.evento3) AS Evento_3, FN_CATALOGODESC(87,A.evento4) AS Evento_4, A.obs_geo AS Obervaciones_Geograficas, A.obs_gen AS Observaciones_Generales, A.equipo AS Asignado, U.nombre AS Nombre_Asignado,A.estado_v AS Estado, A.usu_creo AS Usuario_Creo, U.nombre AS Nombre_Usuario_Creo 
FROM `vspgeo` A
LEFT JOIN usuarios U ON A.equipo = U.id_usuario
LEFT JOIN usuarios U1 ON A.usu_creo = U1.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred12();
	$sql.=whe_date12();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `vspgeo` A LEFT JOIN usuarios U ON A.equipo = U.id_usuario LEFT JOIN usuarios U1 ON A.usu_creo = U1.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred12();
	$tot.=whe_date12();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_asigrelevo($txt){
	$sql="SELECT G.idgeo Cod_Predio,V.idviv AS Cod_Familia,V.idgeo AS Id_Familiar,P.idpeople AS Cod_Registro,G.subred AS Subred,G.localidad AS Localidad, 

P.tipo_doc AS Tipo_Documento_Cuidador, P.idpersona AS N°_Documento_Cuidador, concat(P.nombre1,' ',P.nombre2) AS Nombres_Cuidador, concat(P.apellido1,' ',P.apellido2) AS Apellidos_Cuidador,P.fecha_nacimiento AS Fecha_Nacimiento_Cuidador, FN_CATALOGODESC(21,P.sexo) AS Sexo_Cuidador, FN_CATALOGODESC(19,P.genero) AS Genero_Cuidador, FN_CATALOGODESC(49,P.oriensexual) AS Orientacion_Sexual_Cuidador, FN_CATALOGODESC(30,P.nacionalidad) AS Nacionalidad_Cuidador, FN_CATALOGODESC(16,P.etnia) AS Etnia_Cuidador, FN_CATALOGODESC(15,P.pueblo) AS Pueblo_Etnia_Cuidador, FN_CATALOGODESC(17,P.regimen) AS Regimen_Cuidador, FN_CATALOGODESC(18,P.eapb) AS Eapb_Cuidador, P.cuidador AS Persona_Cuidadora, P.tiempo_cuidador AS Tiempo_Cuidador, FN_CATALOGODESC(67,P.cuidador_unidad) AS Unidad_Medida_Tiempo_Cuidador,  FN_CATALOGODESC(54,P.vinculo) AS Vinculo_Persona_Cuidada, P.tiempo_descanso AS Cada_Cuánto_Descansa, FN_CATALOGODESC(67,P.descanso_unidad) AS Unidad_Medida_Tiempo_Descanso, P.reside_localidad AS Reside_Localidad, P.localidad_vive AS En_Qué_Localidad_Vive, FN_CATALOGODESC(25,P.transporta) AS En_Que_Se_Transporta, S.estado_cierre AS Estado, S.fecha_create AS Fecha_Asignacion, U.nombre AS Colaborador, U.perfil AS Perfil    
  FROM personas P 
  LEFT JOIN asigrelevo S ON P.idpersona = S.documento AND P.tipo_doc = S.tipo_doc 
  LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
  LEFT JOIN hog_geo G ON V.idpre = G.idgeo
  LEFT JOIN usuarios U ON S.doc_asignado=U.id_usuario WHERE P.cuidador = 'SI' ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred15();
	$sql.=whe_date15();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `personas` P  LEFT JOIN asigrelevo S ON P.idpersona = S.documento AND P.tipo_doc = S.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv LEFT JOIN hog_geo G ON V.idpre = G.idgeo LEFT JOIN usuarios U ON S.doc_asignado=U.id_usuario WHERE P.cuidador = 'SI' ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred15();
	$tot.=whe_date15();
	$_SESSION['sql_'.$txt]=$sql;
	$_SESSION['tot_'.$txt]=$tot;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_adscrip($txt){
	$sql="SELECT U.subred,A.territorio AS Territorio,A.doc_asignado AS Cod_Usuario,U.nombre AS Nombre_Usuario,U.perfil AS Perfil_Usuario FROM `adscrip` A 
LEFT JOIN usuarios U ON A.doc_asignado=U.id_usuario 
WHERE U.perfil <> 'ADM'  ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred14();
	// echo $sql;
	$tot="SELECT COUNT(*) total FROM `adscrip` A LEFT JOIN usuarios U ON A.doc_asignado=U.id_usuario WHERE U.perfil <> 'ADM'  ";
	if (perfilUsu()!=='ADM')	$tot.=whe_subred14();
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
	$sql= " AND date(G.fecha_create) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
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
	$sql= " AND date(F.fecha_create) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
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
