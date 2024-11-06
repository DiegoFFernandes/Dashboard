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
                            <a href="#criados" data-toggle="tab" aria-expanded="true">Criar</a>
                        </li>
                        <li class="">
                            <a href="#liberados" data-toggle="tab" aria-expanded="false">Publicar</a>
                        </li>

                        <li class="pull-right">
                            <button style="float: right; font-weight: 900;" class="btn btn-info btn-sm" type="button"
                                data-toggle="modal" data-target="#modal-sgi">
                                Incluir Documentos
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="criados">
                            <table id="table-sgi" class="table">
                                <thead>
                                    <th>Cód.</th>
                                    <th>Unidade</th>
                                    <th>Nome Documento</th>
                                    <th>Descrição</th>
                                    <th>Validade</th>
                                    <th>Status</th>
                                    <th>Ação</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Modal Criar SGI --}}
            <div class="modal modal-procedimento fade" id="modal-sgi">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Procedimento</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('sgi.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="arquivo">Unidade Designada</label>
                                        <select name="unidade" id="unidade" class="form-control select2"
                                            style="width: 100%">
                                            @foreach ($unidades as $u)
                                                <option value="{{ $u->cd_empresa_new }}">
                                                    {{ $u->ds_local }}
                                                </option>
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
                                    <div class="form-group">
                                        <label for="arquivo">Data Validade</label>
                                        <input type="date" name="dt_validade" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="arquivo">Buscar arquivo</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-pdf-o"></i></span>
                                        <input type="file" name="file" class="form-control"
                                            placeholder="Clique/Arraste e Solte aqui" accept="application/pdf" required>

                                    </div>
                                    <p class="help-block">Somente arquivos em PDF até 10MB.</p>
                                </div>
                                <div class="col-md-12" align="center" style="padding-top: 24px">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fa fa-download"></i> Enviar Documento</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
            {{-- Modal Editar SGI --}}
            <div class="modal modal-procedimento fade" id="modal-edit-sgi">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Editar SGI</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('sgi.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="op-table" name="op_table">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="id_sgi">Cód.</label>
                                        <input class="form-control" type="number" id="id_sgi" name="id_sgi"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="arquivo">Unidade Designada</label>
                                        <select name="unidade" id="edit_unidade" class="form-control select2"
                                            style="width: 100%">
                                            @foreach ($unidades as $u)
                                                <option value="{{ $u->cd_empresa_new }}">
                                                    {{ $u->ds_local }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="arquivo">Titulo</label>
                                        <input id="title" type="text" class="form-control" name='title'
                                            placeholder="Titulo SGI" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="arquivo">Descrição</label>
                                        <textarea id="description" name="description" class="form-control" rows="4" cols="50"
                                            placeholder="Descreva um pequeno resumo do SGI"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="arquivo">Data Validade</label>
                                        <input type="date" id="dt_validade" name="dt_validade" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="arquivo">Buscar arquivos</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-pdf-o"></i></span>
                                        <input type="file" name="file" class="form-control"
                                            placeholder="Clique/Arraste e Solte aqui" accept="application/pdf">

                                    </div>
                                    <p class="help-block">Somente arquivos em PDF até 10MB, caso não selecione nenhum, sistema
                                        irá manter o mesmo arquivo.</p>
                                </div>
                                <div class="col-md-12" align="center" style="padding-top: 24px">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fa fa-download"></i> Atualizar SGI</button>
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
            var dataTable = initTable('table-sgi', 'A');

            $('body').on('click', '#getEditSgi', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var table = $(this).data('table');
                var rowData = $('#' + table).DataTable().row($(this).parents('tr')).data();
                if (rowData == undefined) {
                    var selected_row = $(this).parents('tr');
                    if (selected_row.hasClass('child')) {
                        selected_row = selected_row.prev();
                    }
                    rowData = $('#table-sgi').DataTable().row(selected_row).data();
                }

                $('#id_sgi').val(rowData.id);
                $('#edit_unidade').val(rowData.cd_empresa).trigger('change');
                $('#title').val(rowData.title);
                $('#description').val(rowData.description);
                $('#dt_validade').val(moment(rowData.dt_validade).format("YYYY-DD-MM"));
                $('#modal-edit-sgi').modal('show');

            });
            $('body').on('click', '#getDeleteId', function(e) {
                e.preventDefault();
                deleteId = $(this).data('id');
                if (!confirm('Deseja realmente excluir o procedimento ' + deleteId + ' ?')) return;
                $.ajax({
                    url: "{{ route('sgi.delete') }}",
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
                            msgToastr(result.alert, 'warning');
                        } else {
                            $("#loading").addClass('hidden');
                            msgToastr(result.success, 'success');
                            $('#table-sgi').DataTable().ajax.reload();
                        }
                    }
                });

            });
            $('body').on('click', '#btnPublish', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('sgi.store.publish') }}",
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
                            msgToastr(response.alert, 'warning');
                        } else {
                            $("#loading").addClass('hidden');
                            msgToastr(response.success, 'success');
                            $('#table-sgi').DataTable().ajax.reload();
                        }
                    }
                });
            });

            //Cliques nas tabs

            $('.nav-tabs a[href="#publicar"]').on('click', function() {
                $('#table-procedimento-liberados').DataTable().destroy();
                initTable('table-procedimento-liberados', 'L');
            });

            $('body').on('click', '#btnCancelPublish', function(e) {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('sgi.delete-publish') }}",
                    method: 'POST',
                    data: {
                        "id": id,
                        "_token": $("[name=csrf-token]").attr("content"),
                    },
                    beforeSend: function() {
                        // $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        if (result.alert) {
                            $("#loading").addClass('hidden');
                            msgToastr(result.success, 'danger');
                        } else {
                            $("#loading").addClass('hidden');
                            msgToastr(result.success, 'warning');
                            $('#table-sgi').DataTable().ajax.reload();
                        }
                    }
                });
            });

            $('body').on('click', '#EditFile', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var rowData = $('#table-procedimento-liberados').DataTable().row($(this).parents('tr'))
                    .data();
                if (rowData == undefined) {
                    var selected_row = $(this).parents('tr');
                    if (selected_row.hasClass('child')) {
                        selected_row = selected_row.prev();
                    }
                    rowData = $('#table-procedimento-liberados').DataTable().row(selected_row).data();
                }
                $('.id_sgi').val(rowData.id);
                $('.title').val(rowData.title);
                $('#modal-edit-file').modal('show');
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
                        url: "{{ route('sgi.get-procedimento') }}",
                        data: {
                            status: data
                        }
                    },
                    dom: 'Blfrtip',
                    buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'ds_local',
                            name: 'ds_local'
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
                            data: 'dt_validade',
                            name: 'dt_validade'
                        },

                        {
                            data: 'status',
                            name: 'status'
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
