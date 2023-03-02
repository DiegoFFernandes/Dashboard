@extends('admin.master.simple')

@section('content')
    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <div class="logo-section">
                    <img src="{{ asset('img/logo-ivo.png') }}" alt="" class="img-responsive">
                    <h3 class="">
                        {{ isset($title_page) ? $title_page : $uri }}
                        @if (isset($uri))
                            <small class="pull-right">{{ $date }}</small>
                        @endif
                    </h3>
                </div>              

            </div>
        </div>
        <div class="row invoice-info">
            <div>
                <div id="curve_chart" style="width: 800px; height: 400px"></div>
            </div>
            <div>
                <div id="sulco_chart" style="width: 800px; height: 400px"></div>
            </div>
        </div>
        <div class="page-break"></div>

        @foreach ($itens as $i)
            <div class="row invoice-info">
                <div class="col-md-4 invoice-col">
                    <div class="col-xs-12 table-responsive">
                        <p class="lead">Dados Cliente</p>
                        <table class="table table-striped table-sm" style="font-size: 12px">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Placa</th>
                                    <th>Pressão Min</th>
                                    <th>Pressão Max</th>
                                    <th>Sulco Ideal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $i['nm_pessoa'] }}</td>
                                    <td>{{ $i['placa'] }}</td>
                                    <td>{{ $i['ps_min'] }}</td>
                                    <td>{{ $i['ps_max'] }}</td>
                                    <td>{{ $i['sulco_ideal'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4 invoice-col">
                    <div class="col-xs-12 table-responsive">
                        <p class="lead">Dados Analise #{{ $i['id'] }}</p>
                        <table class="table table-striped table-sm" style="font-size: 12px">
                            <thead>
                                <tr>
                                    <th>Analisador</th>
                                    <th>Cód. Item</th>
                                    <th>Criada em</th>
                                    <th>Telefone</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $i['responsavel'] }}</td>
                                    <td>{{ $i['id_item'] }}</td>
                                    <td>{{ $i['created_at'] }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4 invoice-col">
                    <div class="col-xs-12 table-responsive">
                        <p class="lead">Dados Pneu</p>
                        <table class="table table-striped table-sm" style="font-size: 12px">
                            <thead>
                                <tr>
                                    <th>Marca/Modelo</th>
                                    <th>Medida</th>
                                    <th>Fogo</th>
                                    <th>Dot</th>
                                    <th>Sulco</th>
                                    <th>Pressão</th>
                                    <th>Posição</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $i['ds_modelo'] }}</td>
                                    <td>{{ $i['ds_medida'] }}</td>
                                    <td>{{ $i['fogo'] }}</td>
                                    <td>{{ $i['dot'] }}</td>
                                    <td>{{ $i['sulco'] }}</td>
                                    <td>{{ $i['pressao'] }}</td>
                                    <td>{{ $i['ds_posicao'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-bottom: 15px;">
                <p class="lead" style="padding-left: 15px;">Imagens</p>
                <div class="imagem-inline">
                    @foreach ($i['imagens'] as $image)
                        @if (isset($image))
                            <img src="{{ asset('storage/' . $image . '') }}" class="img-responsive">
                        @else
                            <p>Não existe registro com imagens.</p>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 invoice-col">
                    <p class="lead">Danos</p>
                    <table class="table table-striped table-sm" style="font-size: 12px">
                        <tbody>
                            <tr>
                                <th>Avaria</th>
                                <td>{{ $i['ds_causa'] }}</td>
                            </tr>
                            <tr>
                                <th>Recomendações</th>
                                <td>{{ $i['ds_recomendacoes'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="page-break"></div>
        @endforeach

        <div class="page-break"></div>
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <p class="lead">Resumo Coleta:</p>
                <table class="table table-striped table-sm" style="font-size: 12px">
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th>Marca/Modelo</th>
                            <th>Medida</th>
                            <th>Fogo</th>
                            <th>Sulco</th>
                            <th>Pressão</th>
                            <th>Eixo</th>
                            <th>Coleta em:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itens as $i)
                            <tr>
                                <td>{{ $i['placa'] }}</td>
                                <td>{{ $i['ds_modelo'] }}</td>
                                <td>{{ $i['ds_medida'] }}</td>
                                <td>{{ $i['fogo'] }}</td>
                                <td>{{ $i['sulco'] }}</td>
                                <td>{{ $i['pressao'] }}</td>
                                <td>{{ $i['ds_posicao'] }}</td>
                                <td>{{ $i['created_at'] }}</td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
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
                    drawChartsColumnPs();
                    drawChartsColumnSulco();
                }
            }, 100);
        }

        function drawChartsColumnPs() {
            var dados = {!! $dados_ps !!};
            var data = google.visualization.arrayToDataTable(dados);
            var options = {
                title: 'Pressão dos Pneus',
                hAxis: {
                    textPosition: 'none'
                },
                seriesType: 'bars',
                series: {
                    0: { // A série 0 representa as barras do gráfico
                        bar: {
                            groupWidth: '50%'
                        }
                    },
                    1: {
                        type: 'line'
                    },
                    2: {
                        type: 'line'
                    }
                }


            };
            var chart = new google.visualization.ComboChart(document.getElementById('curve_chart'));
            chart.draw(
                data, options);
        }

        function drawChartsColumnSulco() {
            var dados = {!! $dados_sulco !!};
            var data = google.visualization.arrayToDataTable(dados);
            var options = {
                title: 'Sulco dos Pneus',
                hAxis: {
                    textPosition: 'none'
                },
                seriesType: 'bars',
                series: {
                    0: { // A série 0 representa as barras do gráfico
                        bar: {
                            groupWidth: '50%'
                        }
                    },
                    1: {
                        type: 'line'
                    },
                    2: {
                        type: 'line'
                    }
                },
                colors: ['#e0440e', '#000000']


            };
            var chart = new google.visualization.ComboChart(document.getElementById('sulco_chart'));
            chart.draw(
                data, options);
        }
    </script>
@endsection
