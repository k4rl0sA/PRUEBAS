<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>	
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Frecuencia de Uso || <?php echo $APP; ?></title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/a.js?v=1.5"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script>
var mod='frecuenciauso';	
var ruta_app='lib.php';


document.onkeyup=function(ev) {
	ev=ev||window.event;
	if (ev.ctrlKey && ev.keyCode==46) ev.target.value='';
	if (ev.ctrlKey && ev.keyCode==45) ev.target.value=ev.target.placeholder;
};


function actualizar(){
	act_lista(mod);
}

function validDate(a){
		let Ini=dateAdd(-1847);
		let Fin=dateAdd();
	
	let min=`${Ini.a}-${Ini.m}-${Ini.d}`;
	let max=`${Fin.a}-${Fin.m}-${Fin.d}`;
	var a=document.getElementById(a);
	//~ var max=dayjs(`${date.getFullYear()}-${date.getMonth()+1}-${date.getDate()}`).format('YYYY-MM-DD');
	RangeDateTime(a.id,min,max);
	//~ validTime('hci');
}

function valDate(a){
	var b=document.getElementById(a);
	if (b.value<b.min || b.value>b.max){
		error='El valor de la fecha debe ser igual o Posterior a ('+b.min+') ó igual o Inferior a ('+b.max+'), por favor valide para continuar.';
		return rta=[error,false];
	}else{
		return true;
	}
}

function hideMotiv(){
	setTimeout(function(){
		motiv=document.getElementById('frecuenciauso-pro-con').getElementsByClassName('col-6');
		for (i=0;i<motiv.length;i++){
			motiv[i].style.display='none'
			motiv[i].setAttribute("id",i+1);
		}
	},100);
}

function loadMotiv(){
	setTimeout(function(){
		const x = document.getElementById('obs');
		var mo3=document.getElementById('mot3'),
		mo2=document.getElementById('mot2'),
		cit=document.getElementById('cit');
		if (x.value==2){
			document.getElementById('1').style.display = 'block';
			mo2.value="";
		//~ }else if(x.value==3){
			//~ document.getElementById('2').style.display = 'block';
			//~ mo3.value="";
		}else if(x.value==3 && cit.value==11){
			document.getElementById('2').style.display = 'none';
			mo3.value="";
			mo2.value="";
		}else if(x.value==1 && cit.value==13){
			document.getElementById('2').style.display = 'block';
			mo3.value="";
		}else{
			
		}
	},100);
}

function changeSelect(a,b){
	if(b!=''){
		var pmot1=document.getElementById('1'),
		pmot2=document.getElementById('2')
		mo3=document.getElementById('mot3'),
		mo2=document.getElementById('mot2');
		const x = document.getElementById('obs');
		if (x.value==2){
			mo2.value="";
			mo3.value="";
			pmot2.style.display = 'none';
			pmot1.style.display = 'block';
		//~ }else if(x.value==3){
			//~ mo3.value="";
			//~ pmot1.style.display = 'none';
			//~ pmot2.style.display = 'block';
		}else if(x.value==3 && cit.value==11){
			pmot2.style.display = 'none';
			mo3.value="";
			mo2.value="";
		}else if(x.value==1 && cit.value==13){
			pmot2.style.display = 'block';
			mo3.value="";
			mo2.value="";
			pmot1.style.display = 'none';
		}else{
			mo3.value="";
			mo2.value="";
			hideMotiv();
		}
	}
}

function grabar(tb='',ev){
	var cit= document.getElementById('cit'),
	obs= document.getElementById('obs'),	
	sex= document.getElementById('gen'),
	mot3= document.getElementById('mot3'),
	mot2= document.getElementById('mot2');
	
  if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  var f=document.getElementsByClassName('valido '+tb);
   for (i=0;i<f.length;i++) {
     if (!valido(f[i])) {f[i].focus(); return};
  }
  //VALIDACIONES FRECUENCIA DE USO
  if(sex.value=='H' && (cit.value==7 || cit.value==12 || cit.value==14 || cit.value==8 )){
	  alert('El valor del campo Sexo, no corresponde con respecto al tipo de Cita asignado, por favor valide');
  }else if(sex.value=='M' && cit.value==13){
	  alert('El valor del campo Sexo, no corresponde con respecto al tipo de Cita asignado, por favor valide');  
	}else{
		myFetch(ruta_app,"a=gra&tb="+tb,mod);
		setTimeout(actualizar, 1000);
		act_lista(tb+'uso');
	}
}

</script>
</head>
<body Onload="actualizar();">
<?php

require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='frecuenciauso';
$ya = new DateTime();
$estados=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=11 and estado='A' order by 1",'');
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`='DIG' and estado='A' AND subred=(select subred from usuarios where id_usuario='{$_SESSION['us_sds']}') ORDER BY 1",''); 
?>
<form method='post' id='fapp'>
<div class="col-2 menu-filtro" id='<?php echo $mod; ?>-fil'>
	
<div class="campo">
		<div>N° Documento</div>
		<input class="captura" size=50 id="fidpersona" name="fidpersona" OnChange="actualizar();">
	</div>
	
	<div class="campo"><div>Digitador</div>
		<select class="captura" id="fdigita" name="fdigita" OnChange="actualizar();">
			<?php echo $digitadores; ?>
		</select>
	</div>
	<div class="campo"><div>Estado Cita</div>
		<select class="captura" id="festado" name="festado" OnChange="actualizar();">
			<?php echo $estados; ?>
		</select>
	</div>
	<div class="campo">
		<div>Fecha Desde</div>
		<input type="date" class="captura" size=10 id="fdes" name="fdes" OnChange="actualizar();">
	</div>
	<div class="campo">
		<div>Fecha Hasta</div>
		<input type="date" class="captura" size=10 id="fhas" name="fhas" OnChange="actualizar();">
	</div>
	
	</div>
	
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' >FRECUENCIA DE USO
		<nav class='menu left' >
			<li class='icono listado' title='Ver Listado' onclick="desplegar(mod+'-lis');" ></li>
			<!-- <li class='icono exportar'      title='Exportar Información General'    Onclick="csv(mod);"></li> -->
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
			<li class='icono crear'       title='Crear frecuencia de Uso'     Onclick="mostrar(mod,'pro');hideMotiv();"></li><!--hideMotiv();-->
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