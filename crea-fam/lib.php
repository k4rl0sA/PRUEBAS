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
		SELECT G.idgeo AS ACCIONES,G.idgeo AS Cod_Predio,H.direccion,H.sector_catastral Sector,H.nummanzana AS Manzana,H.predio_num AS predio,H.unidad_habit AS 'Unidad',FN_CATALOGODESC(2,H.localidad) AS 'Localidad',U1.nombre,G.fecha_create,FN_CATALOGODESC(44,G.estado_v) AS estado 
		FROM geo_gest G	LEFT JOIN hog_geo H ON G.idgeo = H.idgeo LEFT JOIN usuarios U ON H.subred = U.subred	LEFT JOIN usuarios U1 ON H.usu_creo = U1.id_usuario
			WHERE G.estado_v IN ('7') ".whe_homes()."
			AND U.id_usuario = '{$_SESSION['us_sds']}'
) AS Subquery";
	$info=datos_mysql($total);
	$total=$info['responseResult'][0]['total']; 
	$regxPag=5;
	$pag=(isset($_POST['pag-homes']))? ($_POST['pag-homes']-1)* $regxPag:0;

	
$sql="SELECT G.idgeo AS ACCIONES,
	G.idgeo AS Cod_Predio,
	H.direccion,
	H.sector_catastral Sector,
	H.nummanzana AS Manzana,
	H.predio_num AS predio,
	H.unidad_habit AS 'Unidad',
	FN_CATALOGODESC(2,H.localidad) AS 'Localidad',
	U1.nombre,
	G.fecha_create,
	FN_CATALOGODESC(44,G.estado_v) AS estado
	FROM geo_gest G
	LEFT JOIN hog_geo H ON G.idgeo = H.idgeo
	LEFT JOIN usuarios U ON H.subred = U.subred
	LEFT JOIN usuarios U1 ON H.usu_creo = U1.id_usuario
WHERE G.estado_v in('7') ".whe_homes()." 
	AND U.id_usuario = '{$_SESSION['us_sds']}'
	ORDER BY nummanzana, predio_num
	LIMIT $pag, $regxPag";
// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"homes",$regxPag);
}


function whe_homes() {
	$fefin=date('Y-m-d');
	$feini = date("Y-m-d",strtotime($fefin."- 2 days"));
	$sql = "";
	if (!empty($_POST['fpred']) && $_POST['fdigita']) {
		$sql .= " AND G.idgeo = '" . $_POST['fpred'] . "' AND G.usu_creo ='" . $_POST['fdigita'] . "'";
	}else{
		$sql .="AND G.idgeo ='0'";
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
	$sql="SELECT id_fam ACCIONES,id_fam AS Cod_Familiar,numfam AS N°_FAMILIA,fecha,CONCAT_WS(' ',FN_CATALOGODESC(6,complemento1),nuc1,FN_CATALOGODESC(6,complemento2),nuc2,FN_CATALOGODESC(6,complemento3),nuc3) Complementos,
		V.fecha_create Creado,nombre Creó
		FROM `hog_fam` V 
			LEFT JOIN usuarios P ON V.usu_create=id_usuario
			LEFT JOIN hog_carac C ON V.id_fam=C.idfam
		WHERE idpre='".$_POST['id'];
		$sql.="' ORDER BY V.fecha_create";
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
	$t=['complemento1'=>'','nuc1'=>'','complemento2'=>'','nuc2'=>'','complemento3'=>'','nuc3'=>'','telefono1'=>'','telefono2'=>'','telefono3'=>''];
	$w='homes';
	$d = get_homes();
	if ($d==""){$d=$t;}
	$o='inf';
	// var_dump($_REQUEST);
	$c[]=new cmp($o,'e',null,'INFORMACIÓN COMPLEMENTARIA DE LA VIVIENDA',$w);
	$c[]=new cmp('idg','h',15,$_POST['id'],$w.' '.$o,'id','idg',null,'####',false,false);
	// $c[]=new cmp('numfam','s',3,$numf,$w.' '.$o,'Número de Familia','numfam',null,'',false,false,'','col-2');
	$c[]=new cmp('complemento1','s','3',$d['complemento1'],$w.' '.$o,'complemento1','complemento',null,'',true,true,'','col-2');
    $c[]=new cmp('nuc1','t','4',$d['nuc1'],$w.' '.$o,'nuc1','nuc1',null,'',true,true,'','col-1');
 	$c[]=new cmp('complemento2','s','3',$d['complemento2'],$w.' '.$o,'complemento2','complemento',null,'',false,true,'','col-2');
 	$c[]=new cmp('nuc2','t','4',$d['nuc2'],$w.' '.$o,'nuc2','nuc2',null,'',false,true,'','col-15');
 	$c[]=new cmp('complemento3','s','3',$d['complemento3'],$w.' '.$o,'complemento3','complemento',null,'',false,true,'','col-2');
 	$c[]=new cmp('nuc3','t','4',$d['nuc3'],$w.' '.$o,'nuc3','nuc3',null,'',false,true,'','col-15');
	$c[]=new cmp('telefono1','n','10',$d['telefono1'],$w.' '.$o,'telefono1','telefono1','rgxphone',NULL,true,true,'','col-3');
	$c[]=new cmp('telefono2','n','10',$d['telefono2'],$w.' '.$o,'telefono2','telefono2','rgxphone1',null,false,true,'','col-3');
	$c[]=new cmp('telefono3','n','10',$d['telefono3'],$w.' '.$o,'telefono3','telefono3','rgxphone1',null,false,true,'','col-4');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function get_homes(){
	$id=divide($_REQUEST['id']);
	if($_REQUEST['id']=='' || count($id)!=2){
		return "";
	}else{
		$sql="SELECT id_fam,complemento1,nuc1,complemento2,nuc2,complemento3,nuc3,telefono1,telefono2,telefono3
		FROM `hog_fam` 
		WHERE id_fam ='{$id[1]}'";
		// echo $sql;
		print_r($id);
		$info=datos_mysql($sql);
		if (!$info['responseResult']) {
			return '';
		}
	return json_encode($info['responseResult'][0]);
	} 
}

function num_fam(){
	if($_POST['idg']==''){
		return "";
	}else{
		$id=$_POST['idg'];
		$sql="SELECT max(numfam) nfam
		FROM  hog_fam
		WHERE idpre=$id";
		// echo $sql;
		//print_r($id);
		$info=datos_mysql($sql);
		if (!$info['responseResult']) {
			return '';
		}
		$nf = json_encode($info['responseResult'][0]['nfam']);
	if (is_null($nf)) {
		$numf = 1;
	} else {
		$nf_limpio = preg_replace('/\D/', '', $nf);
		if ($nf_limpio === '') {
			$n = 0;
		} else {
			$n = intval($nf_limpio);
		}
		$numf = $n + 1;
	}
	return $numf;
	} 
}

function namequipo(){
		$sql="SELECT equipo FROM  usuarios WHERE id_usuario='".$_SESSION['us_sds']."'";
		// echo $sql;
		//print_r($id);
		$info=datos_mysql($sql);
		if (!$info['responseResult']) {
			return '';
		}
		return $info['responseResult'][0]['equipo'];
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
	$id=$_POST['idg'];
	$sql = "INSERT INTO hog_fam VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	$params = array(
	array('type' => 'i', 'value' => NULL),
	array('type' => 'i', 'value' => $id),
	array('type' => 'i', 'value' => num_fam()),
	array('type' => 's', 'value' => $_POST['complemento1']),
	array('type' => 's', 'value' => $_POST['nuc1']),
	array('type' => 's', 'value' => $_POST['complemento2']),
	array('type' => 's', 'value' => $_POST['nuc1']),
	array('type' => 's', 'value' => $_POST['complemento3']),
	array('type' => 's', 'value' => $_POST['nuc1']),
	array('type' => 's', 'value' => $_POST['telefono1']),
	array('type' => 's', 'value' => $_POST['telefono2']),
	array('type' => 's', 'value' => $_POST['telefono3']),
	array('type' => 's', 'value' => namequipo()),
	array('type' => 'i', 'value' => $_SESSION['us_sds']),
	array('type' => 's', 'value' => date("Y-m-d H:i:s")),
	array('type' => 's', 'value' => NULL),
	array('type' => 's', 'value' => NULL),
	array('type' => 's', 'value' => 'A')
	);
	// var_dump($params);
	$rta = mysql_prepd($sql, $params);
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
		$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."_".$c['Cod_Familiar']."' Onclick=\"mostrar('homes','pro',event,'','lib.php',7,'homes');setTimeout(getData,300,'homes',event,this,['idviv','numfam','estado_aux']);Color('famili-lis');\"></li>";  //act_lista(f,this);
		$rta.="<li class='icono familia' title='Integrantes Personas' id='".$c['ACCIONES']."' Onclick=\"mostrar('person1','fix',event,'','lib.php',0,'person1');Color('famili-lis');\"></li>";//setTimeout(plegar,500);mostrar('person','pro',event,'','lib.php',7);
			$rta.="<li class='icono crear' title='Crear Integrante Familia' id='".$c['ACCIONES']."' Onclick=\"mostrar('person','pro',event,'','lib.php',7,'person');setTimeout(disabledCmp,300,'cmhi');setTimeout(enabLoca('reside_localidad','lochi'),300);Color('famili-lis');\"></li>";
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
