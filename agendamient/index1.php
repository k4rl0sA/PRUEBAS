<?php
session_start();
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agendamiento || Sigrev</title>
<link href="https://fonts.googleapis.com/css2?family=Economica&family=Spicy+Rice&family=Trade+Winds&display=swap" rel="stylesheet">
<link href="../libs/css/s.css" rel="stylesheet">
<script src="../libs/js/c18082020.js"></script>
<script src="../libs/js/d.js"></script>
<script>
var mod='agendamiento';	
var ruta_app='lib.php';
var hoy = new Date().toISOString().slice(0,10);
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


function changeSelect(a,b,c){		
		const x = document.getElementById(a);
		const y = document.getElementById(b);
		const z = document.getElementById(c);
		if(a!=null && b!=null && c!=null){
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
				xmlhttp.send('a=opc&tb='+a+b+'&id='+x.value+'_'+y.value);
			//~ //	var rta =data;
				var data=Object.values(data);
				var opt = document.createElement('option');
				opt.text ='SELECCIONE';
				opt.classList.add('alerta');
				opt.value='';
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
						var cmp=['idp','tdo','no1','no2','ap1','ap2','fen','gen','eap','te1','te2','eda'];
						for(i=2;i<cmp.length;i++){							
								document.getElementById(cmp[i]).value='';
						}
								
						try {
							var rta=JSON.parse(xmlhttp.responseText);
							if(rta==null){
								//~ if (loader != undefined) loader.style.display = 'none';
								//~ alert('No se encontro el Tipo y Documento ingresado, por favor valide');
								//~ return;
								
								rta1=getPersonExt();
								if(rta1==null){
									if (loader != undefined) loader.style.display = 'none';
									//~ //alert('No se encontro el Tipo y Documento ingresado, por favor valide');
									return;
								}else{
									data =rta1;
									console.log(data);
									var data=Object.values(data);
									for(i=0;i<cmp.length;i++){
										if(i==11){
										//~ //getEdad(cmp[6]);
										}else{
											document.getElementById(cmp[i]).value=data[i];
										}
									}
									changeSelect('idp','tdo','cit');
								if (loader != undefined) loader.style.display = 'none';
								}
								
								
								
							}else{
								data =rta;
								console.log(data);
								var data=Object.values(data);
								for(i=0;i<cmp.length;i++){
									if(i==11){
										//~ getEdad(cmp[6]);
									}else{
										document.getElementById(cmp[i]).value=data[i];
									}
								}
								changeSelect('idp','tdo','cit');
								if (loader != undefined) loader.style.display = 'none';
							}	
						} catch (e) {
							console.log(e);
							return;
						}							
					}
				}
				xmlhttp.open("POST", ruta_app,true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send('a=get&tb=persona&id='+id.value+'_'+tp.value);
	}
}
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
						var cmp=['idp','tdo','no1','no2','ap1','ap2','fen','gen','eap','te1','te2','eda'];
						for(i=2;i<cmp.length;i++){							
								document.getElementById(cmp[i]).value='';
						}
						try {
							var rta=JSON.parse(xmlhttp.responseText);
							if(rta==null){
								if (loader != undefined) loader.style.display = 'none';
								alert('No se encontro el Tipo y Documento ingresado, por favor valide');
								return;
							}else{
								data =rta;
								console.log(data);
								var data=Object.values(data);
								for(i=0;i<cmp.length;i++){
									if(i==11){
										//~ //getEdad(cmp[6]);
									}else{
										document.getElementById(cmp[i]).value=data[i];
									}
								}
								changeSelect('idp','tdo','cit');
								if (loader != undefined) loader.style.display = 'none';
							}	
						} catch (e) {
							console.log(e);
							return;
						}							
					}
				}
				xmlhttp.open("POST", ruta_app,true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send('a=get&tb=persona_ext&id='+id.value+'_'+tp.value);
	}
}


function getEdad(a){
		var f = new Date();
		var dateNow=dayjs(f.getFullYear()+'-'+(f.getMonth()+1)+'-'+f.getDate()).format('YYYY-MM-DD');
		difec=dateDiff(document.getElementById(a).value,dateNow);
		document.getElementById('eda').value='Años='+difec['años']+', Meses='+difec['meses']+', Dias='+difec['dias'];
		console.log(difec);
}

function grabar(tb='',ev){
  if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  var f=document.getElementsByClassName('valido '+tb);
   for (i=0;i<f.length;i++) {
     if (!valido(f[i])) {f[i].focus(); return};
  }
  if (tb=='agendamiento'){
	  var con=document.getElementById('con'),
	  cit=document.getElementById('cit');
	  if ((cit.value==1 || cit.value==2 || cit.value==3 ||cit.value==5 ||cit.value==7 ||cit.value==8 ||cit.value==12 ||cit.value==14 || cit.value==15) && con.value!=2){
		alert('El Tipo Cita NO corresponde con el tipo de Consulta,por favor valide e intente nuevamente');
	  }else{
		  document.getElementById(tb+'-pro-msj').innerHTML=ajax(ruta_app,"a=gra&tb="+tb,false);
			if (document.getElementById(tb+'-pro-msj') != undefined)
				act_lista(tb);
				act_lista('agendamiento');
	  }
  }else{
	document.getElementById(tb+'-pro-msj').innerHTML=ajax(ruta_app,"a=gra&tb="+tb,false);
	if (document.getElementById(tb+'-pro-msj') != undefined)
		act_lista(tb);
		act_lista('agendamiento');
  }

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

function validDate(a){
		let Ini=dateAdd();
		//26-05-2023
		let Fin=dateAdd(18);
	
	let min=`${Ini.a}-${Ini.m}-${Ini.d}`;
	//26-05-2023
	//let min=`2023-05-01`; //26-05-2023
	let max=`${Fin.a}-${Fin.m}-${Fin.d}`;
	
	//~ var max=dayjs(`${date.getFullYear()}-${date.getMonth()+1}-${date.getDate()}`).format('YYYY-MM-DD');
	RangeDateTime(a.id,min,max);
	validTime('hci');
}




function asist(a){
	//~ var asiste=document.getElementById(a.id);
	var cmp=['nom','tin','rea','est','obi'];
	if (a.value=='SI' && a.checked){
		for(i=0;i<cmp.length;i++){							
			document.getElementById(cmp[i]).disabled = true;
			document.getElementById(cmp[i]).required=false;
			document.getElementById(cmp[i]).classList.remove('valido');
		}
	}else{
		for(i=0;i<cmp.length;i++){							
			document.getElementById(cmp[i]).disabled = false;
			if (i!=4) document.getElementById(cmp[i]).required=true;document.getElementById(cmp[i]).classList.add('valido');
			
		}
	}
}

function validTime(a){
	RangeDateTime(a,'06:00','19:00');
}




</script>
</head>
<body Onload="actualizar();">
<?php
require_once "../libs/gestion.php";
if (!isset($_SESSION["us_riesgo"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='agendamiento';
$ya = new DateTime();
$estados=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=40 and estado='A' order by 1",'');
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`='DIG' AND estado='A' ORDER BY 1",$_SESSION["us_riesgo"]); 
?>

<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>
	
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
		<div>Fecha Cita Desde</div>
		<input type="date" class="captura" size=10 id="fdes" name="fdes" OnChange="actualizar();">
	</div>
	<div class="campo">
		<div>Fecha Cita Hasta</div>
		<input type="date" class="captura" size=10 id="fhas" name="fhas" OnChange="actualizar();">
	</div>
</div>
<div class='col-8 panel' id='<?php echo$mod; ?>'>
      <div class='titulo' >AGENDAMIENTO DE CITAS
		<nav class='menu left' >
			<li class='icono listado' title='Ver Listado' onclick="desplegar(mod+'-lis');" ></li>
			<?php if ($info['responseResult'][0]['perfil']=='ADM' || $info['responseResult'][0]['perfil']=='TEC' ){
			 echo "<li class='icono exportar'      title='Exportar CSV'    Onclick=\"csv(mod);\"></li>";	
			}?>
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
			<li class='icono crear'       title='Crear Cita'     Onclick="mostrar(mod,'pro');"></li>	
			<li class='icono comentarios'       title='Crear Observaciones'     Onclick="mostrar('observaciones','pro');"></li>
			<?php $info=datos_mysql("SELECT perfil FROM usuarios WHERE id_usuario='".$_SESSION["us_riesgo"]."'");
	if ($info['responseResult'][0]['perfil']=='ADM' || $info['responseResult'][0]['perfil']=='TEC' ){	
			echo "<li class='icono exportar'      title='Exportar CSV Observaciones'    Onclick=\"ajax(ruta_app, 'a=lis&tb=observaciones', false);csv('observaciones');\"></li>";
		}?>
		</nav>
		<nav class='menu right' >
			<li class='icono ayuda'      title='Necesitas Ayuda'            Onclick=" window.open('https://sites.google.com/', '_blank');"></li>
            <li class='icono cancelar'      title='Salir'            Onclick="document.getElementById('iframe').display=none;"></li>
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
