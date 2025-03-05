<?php
require_once 'PHPExcel/Classes/PHPExcel.php';

$mysqli = new mysqli("srv1723.hstgr.io", "u470700275_08", "z9#KqH!YK2VEyJpT", "u470700275_08");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Scripts SQL de las tablas
$scripts = [
    "Asignacion Predios" => "SELECT G.subred AS Subred, G.idgeo AS Cod_Predio, G.localidad AS Localidad, CONCAT('_', G.sector_catastral, G.nummanzana, G.predio_num, G.unidad_habit) AS Cod_Sector_Catastral, 
    U.id_usuario AS Cod_Asignado, U.nombre AS Nombre_Asignado, U.perfil AS Perfil_Asignado, A.fecha_create AS Fecha_Asignacion, 
    U1.id_usuario AS Cod_Quien_Asigno, U1.nombre AS Nombre_Quien_Asigno, U1.perfil AS Perfil_Quien_Asigno 
    FROM `geo_asig` A 
    LEFT JOIN hog_geo G ON A.idgeo=G.idgeo
    LEFT JOIN usuarios U ON A.doc_asignado=U.id_usuario
    LEFT JOIN usuarios U1 ON A.usu_create=U1.id_usuario WHERE G.subred in (3) AND date(A.fecha_create) BETWEEN '2025-03-01' AND CURDATE()",

    "Gestion Predios" => "SELECT G.idgeo AS Cod_Predio, A.id_ges AS Cod_Registro, G.subred AS Cod_Subred, FN_CATALOGODESC(72,G.subred) AS Subred, G.zona AS Zona, G.localidad AS Cod_Localidad, FN_CATALOGODESC(2,G.localidad) AS Localidad, G.upz AS Cod_Upz, FN_CATALOGODESC(7,G.upz) AS Upz, G.barrio AS Cod_Barrio, C.descripcion AS Barrio, CONCAT('_', G.sector_catastral, G.nummanzana, G.predio_num) AS Cod_Sector, G.sector_catastral AS Sector_catastral, G.nummanzana AS N°_Manzana, G.predio_num AS N°_Predio, G.unidad_habit AS Unidad_Habitacional, G.direccion AS Direccion, G.vereda AS Vereda, G.cordx AS Coordenada_X, G.cordy AS Coordenada_Y, G.estrato AS Estrato,
    A.direccion_nueva AS Direccion_Nueva, A.vereda_nueva AS Vereda_Nueva, A.cordxn AS Coordenada_X_Nueva, A.cordyn AS Coordenada_Y_Nueva, FN_CATALOGODESC(44,A.estado_v) AS Estado_Visita, FN_CATALOGODESC(5,A.motivo_estado) AS Motivo_Estado, A.usu_creo AS Cod_Usuario, U.nombre AS Nombre_Usuario, U.perfil AS Perfil_Usuario, A.fecha_create AS Fecha_Creacion 
    FROM `geo_gest` A
    LEFT JOIN hog_geo G ON A.idgeo=G.idgeo
    LEFT JOIN catadeta C ON G.barrio = C.idcatadeta
    LEFT JOIN usuarios U ON A.usu_creo=U.id_usuario WHERE G.subred in (3) AND date(A.fecha_create) BETWEEN '2025-03-01' AND CURDATE()",

    "Caracterizaciones" => "SELECT G.idgeo Cod_Predio,F.id_fam AS Cod_Familia,V.id_viv AS Cod_Registro,G.subred AS Subred,FN_CATALOGODESC(3,G.zona) AS Zona,G.localidad AS Localidad, FN_CATALOGODESC(7,G.upz) AS Upz, G.barrio AS Barrio, G.direccion AS Direccion, G.cordx AS Cordenada_X, G.cordy AS Cordenada_Y, G.estrato AS Estrato, 
    F.numfam AS Familia_N°,concat(F.complemento1,' ',F.nuc1,' ',F.complemento2,' ',F.nuc2,' ',F.complemento3,' ',F.nuc3) AS Complementos,F.telefono1 AS Telefono_1,F.telefono2 AS Telefono_2,F.telefono3 AS Telefono_3,
    V.fecha AS Fecha_Caracterizacion,FN_CATALOGODESC(215,V.motivoupd) AS Motivo_Caracterizacion, FN_CATALOGODESC(87,V.eventoupd) AS Evento_Notificado, V.fechanot AS Fecha_Notificacion ,V.equipo AS Equipo_Caracterizacion,
    FN_CATALOGODESC(166,V.crit_epi) AS CRITERIO_EPIDE,FN_CATALOGODESC(167,V.crit_geo) AS CRITERIO_GEO,FN_CATALOGODESC(168,V.estr_inters) AS ESTRATEGIAS_INTERSEC,FN_CATALOGODESC(169,V.fam_peretn) AS FAM_PERTEN_ETNICA,FN_CATALOGODESC(170,V.fam_rurcer) AS FAMILIAS_RURALIDAD_CER,
    FN_CATALOGODESC(4,V.tipo_vivienda) AS TIPO_VIVIENDA,FN_CATALOGODESC(8,V.tenencia) AS TENENCIA_VIVIENDA,V.dormitorios AS DORMITORIOS,V.actividad_economica AS USO_ACTIVIDAD_ECONO, FN_CATALOGODESC(10,V.tipo_familia) AS TIPO_FAMILIA, V.personas AS N°_PERSONAS, FN_CATALOGODESC(13,V.ingreso) AS INGRESO_ECONOMICO_FAM,
    V.seg_pre1 AS SEGURIDAD_ALIMEN_PREG1,V.seg_pre2 AS SEGURIDAD_ALIMEN_PREG2,V.seg_pre3 AS SEGURIDAD_ALIMEN_PREG3,V.seg_pre4 AS SEGURIDAD_ALIMEN_PREG4,V.seg_pre5 AS SEGURIDAD_ALIMEN_PREG5,V.seg_pre6 AS SEGURIDAD_ALIMEN_PREG6,V.seg_pre7 AS SEGURIDAD_ALIMEN_PREG7,V.seg_pre8 AS SEGURIDAD_ALIMEN_PREG8,
    V.subsidio_1 AS SUBSIDIO_SDIS1,V.subsidio_2 AS SUBSIDIO_SDIS2,V.subsidio_3 AS SUBSIDIO_SDIS3,V.subsidio_4 AS SUBSIDIO_SDIS4,V.subsidio_5 AS SUBSIDIO_SDIS5,V.subsidio_6 AS SUBSIDIO_SDIS6,V.subsidio_7 AS SUBSIDIO_SDIS7,V.subsidio_8 AS SUBSIDIO_SDIS8,V.subsidio_9 AS SUBSIDIO_SDIS9,V.subsidio_10 AS SUBSIDIO_SDIS10,V.subsidio_11 AS SUBSIDIO_SDIS11,V.subsidio_12 AS SUBSIDIO_SDIS12,V.subsidio_13 AS SUBSIDIO_ICBF1,V.subsidio_14 AS SUBSIDIO_ICBF2,V.subsidio_15 AS SUBSIDIO15_SECRE_HABIT,V.subsidio_16 AS SUBSIDIO_CONSEJERIA,V.subsidio_17 AS SUBSIDIO_ONGS, V.subsidio_18 AS SUBSIDIO_FAMILIAS_ACCION,V.subsidio_19 AS SUBSIDIO_RED_UNIDOS,V.subsidio_20 AS SUBSIDIO_SECADE,
    V.energia AS SERVICIO_ENERGIA,V.gas AS SERVICIO_GAS_NATURAL,V.acueducto AS SERVICIO_ACUEDUCTO,V.alcantarillado AS SERVICIO_ALCANTAR,V.basuras AS SERVICIO_BASURAS,V.pozo AS POZO,V.aljibe AS ALJIBE,
    V.perros AS ANIMALES_PERROS,V.numero_perros AS N°_PERROS,V.perro_vacunas AS N°_PERROS_NOVACU,V.perro_esterilizado AS N°_PERROS_NOESTER,V.gatos AS ANIMALES_GATOS,V.numero_gatos AS N°_GATOS,V.gato_vacunas AS N°_GATOS_NOVACU,V.gato_esterilizado AS N°_GATOS_NOESTER,V.otros AS OTROS_ANIMALES,
    V.facamb1 AS FACTORES_AMBIEN_PRE1,V.facamb2 AS FACTORES_AMBIEN_PRE2,V.facamb3 AS FACTORES_AMBIEN_PRE3,V.facamb4 AS FACTORES_AMBIEN_PRE4,V.facamb5 AS FACTORES_AMBIEN_PRE5,V.facamb6 AS FACTORES_AMBIEN_PRE6,V.facamb7 AS FACTORES_AMBIEN_PRE7,V.facamb8 AS FACTORES_AMBIEN_PRE8,V.facamb9 AS FACTORES_AMBIEN_PRE9,V.observacion AS OBSERVACIONES,
    U.id_usuario AS Cod_Usuario, U.nombre AS Nombre_Usuario, U.perfil AS Perfil_Usuario, V.fecha_create AS Fecha_Creacion
    FROM `hog_carac` V
    LEFT JOIN hog_fam F ON V.idfam = F.id_fam
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo
    LEFT JOIN usuarios U ON V.usu_create=U.id_usuario WHERE AND (G.subred) in (3) AND date(V.fecha) BETWEEN '2025-03-01' AND curdate()"
];

$objPHPExcel = new PHPExcel();
$index = 0;

foreach ($scripts as $nombreHoja => $query) {
    $result = $mysqli->query($query);

    if ($result) {
        if ($index > 0) {
            $objPHPExcel->createSheet();
        }
        $objPHPExcel->setActiveSheetIndex($index);
        $objPHPExcel->getActiveSheet()->setTitle($nombreHoja);

        // Encabezados
        $fields = $result->fetch_fields();
        $col = 0;
        foreach ($fields as $field) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field->name);
            $col++;
        }

        // Datos
        $rowNum = 2;
        while ($row = $result->fetch_assoc()) {
            $col = 0;
            foreach ($row as $value) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $rowNum, $value);
                $col++;
            }
            $rowNum++;
        }
        
        $index++;
    }
}

// Establecer la primera hoja como activa
$objPHPExcel->setActiveSheetIndex(0);

// Configuración para descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="datos_unificados.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
