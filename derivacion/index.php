<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/lib/php/nav.php';
$mod='deriva';
$ya = new DateTime();
$estados=opc_arr(["CAFESALUD","CALISALUD","CAPRECOM","CAPRESOCA","COLMÉDICA","COLSUDSIDIO","COMPENSAR","COMFENALCO","CONVIDA","COOMEVA","CRUZ BLANCA","FAMISANAR","HUMANA VIVIR","NUEVA EPS","SALUD COLMENA","SALUD COLPATRIA","SALUD TOTAL","SALUDCOLOMBIA","SALUDCOOP","SALUDVIDA","SANITAS","SELVASALUD","SOLSALUD","S.O.S","SUSALUD"]);
/* $estados=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=145 and estado='A' order by 1",''); */
$colaborador=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil` IN('PROFAM','AUXHOG') and estado='A' ORDER BY 2",'');
$tipdoc=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=1 and estado='A' order by 1",'');
$hoy = date("Y-m-d");
$ayer = date("Y-m-d",strtotime($hoy."- 2 days")); 
// $info=datos_mysql("SELECT nombre,perfil FROM usuarios WHERE id_usuario='".$_SESSION["us_riesgo"]."'");
?>
<Style>
	.toast {
    position: absolute;
    top: 25px;
    right: 30px;
    border-radius: 12px;
    background: #fff;
    padding: 20px 35px 20px 25px;
    box-shadow: 0 6px 20px -5px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transform: translateX(calc(100% + 30px));
    transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.35);

    animation: efectoBounce .3s ease alternate;
}

.toast.active {
    transform: translateX(0%);
}

.toast .toast-content {
    display: flex;
    align-items: center;
}

.toast-content .check {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 35px;
    min-width: 35px;
    background-color: #4070f4;
    color: #fff;
    font-size: 20px;
    border-radius: 50%;
}

.toast-content .message {
    display: flex;
    flex-direction: column;
    margin: 0 20px;
}

.message .text {
    font-size: 16px;
    font-weight: 400;
    color: #666666;
}

.message .text.text-1 {
    font-weight: 600;
    color: #333;
}

.toast .close {
    position: absolute;
    top: 10px;
    right: 15px;
    padding: 5px;
    cursor: pointer;
    opacity: 0.7;
}

.toast .close:hover {
    opacity: 1;
}

.toast .progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 5px;
    width: 100%;

}

.toast .progress:before {
    content: "";
    position: absolute;
    bottom: 0;
    right: 0;
    height: 100%;
    width: 100%;
    background-color: #4070f4;
}

.progress.active:before {
    animation: progress 5s linear forwards;
}

@keyframes progress {
    100% {
        right: 100%;
    }
}

button {
    padding: 12px 20px;
    font-size: 20px;
    outline: none;
    border: none;
    background-color: #4070f4;
    color: #fff;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background-color: #0e4bf1;
}

.toast.active~button {
    pointer-events: none;
}


@keyframes efectoBounce{
    0% {transform: translateY(0px);}
    100% {transform: translateY(-10px);}
  }
</Style>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Crear Cita || SIGREV</title>
	<link rel="stylesheet" href="../lib/css/app.css">
	<link rel="stylesheet" href="../lib/css/stylePop.css">
	<script src="../lib/js/main.js?v=1.0.2"></script>
	<script src="../lib/js/d.js"></script>
	<script src="../lib/js/popup.js"></script>
	<link rel="stylesheet" href="../lib/css/choices.min.css">
    <script src="../lib/js/choices.min.js"></script>
	<script>
		var mod = 'deriva';
		var ruta_app = 'lib.php';

		

		function actualizar() {
			event.preventDefault();
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
	<form method='post' id='fapp'>
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
    					
						<div class="input-box">
							<label for="fidp">N° Documento:</label>
                			<input type="number" id="fidp" name="fidp" OnChange="actualizar();">
						</div>

						<div class="input-box">
							<label for="choices-multiple-remove-button">Derivado A:</label>
                			<select class='choices-multiple-remove-button' id="fcol" name="fcol" multiple OnChange="actualizar();">
								 <?php echo $colaborador; ?>
                			</select>
    					</div>
						
						<div class="input-box">
							<label for="choices-multiple-remove-button">Tipo de Documento:</label>
                			<select class='choices-multiple-remove-button' id="ftip" name="ftip" multiple OnChange="actualizar();">
								 <?php echo $tipdoc; ?>
                			</select>
    					</div>
						
						

						

						<div class="input-box">
							<label for="choices-multiple-remove-button">Estados:</label>
                			<select  class='choices-multiple-remove-button' id="fest" name="fest" multiple OnChange="actualizar();">
								<?php echo $estados; ?>
                			</select>
                		</div>

						

						<div class="input-box">
							<label for="fdes">Fecha Desde</label>
    					    <input  type="date" class='captura' id="fdes" name="fdes" value='<?php echo $ayer; ?>' OnChange="actualizar();">
    					    
    					</div>
						
						<div class="input-box">
						<label for="fhas">Fecha Hasta</label>
    					    <input  type="date" class='captura' id="fhas" name="fhas" value='<?php echo $hoy; ?>' OnChange="actualizar();">
    					</div>
						<!-- <button  type=button class="btn" OnClick="actualizar();">Quitar Filtros</button> -->
						<button  class="btn" OnClick="actualizar();">Aplicar</button>
					</div>
					
					<div class='load'id='loader' z-index='0'></div>
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
						<span class='mensaje' id='<?php echo $mod; ?>-msj'></span>
						<div class='contenido' id='<?php echo $mod; ?>-lis'></div>
					</div>
				</div>
			</section>
		</div>
		</form>
	</div>
		<div class="overlay" id="overlay" onClick="closeModal();">
			<div class="toast" id="loader">
				<div class="toast-content">
				    <i class="fas fa-solid fa-check check"></i>
				   	<div class='message' id='<?php echo $mod; ?>-toast'>	   
						<span class="text text-1">🥇</span>
						<span class="text text-2"></span>
					</div>
				</div>
				<i class="fa-solid fa-xmark close"></i>
				<div class="progress"></div>
			</div>
		</div>
			

		<!-- <div class="popup" id="popup" z-index="0" onClick="closeModal();">
			<div class="btn-close-popup" id="closePopup" onClick="closeModal();">&times;</div>
			<h3>
				<div class='image' id='<?php /* echo $mod;  */?>-image'></div>
			</h3>
			<h4>
				<div class='message' id='<?php /* echo $mod; */ ?>-modal'></div>
			</h4>
		</div> -->
	
	<script>
		
	</script>
</body>