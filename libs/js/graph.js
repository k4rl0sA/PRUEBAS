document.addEventListener("DOMContentLoaded", function() {
    // Función para cargar el gráfico en el contenedor
    function cargarGrafico() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                if (xhr.status == 200) {
                    document.getElementById('graficoContainer').innerHTML = xhr.responseText;
                }
            }
        };
        xhr.open('GET', 'lib.php', true);
        xhr.send('a=gra&tb='+mod+'&type=radar');
    }

    // Cargar el gráfico al cargar la página
    cargarGrafico();

    // Actualizar el gráfico cada 5 segundos
    setInterval(cargarGrafico, 5000);
});
