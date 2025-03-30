<?php
ini_set('display_errors', '1');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $spreadsheet = new Spreadsheet();
    // $mysqli = new mysqli("srv1111.hstgr.io", "u478152275_08", "micontraseñasupersegura", "u478152275_08");
    $mysqli = new mysqli("srv1723.hstgr.io", "u470700275_08", "z9#KqH!YK2VEyJpT", "u470700275_08");
    if ($mysqli->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']));
    }
    $scripts = [
        "Asignacion Predios" => "SELECT G.subred AS Subred, G.idgeo AS Cod_Predio, G.localidad AS Localidad, CONCAT('_', G.sector_catastral, G.nummanzana, G.predio_num, G.unidad_habit) AS Cod_Sector_Catastral, U.id_usuario AS Cod_Asignado, U.nombre AS Nombre_Asignado, U.perfil AS Perfil_Asignado, A.fecha_create AS Fecha_Asignacion, U1.id_usuario AS Cod_Quien_Asigno, U1.nombre AS Nombre_Quien_Asigno, U1.perfil AS Perfil_Quien_Asigno FROM `geo_asig` A LEFT JOIN hog_geo G ON A.idgeo=G.idgeo  LEFT JOIN usuarios U ON A.doc_asignado=U.id_usuario  LEFT JOIN usuarios U1 ON A.usu_create=U1.id_usuario 
        WHERE G.subred in (3) AND A.fecha_create >='$fecha'",
    
        "Gestion Predios" => "SELECT G.idgeo AS Cod_Predio, A.id_ges AS Cod_Registro, G.subred AS Cod_Subred, FN_CATALOGODESC(72,G.subred) AS Subred, G.zona AS Zona, G.localidad AS Cod_Localidad, FN_CATALOGODESC(2,G.localidad) AS Localidad, G.upz AS Cod_Upz, FN_CATALOGODESC(7,G.upz) AS Upz, G.barrio AS Cod_Barrio, C.descripcion AS Barrio, CONCAT('_', G.sector_catastral, G.nummanzana, G.predio_num) AS Cod_Sector, G.sector_catastral AS Sector_catastral, G.nummanzana AS N°_Manzana, G.predio_num AS N°_Predio, G.unidad_habit AS Unidad_Habitacional, G.direccion AS Direccion, G.vereda AS Vereda, G.cordx AS Coordenada_X, G.cordy AS Coordenada_Y, G.estrato AS Estrato, A.direccion_nueva AS Direccion_Nueva, A.vereda_nueva AS Vereda_Nueva, A.cordxn AS Coordenada_X_Nueva, A.cordyn AS Coordenada_Y_Nueva, FN_CATALOGODESC(44,A.estado_v) AS Estado_Visita, FN_CATALOGODESC(5,A.motivo_estado) AS Motivo_Estado, A.usu_creo AS Cod_Usuario, U.nombre AS Nombre_Usuario, U.perfil AS Perfil_Usuario, A.fecha_create AS Fecha_Creacion FROM `geo_gest` A  LEFT JOIN hog_geo G ON A.idgeo=G.idgeo  LEFT JOIN catadeta C ON G.barrio = C.idcatadeta  LEFT JOIN usuarios U ON A.usu_creo=U.id_usuario 
        WHERE G.subred in (3) AND A.fecha_create >='$fecha'",
        
        /* "Caracterizaciones" => "SELECT G.idgeo Cod_Predio,F.id_fam AS Cod_Familia,V.id_viv AS Cod_Registro,G.subred AS Subred,FN_CATALOGODESC(3,G.zona) AS Zona,G.localidad AS Localidad, FN_CATALOGODESC(7,G.upz) AS Upz, G.barrio AS Barrio, G.direccion AS Direccion, G.cordx AS Cordenada_X, G.cordy AS Cordenada_Y, G.estrato AS Estrato, F.numfam AS Familia_N°,concat(F.complemento1,' ',F.nuc1,' ',F.complemento2,' ',F.nuc2,' ',F.complemento3,' ',F.nuc3) AS Complementos,F.telefono1 AS Telefono_1,F.telefono2 AS Telefono_2,F.telefono3 AS Telefono_3,V.fecha AS Fecha_Caracterizacion,FN_CATALOGODESC(215,V.motivoupd) AS Motivo_Caracterizacion, FN_CATALOGODESC(87,V.eventoupd) AS Evento_Notificado, V.fechanot AS Fecha_Notificacion ,V.equipo AS Equipo_Caracterizacion, FN_CATALOGODESC(166,V.crit_epi) AS CRITERIO_EPIDE,FN_CATALOGODESC(167,V.crit_geo) AS CRITERIO_GEO,FN_CATALOGODESC(168,V.estr_inters) AS ESTRATEGIAS_INTERSEC,FN_CATALOGODESC(169,V.fam_peretn) AS FAM_PERTEN_ETNICA,FN_CATALOGODESC(170,V.fam_rurcer) AS FAMILIAS_RURALIDAD_CER,FN_CATALOGODESC(4,V.tipo_vivienda) AS TIPO_VIVIENDA,FN_CATALOGODESC(8,V.tenencia) AS TENENCIA_VIVIENDA,V.dormitorios AS DORMITORIOS,V.actividad_economica AS USO_ACTIVIDAD_ECONO, FN_CATALOGODESC(10,V.tipo_familia) AS TIPO_FAMILIA, V.personas AS N°_PERSONAS, FN_CATALOGODESC(13,V.ingreso) AS INGRESO_ECONOMICO_FAM,V.seg_pre1 AS SEGURIDAD_ALIMEN_PREG1,V.seg_pre2 AS SEGURIDAD_ALIMEN_PREG2,V.seg_pre3 AS SEGURIDAD_ALIMEN_PREG3,V.seg_pre4 AS SEGURIDAD_ALIMEN_PREG4,V.seg_pre5 AS SEGURIDAD_ALIMEN_PREG5,V.seg_pre6 AS SEGURIDAD_ALIMEN_PREG6,V.seg_pre7 AS SEGURIDAD_ALIMEN_PREG7,V.seg_pre8 AS SEGURIDAD_ALIMEN_PREG8,V.subsidio_1 AS SUBSIDIO_SDIS1,V.subsidio_2 AS SUBSIDIO_SDIS2,V.subsidio_3 AS SUBSIDIO_SDIS3,V.subsidio_4 AS SUBSIDIO_SDIS4,V.subsidio_5 AS SUBSIDIO_SDIS5,V.subsidio_6 AS SUBSIDIO_SDIS6,V.subsidio_7 AS SUBSIDIO_SDIS7,V.subsidio_8 AS SUBSIDIO_SDIS8,V.subsidio_9 AS SUBSIDIO_SDIS9,V.subsidio_10 AS SUBSIDIO_SDIS10,V.subsidio_11 AS SUBSIDIO_SDIS11,V.subsidio_12 AS SUBSIDIO_SDIS12,V.subsidio_13 AS SUBSIDIO_ICBF1,V.subsidio_14 AS SUBSIDIO_ICBF2,V.subsidio_15 AS SUBSIDIO15_SECRE_HABIT,V.subsidio_16 AS SUBSIDIO_CONSEJERIA,V.subsidio_17 AS SUBSIDIO_ONGS, V.subsidio_18 AS SUBSIDIO_FAMILIAS_ACCION,V.subsidio_19 AS SUBSIDIO_RED_UNIDOS,V.subsidio_20 AS SUBSIDIO_SECADE, V.energia AS SERVICIO_ENERGIA,V.gas AS SERVICIO_GAS_NATURAL,V.acueducto AS SERVICIO_ACUEDUCTO,V.alcantarillado AS SERVICIO_ALCANTAR,V.basuras AS SERVICIO_BASURAS,V.pozo AS POZO,V.aljibe AS ALJIBE,V.perros AS ANIMALES_PERROS,V.numero_perros AS N°_PERROS,V.perro_vacunas AS N°_PERROS_NOVACU,V.perro_esterilizado AS N°_PERROS_NOESTER,V.gatos AS ANIMALES_GATOS,V.numero_gatos AS N°_GATOS,V.gato_vacunas AS N°_GATOS_NOVACU,V.gato_esterilizado AS N°_GATOS_NOESTER,V.otros AS OTROS_ANIMALES,V.facamb1 AS FACTORES_AMBIEN_PRE1,V.facamb2 AS FACTORES_AMBIEN_PRE2,V.facamb3 AS FACTORES_AMBIEN_PRE3,V.facamb4 AS FACTORES_AMBIEN_PRE4,V.facamb5 AS FACTORES_AMBIEN_PRE5,V.facamb6 AS FACTORES_AMBIEN_PRE6,V.facamb7 AS FACTORES_AMBIEN_PRE7,V.facamb8 AS FACTORES_AMBIEN_PRE8,V.facamb9 AS FACTORES_AMBIEN_PRE9,V.observacion AS OBSERVACIONES, U.id_usuario AS Cod_Usuario, U.nombre AS Nombre_Usuario, U.perfil AS Perfil_Usuario, V.fecha_create AS Fecha_Creacion  FROM `hog_carac` V  LEFT JOIN hog_fam F ON V.idfam = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo  LEFT JOIN usuarios U ON V.usu_create=U.id_usuario 
        WHERE (G.subred) in (3) AND date(V.fecha) BETWEEN '2025-03-01' AND curdate()", */
    
        /* "Caract" => "SELECT G.idgeo AS Cod_Predio,F.id_fam AS Cod_Familia, V.id_viv AS Cod_Registro, FN_CATALOGODESC(3, G.zona) AS Zona, G.localidad AS Localidad, FN_CATALOGODESC(7, G.upz) AS Upz, G.barrio AS Barrio, G.direccion AS Direccion, G.estrato AS Estrato, F.telefono1 AS Telefono_1, F.telefono2 AS Telefono_2, F.telefono3 AS Telefono_3, V.fecha AS Fecha_Caracterizacion, FN_CATALOGODESC(87, V.eventoupd) AS Evento_Notificado, V.fechanot AS Fecha_Notificacion, V.equipo AS Equipo_Caracterizacion, FN_CATALOGODESC(166, V.crit_epi) AS CRITERIO_EPIDE, FN_CATALOGODESC(169, V.fam_peretn) AS FAM_PERTEN_ETNICA, FN_CATALOGODESC(170, V.fam_rurcer) AS FAMILIAS_RURALIDAD_CER, FN_CATALOGODESC(4, V.tipo_vivienda) AS TIPO_VIVIENDA, FN_CATALOGODESC(8, V.tenencia) AS TENENCIA_VIVIENDA, V.dormitorios AS DORMITORIOS, V.actividad_economica AS USO_ACTIVIDAD_ECONO, FN_CATALOGODESC(10, V.tipo_familia) AS TIPO_FAMILIA, V.personas AS N_PERSONAS, FN_CATALOGODESC(13, V.ingreso) AS INGRESO_ECONOMICO_FAM, V.energia AS SERVICIO_ENERGIA, V.gas AS SERVICIO_GAS_NATURAL, V.acueducto AS SERVICIO_ACUEDUCTO, V.alcantarillado AS SERVICIO_ALCANTAR, V.basuras AS SERVICIO_BASURAS, V.pozo AS POZO, V.aljibe AS ALJIBE, V.perros AS ANIMALES_PERROS, V.numero_perros AS N_PERROS, V.perro_vacunas AS N_PERROS_NOVACU, V.perro_esterilizado AS N_PERROS_NOESTER, V.gatos AS ANIMALES_GATOS, V.numero_gatos AS N_GATOS, V.gato_vacunas AS N_GATOS_NOVACU, V.gato_esterilizado AS N_GATOS_NOESTER, V.otros AS OTROS_ANIMALES, V.facamb1 AS FACTORES_AMBIEN_PRE1, V.facamb2 AS FACTORES_AMBIEN_PRE2, V.facamb3 AS FACTORES_AMBIEN_PRE3, V.facamb4 AS FACTORES_AMBIEN_PRE4, V.facamb5 AS FACTORES_AMBIEN_PRE5, V.facamb6 AS FACTORES_AMBIEN_PRE6, V.facamb7 AS FACTORES_AMBIEN_PRE7, V.facamb8 AS FACTORES_AMBIEN_PRE8, V.facamb9 AS FACTORES_AMBIEN_PRE9, V.observacion AS OBSERVACIONES, U.id_usuario AS Cod_Usuario, U.nombre AS Nombre_Usuario, U.perfil AS Perfil_Usuario, V.fecha_create AS Fecha_Creacion, hp.fecha 'Plan de Cuidado', hp2.fecha Compromisos FROM   hog_carac V LEFT JOIN hog_fam F ON V.idfam = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON V.usu_create = U.id_usuario LEFT JOIN hog_plancuid hp ON V.idfam = hp.idviv LEFT JOIN hog_planconc hp2 ON V.id_viv = hp2.idviv
        WHERE G.subred IN (3) AND V.fecha >= '2025-03-01' AND V.fecha <= CURDATE()", */
           
        "Compromisos" => "SELECT G.idgeo Cod_Predio,C.idviv AS Cod_Familia,C.idcon AS Cod_Registro,G.subred AS Subred,C.fecha AS Fecha, C.compromiso AS Compromiso_Concertado, FN_CATALOGODESC(26,C.equipo) AS Equipo,S.fecha_seg AS Fecha_Seguimiento,FN_CATALOGODESC(234,S.tipo_seg) AS Tipo_Seguimiento, FN_CATALOGODESC(170,S.estado_seg) AS Estado_Seguimiento, S.obs_seg AS Observacion_Seguimiento,C.usu_creo AS Usuario_Creo,U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, U.equipo AS Equipo_Creo, C.fecha_create AS Fecha_Creacion FROM `hog_planconc` C LEFT JOIN hog_segcom S ON C.idcon=S.id_con LEFT JOIN hog_plancuid P ON P.idviv = C.idviv LEFT JOIN hog_fam F ON C.idviv = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON C.usu_creo = U.id_usuario
        WHERE G.subred IN (3) AND C.fecha >= '$fecha'",

        "Atenciones"=>"SELECT G.subred AS Subred, G.localidad AS Localidad, G.idgeo AS Cod_predio, F.id_fam AS Cod_Familia,P.idpeople AS Cod_Persona, P.tipo_doc AS Tipo_Documento, P.idpersona AS N°_Docuumento, CONCAT(P.nombre1, ' ', P.nombre2) AS Nombres_Usuario,CONCAT(P.apellido1, ' ', P.apellido2) AS Apellidos_Usuario,P.fecha_nacimiento AS Fecha_Nacimiento,FN_CATALOGODESC(21,P.sexo) AS Sexo, FN_CATALOGODESC(30,P.nacionalidad) AS Nacionalidad, FN_CATALOGODESC(16,P.etnia) AS Etnia,FN_CATALOGODESC(15,P.pueblo) AS Pueblo_Etnia, FN_CATALOGODESC(14,P.discapacidad) AS Tipo_Discapacidad, FN_CATALOGODESC(17,P.regimen) AS Regimen, FN_CATALOGODESC(18,P.eapb) AS Eapb,A.id_aten AS Cod_Registro, A.id_factura AS Cod_Admision, A.fecha_atencion AS Fecha_Consulta, FN_CATALOGODESC(182,A.tipo_consulta) AS Tipo_Consulta, FN_CATALOGODESC(126,A.codigo_cups) AS Codigo_CUPS, FN_CATALOGODESC(127,A.finalidad_consulta) AS Finalidad_Consulta, FN_DESC(3,A.diagnostico1) AS DX1,FN_DESC(3,A.diagnostico2) AS DX2, FN_DESC(3,A.diagnostico3) AS DX3, A.fertil AS '¿Mujer_Edad_Fertil?', A.preconcepcional AS '¿Consulta_Preconsecional?', A.metodo AS '¿Metodo_Planificacion?', FN_CATALOGODESC(129,A.anticonceptivo) AS '¿Cua_Metodo?', A.planificacion AS Planificacion,A.mestruacion AS Fur,A.vih AS Prueba_VIH, FN_CATALOGODESC(187,A.resul_vih) AS Resultado_VIH, A.hb AS Prueba_HB, FN_CATALOGODESC(188,A.resul_hb) AS Resultado_HB, A.trepo_sifil AS Trepomina_Sifilis, FN_CATALOGODESC(188,A.resul_sifil) AS Resultado_Trepo_Sifilis, A.pru_embarazo AS Prueba_Embarazo, FN_CATALOGODESC(88,A.resul_emba) AS Resultado_Embarazo, A.pru_apetito AS Prueba_Apetito, A.resul_apetito AS Resultado_Apetito,A.orden_psicologia AS Orden_Psicologia, A.relevo AS Aplica_Relevo, FN_CATALOGODESC(203,A.estrategia) AS Estrategia, FN_CATALOGODESC(236,A.motivo_estrategia) AS Motivo_Estrategia,A.usu_creo AS Cod_Usuario, U.nombre AS Nombre_Usuario, U.perfil AS Perfil_Usuario, A.fecha_create AS Fecha_Creacion FROM `eac_atencion` A  LEFT JOIN person P ON A.idpeople = P.idpeople LEFT JOIN hog_fam F ON P.vivipersona =  F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario 
        WHERE G.subred IN (3) AND A.fecha_atencion >= '$fecha'",

        "SesColectivas"=>"SELECT G.idgeo AS Cod_Predio, G.subred AS Subred, G.zona AS Zona, G.localidad, C.id_cole AS Cod_Registro, C.fecha AS Fecha_Sesion, FN_CATALOGODESC(239,C.tipo_activ) AS Tipo_Actividad, C.lugar AS Lugar_Actividad, FN_CATALOGODESC(242,C.jornada) AS Jornada_Actividad, C.equipo AS Equipo_Realiza_Actividad,FN_CATALOGODESC(237,C.tematica1) AS Actividad_Tematica_1, FN_CATALOGODESC(238,C.des_temati1) AS Descrip_Tematica_1,FN_CATALOGODESC(237,C.tematica2) AS Actividad_Tematica_2, FN_CATALOGODESC(238,C.des_temati2) AS Descrip_Tematica_2,FN_CATALOGODESC(237,C.tematica3) AS Actividad_Tematica_3, FN_CATALOGODESC(238,C.des_temati3) AS Descrip_Tematica_3,FN_CATALOGODESC(237,C.tematica4) AS Actividad_Tematica_4, FN_CATALOGODESC(238,C.des_temati4) AS Descrip_Tematica_4,FN_CATALOGODESC(237,C.tematica5) AS Actividad_Tematica_5, FN_CATALOGODESC(238,C.des_temati5) AS Descrip_Tematica_5,FN_CATALOGODESC(237,C.tematica6) AS Actividad_Tematica_6, FN_CATALOGODESC(238,C.des_temati6) AS Descrip_Tematica_6,FN_CATALOGODESC(237,C.tematica7) AS Actividad_Tematica_7, FN_CATALOGODESC(238,C.des_temati7) AS Descrip_Tematica_7,FN_CATALOGODESC(237,C.tematica8) AS Actividad_Tematica_8, FN_CATALOGODESC(238,C.des_temati8) AS Descrip_Tematica_8,P.id_person AS Cod_Persona, P.tipo_doc AS Tipo_Documento, P.idpersona AS N°_Documento, P.nombre1 AS Primer_Nombre, P.nombre2 AS Segundo_Nombre, P.apellido1 AS Primer_Apellido, P.apellido2 AS Seundo_Apellido, P.fecha_nacimiento AS Fecha_Nacimiento, FN_CATALOGODESC(21,P.sexo) AS Sexo, FN_CATALOGODESC(19,P.genero) AS Genero, FN_CATALOGODESC(30,P.nacionalidad) AS Nacionalidad, FN_CATALOGODESC(16,P.etnia) AS Etnia, FN_CATALOGODESC(15,P.pueblo) AS Pueblo_Etnia, FN_CATALOGODESC(17,P.regimen) AS Regimen,FN_CATALOGODESC(18,P.eapb) AS Eapb,C.usu_create AS Usuario_Creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, U.equipo AS Equipo_Creo FROM `persescol` P  LEFT JOIN hog_sescole C ON P.sesion = C.id_cole LEFT JOIN hog_geo G ON C.idpre = G.idgeo LEFT JOIN usuarios U ON C.usu_create = U.id_usuario
         WHERE G.subred IN (3) AND C.fecha >= '$fecha'",

        "SesRBC"=>"SELECT G.subred AS Subred, G.idgeo Cod_Predio, F.id_fam AS Cod_Familia, R.id_people AS Cod_Persona, R.idsesion AS Cod_Registro,R.rel_validacion1 AS N°_Sesion, R.rel_validacion2 AS Fecha_Sesion, R.rel_validacion3 AS Perfil, FN_CATALOGODESC(301,R.rel_validacion4) AS Actividad_Respiro, R.rel_validacion5 AS Descripcion_Intervencion,FN_CATALOGODESC(103,R.autocuidado) AS Autocuidado, FN_CATALOGODESC(194,R.activesparc) AS Actividades_Esparcimiento, FN_CATALOGODESC(157,R.infeducom) AS Inf_Educa_Comuni_salud,R.usu_creo AS Usuario_Creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, U.equipo AS Equipo_Creo FROM `rel_sesion` R  LEFT JOIN person P ON R.id_people = P.idpeople  LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON R.usu_creo= U.id_usuario
        WHERE G.subred IN (3) AND R.rel_validacion2 >= '$fecha'",

        "SesPSi1"=>"SELECT G.idgeo Cod_Predio, F.id_fam AS Cod_Familia, A.idpsi AS Cod_Registro, G.subred AS Subred, FN_CATALOGODESC(3,G.zona) AS Zona, G.localidad AS Localidad,P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS Nombres,concat(P.apellido1,' ',P.apellido2) AS Apellidos,P.fecha_nacimiento AS Fecha_Nacimiento,FN_CATALOGODESC(21,P.sexo) AS Sexo,FN_CATALOGODESC(19,P.genero) AS Genero,FN_CATALOGODESC(30,P.nacionalidad) AS Nacionalidad,FN_CATALOGODESC(16,P.etnia) AS Etnia,FN_CATALOGODESC(15,P.pueblo) AS Pueblo,A.fecha_ses1 AS Fecha_Sesion, A.tipo_caso AS Tipo_de_Caso, A.cod_admin AS Cod_Admision, H.analisis AS Hamilton_Inicial, Z.analisis AS Zung_Inicial, W.analisis AS Whodas_Inicial, A.eva_chips AS Resultado_Eva_Chips,A.psi_validacion1 AS Pensamiento_Termina_Vida, A.psi_validacion2 AS Accion_Termina_Vida, A.psi_validacion3 AS Plan_termina_Vida_Sem, A.psi_validacion4 AS Descripcion_Evaluacion, A.psi_validacion5 AS Persona_Le_Entiende, A.psi_validacion6 AS Persona_Acompaña_Razonable, A.psi_validacion7 AS Respuestas_Raras_Inusuales,A.psi_validacion8 AS No_Contacto_Realidad, A.psi_validacion9 AS Posibles_Transtornos, A.psi_validacion10 AS Plan_Termina_Vida, A.psi_validacion11 AS Posible_Transtorno_Mental, FN_DESC(3,A.psi_diag12) as Impresion_DX, A.psi_validacion13 AS Plan_Menejo_Terapeutico, A.psi_validacion14 AS No_Plan_Manejo_Terapeutico, A.otro AS Otro, A.psi_validacion15 AS Descripcion_Plan_Manejo, A.numsesi AS N°_Sesiones,A.usu_creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, A.fecha_create FROM `psi_psicologia` A LEFT JOIN person P ON A.id_people = P.idpeople LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN hog_tam_hamilton H ON A.id_people = H.idpeople AND H.momento = 1  LEFT JOIN hog_tam_zung Z ON A.id_people = Z.idpeople AND Z.momento = 1  LEFT JOIN hog_tam_whodas W ON A.id_people = W.idpeople AND W.momento = 1 LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario
        WHERE G.subred IN (3) AND A.fecha_ses1 >= '$fecha'",

        "SesPsi"=>"SELECT G.idgeo Cod_Predio, F.id_fam AS Cod_Familia, A.id_sesion2 AS Cod_Registro, G.subred AS Subred, FN_CATALOGODESC(3,G.zona) AS Zona, G.localidad AS Localidad,P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS Nombres,concat(P.apellido1,' ',P.apellido2) AS Apellidos,P.fecha_nacimiento AS Fecha_Nacimiento,FN_CATALOGODESC(21,P.sexo) AS Sexo,FN_CATALOGODESC(19,P.genero) AS Genero,FN_CATALOGODESC(30,P.nacionalidad) AS Nacionalidad,FN_CATALOGODESC(16,P.etnia) AS Etnia,FN_CATALOGODESC(15,P.pueblo) AS Pueblo, A.psi_fecha_sesion AS Fecha_Sesion, A.cod_admin2 AS Cod_Admision, A.psi_validacion1 AS Problema_Que_Aflije, FN_CATALOGODESC(124,A.psi_validacion2) AS Cuanto_Afecto_Semana, A.psi_validacion3 AS Otro_Problema_Aflije, FN_CATALOGODESC(124,A.psi_validacion4) AS Otro_Cuanto_Afecto_Semana, A.psi_validacion5 AS Causa_Problema, FN_CATALOGODESC(124,A.psi_validacion6) AS Cuan_Dificil_Resultado, FN_CATALOGODESC(124,A.psi_validacion7) AS Como_Se_Sintio_Semana, A.psi_validacion8 AS Actividad_Desarrollar_1, A.psi_validacion9 AS Actividad_Desarrollar_2, A.psi_validacion10 AS Actividad_Desarrollar_3, (FN_CATALOGODESC(124,A.psi_validacion2)+FN_CATALOGODESC(124,A.psi_validacion4)+FN_CATALOGODESC(124,A.psi_validacion6)+FN_CATALOGODESC(124,A.psi_validacion7)) AS Resultado_Evaluacion, FN_CATALOGODESC(160,A.contin_caso) AS Continuidad_Caso, A.usu_creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, A.fecha_create  FROM `psi_sesion2` A  LEFT JOIN person P ON A.id_people = P.idpeople LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario
        WHERE G.subred IN (3) AND A.psi_fecha_sesion >= '$fecha'",

        "SesPSi3-10"=>"SELECT G.idgeo Cod_Predio, F.id_fam AS Cod_Familia, A.idsesipsi AS Cod_Registro, G.subred AS Subred, FN_CATALOGODESC(3,G.zona) AS Zona, G.localidad AS Localidad,P.tipo_doc AS Tipo_Documento,P.idpersona AS N°_Documento,concat(P.nombre1,' ',P.nombre2) AS Nombres,concat(P.apellido1,' ',P.apellido2) AS Apellidos,P.fecha_nacimiento AS Fecha_Nacimiento,FN_CATALOGODESC(21,P.sexo) AS Sexo,FN_CATALOGODESC(19,P.genero) AS Genero,FN_CATALOGODESC(30,P.nacionalidad) AS Nacionalidad,FN_CATALOGODESC(16,P.etnia) AS Etnia,FN_CATALOGODESC(15,P.pueblo) AS Pueblo, A.psi_fecha_sesion AS Fecha_Sesion, FN_CATALOGODESC(125,A.psi_sesion) AS N°_Sesion, A.cod_admin4 AS Cod_Admision, A.psi_validacion1 AS Problema_Preocupa_Principio, FN_CATALOGODESC(124,A.psi_validacion2) AS Cuanto_Afecto_Semana, A.psi_validacion3 AS Otro_Problema_Aflije_Principio, FN_CATALOGODESC(124,A.psi_validacion4) AS Otro_Cuanto_Afecto_Semana, A.psi_validacion5 AS Le_Ha_Costado_Hacer_Principio,FN_CATALOGODESC(124,A.difhacer) AS Cuan_Dificil_Resultado,FN_CATALOGODESC(124,A.psi_validacion6) AS Como_Se_Sintio_Semana, A.psi_validacion7 AS Plan_Para_Terminar_Con_Su_Vida, A.psi_validacion8 AS Describa_Pensamientos_Planes, A.psi_validacion9 AS Acciones_Terminar_Su_Vida,FN_CATALOGODESC(130,A.psi_validacion10) AS Plan_Terminar_Con_Su_Vida_Prox_Semana, A.psi_validacion11 AS Describa_Su_Plan, A.psi_validacion12 AS Otro_Problema_Importante, FN_CATALOGODESC(124,A.psi_validacion13) AS Afectado_Otros_Problemas,A.psi_validacion14 AS Actividad_Desarrollar_1,A.psi_validacion15 AS Actividad_Desarrollar_2,A.psi_validacion16 AS Actividad_Desarrollar_3,(FN_CATALOGODESC(124,A.psi_validacion2)+FN_CATALOGODESC(124,A.psi_validacion4)+FN_CATALOGODESC(124,A.psi_validacion6)+FN_CATALOGODESC(124,A.difhacer)+FN_CATALOGODESC(124,A.psi_validacion13)) AS Resultado_Evaluacion,FN_CATALOGODESC(160,A.psi_validacion17) AS Continuidad_Caso,A.usu_creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, A.fecha_create FROM `psi_sesiones` A  LEFT JOIN person P ON A.id_people = P.idpeople LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON A.usu_creo = U.id_usuario
         WHERE G.subred IN (3) AND A.psi_fecha_sesion >= '$fecha'",
        
        /* "Signos"=>"SELECT G.idgeo Cod_Predio,F.id_fam AS Cod_Familia,S.id_signos AS Cod_Registro,G.subred AS Subred,P.tipo_doc AS Tipo_Documento, P.idpersona AS N°_Documento, P.nombre1 AS Primer_Nombre, P.nombre2 AS Segundo_Nombre, P.apellido1 AS Primer_Apellido, P.apellido2 AS Seundo_Apellido, P.fecha_nacimiento AS Fecha_Nacimiento, FN_CATALOGODESC(21,P.sexo) AS Sexo,S.fecha_toma AS Fecha_Toma, S.peso AS PESO, S.talla AS TALLA, S.imc AS IMC, S.tas AS Tension_Sistolica, S.tad AS Tension_Diastolica, S.frecard AS Frecuencia_Cardiaca, S.satoxi AS Saturacion_Oxigeno, S.peri_abdomi AS Perimetro_Abdominal, S.peri_braq AS Perimetro_Braquial, S.zscore AS ZSCORE, S.glucom AS Glucometria,S.usu_create AS Usuario_Creo, U.nombre AS Nombre_Creo, U.perfil AS Perfil_Creo, U.equipo AS Equipo_Creo, S.fecha_create AS Fecha_Creacion FROM `hog_signos` S LEFT JOIN person P ON S.idpeople = P.idpeople LEFT JOIN hog_fam F ON P.vivipersona = F.id_fam LEFT JOIN hog_geo G ON F.idpre = G.idgeo LEFT JOIN usuarios U ON S.usu_create = U.id_usuario
        WHERE  S.fecha_toma>= '$fecha' AND S.fecha_toma <= CURDATE() AND G.subred = 3;", */
    ];
    $index = 0;
    $totalScripts = count($scripts);
    $progreso = 0;

    // Array para almacenar los datos de progreso
    $response = ['progreso' => 0];

    foreach ($scripts as $nombreHoja => $query) {
        $result = $mysqli->query($query);

        if ($result) {
            $sheet = $spreadsheet->createSheet($index);
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

            $index++;
            $progreso = ($index / $totalScripts) * 100;

            // Actualizar el progreso en la respuesta
            $response['progreso'] = $progreso;
        } else {
            die(json_encode(['success' => false, 'message' => 'Error en la consulta']));
        }
    }

    // Guardar el archivo Excel
    $filename = "PLANO_SIN_Validaciones_" . date('Y-m-d H:i:s', strtotime('-5 hours')) . ".xlsx";
    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);

    // Respuesta final con el progreso y el enlace de descarga
    $response['success'] = true;
    $response['file'] = $filename;

    echo json_encode($response);
    exit;
}
?>
