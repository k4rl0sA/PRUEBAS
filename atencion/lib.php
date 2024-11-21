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


function opc_usuario(){
	$id=$_REQUEST['id'];
	$sql="SELECT hg.idgeo,FN_CATALOGODESC(72,hg.subred) AS subred,
	FN_CATALOGODESC(42,hg.estrategia) AS estrategia,
	IFNULL(u.nombre,u1.nombre) asignado,
	IFNULL(u.perfil,u1.perfil) perfil,
	hg.territorio 
	FROM hog_viv hv 
	LEFT JOIN hog_geo hg ON hv.idpre=hg.idgeo
	LEFT JOIN personas p ON hv.idviv=p.vivipersona
	LEFT JOIN usuarios u ON hg.asignado=u.id_usuario
	LEFT JOIN usuarios u1 ON hg.usu_creo=u1.id_usuario
	WHERE p.idpersona='".$id."' and hg.estado_v='7'";
 //echo $sql;
	$info=datos_mysql($sql);
	if(isset($info['responseResult'][0])){ 
		return json_encode($info['responseResult'][0]);
	}else{
		return "[]";
	}
}

function lis_homes(){
	$total="SELECT COUNT(*) AS total FROM (
		SELECT DISTINCT CONCAT_WS('_',H.estrategia,H.sector_catastral,H.nummanzana,H.predio_num,H.unidad_habit,H.estado_v,H.idgeo) AS ACCIONES
		FROM hog_geo H
		LEFT JOIN usuarios U ON H.subred = U.subred
	LEFT JOIN usuarios U1 ON H.usu_creo = U1.id_usuario
	LEFT JOIN adscrip A ON H.territorio=A.territorio 
	LEFT JOIN personas_datocomp M ON U.id_usuario =M.asignado_eac  
	".whe_deriva()."
		WHERE H.estado_v IN ('7') ".whe_homes()."
			AND U.id_usuario = '{$_SESSION['us_sds']}'
) AS Subquery";
	$info=datos_mysql($total);
	$total=$info['responseResult'][0]['total']; 
	$regxPag=5;
	$pag=(isset($_POST['pag-homes']))? ($_POST['pag-homes']-1)* $regxPag:0;

	
$sql="SELECT CONCAT_WS('_',H.estrategia,H.sector_catastral,H.nummanzana,H.predio_num,H.unidad_habit,H.estado_v,H.idgeo) AS ACCIONES,
	H.idgeo AS Cod_Predio,
	FN_CATALOGODESC(42,H.estrategia) AS estrategia,
	direccion,
	H.territorio,
	H.sector_catastral Sector,
	H.nummanzana AS Manzana,
	H.predio_num AS predio,
	H.unidad_habit AS 'Unidad',
	FN_CATALOGODESC(2,H.localidad) AS 'Localidad',
	U1.nombre,
	H.fecha_create,
	FN_CATALOGODESC(44,H.estado_v) AS estado
	FROM hog_geo H
	LEFT JOIN usuarios U ON H.subred = U.subred
	LEFT JOIN usuarios U1 ON H.usu_creo = U1.id_usuario
	LEFT JOIN adscrip A ON H.territorio=A.territorio 
	LEFT JOIN personas_datocomp M ON U.id_usuario =M.asignado_eac 
	".whe_deriva()."
WHERE H.estado_v in('7') ".whe_homes()." 
	AND U.id_usuario = '{$_SESSION['us_sds']}'
	GROUP BY ACCIONES
	ORDER BY nummanzana, predio_num
	LIMIT $pag, $regxPag";

// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"homes",$regxPag);
}

function whe_deriva(){
    $sql = "";
    if ($_POST['fterri']) {
        $sql.=" LEFT JOIN derivacion D ON H.idgeo = D.cod_predio ";
    }else{
        $sql.=" LEFT JOIN derivacion D ON H.idgeo = D.cod_predio AND D.doc_asignado='{$_SESSION['us_sds']}' ";
    }
    return $sql;
}


function whe_homes() {
	$fefin=date('Y-m-d');
	$feini = date("Y-m-d",strtotime($fefin."- 2 days"));
	$sql = "";
	if (!empty($_POST['fpred'])) {
		$sql .= " AND H.idgeo = '" . $_POST['fpred'] . "'";
		if ($_POST['fterri']) {
			$sql .= " AND (H.territorio='" . $_POST['fterri'] . "' OR H.usu_creo = '{$_SESSION['us_sds']}')";
		} else {
			$sql .= " AND (H.territorio IN (SELECT A.territorio FROM adscrip where A.doc_asignado='{$_SESSION['us_sds']}') OR H.usu_creo = '{$_SESSION['us_sds']}'    OR D.doc_asignado='{$_SESSION['us_sds']}'  OR M.asignado_eac='{$_SESSION['us_sds']}')";
		}
		if ($_POST['fdigita']) {
			$sql .= " AND H.usu_creo ='" . $_POST['fdigita'] . "'";
		}
	} else {
		if ($_POST['fterri']) {
			$sql .= " AND (H.territorio='" . $_POST['fterri'] . "' OR H.usu_creo = '{$_SESSION['us_sds']}')";
		} else {
			$sql .= " AND (H.territorio IN (SELECT A.territorio FROM adscrip where A.doc_asignado='{$_SESSION['us_sds']}') OR H.usu_creo = '{$_SESSION['us_sds']}'  )";//OR D.doc_asignado='{$_SESSION['us_sds']}'     OR M.asignado_eac='{$_SESSION['us_sds']}'
		}
		if ($_POST['fdigita']) {
			$sql .= " AND H.usu_creo ='" . $_POST['fdigita'] . "'";
		}
		if ($_POST['fdes']) {
			if ($_POST['fhas']) {
			      $sql .= " AND H.fecha_create BETWEEN '$feini 00:00:00' and '$fefin 23:59:59' ";
			} else {
			    $sql .= " AND H.fecha_create BETWEEN '$feini 00:00:00' and '$feini 23:59:59' ";
			}
		}
	}
	return $sql;
}


function cap_menus($a,$b='cap',$con='con') {
  $rta = "";
  $acc=rol($a);
  if ($a=='homes' && isset($acc['crear']) && $acc['crear']=='SI') {  
  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
//   $rta .= "<li class='icono $a exportar'       title='Exportar'    Onclick=\"csv('$a');\"></li>"; 
  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  
   }
   if ($a=='person' && isset($acc['crear']) && $acc['crear']=='SI') {  

	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	// $rta .= "<li class='icono $a exportar'       title='Exportar'    Onclick=\"csv('$a');\"></li>"; 
	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";

	}
	if($a=='atencion' && isset($acc['crear']) && $acc['crear']=='SI'){
		$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
		$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";

	}
		if($a=='eac_juventud' && isset($acc['crear']) && $acc['crear']=='SI'){
		$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
		$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";

	}
	if($a=='eac_adultez' && isset($acc['crear']) && $acc['crear']=='SI'){
		$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
		$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";

	}
	if($a=='eac_vejez' && isset($acc['crear']) && $acc['crear']=='SI'){
		$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
		$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";

	}
	
  return $rta;
}

function lis_famili(){
	// $id=divide($_POST['id']);
	$cod=divide($_POST['id']);
	$id=$cod[0].'_'.$cod[1].'_'.$cod[2].'_'.$cod[3].'_'.$cod[4].'_'.$cod[5];
	$sql="SELECT concat(idviv,'_',idgeo) ACCIONES,idviv AS Cod_Familiar,numfam AS N°_FAMILIA,fecha,CONCAT_WS(' ',FN_CATALOGODESC(6,complemento1),nuc1,FN_CATALOGODESC(6,complemento2),nuc2,FN_CATALOGODESC(6,complemento3),nuc3) Complementos,FN_CATALOGODESC(4,tipo_vivienda) 'Tipo de Vivienda',
		V.fecha_create Creado,nombre Creó
		FROM `hog_viv` V 
			left join usuarios P ON usu_creo=id_usuario
		WHERE '1'='1' and idgeo='".$id;
		$sql.="' ORDER BY fecha_create";
		//  echo $sql;
			$datos=datos_mysql($sql);
		return panel_content($datos["responseResult"],"famili-lis",8);
		}
	

function cmp_homes1(){
	$rta="";
	$rta.="<div class='encabezado vivienda'>TABLA DE FAMILIAS POR VIVIENDA</div>
	<div class='contenido' id='famili-lis' >".lis_famili()."</div></div>";
	return $rta;
}

function cmp_homes(){
	$rta="";
	$hoy=date('Y-m-d');
	$w='homes';
   	$d='';
	$o='inf';
	$c[]=new cmp($o,'e',null,'INFORMACIÓN COMPLEMENTARIA DE LA VIVIENDA',$w);
	$c[]=new cmp('idg','h',15,$_POST['id'],$w.' '.$o,'id','idg',null,'####',false,false);
	$c[]=new cmp('numfam','s',3,$d,$w.' '.$o,'Número de Familia','numfam',null,'',true,true,'','col-2');
	$c[]=new cmp('fecha','d','10',$d,$w.' oculto '.$o,'fecha Caracterización','fecha',null,'',false,false,'','col-2');
	$c[]=new cmp('complemento1','s','3',$d,$w.' '.$o,'complemento1','complemento',null,'',true,true,'','col-15');
    $c[]=new cmp('nuc1','t','4',$d,$w.' '.$o,'nuc1','nuc1',null,'',true,true,'','col-1');
 	$c[]=new cmp('complemento2','s','3',$d,$w.' '.$o,'complemento2','complemento',null,'',false,true,'','col-15');
 	$c[]=new cmp('nuc2','t','4',$d,$w.' '.$o,'nuc2','nuc2',null,'',false,true,'','col-1');
 	$c[]=new cmp('complemento3','s','3',$d,$w.' '.$o,'complemento3','complemento',null,'',false,true,'','col-15');
 	$c[]=new cmp('nuc3','t','4',$d,$w.' '.$o,'nuc3','nuc3',null,'',false,true,'','col-15');
	$c[]=new cmp('telefono1','n','10',$d,$w.' '.$o,'telefono1','telefono1','rgxphone',NULL,true,true,'','col-3');
	$c[]=new cmp('telefono2','n','10',$d,$w.' '.$o,'telefono2','telefono2','rgxphone1',null,false,true,'','col-3');
	$c[]=new cmp('telefono3','n','10',$d,$w.' '.$o,'telefono3','telefono3','rgxphone1',null,false,true,'','col-4');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}
function opc_incluofici($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=179 and estado='A' ORDER BY 1",$id);
}
function opc_pobladifer($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=178 and estado='A' ORDER BY 1",$id);
}
function opc_tenDencia($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=8 and estado='A' ORDER BY 1",$id);
}
function opc_tipo_vivienda($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=4 and estado='A' ORDER BY 1",$id);
}
function opc_tipo_familia($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=10 and estado='A' ORDER BY 1",$id);
}
function opc_complemento($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=6 and estado='A' ORDER BY 1",$id);
}
function opc_vinculos($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=12 and estado='A' ORDER BY 1",$id);
}
function opc_ingreso($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=13 and estado='A' ORDER BY 1",$id);
}
function opc_encuentra($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
}
function get_homes(){
	if($_REQUEST['id']==''){
		return "";
	}else{
		$id=divide($_REQUEST['id']);
		$sql="SELECT idviv,numfam,fecha,complemento1,nuc1,complemento2,nuc2,complemento3,nuc3,telefono1,telefono2,telefono3
		FROM `hog_viv` 
		WHERE idviv ='{$id[0]}' AND idgeo=concat('".$id[1]."','_','".$id[2]."','_','".$id[3]."','_','".$id[4]."','_','".$id[5]."','_','".$id[6]."')";
		// echo $sql;
		// print_r($id);
		$info=datos_mysql($sql);
		return json_encode($info['responseResult'][0]);
	} 
}

function focus_homes(){
	return 'homes';
   }
   
function focus_homes1(){
	return 'homes1';
}
function men_homes(){
	$rta=cap_menus('homes','pro');
	return $rta;
}
function men_homes1(){
	$rta=cap_menus('homes1','fix');
	return $rta;
}
   
function gra_homes(){
	$id=divide($_POST['idg']);
	// print_r($id);
	$cod=$id[0].'_'.$id[1].'_'.$id[2].'_'.$id[3].'_'.$id[4].'_'.$id[5];
	if(count($id)==1){
	$sql="UPDATE `hog_viv` SET
	`fecha`=TRIM(UPPER('{$_POST['fecha']}')),
	`Complemento1`=TRIM(UPPER('{$_POST['complemento1']}')),`nuc1`=TRIM(UPPER('{$_POST['nuc1']}')),
	`complemento2`=TRIM(UPPER('{$_POST['complemento2']}')),`nuc2`=TRIM(UPPER('{$_POST['nuc2']}')),
	`complemento3`=TRIM(UPPER('{$_POST['complemento3']}')),`nuc3`=TRIM(UPPER('{$_POST['nuc3']}')),
	telefono1=TRIM(UPPER('{$_POST['telefono1']}')),telefono2=TRIM(UPPER('{$_POST['telefono2']}')),
	telefono3=TRIM(UPPER('{$_POST['telefono3']}')),
	`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
	WHERE idviv='{$id[0]}'";
	// echo $sql;
	}elseif(count($id)==7){
		$sql="INSERT INTO hog_viv VALUES (null,
		{$id[6]},
		TRIM(UPPER('{$cod}')),
		TRIM(UPPER('{$_POST['numfam']}')),
		TRIM(UPPER('{$_POST['fecha']}')),
		'','','','','','','','',
		TRIM(UPPER('{$_POST['complemento1']}')),TRIM(UPPER('{$_POST['nuc1']}')),
		TRIM(UPPER('{$_POST['complemento2']}')),TRIM(UPPER('{$_POST['nuc2']}')),
		TRIM(UPPER('{$_POST['complemento3']}')),TRIM(UPPER('{$_POST['nuc3']}')),
		TRIM(UPPER('{$_POST['telefono1']}')),TRIM(UPPER('{$_POST['telefono2']}')),TRIM(UPPER('{$_POST['telefono3']}')),
		'','','','','','','','','','','','','','','','','','','','','','','','','',
		'','','','','','','','','','','','','','','','','','','','','','','','','',
		'','','','','','','','','','',
		'','','','','','',
		TRIM(UPPER('{$_SESSION['us_sds']}')),
		TRIM(UPPER('{$_SESSION['us_sds']}')),
		DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A');";
		// echo $sql;
	}
	  $rta=dato_mysql($sql);
	  
	//   return "correctamente";
	  return $rta;
	}



// INICIO FORMULARIO INTEGRANTES DE LA FAMILIA


function cmp_person1(){
	$rta="";
	$rta .="<div class='encabezado vivienda'>TABLA DE INTEGRANTES FAMILIA</div>
	<div class='contenido' id='datos-lis' >".lista_persons()."</div></div>";
	return $rta;
} 


function cmp_person(){
	$rta="";
	/* $rta .="<div class='encabezado vivienda'>TABLA DE INTEGRANTES FAMILIA</div>
	<div class='contenido' id='datos-lis' >".lista_persons()."</div></div>"; */
	// $t=['anos'=>0];
	$hoy=date('Y-m-d');
	// $p=get_edad();
    $w="person";
	// if ($p==""){$p=$t;}
	/* $ocu= ($p['anos']>5) ? true : false ; */
	/* $t=['vivipersona'=>'','idpersona'=>'','tipo_doc'=>'','nombre1'=>'','nombre2'=>'','apellido1'=>'','apellido2'=>'','fecha_nacimiento'=>'','sexo'=>'','genero'=>'','nacionalidad'=>'','discapacidad'=>'','etnia'=>'','pueblo'=>'','idioma'=>'','regimen'=>'','eapb'=>'','localidad'=>'','upz'=>'','direccion'=>'','telefono1'=>'','telefono2'=>'','telefono3'=>''];$w='person';
	$d=get_person(); 
	if ($d=="") {$d=$t;}$u=($d['vivipersona']=='')?true:false; */
	$d='';
	$o='infgen';
	// print_r($_POST);
	$c[]=new cmp($o,'e',null,'INFORMACIÓN GENERAL',$w);
	$c[]=new cmp('idp','h',15,$_POST['id'],$w.' '.$o,'id','id',null,'####',false,false);
	$c[]=new cmp('encuentra','s','2',$d,$w.' '.$o,'El usuario se encuentra','encuentra',null,null,true,true,'','col-2');
	$c[]=new cmp('idpersona','n','18',$d,$w.' '.$o,'Identificación','idpersona',null,null,true,true,'','col-4');
	$c[]=new cmp('tipo_doc','s','3',$d,$w.' '.$o,'Tipo documento','tipo_doc',null,null,true,true,'','col-4');
	$c[]=new cmp('nombre1','t','30',$d,$w.' '.$o,'Primer Nombre','nombre1',null,null,true,true,'','col-2');
	$c[]=new cmp('nombre2','t','30',$d,$w.' '.$o,'Segundo Nombre','nombre2',null,null,false,true,'','col-2');
	$c[]=new cmp('apellido1','t','30',$d,$w.' '.$o,'Primer Apellido','apellido1',null,null,true,true,'','col-2');
	$c[]=new cmp('apellido2','t','30',$d,$w.' '.$o,'Segundo Apellido','apellido2',null,null,false,true,'','col-2');
	$c[]=new cmp('fecha_nacimiento','d','',$d,$w.' '.$o,'Fecha de nacimiento','fecha_nacimiento',null,null,true,true,'','col-2',"validDate(this,-43800,0);",[],"child14('fecha_nacimiento','osx');Ocup5('fecha_nacimiento','OcU');");
	$c[]=new cmp('sexo','s','3',$d,$w.' '.$o,'Sexo','sexo',null,null,true,true,'','col-2');
	$c[]=new cmp('genero','s','3',$d,$w.' '.$o,'Genero','genero',null,null,true,true,'','col-2');
	$c[]=new cmp('oriensexual','s','3',$d,$w.' osx '.$o,'Orientacion Sexual','oriensexual',null,null,true,true,'','col-2');
	$c[]=new cmp('nacionalidad','s','3',$d,$w.' '.$o,'nacionalidad','nacionalidad',null,null,true,true,'','col-2');
	$c[]=new cmp('estado_civil','s','3',$d,$w.' '.$o,'Estado Civil','estado_civil',null,null,true,true,'','col-2');
	$c[]=new cmp('niveduca','s','3',$d,$w.' '.$o,'Nivel Educativo','niveduca',null,'',true,true,'','col-25',"enabDesEsc('niveduca','aE',fecha_nacimiento);");//true
	$c[]=new cmp('abanesc','s','3',$d,$w.' aE '.$o,'Razón del abandono Escolar','abanesc',null,'',false,false,'','col-25');
	$c[]=new cmp('ocupacion','s','3',$d,$w.' OcU '.$o,'Ocupacion','ocupacion',null,'',false,true,'','col-25',"timeDesem(this,'des');");//true
	$c[]=new cmp('tiemdesem','n','3',$d,$w.' des '.$o,'Tiempo de desempleo (Meses)','tiemdesem',null,'',false,false,'','col-25');
	$c[]=new cmp('vinculo_jefe','s','3',$d,$w.' '.$o,'Vinculo con el jefe del Hogar','vinculo_jefe',null,null,true,true,'','col-2');
	$c[]=new cmp('etnia','s','3',$d,$w.' '.$o,'Pertenencia Etnica','etnia',null,null,true,true,'','col-2',"enabEtni('etnia','ocu','idi');");
	$c[]=new cmp('pueblo','s','50',$d,$w.' ocu cmhi '.$o,'pueblo','pueblo',null,null,false,true,'','col-2');
	$c[]=new cmp('idioma','o','2',$d,$w.' ocu cmhi idi '.$o,'Habla Español','idioma',null,null,false,true,'','col-2');
	$c[]=new cmp('discapacidad','s','3',$d,$w.' '.$o,'discapacidad','discapacidad',null,null,true,true,'','col-2');
	$c[]=new cmp('regimen','s','3',$d,$w.' '.$o,'regimen','regimen',null,null,true,true,'','col-2','enabAfil(\'regimen\',\'eaf\');enabEapb(\'regimen\',\'rgm\');');//enabEapb(\'regimen\',\'reg\');
	$c[]=new cmp('eapb','s','3',$d,$w.' rgm '.$o,'eapb','eapb',null,null,true,true,'','col-2');
	$c[]=new cmp('afiliacion','o','2',$d,$w.' eaf cmhi '.$o,'¿Esta interesado en afiliación por oficio?','afiliacion',null,null,false,true,'','col-2');
	
	
	$c[]=new cmp('sisben','s','3',$d,$w.' '.$o,'Grupo Sisben','sisben',null,null,true,true,'','col-2');
	$c[]=new cmp('catgosisb','n','2',$d,$w.' '.$o,'Categoria Sisben','catgosisb','rgxsisben',null,true,true,'','col-2');
	$c[]=new cmp('pobladifer','s','3',$d,$w.' '.$o,'Poblacion Direferencial y de Inclusión','pobladifer',null,'',true,true,'','col-2');
	$c[]=new cmp('incluofici','s','3',$d,$w.' '.$o,'Población Inclusion por Oficio','incluofici',null,'',true,true,'','col-2');
	
	$o='relevo';
	$c[]=new cmp('cuidador','o','2',$d,$w.' '.$o,'¿Es cuidador de una persona residente en la vivienda?','cuidador',null,null,true,true,'','col-25',"hideCuida('cuidador','cUi');");
	
	$c[]=new cmp('perscuidada','s','3',$d,$w.' cUi '.$o,'N° de identificacion y Nombres','cuida',null,null,false,false,'','col-35');
	$c[]=new cmp('tiempo_cuidador','n','20',$d,$w.' cUi '.$o,'¿Por cuánto tiempo ha sido cuidador?','tiempo_cuidador',null,null,false,false,'','col-2');
	$c[]=new cmp('cuidador_unidad','s','3',$d,$w.' cUi '.$o,'Unidad de medida tiempo cuidador','cuidador_unidad',null,null,false,false,'','col-2');
	$c[]=new cmp('vinculo_cuida','s','3',$d,$w.' cUi '.$o,'Vinculo con la persona cuidada','vinculo_cuida',null,null,false,false,'','col-2');
	$c[]=new cmp('tiempo_descanso','n','20',$d,$w.' cUi '.$o,'¿Cada cuánto descansa?','tiempo_descanso',null,null,false,false,'','col-2');
	$c[]=new cmp('descanso_unidad','s','3',$d,$w.' cUi '.$o,'Unidad de medida tiempo descanso','descanso_unidad',null,null,false,false,'','col-2');
	
	$c[]=new cmp('reside_localidad','o','2',$d,$w.' cUi '.$o,'Reside en la localidad','reside_localidad',null,null,false,false,'','col-3',"enabLoca('reside_localidad','lochi');");
	$c[]=new cmp('localidad_vive','s','3',$d,$w.' lochi cUi '.$o,'¿En qué localidad vive?','localidad_vive',null,null,false,false,'','col-3');
	$c[]=new cmp('transporta','s','3',$d,$w.' lochi cUi  '.$o,'¿En que se transporta?','transporta',null,null,false,false,'','col-4');
	
	
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
   }

   function get_edad(){
	if($_REQUEST['id']==''){
		return "";
	}else{
		$id=divide($_REQUEST['id']);
		if(count($id)!==7){
			$sql="SELECT FLOOR(DATEDIFF(CURDATE(), fecha_nacimiento) / 365) AS anos,
				FLOOR((DATEDIFF(CURDATE(), fecha_nacimiento) % 365) / 30) AS meses,
			DATEDIFF(CURDATE(), fecha_nacimiento) % 30 AS dias
			FROM `personas` P
			WHERE P.idpersona='{$id[0]}'AND P.tipo_doc='{$id[1]}'"; 
			// echo $sql." ".count($id);
			// print_r($id);
			$info=datos_mysql($sql);
			return $info['responseResult'][0];
		}else{
			return "";
		}
	}
}
     
function lista_persons(){ //revisar
	$id=divide($_POST['id']);
		$sql="SELECT DISTINCT concat(idpersona,'_',tipo_doc,'_',vivipersona) ACCIONES,idpeople AS Cod_Persona,idpersona 'Identificación',FN_CATALOGODESC(1,tipo_doc) 'Tipo de Documento',
		concat_ws(' ',nombre1,nombre2,apellido1,apellido2) 'Nombre',fecha_nacimiento 'fecha de nacimiento',
		FLOOR(DATEDIFF(CURDATE(), fecha_nacimiento) / 365)  'edad actual',
		FN_CATALOGODESC(21,sexo) 'sexo',FN_CATALOGODESC(19,genero) 'Genero',FN_CATALOGODESC(30,nacionalidad) 'Nacionalidad',
		IF(a.atencion_cronico = 'SI',IF((SELECT COUNT(*) FROM eac_enfermedades c WHERE c.enfermedades_documento = p.idpersona) > 0,'CON','SIN'),'NO') AS Cronico,
		IF(a.gestante = 'SI',IF((SELECT COUNT(*) FROM eac_gestantes g WHERE g.gestantes_documento=p.idpersona) > 0, 'CON', 'SIN'),'NO') AS Gestante	
		FROM `personas` p 
			LEFT JOIN eac_atencion a ON p.idpersona=a.atencion_idpersona
			WHERE vivipersona='".$id[0]."'";
		// echo $sql;
		$_SESSION['sql_person']=$sql;
			$datos=datos_mysql($sql);
		return panel_content($datos["responseResult"],"datos-lis",10);
} 

function focus_person(){
	return 'person';
}
   
   
function men_person(){
	$rta=cap_menus('person','pro');
	return $rta;
}


function get_person(){
	// print_r($_POST);
	if($_POST['id']==''){
		return "";
	}else{
		$id=divide($_POST['id']);
		// print_r($id);
		$sql="SELECT concat(idpersona,'_',tipo_doc),encuentra,idpersona,tipo_doc,nombre1,nombre2,apellido1,apellido2,fecha_nacimiento,
		sexo,genero,oriensexual,nacionalidad,estado_civil,niveduca,abanesc,ocupacion,tiemdesem,vinculo_jefe,etnia,pueblo,idioma,discapacidad,regimen,eapb,
		afiliaoficio,sisben,catgosisb,pobladifer,incluofici,cuidador,perscuidada,tiempo_cuidador,cuidador_unidad,vinculo,tiempo_descanso,
		descanso_unidad,reside_localidad,localidad_vive,transporta
		FROM `personas` 
		left join personas_datocomp ON idpersona=dc_documento AND tipo_doc=dc_tipo_doc 
		WHERE idpersona ='{$id[0]}' and tipo_doc='{$id[1]}'" ;
		$info=datos_mysql($sql);
		//  echo $sql;
	 return json_encode($info['responseResult'][0]); 
	} 
}



function gra_person(){
	// print_r($_POST);
	$id=divide($_POST['idp']);
	// print_r(count($id));
	if(count($id)!=7){
		$sql="UPDATE `personas` SET 
		encuentra=TRIM(UPPER('{$_POST['encuentra']}')),
		`tipo_doc`=TRIM(UPPER('{$_POST['tipo_doc']}')),
		`nombre1`=TRIM(UPPER('{$_POST['nombre1']}')),
		`nombre2`=TRIM(UPPER('{$_POST['nombre2']}')),
		`apellido1`=TRIM(UPPER('{$_POST['apellido1']}')),
		`apellido2`=TRIM(UPPER('{$_POST['apellido2']}')),
		`fecha_nacimiento`=TRIM(UPPER('{$_POST['fecha_nacimiento']}')),
		`sexo`=TRIM(UPPER('{$_POST['sexo']}')),
		`genero`=TRIM(UPPER('{$_POST['genero']}')),
		`oriensexual`=TRIM(UPPER('{$_POST['oriensexual']}')),
		`nacionalidad`=TRIM(UPPER('{$_POST['nacionalidad']}')),
		`estado_civil`=TRIM(UPPER('{$_POST['estado_civil']}')),
		niveduca=TRIM(UPPER('{$_POST['niveduca']}')),
		abanesc=TRIM(UPPER('{$_POST['abanesc']}')),
		ocupacion=TRIM(UPPER('{$_POST['ocupacion']}')),
		tiemdesem=TRIM(UPPER('{$_POST['tiemdesem']}')),
		`vinculo_jefe`=TRIM(UPPER('{$_POST['vinculo_jefe']}')),
		`etnia`=TRIM(UPPER('{$_POST['etnia']}')),
		`pueblo`=TRIM(UPPER('{$_POST['pueblo']}')),
		`idioma`=TRIM(UPPER('{$_POST['idioma']}')),
		`discapacidad`=TRIM(UPPER('{$_POST['discapacidad']}')),
		`regimen`=TRIM(UPPER('{$_POST['regimen']}')),
		`eapb`=TRIM(UPPER('{$_POST['eapb']}')),
		`afiliaoficio`=TRIM(UPPER('{$_POST['afiliacion']}')),
		`sisben`=TRIM(UPPER('{$_POST['sisben']}')),
		`catgosisb`=TRIM(UPPER('{$_POST['catgosisb']}')),
		`pobladifer`=TRIM(UPPER('{$_POST['pobladifer']}')),
		`incluofici`=TRIM(UPPER('{$_POST['incluofici']}')),
		`cuidador`=TRIM(UPPER('{$_POST['cuidador']}')),
		`perscuidada`=TRIM(UPPER('{$_POST['perscuidada']}')),
		`tiempo_cuidador`=TRIM(UPPER('{$_POST['tiempo_cuidador']}')),
		`cuidador_unidad`=TRIM(UPPER('{$_POST['cuidador_unidad']}')),
		`vinculo`=TRIM(UPPER('{$_POST['vinculo_cuida']}')),
		`tiempo_descanso`=TRIM(UPPER('{$_POST['tiempo_descanso']}')),
		`descanso_unidad`=TRIM(UPPER('{$_POST['descanso_unidad']}')),
		`reside_localidad`=TRIM(UPPER('{$_POST['reside_localidad']}')),
		`localidad_vive`=TRIM(UPPER('{$_POST['localidad_vive']}')),
		`transporta`=TRIM(UPPER('{$_POST['transporta']}')),
		`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
		`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
		WHERE idpersona =TRIM(UPPER('{$id[0]}')) AND tipo_doc=TRIM(UPPER('{$id[1]}'))";
		//    echo $sql;
		//    echo $sql."    ".$rta;
	}else{
		/* $sql1="INSERT INTO `personas_datocomp` VALUES (TRIM(UPPER('{$_POST['tipo_doc']}')),TRIM(UPPER('{$_POST['idpersona']}')),TRIM(UPPER('{$_POST['fpe']}')),TRIM(UPPER('{$_POST['fta']}')),TRIM(UPPER('{$_POST['imc']}')),TRIM(UPPER('{$_POST['tas']}')),TRIM(UPPER('{$_POST['tad']}')),TRIM(UPPER('{$_POST['glu']}')),TRIM(UPPER('{$_POST['bra']}')),TRIM(UPPER('{$_POST['abd']}')),TRIM(UPPER('{$_POST['pef']}')),TRIM(UPPER('{$_POST['des']}')),TRIM(UPPER('{$_POST['fin']}')),TRIM(UPPER('{$_POST['oms']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),TRIM(UPPER('{$_SESSION['us_sds']}')),null,null,'A')";
		$rta1=dato_mysql($sql1); */

		$sql="INSERT INTO personas VALUES (NULL,
		TRIM(UPPER('{$_POST['encuentra']}')),
		TRIM(UPPER('{$_POST['idpersona']}')),$id[0],
		TRIM(UPPER('{$_POST['tipo_doc']}')),
		TRIM(UPPER('{$_POST['nombre1']}')),
		TRIM(UPPER('{$_POST['nombre2']}')),
		TRIM(UPPER('{$_POST['apellido1']}')),
		TRIM(UPPER('{$_POST['apellido2']}')),
		TRIM(UPPER('{$_POST['fecha_nacimiento']}')),
		TRIM(UPPER('{$_POST['sexo']}')),
		TRIM(UPPER('{$_POST['genero']}')),
		TRIM(UPPER('{$_POST['oriensexual']}')),
		TRIM(UPPER('{$_POST['nacionalidad']}')),
		TRIM(UPPER('{$_POST['estado_civil']}')),

		TRIM(UPPER('{$_POST['niveduca']}')),
		TRIM(UPPER('{$_POST['abanesc']}')),
		TRIM(UPPER('{$_POST['ocupacion']}')),
		TRIM(UPPER('{$_POST['tiemdesem']}')),

		TRIM(UPPER('{$_POST['vinculo_jefe']}')),
		TRIM(UPPER('{$_POST['etnia']}')),
		TRIM(UPPER('{$_POST['pueblo']}')),
		TRIM(UPPER('{$_POST['idioma']}')),
		TRIM(UPPER('{$_POST['discapacidad']}')),
		TRIM(UPPER('{$_POST['regimen']}')),
		TRIM(UPPER('{$_POST['eapb']}')),
		TRIM(UPPER('{$_POST['afiliacion']}')),
		TRIM(UPPER('{$_POST['sisben']}')),
		TRIM(UPPER('{$_POST['catgosisb']}')),
		TRIM(UPPER('{$_POST['pobladifer']}')),
		TRIM(UPPER('{$_POST['incluofici']}')),
		TRIM(UPPER('{$_POST['cuidador']}')),
		TRIM(UPPER('{$_POST['perscuidada']}')),
		TRIM(UPPER('{$_POST['tiempo_cuidador']}')),
		TRIM(UPPER('{$_POST['cuidador_unidad']}')),
		TRIM(UPPER('{$_POST['vinculo_cuida']}')),
		TRIM(UPPER('{$_POST['tiempo_descanso']}')),
		TRIM(UPPER('{$_POST['descanso_unidad']}')),
		TRIM(UPPER('{$_POST['reside_localidad']}')),
		TRIM(UPPER('{$_POST['localidad_vive']}')),
		TRIM(UPPER('{$_POST['transporta']}')),
		TRIM(UPPER('{$_SESSION['us_sds']}')),
		DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";	 
	}
	  $rta=dato_mysql($sql);
	  return $rta;
	}
	
	function opc_abanesc($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=181 and estado='A' ORDER BY 1",$id);
	}
	function opc_niveduca($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=180 and estado='A' ORDER BY 1",$id);
	}
	function opc_ocupacion($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=175 and estado='A' ORDER BY 1",$id);
	}
	function opc_numfam($id=''){
		return opc_sql("SELECT `idcatadeta`,concat(descripcion,' - ',idcatadeta) FROM `catadeta` WHERE idcatalogo=172 and estado='A' ORDER BY 1",$id);
	}
	 function opc_tipo_doc($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
    }
    function opc_sexo($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
	}
    function opc_genero($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=19 and estado='A' ORDER BY 1",$id);
	}
	function opc_oriensexual($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=49 and estado='A' ORDER BY 1",$id);
	}
	function opc_nacionalidad($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=30 and estado='A' ORDER BY 1",$id);
    }
    function opc_etnia($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=16 and estado='A' ORDER BY 1",$id);
    }
	function opc_regimen($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=17 and estado='A' ORDER BY 1",$id);
    }
    function opc_eapb($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=18 and estado='A' ORDER BY 1",$id);
    }
	function opc_sisben($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=48 and estado='A' ORDER BY 1",$id);
	}
	function opc_estado_civil($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=47 and estado='A' ORDER BY 1",$id);
	}
	function opc_vinculo_jefe($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=54 and estado='A' ORDER BY 1",$id);
	} 
	function opc_cuidador_unidad($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=67 and estado='A' ORDER BY 1",$id);
	}
	function opc_vinculo_cuida($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=54 and estado='A' ORDER BY 1",$id);
	}
	function opc_localidad_vive($id=''){
	return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,'-',descripcion) FROM `catadeta` WHERE idcatalogo=2  ORDER BY 1",$id);
    }
	function opc_transporta($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=25 and estado='A' ORDER BY 1",$id);
	}
	function opc_descanso_unidad($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=67 and estado='A' ORDER BY 1",$id);
	}
	function opc_pueblo($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=15 and estado='A' ORDER BY 1",$id);
	}
	function opc_discapacidad($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=14 and estado='A' ORDER BY 1",$id);
	}
	function opc_cuida(){
		$id=divide($_REQUEST['id']);
		if(count($id)==7){
			$sql="SELECT idpersona,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) 'Nombres' from personas where vivipersona='$id[0]'";
		}else if(count($id)==3){
			$sql="SELECT idpersona,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) 'Nombres' from personas where vivipersona='$id[2]' and idpersona<>'$id[0]'";
		}
		// var_dump($id);
			return opc_sql($sql,'');		
	}

	
	
	
function opc_accion1($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
}
function opc_accion2($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
}
function opc_accion3($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
}
function opc_accion4($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
}
function opc_equipo($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=26 and estado='A' ORDER BY 1",$id);
}

////////////////////////////////////////////////////atencion//////////////////////////////////////////7777
function cmp_atencion(){
	$rta="";
	$rta .="<div class='encabezado atencion'>Consultas realizadas al paciente</div>
	<div class='contenido' id='atencion-lis' >".lis_atencion()."</div></div>";
	$hoy=date('Y-m-d');
	// $x=['ida'=>'','atencion_tipodoc'=>'','atencion_idpersona'=>'','atencion_fechaatencion'=>'','atencion_codigocups'=>'','atencion_finalidadconsulta'=>'','atencion_peso'=>'','atencion_talla'=>'','atencion_sistolica'=>'','atencion_diastolica'=>'','atencion_abdominal'=>'','atencion_brazo'=>'','atencion_diagnosticoprincipal'=>'','atencion_diagnosticorelacion1'=>'','atencion_diagnosticorelacion2'=>'','atencion_diagnosticorelacion3'=>'','atencion_fertil'=>'','atencion_preconcepcional'=>'','atencion_metodo'=>'','atencion_anticonceptivo'=>'','atencion_planificacion'=>'','atencion_mestruacion'=>'','atencion_gestante'=>'','atencion_gestaciones'=>'','atencion_partos'=>'','atencion_abortos'=>'','atencion_cesarias'=>'','atencion_vivos'=>'','atencion_muertos'=>'','atencion_vacunaciongestante'=>'','atencion_edadgestacion'=>'','atencion_ultimagestacion'=>'','atencion_probableparto'=>'','atencion_prenatal'=>'','atencion_fechaparto'=>'','atencion_rpsicosocial'=>'','atencion_robstetrico'=>'','atencion_rtromboembo'=>'','atencion_rdepresion'=>'','atencion_sifilisgestacional'=>'','atencion_sifiliscongenita'=>'','atencion_morbilidad'=>'','atencion_hepatitisb'=>'','atencion_vih'=>'','atencion_cronico'=>'','atencion_asistenciacronica'=>'','atencion_tratamiento'=>'','atencion_vacunascronico'=>'','atencion_menos5anios'=>'','atencion_esquemavacuna'=>'','atencion_signoalarma'=>'','atencion_cualalarma'=>'','atencion_dxnutricional'=>'','atencion_eventointeres'=>'','atencion_evento'=>'','atencion_cualevento'=>'','atencion_sirc'=>'','atencion_rutasirc'=>'','atencion_remision'=>'','atencion_cualremision'=>'','atencion_ordenpsicologia'=>'','atencion_ordenvacunacion'=>'','atencion_vacunacion'=>'','atencion_ordenlaboratorio'=>'','atencion_laboratorios'=>'','atencion_ordenimagenes'=>'','atencion_imagenes'=>'','atencion_ordenmedicamentos'=>'','atencion_medicamentos'=>'','atencion_rutacontinuidad'=>'','atencion_continuidad'=>'','atencion_relevo'=>''];
	$t=['idpersona'=>'','tipo_doc'=>'','nombres'=>'','fecha_atencion'=>'','tipo_consulta'=>'','cod_cups'=>'','fecha_nacimiento'=>'','sexo'=>'','genero'=>'','nacionalidad'=>''];
	$d=get_personas();
	$x="";
	if ($d==""){$d=$t;}
	$u=($d['idpersona']=='')?true:false;
	$w='atencion';		
	$o='datos';

	$fecha_actual = new DateTime();
	$fecha_nacimiento = new DateTime($d['fecha_nacimiento']);
	$edad = $fecha_nacimiento->diff($fecha_actual)->y;
	$adul = ($edad>=18) ? true : false;
	$adult = ($edad>=18) ? 'true' : 'false';
	$meno = ($edad<5) ? true : false;
	$gest = (($edad>=10 && $edad <= 54) && $d['sexo'] == 'M') ? true : false;
	
	$c[]=new cmp($o,'e',null,'Datos atención medica usuario',$w);
	$c[]=new cmp('ida','h',15,$_POST['id'],$w.' '.$o,'ida','ida',null,'####',false,false,'col-1');
	$c[]=new cmp('atencion_tipodoc','t','20',$d['tipo_doc'],$w.' '.$o,'Tipo','atencion_tipodoc',null,'',false,false,'','col-1');
	$c[]=new cmp('atencion_idpersona','t','20',$d['idpersona'],$w.' '.$o,'N° Identificación','atencion_idpersona',null,'',false,false,'','col-2');
	$c[]=new cmp('nombre1','t','20',$d['nombres'],$w.' '.$o,'Nombres','nombre1',null,'',false,false,'','col-3');
	$c[]=new cmp('fecha_nacimiento','t','20',$d['fecha_nacimiento'],$w.' '.$o,'fecha nacimiento','fecha_nacimiento',null,'',false,false,'','col-1','validDate');
	$c[]=new cmp('sexo','s','20',$d['sexo'],$w.' '.$o,'sexo','sexo',null,'',false,false,'','col-1');
	$c[]=new cmp('genero','s','20',$d['genero'],$w.' '.$o,'genero','genero',null,'',false,false,'','col-1');
	$c[]=new cmp('nacionalidad','s','20',$d['nacionalidad'],$w.' '.$o,'Nacionalidad','nacionalidad',null,'',false,false,'','col-1');

	$o='consulta';
	$c[]=new cmp($o,'e',null,'Datos de la atencion medica	',$w);
	$c[]=new cmp('idf','h',15,'',$w.' '.$o,'idf','idf',null,'####',false,false,'','col-1');
	$c[]=new cmp('atencion_fechaatencion','d',20,$x,$w.' '.$o,'Fecha de la consulta','atencion_fechaatencion',null,'',true,false,'','col-2');
	$c[]=new cmp('tipo_consulta','s',3,$x,$w.' '.$o,'Tipo de Consulta','tipo_consulta',null,'',true,false,'','col-2');
	$c[]=new cmp('atencion_codigocups','s',3,$x,$w.' '.$o,'Código CUPS','cups',null,'',true,false,'','col-3');
	$c[]=new cmp('atencion_finalidadconsulta','s',3,$x,$w.' '.$o,'Finalidad de la Consulta','consultamedica',null,'',true,false,'','col-3');


	$c[]=new cmp('atencion_cronico','s',3,$x,$w.'  '.$o,'¿Usuario con patologia Cronica?','aler',null,'',true,true,'','col-3');
	
	$c[]=new cmp('gestante','s',3,$x,$w.' '.$o,'¿Usuaria Gestante?','aler',null,'',$gest,$gest,'','col-3',"alerPreg(this,'pre','nfe','fer','mef');periAbd('gestante','AbD',$adult);");

	$c[]=new cmp('atencion_peso','sd',6,$x,$w.' '.$o,'Peso (Kg) Mín=0.50 - Máx=150.00','atencion_peso','rgxpeso','###.##',true,true,'','col-2',"valPeso('atencion_peso');ZscoAte('dxnutricional');");
	$c[]=new cmp('atencion_talla','sd',5, $x,$w.' '.$o,'Talla (Cm) Mín=40 - Máx=210','atencion_talla','rgxtalla','###.#',true,true,'','col-2',"valTalla('atencion_talla');ZscoAte('dxnutricional');");
	$c[]=new cmp('atencion_sistolica','n',3, $x,$w,'TAS Mín=40 - Máx=250','atencion_sistolica','rgxsisto','###',$adul,$adul,'','col-2',"valSist('atencion_sistolica');");
	$c[]=new cmp('atencion_diastolica','n',3, $x,$w,'TAD Mín=40 - Máx=150','atencion_diastolica','rgxdiast','###',$adul,$adul,'','col-2',"ValTensions('atencion_sistolica',this);valDist('atencion_diastolica');");
	$c[]=new cmp('atencion_abdominal','n',4,$x,$w.' AbD '.$o,'Perímetro Abdominal (Cm) Mín=50 - Máx=150','atencion_abdominal','rgxperabd','###',$adul,$adul,'','col-3');
	
	
	$c[]=new cmp('perime_braq','sd',4, $x,$w,'Perimetro Braquial (Cm)',0,null,'##,#',$meno,$meno,'','col-3');

	$c[]=new cmp('dxnutricional','t',15,$x,$w.'  '.$o,'Dx Nutricional','des',null,null,false,false,'','col-5');

	$c[]=new cmp('signoalarma','s',2,$x,$w.'  '.$o,'niño o niña con signos de alarma ','aler',null,'',$meno,$meno,'','col-25','AlarChild(this,\'ala\');');
	$c[]=new cmp('cualalarma','s',3,$x,$w.' ala '.$o,'cual?','alarma5',null,'',false,false,'','col-25');
	

	$c[]=new cmp('letra1','s','3',$x,$w.' '.$o,'Letra CIE(1)','letra1',null,null,true,true,'','col-1','valPyd(this,\'tipo_consulta\');valResol(\'tipo_consulta\',\'letra1\');',['rango1']);
 	$c[]=new cmp('rango1','s','3',$x,$w.' '.$o,'Tipo1','rango1',null,null,true,true,'','col-45',false,['diagnostico1']);
 	$c[]=new cmp('diagnostico1','s','8',$x,$w.' '.$o,'Diagnostico Principal','diagnostico1',null,null,true,true,'','col-45');
	$c[]=new cmp('letra2','s','3',$x,$w.' '.$o,'Letra CIE(2)','letra2',null,null,false,true,'','col-1',false,['rango2']);
 	$c[]=new cmp('rango2','s','3',$x,$w.' '.$o,'Tipo2','rango2',null,null,false,true,'','col-45',false,['diagnostico2']);
 	$c[]=new cmp('diagnostico2','s','8',$x,$w.' '.$o,'Diagnostico 2','diagnostico2',null,null,false,true,'','col-45');
	$c[]=new cmp('letra3','s','3',$x,$w.' '.$o,'Letra CIE(3)','letra3',null,null,false,true,'','col-1',false,['rango3']);
 	$c[]=new cmp('rango3','s','3',$x,$w.' '.$o,'Tipo3','rango3',null,null,false,true,'','col-45',false,['diagnostico3']);
 	$c[]=new cmp('diagnostico3','s','8',$x,$w.' '.$o,'Diagnostico 3','diagnostico3',null,null,false,true,'','col-45');


$o='cronico';
	$c[]=new cmp($o,'e',null,'Condiciones',$w);


	$c[]=new cmp('fertil','s',3,$x,$w.' pre mef '.$o,'¿Mujer en Edad Fertil (MEF) con intención reproductiva?','aler',null,'',$gest,$gest,'','col-4',"enabFert(this,'fer','nfe');");
	$c[]=new cmp('preconcepcional','s',3,$x,$w.' pre nfe '.$o,'Tiene consulta preconcepcional','aler',null,'',$gest,false,'','col-2');
	$c[]=new cmp('metodo','s',3,$x,$w.' pre fer '.$o,'Uso actual de método anticonceptivo','aler',null,'',$gest,false,'','col-2','enabAlert(this,\'met\');');
	$c[]=new cmp('anticonceptivo','s',3,$x,$w.' pre fer met '.$o,'Metodo anticonceptivo','metodoscons',null,'',$gest,false,'','col-2');
	$c[]=new cmp('planificacion','s',3,$x,$w.' pre fer '.$o,'Tiene consulta de PF','aler',null,'',$gest,false,'','col-2');
	$c[]=new cmp('mestruacion','d',3,$x,$w.'  '.$o,'Fecha de ultima Mestruacion','atencion_mestruacion',null,'',false,true,'','col-2');	
// }	

$o='prurap';
	$c[]=new cmp($o,'e',null,'Aplicacion de Pruebas Rapidas',$w);
	$c[]=new cmp('vih','s',3,$x,$w.' '.$o,'Prueba Rapida Para VIH','aler',null,'',true,true,'','col-25',"enabTest(this,'vih');");
	$c[]=new cmp('resul_vih','s',3,$x,$w.' vih '.$o,'Resultado VIH','vih',null,'',true,false,'','col-25');
	$c[]=new cmp('hb','s',3,$x,$w.' '.$o,'Prueba Rapida Para Hepatitis B Antigeno de Superficie','aler',null,'',true,true,'','col-25',"enabTest(this,'hb');");
	$c[]=new cmp('resul_hb','s',3,$x,$w.' hb '.$o,'Resultado Hepatitis B Antigeno de Superficie','rep',null,'',true,false,'','col-25');
	$c[]=new cmp('trepo_sifil','s',3,$x,$w.' '.$o,'Prueba Rapida Treponémica Para Sifilis','aler',null,'',true,true,'','col-25',"enabTest(this,'sif');");
	$c[]=new cmp('resul_sifil','s',3,$x,$w.' sif '.$o,'Resultado Treponémica Para Sifilis','rep',null,'',true,false,'','col-25');
	$c[]=new cmp('pru_embarazo','s',3,$x,$w.' '.$o,'Prueba de Embarazo','aler',null,'',$gest,$gest,'','col-25',"enabTest(this,'pem');");
	$c[]=new cmp('resul_emba','s',3,$x,$w.' pem '.$o,'Resultado prueba de Embarazo','rep',null,'',$gest,false,'','col-25');

 $o='plancuidado';
	$c[]=new cmp($o,'e',null,'Plan de Cuidado Individual',$w);
	$c[]=new cmp('atencion_eventointeres','o',3,$x,$w.' '.$o,'Notificacion de eventos de interés en salud pública','atencion_eventointeres	',null,'',false,$u,'','col-35','enabEven(this,\'even\',\'whic\');');//,'hidFieOpt(\'atencion_eventointeres\',\'event_hid\',this,true)'
	$c[]=new cmp('atencion_evento','s',3,$x,$w.' even '.$o,'Evento de Interes en Salud Publica','evento',null,'',false,false,'','col-4','cualEven(this,\'whic\');');//,'hidFieselet(\'atencion_evento\',\'hidd_aten\',this,true,\'5\')'
	$c[]=new cmp('atencion_cualevento','t',300,$x,$w.' whic '.$o,'Otro, Cual?','atencion_cualevento	',null,'',false,false,'','col-25');
	$c[]=new cmp('atencion_sirc','o',3,$x,$w.' '.$o,'Activación rutas SIRC (usuarios otras EAPB)','atencion_sirc	',null,'',false,true,'','col-5',"enabAlert(this,'sirc');");//,'hidFieOpt(\'atencion_sirc\',\'sirc\',this,true)'
	$c[]=new cmp('atencion_rutasirc[]','m',3,$x,$w.' sirc '.$o,'Rutas SIRC','rutapoblacion',null,'',false,false,'','col-5');
	$c[]=new cmp('atencion_remision','o',3,$x,$w.' '.$o,'Usuario que require control','atencion_remision	',null,'',false,true,'','col-5','enabAlert(this,\'rem\');');//,'hidFieOpt(\'atencion_remision\',\'espe_hid\',this,true)'
	$c[]=new cmp('atencion_cualremision[]','m',3,$x,$w.' rem '.$o,'Cuales?	','remision	',null,'',false,false,'','col-5');
	
	$c[]=new cmp('atencion_ordenvacunacion','o',3,$x,$w.' '.$o,'Orden Vacunación?','atencion_ordenvacunacion	',null,'',false,true,'','col-1','enabAlert(this,\'vac\');');//,'hidFieOpt(\'atencion_ordenvacunacion\',\'vacu_hid\',this,true)'
	$c[]=new cmp('atencion_vacunacion','s',3,$x,$w.' vac '.$o,'Vacunación	','vacunacion',null,'',false,false,'','col-2');
	
	$c[]=new cmp('atencion_ordenlaboratorio','o',3,$x,$w.' '.$o,'Ordena Laboratorio ?','atencion_ordenlaboratorio	',null,'',false,true,'','col-15','enabAlert(this,\'lab\');');//,'hidFieOpt(\'atencion_ordenlaboratorio\',\'lab_hid\',this,true)'
	$c[]=new cmp('atencion_laboratorios','s',3,$x,$w.' lab '.$o,'Laboratorio','solicitud',null,'',false,false,'','col-2');
	
	$c[]=new cmp('atencion_ordenmedicamentos','o',3,$x,$w.' '.$o,'Ordena Medicamentos ?','atencion_ordenmedicamentos	',null,'',false,true,'','col-15','enabAlert(this,\'med\');');//,'hidFieOpt(\'atencion_ordenmedicamentos\',\'medi_hid\',this,true)'
	$c[]=new cmp('atencion_medicamentos','s',3,$x,$w.' med '.$o,'Medicamentos','medicamentos',null,'',false,false,'','col-2');
	
	$c[]=new cmp('atencion_rutacontinuidad','o',3,$x,$w.' '.$o,'Remisión para continuidad a rutas integrales de atencion en salud por parte de la subred','prueba	',null,'',false,true,'','col-5',"enabAlert(this,'rut');");//,'hidFieOpt(\'atencion_rutacontinuidad\',\'cont_hid\',this,true)'
	$c[]=new cmp('atencion_continuidad[]','m',3,$x,$w.' rut '.$o,'.','rutapoblacion',null,'',false,false,'','col-5');
	$c[]=new cmp('atencion_ordenimagenes','o',3,$x,$w.' '.$o,'Ordena Imágenes Diagnósticas','atencion_ordenimagenes	',null,'',true,true,'','col-3');//,'hidFieOpt(\'atencion_ordenimagenes\',\'img_hid\',this,true)'
	$c[]=new cmp('atencion_ordenpsicologia','s',3,$x,$w.' '.$o,'Ordena Psicología','aler',null,'',true,true,'','col-3');
	$c[]=new cmp('atencion_relevo','s',3,$x,$w.' '.$o,'Cumple criterios Para relevo domiciliario a cuidadores','aler',null,'',true,true,'','col-4');
	$c[]=new cmp('prioridad','s',3,$x,$w.' '.$o,'Prioridad','prioridad',null,'',true,true,'','col-4');
	$c[]=new cmp('estrategia','s',3,$x,$w.' '.$o,'Estrategia','estrategia',null,'',true,true,'','col-4');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
   }

   function lis_atencion(){
	// FN_CATALOGODESC(1,F.tipo_doc) Identificación, F.documento 'Número', F.`fecha_create` 'fecha creación' 
	/* $id=divide($_POST['id']);
	$id=divide($_POST['ida']); */
	$id = isset($_POST['id']) ? divide($_POST['id']) : (isset($_POST['ida']) ? divide($_POST['ida']) : null);

	// print_r($id);
	$info=datos_mysql("SELECT COUNT(*) total FROM adm_facturacion F WHERE F.documento ='{$id[0]}' AND F.tipo_doc='{$id[1]}'");
	$total=$info['responseResult'][0]['total'];
	$regxPag=4;

	$pag=(isset($_POST['pag-atencion']))? ($_POST['pag-atencion']-1)* $regxPag:0;
	$sql="SELECT  F.id_factura ACCIONES,F.cod_admin,F.fecha_consulta fecha,FN_CATALOGODESC(182,F.tipo_consulta) Consulta,
	FN_CATALOGODESC(126,F.cod_cups) 'Código CUPS',FN_CATALOGODESC(127,F.final_consul) Finalidad
	FROM adm_facturacion F
	WHERE F.documento ='{$id[0]}' AND F.tipo_doc='{$id[1]}'";
		$sql.=" ORDER BY F.fecha_create";
		$sql.=' LIMIT '.$pag.','.$regxPag;
		// echo $sql;
			$datos=datos_mysql($sql);
			return create_table($total,$datos["responseResult"],"atencion",$regxPag,'lib.php');
		// return panel_content($datos["responseResult"],"atencion-lis",5);
	}

function get_personas(){
		if($_REQUEST['id']==''){
			return "";
		}else{
			$id=divide($_REQUEST['id']);
			//  `atencion_fechaatencion`, `atencion_codigocups`, `atencion_finalidadconsulta`, `atencion_peso`, `atencion_talla`, `atencion_sistolica`, `atencion_diastolica`, `atencion_abdominal`, `atencion_brazo`, `atencion_diagnosticoprincipal`, `atencion_diagnosticorelacion1`, `atencion_diagnosticorelacion2`, `atencion_diagnosticorelacion3`, `atencion_fertil`, `atencion_preconcepcional`, `atencion_metodo`, `atencion_anticonceptivo`, `atencion_planificacion`, `atencion_mestruacion`, `atencion_gestante`, `atencion_gestaciones`, `atencion_partos`, `atencion_abortos`, `atencion_cesarias`, `atencion_vivos`, `atencion_muertos`, `atencion_vacunaciongestante`, `atencion_edadgestacion`, `atencion_ultimagestacion`, `atencion_probableparto`, `atencion_prenatal`, `atencion_fechaparto`, `atencion_rpsicosocial`, `atencion_robstetrico`, `atencion_rtromboembo`, `atencion_rdepresion`, `atencion_sifilisgestacional`, `atencion_sifiliscongenita`, `atencion_morbilidad`, `atencion_hepatitisb`, `atencion_vih`, `atencion_cronico`, `atencion_asistenciacronica`, `atencion_tratamiento`, `atencion_vacunascronico`, `atencion_menos5anios`, `atencion_esquemavacuna`, `atencion_signoalarma`, `atencion_cualalarma`, `atencion_dxnutricional`, `atencion_eventointeres`, `atencion_evento`, `atencion_cualevento`, `atencion_sirc`, `atencion_rutasirc`, `atencion_remision`, `atencion_cualremision`, `atencion_ordenpsicologia`, `atencion_ordenvacunacion`, `atencion_vacunacion`, `atencion_ordenlaboratorio`, `atencion_laboratorios`, `atencion_ordenimagenes`, `atencion_imagenes`, `atencion_ordenmedicamentos`, `atencion_medicamentos`, `atencion_rutacontinuidad`, `atencion_continuidad`, `atencion_relevo`  ON a.atencion_idpersona = b.idpersona AND a.atencion_tipodoc = b.tipo_doc
			$sql="SELECT  a.tipo_doc,a.idpersona,concat_ws(' ',a.nombre1,a.nombre2,a.apellido1,a.apellido2) nombres,a.fecha_nacimiento,a.sexo,a.genero,a.nacionalidad,
			b.fecha_consulta,b.tipo_consulta,cod_cups,fecha_consulta,tipo_consulta,final_consul
			FROM personas a
			LEFT JOIN adm_facturacion b ON a.idpersona = b.documento AND a.tipo_doc = b.tipo_doc
			WHERE a.idpersona ='{$id[0]}' AND a.tipo_doc='{$id[1]}'";
			// echo $sql;
			$info=datos_mysql($sql);
			return $info['responseResult'][0];			
		}
}

function get_zscore(){
	$id=divide($_POST['val']);
	 $fechaNacimiento = new DateTime($id[1]);
	 $fechaActual = new DateTime();
	 $diferencia = $fechaNacimiento->diff($fechaActual);
	 $edadEnDias = $diferencia->days;
	 if($edadEnDias<1857){
	$ind = ($edadEnDias<=730) ? 'PL' : 'PT' ;
	$sex=$id[2];

$sql="SELECT (POWER(($id[0] / (SELECT M FROM tabla_zscore WHERE indicador = '$ind' AND sexo = '$sex[0]' AND edad_dias = $id[3])),
	(SELECT L FROM tabla_zscore WHERE indicador = '$ind' AND sexo = '$sex[0]' AND edad_dias = $id[3])) - 1) / 
	((SELECT L FROM tabla_zscore WHERE indicador = '$ind' AND sexo = '$sex[0]' AND edad_dias = $id[3]) *
 (SELECT S FROM tabla_zscore WHERE indicador = '$ind' AND sexo = '$sex[0]' AND edad_dias = $id[3])) as rta ";
//   echo $sql;
 $info=datos_mysql($sql);
 	if (!$info['responseResult']) {
		return '';
	}else{
		$z=number_format((float)$info['responseResult'][0]['rta'], 6, '.', '');
		switch ($z) {
			case ($z <=-3):
				$des='DESNUTRICIÓN AGUDA SEVERA';
				break;
			case ($z >-3 && $z <=-2):
				$des='DESNUTRICIÓN AGUDA MODERADA';
				break;
			case ($z >-2 && $z <=-1):
				$des='RIESGO DESNUTRICIÓN AGUDA';
				break;
			case ($z>-1 && $z <=1):
					$des='PESO ADECUADO PARA LA TALLA';
				break;
			case ($z >1 && $z <=2):
					$des='RIESGO DE SOBREPESO';
				break;
			case ($z >2 && $z <=3):
					$des='SOBREPESO';
				break;
				case ($z >3):
					$des='OBESIDAD';
				break;
			default:
				$des='Error en el rango, por favor valide';
				break;
		}
		return json_encode($z." = ".$des);
	}
}
return json_encode('N/A');
}

function get_atencion(){
	if($_REQUEST['id']==''){
		return "";
	}else{
		 $id=$_REQUEST['id'];
			// print_r($id[0]);
			// print_r($_REQUEST['id']);
			$sql1="SELECT COUNT(*) rta
			FROM adm_facturacion a
			LEFT JOIN eac_atencion c ON a.tipo_doc = c.atencion_tipodoc AND a.documento = c.atencion_idpersona
			WHERE c.id_factura ='{$id}' and a.id_factura='{$id}'";
			$info=datos_mysql($sql1);
			$total=$info['responseResult'][0]['rta'];
			// echo $sql;
			/* $info=datos_mysql($sql); */
			// return json_encode($info['responseResult'][0]);

			if ($total==1){		
				$sql="SELECT concat(a.documento,'_',a.tipo_doc) id,a.tipo_doc,a.documento,concat_ws(' ',b.nombre1,b.nombre2,b.apellido1,b.apellido2) nombres,
			b.fecha_nacimiento,b.sexo,b.genero,b.nacionalidad, a.id_factura,a.fecha_consulta,a.tipo_consulta,a.cod_cups,a.final_consul,
			atencion_cronico, `gestante`, 
				`atencion_peso`, `atencion_talla`, `atencion_sistolica`, `atencion_diastolica`, `atencion_abdominal`, `atencion_brazo`,
				dxnutricional,signoalarma,cualalarma,`letra1`, `rango1`, `diagnostico1`, `letra2`, `rango2`, `diagnostico2`, `letra3`, `rango3`, 
				`diagnostico3`,`fertil`, `preconcepcional`, `metodo`, `anticonceptivo`, `planificacion`, 
				`mestruacion`,
				vih,resul_vih,hb,resul_hb,trepo_sifil,resul_sifil,pru_embarazo,resul_emba,
				`atencion_eventointeres`, `atencion_evento`, `atencion_cualevento`, 
				`atencion_sirc`, `atencion_rutasirc`, `atencion_remision`, `atencion_cualremision`, `atencion_ordenvacunacion`, `atencion_vacunacion`, `atencion_ordenlaboratorio`, `atencion_laboratorios`, `atencion_ordenmedicamentos`, `atencion_medicamentos`, `atencion_rutacontinuidad`, `atencion_continuidad`, `atencion_ordenimagenes`, `atencion_ordenpsicologia`, `atencion_relevo`
				,prioridad,estrategia
			FROM adm_facturacion a
			LEFT JOIN personas b ON a.tipo_doc=b.tipo_doc AND a.documento=b.idpersona
			LEFT JOIN eac_atencion c ON a.tipo_doc=c.atencion_tipodoc AND a.documento=c.atencion_idpersona
			WHERE c.id_factura ='{$id}' and a.id_factura='{$id}'";
			//  echo $sql;
			$info=datos_mysql($sql);
			return json_encode($info['responseResult'][0]);
			}else{
				$sql="SELECT concat(a.documento,'_',a.tipo_doc) id,a.tipo_doc,a.documento,concat_ws(' ',b.nombre1,b.nombre2,b.apellido1,b.apellido2) nombres,
				b.fecha_nacimiento,b.sexo,b.genero,b.nacionalidad, a.id_factura,a.fecha_consulta,a.tipo_consulta,a.cod_cups,a.final_consul,
				`atencion_cronico`,`gestante`,
				`atencion_peso`, `atencion_talla`, `atencion_sistolica`, `atencion_diastolica`, `atencion_abdominal`, `atencion_brazo`,
			dxnutricional,signoalarma,cualalarma,`letra1`, `rango1`, `diagnostico1`, `letra2`, `rango2`, `diagnostico2`, `letra3`, `rango3`, 
			`diagnostico3`, `fertil`, `preconcepcional`, `metodo`, `anticonceptivo`, `planificacion`, 
			`mestruacion`, vih,resul_vih,hb,resul_hb,trepo_sifil,resul_sifil,pru_embarazo,resul_emba,
			  `atencion_eventointeres`, `atencion_evento`, `atencion_cualevento`, 
			`atencion_sirc`, `atencion_rutasirc`, `atencion_remision`, `atencion_cualremision`, `atencion_ordenvacunacion`, `atencion_vacunacion`, `atencion_ordenlaboratorio`, `atencion_laboratorios`, `atencion_ordenmedicamentos`, `atencion_medicamentos`, `atencion_rutacontinuidad`, `atencion_continuidad`, `atencion_ordenimagenes`, `atencion_ordenpsicologia`, `atencion_relevo`
			,prioridad,estrategia
			FROM adm_facturacion a
			LEFT JOIN personas b ON a.tipo_doc=b.tipo_doc AND a.documento=b.idpersona
			LEFT JOIN eac_atencion c ON a.tipo_doc=c.atencion_tipodoc AND a.documento=c.atencion_idpersona AND a.id_factura=c.id_factura
			WHERE a.id_factura='{$id}'";
		//  echo $sql;
			/*  */
			$info=datos_mysql($sql);
			return json_encode($info['responseResult'][0]);
			}
		 }
	} 

	
	function focus_atencion(){
		return 'atencion';
	   }
	   
	function men_atencion(){
		$rta=cap_menus('atencion','pro');
		return $rta;
	   }
	   
	function gra_atencion(){
		$id=divide($_POST['ida']);
		// print_r($_POST['ida']);
		if(count($id)==6){
			return "No es posible actualizar consulte con el administrador";
		}elseif(count($id)==2){
			
	$fertil = isset($_POST['fertil']) ? trim($_POST['fertil']) : '';
	$preconcepcional = isset($_POST['preconcepcional']) ? trim($_POST['preconcepcional']) : '';
	$metodo = isset($_POST['metodo']) ? trim($_POST['metodo']) : '';
	$anticonceptivo = isset($_POST['anticonceptivo']) ? trim($_POST['anticonceptivo']) : '';
	$planificacion = isset($_POST['planificacion']) ? trim($_POST['planificacion']) : '';
	$mestruacion = isset($_POST['mestruacion']) ? trim($_POST['mestruacion']) : '';
	$gestante = isset($_POST['gestante']) ? trim($_POST['gestante']) : '';

	if (($smu2 = $_POST['fatencion_rutasirc'] ?? null) && is_array($smu2)){$rutasirc = implode(",",str_replace("'", "", $smu2));}
	if (($smu1 = $_POST['fatencion_continuidad'] ?? null) && is_array($smu1)){$contin = implode(",",str_replace("'", "", $smu1));}
	if (($smu3 = $_POST['fatencion_cualremision'] ?? null) && is_array($smu3)){$remisi = implode(",",str_replace("'", "", $smu3));}

$sql="INSERT INTO eac_atencion VALUES (null,
		TRIM(UPPER('{$_POST['atencion_tipodoc']}')),
		TRIM(UPPER('{$_POST['atencion_idpersona']}')),
		TRIM(UPPER('{$_POST['idf']}')),
		TRIM(UPPER('{$_POST['atencion_fechaatencion']}')),
		TRIM(UPPER('{$_POST['tipo_consulta']}')),
		TRIM(UPPER('{$_POST['atencion_codigocups']}')),
		TRIM(UPPER('{$_POST['atencion_finalidadconsulta']}')),
		TRIM(UPPER('{$_POST['atencion_cronico']}')),
		TRIM(UPPER('{$gestante}')),
		TRIM(UPPER('{$_POST['atencion_peso']}')),
		TRIM(UPPER('{$_POST['atencion_talla']}')),
		TRIM(UPPER('{$_POST['atencion_sistolica']}')),
		TRIM(UPPER('{$_POST['atencion_diastolica']}')),
		TRIM(UPPER('{$_POST['atencion_abdominal']}')),
		TRIM(UPPER('{$_POST['perime_braq']}')),
		TRIM(UPPER('{$_POST['dxnutricional']}')),
		TRIM(UPPER('{$_POST['signoalarma']}')),
		TRIM(UPPER('{$_POST['cualalarma']}')),
		TRIM(UPPER('{$_POST['letra1']}')),
		TRIM(UPPER('{$_POST['rango1']}')),
		TRIM(UPPER('{$_POST['diagnostico1']}')),
		TRIM(UPPER('{$_POST['letra2']}')),
		TRIM(UPPER('{$_POST['rango2']}')),
		TRIM(UPPER('{$_POST['diagnostico2']}')),
		TRIM(UPPER('{$_POST['letra3']}')),
		TRIM(UPPER('{$_POST['rango3']}')),
		TRIM(UPPER('{$_POST['diagnostico3']}')),
		TRIM(UPPER('{$fertil}')),
		TRIM(UPPER('{$preconcepcional}')),
		TRIM(UPPER('{$metodo}')),
		TRIM(UPPER('{$anticonceptivo}')),
		TRIM(UPPER('{$planificacion}')),
		TRIM(UPPER('{$mestruacion}')),
		TRIM(UPPER('{$_POST['vih']}')),
		TRIM(UPPER('{$_POST['resul_vih']}')),
		TRIM(UPPER('{$_POST['hb']}')),
		TRIM(UPPER('{$_POST['resul_hb']}')),
		TRIM(UPPER('{$_POST['trepo_sifil']}')),
		TRIM(UPPER('{$_POST['resul_sifil']}')),
		TRIM(UPPER('{$_POST['pru_embarazo']}')),
		TRIM(UPPER('{$_POST['resul_emba']}')),
		TRIM(UPPER('{$_POST['atencion_eventointeres']}')),
		TRIM(UPPER('{$_POST['atencion_evento']}')),
		TRIM(UPPER('{$_POST['atencion_cualevento']}')),
		TRIM(UPPER('{$_POST['atencion_sirc']}')),
		TRIM(UPPER('{$rutasirc}')),
		TRIM(UPPER('{$_POST['atencion_remision']}')),
		TRIM(UPPER('{$remisi}')),
		TRIM(UPPER('{$_POST['atencion_ordenvacunacion']}')),
		TRIM(UPPER('{$_POST['atencion_vacunacion']}')),
		TRIM(UPPER('{$_POST['atencion_ordenlaboratorio']}')),
		TRIM(UPPER('{$_POST['atencion_laboratorios']}')),
		TRIM(UPPER('{$_POST['atencion_ordenmedicamentos']}')),
		TRIM(UPPER('{$_POST['atencion_medicamentos']}')),
		TRIM(UPPER('{$_POST['atencion_rutacontinuidad']}')),
		TRIM(UPPER('{$contin}')),
		TRIM(UPPER('{$_POST['atencion_ordenimagenes']}')),
		TRIM(UPPER('{$_POST['atencion_ordenpsicologia']}')),
		TRIM(UPPER('{$_POST['atencion_relevo']}')),
		TRIM(UPPER('{$_POST['prioridad']}')),
		TRIM(UPPER('{$_POST['estrategia']}')),
			TRIM(UPPER('{$_SESSION['us_sds']}')),
			DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
			// echo $sql;
		}
		  $rta=dato_mysql($sql);
		  return $rta; 
}

	///////////// inicio lista principal //////////////////


function lis_tam_personas(){
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(idpersona,'_',tipo_doc) ACCIONES,idpersona 'identificación',
	FN_CATALOGODESC(1,`tipo_doc`) `tipo documento`, concat(nombre1,' ',apellido1) Nombre, `fecha_nacimiento` `fecha de nacimiento`, FLOOR(DATEDIFF(CURDATE(), fecha_nacimiento) / 365)  'edad actual',
	FN_CATALOGODESC(21,`sexo`) `sexo`, 
	FN_CATALOGODESC(49,`genero`) `genero` 
	FROM `personas` 
		WHERE '1'='1'";
	$sql.=whe_tam_personas();
	$sql.=" ORDER BY 1";

	 /* $sql1="SELECT `idpersona`, FN_CATALOGODESC(1,`tipo_doc`) `tipo_doc`, `vivipersona`, `nombre1`, `nombre2`, 
	 `apellido1`, `apellido2`, `fecha_nacimiento`, FN_CATALOGODESC(21,`sexo`) `sexo`, FN_CATALOGODESC(49,`genero`) `genero`, 
	 `nacionalidad`, `discapacidad`, `etnia`, `pueblo`, `idioma`, `regimen`, `eapb`, `localidad`, `upz`, `direccion`, 
	 `telefono1`, `telefono2`, `telefono3`, `usu_creo`, `fecha_create`, `usu_update`, `fecha_update`, 
	  `estado`
	  FROM `personas` WHERE 1";
	$sql1.=whe_tam_personas();	
	//echo $sql;
		$_SESSION['sql_tam_personas']=$sql1; */
		$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"tam_personas",20);
}

function whe_tam_personas() {
	$sql = "";
	if ($_POST['fidentificacion'])
		$sql .= " AND idpersona like '%".$_POST['fidentificacion']."%'";
	if ($_POST['fsexo'])
		$sql .= " AND sexo ='".$_POST['fsexo']."' ";
	if ($_POST['fpersona']){
		if($_POST['fpersona'] == '2'){ //mayor de edad
			$sql .= " AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) <= 18 ";
		}else{ //menor de edad
			$sql .= " AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) > 18 ";
		}
	}

	if ($_POST['frango']){
		if($_POST['frango'] == '1'){ //primera infancia
			$sql .= " AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) > 0 AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) <= 5";
		}else if($_POST['frango'] == '2'){ // infancia
			$sql .= " AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) > 5 AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) <= 11";
		}else if($_POST['frango'] == '3'){ //adolescencia
			$sql .= " AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) > 11 AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) <= 17";
		}else if($_POST['frango'] == '4'){ //juventud
			$sql .= " AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) > 17 AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) <= 28";
		}else if($_POST['frango'] == '5'){ //adultez
			$sql .= " AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) > 28 AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) <= 59";
		}else{ //vejez
			$sql .= " AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) > 59";
		}
	}
				
	return $sql;
}


function focus_tam_personas(){
 return 'tam_personas';
}


function men_tam_personas(){
 $rta=cap_menus('tam_personas','pro');
 return $rta;
}


function cmp_tam_personas(){
 $rta="";
 
 $hoy=date('Y-m-d');

  $w='tam_personas';

 	$d='';
	$u=true;

	$o='info';
	$c[]=new cmp($o,'e',null,'INFORMACIÓN GENERAL',$w);

	$c[]=new cmp('id','h','15','1',$w.' '.$o,'id','id',null,null,"",true,'','col-5');
	$c[]=new cmp('idpersona','n','15',$d,$w.' '.$o,'Identificación','idpersona',null,null,true,true,'','col-5');
	$c[]=new cmp('tipo_doc','s','3',$d,$w.' '.$o,'Tipo documento','tipo_doc',null,null,true,true,'','col-5');
	$c[]=new cmp('nombre1','t','30',$d,$w.' '.$o,'Primer Nombre','nombre1',null,null,true,true,'','col-5');
	$c[]=new cmp('nombre2','t','30',$d,$w.' '.$o,'Segundo Nombre','nombre2',null,null,false,true,'','col-5');
	$c[]=new cmp('apellido1','t','30',$d,$w.' '.$o,'Primer Apellido','apellido1',null,null,true,true,'','col-5');
	$c[]=new cmp('apellido2','t','30',$d,$w.' '.$o,'Segundo Apellido','apellido2',null,null,false,true,'','col-5');
	$c[]=new cmp('fecha_nacimiento','d','',$d,$w.' '.$o,'Fecha de nacimiento','fecha_nacimiento',null,null,true,true,'','col-4');
	$c[]=new cmp('sexo','s','',$d,$w.' '.$o,'Sexo','sexo',null,null,true,true,'','col-3');
	$c[]=new cmp('genero','s','',$d,$w.' '.$o,'Genero','genero',null,null,true,true,'','col-3');
	$c[]=new cmp('telefono1','n','10',$d,$w.' '.$o,'telefono1','telefono1',null,null,true,true,'','col-4');
	$c[]=new cmp('telefono2','n','10',$d,$w.' '.$o,'telefono2','telefono2',null,null,false,true,'','col-3');
	$c[]=new cmp('telefono3','n','10',$d,$w.' '.$o,'telefono3','telefono3',null,null,false,true,'','col-3');

	$o='ubicacion';
	$c[]=new cmp($o,'e',null,'UBICACIÓN',$w);
	$c[]=new cmp('nacionalidad','s','',$d,$w.' '.$o,'nacionalidad','nacionalidad',null,null,true,true,'','col-3');
	$c[]=new cmp('discapacidad','o','2',$d,$w.' '.$o,'discapacidad','discapacidad',null,null,false,true,'','col-1');
	$c[]=new cmp('etnia','s','',$d,$w.' '.$o,'etnia','tipo_etnia',null,null,true,true,'','col-3');
	$c[]=new cmp('idioma','t','50',$d,$w.' '.$o,'idioma','idioma',null,null,false,true,'','col-3');
	$c[]=new cmp('pueblo','t','50',$d,$w.' '.$o,'pueblo','pueblo',null,null,false,true,'','col-4');
	$c[]=new cmp('regimen','s','50',$d,$w.' '.$o,'regimen','regimen',null,null,true,true,'','col-3');
	$c[]=new cmp('eapb','s','50',$d,$w.' '.$o,'eapb','eapb',null,null,true,true,'','col-3');
	$c[]=new cmp('localidad','s','50',$d,$w.' '.$o,'localidad','localidad',null,null,true,true,'','col-3');
	$c[]=new cmp('direccion','t','50',$d,$w.' '.$o,'direccion','direccion',null,null,true,true,'','col-4');
	$c[]=new cmp('upz','s','50',$d,$w.' '.$o,'upz','upz',null,null,true,true,'','col-3');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();

	return $rta;
}


///////////// fin lista principal //////////////////
	
////////////////////////////////////////////////////////////////////////////////////////////////////

function cmp_eac_juventud(){
	$rta="";
	$hoy=date('Y-m-d');
	 $t=['juventud_tipo_doc'=>'','juventud_documento'=>'','juventud_validacion1'=>'','juventud_validacion2'=>'','juventud_validacion3'=>'','juventud_validacion4'=>'','juventud_validacion6'=>'','juventud_validacion7'=>'',	 'juventud_validacion8'=>'','juventud_validacion9'=>'','juventud_validacion10'=>'','juventud_validacion11'=>'','juventud_validacion12'=>'','juventud_validacion13'=>'','juventud_validacion14'=>''];


	$w='eac_juventud';
	$id=divide($_POST['id']);
	 $d=get_eac_juventud(); 
	if ($d=="") {
		$d=$t;
		$u=true; 
		$rta.="<h1>Sin datos de Gestión en el plan de cuidado</h1>"; 
	 }else{
		
		$u=false;  
	 } 

	$o='datos';
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('idjuventud','h',15,$d['juventud_validacion1'],$w.' '.$o,'idjuventud','idjuventud',null,'####',false,false);
	$c[]=new cmp('juventud_documento','t','20',$id['0'],$w.' '.$o,'N° Identificación','juventud_documento',null,'',false,false,'','col-5');
	$c[]=new cmp('juventud_tipo_doc','t','20',$id['1'],$w.' '.$o,'Tipo Identificación','juventud_tipo_doc',null,'',false,false,'','col-5');
	
	$c[]=new cmp('validacion1','o','2',$d['juventud_validacion1'],$w.' '.$o,'1. Se recomienda a la familia que los jòvenes deben tener hábitos y estilos de vida saludables: prevención de la exposición al humo y cesaciòn de consumo de tabaco, organizaciòn de actividad fìsica regular, higiene del sueño y postural.','juventud_validacion1',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion2','o','2',$d['juventud_validacion2'],$w.' '.$o,'2. Se recomienda a la familia el  cuidado de oìdo y la visiòn icluyendo la revisiòn y apropiaciòn de pautas par el uso de pantallas y dispositivos de audio.','juventud_validacion2',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion3','o','2',$d['juventud_validacion3'],$w.' '.$o,'3. Se socializa a la familia las  pautas para el manejo del estrés y medidas preventivas de enfermedades laborales de acuerdo a la ocupación de los jóvenes.','juventud_validacion3',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion4','o','2',$d['juventud_validacion4'],$w.' '.$o,'4. se verifica o se ordena si el paciete tiene condiciones de riesgo y es candidato a la tamizacion de riesgo cardiovascular como lo establece la ruta de promocion y mantenimiento de la salud. (tenga en cuenta las escalas de valoracion de riesgo cardiovascular y los laboratorios pertinentes a realizar.','juventud_validacion4',null,'',true,$u,'','col-10');
	
	$c[]=new cmp('validacion6','o','2',$d['juventud_validacion6'],$w.' '.$o,'6. A las mujeres jòvenes de la familia se recomienda la realizaciòn de un autoexamen mamario para conocer el estado de las mamas suele ser la semana posterior a la finalización del período. CONSULTAR SI: • formación de hoyuelos, arrugas o bultos en la piel • cambio de posición de un pezón o pezón invertido (está metido hacia adentro en lugar de sobresalir) • enrojecimiento, dolor, sarpullido o inflamación.','juventud_validacion6',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion7','o','2',$d['juventud_validacion7'],$w.' '.$o,'7. Se orienta para la atención para la planificación familiar y la anticoncepción.','juventud_validacion7',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion8','o','2',$d['juventud_validacion8'],$w.' '.$o,'8. Se orienta para la atención para el cuidado preconcepcional.','juventud_validacion8',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion9','o','2',$d['juventud_validacion9'],$w.' '.$o,'9. Se recuerda a la familia la importancia del cuidado menstrual.','juventud_validacion9',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion10','o','2',$d['juventud_validacion10'],$w.' '.$o,'10. Se recomienda a las mujeres jòvenes de la familia consumir alimentos saludables,controlar el exceso de peso,Controlar  presión arterial y el colesterol.','juventud_validacion10',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion11','o','2',$d['juventud_validacion11'],$w.' '.$o,'11. se verifica o se ordena la tamizacion de cancer de cuello uterino teniendo en cuenta los riesgos y la periodicidad establecida en la ruta de promocion y mantenimiento de la salud.','juventud_validacion11',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion12','o','2',$d['juventud_validacion12'],$w.' '.$o,'12. Se verifica si el usuario cuenta con riesgo de ITS y ya se encuentra tamizado para estos riesgos en el marco de la ruta de promocion y mantenimiento de la salud.','juventud_validacion12',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion13','o','2',$d['juventud_validacion13'],$w.' '.$o,'13. se verifica si el usuario  ya cuenta con las actividades de salud oral a las que tiene derecho en el marco de la ruta de promocion y mantenimiento de la salud.','juventud_validacion13',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion14','o','2',$d['juventud_validacion14'],$w.' '.$o,'14. se verifica o se ordena las valoraciones de agudeza visual en el marco de la ruta de promocion y mantenimiento de la salud de acuerdo al curso de vida.','juventud_validacion14',null,'',true,$u,'','col-10');
	
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	
	return $rta;
   }

   
function get_eac_juventud(){
	if($_REQUEST['id']==''){
		return "";
	}else{
		 $id=divide($_REQUEST['id']);
		$sql="SELECT `juventud_tipo_doc`, `juventud_documento`, `juventud_validacion1`, `juventud_validacion2`, `juventud_validacion3`, `juventud_validacion4`,  `juventud_validacion6`, `juventud_validacion7`, `juventud_validacion8`, `juventud_validacion9`,`juventud_validacion10`, `juventud_validacion11`,`juventud_validacion12`,`juventud_validacion13`,`juventud_validacion14`,`usu_creo`, `fecha_create`, `usu_update`, `fecha_update` 
		FROM `eac_juventud`
		WHERE juventud_tipo_doc ='{$id[1]}' AND juventud_documento ='{$id[0]}'  ";
		 //echo $sql;
		$info=datos_mysql($sql);
		if(isset($info['responseResult'][0])){ 
				return $info['responseResult'][0];
		}else{
			return "";
		}
	} 
}

function focus_eac_juventud(){
	return 'eac_juventud';
   }
   
function men_eac_juventud(){
	$rta=cap_menus('eac_juventud','pro');
	return $rta;
   }
   
function gra_eac_juventud(){
	$juventud_documento=$_POST['juventud_documento'];
	$juventud_tipo_doc=$_POST['juventud_tipo_doc'];
	$idjuventud=$_POST['idjuventud'];
	//print_r($_POST);
	//die("Ok");
	if($idjuventud != "" ){ 
	
	$sql="UPDATE `eac_juventud` SET 
		`juventud_validacion1`=TRIM(UPPER('{$_POST['validacion1']}')),
		`juventud_validacion2`=TRIM(UPPER('{$_POST['validacion2']}')),
		`juventud_validacion3`=TRIM(UPPER('{$_POST['validacion3']}')),
		`juventud_validacion4`=TRIM(UPPER('{$_POST['validacion4']}')),
		
		`juventud_validacion6`=TRIM(UPPER('{$_POST['validacion6']}')),
		`juventud_validacion7`=TRIM(UPPER('{$_POST['validacion7']}')),
		`juventud_validacion8`=TRIM(UPPER('{$_POST['validacion8']}')),
		`juventud_validacion9`=TRIM(UPPER('{$_POST['validacion9']}')),
		`juventud_validacion10`=TRIM(UPPER('{$_POST['validacion10']}')),
		`juventud_validacion11`=TRIM(UPPER('{$_POST['validacion11']}')),
		`juventud_validacion12`=TRIM(UPPER('{$_POST['validacion12']}')),
		`juventud_validacion13`=TRIM(UPPER('{$_POST['validacion13']}')),
		`juventud_validacion14`=TRIM(UPPER('{$_POST['validacion14']}')),
		`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
		`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
		WHERE juventud_tipo_doc='$juventud_tipo_doc' AND juventud_documento='$juventud_documento'"; 
	  //echo $x;
	  //echo $sql."    ".$rta;

	}else{
		/* $tip=$_POST['p_infancia_tipo_doc'];
        $doc=$_POST['p_infancia_documento']; */
        if(get_atenc($juventud_tipo_doc,$juventud_documento)){
			$sql="INSERT INTO eac_juventud VALUES (NULL,
			'$juventud_tipo_doc',
			'$juventud_documento',
			TRIM(UPPER('{$_POST['validacion1']}')),
			TRIM(UPPER('{$_POST['validacion2']}')),
			TRIM(UPPER('{$_POST['validacion3']}')),
			TRIM(UPPER('{$_POST['validacion4']}')),
			TRIM(UPPER('{$_POST['validacion6']}')),
			TRIM(UPPER('{$_POST['validacion7']}')),
			TRIM(UPPER('{$_POST['validacion8']}')),
			TRIM(UPPER('{$_POST['validacion9']}')),
			TRIM(UPPER('{$_POST['validacion10']}')),
			TRIM(UPPER('{$_POST['validacion11']}')),
			TRIM(UPPER('{$_POST['validacion12']}')),
			TRIM(UPPER('{$_POST['validacion13']}')),
			TRIM(UPPER('{$_POST['validacion14']}')),
			TRIM(UPPER('{$_SESSION['us_sds']}')),
			DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL)";
		// echo $sql;
		$rta=dato_mysql($sql);
	}else{
	  $rta="Error: msj['Para realizar esta operacion, debe tener una atención previa, valida e intenta nuevamente']";
	}
	// echo $sql;
  }
	
  //   return "correctamente";
	return $rta; 
  }

////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

function cmp_eac_adultez(){
	$rta="";
	$hoy=date('Y-m-d');
	 $t=['adultez_tipo_doc'=>'','adultez_documento'=>'','adultez_validacion1'=>'','adultez_validacion2'=>'','adultez_validacion3'=>'','adultez_validacion4'=>'','adultez_validacion5'=>'','adultez_validacion6'=>'','adultez_validacion7'=>'','adultez_validacion8'=>'','adultez_validacion9'=>'','adultez_validacion10'=>'','adultez_validacion11'=>'','adultez_validacion12'=>'','adultez_validacion13'=>'']; 


	$w='eac_adultez';
	$id=divide($_POST['id']);
	 $d=get_eac_adultez(); 
	if ($d=="") {
		$d=$t;
		$u=true;  
		$rta.="<h1>Sin datos de Gestión en el plan de cuidado</h1>";
	 }else{
		
		$u=false;  
	 } 

	$o='datos';
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('idadultez','h',15,$d['adultez_validacion1'],$w.' '.$o,'idadultez','idadultez',null,'####',false,false);
	$c[]=new cmp('adultez_documento','t','20',$id['0'],$w.' '.$o,'N° Identificación','adultez_documento',null,'',false,false,'','col-5');
	$c[]=new cmp('adultez_tipo_doc','t','20',$id['1'],$w.' '.$o,'Tipo Identificación','adultez_tipo_doc',null,'',false,false,'','col-5');
	
	$c[]=new cmp('validacion1','o','2',$d['adultez_validacion1'],$w.' '.$o,'1. Se recomienda a los adultos de la familia consumir alimentos saludables,controlar el exceso de peso,Controlar  presión arterial y el colesterol.','adultez_validacion1',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion2','o','2',$d['adultez_validacion2'],$w.' '.$o,'2. A las mujeres adultas de la familia se les recomienda la realizaciòn de un un autoexamen mamario para conocer el estado de las mamas suele ser la semana posterior a la finalización del período.','adultez_validacion2',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion3','o','2',$d['adultez_validacion3'],$w.' '.$o,'3. Se orienta para la atención para la planificación familiar y la anticoncepción.','adultez_validacion3',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion4','o','2',$d['adultez_validacion4'],$w.' '.$o,'4. Se orienta para la atención para el cuidado preconcepcional.','adultez_validacion4',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion5','o','2',$d['adultez_validacion5'],$w.' '.$o,'5. Se recomienda a la familia que los adultos deben tener hábitos y estilos de vida saludables: prevención de la exposición al humo y cesaciòn de consumo de tabaco, organizaciòn de actividad fìsica regular, higiene del sueño y postural.','adultez_validacion5',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion6','o','2',$d['adultez_validacion6'],$w.' '.$o,'6. Se recuerda a la familia la importancia del cuidado menstrual.','adultez_validacion6',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion7','o','2',$d['adultez_validacion7'],$w.' '.$o,'7. se verifica que el usuario mayor de 40 años cuenta con el cuestionario simple de epoc como tamizacion de riesgo de patologia pulmonar.','adultez_validacion7',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion8','o','2',$d['adultez_validacion8'],$w.' '.$o,'8. se verifica o se ordena si el usuario tiene tamizacion de riesgo cardiovascular como lo establece la ruta de promocion y mantenimiento de la salud. (tenga en cuenta las escalas de valoracion de riesgo cardiovascular y los laboratorios pertinentes a realizar.','adultez_validacion8',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion9','o','2',$d['adultez_validacion9'],$w.' '.$o,'9. Tamización para cáncer de acuerdo a la periodicidad que establece la rurta de promocion y mantenimiento de la salud. (cancer de mama, prostata, cuello uterino y colon y recto)','adultez_validacion9',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion10','o','2',$d['adultez_validacion10'],$w.' '.$o,'10. se verifica o se ordena la tamizacion de cancer de cuello uterino teniendo en cuenta la periodicidad establecida en la ruta de promocion y mantenimiento de la salud.','adultez_validacion10',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion11','o','2',$d['adultez_validacion11'],$w.' '.$o,'11. Se verifica si el usuario cuenta con riesgo de ITS y ya se encuentra tamizado para estos riesgos en el marco de la ruta de promocion y mantenimiento de la salud.','adultez_validacion11',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion12','o','2',$d['adultez_validacion12'],$w.' '.$o,'12. se verifica si el usuario  ya cuenta con las actividades de salud oral a las que tiene derecho en el marco de la ruta de promocion y mantenimiento de la salud.','adultez_validacion12',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion13','o','2',$d['adultez_validacion13'],$w.' '.$o,'13. se verifica o se ordena la valoracion de agudeza visual en el marco de la ruta de promocion y mantenimiento de la salud de acuerdo al curso de vida ','adultez_validacion13',null,'',true,$u,'','col-10');
	
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	
	return $rta;
   }

   
function get_eac_adultez(){
	if($_REQUEST['id']==''){
		return "";
	}else{
		 $id=divide($_REQUEST['id']);
		$sql="SELECT `adultez_tipo_doc`, `adultez_documento`, `adultez_validacion1`, `adultez_validacion2`, `adultez_validacion3`, `adultez_validacion4`, `adultez_validacion5`, `adultez_validacion6`, `adultez_validacion7`, `adultez_validacion8`, `adultez_validacion9`,`adultez_validacion10`,`adultez_validacion11`,`adultez_validacion12`,`adultez_validacion13`,
		 `usu_creo`, `fecha_create`, `usu_update`, `fecha_update` 
		FROM `eac_adultez`
		WHERE adultez_tipo_doc ='{$id[1]}' AND adultez_documento ='{$id[0]}'  ";
		 //echo $sql;
		$info=datos_mysql($sql);
		if(isset($info['responseResult'][0])){ 
				return $info['responseResult'][0];
		}else{
			return "";
		}
	} 
}

function focus_eac_adultez(){
	return 'eac_adultez';
   }
   
function men_eac_adultez(){
	$rta=cap_menus('eac_adultez','pro');
	return $rta;
   }
   
function gra_eac_adultez(){
	$adultez_documento=$_POST['adultez_documento'];
	$adultez_tipo_doc=$_POST['adultez_tipo_doc'];
	$idadultez=$_POST['idadultez'];
	//print_r($_POST);
	//die("Ok");
	if($idadultez != "" ){ 
	
	$sql="UPDATE `eac_adultez` SET 
		`adultez_validacion1`=TRIM(UPPER('{$_POST['validacion1']}')),
		`adultez_validacion2`=TRIM(UPPER('{$_POST['validacion2']}')),
		`adultez_validacion3`=TRIM(UPPER('{$_POST['validacion3']}')),
		`adultez_validacion4`=TRIM(UPPER('{$_POST['validacion4']}')),
		`adultez_validacion5`=TRIM(UPPER('{$_POST['validacion5']}')),
		`adultez_validacion6`=TRIM(UPPER('{$_POST['validacion6']}')),
		`adultez_validacion7`=TRIM(UPPER('{$_POST['validacion7']}')),
		`adultez_validacion8`=TRIM(UPPER('{$_POST['validacion8']}')),
		`adultez_validacion9`=TRIM(UPPER('{$_POST['validacion9']}')),
		`adultez_validacion10`=TRIM(UPPER('{$_POST['validacion10']}')),
		`adultez_validacion11`=TRIM(UPPER('{$_POST['validacion11']}')),
		`adultez_validacion12`=TRIM(UPPER('{$_POST['validacion12']}')),
		`adultez_validacion13`=TRIM(UPPER('{$_POST['validacion13']}')),
		`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
		`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
		WHERE adultez_tipo_doc='$adultez_tipo_doc' AND adultez_documento='$adultez_documento'"; 
	  //echo $x;
	  //echo $sql."    ".$rta;

	}else{
		$tip=$_POST['adultez_tipo_doc'];
        $doc=$_POST['adultez_documento'];
        if(get_atenc($tip,$doc)){
			$sql="INSERT INTO eac_adultez VALUES (NULL,
			TRIM(UPPER('{$_POST['adultez_tipo_doc']}')),
			TRIM(UPPER('{$_POST['adultez_documento']}')),
			TRIM(UPPER('{$_POST['validacion1']}')),
			TRIM(UPPER('{$_POST['validacion2']}')),
			TRIM(UPPER('{$_POST['validacion3']}')),
			TRIM(UPPER('{$_POST['validacion4']}')),
			TRIM(UPPER('{$_POST['validacion5']}')),
			TRIM(UPPER('{$_POST['validacion6']}')),
			TRIM(UPPER('{$_POST['validacion7']}')),
			TRIM(UPPER('{$_POST['validacion8']}')),
			TRIM(UPPER('{$_POST['validacion9']}')),
			TRIM(UPPER('{$_POST['validacion10']}')),
			TRIM(UPPER('{$_POST['validacion11']}')),
			TRIM(UPPER('{$_POST['validacion12']}')),
			TRIM(UPPER('{$_POST['validacion13']}')),
			TRIM(UPPER('{$_SESSION['us_sds']}')),
			DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL)";
		//echo $sql;
		$rta=dato_mysql($sql);
	}else{
  		$rta="Error: msj['Para realizar esta operacion, debe tener una atención previa, valida e intenta nuevamente']";
	}
	// echo $sql;
	}
//   return "correctamente";
	return $rta; 
}
	function get_atenc($tip,$doc){
        $sql="SELECT atencion_idpersona FROM eac_atencion 
        WHERE atencion_tipodoc ='$tip' AND atencion_idpersona ='$doc'";
        // echo $sql;
        $info=datos_mysql($sql);
        if(isset($info['responseResult'][0])){ 
          return true;
        }else{
          return false;
        }
    }

	////////////////////////////////////////////////////////////////////////////////////////////////////
function cmp_eac_vejez(){
	$rta="";
	$hoy=date('Y-m-d');
	 $t=['vejez_tipo_doc'=>'','vejez_documento'=>'','vejez_validacion1'=>'','vejez_validacion2'=>'','vejez_validacion3'=>'',
	 'vejez_validacion4'=>'','vejez_validacion5'=>'','vejez_validacion6'=>'','vejez_validacion7'=>'',	 'vejez_validacion8'=>'',
	 'vejez_validacion9'=>'','vejez_validacion10'=>'','vejez_validacion11'=>'','vejez_validacion12'=>'', 'vejez_validacion13'=>'', 'vejez_validacion14'=>'']; 


	$w='eac_vejez';
	$id=divide($_POST['id']);
	 $d=get_eac_vejez(); 
	if ($d=="") {
		$d=$t;
		$u=true;  
		$rta.="<h1>Sin datos de Gestión en el plan de cuidado</h1>";
	 }else{
		
		$u=false;  
	 } 

	$o='datos';
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('idvejez','h',15,$d['vejez_validacion1'],$w.' '.$o,'idvejez','idvejez',null,'####',false,false);
	$c[]=new cmp('vejez_documento','t','20',$id['0'],$w.' '.$o,'N° Identificación','vejez_documento',null,'',false,false,'','col-5');
	$c[]=new cmp('vejez_tipo_doc','t','20',$id['1'],$w.' '.$o,'Tipo Identificación','vejez_tipo_doc',null,'',false,false,'','col-5');
	
	$c[]=new cmp('validacion1','o','2',$d['vejez_validacion1'],$w.' '.$o,'1. Se verfica con la familia el cumplimiento al esquema de vacunación vigente y el antecedente vacunal y se recuerda la importancia tener los esquemas de vacunaciòn completos.','vejez_validacion1',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion2','o','2',$d['vejez_validacion2'],$w.' '.$o,'2. Se recomienda a la familia que las personas mayores deben consumir alimentos saludables,controlar el exceso de peso,Controlar  presión arterial y el colesterol.','vejez_validacion2',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion3','o','2',$d['vejez_validacion3'],$w.' '.$o,'3. A las mujeres adultas de la familia se les recomienda la realización de un  autoexamen mamario para conocer el estado de las mamas para esta actividad la recomendacion es que la paciente seleccione un dia especifico del mes para su autoexamen mensual.','vejez_validacion3',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion4','o','2',$d['vejez_validacion4'],$w.' '.$o,'4. Se recomienda a la familia que todas las personas mayores de 60 años deben tener hábitos y estilos de vida saludables: prevención de la exposición al humo y cesaciòn de consumo de tabaco, organizaciòn de actividad fìsica','vejez_validacion4',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion5','o','2',$d['vejez_validacion5'],$w.' '.$o,'5. Se recomienda a la familia que las personas mayores de 60 años deben realizar actividades para el mantenimiento la de las funciones mentales superiores (lenguaje, razonamiento, cálculo, memoria, praxias, gnosías etc).','vejez_validacion5',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion6','o','2',$d['vejez_validacion6'],$w.' '.$o,'6. Órdenes médicas para atenciones individuales.','vejez_validacion6',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion7','o','2',$d['vejez_validacion7'],$w.' '.$o,'7. Tamizajes para población con algún tipo de riesgo.',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion8','o','2',$d['vejez_validacion8'],$w.' '.$o,'8. Se verifica que el usuario ya cuenta con el cuestionario simple de epoc como tamizacion de riesgo de patologia pulmonar ','vejez_validacion8',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion9','o','2',$d['vejez_validacion9'],$w.' '.$o,'9. Se verifica o se ordena si el usuario tiene tamizacion de riesgo cardiovascular como lo establece la ruta de promocion y mantenimiento de la salud. (tenga en cuenta las escalas de valoracion de riesgo cardiovascular y los laboratorios pertinentes a realizar.','vejez_validacion9',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion10','o','2',$d['vejez_validacion10'],$w.' '.$o,'10. Tamización para cáncer de acuerdo a la periodicidad que establece la ruta de promocion y mantenimiento de la salud. (cancer de mama, prostata, cuello uterino y colon y recto)','vejez_validacion10',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion11','o','2',$d['vejez_validacion11'],$w.' '.$o,'11. Se verifica o se ordena la tamizacion de cancer de cuello uterino teniendo en cuenta la periodicidad establecida en la ruta de promocion y mantenimiento de la salud.','vejez_validacion11',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion12','o','2',$d['vejez_validacion12'],$w.' '.$o,'12. Se verifica si el usuario cuenta con riesgo de ITS y ya se encuentra tamizado para estos riesgos en el marco de la ruta de promocion y mantenimiento de la salud.','vejez_validacion12',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion13','o','2',$d['vejez_validacion13'],$w.' '.$o,'13. Se verifica si el usuario  ya cuenta con las actividades de salud oral a las que tiene derecho en el marco de la ruta de promocion y mantenimiento de la salud.','vejez_validacion13',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion14','o','2',$d['vejez_validacion14'],$w.' '.$o,'14. Se verifica o se ordena la valoracion de agudeza visual en el marco de la ruta de promocion y mantenimiento de la salud de acuerdo al curso de vida.','vejez_validacion14',null,'',true,$u,'','col-10');
	
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	
	return $rta;
   }

   
function get_eac_vejez(){
	if($_REQUEST['id']==''){
		return "";
	}else{
		 $id=divide($_REQUEST['id']);
		$sql="SELECT `vejez_tipo_doc`, `vejez_documento`, `vejez_validacion1`, `vejez_validacion2`, `vejez_validacion3`, `vejez_validacion4`, `vejez_validacion5`, `vejez_validacion6`, `vejez_validacion7`, `vejez_validacion8`, `vejez_validacion9`,`vejez_validacion10`,`vejez_validacion11`,`vejez_validacion12`,`vejez_validacion13`,`vejez_validacion14`, `usu_creo`, `fecha_create`, `usu_update`, `fecha_update` 
		FROM `eac_vejez`
		WHERE vejez_tipo_doc ='{$id[1]}' AND vejez_documento ='{$id[0]}'  ";
		 //echo $sql;
		$info=datos_mysql($sql);
		if(isset($info['responseResult'][0])){ 
				return $info['responseResult'][0];
		}else{
			return "";
		}
	} 
}

function focus_eac_vejez(){
	return 'eac_vejez';
   }
   
function men_eac_vejez(){
	$rta=cap_menus('eac_vejez','pro');
	return $rta;
   }
   
function gra_eac_vejez(){
	$vejez_documento=$_POST['vejez_documento'];
	$vejez_tipo_doc=$_POST['vejez_tipo_doc'];
	$idvejez=$_POST['idvejez'];
	//print_r($_POST);
	//die("Ok");
	if($idvejez != "" ){ 
	
	$sql="UPDATE `eac_vejez` SET 
		`vejez_validacion1`=TRIM(UPPER('{$_POST['validacion1']}')),
		`vejez_validacion2`=TRIM(UPPER('{$_POST['validacion2']}')),
		`vejez_validacion3`=TRIM(UPPER('{$_POST['validacion3']}')),
		`vejez_validacion4`=TRIM(UPPER('{$_POST['validacion4']}')),
		`vejez_validacion5`=TRIM(UPPER('{$_POST['validacion5']}')),
		`vejez_validacion6`=TRIM(UPPER('{$_POST['validacion6']}')),
		`vejez_validacion7`=TRIM(UPPER('{$_POST['validacion7']}')),
		`vejez_validacion8`=TRIM(UPPER('{$_POST['validacion8']}')),
		`vejez_validacion9`=TRIM(UPPER('{$_POST['validacion9']}')),
		`vejez_validacion10`=TRIM(UPPER('{$_POST['validacion10']}')),
		`vejez_validacion11`=TRIM(UPPER('{$_POST['validacion11']}')),
		`vejez_validacion12`=TRIM(UPPER('{$_POST['validacion12']}')),
		`vejez_validacion13`=TRIM(UPPER('{$_POST['validacion13']}')),
		`vejez_validacion14`=TRIM(UPPER('{$_POST['validacion14']}')),
		`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
		`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
		WHERE vejez_tipo_doc='$vejez_tipo_doc' AND vejez_documento='$vejez_documento'"; 
	  //echo $x;
	  //echo $sql."    ".$rta;

	}else{
		$tip=$_POST['vejez_tipo_doc'];
        $doc=$_POST['vejez_documento'];
        if(get_atenc($tip,$doc)){
			$sql="INSERT INTO eac_vejez VALUES (NULL,
			TRIM(UPPER('{$_POST['vejez_tipo_doc']}')),
			TRIM(UPPER('{$_POST['vejez_documento']}')),
			TRIM(UPPER('{$_POST['validacion1']}')),
			TRIM(UPPER('{$_POST['validacion2']}')),
			TRIM(UPPER('{$_POST['validacion3']}')),
			TRIM(UPPER('{$_POST['validacion4']}')),
			TRIM(UPPER('{$_POST['validacion5']}')),
			TRIM(UPPER('{$_POST['validacion6']}')),
			TRIM(UPPER('{$_POST['validacion7']}')),
			TRIM(UPPER('{$_POST['validacion8']}')),
			TRIM(UPPER('{$_POST['validacion9']}')),
			TRIM(UPPER('{$_POST['validacion10']}')),
			TRIM(UPPER('{$_POST['validacion11']}')),
			TRIM(UPPER('{$_POST['validacion12']}')),
			TRIM(UPPER('{$_POST['validacion13']}')),
			TRIM(UPPER('{$_POST['validacion14']}')),
			TRIM(UPPER('{$_SESSION['us_sds']}')),
			DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL)";
		//echo $sql;
		$rta=dato_mysql($sql);
	}else{
	  $rta="Error: msj['Para realizar esta operacion, debe tener una atención previa, valida e intenta nuevamente']";
	}
	// echo $sql;
  }
  //   return "correctamente";
	return $rta; 
  }



	////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////////////////
function opc_estrategia($id=''){
	return opc_sql("SELECT idcatadeta,descripcion,valor FROM `catadeta` WHERE idcatalogo=203 and estado='A'  ORDER BY 1 ",$id);
}
function opc_prioridad($id=''){
	return opc_sql("SELECT idcatadeta,descripcion,valor FROM `catadeta` WHERE idcatalogo=201 and estado='A'  ORDER BY 1 ",$id);
}
function opc_vih($id=''){
	return opc_sql("SELECT idcatadeta,descripcion,valor FROM `catadeta` WHERE idcatalogo=187 and estado='A'  ORDER BY 1 ",$id);
}
function opc_rep($id=''){
	return opc_sql("SELECT idcatadeta,descripcion,valor FROM `catadeta` WHERE idcatalogo=188 and estado='A'  ORDER BY 1 ",$id);
}
function opc_aler($id=''){
	return opc_sql("SELECT `descripcion`,descripcion,valor FROM `catadeta` WHERE idcatalogo=170 and estado='A'  ORDER BY 1 ",$id);
}

function opc_tipo_consulta($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=182 and estado='A'  ORDER BY 1 ",$id);
}
	function opc_alarma5($id=''){
		return opc_sql("SELECT iddiagnostico,descripcion FROM `diagnosticos` WHERE `iddiag`='1' and estado='A' ORDER BY 2 ",$id);
	}
	function opc_nutricion5($id=''){
		return opc_sql("SELECT iddiagnostico,descripcion FROM `diagnosticos` WHERE `iddiag`='1' and estado='A' ORDER BY 2 ",$id);
	}
	function opc_letra1($id=''){
		return opc_sql("SELECT iddiagnostico,descripcion FROM `diagnosticos` WHERE `iddiag`='1' and estado='A' ORDER BY 2 ",$id);
	}

	function opc_rango1($id=''){
	/* 	print_r($_REQUEST);
		print_r($_POST);
		if (count(divide($_POST['id']))==2){
			return opc_sql("SELECT iddiagnostico,descripcion FROM `diagnosticos` WHERE `iddiag`='2' and estado='A' ORDER BY 1 ",$id);
		} */
	}
	function opc_diagnostico1($id=''){
	/* 	print_r($_POST);
		if (count(divide($_POST['id']))==2){
			return opc_sql("SELECT `iddiagnostico`,descripcion FROM `diagnosticos` WHERE `iddiag`='3' and estado='A'  ORDER BY descripcion ",$id);
		} */
	}

	function opc_letra2($id=''){
		return opc_sql("SELECT iddiagnostico,descripcion FROM `diagnosticos` WHERE `iddiag`='1' and estado='A' ORDER BY 2 ",$id);
	}

	function opc_rango2($id=''){
		/* if (count(divide($_POST['id']))==2){
			return opc_sql("SELECT iddiagnostico,concat(iddiagnostico,'-',descripcion) FROM `diagnosticos` WHERE `iddiag`='2' and estado='A' ORDER BY 1 ",$id);
		} */
	}
	function opc_diagnostico2($id=''){
		/* if (count(divide($_POST['id']))==2){
			return opc_sql("SELECT `iddiagnostico`,concat(iddiagnostico,'-',descripcion) FROM `diagnosticos` WHERE `iddiag`='3' and estado='A'  ORDER BY descripcion ",$id);
		} */
	}
	function opc_letra3($id=''){
		return opc_sql("SELECT iddiagnostico,descripcion FROM `diagnosticos` WHERE `iddiag`='1' and estado='A' ORDER BY 2 ",$id);
	}

	function opc_rango3($id=''){
		/* if (count(divide($_POST['id']))==2){
			return opc_sql("SELECT iddiagnostico,concat(iddiagnostico,'-',descripcion) FROM `diagnosticos` WHERE `iddiag`='2' and estado='A' ORDER BY 1 ",$id);
		} */
	}
	function opc_diagnostico3($id=''){
		/* if (count(divide($_POST['id']))==2){
			return opc_sql("SELECT `iddiagnostico`,concat(iddiagnostico,'-',descripcion) FROM `diagnosticos` WHERE `iddiag`='3' and estado='A'  ORDER BY descripcion ",$id);
		} */
	}

	function opc_letra1rango1(){
		if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT iddiagnostico 'id',descripcion 'asc' FROM `diagnosticos` WHERE iddiag='2' and estado='A' and valor='".$id[0]."' ORDER BY 1";
			$info=datos_mysql($sql);		
			return json_encode($info['responseResult']);
		} 
	}

	function opc_rango1diagnostico1(){
		if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT iddiagnostico 'id',descripcion 'asc' FROM `diagnosticos` WHERE iddiag='3' and estado='A' and valor='".$id[0]."' ORDER BY 1";
			$info=datos_mysql($sql);		
			// echo $_REQUEST['id'];
			return json_encode($info['responseResult']);
		} 
	}

	function opc_letra2rango2(){
		if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT iddiagnostico 'id',descripcion 'asc' FROM `diagnosticos` WHERE iddiag='2' and estado='A' and valor='".$id[0]."' ORDER BY 1";
			$info=datos_mysql($sql);		
			return json_encode($info['responseResult']);
		} 
	}

	function opc_rango2diagnostico2(){
		if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT iddiagnostico 'id',descripcion 'asc' FROM `diagnosticos` WHERE iddiag='3' and estado='A' and valor='".$id[0]."' ORDER BY 1";
			$info=datos_mysql($sql);		
			// echo $sql;
			return json_encode($info['responseResult']);
		} 
	}

		function opc_letra3rango3(){
		if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT iddiagnostico 'id',descripcion 'asc' FROM `diagnosticos` WHERE iddiag='2' and estado='A' and valor='".$id[0]."' ORDER BY 1";
			$info=datos_mysql($sql);		
			return json_encode($info['responseResult']);
		} 
	}

	function opc_rango3diagnostico3(){
		if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT iddiagnostico 'id',descripcion 'asc' FROM `diagnosticos` WHERE iddiag='3' and estado='A' and valor='".$id[0]."' ORDER BY 1";
			$info=datos_mysql($sql);		
			// echo $sql;
			return json_encode($info['responseResult']);
		} 
	}
		function opc_cups($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=126 and estado='A'  ORDER BY 1 ",$id);
	}

	function opc_consultamedica($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=127 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_metodoscons($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=129 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_rutapoblacion($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=131 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_remision($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=132 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_vacunacion($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=185 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_solicitud($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=133 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_medicamentos($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=186 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_evento($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=134 and estado='A'  ORDER BY 1 ",$id);
	}


	
	function get_condicion($c){
		// print_r($_POST);
			$id=divide($c);
			$sql="SELECT fertil,gestante,atencion_cronico cronico FROM eac_atencion where atencion_tipodoc='{$id[1]}' AND atencion_idpersona='{$id[0]}' order by fecha_create DESC limit 1";
			//  echo $sql;
			$info=datos_mysql($sql);
			// print_r($info['responseResult'][0]);
			if(isset($info['responseResult'][0])){
				// print_r($info['responseResult'][0]['gestante']);
				return $info['responseResult'][0];
			}else{
				return array();
			}
	}
	

function asigna_rutePsico(){
    $id=divide($_REQUEST['id']);
    $sql="INSERT INTO asigpsico VALUES 
	(NULL,TRIM(UPPER('{$id[1]}')),TRIM(UPPER('{$id[0]}')),1,NULL,TRIM(UPPER('{$_SESSION['us_sds']}')),TRIM(UPPER('{$_SESSION['us_sds']}')),
	DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'1');";
	$rta1=dato_mysql($sql);
	// echo $sql;
	$sql1="INSERT INTO eac_rutpsico VALUES
	(NULL,TRIM(UPPER('{$id[1]}')),TRIM(UPPER('{$id[0]}')),TRIM(UPPER('{$_SESSION['us_sds']}')),'SI',TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'1');";
	$rta2=dato_mysql($sql1);
	// echo $sql1;
	if (strpos($rta1, "Correctamente") && strpos($rta2, "Correctamente")  !== false) {
		$rta = "Correctamente";
	} else {
		$rta = "Error:";
	}
	return $rta;
}
	
////////////////////////////////////////////////////atencion//////////////////////////////////////////////

function estado($id){
	$sql="select id_eacfam FROM eac_fam where cod_fam='".$id."' AND estado_fam='1'";
	$info=datos_mysql($sql);
	if(isset($info['responseResult'][0])){
	  return true;
	}else{
	  return false;
	}
  }

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// print_r($c);
// var_dump($a);
	if ($a=='homes' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono casa' title='Caracterización del Hogar' id='".$c['ACCIONES']."' Onclick=\"mostrar('homes1','fix',event,'','lib.php',0,'homes1');hideFix('person1','fix');Color('homes-lis');\"></li>";//setTimeout(mostrar('person1','fix',event,'','lib.php',0,'person1'),500);
		$rta.="<li class='icono crear' title='Crear Familia' id='".$c['ACCIONES']."' Onclick=\"mostrar('homes','pro',event,'','lib.php',7,'homes');setTimeout(DisableUpdate,300,'fechaupd','hid');Color('homes-lis');\"></li>";
	}
	if ($a=='famili-lis' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('homes','pro',event,'','lib.php',7,'homes');setTimeout(getData,300,'homes',event,this,['idviv','numfam','estado_aux']);Color('famili-lis');\"></li>";  //act_lista(f,this);
		$rta.="<li class='icono actimed' title='Estado Familia' id='".$c['ACCIONES']."' Onclick=\"mostrar('statFam','pro',event,'','stateFami.php',5,'stateFami');Color('famili-lis');\"></li>";
		if(estado($c['Cod_Familiar'])===true){			
			$rta.="<li class='icono familia' title='Integrantes Personas' id='".$c['ACCIONES']."' Onclick=\"mostrar('person1','fix',event,'','lib.php',0,'person1');Color('famili-lis');\"></li>";//setTimeout(plegar,500);mostrar('person','pro',event,'','lib.php',7);
			$rta.="<li class='icono crear' title='Crear Integrante Familia' id='".$c['ACCIONES']."' Onclick=\"mostrar('person','pro',event,'','lib.php',7,'person');setTimeout(disabledCmp,300,'cmhi');setTimeout(enabLoca('reside_localidad','lochi'),300);Color('famili-lis');\"></li>";
		}
	}
	if ($a=='datos-lis' && $b=='acciones'){
		$rta="<nav class='menu right'>";
		$rta.="<li class='icono editar' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('person','pro',event,'','lib.php',7,'person');setTimeout(getData,500,'person',event,this,['idpersona','tipo_doc','fecha_nacimiento','sexo']);Color('datos-lis');setTimeout(enabAfil,700,'regimen','eaf');setTimeout(enabEtni,700,'etnia','ocu','idi');setTimeout(enabLoca,700,'reside_localidad','lochi');setTimeout(EditOcup,800,'ocupacion','true');\"></li>";//setTimeout(enabEapb,700,'regimen','rgm');
		$rutepsico = (acceso('rutePsico')) ? "<li class='icono asigna1' title='Asigna Psicologia-Ruteo' id='".$c['ACCIONES']."' Onclick=\"rutePsico('{$c['ACCIONES']}');Color('datos-lis');\"></li>" : "" ;
		$rta.=$rutepsico;

		$admision = (acceso('admision')) ? "<li class='icono admsi1' title='Crear Admisión' id='".$c['ACCIONES']."' Onclick=\"mostrar('admision','pro',event,'','admision.php',7,'admision');Color('datos-lis');\"></li>" : "" ;
		$rta.=$admision;

		$atencion = (acceso('atencion')) ? "<li class='icono aten1' title='Crear Atención' id='".$c['ACCIONES']."' Onclick=\"mostrar('atencion','pro',event,'','lib.php',7,'atencion');\"></li>" : "" ;
		$rta.=$atencion;

		if (perfil1()=='MEDATE' || perfil1()=='ADM' || perfil1()=='ENFATE'|| perfil1()=='ADMEAC' || perfil1()=='SUPEAC' || perfil1()=='RELENF' ){
		//$rta.="<li class='icono admsi1' title='Crear Admisión' id='".$c['ACCIONES']."' Onclick=\"mostrar('admision','pro',event,'','admision.php',7,'admision');Color('datos-lis');\"></li>";
		//$rta.="<li class='icono aten1' title='Crear Atención' id='".$c['ACCIONES']."' Onclick=\"mostrar('atencion','pro',event,'','lib.php',7,'atencion');\"></li>";//Color('datos-lis');
		if($c['edad actual'] >= '0' && $c['edad actual'] <'6'){
			$rta.="<li class='icono aterm1' title='PRIMERA INFANCIA' id='".$c['ACCIONES']."' Onclick=\"mostrar('prinfancia','pro',event,'','prinfancia.php',7,'prinfancia');Color('datos-lis');\"></li>";
		}
		if($c['edad actual'] > '5' && $c['edad actual'] <='11'){
			$rta.="<li class='icono canin1' title='INFANCIA' id='".$c['ACCIONES']."' Onclick=\"mostrar('infancia','pro',event,'','infancia.php',7,'infancia');Color('datos-lis');\"></li>";
		}else if($c['edad actual'] > '11' && $c['edad actual'] <='17'){
			$rta.="<li class='icono adol1' title='ADOLESCENCIA' id='".$c['ACCIONES']."' Onclick=\"mostrar('adolesce','pro',event,'','adolescencia.php',7,'adolesce');Color('datos-lis');\"></li>";
		}else if($c['edad actual'] > '17' && $c['edad actual'] <='28' ){
			$rta.="<li class='icono juve1' title='JUVENTUD' id='".$c['ACCIONES']."' Onclick=\"mostrar('eac_juventud','pro',event,'','lib.php',7,'eac_juventud');Color('datos-lis');\"></li>";
		}else if($c['edad actual'] > '28' && $c['edad actual'] <='59'){
			$rta.="<li class='icono adul1' title='ADULTEZ' id='".$c['ACCIONES']."' Onclick=\"mostrar('eac_adultez','pro',event,'','lib.php',7,'eac_adultez');Color('datos-lis');\"></li>";
		}else if($c['edad actual'] > '59' ){
			$rta.="<li class='icono veje1' title='VEJEZ' id='".$c['ACCIONES']."' Onclick=\"mostrar('eac_vejez','pro',event,'','lib.php',7,'eac_vejez');Color('datos-lis');\"></li>";
		}
		
		if(($c['edad actual'] > '10' && $c['edad actual'] <= '54') && $c['sexo'] == 'MUJER'){
			$rta.= (!empty(get_condicion($c['ACCIONES'])) && get_condicion($c['ACCIONES'])['gestante']=='SI') ? "<li class='icono gesta1' title='GESTANTES' id='".$c['ACCIONES']."' Onclick=\"mostrar('pregnant','pro',event,'','gestantes.php',7,'pregnant');Color('datos-lis');setTimeout(hidFieOpt('gestante','ges_hide',this,true),2000);\"></li>" : '' ;
		}
		$rta.= (!empty(get_condicion($c['ACCIONES'])) && get_condicion($c['ACCIONES'])['cronico']=='SI') ? "<li class='icono cronic' title='Cronicos' id='".$c['ACCIONES']."' Onclick=\"mostrar('prechronic','pro',event,'','cronicos.php',7,'prechronic');Color('datos-lis');\"></li>" : '' ;
		}
	}
	if($a=='atencion' && $b=='acciones'){
		$rta="<nav class='menu right'>";
		$rta.="<li class='icono editar ' title='Editar Atención' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,1000,'atencion',event,this,['idpersona','tipo_doc']);setTimeout(getData,1300,'atencion',event,this,['idpersona','tipo_doc']);setTimeout(getData,1500,'atencion',event,this,['idpersona','tipo_doc']);setTimeout(changeSelect,1100,'letra1','rango1');setTimeout(changeSelect,1150,'letra2','rango2');setTimeout(changeSelect,1280,'letra3','rango3');setTimeout(changeSelect,1385,'rango1','diagnostico1');setTimeout(changeSelect,1385,'rango2','diagnostico2');setTimeout(changeSelect,1385,'rango3','diagnostico3');Color('datos-lis');\"></li>";	//
	}
	if($a=='planc-lis' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"Color('planc-lis');\"></li>";  //getData('plancon',event,this,'id');   act_lista(f,this);
	}
	/* if ($a=='admision-lis' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
			$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'admision',event,this,['fecha','tipo_activi'],'amb.php');\"></li>";  //   act_lista(f,this);
	} */
 return $rta;
}

function bgcolor($a,$c,$f='c'){
	$rta = 'red';
	if ($a=='datos-lis'){
		if($c['Cronico']==='SIN'){
			return ($rta !== '') ? "style='background-color: $rta;'" : '';
		}
		if($c['Gestante']==='SIN'){
			return ($rta !== '') ? "style='background-color: $rta;'" : '';
		}
	}
}