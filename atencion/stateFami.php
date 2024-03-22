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
    $t=['id_eacfam'=>'','cod_fam'=>'','estado_fam'=>'','motivo_estafam'=>'']; 
    $w='statFam';
    $d=get_statFam(); 
    if ($d=="") {$d=$t;}
    $u=($d['sector_catastral']=='')?true:false;
    $o='datos';
    $c[]=new cmp($o,'e',null,'ESTADOS DE LA FAMILIA',$w);
    $c[]=new cmp('id_eacfam','h',15,$d['id'],$w.' '.$o,' ','id',null,'####',false,false);
    $c[]=new cmp('cod_fam','n',11,$d['cod_fam'],$w.' '.$o,'Cod de Fam','cod_fam',null,null,true,true,'','col-2');
    $c[]=new cmp('estado_fam','s',3,$d['estado_fam'],$w.' '.$o,'Estado de Fam','estado_fam',null,null,false,true,'','col-2');
    $c[]=new cmp('motivo_estafam','s',3,$d['motivo_estafam'],$w.' '.$o,'Motivo de Estafam','motivo_estafam',null,null,true,true,'','col-2');
    for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
    return $rta;
     }

     function get_atenc($tip,$doc){
      $sql="SELECT atencion_idpersona FROM eac_atencion 
      WHERE atencion_tipodoc ='$tip' AND atencion_idpersona ='$doc'";
      // echo $sql;
      $info=datos_mysql($sql);
      if(isset($info['responseResult'][0])){ 
        return true;
      }else{
        return false;
      }
  }

     function get_statFam(){
      if($_POST['id']==''){
        return "";
      }else{
         $id=divide($_POST['id']);
        $sql="SELECT id_eacfam,cod_fam,estado_fam,motivo_estafam
        WHERE cod_fam='{$id[0]}' ";
        // echo $sql;
        $info=datos_mysql($sql);
        if(isset($info['responseResult'][0])){ 
            return $info['responseResult'][0];
        }else{
          return "";
        }
      } 
    }

    function gra_statFam(){
      $id=divide($_POST['id']);
      // print_r($id);
      //die("Ok");
      if($_POST['id']!=''){
      $sql="UPDATE `eac_adolescencia` SET 
      preg1=trim(upper('{$_POST['preg1']}')),
      preg2=trim(upper('{$_POST['preg2']}')),
      preg3=trim(upper('{$_POST['preg3']}')),
      preg4=trim(upper('{$_POST['preg4']}')),
      preg5=trim(upper('{$_POST['preg5']}')),
      preg6=trim(upper('{$_POST['preg6']}')),
      preg7=trim(upper('{$_POST['preg7']}')),
      preg8=trim(upper('{$_POST['preg8']}')),
      preg9=trim(upper('{$_POST['preg9']}')),
      preg10=trim(upper('{$_POST['preg10']}')),
      preg11=trim(upper('{$_POST['preg11']}')),
      preg12=trim(upper('{$_POST['preg12']}')),
      preg13=trim(upper('{$_POST['preg13']}')),
      preg14=trim(upper('{$_POST['preg14']}')),
        `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
        WHERE adolecencia_tipo_doc='$id[0]' AND adolecencia_documento='$id[1]'"; 
        // echo $x;
 //echo $sql;
      }else{
        $tip=$_POST['adolecencia_tipo_doc'];
        $doc=$_POST['adolecencia_documento'];
        if(get_atenc($tip,$doc)){

        $sql="INSERT INTO eac_adolescencia VALUES (NULL,
        trim(upper('{$_POST['adolecencia_tipo_doc']}')),
        trim(upper('{$_POST['adolecencia_documento']}')),
        trim(upper('{$_POST['preg1']}')),
        trim(upper('{$_POST['preg2']}')),
        trim(upper('{$_POST['preg3']}')),
        trim(upper('{$_POST['preg4']}')),
        trim(upper('{$_POST['preg5']}')),
        trim(upper('{$_POST['preg6']}')),
        trim(upper('{$_POST['preg7']}')),
        trim(upper('{$_POST['preg8']}')),
        trim(upper('{$_POST['preg9']}')),
        trim(upper('{$_POST['preg10']}')),
        trim(upper('{$_POST['preg11']}')),
        trim(upper('{$_POST['preg12']}')),
        trim(upper('{$_POST['preg13']}')),
        trim(upper('{$_POST['preg14']}')),
        TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
        // echo $sql;
        $rta=dato_mysql($sql);
      }else{
        $rta="Error: msj['Para realizar esta operacion, debe tener una atención previa, valida e intenta nuevamente']";
      }
      // echo $sql;
    }
    //   return "correctamente";
      return $rta; 
    }


	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   