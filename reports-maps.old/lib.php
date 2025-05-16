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
        WHERE 1 ". whe_opc_3();

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
        /* if (empty($rta)) {
            $rta = [[null, null, null, null]];
        } */
        $out= array_merge([$title],$rta);
    // var_dump($sql);
    echo json_encode($out);
}


function whe_opc_3() {
	$sql = "";
	if ($_POST['floc'])
		$sql .= " AND localidad = '".$_POST['floc']."'";
	if ($_POST['fter'])
		$sql .= " AND territorio =(select descripcion from catadeta where idcatalogo=202 AND idcatadeta=".$_POST['fter'].")";
	if ($_POST['fest']){
		$sql .= " AND estado_v ='".$_POST['fest']."' ";
	}
	return $sql;
}


function opc_flocfter(){
    $id=divide($_REQUEST['id']);
    $sql="SELECT `idcatadeta`,descripcion FROM `catadeta` WHERE idcatalogo=202 and estado='A' and 
    valor=(select valor from catadeta where idcatalogo=2 AND  idcatadeta=$id[0]) ORDER BY CAST(idcatadeta AS UNSIGNED)";
    $info=datos_mysql($sql);		
    return json_encode($info['responseResult']);
    // return json_encode($sql);

}

