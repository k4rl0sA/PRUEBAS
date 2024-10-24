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

function cmp_atencion(){
	$rta="";
	$rta .="<div class='encabezado atencion'>Consultas realizadas al paciente</div>
	<div class='contenido' id='atencion-lis' >".lis_atencion()."</div></div>";
	$hoy=date('Y-m-d');
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
	$c[]=new cmp('tipodoc','t','20',$d['tipo_doc'],$w.' '.$o,'Tipo','tipodoc',null,'',false,false,'','col-1');
	$c[]=new cmp('idpersona','t','20',$d['idpersona'],$w.' '.$o,'N° Identificación','idpersona',null,'',false,false,'','col-2');
	$c[]=new cmp('nombre1','t','20',$d['nombres'],$w.' '.$o,'Nombres','nombre1',null,'',false,false,'','col-3');
	$c[]=new cmp('fecha_nacimiento','t','20',$d['fecha_nacimiento'],$w.' '.$o,'fecha nacimiento','fecha_nacimiento',null,'',false,false,'','col-1','validDate');
	$c[]=new cmp('sexo','s','20',$d['sexo'],$w.' '.$o,'sexo','sexo',null,'',false,false,'','col-1');
	$c[]=new cmp('genero','s','20',$d['genero'],$w.' '.$o,'genero','genero',null,'',false,false,'','col-1');
	$c[]=new cmp('nacionalidad','s','20',$d['nacionalidad'],$w.' '.$o,'Nacionalidad','nacionalidad',null,'',false,false,'','col-1');

	$o='consulta';
	$c[]=new cmp($o,'e',null,'Datos de la atencion medica	',$w);
	$c[]=new cmp('idf','h',15,'',$w.' '.$o,'idf','idf',null,'####',false,false,'','col-1');
	$c[]=new cmp('fechaatencion','d',20,$x,$w.' '.$o,'Fecha de la consulta','fechaatencion',null,'',true,false,'','col-2');
	$c[]=new cmp('tipo_consulta','s',3,$x,$w.' '.$o,'Tipo de Consulta','tipo_consulta',null,'',true,false,'','col-2');
	$c[]=new cmp('codigocups','s',3,$x,$w.' '.$o,'Código CUPS','cups',null,'',true,false,'','col-3');
	$c[]=new cmp('finalidadconsulta','s',3,$x,$w.' '.$o,'Finalidad de la Consulta','consultamedica',null,'',true,false,'','col-3');

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
	$c[]=new cmp('mestruacion','d',3,$x,$w.'  '.$o,'Fecha de ultima Mestruacion','mestruacion',null,'',false,true,'','col-2');	
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
	$c[]=new cmp('eventointeres','o',3,$x,$w.' '.$o,'Notificacion de eventos de interés en salud pública','eventointeres	',null,'',false,$u,'','col-35','enabEven(this,\'even\',\'whic\');');//,'hidFieOpt(\'eventointeres\',\'event_hid\',this,true)'
	$c[]=new cmp('evento','s',3,$x,$w.' even '.$o,'Evento de Interes en Salud Publica','evento',null,'',false,false,'','col-4','cualEven(this,\'whic\');');//,'hidFieselet(\'evento\',\'hidd_aten\',this,true,\'5\')'
	$c[]=new cmp('cualevento','t',300,$x,$w.' whic '.$o,'Otro, Cual?','cualevento	',null,'',false,false,'','col-25');
	$c[]=new cmp('sirc','o',3,$x,$w.' '.$o,'Activación rutas SIRC (usuarios otras EAPB)','sirc	',null,'',false,true,'','col-5',"enabAlert(this,'sirc');");//,'hidFieOpt(\'sirc\',\'sirc\',this,true)'
	$c[]=new cmp('rutasirc[]','m',3,$x,$w.' sirc '.$o,'Rutas SIRC','rutapoblacion',null,'',false,false,'','col-5');
	$c[]=new cmp('remision','o',3,$x,$w.' '.$o,'Usuario que require control','remision	',null,'',false,true,'','col-5','enabAlert(this,\'rem\');');//,'hidFieOpt(\'remision\',\'espe_hid\',this,true)'
	$c[]=new cmp('cualremision[]','m',3,$x,$w.' rem '.$o,'Cuales?	','remision	',null,'',false,false,'','col-5');
	
	$c[]=new cmp('ordenvacunacion','o',3,$x,$w.' '.$o,'Orden Vacunación?','ordenvacunacion	',null,'',false,true,'','col-1','enabAlert(this,\'vac\');');//,'hidFieOpt(\'ordenvacunacion\',\'vacu_hid\',this,true)'
	$c[]=new cmp('vacunacion','s',3,$x,$w.' vac '.$o,'Vacunación	','vacunacion',null,'',false,false,'','col-2');
	
	$c[]=new cmp('ordenlaboratorio','o',3,$x,$w.' '.$o,'Ordena Laboratorio ?','ordenlaboratorio	',null,'',false,true,'','col-15','enabAlert(this,\'lab\');');//,'hidFieOpt(\'ordenlaboratorio\',\'lab_hid\',this,true)'
	$c[]=new cmp('laboratorios','s',3,$x,$w.' lab '.$o,'Laboratorio','solicitud',null,'',false,false,'','col-2');
	
	$c[]=new cmp('ordenmedicamentos','o',3,$x,$w.' '.$o,'Ordena Medicamentos ?','ordenmedicamentos	',null,'',false,true,'','col-15','enabAlert(this,\'med\');');//,'hidFieOpt(\'ordenmedicamentos\',\'medi_hid\',this,true)'
	$c[]=new cmp('medicamentos','s',3,$x,$w.' med '.$o,'Medicamentos','medicamentos',null,'',false,false,'','col-2');
	
	$c[]=new cmp('rutacontinuidad','o',3,$x,$w.' '.$o,'Remisión para continuidad a rutas integrales de atencion en salud por parte de la subred','prueba	',null,'',false,true,'','col-5',"enabAlert(this,'rut');");//,'hidFieOpt(\'rutacontinuidad\',\'cont_hid\',this,true)'
	$c[]=new cmp('continuidad','m',3,$x,$w.' rut '.$o,'.','rutapoblacion',null,'',false,false,'','col-5');
	$c[]=new cmp('ordenimagenes','o',3,$x,$w.' '.$o,'Ordena Imágenes Diagnósticas','ordenimagenes	',null,'',true,true,'','col-3');//,'hidFieOpt(\'ordenimagenes\',\'img_hid\',this,true)'
	$c[]=new cmp('ordenpsicologia','s',3,$x,$w.' '.$o,'Ordena Psicología','aler',null,'',true,true,'','col-3');
	$c[]=new cmp('relevo','s',3,$x,$w.' '.$o,'Cumple criterios Para relevo domiciliario a cuidadores','aler',null,'',true,true,'','col-4');
	$c[]=new cmp('prioridad','s',3,$x,$w.' '.$o,'Prioridad','prioridad',null,'',true,true,'','col-4');
	$c[]=new cmp('estrategia','s',3,$x,$w.' '.$o,'Estrategia','estrategia',null,'',true,true,'','col-4');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
   }

   function lis_atencion(){
	$id = isset($_POST['id']) ? divide($_POST['id']) : (isset($_POST['ida']) ? divide($_POST['ida']) : null);
	// print_r($id);
	$info=datos_mysql("SELECT COUNT(*) total FROM adm_facturacion F WHERE F.idpeople ='{$id[0]}'");
	$total=$info['responseResult'][0]['total'];
	$regxPag=4;

	$pag=(isset($_POST['pag-atencion']))? ($_POST['pag-atencion']-1)* $regxPag:0;
	$sql="SELECT  F.id_factura ACCIONES,F.cod_admin,F.fecha_consulta fecha,FN_CATALOGODESC(182,F.tipo_consulta) Consulta,
	FN_CATALOGODESC(126,F.cod_cups) 'Código CUPS',FN_CATALOGODESC(127,F.final_consul) Finalidad
	FROM adm_facturacion F
	WHERE F.idpeople ='{$id[0]}'";
		$sql.=" ORDER BY F.fecha_create";
		$sql.=' LIMIT '.$pag.','.$regxPag;
		// echo $sql;
			$datos=datos_mysql($sql);
			return create_table($total,$datos["responseResult"],"atencion",$regxPag,'lib.php');
		// return panel_content($datos["responseResult"],"atencion-lis",5);
	}

	function get_personas(){
		//var_dump($_REQUEST);
		if($_REQUEST['id']==''){
			return "";
		}else{
			$id=divide($_REQUEST['id']);
			//  `fechaatencion`, `codigocups`, `finalidadconsulta`, `peso`, `talla`, `sistolica`, `diastolica`, `abdominal`, `brazo`, `diagnosticoprincipal`, `diagnosticorelacion1`, `diagnosticorelacion2`, `diagnosticorelacion3`, `fertil`, `preconcepcional`, `metodo`, `anticonceptivo`, `planificacion`, `mestruacion`, `gestante`, `gestaciones`, `partos`, `abortos`, `cesarias`, `vivos`, `muertos`, `vacunaciongestante`, `edadgestacion`, `ultimagestacion`, `probableparto`, `prenatal`, `fechaparto`, `rpsicosocial`, `robstetrico`, `rtromboembo`, `rdepresion`, `sifilisgestacional`, `sifiliscongenita`, `morbilidad`, `hepatitisb`, `vih`, `cronico`, `asistenciacronica`, `tratamiento`, `vacunascronico`, `menos5anios`, `esquemavacuna`, `signoalarma`, `cualalarma`, `dxnutricional`, `eventointeres`, `evento`, `cualevento`, `sirc`, `rutasirc`, `remision`, `cualremision`, `ordenpsicologia`, `ordenvacunacion`, `vacunacion`, `ordenlaboratorio`, `laboratorios`, `ordenimagenes`, `imagenes`, `ordenmedicamentos`, `medicamentos`, `rutacontinuidad`, `continuidad`, `relevo`  ON a.idpersona = b.idpersona AND a.tipodoc = b.tipo_doc
			$sql="SELECT  a.idpersona,tipo_doc,concat_ws(' ',a.nombre1,a.nombre2,a.apellido1,a.apellido2) nombres,a.fecha_nacimiento,a.sexo,a.genero,a.nacionalidad,
			b.fecha_consulta,b.tipo_consulta,cod_cups,fecha_consulta,tipo_consulta,final_consul
			FROM person a
			LEFT JOIN adm_facturacion b ON a.idpeople = b.idpeople 
			WHERE a.idpeople ='{$id[0]}'";
			// echo $sql;
			$info=datos_mysql($sql);
			return $info['responseResult'][0];			
		}
}

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
				$sql="SELECT concat(a.idpeople) id,b.tipo_doc,b.idpersona,concat_ws(' ',b.nombre1,b.nombre2,b.apellido1,b.apellido2) nombres,
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
			LEFT JOIN person b ON a.tipo_doc=b.tipo_doc AND a.documento=b.idpersona
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
			LEFT JOIN person b ON a.tipo_doc=b.tipo_doc AND a.documento=b.idpersona
			LEFT JOIN eac_atencion c ON a.tipo_doc=c.atencion_tipodoc AND a.documento=c.atencion_idpersona AND a.id_factura=c.id_factura
			WHERE a.id_factura='{$id}'";
		//  echo $sql;
			/*  */
			$info=datos_mysql($sql);
			return json_encode($info['responseResult'][0]);
			}
		 }
	}

/*************INICIO MENU***********************/
function men_atencion(){
	$rta=cap_menus('atencion','pro');
	return $rta;
   }
   function focus_atencion(){
	return 'atencion';
   }


/****************FIN MENU*****************+*****/
/*************INICIO DESPLEGABLES***********************/
function opc_sexo($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
}
function opc_genero($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=19 and estado='A' ORDER BY 1",$id);
}
function opc_nacionalidad($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=30 and estado='A' ORDER BY 1",$id);
}
function opc_tipo_consulta($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=182 and estado='A'  ORDER BY 1 ",$id);
}
function opc_cups($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=126 and estado='A'  ORDER BY 1 ",$id);
}
function opc_consultamedica($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=127 and estado='A'  ORDER BY 1 ",$id);
}
function opc_aler($id=''){
	return opc_sql("SELECT `descripcion`,descripcion,valor FROM `catadeta` WHERE idcatalogo=170 and estado='A'  ORDER BY 1 ",$id);
}
function opc_alarma5($id=''){
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
function opc_metodoscons($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=129 and estado='A'  ORDER BY 1 ",$id);
}
function opc_vih($id=''){
	return opc_sql("SELECT idcatadeta,descripcion,valor FROM `catadeta` WHERE idcatalogo=187 and estado='A'  ORDER BY 1 ",$id);
}
function opc_rep($id=''){
	return opc_sql("SELECT idcatadeta,descripcion,valor FROM `catadeta` WHERE idcatalogo=188 and estado='A'  ORDER BY 1 ",$id);
}
function opc_evento($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion,valor FROM `catadeta` WHERE idcatalogo=134 and estado='A'  ORDER BY 1 ",$id);
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
function opc_prioridad($id=''){
	return opc_sql("SELECT idcatadeta,descripcion,valor FROM `catadeta` WHERE idcatalogo=201 and estado='A'  ORDER BY 1 ",$id);
}
function opc_estrategia($id=''){
	return opc_sql("SELECT idcatadeta,descripcion,valor FROM `catadeta` WHERE idcatalogo=203 and estado='A'  ORDER BY 1 ",$id);
}
/****************FIN DESPLEGABLES*****************+*****/


function cap_menus($a,$b='cap',$con='con') {
	$rta = "";
	$acc=rol($a);
	if ($a=='homes' && isset($acc['crear']) && $acc['crear']=='SI') {  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
  //   $rta .= "<li class='icono $a exportar'       title='Exportar'    Onclick=\"csv('$a');\"></li>"; 
	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
	 }
	 if ($a=='person' && isset($acc['crear']) && $acc['crear']=='SI') {  
	  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	  // $rta .= "<li class='icono $a exportar'       title='Exportar'    Onclick=\"csv('$a');\"></li>"; 
	  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
	  }
	  if($a=='atencion' && isset($acc['crear']) && $acc['crear']=='SI'){
		  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
		  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  
	  }
		  if($a=='eac_juventud' && isset($acc['crear']) && $acc['crear']=='SI'){
		  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
		  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  
	  }
	  if($a=='eac_adultez' && isset($acc['crear']) && $acc['crear']=='SI'){
		  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
		  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  
	  }
	  if($a=='eac_vejez' && isset($acc['crear']) && $acc['crear']=='SI'){
		  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>";
		  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
  
	  }  
	return $rta;
  }

  function formato_dato($a,$b,$c,$d){
	$b=strtolower($b);
	$rta=$c[$d];
   // print_r($c);
   // var_dump($a);
	   if ($a=='homes' && $b=='acciones'){
		   $rta="<nav class='menu right'>";		
		   $rta.="<li class='icono casa' title='Caracterización del Hogar' id='".$c['ACCIONES']."' Onclick=\"mostrar('homes1','fix',event,'','lib.php',0,'homes1');hideFix('person1','fix');Color('homes-lis');\"></li>";//setTimeout(mostrar('person1','fix',event,'','lib.php',0,'person1'),500);
		   $rta.="<li class='icono crear' title='Crear Familia' id='".$c['ACCIONES']."' Onclick=\"mostrar('homes','pro',event,'','lib.php',7,'homes');setTimeout(DisableUpdate,300,'fechaupd','hid');Color('homes-lis');\"></li>";
	   }
	   if ($a=='famili-lis' && $b=='acciones'){
		   $rta="<nav class='menu right'>";		
		   $rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('homes','pro',event,'','lib.php',7,'homes');setTimeout(getData,300,'homes',event,this,['idviv','numfam','estado_aux']);Color('famili-lis');\"></li>";  //act_lista(f,this);
		   $rta.="<li class='icono actimed' title='Estado Familia' id='".$c['ACCIONES']."' Onclick=\"mostrar('statFam','pro',event,'','stateFami.php',5,'stateFami');Color('famili-lis');\"></li>";
		   if(estado($c['Cod_Familiar'])===true){			
			   $rta.="<li class='icono familia' title='Integrantes Personas' id='".$c['ACCIONES']."' Onclick=\"mostrar('person1','fix',event,'','lib.php',0,'person1');Color('famili-lis');\"></li>";//setTimeout(plegar,500);mostrar('person','pro',event,'','lib.php',7);
			   $rta.="<li class='icono crear' title='Crear Integrante Familia' id='".$c['ACCIONES']."' Onclick=\"mostrar('person','pro',event,'','lib.php',7,'person');setTimeout(disabledCmp,300,'cmhi');setTimeout(enabLoca('reside_localidad','lochi'),300);Color('famili-lis');\"></li>";
		   }
	   }
	   if ($a=='datos-lis' && $b=='acciones'){
		   $rta="<nav class='menu right'>";
		   $rta.="<li class='icono editar' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('person','pro',event,'','lib.php',7,'person');setTimeout(getData,500,'person',event,this,['idpersona','tipo_doc','fecha_nacimiento','sexo']);Color('datos-lis');setTimeout(enabAfil,700,'regimen','eaf');setTimeout(enabEtni,700,'etnia','ocu','idi');setTimeout(enabLoca,700,'reside_localidad','lochi');setTimeout(EditOcup,800,'ocupacion','true');\"></li>";//setTimeout(enabEapb,700,'regimen','rgm');
		   $rutepsico = (acceso('rutePsico')) ? "<li class='icono asigna1' title='Asigna Psicologia-Ruteo' id='".$c['ACCIONES']."' Onclick=\"rutePsico('{$c['ACCIONES']}');Color('datos-lis');\"></li>" : "" ;
		   $rta.=$rutepsico;
   
		   $admision = (acceso('admision')) ? "<li class='icono admsi1' title='Crear Admisión' id='".$c['ACCIONES']."' Onclick=\"mostrar('admision','pro',event,'','admision.php',7,'admision');Color('datos-lis');\"></li>" : "" ;
		   $rta.=$admision;
   
		   $atencion = (acceso('atencion')) ? "<li class='icono aten1' title='Crear Atención' id='".$c['ACCIONES']."' Onclick=\"mostrar('atencion','pro',event,'','lib.php',7,'atencion');\"></li>" : "" ;
		   $rta.=$atencion;
   
		   if (perfil1()=='MEDATE' || perfil1()=='ADM' || perfil1()=='ENFATE'|| perfil1()=='ADMEAC' || perfil1()=='SUPEAC' || perfil1()=='RELENF' ){
		   //$rta.="<li class='icono admsi1' title='Crear Admisión' id='".$c['ACCIONES']."' Onclick=\"mostrar('admision','pro',event,'','admision.php',7,'admision');Color('datos-lis');\"></li>";
		   //$rta.="<li class='icono aten1' title='Crear Atención' id='".$c['ACCIONES']."' Onclick=\"mostrar('atencion','pro',event,'','lib.php',7,'atencion');\"></li>";//Color('datos-lis');
		   if($c['edad actual'] >= '0' && $c['edad actual'] <'6'){
			   $rta.="<li class='icono aterm1' title='PRIMERA INFANCIA' id='".$c['ACCIONES']."' Onclick=\"mostrar('prinfancia','pro',event,'','prinfancia.php',7,'prinfancia');Color('datos-lis');\"></li>";
		   }
		   if($c['edad actual'] > '5' && $c['edad actual'] <='11'){
			   $rta.="<li class='icono canin1' title='INFANCIA' id='".$c['ACCIONES']."' Onclick=\"mostrar('infancia','pro',event,'','infancia.php',7,'infancia');Color('datos-lis');\"></li>";
		   }else if($c['edad actual'] > '11' && $c['edad actual'] <='17'){
			   $rta.="<li class='icono adol1' title='ADOLESCENCIA' id='".$c['ACCIONES']."' Onclick=\"mostrar('adolesce','pro',event,'','adolescencia.php',7,'adolesce');Color('datos-lis');\"></li>";
		   }else if($c['edad actual'] > '17' && $c['edad actual'] <='28' ){
			   $rta.="<li class='icono juve1' title='JUVENTUD' id='".$c['ACCIONES']."' Onclick=\"mostrar('eac_juventud','pro',event,'','lib.php',7,'eac_juventud');Color('datos-lis');\"></li>";
		   }else if($c['edad actual'] > '28' && $c['edad actual'] <='59'){
			   $rta.="<li class='icono adul1' title='ADULTEZ' id='".$c['ACCIONES']."' Onclick=\"mostrar('eac_adultez','pro',event,'','lib.php',7,'eac_adultez');Color('datos-lis');\"></li>";
		   }else if($c['edad actual'] > '59' ){
			   $rta.="<li class='icono veje1' title='VEJEZ' id='".$c['ACCIONES']."' Onclick=\"mostrar('eac_vejez','pro',event,'','lib.php',7,'eac_vejez');Color('datos-lis');\"></li>";
		   }
		   
		   if(($c['edad actual'] > '10' && $c['edad actual'] <= '54') && $c['sexo'] == 'MUJER'){
			   $rta.= (!empty(get_condicion($c['ACCIONES'])) && get_condicion($c['ACCIONES'])['gestante']=='SI') ? "<li class='icono gesta1' title='GESTANTES' id='".$c['ACCIONES']."' Onclick=\"mostrar('pregnant','pro',event,'','gestantes.php',7,'pregnant');Color('datos-lis');setTimeout(hidFieOpt('gestante','ges_hide',this,true),2000);\"></li>" : '' ;
		   }
		   $rta.= (!empty(get_condicion($c['ACCIONES'])) && get_condicion($c['ACCIONES'])['cronico']=='SI') ? "<li class='icono cronic' title='Cronicos' id='".$c['ACCIONES']."' Onclick=\"mostrar('prechronic','pro',event,'','cronicos.php',7,'prechronic');Color('datos-lis');\"></li>" : '' ;
		   }
	   }
	   if($a=='atencion' && $b=='acciones'){
		   $rta="<nav class='menu right'>";
		   $rta.="<li class='icono editar ' title='Editar Atención' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,1000,'atencion',event,this,['idpersona','tipo_doc'],'atencion.php');setTimeout(getData,1300,'atencion',event,this,['idpersona','tipo_doc']);setTimeout(getData,1500,'atencion',event,this,['idpersona','tipo_doc']);setTimeout(changeSelect,1100,'letra1','rango1');setTimeout(changeSelect,1150,'letra2','rango2');setTimeout(changeSelect,1280,'letra3','rango3');setTimeout(changeSelect,1385,'rango1','diagnostico1');setTimeout(changeSelect,1385,'rango2','diagnostico2');setTimeout(changeSelect,1385,'rango3','diagnostico3');Color('datos-lis');\"></li>";	//
	   }
	   if($a=='planc-lis' && $b=='acciones'){
		   $rta="<nav class='menu right'>";		
		   $rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"Color('planc-lis');\"></li>";  //getData('plancon',event,this,'id');   act_lista(f,this);
	   }
	   /* if ($a=='admision-lis' && $b=='acciones'){
		   $rta="<nav class='menu right'>";		
			   $rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'admision',event,this,['fecha','tipo_activi'],'amb.php');\"></li>";  //   act_lista(f,this);
	   } */
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