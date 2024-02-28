<?php
 require_once '../libs/gestion.php';
ini_set('display_errors','1');
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



function lis_gestusu(){
	$info=datos_mysql("SELECT COUNT(*) total FROM adm_usunew
	WHERE 1 ".whe_gestusu());
	$total=$info['responseResult'][0]['total'];
	$regxPag=10;
	$pag=(isset($_POST['pag-gestusu']))? ($_POST['pag-gestusu']-1)* $regxPag:0; 

	$sql="SELECT *
	FROM adm_usunew 
	 WHERE 1  ";
	$sql.=whe_gestusu();
	$sql.=" ORDER BY fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"gestusu",$regxPag);
} 

function whe_gestusu() {
	$sql = "";
	if ($_POST['festado'] && $_POST['festado']=='NULL' )
		$sql .= " AND estado  IS NULL ";
	return $sql;
}


function focus_gestusu(){
 return 'gestusu';
}

function men_gestusu(){
 $rta=cap_menus('gestusu','pro');
 return $rta;
} 


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='gestusu'){  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
  	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  }
  return $rta;
}


function cmp_gestusu(){
	$rta="";
	$hoy=date('Y-m-d');
	$t=['gestion'=>'','perfil'=>'','documento'=>'','nombre'=>'','correo'=>'','bina'=>'','territorio'=>''];
	$d='';
	if ($d==""){$d=$t;}
	$w='adm_usuarios';
	$o='creusu';
	$c[]=new cmp($o,'e',null,'GESTIÓN DE USUARIOS',$w);
	$c[]=new cmp('gestion','s','3',$d['gestion'],$w.' '.$o,'Acción','gestion',null,'',true,true,'','col-2',"enabLoca('gestion','GsT');enClSe('gestion','GsT',[['Rpw'],['Rpw'],['cUS'],['cRL']]);");
	$c[]=new cmp('perfil','s',3,$d['perfil'],$w.' '.$o,'Perfil','perfil',null,'',true,true,'','col-1',"enabDepeValu('perfil','TEr',['2','6','7'],false);enabDepeValu('perfil','bIN',['4'],false);");//enabDepeValu('perfil','bIN',['4']);
	$c[]=new cmp('documento','n',20,$d['documento'],$w.' '.$o,'N° Documento','documento',null,'',false,true,'','col-15');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function get_gestusu(){
	/* if($_POST['idgeo']=='0'){
		return "";
	}else{
		$id=divide($_POST['idgeo']);
		$sql="SELECT estrategia,subred,zona,localidad,upz,barrio,territorio,microterritorio,sector_catastral,direccion,direccion_nueva,nummanzana,predio_num,unidad_habit,vereda,vereda_nueva,
		cordx,cordy,estrato,asignado,estado_v,motivo_estado 
		FROM `hog_geo` WHERE  estrategia='{$id[0]}' AND sector_catastral='{$id[1]}' AND nummanzana='{$id[2]}' AND predio_num='{$id[3]}' AND unidad_habit='{$id[4]}' AND estado_v='{$id[5]}'";

		$info=datos_mysql($sql);
		return $info['responseResult'][0];
	}  */
}

function gra_gestusu(){
  $sql = "INSERT INTO adm_usunew VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
   $rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
   $usu=divide($rta["responseResult"][0]['usu']);

   $rta=datos_mysql("select FN_CATALOGODESC(218,'".$_POST['perfil']."') AS perfil ,FN_CATALOGODESC(202,'".$_POST['territorio']."') AS terr,FN_CATALOGODESC(217,'".$_POST['bina']."') AS bina;");
   $data=$rta["responseResult"][0];

   $params = [
	['type' => 'i', 'value' => NULL],
	['type' => 'i', 'value' => $_POST['documento']],
	['type' => 's', 'value' => $_POST['nombre']],
	['type' => 's', 'value' => $_POST['correo']],
	['type' => 's', 'value' => $data['perfil']],
	['type' => 's', 'value' => $data['terr']],
	['type' => 's', 'value' => $data['bina']],
	['type' => 'i', 'value' => $usu[2]],
	['type' => 's', 'value' => $usu[4]],
	['type' => 'i', 'value' => $_SESSION['us_sds']],
	['type' => 's', 'value' => date("Y-m-d H:i:s")],
	['type' => 's', 'value' => NULL],
	['type' => 's', 'value' => NULL],
	['type' => 's', 'value' => NULL]];
	$rta1 = mysql_prepd($sql, $params);

	$sql1 = "INSERT INTO usuarios VALUES (?,?,?,?,?,?,?,?,?)";
	$equ = ($data['bina']=='') ? $data['terr'] : $data['bina'] ;
	$params1 = [
		['type' => 'i', 'value' => $_POST['documento']],
		['type' => 's', 'value' => $_POST['nombre']],
		['type' => 's', 'value' => $_POST['correo']],
		['type' => 's', 'value' => '$2y$10$U1.jyIhJweaZQlJK6jFauOAeLxEOTJX8hlWzJ6wF5YVbYiNk1xfma'],
		['type' => 's', 'value' => $data['perfil']],
		['type' => 'i', 'value' => $usu[2]],
		['type' => 's', 'value' => $equ],
		['type' => 's', 'value' => $usu[4]],
		['type' => 's', 'value' => 'P']];
		$rta2 = mysql_prepd($sql1, $params1);

	if (strpos($rta1, "Correctamente") && strpos($rta2, "Correctamente")  !== false) {
		$rta = "Se ha Insertado: 1 Registro Correctamente.";
	} else {
		$rta = "Error: msj['No se puede crear la solicitud, el usuario ya se ha creado anteriormente']";
	}
	return $rta;
}


function opc_perfil($id=''){
	$com=datos_mysql("SELECT CASE WHEN componente = 'EAC' THEN 2 WHEN componente = 'HOG' THEN 1 END as componente FROM usuarios WHERE id_usuario ='{$_SESSION['us_sds']}'");
	$comp = $com['responseResult'][0]['componente'] ;
	// return $comp;
	// var_dump("SELECT CASE WHEN componente = 'EAC' THEN 2 WHEN componente = 'HOG' THEN 1 END as componente FROM usuarios WHERE id_usuario ='{$_SESSION['us_sds']}'");
	return opc_sql("SELECT idcatadeta, descripcion FROM `catadeta` WHERE idcatalogo = 218 AND estado = 'A' AND valor='$comp'",$id);
}
function opc_gestion($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=216 and estado='A' ORDER BY 1",$id);
}





function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='gestusu' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono asigna1' title='Asignar Usuario' id='".$c['ACCIONES']."' Onclick=\"mostrar('gestusu','pro',event,'','lib.php',7);\"></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
