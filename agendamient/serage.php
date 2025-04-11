<?php
require_once "../libs/gestion.php";
ini_set('display_errors','1');
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

function focus_servagen(){
  return 'servagen';
 }
 
 function men_servagen(){
  $rta=cap_menus('servagen','pro');
  return $rta;
 }
 
 
 function cap_menus($a,$b='cap',$con='con') {
  $rta = "";
  $acc=rol($a);
  if ($a=='servagen' && isset($acc['crear']) && $acc['crear']=='SI') {  
   $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
    }
  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";  
  return $rta;
}

function lis_servicios(){
    // var_dump($_POST['id']);
    $id=divide($_POST['id']);

    $total="SELECT COUNT(*) AS total FROM (
      SELECT id_agen 'Cod Registro',FN_CATALOGODESC(87,servicio),fecha_solici 'Fecha Solicitó'
    FROM hog_agen E 
    WHERE E.idpeople='{$id[0]}') AS Subquery";
    $info=datos_mysql($total);
    $total=$info['responseResult'][0]['total']; 
    $regxPag=5;
    $pag=(isset($_POST['pag-servicios']))? ($_POST['pag-servicios']-1)* $regxPag:0;

    $sql="SELECT id_agen 'Cod Registro',FN_CATALOGODESC(87,servicio),fecha_solici 'Fecha Solicitó'
    FROM hog_agen E 
    WHERE E.idpeople='{$id[0]}'";  
    $sql.=" ORDER BY 3 desc LIMIT $pag, $regxPag";
    // echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"servicios",$regxPag,'servagen.php');
}

function cmp_servagen(){
	$rta="<div class='encabezado medid'>TABLA DE SERVICIOS POR USUARIO</div>
	<div class='contenido' id='eventos-lis'>".lis_servicios()."</div></div>";
    // $rta="";
	$t=['id_eve'=>'','tipodoc'=>'','idpersona'=>'','nombre'=>'','fechanacimiento'=>'','edad'=>'','sexo'=>'','docum_base'=>'','evento'=>'','fecha_even'=>''];
	$d=get_persona($_POST['id']);
	if ($d==""){$d=$t;}
	$e="";
	$w='servagen';
	$o='datos';
  $key='age';
  $edad='AÑOS= '.$d['anos'].' MESES= '.$d['meses'].' DIAS= '.$d['dias'];
  $days=fechas_app('AGENDA');
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('idp','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('idpersona','n','20',$d['idpersona'],$w.' '.$o.' '.$key,'N° Identificación','idpersona',null,'',false,false,'','col-15');
	$c[]=new cmp('tipodoc','s','3',$d['tipodoc'],$w.' '.$o.' '.$key,'Tipo Identificación','tipodoc',null,'',false,false,'','col-15');//setTimeout(hiddxedad,1000,\'edad\',\'find\');
	$c[]=new cmp('nombre','t','50',$d['nombre'],$w.' '.$o,'nombres','nombre',null,'',false,false,'','col-3');
	$c[]=new cmp('sexo','s','3',$d['sexo'],$w.' '.$o,'Sexo','sexo',null,'',true,false,'','col-1');
	$c[]=new cmp('fechanacimiento','d',10,$d['fechanacimiento'],$w.' '.$o,'fecha nacimiento','fechanacimiento',null,'',true,false,'','col-1');
  $c[]=new cmp('edad','t',30,$edad,$w.' '.$o,'edad en Años','edad',null,'',true,false,'','col-2');
	
	$o='prufin';
    $c[]=new cmp($o,'e',null,'SERVICIO AGENDAMIENTO',$w);
    $c[]=new cmp('fecha_sol','d',10,$e,$w.' '.$o,'Fecha Solicitud','fecha_even',null,null,true,true,'','col-15',"validDate(this,$days,0);");
    $c[]=new cmp('tipo_cons','s',3, $e,$w,'Tipo de Consulta','consulta',null,null,true,true,'','col-25',"selectDepend('tipo_cons','servicio','../agendamient/serage.php');");
    $c[]=new cmp('servicio','s',3, $e,$w,'Servicio','servicio',null,null,true,true,'','col-3');
  for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}



function opc_tipodoc($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}
function opc_sexo($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
}
function opc_consulta($id=''){
  return opc_sql('SELECT idcatadeta,descripcion FROM catadeta WHERE idcatalogo=281 and estado="A" ORDER BY 1',$id);
}
function opc_servicio($id=''){
  return opc_sql('SELECT idcatadeta,descripcion FROM catadeta WHERE idcatalogo=275 and estado="A" ORDER BY 1',$id);
}
function opc_tipo_consservicio($id=''){
  if($_REQUEST['id']!=''){
    $id=divide($_REQUEST['id']);
    var_dump($_POST);
    var_dump($_REQUEST);
    $d=get_persona($_POS['idp']);
      if($d['sexo']=='M'){
        if($d['anos']<6){ 
          $sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo=275 and estado='A' and valor=$id[0] AND idcatadeta IN (1,10,15,9,17,18,19,20,21,22,23,24,25,26,27) ORDER BY LENGTH(idcatadeta), idcatadeta;";
        }elseif($d['anos']>=6 && $d['anos']<=11){
          $sql="SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor=$id[0] AND idcatadeta IN(2,10,15,9,17,18,19,20,21,22,23,24,25,26,27) and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta;";
        }elseif($d['anos']>=12 && $d['anos']<=17){
          $sql="SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor=$id[0] AND idcatadeta IN(3,10,15,9,17,18,19,20,21,22,23,24,25,26,27) and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta;";
        }elseif($d['anos']>=21 && $d['anos']<=26){
          $sql="SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor=$id[0] AND idcatadeta IN(5,10,15,9,17,18,19,20,21,22,23,24,25,26,27) and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta;";
        }elseif($d['anos']>=29 && $d['anos']<=59){
          $sql="SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor=$id[0] AND idcatadeta IN(4,10,15,9,17,18,19,20,21,22,23,24,25,26,27) and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta;";
        }elseif($d['anos']>=60){
          $sql="SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor=$id[0] AND idcatadeta IN(6,10,15,9,17,18,19,20,21,22,23,24,25,26,27) and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta;";
        }
      }else{
        if($d['anos']<6){ 
          $sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo=275 and estado='A' and valor=$id[0] AND idcatadeta IN (1,10,15,9,17,18,19,20,21,22,23,24,25,26,27) ORDER BY LENGTH(idcatadeta), idcatadeta;";
        }elseif($d['anos']>=6 && $d['anos']<=11){
          $sql="SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor=$id[0] AND idcatadeta IN(2,10,15,9,17,18,19,20,21,22,23,24,25,26,27) and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta;";
        }elseif($d['anos']>=12 && $d['anos']<=17){
          $sql="SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor=$id[0] AND idcatadeta IN(3,10,15,9,17,18,19,20,21,22,23,24,25,26,27) and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta;";
        }elseif($d['anos']>=21 && $d['anos']<=26){
          $sql="SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor=$id[0] AND idcatadeta IN(5,10,15,9,17,18,19,20,21,22,23,24,25,26,27) and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta;";
        }elseif($d['anos']>=29 && $d['anos']<=59){
          $sql="SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor=$id[0] AND idcatadeta IN(4,10,15,9,17,18,19,20,21,22,23,24,25,26,27) and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta;";
        }elseif($d['anos']>=60){
          $sql="SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor=$id[0] AND idcatadeta IN(6,10,15,9,17,18,19,20,21,22,23,24,25,26,27) and estado='A' ORDER BY LENGTH(idcatadeta), idcatadeta;";
        }
    }
    //  var_dump($sql);
    $info=datos_mysql($sql);
    return json_encode($info['responseResult']);
  }
}
/* function opc_evento($id=''){
  $d=get_persona();
  if($d['sexo']=='M'){
    if($d['anos']<6){
      return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor IN(1,2,3) and estado='A' ORDER BY 2",$id);
    }elseif($d['anos']>5 && $d['anos']<10){
      return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor IN(1,2,3) and estado='A' ORDER BY 2",$id); 
    }elseif($d['anos']>9 && $d['anos']<18){
      return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor IN(1,2,3) and estado='A' ORDER BY 2",$id); 
    }elseif($d['anos']>17 && $d['anos']<55){
      return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor IN(1,2,3) and estado='A' ORDER BY 2",$id); 
    }elseif($d['anos']>54){
      return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor IN(1,2,3) and estado='A' ORDER BY 2",$id); 
    }
  }else{
    if($d['anos']<6){
      return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor IN(1,2,3) and estado='A' ORDER BY 2",$id);
    }elseif($d['anos']>5 && $d['anos']<18){
      return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor IN(1,2,3) and estado='A' ORDER BY 2",$id); 
    }elseif($d['anos']>17 && $d['anos']<55){
      return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor IN(1,2,3) and estado='A' ORDER BY 2",$id); 
    }elseif($d['anos']>54){
      return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=275 AND valor IN(1,2,3) and estado='A' ORDER BY 2",$id); 
    }
  }
  }
 */
function gra_servagen(){
  // print_r($_POST);
  $id=divide($_POST['id']);
  if (($rtaFec = validFecha('AGENDAMIENTO', $_POST['fecha_sol'] ?? '')) !== true) {
    return $rtaFec;
  }
if(count($id)==2){
  $sql = "INSERT INTO hog_agen VALUES(NULL,?,?,?,?,?,DATE_SUB(NOW(),INTERVAL 5 HOUR),NULL,NULL,'A')";
  $params = [
  ['type' => 'i', 'value' => $id[0]],
  ['type' => 's', 'value' => $_POST['fecha_sol']],
  ['type' => 's', 'value' => $_POST['tipo_cons']],
  ['type' => 's', 'value' => $_POST['servicio']],
  ['type' => 's', 'value' => $_SESSION['us_sds']]
  ];
    // echo $sql;
    return $rta = mysql_prepd($sql, $params);
  }
  } 

function get_persona($a=''){
  if($_POST['id']==''){
    return "";
  }else{
    $id=divide($_POST['id']);
    $sql="SELECT P.idpeople,P.idpersona idpersona,P.tipo_doc tipodoc,CONCAT_WS(' ',nombre1,nombre2,apellido1,apellido2) nombre,P.fecha_nacimiento fechanacimiento,
		P.sexo sexo,
    TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS anos,
    TIMESTAMPDIFF(MONTH, fecha_nacimiento, CURDATE())-(TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) * 12) AS meses,
    DATEDIFF(CURDATE(),DATE_ADD(fecha_nacimiento, INTERVAL TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) YEAR)) % 30 AS dias
		FROM person P
    WHERE P.idpeople='{$id[0]}'"; 
    echo $sql;
    // print_r($_REQUEST);
    $info=datos_mysql($sql);
    return $info['responseResult'][0];
  }
}


function get_servagen(){
  if($_REQUEST['id']==''){
    return "";
  }else{
      $id=divide($_REQUEST['id']);
      $sql="SELECT id_agen,fecha_solici,tipo_consulta,servicio
      FROM hog_agen 
      WHERE id_agen ='{$id[0]}'";
      // echo $sq1l;
      // print_r($id);
      $info=datos_mysql($sql);
      return json_encode($info['responseResult'][0]);
    } 
  }

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
if ($a=='servagen-lis' && $b=='acciones'){//a mnombre del modulo
	$rta="<nav class='menu right'>";	
	$rta.="<li class='icono editar' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'servagen',event,this,['fecha_sol','tipo_cons','servicio'],'servagen.php');\"></li>";
}
 return $rta;
}


function bgcolor($a,$c,$f='c'){
  $rta="";
  return $rta;
}