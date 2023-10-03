<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Caracterizacion Hogar || GIF-SDS</title>
<!-- <link href="../libs/css/s.css" rel="stylesheet"> -->
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<script src="../libs/js/a.js"></script>
<script src="../libs/js/x.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script>
var mod='homes';
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
			if (!valido(f[i])) {
				f[i].focus(); 
				return};
  		}
		if (tb=='homes'){
			const per=document.getElementById('numero_perros');
			const pva=document.getElementById('perro_vacunas');
			const pes=document.getElementById('perro_esterilizado');
			const gat=document.getElementById('numero_gatos');
			const gva=document.getElementById('gato_vacunas');
			const ges=document.getElementById('gato_esterilizado');
			if(pva.value>per.value || pes.value>per.value || gva.value>gat.value || ges.value>gat.value){
				warnin('El valor de vacunados o esterilizados esta errado, no puede ser superior al numero de mascotas,por favor valide e intente nuevamente');
				/* document.getElementById(tb+'-modal').innerHTML='El valor de vacunados o esterilizados esta errado, no puede ser superior al numero de mascotas,por favor valide e intente nuevamente';
				document.getElementById(mod+'-image').innerHTML='<svg class="icon-popup" ><use xlink:href="#bad"/></svg>'; 
				openModal();*/
				return;
			}
		}
		if(tb=='acompsic'){
			if (!min1ElmSi('empatia','hL')){
				warnin('Debe seleccionar por lo menos una Habilidad con "SI"');
				return;
			}
		}
		var rutaMap = {
  'acompsic': 'acompsic.php',
  'apopsicduel': 'apopsicduel.php',
  'bpnpret':'bpnpret.php',
  'bpnterm':'bpnterm.php',
  'cancinfa':'cancinfa.php',
  'cronicos': 'cronicos.php',
  'eraira':'eraira.php',
  'gestantes':'gestantes.php',
  'hbgest':'hbgest.php',
  'mnehosp': 'mnehosp.php',
  'otroprio': 'otroprio.php',
  'saludoral': 'saludoral.php',
  'sificong':'sificong.php',
  'sifigest':'sifigest.php',
  'vihgest':'vihgest.php',
  'violreite': 'violreite.php',
  'vspeve':'vspeve.php',
  'dntsevymod':'dntsevymod.php',
  'condsuic':'condsuic.php',
  'medidas':'../vivienda/medidas.php',
  'ambient':'../vivienda/amb.php'
};

var ruta_app = rutaMap[tb] || 'lib.php';
	myFetch(ruta_app,"a=gra&tb="+tb,mod);
}   

function disFecar(a){
	const x=document.getElementById(a);
	if (x.value!=''){
		x.disabled=true;
	}
}


//*********************************INICIO FUNCIONES VSP*************************
function enabEfec(a,clsCmp,clsNb){
	// var elementos = document.querySelectorAll('.clase-especifica:not(.otra-clase)');
	const ele = document.querySelectorAll('select.'+clsCmp+',input.'+clsCmp);
	const act=document.getElementById(a);
	if (act.value=='1'){
		for (i = 0; i < ele.length; i++){ 
				hidFie(ele[i],false);
		}
	}else{
		for (i = 0; i < ele.length; i++){ 
			hidFie(ele[i],true);
		}	
	}
}

//*********************************FIN FUNCIONES VSP*************************


</script>
</head>
<body Onload="actualizar();">
<?php
	require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='homes';
$hoy = date("Y-m-d");
$ayer = date("Y-m-d",strtotime($hoy."- 1 days")); 
/* $rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
$usu=divide($rta["responseResult"][0]['usu']); */
/*$grupos=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=11 and estado='A' order by 1",'');*/
$localidades=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=2 and estado='A' order by 1",'');
//$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`IN('PROFAM') and subred=$usu[2] ORDER BY 2",'');//$_SESSION['us_sds']

?>
<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>
	<div class="campo"><div>Sector Catastral</div><input class="captura" size=6 id="fsector" name="fsector" OnChange="actualizar();"></div>
	<div class="campo"><div>Manzana</div><input class="captura" size=6 id="fmanz" name="fmanz" OnChange="actualizar();"></div>
	<div class="campo"><div>Predio</div><input class="captura" size=6 id="fpred" name="fpred" OnChange="actualizar();"></div>
	<!-- <div class="campo"><div>Colaborador</div>
		<select class="captura" id="fdigita" name="fdigita" OnChange="actualizar();">
			<?php echo''; // $digitadores; ?>
		</select>
	</div>-->
	<div class="campo"><div>Localidad</div>
		<select class="captura" id="flocalidad" name="flocalidad" OnChange="actualizar();">
			<?php echo $localidades; ?>
		</select>
	</div>
	<div class="campo">
		<div>Fecha Asignado Desde</div>
		<input type="date" class="captura" size=10 id="fdes" name="fdes" value='<?php echo$ayer; ?>' OnChange="actualizar();">
		
	</div>
	<div class="campo">
		<div>Fecha Asignado Hasta</div>
		<input type="date" class="captura" size=10 id="fhas" name="fhas" value='<?php echo$hoy; ?>' OnChange="actualizar();">
	</div>
</div>
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' >    CARACTERIZACIÓN VIVIENDAS
		<nav class='menu left' >
			<li class='icono listado' title='Ver Listado' onclick="desplegar(mod+'-lis');" ></li>
			<!-- <li class='icono exportar'      title='Exportar Información General'    Onclick="csv(mod);"></li> -->
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
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


	
