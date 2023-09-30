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



function focus_adolesce(){
	return 'adolesce';
   }
   
   
   function men_adolesce(){
	$rta=cap_menus('adolesce','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = "";
	 $acc=rol($a);
  if ($a=='adolesce' && isset($acc['crear']) && $acc['crear']=='SI') {  
  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	 
	 return $rta;
   }
}
   function cmp_adolesce(){
    $rta="";
    $hoy=date('Y-m-d');
    $t=['id'=>'','adolecencia_tipo_doc'=>'','adolecencia_documento'=>'','preg1'=>'','preg2'=>'','preg3'=>'','preg4'=>'','preg5'=>'','preg6'=>'','preg7'=>'','preg8'=>'','preg9'=>'','preg10'=>'','preg11'=>'','preg12'=>'','preg13'=>'','preg14'=>'']; 
    $w='adolesce';
    $key=divide($_POST['id']);
    // var_dump($key);
    $d=get_adolesce(); 
    if ($d==""){
      $d=$t;
      $u=true;
      $rta.="<h1>Sin datos de Gestión en el plan de cuidado</h1>";
    }else{
      $u=false;
    } 
    $o='datos';
    $c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
    $c[]=new cmp('id','h',15,$d['id'],$w.' '.$o,'idenfermedades','idenfermedades',null,'####',false,false);
    $c[]=new cmp('adolecencia_tipo_doc','t','20',$key['1'],$w.' '.$o,'Tipo Identificación','enfermedades_tipo_doc',null,'',false,false,'','col-5');
    $c[]=new cmp('adolecencia_documento','t','20',$key['0'],$w.' '.$o,'N° Identificación','enfermedades_documento',null,'',false,false,'','col-5');

    $c[]=new cmp($o,'e',null,'3. ADOLESCENCIA (Dirigido a todos los adolescentes de 12 a 17 años, 11 meses y 29 días - Res. 3280 - 2018)',$w);
    $c[]=new cmp('preg1','o','2',$d['preg1'],$w.' '.$o,'1. Se verfica con la familia el cumplimiento al esquema de vacunación vigente y el antecedente vacunal y se recuerda la importancia tener los esquemas de vacunaciòn completos.','preg1',null,null,true,true,'','col-10');
    $c[]=new cmp('preg2','o','2',$d['preg2'],$w.' '.$o,'2. se verifica si la menor es mujer que ya cuente con la vacunacion para VPH.','preg2',null,null,true,true,'','col-10');
    $c[]=new cmp('preg3','o','2',$d['preg3'],$w.' '.$o,'3. Se recomienda  a la familia que el adolescente debe tener hábitos y estilos de vida saludables (prevención de la exposición al humo y cesaciòn de consumo de tabaco), de prácticas deportivas organizadas, de actividad física y evitación del sedentarismo','preg3',null,null,true,true,'','col-10');
    $c[]=new cmp('preg4','o','2',$d['preg4'],$w.' '.$o,'4. Se recomienda a la familia fortalecer una alimentaciòn sana para el adolescente,  deben incluir alimentos como las verduras, arroz, pastas, legumbre, carne, huevos y frutas. Es conveniente evitar el consumo excesivo de azúcares porque pueden acarrear problemas de obesidad y dentales.','preg4',null,null,true,true,'','col-10');
    $c[]=new cmp('preg5','o','2',$d['preg5'],$w.' '.$o,'5. Se recomienda generar espacios de confianza familiar para explorar temas y debatir sobre ellos: derechos sexuales y reproductivos, construcciòn de identidad, reconocimiento de emociones, comportamiento social y desarrollo de la personalidad, entre otros','preg5',null,null,true,true,'','col-10');
    $c[]=new cmp('preg6','o','2',$d['preg6'],$w.' '.$o,'6. Se recomienda a la  familia  ser soporte de informaciòn para el adolescente y generar una comunicaciòn asertiva y constructiva.','preg6',null,null,true,true,'','col-10');
    $c[]=new cmp('preg7','o','2',$d['preg7'],$w.' '.$o,'7. Se recomienda a la familia en cuidado de oìdo y la visiòn, evitar el uso prolongado de televisiòn, computadores y otras pantallas. ','preg7',null,null,true,true,'','col-10');
    $c[]=new cmp('preg8','o','2',$d['preg8'],$w.' '.$o,'8. se verifica si el menor ya cuenta con las actividades de salud oral a las que tiene derecho en el marco de la ruta de promocion y mantenimiento de la salud.','preg8',null,null,true,true,'','col-10');
    $c[]=new cmp('preg9','o','2',$d['preg9'],$w.' '.$o,'9. Se recuerda la familia la importancia del cuidado menstrual.','preg9',null,null,true,true,'','col-10');
    $c[]=new cmp('preg10','o','2',$d['preg10'],$w.' '.$o,'10. Se verifica si el menor cuenta con riesgo de ITS y ya se encuentra tamizado para estos riesgos en el marco de la ruta de promocion y mantenimiento de la salud.','preg10',null,null,true,true,'','col-10');
    $c[]=new cmp('preg11','o','2',$d['preg11'],$w.' '.$o,'11. se verifica si la menor es mujer y tiene entre los 12 y 17 años 11 meses y 29 dias ya se cuenta con el tamizaje de hemoglobina por el riesgo de anemia en esta poblacion.','preg11',null,null,true,true,'','col-10');
    $c[]=new cmp('preg12','o','2',$d['preg12'],$w.' '.$o,'12. Se orienta para la atención para la planificación familiar y la anticoncepción.','preg12',null,null,true,true,'','col-10');
    $c[]=new cmp('preg13','o','2',$d['preg13'],$w.' '.$o,'13. Se orienta para la atención para el cuidado preconcepcional.','preg13',null,null,true,true,'','col-10');
    $c[]=new cmp('preg14','o','2',$d['preg14'],$w.' '.$o,'14. se verifica o se ordena las valoraciones de agudeza visual en el marco de la ruta de promocion y mantenimiento de la salud de acuerdo al curso de vida.','preg14',null,null,true,true,'','col-10');

    for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
    
    return $rta;
     }

     function get_adolesce(){
      if($_REQUEST['id']==''){
        return "";
      }else{
         $id=divide($_REQUEST['id']);
        $sql="SELECT 
        concat(adolecencia_tipo_doc,'_',adolecencia_documento) id,
        preg1,preg2,preg3,preg4,preg5,preg6,preg7,preg8,preg9,preg10,preg11,preg12,preg13,preg14
        FROM `eac_adolescencia`
        WHERE adolecencia_tipo_doc ='{$id[1]}' AND adolecencia_documento ='{$id[0]}'  ";
        // echo $sql;
        $info=datos_mysql($sql);
        if(isset($info['responseResult'][0])){ 
            return $info['responseResult'][0];
        }else{
          return "";
        }
      } 
    }

    function gra_adolesce(){
      $id=divide($_POST['id']);
      // print_r($id);
      //die("Ok");
      if($_POST['id']!=''){
      $sql="UPDATE `eac_adolescencia` SET 
      preg1=trim(upper('{$_POST['preg1']}')),
      preg2=trim(upper('{$_POST['preg2']}')),
      preg3=trim(upper('{$_POST['preg3']}')),
      preg4=trim(upper('{$_POST['preg4']}')),
      preg5=trim(upper('{$_POST['preg5']}')),
      preg6=trim(upper('{$_POST['preg6']}')),
      preg7=trim(upper('{$_POST['preg7']}')),
      preg8=trim(upper('{$_POST['preg8']}')),
      preg9=trim(upper('{$_POST['preg9']}')),
      preg10=trim(upper('{$_POST['preg10']}')),
      preg11=trim(upper('{$_POST['preg11']}')),
      preg12=trim(upper('{$_POST['preg12']}')),
      preg13=trim(upper('{$_POST['preg13']}')),
      preg14=trim(upper('{$_POST['preg14']}')),
        `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
        WHERE adolecencia_tipo_doc='$id[0]' AND adolecencia_documento='$id[1]'"; 
        // echo $x;
 //echo $sql;
      }else{
        $sql="INSERT INTO eac_adolescencia VALUES (
        trim(upper('{$_POST['adolecencia_tipo_doc']}')),
        trim(upper('{$_POST['adolecencia_documento']}')),
        trim(upper('{$_POST['preg1']}')),
        trim(upper('{$_POST['preg2']}')),
        trim(upper('{$_POST['preg3']}')),
        trim(upper('{$_POST['preg4']}')),
        trim(upper('{$_POST['preg5']}')),
        trim(upper('{$_POST['preg6']}')),
        trim(upper('{$_POST['preg7']}')),
        trim(upper('{$_POST['preg8']}')),
        trim(upper('{$_POST['preg9']}')),
        trim(upper('{$_POST['preg10']}')),
        trim(upper('{$_POST['preg11']}')),
        trim(upper('{$_POST['preg12']}')),
        trim(upper('{$_POST['preg13']}')),
        trim(upper('{$_POST['preg14']}')),
        TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
        // echo $sql;
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
	   