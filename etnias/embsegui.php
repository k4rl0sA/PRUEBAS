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


function focus_seguim(){
	return 'seguim';
   }
   
   
   function men_seguim(){
	$rta=cap_menus('seguim','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 $acc=rol($a);
	   if ($a=='seguim'  && isset($acc['crear']) && $acc['crear']=='SI'){  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	 
	   }
  return $rta;
}
function lis_seguim(){
    // print_r($_POST);
    /*$id = (isset($_POST['id'])) ? divide($_POST['id']) : divide($_POST['idp']) ;
$info=datos_mysql("SELECT COUNT(*) total FROM tabla WHERE idviv=".$id[0]."");
$total=$info['responseResult'][0]['total'];
$regxPag=5;
$pag=(isset($_POST['pag-seguim']))? ($_POST['pag-seguim']-1)* $regxPag:0;

    $sql="SELECT concat(key1,'_',key2) ACCIONES
        FROM `tabla` 
            WHERE key1='".$id[0];
        $sql.="' ORDER BY fecha_create";
        $sql.=' LIMIT '.$pag.','.$regxPag;
        //  echo $sql;
        $datos=datos_mysql($sql);
        return create_table($total,$datos["responseResult"],"seguim",$regxPag,'plncon.php');*/
}


function cmp_seguim(){
  $rta="<div class='encabezado placuifam'>TABLA SEGUIMIENTOS</div><div class='contenido' id='seguim-lis'>".lis_seguim()."</div></div>";
  $w="placuifam";
	$t=['id'=>'']; 
	$o='seguim';
  $x='';
  $d='';
  $d=($d=="")?$d=$t:$d;
  $days=fechas_app('VSP');
  var_dump($_POST);
	$c[]=new cmp($o,'e',null,'PLAN DE CUIDADO FAMILIAR CONCERTADO',$w);
    $c[]=new cmp('idseg','n',11,$d,$w.' '.$o,'Idseg','idseg',null,null,true,true,'','col-2');
    $c[]=new cmp('idpeople','n',18,$d,$w.' '.$o,'Idpeople','idpeople',null,null,true,true,'','col-2');
    $c[]=new cmp('fecha_seg','d',10,$d,$w.' '.$o,'Fecha de Seg','fecha_seg',null,null,true,true,'','col-2');
    $c[]=new cmp('segui','s',3,$d,$w.' '.$o,'Segui','segui',null,null,true,true,'','col-2');
    $c[]=new cmp('estado_seg','s',3,$d,$w.' '.$o,'Estado de Seg','estado_seg',null,null,true,true,'','col-2');
    $c[]=new cmp('motivo_estado','s','3',$d,$w.' '.$o,'Motivo de Estado','motivo_estado',null,null,false,$x,'','col-2');
    $c[]=new cmp('interven','s',3,$d,$w.' '.$o,'Interven','interven',null,null,true,true,'','col-2');
    $c[]=new cmp('gestante','s',3,$d,$w.' '.$o,'Gestante','gestante',null,null,false,true,'','col-2');
    $c[]=new cmp('edad_gest','s',3,$d,$w.' '.$o,'Edad de Gest','edad_gest',null,null,false,true,'','col-2');
    $c[]=new cmp('Nom_fami','t',50,$d,$w.' '.$o,'Nom de Fami','Nom_fami',null,null,false,true,'','col-2');
    $c[]=new cmp('tipo_doc','s',3,$d,$w.' '.$o,'Tipo de Doc','tipo_doc',null,null,false,true,'','col-2');
    $c[]=new cmp('num_doc','s',3,$d,$w.' '.$o,'Num de Doc','num_doc',null,null,false,true,'','col-2');
    $c[]=new cmp('paren','s',3,$d,$w.' '.$o,'Paren','paren',null,null,false,true,'','col-2');
    $c[]=new cmp('tel_conta','s',3,$d,$w.' '.$o,'Tel de Conta','tel_conta',null,null,false,true,'','col-2');
    $c[]=new cmp('ubi','s',3,$d,$w.' '.$o,'Ubi','ubi',null,null,false,true,'','col-2');
    $c[]=new cmp('ser_req','s',3,$d,$w.' '.$o,'Ser de Req','ser_req',null,null,false,true,'','col-2');
    $c[]=new cmp('fecha_ing','sd',10,$d,$w.' '.$o,'Fecha de Ing','fecha_ing',null,null,false,true,'','col-2');
    $c[]=new cmp('uss_ing','s',3,$d,$w.' '.$o,'Uss de Ing','uss_ing',null,null,false,true,'','col-2');
    $c[]=new cmp('motivo_cons','s',3,$d,$w.' '.$o,'Motivo de Cons','motivo_cons',null,null,false,true,'','col-2');
    $c[]=new cmp('uss_tras','s',3,$d,$w.' '.$o,'Uss de Tras','uss_tras',null,null,false,true,'','col-2');
    $c[]=new cmp('ing_unidad','s',3,$d,$w.' '.$o,'Ing de Unidad','ing_unidad',null,null,false,true,'','col-2');
    $c[]=new cmp('ante_salud','s',3,$d,$w.' '.$o,'Ante de Salud','ante_salud',null,null,false,true,'','col-2');
    $c[]=new cmp('imp_diag','s',3,$d,$w.' '.$o,'Imp de Diag','imp_diag',null,null,false,true,'','col-2');
    $c[]=new cmp('uss_encu','s',3,$d,$w.' '.$o,'Uss de Encu','uss_encu',null,null,false,true,'','col-2');
    $c[]=new cmp('servicio_encu','s',3,$d,$w.' '.$o,'Servicio de Encu','servicio_encu',null,null,false,true,'','col-2');
    $c[]=new cmp('imp_diag2','s',3,$d,$w.' '.$o,'Imp de Diag2','imp_diag2',null,null,false,true,'','col-2');
    $c[]=new cmp('nece_apoy','s',3,$d,$w.' '.$o,'Nece de Apoy','nece_apoy',null,null,false,true,'','col-2');
    $c[]=new cmp('espe1','s',3,$d,$w.' '.$o,'Espe1','espe1',null,null,false,true,'','col-2');
    $c[]=new cmp('espe2','t',50,$d,$w.' '.$o,'Espe2','espe2',null,null,false,true,'','col-2');
    $c[]=new cmp('adh_tto','s',3,$d,$w.' '.$o,'Adh de Tto','adh_tto',null,null,false,true,'','col-2');
    $c[]=new cmp('observaciones','t',60,$d,$w.' '.$o,'Observaciones','observaciones',null,null,false,true,'','col-2');
    $c[]=new cmp('usu_equipo','t',7,$d,$w.' '.$o,'Usu de Equipo','usu_equipo',null,null,true,true,'','col-2');
    for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta .="<div class='encabezado placuifam'>TABLA DE COMPROMISOS CONCERTADOS</div>
	<div class='contenido' id='seguim-lis' >".lis_seguim()."</div></div>";
	return $rta;
}

function gra_seguim(){
	$id=divide($_POST['idp']);
    // var_dump(COUNT($id));
    if(COUNT($id)==1){
      $sql = "INSERT INTO tabla VALUES (?,?,?,?,?,?,?,?,?,?)";
      $params = [
        ['type' => 'i', 'value' => NULL ],
        ['type' => 's', 'value' => $id[0]],
        ['type' => 's', 'value' => $_POST['fecha']],
        ['type' => 'i', 'value' => $_POST['equipo']],
        ['type' => 's', 'value' => $_POST['obs']],
        ['type' => 's', 'value' => date("Y-m-d H:i:s")],
        ['type' => 'i', 'value' => $_SESSION['us_sds']],
        ['type' => 's', 'value' => ''],
        ['type' => 's', 'value' => ''],
        ['type' => 's', 'value' => 'A']
      ];
      $rta = mysql_prepd($sql, $params);
    }else{
   /*  $sql="UPDATE hog_planconc SET cumple=?,fecha_update=?,usu_update=? WHERE idcon=?"; //  compromiso=?, equipo=?, 
    $params = [
        ['type' => 's', 'value' => $_POST['cumplio']],
        ['type' => 's', 'value' => date("Y-m-d H:i:s")],
        ['type' => 'i', 'value' => $_SESSION['us_sds']],
        ['type' => 'i', 'value' => $id[1]]
      ];
      $rta = mysql_prepd($sql, $params); */
    }
return $rta;
}

function get_seguim(){
  if($_REQUEST['id']==''){
    return "";
  }else{
    // print_r($_POST);
    $id=divide($_REQUEST['id']);
    // print_r($id);
    $sql="SELECT concat(key1,'_',key2) 'id'
          FROM `tabla` 
          WHERE key1='{$id[0]}' AND key2='{$id[1]}'";
    $info=datos_mysql($sql);
     return json_encode($info['responseResult'][0]);
      } 
}


function opc_motivo_estado($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=74 and estado='A' ORDER BY 1",$id);
}
  function opc_ejemplo($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
        // var_dump($a);
		if ($a=='seguim' && $b=='acciones'){
			$rta="<nav class='menu right'>";
				
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   
