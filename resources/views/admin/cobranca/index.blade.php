@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
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
                        <h3 class="box-title">Clientes novos 3 últimos meses </h3>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table table-striped">
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
                        <div class="box-body no-padding">
                            {!! $chart->container() !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Novos Clientes</h3>
                        </div>
                        <div class="box-body no-padding">
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
    <script>
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
    </script>
@endsection
