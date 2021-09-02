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
                        <h3 class="box-title">Pessoas Cadastradas</h3>
                        <div style="float: right; font-weight: 900;">
                            <button class="btn btn-info btn-sm add" type="button" data-toggle="modal"
                                data-target="#CreatePessoaModal" data-backdrop="static" data-keyboard="false">
                                Adicionar Pessoa
                            </button>
                            <a href="{{ route('email.index') }}" class="btn btn-info btn-sm add" type="button">
                                Adicionar Email
                            </a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- .box-body -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">Cod.</th>
                                        <th>Pessoa</th>
                                        <th>Cpf</th>
                                        <th>Cd. Email</th>
                                        <th>Email</th>
                                        <th>Empresa</th>
                                        <th>Endereço</th>
                                        <th>Número</th>
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

            {{-- Create Pessoa --}}
            <div class="modal" id="CreatePessoaModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <button type="button" class="close btn-cancel" data-dismiss="modal" data-keyboard="false"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Adicionar Pessoa</h4>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="alert alert-dismissible hidden" id="alert">
                                <button type="button" class="close btn-cancel" data-dismiss="alert"
                                    aria-hidden="true">×</button>
                            </div>
                            <form id="formPessoa">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Cd. Pessoa</label>
                                        <input type="text" class="form-control" name="id" id="id" disabled>
                                    </div>
                                </div>
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
                                        <label for="name">Pessoa</label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Nome"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cpf">CPF</label>
                                        <input type="text" class="form-control" name="cpf" id="cpf"
                                            placeholder="000.000.000-00">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="phone">Telefone:</label>
                                        <input type="text" class="form-control" name="phone" id="phone"
                                            placeholder="41 9.99999-9999">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <select class="form-control select2" name="cd_email" id="cd_email"
                                            style="width: 100%" required>
                                            <option value="0" selected>Selecione</option>
                                            @foreach ($emails as $email)
                                                <option value="{{ $email->id }}">{{ $email->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="endereco">Endereço</label>
                                        <input type="text" class="form-control" name="endereco" id="endereco"
                                            placeholder="Rua / Avenida">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="numero">Nº</label>
                                        <input type="text" class="form-control" name="numero" id="numero"
                                            placeholder="Número">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-save">Criar</button>
                            <button type="button" class="btn btn-warning btn-update">Editar</button>

                            <button type="button" class="btn btn-danger btn-cancel" data-dismiss="modal">Cancelar</button>
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
    <script src="{{ asset('js/pessoa/scripts.js') }}"></script>

@endsection
