<?php
require_once "../libs/gestion.php";
ini_set('display_errors','1');
if (isset($_POST['a']) && isset($_POST['tb'])) {
    if ($_POST['a'] !== 'opc') {
        $perf = perfil($_POST['tb']);
    }
    if (!isset($_SESSION['us_sds'])) {
        die("<script>window.top.location.href='/';</script>");
    } else {
        $rta = "";
        switch ($_POST['a']) {
            case 'csv':
                header_csv ($_REQUEST['tb'].'.csv');
                $rs = array('', '');    
                echo csv($rs, '');
                die;
                break;
            default:
                eval('$rta='.$_POST['a'].'_'.$_POST['tb'].'();');
                if (is_array($rta)) {
                    echo json_encode($rta);
                } else {
                    echo $rta;
                }
        }
    }
} else {
    // Manejar el caso en que 'a' y/o 'tb' no estén definidos
    echo "<H1>ACCESO NO AUTORIZADO, PARA VALIDAR TUS PERMISOS CON EL ADMINISTRADOR DEL SISTEMA</H1><div class='message rtawarn'></div>";
}

function whe_reports() {
	$sql = "";
	if ($_POST['fidentificacion'])
		$sql .= " AND srq_idpersona like '%".$_POST['fidentificacion']."%'";
	if ($_POST['fsexo'])
		$sql .= " AND P.sexo ='".$_POST['fsexo']."' ";
	if ($_POST['fpersona']){
		if($_POST['fpersona'] == '2'){ //mayor de edad
			$sql .= " AND TIMESTAMPDIFF(YEAR, P.fecha_nacimiento, CURDATE()) < 18 ";
		}else{ //menor de edad
			$sql .= " AND TIMESTAMPDIFF(YEAR, P.fecha_nacimiento, CURDATE()) >= 18 ";
		}
	}
	return $sql;
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

	function formato_dato($a,$b,$c,$d){
		$b=strtolower($b);
		$rta=$c[$d];
	   // $rta=iconv('UTF-8','ISO-8859-1',$rta);
	   // var_dump($a);
	   // var_dump($rta);
		   if ($a=='reports' && $b=='acciones'){
			$rta="<nav class='menu right'>";		
				$rta.="<li class='icono editar ' title='Editar' id='".$c['ACCIONES']."' Onclick=\"mostrar('reports','pro',event,'','lib.php',7,'TAMIZAJE RQC Y SRQ');\"></li>";  //act_lista(f,this);
			}
		return $rta;
	   }
	   
	   function bgcolor($a,$c,$f='c'){
		// return $rta;
	   }
	