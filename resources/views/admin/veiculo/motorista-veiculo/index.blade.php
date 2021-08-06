@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            {{-- index --}}
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- box-header -->
                    <div class="box-header with-border">
                        <h3 class="box-title">Veiculos Cadastrados</h3>
                        <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"
                            data-toggle="modal" data-target="#CreateMotoristaVeiculoModal">
                            Associar Motorista/Veiculo
                        </button>
                    </div>
                    <!-- /.box-header -->
                    <!-- .box-body -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table datatable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Cd. Pessoa</th>
                                        <th>Pessoa</th>
                                        <th>Placa</th>
                                        <th>Cor</th>
                                        <th>Cd. Frota</th>
                                        <th>Frota</th>
                                        <th>Cd. Marca</th>
                                        <th>Marca</th>
                                        <th>Cd. Modelo</th>
                                        <th>Modelo</th>
                                        <th>Ano</th>
                                        <th>Cd. Tipo</th>
                                        <th>Tipo</th>
                                        <th>Ativo</th>
                                        <th>Acões</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-header -->
                </div>
                <!-- /.box -->
            </div>

            {{-- Create Motorista Veiculo --}}
            <div class="modal" id="CreateMotoristaVeiculoModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Associar Motorista a Veiculo</h4>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="alert alert-dismissible hidden" id="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            </div>
                            <form id="formMotoristaVeiculo">
                                <div class="col-md-12">
                                    <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}">
                                    <div class="form-group">
                                        <label>Empresa</label>
                                        <select class="form-control" name="cd_empresa" id="cd_empresa">
                                            <option selected>Selecione</option>
                                            @foreach ($empresas as $empresa)
                                                <option value="{{ $empresa->CD_EMPRESA }}">{{ $empresa->NM_EMPRESA }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Motorista</label>
                                        <select class="form-control select2" name="cd_pessoa" id="cd_pessoa"
                                            style="width: 100%;">
                                            <option value="0" selected>Selecione</option>
                                            @foreach ($pessoas as $pessoa)
                                                <option value="{{ $pessoa->id }}">{{ $pessoa->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Placa</label>
                                        <input type="text" class="form-control" name="placa" id="placa">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ano</label>
                                        <input type="number" class="form-control" name="ano" id="ano">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Cor</label>
                                        <input type="text" class="form-control" name="cor" id="cor">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cd_marcamodelofrota">Marca/Modelo</label>
                                        <select class="form-control select2" name="cd_marcamodelofrota"
                                            id="cd_marcamodelofrota" style="width: 100%;">
                                            <option value="0" selected>Selecione</option>
                                            @foreach ($marcas as $marca)
                                                <option value="{{ $marca->id }}">{{ $marca->dsmarca }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cd_tipoveiculo">Tipo Veiculo</label>
                                        <select class="form-control select2" name="cd_tipoveiculo" id="cd_tipoveiculo"
                                            style="width: 100%;">
                                            <option value="0" selected>Selecione</option>
                                            @foreach ($tipoveiculos as $tipoveiculo)
                                                <option value="{{ $tipoveiculo->id }}">{{ $tipoveiculo->descricao }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="SubmitCreateMarcaModeloForm">Criar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Edit Motorista Veiculo --}}
            <div class="modal" id="EditMotoristaVeiculoModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Editar Motorista a Veiculo</h4>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="alert alert-dismissible hidden" id="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Empresa</label>
                                    <select class="form-control" name="cd_empresa" id="id_empresa">
                                        <option selected>Selecione</option>
                                        @foreach ($empresas as $empresa)
                                            <option value="{{ $empresa->CD_EMPRESA }}">{{ $empresa->NM_EMPRESA }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Motorista</label>
                                    <select class="form-control select2" name="cd_pessoa" id="id_pessoa"
                                        style="width: 100%;">
                                        <option selected>Selecione</option>
                                        @foreach ($pessoas as $pessoa)
                                            <option value="{{ $pessoa->id }}">{{ $pessoa->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Placa</label>
                                    <input type="text" class="form-control" name="placa" id="id_placa">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ano</label>
                                    <input type="number" class="form-control" name="ano" id="id_ano">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Cor</label>
                                    <input type="text" class="form-control" name="cor" id="id_cor">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cd_marcamodelofrota">Marca/Modelo</label>
                                    <select class="form-control select2" name="cd_marcamodelofrota" id="id_marcamodelofrota"
                                        style="width: 100%;">
                                        <option selected>Selecione</option>
                                        @foreach ($marcas as $marca)
                                            <option value="{{ $marca->id }}">{{ $marca->dsmarca }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cd_tipoveiculo">Tipo Veiculo</label>
                                    <select class="form-control select2" name="cd_tipoveiculo" id="id_tipoveiculo"
                                        style="width: 100%;">
                                        <option selected>Selecione</option>
                                        @foreach ($tipoveiculos as $tipoveiculo)
                                            <option value="{{ $tipoveiculo->id }}">{{ $tipoveiculo->descricao }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" id="SubmitCreateMotoristaVeiculoForm">Editar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Marca Modelo Modal -->
            <div class="modal" id="DeleteMotoristaVeiculo" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <button type="button" class="close modelClose" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Exclusão</h4>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div id="deleteMsg"></div>
                            <div class="alert alert-dismissible hidden">
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="SubmitDeleteMotoristaVeiculoForm">Excluir</button>
                            <button type="button" class="btn btn-default modelClose" id="btnCancel" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>




        </div>
        <!-- /.row -->

        {{-- Icon loading --}}
        <div class="hidden" id="loading">
            <img id="loading-image" class="mb-4" src="{{ Asset('img/loader.gif') }}" alt="Loading...">
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    @includeIf('admin.master.datatables')
    <!-- My Scripts -->
    <script src="{{ asset('js/motorista-veiculo/scripts.js') }}"></script>
@endsection
