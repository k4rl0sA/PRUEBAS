<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Información Geografica || EBEH</title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<script src="../libs/js/a.js?v=9.0"></script>
<script src="../libs/js/x.js?v1.0"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script>
var mod='homes';
var ruta_app='lib.php';

const editUsers = [
    /* { selector: '#regimen', func: enabAfil, params: ['regimen', 'eaf'] },
    { selector: '#etnia', func: enabEtni, params: ['etnia', 'ETn', 'idi'] },
    { selector: '#reside_localidad', func: enabLoca, params: ['reside_localidad', 'lochi'] },
    { selector: '#ocupacion', func: EditOcup, params: ['ocupacion', 'true'] },
    { selector: '#cuidador', func: hideCuida, params: ['cuidador', 'cUi'] } */
];



document.onkeyup=function(ev) {
	ev=ev||window.event;
/* 	if (ev.ctrlKey && ev.keyCode==46) ev.target.value='';
	if (ev.ctrlKey && ev.keyCode==45) ev.target.value=ev.target.placeholder; */
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
  	var rutaMap = {
		'prinfancia':'prinfancia.php',
		'adolesce':'adolescencia.php',
		'infancia':'infancia.php',
		'admision':'admision.php',
		'pregnant':'gestantes.php',
		'prechronic':'cronicos.php',
		'statFam':'stateFami.php',
		'caract':'../crea-caract/lib.php',
		'planDcui':'plancui.php',
		'compConc':'plncon.php',
		'signos':'signos.php',
		'ambient':'amb.php',
		'alertas':'alertas.php',
    'vspeve':'vspeve.php',
    'acompsic':'../vsp/acompsic.php',
    'apopsicduel':'../vsp/apopsicduel.php',
    'bpnpret':'../vsp/bpnpret.php',
    'bpnterm':'../vsp/bpnterm.php',
    'cancinfa':'../vsp/cancinfa.php',
    'cronicos':'../vsp/cronicos.php',
    'eraira':'../vsp/eraira.php',
    'gestantes':'../vsp/gestantes.php',
    'hbgest':'../vsp/hbgest.php',
    'mnehosp':'../vsp/mnehosp.php',
    'mme':'../vsp/mme.php',
    'otroprio':'../vsp/otroprio.php',
    'saludoral':'../vsp/saludoral.php',
    'sificong':'../vsp/sificong.php',
    'sifigest':'../vsp/sifigest.php',
    'vihgest':'../vsp/vihgest.php',
    'violreite':'../vsp/violreite.php',
    'dntsevymod':'../vsp/dntsevymod.php',
    'condsuic':'../vsp/condsuic.php',
    'violgest':'../vsp/violgest.php',
    'tamApgar':'../apgar/lib.php'
 	};
		var ruta_app = rutaMap[tb] || 'lib.php';
		myFetch(ruta_app,"a=gra&tb="+tb,mod);
	if (tb == 'person') {
  		setTimeout(function() {
  		mostrar('person1', 'fix', event, '', 'lib.php', 0, 'person1', document.querySelector('input[type="hidden"]').value.split('_')[0]);
  		}, 1000);
		resetFrm();
	}
}   

 let currentOpenMenu = null;

document.body.addEventListener('click', function(event) {
  // Verifica si el click fue en un botón de menú
  if (event.target.classList.contains('icono') && event.target.classList.contains('menubtn')) {
    const id = event.target.id.split("_");
    crearMenu(id[1] + '_' + id[2]);
  }
});

function crearMenu(id) {
  const menuToggle = document.getElementById('menuToggle_' + id);
  const menuContainer = document.getElementById('menuContainer_' + id);

  // Si el menú ya está cargado, solo muestra/oculta
  if (menuContainer.innerHTML.trim() !== "") {
    toggleMenu(menuContainer, menuToggle);
    return;
  }

  // Hacer la solicitud al backend para obtener los datos de los botones
  fetch('lib.php', 
    { method: 'POST',  
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'a=opc&tb=menu'
    })
    .then(response => response.json())
    .then(buttonsData => {
      cargarRecursosCSSyFontAwesome(); // Función que carga los estilos necesarios

      // Crear el HTML del menú dinámicamente con los datos recibidos
      const html = generateMenuHTML(buttonsData);
      menuContainer.innerHTML = html;

      setupMenuBehavior(menuContainer, menuToggle);  // Configurar el comportamiento del menú
      toggleMenu(menuContainer, menuToggle);  // Mostrar el menú al cargarlo por primera vez
    })
    .catch(error => console.error('Error al cargar el menú:', error));
}

// Función para generar el HTML del menú dinámicamente
function generateMenuHTML(buttonsData) {
    // Crear el div principal
    let html = `<div class="panel-acc">
                  <div class="ind-move"></div>
                  <span class="closePanelAcc">&times;</span>
                  <div class="toolbar">`;

    // Añadir los botones usando los datos del backend
    buttonsData.forEach(btn => {
        html += `<button class="action">
                   <i class="icon ${btn.iconClass}"></i>
                   <span class="actionTitle">${btn.title}</span>
                   <span class="shortcut">${btn.shortcut}</span>
                 </button>`;
    });

    // Cerrar el div 'toolbar' y 'panel-acc'
    html += `</div></div>`;
    return html;
}

// Función para configurar el comportamiento del menú
function setupMenuBehavior(menuContainer, menuToggle) {
  const contextMenu = menuContainer.querySelector('.panel-acc');
  const isMobile = window.innerWidth <= 768;

  // Prevenir que se añadan múltiples listeners al mismo toggle
  menuToggle.removeEventListener('click', menuToggleClickHandler);
  menuToggle.addEventListener('click', menuToggleClickHandler);

  function menuToggleClickHandler(e) {
    e.stopPropagation();
    toggleMenu(menuContainer, menuToggle);
  }

  // Botón de cierre del menú
  const closeButton = contextMenu.querySelector('.closePanelAcc');
  if (closeButton) {
    closeButton.addEventListener('click', () => {
      closeMenu(menuContainer);
    });
  }

  // Acciones dentro del menú
  const actions = contextMenu.querySelectorAll('.action');
  actions.forEach(action => {
    action.addEventListener('click', (event) => {
    const actionName = action.querySelector('.actionTitle').textContent;
    console.log(`Acción seleccionada: ${actionName}`);
    closeMenu(menuContainer);
    event.preventDefault();
  });
});

  // Cerrar el menú cuando se haga clic fuera de él
  document.addEventListener('click', (e) => {
    if (!contextMenu.contains(e.target) && e.target !== menuToggle) {
      closeMenu(menuContainer);
    }
  });

  // Deslizamiento táctil para cerrar el menú
  let touchStartY;
  contextMenu.addEventListener('touchstart', (e) => {
    touchStartY = e.touches[0].clientY;
  });

  contextMenu.addEventListener('touchmove', (e) => {
    const touchEndY = e.touches[0].clientY;
    const diff = touchEndY - touchStartY;
    if (diff > 50) {
      closeMenu(menuContainer);
    }
  });
}

function toggleMenu(menuContainer, menuToggle) {
  const contextMenu = menuContainer.querySelector('.panel-acc');
  const isMobile = window.innerWidth <= 768;

  // Si hay un menú actualmente abierto, lo cerramos antes de abrir el nuevo
  if (currentOpenMenu && currentOpenMenu !== menuContainer) {
    closeMenu(currentOpenMenu);
  }

  // Maneja la visibilidad del menú correctamente
  if (isMobile) {
    contextMenu.classList.toggle('show');
  } else {
    const rect = menuToggle.getBoundingClientRect();
    contextMenu.style.top = rect.bottom + 'px';  // Ajusta la posición top
    contextMenu.style.display = contextMenu.style.display === 'none' || contextMenu.style.display === '' ? 'block' : 'none';
  }

  // Actualiza la variable global para guardar el menú actualmente abierto
  if (contextMenu.style.display === 'block' || contextMenu.classList.contains('show')) {
    currentOpenMenu = menuContainer;
  } else {
    currentOpenMenu = null;  // Si el menú está cerrado, reseteamos la variable
  }
}

function closeMenu(menuContainer) {
  const contextMenu = menuContainer.querySelector('.panel-acc');
  const isMobile = window.innerWidth <= 768;

  if (isMobile) {
    contextMenu.classList.remove('show');
  } else {
    contextMenu.style.display = 'none';
  }

  // Resetea la variable para indicar que no hay menú abierto
  currentOpenMenu = null;
}


</script>
</head>
<body Onload="actualizar();">
<?php
	require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='homes';
/* $hoy = date("Y-m-d");
$ayer = date("Y-m-d",strtotime($hoy."- 2 days")); */
/* $rta=datos_mysql("select FN_USUARIO('".$_SESSION['us_sds']."') as usu;");
$usu=divide($rta["responseResult"][0]['usu']); */
// var_dump($usu);
/*$grupos=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=11 and estado='A' order by 1",'');*/

$digitadores=opc_sql("SELECT `id_usuario`,nombre FROM `usuarios` WHERE`perfil` IN('ADM','AUXHOG','PROFAM','SUPHOG','SUPEAC','ADMHOG','MEDATE','ENFATE') and subred=(SELECT subred FROM usuarios where id_usuario='{$_SESSION['us_sds']}')  ORDER BY 1",$_SESSION['us_sds']);
$perfi=datos_mysql("SELECT perfil as perfil FROM usuarios WHERE id_usuario='{$_SESSION['us_sds']}'");
$perfil = (!$perfi['responseResult']) ? '' : $perfi['responseResult'][0]['perfil'] ;
// $territorios=opc_sql("SELECT descripcion,descripcion FROM catadeta WHERE idcatalogo=202 AND valor=(select subred from usuarios where id_usuario='{$_SESSION['us_sds']}') ORDER BY 1",'');
// $territorio = ($perfil == 'ADMEAC' || $perfil == 'ADM' || $perfil == 'SUPEAC' ) ? '' : 'disabled';
?>
<form method='post' id='fapp' >
<div class="col-2 menu-filtro" id='<?php echo$mod; ?>-fil'>

	<!-- <div class="campo"><div>Documento Usuario</div><input class="captura"  size=20 id="fusu" name="fusu" OnChange="searPers(this);"></div> -->
	<div class="campo"><div>Codigo del Predio</div><input class="captura" type="number" size=20 id="fpred" name="fpred" OnChange="actualizar();"></div>
  <?php
    $filtro = in_array($perfil, ['ADM', 'SUPHOG', 'SUPEAC']);
    $enab = $filtro ? '' : 'disabled';
    $rta = '<div class="campo"><div>Colaborador</div>
            <select class="captura" id="fdigita" name="fdigita" onChange="actualizar();" ' . $enab . '>' . $digitadores . '</select>
            </div>';
    echo $rta;
	?>
 </div>
 <div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' > CREACIÓN DE FAMILIAS
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