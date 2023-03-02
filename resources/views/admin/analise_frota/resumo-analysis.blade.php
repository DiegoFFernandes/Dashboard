@extends('admin.master.master')

@section('content')
    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    {{ isset($title_page) ? $title_page : $uri }}
                    {{-- <small>{{ $uri }}</small> --}}
                    @if (isset($uri))
                        <small class="pull-right">{{ $date }}</small>
                    @endif
                </h2>
            </div>
        </div>
        @foreach ($itens as $i)
            <div class="row invoice-info">
                <div class="col-md-4 invoice-col">
                    <address>
                        <p class="lead">Dados Cliente</p>
                        <strong>Nome:</strong> {{ $i['nm_pessoa'] }}<br>
                        <strong>Placa: </strong>{{ $i['placa'] }}<br>
                        <strong>Pressão Min: </strong>{{ $i['ps_min'] }}<br>
                        <strong>Pressão Max: </strong>{{ $i['ps_max'] }}<br>
                        <strong>Sulco Ideal: </strong>{{ $i['sulco_ideal'] }}<br>
                    </address>
                </div>
                <div class="col-md-4 invoice-col">
                    <address>
                        <p class="lead">Dados Analise #{{ $i['id'] }}</p>
                        <strong>Analisador: </strong>{{ $i['responsavel'] }}<br>
                        <strong>Cód. Item: </strong> {{ $i['id_item'] }}<br>
                        <strong>Criada em:</strong> {{ $i['created_at'] }}<br>
                        <strong>Telefone:</strong>
                    </address>
                </div>
                <div class="col-md-4 invoice-col">
                    <address>
                        <p class="lead">Dados Pneu</p>
                        <strong>Marca/Modelo: </strong> {{ $i['ds_modelo'] }}<br>
                        <strong>Medida: </strong> {{ $i['ds_medida'] }}<br>
                        <strong>Fogo: </strong> {{ $i['fogo'] }}<br>
                        <strong>Dot: </strong> <br>
                        <strong>Sulco: </strong> {{ $i['sulco'] }}<br>
                        <strong>Pressão: </strong> {{ $i['pressao'] }}<br>
                        <strong>Posição: </strong> {{ $i['ds_posicao'] }}<br>
                    </address>
                </div>
            </div>
            <div class="row">
                @foreach ($i['imagens'] as $image)
                    <div class="col-md-4">
                        @if (isset($image))
                            <img src="{{ asset('storage/' . $image . '') }}" class="img-responsive">
                        @else
                            <p>Não existe registro com imagens.</p>
                        @endif
                    </div>
                @endforeach
                <div class="col-md-12">
                    <address>
                        <p class="lead">Danos</p>
                        <strong>Avaria: </strong> {{ $i['ds_causa'] }}<br>
                        <strong>Causas Provaveis:</strong> {{ $i['ds_recomendacoes'] }}<br>
                    </address>

                </div>
            </div>
            <hr class="hr-dashed">
        @endforeach



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
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {});
    </script>
@endsection
