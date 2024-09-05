<?php
require_once "../lib/php/gestion.php";
ini_set('display_errors', '1');
$perf = perfil($_POST['tb']);
if (!isset($_SESSION['us_sds'])) {
    http_response_code(401);
    echo json_encode(['redirect' => '/']);
    exit();
} else {
	$rta = "";
	switch ($_POST['a']) {
		case 'csv':
			header_csv($_REQUEST['tb'] . '.csv');
			$rs = array('', '');
			echo csv($rs, '');
			die;
			break;
		default:
			eval('$rta=' . $_POST['a'] . '_' . $_POST['tb'] . '();');
			if (is_array($rta)) json_encode($rta);
			else echo $rta;
	}
}

function whe_deriva() {
	$sql = "";
	if ($_POST['fidp'])
		$sql .= " AND documento='".$_POST['fidp']."' ";
	if ($_POST['fest'])
		$sql .= " AND realizado IN (".$_POST['fest'].")";
	/* if ($_POST['fdes']) {
		$fefin=date('Y-m-d');
		$feini = date("Y-m-d",strtotime($fefin."- 2 days"));
		if ($_POST['fhas']) {
		      $sql .= " AND fecha_create BETWEEN '$feini 00:00:00' and '$fefin 23:59:59' ";
		} else {
		    $sql .= " AND fecha_create BETWEEN '$feini 00:00:00' and '$feini 23:59:59' ";
		}
	} */
	return $sql;
}

function lis_deriva(){	
	$info=datos_mysql("SELECT COUNT(*) total FROM derivaciones WHERE 1 ".whe_deriva());
	$total=$info['responseResult'][0]['total'];
	$regxPag=17;
	$pag=(isset($_POST['pag-deriva']))? ($_POST['pag-deriva']-1)* $regxPag:0; 

	$sql="SELECT  `id_deriva` ACCIONES,`documento`, `tipo_doc` , `derivado_colaborador` Colaborador, `realizado`, `observacion` from derivaciones
	 WHERE 1 ".whe_deriva()." 
	 ORDER BY fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;
	// echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"deriva",$regxPag,"lib.php");
}

function focus_deriva(){
	return 'deriva';
   }
   
   function men_deriva(){
	$rta=cap_menus('deriva','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	$rta = "";
	// $rta .= "<li class='fa-solid fa-floppy-disk $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
	$rta .= "<button class='frm-btn $a grabar' onclick=\"grabar('$a', this);\"><span class='frm-txt'>Grabar</span><i class='fa-solid fa-floppy-disk icon'></i></button>";
	/* $rta .="<button class='frm-btn $a actualizar' onclick=\"act_lista('".$a."',this);\"'>Actualizar</button>";
	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('".$a."',this);\"></li>"; */
	$rta .= "<li class='icono $a cancelar'    title='Cerrar'          Onclick=\"ocultar('".$a."','".$b."');\" >";
	return $rta;
  }

  function cmp_deriva(){
	$rta="";
	$t=['idcatalogo'=>'','idcatadeta'=>'','descripcion'=>'','valor'=>'','estado'=>'A'];
	$w='deriva';
	//~ $id=explode('-',$_REQUEST['id']);
	$d='';
	// $d=get_deriva(); 
	//~ var_dump($d);
	if ($d=="") {$d=$t;}
	$d['estado']=($d['estado']=='A')?'SI':'NO';
	if($d['idcatalogo']){$ids=$d['idcatalogo'].'_'.$d['idcatadeta'];}else{$ids='';}
	$o='infcat';
	$c[]=new cmp('id','h',100,$ids,$w,'',0,'','','',false,'','col-1');
	$c[]=new cmp('cat','s',3,$d['idcatalogo'],$w.' '.$o,'Nombre Catalogo','catalogo',null,'',false,false,'','col-2');
	$c[]=new cmp('cod','t',12,$d['idcatadeta'],$w.' '.$o,'Codigo Item','',null,'',false,false,'','col-2');
	$c[]=new cmp('des','t',40,$d['descripcion'],$w.' '.$o,'Texto Descriptivo Item',null,null,'',false,false,'','col-2');
	$c[]=new cmp('est','o',1,$d['estado'],$w.' '.$o,'Item Activo',null,null,'',false,false,'','col-2');
	$c[]=new cmp('val','n',10,$d['valor'],$w.' '.$o,'Valor',0,'rgxdfnum','NNNN',false,true,'Número de 4 a 10 Digitos','col-1');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta.="</div>";
	return $rta;
	}

	function opc_catalogo(){
	 return opc_sql("SELECT `idcatalogo`,concat(idcatalogo,' - ',nombre) FROM `catalogo` ORDER BY 1",$id = ($_POST['id'] == '') ? '' : divide($_POST['id'])[0]);
	}

	function get_deriva(){
		if($_POST['id']=='0'){
			return "";
		}else{
			$id=divide($_POST['id']);
			$sql="SELECT * FROM catadeta WHERE idcatalogo='".$id[0]."' AND idcatadeta='".$id[1]."'";
			$info=datos_mysql($sql);
			return $info['responseResult'][0];		
		} 
	}

   function formato_dato($a,$b,$c,$d){
	$b=strtolower($b);
	$rta=$c[$d];
	if (($a=='deriva') && ($b=='acciones')){
		   $rta="<nav class='menu right'>";
		   $rta.="<li class='fa-solid fa-pen-to-square' title='Editar $a' id='".$c['ACCIONES']."' Onclick=\"mostrar('deriva','pro',event,'','lib.php',4);\"></li>";
		   $rta.="</nav>";
	   }    
	return $rta;
   }

function bgcolor($a,$c,$f='c'){
	$rta="";
	//~ if ($a=='transacciones'&&$c['ESTADO']=='A') $rta='green';
	return $rta;
   }
?>
