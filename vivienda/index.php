<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Caracterizacion Hogar || GIF-SDS</title>
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
				document.getElementById(tb+'-modal').innerHTML='El valor de vacunados o esterilizados esta errado, no puede ser superior al numero de mascotas,por favor valide e intente nuevamente';
				document.getElementById(mod+'-image').innerHTML='<svg class="icon-popup" ><use xlink:href="#bad"/></svg>';
				openModal();
				return
			}
		}
		if(tb=='medidas'){
			var res = confirm("Desea guardar la información, recuerda que no se podrá editar posteriormente?");
			if(res==false){
				return;
			}
			var textoEdad = document.getElementById('edad').value;
			var patron = /Años:\s*(\d+)/;
			var resultado = patron.exec(textoEdad);
			if(resultado>17){			
				if(valSist('tas')===true) return;
				if(valDist('tad')===true) return;
				if(valGluco('glucometria')===true) return;
			}
		}

		var rutaMap = {
			'medidas':'medidas.php',
			'ambient':'amb.php'
		};
		var ruta_app = rutaMap[tb] || 'lib.php';
	myFetch(ruta_app,"a=gra&tb="+tb,mod);
	/* setTimeout(actualizar, 1000);
	setTimeout(mostrar('person1','fix',event,'','lib.php',0,'person1'),500); */
	if (tb == 'person') {
  		setTimeout(function() {
    		mostrar('person1', 'fix', event, '', 'lib.php', 0, 'person1', document.querySelector('input[type="hidden"]').value.split('_')[0]);
  		}, 1000);
	}
}   

function disFecar(a){
	const x=document.getElementById(a);
	if (x.value!=''){
		x.disabled=true;
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
				if (Object.keys(rta).length === 5) {
					ter.value='';
					des.value='';
					has.value='';
					pre.value=data['idgeo'];
					inform('<p class="blanco">Subred:'+data['subred']+'<br>Estrategia: '+data['estrategia']+'<br>Asignado: '+data['asignado']+'<br>Territorio: '+data['equipo']+'</p>');
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
/*$grupos=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=11 and estado='A' order by 1",'');*/
$localidades=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=2 and estado='A' order by 1",'');
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`='AUX' ORDER BY 1",$_SESSION['us_sds']);
$perfi=datos_mysql("SELECT perfil as perfil FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}'");
$perfil = (!$perfi['responseResult']) ? '' : $perfi['responseResult'][0]['perfil'];
$territorios=opc_sql("SELECT descripcion,descripcion FROM catadeta WHERE idcatalogo=202 AND valor=(select subred from usuarios where id_usuario='{$_SESSION['us_sds']}') ORDER BY 1",'');
$territorio = ($perfil == 'APYHOG' || $perfil == 'ADM' || $perfil == 'ADMHOG') ? '' : 'disabled';
?>
<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo $mod; ?>-fil'>
	
	<div class="campo"><div>Territorio</div>
		<select class="captura" id="fterri" name="fterri" onchange="actualizar();"<?php echo $territorio; ?> ><?php echo $territorios; ?>
		</select>
	</div>

	<div class="campo"><div>Documento Usuario</div><input class="captura"  size=20 id="fusu" name="fusu" OnChange="searPers(this);"></div>
		<div class="campo"><div>Codigo del Predio</div><input class="captura" type="number" size=20 id="fpred" name="fpred" OnChange="actualizar();"></div>

	<div class="campo"><div>Colaborador</div>
		<select class="captura" id="fdigita" name="fdigita" OnChange="actualizar();" disabled>
			<?php echo $digitadores; ?>
		</select>
	</div>
	
	<div class="campo">
		<div>Fecha Asignado Desde</div>
		<input type="date" class="captura" size=10 id="fdes" name="fdes" value='<?php echo $ayer; ?>' OnChange="actualizar();" disabled="true">
		
	</div>
	<div class="campo">
		<div>Fecha Asignado Hasta</div>
		<input type="date" class="captura" size=10 id="fhas" name="fhas" value='<?php echo $hoy; ?>' OnChange="actualizar();" disabled="true">
	</div>
	
</div>
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' >    CARACTERIZACIÓN VIVIENDAS
		<nav class='menu left'>
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


	
