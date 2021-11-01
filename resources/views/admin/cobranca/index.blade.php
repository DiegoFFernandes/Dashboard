@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Total 3 últimos meses</h3>
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
                        <h3 class="box-title">Clientes novos</h3>
                    </div>
                    <div class="box-body no-padding">

                    </div>
                </div>
            </div>
            <div class="col-md-4">
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Contato com cliente mês</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-agenda">
                            <thead>
                                <tr>
                                    <td colspan="2"></td>
                                    <th class="bg-success text-center" colspan="{{ date('d') }}">
                                        {{ Config::get('constants.meses.nMesHj') }} / Dias</th>
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
                                                <a href="{{route('cobranca.detalhe-agenda', ['cdusuario' => $operadores[$key]->CD_USUARIO, 'dt' => $a->DT])}}">{{ $a->QTD }}</a>
                                            </td>
                                        @endforeach
                                        <td class="bg-light-blue">{{ $total }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
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
@endsection
