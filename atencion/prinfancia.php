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



function focus_prinfancia(){
	return 'prinfancia';
   }
   
   
   function men_prinfancia(){
	$rta=cap_menus('prinfancia','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = "";
	 $acc=rol($a);
  if ($a=='prinfancia' && isset($acc['crear']) && $acc['crear']=='SI') {  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();

	 return $rta;
   }
}
   function cmp_prinfancia(){
    $rta="";
    $hoy=date('Y-m-d');
    $t=['id'=>'','p_infancia_documento'=>'','p_infancia_validacion1'=>'','p_infancia_validacion2'=>'','p_infancia_validacion3'=>'','p_infancia_validacion4'=>'','p_infancia_validacion5'=>'','p_infancia_validacion6'=>'','p_infancia_validacion7'=>'','p_infancia_validacion8'=>'','p_infancia_validacion9'=>'','p_infancia_validacion10'=>'','p_infancia_validacion11'=>'','p_infancia_validacion12'=>'']; 
    $w='prinfancia';
    $key=divide($_POST['id']);
    $d=get_prinfancia(); 
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
    $c[]=new cmp('p_infancia_documento','t','20',$key['0'],$w.' '.$o,'N° Identificación','enfermedades_documento',null,'',false,false,'','col-5');
    $c[]=new cmp('p_infancia_tipo_doc','t','20',$key['1'],$w.' '.$o,'Tipo Identificación','enfermedades_tipo_doc',null,'',false,false,'','col-5');
    
    $c[]=new cmp($o,'e',null,'1. PRIMERA INFANCIA (Dirigido a todas las niñas y niños de 0 días a 5 años, 11 meses y 29 días - Res. 3280 - 2018)',$w);
    $c[]=new cmp('p_infancia_validacion1','o','2',$d['p_infancia_validacion1'],$w.' '.$o,'1. Se recomienda al cuidador verifiar la realizaciòn de  los ciclos de desparasitación intestinal de acuerdo con lo establecido en la ruta de promocion y mantenimiento de la salud.','p_infancia_validacion1',null,null,true,true,'','col-10');
    $c[]=new cmp('p_infancia_validacion2','o','2',$d['p_infancia_validacion2'],$w.' '.$o,'2. Se verfica con la familia el cumplimiento al esquema de vacunación vigente y el antecedente vacunal y se recuerda la importancia tener los esquemas de vacunaciòn completos.','p_infancia_validacion2',null,null,true,true,'','col-10');
    $c[]=new cmp('p_infancia_validacion3','o','2',$d['p_infancia_validacion3'],$w.' '.$o,'3. se verifica si el menor tiene indicacion de tamizacion de anemia con hemoglobina y hematocrito de acuerdo a los criterios establecidos en la ruta de promocion y mantenimiento de la salud.','p_infancia_validacion3',null,null,true,true,'','col-10');
    $c[]=new cmp('p_infancia_validacion4','o','2',$d['p_infancia_validacion4'],$w.' '.$o,'4. se verifica si el menor tiene indicacion del uso de micronutrientes y aun no cuenta con ellos.','p_infancia_validacion4',null,null,true,true,'','col-10');
    $c[]=new cmp('p_infancia_validacion5','o','2',$d['p_infancia_validacion5'],$w.' '.$o,'5. se verifica si el menor de 6 meses ya cuenta con la consulta de valoracion de lactancia materna.','p_infancia_validacion5',null,null,true,true,'','col-10');
    $c[]=new cmp('p_infancia_validacion6','o','2',$d['p_infancia_validacion6'],$w.' '.$o,'6. se verifica si el menor ya cuenta con las actividades de salud oral a las que tiene derecho en el marco de la ruta de promocion y mantenimiento de la salud.','p_infancia_validacion6',null,null,true,true,'','col-10');
    $c[]=new cmp('p_infancia_validacion7','o','2',$d['p_infancia_validacion7'],$w.' '.$o,'7. Se recuerda a la familia que para niños y niñas menores de seis(6) meses es importante  el mantenimiento de la lactancia materna exclusiva.','p_infancia_validacion7',null,null,true,true,'','col-10');
    $c[]=new cmp('p_infancia_validacion8','o','2',$d['p_infancia_validacion8'],$w.' '.$o,'8. Se informa a la familia del tiempo y condiciones de inicio de la alimentación complementaria y pautas para la estimulación del desarrollo.','p_infancia_validacion8',null,null,true,true,'','col-10');
    $c[]=new cmp('p_infancia_validacion9','o','2',$d['p_infancia_validacion9'],$w.' '.$o,'9. Se recomienda a la familia  el cuidado de oìdo y la visiòn, evitar el uso prolongado de televisiòn, computadores y otras pantallas.','p_infancia_validacion9',null,null,true,true,'','col-10');
    $c[]=new cmp('p_infancia_validacion10','o','2',$d['p_infancia_validacion10'],$w.' '.$o,'10. Se recomienda generar ambientes tranquilos, organizados y aseados que sean propicios para el desarrollo de capacidades de los padres o cuidadores y de las niñas y niños.','p_infancia_validacion10',null,null,true,true,'','col-10');
    $c[]=new cmp('p_infancia_validacion11','o','2',$d['p_infancia_validacion11'],$w.' '.$o,'11. Se indican y aclaran a los padres los signos de alarma para enfermedades de la infancia como el asma, tuberculosis, el manejo adecuado en casa y se educa para consultar a urgencias en los casos necesarios.','p_infancia_validacion11',null,null,true,true,'','col-10');
    $c[]=new cmp('p_infancia_validacion12','o','2',$d['p_infancia_validacion12'],$w.' '.$o,'12. se verifica o se ordena las valoraciones de agudeza visual en el marco de la ruta de promocion y mantenimiento de la salud de acuerdo al curso de vida.','p_infancia_validacion12',null,null,true,true,'','col-10');

    for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
    
    return $rta;
     }

     function get_prinfancia(){
      if($_REQUEST['id']==''){
        return "";
      }else{
         $id=divide($_REQUEST['id']);
        $sql="SELECT 
        concat(p_infancia_tipo_doc,'_',p_infancia_documento) id,
        p_infancia_validacion1,p_infancia_validacion2,p_infancia_validacion3,p_infancia_validacion4,p_infancia_validacion5,p_infancia_validacion6,p_infancia_validacion7,p_infancia_validacion8,p_infancia_validacion9,p_infancia_validacion10,p_infancia_validacion11,p_infancia_validacion12
        FROM `eac_pinfancia`
        WHERE p_infancia_tipo_doc ='{$id[1]}' AND p_infancia_documento ='{$id[0]}'  ";
        // echo $sql;
        $info=datos_mysql($sql);
        if(isset($info['responseResult'][0])){ 
            return $info['responseResult'][0];
        }else{
          return "";
        }
      } 
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

    function gra_prinfancia(){
      $id=divide($_POST['id']);
      //print_r($id);
      //die("Ok");
      if($_POST['id']!=''){
      $sql="UPDATE `eac_pinfancia` SET 
      p_infancia_validacion1=trim(upper('{$_POST['p_infancia_validacion1']}')),p_infancia_validacion2=trim(upper('{$_POST['p_infancia_validacion2']}')),p_infancia_validacion3=trim(upper('{$_POST['p_infancia_validacion3']}')),p_infancia_validacion4=trim(upper('{$_POST['p_infancia_validacion4']}')),p_infancia_validacion5=trim(upper('{$_POST['p_infancia_validacion5']}')),p_infancia_validacion6=trim(upper('{$_POST['p_infancia_validacion6']}')),p_infancia_validacion7=trim(upper('{$_POST['p_infancia_validacion7']}')),p_infancia_validacion8=trim(upper('{$_POST['p_infancia_validacion8']}')),p_infancia_validacion9=trim(upper('{$_POST['p_infancia_validacion9']}')),p_infancia_validacion10=trim(upper('{$_POST['p_infancia_validacion10']}')),p_infancia_validacion11=trim(upper('{$_POST['p_infancia_validacion11']}')),p_infancia_validacion12=trim(upper('{$_POST['p_infancia_validacion12']}')),
        `usu_update`=TRIM(UPPER('{$_SESSION['us_sds']}')),
        `fecha_update`=DATE_SUB(NOW(), INTERVAL 5 HOUR) 
        WHERE p_infancia_tipo_doc='$id[0]' AND p_infancia_documento='$id[1]'"; 
        // echo $x;
        // echo $sql;
        $rta=dato_mysql($sql);
      }else{
        $tip=$_POST['p_infancia_tipo_doc'];
        $doc=$_POST['p_infancia_documento'];
        if(get_atenc($tip,$doc)){
          $sql="INSERT INTO eac_pinfancia VALUES (NULL,
            TRIM(UPPER('{$_POST['p_infancia_tipo_doc']}')),
            TRIM(UPPER('{$_POST['p_infancia_documento']}')),
            trim(upper('{$_POST['p_infancia_validacion1']}')),trim(upper('{$_POST['p_infancia_validacion2']}')),trim(upper('{$_POST['p_infancia_validacion3']}')),trim(upper('{$_POST['p_infancia_validacion4']}')),trim(upper('{$_POST['p_infancia_validacion5']}')),trim(upper('{$_POST['p_infancia_validacion6']}')),trim(upper('{$_POST['p_infancia_validacion7']}')),trim(upper('{$_POST['p_infancia_validacion8']}')),trim(upper('{$_POST['p_infancia_validacion9']}')),trim(upper('{$_POST['p_infancia_validacion10']}')),trim(upper('{$_POST['p_infancia_validacion11']}')),trim(upper('{$_POST['p_infancia_validacion12']}')),
            TRIM(UPPER('{$_SESSION['us_sds']}')),
            DATE_SUB(NOW(), INTERVAL 5 HOUR),NULL,NULL,'A')";

            $rta=dato_mysql($sql);
        }else{
          $rta="Error: msj['Para realizar esta operacion, debe tener una atención previa, valida e intenta nuevamente']";
        }
        // echo $sql;
      }
        
      //   return "correctamente";
        return $rta; 
      }

   
	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
		if ($a=='prinfancia-lis' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'prinfancia',event,this,['fecha','tipo_activi'],'amb.php');\"></li>";  //   act_lista(f,this);
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   