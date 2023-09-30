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
    echo csv($rs);
    die;
    break;
  default:
    eval('$rta='.$_POST['a'].'_'.$_POST['tb'].'();');
    if (is_array($rta)) json_encode($rta);
	else echo $rta;
  }   
}


function lis_tamsrq(){
	// concat(srq_idpersona,'_',srq_tipodoc,'_',srq_momento) ACCIONES,
	$sql="SELECT ROW_NUMBER() OVER (ORDER BY 1) R,concat(srq_idpersona,'_',srq_tipodoc,'_',srq_momento) ACCIONES,srq_idpersona Documento,FN_CATALOGODESC(1,srq_tipodoc) 'Tipo de Documento',CONCAT_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres, 
	FN_CATALOGODESC(21,P.sexo) Sexo,FN_CATALOGODESC(116,srq_momento) Momento,`srq_totalsi` 'Puntaje SI',`srq_totalno` 'Puntaje NO'
FROM hog_tam_srq O
LEFT JOIN personas P ON O.srq_idpersona = P.idpersona
		WHERE '1'='1'";
	$sql.=whe_tamsrq();
	$sql.=" ORDER BY 1";

	 $sql1="SELECT * 
	  FROM `hog_tam_srq` WHERE 1";
	$sql1.=whe_tamsrq();	
	//echo $sql;
		$_SESSION['sql_tamsrq']=$sql1;
		$datos=datos_mysql($sql);
	return panel_content($datos["responseResult"],"tamsrq",20);
}

function whe_tamsrq() {
	$sql = "";
	if ($_POST['fidentificacion'])
		$sql .= " AND srq_idpersona like '%".$_POST['fidentificacion']."%'";
	if ($_POST['fsexo'])
		$sql .= " AND P.sexo ='".$_POST['fsexo']."' ";
	if ($_POST['fpersona']){
		if($_POST['fpersona'] == '2'){ //mayor de edad
			$sql .= " AND TIMESTAMPDIFF(YEAR, P.fecha_nacimiento, CURDATE()) <= 18 ";
		}else{ //menor de edad
			$sql .= " AND TIMESTAMPDIFF(YEAR, P.fecha_nacimiento, CURDATE()) > 18 ";
		}
	}
	return $sql;
}

function cmp_tamsrq(){
	$rta="";
	$t=['tam_srq'=>'','srq_tipodoc'=>'','srq_nombre'=>'','srq_idpersona'=>'','srq_fechanacimiento'=>'','srq_totalsi'=>'','srq_totalno'=>'','srq_momento'=>'','srq_edad'=>'','srq_sintoma1'=>'','srq_sintoma2'=>'','srq_sintoma3'=>'','srq_sintoma4'=>'','srq_sintoma5'=>'','srq_sintoma6'=>'','srq_sintoma7'=>'','srq_sintoma8'=>'','srq_sintoma9'=>'','srq_sintoma10'=>'','srq_pregunta1'=>'','srq_pregunta2'=>'','srq_pregunta3'=>'','srq_pregunta4'=>'','srq_pregunta5'=>'','srq_pregunta6'=>'','srq_pregunta7'=>'','srq_pregunta8'=>'','srq_pregunta9'=>'','srq_pregunta10'=>'','srq_pregunta11'=>'','srq_pregunta12'=>'','srq_pregunta13'=>'','srq_pregunta14'=>'','srq_pregunta15'=>'','srq_pregunta16'=>'','srq_pregunta17'=>'','srq_pregunta18'=>'','srq_pregunta19'=>'','srq_pregunta20'=>'','srq_pregunta21'=>'','srq_pregunta22'=>'','srq_pregunta23'=>'','srq_pregunta24'=>'','srq_pregunta25'=>'','srq_pregunta26'=>'','srq_pregunta27'=>'','srq_pregunta28'=>'','srq_pregunta29'=>'','srq_pregunta30'=>'']; 
	$w='tamsrq';
	$d=get_tamsrq(); 
	if ($d=="") {$d=$t;}
	$u = ($d['tam_srq']!='') ? false : true ;
	$o='datos';
    $key='srch';
	$c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
	$c[]=new cmp('idsrq','h',15,$_POST['id'],$w.' '.$o,'','',null,'####',false,false);
	$c[]=new cmp('srq_idpersona','n','20',$d['srq_idpersona'],$w.' '.$o.' '.$key,'N° Identificación','srq_idpersona',null,'',false,$u,'','col-2');
	$c[]=new cmp('srq_tipodoc','s','3',$d['srq_tipodoc'],$w.' '.$o.' '.$key,'Tipo Identificación','srq_tipodoc',null,'',false,$u,'','col-25','getDatForm(\'srch\',\'person\',[\'datos\']);');
	$c[]=new cmp('srq_nombre','t','50',$d['srq_nombre'],$w.' '.$o,'nombres','srq_nombre',null,'',false,false,'','col-4');
	$c[]=new cmp('srq_fechanacimiento','d','10',$d['srq_fechanacimiento'],$w.' '.$o,'fecha nacimiento','srq_fechanacimiento',null,'',false,false,'','col-15');
    $c[]=new cmp('srq_edad','n','3',$d['srq_edad'],$w.' '.$o,'edad','srq_edad',null,'',true,false,'','col-1');
   
    $c[]=new cmp('act','o','3','',$w.' '.$o,'Desea continuar','act',null,'',true,$u,'','col-3','hiddxedad(\'srq_edad\',\'cuestionario1\',\'cuestionario2\')');
  

	$o=' cuestionario1 oculto ';
				$c[]=new cmp($o,'e',null,'Cuestionario de Signos y Síntomas para Niños RQC',$w);
				$c[]=new cmp('srq_sintoma1','o',3,$d['srq_sintoma1'],$w.''.$o,'1.  ¿El lenguaje del niño(a) es anormal en alguna forma?','srq_sintoma1',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_sintoma2','o',3,$d['srq_sintoma2'],$w.''.$o,'2. ¿El niño(a) duerme mal?','srq_sintoma2',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_sintoma3','o',3,$d['srq_sintoma3'],$w.''.$o,'3. ¿Ha tenido el niño(a) en algunas ocasiones convulsiones o caídas al suelo sin razón?	','srq_sintoma3',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_sintoma4','o',3,$d['srq_sintoma4'],$w.''.$o,'4. ¿Sufre el niño(a) de dolores frecuentes de cabeza?','srq_sintoma4',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_sintoma5','o',3,$d['srq_sintoma5'],$w.''.$o,'5. ¿El niño(a) ha huido de la casa frecuentemente?','srq_sintoma5',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_sintoma6','o',3,$d['srq_sintoma6'],$w.''.$o,'6. ¿Ha robado cosas de la casa?','srq_sintoma6',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_sintoma7','o',3,$d['srq_sintoma7'],$w.''.$o,'7. ¿Se asusta o se pone nervioso(a) sin razón?','srq_sintoma7',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_sintoma8','o',3,$d['srq_sintoma8'],$w.''.$o,'8. ¿Parece como retardado(a) o lento(a) para aprender?','srq_sintoma8',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_sintoma9','o',3,$d['srq_sintoma9'],$w.''.$o,'9. ¿El (la) niño(a) casi nunca juega con otros niños(as)?','srq_sintoma9',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_sintoma10','o',3,$d['srq_sintoma10'],$w.''.$o,'10. ¿El niño(a) se orina o defeca en la ropa?','srq_sintoma10',null,null,true,$u,'','col-10');


	$o=' cuestionario2 oculto ';
				$c[]=new cmp($o,'e',null,'Cuestionario de Síntomas SRQ',$w);
				$c[]=new cmp('srq_pregunta1','o',3,$d['srq_pregunta1'],$w.''.$o,'1.¿Tiene frecuentes dolores de cabeza? ','srq_pregunta1',null,null,true,$u,'','col-5');

				$c[]=new cmp('srq_pregunta2','o',3,$d['srq_pregunta2'],$w.''.$o,'2.¿Tiene mal apetito? ','srq_pregunta2',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta3','o',3,$d['srq_pregunta3'],$w.''.$o,'3.¿Duerme mal? ','srq_pregunta3',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta4','o',3,$d['srq_pregunta4'],$w.''.$o,'4.¿Se asusta con facilidad? ','srq_pregunta4',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta5','o',3,$d['srq_pregunta5'],$w.''.$o,'5.¿Sufre de temblor en las manos? ','srq_pregunta5',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta6','o',3,$d['srq_pregunta6'],$w.''.$o,'6.¿Se siente nervioso, tenso o aburrido? ','srq_pregunta6',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta7','o',3,$d['srq_pregunta7'],$w.''.$o,'7.¿Sufre de mala digestión? ','srq_pregunta7',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta8','o',3,$d['srq_pregunta8'],$w.''.$o,'8.¿No puede pensar con claridad? ','srq_pregunta8',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta9','o',3,$d['srq_pregunta9'],$w.''.$o,'9.¿Se siente triste? ','srq_pregunta9',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta10','o',3,$d['srq_pregunta10'],$w.''.$o,'10. ¿Llora usted con mucha frecuencia?','srq_pregunta10',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta11','o',3,$d['srq_pregunta11'],$w.''.$o,'11. ¿Tiene dificultad de disfrutar sus actividades diarias?','srq_pregunta11',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta12','o',3,$d['srq_pregunta12'],$w.''.$o,'12. ¿Tiene dificultad para tomar decisiones?','srq_pregunta12',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta13','o',3,$d['srq_pregunta13'],$w.''.$o,'13. ¿Tiene dificultad en hacer su trabajo? (¿Sufre usted con su trabajo?)','srq_pregunta13',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta14','o',3,$d['srq_pregunta14'],$w.''.$o,'14. ¿Es incapaz de desempeñar un papel útil en su vida?','srq_pregunta14',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta15','o',3,$d['srq_pregunta15'],$w.''.$o,'15. ¿Ha perdido interés en las cosas?','srq_pregunta15',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta16','o',3,$d['srq_pregunta16'],$w.''.$o,'16. ¿Siente que usted es una persona inútil?','srq_pregunta16',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta17','o',3,$d['srq_pregunta17'],$w.''.$o,'17. ¿Ha tenido la idea de acabar con su vida?','srq_pregunta17',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta18','o',3,$d['srq_pregunta18'],$w.''.$o,'18. ¿Si siente cansado todo el tiempo?','srq_pregunta18',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta19','o',3,$d['srq_pregunta19'],$w.''.$o,'19. ¿Tiene sensaciones desagradables en su estómago?','srq_pregunta19',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta20','o',3,$d['srq_pregunta20'],$w.''.$o,'20. ¿Se cansa con facilidad?','srq_pregunta20',null,null,true,$u,'','col-5');
				$c[]=new cmp('srq_pregunta21','o',3,$d['srq_pregunta21'],$w.''.$o,'21. ¿Siente usted que alguien ha tratado de herirlo en alguna forma?','srq_pregunta21',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_pregunta22','o',3,$d['srq_pregunta22'],$w.''.$o,'22. ¿Es usted una persona mucho más importante de lo que piensan los demás?','srq_pregunta22',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_pregunta23','o',3,$d['srq_pregunta23'],$w.''.$o,'23. ¿Ha notado interferencias o algo raro en su pensamiento?','srq_pregunta23',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_pregunta24','o',3,$d['srq_pregunta24'],$w.''.$o,'24. ¿Oye voces sin saber de dónde vienen o que otras personas no puede oir?	','srq_pregunta24',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_pregunta25','o',3,$d['srq_pregunta25'],$w.''.$o,'25. ¿Ha tenido convulsiones, ataques o caídas al suelo, con movimientos de brazos y piernas; con mordedura de la lengua o pérdida del conocimiento?	','srq_pregunta25',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_pregunta26','o',3,$d['srq_pregunta26'],$w.''.$o,'26. ¿Alguna vez le ha parecido a su familia, sus amigos, su médico o a su sacerdote que usted estaba bebiendo demasiado licor?','srq_pregunta26',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_pregunta27','o',3,$d['srq_pregunta27'],$w.''.$o,'27. ¿Alguna vez ha querido dejar de beber, pero no ha podido?','srq_pregunta27',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_pregunta28','o',3,$d['srq_pregunta28'],$w.''.$o,'28. ¿Ha tenido alguna vez dificultades en el trabajo (o estudio) a causa de la bebida, como beber en el trabajo o en el colegio, o faltar a ellos?','srq_pregunta28',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_pregunta29','o',3,$d['srq_pregunta29'],$w.''.$o,'29. ¿Ha estado en riñas o lo han detenido estando borracho?','srq_pregunta29',null,null,true,$u,'','col-10');
				$c[]=new cmp('srq_pregunta30','o',3,$d['srq_pregunta30'],$w.''.$o,'30. ¿Le ha parecido alguna vez que usted bebía demasiado?','srq_pregunta30',null,null,true,$u,'','col-10');

				$o='totalresul';
				$c[]=new cmp($o,'e',null,'TOTAL',$w);
				$c[]=new cmp('srq_totalsi','t',3,$d['srq_totalsi'],$w.''.$o,'Total Si','srq_totalsi',null,'',false,false,'','col-5');
				$c[]=new cmp('srq_totalno','t',3,$d['srq_totalno'],$w.''.$o,'Total NO','srq_totalno',null,'',false,false,'','col-5');

	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	
	return $rta;
   }

   function get_tamsrq(){
	if($_POST['id']==0){
		return "";
	}else{
		 $id=divide($_POST['id']);
		// print_r($_POST);
		$sql="SELECT `tam_srq`,`srq_idpersona`,`srq_tipodoc`,
		FN_CATALOGODESC(116,srq_momento) srq_momento,
 `srq_sintoma1`, `srq_sintoma2`, `srq_sintoma3`, `srq_sintoma4`, `srq_sintoma5`, `srq_sintoma6`, `srq_sintoma7`, `srq_sintoma8`, `srq_sintoma9`, `srq_sintoma10`, `srq_pregunta1`, `srq_pregunta2`, `srq_pregunta3`, `srq_pregunta4`, `srq_pregunta5`, `srq_pregunta6`, `srq_pregunta7`, `srq_pregunta8`, `srq_pregunta9`, `srq_pregunta10`, `srq_pregunta11`, `srq_pregunta12`, `srq_pregunta13`, `srq_pregunta14`, `srq_pregunta15`, `srq_pregunta16`, `srq_pregunta17`, `srq_pregunta18`, `srq_pregunta19`, `srq_pregunta20`, `srq_pregunta21`, `srq_pregunta22`, `srq_pregunta23`, `srq_pregunta24`, `srq_pregunta25`, `srq_pregunta26`, `srq_pregunta27`, `srq_pregunta28`, `srq_pregunta29`, `srq_pregunta30`, `srq_totalsi`,`srq_totalno`,O.estado,P.idpersona,P.tipo_doc,concat_ws(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) srq_nombre,P.fecha_nacimiento srq_fechanacimiento,YEAR(CURDATE())-YEAR(P.fecha_nacimiento) srq_edad
		FROM `hog_tam_srq` O
		LEFT JOIN personas P ON O.srq_idpersona = P.idpersona and O.srq_tipodoc=P.tipo_doc
		WHERE srq_idpersona ='{$id[0]}' AND srq_tipodoc='{$id[1]}' AND srq_momento = '{$id[2]}'  ";
		// echo $sql;
		$info=datos_mysql($sql);
				return $info['responseResult'][0];
		}
	} 


function get_person(){
	// print_r($_POST);
	$id=divide($_POST['id']);
$sql="SELECT idpersona,tipo_doc,concat_ws(' ',nombre1,nombre2,apellido1,apellido2) nombres,fecha_nacimiento,YEAR(CURDATE())-YEAR(fecha_nacimiento) Edad
FROM personas 
	WHERE idpersona='".$id[0]."' AND tipo_doc=upper('".$id[1]."')";
	// echo $sql;
	$info=datos_mysql($sql);
	if (!$info['responseResult']) {
		return '';
	}
return json_encode($info['responseResult'][0]);
}

function focus_tamsrq(){
	return 'tamsrq';
   }
   
function men_tamsrq(){
	$rta=cap_menus('tamsrq','pro');
	return $rta;
   }

   function cap_menus($a,$b='cap',$con='con') {
	$rta = ""; 
	$acc=rol($a);
	if ($a=='tamsrq') {  
		$rta .= "<li class='icono $a  grabar' title='Grabar' Onclick=\"grabar('$a',this);\" ></li>";
		
	}
	return $rta;
  }
   
function gra_tamsrq(){
	$id=$_POST['idsrq'];
	//print_r($_POST);
	if($id != ""){
		return "No es posible actualizar el tamizaje";
	}else{

	$infodata_srq=datos_mysql("SELECT srq_momento,srq_idpersona FROM hog_tam_srq
		 WHERE srq_idpersona = {$_POST['srq_idpersona']} AND srq_momento = 2 ");
	if (isset($infodata_srq['responseResult'][0])){
		return "Ya se realizo los dos momentos";
	}else{
		$infodata2_srq=datos_mysql("SELECT srq_momento,srq_idpersona FROM hog_tam_srq
		 WHERE srq_idpersona = {$_POST['srq_idpersona']} AND srq_momento = 1 ");
		if (isset($infodata2_srq['responseResult'][0])){
			$idmomento = 2;
		}else{
			$idmomento = 1;
		}
	}

	
	$totalsi = 0;
	$totalno = 0;


	if($_POST['srq_edad'] > 0 && $_POST['srq_edad'] < 17){
		 for ($i=1; $i <= 10; $i++) { 
		 	$sin = "srq_sintoma".$i;
		 	if($_POST[$sin] == 'SI'){
		 		$totalsi += 1;
		 	}else{
		 		$totalno += 1;
		 	}
		 }
	}else{
		 for ($j=1; $j <= 30; $j++) { 
		 	$pre = "srq_pregunta".$j;
		 	if($_POST[$pre] == 'SI'){
		 		$totalsi += 1;
		 	}else{
		 		$totalno += 1;
		 	}
		 }
	}

	$sql="INSERT INTO hog_tam_srq VALUES (null,
					TRIM(UPPER('{$_POST['srq_tipodoc']}')),
					TRIM(UPPER('{$_POST['srq_idpersona']}')),
					TRIM(UPPER('{$idmomento}')),
					TRIM(UPPER('{$_POST['srq_sintoma1']}')),
					TRIM(UPPER('{$_POST['srq_sintoma2']}')),
					TRIM(UPPER('{$_POST['srq_sintoma3']}')),
					TRIM(UPPER('{$_POST['srq_sintoma4']}')),
					TRIM(UPPER('{$_POST['srq_sintoma5']}')),
					TRIM(UPPER('{$_POST['srq_sintoma6']}')),
					TRIM(UPPER('{$_POST['srq_sintoma7']}')),
					TRIM(UPPER('{$_POST['srq_sintoma8']}')),
					TRIM(UPPER('{$_POST['srq_sintoma9']}')),
					TRIM(UPPER('{$_POST['srq_sintoma10']}')),
					TRIM(UPPER('{$_POST['srq_pregunta1']}')),
					TRIM(UPPER('{$_POST['srq_pregunta2']}')),
					TRIM(UPPER('{$_POST['srq_pregunta3']}')),
					TRIM(UPPER('{$_POST['srq_pregunta4']}')),
					TRIM(UPPER('{$_POST['srq_pregunta5']}')),
					TRIM(UPPER('{$_POST['srq_pregunta6']}')),
					TRIM(UPPER('{$_POST['srq_pregunta7']}')),
					TRIM(UPPER('{$_POST['srq_pregunta8']}')),
					TRIM(UPPER('{$_POST['srq_pregunta9']}')),
					TRIM(UPPER('{$_POST['srq_pregunta10']}')),
					TRIM(UPPER('{$_POST['srq_pregunta11']}')),
					TRIM(UPPER('{$_POST['srq_pregunta12']}')),
					TRIM(UPPER('{$_POST['srq_pregunta13']}')),
					TRIM(UPPER('{$_POST['srq_pregunta14']}')),
					TRIM(UPPER('{$_POST['srq_pregunta15']}')),
					TRIM(UPPER('{$_POST['srq_pregunta16']}')),
					TRIM(UPPER('{$_POST['srq_pregunta17']}')),
					TRIM(UPPER('{$_POST['srq_pregunta18']}')),
					TRIM(UPPER('{$_POST['srq_pregunta19']}')),
					TRIM(UPPER('{$_POST['srq_pregunta20']}')),
					TRIM(UPPER('{$_POST['srq_pregunta21']}')),
					TRIM(UPPER('{$_POST['srq_pregunta22']}')),
					TRIM(UPPER('{$_POST['srq_pregunta23']}')),
					TRIM(UPPER('{$_POST['srq_pregunta24']}')),
					TRIM(UPPER('{$_POST['srq_pregunta25']}')),
					TRIM(UPPER('{$_POST['srq_pregunta26']}')),
					TRIM(UPPER('{$_POST['srq_pregunta27']}')),
					TRIM(UPPER('{$_POST['srq_pregunta28']}')),
					TRIM(UPPER('{$_POST['srq_pregunta29']}')),
					TRIM(UPPER('{$_POST['srq_pregunta30']}')),
					TRIM(UPPER('{$totalsi}')),
					TRIM(UPPER('{$totalno}')),
					TRIM(UPPER('{$_SESSION['us_sds']}')),
					DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
					//echo $sql;
				}
				  $rta=dato_mysql($sql);
				//   return "correctamente";
				  return $rta;
				}


	function opc_srq_tipodoc($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
	}
	function opc_sexo($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=21 and estado='A' ORDER BY 1",$id);
	}
	function opc_momento($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=116 and estado='A'  ORDER BY 1 ",$id);
	}
	function opc_departamento($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=105 and estado='A' ORDER BY 1",$id);
	}
	function opc_salud_mental($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=104 and estado='A' ORDER BY 1",$id);
	}
	function opc_estado_civil($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=47 and estado='A' ORDER BY 1",$id);
	}
	function opc_niv_educativo($id=''){
		return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=52 and estado='A' ORDER BY 1",$id);
	}

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
	   // $rta=iconv('UTF-8','ISO-8859-1',$rta);
	   // var_dump($a);
	   // var_dump($rta);
		   if ($a=='tamsrq' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('tamsrq','pro',event,'','lib.php',7,'TAMIZAJE RQC Y SRQ');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
	