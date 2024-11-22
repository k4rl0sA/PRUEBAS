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

////////////////////////////////////////////////////atencion//////////////////////////////////////////7777
function cap_menus($a,$b='cap',$con='con') {
  $rta = "";
  $acc=rol($a);
	if($a=='atencion' && isset($acc['crear']) && $acc['crear']=='SI'){
		$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
		$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
	}
  return $rta;
}
     

function cmp_atencion(){
	$rta="";
	$rta .="<div class='encabezado atencion'>Consultas realizadas al paciente</div>
	<div class='contenido' id='atencion-lis' >".lis_atencion()."</div></div>";
	$hoy=date('Y-m-d');
	// $x=['ida'=>'','atencion_tipodoc'=>'','atencion_idpersona'=>'','atencion_fechaatencion'=>'','atencion_codigocups'=>'','atencion_finalidadconsulta'=>'','atencion_peso'=>'','atencion_talla'=>'','atencion_sistolica'=>'','atencion_diastolica'=>'','atencion_abdominal'=>'','atencion_brazo'=>'','atencion_diagnosticoprincipal'=>'','atencion_diagnosticorelacion1'=>'','atencion_diagnosticorelacion2'=>'','atencion_diagnosticorelacion3'=>'','atencion_fertil'=>'','atencion_preconcepcional'=>'','atencion_metodo'=>'','atencion_anticonceptivo'=>'','atencion_planificacion'=>'','atencion_mestruacion'=>'','atencion_gestante'=>'','atencion_gestaciones'=>'','atencion_partos'=>'','atencion_abortos'=>'','atencion_cesarias'=>'','atencion_vivos'=>'','atencion_muertos'=>'','atencion_vacunaciongestante'=>'','atencion_edadgestacion'=>'','atencion_ultimagestacion'=>'','atencion_probableparto'=>'','atencion_prenatal'=>'','atencion_fechaparto'=>'','atencion_rpsicosocial'=>'','atencion_robstetrico'=>'','atencion_rtromboembo'=>'','atencion_rdepresion'=>'','atencion_sifilisgestacional'=>'','atencion_sifiliscongenita'=>'','atencion_morbilidad'=>'','atencion_hepatitisb'=>'','atencion_vih'=>'','atencion_cronico'=>'','atencion_asistenciacronica'=>'','atencion_tratamiento'=>'','atencion_vacunascronico'=>'','atencion_menos5anios'=>'','atencion_esquemavacuna'=>'','atencion_signoalarma'=>'','atencion_cualalarma'=>'','atencion_dxnutricional'=>'','atencion_eventointeres'=>'','atencion_evento'=>'','atencion_cualevento'=>'','atencion_sirc'=>'','atencion_rutasirc'=>'','atencion_remision'=>'','atencion_cualremision'=>'','atencion_ordenpsicologia'=>'','atencion_ordenvacunacion'=>'','atencion_vacunacion'=>'','atencion_ordenlaboratorio'=>'','atencion_laboratorios'=>'','atencion_ordenimagenes'=>'','atencion_imagenes'=>'','atencion_ordenmedicamentos'=>'','atencion_medicamentos'=>'','atencion_rutacontinuidad'=>'','atencion_continuidad'=>'','atencion_relevo'=>''];
	$t=['idpersona'=>'','tipo_doc'=>'','nombres'=>'','fecha_atencion'=>'','tipo_consulta'=>'','cod_cups'=>'','fecha_nacimiento'=>'','sexo'=>'','genero'=>'','nacionalidad'=>''];
	$d=get_personas();
	$x="";
	if ($d==""){$d=$t;}
	$u=($d['idpersona']=='')?true:false;
	$w='atencion';		
	$o='datos';

	$fecha_actual = new DateTime();
	$fecha_nacimiento = new DateTime($d['fecha_nacimiento']);
	$edad = $fecha_nacimiento->diff($fecha_actual)->y;
	$adul = ($edad>=18) ? true : false;
	$adult = ($edad>=18) ? 'true' : 'false';
	$meno = ($edad<5) ? true : false;
	$gest = (($edad>=10 && $edad <= 54) && $d['sexo'] == 'M') ? true : false;
	
	$c[]=new cmp($o,'e',null,'Datos atención medica usuario',$w);
	$c[]=new cmp('ida','h',15,$_POST['id'],$w.' '.$o,'ida','ida',null,'####',false,false,'col-1');
	$c[]=new cmp('atencion_tipodoc','t','20',$d['tipo_doc'],$w.' '.$o,'Tipo','atencion_tipodoc',null,'',false,false,'','col-1');
	$c[]=new cmp('atencion_idpersona','t','20',$d['idpersona'],$w.' '.$o,'N° Identificación','atencion_idpersona',null,'',false,false,'','col-2');
	$c[]=new cmp('nombre1','t','20',$d['nombres'],$w.' '.$o,'Nombres','nombre1',null,'',false,false,'','col-3');
	$c[]=new cmp('fecha_nacimiento','t','20',$d['fecha_nacimiento'],$w.' '.$o,'fecha nacimiento','fecha_nacimiento',null,'',false,false,'','col-1','validDate');
	$c[]=new cmp('sexo','s','20',$d['sexo'],$w.' '.$o,'sexo','sexo',null,'',false,false,'','col-1');
	$c[]=new cmp('genero','s','20',$d['genero'],$w.' '.$o,'genero','genero',null,'',false,false,'','col-1');
	$c[]=new cmp('nacionalidad','s','20',$d['nacionalidad'],$w.' '.$o,'Nacionalidad','nacionalidad',null,'',false,false,'','col-1');

	$o='consulta';
	$c[]=new cmp($o,'e',null,'Datos de la atencion medica	',$w);
	$c[]=new cmp('idf','h',15,'',$w.' '.$o,'idf','idf',null,'####',false,false,'','col-1');
	$c[]=new cmp('atencion_fechaatencion','d',20,$x,$w.' '.$o,'Fecha de la consulta','atencion_fechaatencion',null,'',true,false,'','col-2');
	$c[]=new cmp('tipo_consulta','s',3,$x,$w.' '.$o,'Tipo de Consulta','tipo_consulta',null,'',true,false,'','col-2');
	$c[]=new cmp('atencion_codigocups','s',3,$x,$w.' '.$o,'Código CUPS','cups',null,'',true,false,'','col-3');
	$c[]=new cmp('atencion_finalidadconsulta','s',3,$x,$w.' '.$o,'Finalidad de la Consulta','consultamedica',null,'',true,false,'','col-3');


	$c[]=new cmp('atencion_cronico','s',3,$x,$w.'  '.$o,'¿Usuario con patologia Cronica?','aler',null,'',true,true,'','col-3');
	
	$c[]=new cmp('gestante','s',3,$x,$w.' '.$o,'¿Usuaria Gestante?','aler',null,'',$gest,$gest,'','col-3',"alerPreg(this,'pre','nfe','fer','mef');periAbd('gestante','AbD',$adult);");

	$c[]=new cmp('atencion_peso','sd',6,$x,$w.' '.$o,'Peso (Kg) Mín=0.50 - Máx=150.00','atencion_peso','rgxpeso','###.##',true,true,'','col-2',"valPeso('atencion_peso');ZscoAte('dxnutricional');");
	$c[]=new cmp('atencion_talla','sd',5, $x,$w.' '.$o,'Talla (Cm) Mín=40 - Máx=210','atencion_talla','rgxtalla','###.#',true,true,'','col-2',"valTalla('atencion_talla');ZscoAte('dxnutricional');");
	$c[]=new cmp('atencion_sistolica','n',3, $x,$w,'TAS Mín=40 - Máx=250','atencion_sistolica','rgxsisto','###',$adul,$adul,'','col-2',"valSist('atencion_sistolica');");
	$c[]=new cmp('atencion_diastolica','n',3, $x,$w,'TAD Mín=40 - Máx=150','atencion_diastolica','rgxdiast','###',$adul,$adul,'','col-2',"ValTensions('atencion_sistolica',this);valDist('atencion_diastolica');");
	$c[]=new cmp('atencion_abdominal','n',4,$x,$w.' AbD '.$o,'Perímetro Abdominal (Cm) Mín=50 - Máx=150','atencion_abdominal','rgxperabd','###',$adul,$adul,'','col-3');
	
	
	$c[]=new cmp('perime_braq','sd',4, $x,$w,'Perimetro Braquial (Cm)',0,null,'##,#',$meno,$meno,'','col-3');

	$c[]=new cmp('dxnutricional','t',15,$x,$w.'  '.$o,'Dx Nutricional','des',null,null,false,false,'','col-5');

	$c[]=new cmp('signoalarma','s',2,$x,$w.'  '.$o,'niño o niña con signos de alarma ','aler',null,'',$meno,$meno,'','col-25','AlarChild(this,\'ala\');');
	$c[]=new cmp('cualalarma','s',3,$x,$w.' ala '.$o,'cual?','alarma5',null,'',false,false,'','col-25');
	

	$c[]=new cmp('letra1','s','3',$x,$w.' '.$o,'Letra CIE(1)','letra1',null,null,true,true,'','col-1','valPyd(this,\'tipo_consulta\');valResol(\'tipo_consulta\',\'letra1\');',['rango1']);
 	$c[]=new cmp('rango1','s','3',$x,$w.' '.$o,'Tipo1','rango1',null,null,true,true,'','col-45',false,['diagnostico1']);
 	$c[]=new cmp('diagnostico1','s','8',$x,$w.' '.$o,'Diagnostico Principal','diagnostico1',null,null,true,true,'','col-45');
	$c[]=new cmp('letra2','s','3',$x,$w.' '.$o,'Letra CIE(2)','letra2',null,null,false,true,'','col-1',false,['rango2']);
 	$c[]=new cmp('rango2','s','3',$x,$w.' '.$o,'Tipo2','rango2',null,null,false,true,'','col-45',false,['diagnostico2']);
 	$c[]=new cmp('diagnostico2','s','8',$x,$w.' '.$o,'Diagnostico 2','diagnostico2',null,null,false,true,'','col-45');
	$c[]=new cmp('letra3','s','3',$x,$w.' '.$o,'Letra CIE(3)','letra3',null,null,false,true,'','col-1',false,['rango3']);
 	$c[]=new cmp('rango3','s','3',$x,$w.' '.$o,'Tipo3','rango3',null,null,false,true,'','col-45',false,['diagnostico3']);
 	$c[]=new cmp('diagnostico3','s','8',$x,$w.' '.$o,'Diagnostico 3','diagnostico3',null,null,false,true,'','col-45');


$o='cronico';
	$c[]=new cmp($o,'e',null,'Condiciones',$w);


	$c[]=new cmp('fertil','s',3,$x,$w.' pre mef '.$o,'¿Mujer en Edad Fertil (MEF) con intención reproductiva?','aler',null,'',$gest,$gest,'','col-4',"enabFert(this,'fer','nfe');");
	$c[]=new cmp('preconcepcional','s',3,$x,$w.' pre nfe '.$o,'Tiene consulta preconcepcional','aler',null,'',$gest,false,'','col-2');
	$c[]=new cmp('metodo','s',3,$x,$w.' pre fer '.$o,'Uso actual de método anticonceptivo','aler',null,'',$gest,false,'','col-2','enabAlert(this,\'met\');');
	$c[]=new cmp('anticonceptivo','s',3,$x,$w.' pre fer met '.$o,'Metodo anticonceptivo','metodoscons',null,'',$gest,false,'','col-2');
	$c[]=new cmp('planificacion','s',3,$x,$w.' pre fer '.$o,'Tiene consulta de PF','aler',null,'',$gest,false,'','col-2');
	$c[]=new cmp('mestruacion','d',3,$x,$w.'  '.$o,'Fecha de ultima Mestruacion','atencion_mestruacion',null,'',false,true,'','col-2');	
// }	

$o='prurap';
	$c[]=new cmp($o,'e',null,'Aplicacion de Pruebas Rapidas',$w);
	$c[]=new cmp('vih','s',3,$x,$w.' '.$o,'Prueba Rapida Para VIH','aler',null,'',true,true,'','col-25',"enabTest(this,'vih');");
	$c[]=new cmp('resul_vih','s',3,$x,$w.' vih '.$o,'Resultado VIH','vih',null,'',true,false,'','col-25');
	$c[]=new cmp('hb','s',3,$x,$w.' '.$o,'Prueba Rapida Para Hepatitis B Antigeno de Superficie','aler',null,'',true,true,'','col-25',"enabTest(this,'hb');");
	$c[]=new cmp('resul_hb','s',3,$x,$w.' hb '.$o,'Resultado Hepatitis B Antigeno de Superficie','rep',null,'',true,false,'','col-25');
	$c[]=new cmp('trepo_sifil','s',3,$x,$w.' '.$o,'Prueba Rapida Treponémica Para Sifilis','aler',null,'',true,true,'','col-25',"enabTest(this,'sif');");
	$c[]=new cmp('resul_sifil','s',3,$x,$w.' sif '.$o,'Resultado Treponémica Para Sifilis','rep',null,'',true,false,'','col-25');
	$c[]=new cmp('pru_embarazo','s',3,$x,$w.' '.$o,'Prueba de Embarazo','aler',null,'',$gest,$gest,'','col-25',"enabTest(this,'pem');");
	$c[]=new cmp('resul_emba','s',3,$x,$w.' pem '.$o,'Resultado prueba de Embarazo','rep',null,'',$gest,false,'','col-25');

 $o='plancuidado';
	$c[]=new cmp($o,'e',null,'Plan de Cuidado Individual',$w);
	$c[]=new cmp('atencion_eventointeres','o',3,$x,$w.' '.$o,'Notificacion de eventos de interés en salud pública','atencion_eventointeres	',null,'',false,$u,'','col-35','enabEven(this,\'even\',\'whic\');');//,'hidFieOpt(\'atencion_eventointeres\',\'event_hid\',this,true)'
	$c[]=new cmp('atencion_evento','s',3,$x,$w.' even '.$o,'Evento de Interes en Salud Publica','evento',null,'',false,false,'','col-4','cualEven(this,\'whic\');');//,'hidFieselet(\'atencion_evento\',\'hidd_aten\',this,true,\'5\')'
	$c[]=new cmp('atencion_cualevento','t',300,$x,$w.' whic '.$o,'Otro, Cual?','atencion_cualevento	',null,'',false,false,'','col-25');
	$c[]=new cmp('atencion_sirc','o',3,$x,$w.' '.$o,'Activación rutas SIRC (usuarios otras EAPB)','atencion_sirc	',null,'',false,true,'','col-5',"enabAlert(this,'sirc');");//,'hidFieOpt(\'atencion_sirc\',\'sirc\',this,true)'
	$c[]=new cmp('atencion_rutasirc[]','m',3,$x,$w.' sirc '.$o,'Rutas SIRC','rutapoblacion',null,'',false,false,'','col-5');
	$c[]=new cmp('atencion_remision','o',3,$x,$w.' '.$o,'Usuario que require control','atencion_remision	',null,'',false,true,'','col-5','enabAlert(this,\'rem\');');//,'hidFieOpt(\'atencion_remision\',\'espe_hid\',this,true)'
	$c[]=new cmp('atencion_cualremision[]','m',3,$x,$w.' rem '.$o,'Cuales?	','remision	',null,'',false,false,'','col-5');
	
	$c[]=new cmp('atencion_ordenvacunacion','o',3,$x,$w.' '.$o,'Orden Vacunación?','atencion_ordenvacunacion	',null,'',false,true,'','col-1','enabAlert(this,\'vac\');');//,'hidFieOpt(\'atencion_ordenvacunacion\',\'vacu_hid\',this,true)'
	$c[]=new cmp('atencion_vacunacion','s',3,$x,$w.' vac '.$o,'Vacunación	','vacunacion',null,'',false,false,'','col-2');
	
	$c[]=new cmp('atencion_ordenlaboratorio','o',3,$x,$w.' '.$o,'Ordena Laboratorio ?','atencion_ordenlaboratorio	',null,'',false,true,'','col-15','enabAlert(this,\'lab\');');//,'hidFieOpt(\'atencion_ordenlaboratorio\',\'lab_hid\',this,true)'
	$c[]=new cmp('atencion_laboratorios','s',3,$x,$w.' lab '.$o,'Laboratorio','solicitud',null,'',false,false,'','col-2');
	
	$c[]=new cmp('atencion_ordenmedicamentos','o',3,$x,$w.' '.$o,'Ordena Medicamentos ?','atencion_ordenmedicamentos	',null,'',false,true,'','col-15','enabAlert(this,\'med\');');//,'hidFieOpt(\'atencion_ordenmedicamentos\',\'medi_hid\',this,true)'
	$c[]=new cmp('atencion_medicamentos','s',3,$x,$w.' med '.$o,'Medicamentos','medicamentos',null,'',false,false,'','col-2');
	
	$c[]=new cmp('atencion_rutacontinuidad','o',3,$x,$w.' '.$o,'Remisión para continuidad a rutas integrales de atencion en salud por parte de la subred','prueba	',null,'',false,true,'','col-5',"enabAlert(this,'rut');");//,'hidFieOpt(\'atencion_rutacontinuidad\',\'cont_hid\',this,true)'
	$c[]=new cmp('atencion_continuidad[]','m',3,$x,$w.' rut '.$o,'.','rutapoblacion',null,'',false,false,'','col-5');
	$c[]=new cmp('atencion_ordenimagenes','o',3,$x,$w.' '.$o,'Ordena Imágenes Diagnósticas','atencion_ordenimagenes	',null,'',true,true,'','col-3');//,'hidFieOpt(\'atencion_ordenimagenes\',\'img_hid\',this,true)'
	$c[]=new cmp('atencion_ordenpsicologia','s',3,$x,$w.' '.$o,'Ordena Psicología','aler',null,'',true,true,'','col-3');
	$c[]=new cmp('atencion_relevo','s',3,$x,$w.' '.$o,'Cumple criterios Para relevo domiciliario a cuidadores','aler',null,'',true,true,'','col-4');
	$c[]=new cmp('prioridad','s',3,$x,$w.' '.$o,'Prioridad','prioridad',null,'',true,true,'','col-4');
	$c[]=new cmp('estrategia','s',3,$x,$w.' '.$o,'Estrategia','estrategia',null,'',true,true,'','col-4');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
   }

   function lis_atencion(){
	// FN_CATALOGODESC(1,F.tipo_doc) Identificación, F.documento 'Número', F.`fecha_create` 'fecha creación' 
	/* $id=divide($_POST['id']);
	$id=divide($_POST['ida']); */
	$id = isset($_POST['id']) ? divide($_POST['id']) : (isset($_POST['ida']) ? divide($_POST['ida']) : null);

	// print_r($id);
	$info=datos_mysql("SELECT COUNT(*) total FROM adm_facturacion F WHERE F.documento ='{$id[0]}' AND F.tipo_doc='{$id[1]}'");
	$total=$info['responseResult'][0]['total'];
	$regxPag=4;

	$pag=(isset($_POST['pag-atencion']))? ($_POST['pag-atencion']-1)* $regxPag:0;
	$sql="SELECT  F.id_factura ACCIONES,F.cod_admin,F.fecha_consulta fecha,FN_CATALOGODESC(182,F.tipo_consulta) Consulta,
	FN_CATALOGODESC(126,F.cod_cups) 'Código CUPS',FN_CATALOGODESC(127,F.final_consul) Finalidad
	FROM adm_facturacion F
	WHERE F.documento ='{$id[0]}' AND F.tipo_doc='{$id[1]}'";
		$sql.=" ORDER BY F.fecha_create";
		$sql.=' LIMIT '.$pag.','.$regxPag;
		// echo $sql;
			$datos=datos_mysql($sql);
			return create_table($total,$datos["responseResult"],"atencion",$regxPag,'lib.php');
		// return panel_content($datos["responseResult"],"atencion-lis",5);
	}

function get_personas(){
		if($_REQUEST['id']==''){
			return "";
		}else{
			$id=divide($_REQUEST['id']);
			//  `atencion_fechaatencion`, `atencion_codigocups`, `atencion_finalidadconsulta`, `atencion_peso`, `atencion_talla`, `atencion_sistolica`, `atencion_diastolica`, `atencion_abdominal`, `atencion_brazo`, `atencion_diagnosticoprincipal`, `atencion_diagnosticorelacion1`, `atencion_diagnosticorelacion2`, `atencion_diagnosticorelacion3`, `atencion_fertil`, `atencion_preconcepcional`, `atencion_metodo`, `atencion_anticonceptivo`, `atencion_planificacion`, `atencion_mestruacion`, `atencion_gestante`, `atencion_gestaciones`, `atencion_partos`, `atencion_abortos`, `atencion_cesarias`, `atencion_vivos`, `atencion_muertos`, `atencion_vacunaciongestante`, `atencion_edadgestacion`, `atencion_ultimagestacion`, `atencion_probableparto`, `atencion_prenatal`, `atencion_fechaparto`, `atencion_rpsicosocial`, `atencion_robstetrico`, `atencion_rtromboembo`, `atencion_rdepresion`, `atencion_sifilisgestacional`, `atencion_sifiliscongenita`, `atencion_morbilidad`, `atencion_hepatitisb`, `atencion_vih`, `atencion_cronico`, `atencion_asistenciacronica`, `atencion_tratamiento`, `atencion_vacunascronico`, `atencion_menos5anios`, `atencion_esquemavacuna`, `atencion_signoalarma`, `atencion_cualalarma`, `atencion_dxnutricional`, `atencion_eventointeres`, `atencion_evento`, `atencion_cualevento`, `atencion_sirc`, `atencion_rutasirc`, `atencion_remision`, `atencion_cualremision`, `atencion_ordenpsicologia`, `atencion_ordenvacunacion`, `atencion_vacunacion`, `atencion_ordenlaboratorio`, `atencion_laboratorios`, `atencion_ordenimagenes`, `atencion_imagenes`, `atencion_ordenmedicamentos`, `atencion_medicamentos`, `atencion_rutacontinuidad`, `atencion_continuidad`, `atencion_relevo`  ON a.atencion_idpersona = b.idpersona AND a.atencion_tipodoc = b.tipo_doc
			$sql="SELECT  a.tipo_doc,a.idpersona,concat_ws(' ',a.nombre1,a.nombre2,a.apellido1,a.apellido2) nombres,a.fecha_nacimiento,a.sexo,a.genero,a.nacionalidad,
			b.fecha_consulta,b.tipo_consulta,cod_cups,fecha_consulta,tipo_consulta,final_consul
			FROM personas a
			LEFT JOIN adm_facturacion b ON a.idpersona = b.documento AND a.tipo_doc = b.tipo_doc
			WHERE a.idpersona ='{$id[0]}' AND a.tipo_doc='{$id[1]}'";
			// echo $sql;
			$info=datos_mysql($sql);
			return $info['responseResult'][0];			
		}
}

/* function get_zscore(){
	$id=divide($_POST['val']);
	 $fechaNacimiento = new DateTime($id[1]);
	 $fechaActual = new DateTime();
	 $diferencia = $fechaNacimiento->diff($fechaActual);
	 $edadEnDias = $diferencia->days;
	 if($edadEnDias<1857){
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
return json_encode('N/A');
}
 */
function get_atencion(){
	if($_REQUEST['id']==''){
		return "";
	}else{
		 $id=$_REQUEST['id'];
			// print_r($id[0]);
			// print_r($_REQUEST['id']);
			$sql1="SELECT COUNT(*) rta
			FROM adm_facturacion a
			LEFT JOIN eac_atencion c ON a.tipo_doc = c.atencion_tipodoc AND a.documento = c.atencion_idpersona
			WHERE c.id_factura ='{$id}' and a.id_factura='{$id}'";
			$info=datos_mysql($sql1);
			$total=$info['responseResult'][0]['rta'];
			// echo $sql;
			/* $info=datos_mysql($sql); */
			// return json_encode($info['responseResult'][0]);

			if ($total==1){		
				$sql="SELECT concat(a.documento,'_',a.tipo_doc) id,a.tipo_doc,a.documento,concat_ws(' ',b.nombre1,b.nombre2,b.apellido1,b.apellido2) nombres,
			b.fecha_nacimiento,b.sexo,b.genero,b.nacionalidad, a.id_factura,a.fecha_consulta,a.tipo_consulta,a.cod_cups,a.final_consul,
			atencion_cronico, `gestante`, 
				`atencion_peso`, `atencion_talla`, `atencion_sistolica`, `atencion_diastolica`, `atencion_abdominal`, `atencion_brazo`,
				dxnutricional,signoalarma,cualalarma,`letra1`, `rango1`, `diagnostico1`, `letra2`, `rango2`, `diagnostico2`, `letra3`, `rango3`, 
				`diagnostico3`,`fertil`, `preconcepcional`, `metodo`, `anticonceptivo`, `planificacion`, 
				`mestruacion`,
				vih,resul_vih,hb,resul_hb,trepo_sifil,resul_sifil,pru_embarazo,resul_emba,
				`atencion_eventointeres`, `atencion_evento`, `atencion_cualevento`, 
				`atencion_sirc`, `atencion_rutasirc`, `atencion_remision`, `atencion_cualremision`, `atencion_ordenvacunacion`, `atencion_vacunacion`, `atencion_ordenlaboratorio`, `atencion_laboratorios`, `atencion_ordenmedicamentos`, `atencion_medicamentos`, `atencion_rutacontinuidad`, `atencion_continuidad`, `atencion_ordenimagenes`, `atencion_ordenpsicologia`, `atencion_relevo`
				,prioridad,estrategia
			FROM adm_facturacion a
			LEFT JOIN personas b ON a.tipo_doc=b.tipo_doc AND a.documento=b.idpersona
			LEFT JOIN eac_atencion c ON a.tipo_doc=c.atencion_tipodoc AND a.documento=c.atencion_idpersona
			WHERE c.id_factura ='{$id}' and a.id_factura='{$id}'";
			//  echo $sql;
			$info=datos_mysql($sql);
			return json_encode($info['responseResult'][0]);
			}else{
				$sql="SELECT concat(a.documento,'_',a.tipo_doc) id,a.tipo_doc,a.documento,concat_ws(' ',b.nombre1,b.nombre2,b.apellido1,b.apellido2) nombres,
				b.fecha_nacimiento,b.sexo,b.genero,b.nacionalidad, a.id_factura,a.fecha_consulta,a.tipo_consulta,a.cod_cups,a.final_consul,
				`atencion_cronico`,`gestante`,
				`atencion_peso`, `atencion_talla`, `atencion_sistolica`, `atencion_diastolica`, `atencion_abdominal`, `atencion_brazo`,
			dxnutricional,signoalarma,cualalarma,`letra1`, `rango1`, `diagnostico1`, `letra2`, `rango2`, `diagnostico2`, `letra3`, `rango3`, 
			`diagnostico3`, `fertil`, `preconcepcional`, `metodo`, `anticonceptivo`, `planificacion`, 
			`mestruacion`, vih,resul_vih,hb,resul_hb,trepo_sifil,resul_sifil,pru_embarazo,resul_emba,
			  `atencion_eventointeres`, `atencion_evento`, `atencion_cualevento`, 
			`atencion_sirc`, `atencion_rutasirc`, `atencion_remision`, `atencion_cualremision`, `atencion_ordenvacunacion`, `atencion_vacunacion`, `atencion_ordenlaboratorio`, `atencion_laboratorios`, `atencion_ordenmedicamentos`, `atencion_medicamentos`, `atencion_rutacontinuidad`, `atencion_continuidad`, `atencion_ordenimagenes`, `atencion_ordenpsicologia`, `atencion_relevo`
			,prioridad,estrategia
			FROM adm_facturacion a
			LEFT JOIN personas b ON a.tipo_doc=b.tipo_doc AND a.documento=b.idpersona
			LEFT JOIN eac_atencion c ON a.tipo_doc=c.atencion_tipodoc AND a.documento=c.atencion_idpersona AND a.id_factura=c.id_factura
			WHERE a.id_factura='{$id}'";
		//  echo $sql;
			/*  */
			$info=datos_mysql($sql);
			return json_encode($info['responseResult'][0]);
			}
		 }
	} 

	
	function focus_atencion(){
		return 'atencion';
	   }
	   
	function men_atencion(){
		$rta=cap_menus('atencion','pro');
		return $rta;
	   }
	   
	function gra_atencion(){
		$id=divide($_POST['ida']);
		// print_r($_POST['ida']);
		if(count($id)==6){
			return "No es posible actualizar consulte con el administrador";
		}elseif(count($id)==2){
			
	$fertil = isset($_POST['fertil']) ? trim($_POST['fertil']) : '';
	$preconcepcional = isset($_POST['preconcepcional']) ? trim($_POST['preconcepcional']) : '';
	$metodo = isset($_POST['metodo']) ? trim($_POST['metodo']) : '';
	$anticonceptivo = isset($_POST['anticonceptivo']) ? trim($_POST['anticonceptivo']) : '';
	$planificacion = isset($_POST['planificacion']) ? trim($_POST['planificacion']) : '';
	$mestruacion = isset($_POST['mestruacion']) ? trim($_POST['mestruacion']) : '';
	$gestante = isset($_POST['gestante']) ? trim($_POST['gestante']) : '';

	if (($smu2 = $_POST['fatencion_rutasirc'] ?? null) && is_array($smu2)){$rutasirc = implode(",",str_replace("'", "", $smu2));}
	if (($smu1 = $_POST['fatencion_continuidad'] ?? null) && is_array($smu1)){$contin = implode(",",str_replace("'", "", $smu1));}
	if (($smu3 = $_POST['fatencion_cualremision'] ?? null) && is_array($smu3)){$remisi = implode(",",str_replace("'", "", $smu3));}

$sql="INSERT INTO eac_atencion VALUES (null,
		TRIM(UPPER('{$_POST['atencion_tipodoc']}')),
		TRIM(UPPER('{$_POST['atencion_idpersona']}')),
		TRIM(UPPER('{$_POST['idf']}')),
		TRIM(UPPER('{$_POST['atencion_fechaatencion']}')),
		TRIM(UPPER('{$_POST['tipo_consulta']}')),
		TRIM(UPPER('{$_POST['atencion_codigocups']}')),
		TRIM(UPPER('{$_POST['atencion_finalidadconsulta']}')),
		TRIM(UPPER('{$_POST['atencion_cronico']}')),
		TRIM(UPPER('{$gestante}')),
		TRIM(UPPER('{$_POST['atencion_peso']}')),
		TRIM(UPPER('{$_POST['atencion_talla']}')),
		TRIM(UPPER('{$_POST['atencion_sistolica']}')),
		TRIM(UPPER('{$_POST['atencion_diastolica']}')),
		TRIM(UPPER('{$_POST['atencion_abdominal']}')),
		TRIM(UPPER('{$_POST['perime_braq']}')),
		TRIM(UPPER('{$_POST['dxnutricional']}')),
		TRIM(UPPER('{$_POST['signoalarma']}')),
		TRIM(UPPER('{$_POST['cualalarma']}')),
		TRIM(UPPER('{$_POST['letra1']}')),
		TRIM(UPPER('{$_POST['rango1']}')),
		TRIM(UPPER('{$_POST['diagnostico1']}')),
		TRIM(UPPER('{$_POST['letra2']}')),
		TRIM(UPPER('{$_POST['rango2']}')),
		TRIM(UPPER('{$_POST['diagnostico2']}')),
		TRIM(UPPER('{$_POST['letra3']}')),
		TRIM(UPPER('{$_POST['rango3']}')),
		TRIM(UPPER('{$_POST['diagnostico3']}')),
		TRIM(UPPER('{$fertil}')),
		TRIM(UPPER('{$preconcepcional}')),
		TRIM(UPPER('{$metodo}')),
		TRIM(UPPER('{$anticonceptivo}')),
		TRIM(UPPER('{$planificacion}')),
		TRIM(UPPER('{$mestruacion}')),
		TRIM(UPPER('{$_POST['vih']}')),
		TRIM(UPPER('{$_POST['resul_vih']}')),
		TRIM(UPPER('{$_POST['hb']}')),
		TRIM(UPPER('{$_POST['resul_hb']}')),
		TRIM(UPPER('{$_POST['trepo_sifil']}')),
		TRIM(UPPER('{$_POST['resul_sifil']}')),
		TRIM(UPPER('{$_POST['pru_embarazo']}')),
		TRIM(UPPER('{$_POST['resul_emba']}')),
		TRIM(UPPER('{$_POST['atencion_eventointeres']}')),
		TRIM(UPPER('{$_POST['atencion_evento']}')),
		TRIM(UPPER('{$_POST['atencion_cualevento']}')),
		TRIM(UPPER('{$_POST['atencion_sirc']}')),
		TRIM(UPPER('{$rutasirc}')),
		TRIM(UPPER('{$_POST['atencion_remision']}')),
		TRIM(UPPER('{$remisi}')),
		TRIM(UPPER('{$_POST['atencion_ordenvacunacion']}')),
		TRIM(UPPER('{$_POST['atencion_vacunacion']}')),
		TRIM(UPPER('{$_POST['atencion_ordenlaboratorio']}')),
		TRIM(UPPER('{$_POST['atencion_laboratorios']}')),
		TRIM(UPPER('{$_POST['atencion_ordenmedicamentos']}')),
		TRIM(UPPER('{$_POST['atencion_medicamentos']}')),
		TRIM(UPPER('{$_POST['atencion_rutacontinuidad']}')),
		TRIM(UPPER('{$contin}')),
		TRIM(UPPER('{$_POST['atencion_ordenimagenes']}')),
		TRIM(UPPER('{$_POST['atencion_ordenpsicologia']}')),
		TRIM(UPPER('{$_POST['atencion_relevo']}')),
		TRIM(UPPER('{$_POST['prioridad']}')),
		TRIM(UPPER('{$_POST['estrategia']}')),
			TRIM(UPPER('{$_SESSION['us_sds']}')),
			DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
			// echo $sql;
		}
		  $rta=dato_mysql($sql);
		  return $rta; 
}

////////////////////////////////////////////////////////////////////////////////////////////////////

function opc_estrategia($id=''){
	return opc_sql("SELECT idcatadeta,descripcion,valor FROM `catadeta` WHERE idcatalogo=203 and estado='A'  ORDER BY 1 ",$id);
}
function opc_prioridad($id=''){
	return opc_sql("SELECT idcatadeta,descripcion,valor FROM `catadeta` WHERE idcatalogo=201 and estado='A'  ORDER BY 1 ",$id);
}
function opc_vih($id=''){
	return opc_sql("SELECT idcatadeta,descripcion,valor FROM `catadeta` WHERE idcatalogo=187 and estado='A'  ORDER BY 1 ",$id);
}
function opc_rep($id=''){
	return opc_sql("SELECT idcatadeta,descripcion,valor FROM `catadeta` WHERE idcatalogo=188 and estado='A'  ORDER BY 1 ",$id);
}
function opc_aler($id=''){
	return opc_sql("SELECT `descripcion`,descripcion,valor FROM `catadeta` WHERE idcatalogo=170 and estado='A'  ORDER BY 1 ",$id);
}

function opc_tipo_consulta($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=182 and estado='A'  ORDER BY 1 ",$id);
}
	function opc_alarma5($id=''){
		return opc_sql("SELECT iddiagnostico,descripcion FROM `diagnosticos` WHERE `iddiag`='1' and estado='A' ORDER BY 2 ",$id);
	}
	function opc_nutricion5($id=''){
		return opc_sql("SELECT iddiagnostico,descripcion FROM `diagnosticos` WHERE `iddiag`='1' and estado='A' ORDER BY 2 ",$id);
	}
	function opc_letra1($id=''){
		return opc_sql("SELECT iddiagnostico,descripcion FROM `diagnosticos` WHERE `iddiag`='1' and estado='A' ORDER BY 2 ",$id);
	}

	function opc_rango1($id=''){
	/* 	print_r($_REQUEST);
		print_r($_POST);
		if (count(divide($_POST['id']))==2){
			return opc_sql("SELECT iddiagnostico,descripcion FROM `diagnosticos` WHERE `iddiag`='2' and estado='A' ORDER BY 1 ",$id);
		} */
	}
	function opc_diagnostico1($id=''){
	/* 	print_r($_POST);
		if (count(divide($_POST['id']))==2){
			return opc_sql("SELECT `iddiagnostico`,descripcion FROM `diagnosticos` WHERE `iddiag`='3' and estado='A'  ORDER BY descripcion ",$id);
		} */
	}

	function opc_letra2($id=''){
		return opc_sql("SELECT iddiagnostico,descripcion FROM `diagnosticos` WHERE `iddiag`='1' and estado='A' ORDER BY 2 ",$id);
	}

	function opc_rango2($id=''){
		/* if (count(divide($_POST['id']))==2){
			return opc_sql("SELECT iddiagnostico,concat(iddiagnostico,'-',descripcion) FROM `diagnosticos` WHERE `iddiag`='2' and estado='A' ORDER BY 1 ",$id);
		} */
	}
	function opc_diagnostico2($id=''){
		/* if (count(divide($_POST['id']))==2){
			return opc_sql("SELECT `iddiagnostico`,concat(iddiagnostico,'-',descripcion) FROM `diagnosticos` WHERE `iddiag`='3' and estado='A'  ORDER BY descripcion ",$id);
		} */
	}
	function opc_letra3($id=''){
		return opc_sql("SELECT iddiagnostico,descripcion FROM `diagnosticos` WHERE `iddiag`='1' and estado='A' ORDER BY 2 ",$id);
	}

	function opc_rango3($id=''){
		/* if (count(divide($_POST['id']))==2){
			return opc_sql("SELECT iddiagnostico,concat(iddiagnostico,'-',descripcion) FROM `diagnosticos` WHERE `iddiag`='2' and estado='A' ORDER BY 1 ",$id);
		} */
	}
	function opc_diagnostico3($id=''){
		/* if (count(divide($_POST['id']))==2){
			return opc_sql("SELECT `iddiagnostico`,concat(iddiagnostico,'-',descripcion) FROM `diagnosticos` WHERE `iddiag`='3' and estado='A'  ORDER BY descripcion ",$id);
		} */
	}

	function opc_letra1rango1(){
		if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT iddiagnostico 'id',descripcion 'asc' FROM `diagnosticos` WHERE iddiag='2' and estado='A' and valor='".$id[0]."' ORDER BY 1";
			$info=datos_mysql($sql);		
			return json_encode($info['responseResult']);
		} 
	}

	function opc_rango1diagnostico1(){
		if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT iddiagnostico 'id',descripcion 'asc' FROM `diagnosticos` WHERE iddiag='3' and estado='A' and valor='".$id[0]."' ORDER BY 1";
			$info=datos_mysql($sql);		
			// echo $_REQUEST['id'];
			return json_encode($info['responseResult']);
		} 
	}

	function opc_letra2rango2(){
		if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT iddiagnostico 'id',descripcion 'asc' FROM `diagnosticos` WHERE iddiag='2' and estado='A' and valor='".$id[0]."' ORDER BY 1";
			$info=datos_mysql($sql);		
			return json_encode($info['responseResult']);
		} 
	}

	function opc_rango2diagnostico2(){
		if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT iddiagnostico 'id',descripcion 'asc' FROM `diagnosticos` WHERE iddiag='3' and estado='A' and valor='".$id[0]."' ORDER BY 1";
			$info=datos_mysql($sql);		
			// echo $sql;
			return json_encode($info['responseResult']);
		} 
	}

		function opc_letra3rango3(){
		if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT iddiagnostico 'id',descripcion 'asc' FROM `diagnosticos` WHERE iddiag='2' and estado='A' and valor='".$id[0]."' ORDER BY 1";
			$info=datos_mysql($sql);		
			return json_encode($info['responseResult']);
		} 
	}

	function opc_rango3diagnostico3(){
		if($_REQUEST['id']!=''){
			$id=divide($_REQUEST['id']);
			$sql="SELECT iddiagnostico 'id',descripcion 'asc' FROM `diagnosticos` WHERE iddiag='3' and estado='A' and valor='".$id[0]."' ORDER BY 1";
			$info=datos_mysql($sql);		
			// echo $sql;
			return json_encode($info['responseResult']);
		} 
	}
		function opc_cups($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=126 and estado='A'  ORDER BY 1 ",$id);
	}

	function opc_consultamedica($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=127 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_metodoscons($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=129 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_rutapoblacion($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=131 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_remision($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=132 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_vacunacion($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=185 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_solicitud($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=133 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_medicamentos($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=186 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_evento($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=134 and estado='A'  ORDER BY 1 ",$id);
	}

////////////////////////////////////////////////////atencion//////////////////////////////////////////////

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// print_r($c);
// var_dump($a);
	if($a=='atencion' && $b=='acciones'){
		$rta="<nav class='menu right'>";
		$rta.="<li class='icono editar ' title='Editar Atención' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,1000,'atencion',event,this,['idpersona','tipo_doc']);setTimeout(getData,1300,'atencion',event,this,['idpersona','tipo_doc']);setTimeout(getData,1500,'atencion',event,this,['idpersona','tipo_doc']);setTimeout(changeSelect,1100,'letra1','rango1');setTimeout(changeSelect,1150,'letra2','rango2');setTimeout(changeSelect,1280,'letra3','rango3');setTimeout(changeSelect,1385,'rango1','diagnostico1');setTimeout(changeSelect,1385,'rango2','diagnostico2');setTimeout(changeSelect,1385,'rango3','diagnostico3');Color('datos-lis');\"></li>";	//
	}
 return $rta;
}

function bgcolor($a,$c,$f='c'){
	$rta = 'red';
	if ($a=='datos-lis'){
		if($c['Cronico']==='SIN'){
			return ($rta !== '') ? "style='background-color: $rta;'" : '';
		}
		if($c['Gestante']==='SIN'){
			return ($rta !== '') ? "style='background-color: $rta;'" : '';
		}
	}
}