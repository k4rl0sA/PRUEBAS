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


function focus_uaic_id(){
	return 'uaic_id';
   }
      
   function men_uaic_id(){
	$rta=cap_menus('uaic_id','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 $acc=rol($a);
	   if ($a=='uaic_id'  && isset($acc['crear']) && $acc['crear']=='SI'){  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	 
	   }
  return $rta;
}
function lis_uaic_id(){
    // print_r($_POST);
    /*$id = (isset($_POST['id'])) ? divide($_POST['id']) : divide($_POST['idp']) ;
$info=datos_mysql("SELECT COUNT(*) total FROM uaic_ide WHERE idviv=".$id[0]."");
$total=$info['responseResult'][0]['total'];
$regxPag=5;
$pag=(isset($_POST['pag-cambiar']))? ($_POST['pag-cambiar']-1)* $regxPag:0;

    $sql="SELECT concat(key1,'_',key2) ACCIONES
        FROM `uaic_ide` 
            WHERE key1='".$id[0];
        $sql.="' ORDER BY fecha_create";
        $sql.=' LIMIT '.$pag.','.$regxPag;
        //  echo $sql;
        $datos=datos_mysql($sql);
        return create_table($total,$datos["responseResult"],"cambiar",$regxPag,'cambiar.php');*/
}


function cmp_uaic_id(){
  // $rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div><div class='contenido' id='uaic_id-lis'>".lis_uaic_id()."</div></div>";
  $rta='';
  $w="placuifam";
	$t=['id'=>'']; 
	$e="";
	$key='pln';
	$o='uaic_id';
  $d='';
  $d=($d=="")?$d=$t:$d;
  $days=fechas_app('VSP');
  var_dump($_POST);
	$c[]=new cmp($o,'e',null,'PLAN DE CUIDADO FAMILIAR CONCERTADO',$w);
    $c[]=new cmp('iduaic','h',11,$d['iduaic'],$w.' '.$o,'Iduaic','iduaic',null,null,true,true,'','col-2');
    $c[]=new cmp('idpeople','h',18,$d['idpeople'],$w.' '.$o,'Idpeople','idpeople',null,null,true,true,'','col-2');
    $c[]=new cmp('fecha_seg','d',10,$d['fecha_seg'],$w.' '.$o,'Fecha de Seg','fecha_seg',null,null,true,true,'','col-2');
    $c[]=new cmp('parentesco','s',3,$d['parentesco'],$w.' '.$o,'Parentesco','parentesco',null,null,true,true,'','col-2');
    $c[]=new cmp('nombre_cui','t',50,$d['nombre_cui'],$w.' '.$o,'Nombre de Cui','nombre_cui',null,null,true,true,'','col-2');
    $c[]=new cmp('tipo_doc','s',3,$d['tipo_doc'],$w.' '.$o,'Tipo de Doc','tipo_doc',null,null,true,true,'','col-2');
    $c[]=new cmp('num_doc','n',18,$d['num_doc'],$w.' '.$o,'Num de Doc','num_doc',null,null,false,true,'','col-2');
    $c[]=new cmp('telefono','n',21,$d['telefono'],$w.' '.$o,'Telefono','telefono',null,null,false,true,'','col-2');
    $c[]=new cmp('era','s',3,$d['era'],$w.' '.$o,'Era','era',null,null,false,true,'','col-2');
    $c[]=new cmp('eda','s',3,$d['eda'],$w.' '.$o,'Eda','eda',null,null,false,true,'','col-2');
    $c[]=new cmp('dnt','s',3,$d['dnt'],$w.' '.$o,'Dnt','dnt',null,null,false,true,'','col-2');
    $c[]=new cmp('des_sinto','s',3,$d['des_sinto'],$w.' '.$o,'Des de Sinto','des_sinto',null,null,false,true,'','col-2');
    $c[]=new cmp('peri_cef','s',3,$d['peri_cef'],$w.' '.$o,'Peri de Cef','peri_cef',null,null,false,true,'','col-2');
    $c[]=new cmp('peri_bra','s',3,$d['peri_bra'],$w.' '.$o,'Peri de Bra','peri_bra',null,null,false,true,'','col-2');
    $c[]=new cmp('peso','s',5,2,$d['peso'],$w.' '.$o,'Peso','peso',null,null,false,true,'','col-2');
    $c[]=new cmp('talla','sd',4,1,$d['talla'],$w.' '.$o,'Talla','talla',null,null,false,true,'','col-2');
    $c[]=new cmp('zcore','t',50,$d['zcore'],$w.' '.$o,'Zcore','zcore',null,null,false,true,'','col-2');
    $c[]=new cmp('clasi_nut','s',3,$d['clasi_nut'],$w.' '.$o,'Clasi de Nut','clasi_nut',null,null,false,true,'','col-2');
    $c[]=new cmp('tempe','s',3,$d['tempe'],$w.' '.$o,'Tempe','tempe',null,null,false,true,'','col-2');
    $c[]=new cmp('frec_res','s',3,$d['frec_res'],$w.' '.$o,'Frec de Res','frec_res',null,null,false,true,'','col-2');
    $c[]=new cmp('frec_car','s',3,$d['frec_car'],$w.' '.$o,'Frec de Car','frec_car',null,null,false,true,'','col-2');
    $c[]=new cmp('satu','s',3,$d['satu'],$w.' '.$o,'Satu','satu',null,null,false,true,'','col-2');
    $c[]=new cmp('sales_reh','s',3,$d['sales_reh'],$w.' '.$o,'Sales de Reh','sales_reh',null,null,false,true,'','col-2');
    $c[]=new cmp('aceta','s',3,$d['aceta'],$w.' '.$o,'Aceta','aceta',null,null,false,true,'','col-2');
    $c[]=new cmp('traslados_uss','s',3,$d['traslados_uss'],$w.' '.$o,'Traslados de Uss','traslados_uss',null,null,false,true,'','col-2');
    $c[]=new cmp('educa','s',3,$d['educa'],$w.' '.$o,'Educa','educa',null,null,false,true,'','col-2');
    $c[]=new cmp('menor_hos','s',3,$d['menor_hos'],$w.' '.$o,'Menor de Hos','menor_hos',null,null,false,true,'','col-2');
    $c[]=new cmp('tempe2','t',50,$d['tempe2'],$w.' '.$o,'Tempe2','tempe2',null,null,false,true,'','col-2');
    $c[]=new cmp('frec_res2','s',3,$d['frec_res2'],$w.' '.$o,'Frec de Res2','frec_res2',null,null,false,true,'','col-2');
    $c[]=new cmp('frec_car2','t',60,$d['frec_car2'],$w.' '.$o,'Frec de Car2','frec_car2',null,null,false,true,'','col-2');
    $c[]=new cmp('satu2','t',7,$d['satu2'],$w.' '.$o,'Satu2','satu2',null,null,false,true,'','col-2');
    $c[]=new cmp('seg_entmed','t',7,$d['seg_entmed'],$w.' '.$o,'Seg de Entmed','seg_entmed',null,null,false,true,'','col-2');
    $c[]=new cmp('observacion','t',7,$d['observacion'],$w.' '.$o,'Observacion','observacion',null,null,true,true,'','col-2');
    $c[]=new cmp('usu_creo','t',10,$d['usu_creo'],$w.' '.$o,'Usu de Creo','usu_creo',null,null,true,true,'','col-2');
    $c[]=new cmp('usu_update','t',10,$d['usu_update'],$w.' '.$o,'Usu de Update','usu_update',null,null,false,true,'','col-2');
    $c[]=new cmp('fecha_update','',,$d['fecha_update'],$w.' '.$o,'Fecha de Update','fecha_update',null,null,false,true,'','col-2');
    $c[]=new cmp('estado','',,$d['estado'],$w.' '.$o,'Estado','estado',null,null,false,true,'','col-2');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function gra_uaic_id(){
	$id=divide($_POST['idp']);
    // var_dump(COUNT($id));
    if(COUNT($id)==1){
      $sql = "INSERT INTO uaic_ide VALUES (?,?,?,?,?,?,?,?,?,?)";
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

function get_uaic_id(){
  if($_REQUEST['id']==''){
    return "";
  }else{
    // print_r($_POST);
    $id=divide($_REQUEST['id']);
    // print_r($id);
    $sql="SELECT concat(key1,'_',key2) 'id'
          FROM `uaic_ide` 
          WHERE key1='{$id[0]}' AND key2='{$id[1]}'";
    $info=datos_mysql($sql);
     return json_encode($info['responseResult'][0]);
      } 
}

  function opc_ejemplo($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
        // var_dump($a);
		if ($a=='uaic_id' && $b=='acciones'){
			$rta="<nav class='menu right'>";
				
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }