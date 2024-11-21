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

function grabar(tb='',ev){
	/* if (tb=='prinfancia'){
		const id=document.getElementById('p_infancia_documento').value;
		const tip=document.getElementById('p_infancia_tipo_doc').value;
		const aten= atenReal(id+'_'+tip,'../atencion/prinfancia.php');
	} */
  if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  var f=document.getElementsByClassName('valido '+tb);
   for (i=0;i<f.length;i++) {
     if (!valido(f[i])) {f[i].focus(); return};
  }
  	var rutaMap = {
		'prinfancia':'prinfancia.php',
		'adolesce':'adolescencia.php',
		'infancia':'infancia.php',
		'admision':'admision.php',
		'pregnant':'gestantes.php',
		'prechronic':'cronicos.php',
		'statFam':'stateFami.php'
 	};
		var ruta_app = rutaMap[tb] || 'lib.php';
	// if(aten!==0){
		myFetch(ruta_app,"a=gra&tb="+tb,mod);
/* 	}else{
		warnin('Para realizar esta operacion, debe tener una atención previa, valida e intenta nuevamente');
	} */

	if (tb == 'person') {
  		setTimeout(function() {
    		mostrar('person1', 'fix', event, '', 'lib.php', 0, 'person1', document.querySelector('input[type="hidden"]').value.split('_')[0]);
  		}, 1000);
	}
}   


async function atenReal(a,b) {
	try {
		const data = await getJSON('get','atenc',a,b);
		if (data.length===0) exit;
	} catch (error) {
	  console.error(error);
	  errors('Para realizar esta operacion, debe tener una atención previa, valida e intenta nuevamente');
	}
  }


function valResol(a,el){
	const act = document.getElementById(a);
	const ele = document.getElementById(el);
	if (act.value=='1'){
		ele.value=25;
		ele.disabled = true;
    	ele.required = true;
    	ele.classList.toggle('valido', true);
    	ele.classList.toggle('captura', true);
    	ele.classList.toggle('bloqueo', true);
    	ele.setAttribute('readonly', true);
		changeSelect('letra1','rango1');
	}else{
		if(ele.value==25){// if(ele.value==25 || ele.value==18){
			ele.value='';
			ele.disabled = false;
			ele.required = true;
			ele.classList.toggle('valido', true);
    		ele.classList.toggle('bloqueo', false);
			ele.removeAttribute('readonly');
			document.getElementById('rango1').value='';
			document.getElementById('diagnostico1').value='';
		}
		/* ele.value='';
		ele.disabled = false;
    	ele.required = false;
    	ele.classList.toggle('valido', false);
    	ele.classList.toggle('bloqueo', false);
    	ele.removeAttribute('readonly');
		document.getElementById('rango1').value='';
		document.getElementById('diagnostico1').value=''; */
	}
}

function valPyd(act,el){
	const ele = document.getElementById(el);
	if (act.value=='25' && ele.value==2){
		act.value='';
	}
}

function enabEven(a,b,c){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	const elm = document.querySelectorAll('select.'+c+',input.'+c);
		if(a.value=='NO'){
			for (i=0; i<ele.length;i++) {
				enaFie(ele[i],true);
			}
			for (i=0; i<elm.length;i++) {
				enaFie(elm[i],true);
			}
  		}else{
			for (i=0; i<ele.length;i++) {
				enaFie(ele[i],false);
			}
		}
}



function enabTest(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	for (i=0; i<ele.length;i++) {
		if(a.value=='NO'){
			enaFie(ele[i],true);
  		}else{
			enaFie(ele[i],false);
		}
	}
}

function cualEven(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	for (i=0; i<ele.length;i++) {
		if(a.value=='5'){
			enaFie(ele[i],false);
  		}else{
			enaFie(ele[i],true);
		}
	}
}

function AlarChild(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	for (i=0; i<ele.length;i++) {
		if(a.value=='NO'){
			enaFie(ele[i],true);
  		}else{
			enaFie(ele[i],false);
		}
	}
}

function alerPreg(a,b,c,d,e){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	const nle = document.querySelectorAll('select.'+c+',input.'+c);
	const lem = document.querySelectorAll('select.'+d+',input.'+d);
	const mef = document.querySelectorAll('select.'+e+',input.'+e);
		if(a.value=='SI'){
			for (i=0; i<ele.length;i++) {
				enaFie(ele[i],true);
			}
  		}else{
			for (i=0; i<nle.length;i++) {
				enaFie(nle[i],true);
			}
			for (i=0; i<mef.length;i++) {
				enaFie(mef[i],false);
			}
			for (i=0; i<lem.length;i++) {
				enaFie(lem[i],true);
			}
		}
	}


function enabFert(a,b,c){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
	const nle = document.querySelectorAll('select.'+c+',input.'+c);
		if(a.value=='SI'){
			for (i=0; i<ele.length;i++) {
				enaFie(ele[i],true);
			}
			for (i=0; i<nle.length;i++) {
				enaFie(nle[i],false);
			}
  		}else{
			for (i=0; i<nle.length;i++) {
				enaFie(nle[i],true);
			}
			for (i=0; i<ele.length;i++) {
				enaFie(ele[i],false);
			}
		}
}

function enabDiab(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
		if(a.value=='SI'){
			for (i=0; i<ele.length;i++) {
				enaFie(ele[i],false);
			}
  		}else{
			for (i=0; i<ele.length;i++) {
				enaFie(ele[i],true);
			}
		}
}

function enabHemo(a,b){
	const ele = document.querySelectorAll('select.'+b+',input.'+b);
		if(a.value=='SI'){
			for (i=0; i<ele.length;i++) {
				enaFie(ele[i],false);
			}
  		}else{
			for (i=0; i<ele.length;i++) {
				enaFie(ele[i],true);
			}
		}
}

function rtaMoris(a,b){
	const mor = document.querySelectorAll('select.'+a+',input.'+a);
	var adh =document.getElementById(b);
	adh.value='';
	for (i=0; i<mor.length;i++) {
		if (mor[i].value=='SI'){
			adh.value='NO';
		}
	}
	if(adh.value==''){
		adh.value='SI';
	}
}

function rutePsico(a){
    doc=a.split('_');
	var res = confirm("Desea confirmar la asignación del usuario "+doc[0]+" para Psicologia-Ruteo ?");
		if(res==true){
			if (loader != undefined) loader.style.display = 'block';
		if (window.XMLHttpRequest)
			xmlhttp = new XMLHttpRequest();
		else
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.onreadystatechange = function () {
			if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
				data =xmlhttp.responseText;
				if (loader != undefined) loader.style.display = 'none';
					console.log(data)
			}}
			xmlhttp.open("POST", ruta_app,false);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('a=asigna&tb=rutePsico&id='+a);
			if (data.includes('Correctamente')){
				inform('Se ha asignado Correctamente el usuario.');
			}else{
			    warnin('Ya se ha asignado el usuario, por favor valide.');
			}
	}
}

function searPers(a){
	const doc=document.getElementById('fusu');
	const pre=document.getElementById('fpred');
	if((doc.value!='' || doc!=undefined) && (pre.value!='' || pre!=undefined) ){
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
			}}
			xmlhttp.open("POST", ruta_app,false);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send('a=opc&tb=usuario&id='+a.value);
			
			const ter=document.getElementById('fterri');
			const des=document.getElementById('fdes');
			const has=document.getElementById('fhas');
			var rta =data;
					if (Object.keys(rta).length === 6) {
					ter.value='';
					des.value='';
					has.value='';
					pre.value=data['idgeo'];
					inform('<p class="blanco">Subred:'+data['subred']+
					'<br>Estrategia: '+data['estrategia']+
					'<br>Asignado: '+data['asignado']+
					'<br>Perfil: '+data['perfil']+
					'<br>Territorio: '+data['equipo']+'</p>');
					actualizar();
				}else{
					const hoy = new Date();
					hoy.setDate(hoy.getDate() - 1);
					des.value = hoy.toISOString().split('T')[0];
					const usu = document.getElementById('fusu');
					ter.value='';
					usu.value = '';
					pre.value = '';
					//actualizar();
					warnin('NO se ha encontrado un registro asociado.');
				}

	}
}




</script>
</head>
<body Onload="actualizar();">
<?php
	require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='homes';
$hoy = date("Y-m-d");
$ayer = date("Y-m-d",strtotime($hoy."- 2 days"));
/* $rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
$usu=divide($rta["responseResult"][0]['usu']); */
// var_dump($usu);
/*$grupos=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=11 and estado='A' order by 1",'');*/
$localidades=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=2 and estado='A' order by 1",'');
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`='MED' and subred=(SELECT subred FROM usuarios where id_usuario='{$_SESSION['us_sds']}')  ORDER BY 1",$_SESSION['us_sds']);
$perfi=datos_mysql("SELECT perfil as perfil FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}'");
$perfil = (!$perfi['responseResult']) ? '' : $perfi['responseResult'][0]['perfil'] ;
$territorios=opc_sql("SELECT descripcion,descripcion FROM catadeta WHERE idcatalogo=202 AND valor=(select subred from usuarios where id_usuario='{$_SESSION['us_sds']}') ORDER BY 1",'');
$territorio = ($perfil == 'ADMEAC' || $perfil == 'ADM' || $perfil == 'SUPEAC' ) ? '' : 'disabled';
?>
<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>

<div class="campo"><div>Territorio</div>
		<select class="captura" id="fterri" name="fterri" onchange="actualizar();"<?php echo $territorio; ?> ><?php echo $territorios; ?>
		</select>
	</div>
	<div class="campo"><div>Documento Usuario</div><input class="captura"  size=20 id="fusu" name="fusu" OnChange="searPers(this);"></div>
	<div class="campo"><div>Codigo del Predio</div><input class="captura" type="number" size=20 id="fpred" name="fpred" OnChange="actualizar();"></div>
	<div class="campo">
		<!-- <div>Fecha Asignado Desde</div> -->
		<input type="hidden" class="captura" size=10 id="fdes" name="fdes" value='<?php echo $hoy; ?>'  disabled="true">
		
	</div>
	<div class="campo">
		<!-- <div>Fecha Asignado Hasta</div> -->
		<input type="hidden" class="captura" size=10 id="fhas" name="fhas" value='<?php echo $hoy; ?>'  disabled="true">
	</div> 

	<?php
		$rta="";
		$rta = ($perfil =='ADM'||'MED') ? '<div class="campo"><div>Colaborador</div>
		<select class="captura" id="fdigita" name="fdigita" onChange="actualizar();" disabled="true">'.$digitadores.'</select></div>':'';
		echo $rta;
	?>
 </div>
 <div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' >ATENCIONES
		<nav class='menu left' >
			<li class='icono listado' title='Ver Listado' onclick="desplegar(mod+'-lis');" ></li>
			<!-- <li class='icono exportar'      title='Exportar Información General'    Onclick="csv(mod);"></li> -->
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
			<li class='icono lupa' title='Consultar Predio' Onclick="mostrar('predios','pro',event,'','../consultar/consulpred.php',7);">
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