@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-5">
                @includeIf('admin.master.messages')
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $title }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form
                        action="{{ isset($user_id) ? route('update-connect-people-user') : route('create-connect-people-user.do') }}"
                        method="post">
                        @csrf
                        <div class="box-body">
                            @if (isset($user_id))
                                <input type="hidden" name="id" value="{{ $user_id->id }}">
                            @endif
                            <div class="form-group">
                                <select class="form-control select2" name='id_user' id="user" style="width: 100%;" required>
                                    <option value="0">Selecione um usuário</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            @if (isset($user_id)) {{ $user->id == $user_id->id_user ? 'selected' : '' }} @endif>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label>CPF ou CNPJ</label>
                                    <input type="text" class="form-control" id="cpf_cnpj" name="cpfcnpj"
                                        placeholder="000.000.000-00 ou 00.000.000/00000-00"
                                        value="{{ isset($user_id) ? $user_id->nr_cnpjcpf : '' }}" required>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            @if (isset($user_id))
                                <button type="submit" class="btn btn-warning btn-create">Atualizar</button>
                                <a href="" class="btn btn-danger">Excluir</a>
                            @else
                                <button type="submit" class="btn btn-primary btn-create">Cadastrar</button>
                            @endif
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-7">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Usuario / Pessoas Associados</h3>
                        <div class="box-tools pull-right">
                            <a href="{{ route('connect-people-user') }}" class="btn btn-success">
                                Adicionar
                            </a>

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered" style="width: 100%" id="table-people-user">
                            <thead>
                                <th>Cód.</th>
                                <th>Pessoa</th>
                                <th>Cnpj/CPF</th>
                                <th>Acões</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    @includeIf('admin.master.datatables')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.btn-create').click(function() {
                var user = $('#user').val();
                if (user == 0) {
                    msg('Selecione um usúario', 'alert-warning')
                    return false;
                }
            });
            $('#table-people-user').DataTable({
                responsive: true,
                pagingType: "simple",
                language: {
                    url: "http://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                ajax: {
                    url: "{{ route('get-people-user-all') }}",
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'nr_cnpjcpf',
                        name: 'nr_cnpjcpf'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        serachable: false,
                        sClass: 'text-center'
                    }
                ]
            });

        });
    </script>
@endsection
