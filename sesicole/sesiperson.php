<?php
 require_once '../libs/gestion.php';
ini_set('display_errors','1');
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

function focus_sespers(){
	return 'sespers';
   }
   
   function men_sespers(){
	$rta=cap_menus('sespers','pro');
	return $rta;
   } 
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 if ($a=='sespers'){  
	   $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
		 $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
	 }
	 return $rta;
   }
   

function cmp_sespers(){
	$rta="";
	$t=['fecha_int'=>'','activi'=>'','luga'=>'','temati1'=>'','desc_temati1'=>'','temati2'=>'','desc_temati2'=>'','temati3'=>'','desc_temati3'=>'','temati4'=>'','desc_temati4'=>'','temati5'=>'','desc_temati5'=>'','temati6'=>'','desc_temati6'=>'','temati7'=>'','desc_temati7'=>'','temati8'=>'','desc_temati8'=>''];
	$d=get_sespers();
	if ($d==""){$d=$t;}
	// var_dump($_POST);
	$id=divide($_POST['id']);
    $w="alertas";
	$o='infbas';
	// var_dump($p);
	$days=fechas_app('vivienda');
		
	$o='Sesper';
	$c[]=new cmp($o,'e',null,'IDENTIFICACIÓN DE PERSONAS',$w);
	$c[]=new cmp('idp','h',15,$_POST['id'],$w.' '.$o,'id','id',null,'####',false,false);


	// $c[]=new cmp('medico','s',15,$d,$w.' der '.$o,'Asignado','medico',null,null,false,false,'','col-5');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function get_sespers(){
	return '';
}