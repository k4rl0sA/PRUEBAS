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
/*     // print_r($_POST);
$id = (isset($_POST['id'])) ? divide($_POST['id']) : (isset($_POST['iduaic']) ? divide($_POST['iduaic']) : null);
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
        return create_table($total,$datos["responseResult"],"cambiar",$regxPag,'cambiar.php'); */
}


function cmp_uaic_seg(){
  $rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div><div class='contenido' id='uaic_seg-lis'>".lis_uaic_seg()."</div></div>";
  //$rta='';
  $w='modini';
	$t=['iduaic'=>'','idpeople'=>'','fecha_seg'=>'','parentesco'=>'','nombre_cui'=>'','tipo_doc'=>'','num_doc'=>'','telefono'=>'','era'=>'','eda'=>'','dnt'=>'','des_sinto'=>'','aten_medi'=>'','aten_part'=>'','peri_cef'=>'','peri_bra'=>'','peso'=>'','talla'=>'','zcore'=>'','clasi_nut'=>'','tempe'=>'','frec_res'=>'','frec_car'=>'','satu'=>'','sales_reh'=>'','aceta'=>'','traslados_uss'=>'','educa'=>'','menor_hos'=>'','tempe2'=>'','frec_res2'=>'','frec_car2'=>'','satu2'=>'','seg_entmed'=>'','observacion'=>'','clasi_nutri'=>'']; 
	$o='uaic_seg';
  $d=get_uaic_seg();
  $d=($d=="")?$d=$t:$d;
  $days=fechas_app('ETNIAS');
  var_dump($_POST);
	$c[]=new cmp($o,'e',null,'MODULO INICIAL',$w);
    $c[]=new cmp('iduaic','n',11,$d['iduaic'],$w.' '.$o,'Iduaic','iduaic',null,null,true,true,'','col-2');
    $c[]=new cmp('idpeople','n',18,$d['idpeople'],$w.' '.$o,'Idpeople','idpeople',null,null,true,true,'','col-2');
    $c[]=new cmp('fecha_seg','d',10,$d['fecha_seg'],$w.' '.$o,'Fecha de Seg','fecha_seg',null,null,true,true,'','col-2');
    $c[]=new cmp('segui','s',3,$d['segui'],$w.' '.$o,'Segui','segui',null,null,true,true,'','col-2');
    $c[]=new cmp('estado_seg','t',50,$d['estado_seg'],$w.' '.$o,'Estado de Seg','estado_seg',null,null,true,true,'','col-2');
    $c[]=new cmp('motivo_seg','s',3,$d['motivo_seg'],$w.' '.$o,'Motivo de Seg','motivo_seg',null,null,true,true,'','col-2');
    $c[]=new cmp('peso','n',18,$d['peso'],$w.' '.$o,'Peso','peso',null,null,false,true,'','col-2');
    $c[]=new cmp('talla','n',21,$d['talla'],$w.' '.$o,'Talla','talla',null,null,false,true,'','col-2');
    $c[]=new cmp('zcore','s',3,$d['zcore'],$w.' '.$o,'Zcore','zcore',null,null,false,true,'','col-2');
    $c[]=new cmp('clasi_nutri','s',3,$d['clasi_nutri'],$w.' '.$o,'Clasi de Nutri','clasi_nutri',null,null,false,true,'','col-2');
    $c[]=new cmp('ftlc_apme','s',3,$d['ftlc_apme'],$w.' '.$o,'Ftlc de Apme','ftlc_apme',null,null,false,true,'','col-2');
    $c[]=new cmp('cual','s',3,$d['cual'],$w.' '.$o,'Cual','cual',null,null,false,true,'','col-2');
    $c[]=new cmp('cita_nutri7','s',3,$d['cita_nutri7'],$w.' '.$o,'Cita de Nutri7','cita_nutri7',null,null,false,true,'','col-2');
    $c[]=new cmp('cita_nutri15','s',3,$d['cita_nutri15'],$w.' '.$o,'Cita de Nutri15','cita_nutri15',null,null,false,true,'','col-2');
    $c[]=new cmp('cita_nutri30','s',5,2,$d['cita_nutri30'],$w.' '.$o,'Cita de Nutri30','cita_nutri30',null,null,false,true,'','col-2');
    $c[]=new cmp('observaciones','sd',4,1,$d['observaciones'],$w.' '.$o,'Observaciones','observaciones',null,null,false,true,'','col-2');
    $c[]=new cmp('user_bina','t',60,$d['user_bina'],$w.' '.$o,'User de Bina','user_bina',null,null,true,true,'','col-2');
    $c[]=new cmp('equipo_bina','t',7,$d['equipo_bina'],$w.' '.$o,'Equipo de Bina','equipo_bina',null,null,true,true,'','col-2');
    $c[]=new cmp('usu_creo','s',10,$d['usu_creo'],$w.' '.$o,'Usu de Creo','usu_creo',null,null,true,true,'','col-2');
    $c[]=new cmp('usu_update','s',10,$d['usu_update'],$w.' '.$o,'Usu de Update','usu_update',null,null,false,true,'','col-2');
    $c[]=new cmp('estado','o',2,$d['estado'],$w.' '.$o,'Estado','estado',null,null,false,true,'','col-2'); 




	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function gra_uaic_seg(){
	$id=divide($_POST['iduaic']);
    
    if(COUNT($id)==2){
      $equ=datos_mysql("select equipo from usuarios where id_usuario=".$_SESSION['us_sds']);
      $bina = isset($_POST['fequi'])?(is_array($_POST['fequi'])?implode("-", $_POST['fequi']):implode("-",array_map('trim',explode(",",str_replace("'","",$_POST['fequi']))))):'';
      $equi=$equ['responseResult'][0]['equipo'];
      $sql = "INSERT INTO uaic_seg
       VALUES (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,DATE_SUB(NOW(),INTERVAL 5 HOUR),?,?,'A')";
      $params = [
['type' => 'i', 'value' => $id[0]],
['type' => 's', 'value' => $_POST['fecha_seg']],
['type' => 's', 'value' => $_POST['parentesco']],
['type' => 's', 'value' => $_POST['nombre_cui']],
['type' => 's', 'value' => $_POST['tipo_doc']],
['type' => 'i', 'value' => $_POST['num_doc']],
['type' => 'i', 'value' => $_POST['telefono']],
['type' => 's', 'value' => $_POST['era']],
['type' => 's', 'value' => $_POST['eda']],
['type' => 's', 'value' => $_POST['dnt']],
['type' => 's', 'value' => $_POST['des_sinto']],
['type' => 's', 'value' => $_POST['aten_medi']],
['type' => 's', 'value' => $_POST['aten_part']],
['type' => 's', 'value' => $_POST['peri_cef']],
['type' => 's', 'value' => $_POST['peri_bra']],
['type' => 's', 'value' => $_POST['peso']],
['type' => 's', 'value' => $_POST['talla']],
['type' => 's', 'value' => $_POST['zcore']],
['type' => 's', 'value' => $_POST['clasi_nutri']],
['type' => 's', 'value' => $_POST['tempe']],
['type' => 's', 'value' => $_POST['frec_res']],
['type' => 's', 'value' => $_POST['frec_car']],
['type' => 's', 'value' => $_POST['satu']],
['type' => 's', 'value' => $_POST['sales_reh']],
['type' => 's', 'value' => $_POST['aceta']],
['type' => 's', 'value' => $_POST['traslados_uss']],
['type' => 's', 'value' => $_POST['educa']],
['type' => 's', 'value' => $_POST['menor_hos']],
['type' => 's', 'value' => $_POST['tempe2']],
['type' => 's', 'value' => $_POST['frec_res2']],
['type' => 's', 'value' => $_POST['frec_car2']],
['type' => 's', 'value' => $_POST['satu2']],
['type' => 's', 'value' => $_POST['seg_entmed']],
['type' => 's', 'value' => $_POST['observacion']],
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
    $sql="SELECT fecha_seg,parentesco,nombre_cui,tipo_doc,num_doc,telefono,era,eda,dnt,des_sinto,peri_cef,peri_bra,peso,talla,zcore,clasi_nut,tempe,frec_res,frec_car,satu,sales_reh,aceta,traslados_uss,educa,menor_hos,tempe2,frec_res2,frec_car2,satu2,seg_entmed,observacion
          FROM `uaic_seg` 
          WHERE iduaic='{$id[0]}'";
    $info=datos_mysql($sql);
    if (!$info['responseResult']) {
			return '';
		}else{
			return $info['responseResult'][0];
		}
      }
}

function opc_paren($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=263 and estado='A' ORDER BY 1",$id);
  }

  function opc_tipo_doc($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
  }

  function opc_rta($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
  }

  function opc_clasi_nutri($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=98 and estado='A' ORDER BY 1",$id);
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