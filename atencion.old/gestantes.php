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



function focus_pregnant(){
	return 'pregnant';
   }
   
   
   function men_pregnant(){
	$rta=cap_menus('pregnant','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = "";
	 $acc=rol($a);
	 if ($a=='pregnant' && isset($acc['crear']) && $acc['crear']=='SI') {  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	 
	 return $rta;
   }
}
function cmp_pregnant(){
    $rta="";
	$hoy=date('Y-m-d');
	 $t=['id'=>'','gestaciones'=>'','gestantes_tipo_doc'=>'','gestantes_documento'=>'','partos'=>'','abortos'=>'','cesarias'=>'','vivos'=>'','muertos'=>'','vacunaciongestante'=>'','edadgestacion'=>'','ultimagestacion'=>'','probableparto'=>'','prenatal'=>'','fechaparto'=>'','rpsicosocial'=>'','robstetrico'=>'','rtromboembo'=>'','rdepresion'=>'','sifilisgestacional'=>'','sifiliscongenita'=>'','morbilidad'=>'','hepatitisb'=>'','vih'=>'','gestantes_validacion1'=>'','gestantes_validacion2'=>'','gestantes_validacion3'=>'','gestantes_validacion4'=>'','gestantes_validacion5'=>'','gestantes_validacion6'=>'','gestantes_validacion7'=>'',	 'gestantes_validacion8'=>'','gestantes_validacion9'=>'','gestantes_validacion10'=>'','gestantes_validacion11'=>'','gestantes_validacion12'=>'']; 
	$w='gestantes';
	$id=divide($_POST['id']);
	 $d=get_pregnant(); 
//    $x="";
	if ($d=="") {
		$d=$t;
		$u=true;
		$rta.="<h1>Sin datos de Gestión en el plan de cuidado</h1>";  
	 }else{
		$u=false;  
	 } 
    $o='datos';
    $c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
    $c[]=new cmp('idgestantes','h',15,$d['id'],$w.' '.$o,'idgestantes','idgestantes',null,'####',false,false);
    $c[]=new cmp('gestantes_documento','t','20',$id['0'],$w.' '.$o,'N° Identificación','gestantes_documento',null,'',false,false,'','col-5');
	$c[]=new cmp('gestantes_tipo_doc','t','20',$id['1'],$w.' '.$o,'Tipo Identificación','gestantes_tipo_doc',null,'',false,false,'','col-5');


  $o='gesta';
	$c[]=new cmp($o,'e',null,'Gestante ',$w); 
	$c[]=new cmp('gestaciones','n',3,$d['gestaciones'],$w.' '.$o,'GESTACIONES','gestaciones',null,null,true,true,'','col-2');
	$c[]=new cmp('partos','n',3,$d['partos'],$w.' '.$o,'PARTOS','partos',null,null,true,true,'','col-2');
	$c[]=new cmp('abortos','n',3,$d['abortos'],$w.' '.$o,'ABORTOS','abortos',null,null,true,true,'','col-2');
	$c[]=new cmp('cesarias','n',3,$d['cesarias'],$w.' '.$o,'CESARIAS','cesarias',null,null,true,true,'','col-2','valid1(\'gestaciones\',[\'partos\',\'abortos\',\'cesarias\'])');
	$c[]=new cmp('vivos','n',3,$d['vivos'],$w.' '.$o,'VIVOS','vivos',null,null,true,true,'','col-1');
	$c[]=new cmp('muertos','n',3,$d['muertos'],$w.' '.$o,'MUERTOS','muertos',null,null,true,true,'','col-1','valid1(\'gestaciones\',[\'vivos\',\'muertos\'])');
	$c[]=new cmp('vacunaciongestante','o',3,$d['vacunaciongestante'],$w.' '.$o,'Esquema de vacunacion completo para la edad','vacunaciongestante',null,null,true,true,'','col-4');
	$c[]=new cmp('edadgestacion','n',3,$d['edadgestacion'],$w.' '.$o,'Edad Gestacional','edadgestacion',null,null,true,true,'','col-3');
	$c[]=new cmp('ultimagestacion','d',3,$d['ultimagestacion'],$w.' '.$o,'Fecha Última Gestacion','ultimagestacion',null,null,true,true,'','col-3');
	$c[]=new cmp('probableparto','d',3,$d['probableparto'],$w.' '.$o,'Fecha Probable Parto','probableparto',null,null,true,true,'','col-4');
	$c[]=new cmp('prenatal','n',3,$d['prenatal'],$w.' '.$o,'Semana inicio control prenatal','prenatal',null,null,true,true,'','col-3');
	$c[]=new cmp('fechaparto','d',3,$d['fechaparto'],$w.' '.$o,'fecha ultimo Parto','fechaparto',null,null,true,true,'','col-3');
	$c[]=new cmp('rpsicosocial','o',3,$d['rpsicosocial'],$w.' '.$o,'RIESGO PSICOSOCIAL','rpsicosocial',null,null,true,true,'','col-2');
	$c[]=new cmp('robstetrico','o',3,$d['robstetrico'],$w.' '.$o,'RIESGO OBSTETRICO','robstetrico',null,null,true,true,'','col-2');
	$c[]=new cmp('rtromboembo','o',3,$d['rtromboembo'],$w.' '.$o,'RIESGO TROMBOEMBOLICO','rtromboembo',null,null,true,true,'','col-3');
	$c[]=new cmp('rdepresion','o',3,$d['rdepresion'],$w.' '.$o,'RIESGO DEPRESION_POS_PARTO','rdepresion',null,null,true,true,'','col-3');
	$c[]=new cmp('sifilisgestacional','o',3,$d['sifilisgestacional'],$w.' '.$o,'Sífilis Gestacional','sifilisgestacional',null,null,true,true,'','col-2');
	$c[]=new cmp('sifiliscongenita','o',3,$d['sifiliscongenita'],$w.' '.$o,'Sífilis Congénita','sifiliscongenita',null,null,true,true,'','col-2');
	$c[]=new cmp('morbilidad','o',3,$d['morbilidad'],$w.' '.$o,'Morbilidad materna extrema','morbilidad',null,null,true,true,'','col-2');
	$c[]=new cmp('hepatitisb','o',3,$d['hepatitisb'],$w.' '.$o,'Hepatitis B','hepatitisb',null,null,true,true,'','col-2');
	$c[]=new cmp('vih','o',3,$d['vih'],$w.' '.$o,'VIH','vih',null,null,true,true,'','col-2');

  $o='preges';
	$c[]=new cmp($o,'e',null,'Gestante ',$w); 
  $c[]=new cmp('validacion1','o','2',$d['gestantes_validacion1'],$w.' '.$o,'1. ¿pregnante adherente a la ruta materno perinatal?','gestantes_validacion1',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion2','o','2',$d['gestantes_validacion2'],$w.' '.$o,'2. Indagar por alguna barrera lingüística o de acceso que impida a la pregnante adherencia a los controles prenatales; en caso tal, se deberá indicar la necesidad de albergues (casa de paso u hogares maternos) o facilitadores interculturales (intérpretes).','gestantes_validacion2',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion3','o','2',$d['gestantes_validacion3'],$w.' '.$o,'3. Verificar que la pregnante haya recibido asesoría sobre opciones durante el embarazo (debe informarse a la mujer sobre el derecho a la interrupción voluntaria del embarazo, en caso de configurarse una de las causales establecidas en la sentencia C355 de 2006.)','gestantes_validacion3',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion4','o','2',$d['gestantes_validacion4'],$w.' '.$o,'4. mujer sobre el derecho a la interrupción voluntaria del embarazo, en caso de configurarse una de las causales establecidas en la sentencia C355 de 2006.)','gestantes_validacion4',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion5','o','2',$d['gestantes_validacion5'],$w.' '.$o,'5. Si se sospecha exposición a violencias por parte de la pregnante,  se debe derivar a la Ruta Integral de Atención en Salud para la población con riesgo o víctima de violencia','gestantes_validacion5',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion6','o','2',$d['gestantes_validacion6'],$w.' '.$o,'6. Las mujeres embarazadas deben ser informadas de los riesgos potenciales para su salud y la de sus hijos debido al uso (incluso de mínimas cantidades) de alcohol y otras sustancias psicoactivas.','gestantes_validacion6',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion7','o','2',$d['gestantes_validacion7'],$w.' '.$o,'7. Se recomienda a la pregnante y la familia la necesidad de  tener hábitos y estilos de vida saludables: prevención de la exposición al humo y cesación de consumo de tabaco, organización de actividad física. ','gestantes_validacion7',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion8','o','2',$d['gestantes_validacion8'],$w.' '.$o,'8. De acuerdo con la anamnesis y examen físico generar las órdenes médicas respectivas para canalizar a la RMP','gestantes_validacion8',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion9','o','2',$d['gestantes_validacion9'],$w.' '.$o,'9. Las gestantes en las que se identifique factores de riesgo biopsicosociales, enfermedades asociadas y propias de la gestación deberán ser remitidas al especialista en Ginecoobstetricia','gestantes_validacion9',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion10','o','2',$d['gestantes_validacion10'],$w.' '.$o,'10. Verificar la formulación de micronutrientes (Ácido Fólico, Hierro y Calcio) y su ingesta adecuada.','gestantes_validacion10',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion11','o','2',$d['gestantes_validacion11'],$w.' '.$o,'11. Se verfica con la pregnante y la familia el cumplimiento al esquema de vacunación vigente y el antecedente vacunal y se recuerda la importancia tener los esquemas de vacunación completos (Toxoide tetánico diftérico del adulto, Influenza estacional a partir de la semana 14, Tétanos, difteria y Tos ferina acelular (Tdap) a partir de la semana 26).','gestantes_validacion11',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion12','o','2',$d['gestantes_validacion12'],$w.' '.$o,'12. Verificar que la Información en salud, dirigida a la pregnante y su acompañante, contenga como mínImo: - Los servicios de salud a los que tiene derecho y sus mecanismos de exigibilidad. - Promover los factores protectores para la salud de la gestante, tales como medidas higiénicas, hábitos alimentarios, actividad física recomendada, sueño, fortalecimiento redes de apoyo familiar y social. - Orientar sobre los signos de alarma por los que debe consultar oportunamente. - Importancia de la asistencia al curso de preparación para la maternidad y paternidad.','gestantes_validacion12',null,'',true,$u,'','col-10');
    for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
    
    return $rta;
     }

     function get_pregnant(){
        if($_REQUEST['id']==''){
            return "";
        }else{
             $id=divide($_REQUEST['id']);
            $sql="SELECT concat(gestantes_tipo_doc,'_',gestantes_documento) id,`gestantes_tipo_doc`, `gestantes_documento`,gestaciones,partos,abortos,cesarias,vivos,muertos,vacunaciongestante,edadgestacion,ultimagestacion,probableparto,prenatal,fechaparto,rpsicosocial,robstetrico,rtromboembo,rdepresion,sifilisgestacional,sifiliscongenita,morbilidad,hepatitisb,vih, `gestantes_validacion1`, `gestantes_validacion2`, `gestantes_validacion3`, `gestantes_validacion4`, `gestantes_validacion5`, `gestantes_validacion6`, `gestantes_validacion7`, `gestantes_validacion8`, `gestantes_validacion9`, `gestantes_validacion10`, `gestantes_validacion11`, `gestantes_validacion12`,
			`usu_creo`, `fecha_create`, `usu_update`, `fecha_update` 
            FROM `eac_gestantes`
            WHERE gestantes_tipo_doc ='{$id[1]}' AND gestantes_documento ='{$id[0]}'  ";
             //echo $sql;
            $info=datos_mysql($sql);
            if(isset($info['responseResult'][0])){ 
                    return $info['responseResult'][0];
            }else{
                return "";
            }
        } 
    }

    function gra_pregnant(){
        $gestantes_documento=$_POST['gestantes_documento'];
        $gestantes_tipo_doc=$_POST['gestantes_tipo_doc'];
        $idgestantes=$_POST['idgestantes'];
        //print_r($_POST);
        //die("Ok");
        if($idgestantes != "" ){ 
        
        $sql="UPDATE `eac_gestantes` SET
            gestaciones = TRIM(UPPER('{$_POST['gestaciones']}')),
			partos = TRIM(UPPER('{$_POST['partos']}')),
			abortos = TRIM(UPPER('{$_POST['abortos']}')),
			cesarias =TRIM(UPPER('{$_POST['cesarias']}')),
			vivos = vivos = TRIM(UPPER('{$_POST['vivos']}')),
			muertos = TRIM(UPPER('{$_POST['muertos']}')),
			vacunaciongestante = TRIM(UPPER('{$_POST['vacunaciongestante']}')),
			edadgestacion = TRIM(UPPER('{$_POST['edadgestacion']}')),
			ultimagestacion = '{$_POST['ultimagestacion']}',
			probableparto = '{$_POST['probableparto']}',
			prenatal = TRIM(UPPER('{$_POST['prenatal']}')),
			fechaparto = '{$_POST['fechaparto']}',
			rpsicosocial = TRIM(UPPER('{$_POST['rpsicosocial']}')),
			robstetrico = TRIM(UPPER('{$_POST['robstetrico']}')),
			rtromboembo = TRIM(UPPER('{$_POST['rtromboembo']}')),
			rdepresion = TRIM(UPPER('{$_POST['rdepresion']}')),
			sifilisgestacional = TRIM(UPPER('{$_POST['sifilisgestacional']}')),
			sifiliscongenita = TRIM(UPPER('{$_POST['sifiliscongenita']}')),
			morbilidad = TRIM(UPPER('{$_POST['morbilidad']}')),
			hepatitisb = TRIM(UPPER('{$_POST['hepatitisb']}')),
			vih = TRIM(UPPER('{$_POST['vih']}')),
        
            `gestantes_validacion1`=TRIM(UPPER('{$_POST['validacion1']}')),
            `gestantes_validacion2`=TRIM(UPPER('{$_POST['validacion2']}')),
            `gestantes_validacion3`=TRIM(UPPER('{$_POST['validacion3']}')),
            `gestantes_validacion4`=TRIM(UPPER('{$_POST['validacion4']}')),
            `gestantes_validacion5`=TRIM(UPPER('{$_POST['validacion5']}')),
            `gestantes_validacion6`=TRIM(UPPER('{$_POST['validacion6']}')),
            `gestantes_validacion7`=TRIM(UPPER('{$_POST['validacion7']}')),
            `gestantes_validacion8`=TRIM(UPPER('{$_POST['validacion8']}')),
            `gestantes_validacion9`=TRIM(UPPER('{$_POST['validacion9']}')),
            `gestantes_validacion10`=TRIM(UPPER('{$_POST['validacion10']}')),
            `gestantes_validacion11`=TRIM(UPPER('{$_POST['validacion11']}')),
            `gestantes_validacion12`=TRIM(UPPER('{$_POST['validacion12']}')),

            
			

            
            `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
            `fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
            WHERE gestantes_tipo_doc='$gestantes_tipo_doc' AND gestantes_documento='$gestantes_documento'"; 
          //echo $x;
          //echo $sql."    ".$rta;
    
        }else{

                
			      $gestaciones = isset($_POST['gestaciones']) ? trim($_POST['gestaciones']) : '';
			      $partos = isset($_POST['partos']) ? trim($_POST['partos']) : '';
			      $abortos = isset($_POST['abortos']) ? trim($_POST['abortos']) : '';
			      $cesarias = isset($_POST['cesarias']) ? trim($_POST['cesarias']) : '';
			      $vivos = isset($_POST['vivos']) ? trim($_POST['vivos']) : '';
			      $muertos = isset($_POST['muertos']) ? trim($_POST['muertos']) : '';
			      $vacunaciongestante = isset($_POST['vacunaciongestante']) ? trim($_POST['vacunaciongestante']) : '';
			      $edadgestacion = isset($_POST['edadgestacion']) ? trim($_POST['edadgestacion']) : '';
			      $ultimagestacion = isset($_POST['ultimagestacion']) ? trim($_POST['ultimagestacion']) : '';
			      $probableparto = isset($_POST['probableparto']) ? trim($_POST['probableparto']) : '';
			      $prenatal = isset($_POST['prenatal']) ? trim($_POST['prenatal']) : '';
			      $fechaparto = isset($_POST['fechaparto']) ? trim($_POST['fechaparto']) : '';
			      $rpsicosocial = isset($_POST['rpsicosocial']) ? trim($_POST['rpsicosocial']) : '';
			      $robstetrico = isset($_POST['robstetrico']) ? trim($_POST['robstetrico']) : '';
			      $rtromboembo = isset($_POST['rtromboembo']) ? trim($_POST['rtromboembo']) : '';
			      $rdepresion = isset($_POST['rdepresion']) ? trim($_POST['rdepresion']) : '';
			      $sifilisgestacional = isset($_POST['sifilisgestacional']) ? trim($_POST['sifilisgestacional']) : '';
			      $sifiliscongenita = isset($_POST['sifiliscongenita']) ? trim($_POST['sifiliscongenita']) : '';
			      $morbilidad = isset($_POST['morbilidad']) ? trim($_POST['morbilidad']) : '';
			      $hepatitisb = isset($_POST['hepatitisb']) ? trim($_POST['hepatitisb']) : '';
			      $vih = isset($_POST['vih']) ? trim($_POST['vih']) : '';
    
            $sql="INSERT INTO eac_gestantes VALUES (NULL,
            TRIM(UPPER('{$_POST['gestantes_tipo_doc']}')),
            TRIM(UPPER('{$_POST['gestantes_documento']}')),
            TRIM(UPPER('{$gestaciones}')),
	          TRIM(UPPER('{$partos}')),
	          TRIM(UPPER('{$abortos}')),
	          TRIM(UPPER('{$cesarias}')),
	          TRIM(UPPER('{$vivos}')),
	          TRIM(UPPER('{$muertos}')),
	          TRIM(UPPER('{$vacunaciongestante}')),
	          TRIM(UPPER('{$edadgestacion}')),
	          TRIM(UPPER('{$ultimagestacion}')),
	          TRIM(UPPER('{$probableparto}')),
	          TRIM(UPPER('{$prenatal}')),
	          TRIM(UPPER('{$fechaparto}')),
	          TRIM(UPPER('{$rpsicosocial}')),
	          TRIM(UPPER('{$robstetrico}')),
	          TRIM(UPPER('{$rtromboembo}')),
	          TRIM(UPPER('{$rdepresion}')),
	          TRIM(UPPER('{$sifilisgestacional}')),
	          TRIM(UPPER('{$sifiliscongenita}')),
	          TRIM(UPPER('{$morbilidad}')),
	          TRIM(UPPER('{$hepatitisb}')),
	          TRIM(UPPER('{$vih}')),
            
            TRIM(UPPER('{$_POST['validacion1']}')),
            TRIM(UPPER('{$_POST['validacion2']}')),
            TRIM(UPPER('{$_POST['validacion3']}')),
            TRIM(UPPER('{$_POST['validacion4']}')),
            TRIM(UPPER('{$_POST['validacion5']}')),
            TRIM(UPPER('{$_POST['validacion6']}')),
            TRIM(UPPER('{$_POST['validacion7']}')),
            TRIM(UPPER('{$_POST['validacion8']}')),
            TRIM(UPPER('{$_POST['validacion9']}')),
            TRIM(UPPER('{$_POST['validacion10']}')),
            TRIM(UPPER('{$_POST['validacion11']}')),
            TRIM(UPPER('{$_POST['validacion12']}')),
           
	          
            TRIM(UPPER('{$_SESSION['us_sds']}')),
            DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
            //echo $sql;
    
        }
          $rta=dato_mysql($sql);
        //   return "correctamente";
          return $rta; 
      }


function opc_adolecencia_tipo_doc($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=1 and estado='A' ORDER BY 1",$id);
}   

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   