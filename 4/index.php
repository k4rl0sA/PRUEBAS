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
    <button id="exportarBtn">Exportar Datos</button>

    <!-- Script para manejar la exportación de datos -->
    <script>
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

        // Manejador para el botón de exportar datos
        addEventHandler('#exportarBtn', 'click', function(event) {
            event.preventDefault();
            exportarDatos('exp_datos');
        });

      

         // Función para exportar datos utilizando fetch
         function exportarDatos(funcion) {
            // Construir la URL para la petición a lib.php
            const url = `lib.php?funcion=${funcion}`;

            // Opciones para la petición fetch
            const fetchOptions = {
                method: 'GET', // Método GET para este ejemplo
                headers: {
                    'Content-Type': 'application/json' // Tipo de contenido JSON
                }
            };

            // Realizar la petición fetch
            fetch(url, fetchOptions)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la petición.');
                    }
                    return response.json(); // Convertir la respuesta a JSON
                })
                .then(data => {
                    // Descargar el archivo generado
                    const downloadUrl = `lib.php?download=${data.archivo}`;
                    const link = document.createElement('a');
                    link.href = downloadUrl;
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
                .catch(error => console.error('Error:', error));
        }

        

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
    </script>
</body>
</html>
