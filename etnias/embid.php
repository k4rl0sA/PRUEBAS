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


function cmp_emb_Id(){
  $rta="";
  $w="placuifam";
	$t=['id'=>'','fechavisi'=>'','lider'=>'','educacion'=>'','espanol'=>'','saberes'=>'','enfoque'=>'']; 
	$e="";
	$key='pln';
	$o='emb_Id';
  $d=get_emb_Id();
  $d=($d=="")?$d=$t:$d;
  $days=fechas_app('ETNIAS');
  var_dump($_POST);

  $keys=$_POST['id'];
	$c[]=new cmp($o,'e',null,'IDENTIFICACIóN',$w);
    $c[]=new cmp('id','h',15,$_POST['id'],$w.' '.$key.' '.$o,'id','id',null,'####',false,false);
    $c[]=new cmp('fechavisi','d',10,$d['fechavisi'],$w.' '.$o,'Fechavisi','fechavisi',null,null,true,true,'','col-2',"validDate(this,$days,0);");
    $c[]=new cmp('lider','t',100,$d['lider'],$w.' '.$o,'Lider','lider',null,null,true,true,'','col-3');
    $c[]=new cmp('educacion','o',2,$d['educacion'],$w.' '.$o,'Educacion','educacion',null,null,true,true,'','col-1');
    $c[]=new cmp('espanol','o',2,$d['espanol'],$w.' '.$o,'Espanol','espanol',null,null,true,true,'','col-1');
    $c[]=new cmp('saberes','s',3,$d['saberes'],$w.' '.$o,'Saberes','saberes',null,null,true,true,'','col-15');
    $c[]=new cmp('enfoque','s',3,$d['enfoque'],$w.' '.$o,'Enfoque','enfoque',null,null,true,true,'','col-15');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function gra_emb_Id(){
	$id=divide($_POST['id']);
    var_dump($_POST);
    if(COUNT($id)==1){
      $sql = "INSERT INTO etn_identi VALUES (?,?,?,?,?,?,?,?,?,?)";
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
    }else{
   $sql="UPDATE etn_identi SET  idpeople=?,fechavisi=?,lider=?,educacion=?,espanol=?,saberes=?,enfoque=?,usu_creo=?,fecha_create=?,usu_update=?,fecha_update=?,WHERE idriesgo=?";
    $params = [
        ['type' => 's', 'value' => $_POST['cumplio']],
        ['type' => 's', 'value' => date("Y-m-d H:i:s")],
        ['type' => 'i', 'value' => $_SESSION['us_sds']],
        ['type' => 'i', 'value' => $id[1]]
      ];
    }
    $rta = mysql_prepd($sql, $params);
return $rta;
}

function get_emb_Id(){
  if($_REQUEST['id']==''){
    return "";
  }else{
    print_r($_REQUEST);
    $id=divide($_REQUEST['id']);
    // print_r($id);
    $sql="SELECT idriesgo,idpeople,fechavisi,lider,educacion,espanol,saberes,enfoque
          FROM `etn_identi` 
          WHERE idriesgo='{$id[0]}'";
    $info=datos_mysql($sql);
     return json_encode($info['responseResult'][0]);
      } 
}

function opc_saberes($id=''){
    return opc_sql('SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=256 and estado="A" ORDER BY 1',$id);
}
  
function opc_enfoque($id=''){
       return opc_sql('SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=257 and estado="A" ORDER BY 1',$id);
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
	   
