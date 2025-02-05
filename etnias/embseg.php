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


function focus_segnoreg(){
	return 'segnoreg';
   }
   
   
   function men_segnoreg(){
	$rta=cap_menus('segnoreg','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 $acc=rol($a);
	   if ($a=='segnoreg'  && isset($acc['crear']) && $acc['crear']=='SI'){  
	 $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	 
	   }
  return $rta;
}
function lis_segnoreg(){
    // print_r($_POST);
    $id = (isset($_POST['id'])) ? divide($_POST['id']) : divide($_POST['idp']) ;
$info=datos_mysql("SELECT COUNT(*) total FROM emb_segreg WHERE idpeople=".$id[0]."");
$total=$info['responseResult'][0]['total'];
$regxPag=5;
$pag=(isset($_POST['pag-segnoreg']))? ($_POST['pag-segnoreg']-1)* $regxPag:0;

    $sql="SELECT concat(idpeople,'_',segui) ACCIONES
        FROM `emb_segreg` 
            WHERE idpeople='".$id[0];
        $sql.="' ORDER BY fecha_create";
        $sql.=' LIMIT '.$pag.','.$regxPag;
        //  echo $sql;
        $datos=datos_mysql($sql); 

        return create_table($total,$datos["responseResult"],"segnoreg",$regxPag,'embseg.php');
}


function cmp_segnoreg(){
  $rta="<div class='encabezado'>TABLA SEGUIMIENTOS</div>
	<div class='contenido' id='segnoreg-lis'>".lis_segnoreg()."</div></div>";
  $w="placuifam";
	$t=['id'=>'','idsegnoreg'=>'','idpeople'=>'','fecha_seg'=>'','segui'=>'','estado_seg'=>'','prioridad'=>'','gestaciones'=>'','partos'=>'','abortos'=>'','cesareas'=>'','vivos'=>'','muertos'=>'','fum'=>'','edad_gest'=>'','resul_gest'=>'','peso_nacer'=>'','asist_controles'=>'','exa_labo'=>'','cons_micronutri'=>'','esq_vacu'=>'','signos_alarma1'=>'','diag_sifigest'=>'','adhe_tto'=>'','diag_sificong'=>'','seg_partera'=>'','seg_med_ancestral1'=>'','diag_cronico'=>'','cual'=>'','tto_enf'=>'','ctrl_cronico'=>'','signos_alarma2'=>'','seg_med_ancestral2'=>'','doc_madre'=>'','ctrl_cyd'=>'','lactancia_mat'=>'','esq_vacunacion'=>'','sig_alarma_seg'=>'','seg_med_ancestral3'=>'','sistolica'=>'','diastolica'=>'','frec_cardiaca'=>'','frec_respiratoria'=>'','saturacion'=>'','gluco'=>'','peri_cefalico'=>'','peri_braqueal'=>'','peso'=>'','talla'=>'','imc'=>'','zcore'=>'','clasi_nutri'=>'','ser_remigesti'=>'','observaciones'=>'','users_bina'=>'','equipo_bina'=>'']; 
	$e="";
	$key='pln';
	$o='segnoreg';
  $d='';
  $d=($d=="")?$d=$t:$d;
  $days=fechas_app('VSP');
  var_dump($_POST);
	$c[]=new cmp($o,'e',null,'MODULO INICIAL',$w);
  $c[]=new cmp('id','h',15,$_POST['id'],$w.' '.$key.' '.$o,'id','id',null,'####',false,false);
  $c[]=new cmp('fecha_seg','d',10,$d['fecha_seg'],$w.' '.$o,'Fecha Seguimiento','fecha_seg',null,null,true,true,'','col-2',"validDate(this,$days,0);");
  $c[]=new cmp('segui','s',3,$d['segui'],$w.' '.$o,'Seguimiento N°','segui',null,null,true,true,'','col-2');
  $c[]=new cmp('estado_seg','s',3,$d['estado_seg'],$w.' '.$o,'Estado','estado_seg',null,null,true,true,'','col-2');
  $c[]=new cmp('prioridad','s',3,$d['prioridad'],$w.' '.$o,'Prioridad','prioridad',null,null,true,true,'','col-2');
  $c[]=new cmp('gestaciones','s',3,$d['gestaciones'],$w.' '.$o,'Gestaciones','fxobs',null,null,false,true,'','col-2');
  $c[]=new cmp('partos','s',3,$d['partos'],$w.' '.$o,'Partos','fxobs',null,null,false,true,'','col-2');
  $c[]=new cmp('abortos','s',3,$d['abortos'],$w.' '.$o,'Abortos','fxobs',null,null,false,true,'','col-2');
  $c[]=new cmp('cesareas','s',3,$d['cesareas'],$w.' '.$o,'Cesareas','fxobs',null,null,false,true,'','col-2');
  $c[]=new cmp('vivos','s',3,$d['vivos'],$w.' '.$o,'Vivos','fxobs',null,null,false,true,'','col-2');
  $c[]=new cmp('muertos','s',3,$d['muertos'],$w.' '.$o,'Muertos','fxobs',null,null,false,true,'','col-2');
  
  $o='gestan';
  $c[]=new cmp($o,'e',null,'GESTANTES',$w);
  $c[]=new cmp('fum','d',10,$d['fum'],$w.' '.$o,'Fum','fum',null,null,false,true,'','col-2');
  $c[]=new cmp('edad_gest','s',3,$d['edad_gest'],$w.' '.$o,'Edad Gestacional Al Momento De Identificacion En Semanas','edad_gest',null,null,false,true,'','col-4');
  $c[]=new cmp('resul_gest','s',3,$d['resul_gest'],$w.' '.$o,'Resultado De La Gestación','resul_gest',null,null,false,true,'','col-2');
  $c[]=new cmp('peso_nacer','',5,$d['peso_nacer'],$w.' '.$o,'Peso Al Nacer (Gr)','peso_nacer',null,null,false,true,'','col-2');
  $c[]=new cmp('asist_controles','s',3,$d['asist_controles'],$w.' '.$o,'¿Asiste A Controles Prenatales?','rta',null,null,false,true,'','col-2');
  $c[]=new cmp('exa_labo','s',3,$d['exa_labo'],$w.' '.$o,'¿Cuenta Con Exámenes De Laboratorio Al Día? Con Relación Al Trimestre Gestacional?','rta',null,null,false,true,'','col-4');
  $c[]=new cmp('cons_micronutri','s',3,$d['cons_micronutri'],$w.' '.$o,'¿Consume Micronutrientes?','rta',null,null,false,true,'','col-4');
  $c[]=new cmp('esq_vacu','s',3,$d['esq_vacu'],$w.' '.$o,'¿Tiene Esquema De Vacunacion Completo Para La Eg?','rta',null,null,false,true,'','col-3');
  $c[]=new cmp('signos_alarma1','s',3,$d['signos_alarma1'],$w.' '.$o,'¿Presenta Signos De Alarma?','rta',null,null,false,true,'','col-2');
  $c[]=new cmp('diag_sifigest','s',3,$d['diag_sifigest'],$w.' '.$o,'¿Diagnosticada Con Sifilis Gestacional?','rta',null,null,false,true,'','col-3');
  $c[]=new cmp('adhe_tto','s',3,$d['adhe_tto'],$w.' '.$o,'¿Adherencia A Tratamiento?','rta',null,null,false,true,'','col-2');
  $c[]=new cmp('diag_sificong','s',3,$d['diag_sificong'],$w.' '.$o,'¿Sifilis Congenita?','rta',null,null,false,true,'','col-3');
  $c[]=new cmp('seg_partera','s',3,$d['seg_partera'],$w.' '.$o,'¿Le Ha Realizado Seguimiento Partera?','rta',null,null,false,true,'','col-3');
  $c[]=new cmp('seg_med_ancestral1','s',3,$d['seg_med_ancestral1'],$w.' '.$o,'¿Le Ha Realizado Seguimiento El Médico Ancestral?','rta',null,null,false,true,'','col-4');
  
  $o='cronicos';
  $c[]=new cmp($o,'e',null,'CONDICIONES CRONICAS',$w);
  $c[]=new cmp('diag_cronico','s',3,$d['diag_cronico'],$w.' '.$o,'Diagnostico De Condicion Cronica','diag_cronico',null,null,false,true,'','col-3');
  $c[]=new cmp('cual','t',50,$d['cual'],$w.' '.$o,'¿Cual?','cual',null,null,false,true,'','col-4');
  $c[]=new cmp('tto_enf','s',3,$d['tto_enf'],$w.' '.$o,'Cuenta Con Tratamiento Para Su Enfermedad','rta',null,null,false,true,'','col-3');
  $c[]=new cmp('ctrl_cronico','s',3,$d['ctrl_cronico'],$w.' '.$o,'Asiste A Control De Cronicos','rta',null,null,false,true,'','col-2');
  $c[]=new cmp('signos_alarma2','s',3,$d['signos_alarma2'],$w.' '.$o,'Presenta Signos De Alarma','rta',null,null,false,true,'','col-2');
  $c[]=new cmp('seg_med_ancestral2','s',3,$d['seg_med_ancestral2'],$w.' '.$o,'¿Le Ha Realizado Seguimiento El Médico Ancestral?','rta',null,null,false,true,'','col-3');
  
  
  $o='menor5';
  $c[]=new cmp($o,'e',null,'MENOR DE 5 AÑOS',$w);
  $c[]=new cmp('doc_madre','n',18,$d['doc_madre'],$w.' '.$o,'Número De Documento Madre','doc_madre',null,null,false,true,'','col-35');
  $c[]=new cmp('ctrl_cyd','s',3,$d['ctrl_cyd'],$w.' '.$o,'¿Asiste A Controles De Crecimiento Y Desarrollo?','rta',null,null,false,true,'','col-35');
  $c[]=new cmp('lactancia_mat','s',3,$d['lactancia_mat'],$w.' '.$o,'¿Recibe Lactancia Materna?','rta',null,null,false,true,'','col-3');
  $c[]=new cmp('esq_vacunacion','s',3,$d['esq_vacunacion'],$w.' '.$o,'¿Tiene Esquema De Vacunación Completo Para La Edad?','rta',null,null,false,true,'','col-3');
  $c[]=new cmp('sig_alarma_seg','s',3,$d['sig_alarma_seg'],$w.' '.$o,'Presenta Signos De Alarma En El Momento Del Seguimiento?','rta',null,null,false,true,'','col-35');
  $c[]=new cmp('seg_med_ancestral3','s',3,$d['seg_med_ancestral3'],$w.' '.$o,'¿Le Ha Realizado Seguimiento El Médico Ancestral?','rta',null,null,false,true,'','col-35');
  
  
  $o='';
  $c[]=new cmp($o,'e',null,'SIGNOS VITALES',$w);
  $c[]=new cmp('sistolica','sd',3,$d['sistolica'],$w.' '.$o,'Valor Sistolica','sistolica',null,null,false,true,'','col-25');
  $c[]=new cmp('diastolica','sd',3,$d['diastolica'],$w.' '.$o,'Valor Diastolica','diastolica',null,null,false,true,'','col-25');
  $c[]=new cmp('frec_cardiaca','sd',3,$d['frec_cardiaca'],$w.' '.$o,'Frecuencia Cardiaca','frec_cardiaca',null,null,false,true,'','col-25');
  $c[]=new cmp('frec_respiratoria','sd',3,$d['frec_respiratoria'],$w.' '.$o,'Frecuencia Respiratoria','frec_respiratoria',null,null,false,true,'','col-25');
  $c[]=new cmp('saturacion','sd',3,$d['saturacion'],$w.' '.$o,'Saturación','saturacion',null,null,false,true,'','col-25');
  $c[]=new cmp('gluco','sd',3,$d['gluco'],$w.' '.$o,'Glucometria','gluco',null,null,false,true,'','col-25');
  
  $o='';
  $c[]=new cmp($o,'e',null,'VALORACIÓN ANTROPOMETRICA',$w);  
  $c[]=new cmp('peri_cefalico','sd',2,$d['peri_cefalico'],$w.' '.$o,'Peri de Perimetro Cefalico (Cm)','peri_cefalico',null,null,false,true,'','col-25');
  $c[]=new cmp('peri_braqueal','sd',2,$d['peri_braqueal'],$w.' '.$o,'Perimetro Braquial  (Cm)','peri_braqueal',null,null,false,true,'','col-25');
  $c[]=new cmp('peso','sd',5,$d['peso'],$w.' '.$o,'Peso (Kg)','peso',null,null,false,true,'','col-25');
  $c[]=new cmp('talla','sd',4,$d['talla'],$w.' '.$o,'Talla (Cm)','talla',null,null,false,true,'','col-25');
  $c[]=new cmp('imc','sd',5,$d['imc'],$w.' '.$o,'Imc','imc',null,null,false,true,'','col-3');
  $c[]=new cmp('zcore','t',50,$d['zcore'],$w.' '.$o,'Zcore','zcore',null,null,false,true,'','col-35');
  $c[]=new cmp('clasi_nutri','s',3,$d['clasi_nutri'],$w.' '.$o,'Clasificación Nutricional','clasi_nutri',null,null,false,true,'','col-35');
  
  $o='';
  $c[]=new cmp($o,'e',null,'ASPECTOS FINALES',$w);
  $c[]=new cmp('ser_remigesti','s',3,$d['ser_remigesti'],$w.' '.$o,'Servicio De Remision Y/O Gestion','ser_remigesti',null,null,false,true,'','col-25');
  $c[]=new cmp('observaciones','t',7000,$d['observaciones'],$w.' '.$o,'Observaciones','observaciones',null,null,false,true,'','col-75');
	$c[]=new cmp('equi','m',3,'',$w.' '.$o,'Equipo','equi',null,null,true,true,'','col-5',"fieldsValue('agen_intra','aIM','1',true);");
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta .="<div class='encabezado placuifam'>TABLA DE COMPROMISOS CONCERTADOS</div>
	<div class='contenido' id='segnoreg-lis' >".lis_segnoreg()."</div></div>";
	return $rta;
}

function gra_segnoreg(){
	$id=divide($_POST['id']);
  var_dump('Doc=',$_POST['doc_madre'],'Madre');
  if($_POST['doc_madre']=== ''){
echo 'Vacio';
  }else{
    echo 'NO Vacio';
  }
  $pn = ($_POST['peso_nacer'] === '') ? null : $_POST['peso_nacer'];
  $docma=$_POST['doc_madre']?? 0 ;
  $sis=$_POST['sistolica'] ?? 0;
  $diast=$_POST['diastolica'] ?? 0;
  $fcar=$_POST['frec_cardiaca'] ?? 0;
  $fres=$_POST['frec_respiratoria'] ?? 0;
  $satu=$_POST['saturacion'] ?? 0;
  $gluco=$_POST['gluco'] ?? 0;
  $pcef=$_POST['peri_cefalico'] ?? 0;
  $pbra=$_POST['peri_braqueal'] ?? 0;
  $pes=$_POST['peso'] ?? 0;
  $tal=$_POST['talla'] ?? 0;
  $imc=$_POST['imc'] ?? 0;
  

    if(COUNT($id)==2){
      $equ=datos_mysql("select equipo from usuarios where id_usuario=".$_SESSION['us_sds']);
      $bina = isset($_POST['fequi'])?(is_array($_POST['fequi'])?implode("-", $_POST['fequi']):implode("-",array_map('trim',explode(",",str_replace("'","",$_POST['fequi']))))):'';
      $equi=$equ['responseResult'][0]['equipo'];
      $sql = "INSERT INTO etn_identi VALUES (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,DATE_SUB(NOW(),INTERVAL 5 HOUR),?,?,?)";
    $params = [
['type' => 'i', 'value' => $id[0]],
['type' => 's', 'value' => $_POST['fecha_seg']],
['type' => 's', 'value' => $_POST['segui']],
['type' => 's', 'value' => $_POST['estado_seg']],
['type' => 's', 'value' => $_POST['prioridad']],
['type' => 's', 'value' => $_POST['gestaciones']],
['type' => 's', 'value' => $_POST['partos']],
['type' => 's', 'value' => $_POST['abortos']],
['type' => 's', 'value' => $_POST['cesareas']],
['type' => 's', 'value' => $_POST['vivos']],
['type' => 's', 'value' => $_POST['muertos']],
['type' => 's', 'value' => $_POST['fum']],
['type' => 's', 'value' => $_POST['edad_gest']],
['type' => 's', 'value' => $_POST['resul_gest']],
['type' => 's', 'value' => $pn],
['type' => 's', 'value' => $_POST['asist_controles']],
['type' => 's', 'value' => $_POST['exa_labo']],
['type' => 's', 'value' => $_POST['cons_micronutri']],
['type' => 's', 'value' => $_POST['esq_vacu']],
['type' => 's', 'value' => $_POST['signos_alarma1']],
['type' => 's', 'value' => $_POST['diag_sifigest']],
['type' => 's', 'value' => $_POST['adhe_tto']],
['type' => 's', 'value' => $_POST['diag_sificong']],
['type' => 's', 'value' => $_POST['seg_partera']],
['type' => 's', 'value' => $_POST['seg_med_ancestral1']],
['type' => 's', 'value' => $_POST['diag_cronico']],
['type' => 's', 'value' => $_POST['cual']],
['type' => 's', 'value' => $_POST['tto_enf']],
['type' => 's', 'value' => $_POST['ctrl_cronico']],
['type' => 's', 'value' => $_POST['signos_alarma2']],
['type' => 's', 'value' => $_POST['seg_med_ancestral2']],
['type' => 'i', 'value' => $docma],
['type' => 's', 'value' => $_POST['ctrl_cyd']],
['type' => 's', 'value' => $_POST['lactancia_mat']],
['type' => 's', 'value' => $_POST['esq_vacunacion']],
['type' => 's', 'value' => $_POST['sig_alarma_seg']],
['type' => 's', 'value' => $_POST['seg_med_ancestral3']],
['type' => 's', 'value' => $sis],
['type' => 's', 'value' => $diast],
['type' => 's', 'value' => $fcar],
['type' => 's', 'value' => $fres],
['type' => 's', 'value' => $satu],
['type' => 's', 'value' => $gluco],
['type' => 's', 'value' => $pcef],
['type' => 's', 'value' => $pbra],
['type' => 's', 'value' => $pes],
['type' => 's', 'value' => $tal],
['type' => 's', 'value' => $imc],
['type' => 's', 'value' => $_POST['zcore']],
['type' => 's', 'value' => $_POST['clasi_nutri']],
['type' => 's', 'value' => $_POST['ser_remigesti']],
['type' => 's', 'value' => $_POST['observaciones']],
['type' => 's', 'value' => $bina],
['type' => 's', 'value' => $equi],
['type' => 's', 'value' => $_SESSION['us_sds']],
['type' => 's', 'value' => NULL],
['type' => 's', 'value' => NULL],
['type' => 's', 'value' => 'A']
      ];
      var_dump('dOCMA=',$docma);

  
//INSERT INTO etn_identi VALUES (null,3,'2025-02-05','1','1','1','','','','','','','','','','','','','','','','','','','','','','','','','','',,'','','','','','','','','','','','','','','','','','','','','','ADMSDS','80811594',DATE_SUB(NOW(),INTERVAL 5 HOUR),'','','A')

      $rta = show_sql($sql, $params);
      // $rta = mysql_prepd($sql, $params);
    }else{
   /*  $sql="UPDATE hog_planconc SET cumple=?,fecha_update=?,usu_update=? WHERE idcon=?"; //  compromiso=?, equipo=?, 
    $params = [
        ['type' => 's', 'value' => $_POST['cumplio']],
        ['type' => 's', 'value' => date("Y-m-d H:i:s")],
        ['type' => 'i', 'value' => $_SESSION['us_sds']],
        ['type' => 'i', 'value' => $id[1]]
      ];
      $rta = mysql_prepd($sql, $params); */
    }
return $rta;
}

/* function get_segnoreg(){
  if($_REQUEST['id']==''){
    return "";
  }else{
    // print_r($_POST);
    $id=divide($_REQUEST['id']);
    // print_r($id);
    $sql="SELECT concat(key1,'_',key2) 'id'
          FROM `tabla` 
          WHERE key1='{$id[0]}' AND key2='{$id[1]}'";
    $info=datos_mysql($sql);
     return json_encode($info['responseResult'][0]);
      } 
} */

function opc_equi($id=''){
	return opc_sql("SELECT id_usuario, nombre FROM usuarios WHERE subred=(select subred from usuarios where id_usuario=".$_SESSION['us_sds'].") AND estado='A' AND equipo=(select equipo from usuarios where id_usuario=".$_SESSION['us_sds'].") ORDER BY LPAD(nombre, 2, '0')",$id);
}

function opc_segui($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=76 and estado='A' ORDER BY 1",$id);
  }

  function opc_estado_seg($id=''){
    return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=73 and estado='A' ORDER BY 1",$id);
    }

  function opc_prioridad($id=''){
       return opc_sql('SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=258 and estado="A" ORDER BY 1',$id);
 }

 function opc_fxobs($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=244 and estado='A' ORDER BY cast(idcatadeta AS UNSIGNED)",$id);
}

function opc_edad_gest($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=137 and estado='A' ORDER BY LPAD(idcatadeta, 2, '0') ASC",$id);
}

function opc_resul_gest($id=''){
       return opc_sql('SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=259 and estado="A" ORDER BY 1',$id);
 }

 function opc_rta($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=170 and estado='A' ORDER BY 1",$id);
}

function opc_diag_cronico($id=''){
  return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=252 and estado='A' ORDER BY 1",$id);
}

function opc_clasi_nutri($id=''){
       return opc_sql('SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=260 and estado="A" ORDER BY 1',$id);
 }

 function opc_ser_remigesti($id=''){
       return opc_sql('SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=261 and estado="A" ORDER BY 1',$id);
 }
	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
        // var_dump($a);
		if ($a=='segnoreg' && $b=='acciones'){
			$rta="<nav class='menu right'>";
				
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	   }
	   
