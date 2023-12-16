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



function focus_infancia(){
	return 'infancia';
   }
   
   
   function men_infancia(){
	$rta=cap_menus('infancia','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = "";
	 $acc=rol($a);
	 if ($a=='infancia' && isset($acc['crear']) && $acc['crear']=='SI') {  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	 
	 return $rta;
   }
  }

   function cmp_infancia(){
    $rta="";
    $hoy=date('Y-m-d');
    $t=['id'=>'','infancia_tipo_doc'=>'','infancia_documento'=>'','infancia_validacion1'=>'','infancia_validacion2'=>'','infancia_validacion3'=>'','infancia_validacion4'=>'','infancia_validacion5'=>'','infancia_validacion6'=>'','infancia_validacion7'=>'','infancia_validacion8'=>'','infancia_validacion9'=>'','infancia_validacion10'=>'','infancia_validacion11'=>'','infancia_validacion12'=>'',]; 
    $w='infancia';
    $key=divide($_POST['id']);
    $d=get_infancia(); 
    if ($d=="") {
      $d=$t;
      $u=true;  
      $rta.="<h1>Sin datos de Gestión en el plan de cuidado</h1>";
    }else{
      $u=false;  
    } 
    $o='datos';
    $c[]=new cmp($o,'e',null,'DATOS DE IDENTIFICACIÓN',$w);
    $c[]=new cmp('id','h',15,$d['id'],$w.' '.$o,'idenfermedades','idenfermedades',null,'####',false,false);
    $c[]=new cmp('infancia_tipo_doc','t','20',$key['1'],$w.' '.$o,'Tipo Identificación','enfermedades_tipo_doc',null,'',false,false,'','col-5');
    $c[]=new cmp('infancia_documento','t','20',$key['0'],$w.' '.$o,'N° Identificación','enfermedades_documento',null,'',false,false,'','col-5');
    
    
    $c[]=new cmp($o,'e',null,'2. INFANCIA Dirigido a todas las niñas y niños de 6 a 11 años, 11 meses y 29 días - Res. 3280 - 2018',$w);
    $c[]=new cmp('infancia_validacion1','o','2',$d['infancia_validacion1'],$w.' '.$o,'1. Se verfica con la familia el cumplimiento al esquema de vacunación vigente y el antecedente vacunal y se recuerda la importancia tener los esquemas de vacunaciòn completos','infancia_validacion1',null,null,true,true,'','col-10');
    $c[]=new cmp('infancia_validacion2','o','2',$d['infancia_validacion2'],$w.' '.$o,'2. Se recomienda a la familia tener hábitos y estilos de vida saludables (prevención de la exposición al humo de tabaco), de prácticas deportivas organizadas, de actividad física y evitación del sedentarismo, estudio, alimentaciòn, nutriciòn, sueño.','infancia_validacion2',null,null,true,true,'','col-10');
    $c[]=new cmp('infancia_validacion3','o','2',$d['infancia_validacion3'],$w.' '.$o,'3. Se recomienda a la familia evitar el consumo de productos procesados. Los alimentos procesados se relacionan con diferentes enfermedades. Además contienen mucha sal y grasas que perjudican directamente a la tensión. Son por ejemplo: alimentos precocinados, embutidos, margarinas y mantequillas, fritos.','infancia_validacion3',null,null,true,true,'','col-10');
    $c[]=new cmp('infancia_validacion4','o','2',$d['infancia_validacion4'],$w.' '.$o,'4. Se recomienda a la familia que el  niño deberá comer 5 veces al día, dando especial importancia al desayuno compuesto por un lácteo, cereal y fruta. Se deben incluir alimentos como las verduras, arroz, pastas, legumbre, carne, huevos y frutas asegurándonos de que frutas, verduras y alimentos ricos en fibra están presentes a diario. Es conveniente evitar el consumo excesivo de azúcares porque pueden acarrear problemas de obesidad y dentales.','infancia_validacion4',null,null,true,true,'','col-10');
    $c[]=new cmp('infancia_validacion5','o','2',$d['infancia_validacion5'],$w.' '.$o,'5. Se recuerda a la familia la importancia de  mantener a los niños y niñas hidratados.','infancia_validacion5',null,null,true,true,'','col-10');
    $c[]=new cmp('infancia_validacion6','o','2',$d['infancia_validacion6'],$w.' '.$o,'6. Se recuerda a la familia que el niño alternará la falta de apetito con desinterés por las comidas o rechazo a nuevos sabores. Debes acostumbrar al niño a todo tipo de alimentos. Juega con las texturas, colores y presentaciones para acostumbrar su paladar a diferentes sabores y estimular el consumo de verduras y pescados.','infancia_validacion6',null,null,true,true,'','col-10');
    $c[]=new cmp('infancia_validacion7','o','2',$d['infancia_validacion7'],$w.' '.$o,'7. Se recomienda a la familia no hacer comparaciones entre niños, ni sobre la cantidad de alimentos que consumen ni sobre el ritmo del crecimiento. Es más importante la calidad de lo que comen que la cantidad y de ello dependerá su salud futura.','infancia_validacion7',null,null,true,true,'','col-10');
    $c[]=new cmp('infancia_validacion8','o','2',$d['infancia_validacion8'],$w.' '.$o,'8. se verifica si el menor ya cuenta con las actividades de salud oral a las que tiene derecho en el marco de la ruta de promocion y mantenimiento de la salud.','infancia_validacion8',null,null,true,true,'','col-10');
    $c[]=new cmp('infancia_validacion9','o','2',$d['infancia_validacion9'],$w.' '.$o,'9. se verifica si la menor es mujer y tiene entre los 10 y 11 años 11 meses y 29 dias ya se cuenta con el tamizaje de hemoglobina por el riesgo de anemia en esta poblacion.','infancia_validacion9',null,null,true,true,'','col-10');
    $c[]=new cmp('infancia_validacion10','o','2',$d['infancia_validacion10'],$w.' '.$o,'10. se da educacion a el menor y a su familia sobre los derechos del niño.','infancia_validacion10',null,null,true,true,'','col-10');
    $c[]=new cmp('infancia_validacion11','o','2',$d['infancia_validacion11'],$w.' '.$o,'11. Se recomienda a la familia el  cuidado de oìdo y la visiòn, evitar el uso prolongado de televisiòn, computadores y otras pantallas.','infancia_validacion11',null,null,true,true,'','col-10');
    $c[]=new cmp('infancia_validacion12','o','2',$d['infancia_validacion12'],$w.' '.$o,'12. se verifica o se ordena las valoraciones de agudeza visual en el marco de la ruta de promocion y mantenimiento de la salud de acuerdo al curso de vida.','infancia_validacion12',null,null,true,true,'','col-10');

    for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
    
    return $rta;
     }


  function get_atenc($tip,$doc){
      $sql="SELECT atencion_idpersona FROM eac_atencion 
      WHERE atencion_tipodoc ='$tip' AND atencion_idpersona ='$doc'";
      // echo $sql;
      $info=datos_mysql($sql);
      if(isset($info['responseResult'][0])){ 
        return true;
      }else{
        return false;
      }
  }


     function get_infancia(){
      if($_REQUEST['id']==''){
        return "";
      }else{
         $id=divide($_REQUEST['id']);
        $sql="SELECT 
        concat(infancia_tipo_doc,'_',infancia_documento) id,
        infancia_validacion1,infancia_validacion2,infancia_validacion3,infancia_validacion4,infancia_validacion5,infancia_validacion6,infancia_validacion7,infancia_validacion8,infancia_validacion9,infancia_validacion10,infancia_validacion11,infancia_validacion12
        FROM `eac_infancia`
        WHERE infancia_tipo_doc ='{$id[1]}' AND infancia_documento ='{$id[0]}'  ";
        // echo $sql;
        $info=datos_mysql($sql);
        if(isset($info['responseResult'][0])){ 
            return $info['responseResult'][0];
        }else{
          return "";
        }
      } 
    }

    function gra_infancia(){
      $id=divide($_POST['id']);
    //   print_r($id);
      //die("Ok");
      if($_POST['id']!=''){
      $sql="UPDATE `eac_infancia` SET 
      infancia_validacion1=trim(upper('{$_POST['infancia_validacion1']}')),infancia_validacion2=trim(upper('{$_POST['infancia_validacion2']}')),infancia_validacion3=trim(upper('{$_POST['infancia_validacion3']}')),infancia_validacion4=trim(upper('{$_POST['infancia_validacion4']}')),infancia_validacion5=trim(upper('{$_POST['infancia_validacion5']}')),infancia_validacion6=trim(upper('{$_POST['infancia_validacion6']}')),infancia_validacion7=trim(upper('{$_POST['infancia_validacion7']}')),infancia_validacion8=trim(upper('{$_POST['infancia_validacion8']}')),infancia_validacion9=trim(upper('{$_POST['infancia_validacion9']}')),infancia_validacion10=trim(upper('{$_POST['infancia_validacion10']}')),infancia_validacion11=trim(upper('{$_POST['infancia_validacion11']}')),infancia_validacion12=trim(upper('{$_POST['infancia_validacion12']}')),
        `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),`fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
        WHERE infancia_tipo_doc='$id[0]' AND infancia_documento='$id[1]'";  
        // echo $x;
        // echo $sql;
      }else{
        $tip=$_POST['infancia_tipo_doc'];
        $doc=$_POST['infancia_documento'];
        if(get_atenc($tip,$doc)){
        $sql="INSERT INTO eac_infancia VALUES (NULL,
        TRIM(UPPER('{$_POST['infancia_tipo_doc']}')),TRIM(UPPER('{$_POST['infancia_documento']}')),
        trim(upper('{$_POST['infancia_validacion1']}')),trim(upper('{$_POST['infancia_validacion2']}')),trim(upper('{$_POST['infancia_validacion3']}')),trim(upper('{$_POST['infancia_validacion4']}')),trim(upper('{$_POST['infancia_validacion5']}')),trim(upper('{$_POST['infancia_validacion6']}')),trim(upper('{$_POST['infancia_validacion7']}')),trim(upper('{$_POST['infancia_validacion8']}')),trim(upper('{$_POST['infancia_validacion9']}')),trim(upper('{$_POST['infancia_validacion10']}')),trim(upper('{$_POST['infancia_validacion11']}')),trim(upper('{$_POST['infancia_validacion12']}')),
        TRIM(UPPER('{$_SESSION['us_sds']}')),DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";
        // echo $sql;
        $rta=dato_mysql($sql);
      }else{
        $rta="Error: msj['Para realizar esta operacion, debe tener una atención previa, valida e intenta nuevamente']";
      }
      // echo $sql;
    }
    //   return "correctamente";
      return $rta; 
    }


	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   