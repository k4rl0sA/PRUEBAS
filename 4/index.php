<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Eventos y Exportación</title>
</head>
<body>
    <h1>Gestión de Eventos y Exportación de Datos</h1>

    <!-- Botón para exportar datos -->
    <button onclick="exportarDatos('exp_datos')">Exportar Datos</button>

    <!-- Script para manejar la exportación de datos -->
    <script>
        function exportarDatos(funcion) {
            // Aquí se podría agregar lógica adicional si se requiere
            
            // Hacer una petición AJAX a lib.php con la función deseada
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'lib.php?funcion=' + funcion, true);
            xhr.responseType = 'blob'; // Esperamos una respuesta binaria (para el archivo Excel)
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var blob = xhr.response;
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'datos_exportados.xls'; // Nombre del archivo Excel
                    link.click();
                }
            };
            
            xhr.send();
        }
    </script>
</body>
</html>
