<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Exportar Datos</title>
</head>
<body>
    <h1>Exportar Datos</h1>
    <button onclick="exportarDatos()">Exportar a XLS</button>

    <script>
        function exportarDatos() {
            // Ejemplo de consulta SQL para exportar
            const sql = "SELECT * FROM usuarios";
            const nombreArchivo = "datos_exportados";

            // Configuración de la petición Fetch para llamar a lib.php
            fetch('lib.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ sql: sql, name: nombreArchivo })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al exportar datos');
                }
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(new Blob([blob]));
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = `${nombreArchivo}.xls`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                alert('Datos exportados correctamente');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al exportar los datos');
            });
        }
    </script>
</body>
</html>
