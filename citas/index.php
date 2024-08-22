<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Solicitar Cita || Sigrev</title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/a.js"></script>
<script src="../libs/js/x.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script>
var mod='solcita';	
var ruta_app='lib.php';


function actualizar(){
	act_lista(mod);
}

function grabar(tb='',ev){
  if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  var f=document.getElementsByClassName('valido '+tb);
   for (i=0;i<f.length;i++) {
     if (!valido(f[i])) {f[i].focus(); return};
  }
    myFetch(ruta_app,"a=gra&tb="+tb,mod);  
}   

function enableUpd(a,b){
	const update= document.querySelectorAll('input.'+b);
	if(a.value=='SI'){
		enaFie(update[0],false);
  	}else{
		enaFie(update[0],true);
	}
}

</script>
</head>
<body Onload="actualizar();">
<?php

require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='solcita';
$ya = new DateTime();
$estados=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=145 and estado='A' order by 1",'');
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE perfil IN('ADM')",$_SESSION['us_sds']);
$perfi=datos_mysql("SELECT perfil as perfil FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}'");
$perfil = (!$perfi['responseResult']) ? '' : $perfi['responseResult'][0]['perfil'] ;
$import = ($perfil == 'TEC'||$perfil =='ADM') ? '<div class="campo"><div>Colaborador</div><select class="captura" id="fdigita" name="fdigita" onChange="actualizar();">'.$digitadores.'</select></div><div class="campo"><div>Cargar Datos Geográficos</div></div><input class="button filtro" type="file" id="inputFile1" accept=".csv" name="inputFile1" style="width: 350px;"><br><button class="button campo" title="Cargar Archivo" id="btnLoad" type="button">IMPORTAR</button></div></div>':'';
?>


<form method='post' id='fapp'>
	<div class="col-2 menu-filtro" id='<?php echo $mod; ?>-fil'>

	<div class="campo">
		<div>N° Documento</div>
		<input class="captura" size=50 id="fidpersona" name="fidpersona" OnChange="actualizar();">
	</div>
	
	<div class="campo"><div>Estado Solicitud</div>
		<select class="captura" id="festado" name="festado" OnChange="actualizar();">
			<?php echo $estados; ?>
		</select>
	</div>

    <div class="campo"><div>Colaborador</div>
        <select class="captura" id="fdigita" name="fdigita" OnChange="actualizar();">
			<?php echo $digitadores; ?>
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

	<?php echo $import; ?>


	</div>
	<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' > SOLICITAR CITA
		<nav class='menu left' >
    <li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
    <li class='icono crear'       title='Solicitar Cita'     Onclick="mostrar('solcita','pro',event,'','lib.php',7,'Solicitar Cita');"></li> <!--hideMotiv();-->
    </nav>
		<nav class='menu right' >
			<li class='icono ayuda'      title='Necesitas Ayuda'            Onclick=" window.open('https://drive.google.com/drive/folders/1JGd31V_12mh8-l2HkXKcKVlfhxYEkXpA', '_blank');"></li>
            <li class='icono cancelar'      title='Salir'            Onclick="location.href='../main/'"></li>
        </nav>               
      </div>
</form>
  <script>
	let btnEnviar = document.querySelector("#btnEnviar"),btnLoad = document.querySelector("#btnLoad"),inputFile = document.querySelector("#inputFile");
	btnLoad.addEventListener("click", () => {
	uploadCsv(30, "geografico", inputFile1, "../libs/impGeo.php", mod);
  });
</script>
		<span class='mensaje' id='<?php echo $mod; ?>-msj' ></span>
     <div class='contenido' id='<?php echo $mod; ?>-lis' ></div>
	 <div class='contenido' id='cmprstss' ></div>
</div>			
		
<div class='load' id='loader' z-index='0' ></div>

<div class="overlay" id="overlay" onClick="closeModal();">
	<div class="popup" id="popup" z-index="0" onClick="closeModal();">
		<div class="btn-close-popup" id="closePopup" onClick="closeModal();">&times;</div>
		<h3><div class='image' id='<?php echo$mod; ?>-image'></div></h3>
		<h4><div class='message' id='<?php echo$mod; ?>-modal'></div></h4>
	</div>
</div>
</body>