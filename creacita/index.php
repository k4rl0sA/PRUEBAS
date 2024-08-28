<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/lib/php/nav.php';
$mod='solcita';
$ya = new DateTime();
$estados=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=145 and estado='A' order by 1",'');
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`='DIG' and estado='A' ORDER BY 1",'');
$hoy = date("Y-m-d");
$ayer = date("Y-m-d",strtotime($hoy."- 2 days")); 
// $info=datos_mysql("SELECT nombre,perfil FROM usuarios WHERE id_usuario='".$_SESSION["us_riesgo"]."'");
?>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Crear Cita || SIGREV</title>
	<link rel="stylesheet" href="../lib/css/app.css">
	<link rel="stylesheet" href="../lib/css/stylePop.css">
	<script src="../lib/js/main.js"></script>
	<script src="../lib/js/d.js"></script>
	<script src="../lib/js/popup.js"></script>
	<link rel="stylesheet" href="../lib/css/choices.min.css">
    <script src="../lib/js/choices.min.js"></script>
	<script>
		var mod = 'solcita';
		var ruta_app = 'lib.php';

		function csv(b) {
			var myWindow = window.open("../lib/gestion.php?a=exportar&b=" + b, "Descargar archivo");
		}

		function actualizar() {
			act_lista(mod);
		}

		function grabar(tb = '', ev) {
			if (tb == '' && ev.target.classList.contains(proc)) tb = proc;
			var f = document.getElementsByClassName('valido ' + tb);
			for (i = 0; i < f.length; i++) {
				if (!valido(f[i])) {
					f[i].focus();
					return
				};
			}
			var res = confirm("Desea guardar la información, recuerda que no se podrá editar posteriormente?");
			if (res == true) {
				myFetch(ruta_app, "a=gra&tb=" + tb, mod);
				setTimeout(actualizar, 1000);
			}
		}
	</script>
</head>

<body Onload="actualizar();">
	<div class="wrapper main" id='<?php echo $mod; ?>-main'>
		<div class="top-menu">
			<input type="radio" name="slider"  id="filtros">
			<input type="radio" name="slider" checked id="datos">
			<nav>
				<label for="filtros" class="filtros"><i class="fa-solid fa-sliders fa-rotate-90"></i>Filtros</label>
				<label for="datos" class="datos"><i class="fas fa-table"></i>Datos</label>
				<div class="slider"></div>
			</nav>
			<section>
				<div class="content content-1">
					<div class="title txt-center"><h2>Filtros</h2></div>

					<div class="frm-filter poppins-font" id='<?php echo $mod; ?>-fil'>
    					<form method='post' id='fapp'>

						<div class="input-box">
							<label for="fidp">N° Documento:</label>
                			<input type="number" id="fidp" name="fidp">
						</div>

						<div class="input-box">
						<label for="choices-multiple-remove-button">Estado Solicitud:</label>
                		<select id="choices-multiple-remove-button" name="fest" id="fidp" multiple>
							<?php echo $estados; ?>
                		</select>
                		</div>

						<!-- <div class="input-box">
							<label for="single-select">Estado Solicitud:</label>
                			<select id="single-select" class='captura' id="fest" name="fest">
								 <?php /* echo $estados; */ ?>
                			</select>
    					</div> -->

						<div class="input-box">
							<label for="fdes">Fecha Desde</label>
    					    <input  type="date" class='captura' id="fdes" name="fdes" value='<?php echo $ayer; ?>'>
    					    
    					</div>
						
						<div class="input-box">
						<label for="fhas">Fecha Hasta</label>
    					    <input  type="date" class='captura' id="fhas" name="fhas" value='<?php echo $hoy; ?>'>
    					</div>

   					    <!-- <button type="submit" class="btn" OnChange="actualizar();">Aplicar</button> -->
    					</form>
					</div>
				<div class='load' id='loader' z-index='0'></div>
				</div>
				<div class="content content-2">
					<div class="title txt-center"><h2>SOLICITAR CITA</h2></div>
					<div class='panel' id='<?php echo $mod; ?>'>
							<!-- <nav class='menu left'>
								<li class='icono exportar' title='Exportar CSV' Onclick="csv(mod);"></li>
								<li class='icono actualizar' title='Actualizar' Onclick="actualizar();">
								<li class='icono crear' title='Crear Catalogo' Onclick="mostrar(mod,'pro');"></li>
							</nav> 
							<nav class='menu right'>
								<li class='icono ayuda' title='Necesitas Ayuda' Onclick=" window.open('https://sites.google.com/', '_blank');"></li>
								<li
								 class='icono cancelar' title='Salir' Onclick="location.href='../main/'"></li>
							</nav>-->
						</form>
						<span class='mensaje' id='<?php echo $mod; ?>-msj'></span>
						<div class='contenido' id='<?php echo $mod; ?>-lis'></div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<div class="overlay" id="overlay" onClick="closeModal();">
		<div class="popup" id="popup" z-index="0" onClick="closeModal();">
			<div class="btn-close-popup" id="closePopup" onClick="closeModal();">&times;</div>
			<h3>
				<div class='image' id='<?php echo $mod; ?>-image'></div>
			</h3>
			<h4>
				<div class='message' id='<?php echo $mod; ?>-modal'></div>
			</h4>
		</div>
	</div>
</body>