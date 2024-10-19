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
$info=datos_mysql("SELECT COUNT(*) total FROM hog_planconc WHERE idviv=".$id[0]."");
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
        return create_table($total,$datos["responseResult"],"compConc",$regxPag,'plncon.php');
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
	$c[]=new cmp('obs','a',50,$e,$w.' '.$o,'Compromisos concertados','observaciones',null,null,true,true,'','col-7');
	$c[]=new cmp('equipo','s','3',$e,$w.' '.$o,'Equipo que concerta','equipo',null,null,true,true,'','col-2');
	$c[]=new cmp('cumplio','o','2',$e,$w.' '.$o,'cumplio','cumplio',null,null,false,true,'','col-1');
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


	function get_compConc(){
        // print_r($_POST);
        /* if (!$_POST['id']) {
            return '';
        }
        $id = divide($_POST['id']);
        if(count($id)==2){
          $sql = "SELECT concat(idviv,'_',idcon) 'id',compromiso,equipo,cumple
                FROM `hog_planconc` 
                WHERE idviv='{$id[0]}' AND idcon='{$id[1]}'
                LIMIT 1";
        // echo $sql;		
          $info = datos_mysql($sql);
        }else{
          $sql = "SELECT idviv 'id',compromiso,equipo,cumple
                FROM `hog_planconc` 
                WHERE idviv='{$id[0]}'
                LIMIT 1";
                echo $sql;
                $info = datos_mysql($sql);
        }
        if (!$info['responseResult']) {
          return '';

        }else{
          return $info['responseResult'][0];
          
        } */




        if($_REQUEST['id']==''){
          return "";
        }else{
          // print_r($_POST);
          $id=divide($_REQUEST['id']);
          // print_r($id);
          $sql="SELECT concat(idviv,'_',idcon) 'id',compromiso,equipo,cumple
                FROM `hog_planconc` 
                WHERE idviv='{$id[0]}' AND idcon='{$id[1]}'";
          // echo $sql;
          // LEFT JOIN personas P ON F.tipo_doc=P.tipo_doc AND F.documento=P.idpersona
          // LEFT JOIN hog_viv H ON P.vivipersona = H.idviv
          // LEF JOIN ( SELECT CONCAT(estrategia, '_', sector_catastral, '_', nummanzana, '_', predio_num, '_', unidad_habit, '_', estado_v) AS geo, direccion, localidad, barrio
                    // FROM hog_geo ) AS G ON H.idgeo = G.geo
          // print_r($id);
          $info=datos_mysql($sql);
           return json_encode($info['responseResult'][0]);
            } 
        
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
        // var_dump($a);
		if ($a=='compConc' && $b=='acciones'){
			$rta="<nav class='menu right'>";
				$rta.="<li class='icono editar ' title='Editar Compromiso' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getDataFetch,500,'compConc',event,this,'plncon.php',['obs','equipo']);\"></li>";  //   act_lista(f,this);
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   