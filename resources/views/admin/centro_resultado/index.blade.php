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
                        <h3 class="box-title">Cadastros</h3>
                        <div style="float: right; font-weight: 900;">
                            <button class="btn btn-info btn-sm add" type="button" data-toggle="modal"
                                data-target="#SubGrupoCentroResultadoModal" data-backdrop="static" data-keyboard="false">
                                Adicionar Subgrupo
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- .box-body -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table compact" id="table-item" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Emp</th>
                                        <th>Cód Centro Resultado</th>
                                        <th>Ds Centro Resultado</th>
                                        <th>Cód Sub Grupo</th>
                                        <th>Ds Sub Grupo</th>
                                        <th>Orçamento</th>
                                        <th>Alterado</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-header -->
                </div>
                <!-- /.box -->
            </div>



            {{-- Edit Item Centro de Resultado --}}
            <div class="modal" id="CreateItemCentroResultadoModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <button type="button" class="close btn-cancel" data-dismiss="modal" data-keyboard="false"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Editar Centro Resultado</h4>
                        </div>
                        <!-- Modal body -->
                        <form id="formItem">
                            <div class="modal-body">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cd_centroresultado">Cd. Centro Resultado</label>
                                        <input type="text" class="form-control" name="cd_centroresultado"
                                            id="cd_centroresultado" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cd_centroresultado">Ds. Centro Resultado</label>
                                        <input type="text" class="form-control" name="ds_centroresultado"
                                            id="ds_centroresultado" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cd_empresa_desp">Empresa</label>
                                        <select class="form-control select2" name="cd_empresa_desp" id="cd_empresa_desp"
                                            style="width: 100%">
                                            <option selected>Selecione</option>
                                            @foreach ($empresas as $empresa)
                                                <option value="{{ $empresa->cd_empresa_new }}">{{ $empresa->ds_local }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="subgrupo">Subgrupo Centro Resultado</label>
                                        <select class="form-control select2" name="cd_subgrupo" id="cd_subgrupo"
                                            style="width: 100%">
                                            <option selected>Selecione</option>
                                            @foreach ($sub as $s)
                                                <option value="{{ $s->id }}">
                                                    {{ $s->ds_subgrupo . ' - ' . $s->ds_tipo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="valor">Orçamento</label>
                                        <input type="number" class="form-control" name="valor" id="valor">
                                    </div>
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-warning btn-update">Editar</button>
                                <button type="button" class="btn btn-danger btn-cancel"
                                    data-dismiss="modal">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Subgrupo Centro de Resultado --}}
            <div class="modal" id="SubGrupoCentroResultadoModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <button type="button" class="close btn-cancel" data-dismiss="modal" data-keyboard="false"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Subgrupo Centro Resultado<button
                                    class=" btn btnAdd btn-success btn-xs">Adicionar</button></h4>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <form id="formSubgrupo">
                                <div class="col-md-12" id="InputIdSubgrupo">
                                    <div class="form-group">
                                        <label for="ds_subgrupo">Id Subgrupo</label>
                                        <input type="text" class="form-control" name="id_subgrupo" id="id_subgrupo"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="ds_subgrupo">Descrição Subgrupo</label>
                                        <input type="text" class="form-control" name="ds_subgrupo" id="ds_subgrupo"
                                            placeholder="Ex: BONIFICAÇÃO A FUNCIONARIOS">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cd_subgrupo">Grupo Resultado</label>
                                        <select class="form-control" name="cd_grupo" id="cd_grupo" style="width: 100%">
                                            @foreach ($grupo as $g)
                                                <option value="{{ $g->id }}">
                                                    {{ $g->ds_grupo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="ds_tipo">Classificação</label>
                                        <select class="form-control" name="ds_tipo" id="ds_tipo" style="width: 100%">
                                            @foreach ($ds_tipo as $d)
                                                <option value="{{ $d->ds_tipo }}">
                                                    {{ $d->ds_tipo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="number" style="display:none" value="0" name="update"
                                    class="update">
                                <div class="pull-right ">
                                    <button type="submit" class="btn btn-primary btn-save"
                                        id="btnSaveSubgrupo">Salvar</button>
                                    <button type="submit" class="btn btn-warning btn-update"
                                        id="btnEditSubgrupo">Editar</button>
                                    <button type="button" class="btn btn-danger btn-cancel"
                                        data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                            <div class="col-md-12" style="padding-top: 30px;">
                                <div class="table-responsive no-padding">
                                    <table class="table display compact" id="table-subgrupo" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Cod.</th>
                                                <th>Ds. Subgrupo</th>
                                                <th>Ds. Subgrupo</th>
                                                <th>Cod. Grupo</th>
                                                <th>Ds Grupo</th>
                                                <th>Classificação</th>
                                                <th>Cod. DRE</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>


                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">

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
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script>
        $(document).ready(function() {
            var
                btnAdd = $('.btnAdd'),
                btnUpdate = $('#btnEditSubgrupo'),
                btnSave = $('#btnSaveSubgrupo'),
                InputIdSubgrupo = $('#InputIdSubgrupo');
            btnCancel = $('.btn-cancel');
            btnUpdate.hide();
            btnAdd.hide();
            InputIdSubgrupo.hide();

            var dataTable = $('#table-item').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 5,
                dom: 'Blfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                // scrollX: true,
                //"order": [[0, "asc"]],
                "pageLength": 50,
                ajax: "{{ route('ajax-item-centro-resultado.list') }}",
                columns: [{
                        data: 'cd_empresa_desp',
                        name: 'cd_empresa_desp'
                    },
                    {
                        data: 'cd_centroresultado',
                        name: 'cd_centroresultado'
                    },
                    {
                        data: 'ds_centroresultado',
                        name: 'ds_centroresultado'
                    },
                    {
                        data: 'cd_subgrupo',
                        name: 'cd_subgrupo',
                        visible: false
                    },
                    {
                        data: 'ds_subgrupo',
                        name: 'ds_subgrupo'
                    },
                    {
                        data: 'orcamento',
                        name: 'orcamento'
                    },
                    {
                        data: 'alterado',
                        name: 'alterado'
                    },
                    {
                        data: 'Actions',
                        name: 'Actions',
                        orderable: false,
                        serachable: false,
                        sClass: 'text-center'
                    },
                ],
                columnDefs: [{
                    targets: 0,

                    width: '1%'
                }, {
                    targets: 5,
                    render: $.fn.dataTable.render.number('.', ',', 2),
                    width: '1%'
                }],

            });

            var CreateItemCentroResultadoModal = $('#CreateItemCentroResultadoModal');
            var SubGrupoCentroResultadoModal = $('#SubGrupoCentroResultadoModal');

            // Edit Item Centro Resultado
            $(document).on('click', '.btn-edit', function() { // btnUpdate.show();
                CreateItemCentroResultadoModal.find('.modal-title').text('Atualizar Centro de Resultado');
                var rowData = dataTable.row($(this).parents('tr')).data();

                console.log(rowData);
                $('#cd_centroresultado').val(rowData.cd_centroresultado);
                $('#ds_centroresultado').val(rowData.ds_centroresultado);
                $('#cd_empresa_desp').val(rowData.cd_empresa_desp).trigger('change');
                $('#cd_subgrupo').val(rowData.cd_subgrupo).trigger('change');
                $('#valor').val(rowData.orcamento);
                CreateItemCentroResultadoModal.modal();
            })

            $('#formItem').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('ajax-item-centro-resultado.edit') }}",
                    method: 'POST',
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        cd_centroresultado: $('#cd_centroresultado').val(),
                        cd_subgrupo: $('#cd_subgrupo').val(),
                        cd_empresa_desp: $('#cd_empresa_desp').val(),
                        orcamento: $('#valor').val()
                    },
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: function(response) {
                        $("#loading").addClass('hidden');
                        if (response.success) {
                            CreateItemCentroResultadoModal.modal('toggle');
                            $('#table-item').DataTable().ajax.reload();
                            msgToastr(response.success, 'success');
                        } else {
                            msgToastr(response.error, 'warning');
                        }
                    }
                });
            });
            $('#SubGrupoCentroResultadoModal').on('shown.bs.modal', function() {
                var dataTableSubgrupo = InitDataTableSub();
            });

            $('#formSubgrupo').on('submit', function(e) {
                e.preventDefault();
                if ($('#ds_subgrupo').val() == "") {
                    $('#ds_subgrupo').attr('title', 'Descrição deve ser preenchida!').tooltip(
                        'show');
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('ajax-sub-centro-resultado.store') }}",
                    data: {
                        _token: $("[name=csrf-token]").attr("content"),
                        ds_subgrupo: $('#ds_subgrupo').val(),
                        cd_grupo: $('#cd_grupo').val(),
                        ds_tipo: $('#ds_tipo').val(),
                        update: $('.update').val(),
                        id: $('#id_subgrupo').val()
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#table-subgrupo').DataTable().clear().destroy();
                            var dataTableSubgrupo = InitDataTableSub();
                            $('.update').val(0);
                            $('#ds_subgrupo').val('');
                            $('#cd_grupo').val(0).trigger('change');
                            $('#ds_tipo').val('SEM TIPO').trigger('change');
                            msgToastr(response.success, 'success');
                        } else {
                            msgToastr(response.error, 'warning');
                        }
                    }
                });
            });

            $(document).on('click', '.EditItemSubgrupo', function(e) {

                btnSave.hide();
                btnUpdate.show();
                btnAdd.show();
                InputIdSubgrupo.show();
                var id = $(this).data('id');
                var ds_subgrupo = $(this).data('ds_subgrupo');
                var cd_grupo = $(this).data('cd_grupo');
                var ds_tipo = $(this).data('ds_tipo');
                var cd_dre = $(this).data('cd_dre');
                $('.update').val(1);

                $('#id_subgrupo').val(id);
                $('#ds_subgrupo').val(ds_subgrupo);
                $('#cd_grupo').val(cd_grupo).trigger('change');
                $('#ds_tipo').val(ds_tipo).trigger('change');
            });

            btnAdd.on('click', function(e) {
                btnUpdate.hide();
                btnAdd.hide();
                btnSave.show();
                InputIdSubgrupo.hide();
                $('.update').val(0);
                $('#ds_subgrupo').val('');
                $('#cd_grupo').val(0).trigger('change');
                $('#ds_tipo').val('SEM TIPO').trigger('change');
            });
            btnCancel.on('click', function(e) {
                // Verifique se a tabela já está inicializada como DataTable
                if ($.fn.DataTable.isDataTable('#table-subgrupo')) {
                    // Destrua a instância existente
                    $('#table-subgrupo').DataTable().clear().destroy();                   

                }
                // $('#table-item').DataTable().ajax.reload();
            })
            $(document).on('click', '.DeleteItemSubgrupo', function(e) {
                var id = ($(this).data('id'));
                toastr.warning(
                    "<button type='button' id='confirmationButtonYes' class='btn btn-success clear'>Sim</button>" +
                    "<button type='button' id='confirmationButtonNo' class='btn btn-primary clear'>Não</button>",
                    'Realmente deseja deletar o item?', {
                        closeButton: false,
                        allowHtml: true,
                        progressBar: false,
                        timeOut: 0,
                        positionClass: "toast-top-center",
                        onShown: function(toast) {
                            $("#confirmationButtonYes").click(function() {
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('ajax-sub-centro-resultado.delete') }}",
                                    data: {
                                        _token: $("[name=csrf-token]").attr(
                                            "content"),
                                        id: id
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            msgToastr(response.success,
                                                'success');
                                            $('#table-subgrupo').DataTable()
                                                .clear().destroy();
                                            var dataTableSubgrupo =
                                                InitDataTableSub();
                                        } else {
                                            msgToastr(response.error,
                                                'warning');
                                        }
                                    }
                                });
                            })
                        }
                    });
            });

            function InitDataTableSub() {
                var dataTableSubgrupo = $('#table-subgrupo').DataTable({
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                    },
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    pageLength: 5,
                    // scrollX: true,
                    //"order": [[0, "asc"]],
                    "pageLength": 10,
                    ajax: "{{ route('ajax-sub-centro-resultado.list') }}",
                    columns: [{
                            data: 'id',
                            name: 'id',
                            visible: false
                        },
                        {
                            data: 'subgrupo',
                            name: 'subgrupo',
                            visible: false
                        },
                        {
                            data: 'ds_subgrupo',
                            name: 'ds_subgrupo'
                        },
                        {
                            data: 'cd_grupo',
                            name: 'cd_grupo',
                            visible: false
                        },
                        {
                            data: 'ds_grupo',
                            name: 'ds_grupo'
                        },
                        {
                            data: 'ds_tipo',
                            name: 'ds_tipo'
                        },
                        {
                            data: 'cd_dre',
                            name: 'cd_dre'
                        },
                        {
                            data: 'Actions',
                            name: 'Actions',
                            orderable: false,
                            serachable: false,
                            sClass: 'text-center'
                        },
                    ]
                });
            }

        });
    </script>
@endsection
