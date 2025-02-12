<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>REPORTE INDIVIDUAL || SIGINF</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="../libs/js/a.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>

<script>
var mod='rptindv';	
var ruta_app='lib.php';

function actualizar(){
	act_lista(mod);
}

let Exec = false;
// Definir un Map que mapea tipos de eventos a otro Map que mapea selectores a funciones específicas
const eventHandlers = new Map();
// Añadir manejadores para diferentes elementos y eventos
function addEventHandler(selector, eventType, handler, options = {}) {
    if (!eventHandlers.has(eventType)) {
        eventHandlers.set(eventType, new Map());
    }
    const eventMap = eventHandlers.get(eventType);
    if (!eventMap.has(selector)) {
        eventMap.set(selector, []);
    }
    eventMap.get(selector).push({ handler, options });
}
//PESTAÑAS
addEventHandler('li.tabs', 'click', function(event) {
  Exec=true;
  setupTabClickEvents();
}, { stopPropagation: true });
// Agregar un único listener para una lista ampliada de eventos de interés
const eventTypes = ['click', 'mouseover', 'input', 'focus', 'blur', 'change', 'keydown', 'keyup', 'submit'];
eventTypes.forEach(eventType => {
    document.addEventListener(eventType, function(event) {
        handleEvent(event, eventType);
    });
});
// Función para manejar el evento
function handleEvent(event, eventType) {
    const target = event.target;
    if (eventHandlers.has(eventType)) {
        const eventMap = eventHandlers.get(eventType);
        for (let [selector, handlers] of eventMap.entries()) {
            if (target.matches(selector)) {
                handlers.forEach(({ handler, options }) => {
                    if (options.preventDefault) event.preventDefault();
                    if (options.stopPropagation) event.stopPropagation();
                    handler.call(target, event);
                });
            }
        }
    }
}
  function setupTabClickEvents() {
    if(Exec){
      const tabLinks = document.querySelectorAll('.tab-nav li');
    const tabContents = document.querySelectorAll('.tab-content');
    tabLinks.forEach(link => {
      link.addEventListener('click', () => {
        const tabIndex = link.getAttribute('data-tab');
        // Remove active class from all tabs and contents
        tabLinks.forEach(tab => tab.classList.remove('activ'));
        tabContents.forEach(content => content.classList.remove('activ'));
        // Add active class to the clicked tab and corresponding content
        link.classList.add('activ');
        document.querySelector(`.tab-content[data-content="${tabIndex}"]`).classList.add('activ');
      });
    });
      Exec=false;
    }
  }

  async function apiGet(a) {
    const token = await authenticateUser();  
    if (token) {
        const tipoID = 'CC';  
        const id = '1233254';  
        const persona = await consultarPersona(tipoID, id, token);  // Consultamos la persona
    }
}


async function authenticateUser() {
    const url = 'https://us-central1-interoperabilidad-sds.cloudfunctions.net/Consulta_Personas/entry_init';
    const body = {
        "user": "ebextramurales@saludcapital.gov.co",
        "password": "FDhfcuTjUnlk324*·vy67"
    };

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',  // Aceptar respuestas JSON
                'Content-Type': 'application/json'  // Tipo de contenido que estás enviando
            },
            body: JSON.stringify(body)  // Convertimos el body a JSON
        });

        // Verificamos si la respuesta es correcta
        if (!response.ok) {
            throw new Error(`Error de autenticación: ${response.status}`);
        }

        const data = await response.json();  // Parseamos la respuesta como JSON
        console.log('Token de autenticación:', data.token);
        return data.token;

    } catch (error) {
        console.error('Error en la autenticación:', error);
    }
}


</script>
<style>
:root {
  --high-risk-color: red;
  --medium-risk-color: #faab00;
  --normal-risk-color: #4caf50;
  --low-risk-color: blue;
  --bord-radiu-xs: 50rem;
  --bord-radiu-s: 50rem;
  --bord-radiu-m: 60rem;
  --bord-radiu-l: 80rem;
}

.section {
    border: 1px solid #d1d1d1;
    margin-top: 8px;
    padding: 10px;
    display: flex;
    border-radius: 8px;
    margin-bottom: 30px;
}    

.section::before {
    content: "";
    display: block;
    width: 12px;
    position: relative;
    top: 3px;
    bottom: 3px;
    left: -10px;
}

.title-risk {
    background-color: #e6e6e6;
    color: #333;
    padding: 5px;
    border-radius: 8px;
    display: inline-block;
    font-weight: bold;
    border: 1px solid #ababab;
}

.user-info {
    display: flex;
    flex-grow: 1;
	background-color: #fbfbfb;
	font-size: 14px;
}

.user-name {
    color: blue;
    font-size: 20px;
    padding-bottom: 20px;
    font-weight: bold;
}

.user-details {
    margin-left: 5px;
    padding-top: 20px;
    padding-bottom: 20px;
    padding-right: 150px;
    color: black;
}

.user-details div {
    margin-bottom: 5px;
}

.risk-info {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    padding-top: 20px;
    color: black;
}

.extra-info {
    margin-bottom: 10px;
}

.risk-level {
    padding: 5px;
    font-weight: bold;
    padding-top: 95px;
}

.high-risk {
  color: var(--high-risk-color);
}

.medium-risk {
  color: var(--medium-risk-color);
}

.normal-risk {
  color: var(--normal-risk-color);
}

.low-risk {
  color: var(--low-risk-color);
}

.point.high-risk,span.high-risk,.section.high-risk::before {
    background-color: var(--high-risk-color);
    color:white;
}
.point.medium-risk,span.medium-risk,.section.medium-risk::before {
    background-color: var(--medium-risk-color);
    color:white;
}
.point.normal-risk,span.normal-risk,.section.normal-risk::before{
    background-color: var(--normal-risk-color);
    color:white;
}
.point.low-risk,span.low-risk,.section.low-risk::before {
    background-color: var(--low-risk-color);
    color:white;
}

.point {
    display: inline-block;
      width: 14px;
      height: 14px;
      border-radius: 50%;
      margin-right: 10px;
}

.btn-group {
  display: flex;
}

.btn-contain {
  text-align: center;
  margin-bottom: 20px;
}

.custom-btn {
  font-size: .75em;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
    padding: 0px 20px;
}

.btn-value {
  margin-top: 5px;
  font-size: 14px;
}


/* Tab Panel Styling */

body {
      font-family: 'Roboto', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .container {
      /* max-width: 800px; */
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #333;
    }

.tab-panel {
      display: flex;
      flex-direction: column;
    }

    .tab-nav {
      display: flex;
      list-style: none;
      padding: 0;
      margin: 0;
      border-bottom: 1px solid #ddd;
    }

    .tab-nav li{
      padding: 15px 20px;
      cursor: pointer;
      background-color: #f4f4f4;
      color: #333;
      transition: background-color 0.3s ease, color 0.3s ease;
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: medium;
    }

    .tab-nav li.activ {
      background-color: #fff;
      color: #007bff;
      border-bottom: 3px solid #007bff;
      font-size: large;
    }

    .tab-content {
      /* padding: 20px; */
      display: none;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .tab-content.activ {
      display: block;
      opacity: 1;
    }

    /**TOOLTIP ****/
    .tooltips {
        position: relative;
        /* display: inline-block; */
        cursor: pointer;
    }

    .tooltips .tooltiptext {
        visibility: hidden;
        max-width: 350px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 10px;
        position: absolute;
        z-index: 1;
        opacity: 0;
        transition: opacity 0.3s, transform 0.3s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); 
        transform: translateY(10px); 
        white-space: normal;
        word-wrap: break-word;
    }

    .tooltips:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }

    .tooltips .tooltiptext {
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
    }

    .tooltips .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-width: 5px;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }

@media (max-width: 600px) {
  /**TAB****/
      .tab-nav {
        flex-direction: column;
      }

      .tab-nav li {
        padding: 10px 15px;
        font-size: 14px;
        text-align: center;
      }

      .tab-content {
        padding: 15px;
      }

      /**TOOLTIP ****/
      .tooltip .tooltiptext {
            bottom: auto;
            top: 125%;
            left: 50%;
            margin-left: -60px;
        }

        .tooltip .tooltiptext::after {
            top: -10px;
            border-color: transparent transparent #333 transparent;
            bottom: auto;
        }
    }

</style>
</head>
<body Onload="actualizar();">
<?php
require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='rptindv';
$ya = new DateTime();
$localidades=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=2 and estado='A' order by 1",'');
$territorios=opc_sql("SELECT idcatadeta,descripcion FROM `catadeta` WHERE idcatalogo=202 and estado='A' and valor=(SELECT subred FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}') ORDER BY CAST(idcatadeta AS UNSIGNED)",'');
?>
<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>
	
<div class="campo">
	<div>Identificación</div>
	<input class="captura" type="number" id="fidentificacion" name="fidentificacion" OnChange="actualizar();">
</div>

<div class="campo"><div>Localidad</div>
	<select class="captura" id="floc" name="floc" onChange="actualizar();selectDepend('floc','fter','lib.php');">'.<?php echo $localidades; ?></select>
</div>

<div class="campo"><div>Territorio</div>
	<select class="captura" id="fter" name="fter" onChange="actualizar();">'.<?php echo $territorios; ?></select>
</div>
	
</div>
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' >REPORTE INDIVIDUAL DE ALERTA
		<nav class='menu left' >
			<li class='icono actualizar'    title='Actualizar'      Onclick="actualizar();">
			<li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
		</nav>
		<nav class='menu right'>
			<li class='icono ayuda'      title='Necesitas Ayuda'            Onclick=" window.open('https://sites.google.com/', '_blank');"></li>
        </nav>               
      </div>
    	<div>
		</div>
		<span class='mensaje' id='<?php echo $mod; ?>-msj' ></span>
    <div class='contenido' id='<?php echo $mod;?>-lis' >
			
		
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
