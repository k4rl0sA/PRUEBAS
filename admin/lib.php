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
			$tab = "geo";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_geolo($tab);
            break;
        case '2':
			$tab = "admision";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_admfact($tab);
        default:
            break;
    }
}



function lis_geolo($txt){
	$sql="SELECT CONCAT(G.estrategia, '', G.sector_catastral, '', G.nummanzana, '', G.predio_num, '', G.unidad_habit, '_', G.estado_v) AS ID_FAMILIAR,
	C1.descripcion AS Estrategia,G.subred AS Cod_Subred,C2.descripcion AS Subred,G.zona AS Cod_Zona,C3.descripcion AS Zona,G.localidad AS Cod_Localidad,C4.descripcion AS Localidad,G.upz AS Cod_Upz, C5.descripcion AS Upz,G.barrio AS Cod_Barrio,C6.descripcion AS Barrio,
	G.territorio AS Territorio,G.microterritorio AS Manzana_Cuidado,G.sector_catastral AS Sector_Catastral,G.nummanzana AS Numero_Manzana,G.predio_num AS Numero_Predio,G.unidad_habit AS Unidad_Habitacional,
	G.direccion AS Direccion,G.vereda AS Vereda,G.cordx AS Coordenada_X,G.cordy AS Coordenda_Y,G.direccion_nueva AS Direccion_Nueva,G.vereda_nueva AS Vereda_Nueva,G.cordxn AS Coordenada_X_Nueva,G.cordyn AS Coordenada_Y_Nueva,G.estrato AS Estrato,G.asignado AS Cod_Usuario_Asignado,U1.nombre AS Usuario_Asignado,G.equipo AS Equipo_Usuario,G.estado_v AS Estado_Visita,G.motivo_estado AS Motivo_Estado,
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

function lis_admfact($txt){
	$sql="SELECT CONCAT(G.estrategia, '', G.sector_catastral, '', G.nummanzana, '', G.predio_num, '', G.unidad_habit, '_', G.estado_v) AS ID_FAMILIAR,
	C1.descripcion AS Estrategia,G.subred AS Cod_Subred,C2.descripcion AS Subred,G.zona AS Cod_Zona,C3.descripcion AS Zona,G.localidad AS Cod_Localidad,C4.descripcion AS Localidad,G.upz AS Cod_Upz, C5.descripcion AS Upz,G.barrio AS Cod_Barrio,C6.descripcion AS Barrio,
	G.territorio AS Territorio,G.microterritorio AS Manzana_Cuidado,G.sector_catastral AS Sector_Catastral,G.nummanzana AS Numero_Manzana,G.predio_num AS Numero_Predio,G.unidad_habit AS Unidad_Habitacional,
	G.direccion AS Direccion,G.vereda AS Vereda,G.cordx AS Coordenada_X,G.cordy AS Coordenda_Y,G.direccion_nueva AS Direccion_Nueva,G.vereda_nueva AS Vereda_Nueva,G.cordxn AS Coordenada_X_Nueva,G.cordyn AS Coordenada_Y_Nueva,G.estrato AS Estrato,G.asignado AS Cod_Usuario_Asignado,U1.nombre AS Usuario_Asignado,G.equipo AS Equipo_Usuario,G.estado_v AS Estado_Visita,G.motivo_estado AS Motivo_Estado,
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