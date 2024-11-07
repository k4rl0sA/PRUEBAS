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


function focus_compConc(){
	return 'compConc';
   }
   
   
   function men_compConc(){
	$rta=cap_menus('compConc','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 $acc=rol($a);
	   if ($a=='compConc'  && isset($acc['crear']) && $acc['crear']=='SI'){  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	   }
  return $rta;
}

function cmp_compConc(){
    $rta="";
    $w="placuifam";
      $o='accide';
      $e="";
      $key='pln';
      $o='compConc';
      $c[]=new cmp($o,'e',null,'PLAN DE CUIDADO FAMILIAR CONCERTADO',$w);
        $c[]=new cmp('idp','h',15,$_POST['id'],$w.' '.$key.' '.$o,'id','id',null,'####',false,false);
        $c[]=new cmp('cumplio','s','2',$e,$w.' '.$o,'cumplio','cumplio',null,null,false,true,'','col-1');
        $c[]=new cmp('tipo','s','2',$e,$w.' '.$o,'cumplio','cumplio',null,null,false,true,'','col-1');
      
      for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
        $rta .="<div class='encabezado placuifam'>TABLA DE COMPROMISOS CONCERTADOS</div>
        <div class='contenido' id='compConc-lis' >".lis_compConc()."</div></div>";
      return $rta;
  }

  function gra_compConc(){
	$id=divide($_POST['idp']);
    // var_dump(COUNT($id));
    if(COUNT($id)==1){
      $sql = "INSERT INTO hog_planconc VALUES (?,?,?,?,?,?,?,?,?,?)";
      $params = [
        ['type' => 'i', 'value' => NULL ],
        ['type' => 's', 'value' => $id[0]],
        ['type' => 's', 'value' => $_POST['obs']],
        ['type' => 'i', 'value' => $_POST['equipo']],
        ['type' => 's', 'value' => $_POST['cumplio']],
        ['type' => 's', 'value' => date("Y-m-d H:i:s")],
        ['type' => 'i', 'value' => $_SESSION['us_sds']],
        ['type' => 's', 'value' => ''],
        ['type' => 's', 'value' => ''],
        ['type' => 's', 'value' => 'A']
      ];
      $rta = mysql_prepd($sql, $params);
    }else{
    $sql="UPDATE hog_planconc SET cumple=?,fecha_update=?,usu_update=? WHERE idcon=?"; //  compromiso=?, equipo=?, 
    $params = [
        ['type' => 's', 'value' => $_POST['cumplio']],
        ['type' => 's', 'value' => date("Y-m-d H:i:s")],
        ['type' => 'i', 'value' => $_SESSION['us_sds']],
        ['type' => 'i', 'value' => $id[1]]
      ];
      $rta = mysql_prepd($sql, $params);
    }
return $rta;
}

function opc_cumplio($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=0 and estado='A' ORDER BY 1",$id);
}


function formato_dato($a,$b,$c,$d){
    $b=strtolower($b);
    $rta=$c[$d];
    // var_dump($a);
    if ($a=='compConc' && $b=='acciones'){
        $rta="<nav class='menu right'>";
            $rta.="<li title='Ver Apgar'><i class='fa-solid fa-eye ico' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getDataFetch,500,'compConc',event,this,'plncon.php',['obs','equipo']);\"></i></li>";  //   act_lista(f,this);
    $rta.="<li class='icono editar' title='Seguimiento a Compromisos' id='".$c['ACCIONES']."' Onclick=\"mostrar('plnsegcon','pro',event,'','plnsegcon.php',7);\"></li>";
        }
    return $rta;
}

function bgcolor($a,$c,$f='c'){
    $rta="";
    return $rta;
   }
   
