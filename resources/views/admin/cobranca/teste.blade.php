<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    <title>Laravel Ajax ConsoleTvs Charts Tutorial - ItSolutionStuff.com</title>

</head>

<body>

    <h1>Laravel Ajax ConsoleTvs Charts Tutorial - ItSolutionStuff.com</h1>
    <button class="btn" onclick="updateChart()">Update</button>
    <div style="width: 80%;margin: 0 auto;" class="chart-clientes-novos">

        {!! $chart->container() !!}

    </div>
    {!! $chart->script() !!}

    <script>
        function updateChart() {
            let clientesNovos = $('.chart-clientes-novos>canvas').attr('id'); 
            progresso = new Chart(document.getElementById(clientesNovos).getContext("2d"), {
                type: 'line',                
                data: {
                    labels: [1,2,3,4,5,6],
                    datasets: [{
                        label: '# of Votes',
                        data: [118, 19, 3, 115, 2, 313],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    }],
                }
            });
            progresso.update();
        }
    </script>


</body>

</html>
