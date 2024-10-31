<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>	
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Derivaciones EAC || SIGINF</title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/a.js"></script>
<script src="../libs/js/x.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script>
var mod='derivaeac';	
var ruta_app='lib.php';

function actualizar(){
	act_lista(mod);
}

function grabar(tb='',ev){
  if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  var f=document.getElementsByClassName('valido '+tb);
   for (i=0;i<f.length;i++) {
     if (!valido(f[i])) {f[i].focus(); return};
  }
  var rutaMap = {
  'statFam': 'stateFami.php',
  'ruteresol':'derireso.php'
};
	var ruta_app = rutaMap[tb] || 'lib.php';
	myFetch(ruta_app,"a=gra&tb="+tb,mod);
	setTimeout(actualizar, 1000);
	
}   

</script>
</head>
<body Onload="actualizar();">
<?php

require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='derivaeac';
/*$ya = new DateTime();
$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
$usu=divide($rta["responseResult"][0]['usu']); */
$estados=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=227 and estado='A' order by 1",'');
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`IN('ENFATE','MEDATE') and subred=(SELECT subred FROM usuarios where id_usuario='{$_SESSION['us_sds']}' AND estado='A') ORDER BY 2",$_SESSION['us_sds']);

?>
<form method='post' id='fapp'>
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>
	<div class="campo"><div>Codigo del Predio</div><input type="number" class="captura" size=10 id="fpre" name="fpre" OnChange="actualizar();"></div>
	<div class="campo"><div>Codigo de la Familia </div><input type="number" class="captura" size=10 id="ffam" name="ffam" OnChange="actualizar();"></div>
	<div class="campo"><div>Colaborador</div>
		<select class="captura" id="fdigita" name="fdigita" OnChange="actualizar();">
			<?php echo $digitadores; ?>
		</select>
	</div>
	<div class="campo"><div>Estado</div>
		<select class="captura" id="festa" name="festa" onChange="actualizar();">'.<?php echo $estados;?></select>
	</div>
	
	</div>
	
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' > DERIVACIONES
		<nav class='menu left' >
			<li class='icono listado' title='Ver Listado' onclick="desplegar(mod+'-lis');" ></li>
			<!-- <li class='icono exportar'      title='Exportar Información General'    Onclick="csv(mod);"></li> -->
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
			<!-- <li class='icono crear' title='Crear' onclick="mostrar('mod','pro');"></li> -->
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
	 <div class='contenido' id='cmprstss' ></div>
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