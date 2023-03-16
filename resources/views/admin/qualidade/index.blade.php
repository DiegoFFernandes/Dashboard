@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4 pull-right">
                @includeIf('admin.master.messages')
            </div>
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#criados" data-toggle="tab" aria-expanded="true">Aguardando Analise</a>
                        </li>
                        <li class="">
                            <a href="#pendentes" data-toggle="tab" aria-expanded="false">Pendentes</a>
                        </li>
                        <li class="">
                            <a href="#recusados" data-toggle="tab" aria-expanded="false">Recusados</a>
                        </li>
                        <li class="">
                            <a href="#liberados" data-toggle="tab" aria-expanded="false">Liberados</a>
                        </li>

                        <li class="pull-right">
                            <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"
                                data-toggle="modal" data-target="#modal-procedimento">
                                Incluir Procedimento
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="criados">
                            <table id="table-procedimento" class="table">
                                <thead>
                                    <th>Cód.</th>
                                    <th>Setor</th>
                                    <th>Titulo</th>
                                    <th>Descrição</th>
                                    <th>Status</th>
                                    <th>Criador</th>
                                    <th>Ações</th>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="pendentes">
                            <table id="table-procedimento-pendentes" class="table">
                                <thead>
                                    <th>Cód.</th>
                                    <th>Setor</th>
                                    <th>Titulo</th>
                                    <th>Descrição</th>
                                    <th>Status</th>
                                    <th>Criador</th>
                                    <th>Ações</th>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="recusados">
                            <table id="table-procedimento-recusados" class="table">
                                <thead>
                                    <th>Cód.</th>
                                    <th>Setor</th>
                                    <th>Titulo</th>
                                    <th>Descrição</th>
                                    <th>Status</th>
                                    <th>Criador</th>
                                    <th>Ações</th>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="liberados">
                            <table id="table-procedimento-liberados" class="table">
                                <thead>
                                    <th>Cód.</th>
                                    <th>Setor</th>
                                    <th>Titulo</th>
                                    <th>Descrição</th>
                                    <th>Status</th>
                                    <th>Criador</th>
                                    <th>Ações</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Modal Criar Procedimento --}}
            <div class="modal modal-procedimento fade" id="modal-procedimento">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Procedimento</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('procedimento.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="arquivo">Setor designado</label>
                                        <select name="setor" id="setor" class="form-control select2" style="width: 100%">
                                            @foreach ($setors as $s)
                                                <option value="{{ $s->id }}">
                                                    {{ $s->nm_setor . ' - ' . $s->nm_area }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="users">Usuarios Responsaveis</label>
                                        <select class="form-control users" id="" name="users[]" multiple="multiple"
                                            data-placeholder="Selecione os usuarios" style="width: 100%;">
                                            @foreach ($users as $u)
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="arquivo">Titulo</label>
                                        <input type="text" class="form-control" name='title'
                                            placeholder="Titulo Procedimento" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="arquivo">Descrição</label>
                                        <textarea name="description" class="form-control" rows="4" cols="50"
                                            placeholder="Descreva um pequeno resumo do Procedimento"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="arquivo">Buscar arquivo</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-pdf-o"></i></span>
                                        <input type="file" name="file" class="form-control"
                                            placeholder="Clique/Arraste e Solte aqui" accept="application/pdf" required>

                                    </div>
                                    <p class="help-block">Somente arquivos em PDF.</p>
                                </div>
                                <div class="col-md-12" align="center" style="padding-top: 24px">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fa fa-download"></i> Enviar Procedimento</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">


                        </div>
                    </div>
                </div>
            </div>
            {{-- Modal Editar Procedimento --}}
            <div class="modal modal-procedimento fade" id="modal-edit-procedimento">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Editar Procedimento</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('procedimento.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="op-table" name="op_table">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="id_procedimento">Cód.</label>
                                        <input class="form-control" type="number" id="id_procedimento"
                                            name="id_procedimento" readonly>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="arquivo">Setor designado</label>
                                        <select name="setor" id="edit_setor" class="form-control select2"
                                            style="width: 100%">
                                            @foreach ($setors as $s)
                                                <option value="{{ $s->id }}">
                                                    {{ $s->nm_setor . ' - ' . $s->nm_area }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="users">Usuarios Responsaveis</label>
                                        <select class="form-control users" id="" name="users[]" multiple="multiple"
                                            data-placeholder="Selecione os usuarios" style="width: 100%;" required>
                                            @foreach ($users as $u)
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="arquivo">Titulo</label>
                                        <input id="title" type="text" class="form-control" name='title'
                                            placeholder="Titulo Procedimento" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="arquivo">Descrição</label>
                                        <textarea id="description" name="description" class="form-control" rows="4" cols="50"
                                            placeholder="Descreva um pequeno resumo do Procedimento"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="arquivo">Buscar arquivos</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-pdf-o"></i></span>
                                        <input type="file" name="file" class="form-control"
                                            placeholder="Clique/Arraste e Solte aqui" accept="application/pdf">

                                    </div>
                                    <p class="help-block">Somente arquivos em PDF, caso não selecione nenhum, sistema
                                        irá manter o mesmo arquivo.</p>
                                </div>
                                <div class="col-md-12" align="center" style="padding-top: 24px">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fa fa-download"></i> Atualizar Procedimento</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
            {{-- Modal View Reason Procedimento --}}
            <div class="modal modal-procedimento fade" id="modal-reason-procedimento">
                <div class="modal-dialog" style="">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Aprovador/Reprovados</h4>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered" id="table-motivo-reprovados">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Cód. Criador</th>
                                        <th>Criador</th>
                                        <th>Cód. Aprovador</th>
                                        <th>Aprovador</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>
            {{-- Modal View Chat Reason Recuse Procedimento --}}
            <div class="modal modal-procedimento fade" id="modal-recuse-procedimento">
                <div class="modal-dialog" style="">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title"><i class="fa fa-comments-o"></i> Chat - Procedimento</h4>
                        </div>
                        <div class="modal-body">
                            <div class="direct-chat direct-chat-warning">
                                <div class="box-body" id="box-chat">

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>
            {{-- Modal View Recuse Procedimento --}}
            <div class="modal modal-outstanding-procedimento fade" id="modal-outstanding-procedimento">
                <div class="modal-dialog" style="">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title"><i class="fa fa-comments-o"></i>Procedimento - Aguardando Aprovação
                            </h4>
                        </div>
                        <div class="modal-body">
                            <table class="table" id="table-procedimentos-outstanding">
                                <thead>
                                    <tr>
                                        <td>Cód.</td>
                                        <td>Usuario</td>
                                        <td>Status</td>
                                        <td>Atualizado em</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
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
            $(".users").select2();
            var dataTable = initTable('table-procedimento', 'A');

            $('body').on('click', '#getEditProcedimento', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var table = $(this).data('table');
                var rowData = $('#' + table).DataTable().row($(this).parents('tr')).data();
                if (rowData == undefined) {
                    var selected_row = $(this).parents('tr');
                    if (selected_row.hasClass('child')) {
                        selected_row = selected_row.prev();
                    }
                    rowData = $('#table-procedimento').DataTable().row(selected_row).data();
                }
                $.ajax({
                    url: "{{ route('procedimento.edit') }}",
                    method: 'GET',
                    data: {
                        id: id,
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        var users_aprov = response;
                        $('#id_procedimento').val(rowData.id);
                        $('#edit_setor').val(rowData.id_setor).trigger('change');
                        $('#title').val(rowData.title);
                        $('#description').val(rowData.description);
                        $('.users').val(users_aprov).trigger('change');
                        $('#op-table').val(table);
                        $('#modal-edit-procedimento').modal('show');
                    }
                });
            });
            $('body').on('click', '#getDeleteId', function(e) {
                e.preventDefault();
                deleteId = $(this).data('id');
                if (!confirm('Deseja realmente excluir o procedimento ' + deleteId + ' ?')) return;
                $.ajax({
                    url: "delete",
                    method: 'DELETE',
                    data: {
                        "id": deleteId,
                        "_token": $("[name=csrf-token]").attr("content"),
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        if (result.alert) {
                            $("#loading").addClass('hidden');
                            msg(result.alert, 'alert-warning', 'fa-warning');
                        } else {
                            $("#loading").addClass('hidden');
                            msg(result.success, 'alert-success', 'fa fa-check');
                            $('#table-procedimento').DataTable().ajax.reload();
                        }
                    }
                });

            });
            $('body').on('click', '#getViewReason', function(e) {
                e.preventDefault();
                id = $(this).data('id');
                $("#table-motivo-reprovados").DataTable().destroy();
                $("#table-motivo-reprovados").DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    pagingType: "simple",
                    processing: false,
                    responsive: true,
                    serverSide: false,
                    autoWidth: false,
                    order: [
                        [0, "desc"]
                    ],
                    "pageLength": 10,
                    ajax: {
                        url: "{{ route('procedimento.reprovados') }}",
                        data: {
                            id: id
                        }
                    },
                    columns: [{
                            data: 'id_procedimento',
                            name: 'id_procedimento'
                        },
                        {
                            data: 'id_user_create',
                            name: 'id_user_create',
                            visible: false
                        },
                        {
                            data: 'nm_create',
                            name: 'nm_create'
                        },
                        {
                            data: 'id_user_approver',
                            name: 'id_user_approver',
                            visible: false
                        },
                        {
                            data: 'nm_approver',
                            name: 'nm_approver'
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
                $('#modal-reason-procedimento').modal('show');
            });
            $('body').on('click', '#view-motivo-procedimento', function(e) {
                e.preventDefault();
                var rowData = $('#table-motivo-reprovados').DataTable().row($(this).parents('tr')).data();
                $('.description').val('');
                $.ajax({
                    type: "GET",
                    url: "{{ route('procedimento.chat') }}",
                    data: {
                        data: rowData
                    },
                    success: function(response) {
                        $('.direct-chat-messages').remove();
                        $('#id').val(rowData.id_procedimento);
                        $('#user_approver').val(rowData.id_user_approver);
                        $('#user_created').val(rowData.id_user_create);
                        $('#box-chat').append(response.html);
                    }
                });
                $('#modal-recuse-procedimento').modal('show');
            });
            $('body').on('click', '#btnPublish', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('procedimento.store.publish') }}",
                    method: 'GET',
                    data: {
                        id: id,
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        if (response.alert) {
                            $("#loading").addClass('hidden');
                            msg(response.alert, 'alert-warning', 'fa-warning');
                        } else {
                            $("#loading").addClass('hidden');
                            msg(response.success, 'alert-success', 'fa fa-check');
                            $('#table-procedimento-liberados').DataTable().ajax.reload();
                        }
                    }
                });
            });
            $('body').on('click', '#btnReleaseNotApprover', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('procedimento.store.noapprover') }}",
                    method: 'GET',
                    data: {
                        id: id,
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        if (response.alert) {
                            $("#loading").addClass('hidden');
                            msg(response.alert, 'alert-warning', 'fa-warning');
                        } else {
                            $("#loading").addClass('hidden');
                            msg(response.success, 'alert-success', 'fa fa-check');
                            $('#table-procedimento').DataTable().ajax.reload();
                        }
                    }
                });
            });
            $('body').on('click', '#btnOutstandind', function(e) {
                $("#table-procedimentos-outstanding").DataTable().destroy();
                e.preventDefault();
                var id = $(this).data('id');
                $('#modal-outstanding-procedimento').modal('show');
                $("#table-procedimentos-outstanding").DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    pagingType: "simple",
                    processing: false,
                    responsive: true,
                    serverSide: false,
                    autoWidth: false,
                    order: [
                        [0, "desc"]
                    ],
                    "pageLength": 10,
                    ajax: {
                        url: "{{ route('procedimento.outstanding') }}",
                        data: {
                            id: id
                        }
                    },
                    columns: [{
                            data: 'id_procedimento',
                            name: 'id_procedimento'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at'
                        }
                    ],
                    columnDefs: [{
                        width: '1%',
                        targets: 0
                    }],
                });
            });
            //Cliques nas tabs
            $('.nav-tabs a[href="#recusados"]').on('click', function() {
                $('#table-procedimento-recusados').DataTable().destroy();
                initTable('table-procedimento-recusados', 'R');
            });
            $('.nav-tabs a[href="#pendentes"]').on('click', function() {
                $('#table-procedimento-pendentes').DataTable().destroy();
                initTable('table-procedimento-pendentes', 'P');
            });
            $('.nav-tabs a[href="#liberados"]').on('click', function() {
                $('#table-procedimento-liberados').DataTable().destroy();
                initTable('table-procedimento-liberados', 'L');
            });
            $('body').on('click', '#btnCancelPublish', function(e) {
                var deleteId = $(this).data('id');
                $.ajax({
                    url: "{{ route('procedimento.delete-publish') }}",
                    method: 'DELETE',
                    data: {
                        "id": deleteId,
                        "_token": $("[name=csrf-token]").attr("content"),
                    },
                    beforeSend: function() {
                        // $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        if (result.alert) {
                            $("#loading").addClass('hidden');
                            msg(result.success, 'alert-warning', 'fa-warning');
                        } else {
                            $("#loading").addClass('hidden');
                            msg(result.success, 'alert-success', 'fa fa-check');
                            $('#table-procedimento-liberados').DataTable().ajax.reload();
                        }
                    }
                });
            });

            function initTable(tableId, data) {
                $("#" + tableId).DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    pagingType: "simple",
                    processing: false,
                    responsive: true,
                    serverSide: false,
                    autoWidth: false,
                    order: [
                        [0, "desc"]
                    ],
                    "pageLength": 10,
                    ajax: {
                        url: "{{ route('procedimento.get-procedimento') }}",
                        data: {
                            status: data
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'nm_setor',
                            name: 'nm_setor'
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'Actions',
                            name: 'Actions',
                            orderable: false,
                            serachable: false,
                            sClass: 'text-center'
                        }
                    ],
                    columnDefs: [{
                            width: '1%',
                            targets: 0
                        },
                        {
                            width: '20%',
                            targets: 6
                        }
                    ],
                });
            }

        });
    </script>
@endsection
