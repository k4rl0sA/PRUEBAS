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
    $title=['Coord. Y', 'Coord. X', 'Estado','Marker'];

    $sql= "SELECT 
        cordy,cordx,FN_CATALOGODESC(44, estado_v) AS estado,
        CASE estado_v
            WHEN 1 THEN 'blue'
            WHEN 2 THEN 'yellow'
            WHEN 3 THEN 'yellow'
            WHEN 4 THEN 'purple'
            WHEN 5 THEN 'pink'
            WHEN 6 THEN 'ltblue'
            WHEN 7 THEN 'green'
            ELSE 'red' 
            END AS color
        FROM hog_geo hg
        WHERE estado_v in(7) limit 10";

        $data = datos_mysql($sql);
        $json = $data['responseResult'];

        $rta = array();
        foreach ($json as $fila) {
            $row = array(
                floatval($fila['cordy']),
                floatval($fila['cordx']),
                $fila['estado'],
                $fila['color']
            );
            $rta[] = $row;
        }
        $out= array_merge([$title],$rta);
    echo json_encode($out);
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

