<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Archivo Consolidado</title>
</head>
<body>
    <form id="generarForm">
        <label for="fecha">Seleccione la fecha:</label>
        <input type="date" id="fecha" name="fecha" required>
        <button type="button" onclick="generarArchivo()">Generar Archivo</button>
    </form>
    <div id="progreso" style="margin-top: 20px;">
        <p>Progreso: <span id="porcentaje">0%</span></p>
    </div>
    <script>
        function generarArchivo() {
            const fecha = document.getElementById('fecha').value;
            if (!fecha) {
                alert('Por favor, seleccione una fecha.');
                return;
            }
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'generar_excel.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert('Archivo generado con éxito.');
                        window.location.href = response.file;
                    } else {
                        alert('Error al generar el archivo.');
                    }
                }
            };
            xhr.onprogress = function(event) {
                if (event.lengthComputable) {
                    const porcentaje = (event.loaded / event.total) * 100;
                    document.getElementById('porcentaje').textContent = `${Math.round(porcentaje)}%`;
                }
            };
            xhr.send(`fecha=${fecha}`);
        }
    </script>
</body>
</html>