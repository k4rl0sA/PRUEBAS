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

function opc_usuario(){
	$id=$_REQUEST['id'];
	$sql="SELECT hg.idgeo,FN_CATALOGODESC(72,hg.subred) AS subred,FN_CATALOGODESC(42,hg.estrategia) AS estrategia,u.nombre asignado,hg.equipo FROM hog_viv hv 
	LEFT JOIN hog_geo hg ON hv.idpre=hg.idgeo
	LEFT JOIN personas p ON hv.idviv=p.vivipersona
	LEFT JOIN usuarios u ON hg.asignado=u.id_usuario
	WHERE p.idpersona='".$id."' and hg.estado_v='7'";
// echo $sql;
	$info=datos_mysql($sql);
	if(isset($info['responseResult'][0])){ 
		return json_encode($info['responseResult'][0]);
	}else{
		return "[]";
	}
}

function lis_homes(){
    $total=" SELECT count(*) as total
	FROM hog_geo H
	LEFT JOIN derivacion D ON H.idgeo = D.cod_predio
WHERE 1 ".whe_homes()." AND estado_v in('7')";

	$info=datos_mysql($total);
	$total=$info['responseResult'][0]['total'];
	$regxPag=5;
	$pag=(isset($_POST['pag-homes']))? ($_POST['pag-homes']-1)* $regxPag:0;

	
	$sql="SELECT H.idgeo AS ACCIONES, H.idgeo AS Cod_Predio, FN_CATALOGODESC(42, H.estrategia) AS estrategia, H.direccion, H.territorio, H.sector_catastral AS Sector, H.nummanzana AS Manzana, H.predio_num AS predio, H.unidad_habit AS 'Unidad', FN_CATALOGODESC(2, H.localidad) AS 'Localidad', U.nombre AS nombre, H.fecha_create, FN_CATALOGODESC(44, H.estado_v) AS estado 
	FROM hog_geo H
	LEFT JOIN usuarios U ON H.asignado = U.id_usuario
    ".whe_deriva()."
 WHERE estado_v =('7') ".whe_homes()." 
	GROUP BY ACCIONES
	ORDER BY nummanzana, predio_num
    LIMIT $pag, $regxPag";
 //echo $sql;
		$datos=datos_mysql($sql);
	return create_table($total,$datos["responseResult"],"homes",$regxPag);
}

function whe_deriva(){
    $sql = "";
    if(!$_POST['fbinas']){
        $sql.=" LEFT JOIN derivacion D ON H.idgeo = D.cod_predio AND D.doc_asignado IN(SELECT id_usuario from usuarios where equipo=(select equipo from usuarios where id_usuario='{$_SESSION['us_sds']}'))";
    }else{
        $sql.=" LEFT JOIN derivacion D ON H.idgeo = D.cod_predio AND D.doc_asignado IN(SELECT id_usuario from usuarios where equipo='{$_POST['fbinas']}')";
    }
    return $sql;
}


function whe_homes() {
	$sql = " AND H.subred in(SELECT subred FROM usuarios WHERE id_usuario = '{$_SESSION['us_sds']}')";
	if (!empty($_POST['fpred'])) {
		$sql .= " AND H.idgeo = '" . $_POST['fpred'] . "'";
		if(!$_POST['fbinas']){
			$sql.=" AND (H.usu_creo='{$_SESSION['us_sds']}' OR H.equipo in(select equipo from usuarios where id_usuario = '{$_SESSION['us_sds']}') OR D.doc_asignado IN('{$_SESSION['us_sds']}'))";
		}else{
			$sql .=" AND H.equipo='".$_POST['fbinas']."'";
		}
	} else {
		if(!$_POST['fbinas']){
			$sql.=" AND (H.usu_creo='{$_SESSION['us_sds']}' OR H.equipo in(select equipo from usuarios where id_usuario = '{$_SESSION['us_sds']}') )";
		}else{
			$sql .=" AND H.equipo='".$_POST['fbinas']."'";
		} 
		if ($_POST['fdes']) {
			if ($_POST['fhas']) {
				$sql .= " AND DATE(H.fecha_create) BETWEEN '".$_POST['fdes']."' AND '".$_POST['fhas']."'";
			}else{
				$sql .= " AND DATE(H.fecha_create) BETWEEN '".$_POST['fdes']."' AND '".$_POST['fdes']."'";
			}
		}
	}
	return $sql;
}

function cap_menus($a,$b='cap',$con='con') {
  $rta = ""; 
  $acc=rol($a);
  if ($a=='homes' && isset($acc['crear']) && $acc['crear']=='SI') {  
//   $rta .= "<li class='icono crear'       title='Crear Nuevo'     Onclick=\"mostrar(mod,'pro');\"></li>"; 
  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
  $rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
   }
   if ($a=='person' && isset($acc['crear']) && $acc['crear']=='SI') {  
	$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
	 }
	 if ($a=='medidas' && isset($acc['crear']) && $acc['crear']=='SI') {  
		$rta .= "<li class='icono crear'       title='Crear Nuevo'     Onclick=\"mostrar(mod,'pro');\"></li>"; 
		$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
		$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
		 }
	 if ($a=='placuifam' && isset($acc['crear']) && $acc['crear']=='SI') {  
		$rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
		$rta .= "<li class='icono $a actualizar'  title='Actualizar'      Onclick=\"act_lista('$a',this);\"></li>";
		 }
  return $rta;
}


function lis_famili(){
	$cod=divide($_POST['id']);
	$sql="SELECT
	concat(idviv, '_', idpre) ACCIONES,
	idviv AS Cod_Familia,
	fecha,
	CONCAT_WS(' ', FN_CATALOGODESC(6, complemento1), nuc1, FN_CATALOGODESC(6, complemento2), nuc2, FN_CATALOGODESC(6, complemento3), nuc3) Complementos,
	numfam FAMILIA,
	fecha CARACTERIZACION,
	FN_CATALOGODESC(166,
	crit_epi) CRITERIO_EPIDEMIOLOGICO,
	V.fecha_create Creado,
	nombre Creó
FROM
	`hog_viv` V
left join usuarios P ON
	usu_creo = id_usuario
LEFT JOIN hog_geo hg ON V.idpre=hg.idgeo 
WHERE
	hg.estado_v=7 
	AND idpre=$cod[0]";
	$sql.=" ORDER BY V.fecha_create";
	// echo $sql;
		$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"famili-lis",10);
}
	

function cmp_homes1(){
	$rta="";
	$rta.="<div class='encabezado vivienda'>TABLA DE FAMILIAS POR VIVIENDA</div>
	<div class='contenido' id='famili-lis' >".lis_famili()."</div></div>";
	return $rta;
}

function cmp_homes(){
	$rta="";
	$hoy=date('Y-m-d');
	/* $t=['idviv'=>'','tipo_familia'=>'','vinculos'=>'','ingreso'=>'','seg_pre1'=>'','seg_pre2'=>'','seg_pre3'=>'','seg_pre4'=>'','seg_pre5'=>'','seg_pre6'=>'',
	'seg_pre7'=>'','seg_pre8'=>'','subsidio_1'=>'','subsidio_2'=>'','subsidio_3'=>'','subsidio_4'=>'','subsidio_5'=>'','subsidio_6'=>'','subsidio_7'=>'',
	'subsidio_8'=>'','subsidio_9'=>'','subsidio_10'=>'','subsidio_11'=>'','subsidio_12'=>'','subsidio_13'=>'','subsidio_14'=>'','subsidio_15'=>'',
	'subsidio_16'=>'','subsidio_17'=>'','subsidio_18'=>'','subsidio_19'=>'','subsidio_20'=>'','tipo_vivienda'=>'','tendencia'=>'','dormitorios'=>'',
	'personas'=>'','actividad_economica'=>'','energia'=>'','gas'=>'','acueducto'=>'','alcantarillado'=>'','basuras'=>'','pozo'=>'','perros'=>'','numero_perros'=>'',
	'perro_vacunas'=>'','perro_esterilizado'=>'','gatos'=>'','numero_gatos'=>'','gato_vacunas'=>'','gato_esterilizado'=>'','otros'=>'']; */
	$w='homes';
	// $d=get_vivienda(); 
	/* if ($d=="") {$d=$t;}*/
	// $u=($d['idviv']=='')?true:false;  
   	$d='';
	$o='inf';
	$n='fal';
	$days=fechas_app('vsp');
	$c[]=new cmp($o,'e',null,'INFORMACIÓN COMPLEMENTARIA DE LA VIVIENDA',$w);
	$c[]=new cmp('idg','h',15,$_POST['id'],$w.' '.$o,'id','idg',null,'####',false,false);
	$c[]=new cmp('numfam','s',3,$d,$w.' '.$o,'Número de Familia','numfam',null,'',true,true,'','col-2');
	$c[]=new cmp('fecha','d','10',$d,$w.' '.$o,'fecha Caracterización','fecha',null,'',true,true,'','col-2',"validDate(this,$days,0);");
	$c[]=new cmp('estado_aux','s','3',$d,$w.' '.$o,'Estado Visita','estado_aux',null,'',true,true,'','col-2','enabFielSele(this,true,[\'motivo_estaux\'],[\'4\']);stateVisit(this,[\'cri\',\'fam\',\'ali\',\'sub\',\'ser\',\'ani\',\'amb\',\'fal\']);');
	$c[]=new cmp('motivo_estaux','s','3',$d,$w.' '.$o,'Motivo','motivo_estaux',null,'',false,false,'','col-2');
	$c[]=new cmp('equipo_car','s','3',$d,$w.' '.$o,'Equipo Caracterizacion','equipo',null,'',true,true,'','col-2');
	$c[]=new cmp('fechaupd','d','10',$d,$w.' '.$o,'fecha Actualización','fechaupd',null,'',false,true,'','col-15',"validDate(this,$days,0);",array(''),"addupd(this,'hid','motivoupd');");
	$c[]=new cmp('motivoupd','s','3',$d,$w.' hid '.$o,'Motivo Actualización','motivoupd',null,'',false,false,'','col-3');
	$c[]=new cmp('eventoupd','s','3',$d,$w.' hid '.$o,'Evento Actualización','evenupd',null,'',false,false,'','col-3');
	$c[]=new cmp('fechanot','d','10',$d,$w.' hid '.$o,'fecha Notificación','fechanot',null,'',false,false,'','col-15',"validDate(this,$days,0);");
	$c[]=new cmp('equipo_act','s','3',$d,$w.' hid '.$o,'Equipo Actualizacion','equipo1',null,'',false,false,'','col-1');
	$c[]=new cmp('complemento1','s','3',$d,$w.' '.$o.' '.$n,'complemento1','complemento',null,'',true,true,'','col-2');
	$c[]=new cmp('nuc1','t','3',$d,$w.' '.$o.' '.$n,'nuc1','nuc1',null,'',true,true,'','col-1');
	$c[]=new cmp('complemento2','s','3',$d,$w.' '.$o.' '.$n,'complemento2','complemento',null,'',false,true,'','col-2');
	$c[]=new cmp('nuc2','t','3',$d,$w.' '.$o.' '.$n,'nuc2','nuc2',null,'',false,true,'','col-1');
	$c[]=new cmp('complemento3','s','3',$d,$w.' '.$o.' '.$n,'complemento3','complemento',null,'',false,true,'','col-2');
	$c[]=new cmp('nuc3','t','3',$d,$w.' '.$o.' '.$n,'nuc3','nuc3',null,'',false,true,'','col-2');
	$c[]=new cmp('telefono1','n','10',$d,$w.' '.$o.' '.$n,'telefono1','telefono1','rgxphone',NULL,true,true,'','col-25');
	$c[]=new cmp('telefono2','n','10',$d,$w.' '.$o.' '.$n,'telefono2','telefono2','rgxphone1',null,false,true,'','col-25');
	$c[]=new cmp('telefono3','n','10',$d,$w.' '.$o.' '.$n,'telefono3','telefono3','rgxphone1',null,false,true,'','col-25');
	
	$o='cri';
	$c[]=new cmp($o,'e',null,'CRITERIOS DE PRIORIZACIÓN',$w);
	$c[]=new cmp('crit_epi','s','3',$d,$w.' '.$o,'Criterio Epidemiológico','crit_epi',null,true,true,true,'','col-25');
	$c[]=new cmp('crit_geo','s','3',$d,$w.' '.$o,'Criterio Geográfico','crit_geo',null,true,true,true,'','col-25');
	$c[]=new cmp('estr_inters','s','3',$d,$w.' '.$o,'Estrategias Intersectoriales','estr_inters',null,true,true,true,'','col-25');
	$c[]=new cmp('fam_peretn','s','3',$d,$w.' '.$o,'Familias con Pertenencia Etnica','fam_peretn',null,true,true,true,'','col-25');
	$c[]=new cmp('fam_rurcer','s','3',$d,$w.' '.$o,'Familias de Ruralidad Cercana','fam_rurcer',null,true,true,true,'','col-25');
	
	$o='fam';
	$c[]=new cmp($o,'e',null,'INFORMACIÓN FAMILIAR',$w);
	$c[]=new cmp('tipo_vivienda','s','3',$d,$w.' '.$o,'Tipo de Vivienda','tipo_vivienda',null,null,true,true,'','col-25',"tipVivi('tipo_vivienda','bed');");
	$c[]=new cmp('tendencia','s','3',$d,$w.' '.$o,'Tenencia de la Vivienda','tendencia',null,null,true,true,'','col-25');
	$c[]=new cmp('dormitorios','n',2,$d,$w.' '.$o,'Número de dormitorios','dormitorios',null,null,true,true,'','col-25');
	$c[]=new cmp('actividad_economica','o',2,$d,$w.' '.$o,'Uso para  actividad económicas','actividad_economica',null,null,false,true,'','col-25');
	$c[]=new cmp('tipo_familia','s','3',$d,$w.' '.$o,'Tipo de Familia','tipo_familia',null,true,true,true,'','col-4');
	$c[]=new cmp('personas','n',2,$d,$w.' '.$o,'Número de personas','personas',null,null,true,true,'','col-2');
	$c[]=new cmp('ingreso','s','3',$d,$w.' '.$o,'Ingreso Economico de la Familia','ingreso',null,true,true,true,'','col-4');
	
	$o='ali';
	$c[]=new cmp($o,'e',null,'SEGURIDAD ALIMENTARIA',$w);
	$c[]=new cmp('seg_pre1','o',2,$d,$w.' '.$o,'¿Hubo alguna vez en que usted se haya preocupado por no tener suficientes alimentos para comer por falta de dinero u otros recursos?','sp1',null,null,false,true,'','col-10');
	$c[]=new cmp('seg_pre2','o',2,$d,$w.' '.$o,'¿Hubo alguna vez en que usted no haya podido comer alimentos saludables y nutritivos por falta de dinero u otros recursos?','sp2',null,null,false,true,'','col-10');
	$c[]=new cmp('seg_pre3','o',2,$d,$w.' '.$o,'¿Hubo alguna vez en que usted haya comido poca variedad de alimentos por falta de dinero u otros recursos?','sp3',null,null,false,true,'','col-10');
	$c[]=new cmp('seg_pre4','o',2,$d,$w.' '.$o,'¿Hubo alguna vez en que usted haya tenido que dejar de desayunar, almorzar o cenar porque no había suficiente dinero u otros recursos para obtener alimentos?','sp4',null,null,false,true,'','col-10');
	$c[]=new cmp('seg_pre5','o',2,$d,$w.' '.$o,'¿Hubo alguna vez en que usted haya comido menos de lo que pensaba que debía comer por falta de dinero u otros recursos?','sp5',null,null,false,true,'','col-10');
	$c[]=new cmp('seg_pre6','o',2,$d,$w.' '.$o,'¿Hubo alguna vez en que su hogar se haya quedado sin alimentos por falta de dinero u otros recursos?s','sp6',null,null,false,true,'','col-10');
	$c[]=new cmp('seg_pre7','o',2,$d,$w.' '.$o,'¿Hubo alguna vez en que usted haya sentido hambre, pero no comió porque no había suficiente dinero u otros recursos para obtener alimentos?','sp7',null,null,false,true,'','col-10');
	$c[]=new cmp('seg_pre8','o',2,$d,$w.' '.$o,'¿Hubo alguna vez en que usted haya dejado de comer todo un día por falta de dinero u otros recursos?','sp8',null,null,false,true,'','col-10');

	$o='sub';
	$c[]=new cmp($o,'e',null,'LA FAMILIA RECIBE ALGÚN SUBSIDIO O APORTE DE ALGUNA INSTITUCIÓN DE ORDEN NACIONAL O DISTRITAL',$w);
	$c[]=new cmp('subsidio_1','o',2,$d,$w.' '.$o,'SDIS – Desarrollo integral desde la gestación hasta la adolescencia- gestantes.','sb1',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_2','o',2,$d,$w.' '.$o,'SDIS – Envejecimiento digno, activo y feliz- adultez.','sb2',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_3','o',2,$d,$w.' '.$o,'SDIS – Desarrollo integral desde la gestación hasta la adolescencia- jardín infantil.','sb3',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_4','o',2,$d,$w.' '.$o,'SDIS – Desarrollo integral desde la gestación hasta la adolescencia- creciendo en familia.','sb4',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_5','o',2,$d,$w.' '.$o,'SDIS – Comprometidos por una alimentación integral- comedores.','sb5',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_6','o',2,$d,$w.' '.$o,'SDIS – Comprometidos por una alimentación integral   a,b,c y d - bono','sb6',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_7','o',2,$d,$w.' '.$o,'SDIS – Comprometidos por una alimentación integral - canasta básica rural.','sb7',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_8','o',2,$d,$w.' '.$o,'SDIS – Comprometidos por una alimentación integral cabildos indígenas.','sb8',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_9','o',2,$d,$w.' '.$o,'SDIS – Comprometidos por una alimentación integral - canasta básica afro.','sb9',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_10','o',2,$d,$w.' '.$o,'SDIS – Desarrollo integral desde la gestación hasta la adolescencia- centros amar.','sb10',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_11','o',2,$d,$w.' '.$o,'SDIS - Bono - Programa atención social (Emergencia)','sb11',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_12','o',2,$d,$w.' '.$o,'SDIS - Bono - Persona con discapacidad','sb12',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_13','o',2,$d,$w.' '.$o,'ICBF - CDI Familiar','sb13',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_14','o',2,$d,$w.' '.$o,'ICBF - CDI Institucional','sb14',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_15','o',2,$d,$w.' '.$o,'Secretaria de Hábitat - Caja de vivienda popular - Subsidio - Familias','sb15',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_16','o',2,$d,$w.' '.$o,'Alta Consejería para los derechos de las victimas, la paz y la reconciliación - Ayuda human. Inmediata. ','sb16',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_17','o',2,$d,$w.' '.$o,'ONGs - Subsidio - Familias','sb17',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_18','o',2,$d,$w.' '.$o,'Más familias en acción - Subsidio - Familias','sb18',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_19','o',2,$d,$w.' '.$o,'Red Unidos - Subsidio - Familias','sb19',null,null,false,true,'','col-10');
	$c[]=new cmp('subsidio_20','o',2,$d,$w.' '.$o,'SED - Subsidio - SECAE (Subsidio asistencia escolar)','sb20',null,null,false,true,'','col-10');

	$o='ser';
	$c[]=new cmp($o,'e',null,'ACCESO A SERVICIOS',$w);
	$c[]=new cmp('energia','o',2,$d,$w.' '.$o,'Energía Eléctrica','energia',null,null,false,true,'','col-15');
	$c[]=new cmp('gas','o',2,$d,$w.' '.$o,'Gas natural de red pública','gas',null,null,false,true,'','col-15');
	$c[]=new cmp('acueducto','o',2,$d,$w.' '.$o,'Acueducto','acueducto',null,null,false,true,'','col-15');
	$c[]=new cmp('alcantarillado','o',2,$d,$w.' '.$o,'Alcantarillado','alcantarilladio',null,null,false,true,'','col-15');
	$c[]=new cmp('basuras','o',2,$d,$w.' '.$o,'Recolección de basuras','basura',null,null,false,true,'','col-15');
	$c[]=new cmp('pozo','o',2,$d,$w.' '.$o,'pozo','pozo',null,null,false,true,'','col-15');
	$c[]=new cmp('aljibe','o',2,$d,$w.' '.$o,'aljibe','aljibe',null,null,false,true,'','col-1');

	$o='ani';
	$c[]=new cmp($o,'e',null,'CONVIVENCIA CON ANIMALES',$w);
	$c[]=new cmp('perros','o',2,$d,$w.' '.$o,'Perros','perros',null,null,false,true,'','col-4','enableDog(this,\'dog\')');
	$c[]=new cmp('numero_perros','n',2,$d,$w.' dog '.$o,'N° Perros','numero_perros',null,null,false,false,'','col-2');
	$c[]=new cmp('perro_vacunas','n',2,$d,$w.' dog '.$o,'Perros no vacunados','perro_vacunas',null,null,false,false,'','col-2');
	$c[]=new cmp('perro_esterilizado','n',2,$d,$w.' dog '.$o,'Perros no esterilizados.','perro_esterilizado',null,null,false,false,'','col-2');
	$c[]=new cmp('gatos','o',2,$d,$w.' '.$o,'Gatos. ','gatos',null,null,false,true,'','col-4','enableCat(this,\'cat\')');
	$c[]=new cmp('numero_gatos','n',2,$d,$w.' cat '.$o,'N° Gatos','numero_gatos',null,null,false,false,'','col-2');
	$c[]=new cmp('gato_vacunas','n',2,$d,$w.' cat '.$o,'Gatos no vacunados.','gato_vacunas',null,null,false,false,'','col-2');
	$c[]=new cmp('gato_esterilizado','n',2,$d,$w.' cat '.$o,'Gatos no esterilizados.','gato_esterilizado',null,null,false,false,'','col-2');
	$c[]=new cmp('otros','a',2,$d,$w.' '.$o,'otros','otros',null,null,false,true,'','col-10');
	 
	$o='amb';
	$c[]=new cmp($o,'e',null,'FACTORTES AMBIENTALES',$w);
	$c[]=new cmp('factor_1','o',2,$d,$w.' '.$o,'A menos de 100 metros o a una cuadra de la vivienda hay circulación de tráfico pesado','fm1',null,null,false,true,'','col-10');
	$c[]=new cmp('factor_2','o',2,$d,$w.' '.$o,'Edificaciones o vías en construcción o vías no pavimentadas a menos de una cuadra o 100 metros.','fm2',null,null,false,true,'','col-10');
	$c[]=new cmp('factor_3','o',2,$d,$w.' '.$o,'Cercanía de la vivienda a zonas recreativas, zonas verdes y/o de esparcimiento.','fm3',null,null,false,true,'','col-10');
	$c[]=new cmp('factor_4','o',2,$d,$w.' '.$o,'Cercanía a la vivienda relleno sanitario, rondas hídricas, canales, cementerios, humedales, terminales aéreos o terrestres','fm4',null,null,false,true,'','col-10');
	$c[]=new cmp('factor_5','o',2,$d,$w.' '.$o,'En la vivienda se almacena y conserva los alimentos de forma adecuada','fm5',null,null,false,true,'','col-10');
	$c[]=new cmp('factor_6','o',2,$d,$w.' '.$o,'En la vivienda se manipula adecuadamente agua para consumo humano (desinfección adecuada, uso seguro de utensilios)','fm6',null,null,false,true,'','col-10');
	$c[]=new cmp('factor_7','o',2,$d,$w.' '.$o,'Las personas que habitan en la vivienda adquieren medicamentos con fórmula médica','fm7',null,null,false,true,'','col-10');
	$c[]=new cmp('factor_8','o',2,$d,$w.' '.$o,'En la vivienda los productos quimicos estan almacenados de manera segura','fm8',null,null,false,true,'','col-10');
	$c[]=new cmp('factor_9','o',2,$d,$w.' '.$o,'En la vivienda se realiza adecuado manejo de residuos sólidos','fm9',null,null,false,true,'','col-10');

	$c[]=new cmp('observacion','a',1500,$d,$w.'','Observacion','observacion',null,null,true,true,'','col-10');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
}
function opc_motivoupd($id=''){
	return opc_sql("SELECT `idcatadeta`,concat(idcatadeta,' - ',descripcion) FROM `catadeta` WHERE idcatalogo=215 and estado='A' ORDER BY 1",$id);
}
function opc_evenupd($id=''){
	return opc_sql("SELECT `idcatadeta`,concat(idcatadeta,' - ',descripcion) FROM `catadeta` WHERE idcatalogo=87 and estado='A' ORDER BY 1",$id);
}

function opc_numfam($id=''){
	return opc_sql("SELECT `idcatadeta`,concat(descripcion,' - ',idcatadeta) FROM `catadeta` WHERE idcatalogo=172 and estado='A' ORDER BY 1",$id);
}
function opc_motivo_estaux($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=171 and estado='A' ORDER BY 1",$id);
}
function opc_estr_inters($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=168 and estado='A' ORDER BY LPAD(idcatadeta, 2, '0')",$id);
}
function opc_crit_epi($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=166 and estado='A' ORDER BY LPAD(idcatadeta, 2, '0')",$id);
}
function opc_crit_geo($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=167 and estado='A' ORDER BY LPAD(idcatadeta, 2, '0')",$id);
}
function opc_fam_peretn($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=169 and estado='A' ORDER BY LPAD(idcatadeta, 2, '0')",$id);
}
function opc_fam_rurcer($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY LPAD(idcatadeta, 2, '0')",$id);
}
function opc_estado_aux($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=165 and estado='A' ORDER BY 1",$id);
}
function opc_tenDencia($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=8 and estado='A' ORDER BY 1",$id);
}
function opc_tipo_vivienda($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=4 and estado='A' ORDER BY 1",$id);
}
function opc_tipo_familia($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=10 and estado='A' ORDER BY 1",$id);
}
function opc_complemento($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=6 and estado='A' ORDER BY 1",$id);
}
function opc_ingreso($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=13 and estado='A' ORDER BY 1",$id);
}
function opc_encuentra($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
}

function get_homes(){
	if($_REQUEST['id']==''){
		return "";
	}else{
		$id=divide($_REQUEST['id']);
		$sql="SELECT concat(idviv,'_',idpre) id,numfam,fecha,estado_aux,motivo_estaux,equipo_car,fechaupd,motivoupd,eventoupd,fechanot,equipo_act,complemento1,nuc1,complemento2,nuc2,complemento3,nuc3,telefono1,telefono2,telefono3,crit_epi,crit_geo,estr_inters,fam_peretn,fam_rurcer,tipo_vivienda,tendencia,dormitorios,actividad_economica,tipo_familia,personas,ingreso,seg_pre1,seg_pre2,seg_pre3,seg_pre4,seg_pre5,seg_pre6,seg_pre7,seg_pre8,subsidio_1,subsidio_2,subsidio_3,subsidio_4,subsidio_5,subsidio_6,subsidio_7,subsidio_8,subsidio_9,subsidio_10,subsidio_11,subsidio_12,subsidio_13,subsidio_14,subsidio_15,subsidio_16,subsidio_17,subsidio_18,subsidio_19,subsidio_20,energia,gas,acueducto,alcantarillado,basuras,pozo,aljibe,perros,numero_perros,perro_vacunas,perro_esterilizado,gatos,numero_gatos,gato_vacunas,gato_esterilizado,otros,facamb1,facamb2,facamb3,facamb4,facamb5,facamb6,facamb7,facamb8,facamb9,observacion,asignado
		FROM `hog_viv` 
		WHERE idviv =$id[0] AND idpre=$id[1]";
		// echo $sql;
		// print_r($id);
		$info=datos_mysql($sql);
		return json_encode($info['responseResult'][0]);
	} 
}

function focus_homes(){
	return 'homes';
   }
   
function focus_homes1(){
	return 'homes1';
}
function men_homes(){
	$rta=cap_menus('homes','pro');
	return $rta;
}
function men_homes1(){
	$rta=cap_menus('homes1','fix');
	return $rta;
}
   
function gra_homes(){
	$id=divide($_POST['idg']);
	//print_r($_POST);
	$sql1="SELECT  CONCAT_WS('_', H.estrategia, H.sector_catastral, H.nummanzana, H.predio_num, H.unidad_habit, H.estado_v) COD FROM hog_geo H where idgeo='$id[0]'";
	$info=datos_mysql($sql1);
	$cod=$info['responseResult'][0]['COD'];//REVISAR
	$perros = empty($_POST['numero_perros']) ? 0 :$_POST['numero_perros'];
	$pvacun = empty($_POST['perro_vacunas']) ? 0 :$_POST['perro_vacunas'];
	$peste  = empty($_POST['perro_esterilizado']) ? 0:$_POST['perro_esterilizado'];
	$gatos  = empty($_POST['numero_gatos']) ? 0 : $_POST['numero_gatos'];
	$gvacun = empty($_POST['gato_vacunas']) ? 0 : $_POST['gato_vacunas'];
	$geste  = empty($_POST['gato_esterilizado']) ? 0:$_POST['gato_esterilizado'];
	if(count($id)==2){
		$sql1="SELECT fecha fecCar,fechaupd fecUpd FROM hog_viv H where idviv='$id[0]'";
		$info=datos_mysql($sql1);
		$fechaUpd=$info['responseResult'][0]['fecUpd'];
		$fechaCar=$info['responseResult'][0]['fecCar'];

		if($fechaUpd=='' || $fechaUpd=='0000-00-00' ){
			$sql="UPDATE `hog_viv` SET
			numfam=TRIM(UPPER('{$_POST['numfam']}')),
			`fecha`=TRIM(UPPER('{$_POST['fecha']}')),
			estado_aux=trim(upper('{$_POST['estado_aux']}')),motivo_estaux=trim(upper('{$_POST['motivo_estaux']}')),fechaupd=trim(upper('{$_POST['fechaupd']}')),motivoupd=trim(upper('{$_POST['motivoupd']}')),eventoupd=trim(upper('{$_POST['eventoupd']}')),fechanot=trim(upper('{$_POST['fechanot']}')),crit_epi=trim(upper('{$_POST['crit_epi']}')),crit_geo=trim(upper('{$_POST['crit_geo']}')),estr_inters=trim(upper('{$_POST['estr_inters']}')),fam_peretn=trim(upper('{$_POST['fam_peretn']}')),fam_rurcer=trim(upper('{$_POST['fam_rurcer']}')),complemento1=trim(upper('{$_POST['complemento1']}')),nuc1=trim(upper('{$_POST['nuc1']}')),complemento2=trim(upper('{$_POST['complemento2']}')),nuc2=trim(upper('{$_POST['nuc2']}')),complemento3=trim(upper('{$_POST['complemento3']}')),nuc3=trim(upper('{$_POST['nuc3']}')),telefono1=trim(upper('{$_POST['telefono1']}')),telefono2=trim(upper('{$_POST['telefono2']}')),telefono3=trim(upper('{$_POST['telefono3']}')),tipo_familia=trim(upper('{$_POST['tipo_familia']}')),ingreso=trim(upper('{$_POST['ingreso']}')),seg_pre1=trim(upper('{$_POST['seg_pre1']}')),seg_pre2=trim(upper('{$_POST['seg_pre2']}')),seg_pre3=trim(upper('{$_POST['seg_pre3']}')),seg_pre4=trim(upper('{$_POST['seg_pre4']}')),seg_pre5=trim(upper('{$_POST['seg_pre5']}')),seg_pre6=trim(upper('{$_POST['seg_pre6']}')),seg_pre7=trim(upper('{$_POST['seg_pre7']}')),seg_pre8=trim(upper('{$_POST['seg_pre8']}')),subsidio_1=trim(upper('{$_POST['subsidio_1']}')),subsidio_2=trim(upper('{$_POST['subsidio_2']}')),subsidio_3=trim(upper('{$_POST['subsidio_3']}')),subsidio_4=trim(upper('{$_POST['subsidio_4']}')),subsidio_5=trim(upper('{$_POST['subsidio_5']}')),subsidio_6=trim(upper('{$_POST['subsidio_6']}')),subsidio_7=trim(upper('{$_POST['subsidio_7']}')),subsidio_8=trim(upper('{$_POST['subsidio_8']}')),subsidio_9=trim(upper('{$_POST['subsidio_9']}')),subsidio_10=trim(upper('{$_POST['subsidio_10']}')),subsidio_11=trim(upper('{$_POST['subsidio_11']}')),subsidio_12=trim(upper('{$_POST['subsidio_12']}')),subsidio_13=trim(upper('{$_POST['subsidio_13']}')),subsidio_14=trim(upper('{$_POST['subsidio_14']}')),subsidio_15=trim(upper('{$_POST['subsidio_15']}')),subsidio_16=trim(upper('{$_POST['subsidio_16']}')),subsidio_17=trim(upper('{$_POST['subsidio_17']}')),subsidio_18=trim(upper('{$_POST['subsidio_18']}')),subsidio_19=trim(upper('{$_POST['subsidio_19']}')),subsidio_20=trim(upper('{$_POST['subsidio_20']}')),tipo_vivienda=trim(upper('{$_POST['tipo_vivienda']}')),tendencia=trim(upper('{$_POST['tendencia']}')),dormitorios=trim(upper('{$_POST['dormitorios']}')),personas=trim(upper('{$_POST['personas']}')),actividad_economica=trim(upper('{$_POST['actividad_economica']}')),energia=trim(upper('{$_POST['energia']}')),gas=trim(upper('{$_POST['gas']}')),acueducto=trim(upper('{$_POST['acueducto']}')),alcantarillado=trim(upper('{$_POST['alcantarillado']}')),basuras=trim(upper('{$_POST['basuras']}')),pozo=trim(upper('{$_POST['pozo']}')),aljibe=trim(upper('{$_POST['aljibe']}')),perros=trim(upper('{$_POST['perros']}')),numero_perros=trim(upper('{$_POST['numero_perros']}')),perro_vacunas=trim(upper('{$_POST['perro_vacunas']}')),perro_esterilizado=trim(upper('{$_POST['perro_esterilizado']}')),gatos=trim(upper('{$_POST['gatos']}')),numero_gatos=trim(upper('{$_POST['numero_gatos']}')),gato_vacunas=trim(upper('{$_POST['gato_vacunas']}')),gato_esterilizado=trim(upper('{$_POST['gato_esterilizado']}')),otros=trim(upper('{$_POST['otros']}')),facamb1=trim(upper('{$_POST['factor_1']}')),facamb2=trim(upper('{$_POST['factor_2']}')),facamb3=trim(upper('{$_POST['factor_3']}')),facamb4=trim(upper('{$_POST['factor_4']}')),facamb5=trim(upper('{$_POST['factor_5']}')),facamb6=trim(upper('{$_POST['factor_6']}')),facamb7=trim(upper('{$_POST['factor_7']}')),facamb8=trim(upper('{$_POST['factor_8']}')),facamb9=trim(upper('{$_POST['factor_9']}')),observacion=trim(upper('{$_POST['observacion']}')),
			equipo_car=trim(upper('{$_POST['equipo_car']}')),equipo_act=trim(upper('{$_POST['equipo_act']}')),
			`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
			WHERE idviv='{$id[0]}'";
		}else{
			$sql="UPDATE `hog_viv` SET
			crit_epi=trim(upper('{$_POST['crit_epi']}')),crit_geo=trim(upper('{$_POST['crit_geo']}')),estr_inters=trim(upper('{$_POST['estr_inters']}')),fam_peretn=trim(upper('{$_POST['fam_peretn']}')),fam_rurcer=trim(upper('{$_POST['fam_rurcer']}')),complemento1=trim(upper('{$_POST['complemento1']}')),nuc1=trim(upper('{$_POST['nuc1']}')),complemento2=trim(upper('{$_POST['complemento2']}')),nuc2=trim(upper('{$_POST['nuc2']}')),complemento3=trim(upper('{$_POST['complemento3']}')),nuc3=trim(upper('{$_POST['nuc3']}')),telefono1=trim(upper('{$_POST['telefono1']}')),telefono2=trim(upper('{$_POST['telefono2']}')),telefono3=trim(upper('{$_POST['telefono3']}')),tipo_familia=trim(upper('{$_POST['tipo_familia']}')),ingreso=trim(upper('{$_POST['ingreso']}')),seg_pre1=trim(upper('{$_POST['seg_pre1']}')),seg_pre2=trim(upper('{$_POST['seg_pre2']}')),seg_pre3=trim(upper('{$_POST['seg_pre3']}')),seg_pre4=trim(upper('{$_POST['seg_pre4']}')),seg_pre5=trim(upper('{$_POST['seg_pre5']}')),seg_pre6=trim(upper('{$_POST['seg_pre6']}')),seg_pre7=trim(upper('{$_POST['seg_pre7']}')),seg_pre8=trim(upper('{$_POST['seg_pre8']}')),subsidio_1=trim(upper('{$_POST['subsidio_1']}')),subsidio_2=trim(upper('{$_POST['subsidio_2']}')),subsidio_3=trim(upper('{$_POST['subsidio_3']}')),subsidio_4=trim(upper('{$_POST['subsidio_4']}')),subsidio_5=trim(upper('{$_POST['subsidio_5']}')),subsidio_6=trim(upper('{$_POST['subsidio_6']}')),subsidio_7=trim(upper('{$_POST['subsidio_7']}')),subsidio_8=trim(upper('{$_POST['subsidio_8']}')),subsidio_9=trim(upper('{$_POST['subsidio_9']}')),subsidio_10=trim(upper('{$_POST['subsidio_10']}')),subsidio_11=trim(upper('{$_POST['subsidio_11']}')),subsidio_12=trim(upper('{$_POST['subsidio_12']}')),subsidio_13=trim(upper('{$_POST['subsidio_13']}')),subsidio_14=trim(upper('{$_POST['subsidio_14']}')),subsidio_15=trim(upper('{$_POST['subsidio_15']}')),subsidio_16=trim(upper('{$_POST['subsidio_16']}')),subsidio_17=trim(upper('{$_POST['subsidio_17']}')),subsidio_18=trim(upper('{$_POST['subsidio_18']}')),subsidio_19=trim(upper('{$_POST['subsidio_19']}')),subsidio_20=trim(upper('{$_POST['subsidio_20']}')),tipo_vivienda=trim(upper('{$_POST['tipo_vivienda']}')),tendencia=trim(upper('{$_POST['tendencia']}')),dormitorios=trim(upper('{$_POST['dormitorios']}')),personas=trim(upper('{$_POST['personas']}')),actividad_economica=trim(upper('{$_POST['actividad_economica']}')),energia=trim(upper('{$_POST['energia']}')),gas=trim(upper('{$_POST['gas']}')),acueducto=trim(upper('{$_POST['acueducto']}')),alcantarillado=trim(upper('{$_POST['alcantarillado']}')),basuras=trim(upper('{$_POST['basuras']}')),pozo=trim(upper('{$_POST['pozo']}')),aljibe=trim(upper('{$_POST['aljibe']}')),perros=trim(upper('{$_POST['perros']}')),numero_perros=trim(upper('{$_POST['numero_perros']}')),perro_vacunas=trim(upper('{$_POST['perro_vacunas']}')),perro_esterilizado=trim(upper('{$_POST['perro_esterilizado']}')),gatos=trim(upper('{$_POST['gatos']}')),numero_gatos=trim(upper('{$_POST['numero_gatos']}')),gato_vacunas=trim(upper('{$_POST['gato_vacunas']}')),gato_esterilizado=trim(upper('{$_POST['gato_esterilizado']}')),otros=trim(upper('{$_POST['otros']}')),facamb1=trim(upper('{$_POST['factor_1']}')),facamb2=trim(upper('{$_POST['factor_2']}')),facamb3=trim(upper('{$_POST['factor_3']}')),facamb4=trim(upper('{$_POST['factor_4']}')),facamb5=trim(upper('{$_POST['factor_5']}')),facamb6=trim(upper('{$_POST['factor_6']}')),facamb7=trim(upper('{$_POST['factor_7']}')),facamb8=trim(upper('{$_POST['factor_8']}')),facamb9=trim(upper('{$_POST['factor_9']}')),observacion=trim(upper('{$_POST['observacion']}')),
			`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
			WHERE idviv='{$id[0]}'";
		}
	}elseif(count($id)==1){
		$sql="INSERT INTO hog_viv VALUES (null,
		{$id[0]},
		TRIM(UPPER('{$cod}')),
		TRIM(UPPER('{$_POST['numfam']}')),
		TRIM(UPPER('{$_POST['fecha']}')),
		TRIM(UPPER('{$_POST['estado_aux']}')),
		TRIM(UPPER('{$_POST['motivo_estaux']}')),
		TRIM(UPPER('{$_POST['equipo_car']}')),
		null,null,null,null,NULL,
		trim(upper('{$_POST['complemento1']}')),trim(upper('{$_POST['nuc1']}')),
		trim(upper('{$_POST['complemento2']}')),trim(upper('{$_POST['nuc2']}')),
		trim(upper('{$_POST['complemento3']}')),trim(upper('{$_POST['nuc3']}')),
		trim(upper('{$_POST['telefono1']}')),trim(upper('{$_POST['telefono2']}')),
		trim(upper('{$_POST['telefono3']}')),
		trim(upper('{$_POST['crit_epi']}')),trim(upper('{$_POST['crit_geo']}')),
		trim(upper('{$_POST['estr_inters']}')),trim(upper('{$_POST['fam_peretn']}')),trim(upper('{$_POST['fam_rurcer']}')),
		trim(upper('{$_POST['tipo_vivienda']}')),trim(upper('{$_POST['tendencia']}')),trim(upper('{$_POST['dormitorios']}')),trim(upper('{$_POST['actividad_economica']}')),trim(upper('{$_POST['tipo_familia']}')),trim(upper('{$_POST['personas']}')),trim(upper('{$_POST['ingreso']}')),trim(upper('{$_POST['seg_pre1']}')),trim(upper('{$_POST['seg_pre2']}')),trim(upper('{$_POST['seg_pre3']}')),trim(upper('{$_POST['seg_pre4']}')),trim(upper('{$_POST['seg_pre5']}')),trim(upper('{$_POST['seg_pre6']}')),trim(upper('{$_POST['seg_pre7']}')),trim(upper('{$_POST['seg_pre8']}')),
		trim(upper('{$_POST['subsidio_1']}')),trim(upper('{$_POST['subsidio_2']}')),trim(upper('{$_POST['subsidio_3']}')),trim(upper('{$_POST['subsidio_4']}')),trim(upper('{$_POST['subsidio_5']}')),trim(upper('{$_POST['subsidio_6']}')),trim(upper('{$_POST['subsidio_7']}')),trim(upper('{$_POST['subsidio_8']}')),trim(upper('{$_POST['subsidio_9']}')),trim(upper('{$_POST['subsidio_10']}')),trim(upper('{$_POST['subsidio_11']}')),trim(upper('{$_POST['subsidio_12']}')),trim(upper('{$_POST['subsidio_13']}')),trim(upper('{$_POST['subsidio_14']}')),trim(upper('{$_POST['subsidio_15']}')),trim(upper('{$_POST['subsidio_16']}')),trim(upper('{$_POST['subsidio_17']}')),trim(upper('{$_POST['subsidio_18']}')),trim(upper('{$_POST['subsidio_19']}')),trim(upper('{$_POST['subsidio_20']}')),
		trim(upper('{$_POST['energia']}')),trim(upper('{$_POST['gas']}')),trim(upper('{$_POST['acueducto']}')),trim(upper('{$_POST['alcantarillado']}')),trim(upper('{$_POST['basuras']}')),trim(upper('{$_POST['pozo']}')),trim(upper('{$_POST['aljibe']}')),
		trim(upper('{$_POST['perros']}')),$perros,$pvacun,$peste,TRIM(UPPER('{$_POST['gatos']}')),$gatos,$gvacun,$geste,
		trim(upper('{$_POST['otros']}')),trim(upper('{$_POST['factor_1']}')),trim(upper('{$_POST['factor_2']}')),trim(upper('{$_POST['factor_3']}')),trim(upper('{$_POST['factor_4']}')),trim(upper('{$_POST['factor_5']}')),trim(upper('{$_POST['factor_6']}')),trim(upper('{$_POST['factor_7']}')),trim(upper('{$_POST['factor_8']}')),trim(upper('{$_POST['factor_9']}')),trim(upper('{$_POST['observacion']}')),	
		NULL,TRIM(UPPER('{$_SESSION['us_sds']}')),       DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A');";
		// echo $sql;
	}
	  $rta=dato_mysql($sql);
	//   return "correctamente";
	  return $rta;
	}



// INICIO FORMULARIO INTEGRANTES DE LA FAMILIA


function cmp_person1(){
	$rta="";
	$rta .="<div class='encabezado vivienda'>TABLA DE INTEGRANTES FAMILIA</div>
	<div class='contenido' id='datos-lis' >".lista_persons()."</div></div>";
	return $rta;
} 


function cmp_person(){
	$rta="";
	/* $rta .="<div class='encabezado vivienda'>TABLA DE INTEGRANTES FAMILIA</div>
	<div class='contenido' id='datos-lis' >".lista_persons()."</div></div>"; */
	// $t=['anos'=>0];
	$hoy=date('Y-m-d');
	// $p=get_edad();
    $w="person";
	// if ($p==""){$p=$t;}
	/* $ocu= ($p['anos']>5) ? true : false ; */
	/* $t=['vivipersona'=>'','idpersona'=>'','tipo_doc'=>'','nombre1'=>'','nombre2'=>'','apellido1'=>'','apellido2'=>'','fecha_nacimiento'=>'','sexo'=>'','genero'=>'','nacionalidad'=>'','discapacidad'=>'','etnia'=>'','pueblo'=>'','idioma'=>'','regimen'=>'','eapb'=>'','localidad'=>'','upz'=>'','direccion'=>'','telefono1'=>'','telefono2'=>'','telefono3'=>''];$w='person';
	$d=get_person(); 
	if ($d=="") {$d=$t;}$u=($d['vivipersona']=='')?true:false; */
	$d='';
	$o='infgen';
	// print_r($_POST);
	$c[]=new cmp($o,'e',null,'INFORMACIÓN GENERAL',$w);
	$c[]=new cmp('idp','h',15,$_POST['id'],$w.' '.$o,'id','id',null,'####',false,false);
	$c[]=new cmp('encuentra','s','2',$d,$w.' '.$o,'El usuario se encuentra','encuentra',null,null,true,true,'','col-2');
	$c[]=new cmp('idpersona','n','18',$d,$w.' '.$o,'Identificación','idpersona',null,null,true,true,'','col-4');
	$c[]=new cmp('tipo_doc','s','3',$d,$w.' '.$o,'Tipo documento','tipo_doc',null,null,true,true,'','col-4');
	$c[]=new cmp('nombre1','t','30',$d,$w.' '.$o,'Primer Nombre','nombre1',null,null,true,true,'','col-2');
	$c[]=new cmp('nombre2','t','30',$d,$w.' '.$o,'Segundo Nombre','nombre2',null,null,false,true,'','col-2');
	$c[]=new cmp('apellido1','t','30',$d,$w.' '.$o,'Primer Apellido','apellido1',null,null,true,true,'','col-2');
	$c[]=new cmp('apellido2','t','30',$d,$w.' '.$o,'Segundo Apellido','apellido2',null,null,false,true,'','col-2');
	$c[]=new cmp('fecha_nacimiento','d','',$d,$w.' '.$o,'Fecha de nacimiento','fecha_nacimiento',null,null,true,true,'','col-2',"validDate(this,-43800,0);",[],"child14('fecha_nacimiento','osx');Ocup5('fecha_nacimiento','OcU');");
	$c[]=new cmp('sexo','s','3',$d,$w.' '.$o,'Sexo','sexo',null,null,true,true,'','col-2');
	$c[]=new cmp('genero','s','3',$d,$w.' '.$o,'Genero','genero',null,null,true,true,'','col-2');
	$c[]=new cmp('oriensexual','s','3',$d,$w.' osx '.$o,'Orientacion Sexual','oriensexual',null,null,true,true,'','col-2');
	$c[]=new cmp('nacionalidad','s','3',$d,$w.' '.$o,'nacionalidad','nacionalidad',null,null,true,true,'','col-2');
	$c[]=new cmp('estado_civil','s','3',$d,$w.' '.$o,'Estado Civil','estado_civil',null,null,true,true,'','col-2');
	$c[]=new cmp('niveduca','s','3',$d,$w.' '.$o,'Nivel Educativo','niveduca',null,'',true,true,'','col-25',"enabDesEsc('niveduca','aE',fecha_nacimiento);");//true
	$c[]=new cmp('abanesc','s','3',$d,$w.' aE '.$o,'Razón del abandono Escolar','abanesc',null,'',false,false,'','col-25');
	$c[]=new cmp('ocupacion','s','3',$d,$w.' OcU '.$o,'Ocupacion','ocupacion',null,'',false,false,'','col-25',"timeDesem(this,'des');");//true
	$c[]=new cmp('tiemdesem','n','3',$d,$w.' des '.$o,'Tiempo de desempleo (Meses)','tiemdesem',null,'',false,false,'','col-25');
	$c[]=new cmp('vinculo_jefe','s','3',$d,$w.' '.$o,'Vinculo con el jefe del Hogar','vinculo_jefe',null,null,true,true,'','col-2');
	$c[]=new cmp('etnia','s','3',$d,$w.' '.$o,'Pertenencia Etnica','etnia',null,null,true,true,'','col-2',"enabEtni('etnia','ocu','idi');");
	$c[]=new cmp('pueblo','s','50',$d,$w.' ocu cmhi '.$o,'pueblo','pueblo',null,null,false,true,'','col-2');
	$c[]=new cmp('idioma','o','2',$d,$w.' ocu cmhi idi '.$o,'Habla Español','idioma',null,null,false,true,'','col-2');
	$c[]=new cmp('discapacidad','s','3',$d,$w.' '.$o,'discapacidad','discapacidad',null,null,true,true,'','col-2');
	$c[]=new cmp('regimen','s','3',$d,$w.' '.$o,'regimen','regimen',null,null,true,true,'','col-2','enabAfil(\'regimen\',\'eaf\');enabEapb(\'regimen\',\'rgm\');');//enabEapb(\'regimen\',\'reg\');
	$c[]=new cmp('eapb','s','3',$d,$w.' rgm '.$o,'eapb','eapb',null,null,true,true,'','col-2');
	$c[]=new cmp('afiliacion','o','2',$d,$w.' eaf cmhi '.$o,'¿Esta interesado en afiliación por oficio?','afiliacion',null,null,false,true,'','col-2');
	$c[]=new cmp('sisben','s','3',$d,$w.' '.$o,'Grupo Sisben','sisben',null,null,true,true,'','col-2');
	$c[]=new cmp('catgosisb','n','2',$d,$w.' '.$o,'Categoria Sisben','catgosisb','rgxsisben',null,true,true,'','col-2');
	$c[]=new cmp('pobladifer','s','3',$d,$w.' '.$o,'Poblacion Direferencial y de Inclusión','pobladifer',null,'',true,true,'','col-2');
	$c[]=new cmp('incluofici','s','3',$d,$w.' '.$o,'Población Inclusion por Oficio','incluofici',null,'',true,true,'','col-2');
	
	$o='relevo';
	$c[]=new cmp('cuidador','o','2',$d,$w.' '.$o,'¿Es cuidador de una personacuida residente en la vivienda?','cuidador',null,null,true,true,'','col-25',"enabYes('cuidador','cUi');");
	
	$c[]=new cmp('perscuidada','s','3',$d,$w.' cUi '.$o,'N° de identificacion y Nombres','cuida',null,null,false,false,'','col-35');
	$c[]=new cmp('tiempo_cuidador','n','20',$d,$w.' cUi '.$o,'¿Por cuánto tiempo ha sido cuidador?','tiempo_cuidador',null,null,false,false,'','col-2');
	$c[]=new cmp('cuidador_unidad','s','3',$d,$w.' cUi '.$o,'Unidad de medida tiempo cuidador','cuidador_unidad',null,null,false,false,'','col-2');
	$c[]=new cmp('vinculo_cuida','s','3',$d,$w.' cUi '.$o,'Vinculo con la persona cuidada','vinculo_cuida',null,null,false,false,'','col-2');
	$c[]=new cmp('tiempo_descanso','n','20',$d,$w.' cUi '.$o,'¿Cada cuánto descansa?','tiempo_descanso',null,null,false,false,'','col-2');
	$c[]=new cmp('descanso_unidad','s','3',$d,$w.' cUi '.$o,'Unidad de medida tiempo descanso','descanso_unidad',null,null,false,false,'','col-2');
	
	$c[]=new cmp('reside_localidad','o','2',$d,$w.' cUi '.$o,'Reside en la localidad','reside_localidad',null,null,false,false,'','col-3',"enabLoca('reside_localidad','lochi');");
	$c[]=new cmp('localidad_vive','s','3',$d,$w.' lochi cUi '.$o,'¿En qué localidad vive?','localidad_vive',null,null,false,false,'','col-3');
	$c[]=new cmp('transporta','s','3',$d,$w.' lochi cUi  '.$o,'¿En que se transporta?','transporta',null,null,false,false,'','col-4');
	
	
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	return $rta;
   }

   function get_edad(){
	if($_REQUEST['id']==''){
		return "";
	}else{
		$id=divide($_REQUEST['id']);
		if(count($id)!==7){
			$sql="SELECT FLOOR(DATEDIFF(CURDATE(), fecha_nacimiento) / 365) AS anos,
				FLOOR((DATEDIFF(CURDATE(), fecha_nacimiento) % 365) / 30) AS meses,
			DATEDIFF(CURDATE(), fecha_nacimiento) % 30 AS dias
			FROM `personas` P
			WHERE P.idpersona='{$id[0]}'AND P.tipo_doc='{$id[1]}'"; 
			// echo $sql." ".count($id);
			// print_r($id);
			$info=datos_mysql($sql);
			return $info['responseResult'][0];
		}else{
			return "";
		}
	}
}
   
function lista_persons(){ //revisar
	$id=divide($_POST['id']);
		$sql="SELECT concat(idpersona,'_',tipo_doc,'_',vivipersona) ACCIONES,idpeople AS Cod_Persona ,idpersona 'Identificación',FN_CATALOGODESC(1,tipo_doc) 'Tipo de Documento',
		concat_ws(' ',nombre1,nombre2,apellido1,apellido2) 'Nombre',fecha_nacimiento 'Nació',
		FN_CATALOGODESC(21,sexo) 'Sexo',FN_CATALOGODESC(19,genero) 'Genero',FN_CATALOGODESC(30,nacionalidad) 'Nacionalidad'
		FROM `personas` 
			WHERE '1'='1' and vivipersona='".$id[0]."'";
		$sql.=" ORDER BY fecha_create";
		// echo $sql;
		// $_SESSION['sql_person']=$sql;
			$datos=datos_mysql($sql);
		return panel_content($datos["responseResult"],"datos-lis",10);
		}

function focus_person(){
	return 'person';
}
   
   
function men_person(){
	$rta=cap_menus('person','pro');
	return $rta;
}

function get_person(){
	// print_r($_POST);
	if($_POST['id']==''){
		return "";
	}else{
		$id=divide($_POST['id']);
		// print_r($id);
		$sql="SELECT concat(idpersona,'_',tipo_doc,'_',vivipersona),encuentra,idpersona,tipo_doc,nombre1,nombre2,apellido1,apellido2,fecha_nacimiento,
		sexo,genero,oriensexual,nacionalidad,estado_civil,niveduca,abanesc,ocupacion,tiemdesem,vinculo_jefe,etnia,pueblo,idioma,discapacidad,regimen,eapb,
		afiliaoficio,sisben,catgosisb,pobladifer,incluofici,cuidador,perscuidada,tiempo_cuidador,cuidador_unidad,vinculo,tiempo_descanso,
		descanso_unidad,reside_localidad,localidad_vive,transporta
		FROM `personas`  
		left join personas_datocomp ON idpersona=dc_documento AND tipo_doc=dc_tipo_doc 
		WHERE idpersona ='{$id[0]}' and tipo_doc='{$id[1]}'" ;
		$info=datos_mysql($sql);
		//  echo $sql;
	 return json_encode($info['responseResult'][0]); 
	} 
}
function gra_person(){
	 //print_r($_POST);
	$id=divide($_POST['idp']);
	//  print_r(count($id));
	if(count($id)==3){
		$sql="UPDATE `personas` SET 
		encuentra=TRIM(UPPER('{$_POST['encuentra']}')),
		`tipo_doc`=TRIM(UPPER('{$_POST['tipo_doc']}')),
		`nombre1`=TRIM(UPPER('{$_POST['nombre1']}')),
		`nombre2`=TRIM(UPPER('{$_POST['nombre2']}')),
		`apellido1`=TRIM(UPPER('{$_POST['apellido1']}')),
		`apellido2`=TRIM(UPPER('{$_POST['apellido2']}')),
		`fecha_nacimiento`=TRIM(UPPER('{$_POST['fecha_nacimiento']}')),
		`sexo`=TRIM(UPPER('{$_POST['sexo']}')),
		`genero`=TRIM(UPPER('{$_POST['genero']}')),
		`oriensexual`=TRIM(UPPER('{$_POST['oriensexual']}')),
		`nacionalidad`=TRIM(UPPER('{$_POST['nacionalidad']}')),
		`estado_civil`=TRIM(UPPER('{$_POST['estado_civil']}')),
		niveduca=TRIM(UPPER('{$_POST['niveduca']}')),
		abanesc=TRIM(UPPER('{$_POST['abanesc']}')),
		ocupacion=TRIM(UPPER('{$_POST['ocupacion']}')),
		tiemdesem=TRIM(UPPER('{$_POST['tiemdesem']}')),
		`vinculo_jefe`=TRIM(UPPER('{$_POST['vinculo_jefe']}')),
		`etnia`=TRIM(UPPER('{$_POST['etnia']}')),
		`pueblo`=TRIM(UPPER('{$_POST['pueblo']}')),
		`idioma`=TRIM(UPPER('{$_POST['idioma']}')),
		`discapacidad`=TRIM(UPPER('{$_POST['discapacidad']}')),
		`regimen`=TRIM(UPPER('{$_POST['regimen']}')),
		`eapb`=TRIM(UPPER('{$_POST['eapb']}')),
		`afiliaoficio`=TRIM(UPPER('{$_POST['afiliacion']}')),
		`sisben`=TRIM(UPPER('{$_POST['sisben']}')),
		`catgosisb`=TRIM(UPPER('{$_POST['catgosisb']}')),
		`cuidador_unidad`=TRIM(UPPER('{$_POST['cuidador_unidad']}')),
		`pobladifer`=TRIM(UPPER('{$_POST['pobladifer']}')),
		`incluofici`=TRIM(UPPER('{$_POST['incluofici']}')),
		`cuidador`=TRIM(UPPER('{$_POST['cuidador']}')),
		`perscuidada`=TRIM(UPPER('{$_POST['perscuidada']}')),
		`tiempo_cuidador`=TRIM(UPPER('{$_POST['tiempo_cuidador']}')),
		`vinculo`=TRIM(UPPER('{$_POST['vinculo_cuida']}')),
		`tiempo_descanso`=TRIM(UPPER('{$_POST['tiempo_descanso']}')),
		`descanso_unidad`=TRIM(UPPER('{$_POST['descanso_unidad']}')),
		`reside_localidad`=TRIM(UPPER('{$_POST['reside_localidad']}')),
		`localidad_vive`=TRIM(UPPER('{$_POST['localidad_vive']}')),
		`transporta`=TRIM(UPPER('{$_POST['transporta']}')),
		`usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
		`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
		WHERE idpersona =TRIM(UPPER('{$id[0]}')) AND tipo_doc=TRIM(UPPER('{$id[1]}'))";
		//echo $sql;
		//    echo $sql."    ".$rta;
	}else{
		/* $sql1="INSERT INTO `personas_datocomp` VALUES (TRIM(UPPER('{$_POST['tipo_doc']}')),TRIM(UPPER('{$_POST['idpersona']}')),TRIM(UPPER('{$_POST['fpe']}')),TRIM(UPPER('{$_POST['fta']}')),TRIM(UPPER('{$_POST['imc']}')),TRIM(UPPER('{$_POST['tas']}')),TRIM(UPPER('{$_POST['tad']}')),TRIM(UPPER('{$_POST['glu']}')),TRIM(UPPER('{$_POST['bra']}')),TRIM(UPPER('{$_POST['abd']}')),TRIM(UPPER('{$_POST['pef']}')),TRIM(UPPER('{$_POST['des']}')),TRIM(UPPER('{$_POST['fin']}')),TRIM(UPPER('{$_POST['oms']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),TRIM(UPPER('{$_SESSION['us_sds']}')),null,null,'A')";
		$rta1=dato_mysql($sql1); */

		$sql="INSERT INTO personas VALUES (NULL,
		TRIM(UPPER('{$_POST['encuentra']}')),
		TRIM(UPPER('{$_POST['idpersona']}')),$id[0],
		TRIM(UPPER('{$_POST['tipo_doc']}')),
		TRIM(UPPER('{$_POST['nombre1']}')),
		TRIM(UPPER('{$_POST['nombre2']}')),
		TRIM(UPPER('{$_POST['apellido1']}')),
		TRIM(UPPER('{$_POST['apellido2']}')),
		TRIM(UPPER('{$_POST['fecha_nacimiento']}')),
		TRIM(UPPER('{$_POST['sexo']}')),
		TRIM(UPPER('{$_POST['genero']}')),
		TRIM(UPPER('{$_POST['oriensexual']}')),
		TRIM(UPPER('{$_POST['nacionalidad']}')),
		TRIM(UPPER('{$_POST['estado_civil']}')),
		TRIM(UPPER('{$_POST['niveduca']}')),
		TRIM(UPPER('{$_POST['abanesc']}')),
		TRIM(UPPER('{$_POST['ocupacion']}')),
		TRIM(UPPER('{$_POST['tiemdesem']}')),
		TRIM(UPPER('{$_POST['vinculo_jefe']}')),
		TRIM(UPPER('{$_POST['etnia']}')),
		TRIM(UPPER('{$_POST['pueblo']}')),
		TRIM(UPPER('{$_POST['idioma']}')),
		TRIM(UPPER('{$_POST['discapacidad']}')),
		TRIM(UPPER('{$_POST['regimen']}')),
		TRIM(UPPER('{$_POST['eapb']}')),
		TRIM(UPPER('{$_POST['afiliacion']}')),
		TRIM(UPPER('{$_POST['sisben']}')),
		TRIM(UPPER('{$_POST['catgosisb']}')),
		TRIM(UPPER('{$_POST['pobladifer']}')),
		TRIM(UPPER('{$_POST['incluofici']}')),
		TRIM(UPPER('{$_POST['cuidador']}')),
		TRIM(UPPER('{$_POST['perscuidada']}')),
		TRIM(UPPER('{$_POST['tiempo_cuidador']}')),
		TRIM(UPPER('{$_POST['cuidador_unidad']}')),
		TRIM(UPPER('{$_POST['vinculo_cuida']}')),
		TRIM(UPPER('{$_POST['tiempo_descanso']}')),
		TRIM(UPPER('{$_POST['descanso_unidad']}')),
		TRIM(UPPER('{$_POST['reside_localidad']}')),
		TRIM(UPPER('{$_POST['localidad_vive']}')),
		TRIM(UPPER('{$_POST['transporta']}')),
		TRIM(UPPER('{$_SESSION['us_sds']}')),
		DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";	 
	}
	// echo $sql;
	  $rta=dato_mysql($sql);
	  return $rta;
	}
	function opc_abanesc($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=181 and estado='A' ORDER BY 1",$id);
	}
	function opc_niveduca($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=180 and estado='A' ORDER BY 1",$id);
	}
	function opc_ocupacion($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=175 and estado='A' ORDER BY 1",$id);
	}
	function opc_incluofici($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=179 and estado='A' ORDER BY 1",$id);
	}
	function opc_pobladifer($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=178 and estado='A' ORDER BY 1",$id);
	}
	function opc_tipo_doc($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
    }
    function opc_sexo($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
	}
    function opc_genero($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=19 and estado='A' ORDER BY 1",$id);
	}
	function opc_oriensexual($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=49 and estado='A' ORDER BY 1",$id);
	}
	function opc_nacionalidad($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=30 and estado='A' ORDER BY 1",$id);
    }
    function opc_etnia($id=''){
	    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=16 and estado='A' ORDER BY 1",$id);
    }
	function opc_regimen($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=17 and estado='A' ORDER BY 1",$id);
    }
    function opc_eapb($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=18 and estado='A' ORDER BY 1",$id);
    }
	function opc_sisben($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=48 and estado='A' ORDER BY 1",$id);
	}
	function opc_estado_civil($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=47 and estado='A' ORDER BY 1",$id);
	}
	function opc_vinculo_jefe($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=54 and estado='A' ORDER BY 1",$id);
	}
	function opc_cuidador_unidad($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=67 and estado='A' ORDER BY 1",$id);
	}
	function opc_vinculo_cuida($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=54 and estado='A' ORDER BY 1",$id);
	}
	function opc_localidad_vive($id=''){
	return opc_sql("SELECT `idcatadeta`,CONCAT(idcatadeta,'-',descripcion) FROM `catadeta` WHERE idcatalogo=2  ORDER BY 1",$id);
    }
	function opc_transporta($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=25 and estado='A' ORDER BY 1",$id);
	}
	function opc_descanso_unidad($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=67 and estado='A' ORDER BY 1",$id);
	}
	function opc_pueblo($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=15 and estado='A' ORDER BY 1",$id);
	}
	function opc_discapacidad($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=14 and estado='A' ORDER BY 1",$id);
	}
	function opc_cuida(){
		$id=divide($_REQUEST['id']);
		if(count($id)==2){
		$sql="SELECT idpersona,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) 'Nombres' from personas where vivipersona='$id[0]'";
		}else if(count($id)==3){
		$sql="SELECT idpersona,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) 'Nombres' from personas where vivipersona='$id[2]' and idpersona<>'$id[0]'";
		}
		 //var_dump($id);
			return opc_sql($sql,'');		
	}

	function get_accfam() {
		// print_r($_POST);
			if (!$_POST['id']) {
				return '';
			}
			$id = divide($_POST['id']);
			$sql = "SELECT concat(id,'_',idviv) 'id',fecha,accion1,desc_accion1,accion2,desc_accion2,accion3,desc_accion3,accion4,desc_accion4,observacion
					FROM `hog_plancuid` 
					WHERE idviv='{$id[0]}'
					LIMIT 1";
			// echo $sql;		
			$info = datos_mysql($sql);
			if (!$info['responseResult']) {
				return '';
			}else{
				return $info['responseResult'][0];
			}
	}

 function opc_equipo($id=''){
	$fam=divide($_POST['id']);
	$sql="SELECT CASE WHEN EXISTS (SELECT 1 FROM hog_viv c WHERE c.idviv = $fam[0] AND NOT EXISTS (
        SELECT 1 FROM usuarios u WHERE u.id_usuario ='{$_SESSION['us_sds']}' AND u.equipo = c.equipo_car)) 
	THEN FALSE
    ELSE TRUE
    END AS RTA";
	$info = datos_mysql($sql);
	$rta = $info['responseResult'][0]['RTA'];

	$sql="SELECT equipo_car,equipo_car equipo FROM hog_viv WHERE idviv='$fam[0]' and estado='A' ORDER BY 1";
		$info = datos_mysql($sql);
		$rta = $info['responseResult'][0]['equipo'];
		var_dump($rta);

	if ($rta===0){
		$sql="SELECT equipo_car,equipo_car equipo FROM hog_viv WHERE idviv='$fam[0]' and estado='A' ORDER BY 1";
		$info = datos_mysql($sql);
		$rta = $info['responseResult'][0]['equipo'];
		var_dump($rta);
		// return $rta;
		return opc_sql($sql,$rta);
	}else{
		return opc_sql("SELECT equipo,equipo FROM usuarios WHERE id_usuario= '{$_SESSION['us_sds']}' and estado='A' ORDER BY 1",$id);
	}
}

function opc_equipo1($id=''){
	/* var_dump($_POST);
	// var_dump($_GET);
	// var_dump($_REQUEST);
	$fam=divide($_POST['id']);
	$sql="SELECT equipo from usuarios where ";
	$_POST['id'];
    /* $info = datos_mysql($sql);
    $rta = $info['responseResult']; */ 
	return opc_sql("SELECT equipo,equipo FROM usuarios WHERE id_usuario= '{$_SESSION['us_sds']}' and estado='A' ORDER BY 1",$id);
}
 
function eventAsign($key) {
    $id = divide($key);
    $sql = "SELECT evento as eve
            FROM vspeve
            WHERE documento ='{$id[0]}' AND tipo_doc='{$id[1]}'";
    // echo $sql;
    // print_r($id);
    $info = datos_mysql($sql);
    $rta = $info['responseResult'];

    $eventos = array(
      1 => ['icono' => 'siges1', 'titulo' => 'SIFILIS GESTACIONAL', 'modulo' => 'sifigest'],
	  2 => ['icono' => 'hbges1', 'titulo' => 'HB GESTACIONAL', 'modulo' => 'hbgest'],
	  3 => ['icono' => 'vihge1', 'titulo' => 'VIH GESTACIONAL', 'modulo' => 'vihgest'],
	  4 => ['icono' => 'gesta1', 'titulo' => 'BAJO PESO GESTACIONAL', 'modulo' => 'gestantes'],
	  5 => ['icono' => 'gesta1', 'titulo' => 'FAMILIAS CON GESTANTES', 'modulo' => 'gestantes'],
	  6 => ['icono' => 'gesta3', 'titulo' => 'MORBILIDAD MATERNA EXTREMA', 'modulo' => 'mme'],
	  7 => ['icono' => 'condu1', 'titulo' => 'CONDUCTA SUICIDA (CONSUMADO)', 'modulo' => 'condsuic'],
	  8 => ['icono' => 'siges1', 'titulo' => 'VIOLENCIA EN GESTANTES', 'modulo' => 'violgest'],
	  9 => ['icono' => 'desnu1', 'titulo' => 'DNT AGUDA MODERADA O SEVERA', 'modulo' => 'dntsevymod'],
	  10 => ['icono' => 'desnu1', 'titulo' => 'MENORES CON EXCESO DE PESO', 'modulo' => 'dntsevymod'],
	  11 => ['icono' => 'aterm1', 'titulo' => 'BPN A TÉRMNO', 'modulo' => 'bpnterm'],
	  12 => ['icono' => 'prete1', 'titulo' => 'BPN PRETERMINO', 'modulo' => 'bpnpret'],
	  13 => ['icono' => 'hipot1', 'titulo' => 'MNE HIPOTIRODISMO', 'modulo' => 'mnehosp'],
	  14 => ['icono' => 'siges1', 'titulo' => 'ERA - IRA', 'modulo' => 'eraira'],
	  15 => ['icono' => 'desnu1', 'titulo' => 'FAMILIAS CON MENORES DE 5 AÑOS', 'modulo' => 'dntsevymod'],
	  16 => ['icono' => 'canin1', 'titulo' => 'CANCER INFANTIL', 'modulo' => 'cancinfa'],
	  17 => ['icono' => 'sifil1', 'titulo' => 'SIFILIS CONGENITA', 'modulo' => 'sificong'],
	  18 => ['icono' => 'psico1', 'titulo' => 'ACOMPAÑAMIENTO PSICOSOCIAL', 'modulo' => 'acompsic'],
	  19 => ['icono' => 'duelo1', 'titulo' => 'APOYO PSICOLOGICO EN DUELO', 'modulo' => 'apopsicduel'],
	  20 => ['icono' => 'croni2', 'titulo' => 'CRONICOS', 'modulo' => 'cronicos'],
	  21 => ['icono' => 'viole1', 'titulo' => 'VIOLENCIA REITERADA', 'modulo' => 'violreite'],
	  22 => ['icono' => 'salor1', 'titulo' => 'SALUD ORAL', 'modulo' => 'saludoral'],
	  23 => ['icono' => 'otrca1', 'titulo' => 'OTROS CASOS PRIORIZADOS', 'modulo' => 'otroprio'],
	  24 => ['icono' => 'gesta1', 'titulo' => 'OBESIDAD GESTACIONAL', 'modulo' => 'gestantes'],
	  25 => ['icono' => 'gesta1', 'titulo' => 'MATERNAS ADOLESCENTES', 'modulo' => 'gestantes'],
	  26 => ['icono' => 'condu1', 'titulo' => 'CONDUCTA SUICIDA (AMENAZA)', 'modulo' => 'condsuic'],
	  27 => ['icono' => 'condu1', 'titulo' => 'CONDUCTA SUICIDA (IDEACIÓN)', 'modulo' => 'condsuic'],
	  28 => ['icono' => 'condu1', 'titulo' => 'CONDUCTA SUICIDA (INTENTO)', 'modulo' => 'condsuic']
    );
    $eve = '';
    foreach ($rta as $evento) {
		// print_r($_POST);
        $id = $evento['eve'];
        if (isset($eventos[$id])) {
            $icono = $eventos[$id]['icono'];
            $titulo = $eventos[$id]['titulo'];
            $modulo = $eventos[$id]['modulo'];
            $eve .= "<li class='icono $icono' title='$titulo' id='".$key."_".$id."' Onclick=\"mostrar('$modulo','pro',event,'','../vsp/$modulo.php',7,'$modulo');Color('datos-lis');\"></li>";
        } else {
            $eve .= "ERROR EN EL ID DEL EVENTO";
        }
    }
    return $eve;
}

function plan($id){
	$sql="select id FROM hog_plancuid where idviv='".$id."'";
	$info=datos_mysql($sql);
	if(isset($info['responseResult'][0])){
		return true;
	}else{
		return false;
	}
}

function eac($id){
	$sql="select fecha,numfam,fechaupd FROM hog_viv where idviv='".$id."'";
	$info=datos_mysql($sql);
	$fecha=$info['responseResult'][0]['fecha'];
	$famil=$info['responseResult'][0]['numfam'];
	$fecUp=$info['responseResult'][0]['fechaupd'];
	if(($fecha=='' || $fecha=='0000-00-00') && $famil!='' ){
		return 1;
	}elseif($fecUp!='0000-00-00' && $fecUp!=''){
		return 2;
	}else{
		return 3;
	}
}

function formato_dato($a,$b,$c,$d){
 $b=strtolower($b);
 $rta=$c[$d];
// $rta=iconv('UTF-8','ISO-8859-1',$rta);
// var_dump($c['ACCION);
//  print_r($c); 
	if ($a=='homes' && $b=='acciones'){
		$rta="<nav class='menu right'>";	
			$rta.="<li class='icono casa' title='Caracterización del Hogar' id='".$c['ACCIONES']."' Onclick=\"mostrar('homes1','fix',event,'','lib.php',0,'homes1');hideFix('person1','fix');Color('homes-lis');\"></li>";//mostrar('person1','fix',event,'','lib.php',0,'person1'),500);
			$rta.="<li class='icono crear' title='Crear Familia' id='".$c['ACCIONES']."' Onclick=\"mostrar('homes','pro',event,'','lib.php',7,'homes');setTimeout(DisableUpdate,300,'fechaupd','hid');Color('homes-lis');\"></li>";
		}
		if ($a=='famili-lis' && $b=='acciones'){
			$rta="<nav class='menu right'>";
			switch (eac($c['Cod_Familia'])) {
				case 1:
					$cmps="['idviv','numfam']";	
					break;
				case 2:
					$cmps="['idviv','numfam','estado_aux','equipo_car','fechaupd']";
					break;
				case 3:
					$cmps="['idviv','numfam','estado_aux','equipo_car']";
					break;
				default:
					break;
			}
				/* $rta.="<li class='icono inactiva' title='Eliminar' id='".$c['ACCIONES']."' OnClick=\"inactivareg(this,event,'idviv');\" ></li>"; */
				$rta.="<li class='icono editar ' title='Editar Caracterizacion' id='".$c['ACCIONES']."' Onclick=\"mostrar('homes','pro',event,'','lib.php',7,'homes');setTimeout(getData,1000,'homes',event,this,{$cmps});setTimeout(disFecar,1100,'fecha');Color('famili-lis');\"></li>";  //act_lista(f,this);
				$rta.="<li class='icono familia' title='Integrantes Familia' id='".$c['ACCIONES']."' Onclick=\"mostrar('person1','fix',event,'','lib.php',0,'person1');Color('famili-lis');\"></li>";//setTimeout(plegar,500);mostrar('person','pro',event,'','lib.php',7);
				$rta.="<li class='icono plan1' title='Planes de Cuidado Familiar' id='".$c['ACCIONES']."' Onclick=\"mostrar('planDCui','pro',event,'','../vivienda/plancui.php',7);Color('famili-lis');\"></li>";
				if(plan($c['Cod_Familia'])===true){
					$rta.="<li class='icono comp1' title='Compromisos Concertados' id='".$c['ACCIONES']."' Onclick=\"mostrar('compConc','pro',event,'','../vivienda/plncon.php',7);Color('famili-lis');\"></li>";
				}
				$rta.="<li class='icono ambi1' title='Ambiental' id='".$c['ACCIONES']."' Onclick=\"mostrar('ambient','pro',event,'','../vivienda/amb.php',7);Color('famili-lis');\"></li>";
				$rta.="<li class='icono crear' title='Crear Integrante Familia' id='".$c['ACCIONES']."' Onclick=\"mostrar('person','pro',event,'','lib.php',7,'person');setTimeout(disabledCmp,300,'cmhi');setTimeout(enabLoca('reside_localidad','lochi'),300);Color('famili-lis');\"></li>";
			}
			if ($a=='datos-lis' && $b=='acciones'){
				$rta="<nav class='menu right'>";
				$rta.="<li class='icono editar ' title='Editar Usuario' id='".$c['ACCIONES']."' Onclick=\"mostrar('person','pro',event,'','lib.php',7,'person');setTimeout(getData,500,'person',event,this,['idpersona','tipo_doc','fecha_nacimiento','sexo']);Color('datos-lis');setTimeout(enabAfil,700,'regimen','eaf');setTimeout(enabEtni,700,'etnia','ocu','idi');setTimeout(enabYes,700,'cuidador','cUi');\"></li>";//setTimeout(enabEapb,700,'regimen','rgm');setTimeout(enabLoca,700,'reside_localidad','lochi');
				//$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('person','pro',event,'','lib.php',7,'person');setTimeout(getData,1000,'person',event,this,['idpersona','tipo_doc']);Color('datos-lis');setTimeout(enabAfil,1200,'regimen','eaf');setTimeout(enabEapb,1200,'regimen','rgm');setTimeout(enabEtni,1200,'etnia','ocu','idi');setTimeout(hideCuida,1210,'cuidador','cuihid',false);\"></li>";
					$rta.="<li class='icono medida ' title='Medidas' id='".$c['ACCIONES']."' Onclick=\"mostrar('medidas','pro',event,'','../vivienda/medidas.php',7,'medidas');Color('datos-lis');\"></li>";
					$rta.="<li class='icono admsi1' title='Validar Evento' id='".$c['ACCIONES']."' Onclick=\"mostrar('vspeve','pro',event,'','vspeve.php',7,'vspeve');Color('datos-lis');\"></li>";
					$rta.=eventAsign($c['ACCIONES']);

				}
				if ($a=='planc' && $b=='acciones'){
					$rta="<nav class='menu right'>";		
						$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,1000,'placuifam',event,this,'id');\"></li>";  //   act_lista(f,this);
					}
return $rta;
}

function bgcolor($a,$c,$f='c'){
 $rta="";
 return $rta;
}
