<?php
require_once "../libs/gestion.php";
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

function focus_rutclasif(){
 return 'rutclasif';
}

function men_rutclasif(){
 $rta=cap_menus('rutclasif','pro');
 return $rta;
}

function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='rutclasif'){  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
  }
  return $rta;
}

function cmp_rutclasif(){
 $rta="";
 $t=['id_rutclas'=>'','idrutges'=>'','preclasif'=>'','clasifica'=>'','riesgo'=>'','accion'=>'','fecha'=>'','accion1'=>'','desc_accion1'=>'','accion2'=>'','desc_accion2'=>'','accion3'=>'','desc_accion3'=>'','profesional'=>'','solic_agend'=>'','ruta'=>'','sectorial'=>'','intsectorial'=>'','entornos'=>'','aseguram'=>''];
 $w='rutclasif';
 $d=get_rutclasif();
 if ($d=="") {$d=$t;}
 $u=($d['idrutges']== NULL || $d['idrutges']== '')?true:false;
 var_dump($d);
 $o='gescla';
 $c[]=new cmp($o,'e',null,'PROCESO DE CLASIFICACIÓN',$w);
 $c[]=new cmp('id','h','20',$d['idrutges'],$w.' '.$o,'','',null,null,true,$u,'','col-1');
 $c[]=new cmp('pre_clasif','s','10',$d['preclasif'],$w.' '.$o,'Pre Clasificación','pre_clasif',null,null,true,$u,'','col-25');
 $c[]=new cmp('clasificacion','s','10',$d['clasifica'],$w.' '.$o,'Clasificación','clasificacion',null,null,true,$u,'','col-25');
 $c[]=new cmp('riesgo','s','10',$d['riesgo'],$w.' '.$o,'Riesgo','riesgo',null,null,true,$u,'','col-25','rutRiskHig();rutRisklow();');

 $o='bajo';
 $c[]=new cmp($o,'e',null,'RIESGO BAJO',$w);
 $c[]=new cmp('accion','s','10',$d['accion'],$w.' '.$o,'Definir Acción','clasificacion',null,null,true,false,'','col-5');
 $c[]=new cmp('fecha','d','10',$d['fecha'],$w.' '.$o,'Fecha de Programación','fecha',null,null,true,false,'','col-5','validDate(this,-2,0);');
 $c[]=new cmp('acciones_1','s','3',$d['accion1'],$w.' '.$o,'Accion 1','acciones_1',null,null,false,false,'','col-5',"selectDepend('acciones_1','desc_accion1','clasifica.php');");
 $c[]=new cmp('desc_accion1','s','3',$d['desc_accion1'],$w.' '.$o,'Descripcion Accion 1','desc_accion1',null,null,false,false,'','col-5');
 $c[]=new cmp('acciones_2','s','3',$d['accion2'],$w.' '.$o,'Accion 2','acciones_2',null,null,false,false,'','col-5',"selectDepend('acciones_2','desc_accion2','clasifica.php');");
 $c[]=new cmp('desc_accion2','s','3',$d['desc_accion2'],$w.' '.$o,'Descripcion Accion 2','desc_accion2',null,null,false,false,'','col-5');
 $c[]=new cmp('acciones_3','s','3',$d['accion3'],$w.' '.$o,'Accion 3','acciones_3',null,null,false,false,'','col-5',"selectDepend('acciones_3','desc_accion3','clasifica.php');");
 $c[]=new cmp('desc_accion3','s','3',$d['desc_accion3'],$w.' '.$o,'Descripcion Accion 3','desc_accion3',null,null,false,false,'','col-5');

 $o='alto';
 $c[]=new cmp($o,'e',null,'RIESGO ALTO',$w);
 $c[]=new cmp('perfil','s','90',$d[''],$w.' dir '.$o,'Perfil A Asignar','perfil',null,null,false,false,'','col-25',"selectDepend('perfil','nombre','clasifica.php');");
 $c[]=new cmp('nombre','s','6',$d['profesional'],$w.' dir '.$o,'Profesional Asignado','doc_asignado',null,null,false,false,'','col-25');
 $c[]=new cmp('solici_agenda','s',3,$d['solic_agend'],$w.' AGe '.$o,'solici_agenda','rta',null,'',true,false,'','col-2');
 $c[]=new cmp('activa_ruta','s','10',$d['ruta'],$w.' AGe '.$o,'activa_ruta','rta',null,null,true,false,'','col-2','rutRute();');
 
 $cl='ruta';
 $c[]=new cmp($cl,'l',null,'ACTIVACIÓN DE RUTA',$w);
 $c[]=new cmp('sectorial','s','2',$d['sectorial'],$w.' '.$cl,'¿Sectorial?','rta',null,null,true,false,'','col-25');
 $c[]=new cmp('intersectorial','s','2',$d['intsectorial'],$w.' '.$cl,'¿Intersectorial?','rta',null,null,true,false,'','col-25');
 $c[]=new cmp('entornos','s','2',$d['entornos'],$w.' '.$cl,'¿Entornos?','rta',null,null,true,false,'','col-25');
 $c[]=new cmp('aseguramiento','s','2',$d['aseguram'],$w.' '.$cl,'Aseguramiento','rta',null,null,true,false,'','col-25');
 for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
 return $rta;
}

function get_rutclasif(){
	if($_POST['id']=='0'){
		return "";
	}else{
		$id=divide($_POST['id']);
		// var_dump($id);
		$sql="SELECT id_rutclas,idrutges,preclasif,clasifica,riesgo,accion,fecha,accion1,desc_accion1,accion2,desc_accion2,accion3,desc_accion3,profesional,solic_agend,ruta,sectorial,intsectorial,entornos,aseguram
		 FROM `eac_ruteo_clas` WHERE  idrutges='{$id[0]}'";
		$info=datos_mysql($sql);
    	// var_dump($info['responseResult'][0]);
		return $info['responseResult'][0];
	} 
}

function gra_rutclasif(){
$sql="UPDATE `eac_ruteo` SET 
famili=TRIM(UPPER('{$_POST['famili']}')),
usuario=TRIM(UPPER('{$_POST['usuario']}')),
`predio`=TRIM(UPPER('{$_POST['estado']}')),
`cod_admin`=TRIM(UPPER('{$_POST['cod_admin']}')),
`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR),
estado='G'
	WHERE id_ruteo='{$_POST['id']}'";
	//echo $sql;
  $rta=dato_mysql($sql);
  return $rta;
}

function opc_rta($id=''){
return opc_sql('SELECT idcatadeta,descripcion FROM catadeta WHERE idcatalogo=170 and estado="A" ORDER BY 1',$id);
}
function opc_riesgo($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=273 and estado='A' ORDER BY 1",$id);
}
function opc_clasificacion($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=191 and estado='A' ORDER BY 1",$id);
}
function opc_pre_clasif($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=191 and estado='A' ORDER BY 1",$id);
}
function opc_perfil($id=''){
  return opc_sql('SELECT idcatadeta,descripcion FROM catadeta WHERE idcatalogo=218 and estado="A" ORDER BY 1',$id);
  }
  function opc_doc_asignado($id=''){
    $co=datos_mysql("select FN_USUARIO(".$_SESSION['us_sds'].") as co;");
    $com=divide($co['responseResult'][0]['co']);
    return opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE  subred='{$com[2]}' ORDER BY 1",$id);//`perfil` IN('MED','ENF')
  }
function opc_perfilnombre($id=''){
  if($_REQUEST['id']!=''){	
    $sql = "SELECT id_usuario id,CONCAT(id_usuario,'-',nombre) usuario FROM usuarios WHERE 
    perfil=(select descripcion from catadeta c where idcatalogo=218 and idcatadeta='{$_REQUEST['id']}' and estado='A') 
    and subred=(SELECT subred FROM usuarios WHERE id_usuario ='{$_SESSION['us_sds']}') ORDER BY nombre";
    $info = datos_mysql($sql);		
  return json_encode($info['responseResult']);	
  }
}
function opc_perfil_alto($id=''){
  if($_REQUEST['id']!=''){	
      $sql = "SELECT *,id_usuario id,CONCAT(id_usuario,'-',nombre) usuario FROM usuarios WHERE 
      perfil=(select descripcion from catadeta c where idcatalogo=218 and idcatadeta='{$_REQUEST['id']}' and estado='A') 
      and componente=(SELECT componente FROM usuarios WHERE id_usuario ='{$_SESSION['us_sds']}') 
      and subred=(SELECT subred FROM usuarios WHERE id_usuario ='{$_SESSION['us_sds']}') ORDER BY 1";
      $info = datos_mysql($sql);		
      return json_encode($info['responseResult']);	
  } 
}
function opc_usuario_alto($id=''){
	// return opc_sql("SELECT id_usuario id,CONCAT(id_usuario,'-',nombre) usuario FROM usuarios WHERE estado = 'A'",$id);
}
function opc_perfil_altousuario_alto($id=''){
  if($_REQUEST['id']!=''){	
          $sql = "SELECT id_usuario id,CONCAT(id_usuario,'-',nombre) usuario FROM usuarios WHERE 
          perfil=(select descripcion from catadeta c where idcatalogo=218 and idcatadeta='{$_REQUEST['id']}' and estado='A') 
          and subred=(SELECT subred FROM usuarios WHERE id_usuario ='{$_SESSION['us_sds']}') ORDER BY nombre";
          $info = datos_mysql($sql);		
          return json_encode($info['responseResult']);	
      }
}
function opc_acciones_1($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
  }
  function opc_desc_accion1($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
    }
  
  
  function opc_acciones_1desc_accion1($id=''){
  if($_REQUEST['id']!=''){
        $id=divide($_REQUEST['id']);
        $sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
        $info=datos_mysql($sql);		
        return json_encode($info['responseResult']);
      }
  }
  function opc_acciones_2desc_accion2($id=''){
    if($_REQUEST['id']!=''){
          $id=divide($_REQUEST['id']);
          $sql="SELECT idcatadeta,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
          $info=datos_mysql($sql);		
          return json_encode($info['responseResult']);
        }
    }
    function opc_acciones_3desc_accion3($id=''){
      if($_REQUEST['id']!=''){
            $id=divide($_REQUEST['id']);
            $sql="SELECT idcatadeta 'id',descripcion 'asc' FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
            $info=datos_mysql($sql);		
            return json_encode($info['responseResult']);
          }
      }
  function opc_acciones_2($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
  }
  function opc_desc_accion2($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
  }
  function opc_acciones_3($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
  }
  function opc_desc_accion3($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
  }
function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>