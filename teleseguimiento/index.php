<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teleseguimiento || GIF SDS</title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/c.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script>
var mod='seguimiento';	
var ruta_app='lib.php';
function csv(b){
		var myWindow = window.open("../../libs/gestion.php?a=exportar&b="+b,"Descargar archivo");
}

document.onkeyup=function(ev) {
	ev=ev||window.event;
	if (ev.ctrlKey && ev.keyCode==46) ev.target.value='';
	if (ev.ctrlKey && ev.keyCode==45) ev.target.value=ev.target.placeholder;
};


function actualizar(){
	act_lista(mod);
}

function getData(a, ev,i,blo) {
	if (ev.type == 'click') {
		var c = document.getElementById(a+'-pro-con');
		var cmp=c.querySelectorAll('.captura,.bloqueo')
		if (loader != undefined) loader.style.display = 'block';
			if (window.XMLHttpRequest)
				xmlhttp = new XMLHttpRequest();
			else
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				xmlhttp.onreadystatechange = function () {
				if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
					data =JSON.parse(xmlhttp.responseText);
					if (loader != undefined) loader.style.display = 'none';
						console.log(data)
					}
				}
				xmlhttp.open("POST", ruta_app,false);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send('a=get&tb='+a+'&id=' + i.id);
				var rta =data;
				var data=Object.values(rta);
				for (i=0;i<cmp.length;i++) {
					//~ if cmp[i]==27{
						cmp[i].value=data[i];
						if(cmp[i].type==='checkbox')cmp[i].checked=false;
							if (cmp[i].value=='SI' && cmp[i].type==='checkbox'){
								cmp[i].checked=true;
							}else if(cmp[i].value!='SI' && cmp[i].type==='checkbox'){
								cmp[i].value='NO';
							}
							for (x=0;x<blo.length;x++) {
								if(cmp[i].name==blo[x]) cmp[i].disabled = true;
							}
				}
	}
}

function changeSelect(a,b){
	if(b!=''){		
		const x = document.getElementById(a);
		const z = document.getElementById(b);
		z.innerHTML="";
		loader=document.getElementById('loader');
		if (loader != undefined) loader.style.display = 'block';
		if (window.XMLHttpRequest)
			xmlhttp = new XMLHttpRequest();
		else
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			
			xmlhttp.onreadystatechange = function () {
			if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
				data =JSON.parse(xmlhttp.responseText);
				console.log(data);
			}}
				xmlhttp.open("POST", ruta_app,false);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send('a=opc&tb='+a+b+'&id='+x.value);
				//~ var rta =data;
				var data=Object.values(data);
				var opt = document.createElement('option');
				opt.text ='SELECCIONE';
				opt.classList.add('alerta');
				opt.value='0';
				z.add(opt);
				for(i=0;i<data.length;i++){
					var obj=Object.keys(data[i]);					
					var opt = document.createElement('option');
					opt.text =data[i][obj[1]];
					opt.value=data[i][obj[0]];;
					z.add(opt);
				}
				if (loader != undefined) loader.style.display = 'none';
	}
}

function grabar(tb='',ev){
  if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  var f=document.getElementsByClassName('valido '+tb);
   for (i=0;i<f.length;i++) {
     if (!valido(f[i])) {f[i].focus(); return};
  }
  myFetch(ruta_app,"a=gra&tb="+tb,mod);
	/* if (document.getElementById(mod+'-modal').innerHTML.includes('Correctamente')){
		document.getElementById(mod+'-image').innerHTML='<svg class="icon-popup" ><use xlink:href="#ok"/></svg>';
	}else{
		document.getElementById(mod+'-image').innerHTML='<svg class="icon-popup" ><use xlink:href="#bad"/></svg>';
	}
	openModal(); */
	setTimeout(actualizar, 1000);
	setTimeout(mostrar('person1','fix',event,'','lib.php',0,'person1'),500);
} 


</script>
</head>
<body Onload="actualizar();">
<?php
	require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='seguimiento';
$ya = new DateTime();
// $usu=;
/*$grupos=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=11 and estado='A' order by 1",'');*/
// $digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`='AUX' ORDER BY 1",$_SESSION['us_sds']);
?>
<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>
	
	<div class="campo">
		<div>N° Documento</div>
		<input class="captura" size=50 id="fidpersona" name="fidpersona" OnChange="actualizar();">
	</div>
	
	<div class="campo">
		<div>Fecha</div>
		<input type="date" class="captura" size=10 id="ffecha" name="ffecha" OnChange="actualizar();">
	</div>
	

	<!-- <div class="campo"><div>Colaborador</div>
		<select class="captura" id="fdigita" name="fdigita" OnChange="actualizar();">
			<?php echo $digitadores; ?>
		</select>
	</div> -->
	<!-- <div class="campo"><div>Localidad</div>
		<select class="captura" id="flocalidad" name="flocalidad" OnChange="actualizar();">
		<?php 
			// echo $localidades; 
		?>		
		</select>
	</div> -->
	
	<!-- <div class="campo"><div>Estado (A/I)</div>
		<select class="captura" id="festado" name="festado" OnChange="actualizar();">
			<?php 
			// echo $estados; 
			?>
		</select>
	</div> -->
</div>
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' >TELESEGUIMIENTO
		<nav class='menu left' >
			<li class='icono listado' title='Ver Listado' onclick="desplegar(mod+'-lis');" ></li>
			<li class='icono exportar'      title='Exportar Información General'    Onclick="csv(mod);"></li>
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