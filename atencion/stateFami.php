<?php
ini_set('display_errors','1');
require_once "../libs/gestion.php";
$perf=perfil($_POST['tb']);
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



function focus_statFam(){
	return 'statFam';
   }
   
   
   function men_statFam(){
	$rta=cap_menus('statFam','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = "";
	 $acc=rol($a);
  if ($a=='statFam' && isset($acc['crear']) && $acc['crear']=='SI') {  
	  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	  return $rta;
   }
}
   function cmp_statFam(){
    $rta="";
    $hoy=date('Y-m-d');
    $t=['id'=>'','cod_fam'=>'','estado_fam'=>'','motivo_estafam'=>'']; 
    $w='statFam';
    // var_dump($_POST);
    $d=get_statFam(); 
    if ($d=="") {$d=$t;}
    $u=($d['id']=='')?true:false;
    $o='datos';
    $c[]=new cmp($o,'e',null,'ESTADOS DE LA FAMILIA',$w);
    $c[]=new cmp('id','h',15,$_POST['id'],$w.' '.$o,' ','id',null,'####',false,false);
    $c[]=new cmp('estado_fam','s',3,$d['estado_fam'],$w.' '.$o,'Estado de la Visita','estado_fam',null,null,true,$u,'','col-5',"enbValue('estado_fam','StA',5);");
    $c[]=new cmp('motivo_estafam','s',3,$d['motivo_estafam'],$w.' StA '.$o,'Motivo de Rechazado','motivo_estafam',null,null,false,false,'','col-5');
    for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
    return $rta;
     }

    function get_statFam(){
      // var_dump($_POST);
      // var_dump($_GET);
      if($_POST['id']==''){
        return "";
      }else{
         $id=divide($_POST['id']);
        $sql="SELECT id_eacfam id,cod_fam,estado_fam,motivo_estafam from eac_fam WHERE cod_fam='{$id[0]}' limit 1 ";
        $info=datos_mysql($sql);
        if(isset($info['responseResult'][0])){ 
          var_dump($info['responseResult'][0]);
            return $info['responseResult'][0];
        }else{
          return "";
        }
      } 
    }

    function opc_estado_fam($id=''){
      return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=227 and estado='A' ORDER BY 1",$id);
    }
    function opc_motivo_estafam($id=''){
      return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=5 and estado='A' ORDER BY 1",$id);
    }

  
    function gra_statFam(){
      $id=divide($_POST['id']);
      /* var_dump($_POST);
      var_dump($_REQUEST);
      var_dump($_GET); */
      if(COUNT($id)==8){
      
        // echo $x;
 //echo $sql;
      }else{
        $sql="INSERT INTO eac_fam VALUES (?,?,?,?,?,?,?,?,?)";
        $params = [
        ['type' => 'i', 'value' => NULL],
        ['type' => 'i', 'value' => $_POST['id']],
        ['type' => 's', 'value' => $_POST['estado_fam']],
        ['type' => 's', 'value' => $_POST['motivo_estafam']],
        ['type' => 'i', 'value' => $_SESSION['us_sds']],
        ['type' => 's', 'value' => date("Y-m-d H:i:s")],
        ['type' => 's', 'value' => ''],
        ['type' => 's', 'value' => ''],
        ['type' => 's', 'value' => 'A']];
        $rta = mysql_prepd($sql, $params);
      // echo $sql;
    }
    //   return "correctamente";
      return $rta; 
    }


	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   