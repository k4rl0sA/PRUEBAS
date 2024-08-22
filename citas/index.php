<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sigrev || Solicitar Cita</title>
<link href="https://fonts.googleapis.com/css2?family=Economica&family=Spicy+Rice&family=Trade+Winds&display=swap" rel="stylesheet">
<link href="../libs/css/styleNav.css" rel="stylesheet" type="text/css">
<link href="../libs/css/s.css" rel="stylesheet">
<script src="../libs/js/a.js"></script>
<script src="../libs/js/x.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script >
var mod='solcita';	
var ruta_app='lib.php';
function csv(b){
		var myWindow = window.open("../libs/gestion.php?a=exportar&b="+b,"Descargar archivo");
}

function actualizar(){
	act_lista(mod);
}

function getPerson() {	
	var id = document.getElementById('idp');
	var tp= document.getElementById('tdo');
	if (id.value!='' && tp.value!=''){
		if (loader != undefined) loader.style.display = 'block';
			if (window.XMLHttpRequest)
				xmlhttp = new XMLHttpRequest();
			else
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				xmlhttp.onreadystatechange = function () {
					if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
						var cmp=['idp','tdo','no1','no2','ap1','ap2','fen','gen','fec'];
						for(i=2;i<cmp.length;i++){
							document.getElementById(cmp[i]).value='';
						}
						try {
							var rta=JSON.parse(xmlhttp.responseText);
							if(rta==null){
								rta1=getPersonExt();
								if(rta1==null){
									return;
								}else{
									data =rta1;
									console.log(data);
									var data=Object.values(data);
									for(i=0;i<cmp.length;i++){
										document.getElementById(cmp[i]).value=data[i];
									}
								}
							}else{
								data =rta;
								console.log(data);
								var data=Object.values(data);
								for(i=0;i<cmp.length;i++){
									document.getElementById(cmp[i]).value=data[i];
								}
							}	
						} catch (e) {
							return;
						}							
					}
				}
				xmlhttp.open("POST", ruta_app,true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send('a=get&tb=persona&id='+id.value+'_'+tp.value);
				if (loader != undefined) loader.style.display ='none';		  
	}
}

//~ function grabar(tb='',ev){
  //~ if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  //~ var f=document.getElementsByClassName('valido '+tb);
   //~ for (i=0;i<f.length;i++) {
     //~ if (!valido(f[i])) {f[i].focus(); return};
  //~ }  
  //~ document.getElementById(tb+'-msj').innerHTML=ajax(ruta_app,"a=gra&tb="+tb,false);
  //~ if (document.getElementById(tb+'-msj') != undefined)
  //~ act_lista(tb+'uso');
//~ }


function getPersonExt() {	
	var id = document.getElementById('idp');
	var tp= document.getElementById('tdo');
	if (id.value!='' && tp.value!=''){
		if (loader != undefined) loader.style.display = 'block';
			if (window.XMLHttpRequest)
				xmlhttp = new XMLHttpRequest();
			else
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				xmlhttp.onreadystatechange = function () {
					if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
						var cmp=['idp','tdo','no1','no2','ap1','ap2','fen','gen','fec'];
						for(i=2;i<cmp.length;i++){
							document.getElementById(cmp[i]).value='';
						}
						try {
							var rta=JSON.parse(xmlhttp.responseText);
							if(rta==null){
								alert('No se encontro el Tipo y Documento ingresado, por favor valide');
								return;
							}else{
								data =rta;
								console.log(data);
								var data=Object.values(data);
								for(i=0;i<cmp.length;i++){
									document.getElementById(cmp[i]).value=data[i];
								}
							}	
						} catch (e) {
							return;
						}							
					}
				}
				xmlhttp.open("POST", ruta_app,true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send('a=get&tb=persona_ext&id='+id.value+'_'+tp.value);
				if (loader != undefined) loader.style.display = 'none';				         
	}
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
	  alert('El valor del caSOLICITAR CITAmpo Motivo debe contener una fecha valida,No puede estar vacio, por favor valide');
  }else if(obs.value==2 ){
	  validDate('mot3');
	if (Array.isArray(valDate('mot3'))){
		  alert(rta[0]);
		  return rta[1];
	}else{
		document.getElementById(tb+'-msj').innerHTML=ajax(ruta_app,"a=gra&tb="+tb,false);
		if (document.getElementById(tb+'-msj') != undefined) act_lista(tb+'uso');
	}
	  //~ valDate('mot3');
  //~ }else if(obs.value==3 && mot2.value==''){
	  //~ alert('El valor del campo Motivo,No puede estar vacio, por favor valide');
  }else{
   //VALIDACIONES FRECUENCIA DE USO
	document.getElementById(tb+'-msj').innerHTML=ajax(ruta_app,"a=gra&tb="+tb,false);
	if (document.getElementById(tb+'-msj') != undefined)
		act_lista(tb+'uso');
	}
}

</script>
</head>
<body Onload="actualizar();">
<?php
require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}
$info=datos_mysql("SELECT nombre,perfil FROM usuarios WHERE id_usuario='".$_SESSION["us_sds"]."'");
$mod='solcita';
$ya = new DateTime();
$estados=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=145 and estado='A' order by 1",'');
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`='DIG' and estado='A' ORDER BY 1",''); 
?>

<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>
	
	<div class="campo">
		<div>N° Documento</div>
		<input class="captura" size=50 id="fidpersona" name="fidpersona" OnChange="actualizar();">
	</div>
	
	<div class="campo">
		<div>Fecha Desde</div>
		<input type="date" class="captura" size=10 id="fdes" name="fdes" value='<?php echo $hoy; ?>' OnChange="actualizar();">
	</div>

	<div class="campo">
		<div>Fecha Hasta</div>
		<input type="date" class="captura" size=10 id="fhas" name="fhas" value='<?php echo $hoy; ?>' OnChange="actualizar();">
	</div>

	<div class="campo"><div>Estado Solicitud</div>
		<select class="captura" id="festado" name="festado" OnChange="actualizar();">
			<?php echo $estados; ?>
		</select>
	</div>
	 
</div>
<div class='col-8 panel' id='<?php echo$mod; ?>'>
      <div class='titulo' >SOLICITAR CITA
		<nav class='menu left' >
			<li class='icono listado' title='Ver Listado' onclick="desplegar(mod+'-lis');" ></li>
			<?php if ($info['responseResult'][0]['perfil']=='ADM' || $info['responseResult'][0]['perfil']=='TEC' ){
				echo "<li class='icono exportar'      title='Exportar CSV'    Onclick=\"csv(mod);\"></li>";	
			}?>
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
			<li class='icono crear'       title='Solicitar Cita'     Onclick="mostrar('solcita','pro',event,'','lib.php',7,'Solicitar Cita');"></li> <!--hideMotiv();-->
		</nav>
		<nav class='menu right' >
			<li class='icono ayuda'      title='Necesitas Ayuda'            Onclick=" window.open('https://sites.google.com/', '_blank');"></li>
            <li class='icono cancelar'      title='Salir'            Onclick="location.href='../main/'"></li>
        </nav>               
      </div>
      <div>
		</div>	
     <span class='mensaje' id='<?php echo$mod; ?>-msj' ></span>
     <div class='contenido' id='<?php echo$mod; ?>-lis' ></div>     
</div>
<div class='load' id='loader' z-index='0' ></div>
</form>	
</body>
