@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- right column -->
            <div class="col-md-4">
                <!-- Horizontal Form -->
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form method="post"
                        action="{{ $uri == 'portaria/movimento/entrada' ? route('portaria.entrada') : route('portaria.saida') }}">
                        @csrf
                        <div class="box-body">
                            @includeIf('admin.master.messages')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select class="SearchPlaca form-control" style="width: 100%;" name="placa"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Pesquisar</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <!-- /.box -->
            </div>
            @if (isset($motorista))
                <!-- right column -->
                <div class="col-md-10">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Dados do Motorista</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form method="post"
                            action="{{ $uri == 'portaria/movimento/entrada' ? route('portaria.entrada.do') : route('portaria.saida.do') }}">
                            @csrf <div class="box-body">
                                <input type="text" class="hidden" name="cd_empresa" id="cd_empresa"
                                    value="{{ $motorista[0]->cd_empresa }}">
                                <input type="text" class="hidden" name="cd_motorista_veiculos" id="id"
                                    value="{{ $motorista[0]->id }}">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Empresa</label>
                                        @foreach ($empresas as $empresa)
                                            @if ($empresa->CD_EMPRESA == $motorista[0]->cd_empresa)
                                                <input type="text" class="form-control" name="empresa" id="empresa"
                                                    value="{{ $empresa->NM_EMPRESA }}" disabled>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cd_porteiro">Porteiro</label>
                                        <input type="text" class="form-control" name="cd_porteiro" id="cd_porteiro"
                                            value="" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cd_pessoa">Motorista no Veiculo</label>
                                        <select class="form-control select2" name="cd_pessoa" id="cd_pessoa"
                                            style="width: 100%">
                                            @foreach ($pessoas as $pessoa)
                                                @if ($motorista[0]->cd_pessoa == $pessoa->id)
                                                    <option selected="selected" value="{{ $pessoa->id }}">
                                                        {{ $pessoa->name }}</option>
                                                @else
                                                    <option value="{{ $pessoa->id }}">{{ $pessoa->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cd_linha">Linha Veiculo</label>
                                        <select class="form-control select2" name="cd_linha" id="cd_linha"
                                            style="width: 100%">
                                            @foreach ($linhas as $linha)
                                                @if ($linha->id == $motorista[0]->cd_linha)
                                                    <option selected="selected" value="{{ $linha->id }}">
                                                        {{ $linha->linha }}</option>
                                                @else
                                                    <option value="{{ $linha->id }}">{{ $linha->linha }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Placa</label>
                                        <input type="text" class="form-control" name="placa" id="id_placa"
                                            value="{{ $motorista[0]->placa }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Ano</label>
                                        <input type="number" class="form-control" name="ano" id="id_ano"
                                            value="{{ $motorista[0]->ano }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Cor</label>
                                        <input type="text" class="form-control" name="cor" id="id_cor"
                                            value="{{ $motorista[0]->cor }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="cd_marcamodelofrota">Marca/Modelo</label>
                                        <input type="text" class="form-control" name="modelo" id="id_modelo"
                                            value="{{ $motorista[0]->dsmarca }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipoveiculo">Tipo Veiculo</label>
                                        <input type="text" class="form-control" name="tipoveiculo" id="id_tipoveiculo"
                                            value="{{ $motorista[0]->dstipo }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="horario_movimento">Horario</label>
                                        <input type="text" class="form-control"
                                            name="{{ $uri == 'portaria/movimento/entrada' ? 'entrada' : 'saida' }}"
                                            id="horario_movimento" value="{{ date('Y-m-d H:i:s') }}" readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <label for="observacao">Observação</label>
                                        <textarea class="form-control" rows="2" name="observacao"
                                            placeholder="Precisa informar alguma informação? Digite aqui!"></textarea>
                                    </div>
                                </div>

                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        {{ $uri == 'portaria/movimento/entrada' ? 'Entrada' : 'Saída' }}
                                    </button>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </form>
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (right) -->
            @endif

            <!--/.col (right) -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    <!-- My Scripts -->
    <script src="{{ asset('js/portaria/scripts.js') }}"></script>
    {{-- <script src="{{ asset('js/scripts.js') }}"></script> --}}

@endsection
