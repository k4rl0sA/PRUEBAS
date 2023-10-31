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
	$rta="".$_POST." _".$_REQUEST;
	$hoy=date('Y-m-d');
	$t=['tarea'=>'','rol'=>'','documento'=>'','usuarios'=>''];
	$d=get_personas();
	if ($d==""){$d=$t;}
	$w='administracion';
	$o='infusu';
	$c[]=new cmp($o,'e',null,'GESTIÓN DE USUARIOS',$w);
	$c[]=new cmp('tarea','s','20',$d['tarea'],$w.' '.$o,'Acción','tarea',null,'',false,true,'','col-2');
	$c[]=new cmp('rol','s','20',$d['rol'],$w.' '.$o,'Rol','rol',null,'',false,false,'','col-2');
	$c[]=new cmp('documento','t','20',$d['documento'],$w.' '.$o,'N° Documento','documento',null,'',false,true,'','col-2');
	$c[]=new cmp('usuarios','s','20',$d['usuarios'],$w.' '.$o,'Usuarios','usuarios',null,'',false,true,'','col-2');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta.="<center><button style='background-color:#4d4eef;border-radius:12px;color:white;padding:12px;text-align:center;cursor:pointer;' type='button' Onclick=\"consultar('lista_consulta');\">Ejecutar</button></center>";
	return $rta;
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
        default:
            break;
    }
}



function lis_geolo($txt){
	$sql="SELECT CONCAT(G.estrategia, '_', G.sector_catastral, '_', G.nummanzana, '_', G.predio_num, '_', G.unidad_habit, '_', G.estado_v) AS ID_FAMILIAR,
C1.descripcion AS Estrategia,G.subred AS Cod_Subred,C2.descripcion AS Subred,G.zona AS Cod_Zona,C3.descripcion AS Zona,G.localidad AS Cod_Localidad,C4.descripcion AS Localidad,G.upz AS Cod_Upz, C5.descripcion AS Upz,G.barrio AS Cod_Barrio,C6.descripcion AS Barrio,
G.territorio AS Territorio,G.microterritorio AS Manzana_Cuidado,G.sector_catastral AS Sector_Catastral,G.nummanzana AS Numero_Manzana,G.predio_num AS Numero_Predio,G.unidad_habit AS Unidad_Habitacional,
G.direccion AS Direccion,G.vereda AS Vereda,G.cordx AS Coordenada_X,G.cordy AS Coordenda_Y,G.direccion_nueva AS Direccion_Nueva,G.vereda_nueva AS Vereda_Nueva,G.cordxn AS Coordenada_X_Nueva,G.cordyn AS Coordenada_Y_Nueva,G.estrato AS Estrato,G.asignado AS Cod_Usuario_Asignado,U1.nombre AS Usuario_Asignado,G.equipo AS Equipo_Usuario,FN_CATALOGODESC(44,G.estado_v) AS Estado_Visita,FN_CATALOGODESC(5,G.motivo_estado) AS Motivo_Estado,
G.usu_creo,U2.nombre,U2.perfil,G.fecha_create
FROM `hog_geo` G
LEFT JOIN catadeta C1 ON C1.idcatadeta = G.subred AND C1.idcatalogo = 42 AND C1.estado = 'A'
LEFT JOIN catadeta C2 ON C2.idcatadeta = G.subred AND C2.idcatalogo = 72 AND C2.estado = 'A'
LEFT JOIN catadeta C3 ON C3.idcatadeta = G.zona AND C3.idcatalogo = 3 AND C3.estado = 'A'
LEFT JOIN catadeta C4 ON C4.idcatadeta = G.localidad AND C4.idcatalogo = 2 AND C4.estado = 'A'
LEFT JOIN catadeta C5 ON C5.idcatadeta = G.upz AND C5.idcatalogo = 7 AND C5.estado = 'A'
LEFT JOIN catadeta C6 ON C6.idcatadeta = G.barrio AND C6.idcatalogo = 20 AND C6.estado = 'A'
LEFT JOIN usuarios U1 ON G.asignado = U1.id_usuario
LEFT JOIN usuarios U2 ON G.usu_creo = U2.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
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
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}

function lis_atencion($txt){
	$sql="SELECT G.subred,V.idgeo AS Id_Familiar,V.numfam AS N°_Familia,
P.tipo_doc,P.idpersona,CONCAT(P.nombre1, ' ', P.nombre2) AS Nombres_Usuario,CONCAT(P.apellido1, ' ', P.apellido2) AS Apellidos_Usuario,P.fecha_nacimiento AS Fecha_Nacimiento,C1.descripcion AS Sexo,C2.descripcion AS Genero,C3.descripcion AS Orientacion_Sexual,C4.descripcion AS Nacionalidad,C5.descripcion AS Estado_Civil,    C6.descripcion AS Nivel_Educativo,C7.descripcion AS Razon_Abandono_Escolar,C8.descripcion AS Ocupacion,C9.descripcion AS Vinculo_Jefe_Hogar,C10.descripcion AS Etnia,    C11.descripcion AS Pueblo_Etnia,P.idioma AS Habla_Español_Etnia,C12.descripcion AS Tipo_Discapacidad,C13.descripcion AS Regimen,C14.descripcion AS Eapb,C15.descripcion AS Grupo_Sisben,P.catgosisb AS Categoria_Sisben,C16.descripcion AS Poblacion_Diferencial,C17.descripcion AS Poblacion_Por_Oficio,

A.atencion_fechaatencion,FN_CATALOGODESC(182,A.tipo_consulta) AS TIPO_CONSULTA,FN_CATALOGODESC(126,A.atencion_codigocups) AS CODIGO_CUPS,FN_CATALOGODESC(127,A.atencion_finalidadconsulta) AS FINALIDAD_CONSULTA,
A.atencion_peso,A.atencion_talla,A.atencion_sistolica, A.atencion_diastolica,A.atencion_abdominal,A.atencion_brazo,A.dxnutricional,A.signoalarma,
FN_DESC(3,A.diagnostico1) AS DX1,FN_DESC(3,A.diagnostico2) AS DX2,FN_DESC(3,A.diagnostico3) AS DX3,
A.fertil AS '¿Mujer_Edad_Fertil?',A.preconcepcional AS '¿Consulta_Preconsecional?',A.metodo AS '¿Metodo_Planificacion?',FN_CATALOGODESC(129,A.anticonceptivo) AS '¿Cua_Metodo?', A.planificacion AS Planificacion,A.mestruacion AS Fur,
A.vih AS Prueba_VIH,FN_CATALOGODESC(187,A.resul_vih) AS Resultado_VIH,A.hb AS Prueba_HB,FN_CATALOGODESC(188,A.resul_hb) AS Resultado_HB,A.trepo_sifil AS Trepomina_Sifilis,FN_CATALOGODESC(188,A.resul_sifil) AS Resultado_Trepo_Sifilis,A.pru_embarazo AS Prueba_Embarazo,FN_CATALOGODESC(88,A.resul_emba) AS Resultado_Embarazo,
A.atencion_cronico AS '¿Es_Cronico?',A.gestante AS '¿Es_Gestante?',
GE.edadgestacion AS Edad_Gestacional, GE.fechaparto AS Fecha_Ultimo_Parto, GE.prenatal AS Sem_Inicio_Controles, GE.rpsicosocial AS Riesgo_Psicosocial, GE.robstetrico AS Riesgo_Obstetrico, GE.rtromboembo AS Riesgo_Tromboembolico,GE.rdepresion AS Riesgo_Depresion, GE.sifilisgestacional AS Sifilis_Gestacional,GE.sifiliscongenita AS Sifilis_Congenita,GE.morbilidad AS Morbilidad_Materna_Extrema,GE.hepatitisb AS Hepatitis_B,GE.vih AS Vih,
A.atencion_ordenpsicologia AS Orden_Psicologia,A.atencion_relevo AS Aplica_Relevo,FN_CATALOGODESC(201,A.prioridad) AS Prioridad,FN_CATALOGODESC(203,A.estrategia) AS Estrategia,
A.usu_creo,U.nombre,
U.perfil,A.fecha_create
FROM `eac_atencion` A
LEFT JOIN personas P ON A.atencion_idpersona = P.idpersona
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
LEFT JOIN eac_gestantes GE ON A.atencion_tipodoc=GE.gestantes_tipo_doc AND A.atencion_idpersona=GE.gestantes_documento
LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
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
LEFT JOIN catadeta C36 ON C36.idcatadeta = A.atencion_cualremision AND C36.idcatalogo = 131 AND C36.estado = 'A'
LEFT JOIN catadeta C37 ON C37.idcatadeta = A.atencion_vacunacion AND C37.idcatalogo = 185 AND C37.estado = 'A'
LEFT JOIN catadeta C38 ON C38.idcatadeta = A.atencion_laboratorios AND C38.idcatalogo = 133 AND C38.estado = 'A'
LEFT JOIN catadeta C39 ON C39.idcatadeta = A.atencion_medicamentos AND C39.idcatalogo = 186 AND C39.estado = 'A'
LEFT JOIN catadeta C40 ON C40.idcatadeta = A.atencion_continuidad AND C40.idcatalogo = 131 AND C40.estado = 'A'
LEFT JOIN catadeta C41 ON C41.idcatadeta = A.prioridad AND C41.idcatalogo = 134 AND C41.estado = 'A'
LEFT JOIN catadeta C42 ON C42.idcatadeta = A.estrategia AND C42.idcatalogo = 203 AND C42.estado = 'A'
LEFT JOIN usuarios U ON A.usu_creo=U.id_usuario WHERE 1 ";
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
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
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
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
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
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
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
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
	if (perfilUsu()!=='ADM')	$sql.=whe_subred();
	$sql.=whe_date();
	// echo $sql;
	$_SESSION['sql_'.$txt]=$sql;
	$rta = array('type' => 'OK','file'=>$txt);
	echo json_encode($rta);
}


function whe_subred() {
	$sql= " AND G.subred in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	return $sql;
}

function whe_date(){
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql= " AND date(G.fecha_create) BETWEEN '{$_POST['fechad']}' AND '{$_POST['fechah']}'";
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




function cmp_planos(){
	$rta="";
	$hoy=date('d')-1;
	$t=['proceso'=>'','rol'=>'','documento'=>'','usuarios'=>'','descarga'=>'','fechad'=>'','fechah'=>''];
	$d='';
	if ($d==""){$d=$t;}
	$w='csv';
	$o='infusu';
	$c[]=new cmp($o,'e',null,'DESCARGA DE PLANOS',$w);
	$c[]=new cmp('proceso','s',3,$d['proceso'],$w.' DwL '.$o,'Proceso','proceso',null,'',false,true,'','col-2');
	$c[]=new cmp('fechad','d',10,$d['fechad'],$w.' DwL '.$o,'Desde','proceso',null,'',false,true,'','col-2',"validDate(this,-$hoy,0)");
	$c[]=new cmp('fechah','d',10,$d['fechah'],$w.' DwL '.$o,'Hasta','proceso',null,'',false,true,'','col-2',"validDate(this,-$hoy,0)");
	// $c[]=new cmp('descarga','t',100,$d['descarga'],$w.' '.$o,'Ultima Descarga','rol',null,'',false,false,'','col-5');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta.="<center><button style='background-color:#4d4eef;border-radius:12px;color:white;padding:12px;text-align:center;cursor:pointer;' type='button' Onclick=\"DownloadCsv('lis','planos','fapp');grabar('gestion',this);\">Descargar</button></center>";//DownloadCsv('lis','plano','DwL');setTimeout(csv,100,'geo');
	return $rta;
}

function gra_gestion(){
	$rtaF='';
	// $id=divide($_POST['id_factura']);
		// print_r($id);

	$sql="INSERT INTO monitoreo 
	VALUES(NULL,'1',trim(upper('{$_POST['proceso']}')),'','', '', '',TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR))";
	

	/* 
	1=descargar
	2=actualizar
	3=restaurar
	4=crear
	5=rol
	6=adscripcion 
	*/
	
	/* INSERT INTO `personas` SET
		regimen=trim(upper('{$_POST['regimen']}')), 
		eapb=trim(upper('{$_POST['eapb']}')),
		usu_update=TRIM(UPPER('{$_SESSION['us_sds']}')),
		fecha_update=DATE_SUB(NOW(), INTERVAL 5 HOUR)
		where idpersona='{$id[0]}' and tipo_doc='{$id[1]}'";
		// echo $sql; */
		$rta=dato_mysql($sql);
		// echo $sql;
  return $rta;
}


function opc_proceso($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=206 and estado='A' ORDER BY 1",$id);
}

function opc_tarea($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
}

function opc_rol($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
}

function opc_usuarios($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
}


function focus_administracion(){
 return 'administracion';
}


function men_administracion(){
 $rta=cap_menus('administracion','pro');
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
		$rta.="<li class='icono editar ' title='Editar ' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'administracion',event,this,'lib.php');Color('adm-lis');\"></li>";  //act_lista(f,this);
		// $rta.="<li class='icono editar' title='Editar Información de Facturación' id='".$c['ACCIONES']."' Onclick=\"getData('administracion','pro',event,'','lib.php',7);\"></li>"; //setTimeout(hideExpres,1000,'estado_v',['7']);
	}
	
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>