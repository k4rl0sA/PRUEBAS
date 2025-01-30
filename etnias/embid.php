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

function focus_emb_Id(){
	return 'emb_Id';
   }
   
   function men_emb_Id(){
	$rta=cap_menus('emb_Id','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 $acc=rol($a);
	   if ($a=='emb_Id'  && isset($acc['crear']) && $acc['crear']=='SI'){  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	 
	   }
  return $rta;
}
function lis_emb_Id(){
    // print_r($_POST);
    $id = (isset($_POST['id'])) ? divide($_POST['id']) : divide($_POST['idp']) ;
$info=datos_mysql("SELECT COUNT(*) total FROM tabla WHERE idviv=".$id[0]."");
$total=$info['responseResult'][0]['total'];
$regxPag=5;
$pag=(isset($_POST['pag-emb_Id']))? ($_POST['pag-emb_Id']-1)* $regxPag:0;

    $sql="SELECT concat(key1,'_',key2) ACCIONES
        FROM `tabla` 
            WHERE key1='".$id[0];
        $sql.="' ORDER BY fecha_create";
        $sql.=' LIMIT '.$pag.','.$regxPag;
        //  echo $sql;
        $datos=datos_mysql($sql);
        return create_table($total,$datos["responseResult"],"emb_Id",$regxPag,'plncon.php');
}


function cmp_emb_Id(){
  $rta="";
  $w="placuifam";
	$t=['id'=>'']; 
	$e="";
	$key='pln';
	$o='emb_Id';
  $d='';
  $d=($d=="")?$d=$t:$d;
  $days=fechas_app('ETNIAS');
  var_dump($_POST);
	$c[]=new cmp($o,'e',null,'IDENTIFICACION DEL RIESGO',$w);
    $c[]=new cmp('idp','h',15,$_POST['id'],$w.' '.$key.' '.$o,'id','id',null,'####',false,false);
    $c[]=new cmp('idpeople','n',10,$d['idpeople'],$w.' '.$o,'Idpeople','idpeople',null,null,true,true,'','col-2');
    $c[]=new cmp('fechavisi','d',10,$d['fechavisi'],$w.' '.$o,'Fechavisi','fechavisi',null,null,true,true,'','col-2');
    $c[]=new cmp('lider','t',50,$d['lider'],$w.' '.$o,'Lider','lider',null,null,true,true,'','col-2');
    $c[]=new cmp('educacion','o',2,$d['educacion'],$w.' '.$o,'Educacion','educacion',null,null,true,true,'','col-2');
    $c[]=new cmp('espanol','o',2,$d['espanol'],$w.' '.$o,'Espanol','espanol',null,null,true,true,'','col-2');
    $c[]=new cmp('saberes','s',3,$d['saberes'],$w.' '.$o,'Saberes','saberes',null,null,true,true,'','col-2');
    $c[]=new cmp('enfoque','s',3,$d['enfoque'],$w.' '.$o,'Enfoque','enfoque',null,null,true,true,'','col-2');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta .="<div class='encabezado placuifam'>TABLA DE COMPROMISOS CONCERTADOS</div>
	<div class='contenido' id='emb_Id-lis' >".lis_emb_Id()."</div></div>";
	return $rta;
}

function gra_emb_Id(){
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

function get_emb_Id(){
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

  function opc_ejemplo($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
        // var_dump($a);
		if ($a=='emb_Id' && $b=='acciones'){
			$rta="<nav class='menu right'>";
				
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   
