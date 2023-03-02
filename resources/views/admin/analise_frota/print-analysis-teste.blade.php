<!DOCTYPE html>
<html>

<head>
    <title>Gráfico em PDF</title>
    {{--    <script type="text/javascript" src="http://www.gstatic.com/charts/loader.js"></script> --}}

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">       
        

        function init() {
            google.charts.load('44', {
                packages: ['corechart']
            });
            var interval = setInterval(function() {
                if (google.visualization !== undefined &&
                    google.visualization.DataTable !== undefined &&
                    google.visualization.PieChart !== undefined) {
                    clearInterval(interval);
                    window.status = 'ready';
                    drawCharts();
                }
            }, 100);
        }

        function drawCharts() {
            var data = google.visualization.arrayToDataTable([
                ['Mês', 'Vendas'],
                ['Janeiro', 1000],
                ['Fevereiro', 1170],
                ['Março', 660],
                ['Abril', 1030],
                ['Maio', 780],
                ['Junho', 500]
            ]);

            var options = {
                title: 'Vendas por mês',
                curveType: 'function',
                legend: {
                    position: 'bottom'
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
    </script>
</head>

<body onload="init()">
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
</body>

</html>
