<script src="../libs/js/a.js"></script>
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


function divide($a){
	$id=explode("_", $a);
	return ($id);
}

function lis_cambiar(){
	// CAMBIAR LOS DATOS DEL MODULO , TABLA 
	$info=datos_mysql("SELECT COUNT(*) total from hog_geo where 1 ".whe_cambiar()." AND estado_v <>'7'"); //modificar el nombre de la tabla que se requiera
	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-cambiar']))? ($_POST['pag-cambiar']-1)* $regxPag:0; ////modificar el nombre de la tabla que se requiera[]


	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(estrategia,'_',sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estado_v) ACCIONES,
	FN_CATALOGODESC(42,`estrategia`) estrategia,
	sector_catastral,
	nummanzana 'Manzana',
	predio_num 'predio',
	unidad_habit 'Unidad Hab',
	FN_CATALOGODESC(3,zona) zona,
	FN_CATALOGODESC(2,localidad) 'Localidad',
	usu_creo,
	fecha_create,
	FN_CATALOGODESC(44,`estado_v`) estado 
  FROM `hog_geo` 
  WHERE estado_v = 1 
  AND concat(sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estrategia) NOT IN (
	SELECT concat(sector_catastral,'_',nummanzana,'_',predio_num,'_',unidad_habit,'_',estrategia) 
	FROM hog_geo 
	WHERE estado_v in(4,5,6,7))";
	$sql.=whe_cambiar();
	$sql.="ORDER BY fecha_create";
	$sql.=' LIMIT '.$pag.','.$regxPag;

		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"cambiar",$regxPag);//modificar el nombre de la tabla que se requiera
	} 

function whe_cambiar() {
	$sql = "";
	if ($_POST['fsector'])
		$sql .= " AND sector_catastral = '".$_POST['fsector']."'";
	if ($_POST['fdigita'])
		$sql .= " AND usu_creo ='".$_POST['fdigita']."'";
	return $sql;
}


/* function focus_cambiar(){
 return 'cambiar';
}


function men_cambiar(){
 $rta=cap_menus('cambiar','pro');
 return $rta;
} */


function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='cambiar'){  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  $rta .= "<li class='icono $a cancelar'    title='Cerrar'          Onclick=\"ocultar('".$a."','".$b."');\" >";
  }
  return $rta;
}


function cmp_cambiar(){
 $rta="";
 
 $t=['estrategia'=>'','subred'=>'','zona'=>'','localidad'=>'','upz'=>'','barrio'=>'','territorio'=>'','territorio'=>'','microterritorio'=>'','sector_catastral'=>'','direccion'=>'',
 'direccion_nueva'=>'','nummanzana'=>'','predio_num'=>'','unidad_habit'=>'','vereda'=>'','vereda_nueva'=>'',
 'cordx'=>'','cordy'=>'','estrato'=>'','asignado'=>'','estado_v'=>'','motivo_estado'=>''];
 //t= variable que trae un array con todos los campos del formulario para tear o no los datos
 $w='cambiar';//w= nombre que me indica los campos del formulario
 $d=get_cambiar(); //d= me trae los valores de la consulta con el id asociado en la funcion get
  if ($d=="") {$d=$t;} //me indica si no hay valores en la variable get y me trae los valores de t
 $u=($d['sector_catastral']=='')?true:false;// me indica si debo o no actualizar los campos a traves de un campo
 $key=$d['sector_catastral'].'_'.$d['nummanzana'].'_'.$d['predio_num'].'_'.$d['unidad_habit'].'_'.$d['estrategia'].'_'.$d['estado_v'];
 $o='infgen';//es el subtitulo que indica la seccion del formulario
 $c[]=new cmp($o,'e',null,'INFORMACIÓN GENERAL',$w);//indica el titulo de la seccion 
 $c[]=new cmp('idgeo','h','20',$key,$w.' '.$o,'','',null,null,true,$u,'','col-1');
 $c[]=new cmp('estrategia','s','3',$d['estrategia'],$w.' '.$o,'Estrategia','estrategia',null,null,true,$u,'','col-3');
 $c[]=new cmp('subred','s','3',$d['subred'],$w.' '.$o,'Subred','subred',null,null,true,$u,'','col-3');
 $c[]=new cmp('zona','s','3',$d['zona'],$w.' '.$o,'Zona','zona',null,null,true,$u,'','col-4');
 $c[]=new cmp('localidad','s','3',$d['localidad'],$w.' '.$o,'Localidad','localidad',null,null,false,$u,'','col-2',false,['upz']);
 $c[]=new cmp('upz','s','3',$d['upz'],$w.' '.$o,'Upz','upz',null,null,false,$u,'','col-2',false,['bar']);
 $c[]=new cmp('barrio','t','8',$d['barrio'],$w.' '.$o,'Barrio','barrio',null,null,false,$u,'','col-2');
 $c[]=new cmp('territorio','s','3',$d['territorio'],$w.' '.$o,'Territorio','territorio',null,null,false,$u,'','col-2');
 $c[]=new cmp('microterritorio','s','3',$d['microterritorio'],$w.' '.$o,'Microterritorio','microterritorio',null,null,false,$u,'','col-2');
 $c[]=new cmp('sector_catastral','n','6',$d['sector_catastral'],$w.' '.$o,'Sector Catastral','sector_catastral',null,null,true,$u,'','col-2');
 $c[]=new cmp('nummanzana','n','3',$d['nummanzana'],$w.' '.$o,'Nummanzana','nummanzana',null,null,true,$u,'','col-2');
 $c[]=new cmp('predio_num','n','3',$d['predio_num'],$w.' '.$o,'Predio de Num','predio_num',null,null,true,$u,'','col-2');
 $c[]=new cmp('unidad_habit','n','4',$d['unidad_habit'],$w.' '.$o,'Unidad habitacional','unidad_habit',null,null,true,$u,'','col-2');
 $c[]=new cmp('estrato','s','3',$d['estrato'],$w.' '.$o,'Estrato','estrato',null,null,false,$u,'','col-2');
 $c[]=new cmp('direccion','t','50',$d['direccion'],$w.' '.$o,'Direccion','direccion',null,null,false,$u,'','col-4');
 $c[]=new cmp('edi','o',2,'',$w.' '.$o,'Actualiza Dirección ?','edi',null,null,false,true,'','col-2','updaAddr(this,false,[\'zona\',\'direccion_nueva\',\'vereda_nueva\',\'cordxn\',\'cordyn\']);');//enabFiel(this,true,[adi]);
 $c[]=new cmp('direccion_nueva','t','50',$d['direccion_nueva'],$w.' '.$o,'Direccion Nueva','direccion_nueva',null,null,false,$u,'','col-4');
 
 $c[]=new cmp('vereda','t','50',$d['vereda'],$w.' '.$o,'Vereda','vereda',null,null,false,$u,'','col-4');
 $c[]=new cmp('cordx','t','15',$d['cordx'],$w.' '.$o,'Cordx','cordx',null,null,false,$u,'','col-3');
 $c[]=new cmp('cordy','t','15',$d['cordy'],$w.' '.$o,'Cordy','cordy',null,null,false,$u,'','col-3');
 $c[]=new cmp('vereda_nueva','t','50',$d['vereda_nueva'],$w.' '.$o,'Vereda Nueva','vereda_nueva',null,null,false,$u,'','col-5');
 $c[]=new cmp('cordxn','t','15',$d['cordx'],$w.' '.$o,'Cordx Nueva','cordx',null,null,false,$u,'','col-25');
 $c[]=new cmp('cordyn','t','15',$d['cordy'],$w.' '.$o,'Cordy Nueva','cordy',null,null,false,$u,'','col-25');

 $c[]=new cmp('asignado','s','3',$d['asignado'],$w.' '.$o,'Asignado','asignado',null,null,false,$u,'','col-25');
 $c[]=new cmp('estado_v','s',2,$d['estado_v'],$w.' '.$o,'estado','estado',null,null,true,true,'','col-25','enabFielSele(this,true,[\'motivo_estado\'],[\'4\',\'5\']);hideExpres(\'estado_v\',[\'7\']);');
 $c[]=new cmp('motivo_estado','s','3',$d['motivo_estado'],$w.' '.$o,'Motivo de Estado','motivo_estado',null,null,false,false,'','col-4');


 for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
 return $rta;
}




/* function opc_estrategia($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=42 and estado='A' ORDER BY 1",$id);
} */



function get_cambiar(){
	if($_POST['idgeo']=='0'){
		return "";
	}else{
		$id=divide($_POST['idgeo']);
		$sql="SELECT estrategia,subred,zona,localidad,upz,barrio,territorio,microterritorio,sector_catastral,direccion,direccion_nueva,nummanzana,predio_num,unidad_habit,vereda,vereda_nueva,
		cordx,cordy,estrato,asignado,estado_v,motivo_estado 
		FROM `hog_geo` WHERE  estrategia='{$id[0]}' AND sector_catastral='{$id[1]}' AND nummanzana='{$id[2]}' AND predio_num='{$id[3]}' AND unidad_habit='{$id[4]}' AND estado_v='{$id[5]}'";

		$info=datos_mysql($sql);
		return $info['responseResult'][0];
	} 
}



 
/*
function gra_cambiar(){
	
	 $sql="INSERT INTO hog_geo VALUES 
	(NULL,TRIM(UPPER('{$_POST['estrategia']}')),
	TRIM(UPPER('{$_POST['subred']}')),
	TRIM(UPPER('{$_POST['zona']}')),
	TRIM(UPPER('{$_POST['localidad']}')),
	TRIM(UPPER('{$_POST['upz']}')),
	TRIM(UPPER('{$_POST['barrio']}')),
	TRIM(UPPER('{$_POST['territorio']}')),
	TRIM(UPPER('{$_POST['microterritorio']}')),
	TRIM(UPPER('{$_POST['sector_catastral']}')),
	TRIM(UPPER('{$_POST['direccion']}')),
	TRIM(UPPER('{$_POST['direccion_nueva']}')),
	TRIM(UPPER('{$_POST['nummanzana']}')),
	TRIM(UPPER('{$_POST['predio_num']}')),
	TRIM(UPPER('{$_POST['unidad_habit']}')),
	TRIM(UPPER('{$_POST['vereda']}')),
	TRIM(UPPER('{$_POST['vereda_nueva']}')),
	TRIM(UPPER('{$_POST['cordx']}')),
	TRIM(UPPER('{$_POST['cordy']}')),
	TRIM(UPPER('{$_POST['cordxn']}')),
	TRIM(UPPER('{$_POST['cordyn']}')),
	TRIM(UPPER('{$_POST['estrato']}')),
	TRIM(UPPER('{$_POST['asignado']}')),
	TRIM(UPPER('{$_POST['estado_v']}')),
	TRIM(UPPER('{$_POST['motivo_estado']}')),
	TRIM(UPPER('{$_SESSION['us_sds']}')),
	DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL);";
	// echo $sql;
  $rta=dato_mysql($sql);
  return $rta;
}
 */
function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($a);
// var_dump($rta);
	if ($a=='cambiar' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";	
		$rta.="<li class='icono cambiar' title='Editar Cambiar' id='".$c['ACCIONES']."' Onclick=\"mostrar('cambiar','pro',event,'','lib',7,'','".$c['ACCIONES']."');\"></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
<script>
// Obtener los valores de los parámetros
var cambiar = 'cambiar';
var pro = 'pro';
var event = '';
var lib = '../prueba1/lib.php';
var w = 7;
var acciones = '<?php echo $c['ACCIONES']; ?>';

// Construir los datos del formulario
var formData = new FormData();
formData.append('func', 'mostrar');
formData.append('cambiar', cambiar);
formData.append('pro', pro);
formData.append('event', event);
formData.append('lib', lib);
formData.append('w', w);
formData.append('acciones', acciones);

// Enviar la solicitud POST
fetch('../prueba1/lib.php', {
  method: 'POST',
  body: formData
})
.then(function(response) {
  // Aquí puedes manejar la respuesta de la solicitud
  // y realizar cualquier acción adicional necesaria
})
.catch(function(error) {
  console.error('Error:', error);
});
</script>