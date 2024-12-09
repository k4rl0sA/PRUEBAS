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
	$d=get_sesigcole();
	if ($d==""){$d=$t;}
	// var_dump($_POST);
	$id=divide($_POST['id']);
    $w="alertas";
	$o='infbas';
	// var_dump($p);
	$days=fechas_app('vivienda');
		
	$o='Secgi';
	$c[]=new cmp($o,'e',null,'SESIONES GRUPALES Y COLECTIVAS',$w);
	$c[]=new cmp('fecha_int','d','10',$d['fecha_int'],$w.' '.$o,'fecha_Intervencion','fecha_int',null,null,true,true,'','col-15',"validDate(this,$days,0);"); 
	$c[]=new cmp('activi','s','15',$d['activi'],$w.' '.$o,'Tipo de Actividad','fm1',null,null,false,true,'','col-25');
	$c[]=new cmp('luga','t','15',$d['luga'],$w.' '.$o,'Lugar','rta',null,null,true,true,'','col-6',"fieldsValue('agen_intra','aIM','1',true);");
	$c[]=new cmp('temati1','s','3',$d['temati1'],$w.' '.$o,'tematica 1','temati1',null,null,true,true,'','col-15',"selectDepend('accion1','desc_accion1','../crea-fam/plancui.php');");
	$c[]=new cmp('desc_temati1','s','3',$d['desc_temati1'],$w.' '.$o,'Descripcion tematica 1','desc_temati1',null,null,true,true,'','col-35');
    $c[]=new cmp('temati2','s','3',$d['temati2'],$w.' '.$o,'tematica 2','temati2',null,null,false,true,'','col-15',"selectDepend('accion2','desc_accion2','../crea-fam/plancui.php');");
    $c[]=new cmp('desc_temati2','s','3',$d['desc_temati2'],$w.' '.$o,'Descripcion tematica 2','desc_temati2',null,null,false,true,'','col-35');
    $c[]=new cmp('temati3','s','3',$d['temati3'],$w.' '.$o,'tematica 3','temati3',null,null,false,true,'','col-15',"selectDepend('accion3','desc_accion3','../crea-fam/plancui.php');");
    $c[]=new cmp('desc_temati3','s','3',$d['desc_temati3'],$w.' '.$o,'Descripcion tematica 3','desc_temati3',null,null,false,true,'','col-35');
    $c[]=new cmp('temati4','s','3',$d['temati4'],$w.' '.$o,'tematica 4','temati4',null,null,false,true,'','col-15',"selectDepend('accion4','desc_accion4','../crea-fam/plancui.php');");
    $c[]=new cmp('desc_temati4','s','3',$d['desc_temati4'],$w.' '.$o,'Descripcion tematica 4','desc_temati4',null,null,false,true,'','col-35');
	$c[]=new cmp('temati5','s','3',$d['temati5'],$w.' '.$o,'tematica 5','temati5',null,null,false,true,'','col-15',"selectDepend('accion4','desc_accion4','../crea-fam/plancui.php');");
    $c[]=new cmp('desc_temati5','s','3',$d['desc_temati5'],$w.' '.$o,'Descripcion tematica 5','desc_temati5',null,null,false,true,'','col-35');
	$c[]=new cmp('temati6','s','3',$d['temati6'],$w.' '.$o,'tematica 6','temati6',null,null,false,true,'','col-15',"selectDepend('accion4','desc_accion4','../crea-fam/plancui.php');");
    $c[]=new cmp('desc_temati6','s','3',$d['desc_temati6'],$w.' '.$o,'Descripcion tematica 6','desc_temati6',null,null,false,true,'','col-35');
	$c[]=new cmp('temati7','s','3',$d['temati7'],$w.' '.$o,'tematica 7','temati7',null,null,false,true,'','col-15',"selectDepend('accion4','desc_accion4','../crea-fam/plancui.php');");
    $c[]=new cmp('desc_temati7','s','3',$d['desc_temati7'],$w.' '.$o,'Descripcion tematica 7','desc_temati7',null,null,false,true,'','col-35');
	$c[]=new cmp('temati8','s','3',$d['temati8'],$w.' '.$o,'tematica 8','temati8',null,null,false,true,'','col-15',"selectDepend('accion4','desc_accion4','../crea-fam/plancui.php');");
    $c[]=new cmp('desc_temati8','s','3',$d['desc_temati8'],$w.' '.$o,'Descripcion tematica 8','desc_temati8',null,null,false,true,'','col-35');



	// $c[]=new cmp('medico','s',15,$d,$w.' der '.$o,'Asignado','medico',null,null,false,false,'','col-5');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}