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
                            <label for="tp_lote" class="control-label">Tipo Lote</label>
                            <select class="form-control" id="tp_lote">
                                <option value="entrada">Entrada</option>
                                <option value="emprestimo">Empréstimo</option>
                                <option value="inventario">Inventario</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cd_subgrupo" class="control-label">Produto</label>
                            <select class="form-control" id="cd_subgrupo">
                                <option value="0">Selecione</option>
                                @foreach ($subgrupo as $s)
                                    <option value="{{ $s->id }}">{{ $s->ds_marca }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cd_marca" class="control-label">Marca</label>
                            <select class="form-control" id="cd_marca">
                                <option value="0">Selecione</option>
                                @foreach ($marca as $m)
                                    <option value="{{ $m->id }}">{{ $m->ds_marca }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ds_lote" class="control-label">Descrição</label>
                            <input type="text" class="form-control" id="ds_lote"
                                placeholder="Descrição para o Lote: Banda/Consertos...">
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
                        <table id="table-lote" class="table nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="info">
                                    <th style="width: 10px">Cód.</th>  
                                    <th>Descrição</th>                                  
                                    <th>Tipo Produto</th>
                                    <th>Marca</th>
                                    <th>Qtda Items</th>
                                    <th>Peso Liquido</th>
                                    <th>Status</th>
                                    <th>Tipo Lote</th>
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
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            let token = $("meta[name='csrf-token']").attr("content");
            $('#table-lote').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
                },
                pagingType: "simple",
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                processing: true,
                responsive: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 5,
                // scrollX: true,
                order: [
                    [0, "desc"]
                ],
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
                        data: 'ds_subgrupo',
                        name: 'ds_subgrupo'
                    },
                    {
                        data: 'ds_marca',
                        name: 'ds_marca'
                    },
                    {
                        data: 'qtd_itens',
                        name: 'qtd_itens'
                    },
                    {
                        data: 'ps_liquido_total',
                        name: 'ps_liquido_total',
                        render: function(data, type, full, meta) {
                            // Formata o valor numérico para usar ponto como separador decimal e vírgula como separador de milhar
                            return parseFloat(data).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'tp_lote',
                        name: 'tp_lote',
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
                        tp_lote: $('#tp_lote').val(),
                        cd_subgrupo: $('#cd_subgrupo').val(),
                        cd_marca: $('#cd_marca').val(),
                        ds_lote: $("#ds_lote").val(),
                        _token: $('#token').val(),
                    },
                    beforeSend: function() {
                        if ($("#tp_lote").val() == "") {
                            $('#tp_lote').attr('title', 'Tipo lote é obrigatório!')
                                .tooltip('show');
                            return false;
                        }
                        if ($("#cd_subgrupo").val() == 0) {
                            $('#cd_subgrupo').attr('title', 'Selecione o tipo do produto!')
                                .tooltip('show');
                            return false;
                        }
                        if ($("#cd_marca").val() == 0) {
                            $('#cd_marca').attr('title', 'Selecione uma marca!')
                                .tooltip('show');
                            return false;
                        }
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
                            // alert(result.errors);
                            msgToastr(result.errors, 'warning');
                        } else {
                            // alert(result.success);
                            msgToastr(result.success, 'success');
                        }
                        $('#table-lote').DataTable().ajax.reload();
                    }
                });
            });
            $('#table-lote').on('click', '.delete', function() {
                let id_lote = $(this).data();
                $.ajax({
                    method: 'DELETE',
                    url: '{{ route('estoque.delete-lote') }}',
                    data: {
                        idlote: id_lote['idlote'],
                        _token: token,
                    },
                    beforeSend: function() {
                        $('#loading').removeClass('hidden');
                    },
                    success: function(result) {
                        $('#loading').addClass('hidden');
                        if (result.error) {
                            // alert(result.error);
                            msg(result.error, 'alert-warning', 'fa fa-warning');
                            return false;
                        } else {
                            msg(result.success, 'alert-success', 'fa fa-check');
                            $('#table-lote').DataTable().ajax.reload();
                        }
                    }
                });
            });

        });
    </script>
@endsection
