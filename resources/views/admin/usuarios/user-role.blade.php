@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $title }}</h3>
                        <div class="box-tools pull-right">
                            <a class="btn btn-default" href="{{ route('admin.usuarios.create') }}">Adicionar usúario</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form
                        action="{{ isset($userId->id) ? route('admin.usuarios.role.edit.do') : route('admin.usuarios.role.create.do') }}"
                        method="post">
                        @csrf
                        <div class="box-body">
                            <input type="hidden" name="id" value="{{ isset($userId->id) ? $userId->id : '' }}">
                            <div class="form-group">
                                <label for="email">Nome:</label>
                                @if (isset($userId->name))
                                    <input type="text" class="form-control" id="email"
                                        value="{{ isset($userId->name) ? $userId->name : '' }}" placeholder="Nome"
                                        {{ isset($userId->name) ? 'disabled' : '' }}>
                                @else
                                    <select class="form-control select2" name='id' style="width: 100%;" required>
                                        <option>Selecione um usuário</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            @if (isset($userId->name))
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email"
                                        value="{{ isset($userId->email) ? $userId->email : '' }}" disabled>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Função</label>
                                    <select class="form-control select2" name="roles[]" multiple="multiple"
                                        data-placeholder="Selecione uma função" style="width: 100%;">
                                        @isset($userRoles)
                                            @foreach ($userRoles as $userRole)
                                                <option selected="selected" value="{{ $userRole }}">{{ $userRole }}
                                                </option>
                                            @endforeach
                                        @endisset
                                        @foreach ($all_roles as $role)
                                            <option value="{{ $role }}">{{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Salvar</button>
                            <a href="{{ route('admin.usuarios.role') }}" class="btn btn-warning">Cancelar</a>
                        </div>
                    </form>
                </div>
                <!-- /.box -->



            </div>
    </section>
    <!-- /.content -->
@endsection
