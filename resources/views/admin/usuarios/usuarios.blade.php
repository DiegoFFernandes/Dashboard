@extends('admin.master.master')

@section('content')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Adicionar</h3>
                </div>
                <!-- /.box-header -->

                <!-- form start -->
                <form role="form" method="post"
                    action="{{isset($user_id) ? route('admin.usuarios.update') : route('admin.usuarios.create')}}">
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
                        <div class="form-group">
                            @if(isset($user_id->name))
                            <input type="hidden" name="id" value="{{$user_id->id}}">
                            @endif
                            <label for="name">Nome:</label>
                            <input type="name" name='name' class="form-control" id="name" placeholder="Nome usuario"
                                value="{{isset($user_id->name) ? $user_id->name : ''}}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name='email' class="form-control" id="email" placeholder="Email"
                                value="{{isset($user_id->email) ? $user_id->email : ''}}">
                        </div>
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="password" name='password' class="form-control" id="password"
                                placeholder="Digite uma senha"
                                value="{{isset($user_id->password) ? $user_id->password : ''}}">
                        </div>
                        <!-- select -->
                        <div class="form-group">
                            <label>Empresa Principal</label>
                            <select class="form-control" name="empresa">
                                @if(isset($user_id))
                                @foreach($empresas as $empresa)
                                @if($user_id->empresa == $empresa->CD_EMPRESA)
                                <option value="{{$empresa->CD_EMPRESA}}">{{$empresa->NM_EMPRESA}}</option>
                                @endif
                                @endforeach
                                @foreach($empresas as $empresa)
                                @if($user_id->empresa <> $empresa->CD_EMPRESA)
                                <option value="{{$empresa->CD_EMPRESA}}">{{$empresa->NM_EMPRESA}}</option>
                                @endif
                                @endforeach
                                @else
                                @foreach($empresas as $empresa)
                                <option value="{{$empresa->CD_EMPRESA}}">{{$empresa->NM_EMPRESA}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        @if(isset($user_id))
                        <button type="submit" class="btn btn-warning">Atualizar</button>
                        <a href="{{route('admin.usuarios.delete', ['id' => $user_id->id])}}"
                            class="btn btn-danger">Excluir</a>
                        @else
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                        @endif
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Usuarios Cadastrados</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">Id</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Empresa</th>
                                <th>Editar</th>
                                <th>Excluir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->empresa}}</td>
                                <td>
                                    <a href="{{route('admin.usuarios.edit', ['id' => $user->id])}}"
                                        class="fa fa-pencil"></a>
                                </td>
                                <td>
                                    <a href="{{route('admin.usuarios.delete', ['id' => $user->id])}}"
                                        class="fa fa-trash"></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</section>
<!-- /.content -->
@endsection