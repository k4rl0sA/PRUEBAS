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
    switch ($_REQUEST['id']) {
        case '1':
			$tab = "geo";
			$encr = encript($tab, $clave);
			if($tab=decript($encr,$clave))lis_geolo($tab);
            break;
        case '2':
            break;
        default:
            break;
    }
}



function lis_geolo($txt){
	$sql="SELECT
    CONCAT_ws(' ',G.estrategia,G.sector_catastral,G.nummanzana,G.predio_num,G.unidad_habit,G.estado_v) AS ID_FAMILIAR,
    G.fecha_create,    U.nombre,    C1.descripcion AS SUBRED,    G.localidad,    C2.descripcion AS LOCALIDAD,    G.upz,    C3.descripcion AS UPZ,    G.zona,    C4.descripcion AS ZONA,    G.sector_catastral,    G.barrio,    C5.descripcion AS BARRIO,    G.nummanzana,    G.predio_num,	G.unidad_habit,    G.direccion,
    G.cordx,    G.cordy,    CONCAT_WS(' ',V.complemento1,V.nuc1,V.complemento2,V.nuc2,V.complemento3,V.nuc3) AS COMPLEMENTOS,    G.direccion_nueva,    G.vereda_nueva,    G.cordxn,    G.cordyn,    G.estrato,    C6.descripcion AS ESTRATEGIA,    C7.descripcion AS ESTADO,    G.usu_creo 
	FROM  hog_geo G LEFT JOIN hog_viv V ON CONCAT(G.estrategia, '_', G.sector_catastral, '_', G.nummanzana, '_', G.predio_num, '_', G.unidad_habit, '_', G.estado_v) = V.idgeo
	LEFT JOIN usuarios U ON G.usu_creo = U.id_usuario LEFT JOIN catadeta C1 ON G.subred = C1.idcatadeta AND C1.idcatalogo = 72 LEFT JOIN catadeta C2 ON G.localidad = C2.idcatadeta AND C2.idcatalogo = 2 LEFT JOIN catadeta C3 ON G.upz = C3.idcatadeta AND C3.idcatalogo = 7 LEFT JOIN catadeta C4 ON G.zona = C4.idcatadeta AND C4.idcatalogo = 3 LEFT JOIN catadeta C5 ON G.barrio = C5.idcatadeta AND C5.idcatalogo = 20 LEFT JOIN catadeta C6 ON G.estrategia = C6.idcatadeta AND C6.idcatalogo = 42 LEFT JOIN catadeta C7 ON G.estado_v = C7.idcatadeta AND C7.idcatalogo = 44;";
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

function whe_data() {
	$hoy=date('Y-m-d');
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	$sql = " INNER JOIN usuarios U ON C.subred= U.subred  ";
	$sql.= " WHERE U.componente IN(SELECT componente from usuarios where id_usuario='".$_SESSION['us_sds']."')";
	$sql.= " AND U.subred in (SELECT subred FROM usuarios where id_usuario='".$_SESSION['us_sds']."')";
	$sql.= " AND date(C.fecha_create) BETWEEN '$ano-$mes-01' AND '$ano-$mes-$dia'";
	return $sql;
}


function cmp_planos(){
	$rta="";
	$hoy=date('d')-1;
	$t=['proceso'=>'','rol'=>'','documento'=>'','usuarios'=>'','descarga'=>'','fecha'=>''];
	$d='';
	if ($d==""){$d=$t;}

	$w='csv';
	$o='infusu';
	$c[]=new cmp($o,'e',null,'DESCARGA DE PLANOS',$w);
	$c[]=new cmp('proceso','s',3,$d['proceso'],$w.' DwL '.$o,'Proceso','proceso',null,'',false,true,'','col-2');
	$c[]=new cmp('fecha','d',10,$d['fecha'],$w.' DwL '.$o,'Fecha','proceso',null,'',false,true,'','col-2',"validDate(this,-$hoy,0)");
	$c[]=new cmp('descarga','t',100,$d['descarga'],$w.' '.$o,'Ultima Descarga','rol',null,'',false,false,'','col-5');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta.="<center><button style='background-color:#4d4eef;border-radius:12px;color:white;padding:12px;text-align:center;cursor:pointer;' type='button' Onclick=\"DownloadCsv('lis','planos','DwL');\">Descargar</button></center>";//DownloadCsv('lis','plano','DwL');setTimeout(csv,100,'geo');
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







function gra_administracion(){
	$rtaF='';
	$id=divide($_POST['id_factura']);
	if(count($id)==4){
		if (isset($_POST['cod_factura']) && $_POST['cod_factura']!='' && isset($_POST['cod_admin'])){
			$estado='F';	
		}else{
			$estado='E';
		}
		// print_r($id);

		$sql1="UPDATE `personas` SET
		regimen=trim(upper('{$_POST['regimen']}')), 
		eapb=trim(upper('{$_POST['eapb']}')),
		usu_update=TRIM(UPPER('{$_SESSION['us_sds']}')),
		fecha_update=DATE_SUB(NOW(), INTERVAL 5 HOUR)
		where idpersona='{$id[0]}' and tipo_doc='{$id[1]}'";
		// echo $sql1;
		$rta1=dato_mysql($sql1);

		if (strpos($rta1, "Correctamente") !== false) {
			$rtaF.= "";
		} else {
			$rtaF.= "Error: No se pudo actualizar el Regimen o la Eapb";
		}	

		$sql="UPDATE `adm_facturacion` SET
	fecha_consulta=trim(upper('{$_POST['fecha_consulta']}')), 
	tipo_consulta=trim(upper('{$_POST['tipo_consulta']}')),	
	`cod_admin`=TRIM(UPPER('{$_POST['cod_admin']}')),
	`cod_cups`=TRIM(UPPER('{$_POST['cod_cups']}')),
	`final_consul`=TRIM(UPPER('{$_POST['final_consul']}')),
	`cod_factura`=TRIM(UPPER('{$_POST['cod_factura']}')),
	`estado_hist`=TRIM(UPPER('{$_POST['estado_hist']}')),
	`tipo_docnew`=TRIM(UPPER('{$_POST['tipo_docnew']}')),
	`documento_new`=TRIM(UPPER('{$_POST['documento_new']}')),
	
	`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
	fecha_update=DATE_SUB(NOW(), INTERVAL 5 HOUR),
	`estado`='{$estado}' WHERE id_factura='{$id[3]}'";
		$rtaF.=dato_mysql($sql);
	}else if(count($id)==3){
		$rtaF.= "NO HA SELECIONADO LA administracion A EDITAR";
	}
	// echo $sql;
  return $rtaF;
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