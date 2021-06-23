@extends('admin.master.master')

@section('content')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{$title_page }}</h3>
                </div>
                <form role="form" method="post" action="{{route('marca-veiculo.salvar')}}">
                    @csrf
                    <div class="box-body">
                        @if($errors->all())
                        @foreach($errors->all() as $error)
                        <!-- alert -->
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fa fa-check"></i>{{$error}}
                        </div>
                        <!-- /alert -->
                        @endforeach
                        @endif
                        @if (session('warning'))
                        <!-- alert -->
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fa fa-check"></i>{{session('warning')}}
                        </div>
                        <!-- /alert -->
                        @endif
                        @if (session('status'))
                        <!-- alert -->
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fa fa-check"></i>{{session('status')}}
                        </div>
                        <!-- /alert -->
                        @endif
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Marca</label>
                                    <input type="number" class="form-control" name="cd_marca"
                                        placeholder="Número para Marca">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Descrição Marca</label>
                                    <input type="text" class="form-control" name="descricao"
                                        placeholder="Descrição Marca">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Frota Veiculo</label>
                                    <select class="form-control select2" name="cd_frotaveiculos" style="width: 100%;"
                                        required>
                                        <option selected="selected" value="">Selecione a Frota</option>
                                        @foreach($frotaveiculos as $f)
                                        <option value="{{$f->id}}">{{$f->descricao}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                        <button type="submit" class="btn btn-warning">Alterar</button>
                        <a href="" class="btn btn-primary">Cancelar</a>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Marcas Cadastradas</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="mveiculodatatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">Id</th>
                                <th>Nº Marca</th>
                                <th>Marca</th>
                                <th style="display: none;"></th>
                                <th>Frota</th>                                
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($marcas as $m)
                            <tr>
                                <td>{{$m->id}}</td>
                                <td class="text-center">{{$m->cd_marca}}</td>
                                <td>{{$m->marca}}</td>
                                <td style="display:none;"> {{$m->cd_frotaveiculos}}</td>
                                <td>{{$m->frota}}</td>                                                                
                                <td>
                                    <a href="" class="fa fa-pencil edit" data-toggle="modal"
                                        data-target="#editarMarcaVeiculo" data-id="{{$m->id}}"></a>
                                </td>
                                <td>
                                    <a href="" class="fa fa-trash" data-toggle="modal" data-target="#deleteMarcaVeiculo"
                                        data-id="{{$m->id}}"></a>
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
    <form action="{{route('marca-veiculo.delete')}}" method="post">
        @csrf
        <!-- @method('DELETE') -->
        <div class="modal fade" id="deleteMarcaVeiculo">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmação</h4>
                    </div>
                    <div class="modal-body">
                        <p>Você tem certeza da exclusão?</p>
                        <input type="hidden" name="cd_marca" id="cd_delete_marca" value="">
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
    <form action="{{route('marca-veiculo.update')}}" method="post">
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
                                    <label>Número Marca</label>
                                    <input type="number" class="form-control" id="edit_marca" name="cd_marca"
                                        value="Número Marca">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descrição Marca</label>
                                    <input type="text" class="form-control" id="dsmarca" name="descricao"
                                        value="Descrição Marca">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Frota Veiculo</label>
                                    <select class="form-control select2" id="frotaveiculos" name="cd_frotaveiculos"
                                        style="width: 100%;" required>
                                        @foreach($frotaveiculos as $f)
                                        <option value="{{$f->id}}">{{$f->descricao}}</option>
                                        @endforeach                                        
                                    </select>
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