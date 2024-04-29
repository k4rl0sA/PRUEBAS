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

// var_dump($_POST);

/* function opc_1(){
    $sql = "SELECT FN_CATALOGODESC(176, cursovida) as Curso, FN_CATALOGODESC(231, MONTH(fecha)) AS mes, COUNT(*) AS total_usuarios FROM personas_datocomp GROUP BY FN_CATALOGODESC(176, cursovida), MONTH(fecha) ORDER BY cursovida, MONTH(fecha)";
    $datos = datos_mysql($sql);
    echo json_encode($datos['responseResult']); // Enviar los datos como JSON
    exit(); // Detener la ejecución del script después de enviar la respuesta
} */

function opc_1(){
	$sql = "SELECT FN_CATALOGODESC(176, cursovida) as Curso, FN_CATALOGODESC(231, MONTH(fecha)) AS mes, COUNT(*) AS total_usuarios FROM personas_datocomp GROUP BY FN_CATALOGODESC(176, cursovida), MONTH(fecha) ORDER BY cursovida, MONTH(fecha)";
	$datos = datos_mysql($sql);

	$sql1 = "SELECT GROUP_CONCAT(descripcion ORDER BY idcatadeta  SEPARATOR ', ') AS cursos	FROM catadeta WHERE idcatalogo = 176;";
	$datos1 = datos_mysql($sql1);
	// Crear un array para almacenar los datos en el formato que necesita el gráfico
	$data = array();

	$data[] =Array('Mes',$datos1['responseResult'][0]);

	/* foreach ($datos['responseResult'] as $fila) {
		$data[] = array($fila['mes'], $fila['total_usuarios'], $fila['Curso']); // [Mes, Total Usuarios, Curso de vida]
	}
 */

	// Devolver los datos como JSON
	echo json_encode($data);
	exit();
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
	