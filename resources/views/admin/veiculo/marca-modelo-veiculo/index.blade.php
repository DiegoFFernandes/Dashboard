@extends('admin.master.master')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $title_page }}</h3>
                        <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"
                            data-toggle="modal" data-target="#CreateArticleModal">
                            Associar Marca/Modelo
                        </button>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered datatable">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">Id</th>
                                        <th>Nº Marca</th>
                                        <th>Marca</th>
                                        <th>Nº Modelo</th>
                                        <th>Modelo</th>
                                        <th>Nº Frota</th>
                                        <th>Frota</th>
                                        <th>Acões</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                    </div>
                </div>
                <!-- /.box -->
            </div>


            <!-- Create Marca Modelo Modal -->
            <div class="modal" id="CreateArticleModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Associar Marca e Modelo</h4>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="alert alert-dismissible hidden" id="alert">                                
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            </div>                            
                            <form id="formMarcaModelo">
                                <div class="col-md-4">
                                    <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}">
                                    <div class="form-group">
                                        <label>Marca</label>
                                        <select class="form-control" name="cd_marca" id="cd_marca">
                                            <option selected>Selecione</option>
                                            @foreach ($marcas as $marca)
                                                <option value="{{ $marca->id }}">{{ $marca->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Modelo</label>
                                        <select class="form-control" name="cd_modelo" id="cd_modelo">
                                            <option selected>Selecione</option>
                                            @foreach ($modelos as $modelo)
                                                <option value="{{ $modelo->id }}">{{ $modelo->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Frota</label>
                                        <select class="form-control" name="cd_frota" id="cd_frota">
                                            <option selected>Selecione</option>
                                            @foreach ($frotaveiculos as $frotaveiculo)
                                                <option value="{{ $frotaveiculo->id }}">{{ $frotaveiculo->descricao }}
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

            <!-- Edit Marca Modelo Modal -->
            <div class="modal" id="EditarModeloVeiculo">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Editar Marca Modelo</h4>
                            <button type="button" class="close modelClose" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="alert alert-dismissible hidden" id="alert">                                
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            </div>                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="id_marca">Marca</label>
                                        <select class="form-control" name="cd_marca" id="id_marca">
                                            @foreach ($marcas as $marca)
                                                <option value="{{ $marca->id }}">{{ $marca->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Modelo</label>
                                        <select data-width="100%" class="form-control" name="cd_modelo" id="id_modelo">
                                            @foreach ($modelos as $modelo)
                                                <option value="{{ $modelo->id }}">{{ $modelo->descricao }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Frota</label>
                                        <select data-width="100%" class="form-control" name="cd_frota" id="id_frota">
                                            @foreach ($frotaveiculos as $frotaveiculo)
                                                <option value="{{ $frotaveiculo->id }}">{{ $frotaveiculo->descricao }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="SubmitEditMarcaModeloForm">Atualizar</button>
                            <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Marca Modelo Modal -->
            <div class="modal" id="DeleteMarcaModeloVeiculo" data-keyboard="false" data-backdrop="static">
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
                            <button type="button" class="btn btn-danger" id="SubmitDeleteMarcaModeloForm">Delete</button>
                            <button type="button" class="btn btn-default modelClose" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>



            <div class="hidden" id="loading">
                <img id="loading-image" class="mb-4" src="{{ Asset('img/loader.gif') }}" alt="Loading...">
            </div>
        </div>
    </section>

@endsection
