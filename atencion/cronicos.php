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



function focus_prechronic(){
	return 'prechronic';
   }
   
   
   function men_prechronic(){
	$rta=cap_menus('prechronic','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = "";
	 $acc=rol($a);
     if ($a=='prechronic' && isset($acc['crear']) && $acc['crear']=='SI') {  
 
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	 
	 return $rta;
   }
   } 

function cmp_prechronic(){
    $rta="";
	$hoy=date('Y-m-d');
	 $t=['id'=>'','enfermedades_tipo_doc'=>'','enfermedades_documento'=>'','dx_hiper'=>'','dx_diabe'=>'','dx_epoc'=>'','asiste_control'=>'','vacu_comple'=>'','hemoglobina'=>'','fecha_hemo'=>'','resul_hemo'=>'','morisky_pre1'=>'','morisky_pre2'=>'','morisky_pre3'=>'','morisky_pre4'=>'','adher_trata'=>'','enfermedades_validacion1'=>'','enfermedades_validacion2'=>'','enfermedades_validacion3'=>'','enfermedades_validacion4'=>'','enfermedades_validacion5'=>'','enfermedades_validacion6'=>'','enfermedades_validacion7'=>'',	 'enfermedades_validacion8'=>'','enfermedades_validacion9'=>'','enfermedades_validacion10'=>'','enfermedades_validacion11'=>'','enfermedades_validacion12'=>'','enfermedades_validacion13'=>'','enfermedades_validacion14'=>'','enfermedades_validacion15'=>'','enfermedades_validacion16'=>'','enfermedades_validacion17'=>'','enfermedades_validacion18'=>'']; 
	$w='prechronic';
	$id=divide($_POST['id']);
	 $d=get_prechronic(); 
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
    $c[]=new cmp('idchronic','h',15,$d['id'],$w.' '.$o,'idenfermedades','idenfermedades',null,'####',false,false);
	$c[]=new cmp('enfermedades_documento','t','20',$id['0'],$w.' '.$o,'N° Identificación','enfermedades_documento',null,'',false,false,'','col-5');
	$c[]=new cmp('enfermedades_tipo_doc','t','20',$id['1'],$w.' '.$o,'Tipo Identificación','enfermedades_tipo_doc',null,'',false,false,'','col-5');


  $o='cron';
	$c[]=new cmp($o,'e',null,'Cronico ',$w);
	$c[]=new cmp('dx_hiper','s',3,$d['dx_hiper'],$w.' '.$o,'¿ Usuario con Hipertensión?','aler',null,'',true,$u,'','col-35');
	$c[]=new cmp('dx_diabe','s',3,$d['dx_diabe'],$w.' '.$o,'Usuario con Diabetes','aler',null,'',true,$u,'','col-35',"enabDiab(this,'diab');");
	$c[]=new cmp('dx_epoc','s',3,$d['dx_epoc'],$w.' '.$o,'Usuario con Epoc','aler',null,'',true,$u,'','col-3');
	$c[]=new cmp('atencion_asistenciacronica','s',3,$d['asiste_control'],$w.' '.$o,'a asistido a controles medicos en los ultimos 6 meses para la patologia','aler',null,'',false,$u,'','col-5');
	$c[]=new cmp('atencion_vacunascronico','s',3,$d['vacu_comple'],$w.' '.$o,'Esquema de vacunacion completo para la edad','aler',null,'',false,$u,'','col-5');
   
    $c[]=new cmp('hemoglobina','s',3,$d['hemoglobina'],$w.' diab '.$o,'Hemoglobina Glicosilada','aler',null,'',true,$u,'','col-35',"enabHemo(this,'hem');");
    $c[]=new cmp('fecha_hemo','d',3,$d['fecha_hemo'],$w.' hem '.$o,'Fecha Toma Hemoglobina Glicosilada','fecha_hemo',null,'',false,false,'','col-35');
    $c[]=new cmp('resul_hemo','t',5,$d['resul_hemo'],$w.' hem '.$o,'Resultado Hemoglobina Glicosilada','resul_hemo',null,'',false,false,'','col-3');
 $o='mori';
	$c[]=new cmp($o,'e',null,'Tamizaje Morisky ',$w);
    $c[]=new cmp('morisky_pre1','s','2',$d['morisky_pre1'],$w.' mor '.$o,'¿olvida tomar los medicamentos para tratar su enfermedad?','aler',null,'',true,$u,'','col-25',"rtaMoris('mor','atencion_tratamiento');");	
	$c[]=new cmp('morisky_pre2','s','2',$d['morisky_pre2'],$w.' mor '.$o,'¿Olvida tomar los medicamentos a las horas indicadas?','aler',null,'',true,$u,'','col-25',"rtaMoris('mor','atencion_tratamiento');");	
	$c[]=new cmp('morisky_pre3','s','2',$d['morisky_pre3'],$w.' mor '.$o,'Cuando se encuentra bien ¿Deja de tomar la medicación?','aler',null,'',true,$u,'','col-25',"rtaMoris('mor','atencion_tratamiento');");	
	$c[]=new cmp('morisky_pre4','s','2',$d['morisky_pre4'],$w.' mor '.$o,'Si alguna vez le sienta mal ¿deja ud de tomar la medicación?','aler',null,'',true,$u,'','col-25',"rtaMoris('mor','atencion_tratamiento');");	
	$c[]=new cmp('atencion_tratamiento','t',3,$d['adher_trata'],$w.' '.$o,'Es adherente al tratamiento','atencion_tratamiento',null,'',false,false,'','col-2');
	
	
  $o='precron';
	$c[]=new cmp($o,'e',null,'Cronicos ',$w); 
    $c[]=new cmp('validacion1','o','2',$d['enfermedades_validacion1'],$w.' '.$o,'1. ¿Usuario adherente a la ruta CCVM preguntar si asiste de manera periodica a las atenciones de la ruta si asiste a los talleres o programas de la subred.','enfermedades_validacion1',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion2','o','2',$d['enfermedades_validacion2'],$w.' '.$o,'2. Indagar por alguna barrera lingüística o de acceso que impida al paciente su adherencia al tratamiento; en caso tal, se deberá indicar la necesidad de albergues (casa de paso u hogares maternos) o facilitadores interculturales (intérpretes).','enfermedades_validacion2',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion3','o','2',$d['enfermedades_validacion3'],$w.' '.$o,'3. Orientar hacia la atención en salud bucal por profesional de odontología.','enfermedades_validacion3',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion4','o','2',$d['enfermedades_validacion4'],$w.' '.$o,'4. Tamización para riesgo cardiovascular y metabólico si no cuenta con el al menos en los ultimos 4 meses en el caso de los usuarios hipertensos o diabeticos.','enfermedades_validacion4',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion5','o','2',$d['enfermedades_validacion5'],$w.' '.$o,'5. Tamización para cáncer (cáncer de cuello uterino, mama, próstata y de colon y recto) de acuerdo a la periodicidad establecida en la ruta de promocion y mantenimiento de la salud.','enfermedades_validacion5',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion6','o','2',$d['enfermedades_validacion6'],$w.' '.$o,'6. se verifica o se ordena la valoracion de agudeza visual en el marco de la ruta de promocion y mantenimiento de la salud de acuerdo al curso de vida ','enfermedades_validacion6',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion7','o','2',$d['enfermedades_validacion7'],$w.' '.$o,'7. Educación grupal para la salud de acuerdo al ciclo contemplado según la edad.','enfermedades_validacion7',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion8','o','2',$d['enfermedades_validacion8'],$w.' '.$o,'8. Se orienta hacia la consulta de asesoría en anticoncepción cuando el usuario desea iniciar o cambiar algún método de anticoncepción, o para realizar su control (si aplica de acuerdo con la edad).','enfermedades_validacion8',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion9','o','2',$d['enfermedades_validacion9'],$w.' '.$o,'9. Se orienta para la atención para el cuidado preconcepcional (si aplica de acuerdo con la edad).','enfermedades_validacion9',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion10','o','2',$d['enfermedades_validacion10'],$w.' '.$o,'10. Educación individual para la salud por perfiles requeridos, según los hallazgos, enfermedades en curso, necesidades, intereses e inquietudes, incluyendo practicas en salud mental.','enfermedades_validacion10',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion11','o','2',$d['enfermedades_validacion11'],$w.' '.$o,'11. Orientar al paciente y su familia en la generación de planes de tratamiento para disminuir el deterioro cognoscitivo, Inmovilidad, Inestabilidad y riesgo de caídas, Fragilidad, Incontinencia de esfínteres, patología mental, alteraciones en la salud sexual, etc, si se  identificó.','enfermedades_validacion11',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion12','o','2',$d['enfermedades_validacion12'],$w.' '.$o,'12. Derivar a la Ruta Integral de Atención en Salud para la población con riesgo o alteraciones nutricionales si se identifican factores de riesgo o alteraciones nutricionales','enfermedades_validacion12',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion13','o','2',$d['enfermedades_validacion13'],$w.' '.$o,'13. Se verifica con la familia el cumplimiento al esquema de vacunación vigente y el antecedente vacunal y se recuerda la importancia tener los esquemas de vacunación completos.','enfermedades_validacion13',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion14','o','2',$d['enfermedades_validacion14'],$w.' '.$o,'14. Se recomienda al paciente y la familia la necesidad de  tener hábitos y estilos de vida saludables: prevención de la exposición al humo y cesación de consumo de tabaco, organización de actividad física. ','enfermedades_validacion14',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion15','o','2',$d['enfermedades_validacion15'],$w.' '.$o,'15. Se debe informar al paciente y la familia de los riesgos potenciales para su salud debido al uso (incluso de mínimas cantidades) de alcohol y otras sustancias psicoactivas.','enfermedades_validacion15',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion16','o','2',$d['enfermedades_validacion16'],$w.' '.$o,'16. Concertar con el paciente y la familia las metas a cumplir con el tratamiento en el corto y mediano y plazo.','enfermedades_validacion16',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion17','o','2',$d['enfermedades_validacion17'],$w.' '.$o,'17. Orientar y educar al paciente y la familia sobre la necesidad de la adherencia y continuidad del tratamiento. ','enfermedades_validacion17',null,'',true,$u,'','col-10');
	$c[]=new cmp('validacion18','o','2',$d['enfermedades_validacion18'],$w.' '.$o,'18. Enseñar al paciente y la familia en la identificación de signos de alarma y la derivación hacia servicos hospitalarios y/o urgencias cuando se requiera.','enfermedades_validacion18',null,'',true,$u,'','col-10');
	
	
    for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
    
    return $rta;
     }
     
     

     function get_prechronic(){
        if($_REQUEST['id']==''){
            return "";
        }else{
             $id=divide($_REQUEST['id']);
            $sql="SELECT concat(enfermedades_tipo_doc,'_',enfermedades_documento) id,`enfermedades_tipo_doc`, `enfermedades_documento`, `dx_hiper`,`dx_diabe`,`dx_epoc`,asiste_control,vacu_comple, `hemoglobina`,`fecha_hemo`,`resul_hemo`,`morisky_pre1`, `morisky_pre2`, `morisky_pre3`,morisky_pre4,adher_trata,`enfermedades_validacion1`, `enfermedades_validacion2`, `enfermedades_validacion3`, `enfermedades_validacion4`, `enfermedades_validacion5`, `enfermedades_validacion6`, `enfermedades_validacion7`, `enfermedades_validacion8`, `enfermedades_validacion9`, `enfermedades_validacion10`, `enfermedades_validacion11`, `enfermedades_validacion12`,`enfermedades_validacion13`, `enfermedades_validacion14`, `enfermedades_validacion15`, `enfermedades_validacion16`, `enfermedades_validacion17`, `enfermedades_validacion18`,
			      `usu_creo`, `fecha_create`, `usu_update`, `fecha_update` 
            FROM `eac_enfermedades`
            WHERE enfermedades_tipo_doc ='{$id[1]}' AND enfermedades_documento ='{$id[0]}'  ";
             //echo $sql;
            $info=datos_mysql($sql);
            if(isset($info['responseResult'][0])){ 
                    return $info['responseResult'][0];
            }else{
                return "";
            }
        } 
    }

    function gra_prechronic(){
        $enfermedades_documento=$_POST['enfermedades_documento'];
        $enfermedades_tipo_doc=$_POST['enfermedades_tipo_doc'];
        $idchronic=$_POST['idchronic'];
        // print_r($_POST);
        //die("Ok");
        if($idchronic != "" ){ 
        
        $sql="UPDATE `eac_enfermedades` SET 
            `dx_hiper`=TRIM(UPPER('{$_POST['dx_hiper']}')),
            `dx_diabe`=TRIM(UPPER('{$_POST['dx_diabe']}')),
            `dx_epoc`=TRIM(UPPER('{$_POST['dx_epoc']}')),
            `asiste_control`=TRIM(UPPER('{$_POST['atencion_asistenciacronica']}')),
            `vacu_comple`=TRIM(UPPER('{$_POST['atencion_vacunascronico']}')),
            `hemoglobina`=TRIM(UPPER('{$_POST['hemoglobina']}')),
            `fecha_hemo`=TRIM(UPPER('{$_POST['fecha_hemo']}')),
            `resul_hemo`=TRIM(UPPER('{$_POST['resul_hemo']}')),
            `morisky_pre1`=TRIM(UPPER('{$_POST['morisky_pre1']}')),
            `morisky_pre2`=TRIM(UPPER('{$_POST['morisky_pre2']}')),
            `morisky_pre3`=TRIM(UPPER('{$_POST['morisky_pre3']}')),
            `morisky_pre4`=TRIM(UPPER('{$_POST['morisky_pre4']}')),
            
            `adher_trata`=TRIM(UPPER('{$_POST['atencion_tratamiento']}')),
            
            
             `enfermedades_validacion1`=TRIM(UPPER('{$_POST['validacion1']}')),
            `enfermedades_validacion2`=TRIM(UPPER('{$_POST['validacion2']}')),
            `enfermedades_validacion3`=TRIM(UPPER('{$_POST['validacion3']}')),
            `enfermedades_validacion4`=TRIM(UPPER('{$_POST['validacion4']}')),
            `enfermedades_validacion5`=TRIM(UPPER('{$_POST['validacion5']}')),
            `enfermedades_validacion6`=TRIM(UPPER('{$_POST['validacion6']}')),
            `enfermedades_validacion7`=TRIM(UPPER('{$_POST['validacion7']}')),
            `enfermedades_validacion8`=TRIM(UPPER('{$_POST['validacion8']}')),
            `enfermedades_validacion9`=TRIM(UPPER('{$_POST['validacion9']}')),
            `enfermedades_validacion10`=TRIM(UPPER('{$_POST['validacion10']}')),
            `enfermedades_validacion11`=TRIM(UPPER('{$_POST['validacion11']}')),
            `enfermedades_validacion12`=TRIM(UPPER('{$_POST['validacion12']}')),
            `enfermedades_validacion13`=TRIM(UPPER('{$_POST['validacion13']}')),
            `enfermedades_validacion14`=TRIM(UPPER('{$_POST['validacion14']}')),
            `enfermedades_validacion15`=TRIM(UPPER('{$_POST['validacion15']}')),
            `enfermedades_validacion16`=TRIM(UPPER('{$_POST['validacion16']}')),
            `enfermedades_validacion17`=TRIM(UPPER('{$_POST['validacion17']}')),
            `enfermedades_validacion18`=TRIM(UPPER('{$_POST['validacion18']}')),

            `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
            `fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
            WHERE enfermedades_tipo_doc='$enfermedades_tipo_doc' AND enfermedades_documento='$enfermedades_documento'"; 
          //echo $x;
          //echo $sql."    ".$rta;
    
        }else{

            $sql="INSERT INTO eac_enfermedades VALUES (
            TRIM(UPPER('{$_POST['enfermedades_tipo_doc']}')),
            TRIM(UPPER('{$_POST['enfermedades_documento']}')),
            TRIM(UPPER('{$_POST['dx_hiper']}')),
            TRIM(UPPER('{$_POST['dx_diabe']}')),
            TRIM(UPPER('{$_POST['dx_epoc']}')),
            TRIM(UPPER('{$_POST['atencion_asistenciacronica']}')),
            TRIM(UPPER('{$_POST['atencion_vacunascronico']}')),
            TRIM(UPPER('{$_POST['hemoglobina']}')),
            TRIM(UPPER('{$_POST['fecha_hemo']}')),
            TRIM(UPPER('{$_POST['resul_hemo']}')),
            TRIM(UPPER('{$_POST['morisky_pre1']}')),
            TRIM(UPPER('{$_POST['morisky_pre2']}')),
            TRIM(UPPER('{$_POST['morisky_pre3']}')),
            TRIM(UPPER('{$_POST['morisky_pre4']}')),
            TRIM(UPPER('{$_POST['atencion_tratamiento']}')),
            
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
            TRIM(UPPER('{$_POST['validacion13']}')),
            TRIM(UPPER('{$_POST['validacion14']}')),
            TRIM(UPPER('{$_POST['validacion15']}')),
            TRIM(UPPER('{$_POST['validacion16']}')),
            TRIM(UPPER('{$_POST['validacion17']}')),
            TRIM(UPPER('{$_POST['validacion18']}')),

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
function opc_aler($id=''){
	return opc_sql("SELECT `descripcion`,descripcion,valor FROM `catadeta` WHERE idcatalogo=170 and estado='A'  ORDER BY 1 ",$id);
}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   