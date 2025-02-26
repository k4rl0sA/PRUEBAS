<?php
require_once "../libs/gestion.php";
ini_set('display_errors','1');
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


function focus_uaic_id(){
	return 'uaic_id';
   }
      
   
  function men_uaic_id(){
	$rta=cap_menus('uaic_id','pro');
	return $rta;
   }
   
  
  function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 $acc=rol($a);
	   if ($a=='uaic_id'  && isset($acc['crear']) && $acc['crear']=='SI'){  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	  }
    $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";  
  return $rta;
}


function cmp_uaic_id(){
  // $rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div><div class='contenido' id='uaic_id-lis'>".lis_uaic_id()."</div></div>";
  $rta='';
  $w='modini';
	$t=['iduaic'=>'','idpeople'=>'','fecha_seg'=>'','parentesco'=>'','nombre_cui'=>'','tipo_doc'=>'','num_doc'=>'','telefono'=>'','era'=>'','eda'=>'','dnt'=>'','des_sinto'=>'','aten_medi'=>'','aten_part'=>'','peri_cef'=>'','peri_bra'=>'','peso'=>'','talla'=>'','zcore'=>'','clasi_nut'=>'','tempe'=>'','frec_res'=>'','frec_car'=>'','satu'=>'','sales_reh'=>'','aceta'=>'','traslados_uss'=>'','educa'=>'','menor_hos'=>'','tempe2'=>'','frec_res2'=>'','frec_car2'=>'','satu2'=>'','seg_entmed'=>'','observacion'=>'','clasi_nutri'=>'']; 
	$o='uaic_id';
  $e="";
  $d=get_uaic_id();
  $d=($d=="")?$d=$t:$d;
  $days=fechas_app('ETNIAS');
  $id = isset($d['iduaic']) ? $d['iduaic']:$_POST['id'];
  $p=get_persona();
  $ocu= ($p['ano']>5) ? true : false ;
	$meses = $p['ano'] * 12 + $p['mes'];
	// $esc=($p['ano']>=5 && $p['ano']<18 ) ? true : false ;
	$ed=$p['ano'];
	switch (true) {
			case $ed>=0 && $ed<=5 :
				$curso=1;
				break;
			case $ed>=6 && $ed<=11 :
				$curso=2;
				break;
			case $ed>=12 && $ed <=17 :
				$curso=3;
				break;
			case $ed>=18 && $ed <=28 :
				$curso=4;
				break;
			case $ed>=29 && $ed <=59 :
				$curso=5;
				break;
			case $ed>=60 :
				$curso=6;
				break;
		default:
			$curso='';
			break;
	}

	$des='des';
	$z='zS';
	$f='nFx';
	$days=fechas_app('vivienda');
	$old='Años: '.$p['ano'].' Meses: '.$p['mes'].' Dias:'.$p['dia'];

  // var_dump($_POST);
	$c[]=new cmp($o,'e',null,'MODULO INICIAL',$w);
    $c[]=new cmp('iduaic','h',11,$_POST['id'],$w.' '.$o,'iduaic',null,null,false,false,'','col-2');
    $c[]=new cmp('nombre','t','50',$p['nombres'],$w.' '.$f.' '.$o,'nombres','nombre',null,'',true,false,'','col-3');
	$c[]=new cmp('sexo','t','50',$p['sexo'],$w.' '.$f.' '.$z.' '.$o,'sexo','sexo',null,'',false,false,'','col-1');
	$c[]=new cmp('fechanacimiento','d','10',$p['fecha_nacimiento'],$w.' '.$f.' '.$z.' '.$o,'fecha nacimiento','fechanacimiento',null,'',true,false,'','col-15');
    $c[]=new cmp('edad','n','3',$old,$w.' '.$f.' '.$o,'Edad (Abordaje)','edad',null,'',false,false,'','col-25');
    $c[]=new cmp('fecha_seg','d',10,$d['fecha_seg'],$w.' '.$o,'Fecha de Seguimiento','fecha_seg',null,null,true,true,'','col-25',"validDate(this,$days,0);");
    $c[]=new cmp('parentesco','s',3,$d['parentesco'],$w.' '.$o,'Parentesco','paren',null,null,true,true,'','col-25');
    $c[]=new cmp('nombre_cui','t',50,$d['nombre_cui'],$w.' '.$o,'Nombre Completo del Cuidador','nombre_cui',null,null,true,true,'','col-5');
    $c[]=new cmp('tipo_doc','s',3,$d['tipo_doc'],$w.' '.$o,'Tipo De Documento ','tipo_doc',null,null,true,true,'','col-3');
    $c[]=new cmp('num_doc','n',18,$d['num_doc'],$w.' '.$o,'Número De Documento','num_doc',null,null,false,true,'','col-3');
    $c[]=new cmp('telefono','n',21,$d['telefono'],$w.' '.$o,'Teléfono De Contacto','telefono',null,null,false,true,'','col-4');

    $o='moticons';
    $c[]=new cmp($o,'e',null,'MOTIVO DE LA CONSULTA',$w);
    $c[]=new cmp('era','s',3,$d['era'],$w.' '.$o,'Enfermedad Respiratoria Aguda (Era)','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('eda','s',3,$d['eda'],$w.' '.$o,'Enfermedad Diarreica Aguda (Eda)','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('dnt','s',3,$d['dnt'],$w.' '.$o,'Desnutrición (Dnt)','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('des_sinto','t',80,$d['des_sinto'],$w.' '.$o,'Descripcion De Sintomas','des_sinto',null,null,false,true,'','col-4');

    $o='sigymed';
    $c[]=new cmp($o,'e',null,'SIGNOS VITALES Y MEDIDAS ANTROPOMETRICAS AL INGRESO DE LA ATENCIÓN',$w);
    $c[]=new cmp('aten_medi','s',3,$d['aten_medi'],$w.' '.$o,'Recibio Atención por Medico Ancestral','rta',null,null,false,true,'','col-25');
    $c[]=new cmp('aten_part','s',3,$d['aten_part'],$w.' '.$o,'Recibio Atención por Partera','rta',null,null,false,true,'','col-25');
    $c[]=new cmp('peri_cef','sd',3,$d['peri_cef'],$w.' '.$o,'Perimetro Cefalico (Cm)','peri_cef',null,null,false,true,'','col-2');
    $c[]=new cmp('peri_bra','sd',3,$d['peri_bra'],$w.' '.$o,'Perimetro Braquial (Cm)','peri_bra',null,null,false,true,'','col-2');
    $c[]=new cmp('peso','sd',5,$d['peso'],$w.' '.$o,'Peso (Kg)','peso',null,null,false,true,'','col-2');
    $c[]=new cmp('talla','sd',4,$d['talla'],$w.' '.$o,'Talla (Cm)','talla',null,null,false,true,'','col-2');
    $c[]=new cmp('zcore','t',50,$d['zcore'],$w.' '.$o,'Zcore','zcore',null,null,false,true,'','col-2');
    $c[]=new cmp('clasi_nutri','s','3',$d['clasi_nut'],$w.' '.$o,'Clasificación Nutricional','clasi_nutri',null,null,false,false,'','col-2');
    $c[]=new cmp('tempe','sd',3,$d['tempe'],$w.' '.$o,'Temperatura','tempe','rgxtemp',null,false,true,'','col-2');
    $c[]=new cmp('frec_res','sd',3,$d['frec_res'],$w.' '.$o,'Frecuencia Respiratoria','frec_res','rgxfresp',null,false,true,'','col-2');
    $c[]=new cmp('frec_car','sd',3,$d['frec_car'],$w.' '.$o,'Frecuencia Cardiaca ','frec_car','rgxfcard',null,false,true,'','col-2');
    $c[]=new cmp('satu','sd',3,$d['satu'],$w.' '.$o,'Saturacion','satu','rgxsatu',null,false,true,'','col-2');

    
    $o='mane';
    $c[]=new cmp($o,'e',null,'MANEJO',$w);
    $c[]=new cmp('sales_reh','s',3,$d['sales_reh'],$w.' '.$o,'Sales De Rehidratación','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('aceta','s',3,$d['aceta'],$w.' '.$o,'Acetaminofen','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('traslados_uss','s',3,$d['traslados_uss'],$w.' '.$o,'Traslados de Uss','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('educa','s',3,$d['educa'],$w.' '.$o,'Educación','rta',null,null,false,true,'','col-2');
    $c[]=new cmp('menor_hos','s',3,$d['menor_hos'],$w.' '.$o,'Menor Hospitalizado','rta',null,null,false,true,'','col-2');

    $o='sigyman2';
    $c[]=new cmp($o,'e',null,'SIGNOS VITALES POSTERIOR AL PLAN DE MANEJO EN LA UAIC',$w);
    $c[]=new cmp('tempe2','sd',50,$d['tempe2'],$w.' '.$o,'Temperatura','tempe2',null,null,false,true,'','col-2');
    $c[]=new cmp('frec_res2','sd',3,$d['frec_res2'],$w.' '.$o,'Frecuencia Respiratoria','frec_res2',null,null,false,true,'','col-2');
    $c[]=new cmp('frec_car2','sd',60,$d['frec_car2'],$w.' '.$o,'Frecuencia Cardiaca ','frec_car2',null,null,false,true,'','col-2');
    $c[]=new cmp('satu2','sd',7,$d['satu2'],$w.' '.$o,'Saturación De Oxigeno','satu2',null,null,false,true,'','col-2');
    $c[]=new cmp('seg_entmed','t',7,$d['seg_entmed'],$w.' '.$o,'Seguimiento A Entrega De Medicamentos','seg_entmed',null,null,false,true,'','col-2');

    $o='aspe';
    $c[]=new cmp($o,'e',null,'ASPECTOS FINALES',$w);
    $c[]=new cmp('observacion','a',7,$d['observacion'],$w.' '.$o,'Observacion','observacion',null,null,true,true,'','col-10');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function get_persona(){
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		$sql="SELECT idpersona,tipo_doc,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,FN_CATALOGODESC(21,sexo) sexo,fecha_nacimiento,fecha, 
		FN_EDAD(fecha_nacimiento,CURDATE()),
		TIMESTAMPDIFF(YEAR,fecha_nacimiento, CURDATE() ) AS ano,
  		TIMESTAMPDIFF(MONTH,fecha_nacimiento ,CURDATE() ) % 12 AS mes,
		DATEDIFF(CURDATE(), DATE_ADD(fecha_nacimiento,INTERVAL TIMESTAMPDIFF(MONTH, fecha_nacimiento, CURDATE()) MONTH)) AS dia
		from person P left join hog_carac V ON vivipersona=idfam
		WHERE P.idpeople='".$id[0]."'";
		// echo $sql;
		$info=datos_mysql($sql);
		if (!$info['responseResult']) {
			return '';
		}else{
			return $info['responseResult'][0];
		}
		}
	}

function gra_uaic_id(){
	$id = divide($_POST['id'] ?? '');
  $usu = $_SESSION['us_sds'];
$commonParams=[
  ['type' => 's', 'value' => $_POST['fecha_seg']??''],
  ['type' => 's', 'value' => $_POST['parentesco']??''],
  ['type' => 's', 'value' => $_POST['nombre_cui']??''],
  ['type' => 's', 'value' => $_POST['tipo_doc']??''],
  ['type' => 'i', 'value' => $_POST['num_doc']],
  ['type' => 'i', 'value' => $_POST['telefono']],
  ['type' => 's', 'value' => $_POST['era']??''],
  ['type' => 's', 'value' => $_POST['eda']??''],
  ['type' => 's', 'value' => $_POST['dnt']??''],
  ['type' => 's', 'value' => $_POST['des_sinto']??''],
  ['type' => 's', 'value' => $_POST['aten_medi']??''],
  ['type' => 's', 'value' => $_POST['aten_part']??''],
  ['type' => 's', 'value' => $_POST['peri_cef']??''],
  ['type' => 's', 'value' => $_POST['peri_bra']??''],
  ['type' => 's', 'value' => $_POST['peso']??''],
  ['type' => 's', 'value' => $_POST['talla']??''],
  ['type' => 's', 'value' => $_POST['zcore']??''],
  ['type' => 's', 'value' => $_POST['clasi_nutri']??''],
  ['type' => 's', 'value' => $_POST['tempe']??''],
  ['type' => 's', 'value' => $_POST['frec_res']??''],
  ['type' => 's', 'value' => $_POST['frec_car']??''],
  ['type' => 's', 'value' => $_POST['satu']??''],
  ['type' => 's', 'value' => $_POST['sales_reh']??''],
  ['type' => 's', 'value' => $_POST['aceta']??''],
  ['type' => 's', 'value' => $_POST['traslados_uss']??''],
  ['type' => 's', 'value' => $_POST['educa']??''],
  ['type' => 's', 'value' => $_POST['menor_hos']??''],
  ['type' => 's', 'value' => $_POST['tempe2']??''],
  ['type' => 's', 'value' => $_POST['frec_res2']??''],
  ['type' => 's', 'value' => $_POST['frec_car2']??''],
  ['type' => 's', 'value' => $_POST['satu2']??''],
  ['type' => 's', 'value' => $_POST['seg_entmed']??''],
  ['type' => 's', 'value' => $_POST['observacion']??'']  

];

    if(COUNT($id)==2){
      $equ=datos_mysql("select equipo from usuarios where id_usuario=".$_SESSION['us_sds']);
      $bina = isset($_POST['fequi'])?(is_array($_POST['fequi'])?implode("-", $_POST['fequi']):implode("-",array_map('trim',explode(",",str_replace("'","",$_POST['fequi']))))):'';
      $equi=$equ['responseResult'][0]['equipo'];
      $sql = "INSERT INTO uaic_ide VALUES (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,DATE_SUB(NOW(),INTERVAL 5 HOUR),?,?,'A');";
      $params = [
['type' => 'i', 'value' => $id[0]],
['type' => 's', 'value' => $_POST['fecha_seg']],
['type' => 's', 'value' => $_POST['parentesco']],
['type' => 's', 'value' => $_POST['nombre_cui']],
['type' => 's', 'value' => $_POST['tipo_doc']],
['type' => 'i', 'value' => $_POST['num_doc']],
['type' => 'i', 'value' => $_POST['telefono']],
['type' => 's', 'value' => $_POST['era']],
['type' => 's', 'value' => $_POST['eda']],
['type' => 's', 'value' => $_POST['dnt']],
['type' => 's', 'value' => $_POST['des_sinto']],
['type' => 's', 'value' => $_POST['aten_medi']],
['type' => 's', 'value' => $_POST['aten_part']],
['type' => 's', 'value' => $_POST['peri_cef']],
['type' => 's', 'value' => $_POST['peri_bra']],
['type' => 's', 'value' => $_POST['peso']],
['type' => 's', 'value' => $_POST['talla']],
['type' => 's', 'value' => $_POST['zcore']],
['type' => 's', 'value' => $_POST['clasi_nutri']],
['type' => 's', 'value' => $_POST['tempe']],
['type' => 's', 'value' => $_POST['frec_res']],
['type' => 's', 'value' => $_POST['frec_car']],
['type' => 's', 'value' => $_POST['satu']],
['type' => 's', 'value' => $_POST['sales_reh']],
['type' => 's', 'value' => $_POST['aceta']],
['type' => 's', 'value' => $_POST['traslados_uss']],
['type' => 's', 'value' => $_POST['educa']],
['type' => 's', 'value' => $_POST['menor_hos']],
['type' => 's', 'value' => $_POST['tempe2']],
['type' => 's', 'value' => $_POST['frec_res2']],
['type' => 's', 'value' => $_POST['frec_car2']],
['type' => 's', 'value' => $_POST['satu2']],
['type' => 's', 'value' => $_POST['seg_entmed']],
['type' => 's', 'value' => $_POST['observacion']],
['type' => 's', 'value' => $bina],
['type' => 's', 'value' => $equi],
['type' => 's', 'value' => $_SESSION['us_sds']],
['type' => 's', 'value' => NULL],
['type' => 's', 'value' => NULL]
      ];

      // $rta = show_sql($sql, $params);
     $rta = mysql_prepd($sql, $params);
    }else{
   $sql="UPDATE uaic_ide SET fecha_seg=?,parentesco=?,nombre_cui=?,tipo_doc=?,num_doc=?,telefono=?,era=?,eda=?,dnt=?,des_sinto=?,aten_medi=?,aten_part=?,peri_cef=?,peri_bra=?,peso=?,talla=?,zcore=?,clasi_nut=?,tempe=?,frec_res=?,frec_car=?,satu=?,sales_reh=?,aceta=?,traslados_uss=?,educa=?,menor_hos=?,tempe2=?,frec_res2=?,frec_car2=?,satu2=?,seg_entmed=?,observacion=?, usu_update=?, fecha_update=DATE_SUB(NOW(), INTERVAL 5 HOUR) WHERE iduaic=?";
    $params = [
        ['type' => 's', 'value' => $_POST['CAMPO1']],
        ['type' => 's', 'value' => date("Y-m-d H:i:s")],
        ['type' => 'i', 'value' => $_SESSION['us_sds']],
        ['type' => 'i', 'value' => $id[1]]//IDACORDE AL NUMERO DEL INDICE
      ];
      $rta = mysql_prepd($sql, $params);
    }
return $rta;
}

function get_uaic_id(){
  if($_POST['id']==''){
    return "";
  }else{
    // print_r($_POST);
    $id=divide($_POST['id']);
    // print_r($id);
    $sql="SELECT iduaic,fecha_seg,parentesco,nombre_cui,tipo_doc,num_doc,telefono,era,eda,dnt,des_sinto,aten_medi,aten_part,peri_cef,peri_bra,peso,talla,zcore,clasi_nut,tempe,frec_res,frec_car,satu,sales_reh,aceta,traslados_uss,educa,menor_hos,tempe2,frec_res2,frec_car2,satu2,seg_entmed,observacion
          FROM `uaic_ide` 
          WHERE idpeople='{$id[0]}'";
    $info=datos_mysql($sql);
    if (!$info['responseResult']) {
			return '';
		}else{
			return $info['responseResult'][0];
		}
      }
}

function opc_paren($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=263 and estado='A' ORDER BY 1",$id);
  }

  function opc_tipo_doc($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
  }

  function opc_rta($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
  }

  function opc_clasi_nutri($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=98 and estado='A' ORDER BY 1",$id);
    }

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
        // var_dump($a);
		if ($a=='uaic_id' && $b=='acciones'){
			$rta="<nav class='menu right'>";
				
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }