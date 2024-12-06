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



function lis_sesigcole(){
	$total="SELECT COUNT(*) AS total FROM (
		SELECT G.idgeo AS ACCIONES,G.idgeo AS Cod_Predio,H.direccion,H.sector_catastral Sector,H.nummanzana AS Manzana,H.predio_num AS predio,H.unidad_habit AS 'Unidad',FN_CATALOGODESC(2,H.localidad) AS 'Localidad', H.upz AS PRUEBA ,U1.nombre,G.fecha_create,FN_CATALOGODESC(44,G.estado_v) AS estado 
		FROM geo_gest G	LEFT JOIN hog_geo H ON G.idgeo = H.idgeo LEFT JOIN usuarios U ON H.subred = U.subred	LEFT JOIN usuarios U1 ON H.usu_creo = U1.id_usuario
			WHERE G.estado_v IN ('7') ".whe_sesigcole()." AND U.id_usuario = '{$_SESSION['us_sds']}') AS Subquery";
	$info=datos_mysql($total);
	$total=$info['responseResult'][0]['total']; 
	$regxPag=5;
	$pag=(isset($_POST['pag-sesigcole']))? ($_POST['pag-sesigcole']-1)* $regxPag:0;

	
$sql="SELECT G.idgeo AS ACCIONES,
	G.idgeo AS Cod_Predio,
	H.direccion,
	H.sector_catastral Sector,
	H.nummanzana AS Manzana,
	H.predio_num AS predio,
	H.unidad_habit AS 'Unidad',
	FN_CATALOGODESC(2,H.localidad) AS 'Localidad',
	U1.nombre,
	G.fecha_create,
	FN_CATALOGODESC(44,G.estado_v) AS estado
	FROM geo_gest G
	LEFT JOIN hog_geo H ON G.idgeo = H.idgeo
	LEFT JOIN usuarios U ON H.subred = U.subred
	LEFT JOIN usuarios U1 ON H.usu_creo = U1.id_usuario 
WHERE G.estado_v in('7') ".whe_sesigcole()." 
	AND U.id_usuario = '{$_SESSION['us_sds']}'
	ORDER BY nummanzana, predio_num
	LIMIT $pag, $regxPag";
//  echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"sesigcole",$regxPag);
}	

function whe_sesigcole() {
	$sql = "";
	if (!empty($_POST['fpred']) && $_POST['fdigita']) {
		$sql .= " AND G.idgeo = '" . $_POST['fpred'] . "' AND G.usu_creo ='" . $_POST['fdigita'] . "'";
	}else{
		$sql .="AND G.idgeo ='0'";
	} 
	return $sql;
}

function focus_sesigcole(){
 return 'sesigcole';
}

function men_sesigcole(){
 $rta=cap_menus('sesigcole','pro');
 return $rta;
} 

function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  if ($a=='sesigcole'){  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
  	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  }
  return $rta;
}

function cmp_sesigcole(){
	$rta="<div class='encabezado medid'>TABLA DE ALERTAS</div>
	<div class='contenido' id='alertas-lis'></div></div>";
	// $t=['nombres'=>'','fechanacimiento'=>'','edad'=>'','peso'=>'','talla'=>'','imc'=>'','tas'=>'','tad'=>'','glucometria'=>'','perime_braq'=>'','perime_abdom'=>'','percentil'=>'','zscore'=>'','findrisc'=>'','oms'=>'','alert1'=>'','alert2'=>'','alert3'=>'','alert4'=>'','alert5'=>'','alert6'=>'','alert7'=>'','alert8'=>'','alert9'=>'','alert10'=>'','select1'=>'','selmul1'=>'[]','selmul2'=>'[]','selmul3'=>'[]','selmul4'=>'[]','selmul5'=>'[]','selmul6'=>'[]','selmul7'=>'[]','selmul8'=>'[]','selmul9'=>'[]','selmul10'=>'[]','fecha'=>'','tipo'=>''];
	$p=get_persona();
	// if ($d==""){$d=$t;}
	// var_dump($_POST);
	$id=divide($_POST['id']);
	$d='';
    $w="alertas";
	$o='infbas';
	// var_dump($p);
	
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
	$c[]=new cmp('idp','h',15,$_POST['id'],$w.' '.$f.' '.$o,'id','id',null,'',false,false);
	$c[]=new cmp($o,'e',null,'INFORMACION DE alertas',$w); 
	$c[]=new cmp('idpersona','t','20',$p['idpersona'],$w.' '.$f.' '.$o,'N° Identificación','idpersona',null,'',true,false,'','col-1');
	$c[]=new cmp('tipodoc','t','3',$p['tipo_doc'],$w.' '.$f.' '.$o,'Tipo Identificación','tipodoc',null,'',true,false,'','col-1');
	$c[]=new cmp('nombre','t','50',$p['nombres'],$w.' '.$f.' '.$o,'nombres','nombre',null,'',true,false,'','col-3');
	$c[]=new cmp('sexo','t','50',$p['sexo'],$w.' '.$f.' '.$z.' '.$o,'sexo','sexo',null,'',false,false,'','col-1');
	$c[]=new cmp('fechanacimiento','d','10',$p['fecha_nacimiento'],$w.' '.$f.' '.$z.' '.$o,'fecha nacimiento','fechanacimiento',null,'',true,false,'','col-15');
    $c[]=new cmp('edad','n','3',$old,$w.' '.$f.' '.$o,'Edad (Abordaje)','edad',null,'',false,false,'','col-25');
	$c[]=new cmp('cursovida','s','3',$curso,$w.' '.$f.' '.$o,'Curso de Vida','cursovida',null,'',false,false,'','col-25');
	$c[]=new cmp('fecha','d','10',$d,$w.'  '.$f.' '.$o,'fecha de la Toma','fecha',null,'',true,true,'','col-15',"validDate(this,$days,0);");
	$c[]=new cmp('tipo','s','3',$d,$w.' '.$o,'Tipo','complemento',null,'',true,true,'','col-15');
	$c[]=new cmp('crit_epi','s','3',$d,$w.' '.$o,'Criterio Epidemiológico','crit_epi',null,true,true,true,'','col-35');
	
	$o='infcom';
	$c[]=new cmp($o,'e',null,'DATOS COMPLEMENTARIOS',$w);
	
	$gest = ($p['sexo']=='MUJER' && ($p['ano']>9 && $p['ano']<56 )) ? true : false ;
	$men5 = ($p['ano']<5) ? true : false ;
	$gesta = ($p['sexo']=='MUJER') ? true : false ;

	$c[]=new cmp('men_dnt','s','2',$d,$w.' '.$o,'Menor de 5 años con DNT Aguda','rta',null,null,$men5,$men5,'','col-15', "fieldsValue('men_dnt','dNt','1',true);");
	$c[]=new cmp('men_sinctrl','s','2',$d,$w.' dNt '.$o,'Sin Atencion Ruta Alteracion Nutricional','rta',null,null,$men5,$men5,'','col-15');

	
	$c[]=new cmp('gestante','s','2',$d,$w.' '.$o,'El usuario es gestante','rta',null,null,$gest,$gesta,'','col-2',"fieldsValue('gestante','eTp','1',true);");
	$c[]=new cmp('etapgest','s','3',$d,$w.' eTp '.$o,'Etapa Gestacional','etapgest',null,'',$gest,false,'','col-25');//true
	$c[]=new cmp('ges_sinctrl','s','3',$d,$w.' eTp '.$o,'Gestante Sin Control','rta',null,'',$gest,false,'','col-25');//true

	$c[]=new cmp('cronico','s','2',$d,$w.' '.$o,'El usuario es cronico','rta',null,null,true,true,'','col-2',"fieldsValue('cronico','cRo','1',true);");
	$c[]=new cmp('cro_hiper','s','2',$d,$w.' cRo '.$o,'Hipertension','rta',null,null,true,false,'','col-2');
	$c[]=new cmp('cro_diabe','s','2',$d,$w.' cRo '.$o,'Diabetes','rta',null,null,true,false,'','col-2');
	$c[]=new cmp('cro_epoc','s','2',$d,$w.' cRo '.$o,'Epoc','rta',null,null,true,false,'','col-2');
	$c[]=new cmp('cro_sinctrl','s','2',$d,$w.' cRo '.$o,'Cronico Sin Control','rta',null,null,true,false,'','col-2');
	$c[]=new cmp('esq_vacun','s','2',$d,$w.' '.$o,'Esquema de Vacunacion Completo','rta',null,null,true,true,'','col-2');
	
	$o='alert';
	$c[]=new cmp($o,'e',null,'ALERTAS',$w); 
	$c[]=new cmp('alert1','s',15,$d,$w.' '.$o,'Alerta N° 1','alert',null,null,true,true,'','col-1',"enabAlert(this,'cRoN');",['fselmul1'],false,'alertas.php');
	$c[]=new cmp('selmul1','m',3,$d,$w.' cRoN '.$o,'Descripcion Alerta N° 1','selmul1',null,'',false,false,'','col-4');
	$c[]=new cmp('alert2','s',15,$d,$w.' '.$o,'Alerta N° 2','alert',null,null,false,true,'','col-1',"enabAlert(this,'etv');",['fselmul2'],false,'alertas.php');
	$c[]=new cmp('selmul2','m',3,$d,$w.' etv '.$o,'Descripcion Alerta N° 2','selmul2',null,'',false,false,'','col-4');
	$c[]=new cmp('alert3','s',15,$d,$w.' '.$o,'Alerta N° 3','alert',null,null,false,true,'','col-1',"enabAlert(this,'nut');",['fselmul3'],false,'alertas.php');
	$c[]=new cmp('selmul3','m',3,$d,$w.' nut '.$o,'Descripcion Alerta N° 3','selmul3',null,'',false,false,'','col-4');
	$c[]=new cmp('alert4','s',15,$d,$w.' '.$o,'Alerta N° 4','alert',null,null,false,true,'','col-1',"enabAlert(this,'psi');",['fselmul4'],false,'alertas.php');
	$c[]=new cmp('selmul4','m',3,$d,$w.' psi '.$o,'Descripcion Alerta N° 4','selmul4',null,'',false,false,'','col-4');
	$c[]=new cmp('alert5','s',15,$d,$w.' '.$o,'Alerta N° 5','alert',null,null,false,true,'','col-1',"enabAlert(this,'inf');",['fselmul5'],false,'alertas.php');
	$c[]=new cmp('selmul5','m',3,$d,$w.' inf '.$o,'Descripcigon Alerta N° 5','selmul5',null,'',false,false,'','col-4');
	$c[]=new cmp('alert6','s',15,$d,$w.' '.$o,'Alerta N° 6','alert',null,null,false,true,'','col-1',"enabAlert(this,'muj');",['fselmul6'],false,'alertas.php');
	$c[]=new cmp('selmul6','m',3,$d,$w.' muj '.$o,'Descripcion Alerta N° 6','selmul6',null,'',false,false,'','col-4');
	
	$c[]=new cmp('agen_intra','s',15,$d,$w.' '.$o,'Agendamiento Intramural','rta',null,null,true,true,'','col-1',"fieldsValue('agen_intra','aIM','1',true);");
	$c[]=new cmp('servicio','t',15,$d,$w.' aIM '.$o,'Servicio Agendado','servicio',null,null,false,false,'','col-15');
	$c[]=new cmp('fecha_cita','d','10',$d,$w.' aIM '.$o,'Fecha de la Cita','fecha_cita',null,'',false,false,'','col-15',"validDate(this,0,60);");
	$c[]=new cmp('hora_cita','c','10',$d,$w.' aIM '.$o,'Hora de la Cita','hora_cita',null,'',false,false,'','col-15');
	$c[]=new cmp('lugar_cita','t',15,$d,$w.' aIM '.$o,'Lugar de la Cita','lugar_cita',null,null,false,false,'','col-15');
	
	$c[]=new cmp('deriva_pf','s',15,$d,$w.' '.$o,'Deriva a PCF','rta',null,null,true,true,'','col-1',"enabOthSi('deriva_pf','pCf');");
	$c[]=new cmp('evento_pf','s',15,$d,$w.' pCf '.$o,'Asigna a PCF','evento',null,null,false,false,'','col-5');
	// $c[]=new cmp('medico','s',15,$d,$w.' der '.$o,'Asignado','medico',null,null,false,false,'','col-5');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}

function get_sesigcole(){
	
}

function gra_sesigcole(){

	return $rta;
}

function opc_tipose($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}

function opc_tipo_vivienda($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=4 and estado='A' ORDER BY 1",$id);
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('U	TF-8','ISO-8859-1',$rta);
// var_dump($a);
	if ($a=='sesigcole' && $b=='acciones'){//a mnombre del modulo
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono asigna1' title='Crear Sesion' id='".$c['ACCIONES']."' Onclick=\"mostrar('sesigcole','pro',event,'','lib.php',7);\"></li>";
	}
	
 return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
?>
