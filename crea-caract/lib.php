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

function cmp_caract(){
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
	$days=fechas_app('vivienda');
	$c[]=new cmp($o,'e',null,'INFORMACIÓN COMPLEMENTARIA DE LA VIVIENDA',$w);
	$c[]=new cmp('idg','h',15,$_POST['id'],$w.' '.$o,'id','idg',null,'####',false,false);
	$c[]=new cmp('numfam','s',3,$d,$w.' '.$o,'Número de Familia','numfam',null,'',true,true,'','col-2');
	$c[]=new cmp('fecha','d','10',$d,$w.' '.$o,'fecha Caracterización','fecha',null,'',true,true,'','col-2',"validDate(this,$days,0);");
	$c[]=new cmp('estado_aux','s','3',$d,$w.' '.$o,'Estado Visita','estado_aux',null,'',true,true,'','col-2','enabFielSele(this,true,[\'motivo_estaux\'],[\'4\']);stateVisit(this,[\'cri\',\'fam\',\'ali\',\'sub\',\'ser\',\'ani\',\'amb\',\'fal\'],[\'ne\',\'dog\',\'cat\']);');
	$c[]=new cmp('motivo_estaux','s','3',$d,$w.' '.$o,'Motivo','motivo_estaux',null,'',false,false,'','col-2');
	$c[]=new cmp('equipo_car','s','3',$d,$w.' '.$o,'Equipo Caracterizacion','equipo',null,'',true,true,'','col-2');
	$c[]=new cmp('fechaupd','h','10',$d,$w.' '.$o,'fecha Actualización','fechaupd',null,'',false,true,'','col-15',"validDate(this,$days,0);",array(''),"addupd(this,'hid','motivoupd');");
	$c[]=new cmp('motivoupd','h','3',$d,$w.' hid '.$o,'Motivo Actualización','motivoupd',null,'',false,false,'','col-3');
	$c[]=new cmp('eventoupd','h','3',$d,$w.' hid '.$o,'Evento Actualización','evenupd',null,'',false,false,'','col-3');
	$c[]=new cmp('fechanot','h','10',$d,$w.' hid '.$o,'fecha Notificación','fechanot',null,'',false,false,'','col-15',"validDate(this,$days,0);");
	$c[]=new cmp('equipo_act','h','3',$d,$w.' hid '.$o,'Equipo Actualizacion','equipo',null,'',false,true,'','col-2');
	$c[]=new cmp('complemento1','s','3',$d,$w.' '.$o.' '.$n,'complemento1','complemento',null,'',true,true,'','col-2');
    $c[]=new cmp('nuc1','t','4',$d,$w.' '.$o.' '.$n,'nuc1','nuc1',null,'',true,true,'','col-1');
 	$c[]=new cmp('complemento2','s','3',$d,$w.'ne '.$o.' '.$n,'complemento2','complemento',null,'',false,true,'','col-2');
 	$c[]=new cmp('nuc2','t','4',$d,$w.' '.$o.' ne '.$n,'nuc2','nuc2',null,'',false,true,'','col-1');
 	$c[]=new cmp('complemento3','s','3',$d,$w.' ne '.$o.' '.$n,'complemento3','complemento',null,'',false,true,'','col-2');
 	$c[]=new cmp('nuc3','t','4',$d,$w.' '.$o.' ne '.$n,'nuc3','nuc3',null,'',false,true,'','col-2');
	$c[]=new cmp('telefono1','n','10',$d,$w.' '.$o.' '.$n,'telefono1','telefono1','rgxphone',NULL,true,true,'','col-25');
	$c[]=new cmp('telefono2','n','10',$d,$w.' ne '.$o.' '.$n,'telefono2','telefono2','rgxphone1',null,false,true,'','col-25');
	$c[]=new cmp('telefono3','n','10',$d,$w.' ne '.$o.' '.$n,'telefono3','telefono3','rgxphone1',null,false,true,'','col-25');
    
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
	$c[]=new cmp('dormitorios','n',2,$d,$w.' bed '.$o,'Número de dormitorios','dormitorios',null,null,true,true,'','col-25');
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
	$c[]=new cmp('otros','a',2,$d,$w.' ne '.$o,'otros','otros',null,null,false,true,'','col-10');
      
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

function opc_numfam($id=''){
	return opc_sql("SELECT `idcatadeta`,concat(idcatadeta,' - ',descripcion) FROM `catadeta` WHERE idcatalogo=172 and estado='A' ORDER BY 1",$id);
}
function opc_estado_aux($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=165 and estado='A' ORDER BY 1",$id);
}
function opc_motivo_estaux($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=171 and estado='A' ORDER BY 1",$id);
}
function opc_equipo($id=''){
	return opc_sql("SELECT equipo,equipo FROM usuarios WHERE id_usuario= '{$_SESSION['us_sds']}' and estado='A' ORDER BY 1",$id);
}


function bgcolor($a,$c,$f='c'){
    $rta="";
    return $rta;
   }