<?php
ini_set('display_errors','1');
include $_SERVER['DOCUMENT_ROOT'].'/libs/nav.php';
?>
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reportes || SIGINF</title>
<link href="../libs/css/stylePop.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch&family=Chicle&family=Merienda&family=Rancho&family=Boogaloo&display=swap" rel="stylesheet">
<script src="../libs/js/a.js"></script>
<script src="../libs/js/x.js"></script>
<script src="../libs/js/d.js"></script>
<script src="../libs/js/popup.js"></script>
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
var mod='reports';	
var ruta_app='lib.php';


function actualizar(){
	graficar();
}

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

/* function graficar() {
	var tit = document.getElementById('indicador-indicador').options[document.getElementById('indicador-indicador').selectedIndex].text;
	var tv = document.getElementById('indicador-agrupar').value;
	
	//var th = document.getElementById('indicador-columna').value;
	//var tb = document.getElementById('indicador-objeto').value;
	//var tg = document.getElementById('indicador-tipo_grafico').value; 
	const tb = document.getElementById('indicador-indicador').value;
	const th=900;
	const tg='BAR'; 
	var options = {title: tit, vAxis: {title: tv}, hAxis: {title: th}, legend: {position: 'none'}, pieHole: 0.4, };
	switch (tg) {
		case 'AREA':
			var graf = new google.visualization.AreaChart(document.getElementById('chart_div'));
			break;
		case 'PIE':
			var graf = new google.visualization.PieChart(document.getElementById('chart_div'));
			break;
		case 'BAR':
			var graf = new google.visualization.BarChart(document.getElementById('chart_div'));
			break;
		case 'COLUMN':
			var graf = new google.visualization.ColumnChart(document.getElementById('chart_div'));
			break;
		case 'LINE':
			var graf = new google.visualization.LineChart(document.getElementById('chart_div'));
			break;
		case 'STEP':
			var graf = new google.visualization.SteppedAreaChart(document.getElementById('chart_div'));
			break;
		case 'DONUT':
			var graf = new google.visualization.PieChart(document.getElementById('chart_div'));
			options = {title: tit, vAxis: {title: tv}, hAxis: {title: th}, legend: {position: 'none'}, pieHole: 0.4, };
			break;
	}
  // var datos = obtenerDatosDesdeLibPHP(tb);
  var datos=[
          ['Mushrooms', 1],
          ['Onions', 1],
          ['Olives', 2],
          ['Zucchini', 2],
          ['Pepperoni', 1]
        ];
	// var rows = JSON.parse(pFetch(ruta_app, 'a=opc&tb=' + tb.toLowerCase(), false));
	var data = new google.visualization.DataTable();
	data.addColumn('string', tv);
	data.addColumn('number', th);
	data.addRows(datos);
	graf.draw(data, options);
	sobreponer('grafica', 'gra');
}
 */
function graficar() {
    try {
        var tit = document.getElementById('indicador-indicador').options[document.getElementById('indicador-indicador').selectedIndex].text;
        var tv = document.getElementById('indicador-agrupar').value;
        const tb = document.getElementById('indicador-indicador').value;
        const th = 900;
        const tg = 'BAR'; // Asumiendo que esto es el tipo de gráfico seleccionado

        // Realizar la solicitud para obtener los datos
        pajax('lib.php', 'POST', {a: 'opc', tb: tb},function(responseData) {
            // Convertir la respuesta a un objeto JSON
            var data = JSON.parse(responseText);

            // Crear el objeto de opciones del gráfico
            var options = {title: tit, vAxis: {title: tv}, hAxis: {title: th}, legend: {position: 'none'}, pieHole: 0.4};

            // Crear el objeto de gráfico según el tipo seleccionado
            var graf;
            switch (tg) {
                case 'AREA':
                    graf = new google.visualization.AreaChart(document.getElementById('chart_div'));
                    break;
                case 'PIE':
                    graf = new google.visualization.PieChart(document.getElementById('chart_div'));
                    break;
                case 'BAR':
			            var graf = new google.visualization.BarChart(document.getElementById('chart_div'));
			          break;
                // Otros casos para diferentes tipos de gráficos
            }

            // Crear el objeto de datos del gráfico y agregar los datos obtenidos
            var chartData = new google.visualization.DataTable();
            chartData.addColumn('string', tv);
            chartData.addColumn('number', th);
            chartData.addRows(data);

            // Dibujar el gráfico
            graf.draw(chartData, options);
        });
    } catch (error) {
        console.error("Error:", error);
        // Manejar el error como lo desees
    }
}

function pajax(url, method, data, successCallback, errorCallback) {
    var xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var responseData = JSON.parse(xhr.responseText);
                successCallback(responseData);
            } else {
                errorCallback(xhr.statusText);
            }
        }
    };
    xhr.send(JSON.stringify(data));
}

// Ejemplo de uso:
var url = 'lib.php';
var method = 'POST';
var requestData = {a: 'opc', tb:tb};
ajax(url, method, requestData, function(responseData) {
    // Éxito: hacer algo con los datos
    console.log('Datos recibidos:', responseData);
}, function(errorMsg) {
    // Error: manejar el error
    console.error('Error en la solicitud:', errorMsg);
});

</script>
</head>
<body Onload="actualizar();">
<?php

require_once "../libs/gestion.php";
if (!isset($_SESSION["us_sds"])){ die("<script>window.top.location.href = '/';</script>");}

$mod='reports';
$hoy = date("Y-m-d");
$ayer = date("Y-m-d",strtotime($hoy."- 2 days")); 
$reportes=opc_sql("select idcatadeta,descripcion from catadeta where idcatalogo=229 and estado='A' order by 1",'');
$sizes=opc_sql("select valor,descripcion from catadeta where idcatalogo=228 and estado='A' order by 1",'');
?>


<form method='post' id='fapp'>
<div class="col-2 menu-filtro" id='<?php echo $mod; ?>-fil'>
	

<div class="campo"><div>Reportes</div>
	<select class="captura" id="indicador-indicador" name="indicador-indicador" onChange="graficar();">'.<?php echo $reportes; ?></select>
</div>
<div class="campo"><div>Tamaño</div>
	<select class="captura" id="indicador-agrupar" name="indicador-indicador" onChange="graficar();">'.<?php echo $sizes; ?></select>
</div>
<div class="campo"><div>Documento Colaborador</div><input class="captura" type="number" size=10 id="fdoc" name="fdoc" onChange="graficar();"></div>

<!-- <div class="campo"><div>Estado</div>
	<select class="captura" id="festado" name="festado" onChange="actualizar();">'.<?php /* echo $estados; */?></select>
</div> -->
	
</div>
<div class='col-8 panel' id='<?php echo $mod; ?>'>
      <div class='titulo' > REPORTES
		<nav class='menu left' >
    <li class='icono actualizar'    title='Actualizar'      Onclick="graficar();">
    <li class='icono filtros'    title='Filtros'      Onclick="showFil(mod);">
    </nav>
		<nav class='menu right' >
			<li class='icono ayuda'      title='Necesitas Ayuda'            Onclick=" window.open('https://drive.google.com/drive/folders/1JGd31V_12mh8-l2HkXKcKVlfhxYEkXpA', '_blank');"></li>
            <li class='icono cancelar'      title='Salir'            Onclick="location.href='../main/'"></li>
        </nav>               
      </div>
  </form>
		<span class='mensaje' id='<?php echo $mod; ?>-msj' ></span>
     <div class='contenido' id='chart_div' ></div>
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
