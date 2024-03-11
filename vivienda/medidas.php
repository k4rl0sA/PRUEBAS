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



function focus_medidas(){
	return 'medidas';
}
   
   
function men_medidas(){
	$rta=cap_menus('medidas','pro');
	return $rta;
}
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = "";
	 $acc=rol($a);
	 if ($a=='medidas'  && isset($acc['crear']) && $acc['crear']=='SI'){
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	
	 return $rta;
	 }
   }

   function lis_medidas(){
	// var_dump($_POST['id']);
	$id=divide($_POST['id']);
	$sql="SELECT idmedidas ACCIONES,idmedidas AS Cod_Registro,`fecha`,FN_CATALOGODESC(34,tipo) Tipo,`nombre` Creó,`fecha_create` 'fecha Creó'
	FROM personas_datocomp P
	LEFT JOIN  usuarios U ON P.usu_creo=U.id_usuario ";
	$sql.="WHERE dc_tipo_doc='".$id[1]."' AND dc_documento='".$id[0]."";
	$sql.="' ORDER BY fecha_create";
	// echo $sql;
	$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"medidas-lis",2);
   }
 
   function cmp_medidas(){
	$rta="<div class='encabezado medid'>TABLA DE TOMAS DE MEDIDA</div>
	<div class='contenido' id='medidas-lis'>".lis_medidas()."</div></div>";
	// $t=['nombres'=>'','fechanacimiento'=>'','edad'=>'','peso'=>'','talla'=>'','imc'=>'','tas'=>'','tad'=>'','glucometria'=>'','perime_braq'=>'','perime_abdom'=>'','percentil'=>'','zscore'=>'','findrisc'=>'','oms'=>'','alert1'=>'','alert2'=>'','alert3'=>'','alert4'=>'','alert5'=>'','alert6'=>'','alert7'=>'','alert8'=>'','alert9'=>'','alert10'=>'','select1'=>'','selmul1'=>'[]','selmul2'=>'[]','selmul3'=>'[]','selmul4'=>'[]','selmul5'=>'[]','selmul6'=>'[]','selmul7'=>'[]','selmul8'=>'[]','selmul9'=>'[]','selmul10'=>'[]','fecha'=>'','tipo'=>''];
	$p=get_persona();
	// if ($d==""){$d=$t;}
	$id=divide($_POST['id']);
	// $doc = (is_array($p) && isset($p['dc_documento'])) ? $p['dc_documento'] : $id[0] ;
	// $tip = (is_array($p) && isset($p['dc_tipo_doc'])) ? $p['dc_tipo_doc'] : $id[1] ;
	$d='';
    $w="medidas";
	$o='infbas';
	$gest = ($p['sexo']=='MUJER' && ($p['ano']>9 && $p['ano']<56 )) ? true : false ;
	$ocu= ($p['ano']>5) ? true : false ;
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
	$c[]=new cmp('idp','h',15,$_POST['id'],$w.' '.$o,'id','id',null,'',false,false);
	$c[]=new cmp($o,'e',null,'INFORMACION DE MEDIDAS',$w); 
	$c[]=new cmp('idpersona','t','20',$p['idpersona'],$w.' '.$o,'N° Identificación','idpersona',null,'',true,false,'','col-2');
	$c[]=new cmp('tipodoc','t','3',$p['tipo_doc'],$w.' '.$o,'Tipo Identificación','tipodoc',null,'',true,false,'','col-3');
	$c[]=new cmp('nombre','t','50',$p['nombres'],$w.' '.$o,'nombres','nombre',null,'',true,false,'','col-4');
	$c[]=new cmp('sexo','t','50',$p['sexo'],$w.' '.$z.' '.$o,'sexo','sexo',null,'',false,false,'','col-1');
	$c[]=new cmp('fechanacimiento','d','10',$p['fecha_nacimiento'],$w.' '.$z.' '.$o,'fecha nacimiento','fechanacimiento',null,'',true,false,'','col-2');
    $c[]=new cmp('edad','n','3',' Años: '.$p['ano'].' Meses: '.$p['mes'].' Dias:'.$p['dia'],$w.' '.$o,'Edad (Abordaje)','edad',null,'',false,false,'','col-2');
	$c[]=new cmp('fecha','d','10',$d,$w.' '.$o,'fecha de la Toma','fecha',null,'',true,true,'','col-15','validDate(this,-3,0);');
	$c[]=new cmp('tipo','s','3',$d,$w.' '.$o,'Tipo','complemento',null,'',true,true,'','col-15');
	$c[]=new cmp('crit_epi','s','3',$d,$w.' '.$o,'Criterio Epidemiológico','crit_epi',null,true,true,true,'','col-3');

	$o='infcom';
	$c[]=new cmp($o,'e',null,'DATOS COMPLEMENTARIOS',$w);
	$c[]=new cmp('cursovida','s','3',$curso,$w.' '.$o,'Curso de Vida','cursovida',null,'',false,false,'','col-25');//true}
	$c[]=new cmp('gestante','s','2',$d,$w.' '.$o,'El usuario es gestante','rta',null,null,$gest,$gest,'','col-2',"valGluc('glucometria');enabOthSi('gestante','eTp');");
	$c[]=new cmp('etapgest','s','3',$d,$w.' eTp '.$o,'Etapa Gestacional','etapgest',null,'',$gest,$gest,'','col-25');//true
	$c[]=new cmp('cronico','s','2',$d,$w.' '.$o,'El usuario es cronico','rta',null,null,true,true,'','col-2',"valGluc('glucometria');");
	/* $c[]=new cmp('pobladifer','s','3',$d,$w.' '.$o,'Poblacion Direferencial y de Inclusión','pobladifer',null,'',true,true,'','col-25');//true
	$c[]=new cmp('incluofici','s','3',$d,$w.' '.$o,'Población Inclusion por Oficio','incluofici',null,'',true,true,'','col-25');//true */

	$o='alert';
	$c[]=new cmp($o,'e',null,'ALERTAS',$w); 
	$c[]=new cmp('alert1','o',15,$d,$w.' '.$o,'Condición crónica','alert1',null,null,true,true,'','col-1',"enabAlert(this,'cro');");
	$c[]=new cmp('selmul1[]','m',3,$d,$w.' cro '.$o,'Condición crónica','selmul1',null,'',false,false,'','col-4');
	$c[]=new cmp('alert2','o',15,$d,$w.' '.$o,'Enfermedad Transmisible y ETV','alert2',null,null,true,true,'','col-2',"enabAlert(this,'etv');");
	$c[]=new cmp('selmul2[]','m',3,$d,$w.' etv '.$o,'Enfermedad Transmisible y ETV','selmul2',null,'',false,false,'','col-3');
	$c[]=new cmp('alert3','o',15,$d,$w.' '.$o,'Nutricional','alert3',null,null,true,true,'','col-1',"enabAlert(this,'nut');");
	$c[]=new cmp('selmul3[]','m',3,$d,$w.' nut '.$o,'Nutricional','selmul3',null,'',false,false,'','col-4');
	$c[]=new cmp('alert4','o',15,$d,$w.' '.$o,'Psicosocial','alert4',null,null,true,true,'','col-1',"enabAlert(this,'psi');");
	$c[]=new cmp('selmul4[]','m',3,$d,$w.' psi '.$o,'Psicosociales','selmul4',null,'',false,false,'','col-4');
	$c[]=new cmp('alert5','o',15,$d,$w.' '.$o,'Infancia','alert5',null,null,true,true,'','col-1',"enabAlert(this,'inf');");
	$c[]=new cmp('selmul5[]','m',3,$d,$w.' inf '.$o,'Infancia','selmul5',null,'',false,false,'','col-4');
	$c[]=new cmp('alert6','o',15,$d,$w.' '.$o,'Mujeres','alert6',null,null,true,true,'','col-1',"enabAlert(this,'muj');");
	$c[]=new cmp('selmul6[]','m',3,$d,$w.' muj '.$o,'Mujeres','selmul6',null,'',false,false,'','col-4');
	$c[]=new cmp('alert7','o',15,$d,$w.' '.$o,'Discapacidad','alert7',null,null,true,true,'','col-1',"enabAlert(this,'dis');");
	$c[]=new cmp('selmul7[]','m',3,$d,$w.' dis '.$o,'Discapacidad - Limitaciones para la actividad','selmul7',null,'',false,false,'','col-4');
	$c[]=new cmp('alert8','o',15,$d,$w.' '.$o,'Comunidades Étnicas ','alert8',null,null,true,true,'','col-15',"enabAlert(this,'etn');");
	$c[]=new cmp('selmul8[]','m',3,$d,$w.' etn '.$o,'Étnicas','selmul8',null,'',false,false,'','col-35');
	$c[]=new cmp('alert9','o',15,$d,$w.' '.$o,'Salud Bucal','alert9',null,null,true,true,'','col-1',"enabAlert(this,'orl');");
	$c[]=new cmp('selmul9[]','m',3,$d,$w.' orl '.$o,'Salud Bucal','selmul9',null,'',false,false,'','col-4');
	$c[]=new cmp('codoral','n',1,$d,$w.' '.$o,'Cod. Salud Bucal','codoral','rgx1codora',null,false,true,'','col-1');
	$c[]=new cmp('alert10','o',15,$d,$w.' '.$o,'Derivaciones','alert10',null,null,true,true,'','col-1',"enabAlert(this,'der');");
	$c[]=new cmp('selmul10[]','m',3,$d,$w.' der '.$o,'Derivaciones','selmul10',null,'',false,false,'','col-3');
	$c[]=new cmp('deriva_eac','s',15,$d,$w.' '.$o,'Deriva a EAC','rta',null,null,true,true,'','col-1',"enabOthSi('deriva_eac','eAc');");
	$c[]=new cmp('necesidad_eac','s',15,$d,$w.' eAc '.$o,'Derivaciones EAC','necesidad',null,null,false,false,'','col-45');
	$c[]=new cmp('asignado_eac','s',15,$d,$w.' eAc '.$o,'Asigna a EAC','medico',null,null,false,false,'','col-45');
	$c[]=new cmp('deriva_pf','s',15,$d,$w.' '.$o,'Deriva a PCF','rta',null,null,true,true,'','col-1',"enabOthSi('deriva_pf','pCf');");
	$c[]=new cmp('evento_pf','s',15,$d,$w.' pCf '.$o,'Asigna a PCF','evento',null,null,false,false,'','col-5');
	// $c[]=new cmp('medico','s',15,$d,$w.' der '.$o,'Asignado','medico',null,null,false,false,'','col-5');

	$o='med';
	$c[]=new cmp($o,'e',null,'TOMA DE SIGNOS Y MEDIDAS ANTROPOMÉTRICAS',$w);
	$c[]=new cmp('peso','sd',6, $d,$w.' '.$z.' '.$o,'Peso (Kg) Mín=0.50 - Máx=150.00','fpe','rgxpeso','###.##',true,true,'','col-2',"valPeso('peso');Zsco('zscore');");
	$c[]=new cmp('talla','sd',5, $d,$w.' '.$z.' '.$o,'Talla (Cm) Mín=40 - Máx=210','fta','rgxtalla','###.#',true,true,'','col-2',"calImc('peso',this,'imc');Zsco('zscore');valTalla('talla');valGluc('glucometria');");
	$c[]=new cmp('imc','t',6, $d,$w.' '.$o,'IMC','imc','','',false,false,'','col-1');
	if($p['ano']<5 && ($p['mes']>=6)){
		$c[]=new cmp('perime_braq','sd',4, $d,$w.' '.$o,'Perimetro Braquial (Cm)',0,null,'#,#',true,true,'','col-15');
	}
	if($p['ano']<5){
		$c[]=new cmp('zscore','t',15,'',$w.' '.$o,'Z-score','des',null,null,false,false,'','col-35');
	}
	/* if( $p['ano']==0 && $p['mes']<=1 ){
		$c[]=new cmp('percentil','n',4, $d,$w,'Percentil Fen','pef',null,null,false,true,'','col-2');
	} 
	*/
		
	if($p['ano']>=18){
		$c[]=new cmp('tas','n',3, $d,$w.' '.$o,'Tensión Sistolica Mín=60 - Máx=310','tas','rgxsisto','###',true,true,'','col-2',"valSist('tas');");
		$c[]=new cmp('tad','n',3, $d,$w.' '.$o,'Tensión Diastolica Mín=40 - Máx=185','tad','rgxdiast','##',true,true,'','col-2',"ValTensions('tas',this);valDist('tad');");
		$c[]=new cmp('glucometria','n',4, $d,$w.' gL '.$o,'Glucometría Mín=5 - Máx=600','glu','','###',false,true,'','col-2',"valGluco('glucometria');");//findrisc >12
		/* $c[]=new cmp('tas','n',3, $d,$w,'Tensión Sistolica Mín=90 - Máx=185','tas','rgxsisto','###',true,true,'','col-2');
		$c[]=new cmp('tad','n',3, $d,$w,'Tensión Diastolica Mín=70 - Máx=130','tad','rgxdiast','##',true,true,'','col-2');
		$c[]=new cmp('glucometria','n',4, $d,$w,'Glucometría Mín=70 - Máx=190','glu','','###',true,true,'','col-2'); */
		// $c[]=new cmp('perime_abdom','n',4, $d,$w,'Perimetro Abdominal (Cm) Mín=50 - Máx=150','abd','rgxperabd',null,true,true,'','col-2');
		// $c[]=new cmp('findrisc','s',15,$d,$w,'Findrisc','fin',null,null,true,true,'','col-2');
	}
	/* if($p['ano']>=40){
		$c[]=new cmp('oms','s',15,$d,$w,'OMS','oms',null,null,true,true,'','col-2');
		$c[]=new cmp('epoc','s',15,$d,$w,'EPOC','epoc',null,null,true,true,'','col-2');
	} */
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
   }

function get_zscore(){
	$id=divide($_POST['val']);
	 $fechaNacimiento = new DateTime($id[1]);
	 $fechaActual = new DateTime();
	 $diferencia = $fechaNacimiento->diff($fechaActual);
	 $edadEnDias = $diferencia->days;
	$ind = ($edadEnDias<=730) ? 'PL' : 'PT' ;
	$sex=$id[2];

$sql="SELECT (POWER(($id[0] / (SELECT M FROM tabla_zscore WHERE indicador = '$ind' AND sexo = '$sex[0]' AND edad_dias = $id[3])),
	(SELECT L FROM tabla_zscore WHERE indicador = '$ind' AND sexo = '$sex[0]' AND edad_dias = $id[3])) - 1) / 
	((SELECT L FROM tabla_zscore WHERE indicador = '$ind' AND sexo = '$sex[0]' AND edad_dias = $id[3]) *
 (SELECT S FROM tabla_zscore WHERE indicador = '$ind' AND sexo = '$sex[0]' AND edad_dias = $id[3])) as rta ";
//   echo $sql;
 $info=datos_mysql($sql);
 	if (!$info['responseResult']) {
		return '';
	}else{
		$z=number_format((float)$info['responseResult'][0]['rta'], 6, '.', '');
		switch ($z) {
			case ($z <=-3):
				$des='DESNUTRICIÓN AGUDA SEVERA';
				break;
			case ($z >-3 && $z <=-2):
				$des='DESNUTRICIÓN AGUDA MODERADA';
				break;
			case ($z >-2 && $z <=-1):
				$des='RIESGO DESNUTRICIÓN AGUDA';
				break;
			case ($z>-1 && $z <=1):
					$des='PESO ADECUADO PARA LA TALLA';
				break;
			case ($z >1 && $z <=2):
					$des='RIESGO DE SOBREPESO';
				break;
			case ($z >2 && $z <=3):
					$des='SOBREPESO';
				break;
				case ($z >3):
					$des='OBESIDAD';
				break;
			default:
				$des='Error en el rango, por favor valide';
				break;
		}

		return json_encode($z." = ".$des);
	}
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
  		DATEDIFF(CURDATE(), DATE_ADD(fecha_nacimiento, INTERVAL TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) YEAR ))% 30 AS dia
		from personas P left join hog_viv V ON idviv=vivipersona 
		WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."')";
		// echo $sql;
		$info=datos_mysql($sql);
				return $info['responseResult'][0];
		}
	} 


function gra_medidas(){
	// print_r($_POST);
	if (($smu1 = $_POST['fselmul1'] ?? null) && is_array($smu1)){$sm1 = implode(",",str_replace("'", "", $smu1));}
	if (($smu2 = $_POST['fselmul2'] ?? null) && is_array($smu2)) {$sm2 = implode(",",str_replace("'", "", $smu2));}
	if (($smu3 = $_POST['fselmul3'] ?? null) && is_array($smu3)) {$sm3 = implode(",",str_replace("'", "", $smu3));}
	if (($smu4 = $_POST['fselmul4'] ?? null) && is_array($smu4)) {$sm4 = implode(",",str_replace("'", "", $smu4));}
	if (($smu5 = $_POST['fselmul5'] ?? null) && is_array($smu5)) {$sm5 = implode(",",str_replace("'", "", $smu5));}
	if (($smu6 = $_POST['fselmul6'] ?? null) && is_array($smu6)) {$sm6 = implode(",",str_replace("'", "", $smu6));}
	if (($smu7 = $_POST['fselmul7'] ?? null) && is_array($smu7)) {$sm7 = implode(",",str_replace("'", "", $smu7));}
	if (($smu8 = $_POST['fselmul8'] ?? null) && is_array($smu8)) {$sm8 = implode(",",str_replace("'", "", $smu8));}
	if (($smu9 = $_POST['fselmul9'] ?? null) && is_array($smu9)) {$sm9 = implode(",",str_replace("'", "", $smu9));}
	if (($smu10 = $_POST['fselmul10'] ?? null) && is_array($smu10)) {$sm10 = implode(",",str_replace("'", "", $smu10));}
	$tas = $_POST['tas'] ?? null;
	$tad = $_POST['tad'] ?? null;
	$glu = $_POST['glucometria'] ?? null;
	$pbr = $_POST['perime_braq'] ?? null;
	$per = $_POST['percentil'] ?? null;
	$des = $_POST['zscore'] ?? null;
	$codoral= $_POST['codoral']?? null;

	$id=divide($_POST['idp']);

		/*
		$sql="UPDATE hog_viv SET asignado=trim(upper('{$_POST['medico']}')) 
		WHERE idviv =TRIM(UPPER('{$id[2]}'))";
		// echo $sql;
		$rta=dato_mysql($sql);
		if (strpos($rta, 'Correctamente')) {
			// $resp="\ny Se ha asignado el caso al Medico";
			$resp=$rta;
		}else{
			$resp="\nError: No se pudo asignar,consulte al admin del sistema";
		}
		$rta1=$resp;
		*/
	/*
	$sql="UPDATE `personas_datocomp` SET 
		fecha=trim(upper('{$_POST['fecha']}')),tipo=trim(upper('{$_POST['tipo']}')),alert1=trim(upper('{$_POST['alert1']}')),selmul1=trim(upper('{$_POST['selmul1']}')),alert2=trim(upper('{$_POST['alert2']}')),selmul2=trim(upper('{$_POST['selmul2']}')),alert3=trim(upper('{$_POST['alert3']}')),selmul3=trim(upper('{$_POST['selmul3']}')),alert4=trim(upper('{$_POST['alert4']}')),selmul4=trim(upper('{$_POST['selmul4']}')),alert5=trim(upper('{$_POST['alert5']}')),selmul5=trim(upper('{$_POST['selmul5']}')),alert6=trim(upper('{$_POST['alert6']}')),selmul6=trim(upper('{$_POST['selmul6']}')),alert7=trim(upper('{$_POST['alert7']}')),selmul7=trim(upper('{$_POST['selmul7']}')),alert8=trim(upper('{$_POST['alert8']}')),selmul8=trim(upper('{$_POST['selmul8']}')),alert9=trim(upper('{$_POST['alert9']}')),selmul9=trim(upper('{$_POST['selmul9']}')),alert10=trim(upper('{$_POST['alert10']}')),selmul10=trim(upper('{$_POST['selmul10']}')),peso=trim(upper('{$_POST['peso']}')),talla=trim(upper('{$_POST['talla']}')),imc=trim(upper('{$_POST['imc']}')),tas=trim(upper('{$_POST['tas']}')),tad=trim(upper('{$_POST['tad']}')),glucometria=trim(upper('{$_POST['glucometria']}')),perime_braq=trim(upper('{$_POST['perime_braq']}')),perime_abdom=trim(upper('{$_POST['perime_abdom']}')),percentil=trim(upper('{$_POST['percentil']}')),zscore=trim(upper('{$_POST['zscore']}')),findrisc=trim(upper('{$_POST['findrisc']}')),oms=trim(upper('{$_POST['oms']}')),epoc=trim(upper('{$_POST['epoc']}')),
		`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
		WHERE dc_documento =TRIM(UPPER('{$id[0]}')) AND dc_tipo_doc=TRIM(UPPER('{$id[1]}'))";
	}else if($_POST['tipo']==1){
 	*/

 	/* $sql1="SELECT TIMESTAMPDIFF(YEAR,fecha_nacimiento, fecha ) AS ano,TIMESTAMPDIFF(MONTH,fecha_nacimiento ,fecha ) % 12 AS mes from personas P left join hog_viv V ON idviv=vivipersona WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."')";
	$data=datos_mysql($sql1);
	$edad=$data['responseResult'][0]; */

	/* $sql="INSERT INTO personas_datocomp VALUES (NULL,
		trim(upper('{$_POST['tipodoc']}')),trim(upper('{$_POST['idpersona']}')),trim(upper('{$_POST['fecha']}')),
		trim(upper('{$_POST['tipo']}')),
		trim(upper('{$_POST['alert1']}')),trim(upper('{$sm1}')),
		trim(upper('{$_POST['alert2']}')),trim(upper('{$sm2}')),
		trim(upper('{$_POST['alert3']}')),trim(upper('{$sm3}')),
		trim(upper('{$_POST['alert4']}')),trim(upper('{$sm4}')),
		trim(upper('{$_POST['alert5']}')),trim(upper('{$sm5}')),
		trim(upper('{$_POST['alert6']}')),trim(upper('{$sm6}')),
		trim(upper('{$_POST['alert7']}')),trim(upper('{$sm7}')),
		trim(upper('{$_POST['alert8']}')),trim(upper('{$sm8}')),
		trim(upper('{$_POST['alert9']}')),trim(upper('{$sm9}')),
		trim(upper('{$_POST['alert10']}')),trim(upper('{$sm10}')),
		trim(upper('{$_POST['peso']}')),trim(upper('{$_POST['talla']}')),FN_IMC({$_POST['peso']},{$_POST['talla']}),
		trim(upper('{$tas}')),trim(upper('{$tad}')),trim(upper('{$glu}')),"; */

		$sql="INSERT INTO personas_datocomp VALUES (NULL,
		trim(upper('{$_POST['tipodoc']}')),trim(upper('{$_POST['idpersona']}')),trim(upper('{$_POST['fecha']}')),
		trim(upper('{$_POST['tipo']}')),trim(upper('{$_POST['crit_epi']}')),trim(upper('{$_POST['cursovida']}')),
		trim(upper('{$_POST['gestante']}')),trim(upper('{$_POST['etapgest']}')),trim(upper('{$_POST['cronico']}')),	
		trim(upper('{$_POST['alert1']}')),trim(upper('{$sm1}')),trim(upper('{$_POST['alert2']}')),trim(upper('{$sm2}')),
		trim(upper('{$_POST['alert3']}')),trim(upper('{$sm3}')),trim(upper('{$_POST['alert4']}')),trim(upper('{$sm4}')),
		trim(upper('{$_POST['alert5']}')),trim(upper('{$sm5}')),trim(upper('{$_POST['alert6']}')),trim(upper('{$sm6}')),
		trim(upper('{$_POST['alert7']}')),trim(upper('{$sm7}')),trim(upper('{$_POST['alert8']}')),trim(upper('{$sm8}')),
		trim(upper('{$_POST['alert9']}')),trim(upper('{$sm9}')),trim(upper('{$codoral}')),
		trim(upper('{$_POST['alert10']}')),trim(upper('{$sm10}')),
		trim(upper('{$_POST['deriva_eac']}')),trim(upper('{$_POST['necesidad_eac']}')),trim(upper('{$_POST['asignado_eac']}')),
		trim(upper('{$_POST['deriva_pf']}')),trim(upper('{$_POST['evento_pf']}')),
		trim(upper('{$_POST['peso']}')),trim(upper('{$_POST['talla']}')),trim(upper('{$_POST['imc']}')),
		trim(upper('{$tas}')),trim(upper('{$tad}')),trim(upper('{$glu}')),trim(upper('{$pbr}')),trim(upper('{$per}')),
		trim(upper('{$des}')),";

		/* trim(upper('{$_POST['percentil']}')),trim(upper('{$_POST['zscore']}')),trim(upper('{$_POST['findrisc']}')),
		trim(upper('{$_POST['oms']}')),trim(upper('{$_POST['epoc']}')),"; */
		

	/* if ($edad['ano'] == 0 && $edad['mes'] <= 1) {
		$sql.="trim(upper('0')),trim(upper('0')),trim(upper('{$per}')),trim(upper('{$des}')),trim(upper('0')),
		trim(upper('0')),trim(upper('0')),";
	 } elseif ($edad['ano'] < 5 && $edad['mes'] >= 6) {
		$sql.="trim(upper('{$pbr}')),trim(upper('0')),trim(upper('0')),trim(upper('{$des}')),trim(upper('0')),
		trim(upper('0')),trim(upper('0')),";
	 } elseif ($edad['ano'] >= 18) {
		$sql.="trim(upper('0')),trim(upper('{$pab}')),trim(upper('0')),trim(upper('0')),
		trim(upper('{$fin}')),trim(upper('0')),trim(upper('0')),";
	 } elseif ($edad['ano'] >= 40) {
		$sql.="trim(upper('0')),
		trim(upper('{$pab}')),trim(upper('0')),trim(upper('0')),trim(upper('{$fin}')),
		trim(upper('{$oms}')),trim(upper('{$epo}')),";
	 } */
	$sql.="DATE_SUB(NOW(), INTERVAL 5 HOUR),TRIM(UPPER('{$_SESSION['us_sds']}')),null,null,'A')";
		// }
		// echo $sql;
		$rta=dato_mysql($sql);
		//return $rta.' '.$rta1;
		return $rta;
}

function opc_evento($id=''){
	$d=get_persona();
	if($d['sexo']=='M'){
	  if($d['ano']<6){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5,1,2,3) and estado='A' ORDER BY 2",$id);
	  }elseif($d['ano']>5 && $d['ano']<10){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5,2,3) and estado='A' ORDER BY 2",$id); 
	  }elseif($d['ano']>9 && $d['ano']<18){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5,2,3,4) and estado='A' ORDER BY 2",$id); 
	  }elseif($d['ano']>17 && $d['ano']<55){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5,2,4) and estado='A' ORDER BY 2",$id); 
	  }elseif($d['ano']>54){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5) and estado='A' ORDER BY 2",$id); 
	  }
	}else{
	  if($d['ano']<6){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5,1,2,3) and estado='A' ORDER BY 2",$id);
	  }elseif($d['ano']>5 && $d['ano']<18){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5,2,3) and estado='A' ORDER BY 2",$id); 
	  }elseif($d['ano']>17 && $d['ano']<55){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5) and estado='A' ORDER BY 2",$id); 
	  }elseif($d['ano']>54){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=87 AND valor IN(5) and estado='A' ORDER BY 2",$id); 
	  }
	}
}

function get_medidas(){
	// print_r($_POST);
	if($_POST['id']==''){
		return '';
	}else{
		$id=divide($_POST['id']);
		// print_r($id);
		$sql1="SELECT TIMESTAMPDIFF(YEAR,fecha_nacimiento, fecha ) AS ano,TIMESTAMPDIFF(MONTH,fecha_nacimiento ,fecha ) % 12 AS mes 
		from personas P left join personas_datocomp D ON P.idpersona=D.dc_documento AND P.tipo_doc=D.dc_tipo_doc WHERE idmedidas='{$id[0]}'";
		$data=datos_mysql($sql1);
		$edad=$data['responseResult'][0];


		$sql="SELECT concat(dc_documento,'_',dc_tipo_doc,'_',tipo) as id,dc_documento,dc_tipo_doc,
		concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,sexo,fecha_nacimiento,
		FN_EDAD(fecha_nacimiento,V.fecha),
		D.fecha,`tipo`,D.crit_epi,cursovida,gestante,etapgest,cronico,`alert1`,`selmul1`,`alert2`,`selmul2`,`alert3`,`selmul3`, `alert4`, `selmul4`, `alert5`, `selmul5`, `alert6`, `selmul6`, `alert7`, `selmul7`, `alert8`, `selmul8`, `alert9`, `selmul9`, codoral,`alert10`, `selmul10`,
		D.deriva_eac,D.necesidad_eac,D.asignado_eac,deriva_pf,evento_pf,`peso`, `talla`,imc";
		if ($edad['ano'] > 17 ) {
		    $sql.=",`tas`, `tad`, `glucometria`";
		}
		if ($edad['ano'] < 5 && $edad['mes'] >= 6) {
			$sql.=",`perime_braq`" ;
		}
		if ($edad['ano'] < 5) {
			$sql.=",zscore" ;
		} 
		$sql.=" FROM personas_datocomp D
				LEFT JOIN personas P ON dc_documento=idpersona AND dc_tipo_doc=tipo_doc
				LEFT JOIN hog_viv V ON P.vivipersona=V.idviv 
				WHERE idmedidas ='{$id[0]}'" ;
	 	$info = datos_mysql($sql);
		 //echo $sql; 
		// print_r($info['responseResult'][0]);
		if (!$info['responseResult']) {
			return '';
		}else{
			return json_encode($info['responseResult'][0]);
		}
		
	}
}

function opc_necesidad($id=''){
	return opc_sql("SELECT `idcatadeta`, descripcion FROM `catadeta` WHERE idcatalogo=225 AND estado='A' ORDER BY 1", $id);
}
function opc_rta($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
}
function opc_crit_epi($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=166 and estado='A' ORDER BY LPAD(idcatadeta, 2, '0')",$id);
}
function opc_etapgest($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=177 and estado='A' ORDER BY 1",$id);
}
function opc_cursovida($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=176 and estado='A' ORDER BY 1",$id);
}
function opc_medico($id=''){
	return opc_sql("SELECT
	`id_usuario`,
	CONCAT(nombre, ' - ', LEFT(perfil, 3))
FROM
	`usuarios` U
	RIGHT JOIN adscrip A ON U.id_usuario= A.doc_asignado
WHERE
	`perfil` IN('MEDATE', 'ENFATE')
	AND U.subred = (SELECT subred from usuarios where id_usuario={$_SESSION['us_sds']})
	AND	U.id_usuario IN (SELECT doc_asignado FROM adscrip where territorio in (select territorio from adscrip a where doc_asignado={$_SESSION['us_sds']})) 
	AND U.estado = 'A'
ORDER BY
	perfil,2",$id);
}
function opc_pcf($id=''){
	return opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE `perfil` IN('APYFAM')  AND subred=FN_SUBRED({$_SESSION['us_sds']}) AND estado ='A' ORDER BY 2",$id);
}
function  opc_des($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=0 and estado='A' ORDER BY 1",$id);
}
function  opc_fin($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=39 and estado='A' ORDER BY 1",$id);
}
function opc_oms($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=40 and estado='A' ORDER BY 1",$id);
}
function opc_epoc($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=117 and estado='A' ORDER BY 1",$id);
}
function opc_complemento($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=34 and estado='A' ORDER BY 1",$id);
}
function opc_select1($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=0 and estado='A' ORDER BY 1",$id);
}
function opc_selmul1($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=144 and estado='A' ORDER BY 1",$id);
}
function opc_selmul2($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=145 and estado='A' ORDER BY 1",$id);
}
function opc_selmul3($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=146 and estado='A' ORDER BY 1",$id);
}
function opc_selmul4($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=147 and estado='A' ORDER BY 1",$id);
}
function opc_selmul5($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=148 and estado='A' ORDER BY 1",$id);
}
function opc_selmul6($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=149 and estado='A' ORDER BY 1",$id);
}
function opc_selmul7($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=150 and estado='A' ORDER BY 1",$id);
}
function opc_selmul8($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=151 and estado='A' ORDER BY 1",$id);
}
function opc_selmul9($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=152 and estado='A' ORDER BY 1",$id);
}
function opc_selmul10($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=153 and estado='A' ORDER BY 1",$id);
}


function formato_dato($a,$b,$c,$d){
    $b=strtolower($b);
	$rta=$c[$d];
	if ($a=='medidas-lis' && $b=='acciones'){
		$rta="<nav class='menu right'>";		
		$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'medidas',event,this,['fecha','tipo_activi'],'../vivienda/medidas.php');\"></li>";  //   act_lista(f,this);
	}
return $rta;
}

function bgcolor($a,$c,$f='c'){
	$rta="";
	return $rta;
}
	   
