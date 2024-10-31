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

	// Obtener los encabezados de los cursos de vida
$sql_encabezados = "SELECT descripcion AS cursos FROM catadeta WHERE idcatalogo = 176";
$datos_encabezados = datos_mysql($sql_encabezados);
$cursos = array_column($datos_encabezados['responseResult'], 'cursos');

// Crear un array para almacenar los datos
$datos_por_mes = array();

// Iterar sobre cada curso de vida y obtener los datos correspondientes
foreach ($cursos as $curso) {
    $sql = "SELECT 
                MONTHNAME(fecha) AS Mes,
                COUNT(*) AS Total_usuarios
            FROM 
                personas_datocomp
            WHERE
                cursovida = '$curso'
            GROUP BY 
                Mes
            ORDER BY 
                MONTH(fecha)";

    $datos = datos_mysql($sql);

    // Crear un array asociativo para almacenar los datos del curso actual
    $datos_por_mes[$curso] = $datos['responseResult'];
}

// Crear el array de salida con el formato deseado
$salida = array();

// Agregar los encabezados de los cursos de vida como la primera fila
$salida[] = array_merge(['Mes'], $cursos);

// Iterar sobre los meses para construir el resto de los datos
for ($mes = 1; $mes <= 12; $mes++) {
    $fila_mes = array(date('F', mktime(0, 0, 0, $mes, 1))); // Obtener el nombre del mes

    // Iterar sobre los cursos de vida y agregar los totales de usuarios para el mes actual
    foreach ($cursos as $curso) {
        $total_usuarios_curso = rand(0,150);

        // Buscar el total de usuarios para este mes y curso de vida
        foreach ($datos_por_mes[$curso] as $fila) {
            if (date('n', strtotime($fila['Mes'])) === $mes) {
                $total_usuarios_curso = $fila['Total_usuarios'];
                break;
            }
        }

        // Agregar el total de usuarios para este mes al array del curso actual
        $fila_mes[] = $total_usuarios_curso;
    }

    // Agregar la fila del mes al array de salida
    $salida[] = $fila_mes;
}

// Imprimir el resultado en formato de array de arrays
echo json_encode($salida);
	
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
	