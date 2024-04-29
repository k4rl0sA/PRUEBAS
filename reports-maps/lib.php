<?php
require_once "../libs/gestion.php";
ini_set('display_errors','1');

// Verificar si las claves 'a' y 'tb' están definidas en $_POST
if (isset($_POST['a']) && isset($_POST['tb'])) {
    if ($_POST['a'] != 'opc') $perf = perfil($_POST['tb']);
    if (!isset($_SESSION['us_sds'])) die("<script>window.top.location.href='/';</script>");
    else {
        $rta = "";
        switch ($_POST['a']) {
            case 'csv':
                header_csv($_REQUEST['tb'] . '.csv');
                $rs = array('', '');
                echo csv($rs, '');
                die;
                break;
            default:
                eval('$rta=' . $_POST['a'] . '_' . $_POST['tb'] . '();');
                if (is_array($rta)) json_encode($rta);
                else echo $rta;
        }
    }
}else {
    // var_dump($_POST);
	 "Error: Parámetros 'a' y 'tb' no están definidos en la solicitud.";
}


function opc_3(){
    $salida=[
        ['Lat', 'Long', 'State','Marker'],
        [37.4232, -122.0853,'No Residencial','blue'],
        [37.4289, -122.1697,'Efectivo','green'],
        [37.6153, -122.3900,'Ausente','yellow'],
        [37.4422, -122.1731,'Fallido','red']
      ];
echo json_encode($salida);
	// Obtener los encabezados de los cursos de vida	
}





function whe_rptMap() {
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

