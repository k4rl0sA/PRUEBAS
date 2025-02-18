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


function focus_uaic_seg(){
	return 'uaic_seg';
   }
      
   
  function men_uaic_seg(){
	$rta=cap_menus('uaic_seg','pro');
	return $rta;
   }
   
  
  function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 $acc=rol($a);
	   if ($a=='uaic_seg'  && isset($acc['crear']) && $acc['crear']=='SI'){  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	  }
    $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";  
  return $rta;
}
function lis_uaic_seg(){
    // print_r($_POST);
$id = (isset($_POST['id'])) ? divide($_POST['id']) : (isset($_POST['iduaicseg']) ? divide($_POST['iduaicseg']) : null);
$info=datos_mysql("SELECT COUNT(*) total FROM vsp_mme A LEFT JOIN  usuarios U ON A.usu_creo=U.id_usuario 
  WHERE A.estado = 'A' AND A.idpeople='".$id[0]."'");
$total=$info['responseResult'][0]['total'];
$regxPag=5;
$pag=(isset($_POST['pag-uaic_seg']))? ($_POST['pag-uaic_seg']-1)* $regxPag:0;

    $sql="SELECT concat(key1,'_',key2) ACCIONES
        FROM `uaic_seg` 
            WHERE key1='".$id[0];
        $sql.="' ORDER BY fecha_create";
        $sql.=' LIMIT '.$pag.','.$regxPag;
        //  echo $sql;
        $datos=datos_mysql($sql);
        return create_table($total,$datos["responseResult"],"cambiar",$regxPag,'cambiar.php');
}


function cmp_uaic_seg(){
  $rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div><div class='contenido' id='uaic_seg-lis'>".lis_uaic_seg()."</div></div>";
  $w='modini';
	$t=['iduaicseg'=>'','idpeople'=>'','fecha_seg'=>'','segui'=>'','estado_seg'=>'','motivo_seg'=>'','peso'=>'','talla'=>'','zcore'=>'','clasi_nutri'=>'','ftlc_apme'=>'','cual'=>'','cita_nutri7'=>'','cita_nutri15'=>'','cita_nutri30'=>'','observaciones'=>''];
	$o='uaic_seg';
  $d=get_uaic_seg();
  $d='';
  $d=($d=="")?$d=$t:$d;
  $days=fechas_app('ETNIAS');
  var_dump($_POST);
	$c[]=new cmp($o,'e',null,'MODULO INICIAL',$w);
    $c[]=new cmp('iduaicseg','h',11,$_POST['id'],$w.' '.$o,'Iduaicseg','iduaicseg',null,null,true,true,'','col-2');
    $c[]=new cmp('fecha_seg','d',10,$d['fecha_seg'],$w.' '.$o,'Fecha Seguimiento','fecha_seg',null,null,true,true,'','col-25',"validDate(this,$days,0);");
    $c[]=new cmp('segui','s',3,$d['segui'],$w.' '.$o,'Seguimiento N°','segui',null,null,true,true,'','col-25');
    $c[]=new cmp('estado_seg','s',3,$d['estado_seg'],$w.' '.$o,'Estado de Seguimiento','estado_seg',null,null,true,true,'','col-25');
    $c[]=new cmp('motivo_seg','s',3,$d['motivo_seg'],$w.' '.$o,'Motivo de Seguimiento','motivo_seg',null,null,true,true,'','col-25');
    
    $o='segdnt';
    $c[]=new cmp($o,'e',null,'SEGUIMIENTO MENORES CON  DNT',$w);
    $c[]=new cmp('peso','n',18,$d['peso'],$w.' '.$o,'Peso','peso',null,null,false,true,'','col-2');
    $c[]=new cmp('talla','n',21,$d['talla'],$w.' '.$o,'Talla','talla',null,null,false,true,'','col-2');
    $c[]=new cmp('zcore','t',50,$d['zcore'],$w.' '.$o,'Zcore','zcore',null,null,false,true,'','col-2');
    $c[]=new cmp('clasi_nutri','s',3,$d['clasi_nutri'],$w.' '.$o,'Clasificacion Nutricional','clasi_nutri',null,null,false,true,'','col-2');
    $c[]=new cmp('ftlc_apme','s',3,$d['ftlc_apme'],$w.' '.$o,'Tiene Ftlc U Otro Apme (Cual)','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('cual','T',50,$d['cual'],$w.' '.$o,'Cual','cual',null,null,false,true,'','col-3');
    $c[]=new cmp('cita_nutri7','s',3,$d['cita_nutri7'],$w.' '.$o,'Cita Con Nutricion O Pediatria A Los 7 Dias','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('cita_nutri15','s',3,$d['cita_nutri15'],$w.' '.$o,'Cita Con Nutricion O Pediatria A Los 15 Dias','rta',null,null,false,true,'','col-25');
    $c[]=new cmp('cita_nutri30','s',5,$d['cita_nutri30'],$w.' '.$o,'Cita Con Nutricion O Pediatria A Los 30 Dias','rta',null,null,false,true,'','col-25');
    
    $o='aspe';
    $c[]=new cmp($o,'e',null,'ASPECTOS FINALES',$w);
    $c[]=new cmp('observaciones','a',7000,$d['observaciones'],$w.' '.$o,'Observaciones','observaciones',null,null,false,true,'','col-10');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function gra_uaic_seg(){
	$id=divide($_POST['iduaicseg']);
    
    if(COUNT($id)==2){
      $equ=datos_mysql("select equipo from usuarios where id_usuario=".$_SESSION['us_sds']);
      $bina = isset($_POST['fequi'])?(is_array($_POST['fequi'])?implode("-", $_POST['fequi']):implode("-",array_map('trim',explode(",",str_replace("'","",$_POST['fequi']))))):'';
      $equi=$equ['responseResult'][0]['equipo'];
      $sql = "INSERT INTO uaic_seg VALUES (null,?,?,?,?,?,?,?,?,?,?,?,?,?,DATE_SUB(NOW(),INTERVAL 5 HOUR),?,?,'A')";
      $params = [
['type' => 'i', 'value' => $_POST['iduaicseg']],
['type' => 's', 'value' => $_POST['fecha_seg']],
['type' => 's', 'value' => $_POST['segui']],
['type' => 's', 'value' => $_POST['estado_seg']],
['type' => 's', 'value' => $_POST['motivo_seg']],
['type' => 'i', 'value' => $_POST['peso']],
['type' => 'i', 'value' => $_POST['talla']],
['type' => 's', 'value' => $_POST['zcore']],
['type' => 's', 'value' => $_POST['clasi_nutri']],
['type' => 's', 'value' => $_POST['ftlc_apme']],
['type' => 's', 'value' => $_POST['cual']],
['type' => 's', 'value' => $_POST['cita_nutri7']],
['type' => 's', 'value' => $_POST['cita_nutri15']],
['type' => 's', 'value' => $_POST['cita_nutri30']],
['type' => 's', 'value' => $_POST['observaciones']],
['type' => 's', 'value' => $bina],
['type' => 's', 'value' => $equi],
['type' => 's', 'value' => $_SESSION['us_sds']],
['type' => 's', 'value' => NULL],
['type' => 's', 'value' => NULL]
      ];

       $rta = show_sql($sql, $params);
    //  $rta = mysql_prepd($sql, $params);
    }else{
   /*$sql="UPDATE hog_planconc SET cumple=?,fecha_update=?,usu_update=? WHERE idcon=?"; //  compromiso=?, equipo=?, 
    $params = [
        ['type' => 's', 'value' => $_POST['cumplio']],
        ['type' => 's', 'value' => date("Y-m-d H:i:s")],
        ['type' => 'i', 'value' => $_SESSION['us_sds']],
        ['type' => 'i', 'value' => $id[1]]
      ];
      $rta = mysql_prepd($sql, $params);*/
    }
return $rta;
}

function get_uaic_seg(){
  if($_REQUEST['id']==''){
    return "";
  }else{
    // print_r($_POST);
    $id=divide($_REQUEST['id']);
    // print_r($id);
    $sql="SELECT fecha_seg,segui,estado_seg,motivo_seg,peso,talla,zcore,clasi_nutri,ftlc_apme,cual,cita_nutri7,cita_nutri15,cita_nutri30,observaciones
          FROM `uaic_seg` 
          WHERE iduaicseg='{$id[0]}'";
    $info=datos_mysql($sql);
    if (!$info['responseResult']) {
			return '';
		}else{
			return $info['responseResult'][0];
		}
      }
}

  function opc_rta($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
  }

  function opc_clasi_nutri($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=98 and estado='A' ORDER BY 1",$id);
    }

  function opc_segui($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=76 and estado='A' ORDER BY 1",$id);
    }
      
  function opc_estado_seg($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=73 and estado='A' ORDER BY 1",$id);
    }
      
  function opc_motivo_seg($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=265 and estado='A' ORDER BY 1",$id);
  }

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
        // var_dump($a);
		if ($a=='uaic_seg' && $b=='acciones'){
			$rta="<nav class='menu right'>";
				
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }