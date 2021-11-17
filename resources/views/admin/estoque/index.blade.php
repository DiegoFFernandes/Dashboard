@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">{{ $title_page }}</h3>
                    </div>
                    <div class="box-body">
                        <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label for="ds_lote" class="col-sm-2 control-label">Descrição</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="ds_lote"
                                    placeholder="Descrição para o Lote: Banda/Consertos...">
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="submit-lote" class="btn btn-primary pull-right">Criar Lote</button>
                    </div>
                </div>
                {{-- Icon loading --}}
                <div class="hidden" id="loading">
                    <img id="loading-image" class="mb-4" src="{{ Asset('img/loader.gif') }}" alt="Loading...">
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Lotes Criados</h3>
                    </div>
                    <div class="box-body">
                        @includeIf('admin.master.messages')
                        <table id="table-lote" class="table table-bordered display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="info">
                                    <th style="width: 10px">Cód.</th>
                                    <th>Descrição</th>
                                    <th>Qtda Items</th>
                                    <th>Peso Liquido</th>
                                    <th>Status</th>
                                    <th>Usúario</th>
                                    <th>Criado em</th>
                                    <th>Ações</th>
                                </tr>
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
    <script type="text/javascript">
        $(document).ready(function() {
            // init datatable.    
            var dataTable = $('#table-lote').DataTable({
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                processing: true,
                responsive: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 5,
                // scrollX: true,
                //"order": [[0, "asc"]],
                "pageLength": 10,
                ajax: "{{ route('estoque.get-lotes') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'descricao',
                        name: 'descricao'
                    },
                    {
                        data: 'qtd_itens',
                        name: 'qtd_itens'
                    },
                    {
                        data: 'ps_liquido_total',
                        name: 'ps_liquido_total'
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'cd_usuario',
                        name: 'cd_usuario',
                        visible: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'Actions',
                        name: 'Actions',
                        orderable: false,
                        serachable: false,
                        sClass: 'text-center'
                    },
                ],

            });

            $('#submit-lote').click(function() {
                $.ajax({
                    url: "{{ route('estoque.cria-lote') }}",
                    method: 'POST',
                    data: {
                        ds_lote: $("#ds_lote").val(),
                        _token: $('#token').val(),
                    },
                    beforeSend: function() {
                        if ($("#ds_lote").val() == "") {
                            $('#ds_lote').attr('title', 'Descrição é obrigatório!')
                                .tooltip('show');
                            return false;
                        }
                        $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        $("#loading").addClass('hidden');
                        if (result.errors) {
                            alert(result.errors);
                        } else {
                            alert(result.success);
                        }
                        location.reload();
                    }
                });
            });
        });
    </script>
@endsection
