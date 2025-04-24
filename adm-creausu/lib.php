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

function lis_creausu(){
	$info=datos_mysql("SELECT COUNT(*) total FROM adm_usunew
	WHERE 1 ".whe_creausu());
	$total=$info['responseResult'][0]['total'];
	$regxPag=10;
	$pag=(isset($_POST['pag-creausu']))? ($_POST['pag-creausu']-1)* $regxPag:0; 

	
	$sql="SELECT id_usu Caso,DOCUMENTO,NOMBRES,CORREO,PERFIL,TERRITORIO,BINA,COMPONENTE,USU_CREO CREO,FECHA_CREATE CREO,ESTADO
	FROM adm_usunew 
	 WHERE 1 ";
	$sql.=whe_creausu();
	$sql.=" ORDER BY fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"creausu",$regxPag);
} 

function whe_creausu() {
    $sql = "";
    if ($_POST['fcaso']) {
        $sql .= " AND id_usu = '" . $_POST['fcaso'] . "'";
    } elseif ($_POST['fdoc']) {
        $sql .= " AND documento LIKE '%" . $_POST['fdoc'] . "%'";
    } else {
        $sql .= " AND DATE(fecha_create) BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND CURDATE() AND SUBRED=(select subred from usuarios where id_usuario='" . $_SESSION['us_sds'] . "')";
    }
    return $sql;
}


function focus_creausu(){
 return 'creausu';
}

function men_creausu(){
 $rta=cap_menus('creausu','pro');
 return $rta;
} 


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='creausu'){  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
  	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  }
  return $rta;
}


function cmp_creausu(){
	$rta="";
	$hoy=date('Y-m-d');
	$t=['gestion'=>'','perfil'=>'','documento'=>'','nombre'=>'','correo'=>'','bina'=>'','territorio'=>''];
	$d='';
	if ($d==""){$d=$t;}
	$w='adm_usuarios';
	$o='creusu';
	$c[]=new cmp($o,'e',null,'GESTIÓN DE USUARIOS',$w);
	$c[]=new cmp('documento','n',20,$d['documento'],$w.' '.$o,'N° Documento','documento',null,'',false,true,'','col-15');
	$c[]=new cmp('nombre','t',50,$d['nombre'],$w.' '.$o,'Nombres y Apellidos','nombre',null,'',false,true,'','col-3');
	$c[]=new cmp('correo','t',30,$d['correo'],$w.' '.$o,'Correo','correo',null,'',false,true,'','col-25');
	$c[]=new cmp('perfil','s',3,$d['perfil'],$w.' '.$o,'Perfil','perfil',null,'',true,true,'','col-1',"enabDepeValu('perfil','TEr',['2','6','7'],false);enabDepeValu('perfil','bIN',['4'],false);");//enabDepeValu('perfil','bIN',['4']);
	$c[]=new cmp('bina','s',3,$d['bina'],$w.' bIN '.$o,'Bina','bina',null,'',false,false,'','col-2');
	$c[]=new cmp('territorio','s',3,$d['territorio'],$w.' TEr '.$o,'Territorio','territorio',null,'',false,false,'','col-2');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function get_creausu(){
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

function gra_creausu(){
  
	$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
	$usu=divide($rta["responseResult"][0]['usu']);
 
	$rta=datos_mysql("select FN_CATALOGODESC(218,'".$_POST['perfil']."') AS perfil ,FN_CATALOGODESC(202,'".$_POST['territorio']."') AS terr,FN_CATALOGODESC(217,'".$_POST['bina']."') AS bina;");
	$data=$rta["responseResult"][0];


	$sql1 = "INSERT INTO usuarios VALUES (?,?,?,?,?,?,?,?,?)";
	if (isset($data['bina'])) {
		$equ =$data['bina'];
	} elseif(isset($data['terr'])) {
		$equ =$data['terr'];
	}else{
		$equ ='';
	}
	
	$params1 = [
		['type' => 'i', 'value' => $_POST['documento']],
		['type' => 's', 'value' => $_POST['nombre']],
		['type' => 's', 'value' => $_POST['correo']],
		['type' => 'z', 'value' => '$2y$10$U1.jyIhJweaZQlJK6jFauOAeLxEOTJX8hlWzJ6wF5YVbYiNk1xfma'],
		['type' => 's', 'value' => $data['perfil']],
		['type' => 'i', 'value' => $usu[2]],
		['type' => 's', 'value' => $equ],
		['type' => 's', 'value' => $usu[4]],
		['type' => 's', 'value' => 'P']];
		$rta2 = mysql_prepd($sql1, $params1);

	if (strpos($rta2, "Correctamente")!== false) {
		$rta = "Se ha Insertado: 1 Registro Correctamente.";
		$sql = "INSERT INTO adm_usunew VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
   

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
	['type' => 's', 'value' => 'R']];
	$rta1 = mysql_prepd($sql, $params);
	} else {
		$rta = "Error: msj['No se puede crear la solicitud, el usuario ya se ha creado anteriormente']";
	}
	return $rta;
}


function opc_perfil($id=''){
	$info=datos_mysql("SELECT perfil FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}'");
	$adm=$info['responseResult'][0]['perfil'];
	if ($adm=='ADM') {
		return opc_sql("SELECT idcatadeta, descripcion FROM `catadeta` WHERE idcatalogo = 218 AND estado = 'A'",$id);
	}else {
		$sql ="SELECT CASE WHEN componente = 'EAC' THEN 2 WHEN componente = 'HOG' THEN 1 END as componente FROM usuarios WHERE id_usuario ='{$_SESSION['us_sds']}'";
		$com=datos_mysql($sql);
		$comp = $com['responseResult'][0]['componente'];
		return opc_sql("SELECT idcatadeta, descripcion FROM `catadeta` WHERE idcatalogo = 218 AND estado = 'A' AND valor IN('{$comp}')",$id);
	}
}

function opc_bina($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=217 and estado='A' and valor=(SELECT subred FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}') ORDER BY CAST(idcatadeta AS UNSIGNED)",$id);
}
function opc_territorio($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=202 and estado='A' and valor=(SELECT subred FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}') ORDER BY CAST(idcatadeta AS UNSIGNED)",$id);
}
function opc_subred($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=72 and estado='A' and idcatadeta in(1,2,4,3) ORDER BY 1",$id);
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='creausu' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono asigna1' title='Asignar Usuario' id='".$c['ACCIONES']."' Onclick=\"mostrar('creausu','pro',event,'','lib.php',7);\"></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
