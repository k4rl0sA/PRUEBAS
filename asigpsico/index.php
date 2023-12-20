<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Asignación Psicologia || SIGINF</title><!--cambiar titulo por orden de fernando-->
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/x.js"></script>
<script src="../libs/js/a.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script>
var mod='asigpsico'; ////modulo
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
  myFetch(ruta_app,"a=gra&tb="+tb,mod);
	if (document.getElementById(mod+'-modal').innerHTML.includes('Correctamente')){
		document.getElementById(mod+'-image').innerHTML='<svg class="icon-popup" ><use xlink:href="#ok"/></svg>';
	}else{
		document.getElementById(mod+'-image').innerHTML='<svg class="icon-popup" ><use xlink:href="#bad"/></svg>';
	}
	openModal();
}   

</script>
</head>
<body Onload="actualizar();">
<?php
	require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='asigpsico';//modulo
$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
$usu=divide($rta["responseResult"][0]['usu']);
// var_dump($usu[1]);
$subred = ($usu[1]=='ADM') ? '1,2,3,4,5' : $usu[2] ;
$colaboradores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`='PSIEAC' AND subred IN(".$subred.") AND componente='EAC' ORDER BY 2",$_SESSION['us_sds']);
$estados=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=38 and estado='A' order by 1",'NULL');
?>
<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>

<!--start filtros segun se requiera -->
<div class="campo"><div>Documento Usuario</div><input class="captura" size=18 id="fdocumento" name="fdocumento" OnChange="actualizar();"></div>
<div class="campo"><div>Sector Catastral</div><input class="captura" size=6 id="fseca" name="fseca" OnChange="actualizar();"></div>
	<div class="campo"><div>Manzana</div><input class="captura" size=3 id="fmanz" name="fmanz" OnChange="actualizar();"></div>
	<div class="campo"><div>Predio</div><input class="captura" size=3 id="fpred" name="fpred" OnChange="actualizar();"></div>
	<div class="campo"><div>Estado</div>
		<select class="captura" id="festado" name="festado" OnChange="actualizar();">
			<?php echo $estados; ?>
		</select>
	</div>
	<div class="campo"><div>Colaborador</div>
		<select class="captura" id="fdigita" name="fdigita" OnChange="actualizar();">
			<?php echo $colaboradores; ?>
		</select>
	</div>


<!--end  filtros segun se requiera -->
</div>
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' >    ASIGNACION DE PSICOLOGIA <!-- CAMBIAR TITULO DE LA VENTANA -->
		<nav class='menu left' >
			<li class='icono listado' title='Ver Listado' onclick="desplegar(mod+'-lis');" ></li>
			<li class='icono exportar'      title='Exportar Información General'    Onclick="csv(mod);"></li>
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
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


	
