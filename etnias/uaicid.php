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
    $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";  
  return $rta;
}
function lis_uaic_id(){
    // print_r($_POST);
/*$id = (isset($_POST['id'])) ? divide($_POST['id']) : (isset($_POST['idp']) ? divide($_POST['idp']) : null);
$info=datos_mysql("SELECT COUNT(*) total FROM uaic_ide WHERE idviv=".$id[0]."");
$total=$info['responseResult'][0]['total'];
$regxPag=5;
$pag=(isset($_POST['pag-uaic_id']))? ($_POST['pag-uaic_id']-1)* $regxPag:0;

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
  $rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div>
  <div class='contenido' id='uaic_id-lis'>".lis_uaic_id()."</div></div>";
  //$rta='';
  $w="iden";
	$t=['iduaic'=>'','idpeople'=>'','fecha_seg'=>'','parentesco'=>'','nombre_cui'=>'','tipo_doc'=>'','num_doc'=>'','telefono'=>'','era'=>'','eda'=>'','dnt'=>'','des_sinto'=>'','peri_cef'=>'','peri_bra'=>'','peso'=>'','talla'=>'','zcore'=>'','clasi_nut'=>'','tempe'=>'','frec_res'=>'','frec_car'=>'','satu'=>'','sales_reh'=>'','aceta'=>'','traslados_uss'=>'','educa'=>'','menor_hos'=>'','tempe2'=>'','frec_res2'=>'','frec_car2'=>'','satu2'=>'','seg_entmed'=>'','observacion'=>'','usu_creo'=>'','fecha_create'=>'','usu_update'=>'','fecha_update'=>'','estado']; 
	$e="";
	$key='pln';
	$o='uaic_id';
  $d='';
  $d=($d=="")?$d=$t:$d;
  $days=fechas_app('ETNIAS');
  var_dump($_POST);
	$c[]=new cmp($o,'e',null,'MODULO INICIAL',$w);
    $c[]=new cmp('iduaic','h',11,$d['iduaic'],$w.' '.$o,'Iduaic','iduaic',null,null,true,true,'','col-2');
    $c[]=new cmp('idpeople','h',18,$d['idpeople'],$w.' '.$o,'Idpeople','idpeople',null,null,true,true,'','col-2');
    $c[]=new cmp('fecha_seg','d',10,$d['fecha_seg'],$w.' '.$o,'Fecha de Seguimiento','fecha_seg',null,null,true,true,'','col-25');
    $c[]=new cmp('parentesco','s',3,$d['parentesco'],$w.' '.$o,'Parentesco','paren',null,null,true,true,'','col-25');
    $c[]=new cmp('nombre_cui','t',50,$d['nombre_cui'],$w.' '.$o,'Nombre Completo del Cuidador','nombre_cui',null,null,true,true,'','col-5');
    $c[]=new cmp('tipo_doc','s',3,$d['tipo_doc'],$w.' '.$o,'Tipo De Documento ','tipo_doc',null,null,true,true,'','col-3');
    $c[]=new cmp('num_doc','n',18,$d['num_doc'],$w.' '.$o,'Número De Documento','num_doc',null,null,false,true,'','col-3');
    $c[]=new cmp('telefono','n',21,$d['telefono'],$w.' '.$o,'Teléfono De Contacto','telefono',null,null,false,true,'','col-4');

    $o='moticons';
    $c[]=new cmp($o,'e',null,'MOTIVO DE LA CONSULTA',$w);
    $c[]=new cmp('era','s',3,$d['era'],$w.' '.$o,'Enfermedad Respiratoria Aguda (Era)','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('eda','s',3,$d['eda'],$w.' '.$o,'Enfermedad Diarreica Aguda (Eda)','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('dnt','s',3,$d['dnt'],$w.' '.$o,'Desnutrición (Dnt)','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('des_sinto','t',80,$d['des_sinto'],$w.' '.$o,'Descripcion De Sintomas','des_sinto',null,null,false,true,'','col-4');

    $o='sigymed';
    $c[]=new cmp($o,'e',null,'SIGNOS VITALES Y MEDIDAS ANTROPOMETRICAS AL INGRESO DE LA ATENCIÓN',$w);
    $c[]=new cmp('peri_cef','sd',3,$d['peri_cef'],$w.' '.$o,'Perimetro Cefalico (Cm)','peri_cef',null,null,false,true,'','col-2');
    $c[]=new cmp('peri_bra','sd',3,$d['peri_bra'],$w.' '.$o,'Perimetro Braquial (Cm)','peri_bra',null,null,false,true,'','col-2');
    $c[]=new cmp('peso','sd',5,$d['peso'],$w.' '.$o,'Peso (Kg)','peso',null,null,false,true,'','col-2');
    $c[]=new cmp('talla','sd',4,$d['talla'],$w.' '.$o,'Talla (Cm)','talla',null,null,false,true,'','col-2');
    $c[]=new cmp('zcore','t',50,$d['zcore'],$w.' '.$o,'Zcore','zcore',null,null,false,true,'','col-2');
    $c[]=new cmp('clasi_nutri','s','3',$d,$w.' '.$o,'Clasificación Nutricional','clasi_nutri',null,null,false,false,'','col-2');
    $c[]=new cmp('tempe','sd',3,$d['tempe'],$w.' '.$o,'Temperatura','tempe',null,null,false,true,'','col-2');
    $c[]=new cmp('frec_res','sd',3,$d['frec_res'],$w.' '.$o,'Frecuencia Respiratoria','frec_res',null,null,false,true,'','col-2');
    $c[]=new cmp('frec_car','sd',3,$d['frec_car'],$w.' '.$o,'Frecuencia Cardiaca ','frec_car',null,null,false,true,'','col-2');
    $c[]=new cmp('satu','sd',3,$d['satu'],$w.' '.$o,'Saturacion','satu',null,null,false,true,'','col-2');

    $o='mane';
    $c[]=new cmp($o,'e',null,'MANEJO',$w);
    $c[]=new cmp('sales_reh','s',3,$d['sales_reh'],$w.' '.$o,'Sales De Rehidratación','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('aceta','s',3,$d['aceta'],$w.' '.$o,'Acetaminofen','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('traslados_uss','s',3,$d['traslados_uss'],$w.' '.$o,'Traslados de Uss','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('educa','s',3,$d['educa'],$w.' '.$o,'Educación','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('menor_hos','s',3,$d['menor_hos'],$w.' '.$o,'Menor Hospitalizado','rta',null,null,false,true,'','col-2');

    $o='sigyman2';
    $c[]=new cmp($o,'e',null,'SIGNOS VITALES POSTERIOR AL PLAN DE MANEJO EN LA UAIC',$w);
    $c[]=new cmp('tempe2','sd',50,$d['tempe2'],$w.' '.$o,'Temperatura','tempe2',null,null,false,true,'','col-2');
    $c[]=new cmp('frec_res2','sd',3,$d['frec_res2'],$w.' '.$o,'Frecuencia Respiratoria','frec_res2',null,null,false,true,'','col-2');
    $c[]=new cmp('frec_car2','sd',60,$d['frec_car2'],$w.' '.$o,'Frecuencia Cardiaca ','frec_car2',null,null,false,true,'','col-2');
    $c[]=new cmp('satu2','sd',7,$d['satu2'],$w.' '.$o,'Saturación De Oxigeno','satu2',null,null,false,true,'','col-2');
    $c[]=new cmp('seg_entmed','t',7,$d['seg_entmed'],$w.' '.$o,'Seguimiento A Entrega De Medicamentos','seg_entmed',null,null,false,true,'','col-2');

    $o='aspe';
    $c[]=new cmp($o,'e',null,'ASPECTOS FINALES',$w);
    $c[]=new cmp('observacion','t',7,$d['observacion'],$w.' '.$o,'Observacion','observacion',null,null,true,true,'','col-1');
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
		if ($a=='uaic_id' && $b=='acciones'){
			$rta="<nav class='menu right'>";
				
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }