<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WHODAS || SIGREV</title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/a.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script>
var mod='tamWhodas';	
var ruta_app='lib.php';
function csv(b){
		var myWindow = window.open("../libs/gestion.php?a=exportar&b="+b,"Descargar archivo");
}

document.onkeyup=function(ev) {
	ev=ev||window.event;
	if (ev.ctrlKey && ev.keyCode==46) ev.target.value='';
	if (ev.ctrlKey && ev.keyCode==45) ev.target.value=ev.target.placeholder;
};


function actualizar(){
	act_lista(mod);
}

function grabar(tb='',ev){
	if(document.getElementById('idwhodas').value=='0'){
		if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  			var f=document.getElementsByClassName('valido '+tb);
   			for (i=0;i<f.length;i++) {
    			if (!valido(f[i])) {f[i].focus(); return};
  			}
	
	  		var res = confirm("Desea guardar la información, recuerda que no se podrá editar posteriormente?");
			if(res==true){
				myFetch(ruta_app,"a=gra&tb="+tb,mod);
/*     			if (document.getElementById(mod+'-modal').innerHTML.includes('Correctamente')){
					document.getElementById(mod+'-image').innerHTML='<svg class="icon-popup" ><use xlink:href="#ok"/></svg>';
				}else{
					document.getElementById(mod+'-image').innerHTML='<svg class="icon-popup" ><use xlink:href="#bad"/></svg>';
				}
				openModal(); */
				setTimeout(actualizar, 1000);
			}
	}else{
		errors(`Esta funcion no esta habilitada en este momento,por favor consulta con el administrador del sistema`);
	}
} 

function validardias(a) {
      var numero = parseInt(a.value);
      if (isNaN(numero) || numero < 1 || numero > 30) {
		errors(`Por favor, ingresa un número válido de 1 a 30 dias`);
      }
    }

	

</script>
</head>
<body Onload="actualizar();">
<?php
require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='tamWhodas';
$ya = new DateTime();
// $localidades=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=2 and estado='A' order by 1",'');
$genero=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=21 and estado='A' order by 1",'');
$tiperson=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=102 and estado='A' order by 1",'');
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`='MED' ORDER BY 1",'');
?>
<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>
	
<div class="campo">
	<div>Identificación</div>
	<input class="captura" type="number" id="fidentificacion" name="fidentificacion" OnChange="actualizar();">
</div>
	
	<div class="campo"><div>Sexo</div>
		<select class="captura" id="fsexo" name="fsexo" OnChange="actualizar();">
			<?php echo $genero; ?>
		</select>
	</div>
	<div class="campo"><div>persona</div>
		<select class="captura" id="fpersona" name="fpersona" OnChange="actualizar();">
			<?php echo $tiperson; ?>
		</select>
	</div>
	<div class="campo"><div>Colaborador</div>
		<select class="captura" id="fdigita" name="fdigita" OnChange="actualizar();">
			<?php echo $digitadores; ?>
		</select>
	</div>
	
</div>
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' >TAMIZAJE WHODAS
		<nav class='menu left' >
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
			<li class='icono crear'       title='Crear'     Onclick="mostrar(mod,'pro');"></li> <!--setTimeout(load,500);-->
		</nav>
		<nav class='menu right' >
			<li class='icono ayuda'      title='Necesitas Ayuda'            Onclick=" window.open('https://sites.google.com/', '_blank');"></li>
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
</body>