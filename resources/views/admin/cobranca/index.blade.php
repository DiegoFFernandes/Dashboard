@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-plus-circle"></i></span>
                    <div class="info-box-content">
                        <div class="box-tools pull-right">
                            <select class="form-control form-control-sm no-padding" style="height: auto;"
                                id="option-novos-mes">
                                <option value="0" selected>{{ Config::get('constants.meses.nMesHj') }}</option>
                                <option value="30">{{ Config::get('constants.meses.nMes30') }}</option>
                                <option value="60">{{ Config::get('constants.meses.nMes60') }}</option>
                                <option value="90">{{ Config::get('constants.meses.nMes90') }}</option>
                                <option value="120">{{ Config::get('constants.meses.nMes120') }}</option>
                                <option value="150">{{ Config::get('constants.meses.nMes150') }}</option>
                                <option value="180">{{ Config::get('constants.meses.nMes180') }}</option>
                                <option value="210">{{ Config::get('constants.meses.nMes210') }}</option>
                            </select>
                        </div>
                        <span class="info-box-text">Clientes Novos</span>
                        <span class="info-box-number">{{ $qtdClientesNovosMes[0]->QTD }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 70%"></div>
                        </div>
                        <span class="progress-description">
                            {{ Config::get('constants.meses.nMesHj') }}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3">
                <div class="box box box-success collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Clientes Novos Forma Pagamento</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="display: none;">
                        <table class="table table-sm table-borderless table-fp">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descrição</th>
                                    <th>Qtd</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($qtdClientesFormaPagamento as $q)
                                    <tr>
                                        <td>{{ $q->CD_FORMAPAGTO }}</td>
                                        <td>{{ $q->DS_FORMAPAGTO }}</td>
                                        <td data-toggle="tooltip" data-placement="bottom" title="Clique para ver detalhes">
                                            <a class="list-fp" href="#"
                                                data-href="?fp={{ $q->CD_FORMAPAGTO }}&dti={{$dti}}&dtf={{$dtf}}">{{ $q->QTD }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.info-box -->
            <div class="col-md-5">
                <div class="box box-success box-list-client">
                    <div class="box-header with-border">
                        <h3 class="box-title">Lista de Clientes</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="table-list-client-fp">
                            <p>Expanda <b>Clientes Novos Forma pagamento</b>, e escolha um código para exibir os Clientes!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom" style="cursor: move;">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class=""><a href="#cliente-novos" data-toggle="tab" aria-expanded="true">Clientes
                                Novos</a>
                        </li>
                        <li class="active"><a href="#agenda" data-toggle="tab" aria-expanded="false">Agenda</a>
                        </li>
                        <li class="pull-left header"><i class="fa fa-inbox"></i> Análise Cobranca</li>
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="agenda">
                            <table class="table table-hover table-agenda">
                                <thead>
                                    <tr>
                                        <td colspan="2"></td>
                                        <th class="bg-aqua text-center" colspan="{{ date('d') }}">
                                            Agenda Clientes {{ Config::get('constants.meses.nMesHj') }} / Dias</th>
                                    </tr>
                                    <tr>
                                        <th class="bg-light-blue">Cód. Usuario</th>
                                        <th class="bg-light-blue">Nome</th>
                                        @foreach ($agenda[0] as $a)
                                            <th class="bg-light-blue">{{ date('d', strtotime($a->DT)) }}</th>
                                        @endforeach
                                        <th class="bg-light-blue">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agenda as $key => $valor)
                                        @php $total = 0 @endphp
                                        <tr>
                                            <td>{{ $operadores[$key]->CD_USUARIO }}</td>
                                            <td>{{ $operadores[$key]->NM_USUARIO }}</td>
                                            @foreach ($agenda[$key] as $a)
                                                @php $total += $a->QTD @endphp
                                                <td data-toggle="tooltip" data-placement="bottom"
                                                    title="Clique para ver detalhes">
                                                    <a
                                                        href="{{ route('cobranca.detalhe-agenda', ['cdusuario' => $operadores[$key]->CD_USUARIO, 'dt' => $a->DT]) }}">{{ $a->QTD }}
                                                    </a>
                                                </td>
                                            @endforeach
                                            <td class="bg-light-blue">{{ $total }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="cliente-novos">
                            <table class="table table-hover table-agenda">
                                <thead>
                                    <tr>
                                        <td colspan="2"></td>
                                        <th class="bg-green text-center" colspan="{{ date('d') }}">
                                            Cadastro de clientes {{ Config::get('constants.meses.nMesHj') }} / Dias</th>
                                    </tr>
                                    <tr>
                                        <th class="bg-green">Cód. Usuario</th>
                                        <th class="bg-green">Nome</th>
                                        @foreach ($clientesNovosDia[0] as $a)
                                            <th class="bg-green">{{ date('d', strtotime($a->DT)) }}</th>
                                        @endforeach
                                        <th class="bg-green">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clientesNovosDia as $key => $valor)
                                        @php $total = 0 @endphp
                                        <tr>
                                            <td>{{ $operadores[$key]->CD_USUARIO }}</td>
                                            <td>{{ $operadores[$key]->NM_USUARIO }}</td>
                                            @foreach ($clientesNovosDia[$key] as $a)
                                                @php $total += $a->QTD @endphp
                                                <td data-toggle="tooltip" data-placement="bottom"
                                                    title="Clique para ver detalhes">
                                                    <a
                                                        href="{{ route('cobranca.clientes-novos', ['cdusuario' => $operadores[$key]->CD_USUARIO, 'dt' => $a->DT]) }}">{{ $a->QTD }}
                                                    </a>
                                                </td>
                                            @endforeach
                                            <td class="bg-green">{{ $total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title" id="title-agenda">Agenda 3 últimos meses</h3>
                        <div class="box-tools pull-right">
                            <select id="mesagenda">
                                <option value="0">3 últimos meses</option>
                                <option value="120">{{ Config::get('constants.meses.nMes120') }}</option>
                                <option value="150">{{ Config::get('constants.meses.nMes150') }}</option>
                                <option value="180">{{ Config::get('constants.meses.nMes180') }}</option>
                                <option value="210">{{ Config::get('constants.meses.nMes210') }}</option>
                                <option value="240">{{ Config::get('constants.meses.nMes240') }}</option>
                                <option value="270">{{ Config::get('constants.meses.nMes270') }}</option>
                                <option value="300">{{ Config::get('constants.meses.nMes300') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-body no-padding" id="table-agenda">
                        <table class="table table-striped" id="table-agenda-3-meses">
                            <thead>
                                <tr>
                                    <th>Cód. Usuario</th>
                                    <th>Nome</th>
                                    <th>{{ Config::get('constants.meses.nMes30') }}</th>
                                    <th>{{ Config::get('constants.meses.nMes60') }}</th>
                                    <th>{{ Config::get('constants.meses.nMes90') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($meses as $m)
                                    <tr>
                                        <td>{{ $m->CD_USUARIO }}</td>
                                        <td>{{ $m->NM_USUARIO }}</td>
                                        <td>{{ $m->MES1 }}</td>
                                        <td>{{ $m->MES2 }}</td>
                                        <td>{{ $m->MES3 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title" id="title-clientes-novos">Clientes novos 3 últimos meses </h3>
                        <div class="box-tools pull-right">
                            <select id="mesclientesnovos">
                                <option value="0">3 últimos meses</option>
                                <option value="120">{{ Config::get('constants.meses.nMes120') }}</option>
                                <option value="150">{{ Config::get('constants.meses.nMes150') }}</option>
                                <option value="180">{{ Config::get('constants.meses.nMes180') }}</option>
                                <option value="210">{{ Config::get('constants.meses.nMes210') }}</option>
                                <option value="240">{{ Config::get('constants.meses.nMes240') }}</option>
                                <option value="270">{{ Config::get('constants.meses.nMes270') }}</option>
                                <option value="300">{{ Config::get('constants.meses.nMes300') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-body no-padding" id="table-clientes-novos">
                        <table class="table table-striped" id="table-clientes-novos-3-meses">
                            <thead>
                                <th>Cód. Usuario</th>
                                <th>Nome</th>
                                <th>{{ Config::get('constants.meses.nMes30') }}</th>
                                <th>{{ Config::get('constants.meses.nMes60') }}</th>
                                <th>{{ Config::get('constants.meses.nMes90') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($clientesNovos as $m)
                                    <tr>
                                        <td>{{ $m->CD_USUARIOCAD }}</td>
                                        <td>{{ $m->NM_USUARIO }}</td>
                                        <td>{{ $m->MES1 }}</td>
                                        <td>{{ $m->MES2 }}</td>
                                        <td>{{ $m->MES3 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4 no-padding">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Progresso Mês</h3>
                        </div>
                        <div class="box-body no-padding chart-progresso-mes">
                            {!! $chart->container() !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Novos Clientes</h3>
                        </div>
                        <div class="box-body no-padding chart-clientes-novos">
                            {!! $chartClienteNovos->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
    {!! $chart->script() !!}
    {!! $chartClienteNovos->script() !!}

    <script type="text/javascript">
        $('#mesagenda').change(function() {
            var mes = $(this).val();
            if (mes == 0) {
                $("#title-agenda").text("Agenda 3 últimos meses");
                $("#table-agenda-mes").hide();
                $("#table-agenda-3-meses").show();
            } else {
                $.ajax({
                    url: "{{ route('cobranca.agenda.mes') }}?dt=" + mes,
                    method: 'GET',
                    success: function(data) {
                        $("#title-agenda").text("Agenda por Mês");
                        $("#table-agenda-mes").remove();
                        $("#table-agenda-3-meses").hide();
                        $("#table-agenda").append(data);
                    }
                });
            }
        });
        $('#mesclientesnovos').change(function() {
            var mes = $(this).val();
            if (mes == 0) {
                $("#title-clientes-novos").text("Clientes novos 3 últimos meses");
                $("#table-clientes-novos-mes").hide();
                $("#table-clientes-novos-3-meses").show();
            } else {
                $.ajax({
                    url: "{{ route('cobranca.clientes-novos-mes') }}?dt=" + mes,
                    method: 'GET',
                    success: function(data) {
                        $("#title-clientes-novos").text("Cliente novos por Mês");
                        $("#table-clientes-novos-mes").remove();
                        $("#table-clientes-novos-3-meses").hide();
                        $("#table-clientes-novos").append(data.html);

                        let clientesNovos = $('.chart-clientes-novos>canvas').attr('id');
                        let graphClientes = document.getElementById(clientesNovos).getContext("2d");

                        progresso = new Chart(graphClientes, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: '# Mês',
                                    data: data.qtd,
                                    backgroundColor: 'rgba(75, 192, 192)',
                                }],
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            }
                        });
                        progresso.update();
                    }


                });
            }
        });
        $('.list-fp').click(function() {
            let data = $(this).data('href');                      
            $.ajax({
                method: 'GET',
                url: '{{ route('get-fp-clients-novos') }}',
                data: data,
                success: function(result) {
                    $('.box-list-client').removeClass('hidden')
                    $('#table-list-client-fp').empty();
                    $('#table-list-client-fp').append(result.html)
                    $('#table-list-client-fp > table').DataTable({
                        "scrollY": "200px",
                        "scrollCollapse": true,
                        "paging": false,
                        "columnDefs": [{
                            "width": "32%",
                            "targets": 0
                        }],
                        language: {
                            url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                        }
                    });
                }
            })
        });
        $('#option-novos-mes').change(function() {
            let mes_cli_novo = $(this).val();
            let url = "{{ route('get-qtd-clients-novos') }}?mes=" + mes_cli_novo;
            var html = "";
            $.ajax({
                method: 'GET',
                url: url,
                success: function(result) {                    
                    $(".info-box-number").text(result['qtd']);
                    $(".progress-description").text(result['dt'].nmes);
                    $(".table-fp > tbody").empty();
                    $.each(result['qtdclifp'], function(key, value) {
                        html += "<tr>" +
                            "<td>" + value.CD_FORMAPAGTO + "</td>" +
                            "<td>" + value.DS_FORMAPAGTO + "</td>" +
                            "<td data-toggle='tooltip' data-placement='bottom' title='Clique para ver detalhes'><a class='list-fp' href='#' data-href='?fp=" +
                            value
                            .CD_FORMAPAGTO + "&dti=" + result['dt'].dti + "&dtf=" + result['dt']
                            .dtf + "'>" + value.QTD + "</a></td>" +
                            "</tr>";
                    });
                    $(".table-fp > tbody").append(html);
                    $('.list-fp').click(function() {
                        let data = $(this).data('href');
                        $.ajax({
                            method: 'GET',
                            url: '{{ route('get-fp-clients-novos') }}',
                            data: data,                                                        
                            success: function(result) {                                
                                //$('.box-list-client').removeClass('hidden')
                                $('#table-list-client-fp').empty();
                                $('#table-list-client-fp').append(result.html)
                                $('#table-list-client-fp > table').DataTable({
                                    "scrollY": "200px",
                                    "scrollCollapse": true,
                                    "paging": false,
                                    "columnDefs": [{
                                        "width": "32%",
                                        "targets": 0
                                    }],
                                    language: {
                                        url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                                    }
                                });                                
                            }
                        })
                    });
                }

            });

        });
    </script>

@endsection
