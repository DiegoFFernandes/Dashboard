@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $title_page }}</h3>
                    </div>
                    <form role="form" method="post" action="{{ route('marca-veiculo.salvar') }}">
                        @csrf
                        <div class="box-body">
                            @if ($errors->all())
                                @foreach ($errors->all() as $error)
                                    <!-- alert -->
                                    <div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">&times;</button>
                                        <i class="icon fa fa-check"></i>{{ $error }}
                                    </div>
                                    <!-- /alert -->
                                @endforeach
                            @endif
                            @if (session('warning'))
                                <!-- alert -->
                                <div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-check"></i>{{ session('warning') }}
                                </div>
                                <!-- /alert -->
                            @endif
                            @if (session('status'))
                                <!-- alert -->
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-check"></i>{{ session('status') }}
                                </div>
                                <!-- /alert -->
                            @endif
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Descrição Marca</label>
                                        <input type="text" class="form-control" name="descricao"
                                            placeholder="Descrição Marca" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Criar</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Marcas Cadastradas</h3>
                    </div>
                    <!-- /.box-header id="mveiculodatatable"  -->
                    <div class="box-body">
                        <table class="table table-bordered display" id="mveiculodatatable">
                            <thead>
                                <tr>
                                    <th style="width: 10px">Id</th>
                                    <th>Marca</th>
                                    <th>Editar</th>
                                    <th>Excluir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($marcas as $m)
                                    <tr>
                                        <td>{{ $m->id }}</td>
                                        <td>{{ $m->descricao }}</td>
                                        <td>
                                            <a href="" class="fa fa-pencil btn-edit" data-toggle="modal"
                                                data-target="#editarMarcaVeiculo" data-id="{{ $m->id }}"></a>
                                        </td>
                                        <td>
                                            <a href="" class="fa fa-trash" data-toggle="modal" data-target="#delete"
                                                data-id="{{ $m->id }}"></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>

        <!-- Modal Deletar -->
        <form action="{{ route('marca-veiculo.delete') }}" method="post">
            @csrf
            <!-- @method('DELETE') -->
            <div class="modal fade" id="delete">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Confirmação</h4>
                        </div>
                        <div class="modal-body">
                            <p>Você tem certeza da exclusão?</p>
                            <input type="hidden" name="cd_marca" id="cd_delete" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Sim, Excluir!</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </form>
        <!-- Fim Modal Deletar -->

        <!-- Modal Editar -->
        <form action="{{ route('marca-veiculo.update') }}" method="post">
            @csrf
            <div class="modal fade" id="editarMarcaVeiculo">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Editar Marca</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="id" id="id">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Descrição Marca</label>
                                        <input type="text" class="form-control" id="descricao" name="descricao"
                                            value="Descrição Marca">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">Editar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </form>
        <!-- /Fim Modal Editar -->

    </section>
    <!-- /.content -->
@endsection

@section('scripts')
@includeIf('admin.master.datatables')
<!-- My Scripts -->
<script src="{{ asset('js/marca-veiculo/scripts.js') }}"></script>
@endsection
