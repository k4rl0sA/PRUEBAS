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
function lis_compConc(){
    // print_r($_POST);
    $id = (isset($_POST['id'])) ? divide($_POST['id']) : divide($_POST['idp']) ;
$info=datos_mysql("SELECT COUNT(*) total FROM hog_planconc 
WHERE idviv=".$id[0]."");
$total=$info['responseResult'][0]['total'];
$regxPag=5;
$pag=(isset($_POST['pag-compConc']))? ($_POST['pag-compConc']-1)* $regxPag:0;

    $sql="SELECT concat(idviv,'_',idcon) ACCIONES, idcon AS Cod_Compromiso,compromiso,
        FN_CATALOGODESC(26,equipo) 'Equipo',cumple
        FROM `hog_planconc` 
            WHERE idviv='".$id[0];
        $sql.="' ORDER BY fecha_create";
        $sql.=' LIMIT '.$pag.','.$regxPag;
        //  echo $sql;
        $datos=datos_mysql($sql);
        return create_table($total,$datos["responseResult"],"compConc",$regxPag);
        /* return panel_content($datos["responseResult"],"planc-lis",10); */
}


function cmp_compConc(){
    $rta="";
	// $rta .="<div class='encabezado vivienda'>TABLA DE INTEGRANTES FAMILIA</div>
	//<div class='contenido' id='datos-lis' >".lis_datos()."</div></div>";
	$t=['id'=>'','fecha'=>'','accion1'=>'','desc_accion1'=>'','accion2'=>'','desc_accion2'=>'','accion3'=>'','desc_accion3'=>'','accion4'=>'','desc_accion4'=>'','observacion'=>''];
	$d=get_compConc();
	if ($d==""){$d=$t;}
	$u=($d['id']=='')?true:false;
	$hoy=date('Y-m-d');
    $w="placuifam";
	$o='accide';
	$e="";
	$key='pln';
	$o='compConc';
	$c[]=new cmp($o,'e',null,'PLAN DE CUIDADO FAMILIAR CONCERTADO',$w);
	$c[]=new cmp('obs','a',50,$e,$w.' '.$o,'Compromisos concertados','observaciones',null,null,true,true,'','col-7');
	$c[]=new cmp('equipo','s','3',$e,$w.' '.$o,'Equipo que concerta','equipo',null,null,true,true,'','col-2');
	$c[]=new cmp('cumplio','o','2',$e,$w.' '.$o,'cumplio','cumplio',null,null,false,true,'','col-1');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta .="<div class='encabezado placuifam'>TABLA DE COMPROMISOS CONCERTADOS</div>
	<div class='contenido' id='compConc-lis' >".lis_compConc()."</div></div>";
	return $rta;
}

	function gra_compConc(){
		// print_r($_POST);
	$id=divide($_POST['idp']);
    $sql1="select idviv from hog_plancuid where idviv='{$id[0]}'";
    $info = datos_mysql($sql1);
    if (!$info['responseResult']) {
      $sql = "INSERT INTO hog_plancuid VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

      $params = [
        ['type' => 'i', 'value' => NULL],
        ['type' => 'i', 'value' => $id[0]],
        ['type' => 's', 'value' => $_POST['fecha_caracteriza']],
        ['type' => 's', 'value' => $_POST['accion1']],
        ['type' => 's', 'value' => $_POST['desc_accion1']],
        ['type' => 's', 'value' => $_POST['accion2']],
        ['type' => 's', 'value' => $_POST['desc_accion2']],
        ['type' => 's', 'value' => $_POST['accion3']],
        ['type' => 's', 'value' => $_POST['desc_accion3']],
        ['type' => 's', 'value' => $_POST['accion4']],
        ['type' => 's', 'value' => $_POST['desc_accion4']],
        ['type' => 's', 'value' => $_POST['observacion']],
        ['type' => 'i', 'value' => $_SESSION['us_sds']],
        ['type' => 's', 'value' => date("Y-m-d H:i:s")],
        ['type' => 's', 'value' => ''],
        ['type' => 's', 'value' => ''],
        ['type' => 's', 'value' => 'A']
      ];
      $rta = mysql_prepd($sql, $params);
    }else{
$sql="UPDATE hog_plancuid SET  accion1=?,desc_accion1=?,accion2=?,desc_accion2=?,accion3=?,desc_accion3=?,accion4=?,desc_accion4=?,observacion=?,usu_update=?,fecha_update=? WHERE idviv=?";

$params = [
        ['type' => 's', 'value' => $_POST['accion1']],
        ['type' => 's', 'value' => $_POST['desc_accion1']],
        ['type' => 's', 'value' => $_POST['accion2']],
        ['type' => 's', 'value' => $_POST['desc_accion2']],
        ['type' => 's', 'value' => $_POST['accion3']],
        ['type' => 's', 'value' => $_POST['desc_accion3']],
        ['type' => 's', 'value' => $_POST['accion4']],
        ['type' => 's', 'value' => $_POST['desc_accion4']],
        ['type' => 's', 'value' => $_POST['observacion']],
        ['type' => 'i', 'value' => $_SESSION['us_sds']],
        ['type' => 's', 'value' => date("Y-m-d H:i:s")],
['type' => 'i', 'value' => $id[0]]
      ];
      $rta = mysql_prepd($sql, $params);
    }
return $rta;
	}

	function get_compConc(){
        // print_r($_POST);
        if (!$_POST['id']) {
            return '';
        }
        $id = divide($_POST['id']);
        $sql = "SELECT compromiso,equipo,cumple
                FROM `hog_planconc` 
                WHERE idviv='{$id[0]}' AND idcon='{$id[1]}'
                LIMIT 1";
        // echo $sql;		
        $info = datos_mysql($sql);
        if (!$info['responseResult']) {
            return '';
        }
        return json_encode($info['responseResult'][0]);
	}

    function opc_accion1desc_accion1($id=''){
        if($_REQUEST['id']!=''){
                    $id=divide($_REQUEST['id']);
                    $sql="SELECT idcatadeta ,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
                    $info=datos_mysql($sql);
                    return json_encode($info['responseResult']);
            }
        }
        
        function opc_accion2desc_accion2($id=''){
          if($_REQUEST['id']!=''){
                $id=divide($_REQUEST['id']);
                $sql="SELECT idcatadeta,descripcion  FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
                $info=datos_mysql($sql);		
                return json_encode($info['responseResult']);
              }
          }
          function opc_accion3desc_accion3($id=''){
            if($_REQUEST['id']!=''){
                  $id=divide($_REQUEST['id']);
                  $sql="SELECT idcatadeta 'id',descripcion 'asc' FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
                  $info=datos_mysql($sql);		
                  return json_encode($info['responseResult']);
                }
            }
            function opc_accion4desc_accion4($id=''){
            if($_REQUEST['id']!=''){
                  $id=divide($_REQUEST['id']);
                  $sql="SELECT idcatadeta 'id',descripcion 'asc' FROM `catadeta` WHERE idcatalogo='75' and estado='A' and valor='".$id[0]."' ORDER BY LENGTH(idcatadeta), idcatadeta;";
                  $info=datos_mysql($sql);		
                  return json_encode($info['responseResult']);
                }
            }
        
        function opc_desc_accion1($id=''){
          return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
          }
        function opc_desc_accion2($id=''){
            return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
        }
        function opc_desc_accion3($id=''){
            return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=75 and estado='A' ORDER BY 1",$id);
        }
        function opc_accion1($id=''){
        return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
        }
        function opc_accion2($id=''){
        return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
        }
        function opc_accion3($id=''){
        return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
        }
        function opc_accion4($id=''){
        return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=22 and estado='A' ORDER BY 1",$id);
        }
        
        function opc_equipo($id=''){
            return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=26 and estado='A' ORDER BY 1",$id);
        } 


	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
        var_dump($a);
		if ($a=='compConc' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'compConc',event,this,['fecha','tipo_activi'],'../vivienda/plncon.php');\"></li>";  //   act_lista(f,this);
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   