<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Asignación Ruteo || SIGINF</title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/a.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script>
var mod='asigruteo';	
var ruta_app='lib.php';
// function csv(b){
// 		var myWindow = window.open("../../libs/gestion.php?a=exportar&b="+b,"Descargar archivo");
// }

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
	if (tb == 'asigruteo') {
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




</script>
</head>
<body Onload="actualizar();">
<?php

require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='asigruteo';
$hoy = date("Y-m-d");

//  ["usu"]=> string(44) "CARLOS EDUARDO ACEVEDO AREVALO_ADM_5_SDS_EAC_EQU" }
$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
$usu=divide($rta["responseResult"][0]['usu']);
/*$grupos=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=11 and estado='A' order by 1",'');*/
// $estados=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=44 and estado='A' order by 1",'1');
$localidades=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=2 and estado='A' order by 1",'');
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`IN('ENFATE','MEDATE','PSIEAC') and subred=$usu[2] ORDER BY 1",$_SESSION['us_sds']);
?>
<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>
	
	<div class="campo"><div>Sector Catastral</div><input class="captura" size=6 id="fseca" name="fseca" OnChange="actualizar();"></div>
	<div class="campo"><div>Manzana</div><input class="captura" size=3 id="fmanz" name="fmanz" OnChange="actualizar();"></div>
	<div class="campo"><div>Predio</div><input class="captura" size=3 id="fpred" name="fpred" OnChange="actualizar();"></div>
	<!-- <div class="campo"><div>Estado</div>
		<select class="captura" id="festado" name="festado" onChange="actualizar();">'.<?php echo $estados;?></select>
	</div> -->
	</div>
	
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' >LOCALIZACIÓN GEOGRAFICA
		<nav class='menu left' >
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