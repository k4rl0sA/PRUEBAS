<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Excel con Barra de Progreso</title>
    <style>
        #progress-bar {
            width: 100%;
            background-color: #f3f3f3;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 20px;
        }
        #progress {
            width: 0%;
            height: 30px;
            background-color: #4caf50;
            text-align: center;
            line-height: 30px;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Generar Archivo Excel</h1>
    <button id="generate-button">Generar Excel</button>
    <div id="progress-bar">
        <div id="progress">0%</div>
    </div>

    <script>
        document.getElementById('generate-button').addEventListener('click', function() {
            // Reiniciar la barra de progreso
            document.getElementById('progress').style.width = '0%';
            document.getElementById('progress').innerText = '0%';

            // Iniciar la conexión SSE
            const eventSource = new EventSource('lib.php');

            eventSource.onmessage = function(event) {
                const data = JSON.parse(event.data);
                const progressBar = document.getElementById('progress');

                if (data.progress !== undefined) {
                    progressBar.style.width = data.progress + '%';
                    progressBar.innerText = data.progress + '%';
                }

                if (data.status === 'completed') {
                    eventSource.close(); // Cerrar la conexión SSE
                    window.location.href = data.filename; // Descargar el archivo
                }
            };

            eventSource.onerror = function() {
                eventSource.close(); // Cerrar la conexión en caso de error
                alert('Ocurrió un error durante la generación del archivo.');
            };
        });
    </script>
</body>
</html>