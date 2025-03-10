<?php
ini_set('display_errors', '1');
$mod = 'descargas';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../libs/css/a.css">
    <script src="../libs/js/a.js?v=1.0"></script>
    <script src="../libs/js/popup.js?v=1.0"></script>
    <title>Generar Archivo Consolidado</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h1 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 0.5rem;
        }
        input[type="date"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            margin-bottom: 1rem;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .progress-container {
            margin-top: 1.5rem;
        }
        .progress-bar {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
            height: 10px;
            margin-bottom: 0.5rem;
        }
        .progress-bar-fill {
            height: 100%;
            background-color: #007bff;
            width: 0;
            transition: width 0.3s ease;
        }
        .progress-text {
            font-size: 0.9rem;
            color: #555;
        }
        /* Estilos para el spinner */
    .spinner {
        margin-top: 1rem;
    }
    .spinner-border {
        width: 2rem;
        height: 2rem;
        border: 0.25em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border 0.75s linear infinite;
    }
    @keyframes spinner-border {
        to {
            transform: rotate(360deg);
        }
    }
    .text-primary {
        color: #007bff;
    }
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }
    </style>
</head>
<body>
<div class="container">
    <h1>Generar Archivo Excel</h1>
    <form id="generarForm">
        <label for="fecha">Seleccione la fecha:</label>
        <input type="date" id="fecha" name="fecha" required>
        <button type="button" onclick="generarArchivo()">Generar Archivo</button>
    </form>
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress-bar-fill" id="progressBarFill"></div>
        </div>
        <div class="progress-text" id="progressText">0%</div>
    </div>
    <!-- Spinner de carga -->
    <div class="spinner" id="spinner" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>
</div>
    <script>
        var mod = 'descargas';
        function generarArchivo() {
            const fecha = document.getElementById('fecha').value;
            if (!fecha) {
                inform('Por favor, seleccione una fecha.');
                return;
            }
            document.getElementById('spinner').style.display = 'block';
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'generar_excel.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    document.getElementById('spinner').style.display = 'none';
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            inform('Archivo generado con éxito.');
                            window.location.href = response.file;
                        } else {
                            warnin('Error al generar el archivo.');
                        }
                    } else {
                        warnin('Error en la conexión con el servidor.');
                    }
                }
            };
            xhr.onprogress = function(event) {
                if (event.lengthComputable) {
                    const porcentaje = (event.loaded / event.total) * 100;
                    document.getElementById('progressBarFill').style.width = `${porcentaje}%`;
                    document.getElementById('progressText').textContent = `${Math.round(porcentaje)}%`;
                }
            };
            xhr.send(`fecha=${fecha}`);
        }
    </script>
    <div class="overlay" id="overlay" onClick="closeModal();">
		<div class="popup" id="popup" z-index="0" onClick="closeModal();">
			<div class="btn-close-popup" id="closePopup" onClick="closeModal();">&times;</div>
			<h3>
				<div class='image' id='<?php echo $mod; ?>-image'></div>
			</h3>
			<h4>
				<div class='message' id='<?php echo $mod; ?>-modal'></div>
			</h4>
		</div>
	</div>
</body>
</html>