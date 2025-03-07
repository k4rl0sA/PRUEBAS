<?php
session_start();

ini_set('display_errors', '1');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

// Simular el proceso de generación del archivo
$mysqli = new mysqli("srv1723.hstgr.io", "u470700275_08", "z9#KqH!YK2VEyJpT", "u470700275_08");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$scripts = [
    "VSP" => "SELECT * FROM ( 
    SELECT G.subred, G.localidad, F.idpre, F.id_fam,A.id_acompsic Cod_Registro, A.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, A.fecha_seg,A.numsegui,FN_CATALOGODESC(87,A.evento),FN_CATALOGODESC(73,A.estado_s),FN_CATALOGODESC(170,A.cierre_caso),A.fecha_cierre,FN_CATALOGODESC(198,A.motivo_cierre),FN_CATALOGODESC(170,A.activa_ruta) activa_ruta,FN_CATALOGODESC(79,A.ruta) Ruta,A.observaciones, A.equipo_bina, A.usu_creo, U.nombre, U.perfil FROM `vsp_acompsic` A 
    LEFT JOIN person P ON A.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,B.id_psicduel Cod_Registro, B.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, B.fecha_seg, B.numsegui,FN_CATALOGODESC(87,B.evento),FN_CATALOGODESC(73,B.estado_s),FN_CATALOGODESC(170,B.cierre_caso),B.fecha_cierre,FN_CATALOGODESC(198,B.motivo_cierre),FN_CATALOGODESC(170,B.activa_ruta) activa_ruta,FN_CATALOGODESC(79,B.ruta) Ruta,B.observaciones, B.equipo_bina, B.usu_creo, U.nombre, U.perfil FROM `vsp_apopsicduel` B 
    LEFT JOIN person P ON B.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON B.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,C.id_bpnpret Cod_Registro, C.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, C.fecha_seg, C.numsegui,FN_CATALOGODESC(87,C.evento),FN_CATALOGODESC(73,C.estado_s),FN_CATALOGODESC(170,C.cierre_caso),C.fecha_cierre,FN_CATALOGODESC(198,C.motivo_cierre),FN_CATALOGODESC(170,C.activa_ruta) activa_ruta,FN_CATALOGODESC(79,C.ruta) Ruta,C.observaciones, C.equipo_bina, C.usu_creo, U.nombre, U.perfil FROM `vsp_bpnpret` C 
    LEFT JOIN person P ON C.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON C.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,D.id_bpnterm Cod_Registro, D.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, D.fecha_seg, D.numsegui,FN_CATALOGODESC(87,D.evento),FN_CATALOGODESC(73,D.estado_s),FN_CATALOGODESC(170,D.cierre_caso),D.fecha_cierre,FN_CATALOGODESC(198,D.motivo_cierre),FN_CATALOGODESC(170,D.activa_ruta) activa_ruta,FN_CATALOGODESC(79,D.ruta) Ruta,D.observaciones, D.equipo_bina, D.usu_creo, U.nombre, U.perfil FROM `vsp_bpnterm` D 
    LEFT JOIN person P ON D.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON D.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,E.id_cancinfa Cod_Registro, E.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, E.fecha_seg, E.numsegui,FN_CATALOGODESC(87,E.evento),FN_CATALOGODESC(73,E.estado_s),FN_CATALOGODESC(170,E.cierre_caso),E.fecha_cierre,FN_CATALOGODESC(198,E.motivo_cierre),FN_CATALOGODESC(170,E.activa_ruta) activa_ruta,FN_CATALOGODESC(79,E.ruta) Ruta,E.observaciones, E.equipo_bina, E.usu_creo, U.nombre, U.perfil FROM `vsp_cancinfa` E 
    LEFT JOIN person P ON E.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON E.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,H.id_condsuic Cod_Registro, H.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, H.fecha_seg, H.numsegui,FN_CATALOGODESC(87,H.evento),FN_CATALOGODESC(73,H.estado_s),FN_CATALOGODESC(170,H.cierre_caso),H.fecha_cierre,FN_CATALOGODESC(198,H.motivo_cierre),FN_CATALOGODESC(170,H.activa_ruta) activa_ruta,FN_CATALOGODESC(79,H.ruta) Ruta,H.observaciones, H.equipo_bina, H.usu_creo, U.nombre, U.perfil FROM `vsp_condsuic` H 
    LEFT JOIN person P ON H.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON H.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,I.id_cronicos Cod_Registro, I.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, I.fecha_seg, I.numsegui,FN_CATALOGODESC(87,I.evento),FN_CATALOGODESC(73,I.estado_s),FN_CATALOGODESC(170,I.cierre_caso),I.fecha_cierre,FN_CATALOGODESC(198,I.motivo_cierre),FN_CATALOGODESC(170,I.activa_ruta) activa_ruta,FN_CATALOGODESC(79,I.ruta) Ruta,I.observaciones, I.equipo_bina, I.usu_creo, U.nombre, U.perfil FROM `vsp_cronicos` I 
    LEFT JOIN person P ON I.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON I.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,J.id_dntsevymod Cod_Registro, J.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, J.fecha_seg, J.numsegui,FN_CATALOGODESC(87,J.evento),FN_CATALOGODESC(73,J.estado_s),FN_CATALOGODESC(170,J.cierre_caso),J.fecha_cierre,FN_CATALOGODESC(198,J.motivo_cierre),FN_CATALOGODESC(170,J.activa_ruta) activa_ruta,FN_CATALOGODESC(79,J.ruta) Ruta,J.observaciones, J.equipo_bina, J.usu_creo, U.nombre, U.perfil FROM `vsp_dntsevymod` J 
    LEFT JOIN person P ON J.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON J.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,K.id_eraira Cod_Registro, K.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, K.fecha_seg, K.numsegui,FN_CATALOGODESC(87,K.evento),FN_CATALOGODESC(73,K.estado_s),FN_CATALOGODESC(170,K.cierre_caso),K.fecha_cierre,FN_CATALOGODESC(198,K.motivo_cierre),FN_CATALOGODESC(170,K.activa_ruta) activa_ruta,FN_CATALOGODESC(79,K.ruta) Ruta,K.observaciones, K.equipo_bina, K.usu_creo, U.nombre, U.perfil FROM `vsp_eraira` K 
    LEFT JOIN person P ON K.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON K.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,L.id_gestante Cod_Registro, L.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, L.fecha_seg, L.numsegui,FN_CATALOGODESC(87,L.evento),FN_CATALOGODESC(73,L.estado_s),FN_CATALOGODESC(170,L.cierre_caso),L.fecha_cierre,FN_CATALOGODESC(198,L.motivo_cierre),FN_CATALOGODESC(170,L.activa_ruta) activa_ruta,FN_CATALOGODESC(79,L.ruta) Ruta,L.observaciones, L.equipo_bina, L.usu_creo, U.nombre, U.perfil FROM `vsp_gestantes` L 
    LEFT JOIN person P ON L.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON L.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,M.id_hbgestacio Cod_Registro, M.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, M.fecha_seg, M.numsegui,FN_CATALOGODESC(87,M.evento),FN_CATALOGODESC(73,M.estado_s),FN_CATALOGODESC(170,M.cierre_caso),M.fecha_cierre,FN_CATALOGODESC(198,M.motivo_cierre),FN_CATALOGODESC(170,M.activa_ruta) activa_ruta,FN_CATALOGODESC(79,M.ruta) Ruta,M.observaciones, M.equipo_bina, M.usu_creo, U.nombre, U.perfil FROM `vsp_hbgest` M 
    LEFT JOIN person P ON M.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON M.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,N.id_mnehosp Cod_Registro, N.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, N.fecha_seg, N.numsegui,FN_CATALOGODESC(87,N.evento),FN_CATALOGODESC(73,N.estado_s),FN_CATALOGODESC(170,N.cierre_caso),N.fecha_cierre,FN_CATALOGODESC(198,N.motivo_cierre),FN_CATALOGODESC(170,N.activa_ruta) activa_ruta,FN_CATALOGODESC(79,N.ruta) Ruta,N.observaciones, N.equipo_bina, N.usu_creo, U.nombre, U.perfil FROM `vsp_mnehosp` N 
    LEFT JOIN person P ON N.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON N.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,O.id_otroprio Cod_Registro, O.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, O.fecha_seg, O.numsegui,FN_CATALOGODESC(87,O.evento),FN_CATALOGODESC(73,O.estado_s),FN_CATALOGODESC(170,O.cierre_caso),O.fecha_cierre,FN_CATALOGODESC(198,O.motivo_cierre),FN_CATALOGODESC(170,O.activa_ruta) activa_ruta,FN_CATALOGODESC(79,O.ruta) Ruta,O.observaciones, O.equipo_bina, O.usu_creo, U.nombre, U.perfil FROM `vsp_otroprio` O 
    LEFT JOIN person P ON O.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON O.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,Q.id_saludoral Cod_Registro, Q.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, Q.fecha_seg, Q.numsegui,FN_CATALOGODESC(87,Q.evento),FN_CATALOGODESC(73,Q.estado_s),FN_CATALOGODESC(170,Q.cierre_caso),Q.fecha_cierre,FN_CATALOGODESC(198,Q.motivo_cierre),FN_CATALOGODESC(170,Q.activa_ruta) activa_ruta,FN_CATALOGODESC(79,Q.ruta) Ruta,Q.observaciones, Q.equipo_bina, Q.usu_creo, U.nombre, U.perfil FROM `vsp_saludoral` Q 
    LEFT JOIN person P ON Q.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON Q.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,R.id_sificong Cod_Registro, R.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, R.fecha_seg, R.numsegui,FN_CATALOGODESC(87,R.evento),FN_CATALOGODESC(73,R.estado_s),FN_CATALOGODESC(170,R.cierre_caso),R.fecha_cierre,FN_CATALOGODESC(198,R.motivo_cierre),FN_CATALOGODESC(170,R.activa_ruta) activa_ruta,FN_CATALOGODESC(79,R.ruta) Ruta,R.observaciones, R.equipo_bina, R.usu_creo, U.nombre, U.perfil FROM `vsp_sificong` R 
    LEFT JOIN person P ON R.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON R.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,S.id_sifigest Cod_Registro, S.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, S.fecha_seg, S.numsegui,FN_CATALOGODESC(87,S.evento),FN_CATALOGODESC(731,S.estado_s),FN_CATALOGODESC(170,S.cierre_caso),S.fecha_cierre,FN_CATALOGODESC(198,S.motivo_cierre),FN_CATALOGODESC(170,S.activa_ruta) activa_ruta,FN_CATALOGODESC(79,S.ruta) Ruta,S.observaciones, S.equipo_bina, S.usu_creo, U.nombre, U.perfil FROM `vsp_sifigest` S 
    LEFT JOIN person P ON S.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON S.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,T.id_vihgestacio Cod_Registro, T.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, T.fecha_seg, T.numsegui,FN_CATALOGODESC(87,T.evento),FN_CATALOGODESC(73,T.estado_s),FN_CATALOGODESC(170,T.cierre_caso),T.fecha_cierre,FN_CATALOGODESC(198,T.motivo_cierre),FN_CATALOGODESC(170,T.activa_ruta) activa_ruta,FN_CATALOGODESC(79,T.ruta) Ruta,T.observaciones, T.equipo_bina, T.usu_creo, U.nombre, U.perfil FROM `vsp_vihgest` T 
    LEFT JOIN person P ON T.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON T.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,V.id_gestante Cod_Registro, V.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, V.fecha_seg, V.numsegui,FN_CATALOGODESC(87,V.evento),FN_CATALOGODESC(73,V.estado_s),FN_CATALOGODESC(170,V.cierre_caso),V.fecha_cierre,FN_CATALOGODESC(198,V.motivo_cierre),FN_CATALOGODESC(170,V.activa_ruta) activa_ruta,FN_CATALOGODESC(79,V.ruta) Ruta,V.observaciones, V.equipo_bina, V.usu_creo, U.nombre, U.perfil FROM `vsp_violges` V 
    LEFT JOIN person P ON V.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON V.usu_creo = U.id_usuario 
    UNION 
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,W.id_violreite Cod_Registro,W.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, W.fecha_seg, W.numsegui,FN_CATALOGODESC(87,W.evento),FN_CATALOGODESC(73,W.estado_s),FN_CATALOGODESC(170,W.cierre_caso),W.fecha_cierre,FN_CATALOGODESC(198,W.motivo_cierre),FN_CATALOGODESC(170,W.activa_ruta) activa_ruta,FN_CATALOGODESC(79,W.ruta) Ruta,W.observaciones, W.equipo_bina, W.usu_creo, U.nombre, U.perfil FROM `vsp_violreite` W 
    LEFT JOIN person P ON W.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo
    LEFT JOIN usuarios U ON W.usu_creo = U.id_usuario
    UNION
    SELECT G.subred,G.localidad, F.idpre, F.id_fam,X.Id_mme Cod_Registro,X.idpeople,P.idpersona,P.tipo_doc,CONCAT_WS(' ',P.nombre1,P.nombre2,P.apellido1,P.apellido2) Nombres,P.fecha_nacimiento,P.sexo,P.nacionalidad,P.regimen,P.eapb, X.fecha_seg, X.numsegui,FN_CATALOGODESC(87,X.evento),FN_CATALOGODESC(73,X.estado_s),FN_CATALOGODESC(170,X.cierre_caso),X.fecha_cierre,FN_CATALOGODESC(198,X.motivo_cierre),FN_CATALOGODESC(170,X.activa_ruta) activa_ruta,FN_CATALOGODESC(79,X.ruta) Ruta,X.observaciones,X.users_bina, X.usu_creo, U.nombre, U.perfil FROM `vsp_mme` X 
    LEFT JOIN person P ON X.idpeople = P.idpeople 
    LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam 
    LEFT JOIN hog_geo G ON F.idpre = G.idgeo 
    LEFT JOIN usuarios U ON X.usu_creo = U.id_usuario
    ) AS CombinedQuery WHERE fecha_seg BETWEEN '2025-02-01' AND CURDATE() AND subred = 3;"
    // ,

    /* "Gestion Predios" => "SELECT G.idgeo AS Cod_Predio, A.id_ges AS Cod_Registro, G.subred AS Cod_Subred, FN_CATALOGODESC(72,G.subred) AS Subred, G.zona AS Zona, G.localidad AS Cod_Localidad, FN_CATALOGODESC(2,G.localidad) AS Localidad, G.upz AS Cod_Upz, FN_CATALOGODESC(7,G.upz) AS Upz, G.barrio AS Cod_Barrio, C.descripcion AS Barrio, CONCAT('_', G.sector_catastral, G.nummanzana, G.predio_num) AS Cod_Sector, G.sector_catastral AS Sector_catastral, G.nummanzana AS N°_Manzana, G.predio_num AS N°_Predio, G.unidad_habit AS Unidad_Habitacional, G.direccion AS Direccion, G.vereda AS Vereda, G.cordx AS Coordenada_X, G.cordy AS Coordenada_Y, G.estrato AS Estrato,
    A.direccion_nueva AS Direccion_Nueva, A.vereda_nueva AS Vereda_Nueva, A.cordxn AS Coordenada_X_Nueva, A.cordyn AS Coordenada_Y_Nueva, FN_CATALOGODESC(44,A.estado_v) AS Estado_Visita, FN_CATALOGODESC(5,A.motivo_estado) AS Motivo_Estado, A.usu_creo AS Cod_Usuario, U.nombre AS Nombre_Usuario, U.perfil AS Perfil_Usuario, A.fecha_create AS Fecha_Creacion 
    FROM `geo_gest` A
    LEFT JOIN hog_geo G ON A.idgeo=G.idgeo
    LEFT JOIN catadeta C ON G.barrio = C.idcatadeta
    LEFT JOIN usuarios U ON A.usu_creo=U.id_usuario WHERE G.subred in (3) AND date(A.fecha_create) BETWEEN '2025-03-05' AND CURDATE()",

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
    LEFT JOIN usuarios U ON V.usu_create=U.id_usuario WHERE (G.subred) in (3) AND date(V.fecha) BETWEEN '2025-03-05' AND curdate()" */
];

$spreadsheet = new Spreadsheet();
$totalSteps = count($scripts);
$currentStep = 0;

foreach ($scripts as $nombreHoja => $query) {
    $result = $mysqli->query($query);

    if ($result) {
        $sheet = $spreadsheet->createSheet($currentStep);
        $sheet->setTitle($nombreHoja);

        // Agregar encabezados
        $fields = $result->fetch_fields();
        $col = 1;
        foreach ($fields as $field) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->setCellValue($columnLetter . '1', $field->name);
            $col++;
        }

        // Agregar datos
        $rowNum = 2;
        while ($row = $result->fetch_assoc()) {
            $col = 1;
            foreach ($row as $value) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $sheet->setCellValue($columnLetter . $rowNum, $value);
                $col++;
            }
            $rowNum++;
        }

        // Actualizar el progreso
        $currentStep++;
        $progress = intval(($currentStep / $totalSteps) * 100);

        // Enviar el progreso al cliente
        echo "data: " . json_encode(['progress' => $progress]) . "\n\n";
        ob_flush();
        flush();
    }
}

// Guardar el archivo Excel
$filename = '/datos_unificados.xlsx'; // Guardar en la carpeta temporal del servidor
$writer = new Xlsx($spreadsheet);
$writer->save($filename);

// Notificar que el proceso ha terminado
echo "data: " . json_encode(['status' => 'completed', 'filename' => $filename]) . "\n\n";
ob_flush();
flush();

// Enviar el archivo al cliente para descargar
if (file_exists($filename)) {
    // Configurar las cabeceras para la descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="datos_unificados.xlsx"');
    header('Content-Length: ' . filesize($filename));
    readfile($filename);

    // Eliminar el archivo después de la descarga
    unlink($filename);
    exit; // Terminar la ejecución del script
} else {
    echo "data: " . json_encode(['status' => 'error', 'message' => 'El archivo no existe']) . "\n\n";
}

session_write_close();