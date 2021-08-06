@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="btn-fechar">
            <button class="btn btn-primary" id="btn-hide">Ver Lote PCP</button>
        </div>
        <div class="row">
            <div class="col-md-8 hidden" id="lote-pcp">
                <div class="box box-primary">
                    <div class="box-body table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Lote</th>
                                    <th>Nrº</th>
                                    <th>Sq</th>
                                    <th>Data</th>
                                    <th>Total</th>
                                    <th class="text-center">Produzidos</th>
                                    <th class="text-center">Sem Exame</th>
                                    <th>Ver Pneus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resultados as $resultado)
                                    <tr>
                                        @if ($resultado->DSCONTROLELOTEPCP == 'L2-VERMELHO')
                                            <td class="bg-danger">{!! utf8_encode($resultado->DSCONTROLELOTEPCP) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($resultado->NR_LOTE) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($resultado->NRLOTESEQDIA) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($resultado->DTPRODUCAO) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($resultado->QTDE_TOT) !!}</td>
                                            <td class="bg-danger text-center">{!! utf8_encode($resultado->QTDE_PROD) !!}</td>
                                            <td class="bg-danger text-center">{!! utf8_encode($resultado->QTDE_SEMEXAME) !!}</td>
                                            <td class="bg-danger"><a href="lote-pcp/{{ $resultado->NR_LOTE }}/pneus-lote"
                                                    aria-label="Pesquisa pneus por lote">
                                                    <i class="fa fa-search" aria-hidden="true"></i></a></td>
                                        @elseif($resultado->DSCONTROLELOTEPCP == "L3-AMARELO")
                                            <td class="bg-warning">{!! utf8_encode($resultado->DSCONTROLELOTEPCP) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($resultado->NR_LOTE) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($resultado->NRLOTESEQDIA) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($resultado->DTPRODUCAO) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($resultado->QTDE_TOT) !!}</td>
                                            <td class="bg-warning text-center">{!! utf8_encode($resultado->QTDE_PROD) !!}</td>
                                            <td class="bg-warning text-center">{!! utf8_encode($resultado->QTDE_SEMEXAME) !!}</td>
                                            <td class="bg-warning"><a href="lote-pcp/{{ $resultado->NR_LOTE }}/pneus-lote"
                                                    aria-label="Pesquisa pneus por lote">
                                                    <i class="fa fa-search" aria-hidden="true"></i></a></td>
                                        @else
                                            <td>{!! utf8_encode($resultado->DSCONTROLELOTEPCP) !!}</td>
                                            <td>{!! utf8_encode($resultado->NR_LOTE) !!}</td>
                                            <td>{!! utf8_encode($resultado->NRLOTESEQDIA) !!}</td>
                                            <td>{!! utf8_encode($resultado->DTPRODUCAO) !!}</td>
                                            <td>{!! utf8_encode($resultado->QTDE_TOT) !!}</td>
                                            <td class="text-center">{!! utf8_encode($resultado->QTDE_PROD) !!}</td>
                                            <td class="text-center">{!! utf8_encode($resultado->QTDE_SEMEXAME) !!}</td>
                                            <td><a href="lote-pcp/{{ $resultado->NR_LOTE }}/pneus-lote"
                                                    aria-label="Pesquisa pneus por lote">
                                                    <i class="fa fa-search" aria-hidden="true"></i></a></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table display table-sm">
                            <thead>
                                <tr>
                                    <th>Lote</th>
                                    <th>Ds Lote</th>
                                    <th>Sqº</th>
                                    <th>Cliente</th>
                                    <th>Regão</th>
                                    <th>Coleta</th>
                                    <th>Ordem</th>
                                    <th>Serviço</th>
                                    <th>Etapa</th>
                                    <th>Ex. Inicial</th>
                                    <th>2º Exame</th>
                                    <th>Cobertura</th>
                                    <th>Vulc</th>
                                    <th>Observação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pneus as $pneu)
                                    <tr>
                                        @if ($pneu->DSCONTROLELOTEPCP == 'L2-VERMELHO')
                                            <td class="bg-danger">{!! utf8_encode($pneu->NR_LOTE) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->DSCONTROLELOTEPCP) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->NRLOTESEQDIA) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->NM_PESSOA) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->DS_REGIAOCOMERCIAL) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->NR_COLETA) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->NR_OP) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->DSSERVICO) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->DS_ETAPA) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->DT_EXAME) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->DT_MANCHAO) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->DT_COBER) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->DT_VULC) !!}</td>
                                            <td class="bg-danger">{!! utf8_encode($pneu->DSOBSERVACAO) !!}</td>
                                        @elseif($pneu->DSCONTROLELOTEPCP == "L3-AMARELO")
                                            <td class="bg-warning">{!! utf8_encode($pneu->NR_LOTE) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->DSCONTROLELOTEPCP) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->NRLOTESEQDIA) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->NM_PESSOA) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->DS_REGIAOCOMERCIAL) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->NR_COLETA) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->NR_OP) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->DSSERVICO) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->DS_ETAPA) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->DT_EXAME) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->DT_MANCHAO) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->DT_COBER) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->DT_VULC) !!}</td>
                                            <td class="bg-warning">{!! utf8_encode($pneu->DSOBSERVACAO) !!}</td>
                                        @else
                                            <td>{!! utf8_encode($pneu->NR_LOTE) !!}</td>
                                            <td>{!! utf8_encode($pneu->DSCONTROLELOTEPCP) !!}</td>
                                            <td>{!! utf8_encode($pneu->NRLOTESEQDIA) !!}</td>
                                            <td>{!! utf8_encode($pneu->NM_PESSOA) !!}</td>
                                            <td>{!! utf8_encode($pneu->DS_REGIAOCOMERCIAL) !!}</td>
                                            <td>{!! utf8_encode($pneu->NR_COLETA) !!}</td>
                                            <td>{!! utf8_encode($pneu->NR_OP) !!}</td>
                                            <td>{!! utf8_encode($pneu->DSSERVICO) !!}</td>
                                            <td>{!! utf8_encode($pneu->DS_ETAPA) !!}</td>
                                            <td>{!! utf8_encode($pneu->DT_EXAME) !!}</td>
                                            <td>{!! utf8_encode($pneu->DT_MANCHAO) !!}</td>
                                            <td>{!! utf8_encode($pneu->DT_COBER) !!}</td>
                                            <td>{!! utf8_encode($pneu->DT_VULC) !!}</td>
                                            <td>{!! utf8_encode($pneu->DSOBSERVACAO) !!}</td>
                                        @endif

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
    <!-- My Scripts -->
    <script src="{{ asset('adminlte/dist/js/scripts.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
@endsection
