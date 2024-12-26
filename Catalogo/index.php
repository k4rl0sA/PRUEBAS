<?php
ini_set('display_errors', '1');
include $_SERVER['DOCUMENT_ROOT'] . '/lib/php/nav.php';
require_once "../lib/php/gestion.php";
if (!isset($_SESSION["us_sds"])) {
	die("<script>window.top.location.href = '/';</script>");
}
$mod = 'catalogo';
$estados = opc_sql("SELECT idcatadeta,descripcion from catadeta where idcatalogo=2 and estado='A' order by 1", '');
$catalogos = opc_sql("SELECT `idcatalogo`,concat(idcatalogo,' - ',nombre) FROM `catalogo` ORDER BY 1", '');
?>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Catalogo || Proteger</title>
	<link rel="stylesheet" href="../lib/css/main.css">
	<script src="../lib/js/main.js"></script>
	<script src="../lib/js/d.js"></script>
	<script src="../lib/js/popup.js"></script>
	<script>
		var mod = 'catalogo';
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
    					        <select class='captura' id="fidcata" name="fidcata" OnChange="actualizar();" required>
									<?php echo $catalogos; ?>
    					        </select>
    					        <label for="fidcata">Catalogo</label>
    					    </div>

    					    <div class="input-box">
    					        <input class='captura' type="text" id="fcatalogo" name="fcatalogo" OnChange="actualizar();" required>
    					        <label for="fcatalogo">Nombre del catalogo</label>
    					    </div>

							<div class="input-box">
    					        <select class='captura' id="festado" name="festado" OnChange="actualizar();" required>
									<?php echo $estados; ?>
    					        </select>
    					        <label for="festado">Estado</label>
    					    </div>

    					    <!-- <div class="input-box">
    					        <input class='captura' type="date" id="birthdate" name="birthdate" OnChange="actualizar();" required>
    					        <label for="birthdate">fecha de nacimiento</label>
    					    </div>
							<div class="input-box">
    					        <input class='captura' type="checkbox" id="agree" name="agree" OnChange="actualizar();" required>
    					        <label for="agree">Yo acepto los terminos y condiciones</label>
    					    </div>
							<div class="input-box">
    					        <textarea class='captura' id="message" name="message" rows="4" required></textarea>
    					        <label for="message">Observaciones</label>
    					    </div>
						-->

    					    

							
    					    

    					    

    					    <button type="submit" class="btn">Aplicar</button>
    					</form>
					</div>


					
						<div class='load' id='loader' z-index='0'></div>
				</div>
				<div class="content content-2">
					<div class="title txt-center"><h2>CATALOGO</h2></div>
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