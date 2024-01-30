@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                @includeIf('admin.master.messages')
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left active"><a href="#acompanhamento" data-toggle="tab"
                                aria-expanded="true">Acompanhamento Ordem</a>
                        </li>
                        <li class="pull-left"><a href="#meta" data-toggle="tab" aria-expanded="false">Meta x
                                Operador</a>
                        </li>

                        {{-- <li class="header"><i class="fa fa-inbox"></i> Controle Epi</li> --}}
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="acompanhamento">
                            <div class="box-body" id="body-select">
                                <div class="col-md-6 mb-4" style="margin-bottom: 2em">
                                    <!-- /.box-header -->
                                    <form class="form" action="{{ route('admin.producao.acompanha.ordem') }}"
                                        method="post">
                                        @csrf
                                        <div class="form-inline">
                                            <div class="form-group">
                                                <label for="numeroOrdem">Ordem:</label>
                                                (<a href="https://zxing.appspot.com/scan?ret=https://producao.ivorecap.com.br/producao/acompanha-ordem/h^f7dbtz^E1tj8xAZ6aEy7gsQ4ReEBYdo{CODE}"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Exclusivo Celular">Leitor</a>):
                                                <input type="number" class="form-control" name="nr_ordem"
                                                    value="{{ isset($codigo_barras) ? $codigo_barras : old('nr_ordem') }}"
                                                    placeholder="123456" required />
                                            </div>
                                            <button type="submit" class="btn btn-primary">Pesquisar</button>
                                        </div>

                                    </form>
                                </div>
                                @if (isset($status_etapas))
                                    <div class="col-md-8">
                                        <div class="box collapsed-box">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Informações da Ordem -
                                                    {{ $info_pneu[0]->ORDEM }}</h3>
                                                <div class="box-tools pull-right">
                                                    @if ($info_pneu[0]->ALTERANDO == 'D' || $info_pneu[0]->ALTERANDO == 'S')
                                                        <a href="{{ route('unlock-order', ['nr_ordem' => Crypt::encrypt($info_pneu[0]->ORDEM)]) }}"
                                                            class="btn btn-warning btn-sm">Desbloquear</a>
                                                    @endif
                                                    <button type="button" class="btn btn-box-tool"
                                                        data-widget="collapse"><i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body table-responsive">
                                                <table class="table">
                                                    <tbody>
                                                        @foreach ($info_pneu as $info)
                                                            @foreach ($info as $chave => $valor)
                                                                <tr>
                                                                    <th scope="row">{{ $chave }}:</th>
                                                                    <td>{!! $valor !!}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="box">
                                            <div class="box-body table-responsive">
                                                <table class="table display table-striped table-bordered"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Usuario</th>
                                                            <th>Entrada</th>
                                                            <th>Saida</th>
                                                            <th>Detalhes</th>
                                                            <th>Retrabalho</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($status_etapas as $acompanha)
                                                            <tr>
                                                                <th scope="row">{{ $acompanha->O_DS_ETAPA }}</th>
                                                                <td>{{ $acompanha->O_NM_USUARIO }}</td>
                                                                <td>{{ $acompanha->O_DT_ENTRADA . ' ' . $acompanha->O_HR_ENTRADA }}
                                                                </td>
                                                                <td>{{ $acompanha->O_DT_SAIDA . ' ' . $acompanha->O_HR_SAIDA }}
                                                                </td>
                                                                <td>{{ $acompanha->O_DS_COMPLEMENTOETAPA }}</td>
                                                                <td>{{ $acompanha->O_ST_RETRABALHO }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                    </div>
                                @endif
                                @if (isset($sem_info))
                                    <div class="col-md-12">
                                        <h3>Ordem <strong>{{ $nr_ordem }}</strong> não existe...</h3>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div class="tab-pane" id="meta">
                            <div class="box-body">
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        @includeIf('admin.master.etapas-produtivas')
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="cd_executor">Cód. Operador</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="cd_executor"
                                                    placeholder="Código Operador">
                                                <div class="input-group-addon" id="btn-search-executor">
                                                    <button><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label>Operador</label>
                                            <input type="text" id='nm_executor' class="form-control" disabled="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-block"
                                            id="search_meta">Consultar</button>
                                    </div>

                                </div>
                                <div class="col-md-6" style="padding-top: 1em;">
                                    <div class="box box-primary">
                                        <div class="box-body with-border">
                                            <div class="col-lg-4 col-xs-6">
                                                <div class="small-box bg-green">
                                                    <div class="inner">
                                                        <h3 class="meta-hoje"></h3>
                                                        <p>Hoje</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-xs-6">
                                                <div class="small-box bg-light-blue">
                                                    <div class="inner">
                                                        <h3 class="meta-ontem"></h3>
                                                        <p>Ontem</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-xs-6">
                                                <div class="small-box bg-light-blue">
                                                    <div class="inner">
                                                        <h3 class="meta-anteontem"></h3>
                                                        <p>Anteontem</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.etapas').select2();

            $('#btn-search-executor').click(function() {
                var cd_executor = $('#cd_executor').val();
                var nm_executor;
                $.ajax({
                    type: "GET",
                    url: "{{ route('search-operador') }}",
                    data: {
                        cd_executor: cd_executor
                    },
                    success: function(response) {
                        if (response.error) {
                            ''
                            msg(response.error, 'alert-warning', 'fa fa-warning');
                            return false;
                        } else {
                            $('#nm_executor').val(response.success);
                        }
                    }
                });
            });
            $('#search_meta').click(function() {
                cd_etapa = $('.etapas').val();
                nm_executor = $('#nm_executor').val();
                cd_executor = $('#cd_executor').val();
                if (nm_executor == '' || cd_executor == '' || cd_etapa == 0) {                    
                        msgToastr('Favor insira uma etapa / código de executor e clique na lupa!', 'warning');
                } else {
                    $.ajax({
                        method: 'GET',
                        url: "{{ route('meta-operador') }}",
                        data: {
                            cd_executor: cd_executor,
                            cd_etapa: cd_etapa
                        },
                        beforeSend: function() {

                        },
                        success: function(response) {
                            if (response.error) {                                
                                msgToastr(response.error, 'info');
                                return false;
                            } else {
                                $('.meta-hoje').text(response.hoje);
                                $('.meta-ontem').text(response.ontem);
                                $('.meta-anteontem').text(response.anteontem)
                            }
                        }
                    });
                }
            });

            $('#cd_executor').blur(function() {
                if (!this.value) {
                    $('#nm_executor').val('');
                }
            });
        });
    </script>
@endsection
