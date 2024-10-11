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

function cmp_signos(){
	$rta="<div class='encabezado medid'>TABLA DE TOMAS DE MEDIDA</div>
	<div class='contenido' id='signos-lis'>".lis_signos()."</div></div>";
	// $t=['nombres'=>'','fechanacimiento'=>'','edad'=>'','peso'=>'','talla'=>'','imc'=>'','tas'=>'','tad'=>'','glucometria'=>'','perime_braq'=>'','perime_abdom'=>'','percentil'=>'','zscore'=>'','findrisc'=>'','oms'=>'','alert1'=>'','alert2'=>'','alert3'=>'','alert4'=>'','alert5'=>'','alert6'=>'','alert7'=>'','alert8'=>'','alert9'=>'','alert10'=>'','select1'=>'','selmul1'=>'[]','selmul2'=>'[]','selmul3'=>'[]','selmul4'=>'[]','selmul5'=>'[]','selmul6'=>'[]','selmul7'=>'[]','selmul8'=>'[]','selmul9'=>'[]','selmul10'=>'[]','fecha'=>'','tipo'=>''];
	$p=get_persona();
	// if ($d==""){$d=$t;}
	$id=divide($_POST['id']);
	// $doc = (is_array($p) && isset($p['dc_documento'])) ? $p['dc_documento'] : $id[0] ;
	// $tip = (is_array($p) && isset($p['dc_tipo_doc'])) ? $p['dc_tipo_doc'] : $id[1] ;
	$d='';
    $w="signos";
	$o='infbas';
	$gest = ($p['sexo']=='MUJER' && ($p['ano']>9 && $p['ano']<56 )) ? true : false ;
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
	$days=fechas_app('vivienda');
	$c[]=new cmp('idp','h',15,$_POST['id'],$w.' '.$o,'id','id',null,'',false,false);
	$c[]=new cmp($o,'e',null,'INFORMACION DE signos',$w); 
	$c[]=new cmp('idpersona','t','20',$p['idpersona'],$w.' '.$o,'N° Identificación','idpersona',null,'',true,false,'','col-2');
	$c[]=new cmp('sexo','t','50',$p['sexo'],$w.' '.$z.' '.$o,'sexo','sexo',null,'',false,false,'','col-1');
	$c[]=new cmp('fechanacimiento','d','10',$p['fecha_nacimiento'],$w.' '.$z.' '.$o,'fecha nacimiento','fechanacimiento',null,'',true,false,'','col-2');
    $c[]=new cmp('edad','n','3',' Años: '.$p['ano'].' Meses: '.$p['mes'].' Dias:'.$p['dia'],$w.' '.$o,'Edad (Abordaje)','edad',null,'',false,false,'','col-2');
	
	
	$o='med';
	$c[]=new cmp($o,'e',null,'TOMA DE SIGNOS Y signos ANTROPOMÉTRICAS',$w);
	$c[]=new cmp('peso','sd',6, $d,$w.' '.$z.' '.$o,'Peso (Kg) Mín=0.50 - Máx=150.00','fpe','rgxpeso','###.##',true,true,'','col-2',"valPeso('peso');Zsco('zscore');calImc('peso','talla','imc');");
	$c[]=new cmp('talla','sd',5, $d,$w.' '.$z.' '.$o,'Talla (Cm) Mín=40 - Máx=210','fta','rgxtalla','###.#',true,true,'','col-2',"calImc('peso','talla','imc');Zsco('zscore');valTalla('talla');valGluc('glucometria');");
	$c[]=new cmp('imc','t',6, $d,$w.' '.$o,'IMC','imc','','',false,false,'','col-1');
		
	if($p['ano']>=18){
		$c[]=new cmp('tas','n',3, $d,$w.' '.$o,'Tensión Sistolica Mín=60 - Máx=310','tas','rgxsisto','###',true,true,'','col-2',"valSist('tas');");
		$c[]=new cmp('tad','n',3, $d,$w.' '.$o,'Tensión Diastolica Mín=40 - Máx=185','tad','rgxdiast','##',true,true,'','col-2',"ValTensions('tas',this);valDist('tad');");
		$c[]=new cmp('frecard','n',3, $d,$w.' '.$o,'Frecuencia Cardiaca Mín=60 - Máx=120','frecard',null,'##',true,true,'','col-2');
	    $c[]=new cmp('satoxi','n',3, $d,$w.' '.$o,'saturación de Oxigeno Mín=60 - Máx=100','satoxi',null,'##',true,true,'','col-2'); 
        $c[]=new cmp('peri_abdomi','n',4,$x,$w.' AbD '.$o,'Perímetro Abdominal (Cm) Mín=50 - Máx=150','peri_abdomi','rgxperabd','###',$adul,$adul,'','col-3');
    }

    if($meses>= 6 && $meses < 60){
		$c[]=new cmp('perime_braq','sd',4, $d,$w.' '.$o,'Perimetro Braquial (Cm)',0,null,'#,#',true,true,'','col-15');
	}
    if($p['ano']<5){
		$c[]=new cmp('zscore','t',15,'',$w.' '.$o,'Z-score','des',null,null,false,false,'','col-35');
	}
    $c[]=new cmp('glucometria','n',4, $d,$w.' gL '.$o,'Glucometría Mín=5 - Máx=600','glu','','###',false,true,'','col-2',"valGluco('glucometria');");
	

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
   }

function focus_signos(){
	return 'signos';
}
   
   
function men_signos(){
	$rta=cap_menus('signos','pro');
	return $rta;
}
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = "";
	 $acc=rol($a);
	 if ($a=='signos'  && isset($acc['crear']) && $acc['crear']=='SI'){
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	
	 return $rta;
	 }
   }






function bgcolor($a,$c,$f='c'){
	$rta="";
	return $rta;
}
	   
