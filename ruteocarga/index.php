<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Información Geografica Ruteo || SIGREV</title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/a.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script>
var mod='rut_geo';
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
	if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  		var f=document.getElementsByClassName('valido '+tb);
  		for (i=0;i<f.length;i++) {
			if (!valido(f[i])) {
				f[i].focus(); 
				return};
  		}
	myFetch(ruta_app,"a=gra&tb="+tb,mod);
}   



function enableAddr(a,b,c,d){
	const eru= document.querySelectorAll('input.'+b);
	const eur= document.querySelectorAll('input.'+c);
	const zon=document.querySelectorAll(d);
	if(zon==1){
		for (i=0; i<eur.length;i++) {
		if(a.value=='SI'){
			enaFie(eur[i],false);
  		}else{
			enaFie(eur[i],true);
		}
	}	
	}else{
		for (i=0; i<eru.length;i++) {
		if(a.value=='SI'){
			enaFie(eru[i],false);
  		}else{
			enaFie(eru[i],true);
		}
	}
	}
}

</script>
</head>
<body Onload="actualizar();">
<?php

require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

function subred($a = null){
	if ($a === null) $a = $_SESSION['us_sds'];
	$per=datos_mysql("select FN_SUBRED({$a}) as subred");
	$subred=$per["responseResult"][0]['subred'];
	return $subred;
}
function perfil1($a = null) {
    if ($a === null) $a = $_SESSION['us_sds'];
    $per = datos_mysql("SELECT FN_PERFIL({$a}) AS perfil");
    $perfil = $per["responseResult"][0]['perfil'];
    return $perfil;
}


$mod='rut_geo';
$ya = new DateTime();
$eventos=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=87 and estado='A' order by 1",'');
if(perfil1()=='ADM')
$localidades = (perfil1()=='ADM') ? opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=2 AND estado='A' order by 1",'') : opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=2 and valor='".subred()."' AND estado='A' order by 1",'') ;
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE `perfil` IN ('FAM') ORDER BY 1",$_SESSION['us_sds']);
?>

<form method='post' id='fapp' >
	<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>
	
	<div class="campo"><div>Documento</div><input class="captura" size=6 id="fdoc" name="fdoc" OnChange="actualizar();"></div>
	<div class="campo"><div>Evento</div>
		<select class="captura" id="fevento" name="fevento" onChange="actualizar();">'.<?php echo $eventos;?></select>
	</div>	
	<div class="campo"><div>Localidad</div>
	<select class="captura" id="flocal" name="flocal" onChange="actualizar();">'.<?php echo $localidades;?></select>
	</div>
	
	
	<?php 

	//  ["usu"]=> string(44) "CARLOS EDUARDO ACEVEDO AREVALO_ADM_5_SDS_EAC" }
	$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
	$usu=divide($rta["responseResult"][0]['usu']);
	
	$rta="";
	$rta = ($usu[1] == 'GEO'||$usu[1] =='ADM') ? '<div class="campo"><div>Colaborador</div><select class="captura" id="fdigita" name="fdigita" onChange="actualizar();">'.$digitadores.'</select></div><div class="campo"><div>Cargar Datos Geográficos</div></div><input class="button filtro" type="file" id="inputFile1" name="inputFile1" style="width: 350px;"><br><button class="button campo" title="Cargar Archivo" id="btnLoad" type="button">IMPORTAR</button></div></div>':'';
	$rta.='</div>';
	echo $rta;
	?>



<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' >LOCALIZACIÓN GEOGRAFICA FAMILIAS
		<nav class='menu left' >
			<li class='icono listado' title='Ver Listado' onclick="desplegar(mod+'-lis');" ></li>
			<li class='icono exportar'      title='Exportar Información General'    Onclick="csv(mod);"></li>
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
			<!-- <li class='icono crear'       title='Crear'     Onclick="mostrar(mod,'pro');"></li> setTimeout(load,500); -->
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
<script>
	let btnEnviar = document.querySelector("#btnEnviar"),btnLoad = document.querySelector("#btnLoad"),inputFile = document.querySelector("#inputFile");
	
	
	btnLoad.addEventListener("click", () => {
		if (inputFile1.files.length > 0 ) {
			var formData = new FormData();
			formData.append("ncol", 31);
			formData.append("tab", "vspgeo");
			formData.append("archivo", inputFile1.files[0]);
			var req = new XMLHttpRequest();
			req.addEventListener("readystatechange",function(){
				if(this.readyState === 4){
					if(this.status === 200){
						document.getElementById("cmprstss").innerHTML = this.responseText;
						act_lista(mod);
					}
				}
			});
			req.open("POST", "../libs/import.php", true);
			req.send(formData);
		} else {
			errors("Selecciona un archivo valido");
		}
	});
	
	document.addEventListener("keydown", function(event) {
  if (event.keyCode === 13 || event.key === "Enter") {
	actualizar();
    event.preventDefault();
	
  }
});

</script>	
<div class="overlay" id="overlay" onClick="closeModal();">
	<div class="popup" id="popup" z-index="0" onClick="closeModal();">
		<div class="btn-close-popup" id="closePopup" onClick="closeModal();">&times;</div>
		<h3><div class='image' id='<?php echo$mod; ?>-image'></div></h3>
		<h4><div class='message' id='<?php echo$mod; ?>-modal'></div></h4>
	</div>			
</div>
</body>