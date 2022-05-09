@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="btn-fechar">
            <button class="btn btn-primary" id="btn-hide">Ver Lote PCP</button>
        </div>
        <div class="row">
            <div class="col-md-10 hidden" id="lote-pcp">
                <div class="box box-danger">
                    <div class="box-body table-responsive">
                        <table class="table" id="table-pneus-lote">
                            <thead>
                                <tr>
                                    <th>Lote</th>
                                    <th>Nrº</th>
                                    <th>Sq</th>
                                    <th>Data</th>
                                    <th>Total</th>
                                    <th class="text-center">Sem E. Final</th>
                                    <th class="text-center">Sem E. Inicial</th>
                                    <th>Pneus S/E.F</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resultados as $resultado)
                                    <tr>
                                        @if ($resultado->DSCONTROLELOTEPCP == 'L2-VERMELHO')
                                            <td class="bg-danger">{{ $resultado->DSCONTROLELOTEPCP }}</td>
                                            <td class="bg-danger">{{ $resultado->NR_LOTE }}</td>
                                            <td class="bg-danger">{{ $resultado->NRLOTESEQDIA }}</td>
                                            <td class="bg-danger">{{ $resultado->DTPRODUCAO }}</td>
                                            <td class="bg-danger">{{ $resultado->QTDE_TOT }}</td>
                                            <td class="bg-danger text-center">{{ $resultado->QTDE_PROD }}</td>
                                            <td class="bg-danger text-center">{{ $resultado->QTDE_SEMEXAME }}</td>
                                            <td class="bg-danger"><a
                                                    href="lote-pcp/{{ $resultado->NR_LOTE }}/pneus-lote"
                                                    aria-label="Pesquisa pneus por lote">
                                                    <i class="fa fa-search" aria-hidden="true"></i></a></td>
                                        @elseif($resultado->DSCONTROLELOTEPCP == 'L3-AMARELO')
                                            <td class="bg-warning">{{ $resultado->DSCONTROLELOTEPCP }}</td>
                                            <td class="bg-warning">{{ $resultado->NR_LOTE }}</td>
                                            <td class="bg-warning">{{ $resultado->NRLOTESEQDIA }}</td>
                                            <td class="bg-warning">{{ $resultado->DTPRODUCAO }}</td>
                                            <td class="bg-warning">{{ $resultado->QTDE_TOT }}</td>
                                            <td class="bg-warning text-center">{{ $resultado->QTDE_PROD }}</td>
                                            <td class="bg-warning text-center">{{ $resultado->QTDE_SEMEXAME }}</td>
                                            <td class="bg-warning"><a
                                                    href="lote-pcp/{{ $resultado->NR_LOTE }}/pneus-lote"
                                                    aria-label="Pesquisa pneus por lote">
                                                    <i class="fa fa-search" aria-hidden="true"></i></a></td>
                                        @else
                                            <td>{{ $resultado->DSCONTROLELOTEPCP }}</td>
                                            <td>{{ $resultado->NR_LOTE }}</td>
                                            <td>{{ $resultado->NRLOTESEQDIA }}</td>
                                            <td>{{ $resultado->DTPRODUCAO }}</td>
                                            <td>{{ $resultado->QTDE_TOT }}</td>
                                            <td class="text-center">{{ $resultado->QTDE_PROD }}</td>
                                            <td class="text-center">{{ $resultado->QTDE_SEMEXAME }}</td>
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
                    <div class="box-body table-responsive">
                        <table class="table table-sm" id="table-pneus-lote-pcp" style="font-size: 12px;">
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
                                            <td class="bg-danger">{{ $pneu->NR_LOTE }}</td>
                                            <td class="bg-danger">{{ $pneu->DSCONTROLELOTEPCP }}</td>
                                            <td class="bg-danger">{{ $pneu->NRLOTESEQDIA }}</td>
                                            <td class="bg-danger">{{ $pneu->NM_PESSOA }}</td>
                                            <td class="bg-danger">{{ $pneu->DS_REGIAOCOMERCIAL }}</td>
                                            <td class="bg-danger">{{ $pneu->NR_COLETA }}</td>
                                            <td class="bg-danger">{{ $pneu->NR_OP }}</td>
                                            <td class="bg-danger">{{ $pneu->DSSERVICO }}</td>
                                            <td class="bg-danger">{{ $pneu->DS_ETAPA }}</td>
                                            <td class="bg-danger">{{ $pneu->DT_EXAME }}</td>
                                            <td class="bg-danger">{{ $pneu->DT_MANCHAO }}</td>
                                            <td class="bg-danger">{{ $pneu->DT_COBER }}</td>
                                            <td class="bg-danger">{{ $pneu->DT_VULC }}</td>
                                            <td class="bg-danger">{{ $pneu->DSOBSERVACAO }}</td>
                                        @elseif($pneu->DSCONTROLELOTEPCP == 'L3-AMARELO')
                                            <td class="bg-warning">{{ $pneu->NR_LOTE }}</td>
                                            <td class="bg-warning">{{ $pneu->DSCONTROLELOTEPCP }}</td>
                                            <td class="bg-warning">{{ $pneu->NRLOTESEQDIA }}</td>
                                            <td class="bg-warning">{{ $pneu->NM_PESSOA }}</td>
                                            <td class="bg-warning">{{ $pneu->DS_REGIAOCOMERCIAL }}</td>
                                            <td class="bg-warning">{{ $pneu->NR_COLETA }}</td>
                                            <td class="bg-warning">{{ $pneu->NR_OP }}</td>
                                            <td class="bg-warning">{{ $pneu->DSSERVICO }}</td>
                                            <td class="bg-warning">{{ $pneu->DS_ETAPA }}</td>
                                            <td class="bg-warning">{{ $pneu->DT_EXAME }}</td>
                                            <td class="bg-warning">{{ $pneu->DT_MANCHAO }}</td>
                                            <td class="bg-warning">{{ $pneu->DT_COBER }}</td>
                                            <td class="bg-warning">{{ $pneu->DT_VULC }}</td>
                                            <td class="bg-warning">{{ $pneu->DSOBSERVACAO }}</td>
                                        @else
                                            <td>{{ $pneu->NR_LOTE }}</td>
                                            <td>{{ $pneu->DSCONTROLELOTEPCP }}</td>
                                            <td>{{ $pneu->NRLOTESEQDIA }}</td>
                                            <td>{{ $pneu->NM_PESSOA }}</td>
                                            <td>{{ $pneu->DS_REGIAOCOMERCIAL }}</td>
                                            <td>{{ $pneu->NR_COLETA }}</td>
                                            <td>{{ $pneu->NR_OP }}</td>
                                            <td>{{ $pneu->DSSERVICO }}</td>
                                            <td>{{ $pneu->DS_ETAPA }}</td>
                                            <td>{{ $pneu->DT_EXAME }}</td>
                                            <td>{{ $pneu->DT_MANCHAO }}</td>
                                            <td>{{ $pneu->DT_COBER }}</td>
                                            <td>{{ $pneu->DT_VULC }}</td>
                                            <td>{{ $pneu->DSOBSERVACAO }}</td>
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
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        // Toggle Mostrar PCP
        $(document).ready(function() {
            $('#table-pneus-lote').DataTable({
                pageLength: 25,
                order: [
                    [3, 'desc']
                ]
            });
            $('#table-pneus-lote-pcp').DataTable({
                pageLength: 100,
            });

            var btnHide = $('#btn-hide'),
                lotePcp = $('#lote-pcp'),
                btnClicked;

            btnHide.click(function() {
                if (btnClicked != true) {
                    btnClicked = true;
                    lotePcp.removeClass('hidden ');
                    btnHide.removeClass('btn-primary').addClass('btn-danger');
                    btnHide.html('Fechar Lote');
                } else {
                    btnClicked = false;
                    lotePcp.addClass('hidden');
                    btnHide.removeClass('btn-danger').addClass('btn-primary');
                    btnHide.html('Abrir Lote');
                }
            });
        });
        // Fim Toggle Mostrar PCP
    </script>
@endsection
