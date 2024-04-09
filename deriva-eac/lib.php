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

function lis_derivaeac(){
	$info=datos_mysql("SELECT COUNT(*) total FROM personas_datocomp A LEFT JOIN personas P ON A.dc_documento = P.idpersona AND A.dc_tipo_doc= P.tipo_doc LEFT JOIN hog_viv V ON P.vivipersona = V.idviv	LEFT JOIN hog_geo G ON V.idpre = G.idgeo	LEFT JOIN usuarios U ON A.asignado_eac=U.id_usuario	LEFT JOIN eac_fam E ON V.idviv=E.cod_fam	WHERE A.deriva_eac = 1 AND A.necesidad_eac IS NOT null ".whe_derivaeac());
	$total=$info['responseResult'][0]['total'];
	$regxPag=10;
	$pag=(isset($_POST['pag-derivaeac']))? ($_POST['pag-derivaeac']-1)* $regxPag:0;

	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R, 
	E.id_eacfam ACCIONES,
	G.idgeo Cod_Predio,
	V.idviv Cod_Familia,
	G.territorio Territorio,
	FN_CATALOGODESC(225,A.necesidad_eac) Necesidad,
	U.nombre Colaborador, 
	U.perfil Perfil, 
	FN_CATALOGODESC(44,E.estado_fam) 'Estado', 
	E.fecha_create Rta 
	FROM personas_datocomp A
	LEFT JOIN personas P ON A.dc_documento = P.idpersona AND A.dc_tipo_doc= P.tipo_doc
	LEFT JOIN hog_viv V ON P.vivipersona = V.idviv
	LEFT JOIN hog_geo G ON V.idpre = G.idgeo
	LEFT JOIN usuarios U ON A.asignado_eac=U.id_usuario
	LEFT JOIN eac_fam E ON V.idviv=E.cod_fam
	WHERE A.deriva_eac = 1 AND A.necesidad_eac IS NOT null ";
	$sql.=whe_derivaeac();
	$sql.=" ORDER BY E.fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"derivaeac",$regxPag);
	}

function whe_derivaeac() {
	$sql = "";
	 if ($_POST['fpre'])
		$sql .= " AND G.idgeo = '".$_POST['fpre']."'";
	if ($_POST['ffam'])
		$sql .= " AND V.idviv ='".$_POST['ffam']."' ";
	if($_POST['fdigita']) 
	    $sql .= " AND usu_creo ='".$_POST['fdigita']."'";
	if ($_POST['festado'])
		$sql .= " AND estado_hist ='".$_POST['festado_hist']."' ";
	/*if ($_POST['fpred'])
		$sql .= " AND predio_num ='".$_POST['fpred']."' ";
	if ($_POST['festado'])
		$sql .= " AND estado_v ='".$_POST['festado']."'";
	if (isset($_POST['fdigita'])){
		if($_POST['fdigita']) $sql .= " AND asignado ='".$_POST['fdigita']."'";
	}else{
		$sql .= " AND asignado ='".$_SESSION['us_sds']."'";
	} */
	return $sql;
}


function focus_derivaeac(){
 return 'derivaeac';
}


function men_derivaeac(){
 $rta=cap_menus('derivaeac','pro');
 return $rta;
}


function cap_menus($a,$b='cap',$con='con') {
  $rta = "";
  $acc=rol($a);
  if ($a=='derivaeac'  && isset($acc['crear']) && $acc['crear']=='SI'){  
    $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
    $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
	// $rta .= "<li class='icono $a crear'  title='Actualizar'   id='".print_r($_REQUEST)."'   Onclick=\"\"></li>";
  }
  return $rta;
}

function cmp_derivaeac(){
	$rta="<div class='encabezado adm'>TABLA derivaeac</div>
	<div class='contenido' id='adm-lis'>".lis_adm()."</div></div>";
	$hoy=date('Y-m-d');
	$t=['idpersona'=>'','tipo_doc'=>'','nombre1'=>'','nombre2'=>'','apellido1'=>'','apellido2'=>'','fecha_nacimiento'=>'','sexo'=>'','genero'=>'','nacionalidad'=>'','estado_civil'=>'','niveduca'=>'','ocupacion'=>'','regimen'=>'','eapb'=>'','localidad'=>'','barrio'=>'','direccion'=>'','telefono1'=>'','telefono2'=>'','telefono3'=>''];
	$d=get_personas();
	if ($d==""){$d=$t;}
	$e="";
	$w='derivaeac';
	$o='infusu';
	$c[]=new cmp($o,'e',null,'INFORMACIÓN DEL USUARIO',$w);
	$c[]=new cmp('id_factura','h',15,$_POST['id'],$w.' '.$o,'id','idg',null,'####',false,false);
	$c[]=new cmp('tipo_doc','t','20',$d['tipo_doc'],$w.' '.$o,'Tipo Documento','atencion_tipo_doc',null,'',true,false,'','col-5');
	$c[]=new cmp('documento','t','20',$d['idpersona'],$w.' '.$o,'N° Identificación','atencion_idpersona',null,'',true,false,'','col-5');
	$c[]=new cmp('nombre1','t','20',$d['nombre1'],$w.' '.$o,'primer nombres','nombre1',null,'',false,false,'','col-3');
	$c[]=new cmp('nombre2','t','20',$d['nombre2'],$w.' '.$o,'segundo nombres','nombre2',null,'',false,false,'','col-2');
	$c[]=new cmp('apellido1','t','20',$d['apellido1'],$w.' '.$o,'primer apellido','apellido1',null,'',false,false,'','col-3');
	$c[]=new cmp('apellido2','t','20',$d['apellido2'],$w.' '.$o,'segundo apellido','apellido2',null,'',false,false,'','col-2');
	$c[]=new cmp('fecha_nacimiento','t','20',$d['fecha_nacimiento'],$w.' '.$o,'fecha nacimiento','fecha_nacimiento',null,'',false,false,'','col-3');
	$c[]=new cmp('sexo','s','20',$d['sexo'],$w.' '.$o,'sexo','sexo',null,'',false,false,'','col-2');
	$c[]=new cmp('genero','s','20',$d['genero'],$w.' '.$o,'genero','genero',null,'',false,false,'','col-3');
	$c[]=new cmp('nacionalidad','s','20',$d['nacionalidad'],$w.' '.$o,'Nacionalidad','nacionalidad',null,'',false,false,'','col-2');
	$c[]=new cmp('estado_civil','s','3',$d['estado_civil'],$w.' '.$o,'Estado Civil','estado_civil',null,'',false,false,'','col-15');
	$c[]=new cmp('niveduca','s','3',$d['niveduca'],$w.' '.$o,'Nivel Educativo','niveduca',null,'',false,false,'','col-2');
	$c[]=new cmp('ocupacion','s','3',$d['ocupacion'],$w.' '.$o,'Ocupacion','ocupacion',null,'',false,false,'','col-2');
	$c[]=new cmp('regimen','s','20',$d['regimen'],$w.' '.$o,'Regimen','regimen',null,'',true,true,'','col-2');
	$c[]=new cmp('eapb','s','20',$d['eapb'],$w.' '.$o,'EAPB','eapb',null,'',true,true,'','col-25');
	$c[]=new cmp('localidad','t','20',$d['localidad'],$w.' '.$o,'Localidad','localidad',null,'',false,false,'','col-35');
	$c[]=new cmp('barrio','t','20',$d['barrio'],$w.' '.$o,'Barrio','barrio',null,'',false,false,'','col-35');
	$c[]=new cmp('direccion','t','20',$d['direccion'],$w.' '.$o,'Direccion','direccion',null,'',false,false,'','col-3');
 	$c[]=new cmp('telefono1','n','10',$d['telefono1'],$w.' '.$o,'Telefono 1','telefono1',null,'',false,false,'','col-3');
	$c[]=new cmp('telefono2','n','10',$d['telefono2'],$w.' '.$o,'Telefono 2','telefono2',null,'',false,false,'','col-3');
	$c[]=new cmp('telefono3','n','10',$d['telefono3'],$w.' '.$o,'Telefono 3','telefono3',null,'',false,false,'','col-3');
	
	$o='admfac';
	$c[]=new cmp($o,'e',null,'ADMISIÓN Y FACTURACIÓN',$w);
	$c[]=new cmp('fecha_consulta','d',20,$e,$w.' '.$o,'Fecha de la consulta','fecha_consulta',null,'',true,true,'','col-15','validDate(this,-140,0)');
	$c[]=new cmp('tipo_consulta','s',3,$e,$w.' '.$o,'Tipo de Consulta','tipo_consulta',null,'',true,true,'','col-15');
	$c[]=new cmp('cod_cups','s','3',$e,$w.' '.$o,'Codigo CUPS','cod_cups',null,null,true,true,'','col-35');
	$c[]=new cmp('final_consul','s','3',$e,$w.' '.$o,'Finalidad de la Consulta','final_consul',null,null,true,true,'','col-35');
	$c[]=new cmp('cod_admin','n','12',$e,$w.' '.$o,'Codigo ingreso','cod_admin',null,null,true,true,'','col-15');
	$c[]=new cmp('cod_factura','n','12',$e,$w.' '.$o,'Codigo de Factura','cod_factura',null,null,false,true,'','col-15');
	$c[]=new cmp('estado_hist','s','3',$e,$w.' '.$o,'Estado Admision','estado_hist',null,null,true,true,'','col-2');
	
	$o='admfac';
	$c[]=new cmp($o,'e',null,'AJUSTE IDENTIFICACION DEL USUARIO',$w);
	$c[]=new cmp('tipo_docnew','s','3',$e,$w.' '.$o,'Ajuste Tipo Documento','tipo_docnew',null,'',false,true,'','col-25');
	$c[]=new cmp('documento_new','t','20',$e,$w.' '.$o,'Ajuste N° Identificación','documento_new',null,'',false,true,'','col-25');
	
	 
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}


function get_derivaeac(){
	if($_REQUEST['id']==''){
		return "";
	}else{
		// print_r($_POST);
		$id=divide($_REQUEST['id']);
		// print_r($id);
		$sql="SELECT concat(F.documento,'_',F.tipo_doc,'_',P.vivipersona,'_',id_factura) id,
		F.tipo_doc,F.documento,P.nombre1,P.nombre2,P.apellido1,P.apellido2,P.fecha_nacimiento,P.sexo,P.genero,P.nacionalidad,P.estado_civil,P.niveduca,P.ocupacion,P.regimen,P.eapb,G.localidad,G.barrio,G.direccion,H.telefono1,H.telefono2,H.telefono3,fecha_consulta,tipo_consulta,
		cod_cups,final_consul,cod_admin,cod_factura,estado_hist,tipo_docnew,documento_new
		FROM `adm_facturacion` F
		LEFT JOIN personas P ON F.tipo_doc=P.tipo_doc AND F.documento=P.idpersona
		LEFT JOIN hog_viv H ON P.vivipersona = H.idviv
			LEFT JOIN ( SELECT CONCAT(estrategia, '_', sector_catastral, '_', nummanzana, '_', predio_num, '_', unidad_habit, '_', estado_v) AS geo, direccion, localidad, barrio
        			FROM hog_geo ) AS G ON H.idgeo = G.geo
		WHERE id_factura='{$id[2]}'";
		// echo $sql;
		// print_r($id);
		$info=datos_mysql($sql);
        /*if (!$info['responseResult']) {
				return '';
			}else{
				return json_encode($info['responseResult'][0]);
			}*/
		 return json_encode($info['responseResult'][0]);
	    } 
}

function opc_sexo($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
}
function opc_genero($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=19 and estado='A' ORDER BY 1",$id);
}
function opc_nacionalidad($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=30 and estado='A' ORDER BY 1",$id);
}
function opc_regimen($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=17 and estado='A' ORDER BY 1",$id);
}
function opc_eapb($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=18 and estado='A' ORDER BY 1",$id);
}
function opc_estado_civil($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=47 and estado='A' ORDER BY 1",$id);
}
function opc_niveduca($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=180 and estado='A' ORDER BY 1",$id);
}
function opc_ocupacion($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=175 and estado='A' ORDER BY 1",$id);
}
function opc_tipo_consulta($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=182 and estado='A'  ORDER BY 1 ",$id);
}
function opc_cod_cups($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=126 and estado='A'  ORDER BY 1 ",$id);
}
function opc_final_consul($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=127 and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta;",$id);
}
function opc_estado_hist($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=184 and estado='A' ORDER BY 1",$id);
}
function opc_tipo_docnew($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
    }



function gra_derivaeac(){
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
		$rtaF.= "NO HA SELECIONADO LA ADMISION A EDITAR";
	}
	// echo $sql;
  return $rtaF;
}

function fac($id){
	$id=divide($id);
	$sql="SELECT fecha_consulta fecha
			FROM adm_facturacion F
			WHERE  F.id_factura='{$id[2]}'";
	// echo $sql;
	$info=datos_mysql($sql);
	return $f=$info['responseResult'][0]['fecha'];
	// var_dump($f);
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($c);
	if ($a=='derivaeac' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono admsi1' title='Información de la Facturación' id='".$c['ACCIONES']."' Onclick=\"mostrar('derivaeac','pro',event,'','lib.php',7);\"></li>"; //setTimeout(hideExpres,1000,'estado_v',['7']);
		$rta.="<li class='icono crear' title='Nueva Admisión' id='".$c['ACCIONES']."' Onclick=\"newAdmin('{$c['ACCIONES']}');\"></li>";
	}
	if ($a=='adm' && $b=='acciones'){
		$rta="<nav class='menu right'>";
		$blo = (fac($c['ACCIONES'])=='0000-00-00') ? 'false' :'true';
		// $cmps ='';
		$rta.="<li class='icono editar ' title='Editar ' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'derivaeac',event,this,'','lib.php');setTimeout(bloqElem,700,['fecha_consulta','tipo_consulta','cod_cups','final_consul'],$blo);Color('adm-lis');\"></li>";  //act_lista(f,this);
		// $rta.="<li class='icono editar' title='Editar Información de Facturación' id='".$c['ACCIONES']."' Onclick=\"getData('derivaeac','pro',event,'','lib.php',7);\"></li>"; //setTimeout(hideExpres,1000,'estado_v',['7']);
	}
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
