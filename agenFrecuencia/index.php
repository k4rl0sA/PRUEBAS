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


function grabar(tb='',ev){
  if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  var f=document.getElementsByClassName('valido '+tb);
   for (i=0;i<f.length;i++) {
     if (!valido(f[i])) {f[i].focus(); return};
  }
   //VALIDACIONES FRECUENCIA DE USO
   if (obs.value==3 && cit.value!=11 ){
	  alert('La observación no corresponde al tipo de cita asignado, por favor valide');
  }else if(cit.value==18 && (obs.value!=1 || (mot3.value!='' || mot2.value!=''))){
	  alert('El valor del campo Observaciones ó Motivo,No corresponde con respecto al tipo de Cita asignado, por favor valide');
  }else if(sex.value=='H' && (cit.value==7 || cit.value==12 || cit.value==14 || cit.value==8 )){
	  alert('El valor del campo Sexo, no corresponde con respecto al tipo de Cita asignado, por favor valide');
  }else if(sex.value=='M' && cit.value==13){
	  alert('El valor del campo Sexo, no corresponde con respecto al tipo de Cita asignado, por favor valide');  
  }else if(cit.value==11 && (obs.value==3 && (mot2.value!='' ||mot3.value!=''))){
	  alert('El valor del campo Observaciones ó Motivo,No corresponde con respecto al tipo de Cita asignado, por favor valide');
  //~ }else if(cit.value==13 && (obs.value!=1)){
	  //~ alert('El valor del campo Observaciones,No corresponde con respecto al tipo de Cita asignado, por favor valide');
  }else if(cit.value==13 && obs.value==1 && mot2.value==''){
	  alert('El valor del campo Motivo,No corresponde con respecto al tipo de Cita asignado, por favor valide');
  }else if(obs.value==2 && mot3.value==''){
	  alert('El valor del campo Motivo debe contener una fecha valida,No puede estar vacio, por favor valide');
  }else if(obs.value==2 ){
	  validDate('mot3');
	if (Array.isArray(valDate('mot3'))){
		  alert(rta[0]);
		  return rta[1];
	}else{
	myFetch(ruta_app,"a=gra&tb="+tb,mod);
    act_lista(tb+'uso');
    }
}else{
	myFetch(ruta_app,"a=gra&tb="+tb,mod);
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
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`='DIG' and estado='A' ORDER BY 1",''); 
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
			<li class='icono crear' title='Crear' onclick="mostrar('mod','pro');"></li><!-- hideMotiv(); -->
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