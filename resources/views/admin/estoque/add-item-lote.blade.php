@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">{{ $title_page }}</h3>
                    </div>
                    <div class="box-body">
                        @includeIf('admin.master.messages')
                        <input id="token" name="_token" type="hidden" value="{{ csrf_token() }}">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="lote">Cód. Lote</label>
                                <input type="email" class="form-control" id="id_lote" value="{{ $lote->id }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="ds_lote">Descrição</label>
                                <input type="text" class="form-control" id="ds_lote" value="{{ $lote->descricao }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="responsavel">Responsável</label>
                                <input type="text" class="form-control" id="responsavel" value="{{ $lote->cd_usuario }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="created_at">Criado em:</label>
                                <input type="text" class="form-control" id="created_at" value="{{ $lote->created_at }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cd_barras">Cód. Produto</label>
                                <input type="text" class="form-control" id="cd_barras" placeholder="Cód. Barras">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ds_produto">Descrição Produto</label>
                                <input type="text" class="form-control" id="ds_produto" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="peso">Peso</label>
                                <input type="text" class="form-control" id="peso">
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="submit-add-item" class="btn btn-primary pull-right">Adicionar item</button>
                    </div>
                </div>
                {{-- Icon loading --}}
                <div class="hidden" id="loading">
                    <img id="loading-image" class="mb-4" src="{{ Asset('img/loader.gif') }}" alt="Loading...">
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
            var dataTable = $('.display').DataTable({
                processing: true,
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
                        name: 'cd_usuario'
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

        });
        $(document).ready(function() {
            $("#peso").inputmask("99.99");
            $("#cd_barras").keydown(function(event) {
                var keycode = (event.keyCode ? event.keyCode : event.which);
                var cd_barras = $("#cd_barras").val();
                var url = "{{ route('get-item-lote', ':cd_barras') }}";
                url = url.replace(':cd_barras', cd_barras);
                if (keycode == '9' || keycode == '13') {
                    $.ajax({
                        url: url,
                        method: "GET",
                        success: function(result) {
                            if (result.error) {
                                alert(result.error)
                            } else {                                                           
                                $("#ds_produto").val(result.ds_item);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
