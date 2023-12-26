<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Información Geografica || SIGINF</title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/a.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script>
var mod='hog_geoloc';	
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

/* function getData(a, ev,i,blo) {
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
} */


function grabar(tb='',ev){
  if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  var f=document.getElementsByClassName('valido '+tb);
   for (i=0;i<f.length;i++) {
     if (!valido(f[i])) {f[i].focus(); return};
  }
	if (tb == 'hog_geoloc') {
		var ndir = document.getElementById('direccion_nueva'),
			sec = document.getElementById('sector_catastral'),
			cox = document.getElementById('cordx'),
			ver = document.getElementById('vereda'),
			coy = document.getElementById('cordy');
		if (sec.value == 2  && ndir.value!=='') {
			var err='No se puede ingresar una nueva dirección ya que esta no aplica para el sector Catastral,por favor valide e intente nuevamente';		
			showErr(err,tb);
			return
		}else if(sec.value == 2  && (cox.value=='' || coy.value==''|| ver.value=='' )){
			var err='las Coordenadas ó la Vereda no pueden estar vacias, para el sector Catastral,por favor valide e intente nuevamente';
			showErr(err,tb);
			return
		}
	}
	myFetch(ruta_app,"a=gra&tb="+tb,mod);
	setTimeout(actualizar,500);
	
}   


function enableAddr(a,b,c,d){
	const eru= document.querySelectorAll('input.'+b);
	const eur= document.querySelectorAll('input.'+c);
	const zon=document.getElementById(d).value;
	if(zon==='1'){
		for (i=0; i<eru.length;i++) {
		if(a.value=='SI'){
			enaFie(eru[i],false);
  		}else{
			enaFie(eru[i],true);
		}
		}	
	}else{
		for (i=0; i<eur.length;i++) {
		if(a.value=='SI'){
			enaFie(eur[i],false);
  		}else{
			enaFie(eur[i],true);
		}
	}
	}
	
}

function enaFie(ele, flag) {
	if(ele.type==='checkbox' && ele.checked==true){
		ele.checked=false;
	}else{
		ele.value = '';
	}
    ele.disabled = flag;
    ele.required = !flag;
    ele.classList.toggle('valido', !flag);
    ele.classList.toggle('captura', !flag);
    ele.classList.toggle('bloqueo', flag);
    flag ? ele.setAttribute('readonly', true) : ele.removeAttribute('readonly');
}


function enabFielSele(a, b, c, d) {
	for (i = 0; i < c.length; i++) {
    	var ele = document.getElementById(c[i]);
    	enaFie(ele, !d.includes(a.value) || !b);
  	}
}



/* function hideExpres(a,b) {
  var sel = document.getElementById(a);
  var ele = document.querySelectorAll('#' + mod + '-pro-con .expres');
  hideSection(sel,ele,b);
}

function hideSection(a,b,c){
	for (i = 0; i < b.length; i++) {
    	b[i].hidden = a.value === '' || c.indexOf(a.value) === -1;
    	if (b[i].hidden) {
      		b[i].value = '';
      		b[i].required = false;
    	}
  	}	
} */

  /* for (i = 0; i < ele.length; i++) {
    ele[i].hidden = sel.value === '' || d.indexOf(sel.value) === -1;
    if (ele[i].hidden) {
      ele[i].value = '';
      ele[i].required = false;
    }
  }

  function showFil(a){
	desplegar(a+'-fil');
	if (document.getElementById(a) != undefined) {
		var w=document.getElementById(a);
		if(w.classList.contains('col-8')){
			w.classList.replace('col-8','col');
		}else{
			w.classList.replace('col','col-8');
		}
		
	}
}

function disaFielChec(a, b,c) {
	for (let i = 0; i < c.length; i++) {
		let ele = document.getElementById(c[i]);
		ele.disabled = a.checked ? false : true;
		ele.required = a.checked ? true : false;
		// ele.classList.toggle('valido', false);
		ele.classList.toggle('captura', a.checked ? true : false);
		ele.classList.toggle('bloqueo', a.checked ? false : true);
		a.checked ? ele.removeAttribute('readonly'):ele.setAttribute('readonly', true);
	}
}
 */


/* function load(){
var a=document.getElementById('tcv');
hidePanel(a,'SI',5);
hidePanel(a,'NO',13);
} */

/* function plegar(){
	var a='integrantes';
	var b=['tracui','jefhog','afisss','infsan','tampri','aterut','alepri','matper','salmen','cervas','higora','pobvul','discap','resmed'];
	for(h=0;h<b.length;h++){
		plegarPanel(a,b[h]);
	}
} */

/* function colorRow(){
var table = document.getElementById("myTable");
  var rows = table.getElementsByTagName("tr");

  for (var i = 0; i < rows.length; i++) {
    rows[i].addEventListener("click", function() {
      var current = document.getElementsByClassName("selected-row");
      if (current.length > 0) {
        current[0].classList.remove("selected-row");
      }
      this.classList.add("selected-row");
    });
  }
}
 */
</script>
</head>
<body Onload="actualizar();">
<?php

require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='hog_geoloc';
$hoy = date("Y-m-d");

//  ["usu"]=> string(44) "CARLOS EDUARDO ACEVEDO AREVALO_ADM_5_SDS_EAC_EQU" }
//$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
//$usu=divide($rta["responseResult"][0]['usu']);
/*$grupos=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=11 and estado='A' order by 1",'');*/
$estados=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=44 and estado='A' order by 1",'1');
$localidades=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=2 and estado='A' order by 1",'');
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE `perfil` IN('AUX','MED') and subred=(SELECT subred FROM usuarios where id_usuario='{$_SESSION['us_sds']}') ORDER BY 1",$_SESSION['us_sds']);
$perfi=datos_mysql("SELECT perfil as perfil FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}'");
$perfil = (!$perfi['responseResult']) ? '' : $perfi['responseResult'][0]['perfil'] ;

$import = ($perfil == 'GEO'||$perfil =='ADM'||$perfil =='ADMEAC') ? '<div class="campo"><div>Colaborador</div><select class="captura" id="fdigita" name="fdigita" onChange="actualizar();">'.$digitadores.'</select></div><div class="campo"><div>Cargar Datos Geográficos</div></div><input class="button filtro" type="file" id="inputFile1" accept=".csv" name="inputFile1" style="width: 350px;"><br><button class="button campo" title="Cargar Archivo" id="btnLoad" type="button">IMPORTAR</button></div></div>':'';
$crea = ($perfil == 'ADM') ? "<li class='icono crear' title='Crear' onclick=\"mostrar('{$mod}','pro');\"></li><li class='icono exportar'      title='Exportar Información General'    Onclick='csv(mod);'></li>":"";
?>
<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>
	
	<div class="campo"><div>Sector Catastral</div><input class="captura" size=6 id="fseca" name="fseca" OnChange="actualizar();"></div>
	<div class="campo"><div>Manzana</div><input class="captura" size=3 id="fmanz" name="fmanz" OnChange="actualizar();"></div>
	<div class="campo"><div>Predio</div><input class="captura" size=3 id="fpred" name="fpred" OnChange="actualizar();"></div>
	<div class="campo"><div>Estado</div>
		<select class="captura" id="festado" name="festado" onChange="actualizar();">'.<?php echo $estados;?></select>
	</div>
	<?php echo $import; ?>
	</div>
	
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' >LOCALIZACIÓN GEOGRAFICA
		<nav class='menu left' >
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
			<?php echo $crea; ?>
					
			
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
<script>
	let btnEnviar = document.querySelector("#btnEnviar"),btnLoad = document.querySelector("#btnLoad"),inputFile = document.querySelector("#inputFile");
	btnLoad.addEventListener("click", () => {
	uploadCsv(30, "geografico", inputFile1, "../libs/impGeo.php", mod);
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