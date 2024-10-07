<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>	
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reasignar || SIGINF</title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/a.js"></script>
<script src="../libs/js/d.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="../libs/js/popup.js"></script>
<script>
var mod='asigpred';	
var ruta_app='lib.php';


document.onkeyup=function(ev) {
	ev=ev||window.event;
	if (ev.ctrlKey && ev.keyCode==46) ev.target.value='';
	if (ev.ctrlKey && ev.keyCode==45) ev.target.value=ev.target.placeholder;
};


function actualizar(){
	act_lista(mod);
}


function grabar(tb='',ev){
  if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  var f=document.getElementsByClassName('valido '+tb);
   for (i=0;i<f.length;i++) {
     if (!valido(f[i])) {f[i].focus(); return};
  }
	myFetch(ruta_app,"a=gra&tb="+tb,mod);
	resetFrm();
}   

function resetFrm() {
	document.getElementById('fapp').reset();
}

function enabFielSele(a, b, c, d) {
	for (i = 0; i < c.length; i++) {
    	var ele = document.getElementById(c[i]);
    	enaFie(ele, !d.includes(a.value) || !b);
  	}
}

</script>
</head>
<body Onload="showFil(mod);">
<?php

require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='asigpred';
?>
<form method='post' id='fapp'>
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>
	
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' >DERIVAR O REASIGNAR
		<nav class='menu left' >
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono crear' title='Crear' onclick="mostrar(mod,'pro');"></li>
			<li class='icono lupa' title='Consultar Predio' Onclick="mostrar('predios','pro',event,'','../consultar/consulpred.php',7);">
			<li class='icono ' data-mod="asignacion" title='Importar' Onclick="">
		</nav>
		<nav class='menu right' >
			<li class='icono ayuda'      title='Necesitas Ayuda'            Onclick=" window.open('https://drive.google.com/drive/folders/1JGd31V_12mh8-l2HkXKcKVlfhxYEkXpA', '_blank');"></li>
            <li class='icono cancelar'      title='Salir'            Onclick="location.href='../main/'"></li>
        </nav>               
      </div>
      <div>
		</div>
		<span class='mensaje' id='<?php echo $mod; ?>-msj' ></span>
     <div class='contenido' id='<?php echo $mod; ?>-lis' ></div>
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

<div id="modal" class="modal">
        	<div class="modal-content">
        	    <span class="modal-close" id="closeModal">&times;</span>
        	    <h2>Cargar Registros</h2>
        	    <p>Por favor, seleccione un archivo CSV para cargar a la base de datos.</p>

        	    <div class="file-upload">
        	        <input type="file" id="fileInput" accept=".csv" />
        	        <i class="fa-solid fa-cloud-arrow-up cloud-icon"></i>
        	        <p id="file-name">Selecciona un archivo aquí</p>
        	        <button type="button" class="browse-btn" onclick="document.getElementById('fileInput').click();">
        	            Examinar
        	        </button>
        	    </div>

        	    <div class="progress-container">
				<div id="progressBar" class="progress-bar"></div>
        	    </div>
        	    <p id="progressText">0% completado</p>
        	    <p id="statusMessage"></p>
        	    <div class="button-container">
        	        <button id="startLoading">Iniciar Carga</button>
        	        <button id="cancelLoading" style="display: none;">Cancelar</button>
        	        <button id="closeModal" style="display: none;">Cerrar</button>
        	    </div>
        	</div>
    	</div>
</body>