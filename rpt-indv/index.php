<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>REPORTE INDIVIDUAL|| SIGINF</title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/a.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>

<script>
var mod='rptindv';	
var ruta_app='lib.php';

function actualizar(){
	act_lista(mod);
}

</script>
<style>
:root {
  --high-risk-color: red;
  --medium-risk-color: #faab00;
  --normal-risk-color: #4caf50;
  --low-risk-color: blue;
  --bord-radiu-xs: 50rem;
  --bord-radiu-s: 50rem;
  --bord-radiu-m: 50rem;
  --bord-radiu-m: 50rem;
}

.section {
    border: 1px solid #d1d1d1;
    margin-top: 8px;
    padding: 10px;
    display: flex;
    border-radius: 8px;
    margin-bottom: 30px;
}    

.section::before {
    content: "";
    display: block;
    width: 12px;
    position: relative;
    top: 3px;
    bottom: 3px;
    left: -10px;
}

.title-risk {
    background-color: #e6e6e6;
    color: #333;
    padding: 5px;
    border-radius: 8px;
    display: inline-block;
    font-weight: bold;
    border: 1px solid #ababab;
}

.user-info {
    display: flex;
    flex-grow: 1;
}

.user-name {
    color: blue;
    font-size: 20px;
    padding-bottom: 20px;
    font-weight: bold;
}

.user-details {
    margin-left: 5px;
    padding-top: 20px;
    padding-bottom: 20px;
    padding-right: 150px;
    color: black;
}

.user-details div {
    margin-bottom: 5px;
}

.risk-info {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    padding-top: 20px;
    color: black;
}

.extra-info {
    margin-bottom: 10px;
}

.risk-level {
    padding: 5px;
    font-weight: bold;
    padding-top: 95px;
}

.high-risk {
  color: var(--high-risk-color);
}

.medium-risk {
  color: var(--medium-risk-color);
}

.normal-risk {
  color: var(--normal-risk-color);
}

.low-risk {
  color: var(--low-risk-color);
}

.point.high-risk,span.high-risk,.section.high-risk::before {
    background-color: var(--high-risk-color);
    color:white;
}
.point.medium-risk,span.medium-risk,.section.medium-risk::before {
    background-color: var(--medium-risk-color);
    color:white;
}
.point.normal-risk,span.normal-risk,.section.normal-risk::before{
    background-color: var(--normal-risk-color);
    color:white;
}
.point.low-risk,span.low-risk,.section.low-risk::before {
    background-color: var(--low-risk-color);
    color:white;
}

.point {
    display: inline-block;
      width: 13px;
      height: 14px;
      border-radius: 50%;
      margin-right: 10px;
}
/*
.badge {
    display: inline-block;
    padding: 0.35em 0.65em;
    font-size: .75em;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}
*/


.btn-group {
  display: flex;
}

.btn-contain {
  text-align: center;
  margin-bottom: 20px;
}


.custom-btn {
  font-size: .75em;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
    padding: 0px 20px;
  /*  
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  */
}

.btn-value {
  margin-top: 5px;
  font-size: 14px;
}


</style>
</head>
<body Onload="actualizar();">
<?php
require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='rptindv';
$ya = new DateTime();
$localidades=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=2 and estado='A' order by 1",'');
$territorios=opc_sql("SELECT idcatadeta,descripcion FROM `catadeta` WHERE idcatalogo=202 and estado='A' and valor=(SELECT subred FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}') ORDER BY CAST(idcatadeta AS UNSIGNED)",'');
?>
<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>
	
<div class="campo">
	<div>Identificación</div>
	<input class="captura" type="number" id="fidentificacion" name="fidentificacion" OnChange="actualizar();">
</div>

<div class="campo"><div>Localidad</div>
	<select class="captura" id="floc" name="floc" onChange="actualizar();selectDepend('floc','fter','lib.php');">'.<?php echo $localidades; ?></select>
</div>

<div class="campo"><div>Territorio</div>
	<select class="captura" id="fter" name="fter" onChange="actualizar();">'.<?php echo $territorios; ?></select>
</div>
	
</div>
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <!-- <div class='titulo' >REPORTE INDIVIDUAL DE ALERTA
		<nav class='menu left' >
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
		</nav>
		<nav class='menu right'>
			<li class='icono ayuda'      title='Necesitas Ayuda'            Onclick=" window.open('https://sites.google.com/', '_blank');"></li>
        </nav>               
      </div> -->
    	<div>
		</div>
		<span class='mensaje' id='<?php echo $mod; ?>-msj' ></span>
    <div class='contenido' id='<?php //echo $mod;?>-lis' >
<div class="title-risk">Identificación</div>
    <div class="user-info section normal-risk">
        <div class="user-details">
            <div class="user-name">Sandra Patricia Mora Pérez</div>
            <div><b>Documento:</b> CC 12345678</div>
            <div><b>Sexo:</b> Mujer</div>
            <div><b>Género:</b> Femenino</div>
            <div><b>Nacionalidad:</b> Colombiana</div>
        </div>
        <div class="risk-info">
            <div class="extra-info"><b>Curso de Vida:</b> Adultez</div>
            <div class="risk-level normal-risk"><span class="point normal-risk"></span> Normal</div>
        </div>
    </div>

    <div class="title-risk">Ubicación</div>
    <div class="user-info section">
        <div class="user-details">
            <div><b>Localidad:</b> Bosa</div>
            <div><b>Dirección:</b> Carrera 5A SUR 20 95</div>
            <div><b>Teléfono:</b> 8888888</div>
        </div>
    </div>

     <div class="title-risk">Caracterización</div>
    <div class="user-info section">
        <div class="user-detail">
            <div><b>OMS</b></div>
            <div><br></div>

            <div class="btn-group">

              <div class="btn-contain">
                <span class='custom-btn low-risk'>Delgadez</span>
                <div class='btn-value low-risk'>30</div>
              </div>

              <div class="btn-contain">
                <span class='custom-btn normal-risk'>Normal</span>
                <div class='btn-value normal-risk'>20</div>
              </div>

              <div class="btn-contain">
                <span class='custom-btn medium-risk'>Sobrepeso</span>
                <div class='btn-value medium-risk'>40</div>
              </div>

              <div class="btn-contain">
                <span class='custom-btn high-risk'>Obesidad</span>
                <div class='btn-value high-risk'>40</div>
              </div>
            </div>
         </div>
         <div class="user-details">
            <div><b>SRQ:</b> 20</div>
            <div><b>Findrisc:</b> 30</div>
         </div>
        <div class="user-details">
            <div><b>RQC:</b> 30</div>
            <div><b>COPE 28:</b> 80</div>
        </div>
        <div class="user-details">
            <div><b>EPOC:</b> 80</div>
        </div>
    </div>

    <div class="title-risk">Atención Individual</div>
    <div class="user-info section">
        <div class="user-details">
            <div><b>Zarith:</b> 30</div>
            <div><b>Hamilton:</b> 30</div>
        </div>
        <div class="user-details">
            <div><b>Zung:</b> 30</div>
            <div><b>Ophi II:</b> 30</div>
        </div>
    </div>
</div>
</div>			
		
<div class='load' id='loader' z-index='0' ></div>
</form>	
<div class="overlay" id="overlay" onClick="closeModal();">
	<div class="popup" id="popup" z-index="0" onClick="closeModal();">
		<div class="btn-close-popup" id="closePopup" onClick="closeModal();">&times;</div>
		<h3><div class='image' id='<?php echo$mod; ?>-image'></div></h3>
		<h4><div class='message' id='<?php echo$mod; ?>-modal'></div></h4>
	</div>			
</div>
</body>