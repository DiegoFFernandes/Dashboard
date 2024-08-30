@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                @if (session('status'))
                    <!-- alert -->
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-check"></i>{{ session('status') }}
                    </div>
                    <!-- /alert -->
                @endif
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <a href="{{ route('admin.usuarios.permission.create') }}" class="btn btn-success">Criar novo</a>
                        <a href="{{ route('permission-powerbi.update') }}" class="btn btn-primary" id="updatePermissionPowerBI">Permissão PowerBI</a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-sm" id="table-permissions" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Empresa</th>
                                    <th>Permissões</th>
                                    <th>Acões</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->empresa }}</td>
                                        <td>
                                            @if (!empty($user->getPermissionNames()))
                                                @foreach ($user->getPermissionNames() as $v)
                                                    <span class="badge badge-info">{{ $v }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-info"
                                                href="{{ route('admin.usuarios.permission.edit', $user->id) }}">Editar</a>
                                            <a class="btn btn-danger"
                                                href="{{ route('admin.usuarios.permission.delete', $user->id) }}">Deletar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    @includeIf('admin.master.datatables')
    <script type="text/javascript">
        $('#table-permissions').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
            },
            responsive: true,
            "order": [
                [1, "asc"]
            ],
        });
        
    </script>
@endsection
