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


function focus_predios(){
	return 'predios';
   }
   
   
   function men_predios(){
	$rta=cap_menus('predios','pro');
	return $rta;
   }
   
   function cap_menus($a,$b='cap',$con='con') {
	 $rta = ""; 
	 $acc=rol($a);
	   if ($a=='predios'  && isset($acc['crear']) && $acc['crear']=='SI'){  
	//  $rta .= "<li class='icono $a grabar'      title='Grabar'          OnClick=\"grabar('$a',this);\"></li>"; //~ openModal();
	   }
  return $rta;
}
function lis_predios(){
	// var_dump($_REQUEST);
	// $id=divide($_POST['id']);
	$filtro=  ($_REQUEST['filtro'])??'';
	$sector=  ($_REQUEST['sector'])??'';
	$manzana=($_REQUEST['manzana'])??'';
	$predio=  ($_REQUEST['predio'])??'';
	$unidad=  ($_REQUEST['unidad'])??'';
	$codpre=  ($_REQUEST['codpre'])??'';
	$docume=  ($_REQUEST['documento'])??'';
	switch ($filtro) {
		case '1':
			if($sector!=='' && $manzana!=='' && $predio!=='' && $unidad!==''){
				$sql="select idgeo 'Codigo',FN_CATALOGODESC(42,hg.estrategia) Estrategia,FN_CATALOGODESC(72,hg.subred) Subred,territorio,direccion,u.nombre Asignado,hg.equipo,FN_CATALOGODESC(44,hg.estado_v) Estado,usu_creo Creo 
				from hog_geo hg
				left join usuarios u ON hg.asignado=u.id_usuario";
				$sql.=" WHERE sector_catastral=".$sector." AND nummanzana=".$manzana."  
				 AND predio_num=".$predio." AND unidad_habit=".$unidad;
				$sql.=" ORDER BY estado_v";
				$datos=datos_mysql($sql);
			return panel_content($datos["responseResult"],"predios-lis",7);	
			}else{
				$rta="<br><span style='background-color:red;'>Recuerde que debe tener la totalidad de los datos del ID(Sector,manzana,predio y unidad habitacional).</span>";
				return $rta;
			}
			break;
		case '2':
			if($codpre!==''){
				$sql="select idgeo 'Codigo',FN_CATALOGODESC(42,hg.estrategia) Estrategia,FN_CATALOGODESC(72,hg.subred) Subred,territorio,direccion,u.nombre Asignado,hg.equipo,FN_CATALOGODESC(44,hg.estado_v) Estado,usu_creo Creo 
				from hog_geo hg
				left join usuarios u ON hg.asignado=u.id_usuario";
				$sql.=" WHERE idgeo=".$codpre;
				$sql.=" ORDER BY estado_v";
				$datos=datos_mysql($sql);
			return panel_content($datos["responseResult"],"predios-lis",7);	
			}else{
				$rta="<br><span style='background-color:red;text-transform:uppercase'>No hay registros asociados, por favor valide el codigo ingresado.</span>";
				return $rta;
			}
			break;
		case '3':				
			if($docume!==''){	
				$sql="SELECT hg.idgeo 'Cod Predio',FN_CATALOGODESC(42,hg.estrategia) Estrategia,FN_CATALOGODESC(72,hg.subred) Subred,hg.direccion Direccion,u.nombre 'Creo',u.perfil,p.fecha_create 'Fecha Creo',hv.idviv 'Cod Familia'
				FROM hog_viv hv 
				left JOIN hog_geo hg ON hv.idgeo=CONCAT(hg.estrategia,'_',hg.sector_catastral,'_',hg.nummanzana,'_',hg.predio_num,'_',hg.unidad_habit,'_7')   
				LEFT JOIN personas p ON hv.idviv=p.vivipersona
				LEFT  JOIN usuarios u ON hg.asignado=u.id_usuario";
				$sql.=" WHERE p.idpersona=".$docume;
				$datos=datos_mysql($sql);
			return panel_content($datos["responseResult"],"predios-lis",2);	
			}else{
				$rta="<br><span style='background-color:red;'>No hay registros asociados, recuerde ingresar el numero de documento del usuario, esta busqueda aplica para usuarios que ya fueron creados en el sistema y por ende en predios efectivos.</span>";
				return $rta;
			}
			break;
		default:
		$rta="<div class='error'>
					<span class='closebtn' onclick=\"this.parentElement.style.display='none';\">&times;</span> 
					<strong>Error!</strong> Debe seleccionar el tipo de filtro</div>";
		/* $rta='<div class="overlay active" id="overlay" onClick="closeModal();">
				<div class="popup" id="popup" z-index="0" onClick="closeModal();">
				<div class="btn-close-popup" id="closePopup" onClick="closeModal();">&times;</div>
				<h3><div class="image" id="predios-image"><div class="icon-popup rtainfo"></div></div></h3>
				<h4><div class="message" id="predios-modal">Debe seleccionar el tipo de filtro</div></h4>
				</div>			
			</div>'; */
		// <br><span style='background-color:red;text-transform:uppercase'></span>";
		return $rta;
		break;
	}
	// echo $sql;
}


function cmp_predios(){
	$rta="<div class='encabezado predios'>TABLA ESTADOS DEL PREDIO</div>
	<div class='contenido' id='predios-lis'>".lis_predios()."</div></div>";
	$hoy=date('Y-m-d');
	$w='predios';
	$d='';
	$o='pred';
	$c[]=new cmp($o,'e',null,'CODIGOS DE PREDIO',$w);
	$c[]=new cmp('filtro','s',3,$d,$w.' '.$o,'Buscar Por','filtro',null,null,true,true,'','col-0',"enClSe('filtro','flT',[['IDc'],['cOP'],['DoC']]);");
	$c[]=new cmp('sector','n',6,$d,$w.' flT IDc '.$o,'sector','sector',null,'123456',true,false,'','col-2');
	$c[]=new cmp('manzana','n',3,$d,$w.' flT IDc '.$o,'manzana','manzana',null,'123',true,false,'','col-1');
	$c[]=new cmp('predio','n',3,$d,$w.' flT IDc '.$o,'predio','predio',null,'123',true,false,'','col-1');
	$c[]=new cmp('unidad','n',3,$d,$w.' flT IDc '.$o,'unidad','unidad',null,'123',true,false,'','col-1');
	$c[]=new cmp('codpre','n',15,$d,$w.' flT cOP '.$o,'Codigo del Predio','codpre',null,'#####',true,false,'','col-2');
	$c[]=new cmp('documento','t',21,$d,$w.' flT DoC '.$o,'Documento del Usuario','documento',null,'##########',true,false,'','col-2');
	for ($i=0;$i<count($c);$i++) $rta.=$c[$i]->put();
	$rta.="<center><button style='background-color:#4d4eef;border-radius:12px;color:white;padding:12px;text-align:center;cursor:pointer;' type='button' Onclick=\"act_lista('predios','','../consultar/consulpred.php');\">Buscar</button></center>";
	return $rta;
}

function opc_filtro($id=''){
	return opc_sql("SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=221 and estado='A' ORDER BY 1",$id);
}

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
		if ($a=='ambient-lis' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"setTimeout(getData,500,'ambient',event,this,['fecha','tipo_activi'],'../vivienda/amb.php');\"></li>";  //   act_lista(f,this);
			}
		return $rta;
	}

	function bgcolor($a,$c,$f='c'){
		$rta="";
		return $rta;
	}
	   