<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>	
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administración || SIGREV</title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/a.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script>
var mod='administracion';	
var ruta_app='lib.php';


function actualizar(){
	mostrar(mod);
}

function grabar(tb='',ev){
  if (tb=='' && ev.target.classList.contains(proc)) tb=proc;
  var f=document.getElementsByClassName('valido '+tb);
   for (i=0;i<f.length;i++) {
     if (!valido(f[i])) {f[i].focus(); return};
  }
	myFetch(ruta_app,"a=gra&tb="+tb,mod);
	
}   

</script>
<style> 
        

        .container {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            width: 400px;
        }

        .input-container {
            margin-bottom: 20px;
        }

        .input-label {
            font-weight: bold;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .submit-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php

require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='administracion';
$ya = new DateTime();
$rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
$usu=divide($rta["responseResult"][0]['usu']);
$estados=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=184 and estado='A' order by 1",'1');
$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil`IN('ENFATE','MEDATE','PSIEAC') and subred=$usu[2] ORDER BY 2",$_SESSION['us_sds']);
?>
<!-- <form method='post' id='fapp'> -->

<div class='col-0 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' > ZONA DE ADMINISTRACIÓN
		<nav class='menu left' >
    <li class='icono gestusu'      title='Exportar Información General' onclick="mostrar('gestionusu','sta',event,'','lib.php',0);"></li>
    <li class='icono exportar'      title='Exportar Información General'    Onclick="mostrar('planos','sta',event,'','lib.php',0);"></li>
    </nav>
		<nav class='menu right' >
			<li class='icono ayuda'      title='Necesitas Ayuda'            Onclick=" window.open('https://drive.google.com/drive/folders/1JGd31V_12mh8-l2HkXKcKVlfhxYEkXpA', '_blank');"></li>
            <li class='icono cancelar'      title='Salir'            Onclick="location.href='../main/'"></li>
        </nav>               
      </div>
		<span class='mensaje' id='<?php echo $mod; ?>-msj' ></span>
     <div class='contenido' id='<?php echo $mod; ?>-lis' ></div>
	 <div class='contenido' id='cmprstss' ></div>
</div>			
		
<!-- <div class='load' id='loader' z-index='0' ></div> -->
<!-- </form> -->
<!-- <div class="overlay" id="overlay" onClick="closeModal();">
	<div class="popup" id="popup" z-index="0" onClick="closeModal();">
		<div class="btn-close-popup" id="closePopup" onClick="closeModal();">&times;</div>
		<h3><div class='image' id='<?php echo$mod; ?>-image'></div></h3>
		<h4><div class='message' id='<?php echo$mod; ?>-modal'></div></h4>
	</div>	 -->		
</div>


<script>
/* function openAction(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
} */

// Get the element with id="defaultOpen" and click on it
// document.getElementById("defaultOpen").click();
</script>
</body>
